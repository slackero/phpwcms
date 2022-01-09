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

// recipe
$SQL .= "acontent_text="._dbEscape($content['recipe']['category']).", ";
$SQL .= "acontent_form="._dbEscape(serialize($content['recipe'])).", ";
$SQL .= "acontent_alink="._dbEscape('0-'.substr('000000000000000'.$content['recipe']['calorificvalue'], -15) ).", ";
$SQL .= "acontent_media="._dbEscape('0-'.substr('000000000000000'.$content['recipe']['time'], -15) ).", ";
$SQL .= "acontent_files="._dbEscape('0-'.substr('000000000000000'.$content['recipe']['severity'], -15) ).", ";
$SQL .= "acontent_newsletter="._dbEscape($content['recipe_search'])." ";
