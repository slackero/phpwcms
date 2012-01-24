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


// Revision 427 Update Check
function phpwcms_revision_r427() {
		
	$status = true;
	
	// do former revision check – fallback to r427
	$r421 = '421';
	if(phpwcms_revision_check_temp($r421) !== true) {
		$status = phpwcms_revision_check($r421);
	}
	
	// Change some missing default values for older releases
	$result = _dbQuery("ALTER TABLE ".DB_PREPEND."phpwcms_article CHANGE article_menutitle article_menutitle VARCHAR(255) NOT NULL DEFAULT  ''", 'ALTER');
	$result = _dbQuery("ALTER TABLE ".DB_PREPEND."phpwcms_article CHANGE article_description article_description VARCHAR(255) NOT NULL DEFAULT  ''", 'ALTER');

	return $status;

}

?>