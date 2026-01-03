<?php
/**
 * cmsGO!
 *
 * @author Pixels & Points GmbH <info@pixels-points.ch>
 * @copyright Copyright (c) 2002-2026, Pixels & Points GmbH
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

$cinfo["result"]  = ($row["acontent_title"])?(cut_string($row["acontent_title"],'&#8230;', 55)):("");
$cinfo["result"] .= ($cinfo["result"] && $row["acontent_subtitle"])?(" / "):("");
$cinfo["result"] .= ($row["acontent_subtitle"])?(cut_string($row["acontent_subtitle"],'&#8230;', 55)):("");

if($row["acontent_files"]) {
  $cinfo_files = explode(":", $row["acontent_files"]);
  if(count($cinfo_files)) {
    $fx  = 0;
    $fxa = "";
    $fxb = array();
    foreach($cinfo_files as $key => $value) {
      if($fx) $fxa .= " OR ";
      $fxa .= "f_id=".intval($value);
      $fxb[$key]["fid"] = intval($value);
      $fx++;
    }
    $file_sql = "SELECT f_id, f_name, f_ext FROM ".DB_PREPEND."cmsgo_file WHERE f_public=1 AND f_aktiv=1 AND f_kid=1 AND f_trash=0 AND (".$fxa.")";
    $file_result = _dbQuery($file_sql);
      if(isset($file_result[0]['f_id'])) {
      foreach($file_result as $file_row) {
        foreach($fxb as $key => $value) {
          if($fxb[$key]["fid"] == $file_row['f_id']) {
            $fxb[$key]["fname"] = html($file_row['f_name']);
            $fxb[$key]["fext"] = $file_row['f_ext'];
          }
        }
      }
    }
  }
  if(count($fxb)) {
    $fx = 0;
    $cinfo_files = '';
    foreach($fxb as $key => $value) {
      if(!isset($fxb[$key]["fname"])) {
        continue;
      }
      if($fx) $cinfo_files .= "<br />";
      $cinfo_files .=  '<i class="btn btn-sm btn-blue mb-1 fa fa-'.ext_icon($fxb[$key]["fext"]).'"></i> '.$fxb[$key]["fname"];
      $fx++;
    }
  }
} else {
  $cinfo_files = "";
}

$cinfo["result"] = trim($cinfo["result"]);
if($cinfo["result"] && $cinfo_files) {
    $cinfo["result"] = html($cinfo["result"])."<br />".$cinfo_files;
} elseif($cinfo_files) {
    $cinfo["result"] = $cinfo_files;
} else {
    $cinfo["result"] = html($cinfo["result"]);
}

if($cinfo["result"]) { //Zeige Inhaltinfo
  echo "<div class=\"col-sm-auto\">";
  echo "<a href=\"cmsgo.php?do=articles&amp;p=2&amp;s=1&amp;aktion=2&amp;id=".$article["article_id"]."&amp;acid=".$row["acontent_id"]."\">";
  echo $cinfo["result"]."</a></div>";
}
