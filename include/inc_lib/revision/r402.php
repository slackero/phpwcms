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


// Revision 402 Update Check
function phpwcms_revision_r402() {

	// do former revision check
	$r401 = '401';
	if(phpwcms_revision_check_temp($r401) !== true) {
		phpwcms_revision_check($r401);
	}

	return true;
}
