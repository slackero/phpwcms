<?php
/*************************************************************************************
   Copyright notice
   
   (c) 2002-2011 Oliver Georgi (oliver@phpwcms.de) // All rights reserved.
 
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
if (!defined('PHPWCMS_ROOT')) die("You Cannot Access This Script Directly, Have a Nice Day.");
// ----------------------------------------------------------------


/*
 * Module/Plug-in Feed to Article import
 * =====================================
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
	$BLM =& $BL['modules'][$module];
	define('MODULE_HREF', 'phpwcms.php?do=modules&amp;module='.$module);
	define('MODULE_HREF_DECODE', PHPWCMS_URL . 'phpwcms.php?do=modules&module='.$module);
	define('MODULE_KEY', 'feedimport');

	require_once($phpwcms['modules'][$module]['path'].'inc/functions.inc.php');

	if(isset($_GET['edit'])) {
		
		include_once(PHPWCMS_ROOT.'/include/inc_lib/article.functions.inc.php'); //load article funtions
	
		// handle posts and read data
		include_once($phpwcms['modules'][$module]['path'].'inc/processing.inc.php');
	
		// edit form
		include_once($phpwcms['modules'][$module]['path'].'backend.editform.php');
		
	} elseif(isset($_GET['active']) && !empty($_GET['editid'])) {
	
		// active/inactive
		$data = array(
			'cnt_changed'	=> now(),
			'cnt_status'	=> empty($_GET['active']) ? 0 : 1
		);
		_dbUpdate('phpwcms_content', $data, 'cnt_id='.intval($_GET['editid']).' AND cnt_module='._dbEscape(MODULE_KEY));
		headerRedirect(MODULE_HREF_DECODE);
	
	} elseif(!empty($_GET['delete'])) {
	
		// delete
		$data = array(
			'cnt_changed'	=> now(),
			'cnt_status'	=> 9
		);
		_dbUpdate('phpwcms_content', $data, 'cnt_id='.intval($_GET['delete']).' AND cnt_module='._dbEscape(MODULE_KEY));
		headerRedirect(MODULE_HREF_DECODE);
	
	} else {
	
		// listing
		include_once($phpwcms['modules'][$module]['path'].'backend.listing.php');
		
	}
	
}

?>