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

// ----------------------------------------------------------------
// obligate check for phpwcms constants
if (!defined('PHPWCMS_ROOT')) {
    die("You Cannot Access This Script Directly, Have a Nice Day.");
}
// ----------------------------------------------------------------

// read content type form vars
if($content["aid"] != intval($_POST["caid"])) {
    die("error: wrong form data!");
}

$content["title"]           = clean_slweg($_POST["ctitle"]);
$content["subtitle"]        = clean_slweg($_POST["csubtitle"]);
$content["comment"]         = slweg($_POST["ccomment"]);
$content["paginate_title"]  = clean_slweg($_POST["cpaginate_title"]);
$content["paginate_page"]   = empty($_POST["cpaginate_page"]) ? 0 : intval($_POST["cpaginate_page"]);
$content["visible"]         = empty($_POST["cvisible"]) ? 0 : 1;
$content["before"]          = intval($_POST["cbefore"]);
$content["after"]           = intval($_POST["cafter"]);
$content["top"]             = isset($_POST["ctop"]) ? 1 : 0;
$content["anchor"]          = isset($_POST["canchor"]) ? 1 : 0;
$content["id"]              = intval($_POST["cid"]);
$content["granted"]         = empty($_POST["cgranted"]) ? 0 : intval($_POST["cgranted"]);
if ($content["granted"] < 0 || $content["granted"] > 2) {
    $content["granted"] = 0;
}
$content["tid"]             = empty($_POST["ctid"]) || !in_array(intval($_POST["ctid"]), array(0, 1, 2, 3)) ? 0 : intval($_POST["ctid"]);
$content["attr_class"]      = empty($_POST["cattr_class"]) ? '' : clean_slweg($_POST["cattr_class"]);
$content["attr_id"]         = empty($_POST["cattr_id"]) ? '' : trim(preg_replace('/[^A-Za-z0-9_\/\w\-\:\.]/', '', preg_replace('/\s+/', '-', clean_slweg($_POST["cattr_id"]))), '-');
if($content["attr_id"] && !preg_match('/^[A-Za-z]$/', substr($content["attr_id"], 0, 1))) {
    $content["attr_id"] = 'id_'.$content["attr_id"];
}

$set_livedate = empty($_POST['set_livedate']) ? 0 : 1;
$set_killdate = empty($_POST['set_killdate']) ? 0 : 1;
$content['livedate'] = clean_slweg($_POST["clivedate"]);
$content['killdate'] = clean_slweg($_POST["ckilldate"]);

if($set_livedate && $content['livedate']) {
    $content['livedate'] = phpwcms_strtotime($content['livedate']);
    if($content['livedate'] === false) {
        $content['livedate'] = date('Y-m-d H:i:s', now());
    } else {
        $content['livedate'] = date("Y-m-d H:i:s", $content['livedate']);
    }
} else {
    $content['livedate'] = '0000-00-00 00:00:00';
}
if($set_killdate && $content['killdate']) {
    $content['killdate'] = phpwcms_strtotime($content['killdate']);
    if($content['killdate'] === false) {
        $content['killdate'] = '0000-00-00 00:00:00';
    } else {
        $content['killdate'] = date("Y-m-d H:i:s", $content['killdate']);
    }
} else {
    $content['killdate'] = '0000-00-00 00:00:00';
}

if(!empty($_POST['ctype_change_aid'])) {

    $ctype_change_aid   = intval($_POST['ctype_change_aid']);

    if($ctype_change_aid && $ctype_change_aid != $content["aid"]) {

        $ctype_change_aid   = _dbQuery('SELECT article_id FROM '.DB_PREPEND.'phpwcms_article WHERE article_id='.$ctype_change_aid.' AND article_deleted=0');
        if(!empty($ctype_change_aid[0]['article_id'])) {
            $content["aid"] = $ctype_change_aid[0]['article_id'];
            $ctype_change_aid = 'DO_CHANGE';
        }
    }
}

if(empty($_POST['ccb']) || $content["before"] > 9999 || $content["before"] < -9999) {
    $content["before"] = '';
}
if(empty($_POST['cca']) || $content["after"] > 9999 || $content["after"] < -9999) {
    $content["after"] = '';
}

if(isset($_POST["target_ctype"])) {

    $content["target_type"] = explode(':', $_POST["target_ctype"]);
    $content["module"]      = empty($content["target_type"][1]) ? '' : trim($content["target_type"][1]);
    $content["target_type"] = intval($content["target_type"][0]);

} else {

    $content["target_type"] = 0;
    $content["module"]  = '';

}

$content["sorting"] = intval($_POST["csorting"]);
$content["block"]   = clean_slweg($_POST["cblock"]);
// reset paginate page number to 0 > pagination support for CONTENT block only
if($content["paginate_page"] && $content["block"] != 'CONTENT') {
    $content["paginate_page"] = 0;
}

$content["tab"]         = '';
$content['tab_type']    = empty($_POST['ctab']) ? 0 : clean_slweg($_POST['ctab']);
if($content['tab_type']) {

    $content["tab_number"]  = empty($_POST['ctab_number']) ? 0 : intval($_POST['ctab_number']);
    $content["tab_title"]   = empty($_POST['ctab_title']) ? '' : clean_slweg($_POST['ctab_title'], 100);

    if($content["tab_number"] || $content["tab_title"]) {

        $content["tab"] = $content["tab_number"] . '|' . $content['tab_type'] . '_' . $content["tab_title"];

    }
}

$content["module"] = empty($_POST["ctype_module"]) ? '' : clean_slweg($_POST["ctype_module"]);

// check if content type possibly changed
$content["update_type"] = ($content["target_type"] != $content["type"]) ? 1 : 0;

// read form vars for special content parts
if($content["type"] != 30 && file_exists(PHPWCMS_ROOT."/include/inc_lib/content/cnt".$content["type"].".readform.inc.php")) {

    $content["module"]  = '';
    include_once PHPWCMS_ROOT."/include/inc_lib/content/cnt".$content["type"].".readform.inc.php";

} elseif($content["type"] == 30 && file_exists($phpwcms['modules'][$content['module']]['path'].'inc/cnt.post.php')) {

    include_once $phpwcms['modules'][$content['module']]['path'].'inc/cnt.post.php';

} else {

    $content["module"]  = '';
    include_once PHPWCMS_ROOT."/include/inc_lib/content/cnt0.readform.inc.php";

}
