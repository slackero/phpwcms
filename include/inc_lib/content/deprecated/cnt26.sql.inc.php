<?php
/**
 * cmsGO!
 *
 * @author Pixels & Points GmbH <info@pixels-points.ch>
 * @copyright Copyright (c) 2002-2023, Pixels & Points GmbH
 * @license https://www.pixels-points.ch/cmsgo-license.html Pixels & Points cmsGO! license
 *
 **/

// ----------------------------------------------------------------
// obligate check for cmsgo constants
if (!defined('CMSGO_ROOT')) {
	die("You Cannot Access This Script Directly, Have a Nice Day.");
}
// ----------------------------------------------------------------

// recipe
$SQL .= "acontent_text="._dbEscape($content['recipe']['category']).", ";
$SQL .= "acontent_form="._dbEscape(serialize($content['recipe'])).", ";
$SQL .= "acontent_alink="._dbEscape('0-'.substr('000000000000000'.$content['recipe']['calorificvalue'], -15) ).", ";
$SQL .= "acontent_media="._dbEscape('0-'.substr('000000000000000'.$content['recipe']['time'], -15) ).", ";
$SQL .= "acontent_files="._dbEscape('0-'.substr('000000000000000'.$content['recipe']['severity'], -15) ).", ";
$SQL .= "acontent_newsletter="._dbEscape($content['recipe_search'])." ";
