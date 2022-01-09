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


// Revision 438 Update Check
function phpwcms_revision_r438() {

	$status = true;

	// do former revision check – fallback to r427
	if(phpwcms_revision_check_temp('427') !== true) {
		$status = phpwcms_revision_check('427');
	}

	// Fix possible problem
	_dbQuery('UPDATE '.DB_PREPEND."phpwcms_article SET article_subtitle = '' WHERE article_subtitle = '0'", 'UPDATE');
	_dbQuery('UPDATE '.DB_PREPEND."phpwcms_article SET article_menutitle = '' WHERE article_menutitle = '0'", 'UPDATE');
	_dbQuery('UPDATE '.DB_PREPEND."phpwcms_article SET article_description = '' WHERE article_description = '0'", 'UPDATE');

	return $status;
}
