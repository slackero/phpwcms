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


//Updates the profile datas
$sql =	"UPDATE ".DB_PREPEND."phpwcms_userdetail SET ".
		"detail_title='".getpostvar($_POST["form_title"])."',".
		"detail_firstname='".getpostvar($_POST["form_firstname"])."',".
		"detail_lastname='".getpostvar($_POST["form_lastname"])."',".
		"detail_company='".getpostvar($_POST["form_company"])."',".
		"detail_street='".getpostvar($_POST["form_street"])."',".
		"detail_add='".getpostvar($_POST["form_add"])."',".
		"detail_city='".getpostvar($_POST["form_city"])."',".
		"detail_zip='".getpostvar($_POST["form_zip"])."',".
		"detail_region='".getpostvar($_POST["form_region"])."',".
		"detail_country='".getpostvar($_POST["form_country"])."',".
		"detail_fon='".getpostvar($_POST["form_fon"])."',".
		"detail_fax='".getpostvar($_POST["form_fax"])."',".
		"detail_mobile='".getpostvar($_POST["form_mobile"])."',".
		"detail_signature='".getpostvar(mb_substr($_POST["form_signature"],0,250))."',".
		"detail_prof='".getpostvar($_POST["form_prof"])."',".
		"detail_notes='".getpostvar(mb_substr($_POST["form_notes"],0,3000))."',".
		"detail_public=".(empty($_POST["form_public"]) ? 0 : 1).",".
		"detail_newsletter=".(empty($_POST["form_newsletter"]) ? 0 : 1)." WHERE ".
		"detail_pid=".$_SESSION["wcs_user_id"];

$result = _dbQuery($sql, 'UPDATE');

if(!isset($result['AFFECED_ROWS'])) {
	$detail_updated = $BL['be_profile_update_success'];
} else {
	$detail_updated = $BL['be_profile_update_error'];
}
