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

// Article Menu

$cinfo = [];
if (!empty($row['acontent_title'])) {
    $cinfo[] = html(getCleanSubString($row['acontent_title'], 55, '&#8230;'));
}
if (!empty($row['acontent_subtitle'])) {
    $cinfo[] = html(getCleanSubString($row['acontent_subtitle'], 55, '&#8230;'));
}

$calist = @unserialize($row['acontent_form'], ['allowed_classes' => false]);
if (empty($calist['cat'])) {
    $cinfo[] = $BL['be_cnt_sitecurrent'];
} else {
    $cinfo[] = $BL['be_cnt_sitelevel'] . ' <span class="badge badge-info font-weight-normal badge-align">ID: ' . intval($calist['catid'] ?? 0) . '</span>';
}

$cinfo_result = implode(' / ', $cinfo);
if ($cinfo_result !== '') {
    echo '<div class="col-12">';
    echo '<a href="phpwcms.php?do=articles&amp;p=2&amp;s=1&amp;aktion=2&amp;id=' . $article['article_id'] . '&amp;acid=' . $row['acontent_id'] . '">';
    echo $cinfo_result . '</a></div>';
}