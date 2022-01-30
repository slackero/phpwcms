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

/**
 * Sept. 2009
 * enhancement to enable phpwcms filebrowser support in FCK Editor
 * based on concept and work of Markus Köhl <www.pagewerkstatt.ch>
 *
 * April 2011
 * - enhancement to enable phpwcms filebrowser support in CKEditor
 *   based on concept and work of Markus Köhl <www.leanux.ch>
 * - Issue 265 based on TB's post
 */

$phpwcms            = array('SESSION_START' => true);
$phpwcms_root       = rtrim(str_replace('\\', '/', dirname(__FILE__)), '/');
$js_files_all       = array();
$js_files_select    = array();

require_once $phpwcms_root.'/include/config/conf.inc.php';
require_once $phpwcms_root.'/include/inc_lib/default.inc.php';
require_once PHPWCMS_ROOT.'/include/inc_lib/helper.session.php';

if( empty($_SESSION["wcs_user_lang"]) ) {

    $_SESSION = array();
    @session_destroy();
    headerRedirect(PHPWCMS_URL, 401);

} else {

    $user_lang = strtolower(substr($_SESSION["wcs_user_lang"], 0, 2));

    require PHPWCMS_ROOT.'/include/inc_lang/backend/en/lang.inc.php';
    require PHPWCMS_ROOT.'/include/inc_lang/backend/en/lang.ext.inc.php';
    $cust_lang = PHPWCMS_ROOT.'/include/inc_lang/backend/' . $user_lang . '/lang.inc.php';
    if(is_file($cust_lang)) {
        include $cust_lang;
    }
    $cust_lang = PHPWCMS_ROOT.'/include/inc_lang/backend/' . $user_lang . '/lang.ext.inc.php';
    if(is_file($cust_lang)) {
        include $cust_lang;
    }

}

$js_aktion = empty($_GET["opt"]) ? 0 : intval($_GET["opt"]);

// set target for article summary/list image
if(isset($_GET['target'])) {

    $_SESSION['filebrowser_image_target'] = $_GET['target'] === 'list' ? '_list_' :  '_';

} elseif(empty($_SESSION['filebrowser_image_target'])) {

    $_SESSION['filebrowser_image_target'] = '_';

}

if(isset($_GET['entry_id'])) {
    $_SESSION['filebrowser_image_entry_id'] = preg_replace('/[^a-z0-9_\-]/', '', $_GET['entry_id']);
}
if(isset($_GET['CKEditorFuncNum'])) {
    $_SESSION['CKEditorFuncNum'] = intval($_GET['CKEditorFuncNum']);
}

require_once PHPWCMS_ROOT.'/include/inc_lib/dbcon.inc.php';
require_once PHPWCMS_ROOT.'/include/inc_lib/general.inc.php';

checkLogin();

require_once PHPWCMS_ROOT.'/include/inc_lib/backend.functions.inc.php';
require_once PHPWCMS_ROOT.'/include/inc_lib/imagick.convert.inc.php';

$phpwcms_filestorage = PHPWCMS_FILES;

switch($js_aktion) {

    case 0:
    case 1:
    case 3:
    case 7:
    case 8:
    case 5:
    case 11:
    case 17:
        $titel      = $BL['IMAGE_TITLE'];
        $filetype   = $BL['IMAGE_FILES'];
        break;

    case 4:
    case 9:
    case 10:
    case 16:
    case 15:
    case 18:
    case 19:
        $titel      = $BL['FILE_TITLE'];
        $filetype   = $BL['FILES'];
        break;

    case 2:
    case 6:
    case 12:
    case 13:
    case 14:
        $titel      = $BL['MEDIA_TITLE'];
        $filetype   = $BL['MEDIA_FILES'];
        break;

}

$folder = array();

if(isset($_SESSION["folder"])) {
    $folder = $_SESSION["folder"];
}

if(isset($_GET["folder"])) {
    list($folder_id, $folder_value) = explode('|', $_GET["folder"]);
    $folder_value       = intval($folder_value);
    $folder[$folder_id] = $folder_value;
    $_SESSION["folder"] = $folder; // Return array with current opened folder session values
    if($folder_value) {
        $_SESSION["imgdir"] = $folder_id;
    }
}
$_SESSION["list_zaehler"] = 0;

