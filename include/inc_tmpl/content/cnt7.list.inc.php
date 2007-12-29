<?php
/*************************************************************************************
   Copyright notice
   
   (c) 2002-2007 Oliver Georgi (oliver@phpwcms.de) // All rights reserved.
 
   This script is part of PHPWCMS. The PHPWCMS web content management system is
   free software; you can redistribute it and/or modify it under the terms of
   the GNU General Public License as published by the Free Software Foundation;
   either version 2 of the License, or (at your option) any later version.
  
   The GNU General Public License can be found at http://www.gnu.org/copyleft/gpl.html
   A copy is found in the textfile GPL.txt and important notices to the license 
   from the author is found in LICENSE.txt distributed with these scripts.
  
   This script is distributed in the hope that it will be useful, but WITHOUT ANY 
   WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A
   PARTICULAR PURPOSE.  See the GNU General Public License for more details.
 
   This copyright notice MUST APPEAR in all copies of the script!
*************************************************************************************/

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
		//unset($cinfo_files);
		$file_sql = "SELECT f_id, f_name, f_ext FROM ".DB_PREPEND."phpwcms_file WHERE f_public=1 AND f_aktiv=1".
					" AND f_kid=1 AND f_trash=0 AND (".$fxa.");"; //f_uid=".$_SESSION["wcs_user_id"]
		if($file_result = mysql_query($file_sql, $db) or die ("error while retrieving file list file's info")) {
			while($file_row = mysql_fetch_row($file_result)) {
				foreach($fxb as $key => $value) {
					if($fxb[$key]["fid"] == $file_row[0]) {
						$fxb[$key]["fname"] = html_specialchars($file_row[1]);
						$fxb[$key]["fext"] = $file_row[2];
					}
					// else {
					//	unset($fxb[$key]["fid"]);
					//}
				}
			}
		}
	}
	if(count($fxb)) {
		$fx = 0;
		$cinfo_files = '';
		foreach($fxb as $key => $value) {
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
	$cinfo["result"] = html_specialchars($cinfo["result"])."<br />".$cinfo_files;
} else {
	if($cinfo_files) {
		$cinfo["result"] = $cinfo_files;
	} else {
		$cinfo["result"] = html_specialchars($cinfo["result"]);
	}
}	
 
if($cinfo["result"]) { //Zeige Inhaltinfo
	echo "<tr><td>&nbsp;</td><td class=\"v10\">";
	echo "<a href=\"phpwcms.php?do=articles&p=2&s=1&aktion=2&id=".$article["article_id"]."&acid=".$row["acontent_id"]."\">";
	echo $cinfo["result"]."</a></td><td>&nbsp;</td></tr>";
}

?>