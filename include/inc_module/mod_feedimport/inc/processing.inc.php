<?php
// ----------------------------------------------------------------
// obligate check for phpwcms constants
if (!defined('PHPWCMS_ROOT')) {
	die("You Cannot Access This Script Directly, Have a Nice Day.");
}
// ----------------------------------------------------------------

// set fields SBW gas rates
$plugin['fields'] = array(
	'cnt_name'								=> 'STRING', // The feed source name
	'cnt_text'								=> 'STRING', // Feed source URL
	'cnt_object-structure_level_id'			=> 'SELECT',
	'cnt_object-article_template_detail'	=> 'SELECT',
	'cnt_object-article_template_list'		=> 'SELECT',
	'cnt_object-image_folder_id'			=> 'SELECT',
	'cnt_object-image_url_replace'			=> 'STRING',
	'cnt_object-feed_cache'					=> 'CHECK',
	'cnt_object-feed_cache_timeout'			=> 'SELECT',
	'cnt_object-activate_after_import'		=> 'CHECK',
	'cnt_object-author_id'					=> 'SELECT',
	'cnt_object-author_name'				=> 'STRING',
	'cnt_object-source_link_add'			=> 'CHECK',
	'cnt_object-source_link_text'			=> 'STRING',
	'cnt_object-import_status_email'		=> 'STRING',
	'cnt_status'							=> 'CHECK', // Inactive = 0, Active = 1, Deleted = 9
	'cnt_prio'								=> 'HIDDEN' // put in interval timeout here if enabled
);


$plugin['id']											= isset($_GET['edit']) ? intval($_GET['edit']) : 0;
$plugin['fields_cnt_object-structure_level_id']			= array('-2' => $BLM['cnt_object-structure_empty'], '-1' => ' ', 0 => $BL['be_admin_struct_index']) + struct_select_menu(0, 0, 0, 'array');
$plugin['fields_cnt_object-article_template_detail']	= feedimport_article_templates(PHPWCMS_TEMPLATE.'inc_cntpart/articlesummary/article');
$plugin['fields_cnt_object-article_template_list']		= feedimport_article_templates(PHPWCMS_TEMPLATE.'inc_cntpart/articlesummary/list');
$plugin['fields_cnt_object-author_id']					= feedimport_article_authors();
$plugin['fields_cnt_object-image_folder_id']			= feedimport_filestorage_dirlist();
$plugin['fields_cnt_object-feed_cache_timeout']			= array(
	60 => '1 '.$BL['be_date_minute'],
	300 => '5 '.$BL['be_date_minutes'],
	900 => '15 '.$BL['be_date_minutes'],
	1800 => '30 '.$BL['be_date_minutes'],
	3600 => '1 '.$BL['be_date_hour'],
	14400 => '4 '.$BL['be_date_hours'] . ' (' . $BL['be_admin_tmpl_default'] . ')',
	43200 => '12 '.$BL['be_date_hours'],
	86400 => '1 '.$BL['be_date_day'],
	172800 => '2 '.$BL['be_date_days'],
	604800 => '1 '.$BL['be_date_week'],
	1209600 => '2 '.$BL['be_date_weeks'],
	2592000 => '1 '.$BL['be_date_month']
);

