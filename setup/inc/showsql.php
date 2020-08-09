<?php
/**
 * cmsGO!
 *
 * @author Pixels & Points GmbH <info@pixels-points.ch>
 * @copyright Copyright (c) 2002-2020, Pixels & Points GmbH
 * @license https://www.pixels-points.ch/cmsgo-license.html Pixels & Points cmsGO! license
 *
 **/

$cmsgo = array();
require_once '../../include/config/conf.inc.php';
require_once '../../include/inc_lib/default.inc.php';
require_once CMSGO_ROOT.'/include/inc_lib/dbcon.inc.php';
require_once CMSGO_ROOT.'/include/inc_lib/general.inc.php';

$sqldata = '';

if(isset($_GET['f'])) {
    $file = str_replace(array('..', '/', "\\"), '', clean_slweg($_GET['f']));
    $sql_data = read_textfile(CMSGO_ROOT.'/setup/update_sql/'.$file);
}

?><!DOCTYPE html>
<html>
<head>
    <meta charset="<?php echo $cmsgo['charset']; ?>">
    <title>Upgrade cmsgo</title>
    <style type="text/css">
    body {
        margin: 0;
        padding: 10px;
        background-color: #F6F8FA;
        color: #000000;
    }
    pre {
        font-size: 13px;
        font-family: Menlo, "Courier New", Courier, monospace;
        margin: 0;
        padding: 0;
    }
    </style>
</head>

<body>
    <pre><?php echo empty($sql_data) ? 'No update/upgrade SQL file selected<br /><br />See above menu ... [select SQL file]' : $sql_data; ?></pre>
</body>
</html>