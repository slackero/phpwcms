<?php
/**
 * cmsGO!
 *
 * @author Pixels & Points GmbH <info@pixels-points.ch>
 * @copyright Copyright (c) 2002-2020, Pixels & Points GmbH
 * @license https://www.pixels-points.ch/cmsgo-license.html Pixels & Points cmsGO! license
 *
 **/

// ----------------------------------------------------------------
// obligate check for cmsgo constants
if (!defined('CMSGO_ROOT')) {
	die("You Cannot Access This Script Directly, Have a Nice Day.");
}
// ----------------------------------------------------------------

// Content Type File List
$content["file_descr"]		= $row["acontent_text"];
$content["file_list"]		= explode(":", $row["acontent_files"]);
$content["file_template"]	= $row["acontent_template"];
$content['file']			= unserialize($row["acontent_form"]);
$content["html"]			= $row["acontent_html"];
