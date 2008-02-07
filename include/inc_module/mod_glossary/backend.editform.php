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

<form action="<?php echo GLOSSARY_HREF ?>&amp;edit=<?php echo $glossary['data']['glossary_id'] ?>" method="post" style="background:#F3F5F8;border-top:1px solid #92A1AF;border-bottom:1px solid #92A1AF;margin:0 0 5px 0;padding:10px 8px 15px 8px">
<input type="hidden" name="glossary_id" value="<?php echo $glossary['data']['glossary_id'] ?>" />
<table border="0" cellpadding="0" cellspacing="0" width="100%" summary="">

	<tr> 
		<td align="right" class="chatlist"><?php echo $BL['be_cnt_last_edited']  ?>:&nbsp;</td>
		<td class="v10"><?php echo html_specialchars(date($BL['be_fprivedit_dateformat'], strtotime($glossary['data']['glossary_changed']))) ?></td>
	</tr>
	
	<?php if(!empty($glossary['data']['glossary_created'])) { ?>
	
	<tr> 
		<td align="right" class="chatlist"><?php echo $BL['be_fprivedit_created']  ?>:&nbsp;</td>
		<td class="v10"><?php echo html_specialchars(date($BL['be_fprivedit_dateformat'], strtotime($glossary['data']['glossary_created']))) ?></td>
	</tr>
	
	<?php } ?>
	
	<tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="10" /></td></tr>

	<tr> 
		<td align="right" class="chatlist"><?php echo $BLM['glossary_title'] ?>:&nbsp;</td>
		<td><input name="glossary_title" type="text" id="glossary_title" class="f11b<?php 
		
		//error class
		if(!empty($glossary['error']['glossary_title'])) echo ' errorInputText';
		
		?>" style="width:400px;" value="<?php echo html_specialchars($glossary['data']['glossary_title']) ?>" size="30" maxlength="1000" /></td>
	</tr>
	
	<tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="5" /></td></tr>
	
	<tr> 
		<td align="right" class="chatlist"><?php echo $BLM['glossary_keyword'] ?>:&nbsp;</td>
		<td><input name="glossary_keyword" type="text" id="glossary_keyword" class="f11b<?php 
		
		//error class
		if(!empty($glossary['error']['glossary_keyword'])) echo ' errorInputText';
		
		?>" style="width:400px;" value="<?php echo html_specialchars($glossary['data']['glossary_keyword']) ?>" size="30" maxlength="200" /></td>
	</tr>	
	
	<tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="5" /></td></tr>
	
	<tr> 
		<td align="right" class="chatlist"><?php echo $BLM['glossary_token'] ?>:&nbsp;</td>
		<td><input name="glossary_tag" type="text" id="glossary_tag" class="f11" style="width:400px;" value="<?php echo html_specialchars($glossary['data']['glossary_tag']) ?>" size="30" maxlength="220" /></td>
	</tr>
	
	<tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="10" /></td></tr>
	
	<tr> 
		<td class="chatlist" colspan="2" style="padding-bottom:4px"><?php echo $BLM['glossary_text'] ?>:&nbsp;</td>
	</tr>
	
	<tr> 
		<td colspan="2" align="center"><?php

		$wysiwyg_editor = array(
			'value'		=> $glossary['data']['glossary_text'],
			'field'		=> 'glossary_text',
			'height'	=> '400px',
			'width'		=> '524px',
			'rows'		=> '15',
			'editor'	=> $_SESSION["WYSIWYG_EDITOR"],
			'lang'		=> 'en'
		);
		
		include(PHPWCMS_ROOT.'/include/inc_lib/wysiwyg.editor.inc.php');


		?></td>
	</tr>
	
	<tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="15" /></td></tr>	
	
	<tr>
		<td align="right" class="chatlist"><?php echo $BLM['highlight'] ?>:&nbsp;</td>
		<td><table border="0" cellpadding="0" cellspacing="0" summary="">		
			<tr>
				<td><input type="checkbox" name="glossary_highlight" id="glossary_highlight" value="1"<?php is_checked($glossary['data']['glossary_highlight'], 1) ?> /></td>
				<td><label for="glossary_highlight"><?php echo $BLM['highlight_descr'] ?></label></td>
			</tr>
		</table></td>
	</tr>
	
	<tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="5" /></td></tr>	
	
	<tr>
		<td align="right" class="chatlist"><?php echo $BL['be_ftptakeover_status'] ?>:&nbsp;</td>
		<td><table border="0" cellpadding="0" cellspacing="0" summary="">		
			<tr>
				<td><input type="checkbox" name="glossary_status" id="glossary_status" value="1"<?php is_checked($glossary['data']['glossary_status'], 1) ?> /></td>
				<td><label for="glossary_status"><?php echo $BL['be_cnt_activated'] ?></label></td>
			</tr>
		</table></td>
	</tr>
	
	<tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="10" /></td>
	</tr>
	<tr> 
		<td>&nbsp;</td>
		<td>
			<input name="submit" type="submit" class="button10" value="<?php echo empty($glossary['data']['glossary_id']) ? $BL['be_admin_fcat_button2'] : $BL['be_article_cnt_button1'] ?>" />
			<input name="save" type="submit" class="button10" value="<?php echo $BL['be_article_cnt_button3'] ?>" />
			&nbsp;&nbsp;&nbsp;&nbsp;
			<input name="new" type="button" class="button10" value="<?php echo ucfirst($BL['be_msg_new']) ?>" onclick="location.href='<?php echo decode_entities(GLOSSARY_HREF) ?>&edit=0';return false;" />
			<input name="close" type="button" class="button10" value="<?php echo $BL['be_admin_struct_close'] ?>" onclick="location.href='<?php echo decode_entities(GLOSSARY_HREF) ?>';return false;" />
		</td>
	</tr>

</table>

</form>