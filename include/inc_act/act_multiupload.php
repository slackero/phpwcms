<?php
/**
 * phpwcms
 *
 * @author Oliver Georgi <og@phpwcms.org>
 * @copyright Copyright (c) 2002-2026, Oliver Georgi
 * @license http://opensource.org/licenses/GPL-2.0 GNU GPL-2
 *
 **/

$phpwcms = ['SESSION_START' => true];
$PHPWCMS_ROOT = dirname(__FILE__, 3);
require_once $PHPWCMS_ROOT . '/include/config/conf.inc.php';
require_once $PHPWCMS_ROOT . '/include/inc_lib/default.inc.php';
require_once PHPWCMS_ROOT . '/include/inc_lib/helper.session.php';
require_once PHPWCMS_ROOT . '/include/inc_lib/dbcon.inc.php';
require_once PHPWCMS_ROOT . '/include/inc_lib/general.inc.php';

$user_lang = !empty($_SESSION['wcs_user_lang']) ? strtolower(substr($_SESSION['wcs_user_lang'], 0, 2)) : 'en';
require_once PHPWCMS_ROOT . '/include/inc_lang/backend/en/lang.inc.php';
$cust_lang = PHPWCMS_ROOT . '/include/inc_lang/backend/' . $user_lang . '/lang.inc.php';
if (is_file($cust_lang)) {
    include $cust_lang;
}

if (empty($_SESSION['wcs_user_id']) || !validate_csrf_get_token()) {
    $errMsg = !empty($BL['CSRF_GET_FAILED']) ? strip_tags($BL['CSRF_GET_FAILED']) : 'Session or CSRF failure';
    die(json_encode(['jquery-upload-file-error' => $errMsg]));
}

// Detect POST Content-Length overflow
if ($_SERVER['REQUEST_METHOD'] === 'POST' && empty($_POST) && empty($_FILES) && isset($_SERVER['CONTENT_LENGTH']) && (int)$_SERVER['CONTENT_LENGTH'] > 0) {
    $max_post = ini_get('post_max_size');
    $errMsg = sprintf($BL['be_fprivup_err10'], $max_post);
    die(json_encode(['jquery-upload-file-error' => strip_tags($errMsg)]));
}

if (@ini_get('post_max_size')) {
    $post_max_size = return_bytes(ini_get('post_max_size'));
    if ($post_max_size < $phpwcms['file_maxsize']) {
        $phpwcms['file_maxsize'] = $post_max_size - 1;
    }
} else {
    $post_max_size = $phpwcms['file_maxsize'];
}
if (@ini_get('upload_max_filesize')) {
    $upload_max_filesize = return_bytes(ini_get('upload_max_filesize'));
    if ($upload_max_filesize < $phpwcms['file_maxsize']) {
        $phpwcms['file_maxsize'] = $upload_max_filesize - 1;
    }
} else {
    $upload_max_filesize = $phpwcms['file_maxsize'];
}

if (is_string($phpwcms['allowed_upload_ext'])) {
    $phpwcms['allowed_upload_ext'] = convertStringToArray(strtolower($phpwcms['allowed_upload_ext']));
}

$file_field = !empty($_FILES) ? array_key_first($_FILES) : null;

