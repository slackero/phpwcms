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

session_start();
$phpwcms = array();
require_once ('../../config/phpwcms/conf.inc.php');
require_once ('../inc_lib/default.inc.php');
require_once (PHPWCMS_ROOT.'/include/inc_lib/dbcon.inc.php');

require_once (PHPWCMS_ROOT.'/include/inc_lib/general.inc.php');
checkLogin();
require_once (PHPWCMS_ROOT.'/include/inc_lib/backend.functions.inc.php');

if(empty($_SESSION['REFERER_URL'])) {
	die('Goood bye.');
} else {
	$ref = $_SESSION['REFERER_URL'];
}

if($_SESSION["wcs_user_admin"] == 1) { //Wenn Benutzer Admin-Rechte hat

	if(intval($_POST["scat_new"]) == 1 && intval($_POST["scat_id"]) == 0) {
		if(trim($_POST["scat_name"])) {
			$sql =	"INSERT INTO ".DB_PREPEND."phpwcms_schedulecat (".
					"scat_name, scat_info, scat_aktiv, scat_uid) ".
					"VALUES ('".
					getpostvar($_POST["scat_name"])."','".
					getpostvar($_POST["scat_info"])."',".
					intval($_POST["scat_aktiv"]).",".
					$_SESSION["wcs_user_id"].");";
			if($result = mysql_query($sql, $db) or die("error")) {
				$ref .= "&cat=".mysql_insert_id($db);
			}			
		}
	}
	
	if(isEmpty($_POST["scat_new"]) && intval($_POST["scat_id"])) {
		if(trim($_POST["scat_name"])) {
			$sql =	"UPDATE ".DB_PREPEND."phpwcms_schedulecat SET ".
					"scat_name='".getpostvar($_POST["scat_name"])."', ".
					"scat_info='".getpostvar($_POST["scat_info"])."', ".
					"scat_aktiv=".intval($_POST["scat_aktiv"]).", ".
					"scat_uid=".$_SESSION["wcs_user_id"].
					" WHERE scat_id=".intval($_POST["scat_id"]).";";
			mysql_query($sql, $db) or die("error");		
		}	
	}
	
} //Ende Abarbeiten Aktion

headerRedirect($ref);

?>