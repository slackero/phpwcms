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


// Revision 406 Update Check
function phpwcms_revision_r406() {

	$status = true;


    // upgrade sysvalue fields +KH:24.11.2011 if no field exists
    $result = _dbQuery("SHOW COLUMNS FROM ".DB_PREPEND."phpwcms_sysvalue LIKE 'sysvalue_vartype'");

    // sysvalue_vartype
    if(empty($result)) {
		$result = _dbQuery("ALTER TABLE ".DB_PREPEND."phpwcms_sysvalue ADD sysvalue_vartype VARCHAR(255) NOT NULL DEFAULT ''", 'ALTER');
    } elseif(isset($result[0]['Type']) && $result[0]['Type'] == 'varchar(100)') {
		$result = _dbQuery("ALTER TABLE ".DB_PREPEND."phpwcms_sysvalue CHANGE sysvalue_vartype sysvalue_vartype VARCHAR(255) NOT NULL DEFAULT ''", 'ALTER');
    }

    // sysvalue_value
    $result = _dbQuery("SHOW COLUMNS FROM ".DB_PREPEND."phpwcms_sysvalue LIKE 'sysvalue_value'");
    if(empty($result)) {
		$result = _dbQuery("ALTER TABLE ".DB_PREPEND."phpwcms_sysvalue ADD sysvalue_value MEDIUMTEXT NOT NULL DEFAULT ''", 'ALTER');
    } elseif(isset($result[0]['Type']) && ($result[0]['Type'] == 'text' OR $result[0]['Type'] == 'mediumblob')) {
		$result = _dbQuery("ALTER TABLE ".DB_PREPEND."phpwcms_sysvalue CHANGE sysvalue_value sysvalue_value MEDIUMTEXT NOT NULL", 'ALTER');
    }

    // sysvalue_lastchange
    $result = _dbQuery("SHOW COLUMNS FROM ".DB_PREPEND."phpwcms_sysvalue LIKE 'sysvalue_lastchange'");
    if(empty($result)) {
		$result = _dbQuery("ALTER TABLE ".DB_PREPEND."phpwcms_sysvalue ADD sysvalue_lastchange INT(11) NOT NULL DEFAULT 0", 'ALTER');
    }
    // sysvalue_group
    $result = _dbQuery("SHOW COLUMNS FROM ".DB_PREPEND."phpwcms_sysvalue LIKE 'sysvalue_group'");
    if(empty($result)) {
		$result = _dbQuery("ALTER TABLE ".DB_PREPEND."phpwcms_sysvalue ADD sysvalue_group VARCHAR(255) NOT NULL DEFAULT ''", 'ALTER');
		$result = _dbQuery("ALTER TABLE ".DB_PREPEND."phpwcms_sysvalue ADD INDEX (sysvalue_group)", 'ALTER');
    }
    // sysvalue_status
    $result = _dbQuery("SHOW COLUMNS FROM ".DB_PREPEND."phpwcms_sysvalue LIKE 'sysvalue_status'");
    if(empty($result)) {
		$result = _dbQuery("ALTER TABLE ".DB_PREPEND."phpwcms_sysvalue ADD sysvalue_status INT(1) NOT NULL DEFAULT 0", 'ALTER');
		$result = _dbQuery("ALTER TABLE ".DB_PREPEND."phpwcms_sysvalue ADD INDEX (sysvalue_status)", 'ALTER');
    }

	// do former revision check
	// r404/405 required no action, so fallback to r403
	$r403 = '403';
	if(phpwcms_revision_check_temp($r403) !== true) {
		$status = phpwcms_revision_check($r403);
	}

	return $status;

}
