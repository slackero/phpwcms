<?php
/**
 * phpwcms
 *
 * @author Oliver Georgi <og@phpwcms.org>
 * @copyright Copyright (c) 2002-2026, Oliver Georgi
 * @license http://opensource.org/licenses/GPL-2.0 GNU GPL-2
 *
 **/

$phpwcms = array('SESSION_START' => true);
require_once '../config/conf.inc.php';
require_once '../inc_lib/default.inc.php';
require_once PHPWCMS_ROOT.'/include/inc_lib/helper.session.php';
require_once PHPWCMS_ROOT.'/include/inc_lib/dbcon.inc.php';
require_once PHPWCMS_ROOT.'/include/inc_lib/general.inc.php';
checkLogin();
validate_csrf_tokens();
require_once PHPWCMS_ROOT.'/include/inc_lib/backend.functions.inc.php';

$sort_data = isset($_REQUEST['sortid']) ? $_REQUEST['sortid'] : '';

if (is_string($sort_data) && $sort_data !== '') {
    $values = explode('|', $sort_data);
    $ids    = array();
    $cases  = array();
    $sorti  = 10;

    foreach ($values as $val) {
        $acontent_id = (int) $val;
        if ($acontent_id > 0) {
            $ids[]   = $acontent_id;
            $cases[] = 'WHEN ' . $acontent_id . ' THEN ' . $sorti;
            $sorti  += 10;
        }
    }

    if (!empty($ids)) {
        $where_perm = empty($_SESSION['wcs_user_admin']) ? ' AND acontent_uid = ' . (int) $_SESSION['wcs_user_id'] : '';
        $sql = 'UPDATE ' . DB_PREPEND . 'articlecontent SET ' .
               'acontent_sorting = CASE acontent_id ' . implode(' ', $cases) . ' END, ' .
               'acontent_tstamp = acontent_tstamp ' .
               'WHERE acontent_id IN (' . implode(',', $ids) . ')' . $where_perm;
        _dbQuery($sql, 'UPDATE');
    }
}

update_cache();
