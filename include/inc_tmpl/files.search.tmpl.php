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



//Suche template
			
if(isset($_POST["file_aktion"]) && intval($_POST["file_aktion"]) == 1) {
	$search_string	= explode(" ", clean_slweg($_POST["file_search"]));
	if(sizeof($search_string)) {
		foreach($search_string as $key => $value) {
			if(trim($value)) $search["key"][$key] = trim($value);
		}
		unset($search_string);
		if(isset($search["key"]) && sizeof($search["key"])) {					
		//check for AND or OR
			$search["andor"] = (intval($_POST["file_andor"])) ? 1 : 0;
			$search["which"] = intval($_POST["file_which"]);
			switch($search["which"]) {
			 	case 0: $search["which"]="f_public=0 AND f_uid=".$_SESSION["wcs_user_id"]; break;
			 	case 1: $search["which"]="f_public=1"; break;
				default: $search["which"]="(f_public=1 OR (f_public=0 AND f_uid=".$_SESSION["wcs_user_id"]."))"; break;
			}
			
			$file_key = get_list_of_file_keywords(); //Auslesen der File Schlüsselwörter
			
			//Aufbau des eigentlichen Suchstrings
			$sql = "SELECT * FROM ".DB_PREPEND."phpwcms_file WHERE f_aktiv=1 AND f_trash=0 AND f_kid=1 AND ";
			$sql.= $search["which"].";"; //ob public oder private order keine Angabe
			if($result = mysql_query($sql, $db) or die ("error while running search")) {
				while($row = mysql_fetch_array($result)) {
					$search["string"]  = $row["f_name"]." ".$row["f_shortinfo"]." ".$row["f_longinfo"];
					$search["string"]  = str_replace("\r\n", " ", $search["string"]);
					$search["string"]  = str_replace("\n", " ", $search["string"]);
					$search["string"] .= add_keywords_to_search ($file_key, $row["f_keywords"]); //fügt freie Keywords zum Suchstring hinzu
					
					foreach($search["key"] as $value) {
						if(preg_match("/".preg_quote($value,"/")."/i", $search["string"])) {
							if($search["andor"]) {
								if(!isset($search["result"][$row["f_id"]])) {; //AND clause
									$search["result"][$row["f_id"]] = 1;
								} else {
									$search["result"][$row["f_id"]]++;
								}
							} else {
								$search["result"][$row["f_id"]] = 1; //OR clause
							}
						}
					}
				}
				if(isset($search["result"]) && sizeof($search["result"]) && $search["andor"]) {
					//Prüfen, ob die AND bedingung erfüllt ist
					//gilt nur, wenn Anzahl Suchworte = Anzahl Funde im String
					$search["count_key"] = sizeof($search["key"]);
					foreach($search["result"] as $key => $value) {
						if($search["count_key"] != $value) unset($search["result"][$key]);
					}
				}	
			}													
		} else {
			$search["error"][1] = $BL['be_fsearch_err1'];
		}
	} else {
		$search["error"][1] = $BL['be_fsearch_err1'];
	}
}

