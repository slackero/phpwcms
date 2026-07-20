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
require_once('include/config/conf.inc.php');
require_once('include/inc_lib/default.inc.php');

$src = "img/leer.gif";
$name = '';
$attr = '';

if(!empty($_GET["show"]) && ($data = json_decode(base64_decode($_GET["show"]), true))) {
    $src = strip_tags($data['src']);
    $src_schema = parse_url($src);
    if (!empty($src_schema['scheme'])) {
        $src = "img/leer.gif";
    }
    $width_height = strip_tags($data['attr']);
    $name = $data['name'];
}

?><!DOCTYPE html>
<html lang="<?php echo $phpwcms['DOCTYPE_LANG']; ?>">
<head>
    <title><?php echo html($name); ?></title>
    <meta charset="<?php echo PHPWCMS_CHARSET ?>">
    <script src="<?php echo TEMPLATE_PATH; ?>inc_js/imagezoom.js"></script>
    <link href="<?php echo TEMPLATE_PATH; ?>inc_css/dialog/popup.image.css" rel="stylesheet">
</head>
<body><a href="#" title="Close PopUp" onclick="window.close();return false;"><img src="<?php echo html($src); ?>" alt="<?php echo html($name); ?>" /></a></body>
</html>
