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

if(!empty($_SESSION["wcs_user_admin"])) { // With admin permissions only

    // Delete user account
    if(isset($_GET["del"])) {
        $ui = explode(":", clean_slweg($_GET["del"]));
        $user_id = intval($ui[0]);
        $user_email = empty($ui[1]) ? '' : $ui[1];
        if($user_id && $user_id !== intval($_SESSION["wcs_user_id"]) && is_valid_email($user_email)) {
            $result = _dbQuery("UPDATE ".DB_PREPEND."phpwcms_user SET usr_aktiv=9 WHERE usr_id=".$user_id." AND usr_email="._dbEscape($user_email), 'UPDATE');
            if(!empty($result['AFFECTED_ROWS'])) {
                $host = parse_url($phpwcms["site"], PHP_URL_HOST);
                @mail(
                    $user_email,
                    'Your account on '.$host.' was deactivated',
                    "Dear user,\n\nYour account to phpwcms was deactivated!\n\nContact the admin if you have any question.\n\nSee you on ".$phpwcms["site"].'.',
                    "From: ".$phpwcms["admin_email"]."\nReply-To: ".$phpwcms["admin_email"]."\n"
                );
            }
        }
    }

    if(isset($_GET["aktiv"])) {
        $ui = explode(":", clean_slweg($_GET["aktiv"]));
        $user_id = intval($ui[0]);
        $user_aktiv = empty($ui[1]) ? 0 : 1;
        if($user_id && $user_id !== intval($_SESSION["wcs_user_id"])) {
            _dbQuery($sql =  "UPDATE ".DB_PREPEND."phpwcms_user SET usr_aktiv=".$user_aktiv." WHERE usr_aktiv != 9 AND usr_id=".$user_id, 'UPDATE');
        }
    }

}

headerRedirect(PHPWCMS_URL.'phpwcms.php?'.get_token_get_string().'&do=admin');
