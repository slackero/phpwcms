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


session_start();
$phpwcms = array();

require_once ('../../config/phpwcms/conf.inc.php');
require_once ('../inc_lib/default.inc.php');
require_once (PHPWCMS_ROOT.'/include/inc_lib/dbcon.inc.php');

require_once (PHPWCMS_ROOT.'/include/inc_lib/general.inc.php');
checkLogin();
require_once (PHPWCMS_ROOT.'/include/inc_lib/backend.functions.inc.php');

if($_SESSION["wcs_user_admin"] == 1) { //If user has admin rights

	// enym
	if(isset($_GET["del"])) {
		$gi = explode(":", clean_slweg($_GET["del"]));
		$gi = intval($gi[0]);
		if($gi) {
			_dbUpdate('phpwcms_usergroup', array('group_active' => 9), 'group_id='.$gi);
		}
	}
	
	if(isset($_GET["aktiv"])) {
		$gi		= explode(":", clean_slweg($_GET["aktiv"]));
		$gi[0]	= intval($gi[0]);
		$gi[1]	= empty($gi[1]) ? 0 : 1;
		if($gi[0]) {
			_dbUpdate('phpwcms_usergroup', array('group_active' => $gi[1]), 'group_id='.$gi[0]);
		}
	}

	
} //End action

headerRedirect($_SESSION['REFERER_URL']);


?>