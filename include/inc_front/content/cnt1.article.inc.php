<?php
/*************************************************************************************
   Copyright notice
   
   (c) 2002-2009 Oliver Georgi (oliver@phpwcms.de) // All rights reserved.

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

// read template
if(empty($crow["acontent_template"]) && is_file(PHPWCMS_TEMPLATE.'inc_default/imagetext.tmpl')) {

	$crow["acontent_template"]	= @file_get_contents(PHPWCMS_TEMPLATE.'inc_default/imagetext.tmpl');
	
} elseif(is_file(PHPWCMS_TEMPLATE.'inc_cntpart/imagetext/'.$crow["acontent_template"])) {

	$crow["acontent_template"]	= @file_get_contents(PHPWCMS_TEMPLATE.'inc_cntpart/imagetext/'.$crow["acontent_template"]);

} else {

	$crow["acontent_template"]	= '[IMAGETEXT]<div class="image-with-text">{IMAGETEXT}</div>[/IMAGETEXT]';

}

// 0   :1       :2   :3        :4    :5     :6      :7       :8
// dbid:filename:hash:extension:width:height:caption:position:zoom
$image		= ($crow["acontent_image"]) ? explode(":", $crow["acontent_image"]) : false ;
$image_text	= '';

//zoom click = $image[8];
if($image) {

	// load special functions
	require_once(PHPWCMS_ROOT.'/include/inc_front/img.func.inc.php');


	$image['div'] = empty($template_default["article"]["image_div"]) ? false : true;
	
	$cnt_image	= @unserialize($crow["acontent_form"]);
	if(!isset($cnt_image['cimage_lightbox'])) {
	
		$GLOBALS['cnt_image_lightbox'] = 0;
		$cnt_image_lightbox = 0;
	
	} elseif($cnt_image['cimage_lightbox']) {
	
		$GLOBALS['cnt_image_lightbox'] = 1;
		$cnt_image_lightbox = 1;
		initializeLightbox();
	
	}
	$image['nocaption'] = empty($cnt_image['cimage_nocaption']) ? 0 : 1;
	$image['crop']		= empty($cnt_image['cimage_crop']) ? 0 : 1;
	
	switch($image[7]) {

		//oben links
		case 0: 
		$image_text .= $image['div'] ? imagediv($phpwcms, $image, 'topLeft') : imagetable($phpwcms, $image, "0:5:0:0", "");
		$image_text .= headline($crow["acontent_title"], $crow["acontent_subtitle"], $template_default["article"]);
		$image_text .= ($crow["acontent_text"]) ? div_class($crow["acontent_text"], $template_default["article"]["text_class"]) : "";
		break;
		//oben mitte
		case 1:
		$image_text .= $image['div'] ? imagediv($phpwcms, $image, 'topCenter" align="center') : imagetable($phpwcms, $image, "0:5:0:0", "center");
		$image_text .= headline($crow["acontent_title"], $crow["acontent_subtitle"], $template_default["article"]);
		$image_text .= ($crow["acontent_text"]) ? div_class($crow["acontent_text"], $template_default["article"]["text_class"]) : "";
		break;
		//oben rechts
		case 2:	
		if($image['div']) {
			$image_text .= imagediv($phpwcms, $image, 'topRight" align="right');
		} else {
			$image_text .= '<div align="right" style="text-align:right;margin:0;padding:0;">'.imagetable($phpwcms, $image, "0:5:0:0", "").'</div>';
		}
		$image_text .= headline($crow["acontent_title"], $crow["acontent_subtitle"], $template_default["article"]);
		$image_text .= ($crow["acontent_text"]) ? div_class($crow["acontent_text"], $template_default["article"]["text_class"]) : "";
		break;
		//unten links
		case 3: 
		$image_text .= headline($crow["acontent_title"], $crow["acontent_subtitle"], $template_default["article"]);
		$image_text.= ($crow["acontent_text"]) ? div_class($crow["acontent_text"], $template_default["article"]["text_class"]) : "";
		$image_text .= $image['div'] ? imagediv($phpwcms, $image, 'bottomLeft') : imagetable($phpwcms, $image, "5:0:0:0", "");
		break;
		//unten mitte
		case 4: 
		$image_text .= headline($crow["acontent_title"], $crow["acontent_subtitle"], $template_default["article"]);
		$image_text .= ($crow["acontent_text"]) ? div_class($crow["acontent_text"],$template_default["article"]["text_class"]) : "";
		$image_text .= $image['div'] ? imagediv($phpwcms, $image, 'bottomCenter" align="center') : imagetable($phpwcms, $image, "5:0:0:0", "center");
		break;
		//unten rechts
		case 5: 
		$image_text .= headline($crow["acontent_title"], $crow["acontent_subtitle"], $template_default["article"]);
		$image_text .= ($crow["acontent_text"]) ? div_class($crow["acontent_text"],$template_default["article"]["text_class"]) : "";
		if($image['div']) {
			$image_text .= imagediv($phpwcms, $image, 'bottomRight" align="right');
		} else {
			$image_text .= '<div align="right" style="text-align:right;margin:0;padding:0;">'.imagetable($phpwcms, $image, "5:0:0:0", "").'</div>';
		}
		break;
		//im Text links
		case 6: 
		$image_text .= headline($crow["acontent_title"], $crow["acontent_subtitle"], $template_default["article"]);
		$icontent = ($crow["acontent_text"]) ? "###image_replace###".div_class($crow["acontent_text"], $template_default["article"]["text_class"]) : "###image_replace###";
		if($image['div']) {
			$iconimg = imagediv($phpwcms, $image, 'inTextLeft" align="left');
		} else {
			$iconimg = imagetable($phpwcms, $image, "0:0:0:8", "left");
		}
		$image_text .= str_replace("###image_replace###", $iconimg, $icontent);
		break;
		//im Text rechts
		case 7: 
		$image_text .= headline($crow["acontent_title"], $crow["acontent_subtitle"], $template_default["article"]);
		$icontent = ($crow["acontent_text"]) ? "###image_replace###".div_class($crow["acontent_text"], $template_default["article"]["text_class"]) : "###image_replace###";
		if($image['div']) {
			$iconimg = imagediv($phpwcms, $image, 'inTextRight" align="right');
		} else {
			$iconimg = imagetable($phpwcms, $image, "0:0:10:0", "right");
		}
		$image_text .= str_replace("###image_replace###", $iconimg, $icontent);
		break;
		
		case 9: 
		// Tabelle, links
		$image_text  .= headline($crow["acontent_title"], $crow["acontent_subtitle"], $template_default["article"]);
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
		$image_text  .= $icontent;
		break;
		
		case 8: 
		// Tabelle, rechts
		$image_text  .= headline($crow["acontent_title"], $crow["acontent_subtitle"], $template_default["article"]);
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
		$image_text  .= $icontent;
		break;
		
	}
	
	unset($cnt_image);
	$GLOBALS['cnt_image_lightbox'] = 0;
	$cnt_image_lightbox = 0;
	
} else {
	$image_text .= headline($crow["acontent_title"], $crow["acontent_subtitle"], $template_default["article"]);
	if($crow["acontent_text"]) {
		$image_text .= div_class($crow["acontent_text"], $template_default["article"]["text_class"]);
	}
}
unset($image);

$CNT_TMP .= LF . trim( render_cnt_template($crow["acontent_template"], 'IMAGETEXT', trim($image_text) ) ) . LF;


?>