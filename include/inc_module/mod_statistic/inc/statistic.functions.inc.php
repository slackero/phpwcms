<?php
/**
 * cmsGO!
 *
 * @author Pixels & Points GmbH <info@pixels-points.ch>
 * @copyright Copyright (c) 2002-2024, Pixels & Points GmbH
 * @license https://www.pixels-points.ch/cmsgo-license.html Pixels & Points cmsGO! license
 *
 **/


function statistic_url($get='', $type='htmlentities') {
    $base = MODULE_HREF;
    if(is_array($get) && count($get)) {
        $get = implode('&', $get);
    } elseif(empty($get)) {
        $get = '';
    }
    if($get) $get = '&'.$get;
    if(empty($type) || $type != 'htmlentities') {
        $base = str_replace('&amp;', '&', $_controller_link);
    } else {
        $get = htmlentities($get);
    }
    return $base.$get;
}

function roundAll($a) {
    $a = floatval($a);
    return round($a, 2);
}
