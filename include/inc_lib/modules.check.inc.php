<?php
/*************************************************************************************
   Copyright notice
   
   (c) 2002-2012 Oliver Georgi <oliver@phpwcms.de> // All rights reserved.
 
   This script is part of PHPWCMS. The PHPWCMS web content management system is
   free software; you can redistribute it and/or modify it under the terms of
   the GNU General Public License as published by the Free Software Foundation;
   either version 2 of the License, or (at your option) any later version.
  
   The GNU General Public License can be found at http://www.gnu.org/copyleft/gpl.html
   A copy is found in the textfile GPL.txt and important notices to the license 
   from the author is found in LICENSE.txt distributed with these scripts.
  
   This script is distributed in the hope that it will be useful, but WITHOUT ANY 
   WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A
   PARTICULAR PURPOSE.  See the GNU General Public License for more details.
 
   This copyright notice MUST APPEAR in all copies of the script!
*************************************************************************************/

// ----------------------------------------------------------------
// obligate check for phpwcms constants
if (!defined('PHPWCMS_ROOT')) {
   die("You Cannot Access This Script Directly, Have a Nice Day.");
}
// ----------------------------------------------------------------


// make all neccessary module checks

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

	if(is_file(PHPWCMS_ROOT.'/include/inc_module/'.$value.'/module.default.php')) {
	
		// main settings
		require(PHPWCMS_ROOT.'/include/inc_module/'.$value.'/module.default.php');
		
		// define as module - use default name
		if($_module_name !== '') {
		
			$phpwcms['modules'][$_module_name]				= array();
			$phpwcms['modules'][$_module_name]['name']		= $_module_name;
			$phpwcms['modules'][$_module_name]['type']		= $_module_type;
			$phpwcms['modules'][$_module_name]['cntp']		= $_module_contentpart;
			$phpwcms['modules'][$_module_name]['path']		= PHPWCMS_ROOT.'/include/inc_module/'.$value.'/';
			$phpwcms['modules'][$_module_name]['dir']		= 'include/inc_module/'.$value.'/';
			$phpwcms['modules'][$_module_name]['search']	= empty($_module_fe_search) ? false : true;
			
			// main module language include -> english is always neccessary
			// but not necessary in frontend
			if(!isset($IS_A_BOT)) {
			
				if(is_file($phpwcms['modules'][$_module_name]['path'].'lang/en.lang.php')) {
				
					$BLM = array();
					include_once($phpwcms['modules'][$_module_name]['path'].'lang/en.lang.php');
				
					// try to find right language - will be merged with default english
					if(is_file($phpwcms['modules'][$_module_name]['path'].'lang/'.$BE['LANG'].'.lang.php')) {
						include_once($phpwcms['modules'][$_module_name]['path'].'lang/'.$BE['LANG'].'.lang.php');
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

?>