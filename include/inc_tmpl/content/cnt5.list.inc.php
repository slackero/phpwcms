<?php
/**
 * phpwcms
 *
 * @author Oliver Georgi <og@phpwcms.org>
 * @copyright Copyright (c) 2002-2026, Oliver Georgi
 * @license http://opensource.org/licenses/GPL-2.0 GNU GPL-2
 *
 **/

// ----------------------------------------------------------------
// obligate check for phpwcms constants
if (!defined('PHPWCMS_ROOT')) {
    die("You Cannot Access This Script Directly, Have a Nice Day.");
}
// ----------------------------------------------------------------


// Link List

$cinfo[1] = cut_string($row["acontent_title"],'&#8230;', 55);
$cinfo[2] = cut_string($row["acontent_subtitle"],'&#8230;', 55);

$clink = explode(LF, $row["acontent_text"]);
$clink_liste = "";
if(count($clink)) {
    foreach($clink as $key => $value) {
        list($clink_name, $clink_link)   = explode("|", $value);
        $clink_name = trim($clink_name);
        $clink_link = explode(" ", trim($clink_link));
        $clink_target = isset($clink_link[1]) ? trim($clink_link[1]) : '';
        $clink_link = trim($clink_link[0]);
        $clink_liste .= "<a href=\"".$clink_link."\" target=\"_blank\" ";
        $clink_liste .= "title=\"Link: ".html($clink_link.trim(' '.$clink_target))."\">";
        $clink_liste .= '<i class="fas fa-link mr-1"></i>';
        $clink_liste .= html($clink_name ? $clink_name : $clink_link)."</a>\n";
    }
    unset($clink);
}
$cinfo["result"] = "";

foreach($cinfo as $value) {
 if($value) $cinfo["result"] .= $value."\n";
}
$cinfo["result"] = str_replace("\n", " / ", html(chop($cinfo["result"])));
if($cinfo["result"] || $clink_liste) { //Zeige Inhaltinfo
 echo "<div class=\"col-sm-auto\">";
 if($cinfo["result"]) {
    echo "<a href=\"phpwcms.php?do=articles&amp;p=2&amp;s=1&amp;aktion=2&amp;id=".$article["article_id"]."&amp;acid=";
    echo $row["acontent_id"]."\">".$cinfo["result"]."</a><br />";
 }
 echo nl2br(chop($clink_liste))."</td><td>&nbsp;</td></tr>";
}
