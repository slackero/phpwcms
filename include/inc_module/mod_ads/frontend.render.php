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

// Module/Plug-in Ads/Banner Management
// use it as when it is located under "template/inc_script/frontend_render"
// most times it is used to make global replacements

// $content['all'] = str_replace('{MY_TAG}', 'My Replacement', $content['all'];

// OK, lets get path of that file - normally we know but yes we can take it:
// $_module_root = dirname($value);
// better than using this:
// $_module_root = PHPWCMS_ROOT.'/include/inc_module/mod_ads';
// but OK too as long mods folder is not renamed:

if(strpos($content['all'], '{ADS_')) {

	// OK ADS TAG found and now do the rest :)
	include_once(dirname($value).'/inc/ads.fe_render.inc.php');
	
	$content['ADS_ALL'] = array();
	$content['all'] = preg_replace_callback('/\{ADS_(\d+)\}/','renderAds', $content["all"]);
	
	if(count($content['ADS_ALL'])) {
		
		//render ads tracking image here.	
		$content['all'] .=	'<img src="'.CONTENT_PATH.'ads/adtracking.php?'.
							't='.implode('%2C', $content['ADS_ALL']).'&amp;'.
							'u='.PHPWCMS_USER_KEY.'&amp;r='.(empty($_SERVER['HTTP_REFERER']) ? '' : urlencode($_SERVER['HTTP_REFERER'])).
							'&amp;c='.$aktion[0].'&amp;a='.$aktion[1].'&amp;k='.md5(microtime()).
							'" alt="" width="0" height="0" />';
	
	}

}

?>