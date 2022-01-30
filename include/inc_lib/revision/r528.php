<?php
/**
 * phpwcms content management system
 *
 * @author Oliver Georgi <og@phpwcms.org>
 * @copyright Copyright (c) 2002-2022, Oliver Georgi
 * @license http://opensource.org/licenses/GPL-2.0 GNU GPL-2
 * @link http://www.phpwcms.org
 *
 **/


// Revision 528 Update Check
function phpwcms_revision_r528() {

	$status = true;

	// do former revision check – fallback to r514
	if(phpwcms_revision_check_temp('514') !== true) {
		$status = phpwcms_revision_check('514');
	}

	// add field to disable forced 302 redirect for article to structure level
	$result = _dbQuery("SHOW COLUMNS FROM ".DB_PREPEND."phpwcms_articlecat LIKE 'acat_disable301'", 'COUNT_SHOW');
	if(empty($result)) {
		$result = _dbQuery("ALTER TABLE ".DB_PREPEND."phpwcms_articlecat ADD acat_disable301 INT(1) unsigned NOT NULL DEFAULT '0'", 'ALTER');
	}

	return $status;
}
