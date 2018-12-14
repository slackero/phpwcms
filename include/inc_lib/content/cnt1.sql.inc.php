<?php
/**
 * cmsGo!
 *
 * @author Pixels & Points GmbH <info@pixels-points.ch>
 * @copyright Copyright (c) 2002-2018, Pixels & Points GmbH
 * @license https://www.pixels-points.ch/cmsgo-license.html Pixels & Points cmsGo! license
 *
 **/

// ----------------------------------------------------------------
// obligate check for cmsgo constants
if (!defined('CMSGO_ROOT')) {
	die("You Cannot Access This Script Directly, Have a Nice Day.");
}
// ----------------------------------------------------------------

// Content Type Text with Image

$SQL .= "acontent_text="._dbEscape($content["text"]).", ";
$SQL .= "acontent_image="._dbEscape($content["image_info"]).", ";
$SQL .= "acontent_form="._dbEscape(serialize($content['cimage'])).", ";
$SQL .= "acontent_template="._dbEscape($content["template"])." ";
