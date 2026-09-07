<?php
/**
 * phpwcms
 *
 * @author Oliver Georgi <og@phpwcms.org>
 * @copyright Copyright (c) 2002-2026, Oliver Georgi
 * @license http://opensource.org/licenses/GPL-2.0 GNU GPL-2
 *
 **/


// Revision 542 Update Check
function phpwcms_revision_r542() {

	$status = true;


	if(!_dbColumnExists('articlecat', 'acat_onepage')) {
		$insert = _dbQuery("ALTER TABLE `".DB_PREPEND."articlecat` ADD `acat_onepage` INT(1) unsigned NOT NULL DEFAULT '0'", 'ALTER');
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
