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

if($_SESSION["wcs_user_admin"] == 1) {

	list($do, $id) = explode('|', $_GET['do']);
	$do = intval($do);
	$id = intval($id);

	if($id) {
		if($do === 1) {
			_dbUpdate('cmsgo_pagelayout', array('pagelayout_default'=>0, 'pagelayout_trash'=>9), 'pagelayout_id='.$id);
		} elseif($do === 2) {
			$result = _dbUpdate('cmsgo_template', array('template_default'=>0, 'template_trash'=>9), 'template_id='.$id);
			// Update article categories with new default template ID or to 0 if no default template is defined
			if($result) {
				$default = _dbGet('cmsgo_template', 'template_id, template_default', 'template_trash=0 AND template_default=1', '', '', 1);
				_dbUpdate(
					'cmsgo_articlecat',
					array('acat_template' => isset($default[0]['template_id']) ? $default[0]['template_id'] : 0),
					'acat_trash=0 AND acat_template='.$id
				);
			}
		}
	}
}

$ref = empty($_SESSION['REFERER_URL']) ? CMSGO_URL.'cmsgo.php?'.get_token_get_string() : $_SESSION['REFERER_URL'];

headerRedirect($ref);
