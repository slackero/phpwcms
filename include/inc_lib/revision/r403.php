<?php
/**
 * cmsGo!
 *
 * @author Pixels & Points GmbH <info@pixels-points.ch>
 * @copyright Copyright (c) 2002-2018, Pixels & Points GmbH
 * @license https://www.pixels-points.ch/cmsgo-license.html Pixels & Points cmsGo! license
 *
 **/


// Revision 403 Update Check
function cmsgo_revision_r403() {
	
	$status = true;
	
	// do former revision check
	// r402 required no action, so fallback to r401
	$r401 = '401';
	if(cmsgo_revision_check_temp($r401) !== true) {
		$status = cmsgo_revision_check($r401);
	}
	
	// check if article content tab field exists
	$result = _dbQuery("SHOW COLUMNS FROM ".DB_PREPEND."cmsgo_articlecontent LIKE 'acontent_tab'", 'COUNT_SHOW');
	
	if(empty($result)) {
		$result = _dbQuery("ALTER TABLE ".DB_PREPEND."cmsgo_articlecontent ADD acontent_tab VARCHAR(255) NOT NULL DEFAULT ''", 'ALTER');
	}
	
	// check if new structure level class field exists
	$result = _dbQuery("SHOW COLUMNS FROM ".DB_PREPEND."cmsgo_articlecat LIKE 'acat_class'", 'COUNT_SHOW');
	
	if(empty($result)) {
		$result = _dbQuery("ALTER TABLE ".DB_PREPEND."cmsgo_articlecat ADD acat_class VARCHAR(255) NOT NULL DEFAULT ''", 'ALTER');
	}
	
	// check if new structure level keywords field exists
	$result = _dbQuery("SHOW COLUMNS FROM ".DB_PREPEND."cmsgo_articlecat LIKE 'acat_keywords'", 'COUNT_SHOW');
	
	if(empty($result)) {
		$result = _dbQuery("ALTER TABLE ".DB_PREPEND."cmsgo_articlecat ADD acat_keywords VARCHAR(255) NOT NULL DEFAULT ''", 'ALTER');
	}
	
	// upgrade sysvalue fields
	$result = _dbQuery("SHOW COLUMNS FROM ".DB_PREPEND."cmsgo_sysvalue LIKE 'sysvalue_vartype'");
	if(isset($result[0]['Type']) && $result[0]['Type'] == 'varchar(100)') {
		$result = _dbQuery("ALTER TABLE ".DB_PREPEND."cmsgo_sysvalue CHANGE sysvalue_vartype sysvalue_vartype VARCHAR(255) NOT NULL DEFAULT ''", 'ALTER');
	}
	$result = _dbQuery("SHOW COLUMNS FROM ".DB_PREPEND."cmsgo_sysvalue LIKE 'sysvalue_value'");
	if(isset($result[0]['Type']) && $result[0]['Type'] == 'text') {
		$result = _dbQuery("ALTER TABLE ".DB_PREPEND."cmsgo_sysvalue CHANGE sysvalue_value sysvalue_value MEDIUMTEXT NOT NULL", 'ALTER');
	}
		
	return $status;

}
