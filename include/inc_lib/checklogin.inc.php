<?php
/**
 * phpwcms content management system
 *
 * @author Oliver Georgi <oliver@phpwcms.de>
 * @copyright Copyright (c) 2002-2014, Oliver Georgi
 * @license http://opensource.org/licenses/GPL-2.0 GNU GPL-2
 * @link http://www.phpwcms.de
 *
 **/


//Aktualisieren der Userliste bzgl. der eingeloggten Zeit, Notfalls deaktivieren
// ----------------------------------------------------------------
// obligate check for phpwcms constants
if (!defined('PHPWCMS_ROOT')) {
   die("You Cannot Access This Script Directly, Have a Nice Day.");
}
// ----------------------------------------------------------------


$sql  = "UPDATE ".DB_PREPEND."phpwcms_userlog SET ";
$sql .= "logged_in = 0, logged_change = '".time()."' ";
$sql .= "WHERE logged_in = 1 AND ( ".time()." - logged_change ) > ".intval($phpwcms["max_time"]);
mysql_query($sql, $db);

if(!empty($_SESSION["wcs_user"])) {
	$sql  = "SELECT COUNT(*) FROM ".DB_PREPEND."phpwcms_userlog ";
	$sql .= "WHERE logged_user='".aporeplace($_SESSION["wcs_user"])."' AND ";
	$sql .= "logged_in=1";
	if(!empty($phpwcms['Login_IPcheck'])) {
		$sql .= " AND logged_ip='".aporeplace(getRemoteIP())."'";
	}
	if($check = mysql_query($sql, $db)) {
		if($row = mysql_fetch_row($check)) {
			if($row[0] == 0) {
				unset($_SESSION["wcs_user"]);
			} else {
				$sql  = "UPDATE ".DB_PREPEND."phpwcms_userlog SET ";
				$sql .= "logged_change=".time()." WHERE ";
				$sql .= "logged_user='".aporeplace($_SESSION["wcs_user"])."' AND logged_in=1";
				mysql_query($sql, $db);
			}
			mysql_free_result($check);
		}
	}
}
if(empty($_SESSION["wcs_user"])) {
	@session_destroy();
	$ref_url = '';
	if(!empty($_SERVER['QUERY_STRING'])) {
		$ref_url = '?ref='.rawurlencode(PHPWCMS_URL.'phpwcms.php?'.xss_clean($_SERVER['QUERY_STRING']));
	}
	headerRedirect(PHPWCMS_URL.get_login_file().$ref_url);
}
?>