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
		$file_sql = "SELECT f_id, f_name, f_ext FROM ".DB_PREPEND."phpwcms_file WHERE f_public=1 AND f_aktiv=1 AND f_kid=1 AND f_trash=0 AND (".$fxa.")";
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
			$cinfo_files .= "<img src=\"img/icons/small_".extimg($fxb[$key]["fext"])."\" border=\"0\">";
			$cinfo_files .= "<img src=\"img/leer.gif\" width=\"3\" height=\"1\" border=\"0\">";
			$cinfo_files .= $fxb[$key]["fname"];
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
	echo "<tr><td>&nbsp;</td><td class=\"v10\">";
	echo "<a href=\"phpwcms.php?do=articles&amp;p=2&amp;s=1&amp;aktion=2&amp;id=".$article["article_id"]."&amp;acid=".$row["acontent_id"]."\">";
	echo $cinfo["result"]."</a></td><td>&nbsp;</td></tr>";
}
