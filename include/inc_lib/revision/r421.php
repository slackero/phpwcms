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
