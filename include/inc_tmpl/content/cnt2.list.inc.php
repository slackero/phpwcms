<?php
/**
 * phpwcms
 *
 * @author Oliver Georgi <og@phpwcms.org>
 * @copyright Copyright (c) 2002-2026, Oliver Georgi
 * @license http://opensource.org/licenses/GPL-2.0 GNU GPL-2
 *
 **/

// ----------------------------------------------------------------
// obligate check for phpwcms constants
if (!defined('PHPWCMS_ROOT')) {
    die("You Cannot Access This Script Directly, Have a Nice Day.");
}
// ----------------------------------------------------------------

// Images

$cinfo = [];
if (!empty($row['acontent_title'])) {
    $cinfo[] = html(getCleanSubString($row['acontent_title'], 55, '&#8230;'));
}
if (!empty($row['acontent_subtitle'])) {
    $cinfo[] = html(getCleanSubString($row['acontent_subtitle'], 55, '&#8230;'));
}

// get image array
$image_list = @unserialize($row['acontent_form'], ['allowed_classes' => false]);
$cinfo_img = '';

if (is_array($image_list) && !empty($image_list['images']) && is_array($image_list['images'])) {
    foreach ($image_list['images'] as $img_entry) {
        if (!empty($img_entry[2]) && !empty($img_entry[3])) {
            $thumb_image = get_cached_image([
                'target_ext' => $img_entry[3],
                'image_name' => $img_entry[2] . '.' . $img_entry[3],
                'thumb_name' => md5($img_entry[2] . $phpwcms['img_list_width'] . $phpwcms['img_list_height'] . $phpwcms['sharpen_level'] . $phpwcms['colorspace']),
            ]);
            if ($thumb_image !== false) {
                $cinfo_img .= '<img class="img-thumbnail rounded me-1 mb-1" src="' . $thumb_image['src'] . '" ' . $thumb_image[3] . ' alt="' . html($img_entry[1] ?? '') . '" />';
            }
        }
    }
}

$cinfo_result = implode(' / ', $cinfo);
if ($cinfo_result !== '' || $cinfo_img !== '') {
    echo '<div class="col-12">';
    echo '<a href="phpwcms.php?do=articles&amp;p=2&amp;s=1&amp;aktion=2&amp;id=' . $article['article_id'] . '&amp;acid=' . $row['acontent_id'] . '">';
    if ($cinfo_result !== '') {
        echo $cinfo_result;
        if ($cinfo_img !== '') {
            echo '<br>';
        }
    }
    if ($cinfo_img !== '') {
        echo $cinfo_img;
    }
    echo '</a></div>';
}