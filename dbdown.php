<?php
/**
 * phpwcms content management system
 *
 * @author Oliver Georgi <og@phpwcms.org>
 * @copyright Copyright (c) 2002-2017, Oliver Georgi
 * @license http://opensource.org/licenses/GPL-2.0 GNU GPL-2
 * @link http://www.phpwcms.org
 *
 **/

$phpwcms = array();
$basepath = str_replace('\\', '/', dirname(__FILE__));
require_once $basepath.'/include/config/conf.inc.php';
require_once $basepath.'/include/inc_lib/default.inc.php';
require_once PHPWCMS_ROOT.'/include/inc_lib/helper.session.php';
require_once PHPWCMS_ROOT.'/include/inc_lib/dbcon.inc.php';

// database accessible again?
if($is_mysql_error === false) {
    headerRedirect(PHPWCMS_URL, 302, false); // keep session intact
}

// sending system down message and send 503
header('HTTP/1.1 503 Service Temporarily Unavailable');
header('Retry-After: 3600');

?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Sorry</title>
<style type="text/css">
<!--
body,td,th {
    font-family: Verdana, Arial, Helvetica, sans-serif;
    font-size: 12px;
    color: #000000;
}
body {
    background-color: #FFFFFF;
    margin: 25px 15px 15px 15px;
    text-align: center;
}
h1 {
    font-family: Verdana, Arial, Helvetica, sans-serif;
    font-size: 18px;
    font-weight: bold;
    margin: 0 0 10px 0;
}
p {
    margin: 0 0 5px 0;
}
-->
</style></head>

<body><h1>We are sorry!</h1>
<p>For service reasons the system is temporarily not attainable.</p>
<p>Visit us later again!</p>
</body>
</html>