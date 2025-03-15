<?php
/**
 * cmsGO!
 *
 * @author Pixels & Points GmbH <info@pixels-points.ch>
 * @copyright Copyright (c) 2002-2025, Pixels & Points GmbH
 * @license https://www.pixels-points.ch/cmsgo-license.html Pixels & Points cmsGO! license
 *
 **/


// Revision 551 Update Check
function cmsgo_revision_r551() {

	$status = true;

	// do former revision check – fallback to r550
	if(cmsgo_revision_check_temp('550') !== true) {
		$status = cmsgo_revision_check('550');
	}

	$result = _dbQuery("SHOW COLUMNS FROM `".DB_PREPEND."cmsgo_article` WHERE Field='article_meta'");
	if(!isset($result[0])) {
		$insert = _dbQuery("ALTER TABLE `".DB_PREPEND."cmsgo_article` ADD `article_meta` MEDIUMTEXT NOT NULL DEFAULT ''", 'ALTER');
		if(!$insert) {
			$status = false;
		}
	}

	return $status;
}