// Which folder is active
if(isset($_GET["files"])) {

    $_SESSION["imgdir"] = intval($_GET["files"]);

} elseif(!isset($_SESSION["imgdir"])) {

    $_SESSION["imgdir"] = 0;

} elseif(isset($_SESSION["imgdir"])) {

    $_SESSION["imgdir"] = intval($_SESSION["imgdir"]);

}

//Does user have files and folders that can be used
$sql = "SELECT COUNT(f_id) FROM ".DB_PREPEND."phpwcms_file WHERE f_aktiv=1 AND (f_public=1 OR f_uid=".intval($_SESSION["wcs_user_id"]).") AND f_trash=0";
$count_user_files = _dbQuery($sql, 'COUNT');

?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="<?php echo $user_lang; ?>">
<head>

    <title><?php echo $titel ?></title>

    <meta http-equiv="Content-Type" content="text/html; charset=<?php echo PHPWCMS_CHARSET ?>" />

    <link href="include/inc_css/phpwcms.min.css" rel="stylesheet" type="text/css" />
    <link href="include/inc_js/uploader/fileuploader.css" rel="stylesheet" type="text/css" />
    <link href="include/inc_css/autoSuggest.css" rel="stylesheet" type="text/css" />
    <script src="include/inc_js/jquery/jquery.min.js" type="text/javascript"></script>
    <script src="include/inc_js/uploader/fileuploader.min.js" type="text/javascript"></script>
    <script src="include/inc_js/jquery/jquery.autoSuggest.min.js" type="text/javascript"></script>
    <script src="include/inc_js/phpwcms.min.js" type="text/javascript"></script>
    <script src="include/inc_js/autosize.min.js" type="text/javascript"></script>
    <script type="text/javascript">
        function addFile(obj,text,value) {
            if(obj!=null && obj.options!=null) {
                var newOpt = new Option(text, value);
                obj.options.length++;
                obj.options[obj.length-1].text  = newOpt.text;
                obj.options[obj.length-1].value = newOpt.value;
                obj.options[obj.length-1].selected = false;
            }
        }
    </script>
</head>
<body class="filebrowser">
<div class="uploader filebrowser-uploader closed" id="filebrowser-uploader">

    <h1 class="title" id="fileupload-switch"><?php echo $BL['be_file_multiple_upload'] ?></h1>

    <div class="uploader-button" id="upload-file-select"></div>

    <div class="filebrowser-form">
        <p>
            <label class="chatlist"><?php echo $BL['be_ftptakeover_longinfo'] ?></label>
            <textarea cols="40" rows="3" id="file_longinfo"></textarea>
        </p>
        <p>
            <label class="chatlist"><?php echo $BL['be_copyright'] ?></label>
            <input name="file_copyright" type="text" id="file_copyright" size="40" maxlength="255" value="" />
        </p>
        <p>
            <label class="chatlist"><?php echo $BL['be_tags'] ?></label>
            <input type="text" id="file_tags_autosuggest" />
        </p>

        <div class="uploader-button qq-upload-button" id="upload-trigger-send"><?php echo $BL['be_files_upload'] ?></div>
    </div>

</div>
<table summary="" border="0" cellspacing="0" cellpadding="0" style="margin:4px;">
  <tr>
        <td bgcolor="#7C98A2"><img src="img/leer.gif" alt="" width="1" height="1" border="0" /></td>
        <td rowspan="4"><img src="img/leer.gif" alt="" width="5" height="1" border="0" /></td>
        <td bgcolor="#7C98A2"><img src="img/leer.gif" alt="" width="1" height="1" border="0" /></td>
  </tr>
  <tr>
    <td bgcolor="#7C98A2" class="msgreiter">&nbsp;<?php echo $BL['FOLDER_LIST'] ?>&nbsp;</td>
    <td bgcolor="#7C98A2" class="msgreiter">&nbsp;<?php echo $filetype ?>&nbsp;</td>
  </tr>
  <tr>
        <td bgcolor="#7C98A2"><img src="img/leer.gif" alt="" width="1" height="1" border="0" /></td>
        <td bgcolor="#7C98A2"><img src="img/leer.gif" alt="" width="1" height="1" border="0" /></td>
  </tr>
  <tr>
    <td valign="top"><?php

