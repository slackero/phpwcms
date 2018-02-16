<?php
/**
 * cmsGo!
 *
 * @author Pixels & Points GmbH <info@pixels-points.ch>
 * @copyright Copyright (c) 2002-2017, Pixels & Points GmbH
 * @license https://www.pixels-points.ch/cmsgo-license.html Pixels & Points cmsGo! license
 *
 **/

// ----------------------------------------------------------------
// obligate check for cmsgo constants
if (!defined('CMSGO_ROOT')) {
    die("You Cannot Access This Script Directly, Have a Nice Day.");
}
// ----------------------------------------------------------------


// If there are files marked to be deleted
$deleteFiles = array();

if(isset($_POST['ftp_mark']) && is_array($_POST['ftp_mark']) && count($_POST['ftp_mark'])) {

    foreach($_POST['ftp_mark'] as $key => $value) {

        $deleteFiles[$_POST['ftp_file'][$key]] = $_POST['ftp_filename'][$key];

    }

}

$GLOBALS['BE']['HEADER']['fileuploaderaa.js'] = '
<script type="text/template" id="qq-template-manual-trigger">
        <div class="qq-uploader-selector qq-uploader" qq-drop-area-text="Dateien hier ablegen">
            <div class="qq-total-progress-bar-container-selector qq-total-progress-bar-container">
                <div role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" class="qq-total-progress-bar-selector qq-progress-bar qq-total-progress-bar"></div>
            </div>
            <div class="qq-upload-drop-area-selector qq-upload-drop-area" qq-hide-dropzone>
                <span class="qq-upload-drop-area-text-selector"></span>
            </div>
            <div class="buttons mb-1">
                <div class="qq-upload-button-selector btn btn-blue">
                    <div>Dateien auswählen</div>
                </div>
                <button type="button" id="trigger-upload" class="btn btn-blue">
                    <i class="icon-upload icon-white"></i> Dateien hochladen
                </button>
            </div>
            <span class="qq-drop-processing-selector qq-drop-processing">
                <span>Dateien werden verarbeitet...</span>
                <span class="qq-drop-processing-spinner-selector qq-drop-processing-spinner"></span>
            </span>
            <ul class="qq-upload-list-selector qq-upload-list mb-1" aria-live="polite" aria-relevant="additions removals">
                <li>
                    <div class="qq-progress-bar-container-selector">
                        <div role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" class="qq-progress-bar-selector qq-progress-bar"></div>
                    </div>
                    <span class="qq-upload-spinner-selector qq-upload-spinner"></span>
                    <img class="qq-thumbnail-selector" qq-max-size="100" qq-server-scale>
                    <span class="qq-upload-file-selector qq-upload-file"></span>
                    <span class="qq-edit-filename-icon-selector qq-edit-filename-icon" aria-label="Edit filename"></span>
                    <input class="qq-edit-filename-selector qq-edit-filename" tabindex="0" type="text">
                    <span class="qq-upload-size-selector qq-upload-size"></span>
                    <button type="button" class="qq-btn qq-upload-cancel-selector qq-upload-cancel btn btn-blue">Schliessen</button>
                    <button type="button" class="qq-btn qq-upload-retry-selector qq-upload-retry btn btn-blue">Wiederholen</button>
                    <button type="button" class="qq-btn qq-upload-delete-selector qq-upload-delete btn btn-red">Löschen</button>
                    <span role="status" class="qq-upload-status-text-selector qq-upload-status-text"></span>
                </li>
            </ul>

            <dialog class="qq-alert-dialog-selector">
                <div class="qq-dialog-message-selector"></div>
                <div class="qq-dialog-buttons">
                    <button type="button" class="qq-cancel-button-selector btn btn-blue">Schliessen</button>
                </div>
            </dialog>

            <dialog class="qq-confirm-dialog-selector">
                <div class="qq-dialog-message-selector"></div>
                <div class="qq-dialog-buttons">
                    <button type="button" class="qq-cancel-button-selector btn btn-blue">Nein</button>
                    <button type="button" class="qq-ok-button-selector btn btn-blue">Ja</button>
                </div>
            </dialog>

            <dialog class="qq-prompt-dialog-selector">
                <div class="qq-dialog-message-selector"></div>
                <input type="text">
                <div class="qq-dialog-buttons">
                    <button type="button" class="qq-cancel-button-selector btn btn-red">Abbrechen</button>
                    <button type="button" class="qq-ok-button-selector btn btn-blue">Ok</button>
                </div>
            </dialog>
        </div>
    </script>
