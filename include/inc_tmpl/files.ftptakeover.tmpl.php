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


// If there are files marked to be deleted
$deleteFiles = array();

if(isset($_POST['ftp_mark']) && is_array($_POST['ftp_mark']) && count($_POST['ftp_mark'])) {
    foreach($_POST['ftp_mark'] as $key => $value) {
        $deleteFiles[$_POST['ftp_file'][$key]] = $_POST['ftp_filename'][$key];
    }
}
// Include Dropzone uploader scripts https://www.dropzone.dev/
$GLOBALS['BE']['HEADER']['dropzone.css'] = '<link href="include/inc_css/dropzone.min.css" rel="stylesheet">';
$GLOBALS['BE']['HEADER']['dropzone.js'] = getJavaScriptSourceLink('include/inc_js/dropzone.min.js');
?>

<h1 class="text-center text-sm-start"><?php echo $BL['be_nav_files'] ?></h1>
<div class="card mb-4">
  <div class="card-header"><h2><?php echo $BL['be_file_multiple_upload'] ?></h2></div>
  <div class="card-body">
    <form action="include/inc_act/act_multiupload.php?<?php echo get_token_get_string(); ?>" class="dropzone" id="file-dropzone"></form>
    <div id="dropzone-errors" class="mt-2"></div>
  </div>
</div>

<div class="card">
  <div class="card-header"><h2><?php echo $BL['be_files_select_available'] ?></h2></div>
  <div class="card-body">

    <form action="include/inc_act/act_ftptakeover.php" method="post" name="ftptakeover" id="ftptakeover">
      <div id="filelist" class="table-responsive">
        <table class="table table-hover table-sm table-valign-middle mb-0">
          <thead class="thead-light">
            <tr>
              <th width="40" class="text-center"><?php echo $BL['be_ftptakeover_mark'] ?></th>
              <th><?php echo $BL['be_ftptakeover_available'] ?></th>
              <th width="150" class="text-end"><?php echo $BL['be_ftptakeover_size'] ?></th>
            </tr>
          </thead>
          <tbody>
  <?php
        //Browse FTP Open Directory
        $multiple_upload_files = returnFileListAsArray(PHPWCMS_ROOT.$phpwcms["ftp_path"], $extfilter = '');

        $fx = 0;
        $fxsg = 0;

        if (is_array($multiple_upload_files) && count($multiple_upload_files)) {

            ksort($multiple_upload_files);

            foreach ($multiple_upload_files as $file) {

                // test if the file should be deleted
                $file_base64 = base64_encode($file['filename']);

                if(isset($deleteFiles[$file_base64]) && @unlink($file['path'])) {
                    continue;
                }

                $fxsg += $file['filesize'];
                $filename = html(makeCharsetConversion($file['filename'], 'utf-8', PHPWCMS_CHARSET));
?>
          <tr>
            <td class="text-center align-middle"><input name="ftp_mark[<?php echo $fx ?>]" type="checkbox" id="ftp_mark_<?php echo $fx ?>" value="1" class="ftp_mark" /></td>
            <td class="align-middle"><?php echo $filename ?></td>
            <td class="text-end align-middle">
                <?php echo fsizelong($file['filesize']) ?>
                <input class="form-control" name="ftp_file[<?php echo $fx ?>]" type="hidden" value="<?php echo $file_base64 ?>" />
                <input class="form-control" name="ftp_filename[<?php echo $fx ?>]" type="hidden" value="<?php echo $filename ?>" />
            </td>
          </tr>
                <?php

                $fx++;
            }
        }

        if(!$fx) {
?>
          <tr>
            <td colspan="3" class="text-muted py-3 text-center"><?php echo $BL['be_ftptakeover_nofile'] ?></td>
          </tr>
<?php
        }
?>
          </tbody>
