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
if (!defined('CMSGO_ROOT')) {
    die("You Cannot Access This Script Directly, Have a Nice Day.");
}
// ----------------------------------------------------------------


// custom elements

$cinfo["result"]  = $row["acontent_title"] ? cut_string($row["acontent_title"],'&#8230;', 55) : '';
$cinfo["result"] .= ($cinfo["result"] && $row["acontent_subtitle"]) ? " / " : "";
$cinfo["result"] .= $row["acontent_subtitle"] ? cut_string($row["acontent_subtitle"],'&#8230;', 55) : '';
$cinfo["result"] .= '<br />'.$BL['be_admin_struct_template'] . ':&nbsp;';
$cinfo["result"] .= $row["acontent_template"] ? cut_string($row["acontent_template"],'&#8230;', 55) : '';

// get custom array
$custom_data	= @unserialize($row["acontent_form"]);
if(count($custom_data['custom_elements'])) {
    $cinfo["result"] .= '<br />'.$BL['be_cnt_custom_entries'] . ':&nbsp;'.count($custom_data['custom_elements']);
}

if($cinfo["result"]) { //Zeige Inhaltinfo
    echo "<div class=\"col-sm-auto\">";
    echo "<a href=\"cmsgo.php?do=articles&amp;p=2&amp;s=1&amp;aktion=2&amp;id=".$article["article_id"]."&amp;acid=".$row["acontent_id"]."\">";
    echo $cinfo["result"]."</a></div>";
}
