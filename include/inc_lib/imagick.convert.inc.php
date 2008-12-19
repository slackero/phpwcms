<?php
/*************************************************************************************
   Copyright notice
   
   (c) 2002-2008 Oliver Georgi (oliver@phpwcms.de) // All rights reserved.
 
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


// ========================================================================================================================

// load external GD image handling class
if(!IMAGICK_ON) {
	/*
	$cfg = array(	'JPEG_QUALITY'    => $phpwcms["jpg_quality"],
					'USE_GD2'         => GD2_ON,
					'USE_COLOR_NAMES' => false
				);
	*/
	include_once(PHPWCMS_ROOT."/include/inc_ext/ss_image/ss_image.class.php");
}

// ========================================================================================================================

function imagick_converting ($imagick) {

	$default = array(	"max_width"		=>	150,
						"max_height"	=>	150,
						"error"			=>	0,
						"image_name"	=>	'',
						"thumb_name"	=>	'',
						"target_ext"	=>	'jpg',
						"image_dir"		=>	PHPWCMS_ROOT.PHPWCMS_FILES,
						"thumb_dir"		=>	PHPWCMS_THUMB,
						'jpg_quality'	=>	85,
						'sharpen_level'	=>	0,
						'density'		=>	72,
						'add_command'	=>	'',
						'crop_image'	=>	false
					);
	
	$imagick = array_merge($default, $imagick);
	unset($default);

	if(!intval($imagick["max_width"])) {
		$imagick["max_width"]	= '';
		$imagick['crop_image']	= false;
	}
	if(!intval($imagick["max_height"])) {
		$imagick["max_height"]	= '';
		$imagick['crop_image']	= false;
	}

	//say that it should be converted
	$resize = true;
	
	// now it is good to check if image still available
	// otherwise use placeholder image "filestorage/image_placeholder.png"
	if(!is_file($imagick["image_dir"].$imagick["image_name"])) {
		$imagick["image_name"] = 'image_placeholder.png';
		$imagick["thumb_name"] = 'temp_'.$imagick["thumb_name"];
	}
	
	// check if it is useful only to copy the image
	$check_image = @getimagesize($imagick["image_dir"].$imagick["image_name"]);

	$channel_check = (isset($check_image['channels']) && $check_image['channels'] == 3) ? true : false;

	if($channel_check && $check_image && ($check_image[2] == 1 || $check_image[2] == 2 || $check_image[2] == 3)) {

		$copy_w = 0;
		$copy_h = 0;
		if($imagick["max_width"]	&& $check_image[0]	<= $imagick["max_width"])	$copy_w = 1;
		if($imagick["max_height"]	&& $check_image[1]	<= $imagick["max_height"])	$copy_h = 1;		
		if(!$imagick["max_width"]	&& $check_image[0]) 							$copy_w = 1;
		if(!$imagick["max_height"]	&& $check_image[1])								$copy_h = 1;

		if(!$imagick['crop_image'] && $copy_w && $copy_h) { // do not copy in case crop is enabled

			$imagick["thumb_name"] .= '.' . which_ext($imagick["image_name"]);
			if( @copy( $imagick["image_dir"].$imagick["image_name"], $imagick["thumb_dir"].$imagick["thumb_name"] ) ) {
				$resize = false;
			}
		}
	}
	
	if($resize) {
	
		if(!ini_get('safe_mode') && function_exists('set_time_limit')) set_time_limit(90);

		if(!$imagick['jpg_quality']) {
			$imagick['jpg_quality'] = 80;
		} elseif ($imagick['jpg_quality'] < 25) {
			$imagick['jpg_quality'] = 25;
		} elseif ($imagick['jpg_quality'] > 100) {
			$imagick['jpg_quality'] = 100;
		}
		
		//Sharpen Level - only ImageMagick: 0, 1, 2, 3, 4, 5 -- 0 = no, 5 = extra sharp
		switch($imagick['sharpen_level']) {
			
			case 1:		$sharp4 = '-sharpen 10 ';
						$sharp5 = '-sharpen 1x10 ';
						break;
			case 2:		$sharp4 = '-sharpen 25 ';
						$sharp5 = '-sharpen 3x10 ';
						break;
			case 3:		$sharp4 = '-sharpen 50 ';
						$sharp5 = '-sharpen 5x10 ';
						break;
			case 4:		$sharp4 = '-sharpen 70 ';
						$sharp5 = '-sharpen 7x10 ';
						break;
			case 5:		$sharp4 = '-sharpen 90 ';
						$sharp5 = '-sharpen 9x10 ';
						break;
			default:	$sharp4 = '';
						$sharp5 = '';
			
		}
		
		$sharpen = '';

		$imagick["thumb_name"] .= '.' . $imagick["target_ext"];
	
		if(IMAGICK_ON) {
			// If ImageMagick should be used
			
			$imagick["command"]  = IMAGICK_PATH."convert ";
			switch($imagick["target_ext"]) {
				case "jpg":	if(IMAGICK_ON == 1) {
								//ImageMagick >= 5
								$imagick["command"] .= "-colorspace RGB -type TrueColor ";
								
								$sharpen = $sharp5;
								
							} else {
								//ImageMagick 4.2.9
								$imagick["command"] .= "-colorspace RGB -colors 16777216 ";
								
								$sharpen = $sharp4;
								
							}
							$imagick["source_image_name"] = $imagick["image_dir"].$imagick["image_name"].'[0]';
							break;
				case "gif":	if(IMAGICK_ON == 1) {
								//ImageMagick >= 5
								$imagick["command"] .= "-colors 256 ";
							} else {
								//ImageMagick 4.2.9
								$imagick["command"] .= "-colorspace Transparent -colors 128 ";
							}
							$imagick["source_image_name"] = $imagick["image_dir"].$imagick["image_name"];
							break;
				case "png":	//$imagick["command"] .= "-colors 128 ";
							$imagick["command"] .= "-colorspace RGB ";
							$imagick["source_image_name"] = $imagick["image_dir"].$imagick["image_name"];
							break;
			}
			
			if($imagick['crop_image'] && $imagick["max_width"] && $imagick["max_height"]) {
	
				$resize_factor = 2 * ( $imagick["max_width"] > $imagick["max_height"] ? $imagick["max_width"] : $imagick["max_height"] );
				
				$imagick["command"] .= '-resize "x'.$resize_factor.'" -resize "'.$resize_factor.'x<" -resize 50% ';
				$imagick["command"] .= '-gravity center -crop '.$imagick["max_width"].'x'.$imagick["max_height"].'+0+0 ';
				$imagick["command"] .= '+repage ';
			
			} elseif( $imagick["max_width"] || $imagick["max_height"] ) {

				// resize
				$imagick["command"] .= '-resize "'.$imagick["max_width"].'x'.$imagick["max_height"].'>" ';
			
			}
			

			
			// quality level
			$imagick["command"] .= "-quality ".$imagick['jpg_quality']." ";
			
			// density
			$imagick["command"] .= "-density ".$imagick['density']."x".$imagick['density']." ";
			
			// additional command
			if($imagick['add_command']) {
				$imagick["command"] .= $imagick['add_command'] . " ";
			}
		
			$imagick["command"] .= '-antialias ';
			$imagick["command"] .= $sharpen;
			$imagick['command'] .= '"'.$imagick["source_image_name"].'" ';
			//$imagick["command"] .= '+profile "*" ';
			$imagick["command"] .= '"'.$imagick["thumb_dir"].$imagick["thumb_name"].'" ';
			
			// debug commands
			//write_textfile(PHPWCMS_TEMP.'imagemagick.log', date('Y-m-d H:i:s').' - '.$imagick["command"].LF, 'a');
			
			@exec($imagick["command"], $imagick_return);
			
			if (isset($imagick_return[0])) {
				$imagick["error"] = $imagick_return[0];
			}
		
		} else {
			// use GD function
			if($check_image) {
			
				// try to handle limited PHP memory
				$php_memory = getBytes( @ini_get('memory_limit') );
				$img_memory = getRealImageSize( $check_image );
				
				// do memory checks only when PHP's memory limit 
				// and "real" image size is known
				if(empty($GLOBALS['phpwcms']['gd_memcheck_off']) && $php_memory && $img_memory) {
					
					// in general we need around twice the memory for 
					// successful GD resizing - so lets halve it
					// and compare against the RAM the image will need
					if($php_memory / 2 < $img_memory) {
					
						$imagick["image_name"] = 'image_memoryinfo.png';
						$imagick["thumb_name"] = 'mem_'.$imagick["thumb_name"];
					
					}
				
				}

				$image = new ss_image($imagick["image_dir"].$imagick["image_name"], 'f'); //given original image
				$image->set_parameter($imagick["jpg_quality"], GD2_ON, false);
		
				if($imagick['crop_image']) {
				
					$image->set_size($imagick["max_width"], $imagick["max_height"], '+');
					$image->commit();
					
					$crop_x = abs( $image->get_w() - $imagick["max_width"] );
					$crop_x = ceil( $crop_x / 2 );
					$crop_y = abs( $image->get_h() - $imagick["max_height"] );
					$crop_y = ceil( $crop_y / 2 );
					
					$image->crop($crop_x, $crop_y, $imagick["max_width"], $imagick["max_height"]);					
					
				} else {

					if($imagick["max_width"] && $check_image[0] < $imagick["max_width"]) $imagick["max_width"] = $check_image[0];
					if($imagick["max_height"] && $check_image[1] < $imagick["max_height"]) $imagick["max_height"] = $check_image[1];		
					if(!$imagick["max_width"]) $imagick["max_width"] = "*";
					if(!$imagick["max_height"]) $imagick["max_height"] = "*";
					
					$image->set_size($imagick["max_width"], $imagick["max_height"], '--');

				}
					
				/*
				if($imagick['sharpen_level']) {
					$image->commit();
					$image->unsharp(80, 0.5, 3);
				}
				*/
				$image->output($imagick["thumb_dir"].$imagick["thumb_name"], 'c', strtoupper($imagick["target_ext"]));
				if(!is_file($imagick["thumb_dir"].$imagick["thumb_name"])) {
					$imagick["error"] = "GD image creation failed.";
				}
			} else {
			 	$imagick["error"] = "GD source image error. Maybe not exists.";
			}
		}
		
	}
	return $imagick;
}

