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


?>
<form action="<?php echo shop_url('controller=pref'); ?>" method="post" class="editform1">

<table border="0" cellpadding="0" cellspacing="0" width="100%" summary="">

	<tr> 
		<td align="right" class="chatlist"><?php echo $BLM['shopprod_currency'] ?>:&nbsp;</td>
		<td><table cellpadding="0" cellspacing="0" border="0" summary="">
		
			<tr>
				<td><input name="pref_currency" type="text" id="pref_currency" class="v12 width125" value="<?php echo html_entities($plugin['data']['shop_pref_currency']) ?>" size="10" maxlength="10" onchange="enableSubmit();" /></td>
				<td class="chatlist">&nbsp;&nbsp;EUR, USD, &#8364;, $, &pound;, &yen;</td>
			</tr>
				
			</table></td>
	</tr>

	<tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="10" /></td></tr>	
	
	<tr> 
		<td align="right" class="chatlist"><?php echo $BLM['shopprod_unit'] . ' - ' . $BLM['shopprod_weight'] ?>:&nbsp;</td>
		<td><table cellpadding="0" cellspacing="0" border="0" summary="">
		
			<tr>
				<td><input name="pref_unit_weight" type="text" id="pref_unit_weight" class="v12 width125" value="<?php echo html_specialchars($plugin['data']['shop_pref_unit_weight']) ?>" size="10" maxlength="10" onchange="enableSubmit();" /></td>
				<td class="chatlist">&nbsp;&nbsp;<?php echo $BLM['shopprod_units_weight'] ?></td>
			</tr>
				
			</table></td>
	</tr>
	
	<tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="10" /></td></tr>	
	
	<tr> 
		<td align="right" class="chatlist tdtop4"><?php echo $BLM['shopprod_vat_rates'] ?>:&nbsp;</td>
		<td><table cellpadding="0" cellspacing="0" border="0" summary="">
		
			<tr>
				<td><textarea name="pref_vat" id="pref_vat" class="v12 width125 right" onchange="enableSubmit();" rows="3"><?php 

	foreach( $plugin['data']['shop_pref_vat'] as $value ) {
		echo number_format($value, 2, $BLM['dec_point'], $BLM['thousands_sep']) . LF;
	}
				?></textarea></td>
				<td class="chatlist tdtop4">&nbsp;%</td>
			</tr>
				
			</table></td>
	</tr>
	
	

	<tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="10" /></td></tr>	
	
	<tr> 
		<td align="right" class="chatlist"><?php echo $BLM['shopprod_email_to'] ?>:&nbsp;</td>
		<td><input name="pref_email_to" type="text" id="pref_email_to" class="v12 width375" value="<?php echo html_specialchars(str_replace(';', '; ', $plugin['data']['shop_pref_email_to'])) ?>" size="30" maxlength="200" onchange="enableSubmit();" /></td>
	</tr>
	
	<tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="10" /></td></tr>	
	
	<tr> 
		<td align="right" class="chatlist"><?php echo $BLM['shopprod_email_from'] ?>:&nbsp;</td>
		<td><input name="pref_email_from" type="text" id="pref_email_from" class="v12 width375" value="<?php echo html_specialchars($plugin['data']['shop_pref_email_from']) ?>" size="30" maxlength="200" onchange="enableSubmit();" /></td>
	</tr>
	
	<tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="10" /></td></tr>	
	
	<tr> 
		<td align="right" class="chatlist"><?php echo $BLM['shopprod_id_shop'] ?>:&nbsp;</td>
		<td><input name="pref_shop_id" type="text" id="pref_shop_id" class="v12 width375" value="<?php echo html_specialchars($plugin['data']['shop_pref_id_shop']) ?>" size="30" maxlength="200" onchange="enableSubmit();" /></td>
	</tr>
	
	<tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="10" /></td></tr>	
	
	<tr> 
		<td align="right" class="chatlist"><?php echo $BLM['shopprod_id_cart'] ?>:&nbsp;</td>
		<td><input name="pref_cart_id" type="text" id="pref_cart_id" class="v12 width375" value="<?php echo html_specialchars($plugin['data']['shop_pref_id_cart']) ?>" size="30" maxlength="200" onchange="enableSubmit();" /></td>
	</tr>
	
	
	<tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="10" /></td></tr>

	<tr> 
		<td align="right" class="chatlist" valign="top"><?php echo $BLM['shopprod_shipping'] ?>:&nbsp;</td>
		<td><table cellpadding="0" cellspacing="0" border="0" summary="">
		
			<tr>
				<td class="chatlist"><?php echo $BLM['shopprod_weight'].', '.$BLM['shopprod_weight_max'] ?>&nbsp;</td>
				<td class="chatlist"><?php echo $BLM['shopprod_net'] ?>&nbsp;</td>
				<td class="chatlist"><?php echo $BLM['shopprod_vat'] ?> %</td>
			</tr>
	<?php
	for( $x = 0; $x <= 4; $x++ ) {

		echo '
			<tr>
				<td class="tdtop3"><input name="pref_shipping_weight['.$x.']" type="text" class="v12 width100" value="' . html_specialchars( @number_format($plugin['data']['shop_pref_shipping'][$x]['weight'], 3, $BLM['dec_point'], $BLM['thousands_sep'] ) ) . '" size="10" maxlength="10" onchange="enableSubmit();" />&nbsp;</td>
				<td class="tdtop3"><input name="pref_shipping_net['.$x.']" type="text" class="v12 width100" value="' . html_specialchars( @number_format($plugin['data']['shop_pref_shipping'][$x]['net'], 3, $BLM['dec_point'], $BLM['thousands_sep'] ) ) . '" size="10" maxlength="10" onchange="enableSubmit();" />&nbsp;</td>
				<td class="tdtop3"><input name="pref_shipping_vat['.$x.']" type="text" class="v12 width100" value="' . html_specialchars( @number_format($plugin['data']['shop_pref_shipping'][$x]['vat'], 2, $BLM['dec_point'], $BLM['thousands_sep'] ) ) . '" size="10" maxlength="10" onchange="enableSubmit();" /></td>
			</tr>
			';

	}
	?>
		
			</table></td>
	</tr>


	<tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="10" /></td></tr>	

	<tr> 
		<td align="right" class="chatlist tdtop4"><?php echo $BLM['shopprod_payment_method'] ?>:&nbsp;</td>
		<td><table cellpadding="0" cellspacing="0" border="0" summary="">
		
			<tr>
				<td><input type="checkbox" name="pref_payment_paypal" id="pref_payment_paypal" value="1"<?php is_checked(1, $plugin['data']['shop_pref_payment']['paypal']) ?> onchange="enableSubmit();" /></td>
				<td><label for="pref_payment_paypal">&nbsp;<?php echo $BLM['shopprod_payby_paypal'] ?>&nbsp;&nbsp;&nbsp;</label></td>
				<td align="right" class="chatlist"><?php echo $BLM['shopprod_email_paypal'] ?>:&nbsp;</td>
				<td><input name="pref_email_paypal" type="text" id="pref_email_paypal" class="v12 width175" value="<?php echo html_specialchars($plugin['data']['shop_pref_email_paypal']) ?>" size="30" maxlength="200" onchange="enableSubmit();" /></td>
			</tr>
			
			<tr><td colspan="4"><img src="img/leer.gif" alt="" width="1" height="3" /></td></tr>
			
			<tr>
				<td valign="top"><input type="checkbox" name="pref_payment_ccard" id="pref_payment_ccard" value="1"<?php is_checked(1, $plugin['data']['shop_pref_payment']['ccard']) ?> onchange="enableSubmit();" /></td>
				<td class="tdtop4"><label for="pref_payment_ccard">&nbsp;<?php echo $BLM['shopprod_payby_ccard'] ?>&nbsp;&nbsp;&nbsp;</label></td>
				<td align="right" class="chatlist tdtop4"><?php echo $BLM['shopprod_supported_ccard'] ?>:&nbsp;</td>
				<td><select name="pref_supported_ccard[]" id="pref_supported_ccard" size="4" multiple="multiple" onchange="enableSubmit();" class="v12 width175">
				
					<option value="americanexpress"<?php if(in_array('americanexpress', $plugin['data']['shop_pref_payment']['accepted_ccard'])) echo ' selected="selected"'; ?> style="margin-bottom:1px">American Express</option>
					<option value="mastercard"<?php if(in_array('mastercard', $plugin['data']['shop_pref_payment']['accepted_ccard'])) echo ' selected="selected"'; ?> style="margin-bottom:1px">MasterCard/EuroCard</option>
					<option value="visa"<?php if(in_array('visa', $plugin['data']['shop_pref_payment']['accepted_ccard'])) echo ' selected="selected"'; ?> style="margin-bottom:1px">Visa</option>			
				
				</select></td>
			</tr>
			
			<tr><td colspan="4"><img src="img/leer.gif" alt="" width="1" height="3" /></td></tr>
			
			<tr>
				<td><input type="checkbox" name="pref_payment_prepay" id="pref_payment_prepay" value="1"<?php is_checked(1, $plugin['data']['shop_pref_payment']['prepay']) ?> onchange="enableSubmit();" /></td>
				<td><label for="pref_payment_prepay">&nbsp;<?php echo $BLM['shopprod_payby_prepay'] ?>&nbsp;&nbsp;&nbsp;</label></td>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
			</tr>
			
			<tr><td colspan="4"><img src="img/leer.gif" alt="" width="1" height="3" /></td></tr>
			
			<tr>
				<td><input type="checkbox" name="pref_payment_pod" id="pref_payment_pod" value="1"<?php is_checked(1, $plugin['data']['shop_pref_payment']['pod']) ?> onchange="enableSubmit();" /></td>
				<td><label for="pref_payment_pod">&nbsp;<?php echo $BLM['shopprod_payby_pod'] ?>&nbsp;&nbsp;&nbsp;</label></td>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
			</tr>
			
			<tr><td colspan="4"><img src="img/leer.gif" alt="" width="1" height="3" /></td></tr>
			
			<tr>
				<td><input type="checkbox" name="pref_payment_onbill" id="pref_payment_onbill" value="1"<?php is_checked(1, $plugin['data']['shop_pref_payment']['onbill']) ?> onchange="enableSubmit();" /></td>
				<td><label for="pref_payment_onbill">&nbsp;<?php echo $BLM['shopprod_payby_onbill'] ?>&nbsp;&nbsp;&nbsp;</label></td>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
			</tr>
		
			</table></td>
	</tr>
	
	
	<tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="10" /></td></tr>
	
	<tr>
		<td>&nbsp;</td>
		<td><table summary="" cellpadding="1" cellspacing="0" border="0" bgcolor="#E7E8EB">
			<tr>
				<td><input type="radio" name="pref_terms_format" id="pref_terms_text" value="0"<?php is_checked('0', $plugin['data']['shop_pref_terms_format']) ?> onchange="enableSubmit();" /></td>
				<td class="f10"><label for="pref_terms_text">TEXT</label>&nbsp;&nbsp;</td>
				<td><input type="radio" name="pref_terms_format" id="pref_terms_html" value="1"<?php is_checked('1', $plugin['data']['shop_pref_terms_format']) ?> onchange="enableSubmit();" /></td>
				<td class="f10"><label for="pref_terms_html">HTML</label>&nbsp;</td>
			</tr>
		</table></td>
	
	</tr>
	
	<tr> 
		<td align="right" class="chatlist tdtop4"><?php echo $BLM['shopprod_terms'] ?>:&nbsp;</td>
		<td><textarea name="pref_terms" id="pref_terms" class="v12 width375" onchange="enableSubmit();" rows="10"><?php 

		echo $plugin['data']['shop_pref_terms_format'] ? html_entities($plugin['data']['shop_pref_terms']) : html_specialchars($plugin['data']['shop_pref_terms']);
	
		?></textarea></td>
	</tr>
	
	
	<tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="18" /></td></tr>

	<tr> 
		<td>&nbsp;</td>
		<td>
			<input name="save" type="submit" class="button10" id="save_button" value="<?php echo $BL['be_article_cnt_button3'] ?>" disabled="disabled" />
			&nbsp;
			<input name="reset" type="reset" class="button10" value="<?php echo $BL['be_cnt_field']['reset'] ?>" onclick="disableSubmit();" />
		</td>
	</tr>
	

</table>

</form>
<script type="text/javascript" language="javascript">
<!--
	function enableSubmit() {
		var submit_prefs = getObjectById('save_button');
		submit_prefs.disabled=false;
	}
	function disableSubmit() {
		var submit_prefs = getObjectById('save_button');
		submit_prefs.disabled=true;
	}
//-->
</script>