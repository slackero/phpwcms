<?php
/**
 * phpwcms
 *
 * @author Oliver Georgi <og@phpwcms.org>
 * @copyright Copyright (c) 2002-2026, Oliver Georgi
 * @license http://opensource.org/licenses/GPL-2.0 GNU GPL-2
 *
 **/

// Revision 401 Update Check
function phpwcms_revision_r401() {

	// check if article description field exists
	if(!_dbColumnExists('phpwcms_article', 'article_description')) {
		return _dbQuery("ALTER TABLE ".DB_PREPEND."phpwcms_article ADD article_description VARCHAR(255) NOT NULL DEFAULT ''", 'ALTER');
	}

	return true;
}
