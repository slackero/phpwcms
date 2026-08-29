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

// Link Articles

$cinfo = [];
if (!empty($row['acontent_title'])) {
    $cinfo[] = html(getCleanSubString($row['acontent_title'], 55, '&#8230;'));
}
if (!empty($row['acontent_subtitle'])) {
    $cinfo[] = html(getCleanSubString($row['acontent_subtitle'], 55, '&#8230;'));
}

$cinfo_alink = unserialize($row['acontent_form'], ['allowed_classes' => false]);
$cinfo_alink = isset($cinfo_alink['alink_id']) ? $cinfo_alink['alink_id'] : explode(':', $row['acontent_alink']);

if (is_array($cinfo_alink)) {
    $ids = [];
    foreach ($cinfo_alink as $value) {
        if (intval($value)) {
            $ids[] = '<span class="badge badge-info fw-normal badge-align">AID: ' . intval($value) . '</span>';
        }
    }
    if (count($ids)) {
        $cinfo[] = implode(' ', $ids);
    }
}

$cinfo_result = implode(' / ', $cinfo);
if ($cinfo_result !== '') {
    echo '<div class="col-12">';
    echo '<a href="phpwcms.php?do=articles&amp;p=2&amp;s=1&amp;aktion=2&amp;id=' . $article['article_id'] . '&amp;acid=' . $row['acontent_id'] . '">';
    echo $cinfo_result . '</a></div>';
}