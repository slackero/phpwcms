<?php
/**
 * phpwcms
 *
 * @author Oliver Georgi <og@phpwcms.org>
 * @copyright Copyright (c) 2002-2026, Oliver Georgi
 * @license http://opensource.org/licenses/GPL-2.0 GNU GPL-2
 *
 **/

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
    $folder_id          = intval($folder_id);
    $folder_value       = intval($folder_value);
    $folder[$folder_id] = $folder_value;
    $_SESSION["folder"] = $folder; // Return array with current opened folder session values
    $_SESSION["imgdir"] = $folder_id;
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

?><!DOCTYPE html>
<html lang="<?php echo $user_lang; ?>">
<head>
    <title><?php echo $titel ?></title>
    <meta charset="<?php echo PHPWCMS_CHARSET ?>" />
    <link href="include/inc_css/backend.min.css" rel="stylesheet" type="text/css">
    <link href="include/inc_css/dropzone.min.css" rel="stylesheet" type="text/css">
    <script src="include/inc_js/jquery/jquery-3.7.1.min.js"></script>
    <script src="include/inc_js/dropzone.min.js"></script>
    <?php echo getJavaScriptTranslations(); ?>
    <script src="include/inc_js/phpwcms.min.js"></script>
    <script>
        function addFile(obj, text, value) {
            if (obj && obj.options) {
                const newOpt = new Option(text, value, false, false);
                obj.add(newOpt);
            }
        }
    </script>
</head>
<body class="filebrowser m-3">

<div class="d-flex align-items-center justify-content-between mb-3">
  <h2 class="m-0"><?php echo ($js_aktion == 16) ? $BL['be_article_title'] : $BL['FILE_TITLE']; ?></h2>
  <button type="button" class="btn btn-blue btn-sm" id="showuploader"><i class="fas fa-cloud-upload-alt mr-1"></i><?php echo $BL['be_file_multiple_upload'] ?></button>
</div>
	<div class="uploader filebrowser-uploader" id="filebrowser-uploader" style="display:none">
      <form action="include/inc_act/act_multiupload.php?<?php echo get_token_get_string(); ?>" class="dropzone mb-2" id="filebrowser-dropzone"></form>
      <div id="dropzone-errors" class="mb-3"></div>
      <div class="filebrowser-form card card-body bg-light p-3 mb-3">
			<div class="form-group mb-2">
				<label class="font-weight-bold small mb-1" for="file_longinfo"><?php echo $BL['be_ftptakeover_longinfo'] ?></label>
				<textarea cols="40" rows="2" id="file_longinfo" class="form-control form-control-sm"></textarea>
			</div>
			<div class="form-group mb-2">
				<label class="font-weight-bold small mb-1" for="file_copyright"><?php echo $BL['be_copyright'] ?></label>
				<input name="file_copyright" type="text" id="file_copyright" class="form-control form-control-sm" maxlength="255" value="" />
			</div>
			<div class="form-group mb-0">
				<span class="font-weight-bold small mb-1 d-block"><?php echo $BL['be_tags'] ?></span>
				<input type="text" id="file_tags_autosuggest" class="form-control form-control-sm" aria-label="<?php echo html_specialchars($BL['be_tags']) ?>" />
			</div>
			<div class="mt-3 text-right">
				<button type="button" class="btn btn-success btn-sm font-weight-bold px-3" id="upload-trigger-send"><i class="fas fa-upload mr-1"></i><?php echo $BL['be_files_upload'] ?></button>
			</div>
      </div>
	</div>

<table class="table table-sm">
  <tr>
    <th class="bg-grey" >&nbsp;<?php echo $BL['FOLDER_LIST'] ?></th>
    <th class="bg-grey px-3">&nbsp;</th>
    <th class="bg-grey">&nbsp;<?php echo $filetype ?></th>
  </tr>
  <tr>
    <td class="align-top w-50 p-0"><?php

