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

require_once ('../../config/phpwcms/conf.inc.php');
require_once ('../../include/inc_lib/default.inc.php');
require_once (PHPWCMS_ROOT.'/include/inc_lib/dbcon.inc.php');
require_once (PHPWCMS_ROOT.'/include/inc_lib/general.inc.php');

$sqldata = '';

if(isset($_GET['f'])) {
	$file = str_replace(array('..', '/', "\\"), '', clean_slweg($_GET['f']));
	$sql_data = read_textfile(PHPWCMS_ROOT.'/setup/update_sql/'.$file);
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Upgrade phpwcms</title>
<style type="text/css">
<!--
pre {
	font-size:	9pt;
	font-family:"Courier New", Courier, mono;
	margin:	0;
	padding: 0;
}
body {
	margin:	0;
	padding: 10px;
	background-color: #F6F8FA;
	color: #000000;
}
//-->
</style>
</head>

<body>
<pre><?php
if(!empty($sql_data)) {
	echo $sql_data;
} else {
	echo 'No update/upgrade SQL file selected<br />See above menu...';
}

?></pre>
</body>
</html>
