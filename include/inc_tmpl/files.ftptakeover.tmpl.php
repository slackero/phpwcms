<?php
/**
 * cmsGO!
 *
 * @author Pixels & Points GmbH <info@pixels-points.ch>
 * @copyright Copyright (c) 2002-2026, Pixels & Points GmbH
 * @license https://www.pixels-points.ch/cmsgo-license.html Pixels & Points cmsGO! license
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
// Include uploader scripts http://hayageek.com/docs/jquery-upload-file.php
$GLOBALS['BE']['HEADER']['uploadfile.css'] = '<link href="include/inc_css/uploadfile.css" rel="stylesheet">';
$GLOBALS['BE']['HEADER']['jquery.form.min.js'] = getJavaScriptSourceLink('include/inc_js/jquery.form.min.js');
$GLOBALS['BE']['HEADER']['jquery.uploadfile.min.js'] = getJavaScriptSourceLink('include/inc_js/jquery.uploadfile.min.js');
?>

<h1 class="text-center text-sm-left"><?php echo $BL['be_nav_files'] ?></h1>
<div class="card mb-4">
  <div class="card-header"><h2><?php echo $BL['be_file_multiple_upload'] ?></h2></div>
  <div class="card-body">
    <div id="fileuploader">Upload</div>
  </div>
</div>

<div class="card">
  <div class="card-header"><h2><?php echo $BL['be_files_select_available'] ?></h2></div>
  <div class="card-body">

    <form action="include/inc_act/act_ftptakeover.php" method="post" name="ftptakeover" id="ftptakeover">
      <div id="filelist" class="table-responsive">
        <table class="table table-sm">
            <tr bgcolor="#f3f3f3">
              <th><?php echo $BL['be_ftptakeover_mark'] ?></th>
              <th><?php echo $BL['be_ftptakeover_available'] ?></th>
              <th><?php echo $BL['be_ftptakeover_size'] ?></th>
            </tr>

  <?php
        //Browse FTP Open Directory
        $multiple_upload_files = returnFileListAsArray(CMSGO_ROOT.$cmsgo["ftp_path"], $extfilter = '');

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

                    $fxb = ($fx % 2) ? ' bgColor="#F9FAFB"' : '';
                $fxsg += $file['filesize'];
                $fxe = extimg($file['ext']);
                     // there is a big problem with special chars on Mac OS X and seems Windows too
                    $filename = CMSGO_CHARSET !== 'utf-8' && cmsgo_seems_utf8($file['filename']) ? str_replace('?', '', mb_convert_encoding($file['filename'], CMSGO_CHARSET)) : $file['filename'];
                    $filename = html($filename);
?>
          <tr<?php echo $fxb ?>>
            <td align="center" width="30"><input name="ftp_mark[<?php echo $fx ?>]" type="checkbox" id="ftp_mark_<?php echo $fx ?>" value="1" class="ftp_mark" /></td>
            <td><?php echo $filename ?></td>
            <td>
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
            <td colspan="2" class="dir">&nbsp;<?php echo $BL['be_ftptakeover_nofile'] ?></td>
            <td></td>
        </tr>
<?php
        } else {
?>

          <tr bgcolor="#e3e3e3" height="40">
            <td width="30" class="text-center"><input name="toggle" type="checkbox" id="toggle" value="1" title="<?php echo $BL['be_ftptakeover_all'] ?>" /></td>
            <td><button id="delete-selected-files" style="display:none;" class="btn btn-sm btn-blue my-1"><?php echo $BL['be_delete_selected_files'] ?></button></td>
            <td><?php echo fsizelong($fxsg) ?>&nbsp;</td>
        </tr>
<?php
        }
?>
        </table>
      </div>

    <hr />

    <div  id="showform" style="display: <?php echo ($fx) ? 'block' : 'none'; ?>;">

        <div class="form-group form-row align-items-center">
            <label for="be_ftptakeover_directory" class="col-sm-2 col-form-label text-right"><?php echo $BL['be_ftptakeover_directory'] ?></label>
            <div class="col">
                <select name="file_dir" class="custom-select form-control form-control-sm" id="file_dir">
                    <option value="0"><?php echo $BL['be_ftptakeover_rootdir'] ?></option>
                    <?php dir_menu(0, 0, "-", $_SESSION["wcs_user_id"], "-"); ?>
                </select>
            </div>
        </div>

        <div class="form-group form-row align-items-center">
            <label for="file_dir_new" class="col-sm-2 col-form-label text-right"><?php echo $BL['be_ftptakeover_new_folder']; ?></label>
            <div class="col">
                <input type="text" name="file_dir_new" id="file_dir_new" class="form-control form-control-sm" placeholder="<?php echo $BL['be_ftptakeover_new_folder_placeholder']; ?>">
            </div>
        </div>

        <div class="form-group form-row align-items-center">
            <label class="col-sm-2 col-form-label text-right"><?php echo $BL['be_iptc_data'] ?></label>
            <div class="col form-check-inline">
                <input class="form-check-input" type="checkbox" name="file_iptc_as_caption" id="file_iptc_as_caption" value="1"<?php if(!empty($cmsgo['iptc_as_caption'])): ?> checked="checked"<?php endif; ?> >
                <label class="form-check-label" for="file_iptc_as_caption"><?php echo $BL['be_iptc_as_caption'] ?></label>
            </div>
        </div>

        <div class="form-group form-row align-items-center">
            <label for="be_admin_tmpl_js" class="col-sm-2 col-form-label text-right">JS onload</label>
            <div class="col">
                <input class="form-control form-control-sm" name="template_jsonload" id="template_jsonload" value="" type="text">
            </div>
        </div>

        <hr />

<?php   if(count($cmsgo['allowed_lang']) > 1): ?>

    <ul class="nav nav-tabs" role="tablist">
      <li class="nav-item">
        <a class="nav-link active" data-toggle="tab" href="#<?php echo $cmsgo['default_lang'] ?>"  title="<?php echo get_language_name($cmsgo['default_lang']) . ' ('.$BL['be_admin_tmpl_default'].')' ?>" role="tab">
                <span class="flag-icon flag-icon-<?php echo $cmsgo['default_lang'] ?>"></span> <?php echo $BL['be_admin_tmpl_default'] ?>
        </a>
      </li>
        <?php foreach($cmsgo['allowed_lang'] as $lang):
            $lang = strtolower($lang);
            if($lang == $cmsgo['default_lang']) {
                continue;
            }
        ?>
      <li class="nav-item">
        <a href="#<?php echo $lang ?>" data-toggle="tab" title="<?php echo get_language_name($lang) ?>" class="nav-link" role="tab">
            <span class="flag-icon flag-icon-<?php echo $lang ?>"></span> <?php echo strtoupper($lang) ?>
        </a>
      </li>
        <?php   endforeach; ?>
    </ul>

    <div class="tab-content mt-4">
<?php   endif; ?>

    <div class="tab-pane active" id="<?php echo $cmsgo['default_lang'] ?>" role="tabpanel">
      <div class="form-group form-row align-items-center">
        <label for="file_title" class="col-sm-2 col-form-label text-right"><?php echo $BL['be_attr_title'] ?></label>
        <div class="col"><input name="file_title" type="text" id="file_title" class="form-control form-control-sm" maxlength="1000" value="" /></div>
      </div>

      <div class="form-group form-row">
          <label for="file_longinfo" class="col-sm-2 col-form-label text-right"><?php echo $BL['be_cnt_description'] ?></label>
          <div class="col"><textarea name="file_longinfo" cols="40" rows="4" class="form-control form-control-sm autosize" id="file_longinfo"></textarea></div>
      </div>
      <div class="form-group form-row align-items-center">
        <label for="file_copyright" class="col-sm-2 col-form-label text-right"><?php echo $BL['be_copyright'] ?></label>
        <div class="col"><input name="file_copyright" type="text" id="file_copyright" class="form-control form-control-sm" maxlength="1000" value="" /></div>
      </div>
      <div class="form-group form-row align-items-center">
          <label for="file_alt" class="col-sm-2 col-form-label text-right"><?php echo $BL['be_attr_alt'] ?></label>
          <div class="col"><input name="file_alt" type="text" id="file_alt" class="form-control form-control-sm" maxlength="1000" value="" /></div>
      </div>
    </div>

<?php
   if(count($cmsgo['allowed_lang']) > 1):
      foreach($cmsgo['allowed_lang'] as $lang):
         $lang = strtolower($lang);

          if($lang == $cmsgo['default_lang']) {
              continue;
          }
?>
    <div class="tab-pane" id="<?php echo $lang ?>" role="tabpanel">
      <div class="form-group form-row align-items-center">
          <label for="file_title" class="col-sm-2 col-form-label text-right"><?php echo $BL['be_attr_title'] ?></label>
          <div class="col"><input name="file_title_<?php echo $lang ?>" type="text" id="file_title_<?php echo $lang ?>"  class="form-control form-control-sm" maxlength="1000" value="" /></div>
      </div>
      <div class="form-group form-row">
          <label for="file_title" class="col-sm-2 col-form-label text-right"><?php echo $BL['be_cnt_description'] ?></label>
          <div class="col"><textarea name="file_longinfo_<?php echo $lang ?>" rows="4" class="form-control form-control-sm autosize" id="file_longinfo_<?php echo $lang ?>"></textarea></div>
      </div>
      <div class="form-group form-row align-items-center">
          <label for="file_title" class="col-sm-2 col-form-label text-right"><?php echo $BL['be_copyright'] ?></label>
          <div class="col"><input name="file_copyright_<?php echo $lang ?>" type="text" id="file_copyright_<?php echo $lang ?>" class="form-control form-control-sm" maxlength="1000" value="" /></div>
      </div>
      <div class="form-group form-row align-items-center">
          <label for="file_title" class="col-sm-2 col-form-label text-right"><?php echo $BL['be_attr_alt'] ?></label>
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
    $sql = "SELECT * FROM ".DB_PREPEND."cmsgo_filecat WHERE fcat_deleted=0 ORDER BY fcat_sort, fcat_name";
    $result = _dbQuery($sql);
    $k = '';

    if(isset($result[0]['fcat_id'])) {
        foreach($result as $row) {
            if(get_filecat_childcount($row["fcat_id"])) {

                $ke = empty($file_error["keywords"][$row["fcat_id"]])? '' : "<img src=\"img/symbole/error.gif\" width=\"8\" height=\"9\">&nbsp;";
                $k .= "<div class=\"form-group form-row align-items-center\">\n";
                $k .= "<label for=\"be_ftptakeover_additional\" class=\"col-sm-2 col-form-label text-right\">".$ke.html($row["fcat_name"]).":&nbsp;</label>\n";
                $k .= "<div class=\"col-sm-5\"><select name=\"file_keywords[".$row["fcat_id"]."]\" class=\"custom-select form-control form-control-sm\">\n";
                $k .= "<option value=\"".(($row["fcat_needed"])?"0_".$row["fcat_needed"]."\">".$BL['be_ftptakeover_needed']:'0">'.$BL['be_ftptakeover_optional'])."</option>\n";

                $ksql = "SELECT * FROM ".DB_PREPEND."cmsgo_filekey WHERE fkey_deleted=0 AND fkey_cid=".$row["fcat_id"]." ORDER BY fkey_name";
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

    <div class="form-group form-row align-items-center">
      <label for="be_ftptakeover_additional" class="col-sm-2 col-form-label text-right"><?php echo $BL['be_ftptakeover_additional'] ?></label>
      <div class="col">
     	<input name="file_shortinfo" type="text" class="form-control form-control-sm" id="file_shortinfo" value="" maxlength="250" />
      </div>
    </div>

    <div class="form-group form-row align-items-center">
      <label for="be_tags" class="col-sm-2 col-form-label text-right"><?php echo $BL['be_tags'] ?> <i class="fas fa-info-circle text-blue" data-toggle="tooltip" title="<?php echo $BL['be_input_text_tab'] ?>"></i></label>
      <div class="col">
     	<input type="text" id="file_tags_autosuggest" class="form-control form-control-sm" />
     	<input name="file_tags" type="hidden" id="file_tags" value="" />
      </div>
    </div>

    <div class="form-group form-row align-items-center">
    	<label for="be_ftptakeover_status" class="col-sm-2 col-form-label text-right"><?php echo $BL['be_ftptakeover_status'] ?></label>
    	<div class="col form-check-inline">
    		<div class="form-check form-check-inline">
					<input class="form-check-input" name="file_aktiv" type="checkbox" id="file_aktiv" value="1"<?php is_checked($cmsgo['set_file_active'], 1) ?> />
          <label class="form-check-label" for="file_aktiv">
					<?php echo $BL['be_ftptakeover_active'] ?>
				</label>
			</div>
    		<div class="form-check form-check-inline">
					<input class="form-check-input" name="file_public" type="checkbox" id="file_public" value="1"<?php is_checked($cmsgo['set_file_active'], 1) ?> />
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

    <div class="form-group mt-3 text-center text-sm-right">
		<input name="file_aktion" type="hidden" id="file_aktion" value="1" />
        <input name="Submit" type="submit" class="btn btn-sm btn-blue" value="<?php echo $BL['be_ftptakeover_button'] ?>" />
	</div>

</div>
<?php
initJsAutocompleter();
$fileuploaderAllowedExtensions = '';
if(is_string($cmsgo['allowed_upload_ext'])) {
    $fileuploaderAllowedExtensions = strtolower($cmsgo['allowed_upload_ext']);
    if(strpos($fileuploaderAllowedExtensions, ',') !== false) {
        $fileuploaderAllowedExtensions = "'" . str_replace(',', "','", $fileuploaderAllowedExtensions) . "'";
    }
} elseif(count($cmsgo['allowed_upload_ext'])) {
    $fileuploaderAllowedExtensions = "'" . implode("','", $cmsgo['allowed_upload_ext']) . "'";
}
?>
</form>

</div>
</div>

<script>

$(function () {
    $("#fileuploader").uploadFile({
        url: "include/inc_act/act_multiupload.php?<?php echo get_token_get_string(); ?>",
        fileName: "myfile",
        dragDropStr: "<span><b><?php echo $BL["be_fileuploader_uploadButtonText"] ?></b></span>",
        abortStr: "<?php echo $BL["be_newsletter_button_cancel"] ?>",
        onSuccess: function (files, data, xhr, pd) {
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
        if(confirm('<?php echo str_replace("'", "\\'", html_entity_decode($BL['be_delete_selected_files_confirm'], ENT_QUOTES, CMSGO_CHARSET)) ?>')) {
            ftpTakeOverForm.attr('action', 'cmsgo.php'+'?<?php echo get_token_get_string(); ?>&do=files&p=8').submit();
        }
    });

    $("#file_tags_autosuggest").autoSuggest('<?php echo CMSGO_URL ?>include/inc_act/ajax_connector.php', {
        selectedItemProp: "cat_name",
        selectedValuesProp: 'cat_name',
        searchObjProps: "cat_name",
        queryParam: 'value',
        extraParams: '&method=json&action=category&<?php echo get_token_get_string(); ?>',
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
?>

}

</script>
