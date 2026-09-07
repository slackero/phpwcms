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

    require PHPWCMS_ROOT . '/include/inc_lang/backend/en/lang.inc.php';
    if (!empty($_SESSION['wcs_user_lang_custom'])) {
        $cust_lang = PHPWCMS_ROOT . '/include/inc_lang/backend/' . strtolower($_SESSION['wcs_user_lang']) . '/lang.inc.php';
        if (is_file($cust_lang)) {
            include $cust_lang;
        }
    }

}

$js_aktion = empty($_GET["opt"]) ? 0 : (int)$_GET["opt"];

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
    $_SESSION['CKEditorFuncNum'] = (int)$_GET['CKEditorFuncNum'];
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
    $folder_id          = (int)$folder_id;
    $folder_value       = (int)$folder_value;
    $folder[$folder_id] = $folder_value;
    $_SESSION["folder"] = $folder; // Return array with current opened folder session values
    $_SESSION["imgdir"] = $folder_id;
}
$_SESSION["list_zaehler"] = 0;

// Which folder is active
if(isset($_GET["files"])) {

    $_SESSION["imgdir"] = (int)$_GET["files"];

} elseif(!isset($_SESSION["imgdir"])) {

    $_SESSION["imgdir"] = 0;

} elseif(isset($_SESSION["imgdir"])) {

    $_SESSION["imgdir"] = (int)$_SESSION["imgdir"];

}

//Does user have files and folders that can be used
$sql = "SELECT COUNT(f_id) FROM ".DB_PREPEND."file WHERE f_aktiv=1 AND (f_public=1 OR f_uid=".(int)$_SESSION["wcs_user_id"].") AND f_trash=0";
$count_user_files = _dbQuery($sql, 'COUNT');

?><!DOCTYPE html>
<html <?php echo get_backend_html_tag_attributes($user_lang); ?>>
<head>
    <title><?php echo $titel ?></title>
    <meta charset="<?php echo PHPWCMS_CHARSET ?>" />
    <?php echo get_theme_boot_script(); ?>
    <link href="include/inc_css/backend.min.css" rel="stylesheet" type="text/css">
    <link href="include/inc_css/dropzone.min.css" rel="stylesheet" type="text/css">
    <script src="include/inc_js/jquery/jquery-3.7.1.min.js"></script>
    <script src="include/inc_js/bootstrap.bundle.min.js"></script>
    <script src="include/inc_js/dropzone.min.js"></script>
    <?php echo getJavaScriptTranslations(); ?>
    <script src="include/inc_js/phpwcms.min.js"></script>
    <script>
        var fileExtIcons = <?php echo json_encode(array_map(function($icon) { return 'fa-solid fa-' . $icon; }, ext_icon_map())) ?>;
        function addFile(obj, text, value) {
            if (obj && obj.options) {
                const newOpt = new Option(text, value, false, false);
                obj.add(newOpt);
            }
        }
    </script>
</head>
<body class="filebrowser">

<div class="d-flex align-items-center justify-content-between mb-3">
  <h2 class="m-0"><?php echo ($js_aktion == 16) ? $BL['be_article_title'] : $BL['FILE_TITLE']; ?></h2>
  <button type="button" class="btn btn-blue btn-sm" id="showuploader"><i class="fas fa-cloud-upload-alt me-1"></i><?php echo $BL['be_file_multiple_upload'] ?></button>
