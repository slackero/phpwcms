<?php
/**
 * phpwcms
 *
 * @author Oliver Georgi <og@phpwcms.org>
 * @copyright Copyright (c) 2002-2026, Oliver Georgi
 * @license http://opensource.org/licenses/GPL-2.0 GNU GPL-2
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

if ($msg_send_ok) {
    echo '<div class="alert alert-success shadow-sm mb-4">';
    echo '<h5 class="alert-heading">' . $BL['be_msg_sent'] . '</h5>';
    echo '<p class="mb-0">' . $BL['be_msg_fwd'] . ' <a href="phpwcms.php?do=messages&amp;p=1" class="alert-link">' . $BL['be_msg_create'] . '</a>.</p>';
    echo '</div>';
    $forward_to_message_center = 1;
} else { //Mitteilungszusammenstellung

?>
<form name="sendmsg" action="phpwcms.php?do=messages&amp;p=1" method="post">
<div class="card shadow-sm mb-4">
    <div class="card-header font-weight-bold py-2">
        <?php echo $BL['be_msg_newmsgtitle']; ?>
    </div>
    <div class="card-body">
        <?php if (!empty($msg_err)): ?>
            <div class="alert alert-danger mb-4">
                <strong><?php echo $BL['be_msg_err']; ?>:</strong><br>
                <?php echo nl2br(chop($msg_err)); ?>
            </div>
        <?php endif; ?>

        <div class="form-group row">
            <div class="col-md-5">
                <label for="msg_send_to" class="font-weight-bold"><?php echo $BL['be_msg_sendto']; ?>:</label>
                <select name="msg_send_to" size="10" multiple="multiple" class="form-control" onDblClick="opt.transferRight()">
<?php
    $where1 = 'WHERE usr_aktiv=1 ';
    if (!empty($msg_to)) {
        $msg_receivers = explode(":", $msg_to);
        foreach ($msg_receivers as $value) {
            if (empty($where)) {
                $where = "usr_id=" . intval($value);
                $where1 = "WHERE usr_aktiv=1 AND usr_id<>" . intval($value);
            } else {
                $where .= " OR usr_id=" . intval($value);
                $where1 .= " AND usr_id<>" . intval($value);
            }
        }

        $sql = "SELECT usr_id, usr_login, usr_name FROM " . DB_PREPEND . "phpwcms_user WHERE " . $where . " ORDER BY usr_name ASC";
        $result = _dbQuery($sql);

        if (isset($result[0]['usr_id'])) {
            foreach ($result as $row) {
                echo "<option value=\"" . $row['usr_id'] . "\">" . html($row['usr_name'] . " (" . $row['usr_login']) . ")" . "</option>";
            }
        }
    }
?>
                </select>
            </div>

            <div class="col-md-2 d-flex flex-column align-items-center justify-content-center my-2 my-md-0">
                <button type="button" class="btn btn-sm btn-secondary mb-2" onclick="opt.transferRight();" title="Remove selected">
                    <i class="fa fa-arrow-right d-none d-md-inline"></i>
                    <i class="fa fa-arrow-down d-inline d-md-none"></i>
                </button>
                <button type="button" class="btn btn-sm btn-secondary" onclick="opt.transferLeft();" title="Add selected">
                    <i class="fa fa-arrow-left d-none d-md-inline"></i>
                    <i class="fa fa-arrow-up d-inline d-md-none"></i>
                </button>
                <input name="msg_send_receiver" type="hidden" id="msg_send_receiver2">
                <input name="msg_send_aktion" type="hidden" id="msg_send_aktion" value="1">
                <input name="msg_send_pid" type="hidden" value="<?php echo intval($msg); ?>">
            </div>

            <div class="col-md-5">
                <label for="msg_send_list" class="font-weight-bold"><?php echo $BL['be_msg_available']; ?>:</label>
                <select name="msg_send_list" size="10" multiple="multiple" id="msg_send_list" class="form-control" onDblClick="opt.transferLeft()">
<?php
    //Create the list of possible recipients
    $sql = "SELECT usr_id, usr_login, usr_name FROM " . DB_PREPEND . "phpwcms_user " . $where1 . " ORDER BY usr_name ASC";
    $result = _dbQuery($sql);

    if (isset($result[0]['usr_id'])) {
        foreach ($result as $row) {
            echo "<option value=\"" . $row['usr_id'] . "\">" . html($row['usr_name'] . " (" . $row['usr_login']) . ")" . "</option>";
        }
    }
?>
                </select>
            </div>
        </div>

        <div class="form-group">
            <label for="msg_send_subject" class="font-weight-bold"><?php echo $BL['be_msg_subject']; ?>:</label>
            <input name="msg_send_subject" type="text" id="msg_send_subject" class="form-control form-control-sm" value="<?php echo html($msg_subject); ?>" maxlength="125">
        </div>

        <div class="form-group mb-0">
            <label for="msg_send_msg" class="font-weight-bold"><?php echo $BL['be_msg_msg']; ?>:</label>
            <textarea name="msg_send_msg" cols="40" rows="10" id="msg_send_msg" class="form-control form-control-sm autosize"><?php echo html($msg_message); ?></textarea>
        </div>
    </div>
    <div class="card-footer text-right">
        <button type="submit" name="submit" class="btn btn-sm btn-blue font-weight-bold">
            <i class="fa fa-paper-plane mr-1"></i><?php echo $BL['be_msg_all']; ?>
        </button>
    </div>
</div>
</form>
<?php
} //Ende Mitteilungszusammenstellung
?>

