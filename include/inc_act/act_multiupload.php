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
require_once '../config/conf.inc.php';
require_once '../inc_lib/default.inc.php';
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
if(isset($_FILES["myfile"]))
{
  $ret = array();

//  This is for custom errors;
/*  $custom_error= array();
  $custom_error['jquery-upload-file-error']="File already exists";
  echo json_encode($custom_error);
  die();
*/
  $error = $_FILES["myfile"]["error"];
  if ($error == UPLOAD_ERR_INI_SIZE || $error == UPLOAD_ERR_FORM_SIZE) {
      $max_post = ini_get('upload_max_filesize');
      $errMsg = sprintf($BL['be_fprivup_err10'], $max_post);
      die(json_encode(array('jquery-upload-file-error' => strip_tags($errMsg))));
  }
  //You need to handle  both cases
  //If Any browser does not support serializing of multiple files using FormData()
  if(!is_array($_FILES["myfile"]["name"])) //single file
  {
    $fileName = sanitize_filename($_FILES["myfile"]["name"]);
    $retf[0]["fileName"] = $fileName;
    $retf[0]["fileType"] = $_FILES["myfile"]["type"];
    $retf[0]["fileSize"] = $_FILES["myfile"]["size"];
    $retf[0]["fileExt"] = pathinfo($fileName, PATHINFO_EXTENSION);
    move_uploaded_file($_FILES["myfile"]["tmp_name"],$output_dir.$fileName);
    $ret[]= $fileName;
  }
  else  //Multiple files, file[]
  {
    $fileCount = count($_FILES["myfile"]["name"]);
    for($i=0; $i < $fileCount; $i++)
    {
      $fileName = sanitize_filename($_FILES["myfile"]["name"][$i]);
      $retf[$i]["fileName"] = $fileName;
      $retf[$i]["fileType"] = $_FILES["myfile"]["type"][$i];
      $retf[$i]["fileSize"] = $_FILES["myfile"]["size"][$i];
      $retf[$i]["fileExt"] = pathinfo($fileName, PATHINFO_EXTENSION);
      move_uploaded_file($_FILES["myfile"]["tmp_name"][$i],$output_dir.$fileName);
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
