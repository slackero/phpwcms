<?php
/**
 * phpwcms content management system
 *
 * @author Oliver Georgi <oliver@phpwcms.de>
 * @copyright Copyright (c) 2002-2012, Oliver Georgi
 * @license http://opensource.org/licenses/GPL-2.0 GNU GPL-2
 * @link http://www.phpwcms.de
 *
 **/


$phpwcms	= array();
$root		= rtrim(str_replace('\\', '/', realpath(dirname(__FILE__).'/../') ), '/').'/';
require($root.'/config/phpwcms/conf.inc.php');
require($root.'/include/inc_lib/default.inc.php');
require($root.'/include/inc_lib/general.inc.php');
require($root.'/include/inc_lib/imagick.convert.inc.php');

// get segments: cmsimage.php/%WIDTH%x%HEIGHT%x%CROP%x%QUALITY%/%HASH%.%EXT%
// by default this should be enough: cmsimage.php/%WIDTH%x%HEIGHT/%HASH%.%EXT%
$request_uri		= isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : $_SERVER['PHP_SELF'];

// strip out PHPSESSNAME=...
if(session_id() && session_name()) {
	// session expected at the end of REQUEST URI when added by PHP
	$session_name_pos = strpos($request_uri, session_name().'=');
	if($session_name_pos !== FALSE) {
		$request_uri = trim(trim(mb_substr($request_uri, 0, $session_name_pos), '&'), '?');
	}
}

$query_separator	= strpos($request_uri, 'cmsimage.php?') !== FALSE ? '?' : '/';
$data				= explode('cmsimage.php'.$query_separator, $request_uri, 2);
if(isset($data[1])) { // && !preg_match('/[^a-fgijpnxA-FGIJPN0-9\/\.]/', $data[1])

	$data = explode('/', $data[1]);

	// first check hashed data
	if(isset($data[1])) {
	
		$data[0]	= preg_replace('/[^0-9x]/', '', $data[0]);
		$data[1]	= preg_replace('/[^a-fgijpn0-9\.]/i', '', $data[1]);

		$value		= array();

		$hash		= cut_ext($data[1]);
		$ext		= which_ext($data[1]);
			
		if(is_numeric($hash)) {
			
			@session_start();
			$file_public = empty($_SESSION["wcs_user_id"]) ? 'f_public=1' : '(f_public=1 OR f_uid='.intval($_SESSION["wcs_user_id"]).')';
			
			require_once(PHPWCMS_ROOT.'/include/inc_lib/dbcon.inc.php');
		
			$sql   = 'SELECT f_hash, f_ext FROM '.DB_PREPEND.'phpwcms_file WHERE ';
			$sql  .= 'f_id='.intval($hash)." AND ";
			if(substr($phpwcms['image_library'], 0, 2) == 'gd') {
				$sql .= "f_ext IN ('jpg','jpeg','png','gif','bmp') AND ";
			}
			$sql  .= 'f_trash=0 AND f_aktiv=1 AND '.$file_public;
			$hash  = _dbQuery($sql);
			if(isset($hash[0]['f_hash'])) {
				$ext  = $hash[0]['f_ext'];
				$hash = $hash[0]['f_hash'];
			} else {
				$hash = '';
				$ext  = '';
			}
			
		} elseif($hash && strlen($hash) == 32 && $ext && !is_file(PHPWCMS_ROOT.'/'.PHPWCMS_FILES.$hash.'.'.$ext)) {
			
			@session_start();
			$file_public = empty($_SESSION["wcs_user_id"]) ? 'f_public=1' : '(f_public=1 OR f_uid='.intval($_SESSION["wcs_user_id"]).')';
			
			require_once(PHPWCMS_ROOT.'/include/inc_lib/dbcon.inc.php');
		
			$sql   = 'SELECT f_hash, f_ext FROM '.DB_PREPEND.'phpwcms_file WHERE ';
			$sql  .= 'f_hash='._dbEscape($hash)." AND ";
			if(substr($phpwcms['image_library'], 0, 2) == 'gd') {
				$sql .= "f_ext IN ('jpg','jpeg','png','gif','bmp') AND ";
			}
			$sql  .= 'f_trash=0 AND f_aktiv=1 AND '.$file_public;
			$hash  = _dbQuery($sql);
			if(isset($hash[0]['f_hash'])) {
				$ext  = $hash[0]['f_ext'];
				$hash = $hash[0]['f_hash'];
			} else {
				$hash = '';
				$ext  = '';
			}
			
		}
		
		if($hash && strlen($hash) == 32 && $ext) {
		
			$attribute	= explode('x', $data[0]);
			$width		= intval($attribute[0]);
			$height		= isset($attribute[1]) ? intval($attribute[1]) : 0;
			$crop		= isset($attribute[2]) ? intval($attribute[2]) : 0;
			if($crop) {
				$grid	= $crop > 1 ? $crop : 0;
				$crop	= 1;
			} else {
				$grid	= 0;
			}
			
			// quality
			if(isset($attribute[3]) && ($quality = intval($attribute[3])) ) {
				if($quality < 10 || $quality > 100) {
					$quality = '';
				} else {
					$value['jpg_quality'] = $quality;
				}
			} else {
				$quality = '';
			}

			$value["max_width"]		= $width ? $width : '';
			$value["max_height"]	= $height ? $height : '';
			$value['target_ext']	= $ext;
			$value['image_name']	= $hash . '.' . $ext;
			$value['thumb_name']	= md5($hash.$value["max_width"].$value["max_height"].$phpwcms['sharpen_level'].$crop.$quality);
			$value['crop_image']	= $crop;
			
			// Set width/height based on grid
			if($grid) {
				if(!$value["max_width"] || !$value["max_height"]) {
					if(is_file(PHPWCMS_ROOT.'/'.PHPWCMS_FILES.$value['image_name']) && ($imgdata = @getimagesize(PHPWCMS_ROOT.'/'.PHPWCMS_FILES.$value['image_name']))) {
					
						if($value["max_height"] && !$value["max_width"]) {
							$resize_factor = $imgdata[1] / $value["max_height"];
							$value["max_width"] = floor($imgdata[0] / $resize_factor);
						}
						if($value["max_width"] && !$value["max_height"]) {
							$resize_factor = $imgdata[0] / $value["max_width"];
							$value["max_height"] = floor($imgdata[1] / $resize_factor);
						}
						if(!$value["max_width"] && !$value["max_height"]) {
							$value["max_width"]  = $imgdata[0];
							$value["max_height"] = $imgdata[1];
						}
						
					} elseif(!$value["max_width"]) {
						$value["max_width"] = $value["max_height"];
					} elseif(!$value["max_height"]) {
						$value["max_height"] = $value["max_width"];
					}
				}
				$basis = floor($value["max_width"] / $grid);
				if(!$basis) {
					$basis = 1;
				}
				$value["max_width"] = $basis * $grid;
				
				$basis = floor($value["max_height"] / $grid);
				if(!$basis) {
					$basis = 1;
				}
				$value["max_height"] = $basis * $grid;
			}
			
			$image = get_cached_image( $value, false, false );
			
			if(!empty($image[0])) {
				headerRedirect(PHPWCMS_URL.PHPWCMS_IMAGES.$image[0], 301);
			}
			
		}
	
	}

}

// something did not work - redirect to transparent pixel image
headerRedirect(PHPWCMS_URL.'img/leer.gif', 301);


?>