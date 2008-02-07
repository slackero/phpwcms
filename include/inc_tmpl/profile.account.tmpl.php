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


?><form action="phpwcms.php?do=profile" method="post" name="formprofiledetail" id="formprofiledetail"><table border="0" cellpadding="0" cellspacing="0" summary="">
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
		<td><input name="form_loginname" type="text" id="form_loginname" class="f11b" style="width:250px;" size="30" maxlength="30" value="<?php echo html_specialchars($_SESSION["wcs_user"]); ?>"></td>
	</tr>
	<tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="2"></td></tr>
	<tr> 
		<td align="right"><?php echo $BL['be_profile_label_newpass'] ?>:&nbsp;</td>
		<td><input name="form_password" type="password" id="form_password" class="f11b" style="width:250px;" size="30" maxlength="20"></td>
	</tr>
	<tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="2"></td></tr>
	<tr> 
		<td align="right"><?php echo $BL['be_profile_label_repeatpass'] ?>:&nbsp;</td>
		<td><input name="form_password2" type="password" id="form_password2" class="f11b" style="width:250px;" size="30" maxlength="20"></td>
	</tr>
	<tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="2"></td></tr>
	<tr> 
		<td align="right"><?php echo $BL['be_profile_label_email'] ?>:&nbsp;</td>
		<td><input name="form_useremail" type="text" id="form_useremail" class="f11b" style="width:250px;" value="<?php echo html_specialchars($_SESSION["wcs_user_email"]); ?>" size="30" maxlength="150"></td>
	</tr>
	
	<tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="10"></td></tr>
	
	<tr> 
		<td align="right"><?php echo $BL['be_profile_label_lang'] ?>:&nbsp;</td>
		<td><select name="form_lang" id="form_lang" class="f11" style="width:250px;">
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


?>
        </select></td>
	</tr>
	<tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="10"></td></tr>
	<tr> 
		<td align="right" valign="top" style="padding-top: 3px;"><?php echo $BL['be_WYSIWYG'] ?>:&nbsp;</td>
		<td><select name="form_wysiwyg" id="form_wysiwyg" class="f11" style="width:250px;margin-bottom:2px;" onchange="setWYSIWYGtemplate();">
				<option value="0"<?php if(empty($_SESSION["WYSIWYG_EDITOR"])) echo ' selected'; ?>><?php echo $BL['be_WYSIWYG_disabled'] ?></option>
				<option value="1"<?php if(!empty($_SESSION["WYSIWYG_EDITOR"]) && $_SESSION["WYSIWYG_EDITOR"] == 1) echo ' selected="selected"'; ?>>SPAW2 (IE/Gecko/Opera9)</option>
				<option value="2"<?php if(!empty($_SESSION["WYSIWYG_EDITOR"]) && $_SESSION["WYSIWYG_EDITOR"] > 1  && $_SESSION["WYSIWYG_EDITOR"] < 4) echo ' selected="selected"'; ?>>FCKeditor (IE/Gecko)</option>
				<!-- <option value="4"<?php if(!empty($_SESSION["WYSIWYG_EDITOR"]) && $_SESSION["WYSIWYG_EDITOR"] == 4) echo ' selected="selected"'; ?>>SPAW (IE/Gecko)</option> -->
        </select>
		<div id="wysiwyg_template">
		<input type="hidden" name="form_wysiwyg_toolbar" value="" />
		</div>
		</td>
	</tr>

	<tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="25"><input name="form_aktion" type="hidden" id="form_aktion" value="update_account"></td>
	</tr>
	<tr> 
		<td align="right">&nbsp;</td>
		<td><input type="submit" name="Submit" value="<?php echo $BL['be_profile_account_button'] ?>" class="button10"></td>
	</tr>
	<tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="20"></td></tr>
</table></form>
<?php

// set correct templates for WYSIWYG editor
if(!empty($phpwcms['wysiwyg_template']['SPAW2'])) {
	$wysiwygTemplates['SPAW2'] = convertStringToArray($phpwcms['wysiwyg_template']['SPAW2']);
} elseif(empty($wysiwygTemplates['SPAW2']) || !count($wysiwygTemplates['SPAW2'])) {
	$wysiwygTemplates['SPAW2'] = array('toolbarset_standard','toolbarset_all','toolbarset_mini');
}
if(!empty($phpwcms['wysiwyg_template']['SPAW'])) {
	$wysiwygTemplates['SPAW'] = convertStringToArray($phpwcms['wysiwyg_template']['SPAW']);
} elseif(empty($wysiwygTemplates['SPAW']) || !count($wysiwygTemplates['SPAW'])) {
	$wysiwygTemplates['SPAW'] = array('default','mini','full','sidetable','intlink');
}
if(!empty($phpwcms['wysiwyg_template']['FCKeditor'])) {
	$wysiwygTemplates['FCKeditor'] = convertStringToArray($phpwcms['wysiwyg_template']['FCKeditor']);
} elseif(empty($wysiwygTemplates['FCKeditor']) || !count($wysiwygTemplates['FCKeditor'])) {
	$wysiwygTemplates['FCKeditor'] = array('phpwcms_basic','phpwcms_default','Default','Basic');
}

