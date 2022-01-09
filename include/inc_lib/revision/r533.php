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


// Revision 533 Update Check
function phpwcms_revision_r533() {

	$status = true;

	// do former revision check – fallback to r532
	if(phpwcms_revision_check_temp('532') !== true) {
		$status = phpwcms_revision_check('532');
	}

	$result = _dbQuery("SHOW TABLES LIKE '".DB_PREPEND."phpwcms_shop_products'");

	if(!empty($result)) {

		$result = _dbQuery("SHOW COLUMNS FROM ".DB_PREPEND."phpwcms_shop_products LIKE 'shopprod_overwrite_meta'");
		if(empty($result)) {
			$result = _dbQuery("ALTER TABLE ".DB_PREPEND."phpwcms_shop_products ADD shopprod_overwrite_meta INT(1) NOT NULL DEFAULT '1'", 'ALTER');
		}

	}

	return $status;
}
