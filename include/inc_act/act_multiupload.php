<?php
/**
 * cmsGO!
 *
 * @author Pixels & Points GmbH <info@pixels-points.ch>
 * @copyright Copyright (c) 2002-2020, Pixels & Points GmbH
 * @license https://www.pixels-points.ch/cmsgo-license.html Pixels & Points cmsGO! license
 *
 **/

session_start();

if(empty($_SESSION["wcs_user_id"])) {

  die('{"success":false}');

}

$cmsgo = array();
require '../../include/config/conf.inc.php';
require '../inc_lib/default.inc.php';
require_once CMSGO_ROOT.'/include/inc_lib/helper.session.php';

if(!validate_csrf_get_token('csrftoken')) {
  die('{"success":false}');
}

require CMSGO_ROOT.'/include/inc_lib/general.inc.php';

if(@ini_get('post_max_size')) {
  $post_max_size = return_bytes(ini_get('post_max_size'));
  if($post_max_size < $cmsgo['file_maxsize']) {
    $cmsgo['file_maxsize'] = $post_max_size - 1;
  }
} else {
  $post_max_size = $cmsgo['file_maxsize'];
}
if(@ini_get('upload_max_filesize')) {
  $upload_max_filesize = return_bytes(ini_get('upload_max_filesize'));
  if($upload_max_filesize < $cmsgo['file_maxsize']) {
    $cmsgo['file_maxsize'] = $upload_max_filesize - 1;
  }
} else {
  $upload_max_filesize = $cmsgo['file_maxsize'];
}

if(is_string($cmsgo['allowed_upload_ext'])) {
  $cmsgo['allowed_upload_ext'] = convertStringToArray(strtolower($cmsgo['allowed_upload_ext']));
}
$output_dir = CMSGO_ROOT.$cmsgo["ftp_path"];
if(isset($_FILES["myfile"]))
{
  $ret = array();

//  This is for custom errors;
/*  $custom_error= array();
  $custom_error['jquery-upload-file-error']="File already exists";
  echo json_encode($custom_error);
  die();
*/
  $error =$_FILES["myfile"]["error"];
  //You need to handle  both cases
  //If Any browser does not support serializing of multiple files using FormData()
  if(!is_array($_FILES["myfile"]["name"])) //single file
  {
    $retf[0]["fileName"] = $_FILES["myfile"]["name"];
    $retf[0]["fileType"] = $_FILES["myfile"]["type"];
    $retf[0]["fileSize"] = $_FILES["myfile"]["size"];
    $retf[0]["fileExt"] = pathinfo($_FILES["myfile"]["name"], PATHINFO_EXTENSION);
    move_uploaded_file($_FILES["myfile"]["tmp_name"],$output_dir.$_FILES["myfile"]["name"]);
    $ret[]= $_FILES["myfile"]["name"];
  }
  else  //Multiple files, file[]
  {
    $fileCount = count($_FILES["myfile"]["name"]);
    for($i=0; $i < $fileCount; $i++)
    {
      $retf[$i]["fileName"] = $_FILES["myfile"]["name"][$i];
      $retf[$i]["fileType"] = $_FILES["myfile"]["type"][$i];
      $retf[$i]["fileSize"] = $_FILES["myfile"]["size"][$i];
      $retf[$i]["fileExt"] = pathinfo($_FILES["myfile"]["name"], PATHINFO_EXTENSION);
      move_uploaded_file($_FILES["myfile"]["tmp_name"][$i],$output_dir.$_FILES["myfile"]["name"][$i]);
      $ret[]= $_FILES["myfile"]["name"][$i];

    }

  }
    echo json_encode($ret);
 }



if (!empty($_GET['filepublic'])) {
	require_once CMSGO_ROOT.'/include/inc_lib/dbcon.inc.php';

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

	if(CMSGO_CHARSET != 'utf-8') {
		$data['f_name']			= makeCharsetConversion($data['f_name'], 'utf-8', CMSGO_CHARSET);
		$data['f_longinfo']		= makeCharsetConversion($data['f_longinfo'], 'utf-8', CMSGO_CHARSET);
		$data['f_copyright']	= makeCharsetConversion($data['f_copyright'], 'utf-8', CMSGO_CHARSET);
		$data['f_tags']			= makeCharsetConversion($data['f_tags'], 'utf-8', CMSGO_CHARSET);
	}

	$userftppath = CMSGO_ROOT.$cmsgo["ftp_path"];

	// Try to detect image data
    if($file_image_size = getimagesize($userftppath.$retf[0]["fileName"])) {

        $data['f_image_width'] = $file_image_size[0];
        $data['f_image_height'] = $file_image_size[1];

    } elseif($data['f_ext'] === 'svg') {

        require_once CMSGO_ROOT.'/include/inc_lib/classes/class.svg-reader.php';

        if($file_svg = @SVGMetadataExtractor::getMetadata($userftppath.$retf[0]["fileName"])) {
            $data['f_type'] = 'image/svg+xml';
            $data['f_svg'] = 1;
            $data['f_image_width'] = $file_svg['width'];
            $data['f_image_height'] = $file_svg['height'];
        }

    }

	$insert = _dbInsert('cmsgo_file', $data);

	// move uploaded file
	if(!empty($insert['INSERT_ID'])) {

		$useruploadpath = CMSGO_ROOT.$cmsgo["file_path"];
		$usernewfile	= $useruploadpath.$data['f_hash'];

		if($data['f_ext']) {
			$usernewfile .= '.'.$data['f_ext'];
		}

		$oldmask = umask(0);

		if($dir = @opendir($useruploadpath) && @copy($userftppath.$retf[0]["fileName"], $usernewfile)) {

			@unlink($userftppath.$retf[0]["fileName"]);

		} else {

			require CMSGO_ROOT.'/include/inc_lang/backend/en/lang.inc.php';
			$cust_lang = CMSGO_ROOT.'/include/inc_lang/backend/' . strtolower(substr($_SESSION["wcs_user_lang"], 0, 2)) . '/lang.inc.php';
			if(is_file($cust_lang)) {
				include $cust_lang;
			}

			$retf[0]['success'] = false;
			$retf[0]['error'] = $BL['be_error_while_save'];

			_dbQuery('DELETE FROM '.DB_PREPEND.'cmsgo_file WHERE f_id='._dbEscape($insert['INSERT_ID']));

		}

		if(!empty($dir)) {
			@closedir($dir);
		}

	}

}
