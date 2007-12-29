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


//Auslesen der eventuell für den User bereits vorhandenen Detaildaten
//1. Prüfen, ob überhaupt ein Profil angelegt ist
$sql = 'SELECT COUNT(*) FROM '.DB_PREPEND.'phpwcms_userdetail WHERE detail_pid='.intval($_SESSION["wcs_user_id"]);

if(_dbQuery($sql, 'COUNT')) {
		
	//Es sind bereits Daten hinterlegt - diese jetzt auslesen
	$sql = 'SELECT * FROM '.DB_PREPEND.'phpwcms_userdetail WHERE detail_pid='.intval($_SESSION['wcs_user_id']).' LIMIT 1';
	$detail = _dbQuery($sql);
	if(is_array($detail[0])) {
		$detail = $detail[0];
		$form_detail_aktion = 'update_detail';
	} else {
		$form_detail_aktion = 'create_detail';
	}
			
} else {
			
	$form_detail_aktion = 'create_detail';
		
}

if($form_detail_aktion == 'create_detail') {
	
	$detail = array(
	
		'detail_title'		=> '',
		'detail_firstname'	=> '',
		'detail_lastname'	=> '',
		'detail_company'	=> '',
		'detail_street'		=> '',
		'detail_add'		=> '',
		'detail_city'		=> '',
		'detail_region'		=> '',
		'detail_zip'		=> '',
		'detail_country'	=> '',
		'detail_fon'		=> '',
		'detail_fax'		=> '',
		'detail_mobile'		=> '',
		'detail_signature'	=> '',
		'detail_notes'		=> '',
		'detail_prof'		=> '',
		'detail_newsletter'	=> 1,
		'detail_public'		=> 1
	
	);

}

?><form action="phpwcms.php?do=profile&amp;p=1" method="post" name="formprofiledetail" id="formprofiledetail"><table width="538" border="0" cellpadding="0" cellspacing="0" summary="">
	<tr><td colspan="3" class="title"><?php echo $BL['be_profile_data_title'] ?></td></tr>
	<tr><td colspan="3"><img src="img/leer.gif" alt="" width="1" height="5"></td></tr>
	<tr><td colspan="3"><?php echo $BL['be_profile_data_text'] ?></td></tr>
<?php
	//if error during login occurs
	if(!empty($detail_updated)) {
		echo '<tr><td colspan="3" class="error"><img src="img/leer.gif" alt=" width="1" height="10" /><br /><strong>';
		echo nl2br(chop($detail_updated)).'</strong></td></tr>';
	}
