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

// Content Type Images
$SQL .= "acontent_text="._dbEscape($content["text"]).", ";
$SQL .= "acontent_template="._dbEscape($content["image_template"]).", ";
$SQL .= "acontent_form="._dbEscape(serialize($content['image_list']))." ";
