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
<h1 class="title" style="margin-bottom:10px"><?php 
	
	echo $BLM['cat_edit'];
	if($plugin['data']['cat_id'] && empty($plugin['data']['cat_pid']) ) {
		echo ' <span style="font-weight:normal">[ID: ' . $plugin['data']['cat_id'] . ']</span>';
	}

?></h1>

<form action="<?php echo shop_url( array('controller=cat', 'edit='.$plugin['data']['cat_id']) ) ?>" method="post" style="background:#F3F5F8;border-top:1px solid #92A1AF;border-bottom:1px solid #92A1AF;margin:0 0 5px 0;padding:10px 8px 15px 8px">
<input type="hidden" name="cat_id" value="<?php echo $plugin['data']['cat_id'] ?>" />
<table border="0" cellpadding="0" cellspacing="0" width="100%" summary="">

	<tr> 
		<td align="right" class="chatlist"><?php echo $BL['be_cnt_last_edited']  ?>:&nbsp;</td>
		<td class="v10" width="410"><?php 
		
		echo html_specialchars(date($BL['be_fprivedit_dateformat'], $plugin['data']['cat_changedate'])) ;
		
		if(!empty($plugin['data']['cat_createdate'])) {
		?>		
		&nbsp;&nbsp;&nbsp;<span class="chatlist"><?php echo $BL['be_fprivedit_created']  ?>:</span> 
		<?php 
				echo html_specialchars(date($BL['be_fprivedit_dateformat'], strtotime($plugin['data']['cat_createdate'])));
		}
		
		?></td>
	</tr>
	
	<tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="10" /></td></tr>

	<tr> 
		<td align="right" class="chatlist"><?php echo $BL['be_admin_page_category'] ?>:&nbsp;</td>
		<td><input name="cat_name" type="text" id="cat_name" class="v12<?php 
		
		//error class
		if(!empty($plugin['error']['cat_name'])) echo ' errorInputText';
		
		?>" style="width:400px;" value="<?php echo html_specialchars($plugin['data']['cat_name']) ?>" size="30" maxlength="200" /></td>
	</tr>
	
	<tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="10" /></td></tr>
	
	<tr> 
		<td align="right" class="chatlist"><?php echo $BLM['shopprod_subcategory'] ?>:&nbsp;</td>
		<td><select name="cat_pid" id="cat_pid" class="v12">
		<?php
		//if($plugin['data']['cat_pid'] == 0) {
			echo '<option value="0" selected="selected">&nbsp;</option>' . LF;
		//}
		$sql  = 'SELECT * FROM '.DB_PREPEND."phpwcms_categories WHERE ";
		$sql .= "cat_type='module_shop' AND cat_pid=0 AND cat_status != 9 AND ";
		$sql .= "cat_id != " . $plugin['data']['cat_id'];
		$plugin['data']['subcat'] = _dbQuery($sql);
		foreach($plugin['data']['subcat'] as $value) {
		
			echo '<option value="' . $value['cat_id'] . '"';
			is_selected($plugin['data']['cat_pid'], $value['cat_id']);
			if($value['cat_status'] = 0) {
				echo ' style="font-style:italic;"';
			}
			echo '>' . html_specialchars($value['cat_name']) . '</option>' . LF;
		
		}
		
		?>
		</select></td>
	</tr>	
	
	<tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="10" /></td></tr>

	<tr> 
		<td align="right" class="chatlist" style="padding-top:4px;vertical-align:top;"><?php echo $BL['be_cnt_infotext'] ?>:&nbsp;</td>
		<td colspan="2"><textarea name="cat_info" id="cat_info" rows="10" class="v12 width400"><?php echo html_specialchars($plugin['data']['cat_info']) ?></textarea></td>
	</tr>
	
	<tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="18" /></td></tr>
	
	<tr>
		<td align="right" class="chatlist"><?php echo $BL['be_ftptakeover_status'] ?>:&nbsp;</td>
		<td><table border="0" cellpadding="0" cellspacing="0" summary="">		
			<tr>
				<td><input type="checkbox" name="cat_status" id="cat_status" value="1"<?php is_checked($plugin['data']['cat_status'], 1) ?> /></td>
				<td><label for="cat_status"><?php echo $BL['be_cnt_activated'] ?></label></td>
			</tr>
		</table></td>
	</tr>
	
	<tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="10" /></td>
	</tr>
	<tr> 
		<td>&nbsp;</td>
		<td>
			<input name="submit" type="submit" class="button10" value="<?php echo empty($plugin['data']['cat_id']) ? $BL['be_admin_fcat_button2'] : $BL['be_article_cnt_button1'] ?>" />
			<input name="save" type="submit" class="button10" value="<?php echo $BL['be_article_cnt_button3'] ?>" />
			&nbsp;&nbsp;&nbsp;&nbsp;
			<input name="close" type="submit" class="button10" value="<?php echo $BL['be_admin_struct_close'] ?>" />
		</td>
	</tr>
</table>

</form>