';

?>

    <style>

        #fine-uploader-manual-trigger .qq-upload-button {
            margin-right: 15px;
        }

        #fine-uploader-manual-trigger .buttons {
            width: 45%;
        }

        #fine-uploader-manual-trigger .qq-uploader .qq-total-progress-bar-container {
            width: 50%;
        }
        
        .qq-uploader {
          min-height: 200px;
        }

    </style>

<h1 class="title"><?php echo $BL['be_file_multiple_upload'] ?></h1>


<div id="fine-uploader-manual-trigger"></div>

<h1 class="title"><?php echo $BL['be_files_select_available'] ?></h1>

<form action="include/inc_act/act_ftptakeover.php" method="post" name="ftptakeover" id="ftptakeover">
<table class="table">
  <tr bgcolor="#D9DEE3">
    <th><?php echo $BL['be_ftptakeover_mark'] ?></th>
    <th><?php echo $BL['be_ftptakeover_available'] ?></th>
    <th><?php echo $BL['be_ftptakeover_size'] ?></th>
  </tr>

<?php
        //Browse FTP Open Directory
        $handle = @opendir(CMSGO_ROOT.$cmsgo["ftp_path"]);
        $fx = 0;
        $fxsg = 0;
            while($file = @readdir($handle)) {
                if(!is_dir($file) && $file !== "." && $file !== ".." && substr($file, 0, 1) !== '.' && $fxs = filesize(CMSGO_ROOT.$cmsgo["ftp_path"].$file)) {

                    // test if the file should be deleted
                    $file_base64 = base64_encode($file);

                    if(isset($deleteFiles[$file_base64]) && @unlink(CMSGO_ROOT.$cmsgo["ftp_path"].$file)) {
                            continue;
                        }

                    $fxb = ($fx % 2) ? ' bgColor="#F9FAFB"' : '';
                    $fxsg += $fxs;
                    $fxe = extimg(which_ext($file));
                     // there is a big problem with special chars on Mac OS X and seems Windows too
                    $filename = (CMSGO_CHARSET != 'utf-8' && cmsgo_seems_utf8($file)) ? str_replace('?', '', utf8_decode($file)) : $file;
                    $filename = html($filename);
?>
          <tr<?php echo $fxb ?>>
            <td><input name="ftp_mark[<?php echo $fx ?>]" type="checkbox" id="ftp_mark_<?php echo $fx ?>" value="1" class="ftp_mark" /></td>
            <td><?php echo $filename ?></td>
            <td>
                <?php echo fsizelong($fxs) ?>
                <input name="ftp_file[<?php echo $fx ?>]" type="hidden" value="<?php echo $file_base64 ?>" />
                <input name="ftp_filename[<?php echo $fx ?>]" type="hidden" value="<?php echo $filename ?>" />
            </td>
          </tr>
<?php               $fx++;
                }
            }
        @closedir($handle);

        if(!$fx) {
?>
          <tr>
            <td colspan="2" class="dir">&nbsp;<?php echo $BL['be_ftptakeover_nofile'] ?></td>
            <td></td>
        </tr>
<?php
        } else {
?>

          <tr bgcolor="#EAEDF0">
            <td><input name="toggle" type="checkbox" id="toggle" value="1" title="<?php echo $BL['be_ftptakeover_all'] ?>" /></td>
            <td><button id="delete-selected-files" style="display:none;" class="btn btn-blue"><?php echo $BL['be_delete_selected_files'] ?></button></td>
            <td><?php echo fsizelong($fxsg) ?>&nbsp;</td>
        </tr>
<?php
        }
