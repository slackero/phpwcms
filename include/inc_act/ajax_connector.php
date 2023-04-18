<?php
/**
 * phpwcms content management system
 *
 * @author Oliver Georgi <og@phpwcms.org>
 * @copyright Copyright (c) 2002-2023, Oliver Georgi
 * @license http://opensource.org/licenses/GPL-2.0 GNU GPL-2
 * @link http://www.phpwcms.org
 *
 **/

// general wrapper for ajax based queries

$phpwcms = array('SESSION_START' => true);

require_once '../../include/config/conf.inc.php';
require_once '../inc_lib/default.inc.php';
require_once PHPWCMS_ROOT.'/include/inc_lib/helper.session.php';
require_once PHPWCMS_ROOT.'/include/inc_lib/dbcon.inc.php';
require_once PHPWCMS_ROOT.'/include/inc_lib/general.inc.php';
require_once PHPWCMS_ROOT.'/include/inc_lib/backend.functions.inc.php';

if(empty($_SESSION['wcs_user']) || empty($_SESSION['PHPWCMS_BROWSER_HASH']) || $_SESSION['PHPWCMS_BROWSER_HASH'] !== $GLOBALS['phpwcms']['USER_AGENT']['hash']) {
    headerRedirect('', 401);
    die();
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
    $value = mb_convert_encoding($value, PHPWCMS_CHARSET, 'utf-8');
}

$data = array();

switch($action) {

    case 'category':
        $where  = "cat_status=1 AND cat_type NOT IN('module_shop') AND ";
        $where .= "cat_name LIKE '%" . _dbEscape(preg_replace('/[^\w\-\/]/', '', $value), false) . "%'";
        $result = _dbGet('phpwcms_categories', 'cat_name', $where, 'cat_name', 'cat_name', 20);

        if(isset($result[0])) {
            foreach($result as $value) {
                $value = mb_convert_encoding($value['cat_name'], 'UTF-8');
                $data[] = $jquery ? array('cat_name' => $value) : $value;
            }
        }
        break;

    case 'newstags':
        $where  = "cat_status=1 AND cat_type='news' AND ";
        $where .= "SUBSTRING(cat_name, 1, 5) != '*CSS-' AND ";
        $where .= "cat_name LIKE '%" . _dbEscape(preg_replace('/[^\w\-\/]/', '', $value), false) . "%'";
        $result = _dbGet('phpwcms_categories', 'cat_name', $where, 'cat_name', 'cat_name', 20);

        if(isset($result[0])) {
            foreach($result as $value) {
                $value = mb_convert_encoding($value['cat_name'], 'UTF-8');
                $data[] = $jquery ? array('cat_name' => $value) : $value;
            }
        }
        break;

    case 'lang':
        $data = is_array($phpwcms['allowed_lang']) && count($phpwcms['allowed_lang']) ? $phpwcms['allowed_lang'] : array($phpwcms['default_lang']);
        sort($data);
        break;

    case 'flush_image_cache':
        if (empty($_SESSION['wcs_user_admin'])) {
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
}

if($method === 'json') {

    header('Content-type: application/json');
    echo json_encode($data);

}
