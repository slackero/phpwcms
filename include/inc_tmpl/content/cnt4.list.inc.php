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

// Bullet List Table

$cinfo = [];
if (!empty($row['acontent_title'])) {
    $cinfo[] = html(getCleanSubString($row['acontent_title'], 55, '&#8230;'));
}
if (!empty($row['acontent_subtitle'])) {
    $cinfo[] = html(getCleanSubString($row['acontent_subtitle'], 55, '&#8230;'));
}

$cbullet_lines = array_filter(array_map('trim', explode("\n", $row['acontent_text'])));
$cbullet_preview = '';
if (count($cbullet_lines)) {
    $cbullet_preview = '<ul class="mb-0 ps-3">';
    $count = 0;
    foreach ($cbullet_lines as $b_line) {
        $cbullet_preview .= '<li>' . html(getCleanSubString($b_line, 80, '&#8230;')) . '</li>';
        $count++;
        if ($count >= 5) {
            if (count($cbullet_lines) > 5) {
                $cbullet_preview .= '<li class="text-muted">… (' . count($cbullet_lines) . ')</li>';
            }
            break;
        }
    }
    $cbullet_preview .= '</ul>';
}

$cinfo_result = implode(' / ', $cinfo);

if ($cinfo_result !== '' || $cbullet_preview !== '') {
    echo '<div class="col-12">';
    echo '<a href="phpwcms.php?do=articles&amp;p=2&amp;s=1&amp;aktion=2&amp;id=' . $article['article_id'] . '&amp;acid=' . $row['acontent_id'] . '">';
    if ($cinfo_result !== '') {
        echo $cinfo_result;
    }
    if ($cbullet_preview !== '') {
        echo $cbullet_preview;
    }
    echo '</a></div>';
}