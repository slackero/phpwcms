<?php
/**
 * phpwcms content management system
 *
 * @author Oliver Georgi <og@phpwcms.org>
 * @copyright Copyright (c) 2002-2022, Oliver Georgi
 * @license http://opensource.org/licenses/GPL-2.0 GNU GPL-2
 * @link http://www.phpwcms.org
 *
 **/


// ----------------------------------------------------------------
// obligate check for phpwcms constants
if (!defined('PHPWCMS_ROOT')) {
	die("You Cannot Access This Script Directly, Have a Nice Day.");
}
// ----------------------------------------------------------------


//
// Content Part Frontend Login
//
$content['felogin_template']							= clean_slweg($_POST['template']);
$content['felogin']['felogin_cookie_expire']			= intval($_POST['cookie_expire']);
$content['felogin']['felogin_date_format']				= clean_slweg($_POST['date_format']);
$content['felogin']['felogin_locale']					= clean_slweg($_POST['locale']);
$content['felogin']['felogin_validate_userdetail']		= empty($_POST['validate_userdetail']) ? 0 : 1;
$content['felogin']['felogin_validate_backenduser']		= empty($_POST['validate_backenduser']) ? 0 : 1;
$content['felogin']['felogin_accept_email_login']		= empty($_POST['accept_email_login']) ? 0 : 1;
$content['felogin']['felogin_profile_registration']		= empty($_POST['profile_registration']) ? 0 : 1;
$content['felogin']['felogin_profile_manage']			= empty($_POST['profile_manage']) ? 0 : 1;
$content['felogin']['felogin_profile_manage_redirect']	= clean_slweg($_POST['profile_manage_redirect']);