if(!empty($count_user_files)) { //Listing in case of user files/folders

    echo '<table summary="" bgColor="#FCFDFD" border="0" cellspacing="0" cellpadding="0">'.LF;

    //Anzeige des Festplattensymbols
    $dirname = $BL['ROOT_DIR'];
    if(!isset($folder[0])) {
        $folder[0] = 0;
    }
    $folder_status = true_false($folder[0]);

    $count_sql = "SELECT COUNT(f_id) FROM ".DB_PREPEND."phpwcms_file WHERE f_pid=0 AND f_aktiv=1 AND f_trash=0 AND (f_public=1 OR f_uid=".$_SESSION["wcs_user_id"].")";

    if(($count_wert = _dbQuery($count_sql, 'COUNT'))) {
        $count  = '<img src="img/leer.gif" height="1" width="2" alt="" border="0" /><a href="filebrowser.php?opt='.$js_aktion.'&amp;folder=0';
        $count .= '%7C'.$folder_status.'">'.on_off($folder_status, $dirname, 0).'</a>';
    } else {
        $count = '<img src="img/leer.gif" height="1" width="13" alt="" border="0" />';
    }

    // define current directory name
    $current_dirname = $dirname;

    $dirname    =  "<a href=\"filebrowser.php?opt=".$js_aktion."&amp;files=0\" title=\"".$BL['SHOW_FILES'].'">'.$dirname."</a>";
    $bgcol      = (isset($row["f_id"]) && $row["f_id"] == $_SESSION["imgdir"]) ? ' bgcolor="#FED83F"' : '';

    echo '<tr'.$bgcol.'><td colspan="2"><img src="img/leer.gif" height="2" width="1" border="0" alt="" /></td></tr>'.LF; //Abstand vor
    echo '<tr'.$bgcol.'><td class="msglist nowrap" nowrap="nowrap">';
    echo $count.'<img src="img/leer.gif" height="1" width="4" border="0" alt="" /><img src="img/icons/harddisk_16x11.gif" border="0" alt="" />'; //Zellinhalt 1. Spalte
    echo '<img src="img/leer.gif" height="1" width="4" alt="" border="0" />'.$dirname.'</td><td><img src="img/leer.gif" height="1" width="5" border="0" alt="" /></td></tr>'.LF;
    //Aufbau trennende Tabellen-Zeile
    echo '<tr'.$bgcol.'><td colspan="2"><img src="img/leer.gif" height="1" width="1" border="0" alt="" /></td></tr>'.LF; //Abstand nach
    echo '<tr bgcolor="#CDDEE4"><td colspan="2"><img src="img/leer.gif" height="1" width="1" border="0" alt="" /></td></tr>'.LF;

    //Wenn überhaupt Ordner für User vorhanden, dann Listing
    if(!$folder_status && $count_wert) {
        folder_list(0, 18, "filebrowser.php?opt=".$js_aktion."&amp;");
    }

    echo '</table>';
} else {
    echo "no files available";
}

    ?></td>
    <td valign="top"><?php

    //Tabelle

    echo '<table summary="" bgColor="#FFFFFF" width="100%" border="0" cellspacing="0" cellpadding="0">'.LF;
    $file_sql  = "SELECT * FROM ".DB_PREPEND."phpwcms_file WHERE f_pid=".$_SESSION["imgdir"]." AND ";
    switch($js_aktion) {

        case 6:
            $file_sql .= "f_ext IN ('swf', 'mp3', 'flv', 'mp4', 'm4v', 'f4v', 'jpg', 'jpeg', 'png', 'gif', 'mp3', 'aac', 'webp') AND ";
            break;

            // H.264
        case 12:
            $file_sql .= "f_ext IN ('mp4', 'm4p', 'mov', 'm4p', 'm4a', 'm4v', 'mp3', 'mpeg', 'aac') AND ";
            break;

            // WebM
        case 13:
            $file_sql .= "f_ext IN ('webm') AND ";
            break;

            // Ogg
        case 14:
            $file_sql .= "f_ext IN ('ogg', 'ogv', 'oga', 'ogx') AND ";
            break;

            // Typical Doc files
        case 18:
            $file_sql .= "f_ext IN ('pdf', 'doc', 'docx', 'txt', 'xls', 'xlsx', 'ppt', 'pptx', 'odt', 'ods', 'odp', 'pages', 'key', 'numbers') AND ";
            // no break here
        case 15:
            $entry_id  = empty($_SESSION['filebrowser_image_entry_id']) ? '' : $_SESSION['filebrowser_image_entry_id'];
            break;

            // Custom content part file selector
        case 19:
            $allowed_ext = empty($_SESSION['filebrowser_allowed_ext']) ? array() : $_SESSION['filebrowser_allowed_ext'];
            if(!empty($_GET['allowed'])) {
                $allowed_ext = convertStringToArray(strtolower($_GET['allowed']));
                if(count($allowed_ext)) {
                    foreach($allowed_ext as $key => $ext) {
                        $allowed_ext[$key] = _dbEscape($ext);
                    }
                    $_SESSION['filebrowser_allowed_ext'] = $allowed_ext;
                }
            }
            if(count($allowed_ext)) {
                $file_sql .= 'f_ext IN (' . implode(',', $allowed_ext) . ') AND ';
            }
            $entry_id = empty($_SESSION['filebrowser_field']) ? null : $_SESSION['filebrowser_field'];
            if(!empty($_GET['field']) && ($entry_id = preg_replace('/[^a-z0-9_-]/i', '', $_GET['field']))) {
                $_SESSION['filebrowser_field'] = $entry_id;
            }
            break;

        case 11:
        case 17:
        case 8:
            $entry_id  = empty($_SESSION['filebrowser_image_entry_id']) ? '' : $_SESSION['filebrowser_image_entry_id'];
            // no break here
        case 7:
            $file_sql .= "f_ext IN ('jpeg', 'jpg', 'png', 'gif', 'svg', 'webp'";
            if($phpwcms['image_library'] !== 'gd2') {
                $file_sql .= ", 'pdf', 'ai', 'psd', 'tif', 'tiff', 'bmp', 'eps', 'webp'";
            }
            $file_sql .= ") AND ";
            break;

        case 2:
            $default_ext  = "f_ext IN ('aif', 'aiff', 'mov', 'movie', 'mp3', 'mpeg', 'mpeg4', ";
            $default_ext .= "'mpeg2', 'wav', 'swf', 'ram', 'ra', 'wma', 'wmv', ";
            $default_ext .= "'avi', 'au', 'midi', 'moov', 'rm', 'rpm', 'mid', 'midi')";

            if(!empty($phpwcms["multimedia_ext"])) {

                $allowed_ext = convertStringToArray(strtolower($phpwcms["multimedia_ext"]));
                if(count($allowed_ext)) {
                    $default_ext = "f_ext IN ('" . implode("', '", $allowed_ext) . "')";
                }

            }

            $file_sql .= $default_ext." AND ";

            break;

    }
    $file_sql .= "f_aktiv=1 AND f_kid=1 AND f_trash=0 AND ";
    $file_sql .= "(f_public=1 OR f_uid=".$_SESSION["wcs_user_id"].") ";
    $file_sql .= "ORDER BY f_sort, f_name";

    if (empty($_SESSION['CKEditorFuncNum'])) {
        $ckeditor_action = isset($_GET['CKEditorFuncNum']) ? intval($_GET['CKEditorFuncNum']) : 0;
    } else {
        $ckeditor_action = $_SESSION['CKEditorFuncNum'];
    }

    $file_result = _dbQuery($file_sql);

    if(isset($file_result[0]['f_id'])) {

        $target_form = (empty($_SESSION['image_browser_article'])) ? 'articlecontent' : 'article';

        foreach($file_result as $file_durchlauf => $file_row) {

            $filename = html($file_row["f_name"]);

            $thumb_image = true;
            if( !$file_row['f_svg'] && !in_array($js_aktion, array(2, 4, 9, 10, 16, 18, 19)) ) {
                // check if file can have thumbnail - if so it can be choosen for usage
                $thumb_image = get_cached_image(array(
                    "target_ext"    =>  $file_row["f_ext"],
                    "image_name"    =>  $file_row["f_hash"] . '.' . $file_row["f_ext"],
                    "thumb_name"    =>  md5($file_row["f_hash"].$phpwcms["img_list_width"].$phpwcms["img_list_height"].$phpwcms["sharpen_level"].$phpwcms['colorspace'])
                ));
            }

            if($thumb_image != false || in_array($js_aktion, array(6, 10, 12, 13, 14, 16, 18, 19))) {

                $js_files_select[$file_durchlauf] = '     [' . $file_durchlauf .', ' . $file_row["f_id"] . ', "' . $filename . '"]';
                $add_all = false;

                switch($js_aktion) {
                    case 0:
                        $jst = empty($_SESSION['filebrowser_image_target']) ? '_' : $_SESSION['filebrowser_image_target'];

                        $js  = "window.opener.document.".$target_form.".cimage".$jst."name.value='".$filename."';";
                        $js .= "window.opener.document.".$target_form.".cimage".$jst."id.value='".$file_row["f_id"]."';";
                        break;

                    case 2:
                        $js  = "window.opener.document.articlecontent.cmedia_name.value='".$filename."';";
                        $js .= "window.opener.document.articlecontent.cmedia_id.value='".$file_row["f_id"]."';";
                        break;

                    case 6:
                    case 12:
                    case 13:
                    case 14:
                        $js = "window.opener.setIdName('".$file_row["f_id"]."', '".$filename."', ".$js_aktion.");";
                        break;

                    case 19:
                        $js = "window.opener.setIdName('".$entry_id."', '".$file_row["f_id"]."', '".$filename."');tmt_winControl('self','close()');";
                        break;

                    case 18:
                    case 15:
                        $js = "window.opener.setIdName('".$entry_id."', '".$file_row["f_id"]."', '".$filename."');";
                        break;

                    case 7:
                        $js = "window.opener.setImgIdName('".$file_row["f_id"]."', '".$filename."');";
                        break;

                    case 8:
                        $js = "window.opener.setImgIdName('".$entry_id."', '".$file_row["f_id"]."', '".$filename."');";
                        break;

                    case 4:
                        $js = "addFile(window.opener.document.articlecontent.cfile_list,'".$filename."','".$file_row["f_id"]."');";
                        $js_files_all[] = $js;
                        $add_all = true;
                        break;

                    case 9:
                        $js = "window.opener.addFile('".$file_row["f_id"]."', '".$filename."');";
                        $js_files_all[] = $js;
                        $add_all = true;
                        break;

                    case 5:
                        $js = "addFile(window.opener.img_field,'".$filename."','".$file_row["f_id"]."');";
                        $js_files_all[] = $js;
                        $add_all = true;
                        break;

                    //mod
                    case 10:
                        $js  = "window.opener.SetUrl('download.php?f=".$file_row["f_hash"] . "');";
                        break;

                    case 11:
                        $js  = "window.opener.SetUrl('".PHPWCMS_RESIZE_IMAGE."/".$phpwcms['img_prev_width']."x".$phpwcms['img_prev_height']."/" . $file_row["f_hash"] . '.' . $file_row["f_ext"] . "');";
                        break;

                    //CKEditor
                    case 16:
                        $js  = "window.opener.CKEDITOR.tools.callFunction(".$ckeditor_action.", 'download.php?f=".$file_row["f_hash"] . "');";
                        break;

                    case 17:
                        $js  = "window.opener.CKEDITOR.tools.callFunction(".$ckeditor_action.", '".PHPWCMS_RESIZE_IMAGE."/".$phpwcms['img_prev_width']."x".$phpwcms['img_prev_height']."/" . $file_row["f_hash"] . '.' . $file_row["f_ext"] . "');";
                        break;

                    default:
                        $js = "addFile(window.opener.document.articlecontent.cimage_list,'".$filename."','".$file_row["f_id"]."');";
                        $js_files_all[] = $js;
                        $add_all = true;
                }

                // show "add all files"
                if($file_durchlauf === 0 && $add_all) {

                    echo '<tr id="addAllFilesLink"><td colspan="4" class="add_all_files"><a href="#" onclick="addAllFiles();return false;" title="';
                    echo $BL['ADD_ALL_FILES'];
                    echo '">';
                    echo $BL['ADD_ALL_FILES'];
                    echo '<img src="img/button/add_9x9a.gif" alt="" border="0" /></a></td></tr>';
                    echo '<tr><td colspan="4" bgcolor="#CDDEE4"><img src="img/leer.gif" alt="" border="0" /></td></tr>';

                }

                echo '<tr><td colspan="4"><img src="img/leer.gif" width="1" height="2" border="0" alt="" /></td></tr>';
                echo '<tr><td><img src="img/icons/small_'.extimg($file_row["f_ext"]).'" alt="" hspace="3" vspace="1" /></td>';
                echo '<td class="msglist">';

                if($js_aktion != 4 && $js_aktion != 10 && $js_aktion != 16) {
                    echo $filename.'</td><td><img src="img/leer.gif" width="5" height="1" alt="" />';
                } else {
                    echo '<a href="#" onclick="'.$js."tmt_winControl('self','close()');\">".$filename.'</a></td><td align="right">';
                }

                echo '<a href="#" onclick="'.$js.'return false;" title="'.$BL['TAKE_IMAGE'].'">';
                echo '<img src="img/button/add_9x9a.gif" alt="" hspace="5" vspace="2" /></a></td>';
                echo '<td><img src="img/leer.gif" alt="" /></td></tr>';
                echo '<tr><td colspan="4"><img src="img/leer.gif" width="1" height="1" alt="" /></td></tr>';
                if((!empty($thumb_image[0]) || $file_row['f_svg']) && in_array( $js_aktion, array(0, 1, 3, 5, 6, 7, 8, 10, 11, 17, 18, 19) ) ) {
                    echo '<tr><td>&nbsp;</td><td colspan="3"><a href="#" onclick="'.$js;
                    echo "tmt_winControl('self','close()');\">";
                    if($file_row['f_svg']) {
                        echo '<img src="'.PHPWCMS_RESIZE_IMAGE.'/'.$phpwcms["img_list_width"].'x'.$phpwcms["img_list_height"].'/'.$file_row['f_hash'].'.'.$file_row['f_ext'].'" alt="" />';
                    } else {
                        echo '<img src="'.PHPWCMS_IMAGES . $thumb_image[0] .'" '.$thumb_image[3].' alt="" />';
                    }
                    echo '</a></td></tr>';
                }
                echo '<tr><td colspan="4"><img src="img/leer.gif" width="1" height="2" alt="" /></td></tr>';
                echo '<tr><td colspan="4" bgcolor="#CDDEE4"><img src="img/leer.gif" width="1" height="1" alt="" /></td></tr>';
            }

        }
        if(empty($filename)) { //Abschluss der Filelisten-Tabelle
            echo '<tr><td colspan="4"><img src="img/leer.gif" width="3" height="2" alt="" /></td></tr>';
            echo '<tr><td colspan="4" class="msglist">&nbsp;'.$BL['NO_FILE'].'&nbsp;&nbsp;</td></tr>';
            echo '<tr><td colspan="4"><img src="img/leer.gif" width="3" height="2" alt="" /></td></tr>';
        }
    }

    echo '</table>';

    if( count($js_files_select) ) {

        echo LF . '<script type="text/javascript">';
        echo LF . SCRIPT_CDATA_START . LF;

        echo 'var files_all = new Array(' . LF;
        echo implode(','.LF, $js_files_select);
        echo LF . ' );';
        echo LF . 'var files_total = ' . $file_durchlauf . ';';

        echo LF . LF;
        echo 'function addAllFiles() {';
        echo LF . ' ';
        echo implode(LF . ' ', $js_files_all);
        echo LF . ' //if(closewin == true) tmt_winControl("self","close()");';
        echo LF . ' getObjectById("addAllFilesLink").style.display = "none";';
        $confirm = str_replace('{VAL}', $current_dirname, $BL['ADD_ALL_CONFIRM']);
        if(PHPWCMS_CHARSET !== 'utf-8') {
            $confirm = utf8_decode($confirm);
        }
        echo LF . ' if(confirm("' . $confirm . '")) tmt_winControl("self","close()");';
        echo LF . '}' . LF;

        echo LF . SCRIPT_CDATA_END;
        echo LF . '</script>' . LF;

    }

    $fileuploaderAllowedExtensions = '';
    if(is_string($phpwcms['allowed_upload_ext'])) {
        $fileuploaderAllowedExtensions = strtolower($phpwcms['allowed_upload_ext']);
        if(strpos($fileuploaderAllowedExtensions, ',') !== false) {
            $fileuploaderAllowedExtensions = "'" . str_replace(',', "','", $fileuploaderAllowedExtensions) . "'";
        }
    } elseif(count($phpwcms['allowed_upload_ext'])) {
        $fileuploaderAllowedExtensions = "'" . implode("','", $phpwcms['allowed_upload_ext']) . "'";
    }

    ?></td>
  </tr>
