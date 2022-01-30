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

$phpwcms = array('SESSION_START' => true);
$PHPWCMS_ROOT = dirname(dirname(dirname(__FILE__)));

require_once $PHPWCMS_ROOT.'/include/config/conf.inc.php';
require_once $PHPWCMS_ROOT.'/include/inc_lib/default.inc.php';
require_once PHPWCMS_ROOT.'/include/inc_lib/helper.session.php';
require_once PHPWCMS_ROOT.'/include/inc_lib/dbcon.inc.php';
require_once PHPWCMS_ROOT.'/include/inc_lib/general.inc.php';
checkLogin();
validate_csrf_tokens();
require_once PHPWCMS_ROOT.'/include/inc_lib/backend.functions.inc.php';

$ref = empty($_SESSION['REFERER_URL']) ? PHPWCMS_URL.'phpwcms.php?'.get_token_get_string() : $_SESSION['REFERER_URL'];
$file_error = array();
$new_fileId = 0;

$ftp = array(
    'error' => 0,
    'mark' => isset($_POST["ftp_mark"]) ? $_POST["ftp_mark"] : false,
    'file' => isset($_POST["ftp_file"]) ? $_POST["ftp_file"] : false,
    'filename' => isset($_POST["ftp_filename"]) ? $_POST["ftp_filename"] : false
);

if(is_array($ftp["mark"]) && count($ftp["mark"])) {
    foreach($ftp["mark"] as $key => $value) {
        if(intval($ftp["mark"][$key])) {
            $ftp["file"][$key] = base64_decode($ftp["file"][$key]);
            if (substr($ftp["file"][$key], 0, 1) === '.' || strpos($ftp["file"][$key], '/') !== false || strpos($ftp["file"][$key], "\\") !== false || !is_file(PHPWCMS_ROOT.$phpwcms["ftp_path"].$ftp["file"][$key])) {
                unset(
                    $ftp["mark"][$key],
                    $ftp["file"][$key],
                    $ftp["filename"][$key]
                );
            } else {
                $ftp["filename"][$key] = clean_slweg($ftp["filename"][$key]);
            }
        } else {
            unset(
                $ftp["mark"][$key],
                $ftp["file"][$key],
                $ftp["filename"][$key]
            );
        }
    }
    if(!count($ftp["mark"])) {
        $ftp["error"] = 1;
    }
} else {
    $ftp["error"] = 1;
}

?><!DOCTYPE>
<html>
<head>
    <title>phpwcms: File take over</title>
    <meta charset="<?php echo PHPWCMS_CHARSET ?>">
    <meta http-equiv="Expires" content="0">
    <meta http-equiv="Pragma" content="no-cache">
    <meta http-equiv="cache-control" content="no-cache">
    <meta name="robots" content="noindex,nofollow">
    <link href="../inc_css/phpwcms.min.css" rel="stylesheet" type="text/css">
    <style type="text/css">
        body {
            background-color: #EBF2F4;
            padding: 1em;
        }
        .wrapper {
            display: inline-block;
            padding: 20px;
            margin: 3em;
            background-color: rgba(255, 255, 255, .85);
            border-radius: 10px;
        }
    </style>
</head>
<body>
    <div class="wrapper">
