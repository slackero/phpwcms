<?php
/**
 * phpwcms content management system
 *
 * @author Oliver Georgi <og@phpwcms.org>
 * @copyright Copyright (c) 2002-2022, Oliver Georgi
 * @license http://opensource.org/licenses/GPL-2.0 GNU GPL-2
 * @link http://www.phpwcms.org
 *
 **/

// ----------------------------------------------------------------
// obligate check for phpwcms constants
if (!defined('PHPWCMS_ROOT')) {
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
