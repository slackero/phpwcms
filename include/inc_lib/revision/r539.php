<?php
/**
 * phpwcms
 *
 * @author Oliver Georgi <og@phpwcms.org>
 * @copyright Copyright (c) 2002-2026, Oliver Georgi
 * @license http://opensource.org/licenses/GPL-2.0 GNU GPL-2
 *
 **/


// Revision 539 Update Check
function phpwcms_revision_r539() {

	$status = true;


	$result = _dbQuery("SHOW COLUMNS FROM `".DB_PREPEND."phpwcms_article` WHERE Field='article_description'");
	if(isset($result[0]['Type']) && substr(strtolower($result[0]['Type']), 0, 7) == 'varchar') {
		$update = _dbQuery("ALTER TABLE `".DB_PREPEND."phpwcms_article` CHANGE `article_description` `article_description` text NOT NULL", 'ALTER');
		if(!$update) {
			$status = false;
		}
	}

	return $status;
}
