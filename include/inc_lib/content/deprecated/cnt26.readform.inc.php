<?php
/**
 * cmsGO!
 *
 * @author Pixels & Points GmbH <info@pixels-points.ch>
 * @copyright Copyright (c) 2002-2026, Pixels & Points GmbH
 * @license https://www.pixels-points.ch/cmsgo-license.html Pixels & Points cmsGO! license
 *
 **/

// ----------------------------------------------------------------
// obligate check for cmsgo constants
if(!defined('CMSGO_ROOT')) {
	die("You Cannot Access This Script Directly, Have a Nice Day.");
}
// ----------------------------------------------------------------

// recipe

$content['recipe'] = array(
    'preparation' => slweg($_POST['recipe_preparation']),
    'calorificvalue' => intval($_POST['recipe_calorificvalue']),
    'calorificvalue_add' => slweg($_POST['recipe_calorificvalue_add']),
    'ingredients' => clean_slweg($_POST['recipe_ingredients']),
    'time' => intval($_POST['recipe_time']),
    'time_add' => slweg($_POST['recipe_time_add']),
    'category' => clean_slweg($_POST['recipe_category']),
    'severity' => intval($_POST['recipe_severity']),
    'template' => clean_slweg($_POST['recipe_template'])
);

if($content['recipe']['severity'] < 1) {
	$content['recipe']['severity'] = 1;
} elseif($content['recipe']['severity'] > 5) {
	$content['recipe']['severity'] = 5;
}

$content['recipe']['category'] = convertStringToArray($content['recipe']['category']);
$content['recipe']['category'] = implode(', ', $content['recipe']['category']);

$content['recipe_search'] = optimizeForSearch(
    $content['recipe']['preparation'],
    $content['recipe']['ingredients'],
	$content['recipe']['calorificvalue_add'],
    $content['recipe']['time_add'],
	$content['recipe']['category']
);
