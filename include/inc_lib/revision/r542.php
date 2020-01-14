<?php
/**
 * cmsGO!
 *
 * @author Pixels & Points GmbH <info@pixels-points.ch>
 * @copyright Copyright (c) 2002-2020, Pixels & Points GmbH
 * @license https://www.pixels-points.ch/cmsgo-license.html Pixels & Points cmsGO! license
 *
 **/


// Revision 542 Update Check
function cmsgo_revision_r542() {

	$status = true;

	// do former revision check – fallback to r541
	if(cmsgo_revision_check_temp('541') !== true) {
		$status = cmsgo_revision_check('541');
	}

	$result = _dbQuery("SHOW COLUMNS FROM `".DB_PREPEND."cmsgo_articlecat` WHERE Field='acat_onepage'");
	if(!isset($result[0])) {
		$insert = _dbQuery("ALTER TABLE `".DB_PREPEND."cmsgo_articlecat` ADD `acat_onepage` INT(1) unsigned NOT NULL DEFAULT '0'", 'ALTER');
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
