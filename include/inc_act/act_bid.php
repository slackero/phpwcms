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
$ref = $_SESSION['REFERER_URL'];


require_once ('../../config/phpwcms/conf.inc.php');
require_once ('../inc_lib/default.inc.php');
require_once (PHPWCMS_ROOT.'/include/inc_lib/dbcon.inc.php');

require_once (PHPWCMS_ROOT.'/include/inc_lib/general.inc.php');
require_once (PHPWCMS_ROOT.'/include/inc_lib/backend.functions.inc.php');
require_once (PHPWCMS_ROOT.'/include/inc_lib/checklogin.inc.php');

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>phpwcms Backend Bid</title>
<style type="text/css">
<!--
body,td,th {
	font-family: Verdana, Arial, Helvetica, sans-serif;
	font-size: 10px;
	color: #000000;
}
body {
	background-color: #F3F4F5;
	margin-left: 3px;
	margin-top: 3px;
	margin-right: 3px;
	margin-bottom: 3px;
	width: 417px;
}
a {
	font-family: Verdana, Arial, Helvetica, sans-serif;
	font-size: 10px;
	color: #000000;
}
a:visited {
	color: #000000;
}
a:active {
	color: #000000;
}
-->
</style></head>

<body>
<table width="100%" border="0" cellpadding="2" cellspacing="0" summary="">
<?php
if(intval($_GET['del'])) {
	
	$sql  = "UPDATE ".DB_PREPEND."phpwcms_bid SET ";
	$sql .= "bid_trashed=9 WHERE bid_cid=";
	$sql .= intval($_GET['cid'])." AND bid_id=".intval($_GET['del']);
	$sql .= " LIMIT 1;";
	mysql_query($sql, $db);

}

$sql  = "SELECT *, UNIX_TIMESTAMP(bid_created) AS bid_date FROM ".DB_PREPEND."phpwcms_bid WHERE bid_cid=";
$sql .= intval($_GET['cid'])." AND bid_trashed=0 ORDER BY bid_verified DESC, bid_amount DESC;";
$c = 0;
if($result = mysql_query($sql, $db)) {

	while($row = mysql_fetch_assoc($result)) {
	
		if($row['bid_verified']) {
			$em = '<strong><a href="mailto:'.html_specialchars($row['bid_email']).'">'.html_specialchars($row['bid_email']).'</a></strong>';
			$es = '';
		} else {
			$em = html_specialchars($row['bid_email']);
			$es = ' style="color:#979EAA"';
		}

?>
  <tr bgcolor="#E7E8EB">
    <td><strong><?php echo date('d-m-Y H:i:s', intval($row['bid_date'])) ?></strong></td>
    <td align="right"><a href="act_bid.php?<?php echo 'cid='.$row['bid_cid'].'&amp;del='.$row['bid_id'] ?>" target="_self"><img src="../../img/button/del_11x11.gif" alt="delete bid" width="11" height="11" border="0" /></a></td>
  </tr>
  <tr>
    <td<?php echo $es ?>><?php echo $em; ?></td>
	<td align="right"<?php echo $es ?>><strong><?php echo number_format($row['bid_amount'], 2, ',', '.'); ?></strong>&nbsp;&nbsp;&nbsp;&nbsp;</td>
  </tr>
  <tr><td colspan="2"><img src="../../img/leer.gif" alt="" width="1" height="1" /></td></tr>
<?php
		$c++;
	}
	mysql_free_result($result);
}

// if no bid entry available
if(!$c) {
?><tr>
    <td colspan="2">No bids available</td>
  </tr>
<?php
}

?>
</table>
</body>
</html>
