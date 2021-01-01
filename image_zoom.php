<?php
/**
 * phpwcms content management system
 *
 * @author    Oliver Georgi <og@phpwcms.org>
 * @copyright Copyright (c) 2002-2021, Oliver Georgi
 * @license   http://opensource.org/licenses/GPL-2.0 GNU GPL-2
 * @link      http://www.phpwcms.org
 *
 **/

$phpwcms = array();
require_once('include/config/conf.inc.php');
require_once('include/inc_lib/default.inc.php');

$src = "img/leer.gif";
$name = '';
$attr = '';

if(!empty($_GET["show"]) && ($data = @unserialize(base64_decode($_GET["show"])))) {

    $src = strip_tags(str_replace(array('http://', 'https://', 'ftp://'), '', $data['src']));
    $width_height = strip_tags($data['attr']);
    $name = $data['name'];

}

?><!DOCTYPE html>
<htm lang="<?php echo $phpwcms['DOCTYPE_LANG']; ?>">
<head>
    <title><?php echo html($name); ?></title>
    <meta charset="<?php echo PHPWCMS_CHARSET ?>">
    <script type="text/javascript" src="<?php echo TEMPLATE_PATH; ?>inc_js/imagezoom.js"></script>
    <link href="<?php echo TEMPLATE_PATH; ?>inc_css/dialog/popup.image.css" rel="stylesheet">
</head>
<body><a href="#" title="Close PopUp" onclick="window.close();return false;"><img src="<?php echo html($src); ?>" alt="<?php echo html($name); ?>"<?php echo $attr; ?> /></a></body>
</html>