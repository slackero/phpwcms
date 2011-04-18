<?php
/*************************************************************************************
   Copyright notice
   
   (c) 2002-2011 Oliver Georgi (oliver@phpwcms.de) // All rights reserved.
 
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


// List

if(empty($content['bulletlist']["list_type"])) $content['bulletlist']["list_type"] = 0;

?>
<tr><td colspan="2"><img src="img/lines/l538_70.gif" alt="" width="538" height="1"></td></tr>
<tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="8"></td></tr>
<tr>
	<td align="right" class="chatlist"><?php echo $BL['be_cnt_ullist'] ?>:&nbsp;</td>
	<td><table border="0" cellpadding="1" cellspacing="0" summary="">
			<tr bgcolor="#E7E8EB">
				<td><input type="radio" name="clist_type" id="clist_type0" value="0"<?php echo is_checked('0', $content['bulletlist']["list_type"], 0, 0) ?> /></td>
				<td class="v10"><label for="clist_type0">&lt;ul&gt;&nbsp;</label>&nbsp;</td>
				<td><input type="radio" name="clist_type" id="clist_type1" value="1"<?php echo is_checked('1', $content['bulletlist']["list_type"], 0, 0) ?> /></td>
				<td class="v10"><label for="clist_type1">&lt;ol&gt;&nbsp;</label>&nbsp;</td>
				<td><input type="radio" name="clist_type" id="clist_type2" value="2"<?php echo is_checked('2', $content['bulletlist']["list_type"], 0, 0) ?> /></td>
				<td class="v10"><label for="clist_type2">&lt;dl&gt;&nbsp;</label>&nbsp;</td>
			</tr>
	</table></td>
</tr>
<tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="3"></td></tr>
<tr>
	<td>&nbsp;</td>
	<td class="chatlist"><?php echo $BL['be_cnt_ullist_desc'] ?></td>
</tr>
<tr>
	<td>&nbsp;</td>
	<td><textarea name="ctext" rows="30" class="f11" id="ctext" style="width: 440px"><?php echo  isset($content["text"]) ? $content["text"] : '' ?></textarea></td>
</tr>