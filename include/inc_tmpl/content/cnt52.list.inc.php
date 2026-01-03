<?php
/**
 * cmsGO!
 *
 * @author Pixels & Points GmbH <info@pixels-points.ch>
 * @copyright Copyright (c) 2002-2026, Pixels & Points GmbH
 * @license https://www.pixels-points.ch/cmsgo-license.html Pixels & Points cmsGO! license
 *
 **/

// ----------------------------------------------------------------
// obligate check for cmsgo constants
if (!defined('CMSGO_ROOT')) {
    die("You Cannot Access This Script Directly, Have a Nice Day.");
}
// ----------------------------------------------------------------


// PHP variablen

$cinfo = array();
if($row["acontent_title"]) {
    $cinfo[] = html(cut_string($row["acontent_title"],'&#8230;', 55));
}
if($row["acontent_subtitle"]) {
    $cinfo[] = html(cut_string($row["acontent_subtitle"],'&#8230;', 55));
}
if($row["acontent_text"]) {
    $cinfo[] = str_replace("\n", " ", '<span class="code">'.html(cut_string($row["acontent_text"],'&#8230;', 150)).'</span>');
}

if(count($cinfo)) { //Zeige Inhaltinfo
    $cinfo = implode(" / ", $cinfo);
    echo "<div class=\"col-sm-auto\">";
    echo "<a href=\"cmsgo.php?do=articles&amp;p=2&amp;s=1&amp;aktion=2&amp;id=".$article["article_id"]."&amp;acid=".$row["acontent_id"]."\">";
    echo $cinfo."</a></div>";
}
