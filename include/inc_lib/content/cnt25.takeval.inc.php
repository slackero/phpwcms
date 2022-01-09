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


// Flash Media Player

$fmp_data = array(
    'fmp_template'              => '',
    'fmp_width'                 => 320,
    'fmp_height'                => 240,
    'fmp_sort'                  => 1,
    'fmp_int_ext'               => 0,
    'fmp_internal_id'           => 0,
    'fmp_internal_name'         => '',
    'fmp_external_file'         => '',

    // H.264
    'fmp_int_ext_h264'          => 0,
    'fmp_internal_id_h264'      => 0,
    'fmp_internal_name_h264'    => '',
    'fmp_external_file_h264'    => '',

    // WebM
    'fmp_int_ext_webm'          => 0,
    'fmp_internal_id_webm'      => 0,
    'fmp_internal_name_webm'    => '',
    'fmp_external_file_webm'    => '',

    // Ogg
    'fmp_int_ext_ogg'           => 0,
    'fmp_internal_id_ogg'       => 0,
    'fmp_internal_name_ogg'     => '',
    'fmp_external_file_ogg'     => '',

    'fmp_caption'               => '',
    'fmp_link'                  => '',
    'fmp_marker'                => '',
    'fmp_img_id'                => 0,
    'fmp_img_name'              => '',
    'fmp_set_logo'              => '',
    'fmp_set_color'             => 'FFFFFF',
    'fmp_set_bgcolor'           => '000000',
    'fmp_set_showvolume'        => 1,
    'fmp_set_showeq'            => 0,
    'fmp_set_showdigits'        => 0,
    'fmp_set_showcontrols'      => 'bottom',
    'fmp_set_largecontrols'     => 0,
    'fmp_set_autostart'         => 0,
    'fmp_set_autohidecontrol'   => 0,
    'fmp_set_flashversion'      => '10',
    'fmp_set_showdownload'      => 0,
    'fmp_set_skin_html5'        => '',
    'fmp_player'                => 1,
    'fmp_set_volume'            => 80,
    'fmp_set_preload'           => 'auto'
);

if($content["id"]) {
    if($row["acontent_form"] = @unserialize($row["acontent_form"])) {
        $fmp_data = array_merge($fmp_data, $row["acontent_form"]);
    }
    $fmp_data['fmp_template'] = $row["acontent_template"];
}

// format color
if($fmp_data['fmp_set_bgcolor']) {
    $fmp_data['fmp_set_bgcolor'] = '#'.$fmp_data['fmp_set_bgcolor'];
}
if($fmp_data['fmp_set_color']) {
    $fmp_data['fmp_set_color'] = '#'.$fmp_data['fmp_set_color'];
}
