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



// Content Type Plain Text

// ----------------------------------------------------------------
// obligate check for phpwcms constants
if (!defined('PHPWCMS_ROOT')) {
   die("You Cannot Access This Script Directly, Have a Nice Day.");
}
// ----------------------------------------------------------------

$SQL .= "acontent_text		= '".aporeplace($content["text"])."', ";
$SQL .= "acontent_template	= '".aporeplace($content["template"])."', ";
$SQL .= "acontent_form		= '".aporeplace( serialize( array('ctext_format' => $content["ctext_format"]) ) )."' ";

?>