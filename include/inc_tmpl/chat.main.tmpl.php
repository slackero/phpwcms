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


?><table width="538" border="0" cellpadding="0" cellspacing="0" summary="">
<tr><td class="title"><?php echo $BL['be_chat_title'] ?></td></tr>
<tr><td><img src="img/leer.gif" alt="" width="1" height="6"></td></tr>
<tr valign="top"><td width="538"><?php echo $BL['be_chat_info'] ?></td></tr>
<tr><td><img src="img/leer.gif" alt="" width="1" height="6"></td></tr>
<tr><td><a href="phpwcms.php?do=chat&p=1"><img src="img/symbole/link_grau.gif" alt="" width="13" height="9" border="0"><strong><?php echo $BL['be_chat_start'] ?></strong></a></td></tr>
<tr><td><img src="img/leer.gif" alt="" width="1" height="6"></td></tr>
</table>