$wysiwygTemplates['SPAW_options']		= '';
$wysiwygTemplates['SPAW2_options']		= '';
$wysiwygTemplates['FCKeditor_options'] 	= '';
$wysiwygTemplates['userTemplate']		= empty($_SESSION["WYSIWYG_TEMPLATE"]) ? '' : $_SESSION["WYSIWYG_TEMPLATE"];

if(empty($_SESSION["WYSIWYG_EDITOR"])) {
	$wysiwygTemplates['editor'] = 0;
} elseif($_SESSION["WYSIWYG_EDITOR"] == 1) {	//SPAW2
	$wysiwygTemplates['editor'] = 1;		
} elseif($_SESSION["WYSIWYG_EDITOR"] < 4) {		//FCKeditor
	$wysiwygTemplates['editor'] = 2;
} elseif($_SESSION["WYSIWYG_EDITOR"] == 4) {	//SPAW
	$wysiwygTemplates['editor'] = 4;
} else {
	$wysiwygTemplates['editor'] = 0;
}

foreach($wysiwygTemplates['SPAW2'] as $value) {
	$value1 = html_specialchars($value);
	$wysiwygTemplates['SPAW2_options'] .= '	<option value="'.$value1.'"';
	if($wysiwygTemplates['editor'] == 1 && $wysiwygTemplates['userTemplate'] == $value) {
		$wysiwygTemplates['SPAW2_options'] .= ' selected="selected"';
	}
	$wysiwygTemplates['SPAW2_options'] .= '>SPAW2: '.$value1.'</option>';
}

foreach($wysiwygTemplates['SPAW'] as $value) {
	$value1 = html_specialchars($value);
	$wysiwygTemplates['SPAW_options'] .= '	<option value="'.$value1.'"';
	if($wysiwygTemplates['editor'] == 4 && $wysiwygTemplates['userTemplate'] == $value) {
		$wysiwygTemplates['SPAW_options'] .= ' selected="selected"';
	}
	$wysiwygTemplates['SPAW_options'] .= '>SPAW: '.$value1.'</option>';
}

foreach($wysiwygTemplates['FCKeditor'] as $value) {
	$value = html_specialchars($value);
	$wysiwygTemplates['FCKeditor_options'] .= '	<option value="'.$value.'"';
	if($wysiwygTemplates['editor'] == 2 && $wysiwygTemplates['userTemplate'] == $value) {
		$wysiwygTemplates['FCKeditor_options'] .= ' selected="selected"';
	}
	$wysiwygTemplates['FCKeditor_options'] .= '>FCKeditor: '.$value.'</option>';
}

$wysiwygTemplates['SPAW2_select']  = '<select name="form_wysiwyg_template" class="f11" style="width:250px;">';
$wysiwygTemplates['SPAW2_select'] .= $wysiwygTemplates['SPAW2_options'];
$wysiwygTemplates['SPAW2_select'] .= '</select>';

$wysiwygTemplates['SPAW_select']  = '<select name="form_wysiwyg_template" class="f11" style="width:250px;">';
$wysiwygTemplates['SPAW_select'] .= $wysiwygTemplates['SPAW_options'];
$wysiwygTemplates['SPAW_select'] .= '</select>';

$wysiwygTemplates['FCKeditor_select']  = '<select name="form_wysiwyg_template" class="f11" style="width:250px;">';
$wysiwygTemplates['FCKeditor_select'] .= $wysiwygTemplates['FCKeditor_options'];
$wysiwygTemplates['FCKeditor_select'] .= '</select>';

?>
<script language="javascript" type="text/javascript">
<!--
setWYSIWYGtemplate();

function setWYSIWYGtemplate() {
	var templateObj = document.getElementById('form_wysiwyg');
	var templateVal = templateObj.options[templateObj.selectedIndex].value;
	var baseVal     = '<input type="hidden" name="form_wysiwyg_toolbar" value="" />'
	if(templateVal == '1') {
		baseVal     = '<?php echo $wysiwygTemplates['SPAW2_select'] ?>';
	} else if(templateVal == '2') {
		baseVal     = '<?php echo $wysiwygTemplates['FCKeditor_select'] ?>';
	} else if(templateVal == '4') {
		baseVal     = '<?php echo $wysiwygTemplates['SPAW_select'] ?>';
	}
	document.getElementById('wysiwyg_template').innerHTML = baseVal;
}
//-->
</script>