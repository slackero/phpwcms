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

$content['recipe'] = array();

$content['recipe']['preparation']		= slweg($_POST['recipe_preparation']);
$content['recipe']['calorificvalue']	= intval($_POST['recipe_calorificvalue']);
$content['recipe']['calorificvalue_add']= slweg($_POST['recipe_calorificvalue_add']);
$content['recipe']['ingredients']		= clean_slweg($_POST['recipe_ingredients']);
$content['recipe']['time']				= intval($_POST['recipe_time']);
$content['recipe']['time_add']			= slweg($_POST['recipe_time_add']);
$content['recipe']['category']			= clean_slweg($_POST['recipe_category']);
$content['recipe']['severity']			= intval($_POST['recipe_severity']);
$content['recipe']['template']			= clean_slweg($_POST['recipe_template']);

if($content['recipe']['severity'] < 1) {
	$content['recipe']['severity'] = 1;
} elseif($content['recipe']['severity'] > 5) {
	$content['recipe']['severity'] = 5;
}

$content['recipe']['category']			= convertStringToArray($content['recipe']['category']);
$content['recipe']['category']			= implode(', ', $content['recipe']['category']);

$content['recipe_search'] = optimizeForSearch(	$content['recipe']['preparation'],			$content['recipe']['ingredients'],
												$content['recipe']['calorificvalue_add'],	$content['recipe']['time_add'],
												$content['recipe']['category']);


?>