</div>
	<div class="uploader filebrowser-uploader" id="filebrowser-uploader" style="display:none">
      <form action="include/inc_act/act_multiupload.php?<?php echo get_token_get_string(); ?>" class="dropzone mb-2" id="filebrowser-dropzone"></form>
      <div id="dropzone-errors" class="mb-3"></div>
      <div class="filebrowser-form card card-body bg-light p-3 mb-3">
			<div class="form-group mb-2">
				<label class="fw-bold small mb-1" for="file_longinfo"><?php echo $BL['be_ftptakeover_longinfo'] ?></label>
				<textarea cols="40" rows="2" id="file_longinfo" class="form-control form-control-sm"></textarea>
			</div>
			<div class="form-group mb-2">
				<label class="fw-bold small mb-1" for="file_copyright"><?php echo $BL['be_copyright'] ?></label>
				<input name="file_copyright" type="text" id="file_copyright" class="form-control form-control-sm" maxlength="255" value="" />
			</div>
			<div class="form-group mb-0">
				<span class="fw-bold small mb-1 d-block"><?php echo $BL['be_tags'] ?></span>
				<input type="text" id="file_tags_autosuggest" class="form-control form-control-sm" aria-label="<?php echo html_specialchars($BL['be_tags']) ?>" />
			</div>
			<div class="mt-3 text-end">
				<button type="button" class="btn btn-success btn-sm fw-bold px-3" id="upload-trigger-send"><i class="fas fa-upload me-1"></i><?php echo $BL['be_files_upload'] ?></button>
			</div>
      </div>
	</div>

<div class="filebrowser-layout">
  <div class="filebrowser-col">
    <div class="filebrowser-col-head bg-grey ps-3 py-1 fw-bold"><?php echo $BL['FOLDER_LIST'] ?></div>
    <div class="filebrowser-scroll"><?php

