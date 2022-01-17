<?php
/**
 * cmsGO!
 *
 * @author Pixels & Points GmbH <info@pixels-points.ch>
 * @copyright Copyright (c) 2002-2022, Pixels & Points GmbH
 * @license https://www.pixels-points.ch/cmsgo-license.html Pixels & Points cmsGO! license
 *
 **/

$cmsgo = array('SESSION_START' => true);

require_once '../../include/config/conf.inc.php';
require_once '../inc_lib/default.inc.php';
require_once CMSGO_ROOT.'/include/inc_lib/helper.session.php';
require_once CMSGO_ROOT.'/include/inc_lib/dbcon.inc.php';
require_once CMSGO_ROOT.'/include/inc_lib/general.inc.php';
checkLogin();
validate_csrf_tokens();
require_once CMSGO_ROOT.'/include/inc_lib/backend.functions.inc.php';

// Change file status
if(isset($_GET["aktiv"])) {

    list($id, $wert) = explode("|", $_GET["aktiv"]);
    $id     = intval($id);
    $wert   = intval($wert);
    if($wert != 1 && $wert != 0) $wert = 0;
    $sql  = "UPDATE ".DB_PREPEND."cmsgo_file SET f_aktiv=".$wert.", f_changed='".time()."' WHERE f_id=".$id;
    if(empty($_SESSION["wcs_user_admin"])) {
        $sql .= " AND f_uid=".intval($_SESSION["wcs_user_id"]);
    }
    _dbQuery($sql, 'UPDATE');

} elseif(isset($_GET["public"])) {

    list($id, $wert) = explode("|", $_GET["public"]);
    $id     = intval($id);
    $wert   = intval($wert);
    if($wert != 1 && $wert != 0) $wert = 0;
    $sql = "UPDATE ".DB_PREPEND."cmsgo_file SET f_public=".$wert.", f_changed='".time()."' WHERE f_id=".$id;
    if(empty($_SESSION["wcs_user_admin"])) {
        $sql .= " AND f_uid=".intval($_SESSION["wcs_user_id"]);
    }
    _dbQuery($sql, 'UPDATE');

} elseif(isset($_GET["delete"])) {

    list($id, $wert) = explode("|", $_GET["delete"]);
    $id     = intval($id);
    $wert   = intval($wert);
    if($wert == 9) {
        $sql = "UPDATE ".DB_PREPEND."cmsgo_file SET f_trash=9, f_changed='".time()."' WHERE f_id=".$id;
        if(empty($_SESSION["wcs_user_admin"])) {
            $sql .= " AND f_uid=".intval($_SESSION["wcs_user_id"]);
        }
        _dbQuery($sql, 'UPDATE');
    }

} elseif(isset($_GET["trash"])) {

    list($id, $wert) = explode("|", $_GET["trash"]);
    $id     = intval($id);
    $wert   = intval($wert);
    if($wert == 1 || $wert == 9 || $wert == 0) {
        $sql  = "UPDATE ".DB_PREPEND."cmsgo_file SET f_pid=0, f_trash=".$wert.", f_changed='".time()."' WHERE f_kid=1 AND ";
        $sql .= $id ? "f_id=".$id : "f_trash=1";
        if(empty($_SESSION["wcs_user_admin"])) {
            $sql .= " AND f_uid=".intval($_SESSION["wcs_user_id"]);
        }
        _dbQuery($sql, 'UPDATE');
    }

} elseif(isset($_GET["paste"])) {

    list($file_id, $dir_id) = explode("|", $_GET["paste"]);
    $file_id    = intval($file_id);
    $dir_id     = intval($dir_id);
    $sql  = "UPDATE ".DB_PREPEND."cmsgo_file SET f_pid=".$dir_id.", f_changed='".time()."' WHERE f_id=".$file_id." AND f_kid=1";
    if(empty($_SESSION["wcs_user_admin"])) {
        $sql .= " AND f_uid=".intval($_SESSION["wcs_user_id"]);
    }
    _dbQuery($sql, 'UPDATE');

}

if(isset($_GET["thumbnail"])) {
    $_SESSION["wcs_user_thumb"] = intval($_GET["thumbnail"]);
}

if(!empty($_SESSION["wcs_user_admin"])) { // If user has admin permissions

    $cmsgo['trash_delete_files'] = empty($cmsgo['trash_delete_files']) ? false : true;

    //move deleted files into final deletion directory
    if(isset($_GET['movedeletedfiles']) && intval($_GET['movedeletedfiles']) === intval($_SESSION["wcs_user_id"])) {

        $result = _dbQuery("SELECT * FROM ".DB_PREPEND."cmsgo_file WHERE f_trash=9 AND f_kid=1");

        if(isset($result[0]['f_id'])) {

            //default file storage folder
            $default_path = CMSGO_ROOT.$cmsgo["file_path"];
            $tempimg_path = CMSGO_ROOT.'/'.CMSGO_IMAGES;

            if(!$cmsgo['trash_delete_files']) {
                if(!is_dir($default_path.'can_be_deleted')) {
                    @mkdir($default_path.'can_be_deleted', 0777);
                }
            }

            foreach($result as $row) {

                $delstatus = false;

                // name of the file that should be moved or deleted
                $filename = ($row['f_ext']) ? $row['f_hash'].'.'.$row['f_ext'] : $row['f_hash'];

                if(is_file($default_path.$filename)) {

                    if($cmsgo['trash_delete_files']) {
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

                    $sql_f  = "UPDATE ".DB_PREPEND."cmsgo_file SET f_trash=8 WHERE f_id=".$row['f_id']." AND f_kid=1";
                    _dbQuery($sql_f, 'UPDATE');

                }
            }
        }

        // clean pre-rendered thumbnail images
        $thumbnails = returnFileListAsArray(CMSGO_THUMB, 'jpg,jpeg,gif,png');
        if(is_array($thumbnails) && count($thumbnails)) {

            foreach($thumbnails as $thumbnail) {

                @unlink(CMSGO_THUMB.$thumbnail['filename']);

            }
        }

    }
}

$ref = empty($_SESSION['REFERER_URL']) ? CMSGO_URL.'cmsgo.php?'.get_token_get_string() : $_SESSION['REFERER_URL'];

headerRedirect($ref);
