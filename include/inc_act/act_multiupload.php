<?php
/**
 * phpwcms
 *
 * @author Oliver Georgi <og@phpwcms.org>
 * @copyright Copyright (c) 2002-2026, Oliver Georgi
 * @license http://opensource.org/licenses/GPL-2.0 GNU GPL-2
 *
 **/

$phpwcms = array('SESSION_START' => true);
$PHPWCMS_ROOT = dirname(dirname(dirname(__FILE__)));
require_once $PHPWCMS_ROOT.'/include/config/conf.inc.php';
require_once $PHPWCMS_ROOT.'/include/inc_lib/default.inc.php';
require_once PHPWCMS_ROOT.'/include/inc_lib/helper.session.php';
require_once PHPWCMS_ROOT.'/include/inc_lib/dbcon.inc.php';
require_once PHPWCMS_ROOT.'/include/inc_lib/general.inc.php';

$user_lang = !empty($_SESSION["wcs_user_lang"]) ? strtolower(substr($_SESSION["wcs_user_lang"], 0, 2)) : 'en';
require_once PHPWCMS_ROOT.'/include/inc_lang/backend/en/lang.inc.php';
$cust_lang = PHPWCMS_ROOT.'/include/inc_lang/backend/' . $user_lang . '/lang.inc.php';
if(is_file($cust_lang)) {
    include $cust_lang;
}

if(empty($_SESSION["wcs_user_id"]) || !validate_csrf_get_token()) {
    $errMsg = !empty($BL['CSRF_GET_FAILED']) ? strip_tags($BL['CSRF_GET_FAILED']) : 'Session or CSRF failure';
    die(json_encode(array('jquery-upload-file-error' => $errMsg)));
}

// Detect POST Content-Length overflow
if ($_SERVER['REQUEST_METHOD'] === 'POST' && empty($_POST) && empty($_FILES) && isset($_SERVER['CONTENT_LENGTH']) && (int)$_SERVER['CONTENT_LENGTH'] > 0) {
    $max_post = ini_get('post_max_size');
    $errMsg = sprintf($BL['be_fprivup_err10'], $max_post);
    die(json_encode(array('jquery-upload-file-error' => strip_tags($errMsg))));
}

if(@ini_get('post_max_size')) {
  $post_max_size = return_bytes(ini_get('post_max_size'));
  if($post_max_size < $phpwcms['file_maxsize']) {
    $phpwcms['file_maxsize'] = $post_max_size - 1;
  }
} else {
  $post_max_size = $phpwcms['file_maxsize'];
}
if(@ini_get('upload_max_filesize')) {
  $upload_max_filesize = return_bytes(ini_get('upload_max_filesize'));
  if($upload_max_filesize < $phpwcms['file_maxsize']) {
    $phpwcms['file_maxsize'] = $upload_max_filesize - 1;
  }
} else {
  $upload_max_filesize = $phpwcms['file_maxsize'];
}

if(is_string($phpwcms['allowed_upload_ext'])) {
  $phpwcms['allowed_upload_ext'] = convertStringToArray(strtolower($phpwcms['allowed_upload_ext']));
}
$output_dir = PHPWCMS_ROOT.$phpwcms["ftp_path"];
$file_field = isset($_FILES["file"]) ? "file" : (isset($_FILES["filepond"]) ? "filepond" : (isset($_FILES["myfile"]) ? "myfile" : (!empty($_FILES) ? key($_FILES) : null)));

if($file_field && isset($_FILES[$file_field]))
{
  $ret = array();

  $error = $_FILES[$file_field]["error"];
  $charset = defined('PHPWCMS_CHARSET') ? PHPWCMS_CHARSET : (!empty($phpwcms['charset']) ? $phpwcms['charset'] : 'UTF-8');
  if ($error == UPLOAD_ERR_INI_SIZE || $error == UPLOAD_ERR_FORM_SIZE) {
      http_response_code(400);
      header('Content-Type: text/plain; charset=utf-8');
      $max_post = ini_get('upload_max_filesize');
      $tmpl = !empty($BL['be_fprivup_err10']) ? $BL['be_fprivup_err10'] : 'Server limit exceeded: %s';
      $tmpl = html_entity_decode($tmpl, ENT_QUOTES | ENT_HTML5, $charset);
      if (strtolower($charset) !== 'utf-8') { $tmpl = makeCharsetConversion($tmpl, $charset, 'utf-8'); }
      die(sprintf($tmpl, $max_post));
  }
  //You need to handle  both cases
  //If Any browser does not support serializing of multiple files using FormData()
  if(!is_array($_FILES[$file_field]["name"])) //single file
  {
    $fileName = sanitize_filename($_FILES[$file_field]["name"]);
    if (is_file($output_dir . $fileName)) {
        http_response_code(400);
        header('Content-Type: text/plain; charset=utf-8');
        $tmpl = !empty($BL['be_fprivup_err12']) ? $BL['be_fprivup_err12'] : 'File <strong>%s</strong> already exists.';
        $tmpl = html_entity_decode($tmpl, ENT_QUOTES | ENT_HTML5, $charset);
        if (strtolower($charset) !== 'utf-8') { $tmpl = makeCharsetConversion($tmpl, $charset, 'utf-8'); }
        die(sprintf($tmpl, $fileName));
    }
    $retf[0]["fileName"] = $fileName;
    $retf[0]["fileType"] = $_FILES[$file_field]["type"];
    $retf[0]["fileSize"] = $_FILES[$file_field]["size"];
    $retf[0]["fileExt"] = pathinfo($fileName, PATHINFO_EXTENSION);
    move_uploaded_file($_FILES[$file_field]["tmp_name"],$output_dir.$fileName);
    $ret[]= $fileName;
  }
  else  //Multiple files, file[]
  {
    $fileCount = count($_FILES[$file_field]["name"]);
    for($i=0; $i < $fileCount; $i++)
    {
      $fileName = sanitize_filename($_FILES[$file_field]["name"][$i]);
      if (is_file($output_dir . $fileName)) {
          http_response_code(400);
          header('Content-Type: text/plain; charset=utf-8');
          $tmpl = !empty($BL['be_fprivup_err12']) ? $BL['be_fprivup_err12'] : 'File <strong>%s</strong> already exists.';
          $tmpl = html_entity_decode($tmpl, ENT_QUOTES | ENT_HTML5, $charset);
          if (strtolower($charset) !== 'utf-8') { $tmpl = makeCharsetConversion($tmpl, $charset, 'utf-8'); }
          die(sprintf($tmpl, $fileName));
      }
      $retf[$i]["fileName"] = $fileName;
      $retf[$i]["fileType"] = $_FILES[$file_field]["type"][$i];
      $retf[$i]["fileSize"] = $_FILES[$file_field]["size"][$i];
      $retf[$i]["fileExt"] = pathinfo($fileName, PATHINFO_EXTENSION);
      move_uploaded_file($_FILES[$file_field]["tmp_name"][$i],$output_dir.$fileName);
      $ret[]= $fileName;

    }

  }
    echo json_encode($ret);
 }



