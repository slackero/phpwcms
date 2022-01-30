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


// Revision 427 Update Check
function phpwcms_revision_r427() {

	$status = true;

	// do former revision check – fallback to r427
	$r421 = '421';
	if(phpwcms_revision_check_temp($r421) !== true) {
		$status = phpwcms_revision_check($r421);
	}

	// Change some missing default values for older releases
	$result = _dbQuery("ALTER TABLE ".DB_PREPEND."phpwcms_article CHANGE article_menutitle article_menutitle VARCHAR(255) NOT NULL DEFAULT  ''", 'ALTER');
	$result = _dbQuery("ALTER TABLE ".DB_PREPEND."phpwcms_article CHANGE article_description article_description VARCHAR(255) NOT NULL DEFAULT  ''", 'ALTER');

	return $status;

}
