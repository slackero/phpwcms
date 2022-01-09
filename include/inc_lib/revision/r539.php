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


// Revision 539 Update Check
function phpwcms_revision_r539() {

	$status = true;

	// do former revision check – fallback to r538
	if(phpwcms_revision_check_temp('538') !== true) {
		$status = phpwcms_revision_check('538');
	}

	$result = _dbQuery("SHOW COLUMNS FROM `".DB_PREPEND."phpwcms_article` WHERE Field='article_description'");
	if(isset($result[0]['Type']) && substr(strtolower($result[0]['Type']), 0, 7) == 'varchar') {
		$update = _dbQuery("ALTER TABLE `".DB_PREPEND."phpwcms_article` CHANGE `article_description` `article_description` text NOT NULL", 'ALTER');
		if(!$update) {
			$status = false;
		}
	}

	return $status;
}
