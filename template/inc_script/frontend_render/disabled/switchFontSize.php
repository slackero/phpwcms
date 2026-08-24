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

$userFontSize = 'default';
if(!empty($_GET['switchFontSize'])) {

    $userFontSize = clean_slweg($_GET['switchFontSize']);

    // Write FontSize cookie with modern cookie attributes
    $cookie_options = [
        'expires'  => time() + 86400 * 30,
        'path'     => '/',
        'domain'   => getCookieDomain(),
        'secure'   => PHPWCMS_SSL,
        'httponly' => false,
        'samesite' => !empty($phpwcms['session.cookie_samesite']) ? $phpwcms['session.cookie_samesite'] : 'Lax',
    ];
    setcookie('switchFontSize', $userFontSize, $cookie_options);

} elseif(isset($_SESSION['FontSize'])) {
    $userFontSize = $_SESSION['FontSize'];
} elseif(!empty($_COOKIE['switchFontSize'])) {
    $userFontSize = clean_slweg($_COOKIE['switchFontSize']);
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

if($userFontSize !== 'default') {
    $block['css'][] = 'fontSize/' . $newFontSizeCSS;
}

unset($GLOBALS['_getVar']['switchFontSize']);

$content['all'] = str_replace('[FontSize+]', abs_url(['switchFontSize' => $newFontSizeBigger]), $content['all']);
$content['all'] = str_replace('[FontSize=]', abs_url(['switchFontSize' => 'default']), $content['all']);
$content['all'] = str_replace('[FontSize-]', abs_url(['switchFontSize' => $newFontSizeSmaller]), $content['all']);

