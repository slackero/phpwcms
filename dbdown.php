<?php
/**
 * phpwcms
 *
 * @author Oliver Georgi <og@phpwcms.org>
 * @copyright Copyright (c) 2002-2026, Oliver Georgi
 * @license http://opensource.org/licenses/GPL-2.0 GNU GPL-2
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

?><!DOCTYPE html>
<html lang="<?php echo $phpwcms['DOCTYPE_LANG']; ?>">
<head>
    <meta charset="<?php echo PHPWCMS_CHARSET ?>" />
    <title>Sorry</title>
    <style>
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
