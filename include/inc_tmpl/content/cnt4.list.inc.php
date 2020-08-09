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
if(!defined('CMSGO_ROOT')) {
    die("You Cannot Access This Script Directly, Have a Nice Day.");
}
// ----------------------------------------------------------------

// Bullet List Table

$cinfo[1] = cut_string($row["acontent_title"], '&#8230;', 55);
$cinfo[2] = cut_string($row["acontent_subtitle"], '&#8230;', 55);

$cbullet = explode("\n", chop($row["acontent_text"]));
$cbullet["result"] = '';
if(count($cbullet)) {
    foreach($cbullet as $value) {
        if($value) {
            $cbullet["result"] .= "<li>" . $value . "</li>\n";
        }
    }
    $cbullet["result"] = "<ul>\n" . $cbullet["result"] . "</ul>";
}
$cinfo["result"] = "";

foreach($cinfo as $value) {
    if($value) {
        $cinfo["result"] .= $value . "\n";
    }
}

$cinfo["result"] = str_replace("\n", " / ", html(chop($cinfo["result"])));

if($cinfo["result"] || $cbullet["result"]) { //Zeige Inhaltinfo
    echo "<div class=\"col-sm-auto\">";
    echo "<a href=\"cmsgo.php?do=articles&amp;p=2&amp;s=1&amp;aktion=2&amp;id=" . $article["article_id"] . "&amp;acid=" . $row["acontent_id"] . "\">";
    echo $cinfo["result"] . $cbullet["result"] . "</a></div>";
}

