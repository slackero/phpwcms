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


// Plain Text

$cinfo[1] = html(cut_string($row["acontent_title"],'&#8230;', 55));
$cinfo[2] = html(cut_string($row["acontent_subtitle"],'&#8230;', 55));
$cinfo["result"] = "";
foreach($cinfo as $value) {
	if($value) $cinfo["result"] .= $value."\n";
}
$cinfo["result"] = str_replace("\n", " / ", trim($cinfo["result"]));


echo "<tr><td>&nbsp;</td><td class=\"v10\">";
if($cinfo["result"]) { //Zeige Inhaltinfo
	echo "<a href=\"phpwcms.php?do=articles&amp;p=2&amp;s=1&amp;aktion=2&amp;id=".$article["article_id"]."&amp;acid=";
	echo $row["acontent_id"]."\">".$cinfo["result"].'</a>';
}
$rssfeed = unserialize($row["acontent_form"]);
if($rssfeed['rssurl']) {
	echo ' <a href="'.html($rssfeed['rssurl']).'" target="_blank">';
	echo '<img src="img/symbole/xml.gif" width="36" height="14" border="0" style="vertical-align:middle;"></a>';
}
echo "</td><td>&nbsp;</td></tr>";
