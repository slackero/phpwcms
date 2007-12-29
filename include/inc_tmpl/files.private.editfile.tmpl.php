<?php
/*************************************************************************************
   Copyright notice
   
   (c) 2002-2007 Oliver Georgi (oliver@phpwcms.de) // All rights reserved.
 
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



//Wenn neue Datei hochgeladen werden soll
$file_id = isset($_GET["editfile"]) ? intval($_GET["editfile"]) : 0;
$ja = 0;
$othumb = false;
				
//Auswerten des Formulars
if(isset($_POST["file_aktion"]) && intval($_POST["file_aktion"]) == 2) {
	$file_id		= intval($_POST["file_id"]);
	$file_pid 		= intval($_POST["file_pid"]);
	$file_aktiv		= intval($_POST["file_aktiv"]);
	$file_public 	= intval($_POST["file_public"]);
	$file_name		= clean_slweg($_POST["file_name"]);
	$file_ext		= clean_slweg($_POST["file_ext"]);
	$file_shortinfo	= clean_slweg($_POST["file_shortinfo"]);
	$file_longinfo	= slweg(trim($_POST["file_longinfo"]));
	
	$file_keywords	= $_POST["file_keywords"];
	if(count($file_keywords)) {
		$file_keys = "";
		foreach($file_keywords as $key => $value) {
			unset($file_keywords[$key]);
			$key = intval($key);
			if($value != "0_1") {
				$file_keys .= (($file_keys) ? ":" : "").$key."_".intval($value);
				$file_keywords[$key] = intval($value);
			} else {
				$file_error["keywords"][$key] = 1;
			}		
		}
	}
	
	//if(isEmpty($file_shortinfo)) $file_error["shortinfo"] = 1;
	if(isEmpty($file_name)) {
		$file_error["name"] = 1;
	} else {
		//Wenn Dateiname keine Erweiterung hat, dann Extension anhängen
		if(trim(strtolower(FileExtension($file_name))) != trim($file_ext)) $file_name .= ".".$file_ext;
	}
	//Eintragen der aktualisierten Verzeichnisinfos
	if(!isset($file_error)) {
		$sql =  "UPDATE ".DB_PREPEND."phpwcms_file SET ".
				"f_name='".aporeplace($file_name)."', ".
				"f_pid=".$file_pid.", ".
				"f_aktiv=".$file_aktiv.", ".
				"f_public=".$file_public.", ".
				"f_shortinfo='".aporeplace($file_shortinfo)."', ".
				"f_longinfo='".aporeplace($file_longinfo)."', ".
				"f_keywords='".$file_keys."', ".
				"f_created=CONCAT_WS('|', f_changed, '".time()."') ".
				"WHERE f_kid=1 AND f_id=".$file_id." AND f_uid=".$_SESSION["wcs_user_id"];
		if($result = mysql_query($sql, $db) or die ("error while updating file info")) {
			headerRedirect(PHPWCMS_URL."phpwcms.php?do=files&f=0");
		}
	}	
}
//Ende Auswerten Formular

//Wenn ID angegeben, dann -> oder aber Root Verzeichnis
if($file_id) {
	$sql = "SELECT * FROM ".DB_PREPEND."phpwcms_file WHERE f_id=".$file_id.
		   " AND f_uid=".$_SESSION["wcs_user_id"]." AND f_trash=0 AND f_kid=1 LIMIT 1;";
	if($result = mysql_query($sql, $db) or die("error while reading file information")) {
		if($row = mysql_fetch_array($result)) {
			$file_oldname	= html_specialchars($row["f_name"]);
			$file_created	= intval($row["f_created"]);
			$file_size		= intval($row["f_size"]);
			$file_id		= $row["f_id"];
			$file_ext		= $row["f_ext"];
			//$file_makethumb	= "thumb=".$row["f_id"]."&ext=".$row["f_ext"];
			if(empty($_POST["file_aktion"]) || intval($_POST["file_aktion"]) != 2) {
				$file_pid		= $row["f_pid"];
				$file_name		= $row["f_name"];
				$file_aktiv		= $row["f_aktiv"];
				$file_public	= $row["f_public"];
				$file_shortinfo = $row["f_shortinfo"];
				$file_longinfo	= $row["f_longinfo"];
				$file_thumb		= $row["f_thumb_list"];
				$file_keys		= $row["f_keywords"];
				
				if($file_keys) {
					$file_keys_temp = explode(":", $file_keys);
					if(count($file_keys_temp)) {
						if(isset($file_keywords)) unset($file_keywords);
						foreach($file_keys_temp as $value) {
							list($k1, $k2) = explode("_", $value);
							$file_keywords[intval($k1)] = intval($k2);
						}
					}
				}
			}
			
			if(isset($row["f_hash"])) {
				$thumb_image = get_cached_image(
						array(	"target_ext"	=>	$row["f_ext"],
								"image_name"	=>	$row["f_hash"] . '.' . $row["f_ext"],
								"thumb_name"	=>	md5($row["f_hash"].$phpwcms["img_list_width"].$phpwcms["img_list_height"].$phpwcms["sharpen_level"])
        					  )
							);

				if($thumb_image != false) {
					$file_thumb_small = '<img src="'.PHPWCMS_IMAGES . $thumb_image[0] .'" border="0" '.$thumb_image[3].'>';
					$othumb = true;
				}
			}
			
			
			$ja = 1;
		}
		mysql_free_result($result);
	}
}

if(!$othumb) {
	$file_thumb_small = '<img src="img/icons/small_'.extimg($file_ext).'" border="0">';
}
				
if($ja) {
?><form action="phpwcms.php?do=files&f=0" method="post" enctype="multipart/form-data" name="editfileinfo"><table width="538" border="0" cellpadding="0" cellspacing="0" bgcolor='#EBF2F4' summary="">
	<tr>
		<td width="67" rowspan="2" valign="top"><a href="phpwcms.php?do=files&f=0"><img src="img/button/close_reiter.gif" alt="" width="45" height="12" border="0"></a></td>
		<td width="471"><img src="img/leer.gif" alt="" width="1" height="6"></td>
	</tr>
	<tr><td class="title"><?php echo $BL['be_fprivedit_title'] ?></td></tr>
	<tr>
	  <td colspan="2" valign="top"><img src="img/leer.gif" alt="" width="1" height="5"></td>
	  </tr>
	<tr>
      <td align="right" class="v09"><?php echo $BL['be_fprivedit_filename'] ?>:&nbsp;</td>
      <td class="v10"><table border="0" cellpadding="0" cellspacing="0" summary="">
        <tr>
          <td><?php echo $file_thumb_small ?></td>
          <td><img src="img/leer.gif" alt="" width="4" height="1"></td>
          <td><strong><?php echo $file_oldname ?></strong></td>
        </tr>
      </table></td>
	  </tr>
	<tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="2"></td></tr>
	<tr>
      <td><img src="img/leer.gif" alt="" width="1" height="1"></td>
      <td class="v09"><?php 
	  	echo $BL['be_fprivedit_created'].': '.date($BL['be_fprivedit_dateformat'], $file_created);
		echo '&nbsp;&nbsp;&nbsp;&nbsp;'.$BL['be_fprivedit_size'].': '.fsizelong($file_size);
		echo '&nbsp;&nbsp;&nbsp;&nbsp;EXT: '.strtoupper($file_ext); 
	  ?></td>
	</tr>
	<tr><td colspan="2" valign="top"><img src="img/leer.gif" alt="" width="1" height="5"></td></tr>
	<tr>
		<td width="67" align="right" class="v09"><?php echo $BL['be_ftptakeover_directory'] ?>:&nbsp;</td>
		<td class="v10"><select name="file_pid" id="file_pid" class="f10b" style="width: 450px;">
		<option value="0" <?php if($file_pid == 0) echo "selected"; ?>><?php echo $BL['be_ftptakeover_rootdir'] ?></option>
<?php dir_menu(0, $file_pid, $db, "+", $_SESSION["wcs_user_id"], "+") ?>
	</select></td>
	</tr>
	<tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="6"></td></tr>
	<tr><td colspan="2"><img src="img/lines/line-bluelight.gif" alt="" width="538" height="1"></td></tr>
	<tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="6"></td></tr>
	<?php if(isset($file_error["name"])) { 
			$file_name = $file_oldname;
	?>
	<tr>
	  <td align="right" class="v09"><img src="img/leer.gif" alt="" width="1" height="1"></td>
	  <td class="v10"><strong style="color:#FF3300"><?php echo $BL['be_fprivedit_err1'] ?></strong></td>
	</tr>
	<tr><td colspan="2" valign="top"><img src="img/leer.gif" alt="" width="1" height="2"></td></tr>
	<?php } ?>
	<tr>
      <td align="right" class="v09"><?php echo $BL['be_fpriv_name'] ?>:&nbsp;</td>
      <td><input name="file_name" type="text" class="f10b" style="width: 450px;" id="file_name" value="<?php echo html_specialchars($file_name) ?>" size="40" maxlength="230"></td>
	</tr>
	<tr><td colspan="2" valign="top"><img src="img/leer.gif" alt="" width="1" height="6"></td></tr>
	<tr><td colspan="2" valign="top"><img src="img/lines/line-bluelight.gif" alt="" width="538" height="1"></td></tr>
	<tr bgcolor="#F5F8F9"><td colspan="2" valign="top"><img src="img/leer.gif" alt="" width="1" height="6"></td></tr>
	<?php 
	
	//Auswahlliste vordefinierte Keywörter
	$sql = "SELECT * FROM ".DB_PREPEND."phpwcms_filecat WHERE fcat_deleted=0 ORDER BY fcat_name;";
	if($result = mysql_query($sql, $db) or die("error while browsing file categories for selecting keywords")) {
		$k = "";
		while($row = mysql_fetch_array($result)) {
			if(get_filecat_childcount ($row["fcat_id"], $db)) {
			
				$ke = isset($file_error["keywords"][$row["fcat_id"]]) ? '<img src="img/symbole/error.gif" width="8" height="9" alt="" />&nbsp;' : '';
				$k .= "<tr>\n<td class=\"f10b\">".$ke.html_specialchars($row["fcat_name"]).":&nbsp;</td>\n";
				$k .= "<td><select name=\"file_keywords[".$row["fcat_id"]."]\" class=\"f10b\" style=\"width: 350px;\">\n";
				$k .= "<option value=\"".(($row["fcat_needed"])?"0_".$row["fcat_needed"]."\">".$BL['be_ftptakeover_needed']:'0">'.$BL['be_ftptakeover_optional'])."</option>\n";
				
				$ksql = "SELECT * FROM ".DB_PREPEND."phpwcms_filekey WHERE fkey_deleted=0 AND fkey_cid=".$row["fcat_id"]." ORDER BY fkey_name;";
				if($kresult = mysql_query($ksql, $db) or die("error while listing file keywords")) {
					while($krow = mysql_fetch_array($kresult)) {
						$k .= "<option value=\"".$krow["fkey_id"]."\"";
						$k .= isset($file_keywords[$row["fcat_id"]]) && $file_keywords[$row["fcat_id"]] == $krow["fkey_id"] ? " selected" : "";
						$k .= ">".html_specialchars($krow["fkey_name"])."</option>\n";
					}
					mysql_free_result($kresult);
				}
				
				$k .= "</select></td>\n</tr>\n";
				$k .= "<tr>\n<td colspan=\"2\"><img src=\"img/leer.gif\" width=\"1\" height=\"2\"></td>\n</tr>\n";			
			
			}
		}
		mysql_free_result($result);
	}

	?>
	<tr bgcolor="#F5F8F9">
		<td align="right" valign="top" class="v09"><img src="img/leer.gif" alt="" width="1" height="13"><?php echo $BL['be_ftptakeover_keywords'] ?>:&nbsp;</td>
		<td><table border="0" cellpadding="0" cellspacing="0" summary="">
		<?php if($k) echo $k; ?>
		<tr>
			<td class="f10b"><?php echo $BL['be_ftptakeover_additional'] ?>:&nbsp;</td>
			<td><input name="file_shortinfo" type="text" class="f10b" style="width: 350px;" id="file_shortinfo" value="<?php echo html_specialchars($file_shortinfo) ?>" size="40" maxlength="250"></td>
		</tr>		
		</table></td>
	</tr>
	<tr bgcolor="#F5F8F9"><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="6"></td></tr>
	<tr><td colspan="2"><img src="img/lines/line-bluelight.gif" alt="" width="538" height="1"></td></tr>
	<tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="6"></td></tr>
	<tr>
		<td align="right" valign="top" class="v09"><img src="img/leer.gif" alt="" width="1" height="13"><?php echo $BL['be_ftptakeover_longinfo'] ?>:&nbsp;</td>
		<td valign="top"><textarea name="file_longinfo" cols="40" rows="6" class="v10" id="file_longinfo" style="width: 450px;"><?php echo html_specialchars($file_longinfo) ?></textarea></td>
	</tr>	
	<tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="3"></td></tr>
	<tr>
		<td align="right" class="v09"><?php echo $BL['be_ftptakeover_status'] ?>:&nbsp;</td>
		<td><table border="0" cellpadding="0" cellspacing="0" summary="">
		<tr>
			<td><input name="file_aktiv" type="checkbox" id="file_aktiv" value="1"<?php is_checked("1", $file_aktiv) ?>></td>
			<td class="v10"><strong><label for="file_aktiv"><?php echo $BL['be_ftptakeover_active'] ?></label></strong>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
			<td><input name="file_public" type="checkbox" id="file_public" value="1"<?php is_checked("1", $file_public) ?>></td>
			<td class="v10"><strong><label for="file_public"><?php echo $BL['be_ftptakeover_public'] ?></label></strong></td>
		</tr>
		</table></td>
	</tr>
	<tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="3"></td></tr>
	<tr>
		<td width="67" valign="top"><input name="file_id" type="hidden" id="file_id" value="<?php echo $file_id ?>"><input name="file_aktion" type="hidden" id="file_aktion" value="2"><input name="file_ext" type="hidden" id="file_ext" value="<?php echo strtolower($file_ext) ?>"></td>
		<td><input name="Submit" type="submit" class="button10" value="<?php echo $BL['be_fprivedit_button'] ?>"></td>
	</tr>
	<tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="8"></td></tr>
	<tr><td colspan="2" bgcolor="#9BBECA"><img src="img/leer.gif" alt="" width="1" height="4"></td></tr>
</table> </form><?php 
} 

?>