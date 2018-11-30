<?php

namespace Page;

use Page\Constants as ConstantsPage;

class UploadMedia{

	protected $tester;

	public function __construct( \AcceptanceTester $I ) {
		$this->tester = $I;
	}

	//Upload Media Function
	public function uploadMedia( ) {

		$I = $this->tester;

		// $I->seeElement( ConstantsPage :: $mediaPageScroll );
		// $I->scrollTo( ConstantsPage :: $mediaPageScroll );
		
		// $myfile = fopen("Log.txt", "a") or die("Unable to open file!");
		// fwrite($myfile, 'pooja');
		// fclose($myfile);
		// codecept_debug($I); 

		$I->seeElement('.rtmedia-container');
		$I->scrollTo('.rtmedia-container');
		// $I->scrollTo(['css' => '.rtm-media-options.rtm-media-search-enable>*:nth-last-child(2)']);
		$I->wait(2);
		
		$I->click( ConstantsPage :: $mediaUploadLink);
		$I->waitForElement( ConstantsPage::$uploadContainer, 20 );
		$I->seeElementInDOM( ConstantsPage::$selectFileButton );
		
		$I->attachFile( ConstantsPage::$uploadFile, ConstantsPage::$audioName );
		$I->wait(2);
		$I->waitForElement( ConstantsPage::$fileList, 20 );
		$I->click( ConstantsPage:: $uploadFile);
		// $I->wait(5);
		// $I->seeElement('.start-media-upload');
		// $I->click( ConstantsPage:: $startUpload );
	}

}