<?php
/**
 * phpwcms content management system
 *
 * @author Oliver Georgi <oliver@phpwcms.de>
 * @copyright Copyright (c) 2002-2014, Oliver Georgi
 * @license http://opensource.org/licenses/GPL-2.0 GNU GPL-2
 * @link http://www.phpwcms.de
 *
 **/

// ----------------------------------------------------------------
// obligate check for phpwcms constants
if (!defined('PHPWCMS_ROOT')) {
   die("You Cannot Access This Script Directly, Have a Nice Day.");
}
// ----------------------------------------------------------------


// Glossary module content part listing

$cinfo["result"] = array();
$cinfo["result"][] = trim(html_specialchars(cut_string($row["acontent_title"],'&#8230;', 55)));
$cinfo["result"][] = trim(html_specialchars(cut_string($row["acontent_subtitle"],'&#8230;', 55)));
$cinfo["result"] = implode(' / ', $cinfo["result"]);
if($cinfo["result"]) { //Zeige Inhaltinfo
	echo '<tr><td>&nbsp;</td><td class="v10">';
	echo '<a href="phpwcms.php?do=articles&amp;p=2&amp;s=1&amp;aktion=2&amp;id='.$article["article_id"].'&amp;acid='.$row["acontent_id"].'">';
	echo $cinfo["result"].'</a></td><td>&nbsp;</td></tr>';
}

?>