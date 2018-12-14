<?php
/**
 * cmsGo!
 *
 * @author Pixels & Points GmbH <info@pixels-points.ch>
 * @copyright Copyright (c) 2002-2018, Pixels & Points GmbH
 * @license https://www.pixels-points.ch/cmsgo-license.html Pixels & Points cmsGo! license
 *
 **/

// ----------------------------------------------------------------
// obligate check for cmsgo constants
if(!defined('CMSGO_ROOT')) {
    die("You Cannot Access This Script Directly, Have a Nice Day.");
}
// ----------------------------------------------------------------

// WYSIWYG HTML

$cinfo[1] = cut_string($row["acontent_title"], '&#8230;', 55);
$cinfo[2] = cut_string($row["acontent_subtitle"], '&#8230;', 55);
$cinfo[3] = str_replace("\n", " ", cut_string($row["acontent_html"], '&#8230;', 150));
$cinfo["result"] = "";

foreach($cinfo as $value) {
    if($value) {
        $cinfo["result"] .= $value . "\n";
    }
}
$cinfo["result"] = str_replace("\n", " / ", html(chop($cinfo["result"])));
if($cinfo["result"]) { //Zeige Inhaltinfo
    echo "<div class=\"col align-items-stretch\">";
    echo "<a href=\"cmsgo.php?do=articles&amp;p=2&amp;s=1&amp;aktion=2&amp;id=" . $article["article_id"] . "&amp;acid=" . $row["acontent_id"] . "\">";
    echo $cinfo["result"] . "</a></div>";
}
