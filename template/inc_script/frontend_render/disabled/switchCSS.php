<?php
/**
 * phpwcms
 *
 * @author Oliver Georgi <og@phpwcms.org>
 * @copyright Copyright (c) 2002-2026, Oliver Georgi
 * @license http://opensource.org/licenses/GPL-2.0 GNU GPL-2
 *
 **/


// ----------------------------------------------------------------
// Obligate check for phpwcms constants
if (!defined('PHPWCMS_ROOT')) {
    die('You Cannot Access This Script Directly, Have a Nice Day.');
}
// ----------------------------------------------------------------

$_user_CSS = 'default';
if(!empty($_GET['switchCSS'])) {

    $_user_CSS = clean_slweg($_GET['switchCSS']);

    // Write CSS cookie with modern cookie attributes
    $cookie_options = [
        'expires'  => time() + 86400 * 30,
        'path'     => '/',
        'domain'   => getCookieDomain(),
        'secure'   => PHPWCMS_SSL,
        'httponly' => false,
        'samesite' => !empty($phpwcms['session.cookie_samesite']) ? $phpwcms['session.cookie_samesite'] : 'Lax',
    ];
    setcookie('switchCSS', $_user_CSS, $cookie_options);

} elseif(isset($_SESSION['switchCSS'])) {

    $_user_CSS = $_SESSION['switchCSS'];

} elseif(!empty($_COOKIE['switchCSS'])) {

    $_user_CSS = clean_slweg($_COOKIE['switchCSS']);

}

if(session_id()) {
    $_SESSION['switchCSS'] = $_user_CSS;
}

unset($GLOBALS['_getVar']['switchCSS']);

if($_user_CSS !== 'default') {
    $block['css'][] = 'alternate/' . $_user_CSS . '.css';
    $content['all'] = str_replace('[ALTCSS_URL]', abs_url(['switchCSS' => 'default']), $content['all']);
} else {
    $content['all'] = str_replace('[ALTCSS_URL]', abs_url(['switchCSS' => 'alt']), $content['all']);
}

