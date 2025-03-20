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
$content["poll_list"]		= unserialize($row["acontent_image"], ['allowed_classes' => false]);
$content["poll_form"]		= unserialize($row["acontent_form"], ['allowed_classes' => false]);
$content["poll_text"]		= unserialize($row["acontent_text"], ['allowed_classes' => false]);
