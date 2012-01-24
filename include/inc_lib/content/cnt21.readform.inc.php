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



// Content Type Page/ext. Content
$content["page_file"]['source'] = intval($_POST['cpage_source']);

if(!$content["page_file"]['source']) {

	$content["page_file"]['pfile'] = isset($_POST['cpage_file']) ? clean_slweg($_POST['cpage_file']) : '';
	if(!file_exists($content["page_file"]['pfile'])) {
		$content["page_file"]['pfile'] = '';
	}

} else {

	$content["page_file"]['pfile'] = clean_slweg($_POST['cpage_custom']);

	if(!file_exists($content["page_file"]['pfile'])) {
	
		list($content["page_file"]['checkurl']) = explode('?', $content["page_file"]['pfile']);

		if(!file_get_contents($content["page_file"]['checkurl'])) {
			$content["page_file"]['pfile'] = '';
		}
		unset($content["page_file"]['checkurl']);
	}

}


?>