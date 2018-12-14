<?php
/**
 * cmsGo!
 *
 * @author Pixels & Points GmbH <info@pixels-points.ch>
 * @copyright Copyright (c) 2002-2018, Pixels & Points GmbH
 * @license https://www.pixels-points.ch/cmsgo-license.html Pixels & Points cmsGo! license
 *
 **/


// Revision 539 Update Check
function cmsgo_revision_r539() {

	$status = true;

	// do former revision check – fallback to r538
	if(cmsgo_revision_check_temp('538') !== true) {
		$status = cmsgo_revision_check('538');
	}

	$result = _dbQuery("SHOW COLUMNS FROM `".DB_PREPEND."cmsgo_article` WHERE Field='article_description'");
	if(isset($result[0]['Type']) && substr(strtolower($result[0]['Type']), 0, 7) == 'varchar') {
		$update = _dbQuery("ALTER TABLE `".DB_PREPEND."cmsgo_article` CHANGE `article_description` `article_description` text NOT NULL", 'ALTER');
		if(!$update) {
			$status = false;
		}
	}

	return $status;
}
