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
// obligate check for cmsgo constants
if (!defined('CMSGO_ROOT')) {
	die("You Cannot Access This Script Directly, Have a Nice Day.");
}
// ----------------------------------------------------------------

// Content Type FAQ
$SQL .= "acontent_text="._dbEscape($content["faq_question"]).", ";
$SQL .= "acontent_html="._dbEscape($content["faq_answer"]).", ";
$SQL .= "acontent_form="._dbEscape(serialize($content["faq"])).", ";
$SQL .= "acontent_image="._dbEscape($content["image_info"])." ";
