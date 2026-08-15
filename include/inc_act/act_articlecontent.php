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

$ref = empty($_SESSION['REFERER_URL']) ? PHPWCMS_URL.'phpwcms.php?'.get_token_get_string() : $_SESSION['REFERER_URL'];

if(isset($_GET["do"])) {
    $values = explode(",", $_GET["do"]);
    if(count($values)) {
        switch(intval($values[0])) {
            case 9: //delete article content part
                    $sql = "UPDATE ".DB_PREPEND."phpwcms_articlecontent SET acontent_trash=9".
                           " WHERE (acontent_uid=".intval($_SESSION["wcs_user_id"])." OR ".intval($_SESSION["wcs_user_admin"]).")".
                           " AND acontent_aid=".intval($values[1]).
                           " AND acontent_id=".intval($values[2]);
                    _dbQuery($sql, 'UPDATE');
                    break;
            case 1: //delete article
                    $sql = "UPDATE ".DB_PREPEND."phpwcms_article SET article_deleted=9, article_alias=CONCAT(article_alias,'_del-','".date('YmdHis')."')".
                           " WHERE (article_uid=".intval($_SESSION["wcs_user_id"])." OR ".intval($_SESSION["wcs_user_admin"]).")".
                           " AND article_id=".intval($values[1]);
                    _dbQuery($sql, 'UPDATE');
                    $ref .= '&p=&s=&id=';
                    break;
            case 2: //make content visible/invisible
                    $sql = "UPDATE ".DB_PREPEND."phpwcms_articlecontent SET acontent_visible=".intval($values[3]).
                           " WHERE (acontent_uid=".intval($_SESSION["wcs_user_id"])." OR ".intval($_SESSION["wcs_user_admin"]).")".
                           " AND acontent_aid=".intval($values[1]).
                           " AND acontent_id=".intval($values[2]);
                    _dbQuery($sql, 'UPDATE');
                    break;
            case 3: //make article visible/invisible
                    $sql = "UPDATE ".DB_PREPEND."phpwcms_article SET article_aktiv=".intval($values[3]).
                           " WHERE article_id=".intval($values[1]);
                    _dbQuery($sql, 'UPDATE');
                    break;
        }
    }
}

if(isset($_GET['sort'])) {
    $sort_parts = explode('|', $_GET['sort']);
    if(count($sort_parts) >= 2) {
        $p1    = explode(':', $sort_parts[0]);
        $p2    = explode(':', $sort_parts[1]);
        $id1   = isset($p1[0]) ? (int) $p1[0] : 0;
        $sort1 = isset($p1[1]) ? (int) $p1[1] : 0;
        $id2   = isset($p2[0]) ? (int) $p2[0] : 0;
        $sort2 = isset($p2[1]) ? (int) $p2[1] : 0;

        if($id1 > 0 && $id2 > 0) {
            if($sort1 === $sort2) {
                $sort2 = $sort1 + 10;
            }
            $where_perm = empty($_SESSION['wcs_user_admin']) ? ' AND acontent_uid = ' . (int) $_SESSION['wcs_user_id'] : '';
            $sql = 'UPDATE ' . DB_PREPEND . 'phpwcms_articlecontent SET ' .
                   'acontent_sorting = CASE acontent_id WHEN ' . $id1 . ' THEN ' . $sort1 . ' WHEN ' . $id2 . ' THEN ' . $sort2 . ' END, ' .
                   'acontent_tstamp = acontent_tstamp ' .
                   'WHERE acontent_id IN (' . $id1 . ', ' . $id2 . ')' . $where_perm;
            _dbQuery($sql, 'UPDATE');
        }
    }
}

update_cache();
headerRedirect($ref);