?><table width="538" border="0" cellpadding="0" cellspacing="0" bgcolor='#EBF2F4' summary="">
	<form action="phpwcms.php?do=files&f=3" method="post" enctype="multipart/form-data" name="searchfile">
	<input name="file_aktion" type="hidden" id="file_aktion" value="1">
	<tr><td colspan="2" valign="top"><img src="img/leer.gif" alt="" width="1" height="5"></td></tr>
	<tr>
	<td width="67" rowspan="3" align="right" valign="top"><img src="img/leer.gif" alt="" width="10" height="1"><img src="img/symbole/lupe_suche.gif" alt="" width="23" height="21"><img src="img/leer.gif" alt="" width="10" height="1"></td>
	<td width="471" class="title"><?php echo $BL['be_fsearch_title'] ?></td>
	</tr>
	<tr><td valign="top"><img src="img/leer.gif" alt="" width="1" height="4"></td></tr>
	<tr><td class="v09"><?php echo $BL['be_fsearch_infotext'] ?></td></tr>
	<tr>
	  <td colspan="2" valign="top"><img src="img/leer.gif" alt="" width="1" height="6"></td>
	  </tr>
	  <?php if(isset($search["error"])) { //fehler suche anfang ?>
	  	<tr>
			<td valign="top">&nbsp;</td>
			<td valign="top" class="error"><?php
				  	$zz=0;
	  				foreach($search["error"] as $value) {
						if($zz) echo "<br />";
	  					echo html_specialchars($value);
						$zz++;
	  				}
	  ?></td>
	  </tr>
	  <tr><td colspan="2" valign="top"><img src="img/leer.gif" alt="" width="1" height="6"></td></tr>
	  <?php	  } //fehler suche ende	  ?>
	<tr>
		<td align="right" class="v09"><?php echo $BL['be_fsearch_searchlabel'] ?>:&nbsp;</td>
		<td><table border="0" cellpadding="0" cellspacing="0" summary="">
		  <tr>
		    <td><input name="file_search" type="text" class="v10" id="file_search" style="font-weight: bold; width: 300px;" value="<?php if(isset($_POST["file_search"])) echo html_specialchars(trim($_POST["file_search"])); ?>" size="40" maxlength="250"><img src="img/leer.gif" alt="" width="2" height="1"></td>
			<script language="JavaScript" type="text/javascript">
			<!--
			document.searchfile.file_search.focus();
			//-->
			</script>
		    <td><select name="file_andor" id="file_andor" class="v10" style="width: 50px;">
			<?php
			
			$s1 = isset($_POST["file_andor"]) ? $_POST["file_andor"] : 1;
			$s2 = isset($_POST["file_which"]) ? $_POST["file_which"] : 2;
			
			?>
		      <option value="1" <?php is_selected("1", $s1) ?>><?php echo $BL['be_fsearch_and'] ?></option>
		      <option value="0" <?php is_selected("0", $s1) ?>><?php echo $BL['be_fsearch_or'] ?></option>      
              </select><img src="img/leer.gif" alt="" width="2" height="1"></td>
		    <td><select name="file_which" id="file_which" class="v10" style="width: 75px;">
		      <option value="2" <?php is_selected("2", $s2) ?>><?php echo $BL['be_fsearch_all'] ?></option>
		      <option value="0" <?php is_selected("0", $s2) ?>><?php echo $BL['be_fsearch_personal'] ?></option>
		      <option value="1" <?php is_selected("1", $s2) ?>><?php echo $BL['be_fsearch_public'] ?></option>          
              </select><img src="img/leer.gif" alt="" width="5" height="1"></td>
		    <td><input name="submit" type="image" id="submit" src="img/button/go_search.gif" alt="<?php echo $BL['be_fsearch_startsearch'] ?>" width="22" height="14" border="0"></td>
		    </tr>
		  </table></td>
	</tr>
	<tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="10"></td></tr>
	<tr><td colspan="2" bgcolor="#9BBECA"><img src="img/leer.gif" alt="" width="1" height="4"></td></tr>
  </form>
</table><?php

