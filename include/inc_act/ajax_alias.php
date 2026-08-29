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
require_once PHPWCMS_ROOT.'/include/inc_lib/backend.functions.inc.php';
require_once PHPWCMS_ROOT.'/include/inc_lang/backend/en/lang.inc.php';
if($_SESSION["wcs_user_lang_custom"]) { //use custom lang if available -> was set in login.php
    include(PHPWCMS_ROOT.'/include/inc_lang/backend/'.substr($_SESSION["wcs_user_lang"],0,2).'/lang.inc.php');
}

if(empty($_SESSION["wcs_user_id"]) || !validate_csrf_get_token()) {
    die('Sorry, access forbidden');
}

//get variables
$action = isset($_REQUEST['action']) ? $_REQUEST['action'] : '';
$alias = isset($_REQUEST['article_alias']) ? clean_slweg($_REQUEST['article_alias'], 255) : '';
$acatalias = isset($_REQUEST['acat_alias']) ? clean_slweg($_REQUEST['acat_alias'], 255) : '';
$article_id = isset($_REQUEST['article_id']) ? intval($_REQUEST['article_id']) : '';
$acat_id = isset($_REQUEST['acat_id']) ? intval($_REQUEST['acat_id']) : '';
$acattemplate = isset($_REQUEST['template']) ? intval($_REQUEST['template']) : '0';

if ($phpwcms['charset'] == 'utf-8') {
    $article_description = isset($_REQUEST['article_description']) ? clean_slweg($_REQUEST["article_description"], 255) : '';
    $acat_pagetitle = isset($_REQUEST['acat_pagetitle']) ? clean_slweg($_REQUEST["acat_pagetitle"], 255) : '';
} else {
    $article_description = isset($_REQUEST['article_description']) ? clean_slweg(mb_convert_encoding($_REQUEST["article_description"], PHPWCMS_CHARSET), 255) : '';
    $acat_pagetitle = isset($_REQUEST['acat_pagetitle']) ? clean_slweg(mb_convert_encoding($_REQUEST["acat_pagetitle"], PHPWCMS_CHARSET), 255) : '';
}

