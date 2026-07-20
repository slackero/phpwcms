<?php
/**
 * phpwcms
 *
 * @author Oliver Georgi <og@phpwcms.org>
 * @copyright Copyright (c) 2002-2026, Oliver Georgi
 * @license http://opensource.org/licenses/GPL-2.0 GNU GPL-2
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
