<?php
/**
 * cmsGo!
 *
 * @author Pixels & Points GmbH <info@pixels-points.ch>
 * @copyright Copyright (c) 2002-2017, Pixels & Points GmbH
 * @license https://www.pixels-points.ch/cmsgo-license.html Pixels & Points cmsGo! license
 *
 **/

// ----------------------------------------------------------------
// obligate check for cmsgo constants
if(!defined('CMSGO_ROOT')) {
    die("You Cannot Access This Script Directly, Have a Nice Day.");
}
// ----------------------------------------------------------------

// Article Menu

$cinfo[1] = html(cut_string($row["acontent_title"], '&#8230;', 55));
$cinfo[2] = html(cut_string($row["acontent_subtitle"], '&#8230;', 55));
$calist = unserialize($row["acontent_form"]);
$cinfo[3] = ($cinfo[1] || $cinfo[2]) ? "<br />" : "";
$cinfo[3] .= (empty($calist['cat'])) ? $BL['be_cnt_sitecurrent'] : $BL['be_cnt_sitelevel'] . ' [ID:' . $calist['catid'] . ']';
$cinfo["result"] = "";
foreach($cinfo as $value) {
    if($value) {
        $cinfo["result"] .= $value . "\n";
    }
}
$cinfo["result"] = str_replace("\n", " / ", chop($cinfo["result"]));
if($cinfo["result"]) { //Zeige Inhaltinfo
    echo "<div class=\"col-sm-auto\">";
    echo "<a href=\"cmsgo.php?do=articles&amp;p=2&amp;s=1&amp;aktion=2&amp;id=" . $article["article_id"] . "&amp;acid=" . $row["acontent_id"] . "\">";
    echo $cinfo["result"] . "</a></div>";
}
