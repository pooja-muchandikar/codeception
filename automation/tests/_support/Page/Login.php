<?php

namespace Page;

use Page\Constants as ConstantsPage;

class Login {

	protected $tester;

	public function __construct( \AcceptanceTester $I ) {
		$this->tester = $I;
	}

	public function loginAsAdmin( $wpUserName, $wpPassword ) {

		$I = $this->tester;

		$I->amOnPage( '/wp-admin' );
		$I->wait(5);
		
		$I->seeElement( ConstantsPage::$wpUserNameField );
		$I->fillfield( ConstantsPage::$wpUserNameField, $wpUserName );

		$I->seeElement( ConstantsPage::$wpPasswordField );
		$I->fillfield( ConstantsPage::$wpPasswordField, $wpPassword );

		$I->click( ConstantsPage::$wpSubmitButton );
		$I->waitForElement( ConstantsPage::$wpDashboard, 10 );
		
		$I->reloadPage();
		$I->maximizeWindow();
	}

}