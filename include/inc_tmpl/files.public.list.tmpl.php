<?php
/**
 * phpwcms content management system
 *
 * @author Oliver Georgi <og@phpwcms.org>
 * @copyright Copyright (c) 2002-2016, Oliver Georgi
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


//Default for listing public files
$vor = 0;

if(!isset($_SESSION["pklapp"]) || (isset($_GET["all"]) && $_GET["all"] == "close")) {
	$_SESSION["pklapp"] = array();
}

if(isset($_GET["pklapp"])) {

	list($pklapp_id, $pklapp_value) = explode("|", $_GET["pklapp"]);

	if(intval($pklapp_value)) {
		$_SESSION["pklapp"][$pklapp_id] = 1;
	} else {
		unset($_SESSION["pklapp"][$pklapp_id]);
	}

	foreach($_SESSION["pklapp"] as $pklapp_id => $pklapp_value) {
		if(!$pklapp_value) {
			unset($_SESSION["pklapp"][$pklapp_id]);
		}
	}

	mysql_query("UPDATE ".DB_PREPEND."phpwcms_user SET usr_var_publicfile="._dbEscape(serialize($_SESSION["pklapp"]))." WHERE usr_id=".$_SESSION["wcs_user_id"], $db);

}

$_SESSION["list_zaehler"] = 0; //Z�hler f�r die Public-Listenfunktion setzen

//Feststellen, ob �berhaupt Dateien/Ordner des Users vorhanden sind
$sql = "SELECT COUNT(f_id) FROM ".DB_PREPEND."phpwcms_file WHERE f_public=1 AND f_aktiv=1 AND f_trash=0 LIMIT 1;";
if($result = mysql_query($sql, $db) or die ("error while counting user files")) {
	if($row = mysql_fetch_row($result)) {
		$count_user_files = $row[0];
	}
	mysql_free_result($result);
}

if(isset($count_user_files) && $count_user_files) { //Wenn �berhaupt Public-Dateien vorhanden, dann Listing
	//Beginn Tabelle f�r Public Dateilisting
	echo "<table width=\"538\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">\n";
	echo "<tr><td colspan=\"2\"><img src=\"img/leer.gif\" width=\"1\" height=\"1\"></td></tr>\n";

	//Pr�fen, f�r welche User �berhaupt Public Files vorhanden sind
	$sql = "SELECT DISTINCT f.f_uid, u.usr_login, u.usr_name ".
		   "FROM ".DB_PREPEND."phpwcms_file f INNER JOIN ".DB_PREPEND."phpwcms_user u ON f.f_uid=u.usr_id ".
		   "WHERE f.f_public=1 AND f.f_aktiv=1 AND f.f_trash=0 ".
		   "ORDER BY u.usr_name, u.usr_login;";

	if($result = mysql_query($sql, $db) or die ("error while browsing user's public files")) {
		$user_counter=0;
		while($row = mysql_fetch_array($result)) {
			//Pr�fen
			$pklapp_status = empty($_SESSION["pklapp"][ "u".$row["f_uid"] ]) ? 1 : 0;
			$root_user_id = intval($row["f_uid"]);
			$user_naming = html($row["usr_name"]." (".$row["usr_login"].")");
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
			echo "<img src=\"img/leer.gif\" height=\"1\" width=\"5\"><strong>".$user_naming."</strong></td>\n"; //Schlie�en Zelle 1. Spalte
			echo "<td width=\"50\" align=\"right\" class=\"msglist\">"; //Zelle 2. Spalte - vorgesehen f�r Buttons/Tasten Edit etc.
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
						$filename = html($file_row["f_name"]);
						if(!$file_durchlauf) { //Aufbau der Zeile zum Einflie�en der Filelisten-Tabelle
							echo "<tr bgcolor=\"#F5F8F9\"><td colspan=\"2\"><table width=\"538\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\">\n";
						} else {
							echo "<tr bgcolor=\"#FFFFFF\"><td colspan=\"5\"><img src=\"img/leer.gif\" height=\"1\" width=\"1\"></td></tr>\n";
						}
						echo "<tr>\n";
						echo "<td width=\"37\" class=\"msglist\"><img src=\"img/leer.gif\" height=\"1\" width=\"37\" border=\"0\"></td>\n";
						echo "<td width=\"13\" class=\"msglist\">";
						echo "<img src=\"img/icons/small_".extimg($file_row["f_ext"])."\" border=\"0\"";
						echo ' onmouseover="Tip(\'ID: '.$file_row["f_id"].'\');" onmouseout="UnTip()" alt=""';
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

							$thumb_image = get_cached_image(array(
								"target_ext"	=>	$file_row["f_ext"],
								"image_name"	=>	$file_row["f_hash"] . '.' . $file_row["f_ext"],
								"thumb_name"	=>	md5($file_row["f_hash"].$phpwcms["img_list_width"].$phpwcms["img_list_height"].$phpwcms["sharpen_level"].$phpwcms['colorspace'])
							));

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
