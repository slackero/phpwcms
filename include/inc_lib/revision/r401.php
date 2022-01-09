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

// Revision 401 Update Check
function phpwcms_revision_r401() {

	// check if article description field exists
	$result = _dbQuery("SHOW COLUMNS FROM ".DB_PREPEND."phpwcms_article LIKE 'article_description'", 'COUNT_SHOW');

	if(empty($result)) {
		return _dbQuery("ALTER TABLE ".DB_PREPEND."phpwcms_article ADD article_description VARCHAR(255) NOT NULL DEFAULT ''", 'ALTER');
	}

	return true;
}
