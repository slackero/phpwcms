<?php
/**
 * phpwcms content management system
 *
 * @author Oliver Georgi <og@phpwcms.org>
 * @copyright Copyright (c) 2002-2015, Oliver Georgi
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