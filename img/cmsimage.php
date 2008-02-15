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


$phpwcms	= array();
$root		= rtrim(str_replace('\\', '/', realpath(dirname(__FILE__).'/../') ), '/').'/';
require($root.'/config/phpwcms/conf.inc.php');
require($root.'/include/inc_lib/default.inc.php');
require($root.'/include/inc_lib/dbcon.inc.php');
require($root.'/include/inc_lib/general.inc.php');
require($root.'/include/inc_lib/imagick.convert.inc.php');

// get segments: cmsimage.php/%WIDTH%x%HEIGHT%x%CROP%x%QUALITY%/%HASH%.%EXT%
// by default this should be enough: cmsimage.php/%WIDTH%x%HEIGHT/%HASH%.%EXT%
$request_uri		= isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : $_SERVER['PHP_SELF'];
$query_separator	= strpos($request_uri, 'cmsimage.php?') !== FALSE ? '?' : '/';
$data				= explode('cmsimage.php'.$query_separator, $request_uri, 2);
if(isset($data[1]) && !preg_match('/[^a-fgijpnxA-FGIJPN0-9\/\.]/', $data[1])) {

	$data = explode('/', $data[1]);

	// first check hashed data
	if(isset($data[1])) {
	
		$data[0]	= preg_replace('/[^0-9x]/', '', $data[0]);
		$data[1]	= preg_replace('/[^a-fgijpn0-9\.]/i', '', $data[1]);

		$value		= array();

		$hash		= cut_ext($data[1]);
		$ext		= which_ext($data[1]);
		
		if(is_numeric($hash)) {
			$sql   = 'SELECT f_hash, f_ext FROM '.DB_PREPEND.'phpwcms_file WHERE ';
			$sql  .= 'f_id='.intval($hash)." AND f_ext IN ('jpg','jpeg','png','gif') AND ";
			$sql  .= 'f_trash=0 AND f_aktiv=1 AND f_public=1';
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
			$crop		= isset($attribute[2]) ? (intval($attribute[2]) ? 1 : 0) : 0;
			
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
		
			if($width) {
				$value["max_width"]		= $width;
			} else {
				$width					= '';
			}
			if($height) {
				$value["max_height"]	= $height;
			} else {
				$height					= '';
			}
			$value['target_ext']		= $ext;
			$value['image_name']		= $hash . '.' . $ext;
			$value['image_name']		= $hash . '.' . $ext;
			$value['thumb_name']		= md5($hash.$width.$height.$phpwcms['sharpen_level'].$crop.$quality);
			$value['crop_image']		= $crop;
			
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