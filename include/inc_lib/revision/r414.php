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


// Revision 414 Update Check
function phpwcms_revision_r414() {
		
	$status = true;
	
	// Test against new shopping module fields
	$result = _dbQuery("SHOW TABLES LIKE '".DB_PREPEND."phpwcms_shop_products'");
	
	if(!empty($result)) {
	
		$result = _dbQuery("SHOW COLUMNS FROM ".DB_PREPEND."phpwcms_shop_products LIKE 'shopprod_special_price'");
		if(empty($result)) {
			$result = _dbQuery("ALTER TABLE ".DB_PREPEND."phpwcms_shop_products ADD shopprod_special_price TEXT NOT NULL", 'ALTER');
		}
		
		$result = _dbQuery("SHOW COLUMNS FROM ".DB_PREPEND."phpwcms_shop_products LIKE 'shopprod_track_view'");
		if(empty($result)) {
			$result = _dbQuery("ALTER TABLE ".DB_PREPEND."phpwcms_shop_products ADD shopprod_track_view INT(11) NOT NULL DEFAULT '0'", 'ALTER');
			$result = _dbQuery("ALTER TABLE ".DB_PREPEND."phpwcms_shop_products ADD INDEX (shopprod_track_view)", 'ALTER');
		}
		
		$result = _dbQuery("SHOW COLUMNS FROM ".DB_PREPEND."phpwcms_shop_products LIKE 'shopprod_lang'");
		if(empty($result)) {
			$result = _dbQuery("ALTER TABLE ".DB_PREPEND."phpwcms_shop_products ADD shopprod_lang VARCHAR(255) NOT NULL DEFAULT ''", 'ALTER');
			$result = _dbQuery("ALTER TABLE ".DB_PREPEND."phpwcms_shop_products ADD INDEX (shopprod_lang)", 'ALTER');
		}
		
	}

	// do former revision check
	// r407 - 413 required no action, so fallback to r406
	$r406 = '406';
	if(phpwcms_revision_check_temp($r406) !== true) {
		$status = phpwcms_revision_check($r406);
	}

	return $status;

}

?>