if(!empty($count_user_files)) { //Listing in case of user files/folders

    echo '<table class="table table-sm table-borderless mt-2 mb-0">'.LF;

    //Anzeige des Festplattensymbols
    $dirname = $BL['ROOT_DIR'];
    if(!isset($folder[0])) {
        $folder[0] = 0;
    }
    $folder_status = true_false($folder[0]);
    $counter = 0;

    $count_sql = "SELECT COUNT(f_id) FROM ".DB_PREPEND."file WHERE f_pid=0 AND f_aktiv=1 AND f_trash=0 AND (f_public=1 OR f_uid=".(int)$_SESSION["wcs_user_id"].")";

    if(($count_wert = _dbQuery($count_sql, 'COUNT'))) {
        $count  = '<a href="filebrowser.php?opt='.$js_aktion.'&amp;folder=0';
        $count .= '%7C'.$folder_status.'">'.on_off($folder_status, $dirname, 0, $counter).'</a>';
    } else {
        $count = on_off($folder_status, $dirname, 0, $counter);
        }

    // define current directory name
    $current_dirname = $dirname;

    $dirname    =  "<a href=\"filebrowser.php?opt=".$js_aktion."&amp;files=0\" title=\"".$BL['SHOW_FILES'].'">'.$dirname."</a>";
    $dir_class  = $_SESSION["imgdir"] == 0 ? ' filebrowser-dir-current' : '';

    echo '<tr class="text-nowrap'.$dir_class.'"><td class="text-nowrap">';
    echo $count.'<i class="fa-solid fa-desktop fa-fw me-2" aria-hidden="true"></i>';
    echo $dirname.'</td></tr>'.LF;

    //Wenn überhaupt Ordner für User vorhanden, dann Listing
    if(!$folder_status && $count_wert) {
        folder_list(0, 1, "filebrowser.php?opt=".$js_aktion."&amp;");
    }

    echo '</table>';
} else {
    echo '<div class="msglist py-2 ps-3 text-muted">'.$BL['NO_FILE'].'</div>';
}

    ?></div>
  </div>
  <div class="filebrowser-col">
    <?php

    // extension filter per browser mode - single source for file listing SQL
    // and dropzone upload acceptance (null = no filter / fall back to global)
    $filebrowser_ext_sql = null;
    $filebrowser_ext_upload = null;
    $image_ext = array('jpeg', 'jpg', 'png', 'gif', 'svg', 'webp');
    if($phpwcms['image_library'] !== 'gd2') {
        $image_ext = array_merge($image_ext, array('pdf', 'ai', 'psd', 'tif', 'tiff', 'bmp', 'eps'));
    }
    switch($js_aktion) {

        case 6:
            $filebrowser_ext_sql = $filebrowser_ext_upload = array('swf', 'mp3', 'flv', 'mp4', 'm4v', 'f4v', 'jpg', 'jpeg', 'png', 'gif', 'aac', 'webp');
                    break;

                    // H.264
        case 12:
            $filebrowser_ext_sql = $filebrowser_ext_upload = array('mp4', 'm4p', 'mov', 'm4a', 'm4v', 'mp3', 'mpeg', 'aac');
                    break;

                    // WebM
        case 13:
            $filebrowser_ext_sql = $filebrowser_ext_upload = array('webm');
                    break;

                    // Ogg
        case 14:
            $filebrowser_ext_sql = $filebrowser_ext_upload = array('ogg', 'ogv', 'oga', 'ogx');
                    break;

                    // Typical Doc files
        case 18:
            $filebrowser_ext_sql = $filebrowser_ext_upload = array('pdf', 'doc', 'docx', 'txt', 'xls', 'xlsx', 'ppt', 'pptx', 'odt', 'ods', 'odp', 'pages', 'key', 'numbers');
            // no break here
        case 15:
            $entry_id  = empty($_SESSION['filebrowser_image_entry_id']) ? '' : $_SESSION['filebrowser_image_entry_id'];
            break;

            // Custom content part file selector
        case 19:
            $allowed_ext = empty($_SESSION['filebrowser_allowed_ext']) ? array() : $_SESSION['filebrowser_allowed_ext'];
            if(!empty($_GET['allowed'])) {
                $allowed_ext = convertStringToArray(strtolower($_GET['allowed']));
            }
            // store bare extensions (a-z0-9 only), safe for SQL and JS usage
            $allowed_ext = array_values(array_unique(array_filter(array_map(
                function($ext) {
                    return preg_replace('/[^a-z0-9]/', '', strtolower(trim($ext, " \t\n\r'\"")));
                },
                is_array($allowed_ext) ? $allowed_ext : array()
            ))));
            if(count($allowed_ext)) {
                $_SESSION['filebrowser_allowed_ext'] = $allowed_ext;
                $filebrowser_ext_sql = $filebrowser_ext_upload = $allowed_ext;
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
            $filebrowser_ext_sql = $filebrowser_ext_upload = $image_ext;
                    break;

            // image browser modes - no listing filter, non-image files
            // are skipped by the thumbnail check anyway
        case 0:
        case 1:
        case 3:
        case 5:
            $filebrowser_ext_upload = $image_ext;
                    break;

        case 2:
            if(!empty($phpwcms["multimedia_ext"])) {
                $allowed_ext = convertStringToArray(strtolower($phpwcms["multimedia_ext"]));
                if(count($allowed_ext)) {
                    $filebrowser_ext_sql = $filebrowser_ext_upload = $allowed_ext;
                    break;
                }
            }
            $filebrowser_ext_sql = $filebrowser_ext_upload = array('aif', 'aiff', 'mov', 'movie', 'mp3', 'mpeg', 'mpeg4', 'mpeg2', 'wav', 'swf', 'ram', 'ra', 'wma', 'wmv', 'avi', 'au', 'midi', 'moov', 'rm', 'rpm', 'mid');
                    break;

    }

    $file_sql  = "SELECT * FROM ".DB_PREPEND."file WHERE f_pid=".$_SESSION["imgdir"]." AND ";
    if(is_array($filebrowser_ext_sql)) {
        $file_sql .= "f_ext IN ('" . implode("', '", $filebrowser_ext_sql) . "') AND ";
    }
    $file_sql .= "f_aktiv=1 AND f_kid=1 AND f_trash=0 AND ";
    $file_sql .= "(f_public=1 OR f_uid=".(int)$_SESSION["wcs_user_id"].") ";
    $file_sql .= "ORDER BY f_sort, f_name";

    if (empty($_SESSION['CKEditorFuncNum'])) {
        $ckeditor_action = isset($_GET['CKEditorFuncNum']) ? (int)$_GET['CKEditorFuncNum'] : 0;
    } else {
        $ckeditor_action = $_SESSION['CKEditorFuncNum'];
    }

    $file_result = _dbQuery($file_sql);

    // modes supporting "add all files" (js_files_all collected in listing loop)
    $add_all_possible = isset($file_result[0]['f_id']) && in_array($js_aktion, array(1, 3, 4, 5, 9));

    echo '<div class="filebrowser-col-head bg-grey ps-3 py-1 pe-1 fw-bold d-flex align-items-center">';
    echo '<span class="text-truncate">'.$filetype.'</span>';
    if($add_all_possible) {
        echo '<a href="#" class="btn btn-xs btn-blue py-0 px-2 ms-auto text-nowrap" onclick="addAllFiles();return false;" data-bs-toggle="tooltip" title="';
        echo $BL['ADD_ALL_FILES'].'">';
        echo $BL['ADD_ALL_FILES'].' <i class="fa-solid fa-plus" aria-hidden="true"></i></a>';
    }
    echo '</div>'.LF;

    echo '<div class="filebrowser-scroll">'.LF;

    //Tabelle

    echo '<table class="table table-sm table-borderless mt-2 mb-0">'.LF;

    if(isset($file_result[0]['f_id'])) {

        $target_form = (empty($_SESSION['image_browser_article'])) ? 'articlecontent' : 'article';

        $bg_toggle = false;
        foreach($file_result as $file_durchlauf => $file_row) {

            $filename = html($file_row["f_name"]);
            $filename_json = json_encode($filename);

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

                $js_files_select[] = array(count($js_files_select), (int)$file_row["f_id"], $filename);

                //change js call so it works inside modal
                switch($js_aktion) {
                    case 0:
                        $jst = empty($_SESSION['filebrowser_image_target']) ? '_' : $_SESSION['filebrowser_image_target'];

                        $js  = "parent.document.".$target_form.".cimage".$jst."name.value=" . $filename_json . ";";
                        $js .= "parent.document.".$target_form.".cimage".$jst."id.value='".$file_row["f_id"]."';";
                        $js .= "if (typeof parent.onImageSelected === 'function') { parent.onImageSelected('".$jst."', '".$file_row["f_id"]."', " . $filename_json . "); }";
                        break;

                    case 2:
                        $js  = "parent.document.articlecontent.cmedia_name.value=" . $filename_json . ";";
                        $js .= "parent.document.articlecontent.cmedia_id.value='".$file_row["f_id"]."';";
                        break;

                    case 6:
                    case 12:
                    case 13:
                    case 14:
                        $js = "parent.setIdName('".$file_row["f_id"]."', " . $filename_json . ", ".$js_aktion.");";
                        break;

                    case 19:
                    case 18:
                    case 15:
                        $js = "parent.setIdName('".$entry_id."', '".$file_row["f_id"]."', " . $filename_json . ");";
                        break;

                    case 7:
                        $js = "parent.setImgIdName('".$file_row["f_id"]."', " . $filename_json . ");";
                        break;

                    case 8:
                        $js = "parent.setImgIdName('".$entry_id."', '".$file_row["f_id"]."', " . $filename_json . ");";
                        break;

                    case 4:
                        $js = "addFile(parent.document.getElementById('cfile_list') || (parent.document.articlecontent && parent.document.articlecontent.cfile_list)," . $filename_json . ",'".$file_row["f_id"]."');";
                        $js_files_all[] = $js;
                        break;

                    case 9:
                        $js = "parent.addFile('".$file_row["f_id"]."', " . $filename_json . ");";
                        $js_files_all[] = $js;
                        break;

                    case 5:
                        $js = "addFile(parent.img_field," . $filename_json . ",'".$file_row["f_id"]."');";
                        $js_files_all[] = $js;
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
                        $js = "addFile(parent.document.articlecontent.cimage_list," . $filename_json . ",'".$file_row["f_id"]."');";
                        $js_files_all[] = $js;
                }

                echo '<tr'.$row_class.'><td class="file-icon-col"><i class="fa-solid fa-fw fa-'.ext_icon($file_row["f_ext"]).'" data-bs-toggle="tooltip" data-bs-html="true" title="ID: '.$file_row["f_id"].'&lt;br&gt;Sort: '.$file_row["f_sort"].'&lt;br&gt;Name: '.html($file_row["f_name"]);
                    if($file_row["f_copyright"]) {
                        echo '&lt;br&gt;&copy;: '.html($file_row["f_copyright"]);
                    }
                    echo '"></i></td>';
                echo '<td class="filebrowser-name-col">';

                $js_attr = html($js);

                if($js_aktion != 4 && $js_aktion != 10 && $js_aktion != 16) {
                    echo $filename.'</td><td class="text-end filebrowser-take-col">';
                } else if($js_aktion == 16 || $js_aktion == 17) {
                  echo '<a href="#" onclick="' . $js_attr . 'tmt_winControl(\'self\',\'close()\');">' . $filename . '</a></td><td class="text-end filebrowser-take-col">';
                } else {
                    echo '<a href="#" onclick="' . $js_attr . 'parent.$(\'#browserModal\').modal(\'hide\');">' . $filename . '</a></td><td class="text-end filebrowser-take-col">';
                }

                echo '<a href="#" class="btn btn-xs btn-blue" onclick="' . $js_attr . 'return false;" data-bs-toggle="tooltip" title="' . html($BL['TAKE_IMAGE']) . '">';
                echo '<i class="fa-solid fa-plus" aria-hidden="true"></i></a></td>';
                echo '</tr>';
                if((!empty($thumb_image[0]) || $file_row['f_svg']) && in_array( $js_aktion, array(0, 1, 3, 5, 6, 7, 8, 10, 11, 17, 18, 19) ) ) {
                    filebrowser_thumb_row($row_class, $js_attr, ($js_aktion == 16 || $js_aktion == 17), $thumb_image, $file_row, $phpwcms);
                }
            }

        }
        if(count($js_files_select) === 0) { //Abschluss der Filelisten-Tabelle
            echo '<tr><td colspan="4" class="msglist py-2 ps-3 text-muted">'.$BL['NO_FILE'].'</td></tr>';
        }
    } else {
        echo '<tr><td colspan="4" class="msglist py-2 ps-3 text-muted">'.$BL['NO_FILE'].'</td></tr>';
    }

    echo '</table>'.LF;
    echo '</div>'.LF;

    if( count($js_files_select) ) {

        echo LF . '<script type="text/javascript">';
        echo LF . SCRIPT_CDATA_START . LF;

        echo 'var files_all = ' . json_encode($js_files_select) . ';';
        echo LF . 'var files_total = ' . count($js_files_select) . ';';

        echo LF . LF;
        echo 'function addAllFiles() {';
        echo LF . ' ';
        echo implode(LF . ' ', $js_files_all);
        echo LF . ' //if(closewin == true) '."parent.$('#browserModal').modal('hide');";
        $confirm = str_replace('{VAL}', $current_dirname, $BL['ADD_ALL_CONFIRM']);
        if(PHPWCMS_CHARSET !== 'utf-8') {
            $confirm = mb_convert_encoding($confirm, PHPWCMS_CHARSET);
        }
        echo LF . ' bsConfirmInfo(' . json_encode($confirm, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT) . ', function() { parent.$(\'#browserModal\').modal(\'hide\'); });';
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

    ?></div>
  </div>
</div>
<script>
Dropzone.autoDiscover = false;

$(function() {
    initTomSelectTagAutosuggest('#file_tags_autosuggest', '#file_tags', 'category');

    $('.structarticle').on('click', function () {
        parent.$('#browserModal').modal('hide');
    });

    var maxMB = <?php
        $post_max_size = ini_get('post_max_size') ? return_bytes(ini_get('post_max_size')) : $phpwcms['file_maxsize'];
        $upload_max_filesize = ini_get('upload_max_filesize') ? return_bytes(ini_get('upload_max_filesize')) : $phpwcms['file_maxsize'];
        $maxBytes = min($post_max_size, $upload_max_filesize, $phpwcms['file_maxsize']);
        echo round($maxBytes / 1048576, 2);
    ?>;

    var bs4PreviewTemplate = '<div class="dz-preview dz-file-preview dz-preview-bs4 d-flex align-items-center justify-content-between">' +
        '<div class="d-flex align-items-center overflow-hidden me-3" style="min-width: 0;">' +
            '<div class="me-3 flex-shrink-0 dz-thumb-container">' +
                '<img data-dz-thumbnail class="dz-thumbnail d-none" />' +
                '<div class="dz-icon-placeholder"><i class="fas fa-file"></i></div>' +
            '</div>' +
            '<div class="overflow-hidden" style="min-width: 0;">' +
                '<div class="fw-bold text-truncate text-dark" data-dz-name></div>' +
                '<div class="small text-muted d-flex align-items-center">' +
                    '<span data-dz-size class="me-2"></span>' +
                '</div>' +
                '<div class="progress dz-progress-bar d-none"><div class="progress-bar bg-primary progress-bar-striped progress-bar-animated" role="progressbar" style="width: 0%" data-dz-uploadprogress></div></div>' +
            '</div>' +
        '</div>' +
        '<div class="flex-shrink-0 ms-2">' +
            '<button class="btn btn-outline-danger py-1 px-3" data-dz-remove><i class="fas fa-times me-1"></i><?php echo str_replace("'", "\\'", $BL["be_newsletter_button_cancel"]); ?></button>' +
        '</div>' +
    '</div>';

    // icon classes come from the PHP ext_icon_map() source (fileExtIcons),
    // colors are preview-only sugar
    const fileExtIconColor = {
        'file-pdf':        'text-danger',
        'file-word':       'text-primary',
        'file-excel':      'text-success',
        'file-powerpoint': 'text-warning',
        'file-archive':    'text-secondary',
        'file-audio':      'text-info',
        'file-video':      'text-info',
        'file-image':      'text-image',
        'file-code':       'text-code'
    };
    const getFileIconClass = (filename) => {
        var ext = filename.split('.').pop().toLowerCase();
        var icon = fileExtIcons[ext] || 'fa-solid fa-file';
        var color = fileExtIconColor[icon.replace('fa-solid fa-', '')] || 'text-muted';
        return icon + ' ' + color;
    };

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
            // reuse the mode-specific upload extension list resolved above
            $modal_ext = is_array($filebrowser_ext_upload) ? $filebrowser_ext_upload : array();

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

            echo count($effective_ext) ? json_encode('.' . implode(',.', array_values($effective_ext))) : 'null';
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
            $msg = !empty($BL['be_fileuploader_dictDefaultMessage']) ? $BL['be_fileuploader_dictDefaultMessage'] : (!empty($BL['be_fileuploader_uploadButtonText']) ? $BL['be_fileuploader_uploadButtonText'] : 'Drop files here to upload');
            echo json_encode(html_entity_decode($msg, ENT_QUOTES | ENT_HTML5, 'UTF-8')) ?: '""';
        ?>,
        dictFallbackMessage: <?php
            $msg = !empty($BL['be_fileuploader_dictFallbackMessage']) ? $BL['be_fileuploader_dictFallbackMessage'] : 'Your browser does not support drag and drop.';
            echo json_encode(html_entity_decode(strip_tags($msg), ENT_QUOTES | ENT_HTML5, 'UTF-8')) ?: '""';
        ?>,
        dictFallbackText: <?php
            $msg = !empty($BL['be_fileuploader_dictFallbackText']) ? $BL['be_fileuploader_dictFallbackText'] : '';
            echo json_encode(html_entity_decode(strip_tags($msg), ENT_QUOTES | ENT_HTML5, 'UTF-8')) ?: '""';
        ?>,
        dictFileTooBig: <?php
            $msg = !empty($BL['be_fileuploader_dictFileTooBig']) ? $BL['be_fileuploader_dictFileTooBig'] : 'File is too big ({{filesize}}MiB). Max filesize: {{maxFilesize}}MiB.';
            echo json_encode(html_entity_decode(strip_tags($msg), ENT_QUOTES | ENT_HTML5, 'UTF-8')) ?: '""';
        ?>,
        dictInvalidFileType: <?php
            $msg = !empty($BL['be_fileuploader_dictInvalidFileType']) ? $BL['be_fileuploader_dictInvalidFileType'] : 'Invalid file type.';
            echo json_encode(html_entity_decode(strip_tags($msg), ENT_QUOTES | ENT_HTML5, 'UTF-8')) ?: '""';
        ?>,
        dictResponseError: <?php
            $msg = !empty($BL['be_fileuploader_dictResponseError']) ? $BL['be_fileuploader_dictResponseError'] : 'Server error {{statusCode}}.';
            echo json_encode(html_entity_decode(strip_tags($msg), ENT_QUOTES | ENT_HTML5, 'UTF-8')) ?: '""';
        ?>,
        dictCancelUpload: <?php
            $msg = !empty($BL['be_fileuploader_dictCancelUpload']) ? $BL['be_fileuploader_dictCancelUpload'] : 'Cancel';
            echo json_encode(html_entity_decode(strip_tags($msg), ENT_QUOTES | ENT_HTML5, 'UTF-8')) ?: '""';
        ?>,
        dictCancelUploadConfirmation: <?php
            $msg = !empty($BL['be_fileuploader_dictCancelUploadConfirmation']) ? $BL['be_fileuploader_dictCancelUploadConfirmation'] : 'Cancel upload?';
            echo json_encode(html_entity_decode(strip_tags($msg), ENT_QUOTES | ENT_HTML5, 'UTF-8')) ?: '""';
        ?>,
        dictRemoveFile: <?php
            $msg = !empty($BL['be_fileuploader_dictRemoveFile']) ? $BL['be_fileuploader_dictRemoveFile'] : 'Remove';
            echo json_encode(html_entity_decode(strip_tags($msg), ENT_QUOTES | ENT_HTML5, 'UTF-8')) ?: '""';
        ?>,
        dictMaxFilesExceeded: <?php
            $msg = !empty($BL['be_fileuploader_dictMaxFilesExceeded']) ? $BL['be_fileuploader_dictMaxFilesExceeded'] : 'Max files exceeded.';
            echo json_encode(html_entity_decode(strip_tags($msg), ENT_QUOTES | ENT_HTML5, 'UTF-8')) ?: '""';
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
                    var alertHtml = '<div id="' + errorId + '" class="alert alert-danger fade show d-flex align-items-start mt-2 mb-0 py-2 px-3 small" role="alert">' +
                        '<i class="fas fa-exclamation-triangle me-2 mt-1 flex-shrink-0"></i>' +
                        '<div>' + errText + '</div>' +
                        '<button type="button" class="btn-close ms-auto ps-2 dz-alert-close" data-file-uuid="' + (file.upload ? file.upload.uuid : '') + '" aria-label="Close"></button>' +
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
    $pid = (int)$pid;
    $userID = (int)$_SESSION["wcs_user_id"];
    $sql = "SELECT f_id, f_name, f_aktiv, f_public FROM ".DB_PREPEND."file WHERE ".
           "f_pid=".(int)$pid." AND f_aktiv=1 AND f_kid=0 AND f_trash=0 AND ".
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
            $count_sql = "SELECT COUNT(f_id) FROM ".DB_PREPEND."file WHERE f_pid=".$row["f_id"]." AND f_trash=0 AND f_aktiv=1 AND (f_public=1 OR f_uid=".$userID.")";

            if(($count_wert = _dbQuery($count_sql, 'COUNT'))) {
                $count  = '<a href="'.$zieldatei."folder=".$row["f_id"];
                $count .= '%7C'.$folder_status.'">'.on_off($folder_status, $dirname, 0, $counter).'</a>';
            } else {
                $count = on_off($folder_status, $dirname, 0, $counter);
            }

            $dirname = '<a href="'.$zieldatei."files=".$row["f_id"].'" data-bs-toggle="tooltip" title="'.$GLOBALS['BL']['SHOW_FILES1'].'">'. $dirname . '</a>';

            if($row["f_id"] == $_SESSION["imgdir"]) {
                $bgcol = ' class="text-nowrap filebrowser-dir-current"';
                $current_dirname = $row["f_name"];
            } else {
                $bgcol = ' class="text-nowrap"';
            }

            echo "<tr".$bgcol."><td class=\"text-nowrap\">";
            echo $count.'<i class="fa-solid fa-folder mx-1 fa-fw" aria-hidden="true"></i>';
            echo "".$dirname."</td></tr>\n";


            if(!$folder_status && $count_wert) {
                folder_list($row["f_id"], $counter+1, $zieldatei);
            }

            $_SESSION["list_zaehler"]++;
        }
    }
}

