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

// Content Type 89: Poll		jens
$SQL .= "acontent_image="._dbEscape(serialize($content['poll_list'])).", ";
$SQL .= "acontent_text="._dbEscape(serialize($content['poll_text'])).", ";
$SQL .= "acontent_form="._dbEscape(serialize($content['poll_form']))." ";
