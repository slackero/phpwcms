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

$no_durchlauf = 0;

//Count message boxes
$sql = "SELECT COUNT(*) FROM ".DB_PREPEND."phpwcms_message WHERE ";

//New Messages
$count_newmsg = _dbQuery($sql .= "msg_uid=".intval($_SESSION["wcs_user_id"])." AND (msg_read=0 OR (NOW()-msg_tstamp<86400)) AND msg_deleted=0", 'COUNT');
//Old Messages
$count_readmsg = _dbQuery($sql .= "msg_uid=".$_SESSION["wcs_user_id"]." AND msg_read=1 AND msg_deleted=0", 'COUNT');
//Sent Messages
$count_sentmsg = _dbQuery($sql .= "msg_from=".$_SESSION["wcs_user_id"]." AND msg_from_del=0", 'COUNT');
//Files in trash
$count_delmsg = _dbQuery($sql .= "(msg_uid=".$_SESSION["wcs_user_id"]." AND msg_deleted=1) OR (msg_from=".$_SESSION["wcs_user_id"]." AND msg_from_del=1)", 'COUNT');

//Ermitteln, wieviele Nachrichten angezeigt werden sollen
$msg_list = empty($_GET['l']) ? 15 : intval($_GET['l']);

//Ermitteln, ob aufsteigend oder absteigend
//0 = normal, absteigend (neueste zuerst) -> 1 = absteigend
$msg_order = empty($_GET['o']) ? 0 : intval($_GET['o']);

//Welche Message soll gerade angezeigt werden
if(empty($_GET["msg"])) {
    $msg = 0;
    $msg_get["msg"] = '';
} else {
    $msg_get["msg"]     = "&msg=".$_GET["msg"];
    list($msg, $msg_read) = explode(":", $_GET["msg"]);
    $msg = intval($msg);
}

//Ermitteln, welcher Message Ordner angezeigt wird
$msg_folder = empty($_GET['f']) ? 0 : intval($_GET['f']);
if($msg_folder == 0 || $msg_folder >= 4) {
    $msg_folder = 0; //new msg
}

//fester GET-Konstrukt + Teile
$msg_get["all"]     = "&o=".$msg_order."&f=".$msg_folder."&l=".$msg_list;
$msg_get["list"]    = "&l=".$msg_list;
$msg_get["order"]   = "&o=".$msg_order;
$msg_get["folder"]  = "&f=".$msg_folder;

