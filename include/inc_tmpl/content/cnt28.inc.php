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


// frontend login


if(!isset($content['felogin']['felogin_cookie_expire'])) {
	$content['felogin']['felogin_cookie_expire'] = 2592000;
}
if(empty($content['felogin']['felogin_date_format'])) {
	$content['felogin']['felogin_date_format']	= '%m/%d/%y';
}
if(empty($content['felogin']['felogin_locale'])) {
	$content['felogin']['felogin_locale']	= '';
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
	$content['felogin']['felogin_profile_manage']	= 1;
}
if(!isset($content['felogin']['felogin_reminder_subject'])) {
	$content['felogin']['felogin_reminder_subject']	= '';
}
if(!isset($content['felogin']['felogin_reminder_body'])) {
	$content['felogin']['felogin_reminder_body']	= '';
}


?>
<tr>
	<td align="right" class="chatlist"><?php echo $BL['be_admin_struct_template'] ?>:&nbsp;</td>
	<td><select name="template" id="template" class="f11b">
<?php

// templates for frontend login
$tmpllist = get_tmpl_files(PHPWCMS_TEMPLATE.'inc_cntpart/felogin');
if(is_array($tmpllist) && count($tmpllist)) {
	foreach($tmpllist as $val) {
		$selected_val = (isset($content['felogin_template']) && $val == $content['felogin_template']) ? ' selected="selected"' : '';
		$val = html_specialchars($val);
		echo '	<option value="' . $val . '"' . $selected_val . '>' . $val . '</option>' . LF;
	}
}

?>				  
		</select></td>
</tr>

<tr><td colspan="2" class="rowspacer7x7"></td></tr>

<tr>
  <td align="right" class="chatlist tdtop3"><?php echo $BL['be_settings'] ?>:&nbsp;</td>
  <td><table border="0" cellpadding="0" cellspacing="0" summary="">
	<tr>
		<td align="right" class="chatlist"><?php echo $BL['be_cookie_runtime'] ?>:&nbsp;</td>
		<td><input name="cookie_expire" type="text" class="f11b width150" id="cookie_expire" size="10" maxlength="10" onkeyup="if(!parseInt(this.value*1))this.value='0';" value="<?php echo $content['felogin']['felogin_cookie_expire']; ?>" /></td>
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

	
	</table></td>
</tr>

<tr><td colspan="2" class="rowspacer7x0"></td></tr>


