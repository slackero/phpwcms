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

//Random Pic send to browser
$imgpath = trim($_GET['imgdir']);
$imgArray = [];

if($imgpath) {
    $imgpath = str_replace(["\\", '//', '../', '//'], '/', PHPWCMS_ROOT . '/' . $imgpath . '/');

	if(is_dir($imgpath)) {
		$handle = opendir( $imgpath );
		while($file = readdir( $handle )) {
   			if(substr($file, 0, 1) !== '.' && is_file($imgpath.$file) && preg_match('/(\.jpg|\.jpeg|\.png|\.gif)$/i', $file) )
				$imgArray[] = $file;
		}
		closedir( $handle );
	}
}

$file = __DIR__ . '/leer.gif';
if(is_array($imgArray) && count($imgArray)) {
	mt_srand((int)(microtime(true) * 1000000));
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
		default: header('Content-Type: image/gif');
	}
	@readfile($file);

	exit();
}

die('Error reading image');
