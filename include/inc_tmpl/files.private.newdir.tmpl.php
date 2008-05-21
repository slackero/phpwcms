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


//Wenn neues Verzeichnis angelegt werden soll
//if(isset($_GET["mkdir"]) || intval($_POST["dir_aktion"])) {
	$dir_aktiv = 1;
	$dir_public = 0;
	$dir_newname = '';
	$dir_longinfo = '';
	$dir_pid = empty($_GET["mkdir"]) ? 0 : intval($_GET["mkdir"]);
				
	//Auswerten des Formulars
	if(isset($_POST["dir_aktion"]) && intval($_POST["dir_aktion"]) == 1) {
					$dir_pid 		= intval($_POST["dir_pid"]);
					$dir_aktiv		= intval($_POST["dir_aktiv"]);;
					$dir_public 	= intval($_POST["dir_public"]);;
					$dir_newname	= clean_slweg($_POST["dir_newname"]);
					$dir_longinfo	= clean_slweg($_POST["dir_longinfo"]);
					if(isEmpty($dir_newname)) $dir_error = 1;
					//Eintragen des neuen verzeichnisnamens
					if(!isset($dir_error)) {
						$sql =  "INSERT INTO ".DB_PREPEND."phpwcms_file (f_pid, f_uid, f_name, f_aktiv, f_public, ".
								"f_created, f_kid, f_longinfo) VALUES (".
								$dir_pid.", ".
								$_SESSION["wcs_user_id"].", '".
								aporeplace($dir_newname)."', ".
								$dir_aktiv.", ".
								$dir_public.", '".
								time()."', 0, '".aporeplace($dir_longinfo)."')";
						if($result = mysql_query($sql, $db) or die ("error while writing new dir info")) {
							headerRedirect(PHPWCMS_URL."phpwcms.php?do=files&f=0");
						}
					}
				}
				//Ende Auswerten Formular
				
				//Wenn ID angegeben, dann -> oder aber Root Verzeichnis
				if($dir_pid) {
					$sql = "SELECT f_id, f_name FROM ".DB_PREPEND."phpwcms_file WHERE f_id=".$dir_pid.
						   " AND f_uid=".$_SESSION["wcs_user_id"]." AND f_trash=0 AND f_kid=0 LIMIT 1";
					if($result = mysql_query($sql, $db) or die("error while reading parent folder name")) {
						if($row = mysql_fetch_row($result)) {
							$dir_parent_name	= html_specialchars($row[1]);
							$dir_pid			= intval($row[0]);
						}
					}
				} else {
					$dir_parent_name = $BL['be_fpriv_rootdir'];
					$dir_pid		 = 0;
				}
				
		
?>
<form action="phpwcms.php?do=files&amp;f=0" method="post" name="createnewdir" id="createnewdir">
<table width="538" border="0" cellpadding="0" cellspacing="0" bgcolor='#EBF2F4' summary="">
	<tr>
		<td width="67" rowspan="2" valign="top"><a href="phpwcms.php?do=files&amp;f=0"><img src="img/button/close_reiter.gif" alt="" width="45" height="12" border="0" /></a></td>
		<td width="471"><img src="img/leer.gif" alt="" width="1" height="6" /></td>
	</tr>
	<tr><td class="title"><?php echo $BL['be_fpriv_title'] ?></td></tr>
	<tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="5" /></td></tr>
	<tr>
		<td width="67" align="right" class="v09"><?php echo $BL['be_fpriv_inside'] ?>:&nbsp;</td>
		<td class="v10"><strong><?php echo $dir_parent_name ?></strong></td>
	</tr>
	<tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="6" /></td></tr>
	<tr><td colspan="2"><img src="img/lines/line-bluelight.gif" alt="" width="538" height="1" /></td></tr>
	<tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="6" /></td></tr>
	<?php if(isset($dir_error)) { ?>
	<tr>
	  <td><img src="img/leer.gif" alt="" width="1" height="1" /></td>
	  <td class="v10"><strong style="color:#FF3300;"><?php echo $BL['be_fpriv_error'] ?></strong></td>
	</tr>
	<tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="2" /></td></tr>
	<?php } ?>
	<tr>
		<td align="right" class="v09"><?php echo $BL['be_fpriv_name'] ?>:&nbsp;</td>
		<td><input name="dir_newname" type="text" class="f10b" style="width: 450px;" id="dir_newname" value="<?php echo html_specialchars($dir_newname) ?>" size="40" maxlength="250" /></td>
	</tr>
	<tr><td colspan="2" valign="top"><img src="img/leer.gif" alt="" width="1" height="1" /></td></tr>
	<tr>
		<td align="right" valign="top" class="v09 tdtop4"><?php echo $BL['be_ftptakeover_longinfo'] ?>:&nbsp;</td>
		<td valign="top"><textarea name="dir_longinfo" cols="40" rows="6" class="v10" id="dir_longinfo" style="width: 450px;"><?php echo html_specialchars($dir_longinfo) ?></textarea></td>
	</tr>	
	<tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="3"></td></tr>
	<tr>
		<td align="right" class="v09"><?php echo $BL['be_fpriv_status'] ?>:&nbsp;</td>
		<td><table border="0" cellpadding="0" cellspacing="0" summary="">
		<tr>
			<td><input name="dir_aktiv" type="checkbox" id="dir_aktiv" value="1"<?php is_checked("1", $dir_aktiv) ?> /></td>
			<td class="v10"><strong><label for="dir_aktiv"><?php echo $BL['be_ftptakeover_active'] ?></label></strong>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
			<td><input name="dir_public" type="checkbox" id="dir_public" value="1"<?php is_checked("1", $dir_public) ?> /></td>
			<td class="v10"><strong><label for="dir_public"><?php echo $BL['be_ftptakeover_public'] ?></label></strong></td>
		</tr>
		</table></td>
	</tr>
	<tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="3" /></td></tr>
	<tr>
		<td width="67" valign="top"><input name="dir_pid" type="hidden" id="dir_pid" value="<?php echo $dir_pid ?>" />
		<input name="dir_aktion" type="hidden" id="dir_aktion" value="1" /></td>
		<td><input name="Submit" type="submit" class="button10" value="<?php echo $BL['be_fpriv_button'] ?>" /></td>
	</tr>
	<tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="8" /></td></tr>
	<tr><td colspan="2" bgcolor="#9BBECA"><img src="img/leer.gif" alt="" width="1" height="4" /></td></tr>
</table>
</form>