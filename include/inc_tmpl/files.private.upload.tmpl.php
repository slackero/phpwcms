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

// ----------------------------------------------------------------
// obligate check for phpwcms constants
if (!defined('PHPWCMS_ROOT')) {
    die("You Cannot Access This Script Directly, Have a Nice Day.");
}
// ----------------------------------------------------------------


// Be more modern here - we start switch to jQuery and overwrite non-used MooTools with jQuery call
initJsAutocompleter();

// Upload new file
$file_aktiv             = $phpwcms['set_file_active'];
$file_public            = $phpwcms['set_file_active'];
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

    } elseif($_FILES["file"]["size"] > $phpwcms["file_maxsize"]) {

        $file_error["file"] = $BL['be_fprivup_err2']." ".number_format($phpwcms["file_maxsize"] / 1024, 2, ',', '.')." kB";

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

            if(is_string($phpwcms['allowed_upload_ext'])) {
                $phpwcms['allowed_upload_ext'] = convertStringToArray(strtolower($phpwcms['allowed_upload_ext']));
            }

            $fileExt = strtolower($fileExt);

            if($fileExt === '') {

                $file_error["file"] = sprintf($BL['be_fprivup_err9'], implode(', ', $phpwcms['allowed_upload_ext']));

            } elseif(is_array($phpwcms['allowed_upload_ext']) && count($phpwcms['allowed_upload_ext']) && !in_array($fileExt, $phpwcms['allowed_upload_ext'])) {

                $file_error["file"] = sprintf($BL['be_fprivup_err8'], $fileName, implode(', ', $phpwcms['allowed_upload_ext']));

            } elseif(!$file_image_size && $fileExt === 'svg') {

                require_once PHPWCMS_ROOT.'/include/inc_lib/classes/class.svg-reader.php';

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

        $sql =  "INSERT INTO ".DB_PREPEND."phpwcms_file (".
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
            $useruploadpath = PHPWCMS_ROOT.$phpwcms["file_path"];
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
                headerRedirect(PHPWCMS_URL.'phpwcms.php?'.get_token_get_string().'&do=files&f=0&uploaded=1');

            } else {

                echo $file_error["upload"]."<br />";
                $file_error["upload"] = str_replace('{VAL}', $phpwcms["admin_email"], $BL['be_fprivup_err6']);
                _dbQuery("DELETE FROM ".DB_PREPEND."phpwcms_file WHERE f_id=".$new_fileId." AND f_uid=".$_SESSION["wcs_user_id"], 'DELETE');

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
<form action="phpwcms.php?do=files&amp;f=0" method="post" enctype="multipart/form-data" name="uploadfile" id="uploadfile">
<table border="0" cellpadding="0" cellspacing="0" bgcolor="#EBF2F4" summary="">
    <tr>
        <td rowspan="2" valign="top"><a href="phpwcms.php?do=files&amp;f=0"><img src="img/button/close_reiter.gif" alt="" width="45" height="12" border="0" /></a></td>
        <td><img src="img/leer.gif" alt="" width="1" height="6" /></td>
    </tr>
    <tr><td class="title"><?php echo $BL['be_fprivup_title'] ?></td></tr>
    <tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="5" /></td></tr>
    <tr>
        <td align="right" class="v09"><?php echo $BL['be_ftptakeover_directory'] ?>:&nbsp;</td>
        <td class="v10"><select name="file_pid" id="file_pid" class="width400">
            <option value="0"><?php echo $BL['be_ftptakeover_rootdir'] ?></option>
            <?php dir_menu(0, $file_pid, "+", $_SESSION["wcs_user_id"], "+"); ?>
    </select></td>
    </tr>
    <tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="6" /></td></tr>
    <tr><td colspan="2"><img src="img/lines/line-bluelight.gif" alt="" width="538" height="1" /></td></tr>
    <tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="6" /></td></tr>
    <?php if(isset($file_error["upload"])) { ?>
    <tr>
      <td><img src="img/leer.gif" alt="" width="1" height="1" /></td>
      <td class="v10"><strong style="color:#FF3300"><?php echo $file_error["upload"] ?></strong></td>
    </tr>
    <tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="2" /></td></tr>
    <?php }

    if(isset($file_error["file"])) {
?>
    <tr>
      <td><img src="img/leer.gif" alt="" width="1" height="1" /></td>
      <td class="v10"><strong style="color:#FF3300"><?php echo $file_error["file"] ?></strong></td>
    </tr>
    <tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="2" /></td></tr>
    <?php } ?>
    <tr>
        <td align="right" class="v09"><?php echo $BL['be_fprivup_upload'] ?>:&nbsp;</td>
        <td><input name="file" type="file" id="file" size="40" /></td>
    </tr>

    <tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="6"></td></tr>
    <tr><td colspan="2" valign="top"><img src="img/lines/line-bluelight.gif" alt="" width="538" height="1"></td></tr>
    <tr bgcolor="#F5F8F9"><td colspan="2" valign="top"><img src="img/leer.gif" alt="" width="1" height="6"></td></tr>

    <tr bgcolor="#F5F8F9">
        <td align="right" class="v09 tdtop1"><?php echo $BL['be_iptc_data'] ?>:&nbsp;</td>
        <td>
            <table border="0" cellpadding="0" cellspacing="0" summary="">
                <tr>
                    <td><input name="file_iptc_as_caption" type="checkbox" id="file_iptc_as_caption" value="1"<?php if(!empty($phpwcms['iptc_as_caption'])): ?> checked="checked"<?php endif; ?> /></td>
                    <td class="v10"><label for="file_iptc_as_caption"><?php echo $BL['be_iptc_as_caption'] ?></label></td>
                </tr>
            </table><div id="iptc-info"></div>
        </td>
    </tr>

    <tr bgcolor="#F5F8F9"><td colspan="2" valign="top"><img src="img/leer.gif" alt="" width="1" height="6"></td></tr>

<?php   if(count($phpwcms['allowed_lang']) > 1): ?>

    <tr><td colspan="2"><img src="img/lines/line-bluelight.gif" alt="" width="538" height="1"></td></tr>

    <tr>
        <td>&nbsp;</td>
        <td class="incell-tabs">

            <a href="#" rel="<?php echo $phpwcms['default_lang'] ?>" title="<?php echo get_language_name($phpwcms['default_lang']) . ' ('.$BL['be_admin_tmpl_default'].')' ?>" class="active">
                <img src="img/famfamfam/lang/<?php echo $phpwcms['default_lang'] ?>.png" /> <?php echo $BL['be_admin_tmpl_default'] ?>
            </a>

            <?php foreach($phpwcms['allowed_lang'] as $lang):

                $lang = strtolower($lang);

                if($lang == $phpwcms['default_lang']) {
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

    <tr class="tab-content finfo<?php echo $phpwcms['default_lang'] ?>">
        <td align="right" class="v09"><?php echo $BL['be_attr_title'] ?>:&nbsp;</td>
        <td class="tdbottom2"><input name="file_title" type="text" id="file_title" size="40" class="width400" maxlength="1000" value="<?php echo html($file_title) ?>" /></td>
    </tr>
    <tr class="tab-content finfo<?php echo $phpwcms['default_lang'] ?>">
        <td align="right" valign="top" class="v09 tdtop5"><img src="img/leer.gif" alt="" width="1" height="13"><?php echo $BL['be_cnt_description'] ?>:&nbsp;</td>
        <td valign="top" class="tdbottom2"><textarea name="file_longinfo" cols="40" rows="4" class="width400 autosize" id="file_longinfo"><?php echo html($file_longinfo) ?></textarea></td>
    </tr>
    <tr class="tab-content finfo<?php echo $phpwcms['default_lang'] ?>">
        <td align="right" class="v09"><?php echo $BL['be_copyright'] ?>:&nbsp;</td>
        <td class="tdbottom2"><input name="file_copyright" type="text" id="file_copyright" size="40" class="width400" maxlength="1000" value="<?php echo html($file_copyright) ?>" /></td>
    </tr>
    <tr class="tab-content finfo<?php echo $phpwcms['default_lang'] ?>">
        <td align="right" class="v09"><?php echo $BL['be_attr_alt'] ?>:&nbsp;</td>
        <td><input name="file_alt" type="text" id="file_alt" size="40" class="width400" maxlength="1000" value="<?php echo html($file_alt) ?>" /></td>
    </tr>


<?php   if(count($phpwcms['allowed_lang']) > 1):

            foreach($phpwcms['allowed_lang'] as $lang):

                $lang = strtolower($lang);

                if($lang == $phpwcms['default_lang']) {
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
    <tr class="tab-content finfo<?php echo $lang ?>" style="display:none">
        <td align="right" class="v09"><?php echo $BL['be_attr_title'] ?>:&nbsp;</td>
        <td class="tdbottom2"><input name="file_title_<?php echo $lang ?>" type="text" id="file_title_<?php echo $lang ?>" size="40" class="width400" maxlength="1000" value="<?php echo html($file_vars[$lang]['title']) ?>" /></td>
    </tr>
    <tr class="tab-content finfo<?php echo $lang ?>" style="display:none">
        <td align="right" valign="top" class="v09 tdtop5"><img src="img/leer.gif" alt="" width="1" height="13"><?php echo $BL['be_cnt_description'] ?>:&nbsp;</td>
        <td valign="top" class="tdbottom2"><textarea name="file_longinfo_<?php echo $lang ?>" cols="40" rows="4" class="width400 autosize" id="file_longinfo_<?php echo $lang ?>"><?php echo html($file_vars[$lang]['longinfo']) ?></textarea></td>
    </tr>
    <tr class="tab-content finfo<?php echo $lang ?>" style="display:none">
        <td align="right" class="v09"><?php echo $BL['be_copyright'] ?>:&nbsp;</td>
        <td class="tdbottom2"><input name="file_copyright_<?php echo $lang ?>" type="text" id="file_copyright_<?php echo $lang ?>" size="40" class="width400" maxlength="1000" value="<?php echo html($file_vars[$lang]['copyright']) ?>" /></td>
    </tr>
    <tr class="tab-content finfo<?php echo $lang ?>" style="display:none">
        <td align="right" class="v09"><?php echo $BL['be_attr_alt'] ?>:&nbsp;</td>
        <td><input name="file_alt_<?php echo $lang ?>" type="text" id="file_alt_<?php echo $lang ?>" size="40" class="width400" maxlength="1000" value="<?php echo html($file_vars[$lang]['alt']) ?>" /></td>
    </tr>


<?php       endforeach;
        endif;
?>

    <tr><td colspan="2" valign="top"><img src="img/leer.gif" alt="" width="1" height="6"></td></tr>
    <tr><td colspan="2" valign="top"><img src="img/lines/line-bluelight.gif" alt="" width="538" height="1"></td></tr>
    <tr bgcolor="#F5F8F9"><td colspan="2" valign="top"><img src="img/leer.gif" alt="" width="1" height="6"></td></tr>

    <?php

    //Auswahlliste vordefinierte Keywörter
    $sql = "SELECT * FROM ".DB_PREPEND."phpwcms_filecat WHERE fcat_deleted=0 ORDER BY fcat_sort, fcat_name";
    $result = _dbQuery($sql);
    $k = '';

    if(isset($result[0]['fcat_id'])) {
        foreach($result as $row) {
            if(get_filecat_childcount($row["fcat_id"])) {

                $k .= "<tr><td class=\"f10b\">";
                $k .= isset($file_error["keywords"][$row["fcat_id"]]) ? '<img src="img/symbole/error.gif" width="8" height="9" alt="" />&nbsp;' : '';
                $k .= html($row["fcat_name"]).":&nbsp;</td>";
                $k .= "<td><select name=\"file_keywords[".$row["fcat_id"]."]\" class=\"width300\">";
                $k .= "<option value=\"".(($row["fcat_needed"])?"0_".$row["fcat_needed"]."\">".$BL['be_ftptakeover_needed']:'0">'.$BL['be_ftptakeover_optional'])."</option>";

                $ksql = "SELECT * FROM ".DB_PREPEND."phpwcms_filekey WHERE fkey_deleted=0 AND fkey_cid=".$row["fcat_id"]." ORDER BY fkey_name";
                $kresult = _dbQuery($ksql);
                if(isset($kresult[0]['fkey_id'])) {
                    foreach($kresult as $krow) {
                        $k .= "<option value=\"".$krow["fkey_id"]."\"";
                        $k .= isset($file_keywords[$row["fcat_id"]]) && $file_keywords[$row["fcat_id"]] == $krow["fkey_id"] ? ' selected="selected"' : '';
                        $k .= ">".html($krow["fkey_name"])."</option>\n";
                    }
                }

                $k .= "</select></td></tr>";
                $k .= "<tr><td colspan=\"2\"><img src=\"img/leer.gif\" width=\"1\" height=\"2\"></td>\n</tr>";

            }
        }
    }

    ?>
    <tr bgcolor="#F5F8F9">
        <td align="right" valign="top" class="v09 tdtop1"><?php echo $BL['be_ftptakeover_keywords'] ?>:&nbsp;</td>
        <td><table border="0" cellpadding="0" cellspacing="0" summary="">
        <?php echo $k; ?>
        <tr>
            <td class="f10b"><?php echo $BL['be_ftptakeover_additional'] ?>:&nbsp;</td>
            <td><input name="file_shortinfo" type="text" class="width300" id="file_shortinfo" value="<?php echo html($file_shortinfo) ?>" size="40" maxlength="750"></td>
        </tr>
        </table></td>
    </tr>

    <tr bgcolor="#F5F8F9"><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="3" /></td></tr>

    <tr bgcolor="#F5F8F9">
        <td align="right" class="v09">&nbsp;<?php echo $BL['be_tags'] ?>:&nbsp;</td>
        <td><input type="text" id="file_tags_autosuggest" /><input name="file_tags" type="hidden" id="file_tags" value="<?php echo html($file_tags) ?>" /></td>
    </tr>

    <tr bgcolor="#F5F8F9"><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="6"></td></tr>
    <tr><td colspan="2"><img src="img/lines/line-bluelight.gif" alt="" width="538" height="1"></td></tr>

    <tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="6" /></td></tr>

    <tr>
        <td align="right" class="v09">&nbsp;<?php echo $BL['be_cnt_sorting'] ?>:&nbsp;</td>
        <td><input name="file_sort" type="text" id="file_sort" size="10" class="width50" maxlength="10" value="<?php echo intval($file_sort) ?>" /></td>
    </tr>

    <tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="5" /></td></tr>

    <tr>
        <td align="right" class="v09 tdtop3"><?php echo $BL['be_ftptakeover_status'] ?>:&nbsp;</td>
        <td><table border="0" cellpadding="0" cellspacing="0" summary="">
        <tr>
            <td><input name="file_aktiv" type="checkbox" id="file_aktiv" value="1"<?php is_checked("1", $file_aktiv) ?> /></td>
            <td class="v10"><strong><label for="file_aktiv"><?php echo $BL['be_ftptakeover_active'] ?></label></strong>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>

            <td><input name="file_granted" type="checkbox" id="file_granted" value="1"<?php is_checked("1", $file_granted) ?>></td>
            <td class="v10"><label for="file_granted"><?php echo $BL['be_granted_download'] ?></label></td>
        </tr>
        <tr>
            <td><input name="file_public" type="checkbox" id="file_public" value="1"<?php is_checked("1", $file_public) ?> /></td>
            <td class="v10"><strong><label for="file_public"><?php echo $BL['be_ftptakeover_public'] ?></label></strong>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>

            <td><input name="file_gallerydownload" type="checkbox" id="file_gallerydownload" value="1"<?php is_checked(1, $file_gallerydownload) ?>></td>
            <td class="v10"><label for="file_gallerydownload"><?php echo $BL['be_gallerydownload'] ?></label></td>
        </tr>
        </table></td>
    </tr>

    <tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="5" /></td></tr>

    <tr>
        <td valign="top"><input name="file_aktion" type="hidden" id="file_aktion" value="1" />
            <input type="hidden" name="MAX_FILE_SIZE" value="<?php

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

        ?>" /></td>
        <td>
            <input name="Submit" type="submit" class="button" value="<?php echo $BL['be_fprivup_button'] ?>" />
            <input type="button" class="button" value="<?php echo $BL['be_func_struct_close'] ?>" onclick="document.location.href='phpwcms.php?do=files&amp;f=0'" />
        </td>
    </tr>
    <tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="8" /></td></tr>
    <tr><td colspan="2" bgcolor="#9BBECA"><img src="img/leer.gif" alt="" width="1" height="4" /></td></tr>
</table>
</form>
<script type="text/javascript">

$(function(){

    $("#file_tags_autosuggest").autoSuggest('<?php echo PHPWCMS_URL ?>include/inc_act/ajax_connector.php', {
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
