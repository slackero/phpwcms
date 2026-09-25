<?php
/**
 * phpwcms
 *
 * @author Oliver Georgi <og@phpwcms.org>
 * @copyright Copyright (c) 2002-2026, Oliver Georgi
 * @license http://opensource.org/licenses/GPL-2.0 GNU GPL-2
 *
 **/

$phpwcms = array('SESSION_START' => true);

require_once '../config/conf.inc.php';
require_once '../inc_lib/default.inc.php';
require_once PHPWCMS_ROOT.'/include/inc_lib/helper.session.php';
require_once PHPWCMS_ROOT.'/include/inc_lib/dbcon.inc.php';
require_once PHPWCMS_ROOT.'/include/inc_lib/general.inc.php';
checkLogin();
validate_csrf_tokens();
require_once PHPWCMS_ROOT.'/include/inc_lib/backend.functions.inc.php';
require_once PHPWCMS_ROOT.'/include/inc_lib/files.private-usage.inc.php';

// Change file status
if(isset($_GET["aktiv"])) {

    list($id, $wert) = explode("|", $_GET["aktiv"]);
    $id     = intval($id);
    $wert   = intval($wert);
    if($wert != 1 && $wert != 0) $wert = 0;
    $sql  = "UPDATE ".DB_PREPEND."file SET f_aktiv=".$wert.", f_changed='".time()."' WHERE f_id=".$id;
    if(!has_admin_permission('fileaction')) {
        $sql .= " AND f_uid=".intval($_SESSION["wcs_user_id"]);
    }
    _dbQuery($sql, 'UPDATE');

} elseif(isset($_GET["public"])) {

    list($id, $wert) = explode("|", $_GET["public"]);
    $id     = intval($id);
    $wert   = intval($wert);
    if($wert != 1 && $wert != 0) $wert = 0;
    $sql = "UPDATE ".DB_PREPEND."file SET f_public=".$wert.", f_changed='".time()."' WHERE f_id=".$id;
    if(!has_admin_permission('fileaction')) {
        $sql .= " AND f_uid=".intval($_SESSION["wcs_user_id"]);
    }
    _dbQuery($sql, 'UPDATE');

} elseif(isset($_GET["delete"])) {

    list($id, $wert) = explode("|", $_GET["delete"]);
    $id     = intval($id);
    $wert   = intval($wert);
    if($wert == 9) {
        $sql = "UPDATE ".DB_PREPEND."file SET f_trash=9, f_changed='".time()."' WHERE f_id=".$id;
        if(!has_admin_permission('filedelete')) {
            $sql .= " AND f_uid=".intval($_SESSION["wcs_user_id"]);
        }
        _dbQuery($sql, 'UPDATE');
    }

} elseif(isset($_GET["trash"])) {

    list($ids, $wert) = explode("|", $_GET["trash"]);
    $wert = intval($wert);

    if($wert == 1 || $wert == 9 || $wert == 0) {

        // Supports both a single id ("123") and a colon-separated list of
        // ids ("123:456:789") for bulk-selecting several files at once in
        // the file center.
        $requested_ids = array();
        foreach(explode(":", $ids) as $fid) {
            $fid = intval($fid);
            if($fid > 0) {
                $requested_ids[] = $fid;
            }
        }

        // No id at all is the original bulk "act on every currently
        // trashed file" call (empty trash / restore all), unaffected by
        // the in-use guard below.
        $empty_trash_all = empty($requested_ids);

        // Files currently referenced in content (red usage status) cannot be
        // moved to the trash. This guard only applies to putting still-active
        // files into the trash (wert=1) - not to restoring (0) or purging
        // already-trashed files (9), and not to the bulk "empty trash" call.
        $blocked_ids = array();
        $allowed_ids = $requested_ids;

        if($wert == 1 && $requested_ids) {
            $allowed_ids = array();
            foreach($requested_ids as $fid) {
                if(phpwcms_file_in_use($fid)) {
                    $blocked_ids[] = $fid;
                } else {
                    $allowed_ids[] = $fid;
                }
            }
        }

        if($empty_trash_all || $allowed_ids) {
            $sql  = "UPDATE ".DB_PREPEND."file SET f_pid=0, f_trash=".$wert.", f_changed='".time()."' WHERE f_kid=1 AND ";
            $sql .= $empty_trash_all ? "f_trash=1" : "f_id IN (".implode(",", $allowed_ids).")";
            if(!has_admin_permission('filedelete')) {
                $sql .= " AND f_uid=".intval($_SESSION["wcs_user_id"]);
            }
            _dbQuery($sql, 'UPDATE');
        }

        $ajax_success       = empty($blocked_ids);
        $ajax_blocked_count = count($blocked_ids);

    }

} elseif(isset($_GET["paste"])) {

    list($file_ids, $dir_id) = explode("|", $_GET["paste"]);
    $dir_id = intval($dir_id);

    // Supports both a single id ("123") and a colon-separated list of ids
    // ("123:456:789") for bulk-moving a multi-selection at once.
    $file_id_list = array();
    foreach(explode(":", $file_ids) as $fid) {
        $fid = intval($fid);
        if($fid > 0) {
            $file_id_list[] = $fid;
        }
    }

    $ajax_success = false;

    if($file_id_list) {
        $sql  = "UPDATE ".DB_PREPEND."file SET f_pid=".$dir_id.", f_changed='".time()."' WHERE f_kid=1 AND f_id IN (".implode(",", $file_id_list).")";
        if(!has_admin_permission('fileaction')) {
            $sql .= " AND f_uid=".intval($_SESSION["wcs_user_id"]);
        }
        $paste_result = _dbQuery($sql, 'UPDATE');
        $ajax_success = !empty($paste_result['AFFECTED_ROWS']);
    }

}

