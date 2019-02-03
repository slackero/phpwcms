<?php
/**
 * cmsGO!
 *
 * @author Pixels & Points GmbH <info@pixels-points.ch>
 * @copyright Copyright (c) 2002-2018, Pixels & Points GmbH
 * @license https://www.pixels-points.ch/cmsgo-license.html Pixels & Points cmsGO! license
 *
 **/


// ----------------------------------------------------------------
// obligate check for cmsgo constants
if (!defined('CMSGO_ROOT')) {
    die("You Cannot Access This Script Directly, Have a Nice Day.");
}
// ----------------------------------------------------------------


$userFontSize = 'default';
if(!empty($_GET['switchFontSize'])) {

    $userFontSize = clean_slweg($_GET['switchFontSize']);
    // try to write FontSizeCookie
    setcookie('switchFontSize', $userFontSize, time()+86400);

} else {

    if(isset($_SESSION['FontSize'])) {
        $userFontSize = $_SESSION['FontSize'];
    } else {

        if(!empty($_COOKIE['switchFontSize'])) {
            $userFontSize = $_COOKIE['switchFontSize'];
        }

    }
}

if(session_id()) {

    $_SESSION['FontSize'] = $userFontSize;

}

switch($userFontSize) {

    case 'small':
        $newFontSizeCSS     = 'userFontSize_small.css';
        $newFontSizeBigger  = 'smaller';
        $newFontSizeSmaller = 'small';
        break;

    case 'smaller':
        $newFontSizeCSS     = 'userFontSize_smaller.css';
        $newFontSizeBigger  = 'default';
        $newFontSizeSmaller = 'small';
        break;

    case 'big':
        $newFontSizeCSS     = 'userFontSize_big.css';
        $newFontSizeBigger  = 'big';
        $newFontSizeSmaller = 'bigger';
        break;

    case 'bigger':
        $newFontSizeCSS     = 'userFontSize_bigger.css';
        $newFontSizeBigger  = 'big';
        $newFontSizeSmaller = 'default';
        break;

    default:
        $newFontSizeCSS     = 'userFontSize_default.css';
        $newFontSizeBigger  = 'bigger';
        $newFontSizeSmaller = 'smaller';

}

if($userFontSize != 'default') {
    $block['css'][]  = 'fontSize/'.$newFontSizeCSS;
}

unset($GLOBALS['_getVar']['switchFontSize']);

$content['all'] = str_replace('[FontSize+]', abs_url(array('switchFontSize' => $newFontSizeBigger)), $content['all']);
$content['all'] = str_replace('[FontSize=]', abs_url(array('switchFontSize' => 'default')), $content['all']);
$content['all'] = str_replace('[FontSize-]', abs_url(array('switchFontSize' => $newFontSizeSmaller)), $content['all']);
