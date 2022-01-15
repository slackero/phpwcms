<?php
/**
 * cmsGO!
 *
 * @author Pixels & Points GmbH <info@pixels-points.ch>
 * @copyright Copyright (c) 2002-2022, Pixels & Points GmbH
 * @license https://www.pixels-points.ch/cmsgo-license.html Pixels & Points cmsGO! license
 *
 **/

// ----------------------------------------------------------------
// obligate check for cmsgo constants
if (!defined('CMSGO_ROOT')) {
  die("You Cannot Access This Script Directly, Have a Nice Day.");
}
// ----------------------------------------------------------------


// File List

$cinfo["result"]  = '';
if($row["acontent_title"]) {
  $cinfo["result"] .= getCleanSubString($row["acontent_title"], 55, '&#8230;');
}
if($cinfo["result"] && $row["acontent_subtitle"]) {
  $cinfo["result"] .= ' / ';
}
if($row["acontent_subtitle"]) {
  $cinfo["result"] .= getCleanSubString($row["acontent_subtitle"], 55, '&#8230;');
}
$cinfo["result"]  = html($cinfo["result"]);
if($row["acontent_template"]) {
  if($cinfo["result"]) {
    $cinfo["result"] .= ' / ';
  }
  $cinfo["result"] .= $BL['be_admin_struct_template'].': <strong>'.html($row["acontent_template"]).'</strong>';
}

if($cinfo["result"]) { //Zeige Inhaltinfo
  echo '<div class="col-sm-auto">';
  echo '<a href="cmsgo.php?do=articles&amp;p=2&amp;s=1&amp;aktion=2&amp;id='.$article["article_id"]."&amp;acid=".$row["acontent_id"].'">';
  echo $cinfo["result"].'</a></div>';
}
