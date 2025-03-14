<?php
/**
 * phpwcms content management system
 *
 * @author Oliver Georgi <og@phpwcms.org>
 * @copyright Copyright (c) 2002-2025, Oliver Georgi
 * @license http://opensource.org/licenses/GPL-2.0 GNU GPL-2
 * @link http://www.phpwcms.org
 *
 **/

// Module/Plug-in Glossary

// register module name
//DO NOT USE SPECIAL CHARS HERE, NO WHITE SPACES, USE LOWER CASE!!!
$_module_name 			= 'glossary';

// module type - defines where used
// 0 = BE and FE, 1 = BE only, 2 = FE only
$_module_type 			= 0;

// Set if it should be listed as content part
// has content part: true or false
$_module_contentpart	= true;

// simple switch to allow fe render or fe init
$_module_fe_render		= true;
$_module_fe_init		= false;
$_module_fe_search		= true;
$_module_fe_setting		= false;
