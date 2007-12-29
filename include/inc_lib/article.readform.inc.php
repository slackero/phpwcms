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


// ----------------------------------------------------------------
// obligate check for phpwcms constants
if (!defined('PHPWCMS_ROOT')) {
   die("You Cannot Access This Script Directly, Have a Nice Day.");
}
// ----------------------------------------------------------------


// read content type form vars
if($content["aid"] != intval($_POST["caid"])) die("error: wrong form data!");


$content["title"] 			= clean_slweg($_POST["ctitle"]);
$content["subtitle"] 		= clean_slweg($_POST["csubtitle"]);
$content["comment"] 		= slweg($_POST["ccomment"]);
$content["paginate_title"]	= clean_slweg($_POST["cpaginate_title"]);
$content["paginate_page"]	= empty($_POST["cpaginate_page"]) ? 0 : intval($_POST["cpaginate_page"]);
$content["visible"] 		= empty($_POST["cvisible"]) ? 0 : intval($_POST["cvisible"]);
$content["before"] 			= intval($_POST["cbefore"]);
$content["after"] 			= intval($_POST["cafter"]);
$content["top"] 			= isset($_POST["ctop"]) ? 1 : 0;
$content["anchor"] 			= isset($_POST["canchor"]) ? 1 : 0;
$content["id"] 				= intval($_POST["cid"]);

if(!empty($_POST['ctype_change_aid'])) {

	$ctype_change_aid	= intval($_POST['ctype_change_aid']);
	
	if($ctype_change_aid && $ctype_change_aid != $content["aid"]) {
	
		$ctype_change_aid	= _dbQuery('SELECT article_id FROM '.DB_PREPEND.'phpwcms_article WHERE article_id='.$ctype_change_aid.' AND article_deleted=0');
		if(!empty($ctype_change_aid[0]['article_id'])) {
			$content["aid"] = $ctype_change_aid[0]['article_id'];
			$ctype_change_aid = 'DO_CHANGE';
		}
	}
}


if(!$content["before"] || $content["before"] > 9999) $content["before"] = '';
if(!$content["after"] || $content["after"] > 9999) $content["after"] = '';


if(isset($_POST["target_ctype"])) {
				
	$content["target_type"]	= explode(':', $_POST["target_ctype"]);
	$content["module"]		= empty($content["target_type"][1]) ? '' : trim($content["target_type"][1]);
	$content["target_type"]	= intval($content["target_type"][0]);
				
} else {
			
	$content["target_type"]	= 0;
	$content["module"]	= '';
			
}

$content["sorting"] 	= intval($_POST["csorting"]);

$content["block"]	 	= clean_slweg($_POST["cblock"]);
// reset paginate page number to 0 > pagination support for CONTENT block only
if($content["paginate_page"] && $content["block"] != 'CONTENT') {
	$content["paginate_page"] = 0;
}

$content["module"]	 	= empty($_POST["ctype_module"]) ? '' : clean_slweg($_POST["ctype_module"]);

// check if content type possibly changed
$content["update_type"] = ($content["target_type"] != $content["type"]) ? 1 : 0; 

// read form vars for special content parts

if($content["type"] != 30 && file_exists(PHPWCMS_ROOT."/include/inc_lib/content/cnt".$content["type"].".readform.inc.php")) {
	$content["module"]	= '';
	include_once(PHPWCMS_ROOT."/include/inc_lib/content/cnt".$content["type"].".readform.inc.php");
	
} elseif($content["type"] == 30 && file_exists($phpwcms['modules'][$content['module']]['path'].'inc/cnt.post.php')) {

	include_once($phpwcms['modules'][$content['module']]['path'].'inc/cnt.post.php');

} else {
	$content["module"]	= '';
	include_once(PHPWCMS_ROOT."/include/inc_lib/content/cnt0.readform.inc.php");

}

?>