function filebrowser_thumb_row($row_class, $js_attr, $close_window, $thumb_image, $file_row, $phpwcms) {
    // render thumbnail row below a file row (SVG original or cached preview)
    $close_js = $close_window ? "tmt_winControl('self','close');" : "parent.$('#browserModal').modal('hide');";
    echo '<tr class="filebrowser-thumb-row"'.$row_class.'><td></td><td class="pb-1 pt-0" colspan="2"><a href="#" onclick="' . $js_attr . $close_js . '">';
    if(!empty($file_row['f_svg'])) {
        echo '<img src="'.PHPWCMS_RESIZE_IMAGE.'/'.$phpwcms["img_list_width"].'x'.$phpwcms["img_list_height"].'/'.$file_row['f_hash'].'.'.$file_row['f_ext'].'" alt="" class="img-fluid img-thumbnail" style="max-height: '.$phpwcms["img_list_height"].'px; object-fit: contain;" />';
    } elseif(is_array($thumb_image) && !empty($thumb_image[0])) {
        echo '<img src="'.PHPWCMS_IMAGES.$thumb_image[0].'" alt="" class="img-fluid img-thumbnail" style="max-height: '.$phpwcms["img_list_height"].'px; object-fit: contain;" />';
    }
    echo '</a></td></tr>'.LF;
}

function on_off($wert, $string, $art=1, $counter=0) {
    //Erzeugt das Status-Zeichen für Klapp-Auf/Zu
    //Wenn Art = 1 dann als Zeichen, ansonsten als Bild
    if($wert) {
        return ($art == 1) ? "+" : '<i class="fa-solid fa-caret-right fa-fw slist-'.$counter.'" aria-hidden="true" data-bs-toggle="tooltip" title="'.$GLOBALS['BL']['be_fprivfunc_opendir'].': '.$string.'"></i>';
    } else {
        return ($art == 1) ? "-" : '<i class="fa-solid fa-caret-down fa-fw slist-'.$counter.'" aria-hidden="true" data-bs-toggle="tooltip" title="'.$GLOBALS['BL']['be_fprivfunc_closedir'].': '.$string.'"></i>';
    }
}
function true_false($wert) {
    // Swap true / false
    return (int)$wert ? 0 : 1;
}
