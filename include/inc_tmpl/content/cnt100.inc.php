<?php
/**
 * phpwcms content management system
 *
 * @author Oliver Georgi <og@phpwcms.org>
 * @copyright Copyright (c) 2002-2022, Oliver Georgi
 * @license http://opensource.org/licenses/GPL-2.0 GNU GPL-2
 * @link http://www.phpwcms.org
 *
 **/

// ----------------------------------------------------------------
// obligate check for phpwcms constants
if (!defined('PHPWCMS_ROOT')) {
	die("You Cannot Access This Script Directly, Have a Nice Day.");
}
// ----------------------------------------------------------------


// List

if(empty($content['bulletlist']["list_type"])) $content['bulletlist']["list_type"] = 0;

?>
<tr><td colspan="2" class="rowspacer0x7"><img src="img/leer.gif" alt="" width="1" height="1"></td></tr>

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
	<td><textarea name="ctext" rows="30" class="width440 autosize" id="ctext"><?php echo  isset($content["text"]) ? $content["text"] : '' ?></textarea></td>
</tr>