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

// Flash/HTML5 Media Player
$cinfo = [];

$title_parts = [];
if (!empty($row['acontent_title'])) {
    $title_parts[] = html(getCleanSubString($row['acontent_title'], 55, '&#8230;'));
}
if (!empty($row['acontent_subtitle'])) {
    $title_parts[] = html(getCleanSubString($row['acontent_subtitle'], 55, '&#8230;'));
}
if (count($title_parts)) {
    $cinfo[] = implode(' / ', $title_parts);
}

$cform = @unserialize($row['acontent_form'], ['allowed_classes' => false]);
if (is_array($cform)) {
    if (!empty($cform['fmp_internal_id'])) {
        $cinfo[] = $BL['be_flash_media'] . ' ' . $BL['be_cnt_internal'] . ': ' . html($cform['fmp_internal_name'] ?? '');
    }
    if (!empty($cform['fmp_external_file'])) {
        $cinfo[] = $BL['be_flash_media'] . ' ' . $BL['be_cnt_external'] . ': ' . html($cform['fmp_external_file']);
    }
    if (!empty($cform['fmp_internal_id_h264'])) {
        $cinfo[] = $BL['be_html5_media'] . ' ' . $BL['be_cnt_internal'] . ': ' . html($cform['fmp_internal_name_h264'] ?? '');
    }
    if (!empty($cform['fmp_external_file_h264'])) {
        $cinfo[] = $BL['be_html5_media'] . ' ' . $BL['be_cnt_external'] . ': ' . html($cform['fmp_external_file_h264']);
    }
    if (!empty($cform['fmp_internal_id_webm'])) {
        $cinfo[] = $BL['be_html5_media'] . ' ' . $BL['be_cnt_internal'] . ': ' . html($cform['fmp_internal_name_webm'] ?? '');
    }
    if (!empty($cform['fmp_external_file_webm'])) {
        $cinfo[] = $BL['be_html5_media'] . ' ' . $BL['be_cnt_external'] . ': ' . html($cform['fmp_external_file_webm']);
    }
    if (!empty($cform['fmp_internal_id_ogg'])) {
        $cinfo[] = $BL['be_html5_media'] . ' ' . $BL['be_cnt_internal'] . ': ' . html($cform['fmp_internal_name_ogg'] ?? '');
    }
    if (!empty($cform['fmp_external_file_ogg'])) {
        $cinfo[] = $BL['be_html5_media'] . ' ' . $BL['be_cnt_external'] . ': ' . html($cform['fmp_external_file_ogg']);
    }
}

if (count($cinfo)) {
    echo '<div class="col-12">';
    echo '<a href="phpwcms.php?do=articles&amp;p=2&amp;s=1&amp;aktion=2&amp;id=' . $article['article_id'] . '&amp;acid=' . $row['acontent_id'] . '">';
    echo implode('<br>', $cinfo);
    echo '</a></div>';
}