// ========================================================================================================================


// build thumbnail image name
function get_cached_image($val, $db_track=true, $return_all_imageinfo=true) {
	
	// predefine values
	$default = array(
		"max_width"		=>	$GLOBALS['phpwcms']["img_list_width"],
		"max_height"	=>	$GLOBALS['phpwcms']["img_list_height"],
		"image_dir"		=>	PHPWCMS_ROOT . '/' . PHPWCMS_FILES,
		"thumb_dir"		=>	PHPWCMS_ROOT . '/' . PHPWCMS_IMAGES,
		'jpg_quality'	=>	$GLOBALS['phpwcms']['jpg_quality'],
		'sharpen_level'	=>	$GLOBALS['phpwcms']['sharpen_level'],
		'crop_image'	=>	false
		);
	
	$val = array_merge($default, $val);
	
	$imgCache = false; //do not insert file information in db image cache
	$thumb_image_info = array();
	$thumb_image_info[0] = false; // Thumb Image
	$image_hash = substr($val['image_name'], 0, (strlen($val['target_ext']) * -1) - 1);

	// now check if thumbnail was created - proof for GIF, PNG, JPG
	
	$thumb_check = $val['thumb_dir'] . $val['thumb_name'];
	
	if(is_file($thumb_check .'.jpg')) {
		
		$thumb_image_info[0] = $val['thumb_name'] .'.jpg';
		
	} elseif(is_file($thumb_check .'.png')) {
		
		$thumb_image_info[0] = $val['thumb_name'] .'.png';
	
	} elseif(is_file($thumb_check .'.gif')) {
		
		$thumb_image_info[0] = $val['thumb_name'] .'.gif';
	
	} else {

		// check if current file's extension is handable by ImageMagick or GD
		if( $val["target_ext"] = is_ext_true($val["target_ext"]) ) {	

			$create_preview	= imagick_converting( $val );

			if( is_file( $val['thumb_dir'] . $create_preview["thumb_name"] ) ) {
				$thumb_image_info[0] = $create_preview["thumb_name"];
				$imgCache = true; // insert/update information in db image cache
			};

		}
	}
	
	if($thumb_image_info[0] != false) {
	
		if($return_all_imageinfo === false) {
			return $thumb_image_info;
		}
		
		$thumb_info = @getimagesize($val['thumb_dir'].$thumb_image_info[0]);
		if(is_array($thumb_info)) {
				
			$thumb_image_info[1] = $thumb_info[0]; // width
			$thumb_image_info[2] = $thumb_info[1]; // height
			$thumb_image_info[3] = $thumb_info[3]; // HTML width & height attribute

			// now update image caching information in db
			if($imgCache && $db_track) {
			
				if(!function_exists('_dbQuery')) {
					require_once(PHPWCMS_ROOT.'/include/inc_lib/dbcon.inc.php');
				}
			
				$sql  = "INSERT INTO ".DB_PREPEND."phpwcms_imgcache SET ";
				$sql .= "imgcache_hash = '" . 		aporeplace($image_hash) 			. "', ";
				$sql .= "imgcache_imgname = '" . 	aporeplace($thumb_image_info[0]) 	. "', ";
				$sql .= "imgcache_width = " . 		intval($thumb_image_info[1]) 		. " , ";
				$sql .= "imgcache_height = " . 		intval($thumb_image_info[2]) 		. " , ";
				$sql .= "imgcache_wh = '" . 		aporeplace($thumb_image_info[3]) 	. "'";
				@_dbQuery($sql, 'INSERT');
			}
		
		} else {
		
			// if wrong - no result, return false
			return false;
		
		}
		
	} else {
		// if wrong - no result, return false
		return false;
	}

	// Return cached thumbnail image info
	// $thumb_image_info[0] = Name, 
	// $thumb_image_info[1] = width, 
	// $thumb_image_info[2] = height, 
	// $thumb_image_info[3] = HTML width & height attribute
	return $thumb_image_info;
}

?>