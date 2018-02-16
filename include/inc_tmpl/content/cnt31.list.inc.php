<?php
/**
 * cmsGo!
 *
 * @author Pixels & Points GmbH <info@pixels-points.ch>
 * @copyright Copyright (c) 2002-2017, Pixels & Points GmbH
 * @license https://www.pixels-points.ch/cmsgo-license.html Pixels & Points cmsGo! license
 *
 **/

// ----------------------------------------------------------------
// obligate check for cmsgo constants
if (!defined('CMSGO_ROOT')) {
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
if (isset($image_list['images'])) {
    foreach($image_list['images'] as $img_info) {

        if($img_info['thumb_id']) {
            $image_data .= '<div class="col-sm-auto img-bg m-1">';
            $image_data .= '<img class="m-1 img-fluid" src="'.CMSGO_URL.CMSGO_RESIZE_IMAGE.'img/cmsimage.php/'.$cmsgo['img_list_width'];
            $image_data .= 'x'.$cmsgo['img_list_height'].'/'.$img_info['thumb_id'].'" border="0" alt="" /> ';
            $image_data .= '</div>';
        }

        if($img_info['zoom_id']) {
            $image_data .= '<div class="col-sm-auto img-bg m-1">';
            $image_data .= '<img class="m-1 img-fluid" src="'.CMSGO_URL.CMSGO_RESIZE_IMAGE.'img/cmsimage.php/'.$cmsgo['img_list_width'];
            $image_data .= 'x'.$cmsgo['img_list_height'].'/'.$img_info['zoom_id'].'" border="0" alt="" /> ';
            $image_data .= '</div>';
        }

    }
}

if($cinfo["result"] && $image_data) {
    $cinfo["result"] .= '<br />';
}
$cinfo["result"] .= $image_data;

if($cinfo["result"]) { //Zeige Inhaltinfo
    echo "<a class=\"w-100 mt-2\" href=\"cmsgo.php?do=articles&amp;p=2&amp;s=1&amp;aktion=2&amp;id=".$article["article_id"]."&amp;acid=".$row["acontent_id"]."\">";
    echo "<div class=\"row\">";
    echo $cinfo["result"];
    echo "</div>";
    echo "</a>";

}
