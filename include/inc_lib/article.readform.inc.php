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


// ----------------------------------------------------------------
// obligate check for phpwcms constants
if (!defined('PHPWCMS_ROOT')) {
   die("You Cannot Access This Script Directly, Have a Nice Day.");
}
// ----------------------------------------------------------------


// read content type form vars
if($content["aid"] != intval($_POST["caid"])) {
	die("error: wrong form data!");
}

$content["title"] 			= clean_slweg($_POST["ctitle"]);
$content["subtitle"] 		= clean_slweg($_POST["csubtitle"]);
$content["comment"] 		= slweg($_POST["ccomment"]);
$content["paginate_title"]	= clean_slweg($_POST["cpaginate_title"]);
$content["paginate_page"]	= empty($_POST["cpaginate_page"]) ? 0 : intval($_POST["cpaginate_page"]);
$content["visible"] 		= empty($_POST["cvisible"]) ? 0 : 1;
$content["before"] 			= intval($_POST["cbefore"]);
$content["after"] 			= intval($_POST["cafter"]);
$content["top"] 			= isset($_POST["ctop"]) ? 1 : 0;
$content["anchor"] 			= isset($_POST["canchor"]) ? 1 : 0;
$content["id"] 				= intval($_POST["cid"]);
$content["granted"] 		= empty($_POST["cgranted"]) ? 0 : 1;
$content["tid"] 			= empty($_POST["ctid"]) || !in_array(intval($_POST["ctid"]), array(0, 1, 2, 3)) ? 0 : intval($_POST["ctid"]);

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

if(!$content["before"] || $content["before"] > 9999) {
	$content["before"] = '';
}
if(!$content["after"] || $content["after"] > 9999) {
	$content["after"] = '';
}

if(isset($_POST["target_ctype"])) {

	$content["target_type"]	= explode(':', $_POST["target_ctype"]);
	$content["module"]		= empty($content["target_type"][1]) ? '' : trim($content["target_type"][1]);
	$content["target_type"]	= intval($content["target_type"][0]);

} else {

	$content["target_type"]	= 0;
	$content["module"]	= '';

}

$content["sorting"]	= intval($_POST["csorting"]);
$content["block"]	= clean_slweg($_POST["cblock"]);
// reset paginate page number to 0 > pagination support for CONTENT block only
if($content["paginate_page"] && $content["block"] != 'CONTENT') {
	$content["paginate_page"] = 0;
}

$content["tab"]			= '';
$content['tab_type']	= empty($_POST['ctab']) ? 0 : intval($_POST['ctab']);
if($content['tab_type']) {

	$content["tab_number"]	= empty($_POST['ctab_number']) ? 0 : intval($_POST['ctab_number']);
	$content["tab_title"]	= empty($_POST['ctab_title']) ? '' : clean_slweg($_POST['ctab_title'], 100);

	if($content["tab_number"] || $content["tab_title"]) {

		$content["tab"] = $content["tab_number"] . '|' . $content['tab_type'] . '_' . $content["tab_title"];

	}
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