</table>
<script>
$(function() {

    var fileuploader = $('#filebrowser-uploader'),
        fileuploadSwitch = $('#fileupload-switch'),
        uploadTrigger = $('#upload-trigger-send'),
        fileBrowserForm = $('div.filebrowser-form'),
        uploadFileCount = 0;

    fileuploadSwitch.click(function(){
        if(fileuploader.hasClass('closed')) {
            fileuploader.removeClass('closed');
            if(uploadFileCount) {
                fileBrowserForm.show();
            }
        } else {
            fileBrowserForm.hide();
            fileuploader.addClass('closed');
        }
    });

    // File Uploading
    var uploader = new qq.FileUploader({
        element: $('#upload-file-select')[0],
        action: '<?php echo PHPWCMS_URL; ?>include/inc_act/act_upload.php?<?php echo get_token_get_string(); ?>',
        multiple: true,
        autoUpload: false,
        allowedExtensions: [<?php echo $fileuploaderAllowedExtensions; ?>],
        uploadButtonText: '<?php echo $BL['be_fileuploader_uploadButtonText'] ?>',
        cancelButtonText: '<?php echo $BL['be_newsletter_button_cancel'] ?>',
        failUploadText: '<?php echo $BL['be_error_while_save'] ?>',
        dragText: '<?php echo $BL['be_fileuploader_dragText'] ?>',
        sizeLimit: <?php

            if(ini_get('post_max_size')) {
                $post_max_size = return_bytes(ini_get('post_max_size'));
                if($post_max_size < $phpwcms['file_maxsize']) {
                    $phpwcms['file_maxsize'] = $post_max_size;
                }
            } else {
                $post_max_size = $phpwcms['file_maxsize'];
            }
            if(ini_get('upload_max_filesize')) {
                $upload_max_filesize = return_bytes(ini_get('upload_max_filesize'));
                if($upload_max_filesize < $phpwcms['file_maxsize']) {
                    $phpwcms['file_maxsize'] = $upload_max_filesize;
                }
            } else {
                $upload_max_filesize = $phpwcms['file_maxsize'];
            }

            echo min($post_max_size, $upload_max_filesize, $phpwcms['file_maxsize']);

        ?>,
        messages: {
            typeError: "<?php echo makeCharsetConversion($BL['be_fileuploader_typeError'], 'utf-8', PHPWCMS_CHARSET) ?>",
            sizeError: "<?php echo makeCharsetConversion($BL['be_fileuploader_sizeError'], 'utf-8', PHPWCMS_CHARSET) ?>",
            minSizeError: "<?php echo makeCharsetConversion($BL['be_fileuploader_minSizeError'], 'utf-8', PHPWCMS_CHARSET) ?>",
            emptyError: "<?php echo makeCharsetConversion($BL['be_fileuploader_emptyError'], 'utf-8', PHPWCMS_CHARSET) ?>",
            noFilesError: "<?php echo makeCharsetConversion($BL['be_fileuploader_noFilesError'], 'utf-8', PHPWCMS_CHARSET) ?>",
            onLeave: "<?php echo makeCharsetConversion($BL['be_fileuploader_onLeave'], 'utf-8', PHPWCMS_CHARSET) ?>"
        },
        disableDefaultDropzone: false,
        onSubmit: function(id, fileName) {
            if(!uploadFileCount) {
                fileBrowserForm.show();
            }
            uploadFileCount++;
        },
        onCancel: function(id, fileName) {
            uploadFileCount--;
            if(!uploadFileCount) {
                fileBrowserForm.hide();
            }
        },
        onComplete: function(id, fileName, responseJSON) {
            if(responseJSON.success) {
                uploadFileCount--;
                if(!uploadFileCount) {
                    document.location.reload(true);
                }
            }
        }
    });

    uploadTrigger.click(function() {

        uploader.setParams({
            file_dir: <?php echo $_SESSION["imgdir"] ?>,
            file_aktiv: 1,
            file_public: 1,
            file_longinfo: $('#file_longinfo').val(),
            file_copyright: $('#file_copyright').val(),
            file_tags: $('#as-values-keyword-autosuggest').val()
        });

        uploader.uploadStoredFiles();
    });

    $("#file_tags_autosuggest").autoSuggest('<?php echo PHPWCMS_URL ?>include/inc_act/ajax_connector.php', {
        selectedItemProp: "cat_name",
        selectedValuesProp: 'cat_name',
        searchObjProps: "cat_name",
        queryParam: 'value',
        extraParams: '&method=json&action=category',
        startText: '',
        neverSubmit: true,
        asHtmlID: 'keyword-autosuggest'
    });

});

