<?php
/**
 * cmsGO!
 *
 * @author Pixels & Points GmbH <info@pixels-points.ch>
 * @copyright Copyright (c) 2002-2020, Pixels & Points GmbH
 * @license https://www.pixels-points.ch/cmsgo-license.html Pixels & Points cmsGO! license
 *
 **/

// ----------------------------------------------------------------
// obligate check for cmsgo constants
if (!defined('CMSGO_ROOT')) {
	die("You Cannot Access This Script Directly, Have a Nice Day.");
}
// ----------------------------------------------------------------


?><table width="538" border="0" cellpadding="0" cellspacing="0" summary="">
<tr><td class="title"><?php echo $BL['be_chat_title'] ?></td></tr>
<tr><td><img src="img/leer.gif" alt="" width="1" height="6"></td></tr>
<tr valign="top"><td width="538"><?php echo $BL['be_chat_info'] ?></td></tr>
<tr><td><img src="img/leer.gif" alt="" width="1" height="6"></td></tr>
<tr><td><a href="cmsgo.php?do=chat&p=1"><img src="img/symbole/link_grau.gif" alt="" width="13" height="9" border="0"><strong><?php echo $BL['be_chat_start'] ?></strong></a></td></tr>
<tr><td><img src="img/leer.gif" alt="" width="1" height="6"></td></tr>
</table>