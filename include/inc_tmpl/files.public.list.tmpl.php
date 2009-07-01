<?php
/*************************************************************************************
   Copyright notice
   
   (c) 2002-2009 Oliver Georgi (oliver@phpwcms.de) // All rights reserved.
 
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


//Default for listing public files
$vor = 0;
if(isset($pklapp)) unset($pklapp);

if(isset($_GET["all"])) {
	if($_GET["all"] == "close") {
		if(isset($_SESSION["pklapp"])) unset($_SESSION["pklapp"]);
	}
}			

if(isset($_SESSION["pklapp"])) $pklapp = $_SESSION["pklapp"]; 

if(isset($_GET["pklapp"])) {
	list($pklapp_id, $pklapp_value) = explode("|", $_GET["pklapp"]);
	$pklapp[$pklapp_id] = intval($pklapp_value);
	$_SESSION["pklapp"] = $pklapp; //Rückgabe des Aktuellen Array mit Aufklappwerten in die Session
	mysql_query("UPDATE ".DB_PREPEND."phpwcms_user SET usr_var_publicfile='".serialize($_SESSION["pklapp"])."' WHERE usr_id=".$_SESSION["wcs_user_id"], $db);
}
$_SESSION["list_zaehler"] = 0; //Zähler für die Public-Listenfunktion setzen
			
//Feststellen, ob überhaupt Dateien/Ordner des Users vorhanden sind
$sql = "SELECT COUNT(f_id) FROM ".DB_PREPEND."phpwcms_file WHERE f_public=1 AND f_aktiv=1 AND f_trash=0 LIMIT 1;";
if($result = mysql_query($sql, $db) or die ("error while counting user files")) {
	if($row = mysql_fetch_row($result)) {
		$count_user_files = $row[0];
	}
	mysql_free_result($result);
}

if(isset($count_user_files) && $count_user_files) { //Wenn überhaupt Public-Dateien vorhanden, dann Listing
	//Beginn Tabelle für Public Dateilisting
	echo "<table width=\"538\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">\n";
	echo "<tr><td colspan=\"2\"><img src=\"img/leer.gif\" width=\"1\" height=\"1\"></td></tr>\n";

	//Prüfen, für welche User überhaupt Public Files vorhanden sind
	$sql = "SELECT DISTINCT ".DB_PREPEND."phpwcms_file.f_uid, ".DB_PREPEND."phpwcms_user.usr_login, ".DB_PREPEND."phpwcms_user.usr_name ".
		   "FROM ".DB_PREPEND."phpwcms_file INNER JOIN ".DB_PREPEND."phpwcms_user ON ".DB_PREPEND."phpwcms_file.f_uid=".DB_PREPEND."phpwcms_user.usr_id ".
		   "WHERE ".DB_PREPEND."phpwcms_file.f_public=1 AND ".DB_PREPEND."phpwcms_file.f_aktiv=1 AND ".DB_PREPEND."phpwcms_file.f_trash=0 ".
		   "ORDER BY ".DB_PREPEND."phpwcms_user.usr_name, ".DB_PREPEND."phpwcms_user.usr_login;";

	if($result = mysql_query($sql, $db) or die ("error while browsing user's public files")) {
		$user_counter=0;
		while($row = mysql_fetch_array($result)) {
			//Prüfen
			$pklapp_status = isset($pklapp[ "u".$row["f_uid"] ]) ? true_false($pklapp[ "u".$row["f_uid"] ]) : 1;
			$root_user_id = intval($row["f_uid"]);
			$user_naming = html_specialchars($row["usr_name"]." (".$row["usr_login"].")");
			$count = "<img src=\"img/leer.gif\" width=\"2\" height=\"1\">".
					 "<a href=\"phpwcms.php?do=files&f=1&pklapp=u".$row["f_uid"].
					 "|".$pklapp_status."\">".on_off($pklapp_status, "\n".$BL['be_fpublic_user'].": ".$user_naming, 0)."</a>";

			//Aufbau der Zeile mit den Benutzerinfos
			if($user_counter) {
				//Trennende blaue Tabellen-Zeile zwischen unterschiedlicghen Public Users
				echo "<tr><td colspan=\"2\"><img src=\"img/lines/line-lightgrey-dotted-538.gif\" height=\"1\" width=\"538\"></td></tr>\n";
				echo "<tr><td colspan=\"2\"><img src=\"img/leer.gif\" height=\"1\" width=\"1\"></td></tr>\n";
			}
			echo "<tr bgcolor=\"#D8E4E9\"><td colspan=\"2\"><img src=\"img/leer.gif\" height=\"2\" width=\"1\"></td></tr>\n"; //Abstand vor
			echo "<tr bgcolor=\"#D8E4E9\">\n"; //Einleitung Tabellenzeile
			echo "<td width=\"488\" class=\"msglist\">"; //Einleiten der Tabellenzelle
			echo $count."<img src=\"img/leer.gif\" height=\"1\" width=\"".($vor+6)."\" border=\"0\"><img src=\"img/icons/user_zu.gif\" border=\"0\">";
			echo "<img src=\"img/leer.gif\" height=\"1\" width=\"5\"><strong>".$user_naming."</strong></td>\n"; //Schließen Zelle 1. Spalte
			echo "<td width=\"50\" align=\"right\" class=\"msglist\">"; //Zelle 2. Spalte - vorgesehen für Buttons/Tasten Edit etc.
			echo "<img src=\"img/leer.gif\" width=\"50\" height=\"1\">"; //Spacer
			echo "</td>\n"; 
			echo "</tr>\n"; //Abschluss Tabellenzeile
			//Aufbau trennende Tabellen-Zeile  bgcolor:#EBF2F4
			echo "<tr bgcolor=\"#D8E4E9\"><td colspan=\"2\"><img src=\"img/leer.gif\" height=\"2\" width=\"1\"></td></tr>\n"; //Abstand nach
			echo "<tr><td colspan=\"2\"><img src=\"img/leer.gif\" height=\"1\" width=\"1\"></td></tr>\n"; //Trennlinie

			if(!$pklapp_status) {
				list_public(0, $db, 18, "phpwcms.php?do=files&f=1", $row["f_uid"], $_SESSION["wcs_user_thumb"], $phpwcms);

				//Root files anzeigen
				$file_sql = "SELECT * FROM ".DB_PREPEND."phpwcms_file WHERE f_pid=0 AND f_uid=".$root_user_id.
							" AND f_public=1 AND f_aktiv=1 AND f_kid=1 AND f_trash=0 ORDER BY f_name;";
				if($file_result = mysql_query($file_sql, $db) or die ("error while listing files")) {
					$file_durchlauf = 0;
					while($file_row = mysql_fetch_array($file_result)) {
						$filename = html_specialchars($file_row["f_name"]);
						if(!$file_durchlauf) { //Aufbau der Zeile zum Einfließen der Filelisten-Tabelle
							echo "<tr bgcolor=\"#F5F8F9\"><td colspan=\"2\"><table width=\"538\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\">\n"; 
						} else {
							echo "<tr bgcolor=\"#FFFFFF\"><td colspan=\"5\"><img src=\"img/leer.gif\" height=\"1\" width=\"1\"></td></tr>\n";
						}
						echo "<tr>\n";
						echo "<td width=\"37\" class=\"msglist\"><img src=\"img/leer.gif\" height=\"1\" width=\"37\" border=\"0\"></td>\n";
						echo "<td width=\"13\" class=\"msglist\">";
						echo "<img src=\"img/icons/small_".extimg($file_row["f_ext"])."\" border=\"0\"";
						echo ' onmouseover="Tip(\'ID: '.$file_row["f_id"].'\');" alt=""';
						echo "></td>\n";
						echo "<td width=\"473\" class=\"msglist\"><img src=\"img/leer.gif\" height=\"1\" width=\"5\">"; //438-$vor
						//echo "<a href=\"fileinfo_public.php?fid=".$file_row["f_id"];
						echo "<a href=\"fileinfo.php?public&amp;fid=".$file_row["f_id"];
						echo "\" target=\"_blank\" onclick=\"flevPopupLink(this.href,'filedetail','scrollbars=yes,resizable=yes,width=500,height=400',1);return document.MM_returnValue;\">";
						echo $filename."</a>";

						echo "</td>\n";
						echo "<td width=\"15\" align=\"right\" class=\"msglist\">";
						echo "<a href=\"include/inc_act/act_download.php?pl=1&dl=".$file_row["f_id"];
				 		echo "\" target=\"_blank\" title=\"".$BL['be_fprivfunc_dlfile'].": ".$filename."\">";
	 					echo "<img src=\"img/button/download_disc.gif\" border=\"0\"></a>"; //target='_blank'
						echo "<img src=\"img/leer.gif\" width=\"2\" height=\"1\">"; //Spacer
						echo "</td>\n";
						//Ende Aufbau
						echo "</tr>\n";
						
						if($_SESSION["wcs_user_thumb"]) {
			
							$thumb_image = get_cached_image(
			 					array(	"target_ext"	=>	$file_row["f_ext"],
										"image_name"	=>	$file_row["f_hash"] . '.' . $file_row["f_ext"],
										"thumb_name"	=>	md5($file_row["f_hash"].$phpwcms["img_list_width"].$phpwcms["img_list_height"].$phpwcms["sharpen_level"])
        							  )
								);

							if($thumb_image != false) {
							
								echo "<tr>\n";
								echo "<td width=\"37\"><img src=\"img/leer.gif\" height=\"1\" width=\"37\" border=\"0\"></td>\n";
								echo "<td width=\"13\"><img src=\"img/leer.gif\" height=\"1\" width=\"1\" border=\"0\"></td>\n<td width=\"";
								echo "473\"><img src=\"img/leer.gif\" height=\"1\" width=\"6\"><a href=\"fileinfo.php?public&amp;fid=";
								echo $file_row["f_id"]."\" target=\"_blank\" onclick=\"flevPopupLink(this.href,'filedetail','scrollbars=";
								echo "yes,resizable=yes,width=500,height=400',1); return document.MM_returnValue;\">";
								echo '<img src="'.PHPWCMS_IMAGES . $thumb_image[0] .'" border="0" '.$thumb_image[3]."></a></td>\n";	
								echo "<td width=\"15\"><img src=\"img/leer.gif\" height=\"1\" width=\"1\" border=\"0\"></td>\n</tr>\n";
								echo "<tr><td colspan=\"4\"><img src=\"img/leer.gif\" height=\"2\" width=\"1\" border=\"0\"></td>\n</tr>\n";
		
							}
				
						}
						
						/*
						if($file_row["f_thumb_list"] && $_SESSION["wcs_user_thumb"]) { //Wenn List Preview Image verfügbar
							echo "<tr>\n";
							echo "<td width=\"37\"><img src=\"img/leer.gif\" height=\"1\" width=\"37\" border=\"0\"></td>\n";
							echo "<td width=\"13\"><img src=\"img/leer.gif\" height=\"1\" width=\"1\" border=\"0\"></td>\n<td width=\"";
							//echo "473\"><img src=\"img/leer.gif\" height=\"1\" width=\"6\"><a href=\"fileinfo_public.php?fid="; //523
							echo "473\"><img src=\"img/leer.gif\" height=\"1\" width=\"6\"><a href=\"fileinfo.php?public&amp;fid=";
							echo $file_row["f_id"]."\" target=\"_blank\" onclick=\"flevPopupLink(this.href,'filedetail','scrollbars=";
							echo "yes,resizable=yes,width=500,height=400',1); return document.MM_returnValue;\">";
							echo "<img src=\"".$phpwcms["file_tmp"].$phpwcms["dir_thlist"].$file_row["f_thumb_list"]."\" border=\"0\"></a></td>\n";
							echo "<td width=\"15\"><img src=\"img/leer.gif\" height=\"1\" width=\"1\" border=\"0\"></td>\n</tr>\n";
							echo "<tr><td colspan=\"4\"><img src=\"img/leer.gif\" height=\"2\" width=\"1\" border=\"0\"></td>\n</tr>\n";
						}
						*/
						$file_durchlauf++;
					}
					if($file_durchlauf) { //Abschluss der Filelisten-Tabelle
						echo "</table>\n";
						echo "<tr><td colspan=\"2\"><img src=\"img/leer.gif\" height=\"1\" width=\"1\"></td></tr>\n";
					}
				} //Ende Liste Dateien

				//Ende Anzeige root files public
			}
			$user_counter++;
		}
	}
	echo "</table>\n"; //Ende Tabelle
} else { //Wenn keinerlei Datensatz innerhalb Files durchlaufen wurde, dann
	echo "<img src=\"img/leer.gif\" width=\"1\" height=\"6\"><br />".$BL['be_fpublic_nofiles']."&nbsp;&nbsp;";
	echo "[<a href=\"phpwcms.php?do=files&f=0&mkdir=0\">".$BL['be_fpriv_button']."</a>]";
	echo "<br /><img src=\"img/leer.gif\" width=\"1\" height=\"6\">";
}
?>