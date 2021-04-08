<?php
/**
 * cmsGO!
 *
 * @author Pixels & Points GmbH <info@pixels-points.ch>
 * @copyright Copyright (c) 2002-2021, Pixels & Points GmbH
 * @license https://www.pixels-points.ch/cmsgo-license.html Pixels & Points cmsGO! license
 *
 **/

session_start();
$cmsgo = array();
require '../../include/config/conf.inc.php';
require '../inc_lib/default.inc.php';
require_once CMSGO_ROOT.'/include/inc_lib/helper.session.php';
require CMSGO_ROOT.'/include/inc_lib/dbcon.inc.php';
require CMSGO_ROOT.'/include/inc_lib/general.inc.php';
require CMSGO_ROOT.'/include/inc_lib/backend.functions.inc.php';

if(empty($_SESSION["wcs_user"])) {
	headerRedirect('', 401);
	die('Sorry, access forbidden');
}

if(isset($_GET["sortid"])) {
	$values = explode("|", $_GET["sortid"]);
	$sorti = 10;
	for( $i = 0; $i < count( $values ); $i++ ) {
		if (intval($values[$i]) > 0){
			$sql = "UPDATE ".DB_PREPEND."cmsgo_articlecontent SET acontent_sorting=".$sorti.
				" WHERE (acontent_uid=".$_SESSION["wcs_user_id"]." OR ".$_SESSION["wcs_user_admin"].")".
				" AND acontent_id=".$values[$i].";";
			_dbQuery($sql, 'UPDATE');
		}
		$sorti = $sorti+10;
	}
}

update_cache();
