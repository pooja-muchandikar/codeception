<?php

namespace Page;

class Constants {

    // Username and Password
    public static $userName = 'pooja';
    public static $password = '1100';

    // Login Selectors
	public static $wpUserNameField = 'input#user_login';
	public static $wpPasswordField = 'input#user_pass';
	public static $wpDashboard = '#adminmenu';
	public static $wpSubmitButton = 'input#wp-submit';
	public static $loginLink = 'li#wp-admin-bar-bp-login';
    public static $dashBoardMenu = 'li#menu-dashboard';

    //Logout Selectors
    public static $adminBarMenuSelector = '#wp-admin-bar-my-account';
    public static $logoutLink = '#wp-admin-bar-user-actions #wp-admin-bar-logout a';

    // Constants Setting URL's 
    public static $rtMediaSettingsUrl = '/wp-admin/admin.php?page=rtmedia-settings';
    public static $displaySettingsUrl = '/wp-admin/admin.php?page=rtmedia-settings#rtmedia-display';
    public static $buddypressSettingsUrl = '/wp-admin/admin.php?page=rtmedia-settings#rtmedia-bp';
    public static $typesSettingsUrl = '/wp-admin/admin.php?page=rtmedia-settings#rtmedia-types';
    public static $mediasizeSettingsUrl = '/wp-admin/admin.php?page=rtmedia-settings#rtmedia-sizes';
    public static $privacySettingsUrl = '/wp-admin/admin.php?page=rtmedia-settings#rtmedia-privacy';
    public static $customcssSettingsUrl = '/wp-admin/admin.php?page=rtmedia-settings#rtmedia-custom-css-settings';
    public static $otherSettingsUrl = '/wp-admin/admin.php?page=rtmedia-settings#rtmedia-general';

    

}