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

// frontend login

if(!isset($content['felogin']['felogin_cookie_expire'])) {
	$content['felogin']['felogin_cookie_expire'] = 2592000;
}
if(empty($content['felogin']['felogin_date_format'])) {
	$content['felogin']['felogin_date_format'] = '%m/%d/%y';
}
if(empty($content['felogin']['felogin_locale'])) {
	$content['felogin']['felogin_locale'] = '';
}
if(!isset($content['felogin']['felogin_validate_backenduser'])) {
	$content['felogin']['felogin_validate_backenduser']	= 1;
}
if(!isset($content['felogin']['felogin_validate_userdetail'])) {
	$content['felogin']['felogin_validate_userdetail']	= 1;
}
if(!isset($content['felogin']['felogin_profile_registration'])) {
	$content['felogin']['felogin_profile_registration']	= 1;
}
if(!isset($content['felogin']['felogin_profile_manage'])) {
	$content['felogin']['felogin_profile_manage'] = 1;
}
if(!isset($content['felogin']['felogin_reminder_subject'])) {
	$content['felogin']['felogin_reminder_subject']	= '';
}
if(!isset($content['felogin']['felogin_reminder_body'])) {
	$content['felogin']['felogin_reminder_body'] = '';
}
if(!isset($content['felogin']['felogin_profile_manage_redirect'])) {
	$content['felogin']['felogin_profile_manage_redirect']= '';
}
if(!isset($content['felogin']['felogin_accept_email_login'])) {
    $content['felogin']['felogin_accept_email_login'] = 0;
}

?>
<tr><td colspan="2" class="rowspacer0x7"><img src="img/leer.gif" alt="" width="1" height="1"></td></tr>

<tr>
	<td align="right" class="chatlist"><?php echo $BL['be_admin_struct_template'] ?>:&nbsp;</td>
	<td><select name="template" id="template">
<?php

// templates for frontend login
$tmpllist = get_tmpl_files(PHPWCMS_TEMPLATE.'inc_cntpart/felogin');
if(is_array($tmpllist) && count($tmpllist)) {
	foreach($tmpllist as $val) {
		$selected_val = (isset($content['felogin_template']) && $val == $content['felogin_template']) ? ' selected="selected"' : '';
		$val = html($val);
		echo '	<option value="' . $val . '"' . $selected_val . '>' . $val . '</option>' . LF;
	}
}

?>
		</select></td>
</tr>

<tr><td colspan="2" class="rowspacer7x7"><img src="img/leer.gif" alt="" width="1" height="1" /></td></tr>

<tr>
  <td align="right" class="chatlist tdtop3"><?php echo $BL['be_settings'] ?>:&nbsp;</td>
  <td><table border="0" cellpadding="0" cellspacing="0" summary="">
	<tr>
		<td align="right" class="chatlist"><?php echo $BL['be_cookie_runtime'] ?>:&nbsp;</td>
		<td><input name="cookie_expire" type="text" class="f11b width150" id="cookie_expire" size="10" maxlength="10" onkeyup="if(!parseInt(this.value,10))this.value='0';" value="<?php echo $content['felogin']['felogin_cookie_expire']; ?>" /></td>
		<td class="f10">&nbsp;<?php echo $BL['be_cnt_guestbook_seconds'] ?></td>
	</tr>
	<tr><td colspan="3"><img src="img/leer.gif" alt="" width="1" height="3" /></td></tr>
	<tr>
		<td align="right" class="chatlist"><?php echo $BL['be_date_format'] ?>:&nbsp;</td>
		<td><input name="date_format" type="text" class="f11b width150" id="date_format" value="<?php echo $content['felogin']['felogin_date_format']; ?>" /></td>
		<td>&nbsp;<a href="http://www.php.net/strftime" target="_blank" title="PHP strftime"><img src="img/famfamfam/icon_info.gif" alt="Info" border="0" align="absmiddle" /></a></td>
	</tr>
	<tr><td colspan="3"><img src="img/leer.gif" alt="" width="1" height="3" /></td></tr>
	<tr>
		<td align="right" class="chatlist"><?php echo $BL['be_locale'] ?>:&nbsp;</td>
		<td><input name="locale" type="text" class="f11b width150" id="locale" value="<?php echo $content['felogin']['felogin_locale']; ?>" /></td>
		<td>&nbsp;<a href="http://www.php.net/setlocale" target="_blank" title="PHP setlocale"><img src="img/famfamfam/icon_info.gif" alt="Info" border="0" align="absmiddle" /></a> (en, de_DE)</td>
	</tr>
	<tr><td colspan="3"><img src="img/leer.gif" alt="" width="1" height="6" /></td></tr>

	<tr>
		<td align="right" class="chatlist tdtop4"><?php echo $BL['be_check_login_against'] ?>:&nbsp;</td>
		<td colspan="2" class="inlineCheckbox"><input type="checkbox" name="validate_userdetail" id="validate_userdetail" value="1"<?php echo is_checked(1, $content['felogin']['felogin_validate_userdetail']); ?> />
		<label for="validate_userdetail"><?php echo $BL['be_userprofile_db'] ?></label></td>
	</tr>
	<tr>
		<td>&nbsp;</td>
		<td colspan="2" class="inlineCheckbox"><input type="checkbox" name="validate_backenduser" id="validate_backenduser" value="1"<?php echo is_checked(1, $content['felogin']['felogin_validate_backenduser']); ?> />
		<label for="validate_backenduser"><?php echo $BL['be_backenduser_db']?></label></td>
	</tr>
    <tr>
		<td>&nbsp;</td>
		<td colspan="2" class="inlineCheckbox"><input type="checkbox" name="accept_email_login" id="accept_email_login" value="1"<?php echo is_checked(1, $content['felogin']['felogin_accept_email_login']); ?> />
		<label for="accept_email_login"><?php echo $BL['be_check_login_allow_email']; ?></label></td>
	</tr>

	 <tr><td colspan="3"><img src="img/leer.gif" alt="" width="1" height="6" /></td></tr>

	<tr>
		<td align="right" class="chatlist tdtop4 right"><?php echo $BL['be_check_feuser_profile'] ?>:&nbsp;</td>
		<td colspan="2" class="inlineCheckbox"><input type="checkbox" name="profile_registration" id="profile_registration" value="1"<?php echo is_checked(1, $content['felogin']['felogin_profile_registration']); ?> />
		<label for="profile_registration"><?php echo $BL['be_check_feuser_registration'] ?></label></td>
	  </tr>
	 <tr>
		<td align="right" class="chatlist">&nbsp;</td>
		<td colspan="2" class="inlineCheckbox"><input type="checkbox" name="profile_manage" id="profile_manage" value="1"<?php echo is_checked(1, $content['felogin']['felogin_profile_manage']); ?> />
		<label for="profile_manage"><?php echo $BL['be_check_feuser_manage'] ?></label></td>
	  </tr>
	<tr>
		<td align="right" class="chatlist"><?php //echo $BL['be_article_aredirect']: ?>&nbsp;</td>
		<td><input name="profile_manage_redirect" type="text" class="f11b width150" id="profile_manage_redirect" size="10" value="<?php echo html($content['felogin']['felogin_profile_manage_redirect']); ?>" /></td>
		<td class="f10">&nbsp;<?php
			echo $BL['be_alias'], ', aid=ID', ', id=ID' ?></td>
	</tr>

	</table></td>
</tr>
