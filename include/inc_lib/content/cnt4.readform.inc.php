<?php
/**
 * cmsGO!
 *
 * @author Pixels & Points GmbH <info@pixels-points.ch>
 * @copyright Copyright (c) 2002-2021, Pixels & Points GmbH
 * @license https://www.pixels-points.ch/cmsgo-license.html Pixels & Points cmsGO! license
 *
 **/

// ----------------------------------------------------------------
// obligate check for cmsgo constants
if(!defined('CMSGO_ROOT')) {
    die("You Cannot Access This Script Directly, Have a Nice Day.");
}
// ----------------------------------------------------------------

// Content Type Bullet List Table
$content["text"] = html(slweg($_POST["ctext"], 65500));
$cbullet = explode(LF, $content["text"]);
if(count($cbullet)) {
    foreach($cbullet as $key => $value) {
        if(trim($value)) {
            $cbullet[ $key ] = trim($value);
        } else {
            unset($cbullet[ $key ]);
        }
    }
    $content["text"] = implode(LF, $cbullet);
} else {
    $content["text"] = '';
}

$content["template"] = clean_slweg($_POST['template']);
