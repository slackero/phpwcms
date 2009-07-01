<?php
/*************************************************************************************
   Copyright notice
   
   (c) 2002-2009 Oliver Georgi (oliver@phpwcms.de) // All rights reserved.
 
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
if(isset($phpwcms['modules'][$module]['path']) && file_exists($phpwcms['modules'][$module]['path'].'setup/setup.php')) {

	include_once($phpwcms['modules'][$module]['path'].'setup/setup.php');

} elseif(isset($phpwcms['modules'][$module]['path'])) {

	// module default stuff
	
	// put translation back to have easier access to it - use it as relation
	$BLM = & $BL['modules'][$module];
	define('GLOSSARY_HREF', 'phpwcms.php?do=modules&amp;module='.$module);
	$glossary = array();


	if(isset($_GET['edit'])) {
	
		// handle posts and read data
		include_once($phpwcms['modules'][$module]['path'].'inc/processing.inc.php');
	
		// edit form
		include_once($phpwcms['modules'][$module]['path'].'backend.editform.php');
		
	} elseif(isset($_GET['verify'])) {
	
		// active/inactive
		$sql  = 'UPDATE '.DB_PREPEND.'phpwcms_glossary SET ';
		$sql .= "glossary_status=".(intval($_GET['verify']) ? 1 : 0)." ";
		$sql .= "WHERE glossary_id=".intval($_GET['editid']);
		@_dbQuery($sql, 'UPDATE');
		headerRedirect(decode_entities(GLOSSARY_HREF));
	
	} elseif(isset($_GET['delete'])) {
	
		// delete
		$sql  = 'UPDATE '.DB_PREPEND.'phpwcms_glossary SET ';
		$sql .= "glossary_status=9 WHERE glossary_id=".intval($_GET['delete']);
		@_dbQuery($sql, 'UPDATE');		
		headerRedirect(decode_entities(GLOSSARY_HREF));
	
	} else {
	
		// listing
		include_once($phpwcms['modules'][$module]['path'].'backend.listing.php');
		
	}
	
}

?>