<?php
/**
 * cmsGO!
 *
 * @author Pixels & Points GmbH <info@pixels-points.ch>
 * @copyright Copyright (c) 2002-2022, Pixels & Points GmbH
 * @license https://www.pixels-points.ch/cmsgo-license.html Pixels & Points cmsGO! license
 *
 **/

// general wrapper for ajax based queries

$cmsgo = array('SESSION_START' => true);

require '../config/conf.inc.php';
require '../inc_lib/default.inc.php';
require_once CMSGO_ROOT.'/include/inc_lib/helper.session.php';
require CMSGO_ROOT.'/include/inc_lib/dbcon.inc.php';
require CMSGO_ROOT.'/include/inc_lib/general.inc.php';
require CMSGO_ROOT.'/include/inc_lib/backend.functions.inc.php';

if(empty($_SESSION['wcs_user']) || empty($_SESSION['CMSGO_BROWSER_HASH']) || $_SESSION['CMSGO_BROWSER_HASH'] !== $GLOBALS['cmsgo']['USER_AGENT']['hash']) {
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
if(CMSGO_CHARSET != 'utf-8') {
	$value = @mb_convert_encoding( $value, CMSGO_CHARSET, 'utf-8' );
}

$data = array();

switch($action) {

	case 'category':
		$where  = "cat_status=1 AND cat_type NOT IN('module_shop') AND ";
		$where .= "cat_name LIKE '%" . _dbEscape( preg_replace('/[^\w\- ]/', '', $value), false ) . "%'";
		$result = _dbGet('cmsgo_categories', 'cat_name', $where, 'cat_name', 'cat_name', 20);

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
		$result = _dbGet('cmsgo_categories', 'cat_name', $where, 'cat_name', 'cat_name', 20);

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
		$data1 = is_array($cmsgo['allowed_lang']) && count($cmsgo['allowed_lang']) ? $cmsgo['allowed_lang'] : array($cmsgo['default_lang']);
		sort($data1);
        foreach($data1 as $value) {
            $data[]['allowed_lang'] =  $value;
        }
		break;

	case 'flush_image_cache':
        if (empty($_SESSION['wcs_user_admin'])) {
            headerRedirect('', 401);
            die();
        }
		$files = returnFileListAsArray(CMSGO_ROOT.'/'.CMSGO_IMAGES, array('jpg', 'png', 'gif', 'svg', 'webp'));
		$data = array('file_count' => 0, 'status' => 'ok');
		if(is_array($files)) {
			$data['file_count'] = count($files);
			foreach($files as $file) {
				@unlink(CMSGO_ROOT.'/'.CMSGO_IMAGES.$file['filename']);
			}
		} else {
			$data['status'] = '';
		}
		break;

  //deleting article ajax
  case 'atitle':
    $where  = "article_deleted=0 AND ";
    $where .= "article_title LIKE '%" ._dbEscape( $value, false ) . "%'";
    $result = _dbGet('cmsgo_article', 'article_title', $where, 'article_title', 'article_title', 20);

    if(isset($result[0])) {
      foreach($result as $key => $value) {
        $data[] = array('article_title' => utf8_encode($value['cat_name']));
      }
    }
    break;
  //end
}

if($method === 'json') {

	header('Content-type: application/json');
    echo json_encode($data);

}
