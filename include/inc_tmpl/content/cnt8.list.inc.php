<?php
/**
 * phpwcms content management system
 *
 * @author Oliver Georgi <og@phpwcms.org>
 * @copyright Copyright (c) 2002-2022, Oliver Georgi
 * @license http://opensource.org/licenses/GPL-2.0 GNU GPL-2
 * @link http://www.phpwcms.org
 *
 **/

// ----------------------------------------------------------------
// obligate check for phpwcms constants
if (!defined('PHPWCMS_ROOT')) {
	die("You Cannot Access This Script Directly, Have a Nice Day.");
}
// ----------------------------------------------------------------


// Link Articles

$cinfo[1] = html(cut_string($row["acontent_title"],'&#8230;', 55));
$cinfo[2] = html(cut_string($row["acontent_subtitle"],'&#8230;', 55));

$cinfo_alink = unserialize($row["acontent_form"]);
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
	echo "<tr><td>&nbsp;</td><td class=\"v10\">";
	echo "<a href=\"phpwcms.php?do=articles&amp;p=2&amp;s=1&amp;aktion=2&amp;id=".$article["article_id"]."&amp;acid=".$row["acontent_id"]."\">";
	echo $cinfo["result"]."</a></td><td>&nbsp;</td></tr>";
}
