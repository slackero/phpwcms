<?php
/*************************************************************************************
   Copyright notice
   
   (c) 2002-2007 Oliver Georgi (oliver@phpwcms.de) // All rights reserved.
 
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

session_start();

$phpwcms = array();
require_once('../../../config/phpwcms/conf.inc.php');
require_once('../../../include/inc_lib/default.inc.php');
require_once (PHPWCMS_ROOT.'/include/inc_lib/dbcon.inc.php');
require_once (PHPWCMS_ROOT.'/include/inc_lib/general.inc.php');
require_once (PHPWCMS_ROOT.'/include/inc_lib/backend.functions.inc.php');
require_once (PHPWCMS_ROOT.'/include/inc_lib/checklogin.inc.php');


?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<title>Choose Location</title>
<script type="text/javascript" language="javascript">
<!--
var ie4 = document.all&&navigator.userAgent.indexOf("Opera")==-1;
var ns6 = document.getElementById&&!document.all;
var ns4 = document.layers;

function setLocationXY(kx,ky) {
	//alert('hier');
	window.opener.document.articlecontent.cmap_location_x.value=kx;
	window.opener.document.articlecontent.cmap_location_y.value=ky;
	window.opener.document.articlecontent.cmap_location_edited.value='1';
}
//-->
</script>
<style type="text/css">
<!--
body {
	font-family: Verdana, Arial, Helvetica, sans-serif;
	font-size: 10px;
	margin:0;
	padding:0;
}
-->
</style>
</head>

<body>
<?php


$map_img = clean_slweg($_GET['map']);

if($map_data = getimagesize(PHPWCMS_TEMPLATE.'inc_cntpart/map/map_img/'.$map_img)) {

	$map = '';
	$p = array();
	
	if(isset($_GET['points'])) {
		$points = explode(':|:', $_GET['points']);
		if(count($points)) {
			foreach($points as $value) {
				$point = explode(':::', $value);
				if(empty($point[1])) $point[1] = 0;
				if(empty($point[2])) $point[2] = '';
				$map .= '<area shape="rect" coords="'.($point[0]-3).','.($point[1]-3);
				$map .= ','.($point[0]+4).','.($point[1]+4).'" href="#" title="';
				$map .= $point[2]."\">\n";
				$p[] = $point[0].'x'.$point[1];
			}
		}
	}
	
	echo '<a href="#" onclick="if(ie4){setLocationXY(event.offsetX,event.offsetY);}else{setLocationXY(event.clientX,event.clientY);} window.close(); return false;">';
	echo '<img src="cnt51.map.php?q=90&amp;i='.rawurlencode($map_img).'&amp;xy='.rawurlencode(implode(',',$p)).'" border="0" usemap="#Map">';
	echo '</a>';
	if($map) {
	echo '<map name="Map">
	'.$map.'</map>';
	}
	
} else {

	echo 'Please check given image data!';

}

?>
<div align="center"><a href="#" onclick="window.close();return false;">close window</a></div>
</body>
</html>
