<?php
/**
 * cmsGO!
 *
 * @author Pixels & Points GmbH <info@pixels-points.ch>
 * @copyright Copyright (c) 2002-2025, Pixels & Points GmbH
 * @license https://www.pixels-points.ch/cmsgo-license.html Pixels & Points cmsGO! license
 *
 **/

// obligate check for cmsGO! constants
if (!defined('CMSGO_ROOT')) {
	die("You Cannot Access This Script Directly, Have a Nice Day.");
}

// Updating user list relative to login time or delay login
// --------------------------------------------------------

$sql  = 'UPDATE ' . DB_PREPEND . 'cmsgo_userlog SET ';
$sql .= 'logged_in=0, logged_change=' . time() . ' WHERE ';
$sql .= 'logged_in=1 AND (' . time() . '-logged_change) > ' . intval($cmsgo['max_time']);
_dbQuery($sql, 'UPDATE');

if (!empty($_SESSION["wcs_user"])) {

    $sql  = 'SELECT COUNT(*) FROM ' . DB_PREPEND . 'cmsgo_userlog ';
    $sql .= 'WHERE logged_user=' . _dbEscape($_SESSION['wcs_user']) . ' AND ';
    $sql .= 'logged_in=1';

    if (!CMSGO_GDPR_MODE && !empty($cmsgo['Login_IPcheck'])) {
        $sql .= " AND logged_ip=" . _dbEscape(getRemoteIP());
	}

    if (!($check = _dbQuery($sql, 'COUNT'))) {
        $_SESSION['wcs_user'] = '';
        unset($_SESSION['wcs_user']);
	} else {
        $sql = 'UPDATE ' . DB_PREPEND . 'cmsgo_userlog SET ';
        $sql .= 'logged_change=' . time() . ' WHERE ';
        $sql .= 'logged_user=' . _dbEscape($_SESSION['wcs_user']) . ' AND logged_in=1';
		_dbQuery($sql, 'UPDATE');
	}
}

if (empty($_SESSION["wcs_user"])) {

    $_SESSION = array();
	@session_destroy();

    if (!empty($_SERVER['QUERY_STRING'])) {
        $ref_url = '?ref=' . rawurlencode(CMSGO_URL . 'cmsgo.php?' . xss_clean($_SERVER['QUERY_STRING']));
	} else {
    	$ref_url = '';
	}

    headerRedirect(CMSGO_URL . get_login_file() . $ref_url, 401);
}
