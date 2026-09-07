<?php
/**
 * phpwcms
 *
 * @author Oliver Georgi <og@phpwcms.org>
 * @copyright Copyright (c) 2002-2026, Oliver Georgi
 * @license http://opensource.org/licenses/GPL-2.0 GNU GPL-2
 *
 **/

$phpwcms = ['SESSION_START' => true];

require_once '../config/conf.inc.php';
require_once '../inc_lib/default.inc.php';
require_once PHPWCMS_ROOT . '/include/inc_lib/helper.session.php';
require_once PHPWCMS_ROOT . '/include/inc_lib/dbcon.inc.php';
require_once PHPWCMS_ROOT . '/include/inc_lib/general.inc.php';
checkLogin();
validate_csrf_tokens();
require_once PHPWCMS_ROOT . '/include/inc_lib/backend.functions.inc.php';

if (has_admin_permission('admugroup')) {
    if (isset($_GET['del'])) {
        $gi = explode(':', clean_slweg($_GET['del']));
        $gi = (int)$gi[0];
        if ($gi) {
            _dbUpdate('usergroup', ['group_active' => 9], 'group_id=' . $gi);
        }
    }

    if (isset($_GET['aktiv'])) {
        $sql = 'UPDATE ' . DB_PREPEND . 'usergroup SET group_active= (CASE group_active WHEN 1 THEN 0 ELSE 1 END) WHERE group_id=' . (int)$_GET['aktiv'];
        _dbQuery($sql, 'UPDATE');
    }
}

$ref = empty($_SESSION['REFERER_URL']) ? PHPWCMS_URL . 'phpwcms.php?' . get_token_get_string() : $_SESSION['REFERER_URL'];

headerRedirect($ref);
