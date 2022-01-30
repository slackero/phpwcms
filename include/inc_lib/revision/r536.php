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


// Revision 536 Update Check
function phpwcms_revision_r536() {

	$status = true;

	// do former revision check – fallback to r535
	if(phpwcms_revision_check_temp('535') !== true) {
		$status = phpwcms_revision_check('535');
	}

	$result = _dbQuery("SHOW COLUMNS FROM `".DB_PREPEND."phpwcms_article` WHERE Field='article_public'");
	if(isset($result[0]['Default']) && $result[0]['Default'] == 0) {
		$update = _dbQuery("ALTER TABLE `".DB_PREPEND."phpwcms_article` CHANGE `article_public` `article_public` INT(1) NOT NULL DEFAULT '1'", 'ALTER');
		if(!$update) {
			$status = false;
		}
	}
	$result = _dbQuery("SHOW COLUMNS FROM `".DB_PREPEND."phpwcms_article` WHERE Field='article_opengraph'");
	if(!isset($result[0])) {
		$insert = _dbQuery("ALTER TABLE `".DB_PREPEND."phpwcms_article` ADD `article_opengraph` INT(1) UNSIGNED NOT NULL DEFAULT '1', ADD INDEX (`article_opengraph`)", 'ALTER');
		if(!$insert) {
			$status = false;
		}
	}

	return $status;
}