if(isset($_GET["thumbnail"])) {
    $_SESSION["wcs_user_thumb"] = intval($_GET["thumbnail"]);
}

if(has_admin_permission('adm') || has_admin_permission('filedelete')) { // If user has admin permissions

    $phpwcms['trash_delete_files'] = empty($phpwcms['trash_delete_files']) ? false : true;

    //move deleted files into final deletion directory
    if(isset($_GET['movedeletedfiles']) && intval($_GET['movedeletedfiles']) === intval($_SESSION["wcs_user_id"])) {

        $result = _dbQuery("SELECT * FROM ".DB_PREPEND."file WHERE f_trash=9 AND f_kid=1");

        if(isset($result[0]['f_id'])) {

            //default file storage folder
            $default_path = PHPWCMS_ROOT.$phpwcms["file_path"];
            $tempimg_path = PHPWCMS_ROOT.'/'.PHPWCMS_IMAGES;

            if(!$phpwcms['trash_delete_files']) {
                if(!is_dir($default_path.'can_be_deleted')) {
                    @mkdir($default_path.'can_be_deleted', 0777);
                }
            }

            foreach($result as $row) {

                $delstatus = false;

                // name of the file that should be moved or deleted
                $filename = ($row['f_ext']) ? $row['f_hash'].'.'.$row['f_ext'] : $row['f_hash'];

                if(is_file($default_path.$filename)) {

                    if($phpwcms['trash_delete_files']) {
                        if(@unlink($default_path.$filename)) {
                            $delstatus = true;
                        }
                    } elseif(@rename($default_path.$filename, $default_path.'can_be_deleted/'.$filename)) {
                        $delstatus = true;
                    }

                } else {

                    $delstatus = true;

                }

                if($delstatus) {

                    $sql_f  = "UPDATE ".DB_PREPEND."file SET f_trash=8 WHERE f_id=".$row['f_id']." AND f_kid=1";
                    _dbQuery($sql_f, 'UPDATE');

                }
            }
        }

        // clean pre-rendered thumbnail images
        $thumbnails = returnFileListAsArray(PHPWCMS_THUMB, 'jpg,jpeg,gif,png');
        if(is_array($thumbnails) && count($thumbnails)) {

            foreach($thumbnails as $thumbnail) {

                @unlink(PHPWCMS_THUMB.$thumbnail['filename']);

            }
        }

    }
}

// AJAX callers (e.g. drag & drop moving in the file center) get a small JSON
// response instead of the usual full page redirect used by regular links.
if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest') {
    header('Content-Type: application/json; charset='.PHPWCMS_CHARSET);
    echo json_encode(array(
        'success' => isset($ajax_success) ? $ajax_success : true,
        'blocked' => isset($ajax_blocked_count) ? $ajax_blocked_count : 0,
    ));
    exit;
}

$ref = empty($_SESSION['REFERER_URL']) ? PHPWCMS_URL.'phpwcms.php?'.get_token_get_string() : $_SESSION['REFERER_URL'];

headerRedirect($ref);
