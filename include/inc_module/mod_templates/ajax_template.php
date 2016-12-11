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

// general wrapper for ajax based queries
//pwmod: new File
session_start();
$phpwcms = array();
require_once ('../../../include/config/conf.inc.php');
require('../../inc_lib/default.inc.php');
require_once PHPWCMS_ROOT.'/include/inc_lib/helper.session.php';
require_once PHPWCMS_ROOT.'/include/inc_lib/dbcon.inc.php';
require_once (PHPWCMS_ROOT.'/include/inc_lib/general.inc.php');
require_once (PHPWCMS_ROOT.'/include/inc_lib/backend.functions.inc.php');
require_once (PHPWCMS_ROOT.'/include/inc_lang/backend/en/lang.inc.php');
if($_SESSION["wcs_user_lang_custom"]) { //use custom lang if available -> was set in login.php
	include(PHPWCMS_ROOT.'/include/inc_lang/backend/'.substr($_SESSION["wcs_user_lang"],0,2).'/lang.inc.php');
}

if(empty($_SESSION["wcs_user"])) {
	die('Sorry, access forbidden');
}
//get variables
$action = isset($_REQUEST['action']) ? $_REQUEST['action'] : '';
$ctntemplate = isset($_REQUEST['template']) ? $_REQUEST['template'] : '';
$ctnid = isset($_REQUEST['id']) ? $_REQUEST['id'] : '';
//echo readfile ($ctntemplate);

if ($action == 'form') {
  $frontend_css = read_textfile($ctntemplate);
  $frontend_css = ($frontend_css) ? html($frontend_css) : "";
  echo $frontend_css;
} elseif ($action == 'list') {
  echo '<h2>Sites containing this template:</h2><p class="tmpl_menu">';
  $sql =  "SELECT DISTINCT phpwcms_article.article_title, phpwcms_article.article_id, phpwcms_articlecontent.acontent_id FROM ".DB_PREPEND."phpwcms_articlecontent ";
  $sql .= "INNER JOIN " . DB_PREPEND . "phpwcms_article ON ";
  $sql .= DB_PREPEND . "phpwcms_article.article_id = " . DB_PREPEND . "phpwcms_articlecontent.acontent_aid ";
  $sql .= "WHERE acontent_type="._dbEscape($ctnid)." AND acontent_trash=0 AND article_deleted = 0 AND ";
  if ($ctnid == '8') {
    $sql .= " acontent_form like '%".$ctntemplate."%'";
  } else {
    $sql .= " acontent_template ="._dbEscape($ctntemplate);
  }
  

  if($result = mysql_query($sql, $db) or die("error")) {
  	while($crow = mysql_fetch_row($result)) {
		echo '<a href="phpwcms.php?'.get_token_get_string('csrftoken').'&do=articles&p=2&s=1&aktion=2&id='.$crow[1].'&acid='.$crow[2].'" target=_blank>'.$crow[0].' <img border="0" alt="" src="img/button/edit_22x13.gif"></a><br>';
  	}
  }
  echo '</p>';
}
?>


