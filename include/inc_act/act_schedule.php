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

if(empty($_SESSION['REFERER_URL'])) {
    die('Goood bye.');
} else {
    $ref = empty($_SESSION['REFERER_URL']) ? PHPWCMS_URL.'phpwcms.php?'.get_token_get_string() : $_SESSION['REFERER_URL'];
}

if($_SESSION["wcs_user_admin"] == 1 && trim($_POST["scat_name"])) {

    if(intval($_POST["scat_new"]) === 1 && intval($_POST["scat_id"]) === 0 ) {

        $sql =  "INSERT INTO ".DB_PREPEND."phpwcms_schedulecat (".
                "scat_name, scat_info, scat_aktiv, scat_uid) ".
                "VALUES ('".
                getpostvar($_POST["scat_name"])."','".
                getpostvar($_POST["scat_info"])."',".
                intval($_POST["scat_aktiv"]).",".
                $_SESSION["wcs_user_id"].")";

        $result = _dbQuery($sql, 'INSERT');
        if(isset($result['INSERT_ID'])) {
            $ref .= "&cat=".$result['INSERT_ID'];
        }

    } elseif(!empty($_POST["scat_new"]) && intval($_POST["scat_id"])) {

        $sql =  "UPDATE ".DB_PREPEND."phpwcms_schedulecat SET ".
                "scat_name='".getpostvar($_POST["scat_name"])."', ".
                "scat_info='".getpostvar($_POST["scat_info"])."', ".
                "scat_aktiv=".intval($_POST["scat_aktiv"]).", ".
                "scat_uid=".$_SESSION["wcs_user_id"].
                " WHERE scat_id=".intval($_POST["scat_id"]);
        _dbQuery($sql, 'UPDATE');

    }

}

headerRedirect($ref);
