<?php
/**
 * phpwcms content management system
 *
 * @author Oliver Georgi <og@phpwcms.org>
 * @copyright Copyright (c) 2002-2022, Oliver Georgi
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

//Wenn neues Verzeichnis angelegt werden soll

$dir_aktiv		= $phpwcms['set_file_active'];
$dir_public		= $phpwcms['set_file_active'];
$dir_newname	= '';
$dir_longinfo	= '';
$dir_gallery	= 0;
$dir_pid		= empty($_GET["mkdir"]) ? 0 : intval($_GET["mkdir"]);
$dir_sort		= 0;

//Auswerten des Formulars
if(isset($_POST["dir_aktion"]) && intval($_POST["dir_aktion"]) == 1) {
	$dir_pid 		= intval($_POST["dir_pid"]);
	$dir_aktiv		= empty($_POST["dir_aktiv"]) ? 0 : 1;
	$dir_public 	= empty($_POST["dir_public"]) ? 0 : 1;
	$dir_newname	= clean_slweg($_POST["dir_newname"]);
	$dir_longinfo	= clean_slweg($_POST["dir_longinfo"]);
	$dir_gallery	= empty($_POST["dir_gallery"]) ? 0 : intval($_POST["dir_gallery"]);
	$dir_sort		= intval($_POST["dir_sort"]);

	switch($dir_gallery) {

		case 2:
		case 3: break;

		default: $dir_gallery = 0;

	}

	if(str_empty($dir_newname)) $dir_error = 1;
	//Eintragen des neuen verzeichnisnamens
	if(!isset($dir_error)) {
		$sql =  "INSERT INTO ".DB_PREPEND."phpwcms_file (f_pid, f_uid, f_name, f_aktiv, f_public, ".
				"f_created, f_kid, f_longinfo, f_gallerystatus, f_sort) VALUES (".
				$dir_pid.", ".
				$_SESSION["wcs_user_id"].", '".
				aporeplace($dir_newname)."', ".
				$dir_aktiv.", ".
				$dir_public.", '".
				time()."', 0, '".aporeplace($dir_longinfo)."', ".$dir_gallery.", ".
				$dir_sort.")";
        $result = _dbQuery($sql, 'INSERT');
		if(!empty($result['INSERT_ID'])) {
			headerRedirect(PHPWCMS_URL.'phpwcms.php?'.get_token_get_string().'&do=files&f=0');
		}
	}
}
//Ende Auswerten Formular

//Wenn ID angegeben, dann -> oder aber Root Verzeichnis
if($dir_pid) {
	$sql  = "SELECT f.f_id, f.f_name, f.f_uid, u.usr_login FROM ".DB_PREPEND."phpwcms_file f ";
	$sql .= "LEFT JOIN ".DB_PREPEND."phpwcms_user u ON u.usr_id=f.f_uid WHERE f.f_id=".$dir_pid;
	if(empty($_SESSION["wcs_user_admin"])) {
		$sql .= " AND f.f_uid=".$_SESSION["wcs_user_id"];
	}
	$sql .= " AND f.f_trash=0 AND f.f_kid=0 LIMIT 1";
	$result = _dbQuery($sql);
	if(isset($result[0]['f_id'])) {
		$dir_parent_name	= html($result[0]['f_name']);
		$dir_pid			= intval($result[0]['f_id']);
		if($_SESSION["wcs_user_id"] != $result[0]['f_uid']) {
			$dir_parent_name .= ' (' . html($result[0]['usr_login']) . ')';
		}
	} else {
		$dir_parent_name = $BL['be_fpriv_rootdir'];
		$dir_pid		 = 0;
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
		<td><input name="dir_newname" type="text" class="width440 v12" id="dir_newname" value="<?php echo html($dir_newname) ?>" size="40" maxlength="250" /></td>
	</tr>
	<tr><td colspan="2" valign="top"><img src="img/leer.gif" alt="" width="1" height="1" /></td></tr>
	<tr>
		<td align="right" valign="top" class="v09 tdtop4"><?php echo $BL['be_ftptakeover_longinfo'] ?>:&nbsp;</td>
		<td valign="top"><textarea name="dir_longinfo" cols="40" rows="4" class="width440" id="dir_longinfo"><?php echo html($dir_longinfo) ?></textarea></td>
	</tr>

	<tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="5" /></td></tr>

	<tr>
		<td align="right" class="v09"><?php echo $BL['be_gallery'] ?>:&nbsp;</td>
		<td><select name="dir_gallery" id="dir_gallery">
			<option value="0"<?php is_selected(0, $dir_gallery) ?>>-</option>
			<option value="2"<?php is_selected(2, $dir_gallery) ?>><?php echo $BL['be_gallery_root'] ?></option>
			<option value="3"<?php is_selected(3, $dir_gallery) ?>><?php echo $BL['be_gallery_directory'] ?></option>
		</select></td>
	</tr>

	<tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="8"></td></tr>

	<tr>
		<td align="right" class="v09">&nbsp;<?php echo $BL['be_cnt_sorting'] ?>:&nbsp;</td>
		<td><input name="dir_sort" type="text" id="dir_sort" size="10" class="width50" maxlength="10" value="<?php echo intval($dir_sort) ?>" /></td>
	</tr>

	<tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="5" /></td></tr>


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
	<tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="5" /></td></tr>
	<tr>
		<td width="67" valign="top"><input name="dir_pid" type="hidden" id="dir_pid" value="<?php echo $dir_pid ?>" />
		<input name="dir_aktion" type="hidden" id="dir_aktion" value="1" /></td>
		<td>
			<input name="Submit" type="submit" class="button" value="<?php echo $BL['be_fpriv_button'] ?>" />
			<input type="button" class="button" value="<?php echo $BL['be_func_struct_close'] ?>" onclick="document.location.href='phpwcms.php?do=files&amp;f=0'" />
		</td>
	</tr>
	<tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="8" /></td></tr>
	<tr><td colspan="2" bgcolor="#9BBECA"><img src="img/leer.gif" alt="" width="1" height="4" /></td></tr>
</table>
</form>