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


// Revision 497 Update Check
function phpwcms_revision_r497() {

	$status = true;

	// do former revision check – fallback to r438
	if(phpwcms_revision_check_temp('438') !== true) {
		$status = phpwcms_revision_check('438');
	}

	// Check if seo log hash (for filter unique items) field exists
	$result = _dbQuery("SHOW COLUMNS FROM ".DB_PREPEND."phpwcms_log_seo LIKE 'hash'", 'COUNT_SHOW');

	if(empty($result)) {
		$result = _dbQuery("ALTER TABLE ".DB_PREPEND."phpwcms_log_seo ADD hash CHAR(32) NOT NULL DEFAULT ''", 'ALTER');
		if($result) {
			_dbQuery('UPDATE '.DB_PREPEND.'phpwcms_log_seo SET hash=MD5(LOWER(CONCAT(domain,query)))', 'UPDATE');
			_dbQuery("ALTER TABLE ".DB_PREPEND."phpwcms_log_seo ADD INDEX (hash)", 'ALTER');
		}
	}

	// switch crossreference field type from INT to VARCHAR
	$result = _dbQuery("SHOW COLUMNS FROM ".DB_PREPEND."phpwcms_crossreference LIKE 'cref_type'");

	if(isset($result[0]['Type']) && substr(strtolower($result[0]['Type']), 0, 3) == 'int') {

		$result = _dbQuery("ALTER TABLE ".DB_PREPEND."phpwcms_crossreference CHANGE cref_type cref_type VARCHAR(255) NOT NULL DEFAULT ''", 'ALTER');

		// Update feedimport References
		_dbUpdate('phpwcms_crossreference', array('cref_type'=>'feed_to_article_import'), "cref_str LIKE 'feedimport_%'");

	}

	// add language to article category, article and content part
	$result = _dbQuery("SHOW COLUMNS FROM ".DB_PREPEND."phpwcms_articlecat LIKE 'acat_lang'", 'COUNT_SHOW');
	if(empty($result)) {
		$result = _dbQuery("ALTER TABLE ".DB_PREPEND."phpwcms_articlecat ADD acat_lang VARCHAR(255) NOT NULL DEFAULT ''", 'ALTER');
		_dbQuery("ALTER TABLE ".DB_PREPEND."phpwcms_articlecat ADD INDEX (acat_lang)", 'ALTER');
	}
	$result = _dbQuery("SHOW COLUMNS FROM ".DB_PREPEND."phpwcms_article LIKE 'article_lang'", 'COUNT_SHOW');
	if(empty($result)) {
		$result = _dbQuery("ALTER TABLE ".DB_PREPEND."phpwcms_article ADD article_lang VARCHAR(255) NOT NULL DEFAULT ''", 'ALTER');
		_dbQuery("ALTER TABLE ".DB_PREPEND."phpwcms_article ADD INDEX (article_lang)", 'ALTER');
	}
	$result = _dbQuery("SHOW COLUMNS FROM ".DB_PREPEND."phpwcms_articlecontent LIKE 'acontent_lang'", 'COUNT_SHOW');
	if(empty($result)) {
		$result = _dbQuery("ALTER TABLE ".DB_PREPEND."phpwcms_articlecontent ADD acontent_lang VARCHAR(255) NOT NULL DEFAULT ''", 'ALTER');
		_dbQuery("ALTER TABLE ".DB_PREPEND."phpwcms_articlecontent ADD INDEX (acontent_lang)", 'ALTER');
	}

	return $status;
}
