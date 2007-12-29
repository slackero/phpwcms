<?php
/*************************************************************************************
   Copyright notice
   
   (c) 2002-2007 Oliver Georgi (oliver@phpwcms.de) // All rights reserved.
 
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

$phpwcms = array();
require_once('../../../config/phpwcms/conf.inc.php');
require_once('../../../include/inc_lib/default.inc.php');

$img_file		= (isset($_GET['i'])) ? rawurldecode($_GET['i']) : '';
$img_quality	= (isset($_GET['q']) && intval($_GET['q']) <= 100 && intval($_GET['q'])) ? intval($_GET['q']) : 85;
$img_info		= getimagesize(PHPWCMS_TEMPLATE.'inc_cntpart/map/map_img/'.$img_file);

$img_val		= (isset($_GET['v'])) ? explode(',', $_GET['v']) : array(1,7,7,'FFFFFF','FF4000');
$img_val[0]		= intval($img_val[0]); // border in px
$img_val[1]		= empty($img_val[1]) ? 0 : intval($img_val[1]); // width in px
$img_val[2]		= empty($img_val[2]) ? 0 : intval($img_val[2]); // height in px
$img_val[3]		= empty($img_val[3]) ? hex2rgb('FFFFFF') : hex2rgb($img_val[3]); // background color array RGB
$img_val[4]		= empty($img_val[4]) ? hex2rgb('FF4000') : hex2rgb($img_val[4]); // fill color array RGB

$img_val[5]		= round($img_val[1]/2); // position -x
$img_val[6]		= round($img_val[2]/2); // position -y

$img_val[7]		= $img_val[1] - (2 * $img_val[0]); // width inner fill
$img_val[8]		= $img_val[2] - (2 * $img_val[0]); // height inner fill



$do = 0;

//print_r($img_info);

if($img_info) {

	$img_file = PHPWCMS_TEMPLATE.'inc_cntpart/map/map_img/'.$img_file;
	
	switch($img_info[2]) {
	
		case 1:	// GIF
				if(function_exists('imagegif')) {
					$img_mimetype = 'image/gif';
					$img_target = 'gif';
				} else {
					$img_mimetype = 'image/png';
					$img_target = 'png';
				}
				$img_source = imagecreatefromgif($img_file);
				$do = 1;
				break;
		
		case 2:	// JPG
				$img_mimetype = 'image/jpeg';
				$img_target = 'jpg';
				$img_source = imagecreatefromjpeg($img_file);
				$do = 1;
				break;
		
		case 3:	// PNG
				$img_mimetype = 'image/png';
				$img_target = 'png';
				$img_source = imagecreatefrompng($img_file);
				$do = 1;
				break;
	
	}
	
	// fill image with points
	$img_point = imagecreate($img_val[1], $img_val[2]);
	$background_color = imagecolorallocate($img_point, $img_val[3]['r'], $img_val[3]['g'], $img_val[3]['b']);
	$fill_color = imagecolorallocate($img_point,  $img_val[4]['r'], $img_val[4]['g'], $img_val[4]['b']);
	imagefilledrectangle($img_point,$img_val[0], $img_val[0], $img_val[7], $img_val[8], $fill_color);
	
	if(isset($_GET['xy'])) {
		$points = explode(',', trim($_GET['xy']));
		if(count($points)) {
			foreach($points as $value) {
				$point = explode('x', $value);
				imagecopymerge($img_source, $img_point, $point[0]-$img_val[5], $point[1]-$img_val[6], 0, 0, $img_val[1], $img_val[2], 100);
			}
		}
	}	
	imagedestroy($img_point);
	
}

header("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); 
header("Last-Modified: ".gmdate("D, d M Y H:i:s")." GMT"); 
header("Cache-Control: no-store, no-cache, must-revalidate"); 
header("Cache-Control: post-check=0, pre-check=0",false); 
header("Pragma: no-cache"); 

if($do) {
	
	header('Content-type: '.$img_mimetype);
	
	switch($img_target) {

		case 'jpg':		imagejpeg($img_source, '', $img_quality);
						break;
	
		case 'png':		imagepng($img_source, '', 9);
						break;
	
		case 'gif':		imagegif($img_source);
						break;

	}
	
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

function hex2rgb($hex) {
  $color = trim(str_replace('#','',$hex));
  $rgb = array('r' => intval(hexdec(substr($color,0,2))),
               'g' => intval(hexdec(substr($color,2,2))),
               'b' => intval(hexdec(substr($color,4,2)))
			   );
  return $rgb;
}

?>