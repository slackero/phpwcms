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

//19-11-2004 Fernando Batista -> Copy article, Copy strutures http://fernandobatista.net
//31-03-2005 Fernando Batista -> Copy/Cut Article Content http://fernandobatista.net

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

if($_SESSION["wcs_user_admin"] === 1) { // Only for admin users

    if(isset($_POST["acat_access"]) && is_array($_POST["acat_access"]) && count($_POST["acat_access"])) {

        $acat_permit = implode(',', $_POST["acat_access"]);

        // enym, limited access requires some default settings
        $_POST["acat_regonly"] = 1;
        $_POST["acat_nosearch"] = 1;
        unset($_POST["acat_nositemap"]);

    } else {
        $acat_permit = '';
    }

    $acat_hidden = 0;
    if(isset($_POST["acat_hidden"])) {
        $acat_hidden = 1;
        if(isset($_POST["acat_hiddenactive"])) {
            $acat_hidden = 2;
        }
    }

    $acat_cntpart = '';
    if(isset($_POST['acat_cp']) && is_array($_POST['acat_cp'])) {

        $acat_cntpart = $_POST['acat_cp'];
        $acat_cntpart = array_unique($acat_cntpart);
        $acat_cntpart = implode(',', $acat_cntpart);

    }

    $acat_class = empty($_POST["acat_class"]) ? '' : preg_replace('/[^a-zA-Z0-9_\- ]/', '', clean_slweg($_POST["acat_class"], 150));

    if(empty($_POST["acat_keywords"])) {
        $acat_keywords = '';
    } else {
        $acat_keywords = substr( implode(', ', convertStringToArray( clean_slweg($_POST["acat_keywords"], 255) ) ), 0, 255);
    }

    $acat_breadcrumb = 0;
    if(!empty($_POST["acat_breadcrumb_nothidden"])) {
        $acat_breadcrumb = 1;
    }
    if(!empty($_POST["acat_breadcrumb_nolink"])) {
        $acat_breadcrumb += 2;
    }

    $acat_onepage = empty($_POST["acat_onepage"]) ? 0 : 1;

    if(isset($_POST["acat_id"]) && $_POST["acat_id"] === 'index') {
        // write index page config to flat file
        $sql  = "<?php\n";
        $sql .= "\$indexpage['acat_name'] = '". str_replace("''", "\\'", clean_slweg($_POST["acat_name"], 2000))."';\n";
        $sql .= "\$indexpage['acat_title'] = '". str_replace("''", "\\'", clean_slweg($_POST["acat_title"], 2000))."';\n";
        $sql .= "\$indexpage['acat_info'] = '". str_replace("''", "\\'", clean_slweg($_POST["acat_info"], 32000))."';\n";
        $sql .= "\$indexpage['acat_alias'] = '". proof_alias($_POST["acat_id"], $_POST["acat_alias"])."';\n";
        $sql .= "\$indexpage['acat_aktiv'] = ". (isset($_POST["acat_aktiv"]) ? 1 : 0).";\n";
        $sql .= "\$indexpage['acat_template'] = ". intval($_POST["acat_template"]).";\n";
        $sql .= "\$indexpage['acat_hidden'] = ". $acat_hidden.";\n";
        $sql .= "\$indexpage['acat_ssl'] = ". (isset($_POST["acat_ssl"]) ? 1 : 0).";\n";
        $sql .= "\$indexpage['acat_regonly'] = ". (isset($_POST["acat_regonly"]) ? 1 : 0).";\n";
        $sql .= "\$indexpage['acat_topcount'] = ". intval($_POST["acat_topcount"]).";\n";
        $sql .= "\$indexpage['acat_maxlist'] = ". intval($_POST["acat_maxlist"]).";\n";
        $sql .= "\$indexpage['acat_redirect'] = '". str_replace("''", "\\'", clean_slweg($_POST["acat_redirect"]))."';\n";
        $cache_timeout = clean_slweg($_POST["acat_timeout"]);
        if(isset($_POST['acat_cacheoff']) && intval($_POST['acat_cacheoff'])) $cache_timeout = 0; //check if cache = Off
        $sql .= "\$indexpage['acat_timeout'] = '". $cache_timeout."';\n";
        $sql .= "\$indexpage['acat_nosearch'] = '". ((isset($_POST['acat_nosearch']) && intval($_POST['acat_nosearch'])) ? '1' : '')."';\n";
        $sql .= "\$indexpage['acat_nositemap'] = ". (isset($_POST["acat_nositemap"]) ? 1 : 0).";\n";
        $sql .= "\$indexpage['acat_order'] = ". set_correct_ordersort() .";\n";
        $sql .= "\$indexpage['acat_permit'] = '". $acat_permit."';\n";
        $sql .= "\$indexpage['acat_cntpart'] = '". $acat_cntpart."';\n";
        $sql .= "\$indexpage['acat_pagetitle'] = '". str_replace("''", "\\'", clean_slweg($_POST["acat_pagetitle"]))."';\n";
        $sql .= "\$indexpage['acat_paginate'] = ". (isset($_POST["acat_paginate"]) ? 1 : 0).";\n";
        $sql .= "\$indexpage['acat_overwrite'] = '". str_replace("''", "\\'", clean_slweg($_POST["acat_overwrite"]))."';\n";
        $sql .= "\$indexpage['acat_archive'] = ". (empty($_POST["acat_archive"]) ? 0 : 1) .";\n";
        $sql .= "\$indexpage['acat_class'] = '". str_replace("'", "\\'", $acat_class)."';\n";
        $sql .= "\$indexpage['acat_keywords'] = '". str_replace("'", "\\'", $acat_keywords)."';\n";
        $sql .= "\$indexpage['acat_cpdefault'] = ". intval($_POST["acat_cpdefault"]).";\n";
        $sql .= "\$indexpage['acat_disable301'] = ". (empty($_POST["acat_disable301"]) ? 0 : 1).";\n";
        $sql .= "\$indexpage['acat_opengraph'] = ". (empty($_POST["acat_opengraph"]) ? 0 : 1).";\n";
        $sql .= "\$indexpage['acat_canonical'] = '". str_replace("'", "\\'", clean_slweg($_POST["acat_canonical"], 2000))."';\n";
        $sql .= "\$indexpage['acat_breadcrumb'] = ". $acat_breadcrumb .";\n";
        $sql .= "\$indexpage['acat_onepage'] = ". $acat_onepage .";\n";

        write_textfile(PHPWCMS_ROOT.'/include/config/conf.indexpage.inc.php', $sql);
    }

    $acat_sort_fallback = isset($_POST["acat_sort"]) ? intval(trim($_POST["acat_sort"])) : 0;
    $acat_sort_temp     = isset($_POST["acat_sort_temp"]) ? intval($_POST["acat_sort_temp"]) : 0;
    $acat_lang          = empty($_POST["acat_lang"]) ? '' : clean_slweg($_POST["acat_lang"]);
    $acat_lang_type     = $acat_lang === '' || empty($_POST["acat_lang_type"]) ? '' : (in_array($_POST["acat_lang_type"], array('category', 'article')) ? $_POST["acat_lang_type"] : '');
    $acat_lang_id       = $acat_lang_type == '' || empty($_POST["acat_lang_id"]) ? 0 : intval($_POST["acat_lang_id"]);

    if($acat_sort_fallback === 0 && $acat_sort_temp > 0) {
        $acat_sort_fallback = $acat_sort_temp;
    }

    if(isset($_POST["acat_new"]) && intval($_POST["acat_new"]) == 1 && !intval($_POST["acat_id"]) && $_POST["acat_id"] !== 'index') {
        if(trim($_POST["acat_name"])) {

            $cache_timeout = clean_slweg($_POST["acat_timeout"]);
            if(isset($_POST['acat_cacheoff']) && intval($_POST['acat_cacheoff'])) {
                $cache_timeout = 0; //check if cache = Off
            }

            $sql = "INSERT INTO ".DB_PREPEND."phpwcms_articlecat (acat_name, acat_title, acat_info, acat_aktiv, acat_ssl, acat_regonly, ".
                "acat_struct, acat_template, acat_sort, acat_uid, acat_alias, acat_hidden, acat_topcount, ".
                "acat_redirect, acat_order, acat_cache, acat_nosearch, acat_nositemap, acat_permit, acat_maxlist, ".
                "acat_cntpart, acat_pagetitle, acat_paginate, acat_overwrite, acat_archive, acat_class, acat_keywords, ".
                "acat_cpdefault, acat_lang, acat_lang_type, acat_lang_id, acat_disable301, acat_opengraph, acat_canonical, acat_breadcrumb, acat_onepage) VALUES ('".
                getpostvar($_POST["acat_name"], 2000)."','".
                getpostvar($_POST["acat_title"], 2000)."','".
                getpostvar($_POST["acat_info"], 32000)."',".
                (isset($_POST["acat_aktiv"]) ? 1 : 0).",".
                (isset($_POST["acat_ssl"]) ? 1 : 0).",".
                (isset($_POST["acat_regonly"]) ? 1 : 0).",".
                intval($_POST["acat_struct"]).",".
                intval($_POST["acat_template"]).",".
                $acat_sort_fallback.",".
                $_SESSION["wcs_user_id"].",'".
                proof_alias($_POST["acat_id"], $_POST["acat_alias"])."',".
                $acat_hidden.", ".
                intval($_POST["acat_topcount"]).",'".
                getpostvar($_POST["acat_redirect"])."', ".
                set_correct_ordersort().",'".
                $cache_timeout."', '".(isset($_POST['acat_nosearch']) ? 1 : '')."',".
                (isset($_POST["acat_nositemap"]) ? 1 : 0).",".
                "'".$acat_permit."', ".intval($_POST["acat_maxlist"]).", "._dbEscape($acat_cntpart).",'".
                getpostvar($_POST["acat_pagetitle"])."', ".(isset($_POST["acat_paginate"]) ? 1 : 0).", '".getpostvar($_POST["acat_overwrite"])."',".
                (empty($_POST["acat_archive"]) ? 0 : 1).", "._dbEscape($acat_class).", "._dbEscape($acat_keywords).", ".intval($_POST["acat_cpdefault"]).",".
                _dbEscape($acat_lang).','._dbEscape($acat_lang_type).','._dbEscape($acat_lang_id).','.(empty($_POST["acat_disable301"]) ? '0' : '1').','.
                (empty($_POST["acat_opengraph"]) ? 0 : 1).', '._dbEscape(clean_slweg($_POST["acat_canonical"], 2000)).','.
                $acat_breadcrumb.', '.$acat_onepage.')';

            $result = _dbQuery($sql, 'INSERT');
            if(isset($result['INSERT_ID'])) {
                $ref .= "&cat=".$result['INSERT_ID'];
            }
        }
    }

    if(isset($_POST["acat_new"]) && isset($_POST["acat_id"]) && intval($_POST["acat_new"]) == 0 && intval($_POST["acat_id"])) {
        if(trim($_POST["acat_name"])) {

            $cache_timeout = clean_slweg($_POST["acat_timeout"]);
            if(isset($_POST['acat_cacheoff']) && intval($_POST['acat_cacheoff'])) {
                $cache_timeout = 0; //check if cache = Off
            }

            $sql = "UPDATE ".DB_PREPEND."phpwcms_articlecat SET ".
                "acat_name='".getpostvar($_POST["acat_name"], 2000)."', ".
                "acat_title='".getpostvar($_POST["acat_title"], 2000)."', ".
                "acat_info='".getpostvar($_POST["acat_info"], 32000)."', ".
                "acat_alias="._dbEscape(proof_alias($_POST["acat_id"], $_POST["acat_alias"])).", ".
                "acat_aktiv=".(isset($_POST["acat_aktiv"]) ? 1 : 0).", ".
                "acat_struct=".intval($_POST["acat_struct"]).", ".
                "acat_template=".intval($_POST["acat_template"]).", ".
                "acat_sort=".$acat_sort_fallback.", ".
                "acat_uid=".$_SESSION["wcs_user_id"].", ".
                "acat_hidden=".$acat_hidden.", ".
                "acat_ssl=".(isset($_POST["acat_ssl"]) ? 1 : 0).", ".
                "acat_regonly=".(isset($_POST["acat_regonly"]) ? 1 : 0).", ".
                "acat_topcount=".intval($_POST["acat_topcount"]).", ".
                "acat_redirect='".getpostvar($_POST["acat_redirect"])."',".
                "acat_order=".set_correct_ordersort().", ".
                "acat_cache="._dbEscape($cache_timeout).", ".
                "acat_nosearch='".(isset($_POST['acat_nosearch']) ? 1 : '')."', ".
                "acat_nositemap=".(isset($_POST["acat_nositemap"]) ? 1 : 0).", ".
                "acat_permit="._dbEscape($acat_permit).", ".
                "acat_maxlist=".intval($_POST["acat_maxlist"]).", ".
                "acat_cntpart="._dbEscape($acat_cntpart).", ".
                "acat_pagetitle='".getpostvar($_POST["acat_pagetitle"])."', ".
                "acat_paginate=".(isset($_POST["acat_paginate"]) ? 1 : 0).", ".
                "acat_overwrite='".getpostvar($_POST["acat_overwrite"])."', ".
                "acat_archive=".(empty($_POST["acat_archive"]) ? 0 : 1).", ".
                "acat_class="._dbEscape($acat_class).", ".
                "acat_keywords="._dbEscape($acat_keywords).",".
                "acat_cpdefault=".intval($_POST["acat_cpdefault"]).','.
                "acat_lang="._dbEscape($acat_lang).','.
                "acat_lang_type="._dbEscape($acat_lang_type).','.
                "acat_lang_id="._dbEscape($acat_lang_id).','.
                "acat_disable301=".(empty($_POST["acat_disable301"]) ? '0' : '1').','.
                "acat_opengraph=".(empty($_POST["acat_opengraph"]) ? '0' : '1').','.
                "acat_canonical="._dbEscape(clean_slweg($_POST["acat_canonical"], 2000)).','.
                "acat_breadcrumb=".$acat_breadcrumb.','.
                "acat_onepage=".$acat_onepage.
            " WHERE acat_id=".intval($_POST["acat_id"]);

            _dbQuery($sql, 'UPDATE');
        }
    }

}

