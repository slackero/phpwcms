<?php
/**
 * phpwcms content management system
 *
 * @author Oliver Georgi <oliver@phpwcms.de>
 * @copyright Copyright (c) 2002-2014, Oliver Georgi
 * @license http://opensource.org/licenses/GPL-2.0 GNU GPL-2
 * @link http://www.phpwcms.de
 *
 **/

// ----------------------------------------------------------------
// obligate check for phpwcms constants
if (!defined('PHPWCMS_ROOT')) {
   die("You Cannot Access This Script Directly, Have a Nice Day.");
}
// ----------------------------------------------------------------


// Content Type FAQ

$SQL .= "acontent_text	='".aporeplace($content["faq_question"])."', ";
$SQL .= "acontent_html	='".aporeplace($content["faq_answer"])."', ";
$SQL .= "acontent_form	='".aporeplace(serialize($content["faq"]))."', ";
$SQL .= "acontent_image	='".aporeplace($content["image_info"])."' ";

?>