<?php
/*************************************************************************************
   Copyright notice
   
   (c) 2002-2010 Oliver Georgi (oliver@phpwcms.de) // All rights reserved.
 
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


// session_name('hashID');
session_start();
$phpwcms = array();

require_once ('../../config/phpwcms/conf.inc.php');
require_once ('../inc_lib/default.inc.php');
require_once (PHPWCMS_ROOT.'/include/inc_lib/dbcon.inc.php');

require_once (PHPWCMS_ROOT.'/include/inc_lib/general.inc.php');
checkLogin();
require_once (PHPWCMS_ROOT.'/include/inc_lib/backend.functions.inc.php');


$dl = isset($_GET["dl"]) ? intval($_GET["dl"]) : 0;
$pl = isset($_GET["pl"]) ? intval($_GET["pl"]) : 0;

if($dl) {
	$err = 0;
	if(!$pl) {
		$sql =	"SELECT * FROM ".DB_PREPEND."phpwcms_file WHERE f_trash=0 AND ".
				"f_id=".$dl." AND f_kid=1 AND f_uid=".$_SESSION["wcs_user_id"]." LIMIT 1;";
				//09-20-2003: WHERE f_aktiv=1 AND... not neccessary
	} else {
		$sql =	"SELECT * FROM ".DB_PREPEND."phpwcms_file WHERE f_aktiv=1 AND f_trash=0 AND ".
				"f_id=".$dl." AND f_kid=1 AND (f_public=1 OR f_uid=".$_SESSION["wcs_user_id"].") LIMIT 1;";
	}

	if($result = mysql_query($sql, $db) or die ("error while retrieving file download infos")) {;
		if($download = mysql_fetch_array($result)) {
	
			//$dl_filename = 	$download["f_uid"]."_".$download["f_id"];
			//if($download["f_ext"]) $dl_filename .= ".".$download["f_ext"];
			
			$dl_filename = $download["f_hash"];
			if($download["f_ext"]) {
				$dl_filename .= '.'.$download["f_ext"];
			}
			//$dl_path = PHPWCMS_ROOT.$phpwcms["file_path"].$download["f_uid"]."/";
			$dl_path = PHPWCMS_ROOT.$phpwcms["file_path"];
			
			if(file_exists($dl_path.$dl_filename)) { //echo "Datei existiert<br />";
				//Send file to user
				//Downloads must downloaded to harddisk
				//are not opened and showed inside browser
				//if possible file format (PDF/JPG...)
				//$att = (stristr($_SERVER['HTTP_USER_AGENT'],"MSIE")) ? "" : "attachment; ";
				if($download["f_type"]) {
					header("Content-type: ".$download["f_type"]);
				} else {
					header("Content-type: application/force-download");
				}
				header('Content-Disposition: attachment; filename="'.$download["f_name"].'"');
				header("Content-Length: " . filesize($dl_path.$dl_filename));
				if(readfile($dl_path.$dl_filename)) {
					exit();
				} else {
					$err = 4;
				}				
				
			} else {
				$err = 1; //Wenn Datei nicht exisitiert
			}
		} else {
			$err = 2; //Wenn keine Datei aus Datenbank zurückgegeben wird
		}
	}
} else {
	$err = 3; //Wenn keine korrekte id übergeben wird
}

if($err) {
	session_destroy();	

?><html>
<head>
<title>phpwcms File Error</title>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo PHPWCMS_CHARSET ?>">
<link href="../inc_css/phpwcms.css" rel="stylesheet" type="text/css">
</head>

<body bgcolor="#FFFFFF" text="#000000" link="#FF9900" vlink="#FF9900" alink="#FF9900" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<table border="0" cellpadding="0" cellspacing="0" summary="">
  <tr>
    <td width="9"><img src="../../img/leer.gif" alt="" width="9" height="1"></td>
    <td width="250"><img src="../../img/leer.gif" alt="" width="1" height="15"><br />
      An error (ID:<?php echo $err ?>) occured while trying to download a file of your directory.<br />
      <img src="../../img/leer.gif" alt="" width="1" height="10"><br />
      Please <a href="<?php echo PHPWCMS_URL.get_login_file() ?>"><strong>login</strong></a> again<br />
      and try another file.<br />
      <img src="../../img/leer.gif" alt="" width="1" height="10"><br />
      If you think that this might be a technical problem send an email to the <a href="mailto:webmaster@mailverbund.de">webmaster</a>.</td>
  </tr>
</table>
</body>
</html>
<?php
}
?>