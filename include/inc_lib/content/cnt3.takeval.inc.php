<?php
/**
 * cmsGO!
 *
 * @author Pixels & Points GmbH <info@pixels-points.ch>
 * @copyright Copyright (c) 2002-2026, Pixels & Points GmbH
 * @license https://www.pixels-points.ch/cmsgo-license.html Pixels & Points cmsGO! license
 *
 **/


// ----------------------------------------------------------------
// obligate check for cmsGO! constants
if (!defined('CMSGO_ROOT')) {
	die("You Cannot Access This Script Directly, Have a Nice Day.");
}
// ----------------------------------------------------------------



// Content Type Link/Email
$content["link"] = explode(" ", $row["acontent_redirect"]);
if(isset($content["link"][1])) {
	$content["target"] = $content["link"][1];
} else {
	$content["target"] = '';
}
$content["link"] 		= $content["link"][0];
$content["template"]	= $row["acontent_template"];
