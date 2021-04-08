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
require_once '../../include/config/conf.inc.php';
require_once '../inc_lib/default.inc.php';
require_once CMSGO_ROOT.'/include/inc_lib/helper.session.php';
require_once CMSGO_ROOT.'/include/inc_lib/dbcon.inc.php';
require_once CMSGO_ROOT.'/include/inc_lib/general.inc.php';
checkLogin();
validate_csrf_tokens();
require_once CMSGO_ROOT.'/include/inc_lib/backend.functions.inc.php';

$chat_message = clean_slweg(trim($_POST['chatmsg']));
$chatlist = intval($_POST['chatlist']);
if($chat_message) {
	$sql =	"INSERT INTO ".DB_PREPEND."cmsgo_chat (chat_uid, chat_name, chat_text, chat_cat) ";
	$sql .= "VALUES (".$_SESSION['wcs_user_id'].","._dbEscape($_SESSION['wcs_user']).","._dbEscape($chat_message).",0)";
	_dbQuery($sql, 'INSERT');
}

headerRedirect(CMSGO_URL.'cmsgo.php?'.get_token_get_string().'&do=chat&p=1&l='.$chatlist.'&'.get_token_get_string());
