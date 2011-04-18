<?php
/*************************************************************************************
   Copyright notice
   
   (c) 2002-2011 Oliver Georgi (oliver@phpwcms.de) // All rights reserved.
 
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
		$newFontSizeCSS		= 'userFontSize_small.css';
		$newFontSizeBigger	= 'smaller';
		$newFontSizeSmaller	= 'small';
		break;
		
	case 'smaller':
		$newFontSizeCSS		= 'userFontSize_smaller.css';
		$newFontSizeBigger	= 'default';
		$newFontSizeSmaller	= 'small';
		break;
		
	case 'big':
		$newFontSizeCSS		= 'userFontSize_big.css';
		$newFontSizeBigger	= 'big';
		$newFontSizeSmaller	= 'bigger';
		break;
		
	case 'bigger':
		$newFontSizeCSS		= 'userFontSize_bigger.css';
		$newFontSizeBigger	= 'big';
		$newFontSizeSmaller	= 'default';
		break;
			
	default:
		$newFontSizeCSS		= 'userFontSize_default.css';
		$newFontSizeBigger	= 'bigger';
		$newFontSizeSmaller	= 'smaller';
	
}

if($userFontSize != 'default') {
	$block['css'][]  = 'fontSize/'.$newFontSizeCSS;
}

unset($GLOBALS['_getVar']['switchFontSize']);
$newFontSizeURL = returnGlobalGET_QueryString('htmlentities');
$newFontSizeURL = PHPWCMS_URL . 'index.php' .(($newFontSizeURL == '') ? '?' : $newFontSizeURL);
 
$content['all'] = str_replace('[FontSize+]', $newFontSizeURL.'&amp;switchFontSize='.$newFontSizeBigger, $content['all']);
$content['all'] = str_replace('[FontSize=]', $newFontSizeURL.'&amp;switchFontSize=default', $content['all']);
$content['all'] = str_replace('[FontSize-]', $newFontSizeURL.'&amp;switchFontSize='.$newFontSizeSmaller, $content['all']);
$content['all'] = str_replace('?&amp;switchFontSize', '?switchFontSize', $content['all']);

?>