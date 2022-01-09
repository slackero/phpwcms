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

// obligate check for phpwcms constants
if (!defined('PHPWCMS_ROOT')) {
    die("You Cannot Access This Script Directly, Have a Nice Day.");
}

// Updating user list relative to login time or delay login
// --------------------------------------------------------

$sql  = 'UPDATE ' . DB_PREPEND . 'phpwcms_userlog SET ';
$sql .= 'logged_in=0, logged_change=' . time() . ' WHERE ';
$sql .= 'logged_in=1 AND (' . time() . '-logged_change) > ' . intval($phpwcms['max_time']);
_dbQuery($sql, 'UPDATE');

if (!empty($_SESSION["wcs_user"])) {

    $sql  = 'SELECT COUNT(*) FROM ' . DB_PREPEND . 'phpwcms_userlog ';
    $sql .= 'WHERE logged_user=' . _dbEscape($_SESSION['wcs_user']) . ' AND ';
    $sql .= 'logged_in=1';

    if (!PHPWCMS_GDPR_MODE && !empty($phpwcms['Login_IPcheck'])) {
        $sql .= " AND logged_ip=" . _dbEscape(getRemoteIP());
    }

    if (!($check = _dbQuery($sql, 'COUNT'))) {
        $_SESSION['wcs_user'] = '';
        unset($_SESSION['wcs_user']);
    } else {
        $sql = 'UPDATE ' . DB_PREPEND . 'phpwcms_userlog SET ';
        $sql .= 'logged_change=' . time() . ' WHERE ';
        $sql .= 'logged_user=' . _dbEscape($_SESSION['wcs_user']) . ' AND logged_in=1';
        _dbQuery($sql, 'UPDATE');
    }
}

if (empty($_SESSION["wcs_user"])) {

    $_SESSION = array();
    @session_destroy();

    if (!empty($_SERVER['QUERY_STRING'])) {
        $ref_url = '?ref=' . rawurlencode(PHPWCMS_URL . 'phpwcms.php?' . xss_clean($_SERVER['QUERY_STRING']));
    } else {
        $ref_url = '';
    }

    headerRedirect(PHPWCMS_URL . get_login_file() . $ref_url, 401);
}