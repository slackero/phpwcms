<?php
/*************************************************************************************
   Copyright notice
   
   (c) 2002-2009 Oliver Georgi (oliver@phpwcms.de) // All rights reserved.
 
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


// user is admin
define('IS_ADMIN',			empty($_SESSION["wcs_user_admin"]) ? false : true);
define('BE_CURRENT_URL',	PHPWCMS_URL.'phpwcms.php?'.$_SERVER['QUERY_STRING']);
define('ACTIVE_REFERER',	$_SESSION['REFERER_URL']);

$_SESSION['REFERER_URL'] =	BE_CURRENT_URL;


// some more important constants
define('JS_START',	'<script language="javascript" type="text/javascript">' . LF . '<!--' . LF);
define('JS_END',	LF . '//-->' . LF . '</script>');


?>