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

// Link List

$cinfo = [];
if (!empty($row['acontent_title'])) {
    $cinfo[] = html(getCleanSubString($row['acontent_title'], 55, '&#8230;'));
}
if (!empty($row['acontent_subtitle'])) {
    $cinfo[] = html(getCleanSubString($row['acontent_subtitle'], 55, '&#8230;'));
}

$clinks = array_filter(array_map('trim', explode(LF, $row['acontent_text'])));
$clink_list = '';
if (count($clinks)) {
    foreach ($clinks as $link_item) {
        $parts = explode('|', $link_item);
        $clink_name = trim($parts[0]);
        $clink_target = '';
        $clink_link = '';
        if (isset($parts[1])) {
            $link_parts = explode(' ', trim($parts[1]));
            $clink_link = trim($link_parts[0]);
            $clink_target = isset($link_parts[1]) ? trim($link_parts[1]) : '';
        }
        if ($clink_link === '') {
            $clink_link = $clink_name;
        }
        if ($clink_list !== '') {
            $clink_list .= '<br>';
        }
        $clink_list .= '<a href="' . html($clink_link) . '" target="_blank" title="Link: ' . html($clink_link . ($clink_target ? ' ' . $clink_target : '')) . '">';
        $clink_list .= '<i class="fas fa-link me-1"></i>';
        $clink_list .= html($clink_name !== '' ? $clink_name : $clink_link) . '</a>';
    }
}

$cinfo_result = implode(' / ', $cinfo);

if ($cinfo_result !== '' || $clink_list !== '') {
    echo '<div class="col-12">';
    if ($cinfo_result !== '') {
        echo '<a href="phpwcms.php?do=articles&amp;p=2&amp;s=1&amp;aktion=2&amp;id=' . $article['article_id'] . '&amp;acid=' . $row['acontent_id'] . '">' . $cinfo_result . '</a>';
        if ($clink_list !== '') {
            echo '<br>';
        }
    }
    if ($clink_list !== '') {
        echo $clink_list;
    }
    echo '</div>';
}