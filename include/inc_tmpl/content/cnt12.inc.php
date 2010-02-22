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


//newsletter subscription

?>
<tr><td colspan="2"><img src="img/lines/l538_70.gif" alt="" width="538" height="1" /></td>
</tr>
<tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="6" /></td>
</tr>
<tr>
  <td align="right" valign="top" class="chatlist"><img src="img/leer.gif" alt="" width="1" height="13" /><?php echo $BL['be_cnt_subscription'] ?>:&nbsp;</td>
  <td valign="top"><?php

	$content["newsletter"]['left']	= array();
	$content["newsletter"]['right']	= array();
	
	// default = all subscriptions
	$content["newsletter"]['right'][0] = $BL['be_newsletter_allsubscriptions'];
	
	// retrieve all available subscriptions first
	$result = _dbQuery("SELECT * FROM ".DB_PREPEND."phpwcms_subscription ORDER BY subscription_name");
	foreach($result as $row) {
		$content["newsletter"]['right'][ $row["subscription_id"] ] = html_specialchars($row["subscription_name"]);
	}
	
	if(isset($content["newsletter"]["subscription"]) && is_array($content["newsletter"]["subscription"])) {
		foreach($content["newsletter"]["subscription"] as $row => $result) {
			if(isset($content["newsletter"]['right'][ $row ])) {
				$content["newsletter"]['left'][ $row ] = $content["newsletter"]['right'][ $row ];
				unset($content["newsletter"]['right'][ $row ]);
			}
		}
	}

	echo createOptionTransferSelectList(	'cnewsletter_subscription', 
											$content["newsletter"]['left'], 
											$content["newsletter"]['right'], 
											array('class'=>'optionTransfer', 'formname'=>'articlecontent', 'rows'=>7));

	?></td>
  <td>&nbsp;</td>
</tr>
<tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="6"></td></tr>
<tr><td colspan="2"><img src="img/lines/l538_70.gif" alt="" width="538" height="1"></td></tr>
<tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="6"></td></tr>

<tr>
	<td align="right" class="chatlist"><?php echo $BL['be_cnt_labelemail'] ?>:&nbsp;</td>
	<td><table width="439" border="0" cellpadding="0" cellspacing="0" summary="">
	  <tr>
		<td width="180"><input name="cnewsletter_label_email" type="text" id="cnewsletter_label_email" class="f11b" style="width: 180px" value="<?php echo  isset($content["newsletter"]["label_email"]) ? $content["newsletter"]["label_email"] : '' ?>" size="20" maxlength="100"></td>
		<td width="79" align="right" class="chatlist">&nbsp;&nbsp;<?php 
		
		echo $BL['be_cnt_tablealign'];
		if(!isset($content["newsletter"]["pos"])) $content["newsletter"]["pos"] = 0;
		
		?>:&nbsp;</td>
		<td width="180"><select name="cnewsletter_pos" class="f10" id="cnewsletter_pos">
		  <option value="0" <?php is_selected(0, $content["newsletter"]["pos"]) ?>><?php echo $BL['be_cnt_default'] ?></option>
		  <option value="1" <?php is_selected(1, $content["newsletter"]["pos"]) ?>><?php echo $BL['be_cnt_left'] ?></option>
		  <option value="2" <?php is_selected(2, $content["newsletter"]["pos"]) ?>><?php echo $BL['be_cnt_center'] ?></option>
		  <option value="3" <?php is_selected(3, $content["newsletter"]["pos"]) ?>><?php echo $BL['be_cnt_right'] ?></option>
		</select></td>
	  </tr>
	</table></td>
</tr>
<tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="3"></td></tr>
<tr>
	<td align="right" class="chatlist"><?php echo $BL['be_cnt_labelname'] ?>:&nbsp;</td>
	<td><table width="439" border="0" cellpadding="0" cellspacing="0" summary="">
	  <tr>
		<td width="180"><input name="cnewsletter_label_name" type="text" id="cnewsletter_label_name" class="f11b" style="width: 180px" value="<?php echo  isset($content["newsletter"]["label_name"]) ? $content["newsletter"]["label_name"] : '' ?>" size="20" maxlength="100"></td>
		<td width="79" align="right" class="chatlist">&nbsp;&nbsp;<?php echo $BL['be_cnt_buttontext'] ?>:&nbsp;</td>
		<td width="180"><input name="cnewsletter_button_text" type="text" id="cnewsletter_button_text" class="f11b" style="width: 180px" value="<?php echo  isset($content["newsletter"]["button_text"]) ? $content["newsletter"]["button_text"] : '' ?>" size="40" maxlength="50"></td>
	  </tr>
	</table></td>
