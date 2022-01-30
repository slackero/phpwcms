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


$chatlist = empty($_GET['l']) ? 0 : intval($_GET['l']); //Prüfen, wieviele Chatmeldungen angezeigt werden sollen
if(!$chatlist) {
    $chatlist = 15; //Standardanzahl Chatmitteilungen
}

require_once(PHPWCMS_ROOT."/include/inc_lib/autolink.inc.php");

?>
<form action="include/inc_act/act_addchat.php" method="post" name="sendchatmessage" target="_top" onSubmit="window.document.cookie='chatstring=';">
    <table width="538" border="0" cellpadding="0" cellspacing="0" summary="">
        <tr>
            <td colspan="2" class="title">
                <table width="490" border="0" cellpadding="0" cellspacing="0" summary="">
                    <tr valign="bottom">
                      <td class="title"><?php echo $BL['be_chat_title'] ?></td>
                      <td align="right" class="chatlist"><?php echo $BL['be_chat_lines'] ?>:&nbsp;<a href="javascript:set_chatlist('5');">5</a>|<a href="javascript:set_chatlist('10');">10</a>|<a href="javascript:set_chatlist('15');">15</a>|<a href="javascript:set_chatlist('25');">25</a>|<a href="javascript:set_chatlist('50');">50</a>|<a href="javascript:set_chatlist('100');">100</a>|<a href="javascript:set_chatlist('99999');">ALL</a><input name="chatlist" type="hidden" value="<?php echo $chatlist ?>"></td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="3"></td></tr>
        <tr>
            <td width="490"><input name="chatmsg" type="text" id="chatmsg" style="font-family: Verdana, Arial, sans-serif; width:490px; font-size: 11px" size="40" maxlength="250" onKeyDown="timer=restart_reload(timer);"></td>
            <td width="48">&nbsp;<input name="Submit" type="image" id="Submit" src="img/button/send_chat_message.gif" width="36" height="15" /></td>
        </tr>
        <tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="5"></td></tr>
        <tr><td colspan="2"><img src="img/lines/l538_70.gif" alt="" width="538" height="1"></td></tr>
        <tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="5"></td></tr>
    </table>
</form>

<table width="538" border="0" cellpadding="0" cellspacing="0" summary="">
<?php
    //Chatlisting
    $sql = "SELECT * FROM ".DB_PREPEND."phpwcms_chat WHERE chat_cat=0 ORDER BY chat_tstamp DESC LIMIT ".$chatlist;
    $result = _dbQuery($sql);

    if(isset($result[0]['chat_uid'])) {
        foreach($result as $row) {

            $chatclass = ($row['chat_uid'] == $_SESSION['wcs_user_id']) ? ' class="chat"' : '';

            echo "<tr valign='top'><td align='right'".$chatclass.">".$row["chat_name"].":&nbsp;</td>";
            echo "<td width='90%'".$chatclass.">".auto_link(html($row["chat_text"]))."</td></tr>";

        } //Chatlisting Ende
    }

?>
    <tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="5"></td></tr>
    <tr><td colspan="2"><img src="img/lines/l538_70.gif" alt="" width="538" height="1"></td></tr>
    <tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="5"></td></tr>
</table>