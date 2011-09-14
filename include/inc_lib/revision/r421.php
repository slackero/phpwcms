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


// Revision 421 Update Check
function phpwcms_revision_r421() {
		
	$status = true;
	
	// Add column for default content part
	$result = _dbQuery("SHOW COLUMNS FROM ".DB_PREPEND."phpwcms_categories LIKE 'cat_sort'");
	if(empty($result)) {
		$result = _dbQuery("ALTER TABLE ".DB_PREPEND."phpwcms_categories ADD cat_sort INT(11) NOT NULL DEFAULT '0'", 'ALTER');
		$result = _dbQuery("ALTER TABLE ".DB_PREPEND."phpwcms_categories ADD INDEX (cat_sort)", 'ALTER');
	}

	// do former revision check – fallback to r416
	$r416 = '416';
	if(phpwcms_revision_check_temp($r416) !== true) {
		$status = phpwcms_revision_check($r416);
	}

	return $status;

}

?>