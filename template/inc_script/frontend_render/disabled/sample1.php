<?php

// ----------------------------------------------------------------
// obligate check for phpwcms constants
if (!defined('PHPWCMS_ROOT')) {
   die("You Cannot Access This Script Directly, Have a Nice Day.");
}
// ----------------------------------------------------------------


$content["all"] = preg_replace('/\{D:(.*?)\}/ie', 'myRT1("$1")', $content["all"]);

function myRT1($rtinfo) {

    $dateImage = '';
    $rtinfo = explode(',', $rtinfo);
    $startDate = strtotime($rtinfo[0]);
    $timeout = isset($rtinfo[1]) ? intval($rtinfo[1]) : 0;
    $timeout = $timeout * 24 * 3600;

    if(time() - $timeout < $startDate) {
        $dateImage = '<img src="picture/myRT1/'.$rtinfo[2].'" alt="'.$rtinfo[0].'" border="0">';
    }

    return $dateImage;
}
