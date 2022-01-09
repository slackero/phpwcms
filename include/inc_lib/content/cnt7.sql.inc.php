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

// Content Type File List
$SQL .= "acontent_text="._dbEscape($content["file_descr"]).", ";
$SQL .= "acontent_html="._dbEscape($content["html"]).", ";
$SQL .= "acontent_files="._dbEscape(isset($content["file_id_list"]) ? $content["file_id_list"] : '').", ";
$SQL .= "acontent_template="._dbEscape($content["file_template"]).", ";
$SQL .= "acontent_form="._dbEscape(serialize($content['file']))." ";
