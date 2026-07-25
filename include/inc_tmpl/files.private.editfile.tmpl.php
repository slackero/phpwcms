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

// Be more modern here - we start switch to jQuery and overwrite non-used MooTools with jQuery call
initJsAutocompleter();

$file_id = isset($_GET["editfile"]) ? intval($_GET["editfile"]) : 0;
$file_ext = '';
$ja = 0;
$file_thumb_small = '';
$file_image_iptc = array();
$file_image_width = 0;
$file_image_height = 0;
$file_name = '';

//Auswerten des Formulars
if(isset($_POST["file_aktion"]) && intval($_POST["file_aktion"]) === 2) {
	$file_id                = intval($_POST["file_id"]);
	$file_pid               = intval($_POST["file_pid"]);
	$file_aktiv             = empty($_POST["file_aktiv"]) ? 0 : 1;
	$file_public            = empty($_POST["file_public"]) ? 0 : 1;
	$file_name              = clean_slweg($_POST["file_name"]);
	$file_alias             = clean_slweg($_POST["file_alias"]);
	$file_alias_old         = clean_slweg($_POST["file_alias_old"]);
	$file_ext               = clean_slweg($_POST["file_ext"]);
	$file_shortinfo         = clean_slweg($_POST["file_shortinfo"]);
	$file_longinfo          = slweg($_POST["file_longinfo"]);
	$file_copyright         = clean_slweg($_POST["file_copyright"]);
	$file_tags              = trim( clean_slweg($_POST["file_tags"]), ',' );
	$file_granted           = empty($_POST["file_granted"]) ? 0 : 1;
	$file_gallerydownload   = empty($_POST["file_gallerydownload"]) ? 0 : 1;
	$file_sort              = intval($_POST["file_sort"]);
	$file_title             = clean_slweg($_POST["file_title"]);
	$file_alt               = clean_slweg($_POST["file_alt"]);

    // Set file info based on IPTC for all languages
    if(!empty($_POST['file_iptc_as_caption']) && !empty($_POST['file_image_iptc'])) {

        $file_image_iptc = unserialize(base64_decode($_POST['file_image_iptc'], ['allowed_classes' => false]));
        $file_iptc_info = render_iptc_fileinfo($file_image_iptc);

        if($file_title === '') {
            $file_title = $file_iptc_info['title'];
        }
        if($file_longinfo === '') {
            $file_longinfo = $file_iptc_info['longinfo'];
        }
        if($file_copyright === '') {
            $file_copyright = $file_iptc_info['copyright'];
        }
        if($file_alt === '') {
            $file_alt = $file_iptc_info['alt'];
        }
    } else {
        $file_iptc_info = null;
    }

    if(count($phpwcms['allowed_lang']) > 1) {

        $file_vars = array();

        foreach($phpwcms['allowed_lang'] as $lang) {
            $lang = strtolower($lang);

            $file_vars[$lang] = array(
                'longinfo' => '',
                'copyright' => '',
                'title' => '',
                'alt' => ''
            );

            if($phpwcms['default_lang'] === $lang) {
                $file_vars[$lang]['longinfo'] = $file_longinfo;
                $file_vars[$lang]['copyright'] = $file_copyright;
                $file_vars[$lang]['title'] = $file_title;
                $file_vars[$lang]['alt'] = $file_alt;
            }

            if(isset($_POST['file_longinfo_'.$lang])) {
                $file_vars[$lang]['longinfo'] = slweg($_POST['file_longinfo_'.$lang]);
            }
            if(isset($_POST['file_copyright_'.$lang])) {
                $file_vars[$lang]['copyright'] = clean_slweg($_POST['file_copyright_'.$lang]);
            }
            if(isset($_POST['file_title_'.$lang])) {
                $file_vars[$lang]['title'] = clean_slweg($_POST['file_title_'.$lang]);
            }
            if(isset($_POST['file_alt_'.$lang])) {
                $file_vars[$lang]['alt'] = clean_slweg($_POST['file_alt_'.$lang]);
            }

            // Set file info based on IPTC for all languages
            if(!empty($phpwcms['iptc_as_caption_all_lang']) && $file_iptc_info !== null) {
                if($file_vars[$lang]['title'] === '') {
                    $file_vars[$lang]['title'] = $file_iptc_info['title'];
                }
                if($file_vars[$lang]['longinfo'] === '') {
                    $file_vars[$lang]['longinfo'] = $file_iptc_info['longinfo'];
                }
                if($file_vars[$lang]['copyright'] === '') {
                    $file_vars[$lang]['copyright'] = $file_iptc_info['copyright'];
                }
                if($file_vars[$lang]['alt'] === '') {
                    $file_vars[$lang]['alt'] = $file_iptc_info['alt'];
                }
            }
        }
    }

    $file_keys = '';
    if(isset($_POST["file_keywords"]) && is_array($_POST["file_keywords"]) && count($_POST["file_keywords"])) {
        $file_keywords = $_POST["file_keywords"];
        foreach($file_keywords as $key => $value) {
            unset($file_keywords[$key]);
            $key = intval($key);
            if($value != "0_1") {
                $file_keys .= (($file_keys) ? ":" : "").$key."_".intval($value);
                $file_keywords[$key] = intval($value);
            } else {
                $file_error["keywords"][$key] = 1;
            }
        }
    }

    if ($file_alias != '') {
      $file_alias = clean_slweg(strtolower($file_alias), 150);
      $file_alias = uri_sanitize($file_alias);
      if($file_alias != '') {
          $file_alias = trim( preg_replace('/\-\-+/', '-', $file_alias), '-' );
          $file_alias = trim( preg_replace('/__+/', '_', $file_alias), '_' );
      }
      $f_count  = "SELECT COUNT(f_alias) FROM ".DB_PREPEND."phpwcms_file WHERE ";
      $f_count .= "f_alias='".aporeplace($file_alias)."' AND f_id<>".$file_id;
      $f_count = @_dbQuery($f_count, 'COUNT');
      if ($f_count > 0) {
          $file_alias = $file_alias."-".$f_count;
      }
    }
    //if alias has changed lets deleted existing content images
    if ($file_alias != $file_alias_old && $file_alias_old != '') {
      $files = glob(PHPWCMS_ROOT.'/content/images/*-'.$file_alias_old.'.'.$file_ext);
      foreach ($files as $file) {
         unlink($file);
      }
    }

    if(empty($file_name)) {
        $file_error["name"] = 1;
    } elseif(trim(strtolower(FileExtension($file_name))) != trim($file_ext)) {
        $file_name .= ".".$file_ext;
    }

    if(empty($file_error)) {
        $sql =  "UPDATE ".DB_PREPEND."phpwcms_file SET ".
                "f_name='".aporeplace($file_name)."', ".
                "f_alias='".aporeplace($file_alias)."', ".
                "f_pid=".$file_pid.", ".
                "f_aktiv=".$file_aktiv.", ".
                "f_public=".$file_public.", ".
                "f_shortinfo='".aporeplace($file_shortinfo)."', ".
                "f_longinfo='".aporeplace($file_longinfo)."', ".
                "f_keywords='".$file_keys."', ".
                "f_created='".time()."', ".
                "f_copyright='".aporeplace($file_copyright)."', ".
                "f_tags='".aporeplace($file_tags)."', ".
                "f_granted=".$file_granted.", ".
                "f_gallerystatus=".$file_gallerydownload.", ".
                (isset($file_vars) ? 'f_vars='._dbEscape(serialize($file_vars)).',' : '').
                "f_sort=".$file_sort.", ".
                "f_title="._dbEscape($file_title).", ".
                "f_alt="._dbEscape($file_alt)." ".
                "WHERE f_kid=1 AND f_id=".$file_id;
                if(empty($_SESSION["wcs_user_admin"])) {
                    $sql .= " AND f_uid=".intval($_SESSION["wcs_user_id"]);
                }
        $result = _dbQuery($sql, 'UPDATE');

        if(!empty($result['AFFECTED_ROWS'])) {
            // store tags
            _dbSaveCategories($file_tags, 'file', $file_id, ',');
        } else {
            $file_error["save_failed"] = 1;
        }
    }
}
// end form

