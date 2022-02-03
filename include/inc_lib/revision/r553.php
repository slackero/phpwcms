<?php
/**
 * cmsGO!
 *
 * @author Pixels & Points GmbH <info@pixels-points.ch>
 * @copyright Copyright (c) 2002-2022, Pixels & Points GmbH
 * @license https://www.pixels-points.ch/cmsgo-license.html Pixels & Points cmsGO! license
 *
 **/

// Revision 552 Update Check
function cmsgo_revision_r553() {

	$status = true;

	// do former revision check – fallback to r552
	if(cmsgo_revision_check_temp('552') !== true) {
		$status = cmsgo_revision_check('552');
	}

    setcookie('cmsgoBELang', '', time() - 42000);
    unset($_COOKIE['cmsgoBELang']);

    $_SESSION_STORE = $_SESSION;
    unset($_SESSION_STORE['cmsgoSessionInit']);
    $_SESSION = array();

    setcookie(session_name(), '', time() - 42000);
    session_destroy();
    session_write_close();

    _initSession();

    $_SESSION = $_SESSION_STORE;
    unset($_SESSION_STORE);

	return $status;
}
