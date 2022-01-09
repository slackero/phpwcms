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

// Content Type FAQ
$SQL .= "acontent_text="._dbEscape($content["faq_question"]).", ";
$SQL .= "acontent_html="._dbEscape($content["faq_answer"]).", ";
$SQL .= "acontent_form="._dbEscape(serialize($content["faq"])).", ";
$SQL .= "acontent_image="._dbEscape($content["image_info"])." ";
