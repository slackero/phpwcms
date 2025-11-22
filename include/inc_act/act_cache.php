<?php
/**
 * phpwcms content management system
 *
 * @author Oliver Georgi <og@phpwcms.org>
 * @copyright Copyright (c) 2002-2025, Oliver Georgi
 * @license http://opensource.org/licenses/GPL-2.0 GNU GPL-2
 * @link http://www.phpwcms.org
 *
 **/

$phpwcms = array('SESSION_START' => true);
$base_dir = dirname(__DIR__, 2);
require_once $base_dir . '/include/config/conf.inc.php';
require_once $base_dir . '/include/inc_lib/default.inc.php';
require_once PHPWCMS_ROOT.'/include/inc_lib/helper.session.php';
require_once PHPWCMS_ROOT.'/include/inc_lib/dbcon.inc.php';
require_once PHPWCMS_ROOT.'/include/inc_lib/general.inc.php';
checkLogin();
validate_csrf_tokens();
require_once PHPWCMS_ROOT.'/include/inc_lib/backend.functions.inc.php';

if(!empty($_SESSION["wcs_user_admin"])) { //Wenn Benutzer Admin-Rechte hat

    if(isset($_GET['do']) && intval($_GET['do']) === 9) {

        $sql = "TRUNCATE TABLE ".DB_PREPEND."phpwcms_cache";
        _dbQuery($sql, 'TRUNCATE');

    } else {

        update_cache();

    }

}

$ref = empty($_SESSION['REFERER_URL']) ? PHPWCMS_URL.'phpwcms.php?'.get_token_get_string() : $_SESSION['REFERER_URL'];
headerRedirect($ref);
