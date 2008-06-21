<?php
// MOD Title: phpwcms Graphical Text MOD 
// MOD Author: Jerome < spam@jerome-gamez.de > (Jerome Gamez) http://jerome-gamez.de/ 
// MOD Description: Adds the possibilty to create dynamic graphical text 
//                  with replacement tags 
// 
// MOD Version: 2.0 


require_once (PHPWCMS_ROOT.'/include/inc_module/mod_graphical_text/inc_lib/functions.general.inc.php');

// Path for caching purposes
define('CACHE_PREFIX', PHPWCMS_ROOT.'/'.$phpwcms["content_path"].'gt/' );

// Path for display
define('DISPLAY_PREFIX', $phpwcms["content_path"].'gt/' );



function hex2dec($hex)
{
	// Converts HEX-values to RGB-arrays
	$color = str_replace('#', '', $hex);
	$ret = array(
		'r' => hexdec(substr($color, 0, 2)),
		'g' => hexdec(substr($color, 2, 2)),
		'b' => hexdec(substr($color, 4, 2))
		);
		
  return $ret;
}


function create_picture ($cachefile, $font, $text, $antialiasing, $size, $fgcolor, $fgtransparency, $bgcolor, $bgtransparency, $line_width, $format, $x=0, $y=0, $h=5)
{
	// Creates a picture in the cache folder
	$fgcolor		= hex2dec($fgcolor);
	$bgcolor		= hex2dec($bgcolor);
	
	$_fval_x = intval($x);
	$_fval_y = intval($y);
	$_fval_h = intval($h);
	
	//$text = decode_entities($text);
		
	// Font properties
	$fontfile = PHPWCMS_ROOT.'/include/inc_module/mod_graphical_text/inc_fonts/'.$font;
	$bbox = imagettfbbox($size, 0, $fontfile, $text);
	//$bbox = better_imagettfbbox($size, 0, $fontfile, $text);
	$font_left=($bbox[0]>$bbox[6])?$bbox[6]:$bbox[0];
	$font_right=($bbox[2]>$bbox[4])?$bbox[2]:$bbox[4];
	
	// Check height with letters like 'pbl' etc. to center the text correctly
	$bbox = imagettfbbox($size, 0, $fontfile, "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890!\"§$%&/()=?");
	//$bbox = better_imagettfbbox($size, 0, $fontfile, "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890!\"§$%&/()=?");
	$font_top=($bbox[1]>$bbox[7])?$bbox[7]:$bbox[1];
	$font_bottom=($bbox[3]>$bbox[5])?$bbox[3]:$bbox[5];
	
	$font_width=$font_right-$font_left;
	$font_height=$font_bottom-$font_top;
		
	$im = imagecreate ($font_width + 5, $font_height + $_fval_h);
	
	if (intval($bgtransparency) == 1 && $format != "jpg") {
		$background = imagecolorallocatealpha($im, $bgcolor['r'], $bgcolor['g'], $bgcolor['b'], 127);
	} else{
		$background = imagecolorallocate($im, $bgcolor['r'], $bgcolor['g'], $bgcolor['b']);
	}	

	if (intval($fgtransparency) == 1 && $format != "jpg") {
		$color = imagecolorallocatealpha($im, $fgcolor['r'], $fgcolor['g'], $fgcolor['b'], 127);
	} else {
		$color = imagecolorallocate($im, $fgcolor['r'], $fgcolor['g'], $fgcolor['b']);
	}
		
	// Check antialiasing
	if (intval($antialiasing) == 0) $color = "-".$color;
	
	
	$im_width = imagesx($im);
	$im_height = imagesy($im);
	
	$font_start_x = (($im_width - $font_width)/2) + $_fval_x; 
	//$font_start_y = (($im_height - $font_weight)/2) + ($size/2);
	$font_start_y = ($im_height/2) + ($size/2) + $_fval_y;
		
	if( ! seems_utf8($text) ) {
		$text = utf8_encode($text);
	}

	imagettftext($im, $size, 0, $font_start_x, $font_start_y, $color, $fontfile, $text);
	
	// Create underline
	for ($i = 0;$i < $line_width; $i++)
	{
		imageline ($im, $font_start_x, $font_height+$i, $font_start_x+$font_width, $font_height+$i, $color);
	}
	
	// Create cached image
	switch ($format)
	{
		case "jpg":
			imagejpeg($im, $cachefile, 100);
			break;
			
		case "gif":
			imagegif($im, $cachefile, 100);
			break;
		
		case "png":
			imagepng($im, $cachefile, 9);
			break;
	}
	imagedestroy($im);
}

