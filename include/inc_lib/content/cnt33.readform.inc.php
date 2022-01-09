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


// News
$content["template"]	= clean_slweg($_POST['template']);

$content['news']						= array();
$content['news']['news_lang']			= empty($_POST['news_lang']) || !is_array($_POST['news_lang']) ? array() : $_POST['news_lang'];
$content['news']['news_category']		= convertStringToArray( clean_slweg($_POST['news_category']) );
$content['news']['news_sort']			= abs(intval($_POST['news_sort']));
$content['news']['news_paginate']		= empty($_POST['news_paginate']) ? 0 : 1;
$content['news']['news_prio']			= empty($_POST['news_prio']) ? 0 : 1;
$content['news']['news_paginate_count']	= empty($_POST['news_paginate_count']) ? '' : abs(intval($_POST['news_paginate_count']));
$content['news']['news_paginate_basis']	= abs(intval($_POST['news_paginate_basis']));
$content['news']['news_limit']			= abs(intval($_POST['news_limit']));
$content['news']['news_skip']			= abs(intval($_POST['news_skip']));
$content['news']['news_archive']		= abs(intval($_POST['news_archive']));
$content['news']['news_andor']			= strtoupper(clean_slweg($_POST['news_andor']));
$content['news']['news_archive_link']	= clean_slweg($_POST['news_archive_link']);
$content['news']['news_detail_link']	= clean_slweg($_POST['news_detail_link']);

if(!count($content['news']['news_lang']) || (isset($content['news']['news_lang'][0]) && $content['news']['news_lang'][0] == '')) {
	$content['news']['news_lang'] = array();
}

if( empty($content['news']['news_sort']) || $content['news']['news_sort'] > 18 ) {
	$content['news']['news_sort'] = 9;
}
if( empty($content['news']['news_paginate_count']) ) {
	$content['news']['news_paginate_count'] = $content['news']['news_paginate'] ? 10 : '';
}
if( $content['news']['news_paginate_basis'] > 4 ) {
	$content['news']['news_paginate_basis'] = 3;
}
if( empty($content['news']['news_limit']) ) {
	$content['news']['news_limit'] = '';
}
if( empty($content['news']['news_skip']) ) {
	$content['news']['news_skip'] = '';
}
if( $content['news']['news_archive'] > 3 ) {
	$content['news']['news_sort'] = 1;
}
if( ! in_array($content['news']['news_andor'], array('OR', 'AND', 'NOT')) ) {
	$content['news']['news_andor'] = 'OR';
}

if(is_intval($content['news']['news_detail_link'])) {
	$content['news']['news_detail_link'] = intval($content['news']['news_detail_link']) ? intval($content['news']['news_detail_link']) : '';
}
if(is_intval($content['news']['news_archive_link'])) {
	$content['news']['news_archive_link'] = intval($content['news']['news_archive_link']) ? intval($content['news']['news_archive_link']) : '';
}
