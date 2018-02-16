<?php
/**
 * cmsGo!
 *
 * @author Pixels & Points GmbH <info@pixels-points.ch>
 * @copyright Copyright (c) 2002-2017, Pixels & Points GmbH
 * @license https://www.pixels-points.ch/cmsgo-license.html Pixels & Points cmsGo! license
 *
 **/


// Revision 416 Update Check
function cmsgo_revision_r416() {
		
	$status = true;
	
	// Add column for default content part
	$result = _dbQuery("SHOW COLUMNS FROM ".DB_PREPEND."cmsgo_articlecat LIKE 'acat_cpdefault'");
	if(empty($result)) {
		$result = _dbQuery("ALTER TABLE ".DB_PREPEND."cmsgo_articlecat ADD acat_cpdefault INT(10) UNSIGNED NOT NULL DEFAULT '0'", 'ALTER');
	}

	// do former revision check
	// r415 requires no action, so fallback to r414
	$r414 = '414';
	if(cmsgo_revision_check_temp($r414) !== true) {
		$status = cmsgo_revision_check($r414);
	}

	return $status;

}
