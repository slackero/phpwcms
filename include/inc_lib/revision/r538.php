<?php
/**
 * phpwcms content management system
 *
 * @author Oliver Georgi <oliver@phpwcms.de>
 * @copyright Copyright (c) 2002-2014, Oliver Georgi
 * @license http://opensource.org/licenses/GPL-2.0 GNU GPL-2
 * @link http://www.phpwcms.de
 *
 **/


// Revision 538 Update Check
function phpwcms_revision_r538() {

	$status = true;

	// do former revision check – fallback to r537
	if(phpwcms_revision_check_temp('537') !== true) {
		$status = phpwcms_revision_check('537');
	}

	$result = _dbQuery("SHOW COLUMNS FROM `".DB_PREPEND."phpwcms_articlecat` WHERE Field='acat_public'");
	if(isset($result[0]['Default']) && $result[0]['Default'] == 0) {
		$update = _dbQuery("ALTER TABLE `".DB_PREPEND."phpwcms_articlecat` CHANGE `acat_public` `acat_public` INT(1) NOT NULL DEFAULT '1'", 'ALTER');
		if(!$update) {
			$status = false;
		}
	}

	$result = _dbQuery("SHOW COLUMNS FROM `".DB_PREPEND."phpwcms_articlecat` WHERE Field='acat_alias'");
	if(isset($result[0]['Type']) && strpos($result[0]['Type'], '255') === false) {
		$update = _dbQuery("ALTER TABLE `".DB_PREPEND."phpwcms_articlecat` CHANGE `acat_alias` `acat_alias` VARCHAR(255) NOT NULL DEFAULT ''", 'ALTER');
		if(!$update) {
			$status = false;
		}
	}

	return $status;
}

?>