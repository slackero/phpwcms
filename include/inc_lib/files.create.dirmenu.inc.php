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

//Funktionen zum Listen der privaten Dateien

function dir_menu($pid, $zid, & $dbcon, $vor, $userID, $vorzeichen = ":") {
	$pid = intval($pid);
	$sql = "SELECT f_id, f_name FROM ".DB_PREPEND."phpwcms_file WHERE ".
		   "f_pid=".intval($pid)." AND ".
		   "f_uid=".intval($userID)." AND ".
		   "f_kid=0 AND f_trash=0 ORDER BY f_name";
	$result = mysql_query($sql, $dbcon);
	while($row = mysql_fetch_row($result)) {
		$dirname = html_specialchars($row["1"]);
		echo "<option value='".$row[0]."'";
		if(intval($zid) == $row[0]) echo " selected";
		echo ">".$vor.$dirname."</option>\n";
		dir_menu($row["0"], $zid, $dbcon, $vor.$vorzeichen, $userID, $vorzeichen);
	}
	mysql_free_result($result);
	return $vor;
}

?>