<?php
if(!$ftp["error"]) {

    require_once PHPWCMS_ROOT.'/include/inc_lib/classes/class.svg-reader.php';

    if(!empty($_POST['file_iptc_as_caption'])) {
        require_once PHPWCMS_ROOT.'/include/inc_lib/default.backend.inc.php';
        require_once PHPWCMS_ROOT.'/include/inc_lib/constants/timestamp.php';
        require_once PHPWCMS_ROOT.'/include/inc_lib/classes/class.iptc.php';
        require_once PHPWCMS_ROOT.'/include/inc_lib/classes/class.convertibletimestamp.php';
    }

    $ftp['dir']         = intval($_POST['file_dir']);
    $ftp['dir_new']     = empty($_POST['file_dir_new']) ? '' : clean_slweg($_POST['file_dir_new']);
    $ftp['short_info']  = clean_slweg($_POST['file_shortinfo']);
    $ftp['title']       = clean_slweg($_POST['file_title']);
    $ftp['alt']         = clean_slweg($_POST['file_alt']);
    $ftp['aktiv']       = empty($_POST['file_aktiv']) ? 0 : 1;
    $ftp['public']      = empty($_POST['file_public']) ? 0 : 1;
    $ftp['replace']     = empty($_POST['file_replace']) ? 0 : 1;
    $ftp['long_info']   = slweg($_POST['file_longinfo']);
    $ftp['copyright']   = slweg($_POST['file_copyright']);
    $ftp['tags']        = trim( trim( clean_slweg($_POST['file_tags']), ',') );
    $ftp['keywords']    = isset($_POST['file_keywords']) ? $_POST['file_keywords'] : array();
    $ftp['keys']        = '';
    $ftp['file_vars']   = array();

    if(is_array($ftp["keywords"]) && count($ftp["keywords"])) {
        foreach($ftp["keywords"] as $key => $value) {
            unset($ftp["keywords"][$key]);
            $key = intval($key);
            if($value != "0_1") {
                $ftp["keys"] .= (($ftp["keys"]) ? ":" : "").$key."_".intval($value);
                $ftp["keywords"][$key] = intval($value);
            } else {
                $file_error["keywords"][$key] = 1;
            }
        }
    }

    if(count($phpwcms['allowed_lang']) > 1) {
        foreach($phpwcms['allowed_lang'] as $lang) {
            $lang = strtolower($lang);

            $ftp['file_vars'][$lang] = array(
                'longinfo' => '',
                'copyright' => '',
                'title' => '',
                'alt' => ''
            );

            if($phpwcms['default_lang'] === $lang) {
                $ftp['file_vars'][$lang]['longinfo'] = $ftp["long_info"];
                $ftp['file_vars'][$lang]['copyright'] = $ftp["copyright"];
                $ftp['file_vars'][$lang]['title'] = $ftp["title"];
                $ftp['file_vars'][$lang]['alt'] = $ftp["alt"];
            }

            if(isset($_POST['file_longinfo_'.$lang])) {
                $ftp['file_vars'][$lang]['longinfo'] = slweg($_POST['file_longinfo_'.$lang]);
            }
            if(isset($_POST['file_copyright_'.$lang])) {
                $ftp['file_vars'][$lang]['copyright'] = clean_slweg($_POST['file_copyright_'.$lang]);
            }
            if(isset($_POST['file_title_'.$lang])) {
                $ftp['file_vars'][$lang]['title'] = clean_slweg($_POST['file_title_'.$lang]);
            }
            if(isset($_POST['file_alt_'.$lang])) {
                $ftp['file_vars'][$lang]['alt'] = clean_slweg($_POST['file_alt_'.$lang]);
            }
        }
    }

    if ($ftp['dir_new']) {
        if ($ftp['dir']) {
            $where = 'f_kid=0 AND f_trash=0 AND f_id=' . $ftp['dir'];
            if(empty($_SESSION["wcs_user_admin"])) {
                $where .= ' AND f_uid='.intval($_SESSION["wcs_user_id"]);
            }
            $target_dir = _dbGet('phpwcms_file', '*', $where, '', '', 1);
        }
        if (isset($target_dir[0]['f_id'])) {
            $dir_new_public = intval($target_dir[0]['f_public']);
            $dir_new_active = intval($target_dir[0]['f_aktiv']);
        } else {
            $ftp['dir'] = 0;
            $dir_new_public = $ftp["public"];
            $dir_new_active = $ftp["aktiv"];
        }
        $data = array(
            'f_pid'			=> $ftp['dir'],
            'f_uid'			=> intval($_SESSION["wcs_user_id"]),
            'f_kid'			=> 0,
            'f_aktiv'		=> $dir_new_active,
            'f_public'		=> $dir_new_public,
            'f_name'		=> $ftp['dir_new'],
            'f_created'		=> now()
        );
        $new_dir = _dbInsert('phpwcms_file', $data);
        if (isset($new_dir['INSERT_ID'])) {
            $ftp['dir'] = intval($new_dir['INSERT_ID']);
        }
    }

?><p><img src="../../img/symbole/rotation.gif" alt="" width="15" height="15"><strong class="title">&nbsp;Selected files will be taken over!</strong></p><?php

    echo '<p class="v10">';
    flush();

    $userftppath    = PHPWCMS_ROOT.$phpwcms["ftp_path"];
    $useruploadpath = PHPWCMS_ROOT.$phpwcms["file_path"];

    foreach($ftp["mark"] as $key => $value) {

        if(function_exists('set_time_limit')) {
            @set_time_limit(240);
        }

        $file = $ftp["file"][$key];
        $file_path = $userftppath.$file;
        if(is_file($file_path)) {

            $file_error["upload"] = 0;
            $file_image_size = null;

            $file_type      = '';
            $file_size      = filesize($file_path);
            if(false === ($file_ext = check_image_extension($file_path, $file, $file_image_size))) {
                $file_ext = which_ext($file);
            }
            $file_name      = sanitize_filename($ftp["filename"][$key]);
            $file_hash      = md5( $file_name . microtime() );
            $file_check     = getimagesize($file_path, $file_image_info);
            $file_title     = $ftp["title"];
            $file_longinfo  = $ftp["long_info"];
            $file_copyright = $ftp["copyright"];
            $file_alt       = $ftp["alt"];
            $file_vars      = $ftp['file_vars'];

            $ftp_varsfield  = '';
            $ftp_varsvalue  = '';

            $file_iptc_info = null;
            $file_ext       = strtolower($file_ext);

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

                // set language specific caption, title, copyright…
                foreach($phpwcms['allowed_lang'] as $lang) {
                    $lang = strtolower($lang);

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

            if(count($file_vars)) {
                $ftp_varsfield = ',f_vars';
                $ftp_varsvalue = ','._dbEscape(serialize($file_vars));
            }

            // Check if SVG and detect related values
            if(empty($file_check[0]) && $file_ext === 'svg' && ($file_svg = @SVGMetadataExtractor::getMetadata($file_path))) {

                $file_type = 'image/svg+xml';
                $file_check = array(
                    0 => $file_svg['width'],
                    1 => $file_svg['height']
                );
                $file_svg = 1;

            } else {

                $file_svg = 0;

                //check file_type
                if(is_mimetype_by_extension($file_ext)) {
                    $file_type = get_mimetype_by_extension($file_ext);
                } else {
                    if(function_exists('image_type_to_mime_type') && isset($file_check[2])) {
                        $file_type = image_type_to_mime_type($file_check[2]);
                    }
                    if(!is_mimetype_format($file_type)) {
                        $file_type = get_mimetype_by_extension($file_ext);
                    }
                }

                if($file_type === '') {
                    $file_type = @mime_content_type($file_path);
                }
            }

            $sql  = "INSERT INTO ".DB_PREPEND."phpwcms_file (";
            $sql .= "f_pid, f_uid, f_kid, f_aktiv, f_public, f_name, f_created, f_size, f_type, f_ext, f_svg, f_image_width, f_image_height, ";
            $sql .= "f_shortinfo, f_longinfo, f_keywords, f_hash, f_copyright, f_tags".$ftp_varsfield.", f_title, f_alt) VALUES (";
            $sql .= $ftp["dir"].", ".intval($_SESSION["wcs_user_id"]).", 1, ".$ftp["aktiv"].", ".$ftp["public"].", ";
            $sql .= _dbEscape($file_name).", '".time()."', "._dbEscape($file_size).", "._dbEscape($file_type).", ";
            $sql .= _dbEscape($file_ext).", ".$file_svg.', '._dbEscape(empty($file_check[0]) ? '' : $file_check[0]).", "._dbEscape(empty($file_check[1]) ? '' : $file_check[1]).", ";
            $sql .= _dbEscape($ftp["short_info"]).", ";
            $sql .= _dbEscape($file_longinfo).", "._dbEscape($ftp["keys"]).", '".$file_hash."', ";
            $sql .= _dbEscape($file_copyright).", "._dbEscape($ftp["tags"]).$ftp_varsvalue.", ";
            $sql .= _dbEscape($file_title).", "._dbEscape($file_alt).")";

            $result = _dbQuery($sql, 'INSERT');

            if(isset($result['INSERT_ID'])) {
                $new_fileId = $result['INSERT_ID']; // set new file ID

                $_file_extension = ($file_ext) ? '.'.$file_ext : '';
                $wcs_newfilename = $file_hash . $_file_extension;

                // changed for using hashed file names
                $usernewfile = $useruploadpath.$wcs_newfilename;
                $oldumask = umask(0);

                if ($dir = @opendir($useruploadpath)) {
                    if(@copy($userftppath.$file, $usernewfile)) {

                        @unlink($userftppath.$file);

                        // store tags
                        _dbSaveCategories($ftp["tags"], 'file', $new_fileId, ',');

                    } else {
                        $file_error["upload"] = "Error while writing file to storage (1).";
                    }
                }
            } elseif(($mysql_error = _dbError())) {

                $file_error["upload"] = 'MySQL Error while insert to DB: '.$mysql_error;

            }

            if(empty($file_error["upload"])) {

                // now try to find 1st file having same named and replace it if related mark is set
                if($ftp["replace"]) {

                    $rsql  = "SELECT * FROM ".DB_PREPEND."phpwcms_file WHERE ";
                    $rsql .= "f_name="._dbEscape($file_name)." AND f_kid=1 ";
                    $rsql .= "AND f_pid=".$ftp["dir"]." AND f_trash=0 AND f_id != ".$new_fileId." LIMIT 1";

                    $rrow = _dbQuery($rsql);

                    if(isset($rrow[0]['f_id'])) {

                        $rrow = $rrow[0];

                        $oldFileID      = $rrow['f_id'];
                        $oldFileHash    = $rrow['f_hash'];
                        $oldFileNewHash = md5( $file_name . microtime() . time() );

                        // now update new file by old file information of same named
                        $nsql  = "UPDATE ".DB_PREPEND."phpwcms_file SET ";
                        $nsql .= "f_refid=".$oldFileID.", f_trash=5, f_size=".$rrow['f_size'].', ';
                        $nsql .= "f_type="._dbEscape($rrow['f_type']).", f_changed=".now().', ';
                        $nsql .= "f_hash="._dbEscape($oldFileNewHash)." WHERE f_id=".$new_fileId;

                        if(_dbQuery($nsql, 'UPDATE')) {

                            // yepp both files are updated in db
                            // now change hash of file storage files
                            rename($useruploadpath.$oldFileHash.$_file_extension, $useruploadpath.$oldFileNewHash.$_file_extension);
                            rename($usernewfile, $useruploadpath.$oldFileHash.$_file_extension);

                            // update file size of old file with new filesize
                            _dbUpdate('phpwcms_file', array('f_type'=>$file_type, 'f_size'=>$file_size, 'f_changed'=>now()), 'f_id='.$oldFileID);

                            // empty temp images directory
                            $thumbnails = returnFileListAsArray(PHPWCMS_THUMB, 'jpg,jpeg,gif,png');
                            if(is_array($thumbnails) && count($thumbnails)) {

                                foreach($thumbnails as $thumbnail) {

                                    @unlink(PHPWCMS_THUMB.$thumbnail['filename']);

                                }
                            }
                        }
                    }
                }

                flush();
                echo $file." [OK!]<br />";

            } else {

                echo $file." (".$file_error["upload"].")<br />";
                _dbQuery("DELETE FROM ".DB_PREPEND."phpwcms_file WHERE f_id=".$new_fileId." AND f_uid=".$_SESSION["wcs_user_id"], 'DELETE');

            }

        } else {
            echo $file." does not exist<br />";
        }
        flush();
    }

    echo "</p>";
}

if(empty($file_error["upload"]) && empty($ftp["error"])) {

    echo "<p class=\"title\"><strong>Every selected file was taken over!</strong></p>";
    echo "<p class='v10'><a href=\"".$ref."\" style=\"font-weight: bold;\">&laquo; Return</a> (if no automatic redirect)</p>\n";
    echo "<script type=\"text/javascript\"> window.location.href = \"".$ref."\"; </script>\n";

} else {

    echo "<p class=\"error\"><strong>Error while file take over!</strong></p>\n";
    if(!empty($file_error["upload"])) {
        echo dumpVar($file_error["upload"], 2);
    }
    echo "<p class='v10'><a href=\"".$ref."\" style=\"font-weight: bold;\">&laquo; Return</a></p>\n";

}

echo "</div></body></html>";

if(isset($oldumask)) {
    umask($oldumask);
}
