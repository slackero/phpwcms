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


// ----------------------------------------------------------------
// obligate check for phpwcms constants
if (!defined('PHPWCMS_ROOT')) {
    die("You Cannot Access This Script Directly, Have a Nice Day.");
}
// ----------------------------------------------------------------


$_user_CSS = 'default';
if(!empty($_GET['switchCSS'])) {

    $_user_CSS = clean_slweg($_GET['switchCSS']);

    // try to write FontSizeCookie
    setcookie('switchCSS', $_user_CSS, time()+86400, '/', getCookieDomain(), PHPWCMS_SSL, true);

} elseif(isset($_SESSION['switchCSS'])) {

    $_user_CSS = $_SESSION['switchCSS'];

} elseif(!empty($_COOKIE['switchCSS'])) {

    $_user_CSS = $_COOKIE['switchCSS'];

}

if(session_id()) {

    $_SESSION['switchCSS'] = $_user_CSS;

}

unset($GLOBALS['_getVar']['switchCSS']);

if($_user_CSS != 'default') {

    $block['css'][]  = 'alternate/'.$_user_CSS.'.css';
    $content['all'] = str_replace('[ALTCSS_URL]', abs_url(array('switchCSS' => 'default')), $content['all']);

} else {

    $content['all'] = str_replace('[ALTCSS_URL]', abs_url(array('switchCSS' => 'alt')), $content['all']);

}
