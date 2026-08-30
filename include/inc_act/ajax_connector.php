<?php
/**
 * phpwcms
 *
 * @author Oliver Georgi <og@phpwcms.org>
 * @copyright Copyright (c) 2002-2026, Oliver Georgi
 * @license http://opensource.org/licenses/GPL-2.0 GNU GPL-2
 *
 **/

// general wrapper for ajax based queries

$phpwcms = array('SESSION_START' => true);

require '../config/conf.inc.php';
require '../inc_lib/default.inc.php';
require_once PHPWCMS_ROOT.'/include/inc_lib/helper.session.php';
require PHPWCMS_ROOT.'/include/inc_lib/dbcon.inc.php';
require PHPWCMS_ROOT.'/include/inc_lib/general.inc.php';
require PHPWCMS_ROOT.'/include/inc_lib/backend.functions.inc.php';

if(empty($_SESSION['wcs_user']) || empty($_SESSION['PHPWCMS_BROWSER_HASH']) || $_SESSION['PHPWCMS_BROWSER_HASH'] !== $GLOBALS['phpwcms']['USER_AGENT']['hash']) {
	headerRedirect('', 401);
	die('Sorry, access forbidden');
}

if(isset($_POST['action'])) {
    $action		= $_POST['action'];
    $method		= $_POST['method'] ?? 'json';
    $value		= isset($_POST['value']) ? clean_slweg($_POST['value'], 0, false) : '';
    $jquery		= false;
} elseif($_GET['action']) {
    $action		= $_GET['action'];
    $method		= $_GET['method'] ?? 'json';
    $value		= isset($_GET['value']) ? clean_slweg($_GET['value'], 0, false) : '';
    $jquery		= true;
} else {
    $method     = 'json';
    $action     = 'empty';
    $jquery     = false;
    $value      = '';
}

if(empty($value)) {
    $action = 'empty';
}

// do charset conversions for value
if(PHPWCMS_CHARSET !== 'utf-8') {
    $value = mb_convert_encoding( $value, PHPWCMS_CHARSET, 'utf-8' );
}

$data = array();

switch($action) {

	case 'category':
		$where  = "cat_status=1 AND cat_type NOT IN('module_shop') AND ";
		$where .= 'cat_name LIKE ' . _dbEscapeLike(preg_replace('/[^\w\-\/]/u', '', $value));
		$result = _dbGet('phpwcms_categories', 'cat_name', $where, 'cat_name', 'cat_name', 20);

		if(isset($result[0])) {
			foreach($result as $row) {
				$val = (PHPWCMS_CHARSET !== 'utf-8') ? mb_convert_encoding($row['cat_name'], 'UTF-8', PHPWCMS_CHARSET) : $row['cat_name'];
				$data[] = $jquery ? array('cat_name' => $val) : $val;
			}
		}
		break;

	case 'newstags':
		$where  = "cat_status=1 AND cat_type='news' AND ";
		$where .= "SUBSTRING(cat_name, 1, 5) != '*CSS-' AND ";
		$where .= 'cat_name LIKE ' . _dbEscapeLike(preg_replace('/[^\w\-\/]/u', '', $value));
		$result = _dbGet('phpwcms_categories', 'cat_name', $where, 'cat_name', 'cat_name', 20);

		if(isset($result[0])) {
			foreach($result as $row) {
				$val = (PHPWCMS_CHARSET !== 'utf-8') ? mb_convert_encoding($row['cat_name'], 'UTF-8', PHPWCMS_CHARSET) : $row['cat_name'];
				$data[] = $jquery ? array('cat_name' => $val) : $val;
			}
		}
		break;

	case 'lang':
		$data1 = is_array($phpwcms['allowed_lang']) && count($phpwcms['allowed_lang']) ? $phpwcms['allowed_lang'] : array($phpwcms['default_lang']);
		sort($data1);
		foreach($data1 as $value) {
			$data[]['allowed_lang'] = $value;
		}
		break;

	case 'flush_image_cache':
		if (!has_admin_permission('adm')) {
			headerRedirect('', 401);
			die();
		}
		$files = returnFileListAsArray(PHPWCMS_ROOT.'/'.PHPWCMS_IMAGES, array('jpg', 'png', 'gif', 'svg', 'webp'));
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

  //deleting article ajax
  case 'atitle':
    $where  = "article_deleted=0 AND ";
    $where .= 'article_title LIKE ' . _dbEscapeLike($value);
    $result = _dbGet('phpwcms_article', 'article_title', $where, 'article_title', 'article_title', 20);

    if(isset($result[0])) {
      foreach($result as $key => $row) {
        $title = (PHPWCMS_CHARSET !== 'utf-8') ? mb_convert_encoding($row['article_title'], 'UTF-8', PHPWCMS_CHARSET) : $row['article_title'];
        $data[] = array('article_title' => $title);
      }
    }
    break;
  //end

	case 'set_theme':
		$theme = in_array($value, array('light', 'dark', 'auto'), true) ? $value : 'auto';
		$_SESSION['wcs_user_theme'] = $theme;
		set_theme_cookie($theme);
		if (!empty($_SESSION['wcs_user_id'])) {
			$user_data = _dbGet('phpwcms_user', 'usr_vars', 'usr_id=' . intval($_SESSION['wcs_user_id']) . ' LIMIT 1');
			if (!empty($user_data[0])) {
				$uv = @unserialize($user_data[0]['usr_vars'], array('allowed_classes' => false));
				if (!is_array($uv)) {
					$uv = array();
				}
				$uv['theme'] = $theme;
				_dbUpdate('phpwcms_user', array('usr_vars' => serialize($uv)), 'WHERE usr_id=' . intval($_SESSION['wcs_user_id']));
			}
		}
		$data = array('status' => 'ok', 'theme' => $theme);
		break;
}

if($method === 'json') {

    header('Content-type: application/json');
    echo json_encode($data);

}
