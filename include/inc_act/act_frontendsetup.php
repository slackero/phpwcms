<?php
/**
 * phpwcms content management system
 *
 * @author Oliver Georgi <oliver@phpwcms.de>
 * @copyright Copyright (c) 2002-2014, Oliver Georgi
 * @license http://opensource.org/licenses/GPL-2.0 GNU GPL-2
 * @link http://www.phpwcms.de
 *
 **/

session_start();
$phpwcms = array();

require_once ('../../config/phpwcms/conf.inc.php');
require_once ('../inc_lib/default.inc.php');
require_once (PHPWCMS_ROOT.'/include/inc_lib/dbcon.inc.php');
require_once (PHPWCMS_ROOT.'/include/inc_lib/general.inc.php');
checkLogin();
require_once (PHPWCMS_ROOT.'/include/inc_lib/backend.functions.inc.php');

if($_SESSION["wcs_user_admin"] == 1) {

	list($do, $id) = explode('|', $_GET['do']);
	$do = intval($do);
	$id = intval($id);

	if($id) {
		if($do === 1) {
			_dbUpdate('phpwcms_pagelayout', array('pagelayout_default'=>0, 'pagelayout_trash'=>9), 'pagelayout_id='.$id);
		} elseif($do === 2) {
			$result = _dbUpdate('phpwcms_template', array('template_default'=>0, 'template_trash'=>9), 'template_id='.$id);
			// Update article categories with new default template ID or to 0 if no default template is defined
			if($result) {
				$default = _dbGet('phpwcms_template', 'template_id, template_default', 'template_trash=0 AND template_default=1', '', '', 1);
				_dbUpdate(
					'phpwcms_articlecat',
					array('acat_template' => isset($default[0]['template_id']) ? $default[0]['template_id'] : 0),
					'acat_trash=0 AND acat_template='.$id
				);
			}
		}
	}
}

headerRedirect($_SESSION['REFERER_URL']);

?>