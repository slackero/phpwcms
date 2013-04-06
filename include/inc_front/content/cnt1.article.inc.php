<?php
/**
 * phpwcms content management system
 *
 * @author Oliver Georgi <oliver@phpwcms.de>
 * @copyright Copyright (c) 2002-2013, Oliver Georgi
 * @license http://opensource.org/licenses/GPL-2.0 GNU GPL-2
 * @link http://www.phpwcms.de
 *
 **/

// ----------------------------------------------------------------
// obligate check for phpwcms constants
if (!defined('PHPWCMS_ROOT')) {
   die("You Cannot Access This Script Directly, Have a Nice Day.");
}
// ----------------------------------------------------------------


//image with text

// read template
if(empty($crow["acontent_template"]) && is_file(PHPWCMS_TEMPLATE.'inc_default/imagetext.tmpl')) {

	$crow["acontent_template"]	= render_device( @file_get_contents(PHPWCMS_TEMPLATE.'inc_default/imagetext.tmpl') );
	
} elseif(is_file(PHPWCMS_TEMPLATE.'inc_cntpart/imagetext/'.$crow["acontent_template"])) {

	$crow["acontent_template"]	= render_device( @file_get_contents(PHPWCMS_TEMPLATE.'inc_cntpart/imagetext/'.$crow["acontent_template"]) );

} else {

	$crow["acontent_template"]	= '[IMAGETEXT]<div class="image-with-text">{IMAGETEXT}</div>[/IMAGETEXT]';

}

$crow["settings"]			= get_tmpl_section('IMAGETEXT_SETTINGS', $crow["acontent_template"]);
$crow["settings"]			= parse_ini_str($crow["settings"], false);
$crow["acontent_template"]	= replace_tmpl_section('IMAGETEXT_SETTINGS', $crow["acontent_template"]);

$crow["acontent_template"]  = render_cnt_template($crow["acontent_template"], 'TITLE', html_specialchars($crow['acontent_title']));
$crow["acontent_template"]  = render_cnt_template($crow["acontent_template"], 'SUBTITLE', html_specialchars($crow['acontent_subtitle']));

// 0   :1       :2   :3        :4    :5     :6      :7       :8
// dbid:filename:hash:extension:width:height:caption:position:zoom
$image = ($crow["acontent_image"]) ? explode(":", $crow["acontent_image"]) : false ;

$crow["default_settings"] = array(
	'class_top_left'		=> $template_default['classes']['imgtxt-top-left'],
	'class_top_center'		=> $template_default['classes']['imgtxt-top-center'],
	'class_top_right'		=> $template_default['classes']['imgtxt-top-right'],
	'class_bottom_left'		=> $template_default['classes']['imgtxt-bottom-left'],
	'class_bottom_center'	=> $template_default['classes']['imgtxt-bottom-center'],
	'class_bottom_right'	=> $template_default['classes']['imgtxt-bottom-right'],
	'class_float_left'		=> $template_default['classes']['imgtxt-left'],
	'class_float_right'		=> $template_default['classes']['imgtxt-right'],
	'class_column_left'		=> $template_default['classes']['imgtxt-column-left'],
	'class_column_right'	=> $template_default['classes']['imgtxt-column-right'],
	'width'					=> $image[4],
	'height'				=> $image[5],
	'zoom'					=> $image[8],
	'crop'					=> 0,
	'lightbox'				=> 0,
	'nocaption'				=> 0
);

$image_text	= '';

