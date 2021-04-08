<?php
/**
 * cmsGO!
 *
 * @author Pixels & Points GmbH <info@pixels-points.ch>
 * @copyright Copyright (c) 2002-2021, Pixels & Points GmbH
 * @license https://www.pixels-points.ch/cmsgo-license.html Pixels & Points cmsGO! license
 *
 **/


// ----------------------------------------------------------------
// obligate check for cmsgo constants
if (!defined('CMSGO_ROOT')) {
	die("You Cannot Access This Script Directly, Have a Nice Day.");
}
// ----------------------------------------------------------------

// Content Type WYSIWYG HTML
$content["html"] = $row["acontent_html"];
$content["template"] = $row["acontent_template"];
$content["custom_fields"] = @unserialize($row["acontent_form"]);
$content["custom_fields"] = empty($content["custom_fields"]['cnt_fields']) ? array() : $content["custom_fields"]['cnt_fields'];