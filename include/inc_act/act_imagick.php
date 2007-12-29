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

if(!ini_get('safe_mode') && function_exists('set_time_limit')) set_time_limit(300);
$phpwcms = array();
session_start();

$ref = $_SESSION['REFERER_URL'];


require_once ('../../config/phpwcms/conf.inc.php');
require_once ('../inc_lib/default.inc.php');
require_once (PHPWCMS_ROOT.'/include/inc_lib/dbcon.inc.php');

require_once (PHPWCMS_ROOT.'/include/inc_lib/general.inc.php');
require_once (PHPWCMS_ROOT.'/include/inc_lib/checklogin.inc.php');
require_once (PHPWCMS_ROOT.'/include/inc_lib/imagick.convert.inc.php');

?>
<html><head><title>phpwcms: creating thumbnail</title>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo PHPWCMS_CHARSET ?>">
<meta http-equiv="Expires" content="0">
<meta http-equiv="Pragma" content="no-cache">
<meta http-equiv="cache-control" content="no-cache">
<link href="../inc_css/phpwcms.css" rel="stylesheet" type="text/css">
<style type="text/css">
<!--
body { background-color: #EBF2F4; }
-->
</style>
</head>
<body bgcolor="#EBF2F4" text="#000000" link="#000000" vlink="#000000" alink="#000000" leftmargin="15" topmargin="15" marginwidth="15" marginheight="15">
<table height="45" border="0" align="center" cellpadding="0" cellspacing="0" summary="">
  <tr>
    <td><img src="../../img/symbole/rotation.gif" alt="" width="15" height="15"></td>
    <td height="35" align="center" class="title"><strong>&nbsp;creating thumbnail! wait a moment!</strong></td>
  </tr>
</table>
<?php
flush();


if(intval($_GET["fcat"])) {
	$file_user_id = intval($_GET["fcat"]);
} else {
	$file_user_id = $_SESSION["wcs_user_id"];
}

$useruploadpath = PHPWCMS_ROOT.$phpwcms["file_path"].$file_user_id."/";
$dir_thumb_list	= PHPWCMS_ROOT."/".$phpwcms["file_tmp"].$phpwcms["dir_thlist"];
$dir_thumb_prev = PHPWCMS_ROOT."/".$phpwcms["file_tmp"].$phpwcms["dir_preview"];
$thumb_add		= "_".generic_string(10);
$rotation		= "";

if(intval($_GET["thumb"]) && is_ext_true(strtolower($_GET["ext"])) ) {
	$thumb_filename = $file_user_id."_".intval($_GET["thumb"]);
	if($_GET["ext"]) $thumb_filename .= ".".$_GET["ext"];
	
	if($_GET["aktion"]) {
		$rotation = $_GET["aktion"];
		if($rotation == "clock") $rotation = "-rotate \"90\"";
		if($rotation == "counter") $rotation = "-rotate \"-90\"";	
	}
	
	if(!file_exists($useruploadpath.$thumb_filename)) {
		$create_preview["error"] = "no file for thumbnail creation exists";
	} else {
	
		if(IMAGICK_ON) {
			$check_image = 1;
		} else {
			$check_image = getimagesize($useruploadpath.$thumb_filename);
			if($check_image["channels"] > 3) {
				unset($check_image);
				$check_image = 0;
			}
		}

		if($check_image) {
			$old_abort = ignore_user_abort(true);
	
			$create_preview	= imagick_converting($thumb_filename,$thumb_add,$useruploadpath,$dir_thumb_prev,$phpwcms["img_prev_width"],$phpwcms["img_prev_height"],75,0,72,$rotation);
			//check if file really exists after ImageMagick function -> maybe no error reported
			if(!file_exists($dir_thumb_prev.$create_preview["image_thumb_name"])) $create_preview["error"] = "no thumbnail exists";
			if(!$create_preview["error"]) {//create list thumbnail
				$create_listthumb = imagick_converting($create_preview["image_thumb_name"],"",$dir_thumb_prev,$dir_thumb_list, $phpwcms["img_list_width"], $phpwcms["img_list_height"]);
			}
		
			//If successful then update thumbnail image names in database
			if(!$create_preview["error"]) {
				$sql = "UPDATE ".DB_PREPEND."phpwcms_file SET ";
				$sql .= "f_thumb_preview='".aporeplace($create_preview["image_thumb_name"])."', ";
				$sql .= "f_thumb_list='".aporeplace($create_listthumb["image_thumb_name"])."' ";
				$sql .= "WHERE f_id=".intval($_GET["thumb"])." AND f_uid=".$file_user_id.";";
				mysql_query($sql , $db) or die("error while update thumbnail informations");
			} else {
				$sql = "UPDATE ".DB_PREPEND."phpwcms_file SET ";
				$sql.= "f_thumb_preview='', f_thumb_list='' ";
				$sql .= "WHERE f_id=".intval($_GET["thumb"])." AND f_uid=".$file_user_id.";";
				mysql_query($sql , $db) or die("error while update thumbnail informations");
			}
			ignore_user_abort($old_abort);
		
		} else {
			$create_preview["error"] = "file format problem: check the given image (CMYK JPEG?)";
		}
	}
}

if(!$create_preview["error"]) {
	echo "<table border=0 align=\"center\" cellpadding=0 cellspacing=0>";
	echo "<tr><td align=\"center\" class=\"title\"><strong>thumbnail created</strong></td></tr>";
	echo "<tr><td>&nbsp;</td></tr>";
	echo "<tr><td class='v10' align=\"center\"><a href=\"".$ref;
	echo "\" style=\"font-weight: bold;\">click here to go back</a><br />";
	echo "(if no automatic redirect)</td></tr></table>\n";
	echo "<script language=\"JavaScript\" type=\"text/javascript\">\n<!--\n";
	echo "window.location.href = \"".$ref."\";\n//-->\n</script>\n";
	echo "</body></html>";
} else {
	echo "<table border=0 align=\"center\" cellpadding=0 cellspacing=0>";
	echo "<tr><td align=\"center\" class=\"error\"><strong>".$create_preview["error"]."</strong></td></tr>";
	echo "<tr><td>&nbsp;</td></tr>";
	echo "<tr><td class='v10' align=\"center\"><a href=\"".$ref;
	echo "\" style=\"font-weight: bold;\">click here to go back</a>";
	echo "</td></tr></table>\n";
	echo "</body></html>";
}
flush();

?>