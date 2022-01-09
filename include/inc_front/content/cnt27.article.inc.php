<?php
/**
 * phpwcms content management system
 *
 * @author Oliver Georgi <og@phpwcms.org>
 * @copyright Copyright (c) 2002-2022, Oliver Georgi
 * @license http://opensource.org/licenses/GPL-2.0 GNU GPL-2
 * @link http://www.phpwcms.org
 *
 **/

// ----------------------------------------------------------------
// obligate check for phpwcms constants
if (!defined('PHPWCMS_ROOT')) {
	die("You Cannot Access This Script Directly, Have a Nice Day.");
}
// ----------------------------------------------------------------

//FAQ

$crow["acontent_form"]	= @unserialize($crow["acontent_form"]);
$crow["acontent_image"]	= empty($crow["acontent_image"]) ? '' : explode(":", $crow["acontent_image"]);

if(!empty($crow["acontent_form"]['faq_template']) && file_exists(PHPWCMS_TEMPLATE.'inc_cntpart/faq/'.$crow["acontent_form"]['faq_template'])) {

	$crow["acontent_form"]['faq_template'] = render_device( @file_get_contents(PHPWCMS_TEMPLATE.'inc_cntpart/faq/'.$crow["acontent_form"]['faq_template']) );

} else {
	$crow["acontent_form"]['faq_template'] = '<div class="faq">
	<!-- hidden title/subtitle [TITLE]
	<h3 id="faq_id{FAQ_ID}">{TITLE}</h3>[/TITLE][SUBTITLE]
	<h4>{SUBTITLE}</h4>[/SUBTITLE]
	-->
	[FAQ_QUESTION]<h3>{FAQ_QUESTION}</h3>[/FAQ_QUESTION]
	[FAQ_IMAGE]<div class="faq-image">
	{FAQ_IMAGE}[FAQ_CAPTION]
	<div class="faq-caption">{FAQ_CAPTION}</div>[/FAQ_CAPTION]
	</div>[/FAQ_IMAGE]
	[FAQ_ANSWER]<p>{FAQ_ANSWER}</p>[/FAQ_ANSWER]
</div>';
}

// 0   :1       :2   :3        :4    :5     :6      :7       :8
// dbid:filename:hash:extension:width:height:caption:position:zoom

//build image/image link
$thumb_image	= false;
$thumb_img		= '';
$caption[0]		= '';
if(!empty($crow["acontent_image"][2])) {

	$caption = getImageCaption(array('caption' => base64_decode($crow["acontent_image"][6]), 'file' => $crow["acontent_image"][0]));
	$caption[0]	= html_specialchars($caption[0]);
	$caption[3] = empty($caption[3]) ? '' : ' title="'.html_specialchars($caption[3]).'"'; //title
	$caption[1] = empty($caption[1]) ? html_specialchars($crow["acontent_image"][1]) : html_specialchars($caption[1]);

	$thumb_image = get_cached_image(array(
		"target_ext"	=>	$crow["acontent_image"][3],
		"image_name"	=>	$crow["acontent_image"][2] . '.' . $crow["acontent_image"][3],
		"max_width"		=>	$crow["acontent_image"][4],
		"max_height"	=>	$crow["acontent_image"][5],
		"thumb_name"	=>	md5($crow["acontent_image"][2].$crow["acontent_image"][4].$crow["acontent_image"][5].$phpwcms["sharpen_level"].$phpwcms['colorspace'])
	));

	if($thumb_image != false) {

		$thumb_img  = '<img src="'. $thumb_image['src'] .'" ' . $thumb_image[3] . ' alt="'.$caption[1].'"'.$caption[3].' />';

		if($crow["acontent_image"][8]) {

			$zoominfo = get_cached_image(array(
				"target_ext"	=>	$crow["acontent_image"][3],
				"image_name"	=>	$crow["acontent_image"][2] . '.' . $crow["acontent_image"][3],
				"max_width"		=>	$phpwcms["img_prev_width"],
				"max_height"	=>	$phpwcms["img_prev_height"],
				"thumb_name"	=>	md5($crow["acontent_image"][2].$phpwcms["img_prev_width"].$phpwcms["img_prev_height"].$phpwcms["sharpen_level"].$phpwcms['colorspace'])
			));

			if($zoominfo != false) {

				$popup_img = 'image_zoom.php?'.getClickZoomImageParameter($zoominfo['src'], $zoominfo[3], $crow["acontent_image"][1]);

				if(!empty($caption[2][0])) {
					$open_link = $caption[2][0];
					$return_false = '';
				} else {
					$open_link = $popup_img;
					$return_false = 'return false;';
				}

				$thumb_img = '<a href="'.$popup_img.'" onclick="window.open(\''.$open_link.
				"','previewpic','width=".$zoominfo[1].",height=".$zoominfo[2]."');".$return_false.
				'"'.$caption[2][1].'>'.$thumb_img.'</a>';


			}
		} elseif($caption[2][0]) {
		    $thumb_img = '<a href="'.$caption[2][0].'"'.$caption[2][1].'>'.$thumb_img.'</a>';
		}
	}
}

$crow["acontent_form"]['faq_template'] = render_cnt_template($crow["acontent_form"]['faq_template'], 'ATTR_CLASS', html($crow['acontent_attr_class']));
$crow["acontent_form"]['faq_template'] = render_cnt_template($crow["acontent_form"]['faq_template'], 'ATTR_ID', html($crow['acontent_attr_id']));
$crow["acontent_form"]['faq_template'] = render_cnt_template($crow["acontent_form"]['faq_template'], 'TITLE', html_specialchars($crow['acontent_title']));
$crow["acontent_form"]['faq_template'] = render_cnt_template($crow["acontent_form"]['faq_template'], 'SUBTITLE', html_specialchars($crow['acontent_subtitle']));
$crow["acontent_form"]['faq_template'] = render_cnt_template($crow["acontent_form"]['faq_template'], 'FAQ_QUESTION', html_specialchars($crow["acontent_text"]));
$crow["acontent_form"]['faq_template'] = render_cnt_template($crow["acontent_form"]['faq_template'], 'FAQ_ANSWER', $crow["acontent_html"]);
$crow["acontent_form"]['faq_template'] = render_cnt_template($crow["acontent_form"]['faq_template'], 'FAQ_IMAGE', $thumb_img);
$crow["acontent_form"]['faq_template'] = render_cnt_template($crow["acontent_form"]['faq_template'], 'FAQ_CAPTION', $caption[0]);
$crow["acontent_form"]['faq_template'] = str_replace('{FAQ_ID}', $crow['acontent_id'], $crow["acontent_form"]['faq_template']);

$CNT_TMP .= $crow["acontent_form"]['faq_template'];

unset($image, $caption);
