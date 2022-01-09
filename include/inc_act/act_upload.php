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
require_once '../../include/config/conf.inc.php';
require_once '../inc_lib/default.inc.php';
require_once PHPWCMS_ROOT.'/include/inc_lib/helper.session.php';

if(empty($_SESSION["wcs_user_id"]) || !validate_csrf_get_token()) {
	die('{"success":false}');
}

require PHPWCMS_ROOT.'/include/inc_lib/general.inc.php';
require PHPWCMS_ROOT.'/include/inc_js/uploader/fileuploader.php';

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

$uploader	= new qqFileUploader($phpwcms['allowed_upload_ext'], min($post_max_size, $upload_max_filesize, $phpwcms['file_maxsize']));
$uploadDir	= PHPWCMS_ROOT.$phpwcms["ftp_path"];

// Call handleUpload() with the name of the folder, relative to PHP's getcwd()
$result = $uploader->handleUpload($uploadDir, NULL, TRUE, FALSE);

$result['filename']	= $uploader->getUploadName();

if(!empty($result['success']) && !empty($_GET['file_public'])) {

	require_once PHPWCMS_ROOT.'/include/inc_lib/dbcon.inc.php';

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

	if(PHPWCMS_CHARSET != 'utf-8') {
		$data['f_name']			= makeCharsetConversion($data['f_name'], 'utf-8', PHPWCMS_CHARSET);
		$data['f_longinfo']		= makeCharsetConversion($data['f_longinfo'], 'utf-8', PHPWCMS_CHARSET);
		$data['f_copyright']	= makeCharsetConversion($data['f_copyright'], 'utf-8', PHPWCMS_CHARSET);
		$data['f_tags']			= makeCharsetConversion($data['f_tags'], 'utf-8', PHPWCMS_CHARSET);
	}

	$userftppath = PHPWCMS_ROOT.$phpwcms["ftp_path"];

	// Try to detect image data
    if($file_image_size = getimagesize($userftppath.$result['filename'])) {

        $data['f_image_width'] = $file_image_size[0];
        $data['f_image_height'] = $file_image_size[1];

    } elseif($data['f_ext'] === 'svg') {

        require_once PHPWCMS_ROOT.'/include/inc_lib/classes/class.svg-reader.php';

        if($file_svg = @SVGMetadataExtractor::getMetadata($userftppath.$result['filename'])) {
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

		if($dir = @opendir($useruploadpath) && @copy($userftppath.$result['filename'], $usernewfile)) {

			@unlink($userftppath.$result['filename']);

		} else {

			require PHPWCMS_ROOT.'/include/inc_lang/backend/en/lang.inc.php';
			$cust_lang = PHPWCMS_ROOT.'/include/inc_lang/backend/' . strtolower(substr($_SESSION["wcs_user_lang"], 0, 2)) . '/lang.inc.php';
			if(is_file($cust_lang)) {
				include $cust_lang;
			}

			$result['success'] = false;
			$result['error'] = $BL['be_error_while_save'];

			_dbQuery('DELETE FROM '.DB_PREPEND.'phpwcms_file WHERE f_id='._dbEscape($insert['INSERT_ID']));

		}

		if(!empty($dir) && !is_bool($dir)) {
			@closedir($dir);
		}

	}

}

// to pass data through iframe you will need to encode all html tags
echo htmlspecialchars(json_encode($result), ENT_NOQUOTES);
