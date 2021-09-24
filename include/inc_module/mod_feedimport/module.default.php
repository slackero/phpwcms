<?php
/**
 * cmsGO!
 *
 * @author Pixels & Points GmbH <info@pixels-points.ch>
 * @copyright Copyright (c) 2002-2021, Pixels & Points GmbH
 * @license https://www.pixels-points.ch/cmsgo-license.html Pixels & Points cmsGO! license
 *
 **/

// Module/Plug-in Feed to Article import default settings

// register module name
//DO NOT USE SPECIAL CHARS HERE, NO WHITE SPACES, USE LOWER CASE!!!
$_module_name 			= 'feedimport';

// module type - defines where used
// 0 = BE and FE, 1 = BE only, 2 = FE only
$_module_type 			= 1;

// Set if it should be listed as content part
// has content part: true or false
$_module_contentpart	= false;

// simple switch to allow fe render or fe init
$_module_fe_render		= false;
$_module_fe_init		= true;
$_module_fe_search		= false;
$_module_fe_setting		= false;
