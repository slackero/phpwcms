<?php
/**
 * phpwcms
 *
 * @author Oliver Georgi <og@phpwcms.org>
 * @copyright Copyright (c) 2002-2026, Oliver Georgi
 * @license http://opensource.org/licenses/GPL-2.0 GNU GPL-2
 *
 **/


// Revision 529 Update Check
function phpwcms_revision_r529() {

	$status = true;


	// reset article and file manager status (open/close)
	_dbUpdate('user', array(
		'usr_var_structure' => '',
		'usr_var_publicfile' => '',
		'usr_var_privatefile' => ''
	));

	return $status;
}
