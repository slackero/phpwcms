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

// Module/Plug-in Ads/Banner Management

// register module name
//DO NOT USE SPECIAL CHARS HERE, NO WHITE SPACES, USE LOWER CASE!!!
$_module_name 			= 'ads';

// module type - defines where used
// 0 = BE and FE, 1 = BE only, 2 = FE only
$_module_type 			= 0;

// Set if it should be listed as content part
// has content part: true or false
$_module_contentpart	= false;

$_module_fe_render		= true;
$_module_fe_init		= true;
$_module_fe_search		= false;
$_module_fe_setting		= false;

// Register ADS_DIR constant based on $phpwcms['ads_path']
// mainly used to handle adblocking more flexible
// Fallback to 'ads' - the default value in previous versions
define('PHPWCMS_ADS_DIR', empty($phpwcms['ads_path']) ? 'ads' : $phpwcms['ads_path']);
