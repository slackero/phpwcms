<?php
/**
 * phpwcms
 *
 * @author Oliver Georgi <og@phpwcms.org>
 * @copyright Copyright (c) 2002-2026, Oliver Georgi
 * @license http://opensource.org/licenses/GPL-2.0 GNU GPL-2
 **/

// ----------------------------------------------------------------
// obligate check for phpwcms constants
if (!defined('PHPWCMS_ROOT')) {
    die("You Cannot Access This Script Directly, Have a Nice Day.");
}
// ----------------------------------------------------------------

//file list
if(empty($content["file_descr"])) $content["file_descr"] = '';
$content['file']['direct_download'] = empty($content['file']['direct_download']) ? 0 : 1;

?>
<div class="form-group align-items-center row g-2">
  <label for="cfile_template" class="col-sm-2 col-form-label text-end"><?php echo $BL['be_admin_struct_template']; ?></label>
  <div class="col-sm-4">
    <select name="cfile_template" id="cfile_template" class="form-select form-select-sm">

<?php

echo '<option value="">'.$BL['be_admin_tmpl_default'].'</option>'.LF;

// templates for recipes
$tmpllist = get_tmpl_files(PHPWCMS_TEMPLATE.'inc_cntpart/filelist');
if(is_array($tmpllist) && count($tmpllist)) {
    foreach($tmpllist as $val) {
        if(isset($content['file_template']) && $val == $content['file_template']) {
            $selected_val = ' selected="selected"';
        } else {
            $selected_val = '';
        }
        $val = htmlspecialchars($val);
        echo '  <option value="' . $val . '"' . $selected_val . '>' . $val . '</option>' . LF;
    }
}

if(is_file(PHPWCMS_ROOT.'/'.PHPWCMS_FILES.'.htaccess') && ($content['file']['direct_download_deny'] = file_get_contents(PHPWCMS_ROOT.'/'.PHPWCMS_FILES.'.htaccess'))) {
    $content['file']['direct_download_deny'] = strtolower($content['file']['direct_download_deny']);
    if(strpos($content['file']['direct_download_deny'], 'deny') !== false) {
        $content['file']['direct_download_deny'] = true;
    } else {
        $content['file']['direct_download_deny'] = false;
    }
} else {
    $content['file']['direct_download_deny'] = false;
}
?>
      </select>
   </div>
</div>

<div class="form-group align-items-center row g-2">
	<label for="cfile_direct" class="col-sm-2 col-form-label text-end"><?php echo $BL['be_cnt_download'] ?></label>
	<div class="col-sm-auto">
		<div class="form-check">
			<input class="form-check-input" name="cfile_direct" id="cfile_direct" type="checkbox" value="1" <?php
						is_checked(1, $content['file']['direct_download']);
						if($content['file']['direct_download_deny'] && !$content['file']['direct_download']) {
								echo ' disabled="disabled"';
						}
				?> />
			<label class="form-check-label" for="cfile_direct"><?php echo $BL['be_cnt_download_direct'] ?></label>
		</div>
	</div>
	<div class="col align-self-center small text-muted">
		<?php
			if($content['file']['direct_download_deny']) {
					printf($BL['be_filedownload_direct_blocked'], PHPWCMS_ROOT.'/'.PHPWCMS_FILES.'.htaccess');
			}
		?>
	</div>
</div>

