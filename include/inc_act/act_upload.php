<?php
/**
 * cmsGO!
 *
 * @author Pixels & Points GmbH <info@pixels-points.ch>
 * @copyright Copyright (c) 2002-2019, Pixels & Points GmbH
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
require CMSGO_ROOT.'/include/inc_js/uploader/fileuploader.php';

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

$uploader	= new qqFileUploader($cmsgo['allowed_upload_ext'], min($post_max_size, $upload_max_filesize, $cmsgo['file_maxsize']));
$uploadDir	= CMSGO_ROOT.$cmsgo["ftp_path"];

// Call handleUpload() with the name of the folder, relative to PHP's getcwd()
$result = $uploader->handleUpload($uploadDir, NULL, TRUE, FALSE);

$result['filename']	= $uploader->getUploadName();

if(!empty($result['success']) && !empty($_GET['file_public'])) {

	require_once CMSGO_ROOT.'/include/inc_lib/dbcon.inc.php';

	$data = array(
		'f_pid'			=> intval($_GET['file_dir']),
		'f_uid'			=> intval($_SESSION["wcs_user_id"]),
		'f_kid'			=> 1,
		'f_aktiv'		=> 1,
		'f_public'		=> 1,
		'f_name'		=> $result['filename'],
		'f_created'		=> now(),
		'f_size'		=> $uploader->getFileSize(),
		'f_type'		=> $uploader->getFileType(),
		'f_ext'			=> strtolower($uploader->getFileExtension()),
		'f_svg'         => 0,
		'f_longinfo'	=> slweg($_GET['file_longinfo']),
		'f_hash'		=> md5( $result['filename'] . microtime() ),
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
    if($file_image_size = getimagesize($userftppath.$result['filename'])) {

        $data['f_image_width'] = $file_image_size[0];
        $data['f_image_height'] = $file_image_size[1];

    } elseif($data['f_ext'] === 'svg') {

        require_once CMSGO_ROOT.'/include/inc_lib/classes/class.svg-reader.php';

        if($file_svg = @SVGMetadataExtractor::getMetadata($userftppath.$result['filename'])) {;
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

		if($dir = @opendir($useruploadpath) && @copy($userftppath.$result['filename'], $usernewfile)) {

			@unlink($userftppath.$result['filename']);

		} else {

			require CMSGO_ROOT.'/include/inc_lang/backend/en/lang.inc.php';
			$cust_lang = CMSGO_ROOT.'/include/inc_lang/backend/' . strtolower(substr($_SESSION["wcs_user_lang"], 0, 2)) . '/lang.inc.php';
			if(is_file($cust_lang)) {
				include $cust_lang;
			}

			$result['success'] = false;
			$result['error'] = $BL['be_error_while_save'];

			_dbQuery('DELETE FROM '.DB_PREPEND.'cmsgo_file WHERE f_id='._dbEscape($insert['INSERT_ID']));

		}

		if(!empty($dir)) {
			@closedir($dir);
		}

	}

}

// to pass data through iframe you will need to encode all html tags
echo htmlspecialchars(json_encode($result), ENT_NOQUOTES);