// Diverse actions
$do = explode("|", isset($_GET["do"]) ? $_GET["do"] : '');
$action = intval($do[0]);

if($action) {

    // Admin related actions only
    if($_SESSION["wcs_user_admin"] === 1) {

        // Insert
        if($action === 1) {

            $do[1] = intval($do[1]); //cut ID
            $do[2] = intval($do[2]); //paste ID
            $do[3] = intval($do[3]); //sort Number
            if($do[1]) { // && $do[2] = 0 for Root
                $sql =  "UPDATE ".DB_PREPEND."phpwcms_articlecat SET acat_struct=".$do[2].", acat_sort=".$do[3]." WHERE acat_id=".$do[1];
                _dbQuery($sql, 'UPDATE');
            }

        // Change sorting
        } elseif($action === 2) {

            $do[1] = intval($do[1]); //sort ID1
            $do[2] = intval($do[2]); //sort NR1
            $do[3] = intval($do[3]); //sort ID2
            $do[4] = intval($do[4]); //sort NR2
            if($do[1] && $do[2]>=10 && $do[3] && $do[4]>=10) {
                $sql =  "UPDATE ".DB_PREPEND."phpwcms_articlecat SET acat_sort=".$do[2]." WHERE acat_id=".$do[1];
                _dbQuery($sql, 'UPDATE');

                $sql =  "UPDATE ".DB_PREPEND."phpwcms_articlecat SET acat_sort=".$do[4]." WHERE acat_id=".$do[3];
                _dbQuery($sql, 'UPDATE');
            }

        // Copy Structure
        } elseif($action === 6) {

            $do[1] = intval($do[1]); //copy level ID
            $do[2] = intval($do[2]); //paste level ID
            $do[3] = intval($do[3]); //sort Number
            if($do[1]) { // && $do[2] = 0 for Root
                copy_level_to_level($do);
            }

        // Delete structure level
        } elseif($action === 9) {

            $do[1] = intval($do[1]); //delete ID

            if($do[1]) {
                // extend deleting of structure levels also for deleting of all related child
                // structure levels and articles on structure level
                // this is necessary to be sure that such deleted articles/structures are
                // not available anymore

                // 1.) get all structure level IDs and put into an array
                $struct_del = array();
                $article_del = array();

                $struct_del[] = $do[1]; //start

                get_struct_del_id($do[1]);

                // create SQL query to set articles deleted
                if(count($article_del)) {

                    $a_del = array();
                    foreach($article_del as $value) {
                        //delete cached articles
                        $sql = "DELETE FROM ".DB_PREPEND."phpwcms_cache WHERE cache_aid=".intval($value);
                        _dbQuery($sql, 'DELETE');

                        $a_del[] = $value;
                    }

                    if(count($a_del)) {
                        $sql = "UPDATE ".DB_PREPEND."phpwcms_article SET article_deleted=9, article_alias=CONCAT(article_alias,'_del-','".date('YmdHis')."') WHERE article_id IN (".implode(',', $a_del).")";
                        _dbQuery($sql, 'UPDATE');
                    }
                }

                // create SQL query to set structure levels deleted
                if(count($struct_del)) {

                    $s_del = array();
                    foreach($struct_del as $value) {
                        //delete cached categories
                        $sql = "DELETE FROM ".DB_PREPEND."phpwcms_cache WHERE cache_cid=".intval($value);
                        _dbQuery($sql, 'DELETE');

                        $s_del[] = $value;
                    }

                    if(count($s_del)) {
                        $sql = "UPDATE ".DB_PREPEND."phpwcms_articlecat SET acat_trash=9, acat_alias=CONCAT(acat_alias,'_del-','".date('YmdHis')."') WHERE acat_id IN (".implode(',', $s_del).")";
                        _dbQuery($sql, 'UPDATE');
                    }
                }

            }
        }
    }

    // Insert article
    if($action === 3) {

        $do[1] = intval($do[1]); //cut Article ID
        $do[2] = intval($do[2]); //paste level ID
        if($do[1]) { // && $do[2] = 0 for Root
            $new_sort = getArticleSortValue($do[2]);
            $sql =  "UPDATE ".DB_PREPEND."phpwcms_article SET article_cid=".$do[2].", article_sort=".$new_sort." WHERE article_id=".$do[1];
            _dbQuery($sql, 'UPDATE');
        }

    // Change sorting of articles
    } elseif($action === 4) {

        $do[1] = intval($do[1]); //article sort ID1
        $do[2] = intval($do[2]); //article sort NR1
        $do[3] = intval($do[3]); //article sort ID2
        $do[4] = intval($do[4]); //article sort NR2
        if($do[1] && $do[2]>=10 && $do[3] && $do[4]>=10) {
            $sql =  "UPDATE ".DB_PREPEND."phpwcms_article SET article_sort=".$do[2].", article_tstamp=article_tstamp WHERE article_id=".$do[1];
            _dbQuery($sql, 'UPDATE');

            $sql =  "UPDATE ".DB_PREPEND."phpwcms_article SET article_sort=".$do[4].", article_tstamp=article_tstamp WHERE article_id=".$do[3];
            _dbQuery($sql, 'UPDATE');
        }

    // Copy Article
    } elseif($action === 5) { //19-11-2004  Fernando Batista

        $do[1] = intval($do[1]); //copy Article ID
        $do[2] = intval($do[2]); //paste level ID
        $do[3] = isset($do[3]) && $do[3] == 'open' ? 'open' : 0; // special link to copy an existing article and open the new
        if($do[1]) { //also allowed for pasting in root structure
            copy_article_to_level($do);
        }

    // Cut & Paste Content Part
    } elseif($action === 7) { // 31-03-2005 Fernando Batista

        $do[1] = intval($do[1]); //cut Article Content ID
        $do[2] = intval($do[2]); //paste Article ID
        $do[3] = intval($do[3]); //sort Number
        if($do[1]) {
            $sql = "SELECT acontent_aid, acontent_sorting FROM ".DB_PREPEND."phpwcms_articlecontent WHERE acontent_id=".$do[1];
            $result = _dbQuery($sql);
            if(isset($result[0]['acontent_aid'])) {

                $sql = "UPDATE ".DB_PREPEND."phpwcms_articlecontent SET acontent_sorting=acontent_sorting-10 WHERE acontent_aid=".$result[0]['acontent_aid']." AND acontent_sorting >= ".$result[0]['acontent_sorting']."+10";
                _dbQuery($sql, 'UPDATE');

                $sql = "UPDATE ".DB_PREPEND."phpwcms_articlecontent SET acontent_sorting=acontent_sorting+10 WHERE acontent_aid=".$do[2]." AND acontent_sorting >= ".$do[3]."+10";
                _dbQuery($sql, 'UPDATE');

                $sql = "UPDATE ".DB_PREPEND."phpwcms_articlecontent SET acontent_aid=".$do[2].", acontent_sorting=".$do[3]."+10 WHERE acontent_id=".$do[1];
                _dbQuery($sql, 'UPDATE');

            }
        }

    // Copy Content Part
    } elseif($action === 8) { // 31-03-2005 Fernando Batista

        $do[1] = intval($do[1]); //copy Article Content ID
        $do[2] = intval($do[2]); //paste Article ID
        $do[3] = intval($do[3]); //sort Number
        if($do[1]) {

            $sql = "UPDATE ".DB_PREPEND."phpwcms_articlecontent SET acontent_sorting=acontent_sorting+10 WHERE acontent_aid=".$do[2]." AND acontent_sorting >= ".$do[3]."+10";
            _dbQuery($sql, 'UPDATE');

            $sql  = "SELECT * FROM ".DB_PREPEND."phpwcms_articlecontent WHERE acontent_id=".$do[1];
            $result = _dbQuery($sql);
            if(isset($result[0]['acontent_id'])) {
                $key1s = '';
                foreach($result[0] as $key => $value) {
                    if($key === "acontent_created") {
                        $key1s   .= ", ".$key;
                        $value1s .= ", NOW()";
                    } elseif($key === "acontent_id") {
                        $key1s   = $key;
                        $value1s = "''";
                    } elseif($key === "acontent_aid" ) {
                        $key1s   .= ", ".$key;
                        $value1s .= ", '".$do[2]."'";
                    } elseif($key === "acontent_sorting" ) {
                        $key1s   .= ", ".$key;
                        $do[3] = $do[3] + 10;
                        $value1s .= ", '".$do[3]."'";
                    } else {
                        $key1s   .= ", ".$key;
                        $value1s .= ", "._dbEscape($value);
                    }
                }
                $key1s = trim($key1s, ' ,');
                $value1s = trim($value1s, ' ,');
                $sql = "INSERT INTO ".DB_PREPEND."phpwcms_articlecontent (".$key1s.") VALUES (".$value1s.")";
                _dbQuery($sql, 'INSERT');
            }
        }
    }
}

