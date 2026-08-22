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

$forum["text"] = '';
$forum["title"] = '';
$forum['id'] = 0;
$row_count = 0;

if (!isset($_GET["s"])) {
// check if subscription should be edited
?>
<div class="card shadow-sm mb-4">
    <div class="card-header font-weight-bold py-2">
        <?php echo $BL['be_subnav_msg_forum']; ?>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-sm table-hover table-valign-middle mb-0">
                <thead class="thead-light">
                    <tr>
                        <th style="width: 40px;"></th>
                        <th><?php echo $BL['be_forum_title']; ?></th>
                        <th class="text-right" style="width: 100px;"><?php echo $BL['be_cnt_actions']; ?></th>
                    </tr>
                </thead>
                <tbody>
<?php
// loop listing available subscriptions
$sql = "SELECT * FROM " . DB_PREPEND . "phpwcms_forum WHERE forum_entry=0 AND forum_deleted=0 ORDER BY forum_changed DESC";
$result = _dbQuery($sql);
if (isset($result[0]['forum_id'])) {
    foreach ($result as $row) {
        $tempQuery = build_QueryString('&amp;', 'do=messages', 'p=6', 's=' . $row["forum_id"]);
        echo '<tr>';
        echo '<td class="text-center"><i class="fa fa-folder text-warning"></i></td>';
        echo '<td><a href="phpwcms.php?' . $tempQuery . '" class="font-weight-bold">' . html($row["forum_title"]) . '</a></td>';
        echo '<td class="text-right">';
        echo '<a href="phpwcms.php?' . $tempQuery . '" class="btn btn-sm btn-blue py-0 px-1 mr-1" title="' . $BL['be_cnt_guestbook_edit'] . '"><i class="fa fa-pencil-alt"></i></a>';
        echo '<a href="include/inc_act/act_forum.php?del=' . $row["forum_id"] . '" class="btn btn-sm btn-danger py-0 px-1 confirm-link" data-confirm="' . $BL['be_cnt_delete_confirm'] . '" title="' . $BL['be_ftabhelp_delete'] . '"><i class="far fa-trash-alt"></i></a>';
        echo '</td>';
        echo '</tr>';
        $row_count++;
    }
} else {
    echo '<tr><td colspan="3" class="text-muted p-3">' . $BL['be_msg_nomsg'] . '</td></tr>';
}
?>
                </tbody>
            </table>
        </div>
    </div>
    <div class="card-footer">
        <a href="phpwcms.php?do=messages&amp;p=6&amp;s=0" class="btn btn-sm btn-blue font-weight-bold">
            <i class="fa fa-plus mr-1"></i><?php echo $BL['be_forum_add']; ?>
        </a>
    </div>
</div>
<?php

} else {

// should the edit forum dialog
    $forum["id"] = (!empty($_GET["s"])) ? intval($_GET["s"]) : 0;

    if (isset($_POST["forum_id"])) {
    // read the create or edit forum form data
        $forum["id"] = intval($_POST["forum_id"]);
        $forum["title"] = clean_slweg($_POST["forum_title"]);
        if (!$forum["title"]) {
            $forum["title"] = "Forum " . date('Y/m/d H:i');
        }
        $forum["text"] = clean_slweg($_POST["forum_text"]);

        $sqla  = "forum_title = '" . aporeplace($forum["title"]) . "', ";
        $sqla .= "forum_text  = '" . aporeplace($forum["text"]) . "'";

        if ($forum["id"]) {

            $query_mode = 'UPDATE';
            $sql  = "UPDATE " . DB_PREPEND . "phpwcms_forum SET " . $sqla;
            $sql .= " WHERE forum_entry=0 AND forum_id=" . $forum["id"];
            $sql .= " LIMIT 1";

        } else {

            $query_mode = 'INSERT';
            $sql  = "INSERT INTO " . DB_PREPEND . "phpwcms_forum SET ";
            $sql .= "forum_entry='0', ";
            $sql .= "forum_uid='" . $_SESSION["wcs_user_id"] . "', ";
            $sql .= "forum_created = '" . time() . "', ";
            $sql .= $sqla;

        }
        // update or insert data entry
        $result = _dbQuery($sql, $query_mode);
        if ($query_mode === 'INSERT' && isset($result['INSERT_ID'])) {
            $forum["id"] = $result['INSERT_ID'];
        }
        if ($forum["id"]) {
            headerRedirect(PHPWCMS_URL . 'phpwcms.php?' . get_token_get_string() . '&' . build_QueryString('&', 'do=messages', 'p=6', 's=' . $forum["id"]));
        }
    }

    if ($forum["id"]) {
    // read the given subscription datas from db
        $sql = "SELECT * FROM " . DB_PREPEND . "phpwcms_forum WHERE forum_id=" . $forum["id"] . " LIMIT 1";
        $result = _dbQuery($sql);

        if (isset($result[0]['forum_id'])) {

            $forum["id"] = $result[0]["forum_id"];
            $forum["title"] = $result[0]["forum_title"];
            $forum["text"] = $result[0]["forum_text"];

        }
    }

    // show form
?>
<form action="phpwcms.php?<?php echo build_QueryString('&amp;', 'do=messages', 'p=6', 's=' . $forum["id"]); ?>" method="post" name="forums" target="_self">
<div class="card shadow-sm mb-4">
    <div class="card-header font-weight-bold py-2">
        <?php echo $BL['be_forum_titleedit'] . ": " . ($forum["id"] ? html($forum["title"]) : $BL['be_newsletter_new']); ?>
    </div>
    <div class="card-body">
        <div class="form-group row">
            <label for="forum_title" class="col-sm-3 col-form-label text-sm-right"><?php echo $BL['be_forum_title']; ?>:</label>
            <div class="col-sm-8">
                <input name="forum_title" type="text" class="form-control form-control-sm font-weight-bold" id="forum_title" value="<?php echo html($forum["title"]); ?>" maxlength="250">
            </div>
        </div>

        <div class="form-group row mb-0">
            <label for="forum_text" class="col-sm-3 col-form-label text-sm-right"><?php echo $BL['be_cnt_description']; ?>:</label>
            <div class="col-sm-8">
                <textarea name="forum_text" cols="35" rows="6" class="form-control form-control-sm" id="forum_text"><?php echo html($forum["text"]); ?></textarea>
            </div>
        </div>
        <input name="forum_id" type="hidden" value="<?php echo $forum["id"]; ?>">
    </div>
    <div class="card-footer text-right">
        <button type="submit" name="Submit" class="btn btn-sm btn-blue font-weight-bold mr-2" value="1">
            <i class="fa fa-save mr-1"></i><?php echo $BL['be_save_btn']; ?>
        </button>
        <a href="phpwcms.php?do=messages&amp;p=6" class="btn btn-sm btn-danger ml-3">
            <i class="fa fa-times mr-1"></i><?php echo $BL['be_newsletter_button_cancel']; ?>
        </a>
    </div>
</div>
</form>
<?php
}

?>

