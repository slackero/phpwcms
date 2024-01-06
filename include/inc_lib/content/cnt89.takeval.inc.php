<?php
/**
 * cmsGO!
 *
 * @author Pixels & Points GmbH <info@pixels-points.ch>
 * @copyright Copyright (c) 2002-2024, Pixels & Points GmbH
 * @license https://www.pixels-points.ch/cmsgo-license.html Pixels & Points cmsGO! license
 *
 **/

// ----------------------------------------------------------------
// obligate check for cmsGO! constants
if (!defined('CMSGO_ROOT')) {
	die("You Cannot Access This Script Directly, Have a Nice Day.");
}
// ----------------------------------------------------------------

// Content Type 89: Poll		jens
$content["poll_list"]		= unserialize($row["acontent_image"], ['allowed_classes' => false]);
$content["poll_form"]		= unserialize($row["acontent_form"], ['allowed_classes' => false]);
$content["poll_text"]		= unserialize($row["acontent_text"], ['allowed_classes' => false]);
