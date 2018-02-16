<?php
/**
 * cmsGo!
 *
 * @author Pixels & Points GmbH <info@pixels-points.ch>
 * @copyright Copyright (c) 2002-2017, Pixels & Points GmbH
 * @license https://www.pixels-points.ch/cmsgo-license.html Pixels & Points cmsGo! license
 *
 **/

// ----------------------------------------------------------------
// obligate check for cmsgo constants
if (!defined('CMSGO_ROOT')) {
	die("You Cannot Access This Script Directly, Have a Nice Day.");
}
// ----------------------------------------------------------------

// Content Type redirect
$content["link"]		= clean_slweg($_POST["clink"]);
$content["target"]		= slweg($_POST["ctarget"]);
$content["redirect"]	= $content["link"] . " " . $content["target"];
$content["template"]	= clean_slweg($_POST['template']);
