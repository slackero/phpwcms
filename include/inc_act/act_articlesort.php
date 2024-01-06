<?php
/**
 * cmsGO!
 *
 * @author Pixels & Points GmbH <info@pixels-points.ch>
 * @copyright Copyright (c) 2002-2024, Pixels & Points GmbH
 * @license https://www.pixels-points.ch/cmsgo-license.html Pixels & Points cmsGO! license
 *
 **/

$cmsgo = array('SESSION_START' => true);
require_once '../config/conf.inc.php';
require_once '../inc_lib/default.inc.php';
require_once CMSGO_ROOT.'/include/inc_lib/helper.session.php';
require_once CMSGO_ROOT.'/include/inc_lib/dbcon.inc.php';
require_once CMSGO_ROOT.'/include/inc_lib/general.inc.php';
checkLogin();
validate_csrf_tokens();
require_once CMSGO_ROOT.'/include/inc_lib/backend.functions.inc.php';

if(isset($_GET["sortid"])) {
	$values = explode("|", $_GET["sortid"]);
	$sorti = 10;
	for ($i = 0; $i < count($values); $i++) {
        $acontent_id = intval($values[$i]);
		if ($acontent_id){
			$sql = "UPDATE ".DB_PREPEND."cmsgo_articlecontent SET acontent_sorting=" . $sorti . " WHERE (acontent_uid=" . $_SESSION["wcs_user_id"] . " OR " . $_SESSION["wcs_user_admin"] . ") AND acontent_id=" . $acontent_id;
			_dbQuery($sql, 'UPDATE');
		}
		$sorti += 10;
	}
}

update_cache();
