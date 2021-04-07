<?php
/**
 * cmsGO!
 *
 * @author Pixels & Points GmbH <info@pixels-points.ch>
 * @copyright Copyright (c) 2002-2020, Pixels & Points GmbH
 * @license https://www.pixels-points.ch/cmsgo-license.html Pixels & Points cmsGO! license
 *
 **/

// ----------------------------------------------------------------
// obligate check for cmsgo constants
if (!defined('CMSGO_ROOT')) {
    die("You Cannot Access This Script Directly, Have a Nice Day.");
}
// ----------------------------------------------------------------


// Be more modern here - we start switch to jQuery and overwrite non-used MooTools with jQuery call
initJsAutocompleter();

// Upload new file
$file_aktiv             = $cmsgo['set_file_active'];
$file_public            = $cmsgo['set_file_active'];
$file_shortinfo         = '';
$file_longinfo          = '';
$file_pid               = empty($_GET["upload"]) ? 0 : intval($_GET["upload"]);
$file_copyright         = '';
$file_tags              = '';
$file_granted           = 0;
$file_gallerydownload   = 0;
$file_sort              = 0;
$file_title     = '';
$file_alt       = '';

//Auswerten des Formulars
if(isset($_POST["file_aktion"]) && intval($_POST["file_aktion"]) == 1) {

    if(!ini_get('safe_mode') && function_exists('set_time_limit')) {
        set_time_limit(0);
    }

    $file_pid               = intval($_POST["file_pid"]);
    $file_aktiv             = empty($_POST["file_aktiv"]) ? 0 : 1;
    $file_public            = empty($_POST["file_public"]) ? 0 : 1;
    $file_shortinfo         = clean_slweg($_POST["file_shortinfo"]);
    $file_longinfo          = slweg($_POST["file_longinfo"]);
    $file_copyright         = clean_slweg($_POST["file_copyright"]);
    $file_tags              = trim(clean_slweg($_POST["file_tags"]), ',');
    $file_granted           = empty($_POST["file_granted"]) ? 0 : 1;
    $file_gallerydownload   = empty($_POST["file_gallerydownload"]) ? 0 : 1;
    $file_keys              = '';
    $file_sort              = intval($_POST["file_sort"]);
    $file_title             = clean_slweg($_POST["file_title"]);
    $file_alt               = clean_slweg($_POST["file_alt"]);
    $file_is_uploaded       = isset($_FILES["file"]["tmp_name"]) && is_uploaded_file($_FILES["file"]["tmp_name"]);
    $file_iptc_info         = null;
    $file_image_size        = null;
    $file_svg               = 0;

    if($file_is_uploaded) {

        // Try to read image data
        $file_image_size = getimagesize($_FILES["file"]["tmp_name"], $file_image_info);

    }

    // Check against IPTC and handle IPTC tags if applicable
    if(!empty($_POST['file_iptc_as_caption']) && isset($file_image_info['APP13'])) {
        $file_image_iptc = IPTC::parse($file_image_info['APP13']);
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
    }

    if(count($cmsgo['allowed_lang']) > 1) {

        $file_vars = array();

        foreach($cmsgo['allowed_lang'] as $lang) {
            $lang = strtolower($lang);

            $file_vars[$lang] = array(
                'longinfo' => '',
                'copyright' => '',
                'title' => '',
                'alt' => ''
            );

            if($cmsgo['default_lang'] === $lang) {
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
            if(!empty($cmsgo['iptc_as_caption_all_lang']) && $file_iptc_info !== null) {
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

    $file_keywords = empty($_POST["file_keywords"]) ? array() : $_POST["file_keywords"];
    if(count($file_keywords)) {
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

    //starts upload of file
    if(!$file_is_uploaded) {

        $file_error["file"] = $BL['be_fprivup_err1'];

    } elseif($_FILES["file"]["size"] > $cmsgo["file_maxsize"]) {

        $file_error["file"] = $BL['be_fprivup_err2']." ".number_format($cmsgo["file_maxsize"] / 1024, 2, ',', '.')." kB";

    } else {

        $fileName = sanitize_filename($_FILES["file"]["name"]);
        if(false === ($fileExt = check_image_extension($_FILES["file"]["tmp_name"], $fileName, $file_image_size))) {
            $fileExt = which_ext($fileName);
        }
        $fileHash = md5( $fileName . microtime() );
        $fileType = is_mimetype_format($_FILES["file"]["type"]) ? $_FILES["file"]["type"] : get_mimetype_by_extension($fileExt);
        $fileSize = intval($_FILES["file"]["size"]);

        // Check against forbidden file names
        $forbiddenUploadName = array(
            '.htaccess', // Apache config
            'web.config', // IIS config
            'lighttpd.conf', // Lighttpd
            'nginx.conf', // Nginx
        );

        if(substr($fileName, 0, 1) === '.' || in_array(strtolower($fileName), $forbiddenUploadName)) {
            $file_error["file"] = sprintf($BL['be_fprivup_err7'], $fileName);
        }

        // Only allowed file extensions
        if(empty($file_error["file"])) {

            if(is_string($cmsgo['allowed_upload_ext'])) {
                $cmsgo['allowed_upload_ext'] = convertStringToArray(strtolower($cmsgo['allowed_upload_ext']));
            }

            $fileExt = strtolower($fileExt);

            if($fileExt === '') {

                $file_error["file"] = sprintf($BL['be_fprivup_err9'], implode(', ', $cmsgo['allowed_upload_ext']));

            } elseif(is_array($cmsgo['allowed_upload_ext']) && count($cmsgo['allowed_upload_ext']) && !in_array($fileExt, $cmsgo['allowed_upload_ext'])) {

                $file_error["file"] = sprintf($BL['be_fprivup_err8'], $fileName, implode(', ', $cmsgo['allowed_upload_ext']));

            } elseif(!$file_image_size && $fileExt === 'svg') {

                require_once CMSGO_ROOT.'/include/inc_lib/classes/class.svg-reader.php';

                if($file_svg = @SVGMetadataExtractor::getMetadata($_FILES["file"]["tmp_name"])) {
                    $fileType = 'image/svg+xml';
                    $file_image_size = array(
                        0 => $file_svg['width'],
                        1 => $file_svg['height']
                    );
                    $file_svg = 1;
                }
            }
        }
    }

    if(empty($file_error)) {

        if(isset($file_vars)) {
            $fileVarsField = ',f_vars';
            $fileVarsValue = ','._dbEscape(serialize($file_vars));
        } else {
            $fileVarsField = '';
            $fileVarsValue = '';
        }

        $sql =  "INSERT INTO ".DB_PREPEND."cmsgo_file (".
                "f_pid, f_uid, f_kid, f_aktiv, f_public, f_name, f_created, f_size, f_type, f_ext, f_svg, f_image_width, f_image_height, ".
                "f_shortinfo, f_longinfo, f_keywords, f_hash, f_copyright, f_tags, f_granted, f_gallerystatus, ".
                "f_sort".$fileVarsField.", f_title, f_alt) VALUES (".
                $file_pid.", ".intval($_SESSION["wcs_user_id"]).", 1, ".$file_aktiv.", ".$file_public.", '".
                $fileName."', '".time()."', '".$fileSize."', '".aporeplace($fileType)."', '".$fileExt."', ".
                $file_svg.', '._dbEscape(empty($file_image_size[0]) ? '' : $file_image_size[0]).", ".
                _dbEscape(empty($file_image_size[1]) ? '' : $file_image_size[1]).", '".aporeplace($file_shortinfo)."', '".
                aporeplace($file_longinfo)."', '".aporeplace($file_keys)."', '".aporeplace($fileHash)."', '".
                aporeplace($file_copyright)."', '".aporeplace($file_tags)."', ".$file_granted.", ".
                $file_gallerydownload.", ".$file_sort.$fileVarsValue.","._dbEscape($file_title).", "._dbEscape($file_alt).")";
        $result = _dbQuery($sql, 'INSERT');

        if(!empty($result['INSERT_ID'])) {
            $new_fileId = $result['INSERT_ID']; //Festlegen der aktuellen File-ID
            $wcs_newfilename = ($fileExt) ? $fileHash.'.'.$fileExt : $fileHash;

            // changed for using hashed file names
            $useruploadpath = CMSGO_ROOT.$cmsgo["file_path"];
            $usernewfile    = $useruploadpath.$wcs_newfilename;

            if ($dir = @opendir($useruploadpath)) {
                if(!@move_uploaded_file($_FILES["file"]["tmp_name"], $usernewfile)) {

                    $file_error["upload"] = $BL['be_fprivup_err3'].' (1)';
                }
            } else {
                $oldumask = umask(0);
                if(@mkdir($useruploadpath, 0777)) {
                    if(!@move_uploaded_file($_FILES["file"]["tmp_name"], $usernewfile)) {
                        $file_error["upload"] = $BL['be_fprivup_err3'].' (2)';
                    }
                } else {
                    $file_error["upload"] = $BL['be_fprivup_err4'];
                }
                umask($oldumask);
            }
            if(is_file($usernewfile)) {
                @chmod($usernewfile, 0666);
            }
            if(empty($file_error["upload"])) {

                // store tags
                _dbSaveCategories($file_tags, 'file', $new_fileId, ',');

                //after successful upload go back to clear post (form) var
                headerRedirect(CMSGO_URL.'cmsgo.php?'.get_token_get_string().'&do=files&f=0&uploaded=1');

            } else {

                echo $file_error["upload"]."<br />";
                $file_error["upload"] = str_replace('{VAL}', $cmsgo["admin_email"], $BL['be_fprivup_err6']);
                _dbQuery("DELETE FROM ".DB_PREPEND."cmsgo_file WHERE f_id=".$new_fileId." AND f_uid=".$_SESSION["wcs_user_id"], 'DELETE');

            }
        }
    }

    if(!ini_get('safe_mode') && function_exists('set_time_limit')) {
        set_time_limit(30);
    }
}

// Init Exif.js library
$GLOBALS['BE']['HEADER']['exif.js'] = getJavaScriptSourceLink('include/inc_js/exif.min.js');
$GLOBALS['BE']['BODY_CLOSE']['exif.js.upload'] = '<script type="text/javascript">
document.getElementById("file").onchange = function(e) {
    var iptcdata = document.getElementById("iptc-info");
    iptcdata.innerHTML = "";
    EXIF.getData(e.target.files[0], function() {
        //alert(EXIF.pretty(this));
        var iptctags = {
            Caption: EXIF.getIptcTag(this, "caption"),
            Copyright: EXIF.getIptcTag(this, "copyright"),
            Credit: EXIF.getIptcTag(this, "credit"),
            Title: EXIF.getIptcTag(this, "headline"),
            Category: EXIF.getIptcTag(this, "category"),
            Writer: EXIF.getIptcTag(this, "captionWriter"),
            Creator: EXIF.getIptcTag(this, "byline"),
            Profession: EXIF.getIptcTag(this, "bylineTitle")
        }, iptctable = "";
        for(var a in iptctags) {
            if(iptctags.hasOwnProperty(a)) {
                if(typeof iptctags[a] === "string") {
                    var val = iptctags[a].trim();
                    if(val !== "") {
                        iptctable += \'<tr><td class="chatlist tdtop3" width="5%">\' + a + \'&nbsp;</td><td class="tdtop3">\' + val + \'</td></tr>\';
                    }
                }
            }
    }
        if(iptctable !== "") {
            iptctable = \'<table cellspacing="0" cellpadding="0" border="0" style="width:95%;border-top:1px solid #9BBECA;margin:3px 5px 0 0;">\' + iptctable + \'</table>\';
        }
        iptcdata.innerHTML = iptctable;
    });
}
</script>';

?>
<div class="row">
  <div class="col"><h1><?php echo $BL['be_fprivup_title'] ?></h1></div>
  <div class="col text-right"><a href="cmsgo.php?do=files&amp;f=0"><span aria-hidden="true"><i class="fas fa-times-square fa-2x text-danger"></i></span></a></div>
</div>

<form action="cmsgo.php?do=files&amp;f=0" method="post" enctype="multipart/form-data" name="uploadfile" id="uploadfile">

  <div class="form-group form-row align-items-center">
    <label for="file_pid" class="col-sm-2 col-form-label text-right"><?php echo $BL['be_ftptakeover_directory'] ?></label>
    <div class="col-sm-4">
      <select name="file_pid" id="file_pid" class="custom-select form-control form-control-sm">
        <option value="0"><?php echo $BL['be_ftptakeover_rootdir'] ?></option>
        <?php dir_menu(0, $file_pid, "+", $_SESSION["wcs_user_id"], "+"); ?>
      </select>
    </div>
  </div>

  <div class="form-group form-row align-items-center">
    <?php if(isset($file_error["upload"])) { ?>
      <label for="uploaderror" class="col-sm-2 col-form-label text-right danger"><?php echo $file_error["upload"] ?></label>
    <?php }

    if(isset($file_error["file"])) {
      ?>
      <label for="fileerror" class="col-sm-2 col-form-label text-right danger"><?php echo $file_error["file"] ?></label>
    <?php } ?>

    <label for="fprivup" class="col-sm-2 col-form-label text-right"><?php echo $BL['be_fprivup_upload'] ?></label>
    <div class="col-sm-4">
      <!-- JS: input:file mitnehmen -->
      <div class="input-group">
        <div class="custom-file">
          <input name="file" type="file" class="custom-file-input" id="file" />
          <label class="custom-file-label" for="file"></label>
        </div>
      </div>
    </div>
  </div>
<hr />
	<div class="form-group form-row align-items-center">
		<label for="be_iptc_data" class="col-sm-2 col-form-label text-right"><?php echo $BL['be_iptc_data'] ?></label>
			<div class="col form-check form-check-inline">
				<input class="form-check-input" name="file_iptc_as_caption" type="checkbox" id="file_iptc_as_caption" value="1"<?php if(!empty($cmsgo['iptc_as_caption'])): ?> checked="checked"<?php endif; ?> />
				<label class="form-check-label" for="file_iptc_as_caption"><?php echo $BL['be_iptc_as_caption'] ?></label>
			</div>
		<div id="iptc-info"></div>
	</div>

		<?php if(count($cmsgo['allowed_lang']) > 1): ?>
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
        <div class="col"><input name="file_title" type="text" id="file_title" class="form-control form-control-sm" maxlength="1000" value="<?php echo html($file_title) ?>" /></div>
      </div>

      <div class="form-group form-row">
          <label for="file_longinfo" class="col-sm-2 col-form-label text-right"><?php echo $BL['be_cnt_description'] ?></label>
          <div class="col"><textarea name="file_longinfo" cols="40" rows="4" class="form-control form-control-sm autosize" id="file_longinfo"><?php echo html($file_longinfo) ?></textarea></div>
      </div>
      <div class="form-group form-row align-items-center">
        <label for="file_copyright" class="col-sm-2 col-form-label text-right"><?php echo $BL['be_copyright'] ?></label>
        <div class="col"><input name="file_copyright" type="text" id="file_copyright" class="form-control form-control-sm" maxlength="1000" value="<?php echo html($file_copyright) ?>" /></div>
      </div>
      <div class="form-group form-row align-items-center">
        <label for="file_alt" class="col-sm-2 col-form-label text-right"><?php echo $BL['be_attr_alt'] ?></label>
        <div class="col"><input name="file_alt" type="text" id="file_alt" class="form-control form-control-sm" maxlength="1000" value="<?php echo html($file_alt) ?>" /></div>
      </div>
    </div>

<?php   if(count($cmsgo['allowed_lang']) > 1):

            foreach($cmsgo['allowed_lang'] as $lang):

                $lang = strtolower($lang);

                if($lang == $cmsgo['default_lang']) {
                    continue;
                }

                $file_vars[$lang] = array(
                    'longinfo' => '',
                    'copyright' => '',
                    'title' => '',
                    'alt' => ''
                );

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
      <label class="col-sm-2 col-form-label text-right"><?php echo $BL['be_attr_title'] ?></label>
      <div class="col"><input name="file_title_<?php echo $lang ?>" type="text" id="file_title_<?php echo $lang ?>"  class="form-control form-control-sm" maxlength="1000" value="<?php echo html($file_vars[$lang]['title']) ?>" /></div>
  </div>
  <div class="form-group form-row">
      <label class="col-sm-2 col-form-label text-right"><?php echo $BL['be_cnt_description'] ?></label>
      <div class="col"><textarea name="file_longinfo_<?php echo $lang ?>" rows="4" class="form-control form-control-sm autosize" id="file_longinfo_<?php echo $lang ?>"><?php echo html($file_vars[$lang]['longinfo']) ?></textarea></div>
  </div>
  <div class="form-group form-row align-items-center">
      <label class="col-sm-2 col-form-label text-right"><?php echo $BL['be_copyright'] ?></label>
      <div class="col"><input name="file_copyright_<?php echo $lang ?>" type="text" id="file_copyright_<?php echo $lang ?>" class="form-control form-control-sm" maxlength="1000" value="<?php echo html($file_vars[$lang]['copyright']) ?>" /></div>
  </div>
  <div class="form-group form-row align-items-center">
      <label class="col-sm-2 col-form-label text-right"><?php echo $BL['be_attr_alt'] ?></label>
      <div class="col"><input name="file_alt_<?php echo $lang ?>" type="text" id="file_alt_<?php echo $lang ?>" class="form-control form-control-sm" maxlength="1000" value="<?php echo html($file_vars[$lang]['alt']) ?>" /></div>
  </div>
</div>

<?php       endforeach;
        echo '</div>';
        endif;

    //Auswahlliste vordefinierte Keywörter
    $sql = "SELECT * FROM ".DB_PREPEND."cmsgo_filecat WHERE fcat_deleted=0 ORDER BY fcat_sort, fcat_name";
    $result = _dbQuery($sql);
    $k = '';

    if(isset($result[0]['fcat_id'])) {
        foreach($result as $row) {
            if(get_filecat_childcount($row["fcat_id"])) {

                $k .= "<tr><td>";
                $k .= isset($file_error["keywords"][$row["fcat_id"]]) ? '<img src="img/symbole/error.gif" width="8" height="9" alt="" />&nbsp;' : '';
                $k .= html($row["fcat_name"]).":&nbsp;</td>";
                $k .= "<td><select name=\"file_keywords[".$row["fcat_id"]."]\" class=\"custom-select form-control\">";
                $k .= "<option value=\"".(($row["fcat_needed"])?"0_".$row["fcat_needed"]."\">".$BL['be_ftptakeover_needed']:'0">'.$BL['be_ftptakeover_optional'])."</option>";

                $ksql = "SELECT * FROM ".DB_PREPEND."cmsgo_filekey WHERE fkey_deleted=0 AND fkey_cid=".$row["fcat_id"]." ORDER BY fkey_name";
                $kresult = _dbQuery($ksql);
                if(isset($kresult[0]['fkey_id'])) {
                    foreach($kresult as $krow) {
                        $k .= "<option value=\"".$krow["fkey_id"]."\"";
                        $k .= isset($file_keywords[$row["fcat_id"]]) && $file_keywords[$row["fcat_id"]] == $krow["fkey_id"] ? ' selected="selected"' : '';
                        $k .= ">".html($krow["fkey_name"])."</option>\n";
                    }
                }
                $k .= "</select></td></tr>";
            }
        }
    }
    ?>
		<hr />
    <legend><?php echo $BL['be_ftptakeover_keywords'] ?></legend>
    <?php echo $k; ?>

    <div class="form-group form-row align-items-center">
      <label for="be_ftptakeover_additional" class="col-sm-2 col-form-label text-right"><?php echo $BL['be_ftptakeover_additional'] ?></label>
      <div class="col">
     		<input name="file_shortinfo" type="text" id="file_shortinfo" class="form-control form-control-sm" value="<?php echo html($file_shortinfo) ?>" maxlength="750">
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
      <label for="be_ftptakeover_additional" class="col-sm-2 col-form-label text-right"><?php echo $BL['be_cnt_sorting'] ?></label>
      <div class="col-sm-4">
     	<input name="file_sort" type="text" id="file_sort" class="form-control form-control-sm" maxlength="10" value="<?php echo intval($file_sort) ?>" />
      </div>
    </div>

    <div class="form-group form-row align-items-center">
    	<label for="be_ftptakeover_status" class="col-sm-2 col-form-label text-right"><?php echo $BL['be_ftptakeover_status'] ?></label>
    	<div class="form-check form-check-inline">
				<input class="form-check-input" name="file_aktiv" type="checkbox" id="file_aktiv" value="1"<?php is_checked("1", $file_aktiv) ?> />
				<label class="form-check-label" for="file_aktiv"><?php echo $BL['be_ftptakeover_active'] ?></label>
			</div>
    	<div class="form-check form-check-inline">
				<input class="form-check-input" name="file_public" type="checkbox" id="file_public" value="1"<?php is_checked("1", $file_public) ?> />
				<label class="form-check-label" for="file_public">
				<?php echo $BL['be_ftptakeover_public'] ?>
				</label>
			</div>
    	<div class="form-check-inline">
				<input class="form-check-input" name="file_granted" type="checkbox" id="file_granted" value="1"<?php is_checked("1", $file_granted) ?> />
        <label class="form-check-label" for="file_granted">
					<?php echo $BL['be_granted_download'] ?>
				</label>
			</div>
    	<div class="form-check form-check-inline">
				<input class="form-check-input" name="file_gallerydownload" type="checkbox" id="file_gallerydownload" value="1" <?php is_checked(1, $file_gallerydownload) ?> />
				<label class="form-check-label" for="file_gallerydownload">
				<?php echo $BL['be_gallerydownload'] ?>
			</label>
			</div>
    </div>

		<input name="file_aktion" type="hidden" id="file_aktion" value="1" />
    <input type="hidden" name="MAX_FILE_SIZE" value="<?php
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
    ?>" />

    <div class="form-group form-row align-items-center mt-3">
    	<label for="be_ftptakeover_status" class="col-sm-2 col-form-label"></label>
    	<div class="col text-center text-sm-left">
				<input name="Submit" type="submit" class="btn btn-blue btn-sm mr-1" value="<?php echo $BL['be_fprivup_button'] ?>" />
				<input type="button" class="btn btn-blue btn-sm" value="<?php echo $BL['be_func_struct_close'] ?>" onclick="document.location.href='cmsgo.php?do=files&amp;f=0'" />
			</div>
		</div>
</form>

<script type="text/javascript">

$('input:file').change(
  function(e){
    $("label[for='file']").text(e.target.files[0].name);
});

$(function(){

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

    $('#uploadfile').submit(function(){
        $("#file_tags").val($('#as-values-keyword-autosuggest').val());
    });

<?php   if(count($cmsgo['allowed_lang']) > 1): ?>

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
