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

$ref = $_SESSION['REFERER_URL'];
$phpwcms = array();
require_once ('../../config/phpwcms/conf.inc.php');
require_once ('../inc_lib/default.inc.php');
require_once (PHPWCMS_ROOT.'/include/inc_lib/dbcon.inc.php');
require_once (PHPWCMS_ROOT.'/include/inc_lib/general.inc.php');
checkLogin();
require_once (PHPWCMS_ROOT.'/include/inc_lib/backend.functions.inc.php');

if(isset($_GET["do"])) {
	$values = explode(",", $_GET["do"]);
	if(count($values)) {
		switch(intval($values[0])) {
			case 9: //delete article content part
					$sql = "UPDATE ".DB_PREPEND."phpwcms_articlecontent SET acontent_trash=9".
						   " WHERE (acontent_uid=".$_SESSION["wcs_user_id"]." OR ".$_SESSION["wcs_user_admin"].")".
						   " AND acontent_aid=".intval($values[1]).
						   " AND acontent_id=".intval($values[2]).";";
					mysql_query($sql, $db) or die("error while deleting content part");
					break;
			case 1: //delete article
					$sql = "UPDATE ".DB_PREPEND."phpwcms_article SET article_deleted=9, article_alias=CONCAT(article_alias,'_del-','".date('YmdHis')."')".
						   " WHERE (article_uid=".$_SESSION["wcs_user_id"]." OR ".$_SESSION["wcs_user_admin"].")".
						   " AND article_id=".intval($values[1]).";";
					mysql_query($sql, $db) or die("error while deleting article");
					$ref .= '&p=&s=&id=';
					break;
			case 2: //make content visible/invisible
					$sql = "UPDATE ".DB_PREPEND."phpwcms_articlecontent SET acontent_visible=".intval($values[3]).
						   " WHERE (acontent_uid=".$_SESSION["wcs_user_id"]." OR ".$_SESSION["wcs_user_admin"].")".
						   " AND acontent_aid=".intval($values[1]).
						   " AND acontent_id=".intval($values[2]).";";
					mysql_query($sql, $db) or die("error while changing content visible/invisible");
					break;
			case 3: //make article visible/invisible
					$sql = "UPDATE ".DB_PREPEND."phpwcms_article SET article_aktiv=".intval($values[3]).
						   " WHERE article_id=".intval($values[1]).";";
					mysql_query($sql, $db) or die("error while changing article visible/invisible");
					break;
			case 4: //make article public/nonpublic
					$sql = "UPDATE ".DB_PREPEND."phpwcms_article SET article_public=".intval($values[3]).
						   " WHERE article_id=".intval($values[1]).";";
					mysql_query($sql, $db) or die("error while changing article visible/invisible");
					break;
		}
	}
}

if(isset($_GET["sort"])) {
	list($value1, $value2)	= explode("|", $_GET["sort"]);
	list($id1, $sort1) = explode(":", $value1); list($id2, $sort2) = explode(":", $value2);
	$id1 = intval($id1); $id2 = intval($id2); $sort1 = intval($sort1); $sort2	= intval($sort2);
	
	$sql1 = "UPDATE ".DB_PREPEND."phpwcms_articlecontent SET acontent_sorting=".$sort1.
		    " WHERE (acontent_uid=".$_SESSION["wcs_user_id"]." OR ".$_SESSION["wcs_user_admin"].")".
		    " AND acontent_id=".$id1.";";
	$sql2 = "UPDATE ".DB_PREPEND."phpwcms_articlecontent SET acontent_sorting=".$sort2.
		    " WHERE (acontent_uid=".$_SESSION["wcs_user_id"]." OR ".$_SESSION["wcs_user_admin"].")".
		    " AND acontent_id=".$id2.";";
	mysql_query($sql1, $db) or die("error while changing content part's sorting");
	mysql_query($sql2, $db) or die("error while changing content part's sorting");
}

update_cache();
headerRedirect($ref);

?>