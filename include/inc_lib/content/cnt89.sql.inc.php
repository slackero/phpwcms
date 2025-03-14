<?php
/**
 * phpwcms content management system
 *
 * @author Oliver Georgi <og@phpwcms.org>
 * @copyright Copyright (c) 2002-2025, Oliver Georgi
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

// Content Type 89: Poll		jens
$SQL .= "acontent_image="._dbEscape(serialize($content['poll_list'])).", ";
$SQL .= "acontent_text="._dbEscape(serialize($content['poll_text'])).", ";
$SQL .= "acontent_form="._dbEscape(serialize($content['poll_form']))." ";
