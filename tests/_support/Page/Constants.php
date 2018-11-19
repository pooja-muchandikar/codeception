<?php

namespace Page;

class Constants {

    // Username and Password
    public static $userName = 'pooja';
    public static $password = '1100';

    // URL's 
    public static $rtMediaSettingsUrl = '/wp-admin/admin.php?page=rtmedia-settings';
    public static $displaySettingsUrl = '/wp-admin/admin.php?page=rtmedia-settings#rtmedia-display';
    
    // Login Selectors
	public static $wpUserNameField = 'input#user_login';
	public static $wpPasswordField = 'input#user_pass';
	public static $wpDashboard = '#adminmenu';
	public static $wpSubmitButton = 'input#wp-submit';
	public static $loginLink = 'li#wp-admin-bar-bp-login';
	public static $dashBoardMenu = 'li#menu-dashboard';
}
