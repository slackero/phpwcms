<?php
/**
 * phpwcms
 *
 * @author Oliver Georgi <og@phpwcms.org>
 * @copyright Copyright (c) 2002-2026, Oliver Georgi
 * @license http://opensource.org/licenses/GPL-2.0 GNU GPL-2
 *
 **/


// Revision 528 Update Check
function phpwcms_revision_r528() {

	$status = true;


	// add field to disable forced 302 redirect for article to structure level
	if(!_dbColumnExists('phpwcms_articlecat', 'acat_disable301')) {
		$result = _dbQuery("ALTER TABLE ".DB_PREPEND."phpwcms_articlecat ADD acat_disable301 INT(1) unsigned NOT NULL DEFAULT '0'", 'ALTER');
	}

	return $status;
}