?>
        </table><?php
        //Nur Zeigen wenn Dateien vorhanden
        if($fx) {

?>

       <table width="538" border="0" cellpadding="0" cellspacing="0" summary="" style="background:#EBF2F4">
          <tr><td colspan="2" valign="top"><img src="img/leer.gif" alt="" width="1" height="5" /></td></tr>
            <tr>
              <td width="67" align="right" class="v09"><?php echo $BL['be_ftptakeover_directory'] ?>:&nbsp;</td>
            <td width="471" class="v10">
                <select name="file_dir" id="file_dir" class="custom-select form-control">
                  <option value="0"><?php echo $BL['be_ftptakeover_rootdir'] ?></option>
                  <?php dir_menu(0, 0, "+", $_SESSION["wcs_user_id"], "+"); ?>
                 </select></td>
            </tr>

        <tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="5"></td></tr>
        <tr><td colspan="2"><img src="img/lines/line-bluelight.gif" alt="" width="538" height="1" /></td></tr>
        <tr bgcolor="#F5F8F9"><td colspan="2" valign="top"><img src="img/leer.gif" alt="" width="1" height="6"></td></tr>

        <tr bgcolor="#F5F8F9">
            <td align="right" class="v09 tdtop1"><?php echo $BL['be_iptc_data'] ?>:&nbsp;</td>
            <td>
                <table border="0" cellpadding="0" cellspacing="0" summary="">
                    <tr>
                        <td><input name="file_iptc_as_caption" type="checkbox" id="file_iptc_as_caption" value="1"<?php if(!empty($cmsgo['iptc_as_caption'])): ?> checked="checked"<?php endif; ?> /></td>
                        <td class="v10"><label for="file_iptc_as_caption"><?php echo $BL['be_iptc_as_caption'] ?></label></td>
                    </tr>
                </table><div id="iptc-info"></div>
            </td>
        </tr>

        <tr bgcolor="#F5F8F9"><td colspan="2" valign="top"><img src="img/leer.gif" alt="" width="1" height="6"></td></tr>
 <tr><td colspan="2"><img src="img/lines/line-bluelight.gif" alt="" width="538" height="1" /></td></tr>
        <tr><td colspan="2" valign="top"><img src="img/leer.gif" alt="" width="1" height="3"></td></tr>

<?php   if(count($cmsgo['allowed_lang']) > 1): ?>

    <tr>
        <td>&nbsp;</td>
        <td class="incell-tabs">

            <a href="#" rel="<?php echo $cmsgo['default_lang'] ?>" title="<?php echo get_language_name($cmsgo['default_lang']) . ' ('.$BL['be_admin_tmpl_default'].')' ?>" class="active">
                <img src="img/famfamfam/lang/<?php echo $cmsgo['default_lang'] ?>.png" /> <?php echo $BL['be_admin_tmpl_default'] ?>
            </a>

            <?php foreach($cmsgo['allowed_lang'] as $lang):

                $lang = strtolower($lang);

                if($lang == $cmsgo['default_lang']) {
                    continue;
                }

            ?>

            <a href="#" rel="<?php echo $lang ?>" title="<?php echo get_language_name($lang) ?>">
                <img src="img/famfamfam/lang/<?php echo $lang ?>.png" /> <?php echo strtoupper($lang) ?>
            </a>

            <?php   endforeach; ?>

        </td>
    </tr>

<?php   endif; ?>

    <tr class="tab-content finfo<?php echo $cmsgo['default_lang'] ?>">
        <td align="right" class="v09"><?php echo $BL['be_attr_title'] ?>:&nbsp;</td>
        <td class="tdbottom2"><input name="file_title" type="text" id="file_title" class="form-control" maxlength="1000" value="" /></td>
    </tr>
    <tr class="tab-content finfo<?php echo $cmsgo['default_lang'] ?>">
        <td align="right" valign="top" class="v09 tdtop5"><img src="img/leer.gif" alt="" width="1" height="13"><?php echo $BL['be_cnt_description'] ?>:&nbsp;</td>
        <td valign="top" class="tdbottom2"><textarea name="file_longinfo" cols="40" rows="4" class="form-control autosize" id="file_longinfo"></textarea></td>
    </tr>
    <tr class="tab-content finfo<?php echo $cmsgo['default_lang'] ?>">
        <td align="right" class="v09"><?php echo $BL['be_copyright'] ?>:&nbsp;</td>
        <td class="tdbottom2"><input name="file_copyright" type="text" id="file_copyright" class="form-control" maxlength="1000" value="" /></td>
    </tr>
    <tr class="tab-content finfo<?php echo $cmsgo['default_lang'] ?>">
        <td align="right" class="v09 nowrap">&nbsp;<?php echo $BL['be_attr_alt'] ?>:&nbsp;</td>
        <td><input name="file_alt" type="text" id="file_alt" class="form-control" maxlength="1000" value="" /></td>
    </tr>

<?php   if(count($cmsgo['allowed_lang']) > 1):

            foreach($cmsgo['allowed_lang'] as $lang):

                $lang = strtolower($lang);

                if($lang == $cmsgo['default_lang']) {
                    continue;
                }

?>

    <tr class="tab-content finfo<?php echo $lang ?>" style="display:none">
        <td align="right" class="v09"><?php echo $BL['be_attr_title'] ?>:&nbsp;</td>
        <td class="tdbottom2"><input name="file_title_<?php echo $lang ?>" type="text" id="file_title_<?php echo $lang ?>"  class="form-control" maxlength="1000" value="" /></td>
    </tr>
    <tr class="tab-content finfo<?php echo $lang ?>" style="display:none">
        <td align="right" valign="top" class="v09 tdtop5"><img src="img/leer.gif" alt="" width="1" height="13"><?php echo $BL['be_cnt_description'] ?>:&nbsp;</td>
        <td valign="top" class="tdbottom2"><textarea name="file_longinfo_<?php echo $lang ?>" rows="4" class="form-control autosize" id="file_longinfo_<?php echo $lang ?>"></textarea></td>
    </tr>
    <tr class="tab-content finfo<?php echo $lang ?>" style="display:none">
        <td align="right" class="v09"><?php echo $BL['be_copyright'] ?>:&nbsp;</td>
        <td class="tdbottom2"><input name="file_copyright_<?php echo $lang ?>" type="text" id="file_copyright_<?php echo $lang ?>" class="form-control" maxlength="1000" value="" /></td>
    </tr>
    <tr class="tab-content finfo<?php echo $lang ?>" style="display:none">
        <td align="right" class="v09 nowrap">&nbsp;<?php echo $BL['be_attr_alt'] ?>:&nbsp;</td>
        <td><input name="file_alt_<?php echo $lang ?>" type="text" id="file_alt_<?php echo $lang ?>" class="form-control" maxlength="1000" value="" /></td>
    </tr>

<?php       endforeach;
        endif;
?>

    <tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="6" /></td></tr>
    <tr><td colspan="2"><img src="img/lines/line-bluelight.gif" alt="" width="538" height="1" /></td></tr>
    <tr bgcolor="#F5F8F9"><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="6" /></td></tr>
    <?php

    //Auswahlliste vordefinierte Keywörter
    $sql = "SELECT * FROM ".DB_PREPEND."cmsgo_filecat WHERE fcat_deleted=0 ORDER BY fcat_sort, fcat_name";
    $result = _dbQuery($sql);
    $k = '';

    if(isset($result[0]['fcat_id'])) {
        foreach($result as $row) {
            if(get_filecat_childcount($row["fcat_id"])) {

                $ke = empty($file_error["keywords"][$row["fcat_id"]])? '' : "<img src=\"img/symbole/error.gif\" width=\"8\" height=\"9\">&nbsp;";
                $k .= "<tr>\n<td class=\"f10b\">".$ke.html($row["fcat_name"]).":&nbsp;</td>\n";
                $k .= "<td><select name=\"file_keywords[".$row["fcat_id"]."]\" class=\"custom-select form-control\">\n";
                $k .= "<option value=\"".(($row["fcat_needed"])?"0_".$row["fcat_needed"]."\">".$BL['be_ftptakeover_needed']:'0">'.$BL['be_ftptakeover_optional'])."</option>\n";

                $ksql = "SELECT * FROM ".DB_PREPEND."cmsgo_filekey WHERE fkey_deleted=0 AND fkey_cid=".$row["fcat_id"]." ORDER BY fkey_name";
                $kresult = _dbQuery($ksql);
                if(isset($kresult[0]['fkey_id'])) {
                    foreach($kresult as $krow) {
                        $k .= "<option value=\"".$krow["fkey_id"]."\">".html($krow["fkey_name"])."</option>";
                    }
                }

                $k .= "</select></td>\n</tr>\n";
                $k .= "<tr>\n<td colspan=\"2\"><img src=\"img/leer.gif\" width=\"1\" height=\"2\"></td>\n</tr>\n";

            }
        }
    }
    //Ende vordefinierte Keywörter

    ?>
    <tr bgcolor="#F5F8F9">
        <td align="right" valign="top" class="v09 tdtop1"><?php echo $BL['be_ftptakeover_keywords'] ?>:&nbsp;</td>
        <td><table border="0" cellpadding="0" cellspacing="0" summary="">
        <?php if($k) echo $k; ?>
        <tr>
            <td class="f10b"><?php echo $BL['be_ftptakeover_additional'] ?>:&nbsp;</td>
            <td><input name="file_shortinfo" type="text" class="form-control" id="file_shortinfo" value="" maxlength="250" /></td>
        </tr>
        </table></td>
    </tr>

    <tr bgcolor="#F5F8F9"><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="3" /></td></tr>

    <tr bgcolor="#F5F8F9">
        <td align="right" class="v09">&nbsp;<?php echo $BL['be_tags'] ?>:&nbsp;</td>
        <td><input type="text" id="file_tags_autosuggest" /><input name="file_tags" type="hidden" id="file_tags" value="" /></td>
    </tr>


    <tr bgcolor="#F5F8F9"><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="6" /></td></tr>
    <tr><td colspan="2"><img src="img/lines/line-bluelight.gif" alt="" width="538" height="1" /></td></tr>
    <tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="6" /></td></tr>

            <tr>
              <td align="right" class="v09"><?php echo $BL['be_ftptakeover_status'] ?>:&nbsp;</td>
        <td>
            <table border="0" cellpadding="1" cellspacing="0" summary="">
                  <tr>
                    <td><input name="file_aktiv" type="checkbox" id="file_aktiv" value="1"<?php is_checked($cmsgo['set_file_active'], 1) ?> /></td>
                    <td class="v10"><strong><label for="file_aktiv"><?php echo $BL['be_ftptakeover_active'] ?></label></strong>&nbsp;&nbsp;</td>
                    <td><input name="file_public" type="checkbox" id="file_public" value="1"<?php is_checked($cmsgo['set_file_active'], 1) ?> /></td>
                    <td class="v10"><strong><label for="file_public"><?php echo $BL['be_ftptakeover_public'] ?></label></strong>&nbsp;&nbsp;</td>
                    <td><input name="file_replace" type="checkbox" id="file_replace" value="1" /></td>
                    <td class="v10"><strong><label for="file_replace"><?php echo $BL['be_file_replace'] ?></label></strong>&nbsp;</td>
                  </tr>
                </table>
              </td>
            </tr>
            <tr>
              <td width="67" valign="top"><input name="file_aktion" type="hidden" id="file_aktion" value="1" /></td>
              <td><input name="Submit" type="submit" class="button" value="<?php echo $BL['be_ftptakeover_button'] ?>" /></td>
          </tr>
</table>
<?php }