update_cache();

// empty pre-rendered frontend structure for all visible modes
// VISIBLE_MODE: 0 = frontend (all) mode, 1 = article user mode, 2 = admin user mode
_setConfig('structure_array_vmode_all', '', 'frontend_render', 1);
_setConfig('structure_array_vmode_editor', '', 'frontend_render', 1);
_setConfig('structure_array_vmode_admin', '', 'frontend_render', 1);

if(isset($_POST['SubmitClose'])) {
    headerRedirect(PHPWCMS_URL.'phpwcms.php?'.get_token_get_string().'&do=admin&p=6');
} else {
    headerRedirect($ref);
}

function get_struct_del_id($s_id=0) {

    $s_id = intval($s_id);

    //retrieve article ID list that should be deleted
    $sql = "SELECT article_id FROM ".DB_PREPEND."phpwcms_article WHERE article_deleted=0 AND article_cid=".$s_id;
    $result = _dbQuery($sql);
    if(isset($result[0]['article_id'])) {
        foreach($result as $row) {
            $GLOBALS["article_del"][] = $row['article_id'];
        }
    }

    // retrieve structure ID list that should be deleted
    $sql = "SELECT acat_id FROM ".DB_PREPEND."phpwcms_articlecat WHERE acat_trash=0 AND acat_struct=".$s_id;
    $result = _dbQuery($sql);
    if(isset($result[0]['acat_id'])) {
        foreach($result as $row) {
            $GLOBALS["struct_del"][] = $row['acat_id'];
            get_struct_del_id($row['acat_id']);
        }
    }
}

