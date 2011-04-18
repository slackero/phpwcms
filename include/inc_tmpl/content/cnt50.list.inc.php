<?php
/*************************************************************************************
   Copyright notice
   
   (c) 2002-2011 Oliver Georgi (oliver@phpwcms.de) // All rights reserved.
 
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


// Reference

$cinfo["result"]  = ($row["acontent_title"])?(cut_string($row["acontent_title"],'&#8230;', 55)):("");
$cinfo["result"] .= ($cinfo["result"] && $row["acontent_subtitle"])?(" / "):("");
$cinfo["result"] .= ($row["acontent_subtitle"])?(cut_string($row["acontent_subtitle"],'&#8230;', 55)):("");
							 
$reference = unserialize($row["acontent_form"]);
if(is_array($reference["list"]) && count($reference["list"])) {

	$imgx=0;
	$img_thumbs = '';
	$cinfo_img = '';

	// browse images and list available
	// will be visible only when aceessible
	foreach($reference["list"] as $key => $value) {
	
		$thumb_image = get_cached_image(
						array(	"target_ext"	=>	$reference["list"][$key][3],
								"image_name"	=>	$reference["list"][$key][2] . '.' . $reference["list"][$key][3],
								"thumb_name"	=>	md5($reference["list"][$key][2].$phpwcms["img_list_width"].$phpwcms["img_list_height"].$phpwcms["sharpen_level"])
        					  ));

		if($thumb_image != false) {
			if($imgx == 4) {
				$cinfo_img .= '<br><img src="img/leer.gif" alt="" border="0" width="1" height="2"><br>';
				$imgx = 0;
			}
			if($imgx) {
				$cinfo_img .= '<img src="img/leer.gif" alt="" border="0" width="2" height="1">';
			}
			$cinfo_img .= '<img src="'.PHPWCMS_IMAGES . $thumb_image[0] .'" border="0" '.$thumb_image[3].' alt="'.html_specialchars($reference["list"][$key][1]).'">';
			$imgx++;
		}
	}
	if($imgx) {
		if($cinfo["result"]) $cinfo["result"] .= '<br>';
		$cinfo["result"] .= $cinfo_img;
	}	
}

if($cinfo["result"]) { //Zeige Inhaltinfo
	echo "<tr><td>&nbsp;</td><td class=\"v10\">";
	echo "<a href=\"phpwcms.php?do=articles&amp;p=2&amp;s=1&amp;aktion=2&amp;id=".$article["article_id"]."&amp;acid=".$row["acontent_id"]."\">";
	echo $cinfo["result"]."</a></td><td>&nbsp;</td></tr>";
}

?>