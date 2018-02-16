<?php
/**
 * cmsGo!
 *
 * @author Pixels & Points GmbH <info@pixels-points.ch>
 * @copyright Copyright (c) 2002-2017, Pixels & Points GmbH
 * @license https://www.pixels-points.ch/cmsgo-license.html Pixels & Points cmsGo! license
 *
 **/


// Revision 534 Update Check
function cmsgo_revision_r534() {

	$status = true;

	// do former revision check – fallback to r533
	if(cmsgo_revision_check_temp('533') !== true) {
		$status = cmsgo_revision_check('533');
	}

	// change type of some content related fields from TEXT to MEDIUMTEXT

	// Retrieve Types of article content table
	$result = _dbQuery("SHOW COLUMNS FROM `".DB_PREPEND."cmsgo_articlecontent` WHERE Field IN ('acontent_text', 'acontent_html', 'acontent_media')");
	if(isset($result[0]['Type'])) {
		foreach($result as $column) {
			$update = _dbQuery("ALTER TABLE `".DB_PREPEND."cmsgo_articlecontent` CHANGE `".$column['Field'].'` `'.$column['Field']."` MEDIUMTEXT NOT NULL", 'ALTER');
			if(!$update) {
				$status = false;
			}
		}
	}

	// Retrieve Types of article table
	$result = _dbQuery("SHOW COLUMNS FROM `".DB_PREPEND."cmsgo_article` WHERE Field='article_summary'");
	if(isset($result[0]['Type'])) {
		$update = _dbQuery("ALTER TABLE `".DB_PREPEND."cmsgo_article` CHANGE `article_summary` `article_summary` MEDIUMTEXT NOT NULL", 'ALTER');
		if(!$update) {
			$status = false;
		}
	}

	return $status;
}
