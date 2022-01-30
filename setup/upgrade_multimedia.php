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

//used to convert old style file uploads

$phpwcms = array();

require_once '../include/config/conf.inc.php';
require_once '../include/inc_lib/default.inc.php';
require_once PHPWCMS_ROOT.'/include/inc_lib/dbcon.inc.php';
require_once PHPWCMS_ROOT.'/include/inc_lib/general.inc.php';

echo '<html><body><pre>';

echo 'CONVERT CONTENT PART MULTIMEDIA' . LF;
echo '=================================================================' . LF.LF;

$pagelayout = _dbQuery("SELECT * FROM ".DB_PREPEND."phpwcms_articlecontent WHERE acontent_type=9 and acontent_media != ''");

$c = 1;

foreach($pagelayout as $var) {

	$media = array();

	$media["media"] 		= explode(":", $var["acontent_media"]);

	$media["media_backup"]	= $var["acontent_media"];

	$media["media_type"]	= intval($media["media"][0]);
	$media["media_player"]	= intval($media["media"][1]);
	$media["media_src"]		= intval($media["media"][5]);
	$media["media_pos"]		= intval($media["media"][2]);
	$media["media_width"]	= intval($media["media"][3]);
	$media["media_height"]	= intval($media["media"][4]);
	$media["media_auto"]	= (intval($media["media"][8])) ? 1 : 0;
	$media["media_transparent"]	= isset($media["media"][9]) ? intval($media["media"][9]) : 0;
	$media["media_control"]	= (intval($media["media"][7])) ? 1 : 0;

	if($media["media_src"]) {
		// remote Source
		$media["media_extern"]	= base64_decode($media["media"][6]);
		$media["media_id"]		= 0;
		$media["media_name"]	= '';

	} else {
		//internal source
		list($media["media_id"], $media["media_name"]) = explode(':', base64_decode($media["media"][6]));
		$media["media_id"]		= intval($media["media_id"]);
		$media["media_name"]	= trim($media["media_name"]);
		$media["media_extern"]	= '';
	}

	unset($media["media"]);

	$sql  = "UPDATE ".DB_PREPEND."phpwcms_articlecontent SET ";
	$sql .=	"acontent_form='".aporeplace(serialize($media))."', acontent_media='', ";
	$sql .= "acontent_created='".$var["acontent_created"]."', acontent_tstamp='".$var["acontent_tstamp"]."' ";
	$sql .= "WHERE acontent_id = ".$var["acontent_id"];
	$upgrade = _dbQuery($sql, 'UPDATE');

	echo sprintf('%05d: ', $c).' CP-ID: '.$var['acontent_id'].LF;
	flush();
	$c++;
}

if($c==1) {
	echo 'No content part multimedia found for upgrading!';
}

echo '</pre></body></html>';
