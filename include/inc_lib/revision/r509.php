<?php
/**
 * cmsGO!
 *
 * @author Pixels & Points GmbH <info@pixels-points.ch>
 * @copyright Copyright (c) 2002-2021, Pixels & Points GmbH
 * @license https://www.pixels-points.ch/cmsgo-license.html Pixels & Points cmsGO! license
 *
 **/


// Revision 509 Update Check
function cmsgo_revision_r509() {

	$status = true;

	// do former revision check – fallback to r502
	if(cmsgo_revision_check_temp('502') !== true) {
		$status = cmsgo_revision_check('502');
	}

	// Hide article from teaser list
	$result = _dbQuery("SHOW COLUMNS FROM ".DB_PREPEND."cmsgo_article LIKE 'article_noteaser'", 'COUNT_SHOW');
	if(empty($result)) {
		$result = _dbQuery("ALTER TABLE ".DB_PREPEND."cmsgo_article ADD article_noteaser INT(1) UNSIGNED NOT NULL DEFAULT '0' AFTER article_morelink", 'ALTER');
		_dbQuery("ALTER TABLE ".DB_PREPEND."cmsgo_article ADD INDEX (article_noteaser)", 'ALTER');
	}

	return $status;
}