function show_picture ($font, $text, $antialiasing, $size, $fgcolor, $fgtransparency, $bgcolor, $bgtransparency, $line_width, $format, $x=0, $y=0, $h=5) 
{
	// This function checks if the image with the above parameters has already been created and cached
	// If so, it creates a direct image link to the cached file (this should solve sooner problems with
	// some older browsers
	
	$md5 = md5($font.$text.$antialiasing.$size.$fgcolor.$fgtransparency.$bgcolor.$bgtransparency.$line_width.$format.$x.$y.$h);
	$cachefile = CACHE_PREFIX . $md5 . '.' . $format;	
	
	if(!file_exists($cachefile)) {
		// Call the fontizer-script to generate the image. This will happen only one time
		create_picture ($cachefile, $font, $text, $antialiasing, $size, $fgcolor, $fgtransparency, $bgcolor, $bgtransparency, $line_width, $format, $x, $y, $h);
	}
	
	$displayfile	= DISPLAY_PREFIX . $md5 . '.' .$format;
	$text			= html_specialchars($text);
	
	$display_image_HxW = @getimagesize($cachefile);
	if($display_image_HxW) {
		$gt_replace  = '<img src="'.$displayfile.'" alt="'.$text.'" title="'.$text.'" '.$display_image_HxW[3].' border="0" />';
	} else {
		$gt_replace .= $text;
	}
	
	return $gt_replace;
}

function get_gt_by_style ($matches) {
	// This functions get the image per style {GT:style}Text{/GT}
	
	$style_name = $matches[1];
	$text		= $matches[2];
	
	global $gt;
	if(empty($gt)) {
		$gt = gt2array($GLOBALS['db']);
	}
	
	$a_href_before 		= '';
	$a_href_end			= '';
	$result				= $text;
	
	
	$style_name = '"'.$style_name.'"';
	$style = isset($gt["styles_name"][$style_name]) ? $gt["styles_name"][$style_name] : false;
		
	if (!empty($style)) {	
		$font			= $gt["fonts_id"][$style["font"]]["filename"];
		$fgcolor		= $gt["colors_id"][$style["fgcolor"]]["value"];
		$bgcolor		= $gt["colors_id"][$style["bgcolor"]]["value"];
		
		if(file_exists(PHPWCMS_ROOT.'/include/inc_module/mod_graphical_text/inc_fonts/'.$font)) {
		
			preg_match('/(<a[^>]*?>)(.*?)(<\/a>)/i', $text, $aparts);
			if(is_array($aparts) && count($aparts)) {
				$a_href_before 		= $aparts[1];
				$text 				= $aparts[2];
				$a_href_end			= $aparts[3];
			}
			$text	= stripslashes($text);
			$text	= str_replace('[br]', "\n", $text);
			$text	= str_replace(array('<br','<BR'), "\n<br", $text);
			$text	= strip_tags($text);
			$text	= decode_entities($text);
			$text	= cleanUpSpecialHtmlEntities($text);

			if(strpos($text, "\n")) {
				$gt_splitted = explode("\n", $text);
			} else {
				$gt_splitted = array($text);
			}
			
			$text = '';
			foreach($gt_splitted as $value) {

				$value = rtrim($value);
				
				if(!empty($value)) {
					$temp = show_picture(	$font, 						$value, 
											$style["antialiasing"],		$style["size"],
											$fgcolor,					$style["fgtransparency"], 
											$bgcolor,					$style["bgtransparency"],
											$style["line_width"],		$style["format"], 
											$style["start_x"],			$style["start_y"],
											$style["height"]
											);
					if($text && $temp) {
						$text .= '<br />';
					}
					$text .= $temp;
				}
			}
			
			if($text) $result = $text;
		}
	
	}
	
	return $a_href_before . $result . $a_href_end;
}

if(isset($content["all"]) && !(strpos($content["all"],'{GT')===false)) {
	// Style
	$content["all"] = preg_replace_callback('/\{GT:(.+?)\}(.*?)\{\/GT\}/is', 'get_gt_by_style', $content["all"]);
}

function better_imagettfbbox($size, $angle, $font, $text) {
	$dummy = imagecreate(1, 1);
	$black = imagecolorallocate($dummy, 0, 0, 0);
	$bbox = imagettftext($dummy, $size, $angle, 0, 0, $black, $font, $text);
	imagedestroy($dummy);
	return $bbox;
}

?>