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

if(isset($image_list['images']) && is_array($image_list['images']) && count($image_list['images'])) {

    $imgx = 0;
    $img_thumbs = '';
    $cinfo_img = '';

    // browse images and list available
    // will be visible only when aceessible
    foreach($image_list['images'] as $key => $value) {

        $thumb_image = get_cached_image(array(
            "target_ext"    =>  $image_list['images'][$key][3],
            "image_name"    =>  $image_list['images'][$key][2] . '.' . $image_list['images'][$key][3],
            "thumb_name"    =>  md5($image_list['images'][$key][2].$phpwcms["img_list_width"].$phpwcms["img_list_height"].$phpwcms["sharpen_level"].$phpwcms['colorspace'])
        ));

        if($thumb_image != false) {
            if($imgx == 4) {
                $cinfo_img .= '<br /><img src="img/leer.gif" alt="" border="0" width="1" height="2" /><br />';
                $imgx = 0;
            }
            if($imgx) {
                $cinfo_img .= '<img src="img/leer.gif" alt="" border="0" width="2" height="1" />';
            }
            $cinfo_img .= '<img src="' . $thumb_image['src'] .'" '.$thumb_image[3].' alt="'.html($image_list['images'][$key][1]).'" />';
            $imgx++;
        }
    }
    if($imgx) {
        if($cinfo["result"]) $cinfo["result"] .= '<br>';
        $cinfo["result"] .= $cinfo_img;
    }
}

if($cinfo["result"]) { //Zeige Inhaltinfo
    echo "<tr><td>&nbsp;</td><td class=\"v10\">";
    echo "<a href=\"phpwcms.php?do=articles&amp;p=2&amp;s=1&amp;aktion=2&amp;id=".$article["article_id"]."&amp;acid=".$row["acontent_id"]."\">";
    echo $cinfo["result"]."</a></td><td>&nbsp;</td></tr>";
}
