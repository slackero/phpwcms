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
		case 2:	$CNT_TMP .= '<div class="'.$template_default['classes']['img-list-right'].'">'.LF;
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
				$CNT_TMP .= '<div class="'.$template_default['classes']['img-list-right'].'">'.LF;
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