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

//$CNT_TMP .= headline($crow["acontent_title"], $crow["acontent_subtitle"], $template_default["article"]);

$crow["acontent_form"] = unserialize($crow["acontent_form"]);

if(file_exists(PHPWCMS_TEMPLATE.'inc_cntpart/recipe/'.$crow["acontent_form"]['template'])) {
	$crow["acontent_form"]['template'] = render_device( @file_get_contents(PHPWCMS_TEMPLATE.'inc_cntpart/recipe/'.$crow["acontent_form"]['template']) );
} else {
	$crow["acontent_form"]['template'] = '<div class="recipe">[TITLE]
	<h3>{TITLE}</h3>[/TITLE][SUBTITLE]
	<h4>{SUBTITLE}</h4>[/SUBTITLE][INGREDIENTS]
	<p>{INGREDIENTS}</p>[/INGREDIENTS][CALORIES]
	<p>{CALORIES} kJ / {KCAL} kcal[CALORIESADD] ({CALORIESADD})[/CALORIESADD]</p>[/CALORIES][PREPARATION]
	<p>{PREPARATION}</p>[/PREPARATION][TIME]
	<div class="time">{TIME} Minuten[TIMEADD], {TIMEADD}[/TIMEADD]</div>[/TIME]
	<div class="severity"><img src="img/severity_{SEVERITY}.gif" alt="" /></div>[CAT]
	<div class="cat">{CAT}</div>[/CAT]</div>';
}

$crow["acontent_form"]['kcal']			= ceil($crow["acontent_form"]['calorificvalue'] / 4.1868);
$crow["acontent_form"]['ingredients']	= convertStringToArray($crow["acontent_form"]['ingredients'], "\n", 'NOT-UNIQUE', false);

if(count($crow["acontent_form"]['ingredients'])) {
	$crow["acontent_form"]['temp'] = array();
	$ingrediens_counter = 0;
	foreach($crow["acontent_form"]['ingredients'] as $temp_val) {
		$temp_val = html_specialchars($temp_val);

		if(substr($temp_val, 0, 1) === '*') {	//headline
			if(isset($crow["acontent_form"]['temp'][$ingrediens_counter]['h'])) {
				$ingrediens_counter++;
			}
			$crow["acontent_form"]['temp'][$ingrediens_counter]['h'] = substr($temp_val, 1);
			continue;
		}

		$crow["acontent_form"]['temp1']  = explode('|', $temp_val, 2);
		$temp_val = implode(' ', $crow["acontent_form"]['temp1']);
		if(empty($crow["acontent_form"]['temp1'][1])) {
			$crow["acontent_form"]['temp1'][1] = $crow["acontent_form"]['temp1'][0];
			$crow["acontent_form"]['temp1'][0] = '&nbsp;';
		}

		$crow["acontent_form"]['temp'][$ingrediens_counter]['li'][] = '	<li>'.$temp_val.'</li>';
		$crow["acontent_form"]['temp'][$ingrediens_counter]['tr'][] = '	<tr>' . LF .
																	  '		<td valign="top" align="right" class="ingredients">'.$crow["acontent_form"]['temp1'][0].'</td>'.LF.
																	  '		<td valign="top" class="ingredientsText">'.$crow["acontent_form"]['temp1'][1].'</td>'.LF.
																	  '	</tr>';
	}

	$crow["acontent_form"]['ingredients']	= '';
	$crow["acontent_form"]['i_table']		= '';

	if(count($crow["acontent_form"]['temp'])) {

		foreach($crow["acontent_form"]['temp'] as $temp_val) {

			if(isset($temp_val['h'])) {	//alternative headline
				$crow["acontent_form"]['ingredients']	.= '<h5>' . $temp_val['h'] . '</h5>' . LF;
				$crow["acontent_form"]['i_table']		.= '<h5>' . $temp_val['h'] . '</h5>' . LF;
			}
			if(isset($temp_val['li'])) {
				$crow["acontent_form"]['ingredients']	.= '<ul>' . LF . implode(LF, $temp_val['li']) . LF . '</ul>' . LF;
				$crow["acontent_form"]['i_table']		.= '<table cellpadding="0" cellspacing="0" border="0">' . LF . implode(LF, $temp_val['tr']) . LF . '</table>' . LF;
			}
		}
	}

} else {
	$crow["acontent_form"]['ingredients']	= '';
	$crow["acontent_form"]['i_table']		= '';
}

// now render whole recipe
$crow["acontent_form"]['template'] = render_cnt_template($crow["acontent_form"]['template'], 'ATTR_CLASS', html($crow['acontent_attr_class']));
$crow["acontent_form"]['template'] = render_cnt_template($crow["acontent_form"]['template'], 'ATTR_ID', html($crow['acontent_attr_id']));
$crow["acontent_form"]['template'] = render_cnt_template($crow["acontent_form"]['template'], 'TITLE', html_specialchars($crow['acontent_title']));
$crow["acontent_form"]['template'] = render_cnt_template($crow["acontent_form"]['template'], 'SUBTITLE', html_specialchars($crow['acontent_subtitle']));
$crow["acontent_form"]['template'] = render_cnt_template($crow["acontent_form"]['template'], 'INGREDIENTS', $crow["acontent_form"]['ingredients']);
$crow["acontent_form"]['template'] = render_cnt_template($crow["acontent_form"]['template'], 'INGREDIENTSTABLE', $crow["acontent_form"]['i_table']);
$crow["acontent_form"]['template'] = render_cnt_template($crow["acontent_form"]['template'], 'CALORIES', $crow["acontent_form"]['calorificvalue']);
$crow["acontent_form"]['template'] = render_cnt_template($crow["acontent_form"]['template'], 'KCAL', $crow["acontent_form"]['kcal']);
$crow["acontent_form"]['template'] = render_cnt_template($crow["acontent_form"]['template'], 'CALORIESADD', html_specialchars($crow["acontent_form"]['calorificvalue_add']));
$crow["acontent_form"]['template'] = render_cnt_template($crow["acontent_form"]['template'], 'PREPARATION', $crow["acontent_form"]['preparation']);
$crow["acontent_form"]['template'] = render_cnt_template($crow["acontent_form"]['template'], 'TIME', html_specialchars($crow["acontent_form"]['time']));
$crow["acontent_form"]['template'] = render_cnt_template($crow["acontent_form"]['template'], 'TIMEADD', html_specialchars($crow["acontent_form"]['time_add']));
$crow["acontent_form"]['template'] = render_cnt_template($crow["acontent_form"]['template'], 'SEVERITY', $crow["acontent_form"]['severity']);
$crow["acontent_form"]['template'] = render_cnt_template($crow["acontent_form"]['template'], 'CAT', html_specialchars($crow["acontent_form"]['category']));
$crow["acontent_form"]['template'] = str_replace('{ID}', $crow['acontent_id'], $crow["acontent_form"]['template']);

$CNT_TMP .= $crow["acontent_form"]['template'];