</tr>
<tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="3"></td></tr>
<tr>
	<td align="right" class="chatlist"><?php echo $BL['be_cnt_labelsubsc'] ?>:&nbsp;</td>
	<td><table width="439" border="0" cellpadding="0" cellspacing="0" summary="">
	  <tr>
		<td width="180"><input name="cnewsletter_label_subscriptions" type="text" id="cnewsletter_label_subscriptions" class="f11b" style="width: 180px" value="<?php echo  isset($content["newsletter"]["label_subscriptions"]) ? $content["newsletter"]["label_subscriptions"] : '' ?>" size="20" maxlength="100"></td>
		<td width="79" align="right" class="chatlist">&nbsp;&nbsp;<?php echo $BL['be_cnt_allsubsc'] ?>:&nbsp;</td>
		<td width="180"><input name="cnewsletter_all_subscriptions" type="text" id="cnewsletter_all_subscriptions" class="f11b" style="width: 180px" value="<?php echo  isset($content["newsletter"]["all_subscriptions"]) ? $content["newsletter"]["all_subscriptions"] : '' ?>" size="40" maxlength="50"></td>
	  </tr>
	</table></td>
</tr>
<tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="6"></td></tr>
<tr>
	<td align="right" valign="top" class="chatlist"><img src="img/leer.gif" alt="" width="1" height="13"><?php echo $BL['be_cnt_infotext'] ?>:&nbsp;</td>
	<td valign="top"><textarea name="cnewsletter_text" rows="8" class="f10" id="cnewsletter_text" style="width: 440px"><?php echo  isset($content["newsletter"]["text"]) ? $content["newsletter"]["text"] : '' ?></textarea></td>
</tr>
<tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="3"></td></tr>
<tr>
	<td align="right" valign="top" class="chatlist"><img src="img/leer.gif" alt="" width="1" height="16"><?php echo $BL['be_cnt_successtext'] ?>:&nbsp;</td>
	<td valign="top"><textarea name="cnewsletter_success_text" rows="5" class="f10" id="cnewsletter_success_text" style="width: 440px"><?php echo  isset($content["newsletter"]["success_text"]) ? $content["newsletter"]["success_text"] : '' ?></textarea></td>
</tr>
<tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="6"></td></tr>
<tr>
	<td align="right" class="chatlist">URL 1:&nbsp;</td>
	<td><input name="cnewsletter_url1" type="text" id="cnewsletter_url1" class="f11b" style="width: 440px" value="<?php echo isset($content["newsletter"]["url1"]) ? html_specialchars($content["newsletter"]["url1"]) : '' ?>" size="20" /></td>
</tr>
<tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="3"></td></tr>
<tr>
	<td align="right" class="chatlist">URL 2:&nbsp;</td>
	<td><input name="cnewsletter_url2" type="text" id="cnewsletter_url2" class="f11b" style="width: 440px" value="<?php echo isset($content["newsletter"]["url2"]) ? html_specialchars($content["newsletter"]["url2"]) : '' ?>" size="20" /></td>
</tr>
<tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="6"></td></tr>
<tr><td colspan="2"><img src="img/lines/l538_70.gif" alt="" width="538" height="1"></td></tr>
<tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="6"></td></tr>

<tr>
	<td>&nbsp;</td>
	<td style="padding-bottom:3px;" class="v10">{NEWSLETTER_NAME}, {NEWSLETTER_EMAIL}, {NEWSLETTER_VERIFY}, {NEWSLETTER_DELETE}, {IP}, {DATE:m/d/Y}, [SUBJECT][/SUBJECT]</td>
</tr>

  <tr>
    <td align="right" valign="top" class="chatlist"><img src="img/leer.gif" alt="" width="1" height="16"><?php echo $BL['be_cnt_regmail'] ?>:&nbsp;</td>
    <td valign="top"><textarea name="cnewsletter_reg_text" rows="5" wrap="OFF" class="code" id="cnewsletter_reg_text" style="width: 440px"><?php echo  isset($content["newsletter"]["reg_text"]) ? $content["newsletter"]["reg_text"] : '' ?></textarea></td>
  </tr>
  <tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="3"></td></tr>
  <tr>
    <td align="right" valign="top" class="chatlist"><img src="img/leer.gif" alt="" width="1" height="16"><?php echo $BL['be_cnt_logoffmail'] ?>:&nbsp;</td>
    <td valign="top"><textarea name="cnewsletter_logoff_text" rows="5" wrap="OFF" class="code" id="cnewsletter_logoff_text" style="width: 440px"><?php echo  isset($content["newsletter"]["logoff_text"]) ? $content["newsletter"]["logoff_text"] : '' ?></textarea></td>
  </tr>
  <tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="3"></td></tr>
  <tr>
    <td align="right" valign="top" class="chatlist"><img src="img/leer.gif" alt="" width="1" height="16"><?php echo $BL['be_cnt_changemail'] ?>:&nbsp;</td>
    <td valign="top"><textarea name="cnewsletter_change_text" rows="5" wrap="OFF" class="code" id="cnewsletter_change_text" style="width: 440px"><?php echo  isset($content["newsletter"]["change_text"]) ? $content["newsletter"]["change_text"] : '' ?></textarea></td>
  </tr>
 
<tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="6"></td></tr>
<tr><td colspan="2"><img src="img/lines/l538_70.gif" alt="" width="538" height="1"></td></tr>