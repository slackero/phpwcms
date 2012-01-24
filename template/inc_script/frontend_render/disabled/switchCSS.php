<?php
/*************************************************************************************
   Copyright notice
   
   (c) 2002-2012 Oliver Georgi <oliver@phpwcms.de> // All rights reserved.
 
   This script is part of PHPWCMS. The PHPWCMS web content management system is
   free software; you can redistribute it and/or modify it under the terms of
   the GNU General Public License as published by the Free Software Foundation;
   either version 2 of the License, or (at your option) any later version.
  
   The GNU General Public License can be found at http://www.gnu.org/copyleft/gpl.html
   A copy is found in the textfile GPL.txt and important notices to the license 
   from the author is found in LICENSE.txt distributed with these scripts.
  
   This script is distributed in the hope that it will be useful, but WITHOUT ANY 
   WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A
   PARTICULAR PURPOSE.  See the GNU General Public License for more details.
 
   This copyright notice MUST APPEAR in all copies of the script!
*************************************************************************************/


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