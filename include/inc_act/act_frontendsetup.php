<?php
/*************************************************************************************
   Copyright notice
   
   (c) 2002-2008 Oliver Georgi (oliver@phpwcms.de) // All rights reserved.
 
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


// session_name('hashID');
session_start();
$phpwcms = array();

require_once ('../../config/phpwcms/conf.inc.php');
require_once ('../inc_lib/default.inc.php');
require_once (PHPWCMS_ROOT.'/include/inc_lib/dbcon.inc.php');

require_once (PHPWCMS_ROOT.'/include/inc_lib/general.inc.php');
require_once (PHPWCMS_ROOT.'/include/inc_lib/backend.functions.inc.php');
require_once (PHPWCMS_ROOT.'/include/inc_lib/checklogin.inc.php');

if($_SESSION["wcs_user_admin"] == 1) { //If user has admin rights

	list($do, $id) = explode('|', $_GET['do']);
	$do = intval($do);
	$id = intval($id);
	
	if($id) {
	
		switch($do) {
	
			case 1:	// delete pagelayout
					mysql_query("UPDATE ".DB_PREPEND."phpwcms_pagelayout SET pagelayout_default=0, ".
								"pagelayout_trash=9 WHERE pagelayout_id=".$id, $db);
					break;
				
			case 2:	// delete template
					mysql_query("UPDATE ".DB_PREPEND."phpwcms_template SET template_default=0, ".
								"template_trash=9 WHERE template_id=".$id, $db);
					break;	
	
		}
		
	}

	
} //End action

headerRedirect($_SESSION['REFERER_URL']);

?>