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


// Images

$cinfo["result"]  = $row["acontent_title"] ? cut_string($row["acontent_title"],'&#8230;', 55) : '';
$cinfo["result"] .= ($cinfo["result"] && $row["acontent_subtitle"]) ? " / " : "";
$cinfo["result"] .= $row["acontent_subtitle"] ? cut_string($row["acontent_subtitle"],'&#8230;', 55) : '';

// get image array

$image_list = @unserialize($row["acontent_form"]);
$image_data = '';
foreach($image_list['images'] as $img_info) {

	if($img_info['thumb_id']) {
		$image_data .= '<img src="'.PHPWCMS_URL.PHPWCMS_RESIZE_IMAGE.'/'.$phpwcms['img_list_width'];
		$image_data .= 'x'.$phpwcms['img_list_height'].'/'.$img_info['thumb_id'].'" ';
		$image_data .= 'alt="" height="'.$phpwcms['img_list_height'].'" style="width:auto" /> ';
	}

	if($img_info['zoom_id']) {
		$image_data .= '<img src="'.PHPWCMS_URL.PHPWCMS_RESIZE_IMAGE.'/'.$phpwcms['img_list_width'];
		$image_data .= 'x'.$phpwcms['img_list_height'].'/'.$img_info['zoom_id'].'" ';
		$image_data .= 'alt="" height="'.$phpwcms['img_list_height'].'" style="width:auto" /> ';
	}

}

if($cinfo["result"] && $image_data) {
	$cinfo["result"] .= '<br />';
}
$cinfo["result"] .= $image_data;

if($cinfo["result"]) { //Zeige Inhaltinfo
	echo "<tr><td>&nbsp;</td><td class=\"v10\">";
	echo "<a href=\"phpwcms.php?do=articles&amp;p=2&amp;s=1&amp;aktion=2&amp;id=".$article["article_id"]."&amp;acid=".$row["acontent_id"]."\">";
	echo $cinfo["result"]."</a></td><td>&nbsp;</td></tr>";
}