if ($file_field && isset($_FILES[$file_field])) {
    $ret = [];
    $charset = defined('PHPWCMS_CHARSET') ? PHPWCMS_CHARSET : (!empty($phpwcms['charset']) ? $phpwcms['charset'] : 'UTF-8');

    $is_filebrowser_upload = !empty($_POST['filepublic']);
    $target_dir = (int)($_POST['filedir'] ?? ($_SESSION['imgdir'] ?? 0));

    $ftp_dir = PHPWCMS_ROOT . $phpwcms['ftp_path'];
    $file_dir = PHPWCMS_ROOT . $phpwcms['file_path'];

    if ($is_filebrowser_upload) {
        $file_longinfo = isset($_POST['file_longinfo']) ? slweg($_POST['file_longinfo']) : '';
        $file_copyright = isset($_POST['file_copyright']) ? slweg($_POST['file_copyright']) : '';
        $file_tags = isset($_POST['file_tags']) ? clean_slweg($_POST['file_tags']) : '';

        if (PHPWCMS_CHARSET !== 'utf-8') {
            $file_longinfo = makeCharsetConversion($file_longinfo, 'utf-8', PHPWCMS_CHARSET);
            $file_copyright = makeCharsetConversion($file_copyright, 'utf-8', PHPWCMS_CHARSET);
            $file_tags = makeCharsetConversion($file_tags, 'utf-8', PHPWCMS_CHARSET);
        }
    }

    // Normalize $_FILES into a clean list of files to process
    $filesList = [];
    if (!is_array($_FILES[$file_field]['name'])) {
        $filesList[] = [
            'name' => $_FILES[$file_field]['name'],
            'type' => $_FILES[$file_field]['type'],
            'tmp_name' => $_FILES[$file_field]['tmp_name'],
            'error' => $_FILES[$file_field]['error'],
            'size' => $_FILES[$file_field]['size']
        ];
    } else {
        $fileCount = count($_FILES[$file_field]['name']);
        for ($i = 0; $i < $fileCount; $i++) {
            $filesList[] = [
                'name' => $_FILES[$file_field]['name'][$i],
                'type' => $_FILES[$file_field]['type'][$i],
                'tmp_name' => $_FILES[$file_field]['tmp_name'][$i],
                'error' => $_FILES[$file_field]['error'][$i],
                'size' => $_FILES[$file_field]['size'][$i]
            ];
        }
    }

    foreach ($filesList as $fileItem) {
        if ($fileItem['error'] == UPLOAD_ERR_INI_SIZE || $fileItem['error'] == UPLOAD_ERR_FORM_SIZE) {
            http_response_code(400);
            header('Content-Type: text/plain; charset=utf-8');
            $max_post = ini_get('upload_max_filesize');
            $tmpl = !empty($BL['be_fprivup_err10']) ? $BL['be_fprivup_err10'] : 'Server limit exceeded: %s';
            $tmpl = html_entity_decode($tmpl, ENT_QUOTES | ENT_HTML5, $charset);
            if (strtolower($charset) !== 'utf-8') {
                $tmpl = makeCharsetConversion($tmpl, $charset, 'utf-8');
            }
            die(sprintf($tmpl, $max_post));
        }

        if ($fileItem['error'] !== UPLOAD_ERR_OK || empty($fileItem['tmp_name']) || !is_uploaded_file($fileItem['tmp_name'])) {
            continue;
        }

        $fileName = clean_slweg($fileItem['name']);
        $fileName = sanitize_filename($fileName);
        if (PHPWCMS_CHARSET !== 'utf-8') {
            $fileName = makeCharsetConversion($fileName, 'utf-8', PHPWCMS_CHARSET);
        }
        $fileExt = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

        if (!empty($phpwcms['allowed_upload_ext']) && is_array($phpwcms['allowed_upload_ext']) && !in_array($fileExt, $phpwcms['allowed_upload_ext'])) {
            http_response_code(400);
            header('Content-Type: text/plain; charset=utf-8');
            $tmpl = !empty($BL['be_fileuploader_dictInvalidFileType']) ? $BL['be_fileuploader_dictInvalidFileType'] : 'Invalid file type.';
            die(html_entity_decode(strip_tags($tmpl), ENT_QUOTES | ENT_HTML5, 'UTF-8'));
        }

        if ($is_filebrowser_upload) {
            // Direct upload to file archive storage (phpwcms_file DB + filearchive)
            $fileHash = md5($fileName . microtime());
            $data = [
                'f_pid' => $target_dir,
                'f_uid' => (int)$_SESSION['wcs_user_id'],
                'f_kid' => 1,
                'f_aktiv' => 1,
                'f_public' => 1,
                'f_name' => $fileName,
                'f_created' => now(),
                'f_size' => (int)$fileItem['size'],
                'f_type' => $fileItem['type'],
                'f_ext' => $fileExt,
                'f_svg' => 0,
                'f_longinfo' => $file_longinfo,
                'f_hash' => $fileHash,
                'f_copyright' => $file_copyright,
                'f_tags' => $file_tags
            ];

            // Extract image / SVG dimensions from uploaded tmp file directly
            if ($file_image_size = getimagesize($fileItem['tmp_name'])) {
                $data['f_image_width'] = $file_image_size[0];
                $data['f_image_height'] = $file_image_size[1];
            } elseif ($fileExt === 'svg') {
                require_once PHPWCMS_ROOT . '/include/inc_lib/classes/class.svg-reader.php';
                if ($file_svg = @SVGMetadataExtractor::getMetadata($fileItem['tmp_name'])) {
                    $data['f_type'] = 'image/svg+xml';
                    $data['f_svg'] = 1;
                    $data['f_image_width'] = $file_svg['width'];
                    $data['f_image_height'] = $file_svg['height'];
                }
            }

            $insert = _dbInsert('phpwcms_file', $data);

            if (!empty($insert['INSERT_ID'])) {
                $destFile = $file_dir . $fileHash . ($fileExt !== '' ? '.' . $fileExt : '');
                if (move_uploaded_file($fileItem['tmp_name'], $destFile)) {
                    @chmod($destFile, 0666);
                    if (!empty($data['f_tags'])) {
                        _dbSaveCategories($data['f_tags'], 'file', $insert['INSERT_ID'], ',');
                    }
                    $ret[] = $fileName;
                } else {
                    _dbQuery('DELETE FROM ' . DB_PREPEND . 'phpwcms_file WHERE f_id=' . _dbEscape($insert['INSERT_ID']));
                    http_response_code(500);
                    header('Content-Type: text/plain; charset=utf-8');
                    die($BL['be_error_while_save']);
                }
            }
        } else {
            // FTP takeover upload: move directly to temporary FTP takeover dir
            $ftpFileName = sanitize_filename(clean_slweg($fileItem['name']));
            if (is_file($ftp_dir . $ftpFileName)) {
                http_response_code(400);
                header('Content-Type: text/plain; charset=utf-8');
                $tmpl = !empty($BL['be_fprivup_err12']) ? $BL['be_fprivup_err12'] : 'File <strong>%s</strong> already exists.';
                $tmpl = html_entity_decode($tmpl, ENT_QUOTES | ENT_HTML5, $charset);
                if (strtolower($charset) !== 'utf-8') {
                    $tmpl = makeCharsetConversion($tmpl, $charset, 'utf-8');
                }
                die(sprintf($tmpl, $ftpFileName));
            }

            if (move_uploaded_file($fileItem['tmp_name'], $ftp_dir . $ftpFileName)) {
                $ret[] = $ftpFileName;
            } else {
                http_response_code(500);
                header('Content-Type: text/plain; charset=utf-8');
                die($BL['be_error_while_save']);
            }
        }
    }

    echo json_encode($ret);
}
