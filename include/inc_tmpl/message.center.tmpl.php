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
    $msg = clean_slweg($_GET["msg"]);
    $msg_get["msg"] = '&msg=' . rawurlencode($msg);
    list($msg, $msg_read) = explode(":", $msg);
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

?><div class="card shadow-sm mb-4">
    <div class="card-header d-flex flex-column flex-sm-row justify-content-between align-items-sm-center py-2">
        <span class="fw-bold mb-2 mb-sm-0"><?php echo $BL['be_msg_title']; ?></span>
        <ul class="nav nav-pills card-header-pills small">
            <li class="nav-item">
                <a class="nav-link py-1 px-2 <?php echo ($msg_folder == 0) ? 'active' : ''; ?>" href="phpwcms.php?do=messages<?php echo $msg_get["list"].$msg_get["order"]."&f=0"; ?>">
                    <?php echo $count_newmsg." ".$BL['be_msg_new']; ?>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link py-1 px-2 <?php echo ($msg_folder == 1) ? 'active' : ''; ?>" href="phpwcms.php?do=messages<?php echo $msg_get["list"].$msg_get["order"]."&f=1"; ?>">
                    <?php echo $count_readmsg." ".$BL['be_msg_old']; ?>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link py-1 px-2 <?php echo ($msg_folder == 2) ? 'active' : ''; ?>" href="phpwcms.php?do=messages<?php echo $msg_get["list"].$msg_get["order"]."&f=2"; ?>">
                    <?php echo $count_sentmsg." ".$BL['be_msg_senttop']; ?>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link py-1 px-2 <?php echo ($msg_folder == 3) ? 'active' : ''; ?>" href="phpwcms.php?do=messages<?php echo $msg_get["list"].$msg_get["order"]."&f=3"; ?>">
                    <?php echo $count_delmsg." ".$BL['be_msg_del']; ?>
                </a>
            </li>
        </ul>
    </div>
    <div class="card-body">
        <div class="text-end small text-muted mb-3">
            <a href="phpwcms.php?do=messages<?php echo $msg_get["folder"].$msg_get["order"]."&l=10".$msg_get["msg"]; ?>">10</a> | 
            <a href="phpwcms.php?do=messages<?php echo $msg_get["folder"].$msg_get["order"]."&l=25".$msg_get["msg"]; ?>">25</a> | 
            <a href="phpwcms.php?do=messages<?php echo $msg_get["folder"].$msg_get["order"]."&l=50".$msg_get["msg"]; ?>">50</a> | 
            <a href="phpwcms.php?do=messages<?php echo $msg_get["folder"].$msg_get["order"]."&l=100".$msg_get["msg"]; ?>">100</a> | 
            <a href="phpwcms.php?do=messages<?php echo $msg_get["folder"].$msg_get["order"]."&l=500".$msg_get["msg"]; ?>">250</a> | 
            <a href="phpwcms.php?do=messages<?php echo $msg_get["folder"].$msg_get["order"]."&l=99999".$msg_get["msg"]; ?>"><?php echo $BL['be_ftptakeover_all']; ?></a>
        </div>

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
      <div class="card bg-light mb-4">
        <div class="card-header bg-warning fw-bold d-flex justify-content-between align-items-center py-2">
            <span><?php echo $BL['be_msg_from']; ?>: <?php echo gib_part($msg_user_list[$msgdetail["msg_from"]], 1, "###")." (".gib_part($msg_user_list[$msgdetail["msg_from"]], 0, "###").")"; ?></span>
            <span class="small"><?php echo $BL['be_msg_date']; ?>: <?php echo $msgdetail["msg_date"]; ?></span>
        </div>
        <div class="card-body">
            <h6 class="card-title fw-bold mb-3"><?php echo html($msgdetail["msg_subject"]); ?></h6>
            <div class="card-text mb-4"><?php echo auto_link(nl2br(html($msgdetail["msg_text"]))); ?></div>
            <div class="btn-group btn-group-sm" role="group">
                <a href="phpwcms.php?do=messages<?php echo $msg_get["all"]; ?>" class="btn btn-secondary" title="<?php echo $BL['be_msg_close']; ?>">
                    <i class="fa fa-times fa-fw"></i> <?php echo $BL['be_msg_close']; ?>
                </a>
                <a href="phpwcms.php?do=messages&amp;p=1" class="btn btn-blue" title="<?php echo $BL['be_msg_create']; ?>">
                    <i class="fa fa-plus fa-fw"></i> <?php echo $BL['be_msg_create']; ?>
                </a>
                <a href="phpwcms.php?do=messages&amp;p=1&amp;msg=<?php echo $msgdetail["msg_id"].":"; if(!$msgdetail["msg_read"]) echo "I"; ?>" class="btn btn-blue" title="<?php echo $BL['be_msg_reply']; ?>">
                    <i class="fa fa-reply fa-fw"></i> <?php echo $BL['be_msg_reply']; ?>
                </a>
                <?php if ($msg_folder != 3) { ?>
                <a href="include/inc_act/act_message.php?do=<?php echo $do_move; ?>.<?php echo $msgdetail["msg_id"]; ?>.1" class="btn btn-danger" title="<?php echo $BL['be_msg_move']; ?>">
                    <i class="far fa-trash-alt fa-fw"></i> <?php echo $BL['be_msg_move']; ?>
                </a>
                <?php } ?>
            </div>
        </div>
      </div>
      <?php
                    } //Bedingung für Abfrage
                } //Ende Anzeige komplette gewählte Nachricht
            } //Ende Anzeigen Nachricht

            if ($count_newmsg && $msg_folder == 0) { //Wenn Count > 0 dann Listing der neuen Nachrichten
            ?>
            <h6 class="fw-bold text-primary mb-2"><?php echo $BL['be_msg_unread']; ?></h6>
            <div class="table-responsive mb-4">
                <table class="table table-sm table-hover mb-0">
                    <thead class="thead-light">
                        <tr>
                            <th scope="col"><?php echo $BL['be_msg_from']; ?></th>
                            <th scope="col"><?php echo $BL['be_msg_subject']; ?></th>
                            <th scope="col" style="width: 130px;"><?php echo $BL['be_msg_date']; ?></th>
                            <th scope="col" class="text-end" style="width: 80px;"></th>
                        </tr>
                    </thead>
                    <tbody>
        <?php
    //Listing new messages
    $sql =  "SELECT msg_id, msg_pid, msg_uid, msg_subject, msg_from, msg_read, ".
            "DATE_FORMAT(msg_tstamp, '%m/%d/%y %H:%i') AS msg_date ".
            "FROM ".DB_PREPEND."phpwcms_message WHERE msg_uid=".$_SESSION["wcs_user_id"].
            " AND (msg_read=0 OR (NOW()-msg_tstamp<86400)) AND msg_deleted=0 ".
            "ORDER BY msg_tstamp DESC LIMIT ".$msg_list;
    $result = _dbQuery($sql);

    if(isset($result[0]['msg_id'])) {
        $zaehler = 0;
        foreach ($result as $row) {
            $goto = "phpwcms.php?do=messages".$msg_get["folder"].$msg_get["order"].$msg_get["list"]."&msg=".$row["msg_id"].":";
            if (!$row["msg_read"]) {
                $goto .= "I";
            }
            $is_active = ($msg == $row["msg_id"]) ? ' table-warning' : '';
?>
        <tr class="hover-gold <?php echo $is_active; ?>" onclick="location.href='<?php echo $phpwcms["site"].$goto; ?>';">
          <td class="msglist"><a href="<?php echo $goto; ?>" title="<?php echo html($row["msg_subject"]); ?>"><?php echo gib_part($msg_user_list[$row["msg_from"]], 1, "###"); ?></a></td>
          <td class="msglist"><a href="<?php echo $goto; ?>" title="<?php echo html($row["msg_subject"]); ?>"><?php echo cut_string($row["msg_subject"], "&#8230;", 40); ?></a></td>
          <td class="msglist text-muted small"><?php echo $row["msg_date"]; ?></td>
          <td class="text-end">
              <a href="phpwcms.php?do=messages&amp;p=1&amp;msg=<?php echo $row["msg_id"].":"; if(!$row["msg_read"]) echo "I"; ?>" class="btn btn-sm btn-blue py-0 px-1" title="<?php echo $BL['be_msg_reply']; ?>"><i class="fa fa-reply fa-fw"></i></a>
              <a href="include/inc_act/act_message.php?do=1.<?php echo $row["msg_id"]; ?>.1" class="btn btn-sm btn-danger py-0 px-1" title="<?php echo $BL['be_msg_move']; ?>"><i class="far fa-trash-alt fa-fw"></i></a>
          </td>
    </tr>
<?php
            $zaehler++;
        } //Ende Listing Schleife
    } //Ende Listing new messages
?>
                    </tbody>
                </table>
            </div>
<?php
            $no_durchlauf++;
            } //Ende Anzeige unglesene Mitteilungen


            if ($count_readmsg && $msg_folder == 1) { //Wenn Count > 0 dann Listing der bereits gelesenen Nachrichten
            ?>
            <h6 class="fw-bold text-muted mb-2"><?php echo str_replace('{VAL}', $msg_list, $BL['be_msg_lastread']); ?></h6>
            <div class="table-responsive mb-4">
                <table class="table table-sm table-hover mb-0">
                    <thead class="thead-light">
                        <tr>
                            <th scope="col"><?php echo $BL['be_msg_from']; ?></th>
                            <th scope="col"><?php echo $BL['be_msg_subject']; ?></th>
                            <th scope="col" style="width: 130px;"><?php echo $BL['be_msg_date']; ?></th>
                            <th scope="col" class="text-end" style="width: 80px;"></th>
                        </tr>
                    </thead>
                    <tbody>
        <?php
    //Listing new messages
    $sql =  "SELECT msg_id, msg_pid, msg_uid, msg_subject, msg_from, msg_read, ".
            "DATE_FORMAT(msg_tstamp, '%m/%d/%y %H:%i') AS msg_date ".
            "FROM ".DB_PREPEND."phpwcms_message WHERE msg_uid=".$_SESSION["wcs_user_id"].
            " AND msg_read=1 AND msg_deleted=0 ORDER BY msg_tstamp DESC LIMIT ".$msg_list;
    $result = _dbQuery($sql);

    if(isset($result[0]['msg_id'])) {
        $zaehler = 0;
        foreach ($result as $row) {
            $goto = "phpwcms.php?do=messages".$msg_get["folder"].$msg_get["order"].$msg_get["list"]."&msg=".$row["msg_id"].":";
            if (!$row["msg_read"]) {
                $goto .= "I";
            }
            $is_active = ($msg == $row["msg_id"]) ? ' table-warning' : '';
?>
        <tr class="hover-gold <?php echo $is_active; ?>" onclick="location.href='<?php echo $phpwcms["site"].$goto; ?>';">
          <td class="msglist"><a href="<?php echo $goto; ?>" title="<?php echo html($row["msg_subject"]); ?>"><?php echo gib_part($msg_user_list[$row["msg_from"]], 1, "###"); ?></a></td>
          <td class="msglist"><a href="<?php echo $goto; ?>" title="<?php echo html($row["msg_subject"]); ?>"><?php echo cut_string($row["msg_subject"], "&#8230;", 40); ?></a></td>
          <td class="msglist text-muted small"><?php echo $row["msg_date"]; ?></td>
          <td class="text-end">
              <a href="phpwcms.php?do=messages&amp;p=1&amp;msg=<?php echo $row["msg_id"].":"; if(!$row["msg_read"]) echo "I"; ?>" class="btn btn-sm btn-blue py-0 px-1" title="<?php echo $BL['be_msg_reply']; ?>"><i class="fa fa-reply fa-fw"></i></a>
              <a href="include/inc_act/act_message.php?do=1.<?php echo $row["msg_id"]; ?>.1" class="btn btn-sm btn-danger py-0 px-1" title="<?php echo $BL['be_msg_move']; ?>"><i class="far fa-trash-alt fa-fw"></i></a>
          </td>
        </tr>
        <?php
            $zaehler++;
        } //Ende Listing Schleife
    } //Ende Listing new messages
?>
                    </tbody>
                </table>
            </div>
      <?php
            $no_durchlauf++;
            } //Ende Anzeige gelesene Mitteilungen


            if ($count_sentmsg && $msg_folder == 2) { //Wenn Count > 0 dann Listing der neuen Nachrichten
            ?>
            <h6 class="fw-bold text-muted mb-2"><?php echo str_replace('{VAL}', $msg_list, $BL['be_msg_lastsent']); ?></h6>
            <div class="table-responsive mb-4">
                <table class="table table-sm table-hover mb-0">
                    <thead class="thead-light">
                        <tr>
                            <th scope="col"><?php echo $BL['be_msg_from']; ?></th>
                            <th scope="col"><?php echo $BL['be_msg_subject']; ?></th>
                            <th scope="col" style="width: 130px;"><?php echo $BL['be_msg_date']; ?></th>
                            <th scope="col" class="text-end" style="width: 80px;"></th>
                        </tr>
                    </thead>
                    <tbody>
<?php
    //Listing new messages
    $sql =  "SELECT msg_id, msg_pid, msg_uid, msg_subject, msg_from, msg_read, ".
            "DATE_FORMAT(msg_tstamp, '%m/%d/%y %H:%i') AS msg_date ".
            "FROM ".DB_PREPEND."phpwcms_message WHERE msg_from=".$_SESSION["wcs_user_id"].
            " AND msg_from_del=0 ORDER BY msg_tstamp DESC LIMIT ".$msg_list;
    $result = _dbQuery($sql);

    if(isset($result[0]['msg_id'])) {
        $zaehler = 0;
        foreach ($result as $row) {
            $goto = "phpwcms.php?do=messages".$msg_get["folder"].$msg_get["order"].$msg_get["list"]."&msg=".$row["msg_id"].":";
            if (!$row["msg_read"]) {
                $goto .= "I";
            }
            $is_active = ($msg == $row["msg_id"]) ? ' table-warning' : '';
?>
    <tr class="hover-gold <?php echo $is_active; ?>" onclick="location.href='<?php echo $phpwcms["site"].$goto; ?>';">
        <td class="msglist"><a href="<?php echo $goto; ?>" title="<?php echo html($row["msg_subject"]); ?>"><?php echo gib_part($msg_user_list[$row["msg_from"]], 1, "###"); ?></a></td>
        <td class="msglist"><a href="<?php echo $goto; ?>" title="<?php echo html($row["msg_subject"]); ?>"><?php echo cut_string($row["msg_subject"], "&#8230;", 40); ?></a></td>
        <td class="msglist text-muted small"><?php echo $row["msg_date"]; ?></td>
        <td class="text-end">
            <a href="phpwcms.php?do=messages&amp;p=1&amp;msg=<?php echo $row["msg_id"].":"; if(!$row["msg_read"]) echo "I"; ?>" class="btn btn-sm btn-blue py-0 px-1" title="<?php echo $BL['be_msg_reply']; ?>"><i class="fa fa-reply fa-fw"></i></a>
            <a href="include/inc_act/act_message.php?do=2.<?php echo $row["msg_id"]; ?>.1" class="btn btn-sm btn-danger py-0 px-1" title="<?php echo $BL['be_msg_move']; ?>"><i class="far fa-trash-alt fa-fw"></i></a>
        </td>
    </tr>
<?php
            $zaehler++;
        } //Ende Listing Schleife
    } //Ende Listing new messages
?>
                    </tbody>
                </table>
            </div>
<?php
            $no_durchlauf++;
    } //Ende Anzeige unglesene Mitteilungen

    if ($count_delmsg && $msg_folder == 3) { //Wenn Count > 0 dann Listing der neuen Nachrichten
            ?>
            <h6 class="fw-bold text-muted mb-2"><?php echo $BL['be_msg_marked']; ?></h6>
            <div class="table-responsive mb-4">
                <table class="table table-sm table-hover mb-0">
                    <thead class="thead-light">
                        <tr>
                            <th scope="col"><?php echo $BL['be_msg_from']; ?></th>
                            <th scope="col"><?php echo $BL['be_msg_subject']; ?></th>
                            <th scope="col" style="width: 130px;"><?php echo $BL['be_msg_date']; ?></th>
                            <th scope="col" class="text-end" style="width: 80px;"></th>
                        </tr>
                    </thead>
                    <tbody>
<?php
    //Listing new messages
    $sql =  "SELECT msg_id, msg_pid, msg_uid, msg_subject, msg_from, msg_read, ".
            "DATE_FORMAT(msg_tstamp, '%m/%d/%y %H:%i') AS msg_date, msg_from_del, msg_deleted ".
            "FROM ".DB_PREPEND."phpwcms_message WHERE (msg_uid=".$_SESSION["wcs_user_id"]." AND msg_deleted=1) OR ".
            "(msg_from=".$_SESSION["wcs_user_id"]." AND msg_from_del=1) ".
            "ORDER BY msg_tstamp DESC LIMIT ".$msg_list;
    $result = _dbQuery($sql);
    if(isset($result[0]['msg_id'])) {
        $zaehler = 0;
        foreach ($result as $row) {
            $goto = "phpwcms.php?do=messages".$msg_get["folder"].$msg_get["order"].$msg_get["list"]."&msg=".$row["msg_id"].":";
            if (!$row["msg_read"]) {
                $goto .= "I";
            }
            if ($row["msg_from"] == $_SESSION["wcs_user_id"]) {
                $do_undo = 4;
                $do_del  = 4;
            }
            if ($row["msg_uid"] == $_SESSION["wcs_user_id"]) {
                $do_undo = 3;
                $do_del  = 3;
            }
            $is_active = ($msg == $row["msg_id"]) ? ' table-warning' : '';
?>
        <tr class="hover-gold <?php echo $is_active; ?>" onclick="location.href='<?php echo $phpwcms["site"].$goto; ?>';">
          <td class="msglist"><a href="<?php echo $goto; ?>" title="<?php echo html($row["msg_subject"]); ?>"><?php echo gib_part($msg_user_list[$row["msg_from"]], 1, "###"); ?></a></td>
          <td class="msglist"><a href="<?php echo $goto; ?>" title="<?php echo html($row["msg_subject"]); ?>"><?php echo cut_string($row["msg_subject"], "&#8230;", 40); ?></a></td>
          <td class="msglist text-muted small"><?php echo $row["msg_date"]; ?></td>
          <td class="text-end">
              <a href="include/inc_act/act_message.php?do=<?php echo $do_undo; ?>.<?php echo $row["msg_id"]; ?>.0" class="btn btn-sm btn-blue py-0 px-1" title="<?php echo $BL['be_msg_undo']; ?>"><i class="fa fa-undo fa-fw"></i></a>
              <a href="include/inc_act/act_message.php?do=<?php echo $do_del; ?>.<?php echo $row["msg_id"]; ?>.9" class="btn btn-sm btn-danger py-0 px-1" title="<?php echo $BL['be_msg_del']; ?>"><i class="far fa-trash-alt fa-fw"></i></a>
          </td>
    </tr>
<?php
            $zaehler++;
        } //Ende Listing Schleife
    } //Ende Listing new messages
?>
                    </tbody>
                </table>
            </div>
<?php
            $no_durchlauf++;
        } //Ende Anzeige Dateien im Papierkorb

if (!$no_durchlauf) {
    echo '<div class="alert alert-info mb-0">' . $BL['be_msg_nomsg'] . '</div>';
}
?>
    </div>
</div>

