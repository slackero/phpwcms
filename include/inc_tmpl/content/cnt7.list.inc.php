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

// File List

$cinfo = [];
if (!empty($row['acontent_title'])) {
    $cinfo[] = html(getCleanSubString($row['acontent_title'], 55, '&#8230;'));
}
if (!empty($row['acontent_subtitle'])) {
    $cinfo[] = html(getCleanSubString($row['acontent_subtitle'], 55, '&#8230;'));
}

$cinfo_files = '';
if (!empty($row['acontent_files'])) {
    $file_ids = array_filter(array_map('intval', explode(':', $row['acontent_files'])));
    if (count($file_ids)) {
        $file_sql = 'SELECT f_id, f_name, f_ext FROM ' . DB_PREPEND . 'file WHERE f_public=1 AND f_aktiv=1 AND f_kid=1 AND f_trash=0 AND f_id IN (' . implode(',', $file_ids) . ')';
        $file_result = _dbQuery($file_sql);
        if (is_array($file_result) && count($file_result)) {
            $file_map = [];
            foreach ($file_result as $f_row) {
                $file_map[$f_row['f_id']] = $f_row;
            }
            foreach ($file_ids as $fid) {
                if (isset($file_map[$fid])) {
                    if ($cinfo_files !== '') {
                        $cinfo_files .= '<br>';
                    }
                    $cinfo_files .= '<i class="fa-solid fa-' . ext_icon($file_map[$fid]['f_ext']) . ' fa-fw text-muted"></i> ' . html($file_map[$fid]['f_name']);
                }
            }
        }
    }
}

$cinfo_result = implode(' / ', $cinfo);

if ($cinfo_result !== '' || $cinfo_files !== '') {
    echo '<div class="col-12">';
    echo '<a href="phpwcms.php?do=articles&amp;p=2&amp;s=1&amp;aktion=2&amp;id=' . $article['article_id'] . '&amp;acid=' . $row['acontent_id'] . '">';
    if ($cinfo_result !== '') {
        echo $cinfo_result;
        if ($cinfo_files !== '') {
            echo '<br>';
        }
    }
    if ($cinfo_files !== '') {
        echo $cinfo_files;
    }
    echo '</a></div>';
}