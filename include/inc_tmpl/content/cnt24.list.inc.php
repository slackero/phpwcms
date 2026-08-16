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

// Alias ID
$content['alias'] = @unserialize($row['acontent_form'], ['allowed_classes' => false]);
$content['alias_link'] = '';
$content['alias_list_title'] = [];

if (!empty($row['acontent_title'])) {
    $content['alias_list_title'][] = html(getCleanSubString($row['acontent_title'], 55, '&#8230;'));
}
if (!empty($row['acontent_subtitle'])) {
    $content['alias_list_title'][] = html(getCleanSubString($row['acontent_subtitle'], 55, '&#8230;'));
}

echo '<div class="col-12">';
if (count($content['alias_list_title'])) {
    echo '<a href="phpwcms.php?do=articles&amp;p=2&amp;s=1&amp;aktion=2&amp;id=' . $article['article_id'] . '&amp;acid=' . $row['acontent_id'] . '">';
    echo implode(' / ', $content['alias_list_title']);
    echo '</a><br>';
}
echo $BL['be_alias_ID'] . ': ';
if (empty($content['alias']['alias_ID'])) {
    echo '&ndash;';
} else {
    $alias_id = intval($content['alias']['alias_ID']);
    $cntresult = _dbGet('phpwcms_articlecontent', '*', 'acontent_id=' . $alias_id . ' AND acontent_trash=0');

    if (isset($cntresult[0]['acontent_id'])) {
        echo '<span class="badge badge-info font-weight-normal badge-align mr-1">ID: ' . $alias_id . '</span>';
        echo '<a href="phpwcms.php?do=articles&amp;p=2&amp;s=1&amp;aktion=2&amp;id=' . $cntresult[0]['acontent_aid'] . '&amp;acid=' . $alias_id . '" target="_blank">';
        echo $BL['be_article_cnt_edit'] . ': ' . ($wcs_content_type[$cntresult[0]['acontent_type']] ?? 'ID ' . $alias_id) . '</a>';
    } else {
        echo '&ndash;';
    }
}
echo '</div>';