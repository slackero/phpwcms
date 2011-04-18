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
if (!defined('PHPWCMS_ROOT')) {
   die("You Cannot Access This Script Directly, Have a Nice Day.");
}
// ----------------------------------------------------------------


/*
 * module ads/banner managament
 * ============================
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
	
	// load special backend CSS
	$BE['HEADER']['module_ads.css'] = '	<link href="'.$phpwcms['modules'][$module]['dir'].'template/css/backend.ads.css" rel="stylesheet" type="text/css">';
	
	// put translation back to have easier access to it - use it as relation
	$BLM = & $BL['modules'][$module];
	define('MODULE_HREF', 'phpwcms.php?do=modules&amp;module='.$module);
	$plugin = array();

	// edit campaign
	if(!empty($_GET['campaign'])) {
	
		if(isset($_GET['edit'])) {
		
			// handle posts and read data
			include_once($phpwcms['modules'][$module]['path'].'inc/processing.campaign.inc.php');
		
			// edit campaign form
			include_once($phpwcms['modules'][$module]['path'].'backend.form.campaign.php');
			
		} elseif(isset($_GET['verify'])) {
		
			// active/inactive
			$sql  = 'UPDATE '.DB_PREPEND.'phpwcms_ads_campaign SET ';
			$sql .= "adcampaign_status=".(intval($_GET['verify']) ? 1 : 0)." ";
			$sql .= "WHERE adcampaign_id=".intval($_GET['editid']);
			@_dbQuery($sql, 'UPDATE');
			headerRedirect(decode_entities(MODULE_HREF).'&listcampaign=1');
		
		} elseif(isset($_GET['delete'])) {
		
			$adcampaign_id = intval($_GET['delete']);
		
			// delete
			$sql  = 'UPDATE '.DB_PREPEND.'phpwcms_ads_campaign SET ';
			$sql .= "adcampaign_status=9 WHERE adcampaign_id=".$adcampaign_id;
			@_dbQuery($sql, 'UPDATE');
			
			//rename deleted campaign
			@rename(PHPWCMS_CONTENT.'ads/'.$adcampaign_id, PHPWCMS_CONTENT.'ads/_deleted_'.time().'_'.$adcampaign_id);
			
			headerRedirect(decode_entities(MODULE_HREF).'&listcampaign=1');
			
		} elseif(isset($_GET['duplicate'])) {
			
			@_dbDuplicateRow('phpwcms_ads_campaign', 'adcampaign_id', intval($_GET['duplicate']), 
					array(
				'adcampaign_title'		=> '--SELF-- ('.generic_string(3).')',
				'adcampaign_created'	=> 'SQL:NOW()',
				'adcampaign_changed'	=> 'SQL:NOW()',
				'adcampaign_curview'	=> '0',
				'adcampaign_curclick'	=> '0',
				'adcampaign_curviewuser'=> '0'	));
			headerRedirect(decode_entities(MODULE_HREF).'&listcampaign=1');
		}
	
	
	// edit ad place
	} elseif(!empty($_GET['adplace'])) {
	
		if(isset($_GET['edit'])) {
		
			// handle posts and read data
			include_once($phpwcms['modules'][$module]['path'].'inc/processing.adplace.inc.php');
		
			// edit campaign form
			include_once($phpwcms['modules'][$module]['path'].'backend.form.adplace.php');
			
		} elseif(isset($_GET['verify'])) {
		
			// active/inactive
			$sql  = 'UPDATE '.DB_PREPEND.'phpwcms_ads_place SET ';
			$sql .= "adplace_status=".(intval($_GET['verify']) ? 1 : 0)." ";
			$sql .= "WHERE adplace_id=".intval($_GET['editid']);
			@_dbQuery($sql, 'UPDATE');
			headerRedirect(decode_entities(MODULE_HREF).'&listadplace=1');
		
		} elseif(isset($_GET['delete'])) {
		
			// delete
			$sql  = 'UPDATE '.DB_PREPEND.'phpwcms_ads_place SET ';
			$sql .= "adplace_status=9 WHERE adplace_id=".intval($_GET['delete']);
			@_dbQuery($sql, 'UPDATE');		
			headerRedirect(decode_entities(MODULE_HREF).'&listadplace=1');
		}
	
	
	} else {
	
		// listing
		include_once($phpwcms['modules'][$module]['path'].'backend.listing.php');
		
		if(isset($_GET['listcampaign'])) {
			include_once($phpwcms['modules'][$module]['path'].'inc/listing.campaign.inc.php');
		} elseif(isset($_GET['listadplace'])) {
			include_once($phpwcms['modules'][$module]['path'].'inc/listing.adplace.inc.php');
		} else {
			include_once($phpwcms['modules'][$module]['path'].'inc/listing.summary.inc.php');
		}
		
	}
	
}

?>