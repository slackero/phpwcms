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

    'fmp_template'              => clean_slweg($_POST['fmp_template']),
    'fmp_width'                 => intval($_POST['fmp_width']),
    'fmp_height'                => intval($_POST['fmp_height']),
    'fmp_sort'                  => empty($_POST['fmp_sort']) ? 0 : intval($_POST['fmp_sort']),

    // Flash
    'fmp_int_ext'               => empty($_POST['fmp_int_ext']) ? 0 : 1,
    'fmp_internal_id'           => intval($_POST['fmp_internal_id']),
    'fmp_internal_name'         => clean_slweg($_POST['fmp_internal_name']),
    'fmp_external_file'         => clean_slweg($_POST['fmp_external_file']),

    // H.264
    'fmp_int_ext_h264'          => empty($_POST['fmp_int_ext_h264']) ? 0 : 1,
    'fmp_internal_id_h264'      => intval($_POST['fmp_internal_id_h264']),
    'fmp_internal_name_h264'    => clean_slweg($_POST['fmp_internal_name_h264']),
    'fmp_external_file_h264'    => clean_slweg($_POST['fmp_external_file_h264']),

    // WebM
    'fmp_int_ext_webm'          => empty($_POST['fmp_int_ext_webm']) ? 0 : 1,
    'fmp_internal_id_webm'      => intval($_POST['fmp_internal_id_webm']),
    'fmp_internal_name_webm'    => clean_slweg($_POST['fmp_internal_name_webm']),
    'fmp_external_file_webm'    => clean_slweg($_POST['fmp_external_file_webm']),

    // Ogg
    'fmp_int_ext_ogg'           => empty($_POST['fmp_int_ext_ogg']) ? 0 : 1,
    'fmp_internal_id_ogg'       => intval($_POST['fmp_internal_id_ogg']),
    'fmp_internal_name_ogg'     => clean_slweg($_POST['fmp_internal_name_ogg']),
    'fmp_external_file_ogg'     => clean_slweg($_POST['fmp_external_file_ogg']),

    'fmp_caption'               => clean_slweg($_POST['fmp_caption']),
    'fmp_link'                  => clean_slweg($_POST['fmp_link']),
    'fmp_marker'                => clean_slweg($_POST['fmp_marker']),
    'fmp_img_id'                => intval($_POST['fmp_img_id']),
    'fmp_img_name'              => clean_slweg($_POST['fmp_img_name']),
    'fmp_set_logo'              => clean_slweg($_POST['fmp_set_logo']),
    'fmp_set_color'             => substr(preg_replace('/[^0-9a-f]/i', '', $_POST['fmp_set_color']), 0, 6),
    'fmp_set_bgcolor'           => substr(preg_replace('/[^0-9a-f]/i', '', $_POST['fmp_set_bgcolor']), 0, 6),
    'fmp_set_showvolume'        => empty($_POST['fmp_set_showvolume']) ? 0 : 1,
    'fmp_set_showeq'            => empty($_POST['fmp_set_showeq']) ? 0 : 1,
    'fmp_set_showdigits'        => empty($_POST['fmp_set_showdigits']) ? 0 : 1,
    'fmp_set_largecontrols'     => empty($_POST['fmp_set_largecontrols']) ? 0 : 1,
    'fmp_set_showcontrols'      => clean_slweg($_POST['fmp_set_showcontrols']),
    'fmp_set_autostart'         => empty($_POST['fmp_set_autostart']) ? 0 : 1,
    'fmp_set_autohidecontrol'   => empty($_POST['fmp_set_autohidecontrol']) ? 0 : 1,
    'fmp_set_showdownload'      => empty($_POST['fmp_set_showdownload']) ? 0 : 1,
    'fmp_set_skin_html5'        => clean_slweg($_POST['fmp_set_skin_html5']),
    'fmp_set_volume'            => empty($_POST['fmp_set_volume']) ? 0 : intval($_POST['fmp_set_volume']),
    'fmp_set_preload'           => empty($_POST['fmp_set_preload']) ? 'none' : clean_slweg($_POST['fmp_set_preload']), // auto, none, metadata

    'fmp_set_html5only'         => empty($_POST['fmp_set_html5only']) ? 0 : 1,
    'fmp_set_audio'             => empty($_POST['fmp_set_audio']) ? 0 : 1,
    'fmp_set_loop'              => empty($_POST['fmp_set_loop']) ? 0 : 1,
    'fmp_set_downloadbutton'    => empty($_POST['fmp_set_downloadbutton']) ? 0 : 1
);

// make some checks
// Flash
if(empty($fmp_data['fmp_external_file']) && $fmp_data['fmp_int_ext'] == 1) {
    $fmp_data['fmp_int_ext'] = 0;
} elseif(empty($fmp_data['fmp_internal_id']) && !empty($fmp_data['fmp_external_file']) && $fmp_data['fmp_int_ext'] == 0) {
    $fmp_data['fmp_int_ext'] = 1;
}
// H.264
if(empty($fmp_data['fmp_external_file_h264']) && $fmp_data['fmp_int_ext_h264'] == 1) {
    $fmp_data['fmp_int_ext_h264'] = 0;
} elseif(empty($fmp_data['fmp_internal_id_h264']) && !empty($fmp_data['fmp_external_file_h264']) && $fmp_data['fmp_int_ext_h264'] == 0) {
    $fmp_data['fmp_int_ext_h264'] = 1;
}
// WebM
if(empty($fmp_data['fmp_external_file_webm']) && $fmp_data['fmp_int_ext_webm'] == 1) {
    $fmp_data['fmp_int_ext_webm'] = 0;
} elseif(empty($fmp_data['fmp_internal_id_webm']) && !empty($fmp_data['fmp_external_file_webm']) && $fmp_data['fmp_int_ext_webm'] == 0) {
    $fmp_data['fmp_int_ext_webm'] = 1;
}
// Ogg
if(empty($fmp_data['fmp_external_file_ogg']) && $fmp_data['fmp_int_ext_ogg'] == 1) {
    $fmp_data['fmp_int_ext_ogg'] = 0;
} elseif(empty($fmp_data['fmp_internal_id_ogg']) && !empty($fmp_data['fmp_external_file_ogg']) && $fmp_data['fmp_int_ext_ogg'] == 0) {
    $fmp_data['fmp_int_ext_ogg'] = 1;
}
