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



//sitemap

$CNT_TMP .= headline($crow["acontent_title"], $crow["acontent_subtitle"], $template_default["article"]);
$sitemap = unserialize($crow["acontent_form"]);
$sitemap['startid'] = intval($sitemap['startid']);

if($sitemap['articleimg']) {
	$sitemap['article_style'] = ' style="list-style-image: url('.$sitemap['articleimg'].');"';
} else {
	if(empty($sitemap['article_style'])) {
		$sitemap['article_style'] = '';
	}
}
if($sitemap['catimg']) {
	$sitemap['cat_style'] = ' style="list-style-image: url('.$sitemap['catimg'].');"';
} else {
	if(empty($sitemap['cat_style'])) {
		$sitemap['cat_style'] = '';
	}
}

$CNT_TMP .= $sitemap['before'];

$sitemap['c'] = '';
if($sitemap['classcount']) {
	if($sitemap['catclass']) $sitemap['c'] = ' class="'.$sitemap['catclass'].$counter.'"';
} else {
	if($sitemap['catclass']) $sitemap['c'] = ' class="'.$sitemap['catclass'].'"';
}
if($content['struct'][ $sitemap['startid'] ]['acat_nositemap']) {
	$CNT_TMP .= "<ul".$sitemap['c'].">\n<li".$sitemap['cat_style'].">"; //.$sitemap['c']
	$CNT_TMP .= '<a href="index.php?';
	if($content['struct'][ $sitemap['startid'] ]['acat_alias']) {
		$CNT_TMP .= $content['struct'][ $sitemap['startid'] ]['acat_alias'];
	} else {
		$CNT_TMP .= 'id='.$sitemap['startid'].',0,0,1,0,0';
	}
	$CNT_TMP .= '">'.html_specialchars($content['struct'][ $sitemap['startid'] ]['acat_name']).'</a>';
	if($sitemap["display"]) {
		$CNT_TMP .= build_sitemap_articlelist( $sitemap['startid'] , 0);
	}
	$CNT_TMP .= build_sitemap( $sitemap['startid'] , 0);
	$CNT_TMP .= "</li>\n</ul>";
}
$CNT_TMP .= $sitemap['after'];

unset($sitemap);

?>