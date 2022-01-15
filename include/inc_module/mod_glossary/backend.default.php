<?php
/**
 * cmsGO!
 *
 * @author Pixels & Points GmbH <info@pixels-points.ch>
 * @copyright Copyright (c) 2002-2022, Pixels & Points GmbH
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
 * module glossary
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

// first check if neccessary db exists
if(isset($cmsgo['modules'][$module]['path']) && file_exists($cmsgo['modules'][$module]['path'].'setup/setup.php')) {

	include_once $cmsgo['modules'][$module]['path'].'setup/setup.php';

} elseif(isset($cmsgo['modules'][$module]['path'])) {

	// module default stuff

	// put translation back to have easier access to it - use it as relation
	$BLM = & $BL['modules'][$module];
	define('GLOSSARY_HREF', 'cmsgo.php?'.get_token_get_string().'&amp;do=modules&amp;module='.$module);
	$glossary = array();


	if(isset($_GET['edit'])) {

		// handle posts and read data
		include_once $cmsgo['modules'][$module]['path'].'inc/processing.inc.php';

		// edit form
		include_once $cmsgo['modules'][$module]['path'].'backend.editform.php';

	} elseif(isset($_GET['verify'])) {

		// active/inactive
		$sql  = 'UPDATE '.DB_PREPEND.'cmsgo_glossary SET ';
		$sql .= "glossary_status=".(intval($_GET['verify']) ? 1 : 0)." ";
		$sql .= "WHERE glossary_id=".intval($_GET['editid']);
		@_dbQuery($sql, 'UPDATE');
		headerRedirect(decode_entities(GLOSSARY_HREF));

	} elseif(isset($_GET['delete'])) {

		// delete
		$sql  = 'UPDATE '.DB_PREPEND.'cmsgo_glossary SET ';
		$sql .= "glossary_status=9 WHERE glossary_id=".intval($_GET['delete']);
		@_dbQuery($sql, 'UPDATE');
		headerRedirect(decode_entities(GLOSSARY_HREF));

	} else {

		// listing
		include_once $cmsgo['modules'][$module]['path'].'backend.listing.php';

	}

}
