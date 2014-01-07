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


// try

if(isset($_GET['edit'])) {
	$plugin['id']		= intval($_GET['edit']);
} else {
	$plugin['id']		= 0;
}


// process post form
if(isset($_POST['adcampaign_title'])) {

	$plugin['data'] = array(
	
				'adcampaign_id'			=> intval($_POST['adcampaign_id']),
				'adcampaign_title'		=> clean_slweg($_POST['adcampaign_title']),
				'adcampaign_created'	=> date('Y-m-d H:i:s'),
				'adcampaign_changed'	=> date('Y-m-d H:i:s'),
				'adcampaign_comment'	=> clean_slweg($_POST['adcampaign_comment']),
				'adcampaign_data'		=> array(
				
					'max_views'			=> intval($_POST['adcampaign_max_views']),
					'max_click'			=> intval($_POST['adcampaign_max_click']),
					'width'				=> intval($_POST['adcampaign_width']),
					'height'			=> intval($_POST['adcampaign_height']),
					'unique'			=> empty($_POST['adcampaign_unique']) ? 0 : 1,
					'url'				=> clean_slweg($_POST['adcampaign_url']),
					'target'			=> clean_slweg($_POST['adcampaign_target'])
				
				),
				'adcampaign_status'		=> empty($_POST['adcampaign_status']) ? 0 : 1,
				'adcampaign_date_start'	=> clean_slweg($_POST['adcampaign_date_start']),
				'adcampaign_date_end'	=> clean_slweg($_POST['adcampaign_date_end']),
				'adcampaign_time_start'	=> clean_slweg($_POST['adcampaign_time_start']),
				'adcampaign_time_end'	=> clean_slweg($_POST['adcampaign_time_end']),
				'adcampaign_format'		=> intval($_POST['adcampaign_format'])
								);
								
								
	if(empty($plugin['data']['adcampaign_title'])) {
	
		$plugin['error']['adcampaign_title'] = 1;
	
	}
	
	$plugin['error'] = 1;
	
	
	if(false && !isset($plugin['error'])) {
	
		if($plugin['data']['glossary_id']) {
		
			// UPDATE
			$sql  = 'UPDATE '.DB_PREPEND.'phpwcms_glossary SET ';
			
			$sql .= "glossary_title='".aporeplace($plugin['data']['glossary_title'])."', ";
			$sql .= "glossary_tag='".aporeplace($plugin['data']['glossary_tag'])."', ";
			$sql .= "glossary_keyword='".aporeplace($plugin['data']['glossary_keyword'])."', ";
			$sql .= "glossary_text='".aporeplace($plugin['data']['glossary_text'])."', ";
			$sql .= "glossary_object='".aporeplace(serialize($plugin['data']['glossary_object']))."', ";
			$sql .= "glossary_changed='".aporeplace($plugin['data']['glossary_changed'])."', ";
			$sql .= "glossary_status=".$plugin['data']['glossary_status'].", ";
			$sql .= "glossary_highlight=".$plugin['data']['glossary_highlight']." ";
			
			$sql .= "WHERE glossary_id=".$plugin['data']['glossary_id'];
			
			if(@_dbQuery($sql, 'UPDATE')) {
			
				if(isset($_POST['save'])) {
					
					headerRedirect(decode_entities(MODULE_HREF));
					
				}
			
			} else {
			
				$plugin['error']['update'] = mysql_error();
			
			}
			
		
		} else {
		
			// INSERT
			$sql  = 'INSERT INTO '.DB_PREPEND.'phpwcms_glossary (';
			$sql .= 'glossary_created, glossary_changed, glossary_title, glossary_tag, ';
			$sql .= 'glossary_keyword, glossary_text, glossary_highlight, glossary_object, glossary_status';		
			$sql .= ') VALUES (';
			$sql .= "'".aporeplace($plugin['data']['glossary_created'])."', ";
			$sql .= "'".aporeplace($plugin['data']['glossary_changed'])."', ";
			$sql .= "'".aporeplace($plugin['data']['glossary_title'])."', ";
			$sql .= "'".aporeplace($plugin['data']['glossary_tag'])."', ";
			$sql .= "'".aporeplace($plugin['data']['glossary_keyword'])."', ";
			$sql .= "'".aporeplace($plugin['data']['glossary_text'])."', ";
			$sql .= aporeplace($plugin['data']['glossary_highlight']).', ';
			$sql .= "'".aporeplace(serialize($plugin['data']['glossary_object']))."', ";
			$sql .= aporeplace($plugin['data']['glossary_status']);
			$sql .= ')';
			
			if(@_dbQuery($sql, 'INSERT')) {
			
				if(isset($_POST['save'])) {
					
					headerRedirect(decode_entities(MODULE_HREF));
					
				}
			
			} else {
			
				$plugin['error']['update'] = mysql_error();
			
			}
		
		
		}
	}

}

// try to read entry from database
if($plugin['id'] && !isset($plugin['error'])) {

	$sql  = 'SELECT *,';
	$sql .= "DATE_FORMAT(adcampaign_datestart, '%d".$BLM['date_delimiter']."%m".$BLM['date_delimiter']."%Y') AS adcampaign_date_start, ";
	$sql .= "DATE_FORMAT(adcampaign_dateend,   '%d".$BLM['date_delimiter']."%m".$BLM['date_delimiter']."%Y') AS adcampaign_date_end, ";
	$sql .= "DATE_FORMAT(adcampaign_datestart, '%H:%i') AS adcampaign_time_start, ";
	$sql .= "DATE_FORMAT(adcampaign_dateend,   '%H:%i') AS adcampaign_time_end ";
	$sql .= 'FROM '.DB_PREPEND.'phpwcms_ads_campaign WHERE adcampaign_id='.$plugin['id'];
	$plugin['data'] = _dbQuery($sql);
	$plugin['data'] = $plugin['data'][0];
	$plugin['data']['adcampaign_data'] = @unserialize($plugin['data']['adcampaign_data']);
	if(!is_array($plugin['data']['adcampaign_data'])) {
		$plugin['data']['adcampaign_data'] = array(
				
					'max_views'			=> 0,
					'max_click'			=> 0,
					'unique'			=> 0,
					'width'				=> '',
					'height'			=> '',
					'url'				=> '',
					'target'			=> ''
				
				);
	}
	
}

// default values
if(empty($plugin['data'])) {

	$plugin['data'] = array(
	
				'adcampaign_id'			=> 0,
				'adcampaign_title'		=> '',
				'adcampaign_created'	=> '',
				'adcampaign_changed'	=> date('Y-m-d H:i:s'),
				'adcampaign_comment'	=> '',
				'adcampaign_data'		=> array(
				
					'max_views'			=> 0,
					'max_click'			=> 0,
					'unique'			=> 0,
					'width'				=> '',
					'height'			=> '',
					'url'				=> '',
					'target'			=> ''
				
				),
				'adcampaign_status'		=> 0,
				'adcampaign_date_start'	=> '',
				'adcampaign_date_end'	=> '',
				'adcampaign_time_start'	=> '00:00',
				'adcampaign_time_end'	=> '23:59',
				'adcampaign_format'		=> 0
	
								);

}



?>