// If ID isset or root dir
if($file_id) {
    $sql = "SELECT * FROM ".DB_PREPEND."phpwcms_file WHERE f_id=".$file_id;
    if(empty($_SESSION["wcs_user_admin"])) {
        $sql .= " AND f_uid=".intval($_SESSION["wcs_user_id"]);
    }
    $sql .= " AND f_trash=0 AND f_kid=1 LIMIT 1";
    $result = _dbQuery($sql);

    if(isset($result[0]['f_id'])) {
        $row = $result[0];

        $file_oldname   = html($row["f_name"]);
        $file_created   = intval($row["f_created"]);
        $file_size      = intval($row["f_size"]);
        $file_id        = $row["f_id"];
        $file_ext       = $row["f_ext"];
        $file_hash      = $row["f_hash"];

        if(empty($_POST["file_aktion"]) || intval($_POST["file_aktion"]) != 2) {
            $file_pid               = $row["f_pid"];
            $file_name              = $row["f_name"];
            $file_alias             = $row["f_alias"];
            $file_aktiv             = $row["f_aktiv"];
            $file_public            = $row["f_public"];
            $file_shortinfo         = $row["f_shortinfo"];
            $file_longinfo          = $row["f_longinfo"];
            $file_keys              = $row["f_keywords"];
            $file_copyright         = $row["f_copyright"];
            $file_tags              = $row["f_tags"];
            $file_granted           = $row["f_granted"];
            $file_gallerydownload   = $row["f_gallerystatus"];
            $file_sort              = $row["f_sort"];
            $file_title             = $row["f_title"];
            $file_alt               = $row["f_alt"];
            if (empty($row['f_vars'])) {
                $file_vars = [];
            } else {
                $file_vars = @unserialize($row['f_vars'], ['allowed_classes' => false]);
                if ($file_vars === false) {
                    $file_vars = [];
                }
            }

            if($file_keys) {
                $file_keys_temp = explode(":", $file_keys);
                if(count($file_keys_temp)) {
                    $file_keywords = array();
                    foreach($file_keys_temp as $value) {
                        list($k1, $k2) = explode("_", $value);
                        $file_keywords[intval($k1)] = intval($k2);
                    }
                }
            }
        }

        if(isset($row["f_hash"])) {

            if(empty($row["f_svg"])) {

                $thumb_image = get_cached_image(array(
                    'max_width'     => 420,
                    'max_height'    => 420,
                    "target_ext"    => $row["f_ext"],
                    "image_name"    => $row["f_hash"] . '.' . $row["f_ext"],
                    "thumb_name"    => md5($row["f_hash"].'420420'.$phpwcms["sharpen_level"].$phpwcms['colorspace'])
                ));

                if($thumb_image !== false) {
                    $file_thumb_small = '<img class="img-fluid" src="'.PHPWCMS_IMAGES . $thumb_image[0] .'" '.$thumb_image[3].' alt="" style="border:1px solid #9BBECA;background:#F5F8F9;" />';
                    $file_image_size = getimagesize(PHPWCMS_STORAGE . $row["f_hash"] . '.' . $row["f_ext"], $file_image_info);
                    if(isset($file_image_info['APP13'])) {
                        $file_image_iptc = IPTC::parse($file_image_info['APP13']);
                    }
                    $file_image_width = empty($row['f_image_width']) ? $file_image_size[0] : $row['f_image_width'];
                    $file_image_height = empty($row['f_image_height']) ? $file_image_size[1] : $row['f_image_height'];
                }
            } else {
                $file_image_width = $row['f_image_width'];
                $file_image_height = $row['f_image_height'];
                $file_thumb_small = '<img class="img-fluid" src="'.PHPWCMS_RESIZE_IMAGE.'/420x420/'.$row['f_hash'].'.'.$row['f_ext'].'" alt="" style="border:1px solid #9BBECA;background:#F5F8F9;max-width:420px;height:auto;" />';
            }
        }
        $ja = 1;
    }
}

