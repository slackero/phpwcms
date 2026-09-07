<?php
/**
 * phpwcms
 *
 * @author Oliver Georgi <og@phpwcms.org>
 * @copyright Copyright (c) 2002-2026, Oliver Georgi
 * @license http://opensource.org/licenses/GPL-2.0 GNU GPL-2
 *
 **/


// Revision 534 Update Check
function phpwcms_revision_r534() {

	$status = true;


	// change type of some content related fields from TEXT to MEDIUMTEXT

	// Retrieve Types of article content table
	$result = _dbQuery("SHOW COLUMNS FROM `".DB_PREPEND."articlecontent` WHERE Field IN ('acontent_text', 'acontent_html', 'acontent_media')");
	if(isset($result[0]['Type'])) {
		foreach($result as $column) {
			$update = _dbQuery("ALTER TABLE `".DB_PREPEND."articlecontent` CHANGE `".$column['Field'].'` `'.$column['Field']."` MEDIUMTEXT NOT NULL", 'ALTER');
			if(!$update) {
				$status = false;
			}
		}
	}

	// Retrieve Types of article table
	$result = _dbQuery("SHOW COLUMNS FROM `".DB_PREPEND."article` WHERE Field='article_summary'");
	if(isset($result[0]['Type'])) {
		$update = _dbQuery("ALTER TABLE `".DB_PREPEND."article` CHANGE `article_summary` `article_summary` MEDIUMTEXT NOT NULL", 'ALTER');
		if(!$update) {
			$status = false;
		}
	}

	return $status;
}
