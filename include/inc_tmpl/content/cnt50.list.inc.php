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

// Reference

$cinfo = [];
if (!empty($row['acontent_title'])) {
    $cinfo[] = html(getCleanSubString($row['acontent_title'], 55, '&#8230;'));
}
if (!empty($row['acontent_subtitle'])) {
    $cinfo[] = html(getCleanSubString($row['acontent_subtitle'], 55, '&#8230;'));
}

$reference = @unserialize($row['acontent_form'], ['allowed_classes' => false]);
$cinfo_img = '';
if (is_array($reference) && !empty($reference['list']) && is_array($reference['list'])) {
    foreach ($reference['list'] as $key => $value) {
        if (!empty($reference['list'][$key][2]) && !empty($reference['list'][$key][3])) {
            $thumb_image = get_cached_image([
                'target_ext' => $reference['list'][$key][3],
                'image_name' => $reference['list'][$key][2] . '.' . $reference['list'][$key][3],
                'thumb_name' => md5($reference['list'][$key][2] . $phpwcms['img_list_width'] . $phpwcms['img_list_height'] . $phpwcms['sharpen_level'] . $phpwcms['colorspace']),
            ]);

            if ($thumb_image !== false) {
                $cinfo_img .= '<img src="' . $thumb_image['src'] . '" ' . $thumb_image[3] . ' alt="' . html($reference['list'][$key][1] ?? '') . '" class="img-thumbnail rounded mr-1 mb-1">';
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