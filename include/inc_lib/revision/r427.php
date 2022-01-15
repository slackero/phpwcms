<?php
/**
 * cmsGO!
 *
 * @author Pixels & Points GmbH <info@pixels-points.ch>
 * @copyright Copyright (c) 2002-2022, Pixels & Points GmbH
 * @license https://www.pixels-points.ch/cmsgo-license.html Pixels & Points cmsGO! license
 *
 **/


// Revision 427 Update Check
function cmsgo_revision_r427() {

	$status = true;

	// do former revision check – fallback to r427
	$r421 = '421';
	if(cmsgo_revision_check_temp($r421) !== true) {
		$status = cmsgo_revision_check($r421);
	}

	// Change some missing default values for older releases
	$result = _dbQuery("ALTER TABLE ".DB_PREPEND."cmsgo_article CHANGE article_menutitle article_menutitle VARCHAR(255) NOT NULL DEFAULT  ''", 'ALTER');
	$result = _dbQuery("ALTER TABLE ".DB_PREPEND."cmsgo_article CHANGE article_description article_description VARCHAR(255) NOT NULL DEFAULT  ''", 'ALTER');

	return $status;

}
