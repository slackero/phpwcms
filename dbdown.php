<?php
/**
 * cmsGO!
 *
 * @author Pixels & Points GmbH <info@pixels-points.ch>
 * @copyright Copyright (c) 2002-2021, Pixels & Points GmbH
 * @license https://www.pixels-points.ch/cmsgo-license.html Pixels & Points cmsGO! license
 *
 **/

$cmsgo = array();
$basepath = str_replace('\\', '/', dirname(__FILE__));
require_once $basepath.'/include/config/conf.inc.php';
require_once $basepath.'/include/inc_lib/default.inc.php';
require_once CMSGO_ROOT.'/include/inc_lib/helper.session.php';
require_once CMSGO_ROOT.'/include/inc_lib/dbcon.inc.php';

// database accessible again?
if($is_mysql_error === false) {
    headerRedirect(CMSGO_URL, 302, false); // keep session intact
}

// sending system down message and send 503
header('HTTP/1.1 503 Service Temporarily Unavailable');
header('Retry-After: 3600');

?><!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta charset="utf-8">
    <title>Sorry</title>
    <style type="text/css">
        body {
            background-color: #fff;
            margin: 50px;
            text-align: center;
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif, "Apple Color Emoji", "Segoe UI Emoji", "Segoe UI Symbol";
            font-size: 16px;
            color: #000000;
        }
        h1 {
            font-size: 36px;
            font-weight: bold;
            margin: .5em 0;
        }
        p {
            margin: .75em 0;
        }
    </style>
</head>

<body>
    <h1>Diese Seite ist im Moment nicht verf&uuml;gbar!</h1>
    <p>Unsere Seite wird im Moment aktualisiert.</p>
    <p>Besuchen Sie uns sp&auml;ter wieder!</p>
</body>
</html>