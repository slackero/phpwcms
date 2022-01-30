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


// FAQ
$cinfo[1] = cut_string($row["acontent_title"],'&#8230;', 55);
$cinfo[2] = cut_string($row["acontent_subtitle"],'&#8230;', 55);
$cinfo[3] = str_replace("\n", " ", $row["acontent_text"]);

// 0   :1       :2   :3        :4    :5     :6      :7       :8
// dbid:filename:hash:extension:width:height:caption:position:zoom
$cinfo_image = explode(":", $row["acontent_image"]);
if(isset($cinfo_image[2]) && is_array($cinfo_image) && count($cinfo_image)) {

    $thumb_image = get_cached_image(array(
        "target_ext"    =>  $cinfo_image[3],
        "image_name"    =>  $cinfo_image[2] . '.' . $cinfo_image[3],
        "thumb_name"    =>  md5($cinfo_image[2].$phpwcms["img_list_width"].$phpwcms["img_list_height"].$phpwcms["sharpen_level"].$phpwcms['colorspace'])
    ));

    if($thumb_image != false) {
        $cinfo_image = '<img src="' . $thumb_image['src'] .'" alt="" '.$thumb_image[3].'>';
    } else {
        $cinfo_image = '';
    }
} else {
    $cinfo_image = '';
}
$cinfo["result"] = '';
foreach($cinfo as $value) {
    if($value) $cinfo["result"] .= $value."\n";
}
$cinfo["result"] = str_replace("\n", " / ", html(trim($cinfo["result"])));
if($cinfo["result"] || $cinfo_image) { //Zeige Inhaltinfo
    echo "<tr><td>&nbsp;</td><td class=\"v10\">";
    echo "<a href=\"phpwcms.php?do=articles&amp;p=2&amp;s=1&amp;aktion=2&amp;id=".$article["article_id"]."&amp;acid=".$row["acontent_id"]."\">";
    echo $cinfo["result"];
    if($cinfo["result"] && $cinfo_image) echo "<br />";
    echo $cinfo_image."</a></td><td>&nbsp;</td></tr>";
}
