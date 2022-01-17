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

// Glossary module content part SQL UPDATE/INSERT
// usage:
// SQL .= "acontent_field = '".aporeplace($value)."', ";

if(isset($content['glossary']) && is_array($content['glossary'])) {

	$SQL .= "acontent_form = '".aporeplace(serialize($content['glossary']))."'";

}
