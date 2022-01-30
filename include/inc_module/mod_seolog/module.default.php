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

// Module/Plug-in SEO Log

// register module name
//DO NOT USE SPECIAL CHARS HERE, NO WHITE SPACES, USE LOWER CASE!!!
$_module_name 			= 'seo_log';

// module type - defines where used
// 0 = BE and FE, 1 = BE only, 2 = FE only
$_module_type 			= 1;

// Set if it should be listed as content part
// has content part: true or false
$_module_contentpart	= false;

// simple switch to allow fe render or fe init
$_module_fe_render		= false;
$_module_fe_init		= false;
$_module_fe_search		= false;
$_module_fe_setting		= false;
