<?php

namespace Page;

use Page\Constants as ConstantsPage;

class UploadMedia{

	protected $tester;

	public function _construct( \AcceptanceTester $I ) {
		$this->tester = $I;
	}

	//Upload Media Function
	public function uploadMedia( $mediaUploadLink ) {

		$I = $this->tester;

		// $I->seeElement( ConstantsPage :: $mediaPageScroll );
		// $I->scrollTo( ConstantsPage :: $mediaPageScroll );
		
		$I->seeElement( ConstantsPage :: $mediaUploadLink );
		$I->click( ConstansPage :: $mediaUploadLink);
		$I->waitForElement( ConstantsPage::$uploadContainer, 20 );
		$I->seeElementInDOM( ConstantsPage::$selectFileButton );
		$I->attachFile( ConstantsPage::$uploadFile, $mediaFile );
		$I->waitForElement( ConstantsPage::$fileList, 20 );
	}

	//Upload Media using start Upload
	public function uploadMediaUsingStartUploadButton( $clickonCheckbox = 'no' ) {

		$I = $this->tester;

		$I->waitForElementVisible( ConstantsPage::$uploadMediaButton, 20 );
		$I->click( ConstantsPage::$uploadMediaButton );

		if ( 'no' != $clickonCheckbox ) {
			$I->waitForElementVisible( ConstantsPage::$alertMessageClass , 10);
		} else {
			$I->waitForElementNotVisible( ConstantsPage::$fileList, 20 );
		}
	}
}