// process post form
if(isset($_POST['cnt_name'])) {

	$plugin['data'] = array(
		'id'	=> intval($_POST['id']),
		'ref'	=> MODULE_KEY
	);

	foreach($plugin['fields'] as $key => $value) {

		switch($value) {

			case 'RADIO':
			case 'HIDDEN':
			case 'TEXTAREA':
			case 'SELECT':
			case 'STRING':		$plugin['data'][$key] = isset($_POST[$key]) ? clean_slweg($_POST[$key]) : '';
								break;

			case 'CHECK':		$plugin['data'][$key] = empty($_POST[$key]) ? 0 : 1;
								break;

			case 'FLOAT':		$plugin['data'][$key] = empty($_POST[$key]) ? 0 : floatval($_POST[$key]);
								break;

			case 'MULTICHECK':
			case 'MULTISELECT':	$plugin['data'][$key] = isset($_POST[$key]) && is_array($_POST[$key]) && count($_POST[$key]) ? ' '.implode(' , ', $_POST[$key]).' ' : '';
								break;

			case 'FILE':		$plugin['file_'.$key] =  empty($_POST['file_'.$key]) ? '' : clean_slweg($_POST['file_'.$key]);
			case 'HIDDENINT':
			case 'DATESELECT':
			case 'INT':
								$plugin['data'][$key] = empty($_POST[$key]) ? 0 : intval($_POST[$key]);
								break;

			case 'DECIMAL':		$plugin['data'][$key] = empty($_POST[$key]) ? 0 : clean_preformatted_number($_POST[$key], 'DEC2MET');
								break;

		}

	}

	if(empty($plugin['data']['cnt_name'])) {
		$plugin['error']['cnt_name'] = $BLM['error_name'];
	}
	if(empty($plugin['data']['cnt_text'])) {
		$plugin['error']['cnt_text'] = $BLM['error_url'];
	} elseif(!is_feed_available($plugin['data']['cnt_text'])) {
		$plugin['error']['cnt_text'] = $BLM['error_url_notvalid'];
	}
	if($plugin['data']['cnt_object-structure_level_id'] == '-2' || $plugin['data']['cnt_object-structure_level_id'] == '-1') {
		$plugin['error']['cnt_structure_level_id'] = $BLM['error_structure_level_id'];
	}

	$plugin['data']['cnt_prio'] = $plugin['data']['cnt_object-feed_cache'] ? $plugin['data']['cnt_object-feed_cache_timeout'] : 0;


	if(!isset($plugin['error'])) {

		$data = array(
			'cnt_module'	=> MODULE_KEY,
			'cnt_changed'	=> now(),
			'cnt_sort'		=> now()
		);
		$data_serialize	= array();

		foreach($plugin['fields'] as $key => $value) {

			if($value == 'DISABLED' || $value == 'TEXT') {
				continue;
			}

			$val = $plugin['data'][$key];
			$key = explode('-', $key, 2);

			if(isset($key[1])) {
				$data[ $key[0] ][ $key[1] ] = $val;
			} else {
				$data[ $key[0] ] = $val;
			}

			if(is_array($data[ $key[0] ]) || is_object($data[ $key[0] ])) {
				$data_serialize[$key[0]] = $key[0];
			}

		}

		if(count($data_serialize)) {
			foreach($data_serialize as $value) {
				$data[$value] = serialize($data[$value]);
			}
		}

		if($plugin['data']['id']) {

			// UPDATE
			$result = _dbUpdate('phpwcms_content', $data, 'cnt_id='.$plugin['data']['id'].' AND cnt_module='._dbEscape(MODULE_KEY));

		} else {

			// INSERT
			$data['cnt_created']	= now();
			$result					= _dbInsert('phpwcms_content', $data);

		}

		// Back to module listing
		if(isset($_POST['save'])) {
			headerRedirect(MODULE_HREF_DECODE);
		}

		// set ID
		if(!empty($result['INSERT_ID'])) {
			$plugin['data']['id']	= $result['INSERT_ID'];
			$plugin['id']			= $result['INSERT_ID'];
		}

	} else {

		set_status_message( implode(LF, $plugin['error']), 'warning' );

	}

}

// try to read entry from database
if($plugin['id'] && !isset($plugin['error'])) {

	$plugin['data'] = _dbGet('phpwcms_content', '*', 'cnt_status!=9 AND cnt_module='._dbEscape(MODULE_KEY).' AND cnt_id='.$plugin['id']);
	if(isset($plugin['data'][0])) {
		$plugin['data'] = $plugin['data'][0];
		$plugin['data']['cnt_object'] = @unserialize($plugin['data']['cnt_object']);
		$plugin['data']['id'] = $plugin['data']['cnt_id'];
		if(count($plugin['data']['cnt_object'])) {
			foreach($plugin['data']['cnt_object'] as $key => $value) {
				$plugin['data']['cnt_object-'.$key] = $value;
			}
		}

		$plugin['fields']['cnt_object-feed_import_trigger_url']	= 'TEXTAREA-DISABLED';
		$plugin['data']['cnt_object-feed_import_trigger_url']	= PHPWCMS_URL . 'index.php?feedimport='.md5($plugin['data']['cnt_id'].$plugin['data']['cnt_text']);


	} else {
		$plugin['data'] = false;
		set_status_message( sprintf($BLM['error_false_id'], $plugin['id']), 'warning' );
		headerRedirect(MODULE_HREF_DECODE);
	}
}

// default values
if(empty($plugin['data'])) {

	$plugin['data'] = array( 'id' => 0 );

	foreach($plugin['fields'] as $key => $value) {

		switch($value) {

			case 'RADIO':
			case 'HIDDEN':
			case 'DATESELECT':
			case 'TEXTAREA':
			case 'SELECT':
			case 'STRING':		$plugin['data'][$key] = '';
								break;

			case 'INT':
			case 'FILE':
			case 'DECIMAL':
			case 'HIDDENINT':
			case 'FLOAT':
			case 'CHECK':		$plugin['data'][$key] = 0;
								break;

			case 'MULTICHECK':
			case 'MULTISELECT':	$plugin['data'][$key] = array();
								break;

		}

	}

}

if(empty($plugin['data']['cnt_object-feed_cache_timeout'])) {
	$plugin['data']['cnt_object-feed_cache_timeout'] = 14400;
}
if(empty($plugin['data']['cnt_object-author_name'])) {
	list($plugin['data']['cnt_object-author_name']) = explode(' (', current($plugin['fields_cnt_object-author_id']));
}
if(empty($plugin['data']['cnt_object-source_link_text'])) {
	list($plugin['data']['cnt_object-source_link_text']) = '@@Source@@';
}
if(empty($plugin['data']['cnt_object-import_status_email'])) {
	list($plugin['data']['cnt_object-import_status_email']) = '';
}
if(empty($plugin['data']['cnt_object-image_folder_id'])) {
	$plugin['data']['cnt_object-image_folder_id'] = 0;
}
if(empty($plugin['data']['cnt_object-image_url_replace'])) {
	$plugin['data']['cnt_object-image_url_replace'] = '';
}
