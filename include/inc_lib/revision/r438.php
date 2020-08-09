<?php
/**
 * cmsGO!
 *
 * @author Pixels & Points GmbH <info@pixels-points.ch>
 * @copyright Copyright (c) 2002-2020, Pixels & Points GmbH
 * @license https://www.pixels-points.ch/cmsgo-license.html Pixels & Points cmsGO! license
 *
 **/


// Revision 438 Update Check
function cmsgo_revision_r438() {

	$status = true;

	// do former revision check – fallback to r427
	if(cmsgo_revision_check_temp('427') !== true) {
		$status = cmsgo_revision_check('427');
	}

	// Fix possible problem
	_dbQuery('UPDATE '.DB_PREPEND."cmsgo_article SET article_subtitle = '' WHERE article_subtitle = '0'", 'UPDATE');
	_dbQuery('UPDATE '.DB_PREPEND."cmsgo_article SET article_menutitle = '' WHERE article_menutitle = '0'", 'UPDATE');
	_dbQuery('UPDATE '.DB_PREPEND."cmsgo_article SET article_description = '' WHERE article_description = '0'", 'UPDATE');

	return $status;
}
