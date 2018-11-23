<?php
namespace Page;

class DashboardSettings {

    public function route( $param ) {
        return static::$URL . $param;
    }

    protected $tester;

    public function __construct( \AcceptanceTester $I ) {
		$this->tester = $I;
    }
    

}