<?php if($fx) { ?>
          <tfoot class="bg-light border-top">
            <tr>
              <td class="text-center align-middle"><input name="toggle" type="checkbox" id="toggle" value="1" title="<?php echo $BL['be_ftptakeover_all'] ?>" /></td>
              <td class="align-middle"><button id="delete-selected-files" style="display:none;" class="btn btn-sm btn-danger py-1"><i class="fas fa-trash-alt me-1"></i><?php echo $BL['be_delete_selected_files'] ?></button></td>
              <td class="text-end align-middle fw-bold"><?php echo fsizelong($fxsg) ?></td>
            </tr>
          </tfoot>
<?php } ?>
        </table>
      </div>

    <hr />

    <div  id="showform" style="display: <?php echo ($fx) ? 'block' : 'none'; ?>;">

        <div class="form-group row g-2 align-items-center">
            <label for="be_ftptakeover_directory" class="col-sm-2 col-form-label text-end"><?php echo $BL['be_ftptakeover_directory'] ?></label>
            <div class="col">
                <select name="file_dir" class="form-select form-select-sm" id="file_dir">
                    <option value="0"><?php echo $BL['be_ftptakeover_rootdir'] ?></option>
                    <?php dir_menu(0, 0, "-", $_SESSION["wcs_user_id"], "-"); ?>
                </select>
            </div>
        </div>

        <div class="form-group row g-2 align-items-center">
            <label for="file_dir_new" class="col-sm-2 col-form-label text-end"><?php echo $BL['be_ftptakeover_new_folder']; ?></label>
            <div class="col">
                <input type="text" name="file_dir_new" id="file_dir_new" class="form-control form-control-sm" placeholder="<?php echo $BL['be_ftptakeover_new_folder_placeholder']; ?>">
            </div>
        </div>

        <div class="form-group row g-2 align-items-center">
            <label class="col-sm-2 col-form-label text-end"><?php echo $BL['be_iptc_data'] ?></label>
            <div class="col form-check-inline">
                <input class="form-check-input" type="checkbox" name="file_iptc_as_caption" id="file_iptc_as_caption" value="1"<?php if(!empty($phpwcms['iptc_as_caption'])): ?> checked="checked"<?php endif; ?> >
                <label class="form-check-label" for="file_iptc_as_caption"><?php echo $BL['be_iptc_as_caption'] ?></label>
            </div>
        </div>

        <div class="form-group row g-2 align-items-center">
            <label for="template_jsonload" class="col-sm-2 col-form-label text-end">JS onload</label>
            <div class="col">
                <input class="form-control form-control-sm" name="template_jsonload" id="template_jsonload" value="" type="text">
            </div>
        </div>

        <hr />

<?php   if(count($phpwcms['allowed_lang']) > 1): ?>

    <ul class="nav nav-tabs" role="tablist">
      <li class="nav-item">
        <a class="nav-link active" data-bs-toggle="tab" href="#<?php echo $phpwcms['default_lang'] ?>"  title="<?php echo get_language_name($phpwcms['default_lang']) . ' ('.$BL['be_admin_tmpl_default'].')' ?>" role="tab">
                <span class="flag-icon flag-icon-<?php echo $phpwcms['default_lang'] ?>"></span> <?php echo $BL['be_admin_tmpl_default'] ?>
        </a>
      </li>
        <?php foreach($phpwcms['allowed_lang'] as $lang):
            $lang = strtolower($lang);
            if($lang == $phpwcms['default_lang']) {
                continue;
            }
        ?>
      <li class="nav-item">
        <a href="#<?php echo $lang ?>" data-bs-toggle="tab" title="<?php echo get_language_name($lang) ?>" class="nav-link" role="tab">
            <span class="flag-icon flag-icon-<?php echo $lang ?>"></span> <?php echo strtoupper($lang) ?>
        </a>
      </li>
        <?php   endforeach; ?>
    </ul>

    <div class="tab-content mt-4">
