<?php
/**
 * phpwcms
 *
 * @author Oliver Georgi <og@phpwcms.org>
 * @copyright Copyright (c) 2002-2026, Oliver Georgi
 * @license http://opensource.org/licenses/GPL-2.0 GNU GPL-2
 *
 **/


// Revision 551 Update Check
function phpwcms_revision_r551() {

	$status = true;


	if(!_dbColumnExists('phpwcms_article', 'article_meta')) {
		$insert = _dbQuery("ALTER TABLE `".DB_PREPEND."phpwcms_article` ADD `article_meta` MEDIUMTEXT NOT NULL DEFAULT ''", 'ALTER');
		if(!$insert) {
			$status = false;
		}
	}

	return $status;
}
