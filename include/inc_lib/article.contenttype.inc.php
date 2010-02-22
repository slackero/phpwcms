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

// Content Part Types
// DO NOT define 30 = used for modules
$wcs_content_type = array(
	 0 => $BL['be_ctype_plaintext'] ,
	 6 => $BL['be_ctype_html'],
	14 => $BL['be_ctype_wysywig'],
	11 => $BL['be_ctype_code'],
	 1 => $BL['be_ctype_textimage'],
	29 => $BL['be_ctype_imagesdiv'],
	31 => $BL['be_ctype_imagesspecial'],
	32 => $BL['be_ctype_tabs'],
	 2 => $BL['be_ctype_images'],
	 4 => $BL['be_ctype_bulletlist'],
   100 => $BL['be_ctype_ullist'],
	 3 => $BL['be_ctype_link'],
	 5 => $BL['be_ctype_linklist'],
	 8 => $BL['be_ctype_linkarticle'],
	33 => $BL['be_news'],
	15 => $BL['be_ctype_articlemenu'],
	 9 => $BL['be_ctype_multimedia'],
	 7 => $BL['be_ctype_filelist'],
	16 => $BL['be_ctype_ecard'],
	//17 => $BL['be_ctype_blog'],
	23 => $BL['be_ctype_simpleform'],
	10 => $BL['be_ctype_emailform'].' [old]',
	12 => $BL['be_ctype_newsletter'],
	13 => $BL['be_ctype_search'],
	18 => $BL['be_ctype_guestbook'],
	19 => $BL['be_ctype_sitemap'],
	//20 => $BL['be_ctype_bid'],
	21 => $BL['be_ctype_pages'],
	22 => $BL['be_ctype_rssfeed'],
	50 => $BL['be_ctype_reference'],
	51 => $BL['be_ctype_map'],
	52 => $BL['be_ctype_phpvar'],
	//53 => $BL['be_ctype_forum'],
	24 => $BL['be_ctype_alias'],
	89 => $BL['be_ctype_poll'], // jens poll
	26 => $BL['be_ctype_recipe'],
	27 => $BL['be_ctype_faq'],
	28 => $BL['be_ctype_felogin'],
	25 => $BL['be_ctype_flashplayer']
);

// set module content parts = 30
if(count($phpwcms['modules'])) {
	foreach($phpwcms['modules'] as $value) {
		if($value['cntp']) {
			$wcs_content_type[30] = $BL['be_ctype_module'];
			break;
		}
	}
}

?>