<?php
/**
 * cmsGO!
 *
 * @author Pixels & Points GmbH <info@pixels-points.ch>
 * @copyright Copyright (c) 2002-2022, Pixels & Points GmbH
 * @license https://www.pixels-points.ch/cmsgo-license.html Pixels & Points cmsGO! license
 *
 **/


// ----------------------------------------------------------------
// obligate check for cmsGO! constants
if (!defined('CMSGO_ROOT')) {
	die("You Cannot Access This Script Directly, Have a Nice Day.");
}
// ----------------------------------------------------------------

// Content Type Tabs

//$SQL .= "acontent_html="._dbEscape($content["image_html"]).", ";
$SQL .= "acontent_template="._dbEscape($content["tabs_template"]).", ";
$SQL .= "acontent_form="._dbEscape(serialize($content["tabs"])).", ";
$SQL .= "acontent_text="._dbEscape($content['search']).", ";
$SQL .= "acontent_html="._dbEscape($content['html'])." ";
