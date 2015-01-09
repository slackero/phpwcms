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

// ----------------------------------------------------------------
// obligate check for phpwcms constants
if (!defined('PHPWCMS_ROOT')) {
   die("You Cannot Access This Script Directly, Have a Nice Day.");
}
// ----------------------------------------------------------------


/*
 * Module/Plug-in Shop & Products Extended
 * =======================================
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
if(isset($phpwcms['modules'][$module]['path']) && is_file($phpwcms['modules'][$module]['path'].'setup/setup.php')) {

	include_once($phpwcms['modules'][$module]['path'].'setup/setup.php');

} elseif(isset($phpwcms['modules'][$module]['path'])) {

	// module default stuff
	$plugin = array();
	define('MODULE_HREF', 'phpwcms.php?do=modules&amp;module='.$module);
	include_once($phpwcms['modules'][$module]['path'].'inc/shop.functions.inc.php');
	
	define('SHOP_FELANG_SUPPORT', _getConfig( 'shop_pref_felang' ) ? true : false);

	// put translation back to have easier access to it - use it as relation
	$BLM = & $BL['modules'][$module];
	
	// load special backend CSS
	$BE['HEADER']['module.shop.css'] = '	<link href="'.$phpwcms['modules'][$module]['dir'].'template/module.shop.css" rel="stylesheet" type="text/css" />';
	
	$controller	= empty($_GET['controller']) ? 'order' : strtolower($_GET['controller']);
	
	if(isset($_GET['edit'])) {
		$action	= 'edit';
	} elseif(isset($_GET['status'])) {
		$action	= 'status';
	} elseif(isset($_GET['delete'])) {
		$action	= 'delete';
	} elseif(isset($_GET['show'])) {
		$action	= 'show';
	} else {
		$action		= '';
	}
		
	switch($controller) {
	
		case 'prod':	$controller	= 'products';
						break;
		
		case 'cat':		$controller	= 'categories';
						break;
		
		case 'pref':	$controller	= 'preferences';
						$action		= 'edit';
						break;
		
		case 'order':	$controller	= 'orders';
						break;

		case 'default':	$controller	= 'default';
						break;

		default:		$controller	= 'orders';
						
						// some defaults - unset session vars
						unset($_SESSION['detail_page'], $_SESSION['list_active'], $_SESSION['list_inactive'], $_SESSION['filter']);		

	}

	// processing
	if( $action ) {
		include_once($phpwcms['modules'][$module]['path'].'inc/processing.' . $controller . '.inc.php');
	}
	
	// header
	include_once($phpwcms['modules'][$module]['path'].'inc/tabs.inc.php');
	
	// listing
	if($action) {
		include_once($phpwcms['modules'][$module]['path'].'inc/'.$action.'.' . $controller . '.inc.php');
	} else {
		include_once($phpwcms['modules'][$module]['path'].'inc/listing.' . $controller . '.inc.php');
	}
	
}

?>