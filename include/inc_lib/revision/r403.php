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


// Revision 403 Update Check
function phpwcms_revision_r403() {
	
	$status = true;
	
	// do former revision check
	// r402 required no action, so fallback to r401
	$r401 = '401';
	if(phpwcms_revision_check_temp($r401) !== true) {
		$status = phpwcms_revision_check($r401);
	}
	
	// check if article content tab field exists
	$result = _dbQuery("SHOW COLUMNS FROM ".DB_PREPEND."phpwcms_articlecontent LIKE 'acontent_tab'", 'COUNT_SHOW');
	
	if(empty($result)) {
		$result = _dbQuery("ALTER TABLE ".DB_PREPEND."phpwcms_articlecontent ADD acontent_tab VARCHAR(255) NOT NULL DEFAULT ''", 'ALTER');
	}
	
	// check if new structure level class field exists
	$result = _dbQuery("SHOW COLUMNS FROM ".DB_PREPEND."phpwcms_articlecat LIKE 'acat_class'", 'COUNT_SHOW');
	
	if(empty($result)) {
		$result = _dbQuery("ALTER TABLE ".DB_PREPEND."phpwcms_articlecat ADD acat_class VARCHAR(255) NOT NULL DEFAULT ''", 'ALTER');
	}
	
	// check if new structure level keywords field exists
	$result = _dbQuery("SHOW COLUMNS FROM ".DB_PREPEND."phpwcms_articlecat LIKE 'acat_keywords'", 'COUNT_SHOW');
	
	if(empty($result)) {
		$result = _dbQuery("ALTER TABLE ".DB_PREPEND."phpwcms_articlecat ADD acat_keywords VARCHAR(255) NOT NULL DEFAULT ''", 'ALTER');
	}
	
	// upgrade sysvalue fields
	$result = _dbQuery("SHOW COLUMNS FROM ".DB_PREPEND."phpwcms_sysvalue LIKE 'sysvalue_vartype'");
	if(isset($result[0]['Type']) && $result[0]['Type'] == 'varchar(100)') {
		$result = _dbQuery("ALTER TABLE ".DB_PREPEND."phpwcms_sysvalue CHANGE sysvalue_vartype sysvalue_vartype VARCHAR(255) NOT NULL DEFAULT ''", 'ALTER');
	}
	$result = _dbQuery("SHOW COLUMNS FROM ".DB_PREPEND."phpwcms_sysvalue LIKE 'sysvalue_value'");
	if(isset($result[0]['Type']) && $result[0]['Type'] == 'text') {
		$result = _dbQuery("ALTER TABLE ".DB_PREPEND."phpwcms_sysvalue CHANGE sysvalue_value sysvalue_value MEDIUMTEXT NOT NULL", 'ALTER');
	}
		
	return $status;

}

?>