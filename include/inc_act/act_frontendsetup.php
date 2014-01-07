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

	list($do, $id) = explode('|', $_GET['do']);
	$do = intval($do);
	$id = intval($id);
	
	if($id) {
	
		switch($do) {
	
			case 1:	// delete pagelayout
					mysql_query("UPDATE ".DB_PREPEND."phpwcms_pagelayout SET pagelayout_default=0, ".
								"pagelayout_trash=9 WHERE pagelayout_id=".$id, $db);
					break;
				
			case 2:	// delete template
					mysql_query("UPDATE ".DB_PREPEND."phpwcms_template SET template_default=0, ".
								"template_trash=9 WHERE template_id=".$id, $db);
					break;	
	
		}
		
	}

	
} //End action

headerRedirect($_SESSION['REFERER_URL']);

?>