<?php
/**
 * phpwcms content management system
 *
 * @author Oliver Georgi <oliver@phpwcms.de>
 * @copyright Copyright (c) 2002-2014, Oliver Georgi
 * @license http://opensource.org/licenses/GPL-2.0 GNU GPL-2
 * @link http://www.phpwcms.de
 *
 **/

session_start();

$phpwcms = array();
require_once ('config/phpwcms/conf.inc.php');

if(empty($_SESSION["wcs_user_lang"])) {
	session_destroy();
	headerRedirect($phpwcms['site'].$phpwcms["root"]);

} else {
	require('include/inc_lang/backend/en/lang.ext.inc.php');
	$cust_lang = 'include/inc_lang/backend/'.substr($_SESSION["wcs_user_lang"],0,2).'/lang.ext.inc.php';
	if(is_file($cust_lang)) include($cust_lang);
}
require_once ('include/inc_lib/default.inc.php');
require_once (PHPWCMS_ROOT.'/include/inc_lib/dbcon.inc.php');

require_once ("include/inc_lib/general.inc.php");
checkLogin();
require_once ("include/inc_lib/backend.functions.inc.php");
require_once ("include/inc_lib/imagick.convert.inc.php");
require_once ("include/inc_lib/autolink.inc.php");

$file_id	= (isset($_GET["fid"])) ? intval($_GET["fid"]) : 0;
$public		= (isset($_GET["public"])) ? true : false;

