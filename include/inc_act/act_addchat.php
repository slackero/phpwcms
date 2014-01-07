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

$chat_message = clean_slweg(trim($_POST['chatmsg']));
$chatlist = intval($_POST['chatlist']);
if($chat_message) {
	$sql =	"INSERT INTO ".DB_PREPEND."phpwcms_chat ".
			"(chat_uid, chat_name, chat_text, chat_cat) VALUES (".
			$_SESSION['wcs_user_id'].",'".
			$_SESSION['wcs_user']."','".
			aporeplace($chat_message)."',".
			"0)";
	mysql_query($sql, $db);
}

headerRedirect(PHPWCMS_URL."phpwcms.php?do=chat&p=1&l=".$chatlist);

?>