?><table width="538" border="0" cellpadding="0" cellspacing="0" summary="">
        <tr><td class="title"><?php echo $BL['be_msg_title'] ?></td></tr>
        <tr><td><img src="img/leer.gif" alt="" width="1" height="6"></td></tr>
        <tr><td><table width="538" border="0" cellpadding="2" cellspacing="0" summary=""><tr>
         <td width="70" align="center" background="img/background/bg_eckeli.gif" <?php which_folder_active($msg_folder, 0) ?>><a href="phpwcms.php?do=messages<?php echo $msg_get["list"].$msg_get["order"]."&f=0" ?>"><?php echo $count_newmsg." ".$BL['be_msg_new'] ?></a></td>
         <td width="69" align="center" background="img/background/bg_eckeli.gif" <?php which_folder_active($msg_folder, 1) ?>><a href="phpwcms.php?do=messages<?php echo $msg_get["list"].$msg_get["order"]."&f=1" ?>"><?php echo $count_readmsg." ".$BL['be_msg_old'] ?></a></td>
         <td width="70" align="center" background="img/background/bg_eckeli.gif" <?php which_folder_active($msg_folder, 2) ?>><a href="phpwcms.php?do=messages<?php echo $msg_get["list"].$msg_get["order"]."&f=2" ?>"><?php echo $count_sentmsg." ".$BL['be_msg_senttop'] ?></a></td>
         <td width="70" align="center" background="img/background/bg_eckeli.gif" <?php which_folder_active($msg_folder, 3) ?>><a href="phpwcms.php?do=messages<?php echo $msg_get["list"].$msg_get["order"]."&f=3" ?>"><?php echo $count_delmsg." ".$BL['be_msg_del'] ?></a></td>
         <td width="239" align="right" bgcolor="#FFFFFF" class="chatlist">
            <a href="phpwcms.php?do=messages<?php echo $msg_get["folder"].$msg_get["order"]."&l=10".$msg_get["msg"] ?>">10</a>
            <a href="phpwcms.php?do=messages<?php echo $msg_get["folder"].$msg_get["order"]."&l=25".$msg_get["msg"] ?>">25</a>
            <a href="phpwcms.php?do=messages<?php echo $msg_get["folder"].$msg_get["order"]."&l=50".$msg_get["msg"] ?>">50</a>
            <a href="phpwcms.php?do=messages<?php echo $msg_get["folder"].$msg_get["order"]."&l=100".$msg_get["msg"] ?>">100</a>
            <a href="phpwcms.php?do=messages<?php echo $msg_get["folder"].$msg_get["order"]."&l=500".$msg_get["msg"] ?>">250</a>
            <a href="phpwcms.php?do=messages<?php echo $msg_get["folder"].$msg_get["order"]."&l=99999".$msg_get["msg"] ?>"><?php echo $BL['be_ftptakeover_all'] ?></a>
        </td>
        </tr></table></td>
        <tr><td bgcolor="#9BBECA"><img src="img/leer.gif" alt="" width="1" height="4"></td></tr>
        <tr><td><img src="img/leer.gif" alt="" width="1" height="6"></td></tr>
      </table>
            <?php
            //Read the List of User_ID and User_Name
            $msg_user = _dbQuery("SELECT usr_id, usr_login, usr_name, usr_email FROM ".DB_PREPEND."phpwcms_user");

            if(isset($msg_user[0]['usr_id'])) {
                foreach($msg_user as $msg_user_result) {
                    $msg_user_list[$msg_user_result["usr_id"]] = $msg_user_result["usr_login"]."###".$msg_user_result["usr_name"]."###".$msg_user_result["usr_email"];
                }
            }

            //Wenn Nachricht angezeigt werden soll
            if(!empty($_GET["msg"]) && intval($_GET["msg"])) {
                if($msg_read == "I" && $msg) { //Wenn die Nachricht noch den Status Unread hat, setzen auf read
                    $sql =  "UPDATE ".DB_PREPEND."phpwcms_message SET msg_tstamp=msg_tstamp, msg_read=1 WHERE ".
                            "msg_uid=".$_SESSION["wcs_user_id"]." AND msg_id=".$msg;
                    _dbQuery($sql);
                }

                if($msg) {
                    $sql =  "SELECT msg_id, msg_pid, msg_uid, msg_subject, msg_from, msg_read, msg_deleted, ".
                            "msg_from_del, msg_to, msg_text, DATE_FORMAT(msg_tstamp, '%b %e, %Y (%H:%i)') AS msg_date ".
                            "FROM ".DB_PREPEND."phpwcms_message WHERE ((msg_uid=".$_SESSION["wcs_user_id"]." AND msg_deleted<>9)".
                            " OR (msg_from=".$_SESSION["wcs_user_id"]." AND msg_from_del<>9)) AND msg_id=".$msg.
                            " LIMIT 1";
                    $result = _dbQuery($sql);
                    if(isset($result[0]['msg_id'])) {
                        $msgdetail = $result[0];

                        include "include/inc_lib/autolink.inc.php";

                        if($msgdetail["msg_from"] == $_SESSION["wcs_user_id"]) {
                            $do_move = 2;
                        }
                        if($msgdetail["msg_uid"] == $_SESSION["wcs_user_id"]) {
                            $do_move = 1;
                        }
      ?>
      <table width="538" border="0" cellpadding="0" cellspacing="0" summary="">
        <tr><td colspan="3"><img src="img/lines/l538_70.gif" alt="" width="538" height="1"></td></tr>
        <tr>
          <td width="4"><img src="img/leer.gif" alt="" width="4" height="1"></td>
          <td><img src="img/leer.gif" alt="" width="530" height="1"></td>
          <td width="4"><img src="img/leer.gif" alt="" width="4" height="1"></td>
        </tr>
        <tr bgcolor="#FFE57F">
          <td width="4"><img src="img/leer.gif" alt="" width="4" height="18"></td>
          <td width="530"><span class="v10"><?php echo $BL['be_msg_from'] ?>:</span> <?php echo gib_part($msg_user_list[$msgdetail["msg_from"]], 1, "###")." (".gib_part($msg_user_list[$msgdetail["msg_from"]], 0, "###").")"; ?>&nbsp;&nbsp;<span class="v10"><?php echo $BL['be_msg_date'] ?>:</span> <?php echo $msgdetail["msg_date"] ?></td>
          <td width="4"><img src="img/leer.gif" alt="" width="4" height="1"></td>
        </tr>
        <tr><td colspan="3"><img src="img/leer.gif" alt="" width="1" height="1"></td></tr>
        <tr><td colspan="3" class="title"><img src="img/lines/l538_70.gif" alt="" width="538" height="1"></td></tr>
        <tr><td colspan="3"><img src="img/leer.gif" alt="" width="1" height="1"></td></tr>
        <tr bgcolor="#FFF8DF"><td colspan="3"><img src="img/leer.gif" alt="" width="1" height="1"></td></tr>
        <tr bgcolor="#FFF8DF">
          <td width=4><img src="img/leer.gif" alt="" width="4" height="1"></td>
          <td class="msgtext"><strong><?php echo html($msgdetail["msg_subject"]) ?></strong></td>
          <td width=4><img src="img/leer.gif" alt="" width="4" height="1"></td>
        </tr>
        <tr bgcolor="#FFF8DF">
          <td width=4><img src="img/leer.gif" alt="" width="4" height="1"></td>
          <td class="msgtext"><?php echo auto_link(nl2br(html($msgdetail["msg_text"]))) ?></td>
          <td width=4><img src="img/leer.gif" alt="" width="4" height="1"></td>
        </tr>
        <tr bgcolor="#FFF8DF"><td colspan="3"><img src="img/leer.gif" alt="" width="1" height="5"></td></tr>
        <tr bgcolor="#FFF8DF">
          <td width=4><img src="img/leer.gif" alt="" width="4" height="1"></td>
          <td><a href="phpwcms.php?do=messages<?php echo $msg_get["all"] ?>" title="<?php echo $BL['be_msg_close'] ?>"><img src="img/button/close_message.gif" alt="" width="14" height="14" border="0"></a><img src="img/leer.gif" alt="" width="12" height="1"><a href="phpwcms.php?do=messages&p=1" title="<?php echo $BL['be_msg_create'] ?>"><img src="img/button/new_message1.gif" alt="" width="68" height="14" border="0"></a><img src="img/leer.gif" alt="" width="4" height="1"><a href="phpwcms.php?do=messages&p=1&msg=<?php echo $msgdetail["msg_id"].":"; if(!$msgdetail["msg_read"]) echo "I"; ?>" title="<?php echo $BL['be_msg_reply'] ?>"><img src="img/button/reply_to_message1.gif" alt="" width="83" height="14" border="0"></a><img src="img/leer.gif" alt="" width="4" height="1"><?php if($msg_folder<>3) {?><a href="include/inc_act/act_message.php?do=<?php echo $do_move ?>.<?php echo $msgdetail["msg_id"] ?>.1" title="<?php echo $BL['be_msg_move'] ?>"><img src="img/button/message_to_trash.gif" alt="" width="71" height="14" border="0"></a><?php } // ?></td>
          <td width=4><img src="img/leer.gif" alt="" width="4" height="1"></td>
        </tr>
        <tr bgcolor="#FFF8DF"><td colspan="3"><img src="img/leer.gif" alt="" width="1" height="3"></td></tr>
        <tr><td colspan="3"><img src="img/leer.gif" alt="" width="1" height="1"></td></tr>
        <tr><td colspan="3"><img src="img/lines/l538_70.gif" alt="" width="538" height="1"></td></tr>
        <tr><td colspan="3"><img src="img/leer.gif" alt="" width="1" height="10"></td></tr>
      </table>
      <?php
                    } //Bedingung für Abfrage
                } //Ende Anzeige komplette gewählte Nachricht
            } //Ende Anzeigen Nachricht

            if($count_newmsg && $msg_folder==0) { //Wenn Count > 0 dann Listing der neuen Nachrichten
            ?><table width="538" border="0" cellpadding="0" cellspacing="0" summary="">
    <tr><td colspan="4"><strong style="color:#9BBECA;"><?php echo $BL['be_msg_unread'] ?></strong></td></tr>
    <tr><td colspan="4"><img src="img/leer.gif" alt="" width="1" height="3"></td></tr>
    <tr><td colspan="4"><img src="img/lines/l538_70.gif" alt="" width="538" height="1"></td></tr>
    <tr><td colspan="4"><img src="img/leer.gif" alt="" width="1" height="1"></td></tr>
        <tr bgcolor="#EBF2F4">
          <td width="135" class="columnhead"><img src="img/leer.gif" alt="" width="1" height="1"><span style="color:#727889"><?php echo $BL['be_msg_from'] ?></span>:</td>
          <td width="250" class="columnhead"><?php echo $BL['be_msg_subject'] ?>:</td>
          <td width="93" class="columnhead"><?php echo $BL['be_msg_date'] ?>:</td>
          <td width="60"><img src="img/leer.gif" alt="" width="60" height="1"></td>
    </tr>
    <tr><td colspan="4"><img src="img/leer.gif" alt="" width="1" height="1"></td></tr>
    <tr><td colspan="4"><img src="img/lines/l538_70.gif" alt="" width="538" height="1"></td></tr>
    <tr><td colspan="4"><img src="img/leer.gif" alt="" width="1" height="2"></td></tr>
<?php
    //Listing new messages
    $sql =  "SELECT msg_id, msg_pid, msg_uid, msg_subject, msg_from, msg_read, ".
            "DATE_FORMAT(msg_tstamp, '%m/%d/%y %H:%i') AS msg_date ".
            "FROM ".DB_PREPEND."phpwcms_message WHERE msg_uid=".$_SESSION["wcs_user_id"].
            " AND (msg_read=0 OR (NOW()-msg_tstamp<86400)) AND msg_deleted=0 ".
            "ORDER BY msg_tstamp DESC LIMIT ".$msg_list;
    $result = _dbQuery($sql);

    if(isset($result[0]['msg_id'])) {
    //falls new messages listing schleife
        $bg_color1 = "#FFFFFF";
        $bg_color2 = "#F5F9FA";
        $zaehler = 0;
        foreach($result as $row) {
            $bg_color = ($zaehler % 2) ? $bg_color2 : $bg_color1;
            $goto = "phpwcms.php?do=messages".$msg_get["folder"].$msg_get["order"].$msg_get["list"]."&msg=".$row["msg_id"].":";
            if(!$row["msg_read"]) {
                $goto .= "I";
            }
            if($msg == $row["msg_id"]) {
                $bg_color = "#FFCC00";
            }
?>
        <tr onMouseOver="bgColor='#FFCC00'" onMouseOut="bgColor='<?php echo $bg_color ?>'" onclick="location.href='<?php echo $phpwcms["site"].$goto ?>';">
          <td bgcolor="<?php echo $bg_color ?>" class="msglist"><img src="img/leer.gif" alt="" width="1" height="1"><a href="<?php echo $goto ?>" title="<?php echo $row["msg_subject"] ?>"><?php echo gib_part($msg_user_list[$row["msg_from"]], 1, "###"); ?></a></td>
          <td bgcolor="<?php echo $bg_color ?>" class="msglist"><a href="<?php echo $goto ?>" title="<?php echo $row["msg_subject"] ?>"><?php echo cut_string($row["msg_subject"], "&#8230;", 40) ?></a></td>
          <td width="93" bgcolor="<?php echo $bg_color ?>" class="msglist"><?php echo $row["msg_date"] ?></td>
          <td width="60" align="right" bgcolor="<?php echo $bg_color ?>" class="v10"><a href="phpwcms.php?do=messages&p=1&msg=<?php echo $row["msg_id"].":"; if(!$row["msg_read"]) echo "I"; ?>"><img src="img/button/reply_mini.gif" alt="" width="40" height="15" border="0"></a><a href="include/inc_act/act_message.php?do=1.<?php echo $row["msg_id"] ?>.1"><img src="img/icons/trash.gif" alt="" width="15" height="15" border="0"></a></td>
    </tr>
<?php
            $zaehler++;
        } //Ende Listing Schleife
    } //Ende Listing new messages
?>
    <tr><td colspan="4"><img src="img/leer.gif" alt="" width="1" height="2"></td></tr>
    <tr><td colspan="4"><img src="img/lines/l538_70.gif" alt="" width="538" height="1"></td></tr>
    <tr>
        <td><img src="img/leer.gif" alt="" width="135" height="1"></td>
        <td><img src="img/leer.gif" alt="" width="250" height="1"></td>
        <td width="93"><img src="img/leer.gif" alt="" width="93" height="1"></td>
        <td width="60"><img src="img/leer.gif" alt="" width="60" height="10"></td>
    </tr>
</table><?php
            $no_durchlauf++;
            } //Ende Anzeige unglesene Mitteilungen


            if($count_readmsg && $msg_folder==1) { //Wenn Count > 0 dann Listing der bereits gelesenen Nachrichten
            ?><table width="538" border="0" cellpadding="0" cellspacing="0" summary="">
        <tr><td colspan="4"><strong style="color:#9BBECA"><?php echo str_replace('{VAL}', $msg_list, $BL['be_msg_lastread']); ?></strong></td></tr>
        <tr><td colspan="4"><img src="img/leer.gif" alt="" width="1" height="3"></td></tr>
        <tr><td colspan="4"><img src="img/lines/l538_70.gif" alt="" width="538" height="1"></td></tr>
        <tr><td colspan="4"><img src="img/leer.gif" alt="" width="1" height="1"></td></tr>
        <tr bgcolor="#EBF2F4">
          <td width="135" class="columnhead"><img src="img/leer.gif" alt="" width="1" height="1"><span style="color:#727889"><?php echo $BL['be_msg_from'] ?></span>:</td>
          <td width="250" class="columnhead"><?php echo $BL['be_msg_subject'] ?>:</td>
          <td width="93" class="columnhead"><?php echo $BL['be_msg_date'] ?>:</td>
          <td width="60"><img src="img/leer.gif" alt="" width="60" height="1"></td>
        </tr>
        <tr><td colspan="4"><img src="img/leer.gif" alt="" width="1" height="1"></td></tr>
        <tr><td colspan="4"><img src="img/lines/l538_70.gif" alt="" width="538" height="1"></td></tr>
        <tr><td colspan="4"><img src="img/leer.gif" alt="" width="1" height="2"></td></tr>
        <?php
    //Listing new messages
    $sql =  "SELECT msg_id, msg_pid, msg_uid, msg_subject, msg_from, msg_read, ".
            "DATE_FORMAT(msg_tstamp, '%m/%d/%y %H:%i') AS msg_date ".
            "FROM ".DB_PREPEND."phpwcms_message WHERE msg_uid=".$_SESSION["wcs_user_id"].
            " AND msg_read=1 AND msg_deleted=0 ORDER BY msg_tstamp DESC LIMIT ".$msg_list;
    $result = _dbQuery($sql);

    if(isset($result[0]['msg_id'])) {
    //falls new messages listing schleife
        $bg_color1 = "#FFFFFF";
        $bg_color2 = "#F5F9FA";
        $zaehler = 0;
        foreach($result as $row) {
            $bg_color = ($zaehler % 2) ? $bg_color2 : $bg_color1;
            $goto = "phpwcms.php?do=messages".$msg_get["folder"].$msg_get["order"].$msg_get["list"]."&msg=".$row["msg_id"].":";
            if(!$row["msg_read"]) {
                $goto .= "I";
            }
            if($msg == $row["msg_id"]) {
                $bg_color = "#FFCC00";
            }
?>
        <tr bgcolor="<?php echo $bg_color ?>" onMouseOver="bgColor='#FFCC00'" onMouseOut="bgColor='<?php echo $bg_color ?>'" onclick="location.href='<?php echo $phpwcms["site"].$goto ?>';">
          <td class="msglist"><img src="img/leer.gif" alt="" width="1" height="1"><a href="<?php echo $goto ?>" title="<?php echo $row["msg_subject"] ?>"><?php echo gib_part($msg_user_list[$row["msg_from"]], 1, "###"); ?></a></td>
          <td class="msglist"><a href="<?php echo $goto ?>" title="<?php echo $row["msg_subject"] ?>"><?php echo cut_string($row["msg_subject"], "&#8230;", 40) ?></a></td>
          <td width="93" class="msglist"><?php echo $row["msg_date"] ?></td>
          <td width="60" align="right" class="v10"><a href="phpwcms.php?do=messages&p=1&msg=<?php echo $row["msg_id"].":"; if(!$row["msg_read"]) echo "I"; ?>"><img src="img/button/reply_mini.gif" alt="" width="40" height="15" border="0"></a><a href="include/inc_act/act_message.php?do=1.<?php echo $row["msg_id"] ?>.1"><img src="img/icons/trash.gif" alt="" width="15" height="15" border="0"></a></td>
        </tr>
        <?php
            $zaehler++;
        } //Ende Listing Schleife
    } //Ende Listing new messages
?>
        <tr><td colspan="4"><img src="img/leer.gif" alt="" width="1" height="2"></td></tr>
        <tr><td colspan="4"><img src="img/lines/l538_70.gif" alt="" width="538" height="1"></td></tr>
        <tr>
          <td><img src="img/leer.gif" alt="" width="135" height="1"></td>
          <td><img src="img/leer.gif" alt="" width="250" height="1"></td>
          <td width="93"><img src="img/leer.gif" alt="" width="93" height="1"></td>
          <td width="60"><img src="img/leer.gif" alt="" width="60" height="10"></td>
        </tr>
      </table>
      <?php
            $no_durchlauf++;
            } //Ende Anzeige gelesene Mitteilungen


            if($count_sentmsg && $msg_folder==2) { //Wenn Count > 0 dann Listing der neuen Nachrichten
            ?><table width="538" border="0" cellpadding="0" cellspacing="0" summary="">
    <tr><td colspan="4"><strong style="color:#9BBECA"><?php echo str_replace('{VAL}', $msg_list, $BL['be_msg_lastsent']); ?></strong></td></tr>
    <tr><td colspan="4"><img src="img/leer.gif" alt="" width="1" height="3"></td></tr>
    <tr><td colspan="4"><img src="img/lines/l538_70.gif" alt="" width="538" height="1"></td></tr>
    <tr><td colspan="4"><img src="img/leer.gif" alt="" width="1" height="1"></td></tr>
    <tr bgcolor="#EBF2F4">
          <td width="135" class="columnhead"><img src="img/leer.gif" alt="" width="1" height="1"><span style="color:#727889"><?php echo $BL['be_msg_from'] ?></span>:</td>
          <td width="250" class="columnhead"><?php echo $BL['be_msg_subject'] ?>:</td>
          <td width="93" class="columnhead"><?php echo $BL['be_msg_date'] ?>:</td>
          <td width="60"><img src="img/leer.gif" alt="" width="60" height="1"></td>
    </tr>
    <tr><td colspan="4"><img src="img/leer.gif" alt="" width="1" height="1"></td></tr>
    <tr><td colspan="4"><img src="img/lines/l538_70.gif" alt="" width="538" height="1"></td></tr>
    <tr><td colspan="4"><img src="img/leer.gif" alt="" width="1" height="2"></td></tr>
<?php
    //Listing new messages
    $sql =  "SELECT msg_id, msg_pid, msg_uid, msg_subject, msg_from, msg_read, ".
            "DATE_FORMAT(msg_tstamp, '%m/%d/%y %H:%i') AS msg_date ".
            "FROM ".DB_PREPEND."phpwcms_message WHERE msg_from=".$_SESSION["wcs_user_id"].
            " AND msg_from_del=0 ORDER BY msg_tstamp DESC LIMIT ".$msg_list;
    $result = _dbQuery($sql);

    if(isset($result[0]['msg_id'])) {
    //falls new messages listing schleife
        $bg_color1 = "#FFFFFF";
        $bg_color2 = "#F5F9FA";
        $zaehler = 0;
        foreach($result as $row) {
            $bg_color = ($zaehler % 2) ? $bg_color2 : $bg_color1;
            $goto = "phpwcms.php?do=messages".$msg_get["folder"].$msg_get["order"].$msg_get["list"]."&msg=".$row["msg_id"].":";
            if(!$row["msg_read"]) {
                $goto .= "I";
            }
            if($msg == $row["msg_id"]) {
                $bg_color = "#FFCC00";
            }
?>
    <tr onMouseOver="bgColor='#FFCC00'" onMouseOut="bgColor='<?php echo $bg_color ?>'" onclick="location.href='<?php echo $phpwcms["site"].$goto ?>';">
        <td bgcolor="<?php echo $bg_color ?>" class="msglist"><img src="img/leer.gif" alt="" width="1" height="1"><a href="<?php echo $goto ?>" title="<?php echo $row["msg_subject"] ?>"><?php echo gib_part($msg_user_list[$row["msg_from"]], 1, "###"); ?></a></td>
        <td bgcolor="<?php echo $bg_color ?>" class="msglist"><a href="<?php echo $goto ?>" title="<?php echo $row["msg_subject"] ?>"><?php echo cut_string($row["msg_subject"], "&#8230;", 40) ?></a></td>
        <td width="93" bgcolor="<?php echo $bg_color ?>" class="msglist"><?php echo $row["msg_date"] ?></td>
        <td width="60" align="right" bgcolor="<?php echo $bg_color ?>" class="v10"><a href="phpwcms.php?do=messages&p=1&msg=<?php echo $row["msg_id"].":"; if(!$row["msg_read"]) echo "I"; ?>"><img src="img/button/reply_mini.gif" alt="" width="40" height="15" border="0"></a><a href="include/inc_act/act_message.php?do=2.<?php echo $row["msg_id"] ?>.1"><img src="img/icons/trash.gif" alt="" width="15" height="15" border="0"></a></td>
    </tr>
<?php
            $zaehler++;
        } //Ende Listing Schleife
    } //Ende Listing new messages
?>
    <tr><td colspan="4"><img src="img/leer.gif" alt="" width="1" height="2"></td></tr>
    <tr><td colspan="4"><img src="img/lines/l538_70.gif" alt="" width="538" height="1"></td></tr>
    <tr>
        <td><img src="img/leer.gif" alt="" width="135" height="1"></td>
        <td><img src="img/leer.gif" alt="" width="250" height="1"></td>
        <td width="93"><img src="img/leer.gif" alt="" width="93" height="1"></td>
        <td width="60"><img src="img/leer.gif" alt="" width="60" height="10"></td>
    </tr>
</table><?php
            $no_durchlauf++;
    } //Ende Anzeige unglesene Mitteilungen

    if($count_delmsg && $msg_folder==3) { //Wenn Count > 0 dann Listing der neuen Nachrichten
            ?><table width="538" border="0" cellpadding="0" cellspacing="0" summary="">
    <tr><td colspan="4"><strong style="color:#9BBECA"><?php echo $BL['be_msg_marked'] ?></strong></td></tr>
    <tr><td colspan="4"><img src="img/leer.gif" alt="" width="1" height="3"></td></tr>
    <tr><td colspan="4"><img src="img/lines/l538_70.gif" alt="" width="538" height="1"></td></tr>
    <tr><td colspan="4"><img src="img/leer.gif" alt="" width="1" height="1"></td></tr>
    <tr bgcolor="#EBF2F4">
          <td width="135" class="columnhead"><img src="img/leer.gif" alt="" width="1" height="1"><span style="color:#727889"><?php echo $BL['be_msg_from'] ?></span>:</td>
          <td width="250" class="columnhead"><?php echo $BL['be_msg_subject'] ?>:</td>
          <td width="93" class="columnhead"><?php echo $BL['be_msg_date'] ?>:</td>
          <td width="60"><img src="img/leer.gif" alt="" width="60" height="1"></td>
    </tr>
    <tr><td colspan="4"><img src="img/leer.gif" alt="" width="1" height="1"></td></tr>
    <tr><td colspan="4"><img src="img/lines/l538_70.gif" alt="" width="538" height="1"></td></tr>
    <tr><td colspan="4"><img src="img/leer.gif" alt="" width="1" height="2"></td></tr>
<?php
    //Listing new messages
    $sql =  "SELECT msg_id, msg_pid, msg_uid, msg_subject, msg_from, msg_read, ".
            "DATE_FORMAT(msg_tstamp, '%m/%d/%y %H:%i') AS msg_date, msg_from_del, msg_deleted ".
            "FROM ".DB_PREPEND."phpwcms_message WHERE (msg_uid=".$_SESSION["wcs_user_id"]." AND msg_deleted=1) OR ".
            "(msg_from=".$_SESSION["wcs_user_id"]." AND msg_from_del=1) ".
            "ORDER BY msg_tstamp DESC LIMIT ".$msg_list;
    $result = _dbQuery($sql);
    if(isset($result[0]['msg_id'])) {
        //falls new messages listing schleife
        $bg_color1 = "#FFFFFF";
        $bg_color2 = "#F5F9FA";
        $zaehler = 0;
        foreach($result as $row) {
            $bg_color = ($zaehler % 2) ? $bg_color2 : $bg_color1;
            $goto = "phpwcms.php?do=messages".$msg_get["folder"].$msg_get["order"].$msg_get["list"]."&msg=".$row["msg_id"].":";
            if(!$row["msg_read"]) {
                $goto .= "I";
            }
            if($msg == $row["msg_id"]) {
                $bg_color = "#FFCC00";
            }
            // which action?
            if($row["msg_from_del"] == 1 && $row["msg_from"] == $_SESSION["wcs_user_id"]) {
                $do_undo = 4; //Undo sent message
            }
            if($row["msg_deleted"] == 1 && $row["msg_uid"] == $_SESSION["wcs_user_id"]) {
                $do_undo = 3; //Undo normal message
            }
            if($row["msg_from_del"] == 1 && $row["msg_from"] == $_SESSION["wcs_user_id"]) {
                $do_del = 6; //Delete sent message
            }
            if($row["msg_deleted"] == 1 && $row["msg_uid"] == $_SESSION["wcs_user_id"]) {
                $do_del = 5; //Delete normal message
            }
?>
        <tr onMouseOver="bgColor='#FFCC00'" onMouseOut="bgColor='<?php echo $bg_color ?>'" onclick="location.href='<?php echo $phpwcms["site"].$goto ?>';">
          <td bgcolor="<?php echo $bg_color ?>" class="msglist"><img src="img/leer.gif" alt="" width="1" height="1"><a href="<?php echo $goto ?>" title="<?php echo $row["msg_subject"] ?>"><?php echo gib_part($msg_user_list[$row["msg_from"]], 1, "###"); ?></a></td>
          <td bgcolor="<?php echo $bg_color ?>" class="msglist"><a href="<?php echo $goto ?>" title="<?php echo $row["msg_subject"] ?>"><?php echo cut_string($row["msg_subject"], "&#8230;", 40) ?></a></td>
          <td width="93" bgcolor="<?php echo $bg_color ?>" class="msglist"><?php echo $row["msg_date"] ?></td>
          <td width="60" align="right" bgcolor="<?php echo $bg_color ?>" class="v10"><a href="include/inc_act/act_message.php?do=<?php echo $do_undo ?>.<?php echo $row["msg_id"] ?>.0"><img src="img/button/undo_message.gif" alt="" width="29" height="15" border="0"></a><a href="include/inc_act/act_message.php?do=<?php echo $do_del ?>.<?php echo $row["msg_id"] ?>.9"><img src="img/button/del_message_final.gif" alt="" width="22" height="15" border="0"></a></td>
    </tr>
<?php
            $zaehler++;
        } //Ende Listing Schleife
    } //Ende Listing new messages
?>
    <tr><td colspan="4"><img src="img/leer.gif" alt="" width="1" height="2"></td></tr>
    <tr><td colspan="4"><img src="img/lines/l538_70.gif" alt="" width="538" height="1"></td></tr>
    <tr>
        <td><img src="img/leer.gif" alt="" width="135" height="1"></td>
        <td><img src="img/leer.gif" alt="" width="250" height="1"></td>
        <td width="93"><img src="img/leer.gif" alt="" width="93" height="1"></td>
        <td width="60"><img src="img/leer.gif" alt="" width="60" height="10"></td>
    </tr>
</table><?php
            $no_durchlauf++;
        } //Ende Anzeige Dateien im Papierkorb

if(!$no_durchlauf) {
    echo $BL['be_msg_nomsg']."<br /><img src='img/leer.gif' width=1 height=4>";
}
echo "<table width=538 border=0 cellspacing=0 cellpadding=0>\n";
echo "<tr><td bgcolor='#9BBECA'><img src='img/leer.gif' width=1 height=4></td><tr>\n";
echo "<tr><td><img src='img/leer.gif' width=1 height=15></td><tr>\n";
echo "</table>\n";
