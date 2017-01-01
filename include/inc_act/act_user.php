<?php
/**
 * phpwcms content management system
 *
 * @author Oliver Georgi <og@phpwcms.org>
 * @copyright Copyright (c) 2002-2017, Oliver Georgi
 * @license http://opensource.org/licenses/GPL-2.0 GNU GPL-2
 * @link http://www.phpwcms.org
 *
 **/

session_start();
$phpwcms = array();

require_once '../../include/config/conf.inc.php';
require_once '../inc_lib/default.inc.php';
require_once PHPWCMS_ROOT.'/include/inc_lib/helper.session.php';
require_once PHPWCMS_ROOT.'/include/inc_lib/dbcon.inc.php';
require_once PHPWCMS_ROOT.'/include/inc_lib/general.inc.php';
checkLogin();
validate_csrf_tokens();
require_once PHPWCMS_ROOT.'/include/inc_lib/backend.functions.inc.php';

if(!empty($_SESSION["wcs_user_admin"])) { // With admin permissions only

    // Delete user account
    if(isset($_GET["del"])) {
        $ui = explode(":", clean_slweg($_GET["del"]));
        $user_id = intval($ui[0]);
        $user_email = '';
        if(isset($ui[1])) {
            $user_email = $ui[1];
        }
        if($user_id <> $_SESSION["wcs_user_id"]) {
            $sql =  "UPDATE ".DB_PREPEND."phpwcms_user SET ".
                    "usr_login='".generic_string(10)."', ".
                    "usr_pass='".md5(generic_string(10))."', ".
                    "usr_email='', ".
                    "usr_admin=0, ".
                    "usr_aktiv=9 ".
                    "WHERE usr_id=".$user_id." AND ".
                    "usr_email="._dbEscape($user_email);
            $result = _dbQuery($sql, 'UPDATE');
            if(!empty($result['AFFECTED_ROWS']) && is_valid_email($user_email)) {
                @mail($user_email, "your account", "YOUR PHPWCMS ACCOUNT WAS DELETED\n \ncontact the admin if you have any question.\n\nSee you at ".$phpwcms["site"], "From: ".$phpwcms["admin_email"]."\nReply-To: ".$phpwcms["admin_email"]."\n");
            }
        }
    }

    if(isset($_GET["aktiv"])) {
        $ui = explode(":", clean_slweg($_GET["aktiv"]));
        $user_id = intval($ui[0]);
        $user_aktiv = !empty($ui[1]) ? 1 : 0;
        if($user_id <> $_SESSION["wcs_user_id"]) {
            _dbQuery($sql =  "UPDATE ".DB_PREPEND."phpwcms_user SET usr_aktiv=".$user_aktiv." WHERE usr_id=".$user_id, 'UPDATE');
        }
    }

}

headerRedirect(PHPWCMS_URL.'phpwcms.php?'.get_token_get_string('csrftoken').'&do=admin');
