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


// Revision 509 Update Check
function phpwcms_revision_r509() {

	$status = true;

	// do former revision check – fallback to r502
	if(phpwcms_revision_check_temp('502') !== true) {
		$status = phpwcms_revision_check('502');
	}

	// Hide article from teaser list
	$result = _dbQuery("SHOW COLUMNS FROM ".DB_PREPEND."phpwcms_article LIKE 'article_noteaser'", 'COUNT_SHOW');
	if(empty($result)) {
		$result = _dbQuery("ALTER TABLE ".DB_PREPEND."phpwcms_article ADD article_noteaser INT(1) UNSIGNED NOT NULL DEFAULT '0' AFTER article_morelink", 'ALTER');
		_dbQuery("ALTER TABLE ".DB_PREPEND."phpwcms_article ADD INDEX (article_noteaser)", 'ALTER');
	}

	return $status;
}
