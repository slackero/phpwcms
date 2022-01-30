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
	echo "<tr><td>&nbsp;</td><td class=\"v10\">";
	echo "<a href=\"phpwcms.php?do=articles&amp;p=2&amp;s=1&amp;aktion=2&amp;id=".$article["article_id"]."&amp;acid=".$row["acontent_id"]."\">";
	echo $cinfo."</a></td><td>&nbsp;</td></tr>";
}
