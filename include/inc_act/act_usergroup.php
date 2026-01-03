<?php
/**
 * cmsGO!
 *
 * @author Pixels & Points GmbH <info@pixels-points.ch>
 * @copyright Copyright (c) 2002-2026, Pixels & Points GmbH
 * @license https://www.pixels-points.ch/cmsgo-license.html Pixels & Points cmsGO! license
 *
 **/

$cmsgo = array('SESSION_START' => true);

require_once '../config/conf.inc.php';
require_once '../inc_lib/default.inc.php';
require_once CMSGO_ROOT.'/include/inc_lib/helper.session.php';
require_once CMSGO_ROOT.'/include/inc_lib/dbcon.inc.php';
require_once CMSGO_ROOT.'/include/inc_lib/general.inc.php';
checkLogin();
validate_csrf_tokens();
require_once CMSGO_ROOT.'/include/inc_lib/backend.functions.inc.php';

if($_SESSION["wcs_user_admin"] == 1) { //If user has admin rights

  // enym
  if(isset($_GET["del"])) {
    $gi = explode(":", clean_slweg($_GET["del"]));
    $gi = intval($gi[0]);
    if($gi) {
      _dbUpdate('cmsgo_usergroup', array('group_active' => 9), 'group_id='.$gi);
    }
  }

  if(isset($_GET["aktiv"])) {
    $sql = "UPDATE ".DB_PREPEND."cmsgo_usergroup SET group_active= (CASE group_active WHEN 1 THEN 0 ELSE 1 END) WHERE group_id=".intval($_GET["aktiv"]);
    _dbQuery($sql, 'UPDATE');
  }


} //End action

$ref = empty($_SESSION['REFERER_URL']) ? CMSGO_URL.'cmsgo.php?'.get_token_get_string() : $_SESSION['REFERER_URL'];

headerRedirect($ref);