function copy_article_to_level($do) {

    $sql = "SELECT * FROM ".DB_PREPEND."phpwcms_article WHERE article_deleted=0 AND article_id=".$do[1];
    $result = _dbQuery($sql);
    if(isset($result[0]['article_id'])) {

        $row = $result[0];
        $row["article_cid"]      = $do[2];
        $row["article_created"]  = now();
        $row["article_tstamp"]   = date('Y-m-d H:i:s', now() );
        $row["article_sort"]     = getArticleSortValue($row["article_cid"]);
        $row["article_alias"]    = proof_alias(0, empty($row["article_alias"]) ? $row['article_title'] : $row["article_alias"], 'ARTICLE');

        // Check if the owner of the article needs to be updated
        if($_SESSION["wcs_user_admin"] === 0 && intval($row["article_uid"]) !== intval($_SESSION["wcs_user_id"])) {
            $row["article_uid"]      = $_SESSION["wcs_user_id"];
            $row["article_username"] = $_SESSION["wcs_user_name"];
        }

        $keys = '';
        $values = '';

        foreach($row as $key => $value) {
            if($key === "article_id" ){
                $keys = $key;
                $values = "''";
            } else {
                $keys .= ", ".$key;
                $values .= ", "._dbEscape($value);
            }
        }

        $sql =  "INSERT INTO ".DB_PREPEND."phpwcms_article (".$keys.") VALUES (".$values.")";
        $result = _dbQuery($sql, 'INSERT');

        if(isset($result['INSERT_ID'])) {

            $article_insert_id = $result['INSERT_ID'];

            $sql = "SELECT * FROM ".DB_PREPEND."phpwcms_articlecontent WHERE acontent_aid=".$do[1];
            $result = _dbQuery($sql);

            if(isset($result[0]['acontent_aid'])) {
                foreach($result as $row) {
                    $row["acontent_aid"] = $article_insert_id;

                    // Check if the owner of the content part needs to be updated too
                    if($_SESSION["wcs_user_admin"] === 0 && intval($row["acontent_uid"]) !== intval($_SESSION["wcs_user_id"])) {
                        $row["acontent_uid"] = $_SESSION["wcs_user_id"];
                    }

                    $key1s = '';
                    $value1s = '';

                    foreach($row as $key1 => $value1) {
                        if($key1 === "acontent_id" ){
                            $key1s = $key1;
                            $value1s = "''";
                        } else {
                            $key1s .= ", ".$key1;
                            $value1s .= ", "._dbEscape($value1);
                        }
                    }
                    $sql =  "INSERT INTO ".DB_PREPEND."phpwcms_articlecontent (".$key1s.") VALUES (".$value1s.")";
                    _dbQuery($sql, 'INSERT');
                }
            }

            if(empty($GLOBALS['phpwcms']['disallow_open_copied_article']) && isset($do[3]) && $do[3] == 'open') {

                headerRedirect(PHPWCMS_URL.'phpwcms.php?'.get_token_get_string().'&do=articles&p=2&s=1&id='.$article_insert_id);

            }

        }

    }
}

