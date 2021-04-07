<?php
/**
 * cmsGO!
 *
 * @author Pixels & Points GmbH <info@pixels-points.ch>
 * @copyright Copyright (c) 2002-2020, Pixels & Points GmbH
 * @license https://www.pixels-points.ch/cmsgo-license.html Pixels & Points cmsGO! license
 *
 **/

$cmsgo = array();
require_once '../include/config/conf.inc.php';
require_once '../include/inc_lib/default.inc.php';

//Random Pic send to browser
$imgpath = trim($_GET["imgdir"]);
$imgArray = array();


$root_path = CMSGO_ROOT;
if(!$root_path) {
	$root_path = preg_replace('/(.*)([\/|\\\].(.*)){2}/', '$1', $_SERVER['PATH_TRANSLATED']);
}

if($imgpath) {

	$imgpath = str_replace("\\", "/", $root_path."/".$imgpath."/");
	$imgpath = str_replace("//", "/", $imgpath );
	$imgpath = str_replace("../", "/", $imgpath );
	$imgpath = str_replace("//", "/", $imgpath );

	if(is_dir($imgpath)) {
		$handle = opendir( $imgpath );
		while($file = readdir( $handle )) {
   			if(substr($file, 0, 1) !== '.' && is_file($imgpath.$file) && preg_match('/(\.jpg|\.jpeg|\.png|\.gif)$/i', $file) )
				$imgArray[] = $file;
		}
		closedir( $handle );
	}
}

$file = dirname(__FILE__)."/leer.gif";
if(is_array($imgArray) && sizeof($imgArray)) {
	mt_srand( (double)microtime( ) * 1000000 );
	$randval = mt_rand( 0, sizeof( $imgArray ) - 1 );
	$file = $imgpath.$imgArray[ $randval ];
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
