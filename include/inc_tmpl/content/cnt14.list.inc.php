<?php
/**
 * phpwcms content management system
 *
 * @author Oliver Georgi <og@phpwcms.org>
 * @copyright Copyright (c) 2002-2025, Oliver Georgi
 * @license http://opensource.org/licenses/GPL-2.0 GNU GPL-2
 * @link http://www.phpwcms.org
 *
 **/

// ----------------------------------------------------------------
// obligate check for phpwcms constants
if(!defined('PHPWCMS_ROOT')) {
    die("You Cannot Access This Script Directly, Have a Nice Day.");
}
// ----------------------------------------------------------------

// WYSIWYG HTML

$cinfo[1] = cut_string($row["acontent_title"], '&#8230;', 55);
$cinfo[2] = cut_string($row["acontent_subtitle"], '&#8230;', 55);
$cinfo[3] = str_replace("\n", " ", cut_string($row["acontent_html"], '&#8230;', 150));
$cinfo["result"] = "";

foreach($cinfo as $value) {
    if($value) {
        $cinfo["result"] .= $value . "\n";
    }
}
$cinfo["result"] = str_replace("\n", " / ", html(chop($cinfo["result"])));
if($cinfo["result"]) { //Zeige Inhaltinfo
    echo "<tr><td>&nbsp;</td><td class=\"v10\">";
    echo "<a href=\"phpwcms.php?do=articles&amp;p=2&amp;s=1&amp;aktion=2&amp;id=" . $article["article_id"] . "&amp;acid=" . $row["acontent_id"] . "\">";
    echo $cinfo["result"] . "</a></td><td>&nbsp;</td></tr>";
}
