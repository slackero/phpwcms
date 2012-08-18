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


// Revision 502 Update Check
function phpwcms_revision_r502() {
		
	$status = true;
	
	// do former revision check – fallback to r497
	if(phpwcms_revision_check_temp('497') !== true) {
		$status = phpwcms_revision_check('497');
	}
	
	// add field for default language type and target ID to article, category
	$result = _dbQuery("SHOW COLUMNS FROM ".DB_PREPEND."phpwcms_articlecat LIKE 'acat_lang_type'", 'COUNT_SHOW');
	if(empty($result)) {
		$result = _dbQuery("ALTER TABLE ".DB_PREPEND."phpwcms_articlecat ADD acat_lang_type VARCHAR(255) NOT NULL DEFAULT ''", 'ALTER');
		_dbQuery("ALTER TABLE ".DB_PREPEND."phpwcms_articlecat ADD INDEX (acat_lang_type)", 'ALTER');
	}
	$result = _dbQuery("SHOW COLUMNS FROM ".DB_PREPEND."phpwcms_articlecat LIKE 'acat_lang_id'", 'COUNT_SHOW');
	if(empty($result)) {
		$result = _dbQuery("ALTER TABLE ".DB_PREPEND."phpwcms_articlecat ADD acat_lang_id INT(11) unsigned NOT NULL DEFAULT '0'", 'ALTER');
		_dbQuery("ALTER TABLE ".DB_PREPEND."phpwcms_articlecat ADD INDEX (acat_lang_id)", 'ALTER');
	}
	
	$result = _dbQuery("SHOW COLUMNS FROM ".DB_PREPEND."phpwcms_article LIKE 'article_lang_type'", 'COUNT_SHOW');
	if(empty($result)) {
		$result = _dbQuery("ALTER TABLE ".DB_PREPEND."phpwcms_article ADD article_lang_type VARCHAR(255) NOT NULL DEFAULT ''", 'ALTER');
		_dbQuery("ALTER TABLE ".DB_PREPEND."phpwcms_article ADD INDEX (article_lang_type)", 'ALTER');
	}
	$result = _dbQuery("SHOW COLUMNS FROM ".DB_PREPEND."phpwcms_article LIKE 'article_lang_id'", 'COUNT_SHOW');
	if(empty($result)) {
		$result = _dbQuery("ALTER TABLE ".DB_PREPEND."phpwcms_article ADD article_lang_id INT(11) unsigned NOT NULL DEFAULT '0'", 'ALTER');
		_dbQuery("ALTER TABLE ".DB_PREPEND."phpwcms_article ADD INDEX (article_lang_id)", 'ALTER');
	}

	return $status;
}

?>