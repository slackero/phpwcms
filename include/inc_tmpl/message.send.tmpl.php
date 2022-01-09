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

$msg_send_ok = 0;
$msg_subject = '';
$msg_message = '';
$msg = 0;
$msg_err = '';

//If this should be a replay to another mail
if(isset($_GET["msg"]) && intval($_GET["msg"]) && empty($_POST['msg_send_aktion'])) {
    list($msg, $msg_read) = explode(":", $_GET["msg"]);
    $msg = intval($msg);
    if($msg) {

        if($msg_read == "I") { //Wenn die Nachricht noch den Status Unread hat, setzen auf read
            $sql =  "UPDATE ".DB_PREPEND."phpwcms_message SET msg_tstamp=msg_tstamp, msg_read=1 WHERE ".
                    "msg_uid=".$_SESSION["wcs_user_id"]." AND msg_id=".$msg;
            _dbQuery($sql, 'UPDATE');
        }

        $sql =  "SELECT *, DATE_FORMAT(phpwcms_message.msg_tstamp, '%b %e, %Y (%H:%i)') AS send_date ".
                "FROM ".DB_PREPEND."phpwcms_message INNER JOIN ".DB_PREPEND."phpwcms_user ON ".
                DB_PREPEND."phpwcms_message.msg_from=".DB_PREPEND."phpwcms_user.usr_id WHERE ".DB_PREPEND."phpwcms_message.msg_uid=".$_SESSION["wcs_user_id"].
                " AND ".DB_PREPEND."phpwcms_message.msg_id=".$msg." LIMIT 1";
        $result = _dbQuery($sql);

        if(isset($result[0]['msg_subject'])) {
            $msg_subject = $BL['be_msg_RE'].": ".$result[0]["msg_subject"];
            $msg_message  = "\n\n----[".$BL['be_msg_by']." ".$result[0]["usr_name"]." ".$BL['be_msg_on']." ".$result[0]["send_date"]."]----\n";
            $msg_message .= $BL['be_msg_subject'].": ".$result[0]["msg_subject"]."\n".$BL['be_msg_msg'].": ".$result[0]["msg_text"];
            $msg_to = $result[0]["msg_from"];
            $msg_pid = $msg;
        }
    }
}

//Get signature of the user
$result = _dbQuery("SELECT detail_signature FROM ".DB_PREPEND."phpwcms_userdetail WHERE detail_pid=".$_SESSION["wcs_user_id"]." LIMIT 1");
if(isset($result[0]['detail_signature']) && trim($result[0]['detail_signature'])) {
    $msg_message = "\n\n\t\n".$result[0]['detail_signature'].$msg_message;
}

if(isset($_POST['msg_send_aktion']) && intval($_POST['msg_send_aktion'])) {
    $msg_subject    = strip_tags(slweg(trim($_POST["msg_send_subject"])));
    $msg_message    = strip_tags(slweg($_POST["msg_send_msg"]));
    $msg_to         = slweg(trim($_POST["msg_send_receiver"]));
    $msg_pid        = intval($_POST['msg_send_pid']);

    if(str_empty($msg_to)) {
        $msg_err .= "- ".$BL['be_msg_err1']."\n";
    }
    if(str_empty($msg_subject)) {
        $msg_err .= "- ".$BL['be_msg_err2']."\n";
    }
    if(str_empty($msg_message)) {
        $msg_err .= "- ".$BL['be_msg_err3']."\n";
    }

    if(str_empty($msg_err)) {
        //send message routine
        $msg_receivers = explode(":", $msg_to);
        foreach($msg_receivers as $value) {
            $sql =  "INSERT INTO ".DB_PREPEND."phpwcms_message (".
                    "msg_pid, msg_uid, msg_subject, msg_text, msg_to, msg_from) VALUES (".
                    $msg_pid.",".
                    intval($value).",'".
                    aporeplace($msg_subject)."','".
                    aporeplace($msg_message)."','".
                    aporeplace($msg_to)."',".
                    $_SESSION["wcs_user_id"].
                    ")";
            _dbQuery($sql, 'INSERT');
        }
        $msg_send_ok = 1;
    }
}

