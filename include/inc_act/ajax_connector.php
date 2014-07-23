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

// general wrapper for ajax based queries

session_start();
$phpwcms = array();
require('../../config/phpwcms/conf.inc.php');
require('../inc_lib/default.inc.php');
require(PHPWCMS_ROOT.'/include/inc_lib/dbcon.inc.php');
require(PHPWCMS_ROOT.'/include/inc_lib/general.inc.php');
require(PHPWCMS_ROOT.'/include/inc_lib/backend.functions.inc.php');

if(empty($_SESSION["wcs_user"])) {
	headerRedirect('', 401);
	die('Sorry, access forbidden');
}

if(isset($_POST['action'])) {
	$action		= isset($_POST['action']) ? $_POST['action'] : false;
	$method		= isset($_POST['method']) ? $_POST['method'] : 'json';
	$value		= isset($_POST['value']) ? clean_slweg($_POST['value'], 0, false) : '';
	$jquery		= false;
} elseif($_GET['action']) {
	$action		= isset($_GET['action']) ? $_GET['action'] : false;
	$method		= isset($_GET['method']) ? $_GET['method'] : 'json';
	$value		= isset($_GET['value']) ? clean_slweg($_GET['value'], 0, false) : '';
	$jquery		= true;
}

if(empty($value)) {
	$action = 'empty';
}

// do charset conversions for value
if(PHPWCMS_CHARSET != 'utf-8') {

	if(function_exists('mb_convert_encoding')) {

		$value = @mb_convert_encoding( $value, PHPWCMS_CHARSET, 'utf-8' );

	} else {

		$value = utf8_decode($value);

	}

}

$data = array();

switch($action) {

	case 'category':
		$where  = "cat_status=1 AND cat_type NOT IN('module_shop') AND ";
		$where .= "cat_name LIKE '%" . _dbEscape( preg_replace('/[^\w\- ]/', '', $value), false ) . "%'";
		$result = _dbGet('phpwcms_categories', 'cat_name', $where, 'cat_name', 'cat_name', 20);

		if(isset($result[0])) {

			if($jquery) {

				$data = $result;

			} else {

				foreach($result as $value) {
					$data[] = utf8_encode($value['cat_name']);
				}

			}
		}
		break;

	case 'newstags':
		$where  = "cat_status=1 AND cat_type='news' AND ";
		$where .= "cat_name LIKE '%" . _dbEscape( preg_replace('/[^\w\- ]/', '', $value), false ) . "%'";
		$result = _dbGet('phpwcms_categories', 'cat_name', $where, 'cat_name', 'cat_name', 20);

		if(isset($result[0])) {

			if($jquery) {

				$data = $result;

			} else {

				foreach($result as $value) {
					$data[] = utf8_encode($value['cat_name']);
				}

			}
		}
		break;

	case 'lang':
		$data = is_array($phpwcms['allowed_lang']) && count($phpwcms['allowed_lang']) ? $phpwcms['allowed_lang'] : array($phpwcms['default_lang']);
		sort($data);
		break;

	case 'flush_image_cache':
		$files = returnFileListAsArray(PHPWCMS_ROOT.'/'.PHPWCMS_IMAGES, array('jpg', 'png', 'gif', 'svg'));
		$data = array('file_count' => 0, 'status' => 'ok');
		if(is_array($files)) {
			$data['file_count'] = count($files);
			foreach($files as $file) {
				@unlink(PHPWCMS_ROOT.'/'.PHPWCMS_IMAGES.$file['filename']);
			}
		} else {
			$data['status'] = '';
		}
		break;
}

if($method == 'json') {

	header('Content-type: application/json');
	if(!function_exists('json_encode')) {

		require(PHPWCMS_ROOT.'/include/inc_ext/JSON.php');
		$json = new Services_JSON();

		echo $json->encode( $data );

	} else {

		echo json_encode( $data );

	}

}

?>