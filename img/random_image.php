<?php
/**
 * phpwcms content management system
 *
 * @author Oliver Georgi <oliver@phpwcms.de>
 * @copyright Copyright (c) 2002-2014, Oliver Georgi
 * @license http://opensource.org/licenses/GPL-2.0 GNU GPL-2
 * @link http://www.phpwcms.de
 *
 **/

$phpwcms = array();
require_once ('../config/phpwcms/conf.inc.php');
require_once ('../include/inc_lib/default.inc.php');


//Random Pic send to browser
$imgpath = trim($_GET["imgdir"]);
$imgArray = array();


$root_path = PHPWCMS_ROOT;
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
   			if( $file != "." && $file != ".." && preg_match('/(\.jpg|\.jpeg|\.png|\.gif)$/', strtolower($file)) ) 
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
		case 1: header("Content-Type: image/gif"); break;
		case 2: header("Content-Type: image/jpeg"); break;
		case 3: header("Content-Type: image/png"); break;
		default: header("Content-Type: image/gif");
	}

	@readfile($file);

} else {
	die('Error reading image');
}

?>