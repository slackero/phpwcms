<?php
/**
 * phpwcms content management system
 *
 * @author Oliver Georgi <og@phpwcms.org>
 * @copyright Copyright (c) 2002-2016, Oliver Georgi
 * @license http://opensource.org/licenses/GPL-2.0 GNU GPL-2
 * @link http://www.phpwcms.org
 *
 **/

$phpwcms = array();
require_once('include/config/conf.inc.php');
require_once('include/inc_lib/default.inc.php');

if(empty($_GET["show"])) {

    $width_height = '';
    $img = "img/leer.gif";

} else {

    $img                        = base64_decode($_GET["show"]);
    list($img, $width_height)   = explode('?', $img);
    $img                        = str_replace(array('http://', 'https://', 'ftp://'), '', $img);
    $img                        = strip_tags($img);
    $width_height               = strip_tags($width_height);
    $img = PHPWCMS_IMAGES.urlencode($img);

}

?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <title>Image</title>
    <meta http-equiv="Content-Type" content="text/html; charset=<?php echo PHPWCMS_CHARSET ?>" />
    <script type="text/javascript" src="<?php echo TEMPLATE_PATH; ?>inc_js/imagezoom.js"></script>
    <link href="<?php echo TEMPLATE_PATH; ?>inc_css/dialog/popup.image.css" rel="stylesheet" type="text/css" />
</head>
<body><a href="#" title="Close PopUp" onclick="window.close();return false;"><img src="<?php echo $img ?>" alt="" border="0" <?php echo $width_height ?> /></a></body>
</html>