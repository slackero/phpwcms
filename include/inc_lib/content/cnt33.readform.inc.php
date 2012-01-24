<?php
/*************************************************************************************
   Copyright notice
   
   (c) 2002-2012 Oliver Georgi <oliver@phpwcms.de> // All rights reserved.
 
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


// News
$content["template"]	= clean_slweg($_POST['template']);

$content['news']						= array();
$content['news']['news_lang']			= convertStringToArray( strtolower(clean_slweg($_POST['news_lang'])) );
$content['news']['news_category']		= convertStringToArray( clean_slweg($_POST['news_category']) );
$content['news']['news_sort']			= abs(intval($_POST['news_sort']));
$content['news']['news_paginate']		= empty($_POST['news_paginate']) ? 0 : 1;
$content['news']['news_prio']			= empty($_POST['news_prio']) ? 0 : 1;
$content['news']['news_paginate_count']	= empty($_POST['news_paginate_count']) ? '' : abs(intval($_POST['news_paginate_count']));
$content['news']['news_paginate_basis']	= abs(intval($_POST['news_paginate_basis']));
$content['news']['news_limit']			= abs(intval($_POST['news_limit']));
$content['news']['news_skip']			= abs(intval($_POST['news_skip']));
$content['news']['news_archive']		= abs(intval($_POST['news_archive']));
$content['news']['news_andor']			= strtoupper(clean_slweg($_POST['news_andor']));
$content['news']['news_archive_link']	= clean_slweg($_POST['news_archive_link']);
$content['news']['news_detail_link']	= clean_slweg($_POST['news_detail_link']);
								
if( empty($content['news']['news_sort']) || $content['news']['news_sort'] > 10 ) {
	$content['news']['news_sort'] = 9;
}
if( empty($content['news']['news_paginate_count']) ) {
	$content['news']['news_paginate_count'] = $content['news']['news_paginate'] ? 10 : '';
}
if( $content['news']['news_paginate_basis'] > 4 ) {
	$content['news']['news_paginate_basis'] = 3;
}
if( empty($content['news']['news_limit']) ) {
	$content['news']['news_limit'] = '';
}
if( empty($content['news']['news_skip']) ) {
	$content['news']['news_skip'] = '';
}
if( $content['news']['news_archive'] > 3 ) {
	$content['news']['news_sort'] = 1;
}
if( ! in_array($content['news']['news_andor'], array('OR', 'AND', 'NOT')) ) {
	$content['news']['news_andor'] = 'OR';
}

if(is_intval($content['news']['news_detail_link'])) {
	$content['news']['news_detail_link'] = intval($content['news']['news_detail_link']) ? intval($content['news']['news_detail_link']) : '';
}
if(is_intval($content['news']['news_archive_link'])) {
	$content['news']['news_archive_link'] = intval($content['news']['news_archive_link']) ? intval($content['news']['news_archive_link']) : '';
}

?>