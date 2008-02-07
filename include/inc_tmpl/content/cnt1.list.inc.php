<?php
/*************************************************************************************
   Copyright notice
   
   (c) 2002-2008 Oliver Georgi (oliver@phpwcms.de) // All rights reserved.
 
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


// Text with image
$cinfo[1] = cut_string($row["acontent_title"],'&#8230;', 55);
$cinfo[2] = cut_string($row["acontent_subtitle"],'&#8230;', 55);
$cinfo[3] = str_replace("\n", " ", cut_string($row["acontent_text"],'&#8230;', 150));	

// 0   :1       :2   :3        :4    :5     :6      :7       :8
// dbid:filename:hash:extension:width:height:caption:position:zoom
$cinfo_image = explode(":", $row["acontent_image"]);
if(isset($cinfo_image[2]) && is_array($cinfo_image) && count($cinfo_image)) {

	$thumb_image = get_cached_image(
			 					array(	"target_ext"	=>	$cinfo_image[3],
										"image_name"	=>	$cinfo_image[2] . '.' . $cinfo_image[3],
										"thumb_name"	=>	md5($cinfo_image[2].$phpwcms["img_list_width"].$phpwcms["img_list_height"].$phpwcms["sharpen_level"])
        							  )
								);

	if($thumb_image != false) {
		$cinfo_image = '<img src="'.PHPWCMS_IMAGES . $thumb_image[0] .'" border="0" '.$thumb_image[3].'>';
	} else {
		$cinfo_image = '';
	}
} else {
	$cinfo_image = '';
}
$cinfo["result"] = '';
foreach($cinfo as $value) {
	if($value) $cinfo["result"] .= $value."\n";
}
$cinfo["result"] = str_replace("\n", " / ", html_specialchars(trim($cinfo["result"])));
if($cinfo["result"] || $cinfo_image) { //Zeige Inhaltinfo
	echo "<tr><td>&nbsp;</td><td class=\"v10\">";
	echo "<a href=\"phpwcms.php?do=articles&p=2&s=1&aktion=2&id=".$article["article_id"]."&acid=".$row["acontent_id"]."\">";
	echo $cinfo["result"];
	if($cinfo["result"] && $cinfo_image) echo "<br />";
	echo $cinfo_image."</a></td><td>&nbsp;</td></tr>";
}

?>