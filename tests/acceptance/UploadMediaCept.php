<?php

/**
 * Scenario : Upload Media.
 */

use Page\Login as LoginPage;
use Page\Constants as ConstantsPage;
use Page\UploadMedia as UploadMediaPage;

$I = new AcceptanceTester( $scenario );
$I->wantTo( 'Upload Media' );

$loginPage = new LoginPage( $I );
$loginPage->loginAsAdmin( ConstantsPage::$userName, ConstantsPage::$password );

//Upload Media
$I->amOnPage( '/wp-admin/admin.php?page=rtmedia-settings#rtmedia-display' );
$I->moveMouseOver('li#wp-admin-bar-my-account');
$I->wait(2);
$I->click('li#wp-admin-bar-my-account-media a');
// $I->scrollTo(['css' => '.rtmedia-container ']);
// $I->seeElement('.rtmedia-upload-media-link');
// $I->click('.rtmedia-upload-media-link');
// $I->seeElement('.rtmedia-upload-input.rtmedia-file');
// $I->click('input#rtMedia-upload-button');
// #$I->select(ConstantsPage :: $audioName );
// $I->attachFile('input[@type="dummy-data"]', 'dummy-data/music/test.mp3');
// $I->wait(2);

$uploadmedia = new UploadMediaPage( $I );
// $I->scrollTo(['css' => '.rtmedia-container']);
// $I->wait(2);
$uploadmedia->uploadMedia();

$I->reloadPage();
$I->wait(10);
?>

