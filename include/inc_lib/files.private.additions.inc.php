<?php
/**
 * phpwcms content management system
 *
 * @author Oliver Georgi <oliver@phpwcms.de>
 * @copyright Copyright (c) 2002-2012, Oliver Georgi
 * @license http://opensource.org/licenses/GPL-2.0 GNU GPL-2
 * @link http://www.phpwcms.de
 *
 **/


// ----------------------------------------------------------------
// obligate check for phpwcms constants
if (!defined('PHPWCMS_ROOT')) {
   die("You Cannot Access This Script Directly, Have a Nice Day.");
}
// ----------------------------------------------------------------

$klapp = array();

if(isset($_GET["all"])) { //Übernehmen der Aufklappwerte aus Session-Variable
	
	$_SESSION["klapp"] = array();
	
	if($_GET["all"] == "open") { //alle aufklappen
		$sql = "SELECT f_id FROM ".DB_PREPEND."phpwcms_file WHERE f_kid=0 AND f_trash=0";
		if(empty($_SESSION["wcs_user_admin"])) {
			$sql .= " AND f_uid=".$_SESSION["wcs_user_id"];
		}
		if($result = mysql_query($sql, $db) or die ("error while open all directories")) {
			while($row = mysql_fetch_row($result)) {
				$_SESSION["klapp"][$row[0]] = 1;
			}
		}
	}
	if($_GET["all"] == "close") {
		
	}
	mysql_query("UPDATE ".DB_PREPEND."phpwcms_user SET usr_var_privatefile='".serialize($_SESSION["klapp"])."' WHERE usr_id=".$_SESSION["wcs_user_id"], $db);
}

if(isset($_SESSION["klapp"])) {
	$klapp = $_SESSION["klapp"];
} else {
	$_SESSION["klapp"] = array();
}

if(isset($_GET["klapp"])) {
	list($klapp_id, $klapp_value) = explode("|", $_GET["klapp"]);
	$klapp[intval($klapp_id)] = intval($klapp_value);
	$_SESSION["klapp"] = $klapp; //Rückgabe des Aktuellen Array mit Aufklappwerten in die Session
	mysql_query("UPDATE ".DB_PREPEND."phpwcms_user SET usr_var_privatefile='".serialize($_SESSION["klapp"])."' WHERE usr_id=".$_SESSION["wcs_user_id"], $db);
}

//Zähler für die Listenfunktion setzen
$_SESSION["list_zaehler"] = 0;

//Feststellen, ob überhaupt Dateien/Ordner vorhanden sind
$sql = "SELECT COUNT(f_id) FROM ".DB_PREPEND."phpwcms_file WHERE f_trash=0";
if(empty($_SESSION["wcs_user_admin"])) {
	$sql .= " AND f_uid=".$_SESSION["wcs_user_id"];
}
$sql .= " LIMIT 1";
$count_user_files = _dbCount($sql);

//Wenn überhaupt Dateien für User vorhanden, dann Listing
if($count_user_files) {
	//Beginn Tabelle für Dateilisting
	echo "<table width=\"538\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">\n";
	echo "<tr><td colspan=\"2\"><img src=\"img/leer.gif\" width=\"1\" height=\"1\"></td></tr>\n";
	list_private(0, $db, 0, "phpwcms.php?do=files&amp;f=0", $_SESSION["wcs_user_id"], $cutID, $_SESSION["wcs_user_thumb"], $phpwcms);
	include_once (PHPWCMS_ROOT."/include/inc_lib/files.private-filelist.inc.php");
	echo "</table>\n";
	//Ende Tabelle
} else {
	//Wenn keinerlei Datensatz innerhalb Files durchlaufen wurde, dann
	echo "<img src=\"img/leer.gif\" width=\"1\" height=\"6\"><br />";			
	echo $BL['be_fprivadd_nofolders']."&nbsp;&nbsp;";
	echo "[<a href=\"phpwcms.php?do=files&amp;f=0&amp;mkdir=0\">".$BL['be_fpriv_button']."</a>]";
	echo "<br /><img src=\"img/leer.gif\" width=\"1\" height=\"6\">";
}
?>