if($action == 'form' && $article_id) {
    $content['current_article'] = _dbGet('phpwcms_article', '*', 'article_id='._dbEscape($article_id), '', '', 1);

    echo '<div class="p-2">'.LF;
    $kw_str = '<span class="text-muted">–</span>';
    if (!empty($content['current_article'][0]['article_keyword'])) {
        $kw_str = '';
        foreach (convertStringToArray($content['current_article'][0]['article_keyword'], ',') as $kw) {
            $kw_str .= '<span class="badge badge-light border fw-normal me-1">' . html_specialchars($kw) . '</span>';
        }
    }
    echo '<div class="form-group row g-2 align-items-center my-0"><span class="col-sm-3 col-form-label text-end fw-bold">'.$BL['be_article_akeywords'] .':</span><div class="col-sm-9"> '. $kw_str .'</div></div>';
    echo '<div class="form-group row g-2 align-items-center"><span class="col-sm-3 col-form-label text-end fw-bold">'.$BL['be_cnt_left'] .':</span><div class="col-sm-9">';
    echo '<a href="phpwcms.php?do=articles&p=2&s=1&id=' . $article_id . '" target="_blank">'.$BL['be_cnt_articles'] .'</a>'.LF;
    echo '| <a href="index.php?aid=' . $article_id . '" target="_blank">'.$BL['be_func_struct_preview'] .'</a></div></div>'.LF;
    echo '<div class="form-group row g-2"><label class="col-sm-3 col-form-label text-end fw-bold" for="article_description'.$article_id.'">'.$BL['be_cnt_description'] .':</label><div class="col-sm-9">'.LF;
    echo '<textarea name="article_description" rows="3" class="form-control form-control-sm field-sizing-content field-sizing-content-3" id="article_description'.$article_id.'">'.html_specialchars($content['current_article'][0]['article_description']).'</textarea></div></div>'.LF;
    echo '<div class="form-group row g-2 align-items-center"><label class="col-sm-3 col-form-label text-end fw-bold" for="article_alias'.$article_id.'">'.$BL['be_article_urlalias'] .':</label><div class="col-sm-9">'.LF;
    echo '<input name="article_alias" type="text" class="form-control form-control-sm" id="article_alias'. $article_id.'" value="'.html_specialchars($content['current_article'][0]['article_alias']).'" maxlength="230" onfocus="set_article_alias(true);" onchange="this.value=create_alias(this.value);" /></div></div>'.LF;
    echo '<div class="form-group row g-2 align-items-center">'.LF;
    echo '<div class="col-sm-3"></div>'.LF;
    echo '<div class="col">'.LF;
    echo '<button name="save" type="button" onclick="AjaxSubmit('."'alias-".$article_id."', '".$article_id."', document.editartikel.article_alias".$article_id.".value, document.editartikel.article_description".$article_id.".value".')" class="btn btn-sm btn-blue me-1"><i class="fa fa-check"></i> '.$BL['be_article_cnt_button1'].'</button>';
    echo '<button name="close" type="button" onclick="AjaxClose('."'alias-".$article_id."','','".$article_id."','')".'" class="btn btn-sm btn-danger ms-2"><i class="fa fa-times"></i> '.$BL['be_newsletter_button_cancel'].'</button>';
    echo '</div>'.LF;
    echo '</div>'.LF;
    echo '</div>'.LF;
}
if($action == 'form' && $acat_id) {
    $content['current_articlecat'] = _dbGet('phpwcms_articlecat', '*', 'acat_id='._dbEscape($acat_id), '', '', 1);
    $content['current_template'] = _dbGet('phpwcms_template', '*', 'template_trash=0 AND template_id='._dbEscape($content['current_articlecat'][0]['acat_template']), '', '', 1);
    $acat_template = $content['current_template'][0]['template_name'];

    echo '<div class="p-2">'.LF;
    echo '<div class="form-group row g-2 align-items-center my-0"><span class="col-sm-3 col-form-label text-end fw-bold">'.$BL['be_admin_struct_cat'] .'</span><div class="col-sm-9">'. html_specialchars($content['current_articlecat'][0]['acat_name']).'</div></div>';
    echo '<div class="form-group row g-2 align-items-center"><span class="col-sm-3 col-form-label text-end fw-bold">'.$BL['be_admin_struct_template'] .'</span><div class="col-sm-9">'. html_specialchars($content['current_template'][0]['template_name']).'</div></div>'.LF;
    if ($content['current_articlecat'][0]['acat_keywords']!='') {
        echo '<div class="form-group row g-2 align-items-center"><span class="col-sm-3 col-form-label text-end fw-bold">'.$BL['be_article_akeywords'] .'</span><div class="col-sm-9">'. html_specialchars($content['current_articlecat'][0]['acat_keywords']).'</div></div>'.LF;
    }
    echo '<div class="form-group row g-2 align-items-center"><label class="col-sm-3 col-form-label text-end fw-bold" for="acat_alias'.$acat_id.'">'.$BL['be_admin_struct_alias'] .'</label>'.LF;
    echo '<div class="col-sm-9"><input name="acat_alias" type="text" class="form-control form-control-sm" id="acat_alias'. $acat_id.'" value="'.html_specialchars($content['current_articlecat'][0]['acat_alias']).'" maxlength="230" onfocus="set_article_alias(true,' . "'struct'" . ');" onchange="this.value=create_alias(this.value);" /></div></div>'.LF;

    echo '<div class="form-group row g-2 align-items-center"><label class="col-sm-3 col-form-label text-end fw-bold" for="acat_pagetitle'.$acat_id.'">'.$BL['be_admin_page_pagetitle'] .'</label>'.LF;
    echo '<div class="col-sm-9"><input name="template" type="hidden" id="template'.$acat_id.'" value="'.$content['current_articlecat'][0]['acat_template'].'">'.LF;
    echo '<input name="acat_pagetitle" class="form-control form-control-sm" id="acat_pagetitle'.$acat_id.'" value="'.html_specialchars($content['current_articlecat'][0]['acat_pagetitle']).'" maxlength="230"></div></div>'.LF;
    echo '<div class="row my-3">'.LF;
    echo '<div class="col-sm-3"></div>'.LF;
    echo '<div class="col">'.LF;
    echo '<button name="save" type="button" onclick="AjaxSubmitCat('."'catalias-".$acat_id."', '".$acat_id."', document.editstructur.acat_alias".$acat_id.".value, document.editstructur.acat_pagetitle".$acat_id.".value,'" . $acattemplate . "')" . '" class="btn btn-sm btn-blue me-1"><i class="fa fa-check"></i> '.$BL['be_article_cnt_button1'].'</button>';
    echo '<button name="close" type="button" onclick="AjaxClose('."'catalias-".$acat_id."', '".$acat_id."','','" . $acattemplate . "')".'" class="btn btn-sm btn-danger ms-2"><i class="fa fa-times"></i> '.$BL['be_newsletter_button_cancel'].'</button>';
    echo '</div>'.LF;
    echo '</div>'.LF;
    echo '</div>'.LF;
}

if($action == 'update') {

    $article_alias = proof_alias($article_id, $alias, 'ARTICLE');

    $sql_alias = "UPDATE ".DB_PREPEND."phpwcms_article SET ";
    $sql_alias .= " article_alias = '".aporeplace($article_alias)."', ";
    $sql_alias .= " article_description = '".aporeplace($article_description)."' ";
    $sql_alias .= " WHERE article_id = ".$article_id;
    _dbQuery($sql_alias, 'UPDATE');

    echo '<div class="btn btn-sm '.(empty($article_alias) ? "btn-danger" : "btn-success").' me-1" data-bs-toggle="tooltip" title="'.$BL['be_acat_alias'].'">A</div>';
    echo '<div class="btn btn-sm '.(empty($article_description) ? "btn-danger" : "btn-success").' me-1" data-bs-toggle="tooltip" title="'.$BL['be_article_description'].'">D</div>';

    echo '<a href="phpwcms.php?do=articles&p=2&s=1&id='.$article_id.'">'.(empty($article_alias) ? 'no alias' : html_specialchars($article_alias) ).'</a>';

    echo '<a href="#" class="btn btn-sm btn-blue float-end" onClick="'."AjaxLink('alias-".$article_id."','','".$article_id."');".'"><i class="fa fa-pencil-alt"></i></a>';

}