if(!empty($count_user_files)) { //Listing in case of user files/folders

    echo '<table class="table mt-3">'.LF;

    //Anzeige des Festplattensymbols
    $dirname = $BL['ROOT_DIR'];
    if(!isset($folder[0])) {
        $folder[0] = 0;
    }
    $folder_status = true_false($folder[0]);
    $counter = 0;

    $count_sql = "SELECT COUNT(f_id) FROM ".DB_PREPEND."phpwcms_file WHERE f_pid=0 AND f_aktiv=1 AND f_trash=0 AND (f_public=1 OR f_uid=".$_SESSION["wcs_user_id"].")";

    if(($count_wert = _dbQuery($count_sql, 'COUNT'))) {
        $count  = '<a href="filebrowser.php?opt='.$js_aktion.'&amp;folder=0';
        $count .= '%7C'.$folder_status.'">'.on_off($folder_status, $dirname, 0, $counter).'</a>';
    } else {
        $count = on_off($folder_status, $dirname, 0, $counter);
        }

    // define current directory name
    $current_dirname = $dirname;

    $dirname    =  "<a href=\"filebrowser.php?opt=".$js_aktion."&amp;files=0\" title=\"".$BL['SHOW_FILES'].'">'.$dirname."</a>";
    $bgcol      = (isset($row["f_id"]) && $row["f_id"] == $_SESSION["imgdir"]) ? ' bgcolor="#FFF5C9"' : '';

    echo '<tr'.$bgcol.'><td class="text-nowrap">';
    echo $count.'<i class="fa fa-desktop fa-fw pl-1 mr-2" aria-hidden="true"></i>';
    echo $dirname.'</td></tr>'.LF;

    //Wenn überhaupt Ordner für User vorhanden, dann Listing
    if(!$folder_status && $count_wert) {
        folder_list(0, 1, "filebrowser.php?opt=".$js_aktion."&amp;");
    }

    echo '</table>';
} else {
    echo "no files available";
}

    ?></td>
    <td class="align-top px-3">&nbsp;</td>
    <td class="align-top w-50 p-0"><?php

    //Tabelle

    echo '<table class="table table-borderless mt-2">'.LF;
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

        $bg_toggle = false;
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

                $bg_toggle = !$bg_toggle;
                $row_class = $bg_toggle ? ' class="file-row-even"' : ' class="file-row-odd"';

                $js_files_select[$file_durchlauf] = '     [' . $file_durchlauf .', ' . $file_row["f_id"] . ', "' . $filename . '"]';
                $add_all = false;

                //change js call so it works inside modal
                switch($js_aktion) {
                    case 0:
                        $jst = empty($_SESSION['filebrowser_image_target']) ? '_' : $_SESSION['filebrowser_image_target'];

                        $js  = "parent.document.".$target_form.".cimage".$jst."name.value='".$filename."';";
                        $js .= "parent.document.".$target_form.".cimage".$jst."id.value='".$file_row["f_id"]."';";
                        $js .= "if (typeof parent.onImageSelected === 'function') { parent.onImageSelected('".$jst."', '".$file_row["f_id"]."', '".$filename."'); }";
                        break;

                    case 2:
                        $js  = "parent.document.articlecontent.cmedia_name.value='".$filename."';";
                        $js .= "parent.document.articlecontent.cmedia_id.value='".$file_row["f_id"]."';";
                        break;

                    case 6:
                    case 12:
                    case 13:
                    case 14:
                        $js = "parent.setIdName('".$file_row["f_id"]."', '".$filename."', ".$js_aktion.");";
                        break;

                    case 19:
                    case 18:
                    case 15:
                        $js = "parent.setIdName('".$entry_id."', '".$file_row["f_id"]."', '".$filename."');";
                        break;

                    case 7:
                        $js = "parent.setImgIdName('".$file_row["f_id"]."', '".$filename."');";
                        break;

                    case 8:
                        $js = "parent.setImgIdName('".$entry_id."', '".$file_row["f_id"]."', '".$filename."');";
                        break;

                    case 4:
                        $js = "addFile(parent.document.getElementById('cfile_list') || (parent.document.articlecontent && parent.document.articlecontent.cfile_list),'".$filename."','".$file_row["f_id"]."');";
                        $js_files_all[] = $js;
                        $add_all = true;
                        break;

                    case 9:
                        $js = "parent.addFile('".$file_row["f_id"]."', '".$filename."');";
                        $js_files_all[] = $js;
                        $add_all = true;
                        break;

                    case 5:
                        $js = "addFile(parent.img_field,'".$filename."','".$file_row["f_id"]."');";
                        $js_files_all[] = $js;
                        $add_all = true;
                        break;

                    //mod
                    case 10:
                        $js  = "parent.SetUrl('download.php?f=" . $file_row["f_hash"] . "');";
                        break;

                    case 11:
                        $js  = "parent.SetUrl('".PHPWCMS_RESIZE_IMAGE."/".$phpwcms['img_prev_width']."x".$phpwcms['img_prev_height']."/" . $file_row["f_hash"] . '.' . $file_row["f_ext"] . "');";
                        break;

                    //TinyMCE / CKEditor
                    case 16:
                        $js  = "if(window.opener && window.opener.activeTinyMceCallback){window.opener.activeTinyMceCallback('download.php?f=" . $file_row["f_hash"] . "');window.close();}else{window.opener.CKEDITOR.tools.callFunction(".$ckeditor_action.", 'download.php?f=" . $file_row["f_hash"] . "');}";
                        break;

                    case 17:
                        $resize_url = PHPWCMS_RESIZE_IMAGE."/".$phpwcms['img_prev_width']."x".$phpwcms['img_prev_height']."/" . $file_row["f_hash"] . '.' . $file_row["f_ext"];
                        $js  = "if(window.opener && window.opener.activeTinyMceCallback){window.opener.activeTinyMceCallback('".$resize_url."');window.close();}else{window.opener.CKEDITOR.tools.callFunction(".$ckeditor_action.", '".$resize_url."');}";
                        break;

                    default:
                        $js = "addFile(parent.document.articlecontent.cimage_list,'".$filename."','".$file_row["f_id"]."');";
                        $js_files_all[] = $js;
                         $add_all = true;
                }

                // show "add all files"
                if($file_durchlauf === 0 && $add_all) {

                    echo '<tr id="addAllFilesLink"><td colspan="4"><a href="#" class="btn btn-sm btn-blue" onclick="addAllFiles();return false;" data-toggle="tooltip" title="';
                    echo $BL['ADD_ALL_FILES'];
                    echo '">';
                    echo $BL['ADD_ALL_FILES'];
                    echo '<i class="fa fa-plus fa-fw" aria-hidden="true"></i></a></td></tr>';
                }

                echo '<tr'.$row_class.'><td><i class="fa fa-'.ext_icon($file_row["f_ext"]).'" data-toggle="tooltip" data-html="true" title="ID: '.$file_row["f_id"].'&lt;br&gt;Sort: '.$file_row["f_sort"].'&lt;br&gt;Name: '.html($file_row["f_name"]);
                    if($file_row["f_copyright"]) {
                        echo '&lt;br&gt;&copy;: '.html($file_row["f_copyright"]);
                    }
                    echo '"></i></td>';
                echo '<td>';

                if($js_aktion != 4 && $js_aktion != 10 && $js_aktion != 16) {
                    echo $filename.'</td><td class="text-right py-1">';
                } else if($js_aktion == 16 || $js_aktion == 17) {
                  echo "<a href=\"#\" onclick=\"".$js."tmt_winControl('self','close()');\">".$filename.'</a></td><td class="text-right py-1">';
                } else {
                    echo "<a href=\"#\" onclick=\"".$js."parent.$('#browserModal').modal('hide');\">".$filename.'</a></td><td class="text-right py-1">';
                }

                echo '<a href="#" class="btn btn-sm btn-blue" onclick="'.$js.'return false;" data-toggle="tooltip" title="'.$BL['TAKE_IMAGE'].'">';
                echo '<i class="fa fa-plus" aria-hidden="true"></i></a></td>';
                echo '</tr>';
                if((!empty($thumb_image[0]) || $file_row['f_svg']) && in_array( $js_aktion, array(0, 1, 3, 5, 6, 7, 8, 10, 11, 17, 18, 19) ) ) {
                    echo '<tr style="border-bottom: 1px solid #ccc;"'.$row_class.'><td class="py-1" >&nbsp;</td><td class="py-1" colspan="2"><a href="#" onclick="'.$js;
                    if($js_aktion == 16 || $js_aktion == 17) {
                      echo "tmt_winControl('self','close()');\">";
                    } else {
                      echo "parent.$('#browserModal').modal('hide');\">";
                    }
                    if($file_row['f_svg']) {
                        echo '<img src="'.PHPWCMS_RESIZE_IMAGE.'/'.$phpwcms["img_list_width"].'x'.$phpwcms["img_list_height"].'/'.$file_row['f_hash'].'.'.$file_row['f_ext'].'" alt="" class="img-fluid img-thumbnail" style="max-height: '.$phpwcms["img_list_height"].'px; object-fit: contain;" />';
                    } else {
                        echo '<img src="'.PHPWCMS_IMAGES . $thumb_image[0] .'" alt="" class="img-fluid img-thumbnail" style="max-height: '.$phpwcms["img_list_height"].'px; object-fit: contain;" />';
                    }
                    echo '</a></td></tr>';
                }
            }

        }
        if(empty($filename)) { //Abschluss der Filelisten-Tabelle
            echo '<tr><td colspan="4" class="msglist">&nbsp;'.$BL['NO_FILE'].'&nbsp;&nbsp;</td></tr>';
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
        echo LF . ' //if(closewin == true) '."parent.$('#browserModal').modal('hide');";
        echo LF . ' document.getElementById("addAllFilesLink").style.display = "none";';
        $confirm = str_replace('{VAL}', $current_dirname, $BL['ADD_ALL_CONFIRM']);
        if(PHPWCMS_CHARSET !== 'utf-8') {
            $confirm = mb_convert_encoding($confirm, PHPWCMS_CHARSET);
        }
        echo LF . ' bsConfirmInfo("' . addslashes($confirm) . '", function() { parent.$(\'#browserModal\').modal(\'hide\'); });';
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
    initTomSelectTagAutosuggest('#file_tags_autosuggest', '#file_tags', 'category');
});

    $('.structarticle').on('click', function () {
        parent.$('#browserModal').modal('hide');
    });

    Dropzone.autoDiscover = false;

    var maxMB = <?php
        $post_max_size = ini_get('post_max_size') ? return_bytes(ini_get('post_max_size')) : $phpwcms['file_maxsize'];
        $upload_max_filesize = ini_get('upload_max_filesize') ? return_bytes(ini_get('upload_max_filesize')) : $phpwcms['file_maxsize'];
        $maxBytes = min($post_max_size, $upload_max_filesize, $phpwcms['file_maxsize']);
        echo round($maxBytes / 1048576, 2);
    ?>;

    var bs4PreviewTemplate = '<div class="dz-preview dz-file-preview dz-preview-bs4 d-flex align-items-center justify-content-between">' +
        '<div class="d-flex align-items-center overflow-hidden mr-3" style="min-width: 0;">' +
            '<div class="mr-3 flex-shrink-0 dz-thumb-container">' +
                '<img data-dz-thumbnail class="dz-thumbnail d-none" />' +
                '<div class="dz-icon-placeholder"><i class="fas fa-file"></i></div>' +
            '</div>' +
            '<div class="overflow-hidden" style="min-width: 0;">' +
                '<div class="font-weight-bold text-truncate text-dark" data-dz-name></div>' +
                '<div class="small text-muted d-flex align-items-center">' +
                    '<span data-dz-size class="mr-2"></span>' +
                '</div>' +
                '<div class="progress dz-progress-bar d-none"><div class="progress-bar bg-primary progress-bar-striped progress-bar-animated" role="progressbar" style="width: 0%" data-dz-uploadprogress></div></div>' +
            '</div>' +
        '</div>' +
        '<div class="flex-shrink-0 ml-2">' +
            '<button class="btn btn-outline-danger py-1 px-3" data-dz-remove><i class="fas fa-times mr-1"></i><?php echo str_replace("'", "\\'", $BL["be_newsletter_button_cancel"]); ?></button>' +
        '</div>' +
    '</div>';

    function getFileIconClass(filename) {
        var ext = filename.split('.').pop().toLowerCase();
        switch(ext) {
            case 'pdf': return 'fas fa-file-pdf text-danger';
            case 'doc': case 'docx': return 'fas fa-file-word text-primary';
            case 'xls': case 'xlsx': case 'csv': return 'fas fa-file-excel text-success';
            case 'ppt': case 'pptx': return 'fas fa-file-powerpoint text-warning';
            case 'zip': case 'tar': case 'gz': case '7z': case 'rar': return 'fas fa-file-archive text-warning';
            case 'mp3': case 'wav': case 'ogg': case 'm4a': return 'fas fa-file-audio text-info';
            case 'mp4': case 'mov': case 'webm': case 'avi': case 'm4v': return 'fas fa-file-video text-secondary';
            case 'txt': case 'html': case 'css': case 'js': case 'php': case 'json': case 'xml': return 'fas fa-file-code text-secondary';
            default: return 'fas fa-file text-muted';
        }
    }

    if ($("#filebrowser-dropzone").data("dropzone")) {
        $("#filebrowser-dropzone").data("dropzone").destroy();
    }

    var fileDropzone = new Dropzone("#filebrowser-dropzone", {
        paramName: "file",
        maxFilesize: maxMB,
        autoProcessQueue: false,
        parallelUploads: 10,
        previewTemplate: bs4PreviewTemplate,
        acceptedFiles: <?php
            $modal_ext = array();
            switch($js_aktion) {
                case 6:
                    $modal_ext = array('swf', 'mp3', 'flv', 'mp4', 'm4v', 'f4v', 'jpg', 'jpeg', 'png', 'gif', 'aac', 'webp');
                    break;
                case 12:
                    $modal_ext = array('mp4', 'm4p', 'mov', 'm4a', 'm4v', 'mp3', 'mpeg', 'aac');
                    break;
                case 13:
                    $modal_ext = array('webm');
                    break;
                case 14:
                    $modal_ext = array('ogg', 'ogv', 'oga', 'ogx');
                    break;
                case 18:
                    $modal_ext = array('pdf', 'doc', 'docx', 'txt', 'xls', 'xlsx', 'ppt', 'pptx', 'odt', 'ods', 'odp', 'pages', 'key', 'numbers');
                    break;
                case 19:
                    $modal_ext = empty($_SESSION['filebrowser_allowed_ext']) ? array() : $_SESSION['filebrowser_allowed_ext'];
                    break;
                case 0:
                case 1:
                case 3:
                case 5:
                case 7:
                case 8:
                case 11:
                case 17:
                    $modal_ext = array('jpeg', 'jpg', 'png', 'gif', 'svg', 'webp');
                    if ($phpwcms['image_library'] !== 'gd2') {
                        $modal_ext = array_merge($modal_ext, array('pdf', 'ai', 'psd', 'tif', 'tiff', 'bmp', 'eps'));
                    }
                    break;
                case 2:
                    if (!empty($phpwcms["multimedia_ext"])) {
                        $modal_ext = convertStringToArray(strtolower($phpwcms["multimedia_ext"]));
                    } else {
                        $modal_ext = array('aif', 'aiff', 'mov', 'movie', 'mp3', 'mpeg', 'mpeg4', 'mpeg2', 'wav', 'swf', 'ram', 'ra', 'wma', 'wmv', 'avi', 'au', 'midi', 'moov', 'rm', 'rpm', 'mid');
                    }
                    break;
            }

            // Intersect modal-specific allowed extensions with global allowed_upload_ext
            $global_ext = is_array($phpwcms['allowed_upload_ext']) ? $phpwcms['allowed_upload_ext'] : (is_string($phpwcms['allowed_upload_ext']) && $phpwcms['allowed_upload_ext'] !== '' ? convertStringToArray(strtolower($phpwcms['allowed_upload_ext'])) : array());
            if (count($modal_ext)) {
                if (count($global_ext)) {
                    $effective_ext = array_intersect($modal_ext, $global_ext);
                } else {
                    $effective_ext = $modal_ext;
                }
            } else {
                $effective_ext = $global_ext;
            }

            if (count($effective_ext)) {
                echo json_encode('.' . implode(',.', array_values($effective_ext)));
            } else {
                echo "null";
            }
        ?>,
        accept: function(file, done) {
            var existingFiles = [];
            $("table.table tr td:nth-child(2) a, table.table tr td:nth-child(3) a").each(function() {
                existingFiles.push($.trim($(this).text()).toLowerCase());
            });
            if (existingFiles.indexOf(file.name.toLowerCase()) !== -1) {
                var errStr = <?php
                    $err = !empty($BL['be_fprivup_err12']) ? $BL['be_fprivup_err12'] : 'File <strong>%s</strong> already exists in destination.';
                    echo json_encode(html_entity_decode($err, ENT_QUOTES | ENT_HTML5, 'UTF-8'));
                ?>;
                done(errStr.replace('%s', file.name));
            } else {
                done();
            }
        },
        dictDefaultMessage: <?php
            $msg = !empty($BL['be_fileuploader_dictDefaultMessage']) ? $BL['be_fileuploader_dictDefaultMessage'] : $BL['be_fileuploader_uploadButtonText'];
            echo json_encode(html_entity_decode($msg, ENT_QUOTES | ENT_HTML5, 'UTF-8'));
        ?>,
        dictFallbackMessage: <?php
            $msg = !empty($BL['be_fileuploader_dictFallbackMessage']) ? $BL['be_fileuploader_dictFallbackMessage'] : 'Your browser does not support drag and drop.';
            echo json_encode(html_entity_decode(strip_tags($msg), ENT_QUOTES | ENT_HTML5, 'UTF-8'));
        ?>,
        dictFallbackText: <?php
            $msg = !empty($BL['be_fileuploader_dictFallbackText']) ? $BL['be_fileuploader_dictFallbackText'] : '';
            echo json_encode(html_entity_decode(strip_tags($msg), ENT_QUOTES | ENT_HTML5, 'UTF-8'));
        ?>,
        dictFileTooBig: <?php
            $msg = !empty($BL['be_fileuploader_dictFileTooBig']) ? $BL['be_fileuploader_dictFileTooBig'] : 'File is too big ({{filesize}}MiB). Max filesize: {{maxFilesize}}MiB.';
            echo json_encode(html_entity_decode(strip_tags($msg), ENT_QUOTES | ENT_HTML5, 'UTF-8'));
        ?>,
        dictInvalidFileType: <?php
            $msg = !empty($BL['be_fileuploader_dictInvalidFileType']) ? $BL['be_fileuploader_dictInvalidFileType'] : 'Invalid file type.';
            echo json_encode(html_entity_decode(strip_tags($msg), ENT_QUOTES | ENT_HTML5, 'UTF-8'));
        ?>,
        dictResponseError: <?php
            $msg = !empty($BL['be_fileuploader_dictResponseError']) ? $BL['be_fileuploader_dictResponseError'] : 'Server error {{statusCode}}.';
            echo json_encode(html_entity_decode(strip_tags($msg), ENT_QUOTES | ENT_HTML5, 'UTF-8'));
        ?>,
        dictCancelUpload: <?php
            $msg = !empty($BL['be_fileuploader_dictCancelUpload']) ? $BL['be_fileuploader_dictCancelUpload'] : 'Cancel';
            echo json_encode(html_entity_decode(strip_tags($msg), ENT_QUOTES | ENT_HTML5, 'UTF-8'));
        ?>,
        dictCancelUploadConfirmation: <?php
            $msg = !empty($BL['be_fileuploader_dictCancelUploadConfirmation']) ? $BL['be_fileuploader_dictCancelUploadConfirmation'] : 'Cancel upload?';
            echo json_encode(html_entity_decode(strip_tags($msg), ENT_QUOTES | ENT_HTML5, 'UTF-8'));
        ?>,
        dictRemoveFile: <?php
            $msg = !empty($BL['be_fileuploader_dictRemoveFile']) ? $BL['be_fileuploader_dictRemoveFile'] : 'Remove';
            echo json_encode(html_entity_decode(strip_tags($msg), ENT_QUOTES | ENT_HTML5, 'UTF-8'));
        ?>,
        dictMaxFilesExceeded: <?php
            $msg = !empty($BL['be_fileuploader_dictMaxFilesExceeded']) ? $BL['be_fileuploader_dictMaxFilesExceeded'] : 'Max files exceeded.';
            echo json_encode(html_entity_decode(strip_tags($msg), ENT_QUOTES | ENT_HTML5, 'UTF-8'));
        ?>,
        addRemoveLinks: false,
        init: function() {
            var self = this;
            this.on("addedfile", function(file) {
                if (file.previewElement) {
                    var icon = file.previewElement.querySelector(".dz-icon-placeholder i");
                    if (icon) {
                        icon.className = getFileIconClass(file.name);
                    }
                }
            });
            this.on("thumbnail", function(file, dataUrl) {
                if (file.previewElement) {
                    var img = file.previewElement.querySelector("[data-dz-thumbnail]");
                    var icon = file.previewElement.querySelector(".dz-icon-placeholder");
                    if (img) {
                        img.src = dataUrl;
                        img.classList.remove("d-none");
                    }
                    if (icon) {
                        icon.classList.add("d-none");
                    }
                }
            });
            this.on("sending", function(file, xhr, formData) {
                formData.append("filepublic", "1");
                formData.append("filedir", "<?php echo (int)$_SESSION['imgdir']; ?>");
                formData.append("file_longinfo", $('#file_longinfo').val());
                formData.append("file_copyright", $('#file_copyright').val());
                formData.append("file_tags", $('#as-values-keyword-autosuggest').val());
                if (file.previewElement) {
                    var pBar = file.previewElement.querySelector(".dz-progress-bar");
                    if (pBar) {
                        pBar.classList.remove("d-none");
                    }
                }
            });
            this.on("error", function(file, message, xhr) {
                var errText = "Upload error";
                if (typeof message === "string") {
                    try {
                        var parsed = JSON.parse(message);
                        errText = parsed.error || parsed["jquery-upload-file-error"] || message;
                    } catch(e) {
                        errText = message;
                    }
                } else if (message && typeof message === "object") {
                    errText = message.error || message["jquery-upload-file-error"] || JSON.stringify(message);
                }

                var errorId = "dz-err-" + (file.upload ? file.upload.uuid : Math.random().toString(36).substr(2, 9));
                
                if ($("#" + errorId).length === 0) {
                    var alertHtml = '<div id="' + errorId + '" class="alert alert-danger alert-dismissible fade show d-flex align-items-start mt-2 mb-0 py-2 px-3 small" role="alert">' +
                        '<i class="fas fa-exclamation-triangle mr-2 mt-1 flex-shrink-0"></i>' +
                        '<div>' + errText + '</div>' +
                        '<button type="button" class="close ml-auto pl-2 py-2 dz-alert-close" data-file-uuid="' + (file.upload ? file.upload.uuid : '') + '" aria-label="Close">' +
                        '<span aria-hidden="true">&times;</span>' +
                        '</button>' +
                        '</div>';
                    $("#dropzone-errors").append(alertHtml);

                    $("#" + errorId + " .dz-alert-close").on("click", function() {
                        self.removeFile(file);
                        $("#" + errorId).remove();
                    });
                }
            });
            this.on("removedfile", function(file) {
                if (file.upload && file.upload.uuid) {
                    $("#dz-err-" + file.upload.uuid).remove();
                }
            });
            this.on("success", function(file, response) {
                if (file.upload && file.upload.uuid) {
                    $("#dz-err-" + file.upload.uuid).remove();
                }
            });
            this.on("queuecomplete", function() {
                // If all files in queue have finished processing and at least one succeeded:
                if (self.getQueuedFiles().length === 0 && self.getUploadingFiles().length === 0) {
                    if (self.getFilesWithStatus(Dropzone.SUCCESS).length > 0) {
                        // Delay slightly so user can observe success state if needed
                        document.location.reload();
                    }
                }
            });

            $("#upload-trigger-send").on("click", function(e) {
                e.preventDefault();
                var queued = self.getQueuedFiles();
                if (queued.length > 0) {
                    self.options.autoProcessQueue = true;
                    self.processQueue();
                }
            });
        }
    });

    $('#showuploader').on('click', function () {
        $('#filebrowser-uploader').toggle();
    });
});
</script>
</body>
</html>
<?php

