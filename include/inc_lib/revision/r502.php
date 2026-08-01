<?php
/**
 * phpwcms
 *
 * @author Oliver Georgi <og@phpwcms.org>
 * @copyright Copyright (c) 2002-2026, Oliver Georgi
 * @license http://opensource.org/licenses/GPL-2.0 GNU GPL-2
 *
 **/


// Revision 502 Update Check
function phpwcms_revision_r502() {

	$status = true;


	// add field for default language type and target ID to article, category
	if(!_dbColumnExists('phpwcms_articlecat', 'acat_lang_type')) {
		$result = _dbQuery("ALTER TABLE ".DB_PREPEND."phpwcms_articlecat ADD acat_lang_type VARCHAR(255) NOT NULL DEFAULT ''", 'ALTER');
		_dbQuery("ALTER TABLE ".DB_PREPEND."phpwcms_articlecat ADD INDEX (acat_lang_type)", 'ALTER');
	}
	if(!_dbColumnExists('phpwcms_articlecat', 'acat_lang_id')) {
		$result = _dbQuery("ALTER TABLE ".DB_PREPEND."phpwcms_articlecat ADD acat_lang_id INT(11) unsigned NOT NULL DEFAULT '0'", 'ALTER');
		_dbQuery("ALTER TABLE ".DB_PREPEND."phpwcms_articlecat ADD INDEX (acat_lang_id)", 'ALTER');
	}

	if(!_dbColumnExists('phpwcms_article', 'article_lang_type')) {
		$result = _dbQuery("ALTER TABLE ".DB_PREPEND."phpwcms_article ADD article_lang_type VARCHAR(255) NOT NULL DEFAULT ''", 'ALTER');
		_dbQuery("ALTER TABLE ".DB_PREPEND."phpwcms_article ADD INDEX (article_lang_type)", 'ALTER');
	}
	if(!_dbColumnExists('phpwcms_article', 'article_lang_id')) {
		$result = _dbQuery("ALTER TABLE ".DB_PREPEND."phpwcms_article ADD article_lang_id INT(11) unsigned NOT NULL DEFAULT '0'", 'ALTER');
		_dbQuery("ALTER TABLE ".DB_PREPEND."phpwcms_article ADD INDEX (article_lang_id)", 'ALTER');
	}

	return $status;
}