initJsAutocompleter();
$GLOBALS['BE']['HEADER']['fileuploader.css']    = ' <link href="include/inc_js/uploader/fine-uploader.min.css" rel="stylesheet" type="text/css" />';
$GLOBALS['BE']['HEADER']['fileuploader.js']     = getJavaScriptSourceLink('include/inc_js/uploader/fine-uploader.min.js');
//$GLOBALS['BE']['HEADER']['fine-uploader.map.js']     = getJavaScriptSourceLink('include/inc_js/uploader/fine-uploader.js.map');


$fileuploaderAllowedExtensions = '';
if(is_string($cmsgo['allowed_upload_ext'])) {
    $fileuploaderAllowedExtensions = $cmsgo['allowed_upload_ext'];
    if(strpos($fileuploaderAllowedExtensions, ',') !== false) {
        $fileuploaderAllowedExtensions = "'" . str_replace(',', "','", $fileuploaderAllowedExtensions) . "'";
    }
} elseif(count($cmsgo['allowed_upload_ext'])) {
    $fileuploaderAllowedExtensions = "'" . implode("','", $cmsgo['allowed_upload_ext']) . "'";
}

?>
</form>
<script>
$(function() {

    var manualUploader = new qq.FineUploader({
        element: document.getElementById('fine-uploader-manual-trigger'),
        template: 'qq-template-manual-trigger',
        request: {
            endpoint: 'include/inc_ext/uploader/endpoint.php'
        },
        thumbnails: {
            placeholders: {
                waitingPath: '<?php echo CMSGO_URL ?>include/inc_js/uploader/placeholders/waiting-generic.png',
                notAvailablePath: '<?php echo CMSGO_URL ?>include/inc_js/uploader/placeholders/not_available-generic.png'
            }
        },
        validation: {
            allowedExtensions: [<?php echo $fileuploaderAllowedExtensions ?>],
            sizeLimit: <?php

                if(ini_get('post_max_size')) {
                    $post_max_size = return_bytes(ini_get('post_max_size'));
                    if($post_max_size < $cmsgo['file_maxsize']) {
                        $cmsgo['file_maxsize'] = $post_max_size;
                    }
                } else {
                    $post_max_size = $cmsgo['file_maxsize'];
                }
                if(ini_get('upload_max_filesize')) {
                    $upload_max_filesize = return_bytes(ini_get('upload_max_filesize'));
                    if($upload_max_filesize < $cmsgo['file_maxsize']) {
                        $cmsgo['file_maxsize'] = $upload_max_filesize;
                    }
                } else {
                    $upload_max_filesize = $cmsgo['file_maxsize'];
                }

                echo min($post_max_size, $upload_max_filesize, $cmsgo['file_maxsize']);

            ?>
        },
        failedUploadTextDisplay: {
          mode: 'custom',
          maxChars: 40,
          responseProperty: 'error',
          enableTooltip: true
        },
        callbacks: {
          onComplete: function(id, fileName, responseJSON) {
            if (responseJSON.success) {
              document.location.reload(true);
            }
          }
        },
        showMessage: function(message) {
          // Using Twitter Bootstrap's classes and jQuery selector and method
          $('#restricted-fine-uploader').append('<div class="alert alert-error">' + message + '</div>');
        },
        autoUpload: false,
        debug: true
    });

    qq(document.getElementById("trigger-upload")).attach("click", function() {
        manualUploader.uploadStoredFiles();
    });


    var uploadButton = $('#upload-file-select');

    if(uploadButton) {

        var uploadFileCount = 0;

        // File Uploading
        /*var uploader = new qq.FileUploader({
            element: uploadButton[0],
            action: '<?php echo CMSGO_URL ?>include/inc_act/act_upload.php?<?php echo get_token_get_string('csrftoken'); ?>',
            multiple: true,
            autoUpload: true,
            allowedExtensions: [<?php echo $fileuploaderAllowedExtensions ?>],
            uploadButtonText: '<?php echo $BL['be_fileuploader_uploadButtonText'] ?>',
            cancelButtonText: '<?php echo $BL['be_newsletter_button_cancel'] ?>',
            failUploadText: '<?php echo $BL['be_error_while_save'] ?>',
            dragText: '<?php echo $BL['be_fileuploader_dragText'] ?>',
            sizeLimit: <?php

                if(ini_get('post_max_size')) {
                    $post_max_size = return_bytes(ini_get('post_max_size'));
                    if($post_max_size < $cmsgo['file_maxsize']) {
                        $cmsgo['file_maxsize'] = $post_max_size;
                    }
                } else {
                    $post_max_size = $cmsgo['file_maxsize'];
                }
                if(ini_get('upload_max_filesize')) {
                    $upload_max_filesize = return_bytes(ini_get('upload_max_filesize'));
                    if($upload_max_filesize < $cmsgo['file_maxsize']) {
                        $cmsgo['file_maxsize'] = $upload_max_filesize;
                    }
                } else {
                    $upload_max_filesize = $cmsgo['file_maxsize'];
                }

                echo min($post_max_size, $upload_max_filesize, $cmsgo['file_maxsize']);

            ?>,
            messages: {
                typeError: "<?php echo makeCharsetConversion($BL['be_fileuploader_typeError'], 'utf-8', CMSGO_CHARSET) ?>",
                sizeError: "<?php echo makeCharsetConversion($BL['be_fileuploader_sizeError'], 'utf-8', CMSGO_CHARSET) ?>",
                minSizeError: "<?php echo makeCharsetConversion($BL['be_fileuploader_minSizeError'], 'utf-8', CMSGO_CHARSET) ?>",
                emptyError: "<?php echo makeCharsetConversion($BL['be_fileuploader_emptyError'], 'utf-8', CMSGO_CHARSET) ?>",
                noFilesError: "<?php echo makeCharsetConversion($BL['be_fileuploader_noFilesError'], 'utf-8', CMSGO_CHARSET) ?>",
                onLeave: "<?php echo makeCharsetConversion($BL['be_fileuploader_onLeave'], 'utf-8', CMSGO_CHARSET) ?>"
            },
            disableDefaultDropzone: false,
            onSubmit: function(id, fileName) {
                uploadFileCount++;
            },
            onCancel: function(id, fileName) {
                uploadFileCount--;
            },
            onComplete: function(id, fileName, responseJSON) {
                if(responseJSON.success) {
                    uploadFileCount--;
                    if(uploadFileCount == 0) {
                        document.location.reload(true);
                    }
                }
            }
        });*/

    }

<?php if($fx): ?>

    var ftpTakeOverForm = $('#ftptakeover'),
        deleteFiles = $('#delete-selected-files'),
        fileMarker = $('input.ftp_mark'),
        checkToggle = $('#toggle');

    checkToggle.on('change', function() {

        var toggle_var  = $(this).is(':checked');
        var isChecked   = false;

        fileMarker.each(function() {
            var $_this = $(this);
            if($_this.is(':checked')) {
                $_this.prop('checked', false);
            } else {
                $_this.prop('checked', true);
                isChecked = true;
            }
        });

        if(isChecked) {
            deleteFiles.show();
        } else {
            deleteFiles.hide();
            checkToggle.prop('checked', false);
        }

    });

    fileMarker.on('change', function() {

        var isChecked = false;

        fileMarker.each(function() {
            if($(this).is(':checked')) {
                isChecked = true;
            }
        });

        if(isChecked) {
            deleteFiles.show();
        } else {
            deleteFiles.hide();
            checkToggle.prop('checked', false);
        }

    });

    deleteFiles.on('click', function(evt) {
        evt.preventDefault();
        if(confirm('<?php echo str_replace("'", "\\'", html_entity_decode($BL['be_delete_selected_files_confirm'], ENT_QUOTES, CMSGO_CHARSET)) ?>')) {
            ftpTakeOverForm.attr('action', 'cmsgo.php'+'?<?php echo get_token_get_string('csrftoken'); ?>&do=files&p=8').submit();
        }
    });

    $("#file_tags_autosuggest").autoSuggest('<?php echo CMSGO_URL ?>include/inc_act/ajax_connector.php', {
        selectedItemProp: "cat_name",
        selectedValuesProp: 'cat_name',
        searchObjProps: "cat_name",
        queryParam: 'value',
        extraParams: '&method=json&action=category',
        startText: '',
        preFill: $("#file_tags").val(),
        neverSubmit: true,
        asHtmlID: 'keyword-autosuggest'
    });

    ftpTakeOverForm.submit(function(evt) {

        if($('input.ftp_mark:checked').length) {
            $("#file_tags").val($('#as-values-keyword-autosuggest').val());
        } else {
            evt.preventDefault();
        }

    });

<?php
        if(count($cmsgo['allowed_lang']) > 1): ?>

    var tab_content = $('tr.tab-content');
    var tabs        = $('td.incell-tabs a');

    tabs.click(function(event) {
        event.preventDefault();

        var $_this = $(this);

        if($_this.hasClass('active')) {
            return false;
        }

        tab_content.hide();
        tabs.removeClass('active');

        $_this.addClass('active');
        $('tr.finfo'+$_this.attr('rel')).show();

    });

<?php
        endif;
    endif;
?>

});

</script>
