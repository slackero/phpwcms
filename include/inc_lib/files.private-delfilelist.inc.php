<?php
/**
 * phpwcms content management system
 *
 * @author Oliver Georgi <og@phpwcms.org>
 * @copyright Copyright (c) 2002-2015, Oliver Georgi
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



//Listing der gelöschten Dateien
$file_sql = "SELECT * FROM ".DB_PREPEND."phpwcms_file WHERE f_uid=".$_SESSION["wcs_user_id"].
			" AND f_kid=1 AND f_trash=1 ORDER BY f_name";
if($file_result = mysql_query($file_sql, $db) or die ("error while listing files")) {
	$file_durchlauf = 0;
	while($file_row = mysql_fetch_array($file_result)) {
		$filename = html($file_row["f_name"]);
		if(!$file_durchlauf) { //Aufbau der Zeile zum Einfließen der Filelisten-Tavbelle
			echo "<tr bgcolor=\"#F5F8F9\"><td colspan=2><table width=\"538\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\">\n";
		} else {
			echo "<tr bgcolor=\"#FFFFFF\"><td colspan=\"5\"><img src=\"img/leer.gif\" height=\"1\" width=\"1\"></td></tr>\n";
		}
		echo "<tr>\n";
		echo "<td width=\"6\" class=\"msglist\"><img src=\"img/leer.gif\" height=\"1\" width=\"6\" border=\"0\"></td>\n";
		echo "<td width=\"13\" class=\"msglist\">";
		echo "<img src=\"img/icons/small_".extimg($file_row["f_ext"])."\" border=\"0\"></td>\n";
		echo "<td width=\"419\" class=\"msglist\"><img src=\"img/leer.gif\" height=\"1\" width=\"5\">";
		echo "<a href=\"fileinfo.php?fid=".$file_row["f_id"];
		echo "\" target=\"_blank\" onclick=\"flevPopupLink(this.href,'filedetail','scrollbars=yes,resizable=yes,width=500,height=400',1);return document.MM_returnValue;\">";
		echo $filename."</a></td>\n";
		//Aufbauen Buttonleiste für jeweilige Datei
		echo "<td width=\"100\" align=\"right\" class=\"msglist\">";
		//Button zum Herausholen der Datei aus dem Papierkorb
		echo "<a href=\"include/inc_act/act_file.php?trash=".$file_row["f_id"]."|0".
	 		 "\" title=\"".$BL['be_ftrash_undo'].": ".$filename."\" onclick=\"return confirm('".
			 str_replace('{VAL}', $filename, $BL['be_ftrash_restore'])."');\">".
			 "<img src=\"img/button/cancel_trash_13x13.gif\" border=\"0\"></a>";
		echo "<img src=\"img/leer.gif\" width=\"5\" height=\"1\">"; //Spacer
		//Button zum Löschen der Datei
		echo "<a href=\"include/inc_act/act_file.php?trash=".$file_row["f_id"]."|9".
	 		 "\" title=\"".$BL['be_ftrash_delfinal'].": ".$filename."\" onclick=\"return confirm('".
			 str_replace('{VAL}', $filename, $BL['be_ftrash_delete'])."');\">".
			 "<img src=\"img/button/trash_13x13_1.gif\" border=\"0\"></a>";
		echo "<img src=\"img/leer.gif\" width=\"2\" height=\"1\">"; //Spacer
		echo "</td>\n";
		//Ende Aufbau
		echo "</tr>\n";
		$file_durchlauf++;
	}
	if($file_durchlauf) { //Abschluss der Filelisten-Tabelle
		echo "</table>\n";
		echo "<tr bgcolor=\"#F5F8F9\"><td colspan=\"2\"><img src=\"img/leer.gif\" height=\"1\" width=\"1\"></td></tr>\n"; //Abstand vor
	}
} //Ende Liste Dateien
?>