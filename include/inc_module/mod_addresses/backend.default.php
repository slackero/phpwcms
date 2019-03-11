<?php
// ----------------------------------------------------------------
// obligate check for cmsgo constants
if (!defined('CMSGO_ROOT')) {
   die("You Cannot Access This Script Directly, Have a Nice Day.");
}
// ----------------------------------------------------------------


/*
 * cmsGO! Addresses
 * ================
 */

// first check if neccessary db exists
if(isset($cmsgo['modules'][$module]['path'])) {

	// module default stuff

	// put translation back to have easier access to it - use it as relation
	$BLM = & $BL['modules'][$module];
	define('MODULE_HREF', 'cmsgo.php?'.get_token_get_string('csrftoken').'&amp;do=modules&amp;module='.$module);
	define('MODULE_HREF_DECODE', str_replace('&amp;', '&', MODULE_HREF));
	define('MODULE_PATH', $cmsgo['modules'][$module]['path']);
	define('MODULE_BASEPATH', $cmsgo['modules'][$module]['dir']);
	define('MODULE_KEY', 'CMSGO-ADDRESS');

	include_once(MODULE_PATH.'inc/conf.inc.php');


	$_dealer = array();


	if(isset($_GET['edit'])) {

		// handle posts and read data
		include_once(MODULE_PATH.'inc/processing.inc.php');

		// edit form
		include_once(MODULE_PATH.'backend.editform.php');

	} elseif(isset($_GET['verify'])) {

		// active/inactive
		$sql  = 'UPDATE '.DB_PREPEND.'cmsgo_userdetail SET ';
		$sql .= "detail_aktiv=".(intval($_GET['verify']) ? 1 : 0)." ";
		$sql .= "WHERE detail_regkey="._dbEscape(MODULE_KEY)." AND detail_id=".intval($_GET['editid']);
		@_dbQuery($sql, 'UPDATE');
		headerRedirect(MODULE_HREF_DECODE);

	} elseif(isset($_GET['delete'])) {

		// delete
		$sql  = 'UPDATE '.DB_PREPEND.'cmsgo_userdetail SET detail_aktiv=9 ';
		$sql .= "WHERE detail_regkey="._dbEscape(MODULE_KEY)." AND detail_id=".intval($_GET['delete']);
		@_dbQuery($sql, 'UPDATE');
		headerRedirect(MODULE_HREF_DECODE);

	} else {

		// listing
		include_once(MODULE_PATH.'backend.listing.php');

	}

}
