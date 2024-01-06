<?php
/**
 * cmsGO!
 *
 * @author Pixels & Points GmbH <info@pixels-points.ch>
 * @copyright Copyright (c) 2002-2024, Pixels & Points GmbH
 * @license https://www.pixels-points.ch/cmsgo-license.html Pixels & Points cmsGO! license
 *
 **/

// ----------------------------------------------------------------
// obligate check for cmsgo constants
if (!defined('CMSGO_ROOT')) {
    die("You Cannot Access This Script Directly, Have a Nice Day.");
}
// ----------------------------------------------------------------


// Link Articles

$cinfo[1] = html(cut_string($row["acontent_title"],'&#8230;', 55));
$cinfo[2] = html(cut_string($row["acontent_subtitle"],'&#8230;', 55));

$cinfo_alink = unserialize($row["acontent_form"], ['allowed_classes' => false]);
$cinfo_alink = isset($cinfo_alink['alink_id']) ? $cinfo_alink['alink_id'] : explode(':', $row["acontent_alink"]);

$cinfo[3] = '';
if(is_array($cinfo_alink)) {
    foreach($cinfo_alink as $value) {
        $cinfo[3] .= intval($value) ? "[".$value."] " : "";
    }
    $cinfo[3] = ($cinfo[3]) ? (($cinfo[1] || $cinfo[2])?"<br />":"").trim($cinfo[3]) : "";
}
$cinfo["result"] = "";
foreach($cinfo as $value) {
    if($value) $cinfo["result"] .= $value."\n";
}
$cinfo["result"] = str_replace("\n", " / ", chop($cinfo["result"]));
if($cinfo["result"]) { //Zeige Inhaltinfo
    echo "<div class=\"col-sm-auto\">";
    echo "<a href=\"cmsgo.php?do=articles&amp;p=2&amp;s=1&amp;aktion=2&amp;id=".$article["article_id"]."&amp;acid=".$row["acontent_id"]."\">";
    echo $cinfo["result"]."</a></div>";
}
