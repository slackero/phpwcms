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

// News

$cinfo["result"] = '';

$cinfo[1] = html(cut_string($row["acontent_title"], '&#8230;', 55));
$cinfo[2] = html(cut_string($row["acontent_subtitle"], '&#8230;', 55));
$cinfo[3] = @unserialize($row["acontent_form"]);
if(isset($cinfo[3]['news_category'])) {
    $cinfo[3] = implode(', ', $cinfo[3]['news_category']);
}

foreach($cinfo as $value) {
    if($value) {
        $cinfo["result"] .= $value . ' / ';
    }
}
$cinfo["result"] = trim(trim($cinfo["result"]), '/');
if($cinfo["result"]) { //Zeige Inhaltinfo
    echo "<div class=\"col-sm-auto\">";
    echo "<a href=\"cmsgo.php?do=articles&amp;p=2&amp;s=1&amp;aktion=2&amp;id=" . $article["article_id"] . "&amp;acid=" . $row["acontent_id"] . "\">";
    echo $cinfo["result"] . "</a></div>";
}
