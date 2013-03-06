<?php
/**
 * phpwcms content management system
 *
 * @author Oliver Georgi <oliver@phpwcms.de>
 * @copyright Copyright (c) 2002-2013, Oliver Georgi
 * @license http://opensource.org/licenses/GPL-2.0 GNU GPL-2
 * @link http://www.phpwcms.de
 *
 **/

session_start();

if(empty($_SESSION["wcs_user_id"])) {

	die('{"success":false}');

}

$phpwcms = array();
require ('../../config/phpwcms/conf.inc.php');
require ('../inc_lib/default.inc.php');
require (PHPWCMS_ROOT.'/include/inc_lib/general.inc.php');
require (PHPWCMS_ROOT.'/include/inc_js/uploader/fileuploader.php');

if(@ini_get('post_max_size')) {
	$post_max_size = return_bytes(ini_get('post_max_size'));
	if($post_max_size < $phpwcms['file_maxsize']) {
		$phpwcms['file_maxsize'] = $post_max_size;
	}
} else {
	$post_max_size = $phpwcms['file_maxsize'];
}
if(@ini_get('upload_max_filesize')) {
	$upload_max_filesize = return_bytes(ini_get('upload_max_filesize'));
	if($upload_max_filesize < $phpwcms['file_maxsize']) {
		$phpwcms['file_maxsize'] = $upload_max_filesize;
	}
} else {
	$upload_max_filesize = $phpwcms['file_maxsize'];
}

$uploader	= new qqFileUploader(array(), min($post_max_size, $upload_max_filesize, $phpwcms['file_maxsize']));
$uploadDir	= PHPWCMS_ROOT.$phpwcms["ftp_path"];

// Call handleUpload() with the name of the folder, relative to PHP's getcwd()
$result = $uploader->handleUpload($uploadDir, NULL, TRUE, FALSE);

$result['filename']	= $uploader->getUploadName();

if($result['success'] && !empty($_GET['file_public'])) {
	
	require_once (PHPWCMS_ROOT.'/include/inc_lib/dbcon.inc.php');

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
	
	$insert = _dbInsert('phpwcms_file', $data);

	// move uploaded file
	if(!empty($insert['INSERT_ID'])) {
		
		$userftppath    = PHPWCMS_ROOT.$phpwcms["ftp_path"];
		$useruploadpath = PHPWCMS_ROOT.$phpwcms["file_path"];
		$usernewfile	= $useruploadpath.$data['f_hash'];
		
		if($data['f_ext']) {
			$usernewfile .= '.'.$data['f_ext'];
		}
		
		$oldmask = umask(0);

		if($dir = @opendir($useruploadpath) && @copy($userftppath.$result['filename'], $usernewfile)) {
		
			@unlink($userftppath.$result['filename']);
		
		} else {
			
			require(PHPWCMS_ROOT.'/include/inc_lang/backend/en/lang.inc.php');
			$cust_lang = PHPWCMS_ROOT.'/include/inc_lang/backend/' . strtolower(substr($_SESSION["wcs_user_lang"], 0, 2)) . '/lang.inc.php';
			if(is_file($cust_lang)) {
				include($cust_lang);
			}
			
			$result['success'] = false;
			$result['error'] = $BL['be_error_while_save'];
			
			_dbQuery('DELETE FROM '.DB_PREPEND.'phpwcms_file WHERE f_id='._dbEscape($insert['INSERT_ID']));
			
		}
		
		if(!empty($dir)) {
			@closedir($dir);
		}
		
	}

}

// to pass data through iframe you will need to encode all html tags
echo html_entities(json_encode($result), ENT_NOQUOTES);

?>