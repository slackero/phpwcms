<?php
/**
 * cmsGO!
 *
 * @author Pixels & Points GmbH <info@pixels-points.ch>
 * @copyright Copyright (c) 2002-2020, Pixels & Points GmbH
 * @license https://www.pixels-points.ch/cmsgo-license.html Pixels & Points cmsGO! license
 *
 **/


// Revision 528 Update Check
function cmsgo_revision_r528() {

	$status = true;

	// do former revision check – fallback to r514
	if(cmsgo_revision_check_temp('514') !== true) {
		$status = cmsgo_revision_check('514');
	}

	// add field to disable forced 302 redirect for article to structure level
	$result = _dbQuery("SHOW COLUMNS FROM ".DB_PREPEND."cmsgo_articlecat LIKE 'acat_disable301'", 'COUNT_SHOW');
	if(empty($result)) {
		$result = _dbQuery("ALTER TABLE ".DB_PREPEND."cmsgo_articlecat ADD acat_disable301 INT(1) unsigned NOT NULL DEFAULT '0'", 'ALTER');
	}

	return $status;
}
