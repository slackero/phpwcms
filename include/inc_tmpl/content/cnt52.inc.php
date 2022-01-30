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


// PHP variables

?>
<tr><td colspan="2" class="rowspacer0x7"><img src="img/leer.gif" alt="" width="1" height="1"></td></tr>
<tr>
    <td align="right" class="chatlist tdtop3"><?php echo $BL['be_cnt_vars'] ?>:&nbsp;</td>
    <td><textarea name="cvar" rows="20" wrap="VIRTUAL" class="code width440 autosize" id="cvar"><?php echo isset($content["var"]) ? html($content["var"]) : '' ?></textarea></td>
</tr>