if($msg_send_ok) {
    echo "<span class=\"title\">".$BL['be_msg_sent']."</span><br /><img src='img/leer.gif' width=1 height=6><br />";
    echo $BL['be_msg_fwd']." <br /><a href='phpwcms.php?do=messages&p=1'>".$BL['be_msg_create']."</a>.";
    $forward_to_message_center = 1;
} else { //Mitteilungszusammenstellung

?>
<form name="sendmsg" action="phpwcms.php?do=messages&p=1" method="post">
<table width="538" border="0" cellpadding="0" cellspacing="0" summary="">
    <tr><td colspan="3" class="title"><?php echo $BL['be_msg_newmsgtitle'] ?></td></tr>
    <tr><td colspan="3"><img src="img/leer.gif" alt="" width="1" height="6"></td></tr>
    <tr><td colspan="3"><img src="img/lines/l538_70.gif" alt="" width="538" height="1"></td></tr>
    <tr><td colspan="3"><img src="img/leer.gif" alt="" width="1" height="3"></td></tr>
<?php
    //Errormeldung wenn Fehler beim Nachrichtenversand
    if(!empty($msg_err)) {
?>
    <tr><td colspan="3"><img src="img/leer.gif" alt="" width="1" height="1"></td></tr>
    <tr><td colspan="3"><strong style="color:#FF6600"><?php echo $BL['be_msg_err'].":<br />".nl2br(chop($msg_err)) ?></strong></td></tr>
    <tr><td colspan="3"><img src="img/leer.gif" alt="" width="1" height="6"></td></tr>
    <tr><td colspan="3"><img src="img/lines/l538_70.gif" alt="" width="538" height="1"></td></tr>
    <tr><td colspan="3"><img src="img/leer.gif" alt="" width="1" height="3"></td></tr>
<?php
  } //Ende Fehler Nachrichtenversand
?>
    <tr>
        <td width="253" class="v09"><?php echo $BL['be_msg_sendto'] ?>:</td>
        <td width="30"><img src="img/leer.gif" alt="" width="30" height="1"></td>
        <td width="255" class="v09"><?php echo $BL['be_msg_available'] ?>:</td>
    </tr>
    <tr valign="top">
        <td class="v09"><select name="msg_send_to" size="10" multiple="multiple" class="width250" onDblClick="opt.transferRight()">
<?php
    $where1 = 'WHERE usr_aktiv=1 ';
    if(!empty($msg_to)) {
        $msg_receivers = explode(":", $msg_to);
        foreach($msg_receivers as $value) {
            if(empty($where)) {
                $where = "usr_id=".intval($value);
                $where1 = "WHERE usr_aktiv=1 AND usr_id<>".intval($value);
            } else {
                $where .= " OR usr_id=".intval($value);
                $where1 .= " AND usr_id<>".intval($value);
            }
        }

        $sql = "SELECT usr_id, usr_login, usr_name FROM ".DB_PREPEND."phpwcms_user WHERE ".$where." ORDER BY usr_name ASC";
        $result = _dbQuery($sql);

        if(isset($result[0]['usr_id'])) {
            foreach($result as $row) {
                echo "<option value=\"".$row['usr_id']."\">".html($row['usr_name']." (".$row['usr_login']).")"."</option>";
            }
        }
    }
?>
        </select></td>
    <td class="v09"><a href="javascript: opt.transferRight();"><img src="img/icons/trash.gif" alt="" width="15" height="15" border="0"></a><input name="msg_send_receiver" type="hidden" id="msg_send_receiver2"><input name="msg_send_aktion" type="hidden" id="msg_send_aktion" value="1"><input name="msg_send_pid" type="hidden" value="<?php echo intval($msg) ?>"></td>
    <td class="v09"><select name="msg_send_list" size="10" multiple="multiple" id="msg_send_list" class="width250" onChange="opt.transferLeft()">
<?php
    //Create the list of possible recipients
    $sql = "SELECT usr_id, usr_login, usr_name FROM ".DB_PREPEND."phpwcms_user ".$where1." ORDER BY usr_name ASC";
    $result = _dbQuery($sql);

    if(isset($result[0]['usr_id'])) {
        foreach($result as $row) {
            echo "<option value=\"".$row['usr_id']."\">".html($row['usr_name']." (".$row['usr_login']).")"."</option>";
        }
    }
?>
        </select></td>
    </tr>
    <tr><td colspan="3"><img src="img/leer.gif" alt="" width="1" height="2"></td></tr>
    <tr><td colspan="3" class="v09"><?php echo $BL['be_msg_subject'] ?>:</td></tr>
    <tr><td colspan="3"><input name="msg_send_subject" type="text" id="msg_send_subject" class="code width540" value="<?php echo html($msg_subject); ?>" size="40" maxlength="125"></td></tr>
    <tr><td colspan="3" class="v09"><?php echo $BL['be_msg_msg'] ?>:</td></tr>
    <tr><td colspan="3"><textarea name="msg_send_msg" cols="40" rows="15" id="msg_send_msg" class="code width540 autosize"><?php echo html($msg_message); ?></textarea></td></tr>
    <tr><td colspan="3"><img src="img/leer.gif" alt="" width="1" height="6"></td></tr>
    <tr><td colspan="3"><input name="submit" type="image" id="submit" src="img/button/send_message.gif" alt="<?php echo $BL['be_msg_all'] ?>" width="87" height="17" border="0"></td></tr>
    <tr><td colspan="3">&nbsp;</td></tr>
</table>
</form>
<?php
} //Ende Mitteilungszusammenstellung
?>