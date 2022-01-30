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


// Revision 540 Update Check
function phpwcms_revision_r540() {

	$status = true;

	// do former revision check – fallback to r539
	if(phpwcms_revision_check_temp('539') !== true) {
		$status = phpwcms_revision_check('539');
	}

	$result = _dbQuery("SHOW COLUMNS FROM `".DB_PREPEND."phpwcms_article` WHERE Field='article_canonical'");
	if(!isset($result[0])) {
		$insert = _dbQuery("ALTER TABLE `".DB_PREPEND."phpwcms_article` ADD `article_canonical` VARCHAR(2000) NOT NULL DEFAULT ''", 'ALTER');
		if(!$insert) {
			$status = false;
		}
	}
	$result = _dbQuery("SHOW COLUMNS FROM `".DB_PREPEND."phpwcms_articlecat` WHERE Field='acat_canonical'");
	if(!isset($result[0])) {
		$insert = _dbQuery("ALTER TABLE `".DB_PREPEND."phpwcms_articlecat` ADD `acat_canonical` VARCHAR(2000) NOT NULL DEFAULT ''", 'ALTER');
		if(!$insert) {
			$status = false;
		}

		// Reset cache values
		_setConfig('structure_array_vmode_all', '', 'frontend_render', 1);
		_setConfig('structure_array_vmode_editor', '', 'frontend_render', 1);
		_setConfig('structure_array_vmode_admin', '', 'frontend_render', 1);
	}

	return $status;
}
