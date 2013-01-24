<?php
/**
 * phpwcms content management system
 *
 * @author Oliver Georgi <oliver@phpwcms.de>
 * @copyright Copyright (c) 2002-2013, Oliver Georgi
 * @license http://opensource.org/licenses/GPL-2.0 GNU GPL-2
 * @link http://www.phpwcms.de
 *
 **/

session_start();
$phpwcms = array();

require_once ('../../config/phpwcms/conf.inc.php');
require_once ('../inc_lib/default.inc.php');
require_once (PHPWCMS_ROOT.'/include/inc_lib/dbcon.inc.php');

require_once (PHPWCMS_ROOT.'/include/inc_lib/general.inc.php');
checkLogin();
require_once (PHPWCMS_ROOT.'/include/inc_lib/backend.functions.inc.php');

$ref = $_SESSION['REFERER_URL'];
$ftp = array();
$ftp["error"] = 0;

$ftp["mark"]		= isset($_POST["ftp_mark"]) ? $_POST["ftp_mark"] : false;
$ftp["file"]		= isset($_POST["ftp_file"]) ? $_POST["ftp_file"] : false;
$ftp["filename"]	= isset($_POST["ftp_filename"]) ? $_POST["ftp_filename"] : false;

if(is_array($ftp["mark"]) && count($ftp["mark"])) {
	foreach($ftp["mark"] as $key => $value) {
		if(intval($ftp["mark"][$key])) {
			$ftp["file"][$key]		= base64_decode($ftp["file"][$key]);
			$ftp["filename"][$key]	= clean_slweg($ftp["filename"][$key]);
		} else {
			unset($ftp["mark"][$key], $ftp["file"][$key], $ftp["filename"][$key]);
		}
	}	
	if(!count($ftp["mark"])) $ftp["error"] = 1;	
} else {
	$ftp["error"] = 1;
}

