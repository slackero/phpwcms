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

// ----------------------------------------------------------------
// obligate check for phpwcms constants
if (!defined('PHPWCMS_ROOT')) {
	die("You Cannot Access This Script Directly, Have a Nice Day.");
}
// ----------------------------------------------------------------


/*
 * module Calendar
 * ===============
 *
 * some defaults for modules: $phpwcms['modules'][$module]
 * store all related in here and holds some default values
 * ['path'], ['type'], ['name']
 * language values are store in $BL['modules'][$module]
 * as defined in lang/en.lang.php
 * but maybe to keep default language file more lightweight
 * you can use own language definitions starting within this file
 *
 */

if(isset($phpwcms['modules'][$module]['path'])) {

	// module default stuff

	// load special backend CSS
	$BE['HEADER']['module_calendar.css'] = '	<link href="'.$phpwcms['modules'][$module]['dir'].'template/backend.calendar.css" rel="stylesheet" type="text/css" />';

	// put translation back to have easier access to it - use it as relation
	$BLM = & $BL['modules'][$module];
	define('MODULE_HREF', 'phpwcms.php?'.get_token_get_string().'&amp;do=modules&amp;module='.$module);
	$glossary = array();


	if(isset($_GET['edit'])) {

		// handle posts and read data
		include_once $phpwcms['modules'][$module]['path'].'inc/processing.inc.php';

		// edit form
		include_once $phpwcms['modules'][$module]['path'].'backend.editform.php';

	} elseif(isset($_GET['verify'])) {

		// active/inactive
		$sql  = 'UPDATE '.DB_PREPEND.'phpwcms_calendar SET ';
		$sql .= "calendar_status=".(intval($_GET['verify']) ? 1 : 0)." ";
		$sql .= "WHERE calendar_id=".intval($_GET['editid']);
		@_dbQuery($sql, 'UPDATE');
		headerRedirect(decode_entities(MODULE_HREF));

	} elseif(isset($_GET['delete'])) {

		// delete
		$sql  = 'UPDATE '.DB_PREPEND.'phpwcms_calendar SET ';
		$sql .= "calendar_status=9 WHERE calendar_id=".intval($_GET['delete']);
		@_dbQuery($sql, 'UPDATE');
		headerRedirect(decode_entities(MODULE_HREF));

	} else {

		// listing
		include_once $phpwcms['modules'][$module]['path'].'backend.listing.php';

	}

}
