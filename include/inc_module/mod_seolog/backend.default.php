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
 * module glossary
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

// first check if neccessary db exists
if(isset($phpwcms['modules'][$module]['path'])) {

	// module default stuff

	// put translation back to have easier access to it - use it as relation
	$BLM = & $BL['modules'][$module];
	define('MODULE_HREF', 'phpwcms.php?'.get_token_get_string().'&amp;do=modules&amp;module='.$module);

	// listing
	include_once $phpwcms['modules'][$module]['path'].'backend.listing.php';

}
