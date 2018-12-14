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


// RSS Feed

$cinfo[1] = html(cut_string($row["acontent_title"],'&#8230;', 55));
$cinfo[2] = html(cut_string($row["acontent_subtitle"],'&#8230;', 55));
$cinfo["result"] = "";
foreach($cinfo as $value) {
  if($value) $cinfo["result"] .= $value."\n";
}
$cinfo["result"] = str_replace("\n", " / ", trim($cinfo["result"]));


echo "<div class=\"col-sm-auto\">";
if($cinfo["result"]) { //Zeige Inhaltinfo
  echo "<a href=\"cmsgo.php?do=articles&amp;p=2&amp;s=1&amp;aktion=2&amp;id=".$article["article_id"]."&amp;acid=";
  echo $row["acontent_id"]."\">".$cinfo["result"].'</a>';
}
$rssfeed = unserialize($row["acontent_form"]);
if($rssfeed['rssurl']) {
  echo ' <a href="'.html($rssfeed['rssurl']).'" target="_blank">';
  echo '<i class="fa fa-rss"></i></a>';
}
echo "</div>";
