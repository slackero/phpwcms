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


// Revision 529 Update Check
function phpwcms_revision_r529() {

	$status = true;

	// do former revision check – fallback to r528
	if(phpwcms_revision_check_temp('528') !== true) {
		$status = phpwcms_revision_check('528');
	}

	// reset article and file manager status (open/close)
	_dbUpdate('phpwcms_user', array(
		'usr_var_structure' => '',
		'usr_var_publicfile' => '',
		'usr_var_privatefile' => ''
	));

	return $status;
}
