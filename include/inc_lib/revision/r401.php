<?php
/**
 * cmsGO!
 *
 * @author Pixels & Points GmbH <info@pixels-points.ch>
 * @copyright Copyright (c) 2002-2024, Pixels & Points GmbH
 * @license https://www.pixels-points.ch/cmsgo-license.html Pixels & Points cmsGO! license
 *
 **/

// Revision 401 Update Check
function cmsgo_revision_r401() {

	// check if article description field exists
	$result = _dbQuery("SHOW COLUMNS FROM ".DB_PREPEND."cmsgo_article LIKE 'article_description'", 'COUNT_SHOW');

	if(empty($result)) {
		return _dbQuery("ALTER TABLE ".DB_PREPEND."cmsgo_article ADD article_description VARCHAR(255) NOT NULL DEFAULT ''", 'ALTER');
	}

	return true;
}
