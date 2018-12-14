<?php
/**
 * cmsGo!
 *
 * @author Pixels & Points GmbH <info@pixels-points.ch>
 * @copyright Copyright (c) 2002-2018, Pixels & Points GmbH
 * @license https://www.pixels-points.ch/cmsgo-license.html Pixels & Points cmsGo! license
 *
 **/

// ----------------------------------------------------------------
// obligate check for cmsgo constants
if (!defined('CMSGO_ROOT')) {
	die("You Cannot Access This Script Directly, Have a Nice Day.");
}
// ----------------------------------------------------------------


/*
 * module Calendar
 * ===============
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

if(isset($cmsgo['modules'][$module]['path'])) {

	// module default stuff

	// load special backend CSS
	$BE['HEADER']['module_calendar.css'] = '	<link href="'.$cmsgo['modules'][$module]['dir'].'template/backend.calendar.css" rel="stylesheet" type="text/css" />';

	// put translation back to have easier access to it - use it as relation
	$BLM = & $BL['modules'][$module];
	define('MODULE_HREF', 'cmsgo.php?'.get_token_get_string('csrftoken').'&amp;do=modules&amp;module='.$module);
	$glossary = array();


	if(isset($_GET['edit'])) {

		// handle posts and read data
		include_once $cmsgo['modules'][$module]['path'].'inc/processing.inc.php';

		// edit form
		include_once $cmsgo['modules'][$module]['path'].'backend.editform.php';

	} elseif(isset($_GET['verify'])) {

		// active/inactive
		$sql  = 'UPDATE '.DB_PREPEND.'cmsgo_calendar SET ';
		$sql .= "calendar_status=".(intval($_GET['verify']) ? 1 : 0)." ";
		$sql .= "WHERE calendar_id=".intval($_GET['editid']);
		@_dbQuery($sql, 'UPDATE');
		headerRedirect(decode_entities(MODULE_HREF));

	} elseif(isset($_GET['delete'])) {

		// delete
		$sql  = 'UPDATE '.DB_PREPEND.'cmsgo_calendar SET ';
		$sql .= "calendar_status=9 WHERE calendar_id=".intval($_GET['delete']);
		@_dbQuery($sql, 'UPDATE');
		headerRedirect(decode_entities(MODULE_HREF));

	} else {

		// listing
		include_once $cmsgo['modules'][$module]['path'].'backend.listing.php';

	}

}