function folder_list($pid, $counter, $zieldatei) {
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
            $folder_status = true_false($folder[$row["f_id"]]);

            //Ermitteln, ob überhaupt abhängige Dateien/Ordner existieren
            $count_sql = "SELECT COUNT(f_id) FROM ".DB_PREPEND."phpwcms_file WHERE f_pid=".$row["f_id"]." AND f_trash=0 AND f_aktiv=1 AND (f_public=1 OR f_uid=".$userID.")";

            if(($count_wert = _dbQuery($count_sql, 'COUNT'))) {
                $count  = '<a href="'.$zieldatei."folder=".$row["f_id"];
                $count .= '%7C'.$folder_status.'">'.on_off($folder_status, $dirname, 0, $counter).'</a>';
            } else {
                $count = on_off($folder_status, $dirname, 0, $counter);
            }

            $dirname = '<a href="'.$zieldatei."files=".$row["f_id"].'" data-toggle="tooltip" title="'.$GLOBALS['BL']['SHOW_FILES1'].'">'. $dirname . '</a>';

            if($row["f_id"] == $_SESSION["imgdir"]) {
                $bgcol = ' bgcolor="#FFF5C9"';
                $current_dirname = $row["f_name"];
            } else {
                $bgcol = '';
            }

            echo "<tr".$bgcol."><td class=\"text-nowrap\">";
            echo $count.'<i class="fa fa-folder mx-1 fa-fw" aria-hidden="true"></i>';
            echo "".$dirname."</td></tr>\n";


            if(!$folder_status && $count_wert) {
                folder_list($row["f_id"], $counter+1, $zieldatei);
            }

            $_SESSION["list_zaehler"]++;
        }
    }
}

function on_off($wert, $string, $art=1, $counter=0) {
    //Erzeugt das Status-Zeichen für Klapp-Auf/Zu
    //Wenn Art = 1 dann als Zeichen, ansonsten als Bild
    if($wert) {
        return ($art == 1) ? "+" : '<i class="far fa-plus-square fa-fw px-1 slist-'.$counter.'" aria-hidden="true" data-toggle="tooltip" title="'.$GLOBALS['BL']['be_fprivfunc_opendir'].': '.$string.'"></i>';
    } else {
        return ($art == 1) ? "-" : '<i class="far fa-minus-square fa-fw px-1 slist-'.$counter.'" aria-hidden="true" data-toggle="tooltip" title="'.$GLOBALS['BL']['be_fprivfunc_closedir'].': '.$string.'"></i>';
    }
}
function true_false($wert) {
    // Swap true / false
    return intval($wert) ? 0 : 1;
}
