<?php
/**
 * phpwcms content management system
 *
 * @author Oliver Georgi <og@phpwcms.org>
 * @copyright Copyright (c) 2002-2015, Oliver Georgi
 * @license http://opensource.org/licenses/GPL-2.0 GNU GPL-2
 * @link http://www.phpwcms.de
 *
 **/

// ----------------------------------------------------------------
// obligate check for phpwcms constants
if (!defined('PHPWCMS_ROOT')) {
	die("You Cannot Access This Script Directly, Have a Nice Day.");
}
// ----------------------------------------------------------------


// PHP variables

?><tr>
<td align="right" valign="top" class="chatlist"><img src="img/leer.gif" alt="" width="1" height="13"><?php echo $BL['be_cnt_vars'] ?>:&nbsp;</td>
<td valign="top"><textarea name="cvar" rows="30" wrap="VIRTUAL" class="code width440" id="cvar"><?php echo isset($content["var"]) ? html($content["var"]) : '' ?></textarea></td>
</tr>