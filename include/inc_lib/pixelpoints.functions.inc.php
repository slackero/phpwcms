<?php
/**
 * cmsGO!
 *
 * @author Pixels & Points GmbH <info@pixels-points.ch>
 * @copyright Copyright (c) 2002-2020, Pixels & Points GmbH
 * @license https://www.pixels-points.ch/cmsgo-license.html Pixels & Points cmsGO! license
 *
 **/

// ----------------------------------------------------------------
// obligate check for cmsgo constants
if (!defined('CMSGO_ROOT')) {
    die("You Cannot Access This Script Directly, Have a Nice Day.");
}
// ----------------------------------------------------------------

// general functions used in backend only
function pp_subnavtext($text, $link, $is, $should, $getback=1, $js='') {
    //generate ul based subnavigation based on text
    $id = "subnavid".generic_string(5);
    $sn = '';
    if($is == $should) {
        $sn .= '<li class="subnavactive"><a href="'.$link.'">'.$text.'</a></li>';
    } else {
        $sn .= '<li class="subnavinactive"><a href="'.$link.'" ' .$js.'>'.$text."</a></li>";
    }
    $sn .= "\n";
    if(!$getback) {
        return $sn;
    } else {
        echo $sn;
    }
    return null;
}

function pp_subnavtextext($text, $link, $target='_blank', $getback=1) {
    //generate ul based subnavigation based on text and links to new page
    $id  = 'subnavid'.generic_string(5);
    $sn  = '<li class="subnavinactive"><a href="'.$link.'" target="'.$target.'" >'.$text.'</a></li>';
    $sn .= "\n";

    if(!$getback) {
        return $sn;
    } else {
        echo $sn;
    }
}