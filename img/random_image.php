<?php
/**
 * phpwcms content management system
 *
 * @author Oliver Georgi <og@phpwcms.org>
 * @copyright Copyright (c) 2002-2026, Oliver Georgi
 * @license http://opensource.org/licenses/GPL-2.0 GNU GPL-2
 * @link http://www.phpwcms.org
 *
 **/

$phpwcms = array();
$base_dir = dirname(__DIR__);
require_once $base_dir . '/include/config/conf.inc.php';
require_once $base_dir . '/include/inc_lib/default.inc.php';

// Random image sent to the browser
$root_path = PHPWCMS_ROOT;
if(!$root_path) {
	$root_path = preg_replace('/(.*)([\/|\\\].(.*)){2}/', '$1', $_SERVER['PATH_TRANSLATED']);
}

$imgpath = phpwcms_image_dir_path(isset($_GET["imgdir"]) ? $_GET["imgdir"] : '', $root_path);
$imgArray = array();

if($imgpath) {

	if(is_dir($imgpath)) {
		$handle = opendir( $imgpath );
		while($file = readdir( $handle )) {
   			if(!str_starts_with($file, '.') && is_file($imgpath.$file) && preg_match('/(\.jpg|\.jpeg|\.png|\.gif)$/i', $file) )
				$imgArray[] = $file;
		}
		closedir( $handle );
	}

	// The archive is private; only files with a public record may be served.
	// This endpoint has no frontend session, so a granted image (f_granted=1)
	// is never selected here; use download.php or a session-aware renderer.
	if($imgArray && phpwcms_image_dir_is_archive($imgpath)) {
		require_once $base_dir . '/include/inc_lib/dbcon.inc.php';
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
			$sql .= 'AND '.phpwcms_archive_visibility_sql(true);
			$records = _dbQuery($sql);
		}
		$imgArray = phpwcms_archive_files_matching_records($imgArray, $records ? $records : array());
	}
}

$file = dirname(__FILE__)."/leer.gif";
if(is_array($imgArray) && sizeof($imgArray)) {
	$randval = array_rand($imgArray, 1);
	$file = $imgpath.$imgArray[$randval];
}

$imageinfo = getimagesize($file);

if($imageinfo != false && isset($imageinfo[2])) {

	switch($imageinfo[2]) {
		//1 = GIF, 2 = JPG, 3 = PNG
		case IMAGETYPE_GIF: header("Content-Type: image/gif"); break;
		case IMAGETYPE_JPEG: header("Content-Type: image/jpeg"); break;
		case IMAGETYPE_PNG: header("Content-Type: image/png"); break;
		case IMAGETYPE_WEBP: header("Content-Type: image/webp"); break;
		default: header("Content-Type: image/gif");
	}

	@readfile($file);
	exit();

} else {
	die('Error reading image');
}
