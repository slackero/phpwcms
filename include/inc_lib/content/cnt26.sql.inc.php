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


// recipe
$SQL .= "acontent_text='".aporeplace($content['recipe']['category'])."', ";
$SQL .= "acontent_form='".aporeplace(serialize($content['recipe']))."', ";
$SQL .= "acontent_alink='".aporeplace( '0-'.substr('000000000000000'.$content['recipe']['calorificvalue'], -15) )."', ";
$SQL .= "acontent_media='".aporeplace( '0-'.substr('000000000000000'.$content['recipe']['time'], -15) )."', ";
$SQL .= "acontent_files='".aporeplace( '0-'.substr('000000000000000'.$content['recipe']['severity'], -15) )."', ";
$SQL .= "acontent_newsletter='".aporeplace($content['recipe_search'])."' ";


?>