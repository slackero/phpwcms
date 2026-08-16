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

// List

$cinfo = [];
if (!empty($row['acontent_title'])) {
    $cinfo[] = html(getCleanSubString($row['acontent_title'], 55, '&#8230;'));
}
if (!empty($row['acontent_subtitle'])) {
    $cinfo[] = html(getCleanSubString($row['acontent_subtitle'], 55, '&#8230;'));
}

$cinfo_list_type = @unserialize($row['acontent_form'], ['allowed_classes' => false]);
$list_tag = '&lt;ul&gt;';
if (isset($cinfo_list_type['list_type'])) {
    switch ($cinfo_list_type['list_type']) {
        case 1:
            $list_tag = '&lt;ol&gt;';
            break;
        case 2:
            $list_tag = '&lt;dl&gt;';
            break;
    }
}
if (!empty($row['acontent_text'])) {
    $cinfo[] = '<strong>' . $list_tag . '</strong> / ' . html(str_replace(["\r\n", "\r", "\n"], ' ', getCleanSubString($row['acontent_text'], 150, '&#8230;')));
}

$cinfo_result = implode(' / ', $cinfo);
if ($cinfo_result !== '') {
    echo '<div class="col-12">';
    echo '<a href="phpwcms.php?do=articles&amp;p=2&amp;s=1&amp;aktion=2&amp;id=' . $article['article_id'] . '&amp;acid=' . $row['acontent_id'] . '">';
    echo $cinfo_result . '</a></div>';
}