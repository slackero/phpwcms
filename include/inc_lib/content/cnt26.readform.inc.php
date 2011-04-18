<?php
/*************************************************************************************
   Copyright notice
   
   (c) 2002-2011 Oliver Georgi (oliver@phpwcms.de) // All rights reserved.
 
   This script is part of PHPWCMS. The PHPWCMS web content management system is
   free software; you can redistribute it and/or modify it under the terms of
   the GNU General Public License as published by the Free Software Foundation;
   either version 2 of the License, or (at your option) any later version.
  
   The GNU General Public License can be found at http://www.gnu.org/copyleft/gpl.html
   A copy is found in the textfile GPL.txt and important notices to the license 
   from the author is found in LICENSE.txt distributed with these scripts.
  
   This script is distributed in the hope that it will be useful, but WITHOUT ANY 
   WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A
   PARTICULAR PURPOSE.  See the GNU General Public License for more details.
 
   This copyright notice MUST APPEAR in all copies of the script!
*************************************************************************************/


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