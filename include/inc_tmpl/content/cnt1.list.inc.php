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

// Text with image
$cinfo = [];
if (!empty($row['acontent_title'])) {
    $cinfo[] = html(getCleanSubString($row['acontent_title'], 55, '&#8230;'));
}
if (!empty($row['acontent_subtitle'])) {
    $cinfo[] = html(getCleanSubString($row['acontent_subtitle'], 55, '&#8230;'));
}
if (!empty($row['acontent_text'])) {
    $cinfo[] = html(str_replace(["\r\n", "\r", "\n"], ' ', getCleanSubString(strip_tags($row['acontent_text']), 150, '&#8230;')));
}

// 0   :1       :2   :3        :4    :5     :6      :7       :8
// dbid:filename:hash:extension:width:height:caption:position:zoom
$cinfo_image = explode(':', $row['acontent_image']);
$image_preview = '';
if (!empty($cinfo_image[2]) && !empty($cinfo_image[3])) {
    $thumb_image = get_cached_image([
        'target_ext' => $cinfo_image[3],
        'image_name' => $cinfo_image[2] . '.' . $cinfo_image[3],
        'thumb_name' => md5($cinfo_image[2] . $phpwcms['img_list_width'] . $phpwcms['img_list_height'] . $phpwcms['sharpen_level'] . $phpwcms['colorspace']),
    ]);
    if ($thumb_image !== false) {
        $image_preview = '<img class="img-thumbnail rounded me-2 mb-1" src="' . $thumb_image['src'] . '" alt="" ' . $thumb_image[3] . ' />';
    }
}

$cinfo_result = implode(' / ', $cinfo);
if ($cinfo_result !== '' || $image_preview !== '') {
    echo '<div class="col-12">';
    echo '<a href="phpwcms.php?do=articles&amp;p=2&amp;s=1&amp;aktion=2&amp;id=' . $article['article_id'] . '&amp;acid=' . $row['acontent_id'] . '">';
    if ($cinfo_result !== '') {
        echo $cinfo_result;
        if ($image_preview !== '') {
            echo '<br>';
        }
    }
    if ($image_preview !== '') {
        echo $image_preview;
    }
    echo '</a></div>';
}