</script>
</body>
</html>
<?php

function folder_list($pid, $vor, $zieldatei) {
    global $current_dirname;
    $folder = $_SESSION["folder"];
    $pid = intval($pid);
    $userID = intval($_SESSION["wcs_user_id"]);
    $sql = "SELECT f_id, f_name, f_aktiv, f_public FROM ".DB_PREPEND."phpwcms_file WHERE ".
           "f_pid=".intval($pid)." AND f_aktiv=1 AND f_kid=0 AND f_trash=0 AND ".
           "(f_public=1 OR f_uid=".$userID.") ORDER BY f_sort, f_name";

    $result = _dbQuery($sql);

    if(isset($result[0]['f_id'])) {
        foreach($result as $row) {

            $dirname = html($row["f_name"]);

            //Ermitteln des Aufolderwertes
            if(empty($folder[$row["f_id"]])) {
                $folder[ $row["f_id"] ] = 0;
            }
            $folder_status = true_false($folder[ $row["f_id"] ]);

            //Ermitteln, ob überhaupt abhängige Dateien/Ordner existieren
            $count_sql = "SELECT COUNT(f_id) FROM ".DB_PREPEND."phpwcms_file WHERE f_pid=".$row["f_id"]." AND f_trash=0 AND f_aktiv=1 AND (f_public=1 OR f_uid=".$userID.")";

            if(($count_wert = _dbQuery($count_sql, 'COUNT'))) {
                $count  = '<img src="img/leer.gif" height="1" width="2" alt="" border="0" /><a href="'.$zieldatei."folder=".$row["f_id"];
                $count .= '%7C'.$folder_status.'">'.on_off($folder_status, $dirname, 0).'</a>';
            } else {
                $count = '<img src="img/leer.gif" height="1" width="13" alt="" border="0" />';
            }

            $dirname = '<a href="'.$zieldatei."files=".$row["f_id"].'" title="'.$GLOBALS['BL']['SHOW_FILES1'].'">'. $dirname . '</a>';

            if($row["f_id"] == $_SESSION["imgdir"]) {
                $bgcol = ' bgcolor="#FED83F"';
                $current_dirname = $row["f_name"];
            } else {
                $bgcol = '';
            }

            echo "<tr".$bgcol."><td colspan=\"2\"><img src=\"img/leer.gif\" height=\"2\" width=\"1\" alt=\"\" border=\"0\" /></td></tr>\n";
            echo "<tr".$bgcol."><td class=\"msglist nowrap\" nowrap=\"nowrap\">";
            echo $count."<img src=\"img/leer.gif\" height=\"1\" width=\"".($vor+6)."\" border=\"0\" alt=\"\" /><img src=\"img/icons/folder_zu.gif\" border=\"0\" alt=\"\" />";
            echo "<img src=\"img/leer.gif\" height=\"1\" width=\"5\" alt=\"\" border=\"0\" />".$dirname."</td><td><img src=\"img/leer.gif\" height=\"1\" width=\"5\" alt=\"\" border=\"0\" /></td></tr>\n";
            echo "<tr".$bgcol."><td colspan=\"2\"><img src=\"img/leer.gif\" height=\"1\" width=\"1\" alt=\"\" border=\"0\" /></td></tr>\n";
            echo "<tr bgcolor=\"#CDDEE4\"><td colspan=\"2\"><img src=\"img/leer.gif\" height=\"1\" width=\"1\" alt=\"\" border=\"0\" /></td></tr>\n";

            if(!$folder_status && $count_wert) {
                folder_list($row["f_id"], $vor+18, $zieldatei);
            }

            $_SESSION["list_zaehler"]++;
        }
    }
}

function on_off($wert, $string, $art = 1) {
    //Erzeugt das Status-Zeichen für Klapp-Auf/Zu
    //Wenn Art = 1 dann als Zeichen, ansonsten als Bild
    if($wert) {
        return $art == 1 ? "+" : "<img src=\"img/symbols/klapp_zu.gif\" border=\"0\" alt=\"\" title=\"".$GLOBALS['BL']['OPEN_DIR'].": ".$string."\" />";
    } else {
        return $art == 1 ? "-" : "<img src=\"img/symbols/klapp_auf.gif\" border=\"0\" alt=\"\" title=\"".$GLOBALS['BL']['CLOSE_DIR'].": ".$string."\" />";
    }
}

function true_false($wert) {
    // Swap true / false
    return intval($wert) ? 0 : 1;
}
