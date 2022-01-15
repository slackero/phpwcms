<?php
/**
 * cmsGO!
 *
 * @author Pixels & Points GmbH <info@pixels-points.ch>
 * @copyright Copyright (c) 2002-2022, Pixels & Points GmbH
 * @license https://www.pixels-points.ch/cmsgo-license.html Pixels & Points cmsGO! license
 *
 **/


// ----------------------------------------------------------------
// obligate check for cmsgo constants
if (!defined('CMSGO_ROOT')) {
    die("You Cannot Access This Script Directly, Have a Nice Day.");
}
// ----------------------------------------------------------------


$_user_CSS = 'default';
if(!empty($_GET['switchCSS'])) {

    $_user_CSS = clean_slweg($_GET['switchCSS']);

    // try to write FontSizeCookie
    setcookie('switchCSS', $_user_CSS, time()+86400);

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
