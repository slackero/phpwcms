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


// Revision 416 Update Check
function phpwcms_revision_r416() {

	$status = true;

	// Add column for default content part
	$result = _dbQuery("SHOW COLUMNS FROM ".DB_PREPEND."phpwcms_articlecat LIKE 'acat_cpdefault'");
	if(empty($result)) {
		$result = _dbQuery("ALTER TABLE ".DB_PREPEND."phpwcms_articlecat ADD acat_cpdefault INT(10) UNSIGNED NOT NULL DEFAULT '0'", 'ALTER');
	}

	// do former revision check
	// r415 requires no action, so fallback to r414
	$r414 = '414';
	if(phpwcms_revision_check_temp($r414) !== true) {
		$status = phpwcms_revision_check($r414);
	}

	return $status;

}