<?php   endif; ?>

    <div class="tab-pane active" id="<?php echo $phpwcms['default_lang'] ?>" role="tabpanel">
      <div class="form-group row g-2 align-items-center">
        <label for="file_title" class="col-sm-2 col-form-label text-end"><?php echo $BL['be_attr_title'] ?></label>
        <div class="col"><input name="file_title" type="text" id="file_title" class="form-control form-control-sm" maxlength="1000" value="" /></div>
      </div>

      <div class="form-group row g-2">
          <label for="file_longinfo" class="col-sm-2 col-form-label text-end"><?php echo $BL['be_cnt_description'] ?></label>
          <div class="col"><textarea name="file_longinfo" cols="40" rows="4" class="form-control form-control-sm autosize" id="file_longinfo"></textarea></div>
      </div>
      <div class="form-group row g-2 align-items-center">
        <label for="file_copyright" class="col-sm-2 col-form-label text-end"><?php echo $BL['be_copyright'] ?></label>
        <div class="col"><input name="file_copyright" type="text" id="file_copyright" class="form-control form-control-sm" maxlength="1000" value="" /></div>
      </div>
      <div class="form-group row g-2 align-items-center">
          <label for="file_alt" class="col-sm-2 col-form-label text-end"><?php echo $BL['be_attr_alt'] ?></label>
          <div class="col"><input name="file_alt" type="text" id="file_alt" class="form-control form-control-sm" maxlength="1000" value="" /></div>
      </div>
    </div>

<?php
   if(count($phpwcms['allowed_lang']) > 1):
      foreach($phpwcms['allowed_lang'] as $lang):
         $lang = strtolower($lang);

          if($lang == $phpwcms['default_lang']) {
              continue;
          }
?>
    <div class="tab-pane" id="<?php echo $lang ?>" role="tabpanel">
      <div class="form-group row g-2 align-items-center">
          <label for="file_title" class="col-sm-2 col-form-label text-end"><?php echo $BL['be_attr_title'] ?></label>
          <div class="col"><input name="file_title_<?php echo $lang ?>" type="text" id="file_title_<?php echo $lang ?>"  class="form-control form-control-sm" maxlength="1000" value="" /></div>
      </div>
      <div class="form-group row g-2">
          <label for="file_title" class="col-sm-2 col-form-label text-end"><?php echo $BL['be_cnt_description'] ?></label>
          <div class="col"><textarea name="file_longinfo_<?php echo $lang ?>" rows="4" class="form-control form-control-sm autosize" id="file_longinfo_<?php echo $lang ?>"></textarea></div>
      </div>
      <div class="form-group row g-2 align-items-center">
          <label for="file_title" class="col-sm-2 col-form-label text-end"><?php echo $BL['be_copyright'] ?></label>
          <div class="col"><input name="file_copyright_<?php echo $lang ?>" type="text" id="file_copyright_<?php echo $lang ?>" class="form-control form-control-sm" maxlength="1000" value="" /></div>
      </div>
      <div class="form-group row g-2 align-items-center">
          <label for="file_title" class="col-sm-2 col-form-label text-end"><?php echo $BL['be_attr_alt'] ?></label>
          <div class="col"><input name="file_alt_<?php echo $lang ?>" type="text" id="file_alt_<?php echo $lang ?>" class="form-control form-control-sm" maxlength="1000" value="" /></div>
      </div>
    </div>

<?php
    endforeach;
    echo '</div>';
  endif;
