<?php
/**
 * cmsGO!
 *
 * @author Pixels & Points GmbH <info@pixels-points.ch>
 * @copyright Copyright (c) 2002-2023, Pixels & Points GmbH
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

if($_SESSION["wcs_user_admin"] == 1) { //if user has admin rights

	write_textfile(CMSGO_TEMPLATE."inc_css/frontend.css", slweg($_POST["frontend_css"]));

}

$ref = empty($_SESSION['REFERER_URL']) ? CMSGO_URL.'cmsgo.php?'.get_token_get_string() : $_SESSION['REFERER_URL'];

headerRedirect($ref);
