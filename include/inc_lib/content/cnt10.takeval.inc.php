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

// Content Type Form Email
$content["form"]			= explode("#:#", $row["acontent_form"]);
$content["mailform"]		= base64_decode($content["form"][0]);
$content["mailsubject"]		= $content["form"][1];
$content["mailrecipient"]	= $content["form"][2];
$content["mailbutton"]		= $content["form"][3];
$content["mailhtml"]		= intval($content["form"][4]);
