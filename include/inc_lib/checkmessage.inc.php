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



//Check is actual user has new messages waiting
$sql = "SELECT COUNT(*) FROM ".DB_PREPEND."phpwcms_message WHERE msg_uid=".$_SESSION["wcs_user_id"]." AND msg_read=0;";
if($check = mysql_query($sql, $db)) {
	$wcs_msg_waiting = ($row = mysql_fetch_row($check)) ? $row[0] : 0;
} else {
	$wcs_msg_waiting = 0;
}

if($wcs_msg_waiting) {

	$wcsnav["navspace1"] = "<a href=\"phpwcms.php?do=messages\" title=\"".$wcs_msg_waiting." new messages waiting!\">".
						   "<img src=\"img/symbole/new_mail.gif\" border=\"0\"></a>";
	$new_mail_waiting = 1;	
}
?>