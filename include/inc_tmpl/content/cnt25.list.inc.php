<?php
/**
 * phpwcms content management system
 *
 * @author Oliver Georgi <og@phpwcms.org>
 * @copyright Copyright (c) 2002-2022, Oliver Georgi
 * @license http://opensource.org/licenses/GPL-2.0 GNU GPL-2
 * @link http://www.phpwcms.org
 *
 **/

// ----------------------------------------------------------------
// obligate check for phpwcms constants
if (!defined('PHPWCMS_ROOT')) {
    die("You Cannot Access This Script Directly, Have a Nice Day.");
}
// ----------------------------------------------------------------


// Flash/HTML5 Media Player
$cinfo["result"] = array();

$cinfo["result"][] = html(
    ($row["acontent_title"] ? cut_string($row["acontent_title"],'&#8230;', 55) : '') .
    ($cinfo["result"] && $row["acontent_subtitle"] ? " / " : "") .
    ($row["acontent_subtitle"] ? cut_string($row["acontent_subtitle"],'&#8230;', 55) : '')
);

if( $row["acontent_form"] = @unserialize($row["acontent_form"]) ) {

    // Flash
    if(!empty($row["acontent_form"]['fmp_internal_id'])) {
        $cinfo['result'][] = $BL['be_flash_media'] . ' ' . $BL['be_cnt_internal'] . ': ' . html($row["acontent_form"]['fmp_internal_name']);
    }
    if(!empty($row["acontent_form"]['fmp_external_file'])) {
        $cinfo['result'][] = $BL['be_flash_media'] . ' ' . $BL['be_cnt_external'] . ': ' . html($row["acontent_form"]['fmp_external_file']);
    }

    // H.264
    if(!empty($row["acontent_form"]['fmp_internal_id_h264'])) {
        $cinfo['result'][] = $BL['be_html5_media'] . ' ' . $BL['be_cnt_internal'] . ': ' . html($row["acontent_form"]['fmp_internal_name_h264']);
    }
    if(!empty($row["acontent_form"]['fmp_external_file_h264'])) {
        $cinfo['result'][] = $BL['be_html5_media'] . ' ' . $BL['be_cnt_external'] . ': ' . html($row["acontent_form"]['fmp_external_file_h264']);
    }

    // WebM
    if(!empty($row["acontent_form"]['fmp_internal_id_webm'])) {
        $cinfo['result'][] = $BL['be_html5_media'] . ' ' . $BL['be_cnt_internal'] . ': ' . html($row["acontent_form"]['fmp_internal_name_webm']);
    }
    if(!empty($row["acontent_form"]['fmp_external_file_webm'])) {
        $cinfo['result'][] = $BL['be_html5_media'] . ' ' . $BL['be_cnt_external'] . ': ' . html($row["acontent_form"]['fmp_external_file_webm']);
    }

    // Flash
    if(!empty($row["acontent_form"]['fmp_internal_id_ogg'])) {
        $cinfo['result'][] = $BL['be_html5_media'] . ' ' . $BL['be_cnt_internal'] . ': ' . html($row["acontent_form"]['fmp_internal_name_ogg']);
    }
    if(!empty($row["acontent_form"]['fmp_external_file_ogg'])) {
        $cinfo['result'][] = $BL['be_html5_media'] . ' ' . $BL['be_cnt_external'] . ': ' . html($row["acontent_form"]['fmp_external_file_ogg']);
    }

}

if(count($cinfo["result"])) {
    echo '<tr><td>&nbsp;</td><td class="v10">';
    echo '<a href="phpwcms.php?do=articles&amp;p=2&amp;s=1&amp;aktion=2&amp;id='.$article["article_id"].'&amp;acid='.$row["acontent_id"].'">';
    echo implode('<br />', $cinfo["result"]);
    echo '</a></td><td>&nbsp;</td></tr>';
}