<div class="form-group row g-2">
  <label for="cfile_list" class="col-sm-2 col-form-label text-end"><?php echo $BL['be_cnt_files'] ?></label>
    <div class="col">
        <select name="cfile_list[]" size="8" multiple class="form-select form-select-sm" id="cfile_list">
        <?php

        if(isset($content["file_list"]) && is_array($content["file_list"]) && count($content["file_list"])) {
            $fx  = 0;
            $fxa = "";
            $fxb = array();
            foreach($content["file_list"] as $key => $value) {
                if($fx) $fxa .= " OR ";
                $fxa .= "f_id=".intval($value);
                $fxb[$key]["fid"] = intval($value);
                $fx++;
            }
            if($fx) {
                $file_sql = "SELECT f_id, f_name FROM ".DB_PREPEND."phpwcms_file WHERE f_public=1 AND f_aktiv=1 AND f_kid=1 AND f_trash=0 AND (".$fxa.")";
                $file_result = _dbQuery($file_sql);
                if(isset($file_result[0]['f_id'])) {
                    foreach($file_result as $file_row) {
                        foreach($fxb as $key => $value) {
                            if($fxb[$key]["fid"] == $file_row['f_id']) {
                                $fxb[$key]["fname"] = html($file_row['f_name']);
                            }
                        }
                    }
                }
                foreach($fxb as $key => $value) {
                    if(!empty($fxb[$key]["fname"])) {
                        echo "<option value=\"".$fxb[$key]["fid"]."\">".$fxb[$key]["fname"]."</option>\n";
                    }
                }
                unset($fxb, $content["file_list"]);
            }
        }

        ?>
        </select>
    </div>
      <div class="col-sm-auto">
        <button type="button" class="modalButton btn btn-sm btn-blue mb-1" data-bs-toggle="modal" data-bs-target="#browserModal" data-src="filebrowser.php?opt=4&amp;target=nolist" ><i class="fa fa-folder-open fa-fw" aria-hidden="true"></i></button><br />
        <button type="button" class="btn btn-sm btn-secondary mb-1" data-bs-toggle="tooltip" title="<?php echo $BL['be_cnt_sortup'] ?>" onclick="moveOptionUp(document.articlecontent.cfile_list)"><i class="fa fa-angle-up fa-fw" aria-hidden="true"></i></button><br />
        <button type="button" class="btn btn-sm btn-secondary mb-1" data-bs-toggle="tooltip" title="<?php echo $BL['be_cnt_sortdown'] ?>" onclick="moveOptionDown(document.articlecontent.cfile_list)"><i class="fa fa-angle-down fa-fw" aria-hidden="true"></i></button><br />
        <button type="button" class="btn btn-sm btn-danger mb-1" onclick="removeSelectedOptions(document.articlecontent.cfile_list)" data-bs-toggle="tooltip" title="<?php echo $BL['be_cnt_delfile'] ?>"><i class="far fa-trash-alt fa-fw" aria-hidden="true"></i></button>
      </div>
  </div>

<div class="form-group row g-2 mb-5">
  <label for="cfile_descr" class="col-sm-2 col-form-label text-end"><?php echo $BL['be_cnt_description'] ?></label>
  <div class="col">
    <textarea name="cfile_descr" cols="40" rows="5" class="form-control form-control-sm" id="cfile_descr"><?php

    if(!empty($content["file_descr"]) && (substr($content["file_descr"], 0, 1) === "\r" || substr($content["file_descr"], 0, 1) === "\n")) {
        echo ' ';
    }
    echo html($content["file_descr"]);

  ?></textarea>
    <div class="caption pt-2">
        <?php echo $BL['be_cnt_description']; ?>
        |
        <?php echo $BL['be_fprivedit_filename']; ?>
        |
        <?php echo $BL['be_caption_file_title']; ?>
        |
        <?php echo $BL['be_cnt_target']; ?>
        |
        <?php echo $BL['be_caption_file_imagesize']; ?>
        |
        <?php echo $BL['be_copyright']; ?>&nbsp;&crarr;&nbsp;&hellip;
    </div>
  </div>
</div>

<?php

$wysiwyg_editor = array(
    'value'     => isset($content["html"]) ? $content["html"] : '',
    'field'     => 'chtml',
    'height'    => '250px',
    'width'     => '100%',
    'rows'      => '15',
    'editor'    => $_SESSION["WYSIWYG_EDITOR"],
    'lang'      => 'en'
);

include PHPWCMS_ROOT.'/include/inc_lib/wysiwyg.editor.inc.php';
