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

// ----------------------------------------------------------------
// obligate check for phpwcms constants
if (!defined('PHPWCMS_ROOT')) {
   die("You Cannot Access This Script Directly, Have a Nice Day.");
}
// ----------------------------------------------------------------




//image with text

// 0   :1       :2   :3        :4    :5     :6      :7       :8
// dbid:filename:hash:extension:width:height:caption:position:zoom
$image		= ($crow["acontent_image"]) ? explode(":", $crow["acontent_image"]) : false ;

//zoom click = $image[8];
if($image) {

	// load special functions
	require_once(PHPWCMS_ROOT.'/include/inc_front/img.func.inc.php');


	$image['div'] = empty($template_default["article"]["image_div"]) ? false : true;
	
	$cnt_image	= @unserialize($crow["acontent_form"]);
	if(!isset($cnt_image['cimage_lightbox'])) {
	
		$cnt_image_lightbox = 0;
	
	} elseif($cnt_image['cimage_lightbox']) {
	
		$cnt_image_lightbox = 1;
		initializeLightbox();
	
	}
	$image['nocaption'] = empty($cnt_image['cimage_nocaption']) ? 0 : 1;
	$image['crop']		= empty($cnt_image['cimage_crop']) ? 0 : 1;
	
	switch($image[7]) {

		//oben links
		case 0: 
		$CNT_TMP .= $image['div'] ? imagediv($phpwcms, $image, 'topLeft') : imagetable($phpwcms, $image, "0:5:0:0", "");
		$CNT_TMP .= headline($crow["acontent_title"], $crow["acontent_subtitle"], $template_default["article"]);
		$CNT_TMP .= ($crow["acontent_text"]) ? div_class($crow["acontent_text"], $template_default["article"]["text_class"]) : "";
		break;
		//oben mitte
		case 1:
		$CNT_TMP .= $image['div'] ? imagediv($phpwcms, $image, 'topCenter" align="center') : imagetable($phpwcms, $image, "0:5:0:0", "center");
		$CNT_TMP .= headline($crow["acontent_title"], $crow["acontent_subtitle"], $template_default["article"]);
		$CNT_TMP .= ($crow["acontent_text"]) ? div_class($crow["acontent_text"], $template_default["article"]["text_class"]) : "";
		break;
		//oben rechts
		case 2:	
		if($image['div']) {
			$CNT_TMP .= imagediv($phpwcms, $image, 'topRight" align="right');
		} else {
			$CNT_TMP .= '<div align="right" style="text-align:right;margin:0;padding:0;">'.imagetable($phpwcms, $image, "0:5:0:0", "").'</div>';
		}
		$CNT_TMP .= headline($crow["acontent_title"], $crow["acontent_subtitle"], $template_default["article"]);
		$CNT_TMP .= ($crow["acontent_text"]) ? div_class($crow["acontent_text"], $template_default["article"]["text_class"]) : "";
		break;
		//unten links
		case 3: 
		$CNT_TMP .= headline($crow["acontent_title"], $crow["acontent_subtitle"], $template_default["article"]);
		$CNT_TMP .= ($crow["acontent_text"]) ? div_class($crow["acontent_text"], $template_default["article"]["text_class"]) : "";
		$CNT_TMP .= $image['div'] ? imagediv($phpwcms, $image, 'bottomLeft') : imagetable($phpwcms, $image, "5:0:0:0", "");
		break;
		//unten mitte
		case 4: 
		$CNT_TMP .= headline($crow["acontent_title"], $crow["acontent_subtitle"], $template_default["article"]);
		$CNT_TMP .= ($crow["acontent_text"]) ? div_class($crow["acontent_text"],$template_default["article"]["text_class"]) : "";
		$CNT_TMP .= $image['div'] ? imagediv($phpwcms, $image, 'bottomCenter" align="center') : imagetable($phpwcms, $image, "5:0:0:0", "center");
		break;
		//unten rechts
		case 5: 
		$CNT_TMP .= headline($crow["acontent_title"], $crow["acontent_subtitle"], $template_default["article"]);
		$CNT_TMP .= ($crow["acontent_text"]) ? div_class($crow["acontent_text"],$template_default["article"]["text_class"]) : "";
		if($image['div']) {
			$CNT_TMP .= imagediv($phpwcms, $image, 'bottomRight" align="right');
		} else {
			$CNT_TMP .= '<div align="right" style="text-align:right;margin:0;padding:0;">'.imagetable($phpwcms, $image, "5:0:0:0", "").'</div>';
		}
		break;
		//im Text links
		case 6: 
		$CNT_TMP .= headline($crow["acontent_title"], $crow["acontent_subtitle"], $template_default["article"]);
		$icontent = ($crow["acontent_text"]) ? "###image_replace###".div_class($crow["acontent_text"], $template_default["article"]["text_class"]) : "###image_replace###";
		if($image['div']) {
			$iconimg = imagediv($phpwcms, $image, 'inTextLeft" align="left');
		} else {
			$iconimg = imagetable($phpwcms, $image, "0:0:0:8", "left");
		}
		$CNT_TMP .= str_replace("###image_replace###", $iconimg, $icontent);
		break;
		//im Text rechts
		case 7: 
		$CNT_TMP .= headline($crow["acontent_title"], $crow["acontent_subtitle"], $template_default["article"]);
		$icontent = ($crow["acontent_text"]) ? "###image_replace###".div_class($crow["acontent_text"], $template_default["article"]["text_class"]) : "###image_replace###";
		if($image['div']) {
			$iconimg = imagediv($phpwcms, $image, 'inTextRight" align="right');
		} else {
			$iconimg = imagetable($phpwcms, $image, "0:0:10:0", "right");
		}
		$CNT_TMP .= str_replace("###image_replace###", $iconimg, $icontent);
		break;
		
		case 9: 
		// Tabelle, links
		$CNT_TMP  .= headline($crow["acontent_title"], $crow["acontent_subtitle"], $template_default["article"]);
		$icontent  = '<table width="100%" cellpadding="0" cellspacing="0" border="0" class="imgTextLeft">';
		$icontent .= "\n<tr>\n";
		if($crow["acontent_text"]) {
			$icontent .= '<td valign="top" class="'.$template_default["article"]["text_class"].'">';
			$icontent .= $crow["acontent_text"];
			$icontent .= "</td>\n";
		}
		if($image['div']) {
			$iconimg = imagediv($phpwcms, $image, 'blockImgRight');
		} else {
			$iconimg = div_class(imagetable($phpwcms, $image, "0:0:0:0"), '');
		}
		if($iconimg) {
			$icontent .= '<td valign="top" width="10%" class="'.$template_default["article"]["image_class"].'">';
			$icontent .= $iconimg."</td>\n";
		}
		$icontent .= "</tr>\n</table>";
		$CNT_TMP  .= $icontent;
		break;
		
		case 8: 
		// Tabelle, rechts
		$CNT_TMP  .= headline($crow["acontent_title"], $crow["acontent_subtitle"], $template_default["article"]);
		$icontent  = '<table width="100%" cellpadding="0" cellspacing="0" border="0" class="imgTextRight">';
		$icontent .= "\n<tr>\n";
		if($image['div']) {
			$iconimg = imagediv($phpwcms, $image, 'blockImgLeft');
		} else {
			$iconimg = div_class(imagetable($phpwcms, $image, "0:0:0:0"), '');
		}
		if($iconimg) {
			$icontent .= '<td valign="top" width="10%" class="'.$template_default["article"]["image_class"].'">';
			$icontent .= $iconimg."</td>\n";
		}
		if($crow["acontent_text"]) {
			$icontent .= '<td valign="top" class="'.$template_default["article"]["text_class"].'">';
			$icontent .= $crow["acontent_text"];
			$icontent .= "</td>\n";
		}
		$icontent .= "</tr>\n</table>";
		$CNT_TMP  .= $icontent;
		break;
		
	}
	
	unset($cnt_image);
	$cnt_image_lightbox = 0;
	
} else {
	$CNT_TMP .= headline($crow["acontent_title"], $crow["acontent_subtitle"], $template_default["article"]);
	if($crow["acontent_text"]) {
		$CNT_TMP .= div_class($crow["acontent_text"], $template_default["article"]["text_class"]);
	}
}
unset($image);

?>