<?php
/**
 * phpwcms content management system
 *
 * @author Oliver Georgi <oliver@phpwcms.org>
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

/*
 * Module/Plug-in Shop & Products Extended
 * =======================================
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
if(isset($phpwcms['modules'][$module]['path'])) {

    // Proof existence of necessary fields only once per Session
    if(empty($_SESSION['shop_db_proof'])) {
        $result = _dbQuery("SHOW TABLES LIKE '".DB_PREPEND."phpwcms_shop_products'");
        if(!empty($result)) {
            $result = _dbQuery("SHOW COLUMNS FROM ".DB_PREPEND."phpwcms_shop_products LIKE 'shopprod_special_price'");
            if(!isset($result[0])) {
                $result = _dbQuery("ALTER TABLE ".DB_PREPEND."phpwcms_shop_products ADD shopprod_special_price TEXT NOT NULL", 'ALTER');
            }
            $result = _dbQuery("SHOW COLUMNS FROM ".DB_PREPEND."phpwcms_shop_products LIKE 'shopprod_track_view'");
            if(!isset($result[0])) {
                $result = _dbQuery("ALTER TABLE ".DB_PREPEND."phpwcms_shop_products ADD shopprod_track_view INT(11) NOT NULL DEFAULT '0', ADD INDEX (shopprod_track_view)", 'ALTER');
            }
            $result = _dbQuery("SHOW COLUMNS FROM ".DB_PREPEND."phpwcms_shop_products LIKE 'shopprod_lang'");
            if(!isset($result[0])) {
                $result = _dbQuery("ALTER TABLE ".DB_PREPEND."phpwcms_shop_products ADD shopprod_lang VARCHAR(255) NOT NULL DEFAULT '', ADD INDEX (shopprod_lang)", 'ALTER');
            }
            $result = _dbQuery("SHOW COLUMNS FROM ".DB_PREPEND."phpwcms_shop_products LIKE 'shopprod_overwrite_meta'");
            if(!isset($result[0])) {
                $result = _dbQuery("ALTER TABLE ".DB_PREPEND."phpwcms_shop_products ADD shopprod_overwrite_meta INT(1) NOT NULL DEFAULT '1'", 'ALTER');
            }
            $result = _dbQuery("SHOW COLUMNS FROM `".DB_PREPEND."phpwcms_shop_products` WHERE Field='shopprod_opengraph'");
            if(!isset($result[0])) {
                $insert = _dbQuery("ALTER TABLE `".DB_PREPEND."phpwcms_shop_products` ADD `shopprod_opengraph` INT(1) UNSIGNED NOT NULL DEFAULT '1', ADD INDEX (`shopprod_opengraph`)", 'ALTER');
            }
        }
        $_SESSION['shop_db_proof'] = true;
    }

    // module default stuff
    $plugin = array();
    define('MODULE_HREF', 'phpwcms.php?do=modules&amp;module='.$module);
    include_once $phpwcms['modules'][$module]['path'].'inc/functions.backend.inc.php';
    include_once $phpwcms['modules'][$module]['path'].'inc/functions.global.inc.php';

    define('SHOP_FELANG_SUPPORT', _getConfig( 'shop_pref_felang' ) ? true : false);

    // put translation back to have easier access to it - use it as relation
    $BLM = & $BL['modules'][$module];

    // load special backend CSS
    $BE['HEADER']['module.shop.css'] = '	<link href="'.$phpwcms['modules'][$module]['dir'].'template/module.shop.css" rel="stylesheet" type="text/css" />';

    $controller	= empty($_GET['controller']) ? 'order' : strtolower($_GET['controller']);

    if(isset($_GET['edit'])) {
        $action	= 'edit';
    } elseif(isset($_GET['status'])) {
        $action	= 'status';
    } elseif(isset($_GET['delete'])) {
        $action	= 'delete';
    } elseif(isset($_GET['show'])) {
        $action	= 'show';
    } else {
        $action		= '';
    }

    switch($controller) {

        case 'prod':	$controller	= 'products';
                        break;

        case 'cat':		$controller	= 'categories';
                        break;

        case 'pref':	$controller	= 'preferences';
                        $action		= 'edit';
                        break;

        case 'order':	$controller	= 'orders';
                        break;

        case 'default':	$controller	= 'default';
                        break;

        default:		$controller	= 'orders';

                        // some defaults - unset session vars
                        unset($_SESSION['detail_page'], $_SESSION['list_active'], $_SESSION['list_inactive'], $_SESSION['filter']);

    }

    // processing
    if( $action ) {
        include_once $phpwcms['modules'][$module]['path'].'inc/processing.' . $controller . '.inc.php';
    }

    // header
    include_once $phpwcms['modules'][$module]['path'].'inc/tabs.inc.php';

    // listing
    if($action) {
        include_once $phpwcms['modules'][$module]['path'].'inc/'.$action.'.' . $controller . '.inc.php';
    } else {
        include_once $phpwcms['modules'][$module]['path'].'inc/listing.' . $controller . '.inc.php';
    }

}
