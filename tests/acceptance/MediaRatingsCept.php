<?php

/**
 * Scenario : Media Ratings.
 */

use Page\Login as LoginPage;
use Page\Constants as ConstantsPage;

$I = new AcceptanceTester( $scenario );
$I->wantTo( 'Navigate to Dashboard.' );

$loginPage = new LoginPage( $I );
$loginPage->loginAsAdmin( ConstantsPage::$userName, ConstantsPage::$password );

// $settings = new DashboardSettingsPage( $I );
// $settings->gotoSettings( ConstantsPage::displaySettingsUrl );

$I->amOnPage( '/wp-admin/admin.php?page=rtmedia-settings#rtmedia-display' );
$I->moveMouseOver('li#wp-admin-bar-my-account');
$I->wait(2);
$I->click('li#wp-admin-bar-my-account-media a');
$I->scrollTo(['css' => '.rtmedia-container ']);
$I->click('.rtmedia-item-thumbnail');
$I->seeElement('.webwidget_rating_simple');
$I->click('.dashicons.dashicons-star-empty');
$I->wait(2);
$I->click('.rtmedia-container.rtmedia-single-container span.rtm-mfp-close');
$I->wait(2);
$I->seeElement('.rtmedia-container');
$I->seeElement('.rtmedia-list.rtmedia-list-media');
$I->click('.rtmedia-container .rtmedia-list > li:nth-child(2)');
$I->wait(2);
$I->moveMouseOver(['css' => '.rtmedia-media-rating .rtmedia-pro-average-rating']);
$I->wait(2);
$I->seeElement('.rtmedia-pro-average-rating .rtm-undo-rating');
$I->click('.rtmedia-pro-average-rating .rtm-undo-rating');
$I->wait(2);
$I->click('.rtmedia-container.rtmedia-single-container span.rtm-mfp-close');
$I->scrollTo(['css' => '#buddypress div.item-list-tabs']);
$I->seeElement('.item-list-tabs');
$I->click('li#rtmedia-nav-item-albums-li a');
$I->scrollTo(['css' => '.rtmedia-container']);
$I->seeElement('.rtmedia-media-rating');
$I->seeElement('.webwidget_rating_simple');
$I->click('.webwidget_rating_simple');
$I->wait(2);
?>
