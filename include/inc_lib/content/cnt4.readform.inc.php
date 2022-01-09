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
if(!defined('PHPWCMS_ROOT')) {
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
