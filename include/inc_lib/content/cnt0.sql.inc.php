<?php
/**
 * cmsGO!
 *
 * @author Pixels & Points GmbH <info@pixels-points.ch>
 * @copyright Copyright (c) 2002-2022, Pixels & Points GmbH
 * @license https://www.pixels-points.ch/cmsgo-license.html Pixels & Points cmsGO! license
 *
 **/

// Content Type Plain Text

// ----------------------------------------------------------------
// obligate check for cmsGO! constants
if (!defined('CMSGO_ROOT')) {
	die("You Cannot Access This Script Directly, Have a Nice Day.");
}
// ----------------------------------------------------------------

$SQL .= "acontent_text		= "._dbEscape($content["text"]).", ";
$SQL .= "acontent_template	= "._dbEscape($content["template"]).", ";
$SQL .= "acontent_form		= "._dbEscape( serialize( array('ctext_format' => $content["ctext_format"]) ) )." ";
