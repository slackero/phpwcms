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

// Tabs

$cinfo = [];
if (!empty($row['acontent_title'])) {
    $cinfo[] = html(getCleanSubString($row['acontent_title'], 55, '&#8230;'));
}
if (!empty($row['acontent_subtitle'])) {
    $cinfo[] = html(getCleanSubString($row['acontent_subtitle'], 55, '&#8230;'));
}

$tabs_form = @unserialize($row['acontent_form'], ['allowed_classes' => false]);
$tab_titles = [];
if (is_array($tabs_form)) {
    unset($tabs_form['tabwysiwygoff'], $tabs_form['tab_fieldgroup']);
    foreach ($tabs_form as $value) {
        if (is_array($value) && !empty($value['tabtitle'])) {
            $tab_titles[] = '&raquo; ' . html($value['tabtitle'] . (!empty($value['tabheadline']) ? ' - ' . $value['tabheadline'] : ''));
        }
    }
}

$cinfo_result = implode(' / ', $cinfo);
if ($cinfo_result !== '' || count($tab_titles)) {
    echo '<div class="col-12">';
    echo '<a href="phpwcms.php?do=articles&amp;p=2&amp;s=1&amp;aktion=2&amp;id=' . $article['article_id'] . '&amp;acid=' . $row['acontent_id'] . '">';
    if ($cinfo_result !== '') {
        echo $cinfo_result;
        if (count($tab_titles)) {
            echo '<br>';
        }
    }
    if (count($tab_titles)) {
        echo implode('<br>', $tab_titles);
    }
    echo '</a></div>';
}