<?php
/**
 * cmsGO!
 *
 * @author Pixels & Points GmbH <info@pixels-points.ch>
 * @copyright Copyright (c) 2002-2025, Pixels & Points GmbH
 * @license https://www.pixels-points.ch/cmsgo-license.html Pixels & Points cmsGO! license
 *
 **/

// ----------------------------------------------------------------
// obligate check for cmsGO! constants
if (!defined('CMSGO_ROOT')) {
	die("You Cannot Access This Script Directly, Have a Nice Day.");
}
// ----------------------------------------------------------------


/*
 * module ads/banner managament
 * ============================
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
if(isset($cmsgo['modules'][$module]['path'])) {

	// module default stuff

    // Initial check against 'content/%ads_dir%'
    if(@!is_dir(CMSGO_CONTENT.CMSGO_ADS_DIR)) {
        // Check older 'ads' static dir and try to rename
        if(@is_dir(CMSGO_CONTENT.'ads')) {
            @rename( CMSGO_CONTENT.'ads', CMSGO_CONTENT.CMSGO_ADS_DIR);
        }
        // Create new if not existing
        if(_mkdir(CMSGO_CONTENT . CMSGO_ADS_DIR)) {
            if(!is_file(CMSGO_CONTENT . CMSGO_ADS_DIR.'/index.html')) {
                @file_put_contents(CMSGO_CONTENT . CMSGO_ADS_DIR.'/index.html', '<html><head><title></title><meta content="0; url=../" http-equiv="refresh"/></head></html>');
            }
        }
    }

	// load special backend CSS
	$BE['HEADER']['module_ads.css'] = '	<link href="'.$cmsgo['modules'][$module]['dir'].'template/backend.ads.css" rel="stylesheet" type="text/css">';

	// put translation back to have easier access to it - use it as relation
	$BLM = & $BL['modules'][$module];
	define('MODULE_HREF', 'cmsgo.php?'.get_token_get_string().'&amp;do=modules&amp;module='.$module);
	$plugin = array();

	// edit campaign
	if(!empty($_GET['campaign'])) {

		if(isset($_GET['edit'])) {

			// handle posts and read data
			include_once $cmsgo['modules'][$module]['path'].'inc/processing.campaign.inc.php';

			// edit campaign form
			include_once $cmsgo['modules'][$module]['path'].'backend.form.campaign.php';

		} elseif(isset($_GET['verify'])) {

			// active/inactive
			$sql  = 'UPDATE '.DB_PREPEND.'cmsgo_ads_campaign SET ';
			$sql .= "adcampaign_status=".(intval($_GET['verify']) ? 1 : 0)." ";
			$sql .= "WHERE adcampaign_id=".intval($_GET['editid']);
			@_dbQuery($sql, 'UPDATE');
			headerRedirect(decode_entities(MODULE_HREF).'&listcampaign=1');

		} elseif(isset($_GET['delete'])) {

			$adcampaign_id = intval($_GET['delete']);

			// delete
			$sql  = 'UPDATE '.DB_PREPEND.'cmsgo_ads_campaign SET ';
			$sql .= "adcampaign_status=9 WHERE adcampaign_id=".$adcampaign_id;
			@_dbQuery($sql, 'UPDATE');

			//rename deleted campaign
			@rename(CMSGO_CONTENT.CMSGO_ADS_DIR.'/'.$adcampaign_id, CMSGO_CONTENT.CMSGO_ADS_DIR.'/_deleted_'.time().'_'.$adcampaign_id);

			headerRedirect(decode_entities(MODULE_HREF).'&listcampaign=1');

		} elseif(isset($_GET['duplicate'])) {

			@_dbDuplicateRow('cmsgo_ads_campaign', 'adcampaign_id', intval($_GET['duplicate']),
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
			include_once $cmsgo['modules'][$module]['path'].'inc/processing.adplace.inc.php';

			// edit campaign form
			include_once $cmsgo['modules'][$module]['path'].'backend.form.adplace.php';

		} elseif(isset($_GET['verify'])) {

			// active/inactive
			$sql  = 'UPDATE '.DB_PREPEND.'cmsgo_ads_place SET ';
			$sql .= "adplace_status=".(intval($_GET['verify']) ? 1 : 0)." ";
			$sql .= "WHERE adplace_id=".intval($_GET['editid']);
			@_dbQuery($sql, 'UPDATE');
			headerRedirect(decode_entities(MODULE_HREF).'&listadplace=1');

		} elseif(isset($_GET['delete'])) {

			// delete
			$sql  = 'UPDATE '.DB_PREPEND.'cmsgo_ads_place SET ';
			$sql .= "adplace_status=9 WHERE adplace_id=".intval($_GET['delete']);
			@_dbQuery($sql, 'UPDATE');
			headerRedirect(decode_entities(MODULE_HREF).'&listadplace=1');
		}


	} else {

		// listing
		include_once $cmsgo['modules'][$module]['path'].'backend.listing.php';

		if(isset($_GET['listcampaign']) || empty($_GET['listadplace'])) {
			include_once $cmsgo['modules'][$module]['path'].'inc/listing.campaign.inc.php';
		} elseif(isset($_GET['listadplace'])) {
			include_once $cmsgo['modules'][$module]['path'].'inc/listing.adplace.inc.php';
		} else {
			include_once $cmsgo['modules'][$module]['path'].'inc/listing.summary.inc.php';
		}

	}

}
