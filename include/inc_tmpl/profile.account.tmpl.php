<?php
/*************************************************************************************
   Copyright notice
   
   (c) 2002-2010 Oliver Georgi (oliver@phpwcms.de) // All rights reserved.
 
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
		<td><input name="form_loginname" type="text" id="form_loginname" class="v12b width250" size="30" maxlength="30" value="<?php echo html_specialchars($_SESSION["wcs_user"]); ?>"></td>
	</tr>
	<tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="2"></td></tr>
	<tr> 
		<td align="right"><?php echo $BL['be_profile_label_newpass'] ?>:&nbsp;</td>
		<td><input name="form_password" type="password" id="form_password" class="v12b width250" size="30" maxlength="20"></td>
	</tr>
	<tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="2"></td></tr>
	<tr> 
		<td align="right"><?php echo $BL['be_profile_label_repeatpass'] ?>:&nbsp;</td>
		<td><input name="form_password2" type="password" id="form_password2" class="v12b width250" size="30" maxlength="20"></td>
	</tr>
	<tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="2"></td></tr>
	<tr> 
		<td align="right"><?php echo $BL['be_profile_label_email'] ?>:&nbsp;</td>
		<td><input name="form_useremail" type="text" id="form_useremail" class="v12b width250" value="<?php echo html_specialchars($_SESSION["wcs_user_email"]); ?>" size="30" maxlength="150"></td>
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
	if( $lang_codes != "." && $lang_codes != ".." && file_exists(PHPWCMS_ROOT."/include/inc_lang/backend/".$lang_codes."/lang.inc.php")) {
		echo '<option value="'.$lang_codes.'"';
		echo ($lang_codes == $_SESSION["wcs_user_lang"]) ? " selected" : "";
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
		<td align="right" valign="top" style="padding-top: 3px;"><?php echo $BL['be_WYSIWYG'] ?>:&nbsp;</td>
		<td><select name="form_wysiwyg" id="form_wysiwyg" class="v12 width250" style="margin-bottom:2px;" onchange="setWYSIWYGtemplate();">
				<option value="0"<?php is_selected(0, $_SESSION["WYSIWYG_EDITOR"]) ?>><?php echo $BL['be_WYSIWYG_disabled'] ?></option>
				<option value="1"<?php is_selected(1, $_SESSION["WYSIWYG_EDITOR"]) ?>>CKEditor 3.2</option>
				<option value="2"<?php is_selected(2, $_SESSION["WYSIWYG_EDITOR"]) ?>>FCKeditor 2.6.6</option>
        </select>
		<div id="wysiwyg_template"><input type="hidden" name="form_wysiwyg_toolbar" value="" /></div>
		</td>
	</tr>

	<tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="25"><input name="form_aktion" type="hidden" id="form_aktion" value="update_account"></td>
	</tr>
	<tr> 
		<td align="right">&nbsp;</td>
		<td><input type="submit" name="Submit" value="<?php echo $BL['be_profile_account_button'] ?>" class="button12"></td>
	</tr>
	<tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="20"></td></tr>
</table></form>
<?php

// set templates for WYSIWYG editor
if(!empty($phpwcms['wysiwyg_template']['FCKeditor'])) {
	$wysiwygTemplates['FCKeditor'] = convertStringToArray($phpwcms['wysiwyg_template']['FCKeditor']);
}
if(empty($wysiwygTemplates['FCKeditor']) || count($wysiwygTemplates['FCKeditor']) == 0) {
	$wysiwygTemplates['FCKeditor'] = array('phpwcms_basic','phpwcms_default','Default','Basic');
}
if(!empty($phpwcms['wysiwyg_template']['CKEditor'])) {
	$wysiwygTemplates['CKEditor'] = convertStringToArray($phpwcms['wysiwyg_template']['CKEditor']);
}
if(empty($wysiwygTemplates['CKEditor']) || count($wysiwygTemplates['CKEditor']) == 0) {
	$wysiwygTemplates['CKEditor'] = array('Default','Basic');
}

$wysiwygTemplates['userTemplate']		= empty($_SESSION["WYSIWYG_TEMPLATE"]) ? '' : $_SESSION["WYSIWYG_TEMPLATE"];

// FCKeditor
$wysiwygTemplates['FCKeditor_options'] 	= '';
foreach($wysiwygTemplates['FCKeditor'] as $value) {
	$value = html_specialchars($value);
	$wysiwygTemplates['FCKeditor_options'] .= '	<option value="'.$value.'"';
	if($wysiwygTemplates['userTemplate'] == $value) {
		$wysiwygTemplates['FCKeditor_options'] .= ' selected="selected"';
	}
	$wysiwygTemplates['FCKeditor_options'] .= '>FCKeditor: '.$value.'<\'+\'/option>';
}

$wysiwygTemplates['FCKeditor_select']  = '<select name="form_wysiwyg_template" class="v12 width250">';
$wysiwygTemplates['FCKeditor_select'] .= $wysiwygTemplates['FCKeditor_options'];
$wysiwygTemplates['FCKeditor_select'] .= '<\'+\'/select>';

// CKEditor
$wysiwygTemplates['CKEditor_options'] 	= '';
foreach($wysiwygTemplates['CKEditor'] as $value) {
	$value = html_specialchars($value);
	$wysiwygTemplates['CKEditor_options'] .= '	<option value="'.$value.'"';
	if($wysiwygTemplates['userTemplate'] == $value) {
		$wysiwygTemplates['CKEditor_options'] .= ' selected="selected"';
	}
	$wysiwygTemplates['CKEditor_options'] .= '>CKEditor: '.$value.'<\'+\'/option>';
}

$wysiwygTemplates['CKEditor_select']  = '<select name="form_wysiwyg_template" class="v12 width250">';
$wysiwygTemplates['CKEditor_select'] .= $wysiwygTemplates['CKEditor_options'];
$wysiwygTemplates['CKEditor_select'] .= '<\'+\'/select>';

?>
<script language="javascript" type="text/javascript">
<!--
setWYSIWYGtemplate();

function setWYSIWYGtemplate() {
	var templateObj = document.getElementById('form_wysiwyg');
	var templateVal = templateObj.options[templateObj.selectedIndex].value;
	switch(templateVal) {
		case 2:
		case '2': 
			var baseVal = '<?php echo $wysiwygTemplates['FCKeditor_select'] ?>';
			break;
		case 1:
		case '1': 
			var baseVal = '<?php echo $wysiwygTemplates['CKEditor_select'] ?>';
			break;
		default:
			var baseVal = '<input type="hidden" name="form_wysiwyg_toolbar" value="" />';
	}
	document.getElementById('wysiwyg_template').innerHTML = baseVal;
}
//-->
</script>