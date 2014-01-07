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



// Content Type 89: Poll		jens

$SQL .= "acontent_image	='".aporeplace(serialize($content['poll_list']))."', ";
$SQL .= "acontent_text	='".aporeplace(serialize($content['poll_text']))."', ";
$SQL .= "acontent_form	='".aporeplace(serialize($content['poll_form']))."' ";

?>