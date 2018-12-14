<?php
/**
 * cmsGo!
 *
 * @author Pixels & Points GmbH <info@pixels-points.ch>
 * @copyright Copyright (c) 2002-2018, Pixels & Points GmbH
 * @license https://www.pixels-points.ch/cmsgo-license.html Pixels & Points cmsGo! license
 *
 **/


// Revision 502 Update Check
function cmsgo_revision_r502() {
		
	$status = true;
	
	// do former revision check – fallback to r497
	if(cmsgo_revision_check_temp('497') !== true) {
		$status = cmsgo_revision_check('497');
	}
	
	// add field for default language type and target ID to article, category
	$result = _dbQuery("SHOW COLUMNS FROM ".DB_PREPEND."cmsgo_articlecat LIKE 'acat_lang_type'", 'COUNT_SHOW');
	if(empty($result)) {
		$result = _dbQuery("ALTER TABLE ".DB_PREPEND."cmsgo_articlecat ADD acat_lang_type VARCHAR(255) NOT NULL DEFAULT ''", 'ALTER');
		_dbQuery("ALTER TABLE ".DB_PREPEND."cmsgo_articlecat ADD INDEX (acat_lang_type)", 'ALTER');
	}
	$result = _dbQuery("SHOW COLUMNS FROM ".DB_PREPEND."cmsgo_articlecat LIKE 'acat_lang_id'", 'COUNT_SHOW');
	if(empty($result)) {
		$result = _dbQuery("ALTER TABLE ".DB_PREPEND."cmsgo_articlecat ADD acat_lang_id INT(11) unsigned NOT NULL DEFAULT '0'", 'ALTER');
		_dbQuery("ALTER TABLE ".DB_PREPEND."cmsgo_articlecat ADD INDEX (acat_lang_id)", 'ALTER');
	}
	
	$result = _dbQuery("SHOW COLUMNS FROM ".DB_PREPEND."cmsgo_article LIKE 'article_lang_type'", 'COUNT_SHOW');
	if(empty($result)) {
		$result = _dbQuery("ALTER TABLE ".DB_PREPEND."cmsgo_article ADD article_lang_type VARCHAR(255) NOT NULL DEFAULT ''", 'ALTER');
		_dbQuery("ALTER TABLE ".DB_PREPEND."cmsgo_article ADD INDEX (article_lang_type)", 'ALTER');
	}
	$result = _dbQuery("SHOW COLUMNS FROM ".DB_PREPEND."cmsgo_article LIKE 'article_lang_id'", 'COUNT_SHOW');
	if(empty($result)) {
		$result = _dbQuery("ALTER TABLE ".DB_PREPEND."cmsgo_article ADD article_lang_id INT(11) unsigned NOT NULL DEFAULT '0'", 'ALTER');
		_dbQuery("ALTER TABLE ".DB_PREPEND."cmsgo_article ADD INDEX (article_lang_id)", 'ALTER');
	}

	return $status;
}
