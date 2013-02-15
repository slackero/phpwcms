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

// ----------------------------------------------------------------
// obligate check for phpwcms constants
if (!defined('PHPWCMS_ROOT')) {
   die("You Cannot Access This Script Directly, Have a Nice Day.");
}
// ----------------------------------------------------------------


// These are all kind of deprecated replacer and function 
// might kicked off the system in the near future
// If you need these, please use config setting
// $phpwcms['enable_deprecated'] = true;

function get_random_image_tag($path) {
	// returns an random image from the give path
	// it looks for image of following type: gif, jpg, jpeg, png
	// {RANDOM:path} willl return <img src="path/rand_image" />
	// {RANDOM:SRC:path} willl return absolute URI PHPWCMS_URL/path/rand_image

	$imgArray	= array();
	$path		= trim($path);
	if(strtoupper(substr($path, 0, 4)) == 'SRC:') {
		$tag	= false;
		$path	= trim(substr($path, 4));
	} else {
		$tag	= true;
	}
	
	$path		= trim($path, '/');
	$imgpath	= PHPWCMS_ROOT . '/' . $path . '/';
	$imageinfo	= false;

	if(is_dir($imgpath)) {
		$handle = opendir( $imgpath );
		while($file = readdir( $handle )) {
   			if( $file{0} != '.' && preg_match('/(\.jpg|\.jpeg|\.gif|\.png)$/i', $file)) {
				$imgArray[] = $file;
			}
		}
		closedir( $handle );
	}

	if(count($imgArray) && ($imageinfo = is_random_image($imgArray, $imgpath))) {
		if($tag) {
			return '<img src="'.$path.'/'.urlencode($imageinfo['imagename']).'" '.$imageinfo[3].' border="0" alt="'.html_specialchars($imageinfo["imagename"]).'"'.HTML_TAG_CLOSE;
		} else {
			return PHPWCMS_URL . $path . '/' . urlencode($imageinfo['imagename']);
		}
	}

	return '';
}

function is_random_image($imgArray, $imagepath, $count=0) {
	// tests if the random choosed image is really an image
	$count++;
	$randval = mt_rand( 0, count( $imgArray ) - 1 );
	$file = $imagepath.$imgArray[ $randval ];
	$imageinfo = @getimagesize($file);
	//if $imageinfo is not true repeat function and count smaller count all images
	if(!$imageinfo && $count < count($imgArray)) {
		$imageinfo = is_random_image($imgArray, $imagepath, $count);
	} else {
		$imageinfo["imagename"] = $imgArray[ $randval ];
	}
	return $imageinfo;
}

function html_parser_deprecated($string='') {
	
	$search			= array();
	$replace		= array();
	
	// random GIF Image
	$search[0]		= '/\{RANDOM_GIF:(.*?)\}/';
	$replace[0]		= '<img src="img/random_image.php?type=0&imgdir=$1" border="0" alt="" />';

	// random JPEG Image
	$search[1]		= '/\{RANDOM_JPEG:(.*?)\}/';
	$replace[1]		= '<img src="img/random_image.php?type=1&amp;imgdir=$1" border="0" alt="" />';

	// random PNG Image
	$search[2]		= '/\{RANDOM_PNG:(.*?)\}/';
	$replace[2]		= '<img src="img/random_image.php?type=2&amp;imgdir=$1" border="0" alt="" />';

	// insert non db image standard
	$search[3]		= '/\{IMAGE:(.*?)\}/';
	$replace[3]		= '<img src="picture/$1" border="0" alt="" />';

	// insert non db image left
	$search[4]		= '/\{IMAGE_LEFT:(.*?)\}/';
	$replace[4]		= '<img src="picture/$1" border="0" align="left" alt="" />';

	// insert non db image right
	$search[5]		= '/\{IMAGE_RIGHT:(.*?)\}/';
	$replace[5]		= '<img src="picture/$1" border="0" align="right" alt="" />';

	// insert non db image center
	$search[6]		= '/\{IMAGE_CENTER:(.*?)\}/';
	$replace[6]		= '<div align="center"><img src="picture/$1" border="0" alt="" /></div>';
	
	// random Image Tag
	$search[7]		= '/\{RANDOM:(.*?)\}/e';
	$replace[7]	= 'get_random_image_tag("$1");';

	
	return preg_replace($search, $replace, $string);

}


?>