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


//
// Content Part Frontend Login
//
$content['felogin_template']						= clean_slweg($_POST['template']);
$content['felogin']['felogin_cookie_expire']		= intval($_POST['cookie_expire']);
$content['felogin']['felogin_date_format']			= clean_slweg($_POST['date_format']);
$content['felogin']['felogin_locale']				= clean_slweg($_POST['locale']);
$content['felogin']['felogin_validate_userdetail']	= empty($_POST['validate_userdetail']) ? 0 : 1;
$content['felogin']['felogin_validate_backenduser']	= empty($_POST['validate_backenduser']) ? 0 : 1;
$content['felogin']['felogin_profile_registration']	= empty($_POST['profile_registration']) ? 0 : 1;
$content['felogin']['felogin_profile_manage']		= empty($_POST['profile_manage']) ? 0 : 1;

?>