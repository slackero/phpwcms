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


// Revision 542 Update Check
function phpwcms_revision_r542() {

	$status = true;

	// do former revision check – fallback to r541
	if(phpwcms_revision_check_temp('541') !== true) {
		$status = phpwcms_revision_check('541');
	}

	$result = _dbQuery("SHOW COLUMNS FROM `".DB_PREPEND."phpwcms_articlecat` WHERE Field='acat_onepage'");
	if(!isset($result[0])) {
		$insert = _dbQuery("ALTER TABLE `".DB_PREPEND."phpwcms_articlecat` ADD `acat_onepage` INT(1) unsigned NOT NULL DEFAULT '0'", 'ALTER');
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
