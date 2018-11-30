<?php

/**
 * Scenario : Add Sidebar Widgets.
 */

use Page\Login as LoginPage;
use Page\Constants as ConstantsPage;

$I = new AcceptanceTester( $scenario );
$I->wantTo( 'Add rtMedia Sidebar Widgets' );

$loginPage = new LoginPage( $I );
$loginPage->loginAsAdmin( ConstantsPage::$userName, ConstantsPage::$password );

// $I->amOnPage( ConstantsPage::$displaySettingsUrl );

$I->seeElement( ConstantsPage:: $appearance);
$I->click( ConstantsPage:: $appearance);
$I->amOnPage( '/wp-admin/widgets.php' );
$I->scrollTo('#widget-28_rtmediagallerywidget-__i__');
$I->wait(2);
$I->seeElement('#widget-28_rtmediagallerywidget-__i__');
$I->click('#widget-28_rtmediagallerywidget-__i__');
$I->wait(2);
$I->seeElement('.widgets-chooser-actions');
$I->click('.button.button-primary.widgets-chooser-add');
$I->wait(2);
$I->seeElement('.rtmedia-gallery-widget');
$I->selectOption('#widget-rtmediagallerywidget-wdType', 'Recent Media');


// $I->seeElement('.widget-rtmediagallerywidget[5][wdTime]');
// $I->selectOption('select[name=widget-rtmediagallerywidget[5][wdTime]]', 'All Time');
// $I->seeElement('.widget-rtmediagallerywidget-5-title.widefat');
// $I->fillfield('input#widget-rtmediagallerywidget-5-title.widefat', 'Gallery');
// $I->seeElement('.widget-rtmediagallerywidget-5-number');
// $I->fillfield('input#widget-rtmediagallerywidget-5-number','6');
// $I->seeElement('.widget-rtmediagallerywidget-5-thumbnail_width');
// $I->fillfield('input#widget-rtmediagallerywidget-5-thumbnail_width','90');
// $I->seeElement('.widget-rtmediagallerywidget-5-savewidget');
// $I->click('#widget-rtmediagallerywidget-5-savewidget');


?>
