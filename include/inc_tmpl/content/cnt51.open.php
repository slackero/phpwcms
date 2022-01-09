<?php
/**
 * phpwcms content management system
 *
 * @author Oliver Georgi <og@phpwcms.org>
 * @copyright Copyright (c) 2002-2022, Oliver Georgi
 * @license http://opensource.org/licenses/GPL-2.0 GNU GPL-2
 * @link http://www.phpwcms.org
 *
 **/

$phpwcms = array('SESSION_START' => true);

require_once '../../../include/config/conf.inc.php';
require_once '../../../include/inc_lib/default.inc.php';
require_once PHPWCMS_ROOT.'/include/inc_lib/helper.session.php';
require_once PHPWCMS_ROOT.'/include/inc_lib/dbcon.inc.php';
require_once PHPWCMS_ROOT.'/include/inc_lib/general.inc.php';
checkLogin();
require_once PHPWCMS_ROOT.'/include/inc_lib/backend.functions.inc.php';

?><!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=<?php echo PHPWCMS_CHARSET ?>">
	<title>Choose Location</title>
	<script type="text/javascript">
		var ie4 = document.all && navigator.userAgent.indexOf("Opera") === -1,
            ns6 = document.getElementById && !document.all,
            ns4 = document.layers;

		function setLocationXY(kx,ky) {
			window.opener.document.articlecontent.cmap_location_x.value=kx;
			window.opener.document.articlecontent.cmap_location_y.value=ky;
			window.opener.document.articlecontent.cmap_location_edited.value='1';
		}
	</script>
	<style type="text/css">
		body {
			font-family: "Helvetica Neue", Helvetica, Arial, sans-serif;
			font-size: 10px;
			margin:0;
			padding:0;
		}
	</style>
</head>
<body><?php

$map_img = clean_slweg($_GET['map']);

if($map_img && ($map_data = getimagesize(PHPWCMS_TEMPLATE.'inc_cntpart/map/map_img/'.$map_img))) {

	$map = '';
	$p = array();

	if(isset($_GET['points'])) {
		$points = explode(':|:', $_GET['points']);
		if(count($points)) {
			foreach($points as $value) {
				$point = explode(':::', $value);
				if(empty($point[1])) {
					$point[1] = 0;
				}
				if(empty($point[2])) {
					$point[2] = '';
				}
				$map .= '<area shape="rect" coords="'.($point[0]-3).','.($point[1]-3).','.($point[0]+4).','.($point[1]+4).'" href="#" title="'.$point[2].'">';
				$p[] = $point[0].'x'.$point[1];
			}
		}
	}

	echo '<a href="#" onclick="if(ie4){setLocationXY(event.offsetX,event.offsetY);}else{setLocationXY(event.clientX,event.clientY);} window.close(); return false;">';
	echo '<img src="cnt51.map.php?q=90&amp;i='.rawurlencode($map_img).'&amp;xy='.rawurlencode(implode(',',$p)).'" border="0" usemap="#Map">';
	echo '</a>';
	if($map) {
	echo '<map name="Map">'.$map.'</map>';
	}

} else {

	echo 'Please check given image data!';

}

?><div align="center"><a href="#" onclick="window.close();return false;">close window</a></div>
</body>
</html>