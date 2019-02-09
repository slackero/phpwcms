<?php
/**
 * cmsGO!
 *
 * @author Pixels & Points GmbH <info@pixels-points.ch>
 * @copyright Copyright (c) 2002-2019, Pixels & Points GmbH
 * @license https://www.pixels-points.ch/cmsgo-license.html Pixels & Points cmsGO! license
 *
 **/

// ----------------------------------------------------------------
// obligate check for cmsgo constants
if(!defined('CMSGO_ROOT')) {
    die("You Cannot Access This Script Directly, Have a Nice Day.");
}
// ----------------------------------------------------------------

// E-Card

$cinfo["result"] = $row["acontent_title"] ? cut_string($row["acontent_title"], '&#8230;', 55) : "";
$cinfo["result"] .= ($cinfo["result"] && $row["acontent_subtitle"]) ? " / " : "";
$cinfo["result"] .= $row["acontent_subtitle"] ? cut_string($row["acontent_subtitle"], '&#8230;', 55) : "";

// same as image list

// get image array
$image_list = unserialize($row["acontent_form"]);

if(isset($image_list['images']) && is_array($image_list['images']) && count($image_list['images'])) {

    $imgx = 0;
    $img_thumbs = '';
    $cinfo_img = '';

    // browse images and list available
    // will be visible only when aceessible
    foreach($image_list['images'] as $key => $value) {

        $thumb_image = get_cached_image([
            "target_ext" => $image_list['images'][ $key ][3],
            "image_name" => $image_list['images'][ $key ][2] . '.' . $image_list['images'][ $key ][3],
            "thumb_name" => md5($image_list['images'][ $key ][2] . $cmsgo["img_list_width"] . $cmsgo["img_list_height"] . $cmsgo["sharpen_level"] . $cmsgo['colorspace']),
        ]);

        if($thumb_image != false) {
            if($imgx == 4) {
                $cinfo_img .= '<br>><br>';
                $imgx = 0;
            }
            if($imgx) {
                $cinfo_img .= '';
            }
            $cinfo_img .= '<img src="' . $thumb_image['src'] . '" ' . $thumb_image[3] . ' alt="' . html($image_list['images'][ $key ][1]) . '">';
            $imgx++;
        }
    }
    if($imgx) {
        if($cinfo["result"]) {
            $cinfo["result"] .= '<br>';
        }
        $cinfo["result"] .= $cinfo_img;
    }
}

if($cinfo["result"]) { //Zeige Inhaltinfo
    echo "<div class=\"col-sm-auto\">";
    echo "<a href=\"cmsgo.php?do=articles&amp;p=2&amp;s=1&amp;aktion=2&amp;id=" . $article["article_id"] . "&amp;acid=" . $row["acontent_id"] . "\">";
    echo $cinfo["result"] . "</a></div>";
}
