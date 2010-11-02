<?php
/*************************************************************************************
   Copyright notice
   
   (c) 2002-2010 Oliver Georgi (oliver@phpwcms.de) // All rights reserved.
 
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


// Flash/HTML5 Media Player
$cinfo["result"] = array();

$cinfo["result"][] = html_specialchars(
						($row["acontent_title"] ? cut_string($row["acontent_title"],'&#8230;', 55) : '') .
						($cinfo["result"] && $row["acontent_subtitle"] ? " / " : "") .
						($row["acontent_subtitle"] ? cut_string($row["acontent_subtitle"],'&#8230;', 55) : '')
					);

if( $row["acontent_form"] = @unserialize($row["acontent_form"]) ) {

	// Flash
	if(!empty($row["acontent_form"]['fmp_internal_id'])) {
		$cinfo['result'][] = $BL['be_flash_media'] . ' ' . $BL['be_cnt_internal'] . ': ' . html_specialchars($row["acontent_form"]['fmp_internal_name']);
	}
	if(!empty($row["acontent_form"]['fmp_external_file'])) {
		$cinfo['result'][] = $BL['be_flash_media'] . ' ' . $BL['be_cnt_external'] . ': ' . html_specialchars($row["acontent_form"]['fmp_external_file']);
	}

	// H.264
	if(!empty($row["acontent_form"]['fmp_internal_id_h264'])) {
		$cinfo['result'][] = $BL['be_html5_media'] . ' ' . $BL['be_cnt_internal'] . ': ' . html_specialchars($row["acontent_form"]['fmp_internal_name_h264']);
	}
	if(!empty($row["acontent_form"]['fmp_external_file_h264'])) {
		$cinfo['result'][] = $BL['be_html5_media'] . ' ' . $BL['be_cnt_external'] . ': ' . html_specialchars($row["acontent_form"]['fmp_external_file_h264']);
	}

	// WebM
	if(!empty($row["acontent_form"]['fmp_internal_id_webm'])) {
		$cinfo['result'][] = $BL['be_html5_media'] . ' ' . $BL['be_cnt_internal'] . ': ' . html_specialchars($row["acontent_form"]['fmp_internal_name_webm']);
	}
	if(!empty($row["acontent_form"]['fmp_external_file_webm'])) {
		$cinfo['result'][] = $BL['be_html5_media'] . ' ' . $BL['be_cnt_external'] . ': ' . html_specialchars($row["acontent_form"]['fmp_external_file_webm']);
	}

	// Flash
	if(!empty($row["acontent_form"]['fmp_internal_id_ogg'])) {
		$cinfo['result'][] = $BL['be_html5_media'] . ' ' . $BL['be_cnt_internal'] . ': ' . html_specialchars($row["acontent_form"]['fmp_internal_name_ogg']);
	}
	if(!empty($row["acontent_form"]['fmp_external_file_ogg'])) {
		$cinfo['result'][] = $BL['be_html5_media'] . ' ' . $BL['be_cnt_external'] . ': ' . html_specialchars($row["acontent_form"]['fmp_external_file_ogg']);
	}

}

if(count($cinfo["result"])) {
	echo '<tr><td>&nbsp;</td><td class="v10">';
	echo '<a href="phpwcms.php?do=articles&amp;p=2&amp;s=1&amp;aktion=2&amp;id='.$article["article_id"].'&amp;acid='.$row["acontent_id"].'">';
	echo implode('<br />', $cinfo["result"]);
	echo '</a></td><td>&nbsp;</td></tr>';
}

?>