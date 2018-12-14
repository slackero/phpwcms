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


// Tabs

$cinfo["result"]  = $row["acontent_title"] ? cut_string($row["acontent_title"],'&#8230;', 55) : '';
$cinfo["result"] .= ($cinfo["result"] && $row["acontent_subtitle"]) ? " / " : "";
$cinfo["result"] .= $row["acontent_subtitle"] ? cut_string($row["acontent_subtitle"],'&#8230;', 55) : '';

$row["acontent_form"] = @unserialize($row["acontent_form"]);
unset($row['acontent_form']['tabwysiwygoff'], $row['acontent_form']['tab_fieldgroup']);

if($cinfo["result"] || count($row["acontent_form"])) {
	echo "<div class=\"col-sm-auto\">";
	echo '<a href="cmsgo.php?do=articles&amp;p=2&amp;s=1&amp;aktion=2&amp;id='.$article["article_id"].'&amp;acid='.$row["acontent_id"].'">';
	echo $cinfo["result"];

	$cinfo["result"] = trim($cinfo["result"]) ? '<br />' : '';

	foreach($row["acontent_form"] as $value) {

		echo $cinfo["result"] . '&raquo; '.html($value['tabtitle'].($value['tabheadline'] != '' ? ' - '.$value['tabheadline'] : ''));
		$cinfo["result"] = '<br />';

	}

	echo "</a></div>";
}
