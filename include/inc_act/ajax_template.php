<?php
/**
 * phpwcms
 *
 * @author Oliver Georgi <og@phpwcms.org>
 * @copyright Copyright (c) 2002-2026, Oliver Georgi
 * @license http://opensource.org/licenses/GPL-2.0 GNU GPL-2
 *
 **/

// general wrapper for ajax based queries
$phpwcms = array('SESSION_START' => true);
require_once '../config/conf.inc.php';
require_once '../inc_lib/default.inc.php';
require_once PHPWCMS_ROOT.'/include/inc_lib/helper.session.php';
require_once PHPWCMS_ROOT.'/include/inc_lib/dbcon.inc.php';
require_once PHPWCMS_ROOT.'/include/inc_lib/general.inc.php';
require_once PHPWCMS_ROOT.'/include/inc_lib/backend.functions.inc.php';
require_once PHPWCMS_ROOT.'/include/inc_lang/backend/en/lang.inc.php';
if(!empty($_SESSION["wcs_user_lang_custom"])) { //use custom lang if available -> was set in login.php
    include(PHPWCMS_ROOT.'/include/inc_lang/backend/'.substr($_SESSION["wcs_user_lang"],0,2).'/lang.inc.php');
}

if(empty($_SESSION["wcs_user_id"]) || !validate_csrf_get_token()) {
    die('Sorry, access forbidden');
}

//get variables
$action = isset($_REQUEST['action']) ? $_REQUEST['action'] : '';
$ctntemplate = isset($_REQUEST['template']) ? $_REQUEST['template'] : '';
$ctnid = isset($_REQUEST['id']) ? $_REQUEST['id'] : '';
//echo readfile ($ctntemplate);

if ($action == 'form') {
    $frontend_css = read_textfile($ctntemplate);
    $frontend_css = ($frontend_css) ? html($frontend_css) : "";
    echo $frontend_css;
} elseif ($action == 'list') {
    echo '<h2>Sites containing this template:</h2><p class="tmpl_menu">';
    $sql =  "SELECT DISTINCT ar.article_title, ar.article_id, ac.acontent_id FROM ".DB_PREPEND."phpwcms_articlecontent ac ";
    $sql .= "INNER JOIN " . DB_PREPEND . "phpwcms_article ar ON ";
    $sql .= "ar.article_id = ac.acontent_aid ";
    $sql .= "WHERE ac.acontent_type="._dbEscape($ctnid)." AND acontent_trash=0 AND article_deleted = 0 AND ";
    if ($ctnid == '8') {
        $sql .= ' acontent_form LIKE ' . _dbEscapeLike($ctntemplate);
    } else {
        $sql .= ' acontent_template = ' . _dbEscape($ctntemplate);
    }


    $data = _dbQuery($sql);
    if(isset($data[0]['article_id'])) {
        foreach($data as $crow) {
            echo '<a href="phpwcms.php?'.get_token_get_string().'&do=articles&p=2&s=1&aktion=2&id='.$crow[1].'&acid='.$crow[2].'" target=_blank>'.$crow[0].' <i class="fa-solid fa-pencil-alt text-primary ms-1"></i></a><br>';
        }
    }

    echo '</p>';
}
