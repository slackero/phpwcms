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


// Multimedia

$cinfo[1] = html(cut_string($row["acontent_title"],'&#8230;', 55));
$cinfo[2] = html(cut_string($row["acontent_subtitle"],'&#8230;', 55));

$media["media"]			= unserialize($row["acontent_form"]);
$media["media_type"]	= $media["media"]['media_type'];
$media["media_player"]	= $media["media"]['media_player'];
$media["media_id"]		= $media["media"]["media_id"];
$media["media_cnt"]		= $media["media_id"] ? $media["media"]['media_name'] : $media["media"]['media_extern'];
switch($media["media_type"]) {
	case 0: $media["media_type"]="VIDEO"; break;
	case 1: $media["media_type"]="AUDIO"; break;
	case 2: $media["media_type"]="FLASH"; break;
}
switch($media["media_player"]) {
	case 0: $media["media_player"]="quicktime_player.gif"; break;
	case 1: $media["media_player"]="real_player.gif"; break;
	case 2: $media["media_player"]="windowsmedia_player.gif"; break;
	case 3: $media["media_player"]="flash_player.gif"; break;
}
$media["media_src"] =  $media["media_id"] ? "INTERNAL SOURCE" : "EXTERNAL SOURCE";
if($media["media_cnt"]) {
	$cinfo["media"]  = '<img src="img/symbole/'.$media["media_player"].'" border="0" alt="" align="left" style="margin-right:5px" />';
	$cinfo["media"] .= ($cinfo[1] || $cinfo[2]) ? '<br />' : '';
	$cinfo["media"] .= "<strong>".$media["media_src"]."<br />".$media["media_type"]."</strong>";
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
