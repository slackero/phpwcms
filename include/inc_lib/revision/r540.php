<?php
/**
 * cmsGO!
 *
 * @author Pixels & Points GmbH <info@pixels-points.ch>
 * @copyright Copyright (c) 2002-2022, Pixels & Points GmbH
 * @license https://www.pixels-points.ch/cmsgo-license.html Pixels & Points cmsGO! license
 *
 **/


// Revision 540 Update Check
function cmsgo_revision_r540() {

	$status = true;

	// do former revision check – fallback to r539
	if(cmsgo_revision_check_temp('539') !== true) {
		$status = cmsgo_revision_check('539');
	}

	$result = _dbQuery("SHOW COLUMNS FROM `".DB_PREPEND."cmsgo_article` WHERE Field='article_canonical'");
	if(!isset($result[0])) {
		$insert = _dbQuery("ALTER TABLE `".DB_PREPEND."cmsgo_article` ADD `article_canonical` VARCHAR(2000) NOT NULL DEFAULT ''", 'ALTER');
		if(!$insert) {
			$status = false;
		}
	}
	$result = _dbQuery("SHOW COLUMNS FROM `".DB_PREPEND."cmsgo_articlecat` WHERE Field='acat_canonical'");
	if(!isset($result[0])) {
		$insert = _dbQuery("ALTER TABLE `".DB_PREPEND."cmsgo_articlecat` ADD `acat_canonical` VARCHAR(2000) NOT NULL DEFAULT ''", 'ALTER');
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
