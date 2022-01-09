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


// Revision 537 Update Check
function phpwcms_revision_r537() {

	$status = true;

	// do former revision check – fallback to r536
	if(phpwcms_revision_check_temp('536') !== true) {
		$status = phpwcms_revision_check('536');
	}

	$result = _dbQuery("SHOW COLUMNS FROM `".DB_PREPEND."phpwcms_articlecat` WHERE Field='acat_public'");
	if(isset($result[0]['Default']) && $result[0]['Default'] == 0) {
		$update = _dbQuery("ALTER TABLE `".DB_PREPEND."phpwcms_articlecat` CHANGE `acat_public` `acat_public` INT(1) NOT NULL DEFAULT '1'", 'ALTER');
		if(!$update) {
			$status = false;
		}
	}
	$result = _dbQuery("SHOW COLUMNS FROM `".DB_PREPEND."phpwcms_articlecat` WHERE Field='acat_opengraph'");
	if(!isset($result[0])) {
		$insert = _dbQuery("ALTER TABLE `".DB_PREPEND."phpwcms_articlecat` ADD `acat_opengraph` INT(1) UNSIGNED NOT NULL DEFAULT '1', ADD INDEX (`acat_opengraph`)", 'ALTER');
		if(!$insert) {
			$status = false;
		}
		// Reset cache values
		_setConfig('structure_array_vmode_all', '', 'frontend_render', 1);
		_setConfig('structure_array_vmode_editor', '', 'frontend_render', 1);
		_setConfig('structure_array_vmode_admin', '', 'frontend_render', 1);
	}
	$result = _dbQuery("SHOW COLUMNS FROM `".DB_PREPEND."phpwcms_content` WHERE Field='cnt_opengraph'");
	if(!isset($result[0])) {
		$insert = _dbQuery("ALTER TABLE `".DB_PREPEND."phpwcms_content` ADD `cnt_opengraph` INT(1) UNSIGNED NOT NULL DEFAULT '1', ADD INDEX (`cnt_opengraph`)", 'ALTER');
		if(!$insert) {
			$status = false;
		}
	}
	$result = _dbQuery("SHOW COLUMNS FROM `".DB_PREPEND."phpwcms_categories` WHERE Field='cat_opengraph'");
	if(!isset($result[0])) {
		$insert = _dbQuery("ALTER TABLE `".DB_PREPEND."phpwcms_categories` ADD `cat_opengraph` INT(1) UNSIGNED NOT NULL DEFAULT '1', ADD INDEX (`cat_opengraph`)", 'ALTER');
		if(!$insert) {
			$status = false;
		}
	}
	$result = _dbQuery("SHOW COLUMNS FROM `".DB_PREPEND."phpwcms_shop_products` WHERE Field='shopprod_opengraph'");
	if(!isset($result[0])) {
		$insert = _dbQuery("ALTER TABLE `".DB_PREPEND."phpwcms_shop_products` ADD `shopprod_opengraph` INT(1) UNSIGNED NOT NULL DEFAULT '1', ADD INDEX (`shopprod_opengraph`)", 'ALTER');
		if(!$insert) {
			$status = false;
		}
	}

	return $status;
}
