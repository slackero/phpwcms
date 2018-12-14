<?php
/**
 * cmsGo!
 *
 * @author Pixels & Points GmbH <info@pixels-points.ch>
 * @copyright Copyright (c) 2002-2018, Pixels & Points GmbH
 * @license https://www.pixels-points.ch/cmsgo-license.html Pixels & Points cmsGo! license
 *
 **/


// Revision 533 Update Check
function cmsgo_revision_r533() {

	$status = true;

	// do former revision check – fallback to r532
	if(cmsgo_revision_check_temp('532') !== true) {
		$status = cmsgo_revision_check('532');
	}

	$result = _dbQuery("SHOW TABLES LIKE '".DB_PREPEND."cmsgo_shop_products'");

	if(!empty($result)) {

		$result = _dbQuery("SHOW COLUMNS FROM ".DB_PREPEND."cmsgo_shop_products LIKE 'shopprod_overwrite_meta'");
		if(empty($result)) {
			$result = _dbQuery("ALTER TABLE ".DB_PREPEND."cmsgo_shop_products ADD shopprod_overwrite_meta INT(1) NOT NULL DEFAULT '1'", 'ALTER');
		}

	}

	return $status;
}
