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



// Forum
$content["forum"]["template"]		= empty($_POST["cforum_template"]) ? '' : clean_slweg($_POST["cforum_template"]);
$content["forum"]['selection'] 		= empty($_POST["cforum_selection"]) ? array() : $_POST["cforum_selection"];

$content['forum']['permissions'] = array();
$content['forum']['permissions']['read']['admin']	= empty($_POST["cforum_permission_admin_read"]) ? 0 : 1;
$content['forum']['permissions']['read']['user']	= empty($_POST["cforum_permission_user_read"]) ? 0 : 1;
$content['forum']['permissions']['read']['guest']	= empty($_POST["cforum_permission_guest_read"]) ? 0 : 1;

$content['forum']['permissions']['write']['admin']	= empty($_POST["cforum_permission_admin_write"]) ? 0 : 1;
$content['forum']['permissions']['write']['user']	= empty($_POST["cforum_permission_user_write"]) ? 0 : 1;
$content['forum']['permissions']['write']['guest']	= empty($_POST["cforum_permission_guest_write"]) ? 0 : 1;

$content['forum']['permissions']['delete']['admin']	= empty($_POST["cforum_permission_admin_delete"]) ? 0 : 1;
$content['forum']['permissions']['delete']['user']	= empty($_POST["cforum_permission_user_delete"]) ? 0 : 1;
$content['forum']['permissions']['delete']['guest']	= empty($_POST["cforum_permission_guest_delete"]) ? 0 : 1;



?>