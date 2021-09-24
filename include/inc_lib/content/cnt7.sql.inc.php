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

// Content Type File List
$SQL .= "acontent_text="._dbEscape($content["file_descr"]).", ";
$SQL .= "acontent_html="._dbEscape($content["html"]).", ";
$SQL .= "acontent_files="._dbEscape(isset($content["file_id_list"]) ? $content["file_id_list"] : '').", ";
$SQL .= "acontent_template="._dbEscape($content["file_template"]).", ";
$SQL .= "acontent_form="._dbEscape(serialize($content['file']))." ";