?>
	<tr> 
		<td width="110" height="12"><img src="img/leer.gif" alt="" width="110" height="10"></td>
		<td><img src="img/leer.gif" alt="" width="25" height="1"></td>
        <td><img src="img/leer.gif" alt="" width="403" height="1"></td>
	</tr>
	<tr> 
		<td align="right"><?php echo $BL['be_profile_label_title'] ?>:&nbsp;</td>
		<td colspan="2"><input name="form_title" type="text" id="form_title" class="v12 width250" value="<?php echo html_specialchars($detail["detail_title"]) ?>" size="30" maxlength="50"></td>
	</tr>
	<tr><td colspan="3"><img src="img/leer.gif" alt="" width="1" height="2"></td></tr>
	<tr> 
		<td align="right"><?php echo $BL['be_profile_label_firstname'] ?>:&nbsp;</td>
		<td colspan="2"><input name="form_firstname" type="text" id="form_firstname" class="v12 width250" value="<?php echo html_specialchars($detail["detail_firstname"]) ?>" size="30" maxlength="100"></td>
	</tr>
	<tr><td colspan="3"><img src="img/leer.gif" alt="" width="1" height="2"></td></tr>
	<tr> 
		<td align="right"><?php echo $BL['be_profile_label_name'] ?>:&nbsp;</td>
		<td colspan="2"><input name="form_lastname" type="text" id="form_lastname" class="v12 width250" value="<?php echo html_specialchars($detail["detail_lastname"]) ?>" size="30" maxlength="100"></td>
	</tr>
	<tr><td colspan="3"><img src="img/leer.gif" alt="" width="1" height="2"></td></tr>
	<tr> 
		<td align="right"><?php echo $BL['be_profile_label_company'] ?>:&nbsp;</td>
		<td colspan="2"><input name="form_company" type="text" id="form_company" class="v12 width250" value="<?php echo html_specialchars($detail["detail_company"]) ?>" size="30" maxlength="100"></td>
	</tr>
	<tr><td colspan="3"><img src="img/leer.gif" alt="" width="1" height="2"></td></tr>
	<tr> 
		<td align="right"><?php echo $BL['be_profile_label_street'] ?>:&nbsp;</td>
		<td colspan="2"><input name="form_street" type="text" id="form_street" class="v12 width250" value="<?php echo html_specialchars($detail["detail_street"]) ?>" size="30" maxlength="100"></td>
	</tr>
	<tr><td colspan="3"><img src="img/leer.gif" alt="" width="1" height="2"></td></tr>
	<tr> 
		<td align="right">&nbsp;</td>
		<td colspan="2"><input name="form_add" type="text" id="form_add" class="v12 width250" value="<?php echo html_specialchars($detail["detail_add"]) ?>" size="30" maxlength="100"></td>
	</tr>
	<tr><td colspan="3"><img src="img/leer.gif" alt="" width="1" height="2"></td></tr>
	<tr> 
		<td align="right"><?php echo $BL['be_profile_label_city'] ?>:&nbsp;</td>
		<td colspan="2"><input name="form_city" type="text" id="form_city" class="v12 width250" value="<?php echo html_specialchars($detail["detail_city"]) ?>" size="30" maxlength="100"></td>
	</tr>
	<tr><td colspan="3"><img src="img/leer.gif" alt="" width="1" height="2"></td></tr>
	<tr> 
		<td align="right"><?php echo $BL['be_profile_label_state'] ?>:&nbsp;</td>
		<td colspan="2"><input name="form_region" type="text" id="form_region" class="v12 width250" value="<?php echo html_specialchars($detail["detail_region"]) ?>" size="30" maxlength="100"></td>
	</tr>
	<tr><td colspan="3"><img src="img/leer.gif" alt="" width="1" height="2"></td></tr>
	<tr> 
		<td align="right"><?php echo $BL['be_profile_label_zip'] ?>:&nbsp;</td>
		<td colspan="2"><input name="form_zip" type="text" id="form_zip" class="v12 width100" value="<?php echo html_specialchars($detail["detail_zip"]) ?>" size="30" maxlength="50"></td>
	</tr>
	<tr><td colspan="3"><img src="img/leer.gif" alt="" width="1" height="2"></td></tr>
	<tr> 
		<td align="right"><?php echo $BL['be_profile_label_country'] ?>:&nbsp;</td>
		<td colspan="2"><select name="form_country" id="form_country" class="v12 width250">
		<?php echo list_country($detail["detail_country"], $db); ?>
		</select></td>
	</tr>
	<tr><td colspan="3"><img src="img/leer.gif" alt="" width="1" height="7"></td></tr>
	<tr> 
		<td align="right"><?php echo $BL['be_profile_label_phone'] ?>:&nbsp;</td>
		<td colspan="2"><input name="form_fon" type="text" id="form_fon" class="v12 width250" value="<?php echo html_specialchars($detail["detail_fon"]) ?>" size="30" maxlength="30"></td>
	</tr>
	<tr><td colspan="3"><img src="img/leer.gif" alt="" width="1" height="2"></td></tr>
	<tr> 
		<td align="right"><?php echo $BL['be_profile_label_fax'] ?>:&nbsp;</td>
		<td colspan="2"><input name="form_fax" type="text" id="form_fax" class="v12 width250" value="<?php echo html_specialchars($detail["detail_fax"]) ?>" size="30" maxlength="30"></td>
	</tr>
	<tr><td colspan="3"><img src="img/leer.gif" alt="" width="1" height="2"></td></tr>
	<tr> 
		<td align="right"><?php echo $BL['be_profile_label_cellphone'] ?>:&nbsp;</td>
		<td colspan="2"><input name="form_mobile" type="text" id="form_mobile" class="v12 width250" value="<?php echo html_specialchars($detail["detail_mobile"]) ?>" size="30" maxlength="30"></td>
	</tr>
	<tr><td colspan="3"><img src="img/leer.gif" alt="" width="1" height="7"></td></tr>
	<tr> 
		<td align="right" valign="top"><img src="img/leer.gif" alt="" width="1" height="13"><?php echo $BL['be_profile_label_signature'] ?>:&nbsp;</td>
		<td colspan="2"><textarea name="form_signature" cols="30" rows="3" id="form_signature" class="v12 width400"><?php echo html_specialchars($detail["detail_signature"]) ?></textarea></td>
	</tr>
	<tr><td colspan="3"><img src="img/leer.gif" alt="" width="1" height="2"></td></tr>
	<tr> 
		<td align="right" valign="top"><img src="img/leer.gif" alt="" width="1" height="13"><?php echo $BL['be_profile_label_notes'] ?>:&nbsp;</td>
		<td colspan="2"><textarea name="form_notes" cols="30" rows="6" id="form_notes" class="v12 width400"><?php echo html_specialchars($detail["detail_notes"]) ?></textarea></td>
	</tr>
	<tr><td colspan="3"><img src="img/leer.gif" alt="" width="1" height="7"></td></tr>
	<tr> 
		<td align="right"><?php echo $BL['be_profile_label_profession'] ?>:&nbsp;</td>
		<td colspan="2"><select name="form_prof" id="select2" class="v12 width250">
		<?php list_profession($detail["detail_prof"]); ?>
		</select></td>
	</tr>
	<tr><td colspan="3"><img src="img/leer.gif" alt="" width="1" height="7"></td></tr>
	<tr> 
		<td align="right"><?php echo $BL['be_profile_label_newsletter'] ?>:&nbsp;</td>
		<td><input name="form_newsletter" type="checkbox" id="form_newsletter" value="1"<?php is_checked($detail["detail_newsletter"], "1"); ?>></td>
		<td><?php echo $BL['be_profile_text_newsletter'] ?></td>
	</tr>
	<tr><td colspan="3"><img src="img/leer.gif" alt="" width="1" height="2"></td></tr>
	<tr> 
		<td align="right"><?php echo $BL['be_profile_label_public'] ?>:&nbsp;</td>
		<td width="25"><input name="form_public" type="checkbox" id="form_public" value="1"<?php is_checked($detail["detail_public"], "1"); ?>></td>
        <td width="403"><?php echo $BL['be_profile_text_public'] ?></td>
	</tr>
	<tr><td colspan="3"><img src="img/leer.gif" alt="" width="1" height="15"><input name="form_aktion" type="hidden" id="form_aktion" value="<?php echo $form_detail_aktion ?>"></td></tr>
	<tr> 
		<td align="right">&nbsp;</td>
		<td colspan="2"><input type="submit" name="Submit" value="<?php echo $BL['be_profile_label_button'] ?>" class="button10"></td>
	</tr>
	<tr><td colspan="3"><img src="img/leer.gif" alt="" width="1" height="20"></td></tr>
</table></form>