if(isset($search["result"])) {
	//Beginn Tabelle für Dateilisting
	echo "<table width=\"538\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">\n";
	echo "<tr><td colspan=\"2\"><img src=\"img/leer.gif\" width=\"1\" height=\"1\"></td></tr>\n";

	$sl=0; $search["filelist"] = " ";
	foreach($search["result"] as $key => $value) {
		if($sl) $search["filelist"] .=" OR ";
		$search["filelist"] .= "f_id=".intval($key);
		$sl++;					
	}

	//Listing der gefundenen Dateien
	$file_sql = "SELECT * FROM ".DB_PREPEND."phpwcms_file WHERE (".
				( (trim($search["filelist"])) ? $search["filelist"] : 0 ).
				") AND f_kid=1 AND f_trash=0 ORDER BY f_name;";
	if($file_result = mysql_query($file_sql, $db) or die ("error while listing files")) {
		$file_durchlauf = 0;
		while($file_row = mysql_fetch_array($file_result)) {
			$filename = html_specialchars($file_row["f_name"]);
			if(!$file_durchlauf) { //Aufbau der Zeile zum Einfließen der Filelisten-Tavbelle
				echo "<tr bgcolor=\"#F5F8F9\"><td colspan=\"2\"><table width=\"538\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\">\n"; 
			} else {
				echo "<tr bgcolor=\"#FFFFFF\"><td colspan=\"5\"><img src=\"img/leer.gif\" height=\"1\" width=\"1\"></td></tr>\n";
			}
			echo "<tr>\n";
			echo "<td width=\"6\" class=\"msglist\"><img src=\"img/leer.gif\" height=\"1\" width=\"6\" border=\"0\"></td>\n";
			echo "<td width=\"13\" class=\"msglist\">";
			echo "<img src=\"img/icons/small_".extimg($file_row["f_ext"])."\" border=\"0\"></td>\n";
			echo "<td width=\"504\" class=\"msglist\"><img src=\"img/leer.gif\" height=\"1\" width=\"5\">";
			//echo "<a href=\"fileinfo_public.php?fid=".$file_row["f_id"];
			echo "<a href=\"fileinfo.php?public&amp;fid=".$file_row["f_id"];
			echo "\" target=\"_blank\" onclick=\"flevPopupLink(this.href,'filedetail','scrollbars=yes,resizable=yes,width=500,height=400',1);return document.MM_returnValue;\">";
			echo $filename."</a>";
			//Wenn für das Public File keine Vorschau existiert und Extension passt
			/*
			if(isEmpty($file_row["f_thumb_preview"]) && is_ext_true(strtolower($file_row["f_ext"]))) {
				$file_makethumb	= "thumb=".$file_row["f_id"]."&ext=".$file_row["f_ext"]."&fcat=".$file_row["f_uid"];
				echo " <a href=\"include/inc_act/act_imagick.php?".$file_makethumb."\" ";
				echo " title=\"".$BL['be_ftptakeover_createthumb'].": ".$filename."\">";
				echo " <img src=\"img/button/create_thumbnail_small.gif\" border=\"0\"></a>";
			}
			*/
			echo "</td>\n";
			echo "<td width=\"15\" align=\"right\" class=\"msglist\">";
			echo "<a href=\"include/inc_act/act_download.php?pl=1&dl=".$file_row["f_id"].
				 "\" target=\"_blank\" title=\"".$BL['be_fprivfunc_dlfile'].": ".$filename."\" target=\"_blank\">".
				 "<img src=\"img/button/download_disc.gif\" border=\"0\"></a>";
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
					echo "<td width=\"6\"><img src=\"img/leer.gif\" height=\"1\" width=\"6\" border=\"0\"></td>\n";
					echo "<td width=\"13\"><img src=\"img/leer.gif\" height=\"1\" width=\"1\" border=\"0\"></td>\n<td width=\"";
					echo "504\"><img src=\"img/leer.gif\" height=\"1\" width=\"6\"><a href=\"fileinfo.php?public&amp;fid=";
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
				echo "<td width=\"6\"><img src=\"img/leer.gif\" height=\"1\" width=\"6\" border=\"0\"></td>\n";
				echo "<td width=\"13\"><img src=\"img/leer.gif\" height=\"1\" width=\"1\" border=\"0\"></td>\n<td width=\"";
				//echo "504\"><img src=\"img/leer.gif\" height=\"1\" width=\"6\"><a href=\"fileinfo_public.php?fid=";
				echo "504\"><img src=\"img/leer.gif\" height=\"1\" width=\"6\"><a href=\"fileinfo.php?public&amp;fid=";
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
			echo "<tr bgcolor=\"#F5F8F9\"><td colspan=\"2\"><img src=\"img/leer.gif\" height=\"1\" width=\"1\"></td></tr>\n"; //Abstand vor
		} else {
			echo "<tr><td colspan=\"2\">";
			echo "<img src=\"img/leer.gif\" width=\"1\" height=\"6\"><br /><span class=\"error\" style=\"font-weight: bold;\">";			
			echo "&nbsp;&nbsp;&nbsp;&nbsp;".$BL['be_fsearch_nonfound'];
			echo "</span><br /><img src=\"img/leer.gif\" width=\"1\" height=\"10\"></td></tr>\n";
		}
	} //Ende Liste Dateien
	
	echo "</table>\n"; //Ende Tabelle

} else {
	//kein gültiges Suchergebnis
	if(isset($search["string"])) {			
		echo "<img src=\"img/leer.gif\" width=\"1\" height=\"6\"><br /><span class=\"error\" style=\"font-weight: bold;\">";			
		echo "&nbsp;&nbsp;&nbsp;&nbsp;".$BL['be_fsearch_nonfound'];
		echo "</span><br /><img src=\"img/leer.gif\" width=\"1\" height=\"6\">";
	} else {
		echo "<img src=\"img/leer.gif\" width=\"1\" height=\"6\"><br />";			
		echo "&nbsp;&nbsp;&nbsp;&nbsp;".$BL['be_fsearch_fillin'];
		echo "<br /><img src=\"img/leer.gif\" width=\"1\" height=\"6\">";
	}
}
?>