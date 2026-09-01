<?php
/**
 * phpwcms
 *
 * @author Oliver Georgi <og@phpwcms.org>
 * @copyright Copyright (c) 2002-2026, Oliver Georgi
 * @license http://opensource.org/licenses/GPL-2.0 GNU GPL-2
 *
 **/


// Revision 541 Update Check
function phpwcms_revision_r541() {

	$status = true;


	if(!_dbColumnExists('phpwcms_articlecat', 'acat_breadcrumb')) {
		$insert = _dbQuery("ALTER TABLE `".DB_PREPEND."phpwcms_articlecat` ADD `acat_breadcrumb` INT(1) unsigned NOT NULL DEFAULT '0'", 'ALTER');
		if(!$insert) {
			$status = false;
		}

		// Reset cache values
		_setConfig('structure_array_vmode_all', '', 'frontend_render', 1);
		_setConfig('structure_array_vmode_editor', '', 'frontend_render', 1);
		_setConfig('structure_array_vmode_admin', '', 'frontend_render', 1);
	}

	if(_dbTableExists('phpwcms_shop_products') && !_dbColumnExists('phpwcms_shop_products', 'shopprod_unit')) {
		if(!($insert = _dbQuery("ALTER TABLE `".DB_PREPEND."phpwcms_shop_products` ADD `shopprod_unit` VARCHAR(100) NOT NULL DEFAULT ''", 'ALTER'))) {
			$status = false;
		}
	}

	return $status;
}
