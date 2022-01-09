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
