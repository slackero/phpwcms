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



?>
<h1 class="title" style="margin-bottom:10px"><?php echo $BLM['listing_title'] ?></h1>

<form action="<?php echo MODULE_HREF ?>&amp;edit=<?php echo $plugin['data']['detail_id'] ?>" method="post" style="background:#F3F5F8;border-top:1px solid #92A1AF;border-bottom:1px solid #92A1AF;margin:0 0 5px 0;padding:10px 8px 15px 8px">
<input type="hidden" name="detail_id" value="<?php echo $plugin['data']['detail_id'] ?>" />
<table border="0" cellpadding="0" cellspacing="0" width="100%" summary="">

	<?php 
	
	if(isset($plugin['error'])) {
	
	?>
	<tr>
		<td>&nbsp;</td>
		<td class="v12 error"><?php echo implode('<br />', $plugin['error']) ;?></td>	
	</tr>

	<tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="10" /></td></tr>	
	<?php
	}
	
	
	
	?>

	<tr>
		<td>&nbsp;</td>
		<td class="v12"><?php echo $BLM['forminfo'] ?></td>	
	</tr>

	<tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="5" /></td></tr>
	<!--
	
	detail_title
	detail_firstname
	detail_lastname
	detail_company
	detail_street
	detail_add
	detail_city
	detail_zip
	detail_region
	detail_country
	detail_fon
	detail_fax
	detail_mobile
	detail_signature
	detail_public
	detail_aktiv
	detail_newsletter
	detail_website
	detail_varchar1
	detail_varchar2
	detail_varchar3
	detail_varchar4
	detail_email
	
	//-->

<?php

	foreach($plugin['fields'] as $key => $value) {
	
		switch($value) {
		
			case 'STRING':	
		
		echo '<tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="5" /></td></tr>'.LF;
		echo '<tr>'.LF; 
		echo '<td align="right" class="chatlist">'.$BLM[$key].':&nbsp;</td>'.LF;
		echo '<td><input name="'.$key.'" type="text" id="'.$key.'" class="v12" style="width:400px;" value="'.html_specialchars($plugin['data'][$key]).'" size="30" maxlength="200" /></td>'.LF;
		echo '</tr>'.LF;
			
			
							break;
							
			case 'CHECK':
			
		echo '<tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="5" /></td></tr>'.LF;
		echo '<tr>'.LF; 
		echo '<td>&nbsp;</td>'.LF;
		echo '<td><table border="0" cellpadding="0" cellspacing="0" summary=""><tr><td><input type="checkbox" name="'.$key.'" id="'.$key.'" value="1"';
		is_checked($plugin['data'][$key], 1);
		echo ' /></td><td><label for="'.$key.'">'.$BLM[$key].'</label></td></tr></table></td>'.LF;
		echo '</tr>'.LF;
							break;
		
		}
	
	}
?>

	<tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="10" /></td></tr>

	<tr> 
		<td>&nbsp;</td>
		<td>
			<input name="submit" type="submit" class="button10" value="<?php echo empty($plugin['data']['detail_id']) ? $BL['be_admin_fcat_button2'] : $BL['be_article_cnt_button1'] ?>" />
			<input name="save" type="submit" class="button10" value="<?php echo $BL['be_article_cnt_button3'] ?>" />
			&nbsp;&nbsp;&nbsp;&nbsp;
			<input name="new" type="button" class="button10" value="<?php echo ucfirst($BL['be_msg_new']) ?>" onclick="location.href='<?php echo decode_entities(MODULE_HREF) ?>&edit=0';return false;" />
			<input name="close" type="button" class="button10" value="<?php echo $BL['be_admin_struct_close'] ?>" onclick="location.href='<?php echo decode_entities(MODULE_HREF) ?>';return false;" />
		</td>
	</tr>

</table>

</form>