?>
<html>
<head><title>phpwcms: creating thumbnail</title>
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
<?php
if(!$ftp["error"]) {

	$ftp["dir"]			= intval($_POST["file_dir"]);
	$ftp["short_info"]	= clean_slweg($_POST["file_shortinfo"]);
	
	$ftp["aktiv"]		= empty($_POST["file_aktiv"]) ? 0 : 1;
	$ftp["public"]		= empty($_POST["file_public"]) ? 0 : 1;
	$ftp["replace"] 	= empty($_POST["file_replace"]) ? 0 : 1;
	$ftp["long_info"]	= slweg($_POST["file_longinfo"]);
	$ftp["copyright"]	= slweg($_POST["file_copyright"]);
	$ftp["tags"]		= trim( trim( clean_slweg($_POST["file_tags"]), ',') );
	$ftp["keywords"]	= isset($_POST["file_keywords"]) ? $_POST["file_keywords"] : array();
	$ftp["keys"] 		= "";
	if(is_array($ftp["keywords"]) && count($ftp["keywords"])) {
		foreach($ftp["keywords"] as $key => $value) {
			unset($ftp["keywords"][$key]);
			$key = intval($key);
			if($value != "0_1") {
				$ftp["keys"] .= (($ftp["keys"]) ? ":" : "").$key."_".intval($value);
				$ftp["keywords"][$key] = intval($value);
			} else {
				$file_error["keywords"][$key] = 1;
			}		
		}
	}
	
	$ftp['fileVarsField'] = '';
	$ftp['fileVarsValue'] = '';
	
	if(count($phpwcms['allowed_lang']) > 1) {
		
		$ftp['file_vars'] = array();
		
		foreach($phpwcms['allowed_lang'] as $lang) {
			$lang = strtolower($lang);
			
			if(isset($_POST['file_longinfo_'.$lang])) {
				$ftp['file_vars'][$lang]['longinfo'] = slweg($_POST['file_longinfo_'.$lang]);
			}
			if(isset($_POST['file_copyright_'.$lang])) {
				$ftp['file_vars'][$lang]['copyright'] = slweg($_POST['file_copyright_'.$lang]);
			}
		}
		
		if(count($ftp['file_vars'])) {
			$ftp['fileVarsField'] = ',f_vars';
			$ftp['fileVarsValue'] = ','._dbEscape(serialize($ftp['file_vars']));
		}		
	} 
	
	
?><p><img src="../../img/symbole/rotation.gif" alt="" width="15" height="15"><strong class="title">&nbsp;selected files uploaded via ftp will be taken over!</strong></p><?php

	echo "<p class=\"v10\">";	  
	flush();
	
	foreach($ftp["mark"] as $key => $value) {
		if(!ini_get('safe_mode') && function_exists('set_time_limit')) set_time_limit(60);
		
		$file = $ftp["file"][$key];
		$file_path = PHPWCMS_ROOT.$phpwcms["ftp_path"].$file;
		if(is_file($file_path)) {
		
			$file_type = '';
			$file_error["upload"] = 0;
			$file_size	= filesize($file_path);

			$file_ext  = check_image_extension($file_path);
			$file_ext  = (false === $file_ext) ? which_ext($file) : $file_ext;
			
			$file_name = $ftp["filename"][$key];
			$file_hash = md5( $file_name . microtime() );
			
			
			if(trim($file_type) == '') {
	
				//check file_type
				if(is_mimetype_by_extension($file_ext)) {
					$file_type = get_mimetype_by_extension($file_ext);
				} else {
					$file_check	= getimagesize($file_path);
					if(version_compare("4.3.0", phpversion(), ">=") && $file_check) {
						$file_type = image_type_to_mime_type($file_check[2]);
					}
					if(!is_mimetype_format($file_type)) {
						$file_type = get_mimetype_by_extension($file_ext);
					}
				}
			
			}
			
			$sql =  "INSERT INTO ".DB_PREPEND."phpwcms_file (".
					"f_pid, f_uid, f_kid, f_aktiv, f_public, f_name, f_created, f_size, f_type, f_ext, ".
					"f_shortinfo, f_longinfo, f_keywords, f_hash, f_copyright, f_tags".$ftp['fileVarsField'].") VALUES (".
					$ftp["dir"].", ".intval($_SESSION["wcs_user_id"]).", 1, ".$ftp["aktiv"].", ".$ftp["public"].", '".
					aporeplace($file_name)."', '".time()."', '".$file_size."', '".aporeplace($file_type)."', '".
					aporeplace($file_ext)."', '".aporeplace($ftp["short_info"])."', '".
					aporeplace($ftp["long_info"])."', '".$ftp["keys"]."', '".$file_hash."', '".
					aporeplace($ftp["copyright"])."', '".aporeplace($ftp["tags"])."'".$ftp['fileVarsValue'].")";
					
			if($result = mysql_query($sql, $db) or die("error while insert file information")) {
				$new_fileId = mysql_insert_id($db); //Festlegen der aktuellen File-ID
				
				$_file_extension = ($file_ext) ? '.'.$file_ext : '';
				$wcs_newfilename = $file_hash . $_file_extension;

				// changed for using hashed file names
				$userftppath    = PHPWCMS_ROOT.$phpwcms["ftp_path"];
				$useruploadpath = PHPWCMS_ROOT.$phpwcms["file_path"];
				$usernewfile	= $useruploadpath.$wcs_newfilename;
				
				
				$oldumask = umask(0);
				
				if ($dir = @opendir($useruploadpath)) {
					if(@copy($userftppath.$file, $usernewfile)) {
						
						@unlink($userftppath.$file);
						
						// store tags
						_dbSaveCategories($ftp["tags"], 'file', $new_fileId, ',');
						
					} else {
						$file_error["upload"] = "Error while writing file to storage (1).";
					}
				}
			}
			
			if(empty($file_error["upload"])) {
			
				// now try to find 1st file having same named and replace it if related mark is set
				if($ftp["replace"]) {
			
					$rsql  = "SELECT * FROM ".DB_PREPEND."phpwcms_file WHERE ";
					$rsql .= "f_name='".aporeplace($file_name)."' AND f_kid=1 ";
					$rsql .= "AND f_pid=".$ftp["dir"]." AND f_trash=0 AND f_id != ".$new_fileId." LIMIT 1";
					if($rresult = mysql_query($rsql, $db)) {
					
						if($rrow = mysql_fetch_assoc($rresult)) {
						
							$oldFileID      = $rrow['f_id'];
							$oldFileHash    = $rrow['f_hash'];
							$oldFileNewHash = md5( $file_name . microtime() . time() );
						
							// now update new file by old file information of same named
							$nsql  = "UPDATE ".DB_PREPEND."phpwcms_file SET ";
							$nsql .= "f_refid=".$oldFileID.", f_trash=5, f_size=".$rrow['f_size'].', ';
							$nsql .= "f_type='".$rrow['f_type']."', f_changed=".now().', ';
							$nsql .= "f_hash='".aporeplace($oldFileNewHash)."' WHERE f_id=".$new_fileId;
							
							if(mysql_query($nsql, $db)) {
								
								// yepp both files are updated in db
								// now change hash of file storage files
								rename($useruploadpath.$oldFileHash.$_file_extension, $useruploadpath.$oldFileNewHash.$_file_extension);
								rename($usernewfile, $useruploadpath.$oldFileHash.$_file_extension);
								
								// update file size of old file with new filesize
								_dbUpdate('phpwcms_file', array('f_type'=>$file_type, 'f_size'=>$file_size, 'f_changed'=>now()), 'f_id='.$oldFileID);
								
								// empty temp images directory
								$thumbnails = returnFileListAsArray(PHPWCMS_THUMB, 'jpg,jpeg,gif,png');
								if(is_array($thumbnails) && count($thumbnails)) {
									
									foreach($thumbnails as $thumbnail) {
									
										@unlink(PHPWCMS_THUMB.$thumbnail['filename']);
									
									}
								}
									
							}
						}
						mysql_free_result($rresult);				
					
					}
			
				}
				
				flush();
				echo $file." [OK!]<br />";
			} else {
				echo $file." (".$file_error["upload"].")<br />";
				mysql_query("DELETE FROM ".DB_PREPEND."phpwcms_file WHERE f_id=".$new_fileId." AND f_uid=".$_SESSION["wcs_user_id"], $db);
			}
			
		} else {
			echo $file." not exists<br />";
		}
		flush();
	}
echo "</p>\n";
}

if(empty($file_error["upload"]) && empty($ftp["error"])) {
	echo "<p class=\"title\"><strong>every selected file was taken over</strong></p>\n";
	echo "<p class='v10'><a href=\"".$ref."\" style=\"font-weight: bold;\">click here to go back</a> (if no automatic redirect)</p>\n";
	echo "<script type=\"text/javascript\"> window.location.href = \"".$ref."\"; </script>\n";
	
} else {
	echo "<p class=\"error\"><strong>error while file take over</strong></p>\n";
	echo "<p class='v10'><a href=\"".$ref."\" style=\"font-weight: bold;\">click here to go back</a></p>\n";
	echo "<script type=\"text/javascript\"> history.back(); </script>\n";
}
echo "</body>\n</html>\n";


if(isset($oldumask)) {
	umask($oldumask);
}

?>