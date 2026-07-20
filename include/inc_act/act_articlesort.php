<?php
/**
 * phpwcms
 *
 * @author Oliver Georgi <og@phpwcms.org>
 * @copyright Copyright (c) 2002-2026, Oliver Georgi
 * @license http://opensource.org/licenses/GPL-2.0 GNU GPL-2
 *
 **/

$phpwcms = array('SESSION_START' => true);
require_once '../config/conf.inc.php';
require_once '../inc_lib/default.inc.php';
require_once PHPWCMS_ROOT.'/include/inc_lib/helper.session.php';
require_once PHPWCMS_ROOT.'/include/inc_lib/dbcon.inc.php';
require_once PHPWCMS_ROOT.'/include/inc_lib/general.inc.php';
checkLogin();
validate_csrf_tokens();
require_once PHPWCMS_ROOT.'/include/inc_lib/backend.functions.inc.php';

if(isset($_GET["sortid"])) {
	$values = explode("|", $_GET["sortid"]);
	$sorti = 10;
	for ($i = 0, $count = count($values); $i < $count; $i++) {
        $acontent_id = intval($values[$i]);
		if ($acontent_id){
			$sql = "UPDATE ".DB_PREPEND."phpwcms_articlecontent SET acontent_sorting=" . $sorti . " WHERE (acontent_uid=" . $_SESSION["wcs_user_id"] . " OR " . $_SESSION["wcs_user_admin"] . ") AND acontent_id=" . $acontent_id;
			_dbQuery($sql, 'UPDATE');
		}
		$sorti += 10;
	}
}

update_cache();
