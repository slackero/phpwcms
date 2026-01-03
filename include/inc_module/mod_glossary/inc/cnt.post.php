<?php
/**
 * cmsGO!
 *
 * @author Pixels & Points GmbH <info@pixels-points.ch>
 * @copyright Copyright (c) 2002-2026, Pixels & Points GmbH
 * @license https://www.pixels-points.ch/cmsgo-license.html Pixels & Points cmsGO! license
 *
 **/

// ----------------------------------------------------------------
// obligate check for cmsGO! constants
if (!defined('CMSGO_ROOT')) {
	die("You Cannot Access This Script Directly, Have a Nice Day.");
}
// ----------------------------------------------------------------


// Glossary module handle content part POST values

$content['glossary'] = array();
$content['glossary']['glossary_template']	= clean_slweg($_POST['glossary_template']);
$content['glossary']['glossary_filter']		= clean_slweg($_POST['glossary_filter']);
$content['glossary']['glossary_maxwords']	= intval($_POST['glossary_maxwords']);
if(empty($content['glossary']['glossary_maxwords'])) {
	$content['glossary']['glossary_maxwords'] = '';
}

$content['glossary']['glossary_tag']		= strtolower(clean_slweg($_POST['glossary_tag']));
$content['glossary']['glossary_noentry']	= slweg($_POST['glossary_noentry']);
