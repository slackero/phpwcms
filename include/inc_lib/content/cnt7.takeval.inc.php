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
$content["file_descr"]		= $row["acontent_text"];
$content["file_list"]		= explode(":", $row["acontent_files"]);
$content["file_template"]	= $row["acontent_template"];
$content['file']			= unserialize($row["acontent_form"]);
$content["html"]			= $row["acontent_html"];