if($file_id) {

	$file_key = get_list_of_file_keywords();

	if($public) {
		//public file
		$sql  = "SELECT * FROM ".DB_PREPEND."phpwcms_file WHERE f_id=".$file_id." ";
		$sql .= "AND f_kid=1 AND f_trash=0 AND f_aktiv=1 AND (f_public=1";
		if(empty($_SESSION["wcs_user_admin"])) {
			$sql .= " OR f_uid=".intval($_SESSION["wcs_user_id"]);
		}
		$sql .= ") LIMIT 1";
	} else {
		//private file
		$sql  = "SELECT * FROM ".DB_PREPEND."phpwcms_file WHERE f_id=".$file_id.' AND ';
		$sql .= "f_kid=1 AND f_trash IN (0, 1) ";
		if(empty($_SESSION["wcs_user_admin"])) {
			$sql .= "AND f_uid=".intval($_SESSION["wcs_user_id"]).' ';
		}
		$sql .= "LIMIT 1";
	}
	if($result = mysql_query($sql, $db) or die("error")) {
		if($row = mysql_fetch_assoc($result)) {
			$filename = html_specialchars($row["f_name"]);

			$thumb_image = get_cached_image(array(
				"target_ext"	=>	$row["f_ext"],
				"image_name"	=>	$row["f_hash"] . '.' . $row["f_ext"],
				"thumb_name"	=>	md5($row["f_hash"].'538538'.$phpwcms["sharpen_level"].$phpwcms['colorspace']),
				"max_width"		=>	538,
				"max_height"	=>	538
			));

?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title><?php echo $BL['FILEINFO_TITLE'] ?>: <?php echo $filename ?></title>
	<meta http-equiv="Content-Type" content="text/html; charset=<?php echo PHPWCMS_CHARSET ?>" />
	<meta http-equiv="Expires" content="0" />
	<meta http-equiv="Pragma" content="no-cache" />
	<meta http-equiv="cache-control" content="no-cache" />
	<meta name="robots" content="noindex, nofollow" />
	<script src="include/inc_js/phpwcms.js" type="text/javascript"></script>
<?php
	echo '	<script type="text/javascript">
	function ResizeAndCenter(){
		var width = 590;
	';
	if($thumb_image != false) {
		echo '
		var height = screen.availHeight;
		if(height < 490) {
			height=420;
		} else {
			height=570;
		}
	';
	} else {
		echo '	var height = 300;';
	}

	echo '
		window.moveTo(5,5);
		window.resizeTo(width,height);
	}
		</script>
	';

?>

	<link href="include/inc_css/phpwcms.css" rel="stylesheet" type="text/css" />
</head>

<body onload="ResizeAndCenter();">
<table width="100%" border="0" align="center" cellpadding="0" cellspacing="0" bgcolor="#C1D2E2" summary="">
  <tr bgcolor="#C1D2E2"><td colspan="4"><img src="img/leer.gif" alt="" width="1" height="8" /></td></tr>
  <tr bgcolor="#C1D2E2">
    <td width="10"><img src="img/leer.gif" alt="" width="10" height="1" /></td>
    <td width="20"><img src='img/icons/small_<?php echo extimg($row["f_ext"]) ?>' alt="" border="0" /></td>
    <td width="518" class="h14b"><strong><?php echo $filename ?></strong></td>
    <td width="10"><img src="img/leer.gif" alt="" width="10" height="1" /></td>
  </tr>
  <tr bgcolor="#C1D2E2"><td colspan="4"><img src="img/leer.gif" alt="" width="10" height="6" /></td></tr>
  <tr bgcolor="#363E57"><td colspan="4"><img src="img/leer.gif" alt="" width="1" height="1" /></td></tr>
  <tr><td colspan="4" bgcolor="#F5F8F9"><img src="img/leer.gif" alt="" width="1" height="5" /></td></tr>
  <tr>
    <td bgcolor="#F5F8F9"><img src="img/leer.gif" alt="" width="1" height="1" /></td>
    <td bgcolor="#F5F8F9"><img src="img/leer.gif" alt="" width="1" height="1" /></td>
    <td bgcolor="#F5F8F9"><table width="518" border="0" cellpadding="0" cellspacing="0" summary="">
      <tr>
        <td width="422" class="v10"><?php echo $BL['CREATED'] ?>: <strong><?php echo date($BL['DATE_FORMAT'], intval($row["f_created"])) ?></strong>&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $BL['SIZE'] ?>: <strong><?php echo fsizelong($row["f_size"]) ?></strong></td>
        <td width="96" align="right"><?php
		if(!$row["f_trash"]) {
		?><a href="include/inc_act/act_download.php?dl=<?php

		echo $row["f_id"];
		//download public file too
		if($public) echo '&amp;pl=1';

		?>" target="_blank" title="<?php echo $BL['DOWNLOAD_FILE'].": ".$filename ?>"><img src="img/button/download_disc_large.gif" alt="" width="61" height="13" border="0" /></a><?php
		} else {
		 echo "<img src=\"img/button/file_in_trash.gif\" width=\"61\" height=\"13\" border=\"0\" title=\"".$BL['FILE_IN_TRASH']."\">";
		}
		?><img src="img/leer.gif" alt="" width="9" height="1" /><img src="img/button/aktiv_12x13_<?php echo $row["f_aktiv"] ?>.gif" alt="" width="12" height="13" /><img src="img/button/public_12x13_<?php echo $row["f_public"] ?>.gif" alt="" width="12" height="13" /></td>
      </tr>
    </table></td>
    <td bgcolor="#F5F8F9"><img src="img/leer.gif" alt="" width="1" height="1" /></td>
  </tr>
  <tr><td colspan="4" bgcolor="#F5F8F9"><img src="img/leer.gif" alt="" width="1" height="5" /></td></tr>
  <tr><td colspan="4" bgcolor="#CDDEE4"><img src="img/leer.gif" alt="" width="1" height="1" /></td></tr>
  <tr><td colspan="4"><img src="img/leer.gif" alt="" width="1" height="5" /></td></tr>
  <tr>
    <td><img src="img/leer.gif" alt="" width="1" height="5" /></td>
    <td><img src="img/leer.gif" alt="" width="1" height="5" /></td>
    <td class="v10"><?php echo $BL['KEYWORDS'].": ".html_specialchars($row["f_shortinfo"].add_keywords_to_search ($file_key, $row["f_keywords"])) ?></td>
    <td><img src="img/leer.gif" alt="" width="1" height="5" /></td>
  </tr>
  <tr><td colspan="4"><img src="img/leer.gif" alt="" width="1" height="5" /></td></tr>
<?php

	if($thumb_image != false) {
?>
  <tr><td colspan="4" bgcolor="#CDDEE4"><img src="img/leer.gif" alt="" width="1" height="1" /></td></tr>
  <tr><td colspan="4" bgcolor="#F5F8F9"><img src="img/leer.gif" alt="" width="1" height="5" /></td></tr>
  <tr>
    <td bgcolor="#F5F8F9"><img src="img/leer.gif" alt="" width="1" height="1" /></td>
    <td colspan="2" align="center" bgcolor="#F5F8F9"><?php

	echo '<img src="'.PHPWCMS_IMAGES . $thumb_image[0] .'" border="0" '.$thumb_image[3].'>';

	?></td>
    <td bgcolor="#F5F8F9"><img src="img/leer.gif" alt="" width="1" height="5" /></td>
  </tr>
  <tr><td colspan="4" bgcolor="#F5F8F9"><img src="img/leer.gif" alt="" width="1" height="5" /></td></tr>
<?php
	}
	if($row["f_longinfo"]) {
?>
  <tr><td colspan="4" bgcolor="#CDDEE4"><img src="img/leer.gif" alt="" width="1" height="1" /></td></tr>
  <tr><td colspan="4"><img src="img/leer.gif" alt="" width="1" height="5" /></td></tr>
  <tr>
    <td><img src="img/leer.gif" alt="" width="1" height="5" /></td>
    <td><img src="img/leer.gif" alt="" width="1" height="5" /></td>
    <td class="v10"><?php echo nl2br(auto_link(html_specialchars($row["f_longinfo"]))) ?></td>
    <td><img src="img/leer.gif" alt="" width="1" height="5" /></td>
  </tr>
  <tr><td colspan="4"><img src="img/leer.gif" alt="" width="1" height="5" /></td></tr>
  <?php } ?>
  <tr><td colspan="4" bgcolor="#CDDEE4"><img src="img/leer.gif" alt="" width="1" height="1" /></td></tr>
  <tr bgcolor="#EBF2F4">
    <td><img src="img/leer.gif" alt="" width="10" height="10" /></td>
    <td><img src="img/leer.gif" alt="" width="20" height="1" /></td>
    <td><img src="img/leer.gif" alt="" width="518" height="1" /></td>
    <td><img src="img/leer.gif" alt="" width="10" height="10" /></td>
  </tr>
</table>
</body>
</html>
<?php
		} else {
			$fehler = 1;
		}
	} else {
		$fehler = 1;
	}
} else {
	$fehler = 1;
}

if(isset($fehler)) {
	echo $BL['DOWNLOAD_ERR3'];
}
?>