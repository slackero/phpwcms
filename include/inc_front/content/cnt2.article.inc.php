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

//images (gallery)

$image = @unserialize($crow["acontent_form"]);

if(is_array($image) && count($image)) {

	// load special functions
	require_once(PHPWCMS_ROOT.'/include/inc_front/img.func.inc.php');

	if(!isset($image['lightbox'])) {

		$GLOBALS['cnt_image_lightbox'] = 0;
		$cnt_image_lightbox = 0;

	} elseif($image['lightbox']) {

		$GLOBALS['cnt_image_lightbox'] = 1;
		$cnt_image_lightbox = 1;
		initSlimbox();

	}

	switch($image['pos']) {
		//oben mitte
		case 1:	$CNT_TMP .= imagelisttable($image, "0:0:0:0", "center");
				$CNT_TMP .= headline($crow["acontent_title"], $crow["acontent_subtitle"], $template_default["article"]);
				break;
		//oben rechts
		case 2:	$CNT_TMP .= '<div class="phpwcmsImgListRight">'.LF;
				$CNT_TMP .= imagelisttable($image, "0:0:0:0", "", $template_default["article"]);
				$CNT_TMP .= '</div>'.LF;
				$CNT_TMP .= headline($crow["acontent_title"], $crow["acontent_subtitle"], $template_default["article"]);
				break;
		//unten links
		case 3: $CNT_TMP .= headline($crow["acontent_title"], $crow["acontent_subtitle"], $template_default["article"]);
				$CNT_TMP .= imagelisttable($image, "0:0:0:0", "");
				break;
		//unten mitte
		case 4: $CNT_TMP .= headline($crow["acontent_title"], $crow["acontent_subtitle"], $template_default["article"]);
				$CNT_TMP .= imagelisttable($image, "0:0:0:0", "center");
				break;
		//unten rechts
		case 5:	$CNT_TMP .= headline($crow["acontent_title"], $crow["acontent_subtitle"], $template_default["article"]);
				$CNT_TMP .= '<div class="phpwcmsImgListRight">'.LF;
				$CNT_TMP .= imagelisttable($image, "0:0:0:0", "");
				$CNT_TMP .= '</div>'.LF;
				break;
		//im Text links
		case 6:	$CNT_TMP .= headline($crow["acontent_title"], $crow["acontent_subtitle"], $template_default["article"]);
				$CNT_TMP .= imagelisttable($image, "0:0:0:0", "left");
				break;
		//im Text rechts
		case 7: $CNT_TMP .= headline($crow["acontent_title"], $crow["acontent_subtitle"], $template_default["article"]);
				$CNT_TMP .= imagelisttable($image, "0:0:0:0", "right");
				break;
		//oben links
		default:
				$CNT_TMP .= imagelisttable($image, "0:0:0:0", "");
				$CNT_TMP .= headline($crow["acontent_title"], $crow["acontent_subtitle"], $template_default["article"]);
	}
	
	$GLOBALS['cnt_image_lightbox'] = 0;
	$cnt_image_lightbox = 0;
	
} else {
	$CNT_TMP .= headline($crow["acontent_title"], $crow["acontent_subtitle"], $template_default["article"]);
}
unset($image);

?>