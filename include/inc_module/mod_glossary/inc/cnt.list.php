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
// obligate check for cmsGO! constants
if (!defined('CMSGO_ROOT')) {
	die("You Cannot Access This Script Directly, Have a Nice Day.");
}
// ----------------------------------------------------------------


// Glossary module content part listing

$cinfo["result"] = array();
$cinfo["result"][] = trim(html(cut_string($row["acontent_title"],'&#8230;', 55)));
$cinfo["result"][] = trim(html(cut_string($row["acontent_subtitle"],'&#8230;', 55)));
$cinfo["result"] = implode(' / ', $cinfo["result"]);
if($cinfo["result"]) { //Zeige Inhaltinfo
	echo '<tr><td>&nbsp;</td><td class="v10">';
	echo '<a href="cmsgo.php?do=articles&amp;p=2&amp;s=1&amp;aktion=2&amp;id='.$article["article_id"].'&amp;acid='.$row["acontent_id"].'">';
	echo $cinfo["result"].'</a></td><td>&nbsp;</td></tr>';
}
