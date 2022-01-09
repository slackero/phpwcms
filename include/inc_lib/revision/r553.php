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

// Revision 552 Update Check
function phpwcms_revision_r553() {

	$status = true;

	// do former revision check – fallback to r552
	if(phpwcms_revision_check_temp('552') !== true) {
		$status = phpwcms_revision_check('552');
	}

    setcookie('phpwcmsBELang', '', time() - 42000);
    unset($_COOKIE['phpwcmsBELang']);

    $_SESSION_STORE = $_SESSION;
    unset($_SESSION_STORE['phpwcmsSessionInit']);
    $_SESSION = array();

    setcookie(session_name(), '', time() - 42000);
    session_destroy();
    session_write_close();

    _initSession();

    $_SESSION = $_SESSION_STORE;
    unset($_SESSION_STORE);

	return $status;
}
