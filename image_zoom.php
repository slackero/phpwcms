<?php
/*************************************************************************************
   Copyright notice
   
   (c) 2002-2012 Oliver Georgi <oliver@phpwcms.de> // All rights reserved.
 
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

$phpwcms = array();
require_once('config/phpwcms/conf.inc.php');
require_once('include/inc_lib/default.inc.php');

if(empty($_GET["show"])) {

	$width_height = '';
	$img = "img/leer.gif";
	
} else {

	$img 						= base64_decode($_GET["show"]);
	list($img, $width_height)	= explode('?', $img);
	$img 						= str_replace(array('http://', 'https://', 'ftp://'), '', $img);
	$img						= strip_tags($img);
	$width_height				= strip_tags($width_height);
	$img = PHPWCMS_IMAGES.urlencode($img);

}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>

	<title>Image</title>
	<meta http-equiv="Content-Type" content="text/html; charset=<?php echo PHPWCMS_CHARSET ?>" />

	<script type="text/javascript" src="<?php echo TEMPLATE_PATH; ?>inc_js/imagezoom.js"></script>
	<link href="<?php echo TEMPLATE_PATH; ?>inc_css/dialog/popup.image.css" rel="stylesheet" type="text/css" />

</head>

<body>
<a href="#" title="Close PopUp" onclick="window.close();return false;"><img src="<?php echo $img ?>" alt="" border="0" <?php echo $width_height ?> /></a>
</body>
</html>