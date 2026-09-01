<?php
/**
 * phpwcms
 *
 * @author Oliver Georgi <og@phpwcms.org>
 * @copyright Copyright (c) 2002-2026, Oliver Georgi
 * @license http://opensource.org/licenses/GPL-2.0 GNU GPL-2
 *
 **/


// Revision 537 Update Check
function phpwcms_revision_r537() {

	$status = true;


	$result = _dbQuery("SHOW COLUMNS FROM `".DB_PREPEND."phpwcms_articlecat` WHERE Field='acat_public'");
	if(isset($result[0]['Default']) && (string)$result[0]['Default'] === '0') {
		$update = _dbQuery("ALTER TABLE `".DB_PREPEND."phpwcms_articlecat` CHANGE `acat_public` `acat_public` INT(1) NOT NULL DEFAULT '1'", 'ALTER');
		if(!$update) {
			$status = false;
		}
	}
	if(!_dbColumnExists('phpwcms_articlecat', 'acat_opengraph')) {
		$insert = _dbQuery("ALTER TABLE `".DB_PREPEND."phpwcms_articlecat` ADD `acat_opengraph` INT(1) UNSIGNED NOT NULL DEFAULT '1', ADD INDEX (`acat_opengraph`)", 'ALTER');
		if(!$insert) {
			$status = false;
		}
		// Reset cache values
		_setConfig('structure_array_vmode_all', '', 'frontend_render', 1);
		_setConfig('structure_array_vmode_editor', '', 'frontend_render', 1);
		_setConfig('structure_array_vmode_admin', '', 'frontend_render', 1);
	}
	if(_dbTableExists('phpwcms_content') && !_dbColumnExists('phpwcms_content', 'cnt_opengraph')) {
		$insert = _dbQuery("ALTER TABLE `".DB_PREPEND."phpwcms_content` ADD `cnt_opengraph` INT(1) UNSIGNED NOT NULL DEFAULT '1', ADD INDEX (`cnt_opengraph`)", 'ALTER');
		if(!$insert) {
			$status = false;
		}
	}
	if(_dbTableExists('phpwcms_categories') && !_dbColumnExists('phpwcms_categories', 'cat_opengraph')) {
		$insert = _dbQuery("ALTER TABLE `".DB_PREPEND."phpwcms_categories` ADD `cat_opengraph` INT(1) UNSIGNED NOT NULL DEFAULT '1', ADD INDEX (`cat_opengraph`)", 'ALTER');
		if(!$insert) {
			$status = false;
		}
	}
	if(_dbTableExists('phpwcms_shop_products') && !_dbColumnExists('phpwcms_shop_products', 'shopprod_opengraph')) {
		$insert = _dbQuery("ALTER TABLE `".DB_PREPEND."phpwcms_shop_products` ADD `shopprod_opengraph` INT(1) UNSIGNED NOT NULL DEFAULT '1', ADD INDEX (`shopprod_opengraph`)", 'ALTER');
		if(!$insert) {
			$status = false;
		}
	}

	return $status;
}