if($action == 'updatecat') {

    $acat_alias = proof_alias($acat_id, $acatalias, 'CATEGORY');

    $sql_alias = "UPDATE ".DB_PREPEND."phpwcms_articlecat SET ";
    $sql_alias .= " acat_alias = '".aporeplace($acat_alias)."', ";
    $sql_alias .= " acat_pagetitle = '".aporeplace($acat_pagetitle)."' ";
    $sql_alias .= " WHERE acat_id = ".$acat_id;
    _dbQuery($sql_alias, 'UPDATE');

    echo '<div class="btn btn-sm '.(empty($acat_alias) ? "btn-danger" : "btn-success").' me-1" data-bs-toggle="tooltip" title="'.$BL['be_acat_alias'].'">A</div>';
    echo '<div class="btn btn-sm '.(empty($acat_pagetitle) ? "btn-danger" : "btn-success").' me-1" data-bs-toggle="tooltip" title="'.$BL['be_acat_redirect'].'">T</div>';

    $content['current_template'] = _dbGet('phpwcms_template', '*', 'template_trash=0 AND template_id='._dbEscape($acattemplate), '', '', 1);
    echo $content['current_template'][0]['template_name'] . ' | ';

    echo '<a href="phpwcms.php?do=articles&p=2&s=1&id='.$acat_id.'">'.(empty($acat_alias) ? 'no alias' : html_specialchars($acat_alias) ).'</a>';

    echo '<a href="phpwcms.php?do=article&p=6&struct=0&cat='.$acat_id.'">'.(empty($acat_alias) ? 'no alias' : html_specialchars($acat_alias) ).'</a>';

    echo '<a href="#" class="btn btn-sm btn-blue float-end" onClick="'."AjaxLink('catalias-".$acat_id."', '".$acat_id."','');".'"><i class="fa fa-pencil-alt"></i></a>';

}

if($action == 'close' && $article_id) {
    $content['current_article'] = _dbGet('phpwcms_article', 'article_alias, article_description', 'article_id='._dbEscape($article_id), '', '', 1);

    echo '<div class="btn btn-sm '.(empty($content['current_article'][0]["article_alias"]) ? "btn-danger" : "btn-success").' me-1" data-bs-toggle="tooltip" title="'.$BL['be_acat_alias'].'">A</div>';
    echo '<div class="btn btn-sm '.(empty($content['current_article'][0]["article_description"]) ? "btn-danger" : "btn-success").' me-1" data-bs-toggle="tooltip" title="'.$BL['be_article_description'].'">D</div>';

    echo '<a href="phpwcms.php?do=articles&p=2&s=1&id='.$content['current_article'][0]["article_id"].'">'.(empty($content['current_article'][0]["article_alias"]) ? 'no alias' : html_specialchars($content['current_article'][0]["article_alias"]) ).'</a>';

    echo '<a href="#" class="btn btn-sm btn-blue float-end" onClick="'."AjaxLink('alias-".$article_id."', '".$article_id."');".'"><i class="fa fa-pencil-alt"></i></a>';
}

if($action == 'close' && $acat_id) {
    $content['current_acat'] = _dbGet('phpwcms_articlecat', 'acat_alias, acat_pagetitle', 'acat_id='._dbEscape($acat_id), '', '', 1);

    echo '<div class="btn btn-sm '.(empty($content['current_acat'][0]["acat_alias"]) ? "btn-danger" : "btn-success").' me-1" data-bs-toggle="tooltip" title="'.$BL['be_acat_alias'].'">A</div>';
    echo '<div class="btn btn-sm '.(empty($content['current_acat'][0]["acat_pagetitle"]) ? "btn-danger" : "btn-success").' me-1" data-bs-toggle="tooltip" title="'.$BL['be_acat_pagetitle'].'">T</div>';

    $content['current_template'] = _dbGet('phpwcms_template', '*', 'template_trash=0 AND template_id='._dbEscape($acattemplate), '', '', 1);
    echo $content['current_template'][0]['template_name'] . ' | ';

    echo empty($content['current_acat'][0]["acat_alias"]) ? 'no alias' : $content['current_acat'][0]["acat_alias"];
    echo '<a href="#" class="btn btn-sm btn-blue float-end" onClick="'."AjaxLink('catalias-".$acat_id."', '".$acat_id."','');".'"><i class="fa fa-pencil-alt"></i></a>';
}



