<?php
/**
 * phpwcms
 *
 * @author Oliver Georgi <og@phpwcms.org>
 * @copyright Copyright (c) 2002-2026, Oliver Georgi
 * @license http://opensource.org/licenses/GPL-2.0 GNU GPL-2
 *
 **/


// Revision 403 Update Check
function phpwcms_revision_r403() {

	$status = true;


	// check if article content tab field exists
	if(!_dbColumnExists('phpwcms_articlecontent', 'acontent_tab')) {
		$result = _dbQuery("ALTER TABLE ".DB_PREPEND."phpwcms_articlecontent ADD acontent_tab VARCHAR(255) NOT NULL DEFAULT ''", 'ALTER');
	}

	// check if new structure level class field exists
	if(!_dbColumnExists('phpwcms_articlecat', 'acat_class')) {
		$result = _dbQuery("ALTER TABLE ".DB_PREPEND."phpwcms_articlecat ADD acat_class VARCHAR(255) NOT NULL DEFAULT ''", 'ALTER');
	}

	// check if new structure level keywords field exists
	if(!_dbColumnExists('phpwcms_articlecat', 'acat_keywords')) {
		$result = _dbQuery("ALTER TABLE ".DB_PREPEND."phpwcms_articlecat ADD acat_keywords VARCHAR(255) NOT NULL DEFAULT ''", 'ALTER');
	}

	// upgrade sysvalue fields
	$result = _dbQuery("SHOW COLUMNS FROM ".DB_PREPEND."phpwcms_sysvalue LIKE 'sysvalue_vartype'");
	if(isset($result[0]['Type']) && $result[0]['Type'] == 'varchar(100)') {
		$result = _dbQuery("ALTER TABLE ".DB_PREPEND."phpwcms_sysvalue CHANGE sysvalue_vartype sysvalue_vartype VARCHAR(255) NOT NULL DEFAULT ''", 'ALTER');
	}
	$result = _dbQuery("SHOW COLUMNS FROM ".DB_PREPEND."phpwcms_sysvalue LIKE 'sysvalue_value'");
	if(isset($result[0]['Type']) && $result[0]['Type'] == 'text') {
		$result = _dbQuery("ALTER TABLE ".DB_PREPEND."phpwcms_sysvalue CHANGE sysvalue_value sysvalue_value MEDIUMTEXT NOT NULL", 'ALTER');
	}

	return $status;

}
