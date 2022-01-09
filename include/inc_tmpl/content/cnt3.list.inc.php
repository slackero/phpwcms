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
if(!defined('PHPWCMS_ROOT')) {
    die("You Cannot Access This Script Directly, Have a Nice Day.");
}
// ----------------------------------------------------------------

// Redirect

$cinfo[1] = cut_string($row["acontent_title"], '&#8230;', 55);
$cinfo[2] = cut_string($row["acontent_subtitle"], '&#8230;', 55);
$cinfo["result"] = "";
$content["link"] = explode(" ", $row["acontent_redirect"]);
if(isset($content["link"][1])) {
    $content["target"] = $content["link"][1];
} else {
    $content["target"] = '';
}
$content["link"] = $content["link"][0];

foreach($cinfo as $value) {
    if($value) {
        $cinfo["result"] .= $value . "\n";
    }
}
$cinfo["result"] .= str_replace("\n", " / ", html(chop($cinfo["result"])));
echo "<tr><td>&nbsp;</td><td class=\"v10\">";
echo "<a href=\"" . $content["link"] . "\" target=\"_blank\">";
echo "<img src=\"img/symbole/link_to.gif\" border=\"0\" ";
echo "title=\"test link to: " . html($content["link"]) . "\"></a>";
if($cinfo["result"]) { //Zeige Inhaltinfo
    echo " <a href=\"phpwcms.php?do=articles&amp;p=2&amp;s=1&amp;aktion=2&amp;id=" . $article["article_id"];
    echo "&amp;acid=" . $row["acontent_id"] . "\">" . $cinfo["result"] . "</a>";
}
echo "</td><td>&nbsp;</td></tr>";
