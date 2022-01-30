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

if(isset($_GET["sort"])) {
    list($value1, $value2) = explode("|", $_GET["sort"]);
    list($id1, $sort1) = explode(":", $value1);
    list($id2, $sort2) = explode(":", $value2);
    $id1 = intval($id1);
    $id2 = intval($id2);
    $sort1 = intval($sort1);
    $sort2 = intval($sort2);

    if($sort1 === $sort2) {
        $sort2 = $sort1+10;
    }

    $sql = "UPDATE ".DB_PREPEND."phpwcms_articlecontent SET acontent_sorting=".$sort1.',acontent_tstamp=acontent_tstamp'.
           " WHERE (acontent_uid=".intval($_SESSION["wcs_user_id"])." OR ".intval($_SESSION["wcs_user_admin"]).")".
           " AND acontent_id=".$id1;
    _dbQuery($sql, 'UPDATE');

    $sql = "UPDATE ".DB_PREPEND."phpwcms_articlecontent SET acontent_sorting=".$sort2.',acontent_tstamp=acontent_tstamp'.
           " WHERE (acontent_uid=".intval($_SESSION["wcs_user_id"])." OR ".intval($_SESSION["wcs_user_admin"]).")".
           " AND acontent_id=".$id2;
    _dbQuery($sql, 'UPDATE');
}

update_cache();
headerRedirect($ref);