?>

    <hr />

    <?php

    //Auswahlliste vordefinierte Keywörter
    $sql = "SELECT * FROM ".DB_PREPEND."phpwcms_filecat WHERE fcat_deleted=0 ORDER BY fcat_sort, fcat_name";
    $result = _dbQuery($sql);
    $k = '';

    if(isset($result[0]['fcat_id'])) {
        foreach($result as $row) {
            if(get_filecat_childcount($row["fcat_id"])) {

                $ke = empty($file_error["keywords"][$row["fcat_id"]])? '' : '<i class="fas fa-exclamation-circle text-danger me-1"></i>';
                $k .= "<div class=\"form-group row g-2 align-items-center\">\n";
                $k .= "<label for=\"be_ftptakeover_additional\" class=\"col-sm-2 col-form-label text-end\">".$ke.html($row["fcat_name"]).":&nbsp;</label>\n";
                $k .= "<div class=\"col-sm-5\"><select name=\"file_keywords[".$row["fcat_id"]."]\" class=\"form-select form-control form-control-sm\">\n";
                $k .= "<option value=\"".(($row["fcat_needed"])?"0_".$row["fcat_needed"]."\">".$BL['be_ftptakeover_needed']:'0">'.$BL['be_ftptakeover_optional'])."</option>\n";

                $ksql = "SELECT * FROM ".DB_PREPEND."phpwcms_filekey WHERE fkey_deleted=0 AND fkey_cid=".$row["fcat_id"]." ORDER BY fkey_name";
                $kresult = _dbQuery($ksql);
                if(isset($kresult[0]['fkey_id'])) {
                    foreach($kresult as $krow) {
                        $k .= "<option value=\"".$krow["fkey_id"]."\">".html($krow["fkey_name"])."</option>";
                    }
                }
                $k .= "</select></div>\n";
                $k .= "</div>\n";

            }
        }
    }
    //Ende vordefinierte Keywörter
    ?>
	<legend class="col-form-legend"><?php echo $BL['be_ftptakeover_keywords'] ?></legend>

	<?php if($k) echo $k; ?>

    <div class="form-group row g-2 align-items-center">
      <label for="file_shortinfo" class="col-sm-2 col-form-label text-end"><?php echo $BL['be_ftptakeover_additional'] ?></label>
      <div class="col">
     	<input name="file_shortinfo" type="text" class="form-control form-control-sm" id="file_shortinfo" value="" maxlength="250" />
      </div>
    </div>

    <div class="form-group row g-2 align-items-center">
      <span class="col-sm-2 col-form-label text-end"><?php echo $BL['be_tags'] ?> <i class="fas fa-info-circle text-blue" data-bs-toggle="tooltip" title="<?php echo $BL['be_input_text_tab'] ?>"></i></span>
      <div class="col">
     	<input type="text" id="file_tags_autosuggest" class="form-control form-control-sm" aria-label="<?php echo html_specialchars($BL['be_tags']) ?>" />
     	<input name="file_tags" type="hidden" id="file_tags" value="" />
      </div>
    </div>

    <div class="form-group row g-2 align-items-center">
    	<label class="col-sm-2 col-form-label text-end"><?php echo $BL['be_ftptakeover_status'] ?></label>
    	<div class="col form-check-inline">
    		<div class="form-check form-check-inline">
					<input class="form-check-input" name="file_aktiv" type="checkbox" id="file_aktiv" value="1"<?php is_checked($phpwcms['set_file_active'], 1) ?> />
          <label class="form-check-label" for="file_aktiv">
					<?php echo $BL['be_ftptakeover_active'] ?>
				</label>
			</div>
    		<div class="form-check form-check-inline">
					<input class="form-check-input" name="file_public" type="checkbox" id="file_public" value="1"<?php is_checked($phpwcms['set_file_active'], 1) ?> />
          <label class="form-check-label" for="file_public">
					<?php echo $BL['be_ftptakeover_public'] ?>
				</label>
			</div>
    		<div class="form-check form-check-inline">
					<input class="form-check-input" name="file_replace" type="checkbox" id="file_replace" value="1" />
          <label class="form-check-label" for="file_replace">
					<?php echo $BL['be_file_replace'] ?>
				</label>
			</div>
      </div>
    </div>

    <div class="form-group mt-4 mb-0 text-center text-sm-end">
		<input name="file_aktion" type="hidden" id="file_aktion" value="1" />
        <button name="Submit" type="submit" class="btn btn-sm btn-blue" value="1"><i class="fa fa-cogs me-1"></i> <?php echo $BL['be_ftptakeover_button'] ?></button>
	</div>

