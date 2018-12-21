<?php

namespace Page;

class Constants {

    // Username and Password
    public static $userName = 'pooja';
    public static $password = '1100';

    // Media files 
    public static $audioName = 'dummy-data/music/test.mp3';
    
    // BuddyPress Settings


    // URL's 
    public static $rtMediaSettingsUrl = '/wp-admin/admin.php?page=rtmedia-settings';
    public static $displaySettingsUrl = '/wp-admin/admin.php?page=rtmedia-settings#rtmedia-display';
    public static $buddypressSettingsUrl = '/wp-admin/admin.php?page=rtmedia-settings#rtmedia-bp';
    public static $typesSettingsUrl = '/wp-admin/admin.php?page=rtmedia-settings#rtmedia-types';
    public static $mediasizeSettingsUrl = '/wp-admin/admin.php?page=rtmedia-settings#rtmedia-sizes';
    public static $privacySettingsUrl = '/wp-admin/admin.php?page=rtmedia-settings#rtmedia-privacy';
    public static $customcssSettingsUrl = '/wp-admin/admin.php?page=rtmedia-settings#rtmedia-custom-css-settings';
    public static $otherSettingsUrl = '/wp-admin/admin.php?page=rtmedia-settings#rtmedia-general';

    

    // Login Selectors
	public static $wpUserNameField = 'input#user_login';
	public static $wpPasswordField = 'input#user_pass';
	public static $wpDashboard = '#adminmenu';
	public static $wpSubmitButton = 'input#wp-submit';
	public static $loginLink = 'li#wp-admin-bar-bp-login';
    public static $dashBoardMenu = 'li#menu-dashboard';
    
    // Frontend Selectors for Upload
    public static $uploadMediaButton = '#rtmedia-upload-container .start-media-upload';
    public static $mediaPageScroll = '#user-activity'; 
    public static $mediaUploadLink = '.rtm-media-options.rtm-media-search-enable>*:nth-last-child(2)';
    public static $uploadContainer = '.rtmedia-container';
    public static $selectFileButton = 'input#rtMedia-upload-button';
    public static $uploadFile = 'div.moxie-shim.moxie-shim-html5 input[type=file]';
    public static $startUpload = '.start-media-upload';
    public static $mediaPrivacy = 'select#rtSelectPrivacy';
    public static $fileList = '#rtmedia_uploader_filelist';
    public static $appearance = '.wp-menu-image.dashicons-before.dashicons-admin-appearance';
    public static $widgetType = '.widget-rtmediagallerywidget-5-wdType';
    
    
    
}
