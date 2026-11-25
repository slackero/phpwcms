<?php
/**
 * phpwcms
 *
 * @author Oliver Georgi <og@phpwcms.org>
 * @copyright Copyright (c) 2002-2026, Oliver Georgi
 * @license http://opensource.org/licenses/GPL-2.0 GNU GPL-2
 *
 **/

$phpwcms = [];
require_once '../include/config/conf.inc.php';
require_once '../include/inc_lib/default.inc.php';

// Random image sent to the browser
$imgpath = phpwcms_image_dir_path($_GET['imgdir'] ?? '');
$imgArray = [];

if($imgpath) {
	if(is_dir($imgpath)) {
		$handle = opendir( $imgpath );
		while($file = readdir( $handle )) {
   			if(substr($file, 0, 1) !== '.' && is_file($imgpath.$file) && preg_match('/(\.jpg|\.jpeg|\.png|\.gif|\.webp|\.avif)$/i', $file) )
				$imgArray[] = $file;
		}
		closedir( $handle );
	}

	// The archive is private; only files with a visible record may be served.
	// A logged-in frontend user may also receive granted images. The session
	// is read only when the request already carries one, so anonymous image
	// requests stay cacheable and cookie-free. The database is loaded only
	// for an archive directory.
	if($imgArray && phpwcms_image_dir_is_archive($imgpath)) {
		$feuser_logged_in = false;
		if(!empty($_COOKIE[session_name()])) {
			$phpwcms['SESSION_START'] = true;
			require_once '../include/inc_lib/helper.session.php';
			$session_key = session_id();
			$feuser_logged_in = $session_key !== ''
				&& !empty($_SESSION[$session_key])
				&& !isset($_GET[$session_key])
				&& !isset($_POST[$session_key]);
			if($feuser_logged_in && function_exists('headerAvoidPageCaching')) {
				headerAvoidPageCaching();
			}
		}
		require_once '../include/inc_lib/dbcon.inc.php';
		$hashes = array();
		foreach($imgArray as $imgfile) {
			$hash = pathinfo($imgfile, PATHINFO_FILENAME);
			if($hash !== '') {
				$hashes[$hash] = _dbEscape($hash);
			}
		}
		$records = array();
		if($hashes) {
			$sql  = 'SELECT f_hash, f_ext FROM '.DB_PREPEND.'file WHERE f_hash IN ('.implode(',', $hashes).') ';
			$sql .= 'AND '.phpwcms_archive_visibility_sql(!$feuser_logged_in);
			$records = _dbQuery($sql);
		}
		$imgArray = phpwcms_archive_files_matching_records($imgArray, $records ? $records : array());
	}
}

$file = __DIR__ . '/leer.gif';
if(is_array($imgArray) && count($imgArray)) {
	$randval = random_int( 0, count( $imgArray ) - 1 );
	$file = $imgpath.$imgArray[ $randval ];
}

$imageinfo = getimagesize($file);

if($imageinfo && isset($imageinfo[2])) {

	switch($imageinfo[2]) {
		//1 = GIF, 2 = JPG, 3 = PNG
		case IMAGETYPE_GIF: header('Content-Type: image/gif'); break;
		case IMAGETYPE_JPEG: header('Content-Type: image/jpeg'); break;
		case IMAGETYPE_PNG: header('Content-Type: image/png'); break;
		case IMAGETYPE_WEBP: header('Content-Type: image/webp'); break;
		case IMAGETYPE_AVIF: header('Content-Type: image/avif'); break;
		default: header('Content-Type: image/gif');
	}
	@readfile($file);

	exit();
}

die('Error reading image');