</div>
<?php
initJsAutocompleter();
$fileuploaderAllowedExtensions = '';
if(is_string($phpwcms['allowed_upload_ext'])) {
    $fileuploaderAllowedExtensions = strtolower($phpwcms['allowed_upload_ext']);
    if(strpos($fileuploaderAllowedExtensions, ',') !== false) {
        $fileuploaderAllowedExtensions = "'" . str_replace(',', "','", $fileuploaderAllowedExtensions) . "'";
    }
} elseif(count($phpwcms['allowed_upload_ext'])) {
    $fileuploaderAllowedExtensions = "'" . implode("','", $phpwcms['allowed_upload_ext']) . "'";
}
?>
</form>

</div>
</div>

<script>

Dropzone.autoDiscover = false;

$(function () {
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

    if ($("#file-dropzone").data("dropzone")) {
        $("#file-dropzone").data("dropzone").destroy();
    }

    var fileDropzone = new Dropzone("#file-dropzone", {
        paramName: "file",
        maxFilesize: maxMB,
        previewTemplate: bs4PreviewTemplate,
        acceptedFiles: <?php
            if (is_array($phpwcms['allowed_upload_ext']) && count($phpwcms['allowed_upload_ext'])) {
                echo json_encode('.' . implode(',.', $phpwcms['allowed_upload_ext']));
            } elseif (is_string($phpwcms['allowed_upload_ext']) && $phpwcms['allowed_upload_ext'] !== '') {
                echo json_encode('.' . str_replace(',', ',.', $phpwcms['allowed_upload_ext']));
            } else {
                echo "null";
            }
        ?>,
        accept: function(file, done) {
            var existingFiles = [];
            $("#filelist td:nth-child(2)").each(function() {
                existingFiles.push($.trim($(this).text()).toLowerCase());
            });
            if (existingFiles.indexOf(file.name.toLowerCase()) !== -1) {
                var errStr = <?php
                    $err = !empty($BL['be_fprivup_err12']) ? $BL['be_fprivup_err12'] : 'File <strong>%s</strong> already exists in destination.';
                    echo json_encode(html_entity_decode($err, ENT_QUOTES | ENT_HTML5, 'UTF-8')) ?: '""';
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
            this.on("sending", function(file) {
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
                        '<button type="button" class="btn-close ms-auto ps-2 dz-alert-close" data-file-uuid="' + (file.upload ? file.upload.uuid : '') + '" aria-label="Close">' +
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
                setTimeout(function() {
                    self.removeFile(file);
                }, 1000);
                $.ajax({
                    url: 'include/inc_act/act_multiupload-list.php?<?php echo get_token_get_string(); ?>',
                    xhrFields: {
                        withCredentials: true
                    },
                    success: function (data) {
                        $("#filelist").html(data);
                        $("#showform").show();
                        ppInitFunction();
                    }
                });
            });
        }
    });

    ppInitFunction();
});

function ppInitFunction() {

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
        bsConfirmDanger('<?php echo str_replace("'", "\\'", html_entity_decode($BL['be_delete_selected_files_confirm'], ENT_QUOTES, PHPWCMS_CHARSET)); ?>', function() {
            ftpTakeOverForm.attr('action', 'phpwcms.php'+'?<?php echo get_token_get_string(); ?>&do=files&p=8').submit();
        });
    });

    initTomSelectTagAutosuggest('#file_tags_autosuggest', '#file_tags', 'category');

    ftpTakeOverForm.submit(function(evt) {
        if (!$('input.ftp_mark:checked').length) {
            evt.preventDefault();
        }
    });


<?php
  if(count($phpwcms['allowed_lang']) > 1): ?>

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
?>

}

</script>
