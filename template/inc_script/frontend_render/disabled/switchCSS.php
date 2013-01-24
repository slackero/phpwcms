<?php
/**
 * phpwcms content management system
 *
 * @author Oliver Georgi <oliver@phpwcms.de>
 * @copyright Copyright (c) 2002-2013, Oliver Georgi
 * @license http://opensource.org/licenses/GPL-2.0 GNU GPL-2
 * @link http://www.phpwcms.de
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
	setcookie('switchCSS', $_user_CSS, time()+86400);

} else {
	
	if(isset($_SESSION['switchCSS'])) {
		$_user_CSS = $_SESSION['switchCSS'];
	} else {

		if(!empty($_COOKIE['switchCSS'])) {
			$_user_CSS = $_COOKIE['switchCSS'];
		}
		
	}
}

if(session_id()) {

	$_SESSION['switchCSS'] = $_user_CSS;

}

unset($GLOBALS['_getVar']['switchCSS']);
$newCSSURL = returnGlobalGET_QueryString('htmlentities');
$newCSSURL = PHPWCMS_URL . 'index.php' .(($newCSSURL == '') ? '?' : $newCSSURL);

if($_user_CSS != 'default') {
	$block['css'][]  = 'alternate/'.$_user_CSS.'.css';
	$content['all'] = str_replace('[ALTCSS_URL]', $newCSSURL.'&amp;switchCSS=default', $content['all']);
} else {
	$content['all'] = str_replace('[ALTCSS_URL]', $newCSSURL.'&amp;switchCSS=alt', $content['all']);
}



?>