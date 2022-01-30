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

?><form action="phpwcms.php?do=profile" method="post" name="formprofiledetail" id="formprofiledetail" autocomplete="off">
	<table border="0" cellpadding="0" cellspacing="0" summary="">
	<tr><td colspan="2" class="title"><?php echo $BL['be_profile_account_title'] ?></td></tr>
	<tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="5"></td></tr>
	<tr><td colspan="2"><?php echo $BL['be_profile_account_text'] ?></td></tr>
<?php
	//if error during login occurs
	if(!empty($err)) {
		echo '<tr valign="top"><td  align="right" class="error"><img src="img/leer.gif" width="1" height="10"><br /><strong>';
		echo $BL['be_profile_label_err'];
		echo ':</strong>&nbsp;</td><td class="error"><img src="img/leer.gif" width="1" height="10"><br />';
		echo nl2br(chop($err)).'</td></tr>';
	}
?>
	<tr>
		<td colspan="2"><img src="img/leer.gif" alt="" width="1" height="12"></td>
	</tr>
	<tr>
		<td align="right"><?php echo $BL['be_profile_label_username'] ?>:&nbsp;</td>
		<td><input name="form_loginname" type="text" id="form_loginname" class="v12b width250" size="30" maxlength="30" value="<?php echo html($_SESSION["wcs_user"]); ?>" autocomplete="off" /></td>
	</tr>
	<tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="2"></td></tr>
	<tr>
		<td align="right"><?php echo $BL['be_profile_label_newpass'] ?>:&nbsp;</td>
		<td><input name="form_password" type="password" id="form_password" class="v12b width250" size="30" maxlength="50" autocomplete="new-password" value="" /></td>
	</tr>
	<tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="2"></td></tr>
	<tr>
		<td align="right"><?php echo $BL['be_profile_label_repeatpass'] ?>:&nbsp;</td>
		<td><input name="form_password2" type="password" id="form_password2" class="v12b width250" size="30" maxlength="50" autocomplete="new-password" value="" /></td>
	</tr>
	<tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="2"></td></tr>
	<tr>
		<td align="right"><?php echo $BL['be_profile_label_email'] ?>:&nbsp;</td>
		<td><input name="form_useremail" type="text" id="form_useremail" class="v12b width250" value="<?php echo html($_SESSION["wcs_user_email"]); ?>" size="30" maxlength="150" autocomplete="off" /></td>
	</tr>

	<tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="10"></td></tr>

	<tr>
		<td align="right"><?php echo $BL['be_profile_label_lang'] ?>:&nbsp;</td>
		<td><select name="form_lang" id="form_lang" class="v12 width250">
<?php
	// check available languages installed and build language selector menu
	include_once PHPWCMS_ROOT."/include/inc_lang/code.lang.inc.php";
	$lang_dirs = opendir(PHPWCMS_ROOT."/include/inc_lang/backend");
	while($lang_codes = readdir( $lang_dirs )) {
		if( substr($lang_codes, 0, 1) !== '.' && file_exists(PHPWCMS_ROOT."/include/inc_lang/backend/".$lang_codes."/lang.inc.php")) {
			echo '<option value="'.$lang_codes.'"';
			if($lang_codes == $_SESSION["wcs_user_lang"]) {
				echo ' selected="selected"';
			}
			echo '>';
			echo (isset($BL[strtoupper($lang_codes)])) ? $BL[strtoupper($lang_codes)] : strtoupper($lang_codes);
			echo "</option>\n";
		}
	}
	closedir( $lang_dirs );

	$wysiwygTemplates['editor'] = empty($_SESSION["WYSIWYG_EDITOR"]) ? 0 : 1;
?>
        </select></td>
	</tr>
	<tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="10"></td></tr>
	<tr>
		<td align="right" valign="top" class="tdtop5"><?php echo $BL['be_WYSIWYG'] ?>:&nbsp;</td>
		<td class="checkbox-list"><label>
				<input type="checkbox" name="form_wysiwyg" value="1"<?php if(!empty($_SESSION["WYSIWYG_EDITOR"])): ?> checked="checked"<?php endif; ?> />
				<?php echo $BL['be_on']; ?> (CKEditor 4.x)
			</label>
			<input type="hidden" name="form_wysiwyg_toolbar" value="" />
		</td>
	</tr>

	<tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="10"></td></tr>

	<tr>
		<td align="right" valign="top" class="tdtop5 nowrap"><?php echo $BL['be_structform_select_cp'] ?>:&nbsp;</td>
		<td class="checkbox-list">
<?php
		$has_selected_cp	= isset($_SESSION["wcs_user_cp"]) ? count($_SESSION["wcs_user_cp"]) : 0;
		$has_allowed_cp		= isset($_SESSION["wcs_allowed_cp"]) ? count($_SESSION["wcs_allowed_cp"]) : 0;

		foreach($wcs_content_type as $key => $value):
			if($has_allowed_cp && !isset($_SESSION["wcs_allowed_cp"][$key])):
?>
			<label class="disabled">
				<input type="checkbox" disabled="disabled" /> <?php echo html($value) ?>
			</label>
<?php
				continue;
			endif;
?>
			<label>
				<input type="checkbox" name="profile_account_cp[<?php echo $key ?>]" value="<?php echo $key ?>"<?php if(!$has_selected_cp || isset($_SESSION["wcs_user_cp"][$key])): ?> checked="checked"<?php endif; ?> />
				<?php echo html($value) ?>
			</label>

<?php	endforeach;	?>
			<input type="hidden" name="profile_cp_total" value="<?php echo count($wcs_content_type) ?>" />
		</td>
	</tr>

	<tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="25"><input name="form_aktion" type="hidden" id="form_aktion" value="update_account"></td></tr>
	<tr>
		<td align="right">&nbsp;</td>
		<td><input type="submit" name="Submit" value="<?php echo $BL['be_profile_account_button'] ?>" class="button12"></td>
	</tr>
	<tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="20"></td></tr>
</table></form>