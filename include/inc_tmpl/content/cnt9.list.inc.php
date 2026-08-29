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

// Multimedia

$cinfo = [];
if (!empty($row['acontent_title'])) {
    $cinfo[] = html(getCleanSubString($row['acontent_title'], 55, '&#8230;'));
}
if (!empty($row['acontent_subtitle'])) {
    $cinfo[] = html(getCleanSubString($row['acontent_subtitle'], 55, '&#8230;'));
}

$media = [];
$media['media']        = @unserialize($row['acontent_form'], ['allowed_classes' => false]);
$media['media_type']   = $media['media']['media_type'] ?? 0;
$media['media_player'] = $media['media']['media_player'] ?? 0;
$media['media_id']     = $media['media']['media_id'] ?? 0;
$media['media_cnt']    = $media['media_id'] ? ($media['media']['media_name'] ?? '') : ($media['media']['media_extern'] ?? '');

switch ($media['media_type']) {
    case 0: $type_label = 'VIDEO'; break;
    case 1: $type_label = 'AUDIO'; break;
    case 2: $type_label = 'FLASH'; break;
    default: $type_label = 'MEDIA';
}

switch ($media['media_player']) {
    case 0: $player_icon = 'fab fa-apple text-secondary'; break;
    case 1: $player_icon = 'fas fa-play-circle text-primary'; break;
    case 2: $player_icon = 'fab fa-windows text-info'; break;
    case 3: $player_icon = 'fab fa-adobe text-danger'; break;
    default: $player_icon = 'fas fa-play-circle text-primary';
}

$media_src = $media['media_id'] ? 'INTERNAL SOURCE' : 'EXTERNAL SOURCE';
$cinfo_media = '';
if (!empty($media['media_cnt'])) {
    $cinfo_media = '<i class="' . $player_icon . ' me-1" title="' . $type_label . '"></i> ';
    $cinfo_media .= '<strong>' . $media_src . ' [' . $type_label . ']</strong>';
}

$cinfo_result = implode(' / ', $cinfo);

if ($cinfo_result !== '' || $cinfo_media !== '') {
    echo '<div class="col-12">';
    echo '<a href="phpwcms.php?do=articles&amp;p=2&amp;s=1&amp;aktion=2&amp;id=' . $article['article_id'] . '&amp;acid=' . $row['acontent_id'] . '">';
    if ($cinfo_result !== '') {
        echo $cinfo_result;
        if ($cinfo_media !== '') {
            echo '<br>';
        }
    }
    if ($cinfo_media !== '') {
        echo $cinfo_media;
    }
    echo '</a></div>';
}