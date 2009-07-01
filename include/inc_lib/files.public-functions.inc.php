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


//Funktionen zum Listen der privaten Dateien

function list_public($pid, $dbcon, $vor, $zieldatei, $userID, $show_thumb=1, $phpwcms) {
	$klapp = $_SESSION["pklapp"];
	$pid = intval($pid);
	//Folder Listing für Public files
	$sql = "SELECT f_id, f_name FROM ".DB_PREPEND."phpwcms_file WHERE ".
		   "f_pid=".intval($pid)." AND ".
		   "f_public=1 AND f_aktiv=1 AND ".
		   "f_uid=".intval($userID)." AND ".
		   "f_kid=0 AND f_trash=0 ORDER BY f_sort, f_name";
	$result = mysql_query($sql, $dbcon);
	while($row = mysql_fetch_array($result)) {
		
		$dirname = html_specialchars($row["f_name"]);
		
		//Ermitteln des Aufklappwertes
		$klapp_status = isset($klapp[$row["f_id"]]) ? true_false($klapp[$row["f_id"]]) : 0;
		
		//Ermitteln, ob überhaupt abhängige Dateien/Ordner existieren
		$count_sql = "SELECT COUNT(f_id) FROM ".DB_PREPEND."phpwcms_file WHERE ".
					 "f_pid=".$row["f_id"]." AND ".
					 "f_uid=".intval($userID)." AND ".
					 "f_public=1 AND f_aktiv=1 AND ".
					 "f_trash=0 LIMIT 1";
		if($count_result = mysql_query($count_sql, $dbcon)) {
			if($count_row = mysql_fetch_row($count_result)) {
				$count = "<img src=\"img/leer.gif\" width=\"2\" height=\"1\">".
						 "<a href=\"".$zieldatei."&pklapp=".$row["f_id"].
						 "|".$klapp_status."\">".on_off($klapp_status, $dirname, 0)."</a>";
				$count_wert = $count_row[0];
			}
			mysql_free_result($count_result);
		}
		
		//Aufbau der Zeile
		echo "<tr bgcolor=\"#EBF2F4\"><td colspan=\"2\"><img src=\"img/leer.gif\" height=\"1\" width=\"1\"></td></tr>\n"; //Abstand vor
		echo "<tr bgcolor=\"#EBF2F4\">\n"; //Einleitung Tabellenzeile
		echo "<td width=\"488\" class='msglist'>"; //Einleiten der Tabellenzelle
		echo $count."<img src=\"img/leer.gif\" height=\"1\" width=\"".($vor+6)."\" border=\"0\"><img src=\"img/icons/folder_zu.gif\" border=\"0\">"; //Zellinhalt 1. Spalte
		echo "<img src=\"img/leer.gif\" height=\"1\" width=\"5\"><strong>".$dirname; //Zellinhalt 1. Spalte Fortsetzung
		echo "</strong></td>\n"; //Schließen Zelle 1. Spalte
		//Zelle 2. Spalte - vorgesehen für Buttons/Tasten Edit etc.
		echo "<td width=\"50\" align=\"right\" class=\"msglist\">";
		echo "<img src=\"img/leer.gif\" width=\"50\" height=\"1\">"; //Spacer
		echo "</td>\n"; 
		echo "</tr>\n"; //Abschluss Tabellenzeile
		
		//Aufbau trennende Tabellen-Zeile
		echo "<tr bgcolor=\"#EBF2F4\"><td colspan=\"2\"><img src=\"img/leer.gif\" height=\"1\" width=\"1\"></td></tr>\n"; //Abstand nach
		echo "<tr><td colspan=\"2\"><img src=\"img/leer.gif\" height=\"1\" width=\"1\"></td></tr>\n"; //Trennlinie<img src='img/lines/line-lightgrey-dotted-538.gif'>
		
		//Weiter, wenn Unterstruktur
		if(!$klapp_status && $count_wert) { //$vor."<img src='img/leer.gif' height=1 width=18 border=0>"
			list_public($row["f_id"], $dbcon, $vor+18, $zieldatei, $userID, $show_thumb, $phpwcms);
			
			//Listing eventuell im Verzeichnis enthaltener Dateien
			$file_sql = "SELECT * FROM ".DB_PREPEND."phpwcms_file WHERE f_pid=".$row["f_id"]." AND f_uid=".intval($userID).
						" AND f_public=1 AND f_aktiv=1 AND f_kid=1 AND f_trash=0 ORDER BY f_sort, f_name";
			if($file_result = mysql_query($file_sql, $dbcon) or die ("error while listing files")) {
				$file_durchlauf = 0;
				while($file_row = mysql_fetch_array($file_result)) {
					$filename = html_specialchars($file_row["f_name"]);
					if(!$file_durchlauf) { //Aufbau der Zeile zum Einfließen der Filelisten-Tabelle
						echo "<tr bgcolor=\"#F5F8F9\"><td colspan=\"2\"><table width=\"538\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\">\n"; 
					} else {
						echo "<tr bgcolor=\"#FFFFFF\"><td colspan=\"5\"><img src=\"img/leer.gif\" height=\"1\" width=\"1\"></td></tr>\n";
					}
					echo "<tr>\n";
					echo "<td width=\"".($vor+37)."\" class=\"msglist\"><img src=\"img/leer.gif\" height=\"1\" width=\"".($vor+37)."\" border=\"0\"></td>\n";
					echo "<td width=\"13\" class=\"msglist\">";
					echo "<img src=\"img/icons/small_".extimg($file_row["f_ext"])."\" border=\"0\"";
	
					echo ' onmouseover="Tip(\'ID: '.$file_row["f_id"].'&lt;br&gt;Sort: '.$file_row["f_sort"].'\');" alt=""';
					
					echo "></td>\n";
					echo "<td width=\"".(473-$vor)."\" class=\"msglist\"><img src=\"img/leer.gif\" height=\"1\" width=\"5\">"; //438-$vor
					echo "<a href=\"fileinfo.php?public&amp;fid=".$file_row["f_id"];
					echo "\" target=\"_blank\" onclick=\"flevPopupLink(this.href,'filedetail','scrollbars=yes,resizable=yes,width=500,height=400',1);return document.MM_returnValue;\">";
					echo $filename."</a>";
					echo "</td>\n";
					echo "<td width=\"15\" align=\"right\" class=\"msglist\">";
					echo "<a href=\"include/inc_act/act_download.php?pl=1&dl=".$file_row["f_id"];
					echo "\" target=\"_blank\" title=\"".$GLOBALS['BL']['be_fprivfunc_dlfile'].": ".$filename."\">";
					echo "<img src=\"img/button/download_disc.gif\" border=0></a>"; //target='_blank'
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
							echo "<td width=\"".($vor+37)."\"><img src=\"img/leer.gif\" height=\"1\" width=\"".($vor+37)."\" border=\"0\"></td>\n";
							echo "<td width=\"13\"><img src=\"img/leer.gif\" height=\"1\" width=\"1\" border=\"0\"></td>\n<td width=\"";
							echo (473-$vor)."\"><img src=\"img/leer.gif\" height=\"1\" width=\"6\"><a href=\"fileinfo.php?public&amp;fid=";
							echo $file_row["f_id"]."\" target=\"_blank\" onclick=\"flevPopupLink(this.href,'filedetail','scrollbars=";
							echo "yes,resizable=yes,width=500,height=400',1); return document.MM_returnValue;\">";
							echo '<img src="'.PHPWCMS_IMAGES . $thumb_image[0] .'" border="0" '.$thumb_image[3];
							
							echo ' onmouseover="Tip(\'ID: '.$file_row["f_id"].'\');" alt=""';
							
							echo "></a></td>\n";	
							echo "<td width=\"15\"><img src=\"img/leer.gif\" height=\"1\" width=\"1\" border=\"0\"></td>\n</tr>\n";
							echo "<tr><td colspan=\"4\"><img src=\"img/leer.gif\" height=\"2\" width=\"1\" border=\"0\"></td>\n</tr>\n";
		
						}
				
					}

					$file_durchlauf++;
				}
				if($file_durchlauf) { //Abschluss der Filelisten-Tabelle
					echo "</table>\n";
					echo "<tr><td colspan=\"2\"><img src=\"img/leer.gif\" height=\"1\" width=\"1\"></td></tr>\n";
				}
			} //Ende Liste Dateien
		}
		//Zaehler mitführen
		$_SESSION["list_zaehler"]++;
	}
	mysql_free_result($result);
	return $vor;
}

function true_false($wert) {
	//Wechselt den Wahr/Falsch wert zum Gegenteil: 1=>0 und 0=>1
	return (intval($wert)) ? 0 : 1;
}

function on_off($wert, $string, $art = 1) {
	//Erzeugt das Status-Zeichen für Klapp-Auf/Zu
	//Wenn Art = 1 dann als Zeichen, ansonsten als Bild
	if($wert) {
		return ($art == 1) ? "+" : "<img src=\"img/symbols/klapp_zu.gif\" border=\"0\" title=\"".$GLOBALS['BL']['be_fprivfunc_opendir']." ".$string."\">";
	} else {
		return ($art == 1) ? "-" : "<img src=\"img/symbols/klapp_auf.gif\" border=\"0\" title=\"".$GLOBALS['BL']['be_fprivfunc_closedir']." ".$string."\">";
	}
}

function list_public_root($wert) {
	//Checken ob public root files für user gezeigt werden sollen
	return ($wert) ? 1 : 0;
}
?>