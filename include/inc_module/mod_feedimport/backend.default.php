<?php
/**
 * cmsGO!
 *
 * @author Pixels & Points GmbH <info@pixels-points.ch>
 * @copyright Copyright (c) 2002-2021, Pixels & Points GmbH
 * @license https://www.pixels-points.ch/cmsgo-license.html Pixels & Points cmsGO! license
 *
 **/

// ----------------------------------------------------------------
// obligate check for cmsgo constants
if (!defined('CMSGO_ROOT')) {
	die("You Cannot Access This Script Directly, Have a Nice Day.");
}
// ----------------------------------------------------------------

/*
 * Module/Plug-in Feed to Article import
 * =====================================
 *
 * some defaults for modules: $cmsgo['modules'][$module]
 * store all related in here and holds some default values
 * ['path'], ['type'], ['name']
 * language values are store in $BL['modules'][$module]
 * as defined in lang/en.lang.php
 * but maybe to keep default language file more lightweight
 * you can use own language definitions starting within this file
 *
 */

// first check if neccessary db exists
if(isset($cmsgo['modules'][$module]['path'])) {

	// module default stuff

	// put translation back to have easier access to it - use it as relation
	$BLM =& $BL['modules'][$module];
	define('MODULE_HREF', 'cmsgo.php?'.get_token_get_string().'&amp;do=modules&amp;module='.$module);
	define('MODULE_HREF_DECODE', CMSGO_URL . 'cmsgo.php?'.get_token_get_string().'&do=modules&module='.$module);
	define('MODULE_KEY', 'feedimport');

	require_once($cmsgo['modules'][$module]['path'].'inc/functions.inc.php');

	if(isset($_GET['edit'])) {

		include_once CMSGO_ROOT.'/include/inc_lib/article.functions.inc.php'; //load article funtions

		// handle posts and read data
		include_once $cmsgo['modules'][$module]['path'].'inc/processing.inc.php';

		// edit form
		include_once $cmsgo['modules'][$module]['path'].'backend.editform.php';

	} elseif(isset($_GET['active']) && !empty($_GET['editid'])) {

		// active/inactive
		$data = array(
			'cnt_changed'	=> now(),
			'cnt_status'	=> empty($_GET['active']) ? 0 : 1
		);
		_dbUpdate('cmsgo_content', $data, 'cnt_id='.intval($_GET['editid']).' AND cnt_module='._dbEscape(MODULE_KEY));
		headerRedirect(MODULE_HREF_DECODE);

	} elseif(!empty($_GET['delete'])) {

		// delete
		$data = array(
			'cnt_changed'	=> now(),
			'cnt_status'	=> 9
		);
		_dbUpdate('cmsgo_content', $data, 'cnt_id='.intval($_GET['delete']).' AND cnt_module='._dbEscape(MODULE_KEY));
		headerRedirect(MODULE_HREF_DECODE);

	} else {

		// listing
		include_once $cmsgo['modules'][$module]['path'].'backend.listing.php';

	}

}
