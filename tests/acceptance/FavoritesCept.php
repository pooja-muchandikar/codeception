<?php

/**
 * Scenario : Add Media to Favlist.
 */

use Page\Login as LoginPage;
use Page\Constants as ConstantsPage;
use \Codeception\Util\Locator;

$I = new AcceptanceTester( $scenario );
$I->wantTo( 'Add Media to Favlist' );

$loginPage = new LoginPage( $I );
$loginPage->loginAsAdmin( ConstantsPage::$userName, ConstantsPage::$password );

$I->amOnPage( '/wp-admin/plugins.php?plugin_status=all' );

//Create Favlist
$I->moveMouseOver('li#wp-admin-bar-my-account');
$I->wait(2);
$I->click('li#wp-admin-bar-my-account-media a');
$I->scrollTo(['css' => '.rtmedia-container ']);
$I->scrollTo(['css' => '#buddypress div.item-list-tabs']);
$I->seeElement('.item-list-tabs');
$I->click( 'li#rtmedia-nav-item-favlist-profile-1-li a');
$I->scrollTo('#rtm-gallery-title-container');
$I->seeElement( '#rtmedia-media-options' );
$I->seeElement('.rtm-media-options .dashicons');
$I->click('.rtm-media-options .dashicons');
$I->wait(2);
$I->fillfield('input#rtmedia_favlist_name', 'My FavList');
$I->seeElement('.rtmedia-create-popup-option');
$I->selectOption('select[name=privacy]', 'Friends');
$I->click('button#rtmedia_create_new_favlist');
$I->wait(2);
$I->click('#new-favlist-modal > button.mfp-close');

//Add Media to Favlist
$I->scrollTo(['css' => '#buddypress div.item-list-tabs']);
$I->seeElement('.item-list-tabs');
$I->click('li#rtmedia-nav-item-all-li a');
$I->scrollTo(['css' => '.rtmedia-container ']);
$I->seeElement('.rtmedia-item-thumbnail');
$I->click('.rtmedia-item-thumbnail');
$I->wait(2);
$I->seeElement('.rtm-media-options-list');
$I->click('button.clicker.rtmedia-media-options');
$I->click('.rtmedia-add-to-favlist');
$I->wait(2);
$I->seeElement('#rtm-favlist-list');
$I->selectOption('select[name=rtm_favlist_id]', 'Camping');
$I->seeElement('.add-to-rtmp-favlist');
$I->click('input.add-to-rtmp-favlist');
$I->click('.rtmedia-container.rtmedia-single-container span.rtm-mfp-close');
$I->wait(2);

//View Media added to Favlist
$I->scrollTo(['css' => '#buddypress div.item-list-tabs']);
$I->seeElement('.item-list-tabs');
$I->click( 'li#rtmedia-nav-item-favlist-profile-1-li a');
$I->scrollTo('#rtm-gallery-title-container');
$I->seeElement('.rtmedia-item-thumbnail');
$I->seeElement('.rtmedia-list.rtmedia-album-list');
$I->click('.rtmedia-container.favlist .rtmedia-list.rtmedia-album-list > li:nth-child(2)');
$I->scrollTo('#rtm-gallery-title-container');
$I->wait(2);
?>
