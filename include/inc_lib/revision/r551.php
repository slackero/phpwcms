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


// Revision 551 Update Check
function phpwcms_revision_r551() {

	$status = true;

	// do former revision check – fallback to r550
	if(phpwcms_revision_check_temp('550') !== true) {
		$status = phpwcms_revision_check('550');
	}

	$result = _dbQuery("SHOW COLUMNS FROM `".DB_PREPEND."phpwcms_article` WHERE Field='article_meta'");
	if(!isset($result[0])) {
		$insert = _dbQuery("ALTER TABLE `".DB_PREPEND."phpwcms_article` ADD `article_meta` MEDIUMTEXT NOT NULL DEFAULT ''", 'ALTER');
		if(!$insert) {
			$status = false;
		}
	}

	return $status;
}
