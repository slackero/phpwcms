<?php
/**
 * cmsGO!
 *
 * @author Pixels & Points GmbH <info@pixels-points.ch>
 * @copyright Copyright (c) 2002-2025, Pixels & Points GmbH
 * @license https://www.pixels-points.ch/cmsgo-license.html Pixels & Points cmsGO! license
 *
 **/


// Revision 541 Update Check
function cmsgo_revision_r541() {

	$status = true;

	// do former revision check – fallback to r540
	if(cmsgo_revision_check_temp('540') !== true) {
		$status = cmsgo_revision_check('540');
	}

	$result = _dbQuery("SHOW COLUMNS FROM `".DB_PREPEND."cmsgo_articlecat` WHERE Field='acat_breadcrumb'");
	if(!isset($result[0])) {
		$insert = _dbQuery("ALTER TABLE `".DB_PREPEND."cmsgo_articlecat` ADD `acat_breadcrumb` INT(1) unsigned NOT NULL DEFAULT '0'", 'ALTER');
		if(!$insert) {
			$status = false;
		}

		// Reset cache values
		_setConfig('structure_array_vmode_all', '', 'frontend_render', 1);
		_setConfig('structure_array_vmode_editor', '', 'frontend_render', 1);
		_setConfig('structure_array_vmode_admin', '', 'frontend_render', 1);
	}

	$result = _dbQuery("SHOW COLUMNS FROM `".DB_PREPEND."cmsgo_shop_products` WHERE Field='shopprod_unit'");
	if(!isset($result[0])) {
		if(!($insert = _dbQuery("ALTER TABLE `".DB_PREPEND."cmsgo_shop_products` ADD `shopprod_unit` VARCHAR(100) NOT NULL DEFAULT ''", 'ALTER'))) {
			$status = false;
		}
	}

	return $status;
}
