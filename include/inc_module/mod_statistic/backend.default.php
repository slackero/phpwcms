<?php
/**
 * phpwcms
 *
 * @author Oliver Georgi <og@phpwcms.org>
 * @copyright Copyright (c) 2002-2026, Oliver Georgi
 * @license http://opensource.org/licenses/GPL-2.0 GNU GPL-2
 *
 **/

// ----------------------------------------------------------------
// obligate check for phpwcms constants
if (!defined('PHPWCMS_ROOT')) {
    die("You Cannot Access This Script Directly, Have a Nice Day.");
}
// ----------------------------------------------------------------

/*
 * module statisics
 * ================
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
define('MODULE_HREF', 'phpwcms.php?do=modules&amp;module='.$module);
include_once($phpwcms['modules'][$module]['path'].'inc/statistic.functions.inc.php');


$BLM = & $BL['modules'][$module];
$controller = empty($_GET['controller']) ? 'overview' : strtolower($_GET['controller']);

switch($controller) {

    case 'overview':  $controller = 'overview';
    break;

    case 'downloads': $controller = 'downloads';
    break;

    case 'polls':   $controller = 'polls';
    break;

    case 'subscriptions': $controller = 'subscriptions';
    break;

    case 'guestbook': $controller = 'guestbook';
    break;
    case 'user':  $controller = 'user';
    break;
    case 'seo': $controller = 'seo';
    break;

    default:    $controller = 'overview';
}
// header
include_once($phpwcms['modules'][$module]['path'].'inc/tabs.inc.php');

// listing
include_once($phpwcms['modules'][$module]['path'].'inc/listing.' . $controller . '.inc.php');
