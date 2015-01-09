<?php
/**
 * phpwcms content management system
 *
 * @author Oliver Georgi <oliver@phpwcms.de>
 * @copyright Copyright (c) 2002-2014, Oliver Georgi
 * @license http://opensource.org/licenses/GPL-2.0 GNU GPL-2
 * @link http://www.phpwcms.de
 *
 **/

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
