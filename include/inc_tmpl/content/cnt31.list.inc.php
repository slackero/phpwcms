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

// Images Lightbox

$cinfo = [];
if (!empty($row['acontent_title'])) {
    $cinfo[] = html(getCleanSubString($row['acontent_title'], 55, '&#8230;'));
}
if (!empty($row['acontent_subtitle'])) {
    $cinfo[] = html(getCleanSubString($row['acontent_subtitle'], 55, '&#8230;'));
}

// get image array
$image_list = @unserialize($row['acontent_form'], ['allowed_classes' => false]);
$image_data = '';
if (isset($image_list['images']) && is_array($image_list['images'])) {
    foreach ($image_list['images'] as $img_info) {
        $img_id = !empty($img_info['thumb_id']) ? $img_info['thumb_id'] : (!empty($img_info['zoom_id']) ? $img_info['zoom_id'] : 0);
        if ($img_id) {
            $image_data .= '<img class="img-thumbnail rounded mr-1 mb-1" src="' . PHPWCMS_URL . PHPWCMS_RESIZE_IMAGE . '/' . $phpwcms['img_list_width'] . 'x' . $phpwcms['img_list_height'] . '/' . $img_id . '" alt="" />';
        }
    }
}

$cinfo_result = implode(' / ', $cinfo);
if ($cinfo_result !== '' || $image_data !== '') {
    echo '<div class="col-12">';
    echo '<a href="phpwcms.php?do=articles&amp;p=2&amp;s=1&amp;aktion=2&amp;id=' . $article['article_id'] . '&amp;acid=' . $row['acontent_id'] . '">';
    if ($cinfo_result !== '') {
        echo $cinfo_result;
        if ($image_data !== '') {
            echo '<br>';
        }
    }
    if ($image_data !== '') {
        echo $image_data;
    }
    echo '</a></div>';
}