<?php
/*************************************************************************************
   Copyright notice
   
   (c) 2002-2011 Oliver Georgi (oliver@phpwcms.de) // All rights reserved.
 
   This script is part of PHPWCMS. The PHPWCMS web content management system is
   free software; you can redistribute it and/or modify it under the terms of
   the GNU General Public License as published by the Free Software Foundation;
   either version 2 of the License, or (at your option) any later version.
  
   The GNU General Public License can be found at http://www.gnu.org/copyleft/gpl.html
   A copy is found in the textfile GPL.txt and important notices to the license 
   from the author is found in LICENSE.txt distributed with these scripts.
  
   This script is distributed in the hope that it will be useful, but WITHOUT ANY 
   WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A
   PARTICULAR PURPOSE.  See the GNU General Public License for more details.
 
   This copyright notice MUST APPEAR in all copies of the script!
*************************************************************************************/

// <img src="image_resized.php?format=jpg&w=100&h=200&q=85&imgfile=test.jpg" alt="" border="0">

$img_target	= (isset($_GET['format'])) ? strtolower(trim($_GET['format'])) : 'jpg';
$img_file	= (isset($_GET['imgfile'])) ? trim($_GET['imgfile']) : 'img/leer.gif';
$img_width	= (isset($_GET['w'])) ? intval($_GET['w']) : 0;
$img_height	= (isset($_GET['h'])) ? intval($_GET['h']) : 0;
$img_quality= (isset($_GET['q']) && intval($_GET['q']) <= 100 && intval($_GET['q'])) ? intval($_GET['q']) : 75;

$img_file	= str_replace(array('http://', 'https://'), '', $img_file);

switch($img_target) {
	
	case 'png':		$img_mimetype	= 'image/png';
					$img_target		= 'jpg';
					break;
	
	case 'gif':		if(function_exists('imagegif')) {
						$img_mimetype = 'image/gif';
						$img_target   = 'gif';
					} else {
						$img_target   = 'png';
						$img_mimetype = 'image/png';
					}
					break;
	
	case 'jpeg':
	case 'jpg':
	default:		$img_mimetype	= 'image/jpeg';
					$img_target		= 'jpg';

}

if(is_file($img_file) && $img_info = getimagesize($img_file)) {
	
	if(!$img_width || $img_width >= $img_info[0]) {
		$percent_width = 1;
	} else {
		$percent_width = $img_width / $img_info[0];
	}
	
	if(!$img_height || $img_height >= $img_info[1]) {
		$percent_height = 1;
	} else {
		$percent_height = $img_height / $img_info[1];
	}
	
	if($percent_height < $percent_width) {
		$percent = $percent_height;
	} elseif($percent_height > $percent_width) {
		$percent = $percent_width;
	} else {
		$percent = $percent_width;
	}

	
	$img_width	= ($img_info[0] * $percent);
	$img_height	= ($img_info[1] * $percent);
	
	
	switch($img_target) {

		case 'jpg':		$new_img = imagecreatetruecolor($img_width, $img_height);
						break;
	
		case 'png':		$new_img = imagecreatetruecolor($img_width, $img_height);
						break;
	
		case 'gif':		$new_img = imagecreate($img_width, $img_height);
						break;

	}
	
	switch($img_info[2]) {
	
		case 1:	// GIF
				$img_source = imagecreatefromgif($img_file);
				break;
		
		case 2:	// JPG
				$img_source = imagecreatefromjpeg($img_file);
				break;
		
		case 3:	// PNG
				$img_source = imagecreatefrompng($img_file);
				break;
	
	}
	
	imagecopyresized($new_img, $img_source, 0, 0, 0, 0, $img_width, $img_height, $img_info[0], $img_info[1]);
	
	header('Content-type: '.$img_mimetype);
	
	switch($img_target) {

		case 'jpg':		imagejpeg($new_img, '', $img_quality);
						break;
	
		case 'png':		imagepng($new_img, '', 9);
						break;
	
		case 'gif':		imagegif($new_img);
						break;

	}
	
	imagedestroy($new_img);
	imagedestroy($img_source);
	
} else {

	// error / no image
	header ('Content-type: image/png');
	$new_img = imagecreatetruecolor(75, 20);
	$text_color = imagecolorallocate($new_img, 255, 255, 255);
	imagestring($new_img, 1, 5, 5,  "Image Error", $text_color);
	imagepng($new_img, '', 9);
	imagedestroy($new_img);


}

?>