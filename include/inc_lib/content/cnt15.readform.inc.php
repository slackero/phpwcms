<?php
/*************************************************************************************
   Copyright notice
   
   (c) 2002-2010 Oliver Georgi (oliver@phpwcms.de) // All rights reserved.
 
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



// Content Type Article List Menu
$content["alist"]["cat"]			= isset($_POST['calist_cat']) ? intval($_POST['calist_cat']) : 0;
$content["alist"]["catid"]			= intval($_POST['calist_catid']);
$content["alist"]["headertext"]		= isset($_POST['calist_headertext']) ? 1 : 0;
$content["alist"]["ul"]				= isset($_POST['calist_ul']) ? intval($_POST['calist_ul']) : 0;
$content["alist"]["class"]			= clean_slweg($_POST['calist_class']);
$content["alist"]["maxchar"]		= intval($_POST['calist_maxchar']);
$content["alist"]["morelink"]		= slweg($_POST['calist_morelink']);
$content["alist"]["titlewrap"]		= clean_slweg($_POST['calist_titlewrap']);
$content["alist"]["hideactive"]		= empty($_POST['calist_hideactive']) ? 0 : 1;

switch($content["alist"]["ul"]) {
	case 2:		break;							// DIV
	case 1:		break;							// UL
	default:	$content["alist"]["ul"] = 0;	// TABLE
}


?>