<?php
/**
 * cmsGo!
 *
 * @author Pixels & Points GmbH <info@pixels-points.ch>
 * @copyright Copyright (c) 2002-2018, Pixels & Points GmbH
 * @license https://www.pixels-points.ch/cmsgo-license.html Pixels & Points cmsGo! license
 *
 **/


// Revision 421 Update Check
function cmsgo_revision_r421() {
		
	$status = true;
	
	// Add column for default content part
	$result = _dbQuery("SHOW COLUMNS FROM ".DB_PREPEND."cmsgo_categories LIKE 'cat_sort'");
	if(empty($result)) {
		$result = _dbQuery("ALTER TABLE ".DB_PREPEND."cmsgo_categories ADD cat_sort INT(11) NOT NULL DEFAULT '0'", 'ALTER');
		$result = _dbQuery("ALTER TABLE ".DB_PREPEND."cmsgo_categories ADD INDEX (cat_sort)", 'ALTER');
	}

	// do former revision check – fallback to r416
	$r416 = '416';
	if(cmsgo_revision_check_temp($r416) !== true) {
		$status = cmsgo_revision_check($r416);
	}

	return $status;

}
