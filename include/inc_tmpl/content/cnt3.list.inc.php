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

// Link & Ext Link

$cinfo = [];
if (!empty($row['acontent_title'])) {
    $cinfo[] = html(getCleanSubString($row['acontent_title'], 55, '&#8230;'));
}
if (!empty($row['acontent_subtitle'])) {
    $cinfo[] = html(getCleanSubString($row['acontent_subtitle'], 55, '&#8230;'));
}

$cinfo_link = explode(' ', trim($row['acontent_text']));
$content['target'] = isset($cinfo_link[1]) ? trim($cinfo_link[1]) : '';
$content['link']   = trim($cinfo_link[0]);

if ($content['link'] !== '') {
    $cinfo[] = html($content['link'] . ($content['target'] !== '' ? ' ' . $content['target'] : ''));
}

$cinfo_result = implode(' / ', $cinfo);

echo '<div class="col-12">';
if ($content['link'] !== '') {
    echo '<a class="me-2" href="' . html($content['link']) . '" target="_blank" title="' . html($content['link']) . '">';
    echo '<i class="fa-solid fa-external-link-alt"></i>';
    echo '</a>';
}
if ($cinfo_result !== '') {
    echo '<a href="phpwcms.php?do=articles&amp;p=2&amp;s=1&amp;aktion=2&amp;id=' . $article['article_id'] . '&amp;acid=' . $row['acontent_id'] . '">' . $cinfo_result . '</a>';
}
echo '</div>';