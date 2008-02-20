<?php
/*************************************************************************************
   Copyright notice
   
   (c) 2002-2008 Oliver Georgi (oliver@phpwcms.de) // All rights reserved.
 
   This script is part of PHPWCMS. The PHPWCMS web content management system is
   free software; you can redistribute it and/or modify it under the terms of
   the GNU General Public License as published by the Free Software Foundation;
   either version 2 of the License, or (at your option) any later version.
  
   The GNU General Public License can be found at http://www.gnu.org/copyleft/gpl.html
   A copy is found in the textfile GPL.txt and important notices to the license 
   from the author is found in LICENSE.txt distributed with these scripts.
  
   This script is distributed in the hope that it will be useful, but WITHOUT ANY 
   WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A
   PARTICULAR PURPOSE.  See the GNU General Public License for more details.
 
   This copyright notice MUST APPEAR in all copies of the script!
*************************************************************************************/


// ----------------------------------------------------------------
// obligate check for phpwcms constants
if (!defined('PHPWCMS_ROOT')) {
   die("You Cannot Access This Script Directly, Have a Nice Day.");
}
// ----------------------------------------------------------------


if(isset($_GET["all"])) { //Übernehmen der Aufklappwerte aus Session-Variable
	if($_GET["all"] == "open") { //alle aufklappen
		$sql = "SELECT f_id FROM ".DB_PREPEND."phpwcms_file WHERE f_kid=0 AND f_trash=0 AND f_uid=".$_SESSION["wcs_user_id"];
		if($result = mysql_query($sql, $db) or die ("error while open all directories")) {
			while($row = mysql_fetch_row($result)) {
				$klapp[$row[0]] = 1;
			}
			$_SESSION["klapp"] = $klapp;
		}
	}
	if($_GET["all"] == "close") if(isset($_SESSION["klapp"])) unset($_SESSION["klapp"]);
}

if(isset($_SESSION["klapp"])) $klapp = $_SESSION["klapp"];

if(isset($_GET["klapp"])) {
	list($klapp_id, $klapp_value) = explode("|", $_GET["klapp"]);
	$klapp[intval($klapp_id)] = intval($klapp_value);
	$_SESSION["klapp"] = $klapp; //Rückgabe des Aktuellen Array mit Aufklappwerten in die Session
	mysql_query("UPDATE ".DB_PREPEND."phpwcms_user SET usr_var_privatefile='".serialize($_SESSION["klapp"])."' WHERE usr_id=".$_SESSION["wcs_user_id"], $db);
}

//Zähler für die Listenfunktion setzen
$_SESSION["list_zaehler"] = 0;

//Feststellen, ob überhaupt Dateien/Ordner des Users vorhanden sind
$sql = "SELECT COUNT(f_id) FROM ".DB_PREPEND."phpwcms_file WHERE ".
	   "f_uid=".$_SESSION["wcs_user_id"]." AND f_trash=0 LIMIT 1;";
if($result = mysql_query($sql, $db) or die ("error while counting user files")) {
	if($row = mysql_fetch_row($result)) $count_user_files = $row[0];
	mysql_free_result($result);
}

//Wenn überhaupt Dateien für User vorhanden, dann Listing
if(isset($count_user_files) && $count_user_files) {
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