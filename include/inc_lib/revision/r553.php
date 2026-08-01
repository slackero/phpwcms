<?php
/**
 * phpwcms
 *
 * @author Oliver Georgi <og@phpwcms.org>
 * @copyright Copyright (c) 2002-2026, Oliver Georgi
 * @license http://opensource.org/licenses/GPL-2.0 GNU GPL-2
 *
 **/

// Revision 553 Update Check
function phpwcms_revision_r553() {

	$status = true;


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
