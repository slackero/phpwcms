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


// make all necessary module checks

$phpwcms_modules	= returnSubdirListAsArray(PHPWCMS_ROOT.'/include/inc_module');
$value				= '';

foreach($phpwcms_modules as $value) {

	// set default vars
	$_module_name 			= '';
	$_module_type 			= 0;
	$_module_contentpart	= false;
	$_module_fe_render		= false;
	$_module_fe_init		= false;
	$_module_fe_search		= false;
	$_module_fe_setting		= false;

	if(is_file(PHPWCMS_ROOT.'/include/inc_module/'.$value.'/module.default.php')) {

		// main settings
		require PHPWCMS_ROOT.'/include/inc_module/'.$value.'/module.default.php';

		// define as module - use default name
		if($_module_name !== '') {

			$phpwcms['modules'][$_module_name]				= array();
			$phpwcms['modules'][$_module_name]['name']		= $_module_name;
			$phpwcms['modules'][$_module_name]['type']		= $_module_type;
			$phpwcms['modules'][$_module_name]['cntp']		= $_module_contentpart;
			$phpwcms['modules'][$_module_name]['path']		= PHPWCMS_ROOT.'/include/inc_module/'.$value.'/';
			$phpwcms['modules'][$_module_name]['dir']		= 'include/inc_module/'.$value.'/';
			$phpwcms['modules'][$_module_name]['search']	= $_module_fe_search;
			$phpwcms['modules'][$_module_name]['setting']	= $_module_fe_setting;

			// main module language include -> english is always neccessary
			// but not necessary in frontend
			if(!isset($IS_A_BOT)) {

				if(is_file($phpwcms['modules'][$_module_name]['path'].'lang/en.lang.php')) {

					$BLM = array();
					include_once $phpwcms['modules'][$_module_name]['path'].'lang/en.lang.php';

					// try to find right language - will be merged with default english
					if(is_file($phpwcms['modules'][$_module_name]['path'].'lang/'.$BE['LANG'].'.lang.php')) {
						include_once $phpwcms['modules'][$_module_name]['path'].'lang/'.$BE['LANG'].'.lang.php';
					}

					// put mdule language setting into global language array
					$BL['modules'][$_module_name] = $BLM;

				} else {

					unset($phpwcms['modules'][$_module_name]);

				}
			}

			if($_module_fe_render && is_file(PHPWCMS_ROOT.'/include/inc_module/'.$value.'/frontend.render.php')) {
				$phpwcms['modules_fe_render'][]	= PHPWCMS_ROOT.'/include/inc_module/'.$value.'/frontend.render.php';
			}
			if($_module_fe_init && is_file(PHPWCMS_ROOT.'/include/inc_module/'.$value.'/frontend.init.php')) {
				$phpwcms['modules_fe_init'][]	= PHPWCMS_ROOT.'/include/inc_module/'.$value.'/frontend.init.php';
			}

		}

	}

}

unset($phpwcms_modules, $BLM);
