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


$frontend_css = read_textfile(PHPWCMS_TEMPLATE."inc_css/frontend.css"); //reads the css template
$frontend_css = ($frontend_css) ? html($frontend_css) : "";

?><form action="include/inc_act/act_frontendcss.php" method="post" name="css" target="_self">

    <table width="538" border="0" cellpadding="0" cellspacing="0" summary="">

        <tr>
          <td colspan="2" class="title"><?php echo $BL['be_admin_css_title'] ?></td>
        </tr>
        <tr>
            <td width="35"><img src="img/leer.gif" alt="" width="35" height="4"></td>
            <td width="503"><img src="img/leer.gif" alt="" width="1" height="1"></td>
        </tr>
        <tr><td colspan="2"><img src="img/lines/l538_70.gif" alt="" width="538" height="1"></td></tr>
        <tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="15"></td></tr>
        <tr>
            <td align="right" valign="top" class="chatlist"><a name="frontend_css"></a><img src="img/leer.gif" alt="" width="1" height="16"><?php echo $BL['be_admin_css_css'] ?>:&nbsp;</td>
            <td><textarea name="frontend_css" cols="35" rows="20" wrap="off" class="width500 autosize" id="frontend_css"><?php echo $frontend_css; ?></textarea></td>
        </tr>
        <tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="15"></td></tr>
        <tr>
            <td>&nbsp;</td>
            <td><input name="Submit" type="submit" class="button" value="<?php echo $BL['be_admin_css_button'] ?>"></td>
        </tr>
        <tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="15"></td></tr>

    </table>
</form>
