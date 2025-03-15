<?php
/**
 * cmsGO!
 *
 * @author Pixels & Points GmbH <info@pixels-points.ch>
 * @copyright Copyright (c) 2002-2025, Pixels & Points GmbH
 * @license https://www.pixels-points.ch/cmsgo-license.html Pixels & Points cmsGO! license
 *
 **/


// Revision 497 Update Check
function cmsgo_revision_r497() {

	$status = true;

	// do former revision check – fallback to r438
	if(cmsgo_revision_check_temp('438') !== true) {
		$status = cmsgo_revision_check('438');
	}

	// Check if seo log hash (for filter unique items) field exists
	$result = _dbQuery("SHOW COLUMNS FROM ".DB_PREPEND."cmsgo_log_seo LIKE 'hash'", 'COUNT_SHOW');

	if(empty($result)) {
		$result = _dbQuery("ALTER TABLE ".DB_PREPEND."cmsgo_log_seo ADD hash CHAR(32) NOT NULL DEFAULT ''", 'ALTER');
		if($result) {
			_dbQuery('UPDATE '.DB_PREPEND.'cmsgo_log_seo SET hash=MD5(LOWER(CONCAT(domain,query)))', 'UPDATE');
			_dbQuery("ALTER TABLE ".DB_PREPEND."cmsgo_log_seo ADD INDEX (hash)", 'ALTER');
		}
	}

	// switch crossreference field type from INT to VARCHAR
	$result = _dbQuery("SHOW COLUMNS FROM ".DB_PREPEND."cmsgo_crossreference LIKE 'cref_type'");

	if(isset($result[0]['Type']) && substr(strtolower($result[0]['Type']), 0, 3) == 'int') {

		$result = _dbQuery("ALTER TABLE ".DB_PREPEND."cmsgo_crossreference CHANGE cref_type cref_type VARCHAR(255) NOT NULL DEFAULT ''", 'ALTER');

		// Update feedimport References
		_dbUpdate('cmsgo_crossreference', array('cref_type'=>'feed_to_article_import'), "cref_str LIKE 'feedimport_%'");

	}

	// add language to article category, article and content part
	$result = _dbQuery("SHOW COLUMNS FROM ".DB_PREPEND."cmsgo_articlecat LIKE 'acat_lang'", 'COUNT_SHOW');
	if(empty($result)) {
		$result = _dbQuery("ALTER TABLE ".DB_PREPEND."cmsgo_articlecat ADD acat_lang VARCHAR(255) NOT NULL DEFAULT ''", 'ALTER');
		_dbQuery("ALTER TABLE ".DB_PREPEND."cmsgo_articlecat ADD INDEX (acat_lang)", 'ALTER');
	}
	$result = _dbQuery("SHOW COLUMNS FROM ".DB_PREPEND."cmsgo_article LIKE 'article_lang'", 'COUNT_SHOW');
	if(empty($result)) {
		$result = _dbQuery("ALTER TABLE ".DB_PREPEND."cmsgo_article ADD article_lang VARCHAR(255) NOT NULL DEFAULT ''", 'ALTER');
		_dbQuery("ALTER TABLE ".DB_PREPEND."cmsgo_article ADD INDEX (article_lang)", 'ALTER');
	}
	$result = _dbQuery("SHOW COLUMNS FROM ".DB_PREPEND."cmsgo_articlecontent LIKE 'acontent_lang'", 'COUNT_SHOW');
	if(empty($result)) {
		$result = _dbQuery("ALTER TABLE ".DB_PREPEND."cmsgo_articlecontent ADD acontent_lang VARCHAR(255) NOT NULL DEFAULT ''", 'ALTER');
		_dbQuery("ALTER TABLE ".DB_PREPEND."cmsgo_articlecontent ADD INDEX (acontent_lang)", 'ALTER');
	}

	return $status;
}