function copy_level_to_level($do) {
    // $do[1] -- copy level
    // $do[2] -- paste level
    // $do[3] -- sort Number

    $sql = "SELECT * FROM ".DB_PREPEND."phpwcms_articlecat WHERE acat_trash=0 AND acat_id=".$do[1];
    $result = _dbQuery($sql);
    $acat_insert_id = 0;

    if(isset($result[0]['acat_id'])) {
        $row = $result[0];
        $row["acat_struct"] = $do[2];
        $row["acat_sort"]   = $do[3];
        $row["acat_alias"]  = proof_alias(0, empty($row["acat_alias"]) ? $row['acat_name'] : $row["acat_alias"], 'CATEGORY');

        // Check if the owner of the structure level needs to be updated
        if($_SESSION["wcs_user_admin"] === 0 && intval($row["acat_uid"]) !== intval($_SESSION["wcs_user_id"])) {
            $row["acat_uid"] = $_SESSION["wcs_user_id"];
        }

        $keys = '';
        $values = '';

        foreach($row as $key => $value) {
            if($key === 'acat_id' ) {
                $keys = $key;
                $values = "''";
            } else {
                $keys   .= ", ".$key;
                $values .= ", "._dbEscape($value);
            }
        }

        $sql = "INSERT INTO ".DB_PREPEND."phpwcms_articlecat (".$keys.") VALUES (".$values.")";
        $result = _dbQuery($sql, 'INSERT');
        if(isset($result['INSERT_ID'])) {
            $acat_insert_id = $result['INSERT_ID'];
        }
    }

    if($acat_insert_id) {
        $sql = "SELECT article_id FROM ".DB_PREPEND."phpwcms_article WHERE article_deleted=0 AND article_cid=".$do[1];
        $result = _dbQuery($sql);
        if(isset($result[0]['article_id'])) {
            foreach($result as $row) {
                $do_article[1] = $row['article_id'];
                $do_article[2] = $acat_insert_id;
                copy_article_to_level($do_article);
            }
        }

        $sql = "SELECT acat_id,acat_sort FROM ".DB_PREPEND."phpwcms_articlecat WHERE acat_trash=0 AND acat_struct=".$do[1];
        $result = _dbQuery($sql);
        if(isset($result[0]['acat_id'])) {
            foreach($result as $row) {
                $do_struct[1] = $row['acat_id'];
                $do_struct[2] = $acat_insert_id;
                $do_struct[3] = $row['acat_sort'];
                copy_level_to_level($do_struct);
            }
        }
    }
}

function set_correct_ordersort() {

    // but why not - should be possible too based on new sorting
    $val  = empty($_POST["acat_order"]) ? 0 : intval($_POST["acat_order"]);
    $val += empty($_POST["acat_ordersort"]) ? 0 : intval($_POST["acat_ordersort"]);

    return $val;
}
