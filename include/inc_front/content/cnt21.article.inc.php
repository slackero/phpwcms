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



// Content Type external Pages

$CNT_TMP .= headline($crow["acontent_title"], $crow["acontent_subtitle"], $template_default["article"]);
$content['page_file'] = @unserialize($crow["acontent_form"]);
if(!$content["page_file"]['source']) {
	$content['page_file']['pfile'] = include_ext_php( $content['page_file']['pfile'] , 1 );
	if(strpos(strtolower($content['page_file']['pfile']), '<body') !== false) {
		$CNT_TMP .= trim( preg_replace("/.*?<body[^>]*?>(.*?)<\/body>.*?/si", '$1', $content['page_file']['pfile']) );
	} else {
		$CNT_TMP .= trim( $content['page_file']['pfile'] );
	}
} else {
	$CNT_TMP .= include_url( $content['page_file']['pfile'] );
}

?>