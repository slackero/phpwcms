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
if (!defined('CMSGO_ROOT')) {
	die("You Cannot Access This Script Directly, Have a Nice Day.");
}
// ----------------------------------------------------------------


// Guestbook

$cinfo[1] = html(cut_string($row["acontent_title"],'&#8230;', 55));
$cinfo[2] = html(cut_string($row["acontent_subtitle"],'&#8230;', 55));
$cinfo["result"] = "";

foreach($cinfo as $value) {
	 if($value) $cinfo["result"] .= $value."\n";
}

$cinfo["result"] = str_replace("\n", " / ", chop($cinfo["result"]));
if($cinfo["result"]) { //Zeige Inhaltinfo
	 echo "<tr><td>&nbsp;</td><td class=\"v10\">";
	 echo "<a href=\"cmsgo.php?do=articles&amp;p=2&amp;s=1&amp;aktion=2&amp;id=".$article["article_id"]."&amp;acid=".$row["acontent_id"]."\">";
	 echo $cinfo["result"]."</a></td><td>&nbsp;</td></tr>";
}