if (!empty($_GET['filepublic'])) {
	require_once PHPWCMS_ROOT.'/include/inc_lib/dbcon.inc.php';

	$data = array(
		'f_pid'			=> intval($_GET['filedir']),
		'f_uid'			=> intval($_SESSION["wcs_user_id"]),
		'f_kid'			=> 1,
		'f_aktiv'		=> 1,
		'f_public'		=> 1,
		'f_name'		=> $retf[0]["fileName"],
		'f_created'		=> now(),
		'f_size'		=> $retf[0]["fileSize"],
		'f_type'		=> $retf[0]["fileType"],
		'f_ext'			=> strtolower($retf[0]["fileExt"]),
		'f_svg'         => 0,
		'f_longinfo'	=> slweg($_GET['file_longinfo']),
		'f_hash'		=> md5( $retf[0]["fileName"] . microtime() ),
		'f_copyright'	=> slweg($_GET['file_copyright']),
		'f_tags'		=> clean_slweg($_GET['file_tags'])
	);

	if(PHPWCMS_CHARSET != 'utf-8') {
		$data['f_longinfo']		= makeCharsetConversion($data['f_longinfo'], 'utf-8', PHPWCMS_CHARSET);
		$data['f_copyright']	= makeCharsetConversion($data['f_copyright'], 'utf-8', PHPWCMS_CHARSET);
		$data['f_tags']			= makeCharsetConversion($data['f_tags'], 'utf-8', PHPWCMS_CHARSET);
	}

	$userftppath = PHPWCMS_ROOT.$phpwcms["ftp_path"];

	// Try to detect image data
    if($file_image_size = getimagesize($userftppath.$retf[0]["fileName"])) {

        $data['f_image_width'] = $file_image_size[0];
        $data['f_image_height'] = $file_image_size[1];

    } elseif($data['f_ext'] === 'svg') {

        require_once PHPWCMS_ROOT.'/include/inc_lib/classes/class.svg-reader.php';

        if($file_svg = @SVGMetadataExtractor::getMetadata($userftppath.$retf[0]["fileName"])) {
            $data['f_type'] = 'image/svg+xml';
            $data['f_svg'] = 1;
            $data['f_image_width'] = $file_svg['width'];
            $data['f_image_height'] = $file_svg['height'];
        }

    }

	$insert = _dbInsert('phpwcms_file', $data);

	// move uploaded file
	if(!empty($insert['INSERT_ID'])) {

		$useruploadpath = PHPWCMS_ROOT.$phpwcms["file_path"];
		$usernewfile	= $useruploadpath.$data['f_hash'];

		if($data['f_ext']) {
			$usernewfile .= '.'.$data['f_ext'];
		}

		$oldmask = umask(0);

		if($dir = @opendir($useruploadpath) && @copy($userftppath.$retf[0]["fileName"], $usernewfile)) {

			@unlink($userftppath.$retf[0]["fileName"]);

		} else {

			require PHPWCMS_ROOT.'/include/inc_lang/backend/en/lang.inc.php';
			$cust_lang = PHPWCMS_ROOT.'/include/inc_lang/backend/' . strtolower(substr($_SESSION["wcs_user_lang"], 0, 2)) . '/lang.inc.php';
			if(is_file($cust_lang)) {
				include $cust_lang;
			}

			$retf[0]['success'] = false;
			$retf[0]['error'] = $BL['be_error_while_save'];

			_dbQuery('DELETE FROM '.DB_PREPEND.'phpwcms_file WHERE f_id='._dbEscape($insert['INSERT_ID']));

		}

		if(!empty($dir)) {
			@closedir($dir);
		}

	}

}
