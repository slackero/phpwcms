<?php
/**
 * cmsGo!
 *
 * @author Pixels & Points GmbH <info@pixels-points.ch>
 * @copyright Copyright (c) 2002-2018, Pixels & Points GmbH
 * @license https://www.pixels-points.ch/cmsgo-license.html Pixels & Points cmsGo! license
 *
 **/

// general wrapper for ajax based queries
session_start();
$cmsgo = array();
require_once ('../../include/config/conf.inc.php');
require('../inc_lib/default.inc.php');
require(CMSGO_ROOT.'/include/inc_lib/dbcon.inc.php');
require(CMSGO_ROOT.'/include/inc_lib/general.inc.php');
require(CMSGO_ROOT.'/include/inc_lib/backend.functions.inc.php');
require_once (CMSGO_ROOT.'/include/inc_lang/backend/en/lang.inc.php');
if($_SESSION["wcs_user_lang_custom"]) { //use custom lang if available -> was set in edit.php
  include(CMSGO_ROOT.'/include/inc_lang/backend/'.substr($_SESSION["wcs_user_lang"],0,2).'/lang.inc.php');
  // Adding specific language files
  include CMSGO_ROOT.'/include/inc_lang/backend/'. substr($_SESSION["wcs_user_lang"],0,2) .'/lang.pp.inc.php';
}

if(empty($_SESSION["wcs_user"])) {
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
  $sql =  "SELECT DISTINCT ar.article_title, ar.article_id, ac.acontent_id FROM ".DB_PREPEND."cmsgo_articlecontent ac ";
  $sql .= "INNER JOIN " . DB_PREPEND . "cmsgo_article ar ON ";
  $sql .= "ar.article_id = ac.acontent_aid ";
  $sql .= "WHERE ac.acontent_type="._dbEscape($ctnid)." AND acontent_trash=0 AND article_deleted = 0 AND ";
  if ($ctnid == '8') {
    $sql .= " acontent_form like '%".$ctntemplate."%'";
  } else {
    $sql .= " acontent_template ="._dbEscape($ctntemplate);
  }
  

  $data = _dbQuery($sql);
  if(isset($data[0]['article_id'])) {
  	foreach($data as $crow) {
		echo '<a href="cmsgo.php?'.get_token_get_string('csrftoken').'&do=articles&p=2&s=1&aktion=2&id='.$crow[1].'&acid='.$crow[2].'" target=_blank>'.$crow[0].' <img border="0" alt="" src="img/button/edit_22x13.gif"></a><br>';
  	}
  }

  echo '</p>';
}
?>


