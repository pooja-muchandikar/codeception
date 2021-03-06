<?php

namespace WP_CLI\AutoloadSplitter;

use Composer\Autoload\ClassMapGenerator;
use Composer\Config;
use Composer\EventDispatcher\EventDispatcher;
use Composer\Installer\InstallationManager;
use Composer\IO\IOInterface;
use Composer\Autoload\AutoloadGenerator as ComposerAutoloadGenerator;
use Composer\Package\PackageInterface;
use Composer\Repository\InstalledRepositoryInterface;
use Composer\Util\Filesystem;

/**
 * Class AutoloadGenerator.
 *
 * Generates the customized split autoloader files required for WP-CLI.
 *
 * @package WP_CLI\AutoloadSplitter
 */
class AutoloadGenerator extends ComposerAutoloadGenerator
{

    /**
     * @var EventDispatcher
     */
    private $eventDispatcher;

    /**
     * @var IOInterface
     */
    private $io;

    /**
     * @var bool
     */
    private $devMode = false;

    /**
     * @var bool
     */
    private $classMapAuthoritative = false;

    /**
     * @var bool
     */
    private $apcu = false;

    /**
     * @var bool
     */
    private $runScripts = false;

    /**
     * @var string
     */
    private $splitterLogic;

    /**
     * @var string
     */
    private $filePrefixTrue;

    /**
     * @var string
     */
    private $filePrefixFalse;

    public function __construct(
        EventDispatcher $eventDispatcher,
        IOInterface $io = null,
        $splitterLogic,
        $filePrefixTrue,
        $filePrefixFalse
    ) {
        $this->eventDispatcher = $eventDispatcher;
        $this->io              = $io;
        $this->splitterLogic   = $splitterLogic;
        $this->filePrefixTrue  = $filePrefixTrue;
        $this->filePrefixFalse = $filePrefixFalse;
        parent::__construct($eventDispatcher);
    }

    public function setDevMode($devMode = true)
    {
        $this->devMode = (boolean)$devMode;
    }

    /**
     * Whether or not generated autoloader considers the class map
     * authoritative.
     *
     * @param bool $classMapAuthoritative
     */
    public function setClassMapAuthoritative($classMapAuthoritative)
    {
        $this->classMapAuthoritative = (boolean)$classMapAuthoritative;
    }

    /**
     * Whether or not generated autoloader considers APCu caching.
     *
     * @param bool $apcu
     */
    public function setApcu($apcu)
    {
        $this->apcu = (boolean)$apcu;
    }

    /**
     * Set whether to run scripts or not
     *
     * @param bool $runScripts
     */
    public function setRunScripts($runScripts = true)
    {
        $this->runScripts = (boolean)$runScripts;
    }

