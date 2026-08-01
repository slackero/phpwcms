<?php
/**
 * phpwcms
 *
 * @author Oliver Georgi <og@phpwcms.org>
 * @copyright Copyright (c) 2002-2026, Oliver Georgi
 * @license http://opensource.org/licenses/GPL-2.0 GNU GPL-2
 *
 **/


// Revision 509 Update Check
function phpwcms_revision_r509() {

	$status = true;


	// Hide article from teaser list
	if(!_dbColumnExists('phpwcms_article', 'article_noteaser')) {
		$result = _dbQuery("ALTER TABLE ".DB_PREPEND."phpwcms_article ADD article_noteaser INT(1) UNSIGNED NOT NULL DEFAULT '0' AFTER article_morelink", 'ALTER');
		_dbQuery("ALTER TABLE ".DB_PREPEND."phpwcms_article ADD INDEX (article_noteaser)", 'ALTER');
	}

	return $status;
}