//zoom click = $image[8];
if($image) {

	// load special functions
	require_once(PHPWCMS_ROOT.'/include/inc_front/img.func.inc.php');
	
	$cnt_image	= @unserialize($crow["acontent_form"]);

	$crow["default_settings"]['lightbox']	= empty($cnt_image['cimage_lightbox']) ? 0 : 1;
	$crow["default_settings"]['nocaption']	= empty($cnt_image['cimage_nocaption']) ? 0 : 1;
	$crow["default_settings"]['crop']		= empty($cnt_image['cimage_crop']) ? 0 : 1;
	
	$crow["settings"] = array_merge($crow["default_settings"], $crow["settings"]);
	
	if($crow["settings"]['lightbox']) {
		initSlimbox();
		$crow["settings"]['zoom'] = 1;
	}
	
	$GLOBALS['cnt_image_lightbox']	= $cnt_image_lightbox = $crow["settings"]['lightbox'];
	$image['nocaption']				= $crow["settings"]['nocaption'];
	$image['crop']					= $crow["settings"]['crop'];
	$image[4]						= $crow["settings"]['width'];
	$image[5]						= $crow["settings"]['height'];
	$image[8]						= $crow["settings"]['zoom'];

	
	switch($image[7]) {

		//oben links
		case 0:
			$image_text .= imagediv($phpwcms, $image, $crow["settings"]['class_top_left']);
			$image_text .= LF . $crow["acontent_text"];
			break;
		
		//oben mitte
		case 1:
			$image_text .= imagediv($phpwcms, $image, $crow["settings"]['class_top_center']);
			$image_text .= LF . $crow["acontent_text"];
			break;
		
		//oben rechts
		case 2:	
			$image_text .= imagediv($phpwcms, $image, $crow["settings"]['class_top_right']);
			$image_text .= LF . $crow["acontent_text"];
			break;
		
		//unten links
		case 3: 
			$image_text .= $crow["acontent_text"] . LF;
			$image_text .= imagediv($phpwcms, $image, $crow["settings"]['class_bottom_left']);
			break;
		
		//unten mitte
		case 4: 
			$image_text .= $crow["acontent_text"] . LF;
			$image_text .= imagediv($phpwcms, $image, $crow["settings"]['class_bottom_center']);
			break;
		
		//unten rechts
		case 5: 
			$image_text .= $crow["acontent_text"] . LF;
			$image_text .= imagediv($phpwcms, $image, $crow["settings"]['class_bottom_right']);
			break;
		
		//im Text links
		case 6:
			$image_text .= imagediv($phpwcms, $image, $crow["settings"]['class_float_left']);
			$image_text .= LF . $crow["acontent_text"];
			break;
		
		//im Text rechts
		case 7:
			$image_text .= imagediv($phpwcms, $image, $crow["settings"]['class_float_right']);
			$image_text .= LF . $crow["acontent_text"];	
			break;
		
		// table mode left
		case 8: 
			$iconimg = imagediv($phpwcms, $image, $crow["settings"]['class_column_left'].'-image');
			if(trim($iconimg.$crow["acontent_text"])) {
				$image_text .= '<div class="'.$crow["settings"]['class_column_left'].'">'.LF;
				$image_text .= '	' . $iconimg . LF;
				$image_text .= '	<div class="'.$crow["settings"]['class_column_left'].'-text">'.$crow["acontent_text"].'</div>' . LF;
				$image_text .= '</div>';
			}
			break;
		
		// table mode right
		case 9:
			$iconimg = imagediv($phpwcms, $image, $crow["settings"]['class_column_right'].'-image');
			if(trim($iconimg.$crow["acontent_text"])) {
				$image_text .= '<div class="'.$crow["settings"]['class_column_right'].'">' . LF;
				$image_text .= '	<div class="'.$crow["settings"]['class_column_right'].'-text">'.$crow["acontent_text"].'</div>' . LF;
				$image_text .= '	' . $iconimg . LF;
				$image_text .= '</div>';
			}
			break;
		
	}
	
	unset($cnt_image);
	$GLOBALS['cnt_image_lightbox'] = $cnt_image_lightbox = 0;
	
} else {
	
	$image_text .= $crow["acontent_text"];

}

unset($image);

$CNT_TMP .= LF . trim(str_replace('{ID}', $crow["acontent_id"], render_cnt_template($crow["acontent_template"], 'IMAGETEXT', $image_text ))) . LF;

?>