    public function dump(
        Config $config,
        InstalledRepositoryInterface $localRepo,
        PackageInterface $mainPackage,
        InstallationManager $installationManager,
        $targetDir,
        $scanPsr0Packages = false,
        $suffix = ''
    ) {
        if ($this->classMapAuthoritative) {
            // Force scanPsr0Packages when classmap is authoritative
            $scanPsr0Packages = true;
        }

        $filesystem = new Filesystem();
        $filesystem->ensureDirectoryExists($config->get('vendor-dir'));
        // Do not remove double realpath() calls.
        // Fixes failing Windows realpath() implementation.
        // See https://bugs.php.net/bug.php?id=72738
        $basePath             = $filesystem->normalizePath(realpath(realpath(getcwd())));
        $vendorPath           = $filesystem->normalizePath(realpath(realpath($config->get('vendor-dir'))));
        $useGlobalIncludePath = (bool)$config->get('use-include-path');
        $prependAutoloader    = $config->get('prepend-autoloader') === false ? 'false' : 'true';
        $targetDir            = $vendorPath . '/' . $targetDir;
        $filesystem->ensureDirectoryExists($targetDir);

        $vendorPathCode = $filesystem->findShortestPathCode(
            realpath($targetDir),
            $vendorPath,
            true
        );

        $vendorPathCode52 = str_replace('__DIR__', 'dirname(__FILE__)', $vendorPathCode);

        $vendorPathToTargetDirCode = $filesystem->findShortestPathCode(
            $vendorPath,
            realpath($targetDir),
            true
        );

        $appBaseDirCode = $filesystem->findShortestPathCode(
            $vendorPath,
            $basePath,
            true
        );

        $appBaseDirCode = str_replace('__DIR__', '$vendorDir', $appBaseDirCode);

        $filePrefixes = array(
            $this->filePrefixTrue,
            $this->filePrefixFalse,
        );

        $classmapFile = array();
        foreach ($filePrefixes as $filePrefix) {

            $classmapFile[$filePrefix] = <<<EOF
<?php

// {$filePrefix}_classmap.php @generated by Composer

\$vendorDir = $vendorPathCode52;
\$baseDir = $appBaseDirCode;

return array(

EOF;
        }

        // Collect information from all packages.
        $packageMap = $this->buildPackageMap(
            $installationManager,
            $mainPackage,
            $localRepo->getCanonicalPackages()
        );

        $autoloads = $this->parseAutoloads($packageMap, $mainPackage);

        $blacklist = null;
        if (! empty($autoloads['exclude-from-classmap'])) {
            $blacklist = '{(' . implode('|', $autoloads['exclude-from-classmap']) . ')}';
        }

        // flatten array
        $classMap = array();
        if ($scanPsr0Packages) {
            $namespacesToScan = array();

            // Scan the PSR-0/4 directories for class files, and add them to the class map
            foreach (array('psr-0', 'psr-4') as $psrType) {
                foreach ($autoloads[$psrType] as $namespace => $paths) {
                    $namespacesToScan[$namespace][] = array(
                        'paths' => $paths,
                        'type'  => $psrType,
                    );
                }
            }

            krsort($namespacesToScan);

            foreach ($namespacesToScan as $namespace => $groups) {
                foreach ($groups as $group) {
                    foreach ($group['paths'] as $dir) {
                        $dir = $filesystem->normalizePath(
                            $filesystem->isAbsolutePath($dir)
                                ? $dir
                                : $basePath . '/' . $dir
                        );
                        if (! is_dir($dir)) {
                            continue;
                        }

                        $namespaceFilter = $namespace === ''
                            ? null
                            : $namespace;
                        $classMap        = $this->addClassMapCode(
                            $filesystem,
                            $basePath,
                            $vendorPath,
                            $dir,
                            $blacklist,
                            $namespaceFilter,
                            $classMap
                        );
                    }
                }
            }
        }

        foreach ($autoloads['classmap'] as $dir) {
            $classMap = $this->addClassMapCode(
                $filesystem,
                $basePath,
                $vendorPath,
                $dir,
                $blacklist,
                null,
                $classMap
            );
        }

        ksort($classMap);
        foreach ($classMap as $class => $code) {
            $logic      = new $this->splitterLogic();
            $filePrefix = $logic($class, $code)
                ? $this->filePrefixTrue
                : $this->filePrefixFalse;
            $classmapFile[$filePrefix] .= '    ' . var_export($class, true) . ' => ' . $code;
        }

        foreach ($filePrefixes as $filePrefix) {
            $classmapFile[$filePrefix] .= ");\n";
        }

        if (! $suffix) {
            if (! $config->get('autoloader-suffix')
                && is_readable($vendorPath . '/autoload.php')
            ) {
                $content = file_get_contents($vendorPath . '/autoload.php');
                if (preg_match(
                    '{ComposerAutoloaderInit([^:\s]+)::}',
                    $content,
                    $match
                )) {
                    $suffix = $match[1];
                }
            }

            if (! $suffix) {
                $suffix = $config->get('autoloader-suffix')
                    ?: md5(uniqid('', true));
            }
        }

        foreach ($filePrefixes as $filePrefix) {
            $fileSuffix = md5($filePrefix . $suffix);
            file_put_contents(
                "{$targetDir}/{$filePrefix}_classmap.php",
                $classmapFile[$filePrefix]
            );
            file_put_contents(
                "{$vendorPath}/{$filePrefix}.php",
                $this->getCustomAutoloadFile(
                    $filePrefix,
                    $vendorPathToTargetDirCode,
                    $fileSuffix
                )
            );
            file_put_contents(
                "{$targetDir}/{$filePrefix}_real.php",
                $this->getCustomAutoloadRealFile(
                    $filePrefix,
                    $fileSuffix,
                    $useGlobalIncludePath,
                    $prependAutoloader
                )
            );
        }
    }

    private function addClassMapCode(
        $filesystem,
        $basePath,
        $vendorPath,
        $dir,
        $blacklist = null,
        $namespaceFilter = null,
        array $classMap = array()
    ) {
        foreach (
            $this->generateClassMap($dir, $blacklist, $namespaceFilter) as $class => $path) {
            $pathCode = $this->getPathCode($filesystem, $basePath, $vendorPath, $path) . ", \n";
            if (! isset($classMap[$class])) {
                $classMap[$class] = $pathCode;
            } elseif ($this->io
                && $classMap[$class] !== $pathCode
                && ! preg_match('{/(test|fixture|example|stub)s?/}i', strtr($classMap[$class] . ' ' . $path, '\\', '/'))
            ) {
                $this->io->writeError(
                    '<warning>Warning: Ambiguous class resolution, "' . $class . '"' . ' was found in both "'
                    . str_replace(array('$vendorDir . \'', "',\n",),
                        array($vendorPath, ''),
                        $classMap[$class]) . '" and "' . $path . '", the first will be used.</warning>'
                );
            }
        }

        return $classMap;
    }

    private function generateClassMap(
        $dir,
        $blacklist = null,
        $namespaceFilter = null,
        $showAmbiguousWarning = true
    ) {
        return ClassMapGenerator::createMap(
            $dir,
            $blacklist,
            $showAmbiguousWarning
                ? $this->io
                : null,
            $namespaceFilter
        );
    }

    protected function getCustomAutoloadFile($filePrefix, $vendorPathToTargetDirCode, $suffix)
    {
        $lastChar = $vendorPathToTargetDirCode[strlen($vendorPathToTargetDirCode) - 1];
        if ("'" === $lastChar
            || '"' === $lastChar
        ) {
            $vendorPathToTargetDirCode = substr($vendorPathToTargetDirCode, 0, -1)
                . "/{$filePrefix}_real.php{$lastChar}";
        } else {
            $vendorPathToTargetDirCode .= " . '/{$filePrefix}_real.php'";
        }

        return <<<AUTOLOAD
<?php

// {$filePrefix}.php @generated by Composer

require_once $vendorPathToTargetDirCode;

return ComposerAutoloaderInit$suffix::getLoader();

AUTOLOAD;
    }

    protected function getCustomAutoloadRealFile(
        $filePrefix,
        $suffix,
        $useGlobalIncludePath,
        $prependAutoloader
    ) {
        $file = <<<HEADER
<?php

// {$filePrefix}_real.php @generated by Composer

class ComposerAutoloaderInit$suffix
{
    private static \$loader;

    public static function loadClassLoader(\$class)
    {
        if ('Composer\\Autoload\\ClassLoader' === \$class) {
            require __DIR__ . '/ClassLoader.php';
        }
    }

    public static function getLoader()
    {
        if (null !== self::\$loader) {
            return self::\$loader;
        }

        spl_autoload_register(array('ComposerAutoloaderInit$suffix', 'loadClassLoader'), true, $prependAutoloader);
        self::\$loader = \$loader = new \\Composer\\Autoload\\ClassLoader();
        spl_autoload_unregister(array('ComposerAutoloaderInit$suffix', 'loadClassLoader'));


HEADER;

        $file .= <<<CLASSMAP
        \$classMap = require __DIR__ . '/{$filePrefix}_classmap.php';
        if (\$classMap) {
            \$loader->addClassMap(\$classMap);
        }

CLASSMAP;

        if ($this->apcu) {
            $apcuPrefix = substr(base64_encode(md5(uniqid('', true), true)), 0, -3);
            $file .= <<<APCU
        \$loader->setApcuPrefix('$apcuPrefix');

APCU;
        }

        if ($useGlobalIncludePath) {
            $file .= <<<'INCLUDEPATH'
        $loader->setUseIncludePath(true);

INCLUDEPATH;
        }

        $file .= <<<REGISTER_LOADER
        \$loader->register($prependAutoloader);


REGISTER_LOADER;

        $file .= <<<METHOD_FOOTER
        return \$loader;
    }

METHOD_FOOTER;

        return $file . <<<FOOTER
}

FOOTER;
    }
}