if($ja) {
?>
<h2 class="text-center text-sm-left"><?php echo $BL['be_fprivedit_title'] ?></h2>

<form action="phpwcms.php?do=files&amp;f=0" method="post" name="editfileinfo" id="editfileinfo">
  <div class="form-group row">
    <div class="col-sm-2"></div>
    <div class="col">
        <?php echo $file_thumb_small ?: '<i class="fa fa-fw fa-'.extimg($file_ext).'"></i> ' . html($file_name); ?>
    </div>
  </div>

  <div class="form-group row">
    <div class="col-sm-2"></div>
    <div class="col">
        ID: <strong><?php echo $file_id ?></strong>&nbsp;&nbsp;&nbsp;&nbsp;Hash: <strong><?php echo $file_hash ?></strong><br />
      <?php
        echo $BL['be_fprivedit_size'], ': <strong>', fsizelong($file_size), '</strong>', '&nbsp;&nbsp;&nbsp;';
        echo 'EXT: <strong>', strtoupper($file_ext), '</strong>';

        if($file_thumb_small) {
            echo '&nbsp;&nbsp;&nbsp;';
            echo $BL['be_admin_page_width'], ': <strong>', $file_image_width, '</strong>px', '&nbsp;&nbsp;&nbsp;';
            echo $BL['be_admin_page_height'], ': <strong>', $file_image_height, '</strong>px';
        }

        echo '<br />';
        echo $BL['be_fprivedit_created'], ': <strong>', date($BL['be_fprivedit_dateformat'], $file_created), '</strong>'
      ?>
    </div>
  </div>

  <div class="form-group form-row align-items-center">
    <label for="file_pid" class="col-sm-2 col-form-label text-right"><?php echo $BL['be_ftptakeover_directory'] ?></label>
    <div class="col">
      <select name="file_pid" id="file_pid" class="custom-select form-control form-control-sm">
          <option value="0" <?php if($file_pid == 0) echo "selected"; ?>><?php echo $BL['be_ftptakeover_rootdir'] ?></option>
          <?php dir_menu(0, $file_pid, "+", $_SESSION["wcs_user_id"], "+"); ?>
      </select>
    </div>
  </div>

    <?php if(isset($file_error["name"])) {
            $file_name = $file_oldname;
    ?>
<strong style="color:#cc0000"><?php echo $BL['be_fprivedit_err1'] ?></strong>

    <?php } ?>

  <div class="form-group form-row align-items-center">
    <label for="file_name" class="col-sm-2 col-form-label text-right"><?php echo $BL['be_fprivedit_filename'] ?></label>
    <div class="col">
      <input name="file_name" type="text" class="form-control form-control-sm" id="file_name" value="<?php echo html($file_name) ?>" maxlength="230">
    </div>
  </div>

  <div class="form-group form-row align-items-center">
    <label for="file_alias" class="col-sm-2 col-form-label text-right"><?php echo $BL['be_alias'] ?></label>
    <div class="col">
      <input name="file_alias" type="text" class="form-control form-control-sm" id="file_alias" value="<?php echo html($file_alias) ?>" maxlength="230" onfocus="set_file_alias(true);" onchange="this.value=create_alias(this.value);document.getElementById('file_alias_changed').value='changed';"><input name="file_alias_changed" type="hidden" id="file_alias_changed" value="" /><input name="file_alias_old" type="hidden" id="file_alias_old" value="<?php echo $file_alias ?>" />
    </div>
  </div>

<?php   if(count($phpwcms['allowed_lang']) > 1): ?>
     <ul class="nav nav-tabs" role="tablist">
      <li class="nav-item">
        <a class="nav-link active" data-toggle="tab" href="#<?php echo $phpwcms['default_lang'] ?>"  title="<?php echo get_language_name($phpwcms['default_lang']) . ' ('.$BL['be_admin_tmpl_default'].')' ?>" role="tab">
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
        <a href="#<?php echo $lang ?>" data-toggle="tab" title="<?php echo get_language_name($lang) ?>" class="nav-link" role="tab">
            <span class="flag-icon flag-icon-<?php echo $lang ?>"></span> <?php echo strtoupper($lang) ?>
        </a>
      </li>
      <?php   endforeach; ?>
   </ul>

   <div class="tab-content mt-4">

<?php   endif; ?>

      <div class="tab-pane active" id="<?php echo $phpwcms['default_lang'] ?>" role="tabpanel">
        <div class="form-group form-row align-items-center">
          <label for="file_title" class="col-sm-2 col-form-label text-right"><?php echo $BL['be_attr_title'] ?></label>
          <div class="col"><input name="file_title" type="text" id="file_title" class="form-control form-control-sm" maxlength="1000" value="<?php echo html($file_title) ?>" /></div>
        </div>

      <div class="form-group form-row">
        <label for="file_longinfo" class="col-sm-2 col-form-label text-right"><?php echo $BL['be_cnt_description'] ?></label>
        <div class="col">
          <textarea name="file_longinfo" cols="40" rows="4" class="form-control form-control-sm" id="file_longinfo"><?php echo html($file_longinfo) ?></textarea>
        </div>
      </div>
      <div class="form-group form-row align-items-center">
        <label for="file_copyright" class="col-sm-2 col-form-label text-right"><?php echo $BL['be_copyright'] ?></label>
        <div class="col">
          <input name="file_copyright" type="text" id="file_copyright" class="form-control form-control-sm" maxlength="1000" value="<?php echo html($file_copyright) ?>" />
        </div>
      </div>
      <div class="form-group form-row align-items-center">
        <label for="file_alt" class="col-sm-2 col-form-label text-right"><?php echo $BL['be_attr_alt'] ?></label>
        <div class="col">
          <input name="file_alt" type="text" id="file_alt" class="form-control form-control-sm" maxlength="1000" value="<?php echo html($file_alt) ?>" />
        </div>
      </div>
    </div>

<?php   if(count($phpwcms['allowed_lang']) > 1):

            foreach($phpwcms['allowed_lang'] as $lang):

                $lang = strtolower($lang);

                if($lang == $phpwcms['default_lang']) {
                    continue;
                }

                if(empty($file_vars[$lang]['longinfo'])) {
                    $file_vars[$lang]['longinfo'] = '';
                }
                if(empty($file_vars[$lang]['copyright'])) {
                    $file_vars[$lang]['copyright'] = '';
                }
                if(empty($file_vars[$lang]['title'])) {
                    $file_vars[$lang]['title'] = '';
                }
                if(empty($file_vars[$lang]['alt'])) {
                    $file_vars[$lang]['alt'] = '';
                }

?>
    <div class="tab-pane" id="<?php echo $lang ?>" role="tabpanel">
      <div class="form-group form-row align-items-center">
        <label for="file_title_<?php echo $lang ?>" class="col-sm-2 col-form-label text-right"><?php echo $BL['be_attr_title'] ?></label>
        <div class="col">
          <input name="file_title_<?php echo $lang ?>" type="text" id="file_title_<?php echo $lang ?>" class="form-control form-control-sm" maxlength="1000" value="<?php echo html($file_vars[$lang]['title']) ?>" />
        </div>
      </div>
      <div class="form-group form-row">
        <label for="file_longinfo_<?php echo $lang ?>" class="col-sm-2 col-form-label text-right"><?php echo $BL['be_cnt_description'] ?></label>
        <div class="col">
          <textarea name="file_longinfo_<?php echo $lang ?>" cols="40" rows="4" class="form-control autosize" id="file_longinfo_<?php echo $lang ?>"><?php echo html($file_vars[$lang]['longinfo']) ?></textarea>
        </div>
      </div>
      <div class="form-group form-row align-items-center">
        <label for="file_copyright_<?php echo $lang ?>" class="col-sm-2 col-form-label text-right"><?php echo $BL['be_copyright'] ?></label>
        <div class="col">
          <input name="file_copyright_<?php echo $lang ?>" type="text" id="file_copyright_<?php echo $lang ?>" class="form-control form-control-sm" maxlength="1000" value="<?php echo html($file_vars[$lang]['copyright']) ?>" />
        </div>
      </div>
      <div class="form-group form-row align-items-center">
        <label for="file_alt_<?php echo $lang ?>" class="col-sm-2 col-form-label text-right"><?php echo $BL['be_attr_alt'] ?></label>
        <div class="col">
          <input name="file_alt_<?php echo $lang ?>" type="text" id="file_alt_<?php echo $lang ?>" class="form-control form-control-sm" maxlength="1000" value="<?php echo html($file_vars[$lang]['alt']) ?>" />
       </div>
      </div>
    </div>

<?php
    endforeach;
    echo '</div>';
  endif;
?>

    <hr />

<?php
    // List IPTC data
    if(!empty($file_image_iptc)):
?>

<div class="form-group form-row align-items-center">
  <label class="col-sm-2 col-form-label text-right"><?php echo $BL['be_iptc_data'] ?></label>
		<div class="col-sm-auto">
			<div class="form-check form-check-inline">
				<input class="form-check-input" name="file_iptc_as_caption" type="checkbox" id="file_iptc_as_caption" value="1"<?php if(!empty($phpwcms['iptc_as_caption'])): ?> checked="checked"<?php endif; ?> />
				<label class="form-check-label" for="file_iptc_as_caption"><?php echo $BL['be_iptc_as_caption'] ?></label>
				<input type="hidden" name="file_image_iptc" value="<?php echo base64_encode(serialize($file_image_iptc)); ?>" />
			</div>
		</div>
		<div class="col-sm-auto">
		<?php
			ksort($file_image_iptc);
			foreach($file_image_iptc as $iptc_key => $iptc_value):
				echo $BL['iptc_'.$iptc_key];
				echo html(is_array($iptc_value) ? implode(', ', $iptc_value) : $iptc_value);
      endforeach;
		?>
		</div>
</div>

<?php
    endif;
    // End list IPTC data
?>

    <?php
    // List of predefined keywords (File Categories & Keys)
    $sql = "SELECT * FROM ".DB_PREPEND."phpwcms_filecat WHERE fcat_deleted=0 ORDER BY fcat_sort, fcat_name";
    $result = _dbQuery($sql);
    if(isset($result[0]['fcat_id'])) {
        $k_rows = '';
        foreach($result as $row) {
            if(get_filecat_childcount($row["fcat_id"])) {
                $has_error = isset($file_error["keywords"][$row["fcat_id"]]);
                $k_rows .= '<tr class="mb-2">' . LF;
                $k_rows .= '  <td class="pr-3 text-nowrap align-middle" style="width: 1%;">';
                $k_rows .= '    <label for="file_keywords_' . $row["fcat_id"] . '" class="col-form-label py-1">';
                if ($has_error) {
                    $k_rows .= '<span class="text-danger mr-1"><i class="fa fa-exclamation-circle"></i></span>';
                }
                $k_rows .= html($row["fcat_name"]) . ':</label>';
                $k_rows .= '  </td>' . LF;
                $k_rows .= '  <td class="align-middle py-1">' . LF;
                $k_rows .= '    <select name="file_keywords[' . $row["fcat_id"] . ']" id="file_keywords_' . $row["fcat_id"] . '" class="custom-select custom-select-sm' . ($has_error ? ' is-invalid' : '') . '" style="max-width: 350px;">' . LF;
                $k_rows .= '      <option value="' . ($row["fcat_needed"] ? "0_".$row["fcat_needed"] : "0") . '">' . ($row["fcat_needed"] ? $BL['be_ftptakeover_needed'] : $BL['be_ftptakeover_optional']) . '</option>' . LF;

                $ksql = "SELECT * FROM ".DB_PREPEND."phpwcms_filekey WHERE fkey_deleted=0 AND fkey_cid=".$row["fcat_id"]." ORDER BY fkey_name";
                $kresult = _dbQuery($ksql);

                if(isset($kresult[0]['fkey_id'])) {
                    foreach($kresult as $krow) {
                        $selected = (isset($file_keywords[$row["fcat_id"]]) && $file_keywords[$row["fcat_id"]] == $krow["fkey_id"]) ? ' selected="selected"' : '';
                        $k_rows .= '      <option value="' . $krow["fkey_id"] . '"' . $selected . '>' . html($krow["fkey_name"]) . '</option>' . LF;
                    }
                }
                $k_rows .= '    </select>' . LF;
                $k_rows .= '  </td>' . LF;
                $k_rows .= '</tr>' . LF;
            }
        }

        if ($k_rows !== '') {
?>
    <div class="form-group row align-items-start mb-2">
        <label class="col-sm-2 col-form-label text-sm-right font-weight-bold pt-1">
            <?php echo $BL['be_ftptakeover_keywords']; ?>:
        </label>
        <div class="col-sm-10">
            <table class="table table-borderless table-sm mb-0 w-auto">
                <tbody>
                    <?php echo $k_rows; ?>
                </tbody>
            </table>
        </div>
    </div>
<?php
        }
    }
    ?>


    <div class="form-group row align-items-center">
        <label for="file_shortinfo" class="col-sm-2 col-form-label text-sm-right"><?php echo $BL['be_ftptakeover_additional']; ?>:</label>
        <div class="col-sm-10">
            <input name="file_shortinfo" type="text" class="form-control form-control-sm" id="file_shortinfo" value="<?php echo html($file_shortinfo); ?>" maxlength="750">
        </div>
    </div>



  <div class="form-group align-items-center form-row">
    <span class="col-sm-2 col-form-label text-right">&nbsp;<?php echo $BL['be_tags'] ?> <i class="fas fa-info-circle text-blue" data-toggle="tooltip" title="<?php echo $BL['be_input_text_tab'] ?>"></i></span>
    <div class="col">
      <input type="text" id="file_tags_autosuggest" class="form-control form-control-sm" aria-label="<?php echo html_specialchars($BL['be_tags']) ?>" /><input name="file_tags" type="hidden" id="file_tags" value="<?php echo html($file_tags) ?>" />
    </div>
  </div>


  <div class="form-group form-row align-items-center">
    <label for="file_sort" class="col-sm-2 col-form-label text-right">&nbsp;<?php echo $BL['be_cnt_sorting'] ?></label>
    <div class="col-auto"><input name="file_sort" type="number" id="file_sort" class="form-control form-control-sm" maxlength="10" value="<?php echo intval($file_sort) ?>" />
    </div>
  </div>

  <div class="form-group form-row align-items-center">
    <label class="col-sm-2 col-form-label text-right"><?php echo $BL['be_ftptakeover_status'] ?></label>

      <div class="col">
        <div class="form-check form-check-inline">
          <input class="form-check-input" name="file_aktiv" type="checkbox" id="file_aktiv" value="1"<?php is_checked("1", $file_aktiv) ?> />
          <label class="font-weight-bold form-check-label" for="file_aktiv"><?php echo $BL['be_ftptakeover_active'] ?></label>
        </div>
        <div class="form-check form-check-inline">
          <input class="form-check-input"  name="file_granted" type="checkbox" id="file_granted" value="1"<?php is_checked("1", $file_granted) ?>>
          <label class="font-weight-bold form-check-label" for="file_granted"><?php echo $BL['be_granted_download'] ?></label>
        </div>
        <div class="form-check form-check-inline">
          <input class="form-check-input"  name="file_public" type="checkbox" id="file_public" value="1"<?php is_checked("1", $file_public) ?> />
          <label class="form-check-label" for="file_public"><?php echo $BL['be_ftptakeover_public'] ?></label>
        </div>
        <div class="form-check form-check-inline">
          <input class="form-check-input"  name="file_gallerydownload" type="checkbox" id="file_gallerydownload" value="1"<?php is_checked(1, $file_gallerydownload) ?>>
          <label class="form-check-label" for="file_gallerydownload"><?php echo $BL['be_gallerydownload'] ?></label>
        </div>
      </div>

  </div>

  <div class="form-group row">
    <div class="col-sm-2"></div>
    <div class="col-sm-10">
      <input name="Submit" type="submit" class="btn btn-blue btn-sm" value="<?php echo $BL['be_fprivedit_button'] ?>" />
      <input type="button" class="btn btn-blue btn-sm" value="<?php echo $BL['be_func_struct_close'] ?>" onclick="document.location.href='phpwcms.php?do=files&amp;f=0'" />
    </div>
  </div>

  <input name="file_id" type="hidden" id="file_id" value="<?php echo $file_id ?>" />
  <input name="file_aktion" type="hidden" id="file_aktion" value="2" />
  <input name="file_ext" type="hidden" id="file_ext" value="<?php echo strtolower($file_ext) ?>" />
</form>

<script type="text/javascript">

$(function(){

    $("#file_tags_autosuggest").autoSuggest('<?php echo PHPWCMS_URL ?>include/inc_act/ajax_connector.php', {
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

    $('#editfileinfo').submit(function(){
        $("#file_tags").val($('#as-values-keyword-autosuggest').val());
    });

<?php   if(count($phpwcms['allowed_lang']) > 1): ?>

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

<?php   endif; ?>

});

</script>

<?php
}
