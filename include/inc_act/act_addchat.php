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

$phpwcms = array('SESSION_START' => true);
require_once '../../include/config/conf.inc.php';
require_once '../inc_lib/default.inc.php';
require_once PHPWCMS_ROOT.'/include/inc_lib/helper.session.php';
require_once PHPWCMS_ROOT.'/include/inc_lib/dbcon.inc.php';
require_once PHPWCMS_ROOT.'/include/inc_lib/general.inc.php';
checkLogin();
validate_csrf_tokens();
require_once PHPWCMS_ROOT.'/include/inc_lib/backend.functions.inc.php';

$chat_message = clean_slweg(trim($_POST['chatmsg']));
$chatlist = intval($_POST['chatlist']);
if($chat_message) {
	$sql =	"INSERT INTO ".DB_PREPEND."phpwcms_chat (chat_uid, chat_name, chat_text, chat_cat) ";
	$sql .= "VALUES (".$_SESSION['wcs_user_id'].","._dbEscape($_SESSION['wcs_user']).","._dbEscape($chat_message).",0)";
	_dbQuery($sql, 'INSERT');
}

headerRedirect(PHPWCMS_URL.'phpwcms.php?'.get_token_get_string().'&do=chat&p=1&l='.$chatlist);
