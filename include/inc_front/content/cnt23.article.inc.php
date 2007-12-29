<?php
/*************************************************************************************
   Copyright notice
   
   (c) 2002-2007 Oliver Georgi (oliver@phpwcms.de) // All rights reserved.
 
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

// Form
$CNT_TMP .= '<a name="jumpForm'.$crow["acontent_id"].'" id="jumpForm'.$crow["acontent_id"].'" />';
$CNT_TMP .= headline($crow["acontent_title"], $crow["acontent_subtitle"], $template_default["article"]);
$cnt_form = unserialize($crow["acontent_form"]);

$form_error_text = '';

$form_cnt = $cnt_form['labelpos']== 2 ? $cnt_form['customform'] : '';

// set sender email address
if(empty($cnt_form['sendertype']) || $cnt_form['sendertype'] == 'system') {
	$cnt_form['sender'] = $phpwcms['SMTP_FROM_EMAIL'];
} elseif($cnt_form['sendertype'] == 'email' && !is_valid_email($cnt_form['sender'])) {
	$cnt_form['sender'] = $phpwcms['SMTP_FROM_EMAIL'];
}

// basic sender name check
if(empty($cnt_form['sendernametype'])) {

	$cnt_form['sendername'] 	= '';
	$cnt_form['sendernametype']	= '';
	
} elseif($cnt_form['sendernametype'] == 'system') {

	$cnt_form['sendername'] = $phpwcms['SMTP_FROM_NAME'];

}

if(empty($cnt_form['sendername'])) {

	$cnt_form['sendername'] = '';

}


/*
 * Browse form fields
 */
if(isset($cnt_form["fields"]) && is_array($cnt_form["fields"]) && count($cnt_form["fields"])) {
	
	$form_counter = 0;
	$cnt_form['label_wrap'] = explode('|', $cnt_form['label_wrap']);
	$cnt_form['label_wrap'][0] = !empty($cnt_form['label_wrap'][0]) ? trim($cnt_form['label_wrap'][0]) : '';
	$cnt_form['label_wrap'][1] = !empty($cnt_form['label_wrap'][1]) ? trim($cnt_form['label_wrap'][1]) : '';
	$form_field_hidden = '';
	
	if(!empty($_POST['cpID'.$crow["acontent_id"]]) && intval($_POST['cpID'.$crow["acontent_id"]]) == $crow["acontent_id"]) {
		$POST_DO = true;
		$POST_val = array();
		$cache_nosave = true;
	} else {
		$POST_DO = false;
	}
	
	// make spam check
	if($POST_DO && !checkFormTrackingValue()) {
		$POST_ERR['spamFormAlert'.time()] = '[span_class:spamFormAlert]Your IP '.getRemoteIP().' is not allowed to send form![/class]';
	}
	
	foreach($cnt_form["fields"] as $key => $value) {
	
		$form_field = '';
		$form_name = html_specialchars($cnt_form["fields"][$key]['name']);	
		$POST_name = $cnt_form["fields"][$key]['name'];
		
		switch($cnt_form["fields"][$key]['type']) {
	
			case 'text'		:	/*
								 * Text
								 */
								if($POST_DO && isset($_POST[$POST_name])) {
									$POST_val[$POST_name] = remove_unsecure_rptags(clean_slweg($_POST[$POST_name]));
									if($cnt_form["fields"][$key]['required'] && $POST_val[$POST_name] == '') {
										$POST_ERR[$key] = $cnt_form["fields"][$key]['error'];
									} else {
										$cnt_form["fields"][$key]['value'] = $POST_val[$POST_name];
									}
								}
								//
								$form_field .= '<input type="text" name="'.$form_name.'" id="'.$form_name.'" ';
								$form_field .= 'value="'.html_specialchars($cnt_form["fields"][$key]['value']).'"';
								if($cnt_form["fields"][$key]['size']) {
									$form_field .= ' size="'.$cnt_form["fields"][$key]['size'].'"';
								}
								if($cnt_form["fields"][$key]['max']) {
									$form_field .= ' maxlength="'.$cnt_form["fields"][$key]['max'].'"';
								}
								if($cnt_form["fields"][$key]['class']) {
									$form_field .= ' class="'.$cnt_form["fields"][$key]['class'].'"';
								}
								if($cnt_form["fields"][$key]['style']) {
									$form_field .= ' style="'.$cnt_form["fields"][$key]['style'].'"';
								}
								$form_field .= ' />';
								break;
								
			case 'captcha':		/*
								 * Captcha
								 */
								if($POST_DO && isset($_POST[$POST_name])) {
									$POST_val[$POST_name] = remove_unsecure_rptags(clean_slweg($_POST[$POST_name]));
									include_once (PHPWCMS_ROOT.'/include/inc_ext/SOLMETRA_FormValidator/SPAF_FormValidator.class.php');
									$spaf_obj = new SPAF_FormValidator();
									if($spaf_obj->validRequest($POST_val[$POST_name])) {
										$spaf_obj->destroy();
									} else {
										$POST_ERR[$key] = empty($cnt_form["fields"][$key]['error']) ? 'Captcha error' : $cnt_form["fields"][$key]['error'];
									}
									$cnt_form["fields"][$key]['value'] = '';
								}
								//
								$form_field .= '<input type="text" name="'.$form_name.'" id="'.$form_name.'" value=""';
								if($cnt_form["fields"][$key]['size']) {
									$form_field .= ' size="'.$cnt_form["fields"][$key]['size'].'"';
								}
								if($cnt_form["fields"][$key]['max']) {
									$form_field .= ' maxlength="'.$cnt_form["fields"][$key]['max'].'"';
								}
								if($cnt_form["fields"][$key]['class']) {
									$form_field .= ' class="'.$cnt_form["fields"][$key]['class'].'"';
								}
								if($cnt_form["fields"][$key]['style']) {
									$form_field .= ' style="'.$cnt_form["fields"][$key]['style'].'"';
								}
								$form_field .= ' />';
								break;
								
			case 'special'	:	/*
								 * Special
								 */
								if($cnt_form["fields"][$key]['value']) {
									$cnt_form['special_value'] = str_replace('"', '', $cnt_form["fields"][$key]['value']);
									$cnt_form['special_value'] = str_replace("'", '',$cnt_form['special_value']);
									$cnt_form['special_value'] = str_replace("\r'", '',$cnt_form['special_value']);
									$cnt_form['special_value'] = explode("\n", $cnt_form['special_value']);
									$cnt_form["fields"][$key]['value'] = '';
									
									if(is_array($cnt_form['special_value']) && count($cnt_form['special_value'])) {
										foreach($cnt_form['special_value'] as $cnt_form['special_key'] => $cnt_form['special_val']) {
											$temp_array = explode('=', $cnt_form['special_val']);
											switch($temp_array[0]) {
												case 'default':		$cnt_form['special_attribute']['default'] = isset($temp_array[1]) ? $temp_array[1] : '';
																	break;
												case 'type':		$cnt_form['special_attribute']['type'] = isset($temp_array[1]) ? $temp_array[1] : 'MIX';
																	break;
												case 'dateformat':	$cnt_form['special_attribute']['dateformat'] = isset($temp_array[1]) ? $temp_array[1] : 'm/d/Y';
																	break;
											}
										}
									}
								}
								

								
								$cnt_form["fields"][$key]['value'] = isset($cnt_form['special_attribute']['default']) ? $cnt_form['special_attribute']['default'] : '';
								 
								if($POST_DO && isset($_POST[$POST_name])) {
									$POST_val[$POST_name] = remove_unsecure_rptags(clean_slweg($_POST[$POST_name]));
									if($cnt_form["fields"][$key]['required'] && $POST_val[$POST_name] == '') {
										$POST_ERR[$key] = $cnt_form["fields"][$key]['error'];
									} else {
										$cnt_form["fields"][$key]['value'] = $POST_val[$POST_name];
										// try to check for special value
										if(isset($cnt_form['special_attribute']['type'])) {
											switch($cnt_form['special_attribute']['type']) {
										
												//case 'MIX':		
												//				break;
												
												case 'INT':		if(!preg_match('/^[0-9\-\+]+$/', $cnt_form["fields"][$key]['value'])) {
																	$POST_ERR[$key] = $cnt_form["fields"][$key]['error'];
																} else {
																	 $cnt_form["fields"][$key]['value'] = intval( $cnt_form["fields"][$key]['value']);
																}
																break;
												
												case 'DEC':
												case 'FLOAT':	if(!is_float_ex($cnt_form["fields"][$key]['value'])) {
																	$POST_ERR[$key] = $cnt_form["fields"][$key]['error'];
																}
																break;
												
												case 'IDENT':	if(isset($cnt_form['special_attribute']['default']) && 
																   decode_entities($cnt_form['special_attribute']['default']) != decode_entities($cnt_form["fields"][$key]['value'])) {
																	$POST_ERR[$key] = $cnt_form["fields"][$key]['error'];
																}
																break;
												
												case 'MIX':
												case 'STRING':	
																break;
												
												case 'DATE':	if(isset($cnt_form['special_attribute']['dateformat']) && 
																   !is_date($cnt_form["fields"][$key]['value'], $cnt_form['special_attribute']['dateformat'])) {
																	$POST_ERR[$key] = $cnt_form["fields"][$key]['error'];
																	$cnt_form["fields"][$key]['value'] = date($cnt_form['special_attribute']['dateformat']);
																}
																break;
										
											}
										}
									}
								} else {
								
									if(isset($cnt_form['special_attribute']['default']) && isset($cnt_form['special_attribute']['type']) &&
									   $cnt_form['special_attribute']['type'] == 'DATE' && $cnt_form['special_attribute']['default'] == 'NOW') {
									   	echo 'ja';
									 	if(isset($cnt_form['special_attribute']['dateformat'])) {
											$cnt_form["fields"][$key]['value'] = date($cnt_form['special_attribute']['dateformat']);
										} else {
											$cnt_form["fields"][$key]['value'] = date('m/d/Y');
										}
									}
								}
								//
								$form_field .= '<input type="text" name="'.$form_name.'" id="'.$form_name.'" ';
								$form_field .= 'value="'.html_specialchars($cnt_form["fields"][$key]['value']).'"';
								if($cnt_form["fields"][$key]['size']) {
									$form_field .= ' size="'.$cnt_form["fields"][$key]['size'].'"';
								}
								if($cnt_form["fields"][$key]['max']) {
									$form_field .= ' maxlength="'.$cnt_form["fields"][$key]['max'].'"';
								}
								if($cnt_form["fields"][$key]['class']) {
									$form_field .= ' class="'.$cnt_form["fields"][$key]['class'].'"';
								}
								if($cnt_form["fields"][$key]['style']) {
									$form_field .= ' style="'.$cnt_form["fields"][$key]['style'].'"';
								}
								$form_field .= ' />';
								break;
								
			case 'email'	:	/*
								 * Email
								 */
								if($POST_DO && isset($_POST[$POST_name])) {
									$POST_val[$POST_name] = remove_unsecure_rptags(clean_slweg($_POST[$POST_name]));
									if(($cnt_form["fields"][$key]['required'] && !$POST_val[$POST_name]) || ($POST_val[$POST_name] && !is_valid_email($POST_val[$POST_name]))) {
										$POST_ERR[$key] = $cnt_form["fields"][$key]['error'];
									}
									$cnt_form["fields"][$key]['value'] = $POST_val[$POST_name];
								}
								// check if message should be delivered to email address of this field
								if($POST_DO && ($cnt_form['targettype'] == 'emailfield_'.$POST_name) && empty($POST_ERR[$key]) && is_valid_email($cnt_form["fields"][$key]['value'])) {
									if(empty($cnt_form['target'])) {
										$cnt_form['target'] = $cnt_form["fields"][$key]['value'];
									} else {
										$cnt_form['target'] = $cnt_form["fields"][$key]['value'].';'.$cnt_form['target'];
									}
								}
								//
								// check if message should be sent by email address of this field
								if($POST_DO && ($cnt_form['sendertype'] == 'emailfield_'.$POST_name) && empty($POST_ERR[$key]) && is_valid_email($cnt_form["fields"][$key]['value'])) {
									$cnt_form['sender'] = $cnt_form["fields"][$key]['value'];
								}
								//
								$form_field .= '<input type="text" name="'.$form_name.'" id="'.$form_name.'" ';
								$form_field .= 'value="'.html_specialchars($cnt_form["fields"][$key]['value']).'"';
								if($cnt_form["fields"][$key]['size']) {
									$form_field .= ' size="'.$cnt_form["fields"][$key]['size'].'"';
								}
								if($cnt_form["fields"][$key]['max']) {
									$form_field .= ' maxlength="'.$cnt_form["fields"][$key]['max'].'"';
								}
								if($cnt_form["fields"][$key]['class']) {
									$form_field .= ' class="'.$cnt_form["fields"][$key]['class'].'"';
								}
								if($cnt_form["fields"][$key]['style']) {
									$form_field .= ' style="'.$cnt_form["fields"][$key]['style'].'"';
								}
								$form_field .= ' />';
								break;

			case 'textarea'	:	/*
								 * Textarea
								 */
								if($POST_DO && isset($_POST[$POST_name])) {
									$POST_val[$POST_name] = remove_unsecure_rptags(clean_slweg($_POST[$POST_name]));
									if($cnt_form["fields"][$key]['required'] && $POST_val[$POST_name] == '') {
										$POST_ERR[$key] = $cnt_form["fields"][$key]['error'];
									} else {
										$cnt_form["fields"][$key]['value'] = $POST_val[$POST_name];
									}
								}
								//
								$form_field .= '<textarea name="'.$form_name.'" id="'.$form_name.'"';
								if($cnt_form["fields"][$key]['size']) {
									$form_field .= ' cols="'.$cnt_form["fields"][$key]['size'].'"';
								} else {
									$form_field .= ' cols="20"';
								}
								if($cnt_form["fields"][$key]['max']) {
									$form_field .= ' rows="'.$cnt_form["fields"][$key]['max'].'"';
								}
								if($cnt_form["fields"][$key]['class']) {
									$form_field .= ' class="'.$cnt_form["fields"][$key]['class'].'"';
								}
								if($cnt_form["fields"][$key]['style']) {
									$form_field .= ' style="'.$cnt_form["fields"][$key]['style'].'"';
								}
								$form_field .= '>'.html_specialchars($cnt_form["fields"][$key]['value']).'</textarea>';
								break;

			case 'hidden'	:	/*
								 * Hidden
								 */
								if($POST_DO && isset($_POST[$POST_name])) {
									$POST_val[$POST_name] = remove_unsecure_rptags(clean_slweg($_POST[$POST_name]));
									if($cnt_form["fields"][$key]['required'] && $POST_val[$POST_name] = '') {
										$POST_ERR[$key] = $cnt_form["fields"][$key]['error'];
									} else {
										$cnt_form["fields"][$key]['value'] = $POST_val[$POST_name];
									}
								}
								//
								$form_field_hidden .= '<input type="hidden" name="'.$form_name.'" ';
								$form_field_hidden .= 'value="'.html_specialchars($cnt_form["fields"][$key]['value']).'" />';
								break;

			case 'password'	:	/*
								 * Password
								 */
								if($POST_DO && isset($_POST[$POST_name])) {
									$POST_val[$POST_name] = remove_unsecure_rptags(clean_slweg($_POST[$POST_name]));
									if($cnt_form["fields"][$key]['required'] && $POST_val[$POST_name] == '') {
										$POST_ERR[$key] = $cnt_form["fields"][$key]['error'];
									} else {
										$cnt_form["fields"][$key]['value'] = $POST_val[$POST_name];
									}
								}
								//
								$form_field .= '<input type="password" name="'.$form_name.'" id="'.$form_name.'" ';
								$form_field .= 'value="'.html_specialchars($cnt_form["fields"][$key]['value']).'"';
								if($cnt_form["fields"][$key]['size']) {
									$form_field .= ' size="'.$cnt_form["fields"][$key]['size'].'"';
								}
								if($cnt_form["fields"][$key]['max']) {
									$form_field .= ' maxlength="'.$cnt_form["fields"][$key]['max'].'"';
								}
								if($cnt_form["fields"][$key]['class']) {
									$form_field .= ' class="'.$cnt_form["fields"][$key]['class'].'"';
								}
								if($cnt_form["fields"][$key]['style']) {
									$form_field .= ' style="'.$cnt_form["fields"][$key]['style'].'"';
								}
								$form_field .= ' autocomplete="off" />';
								break;

			case 'select'	:	/*
								 * Menü
								 */
								if($POST_DO && isset($_POST[$POST_name])) {
									$POST_val[$POST_name] = remove_unsecure_rptags(clean_slweg($_POST[$POST_name]));
									if($cnt_form["fields"][$key]['required'] && $POST_val[$POST_name] == '') {
										$POST_ERR[$key] = $cnt_form["fields"][$key]['error'];
									} else {
										$cnt_form["fields"][$key]['value'] = str_replace(' selected', '', $cnt_form["fields"][$key]['value']);
									}
								}
								//
								$form_field .= '<select name="'.$form_name.'" id="'.$form_name.'"';
								if($cnt_form["fields"][$key]['class']) {
									$form_field .= ' class="'.$cnt_form["fields"][$key]['class'].'"';
								}
								if($cnt_form["fields"][$key]['style']) {
									$form_field .= ' style="'.$cnt_form["fields"][$key]['style'].'"';
								}
								$form_field .= ">\n";
								$form_value = explode("\n", $cnt_form["fields"][$key]['value']);
								$form_value = array_map('trim', $form_value);
								$form_value = array_diff($form_value, array(''));
								if(count($form_value)) {
									foreach($form_value as $option_value) {
									
										if(isset($POST_val[$POST_name]) && $POST_val[$POST_name] == $option_value) {
											$option_value .= ' selected';
										}
										
										$option_value = html_specialchars($option_value);
										if(strtolower(substr($option_value, -9)) != ' selected') {
											$form_field .= '<option value="'.$option_value.'"';
										} else {
											$option_value = str_replace(' selected', '', $option_value);
											$form_field .= '<option value="'.$option_value.'" selected="selected"';
										}
										$form_field .= '>'.$option_value."</option>\n";
									}
								}
								$form_field .= '</select>';
								break;

			case 'list'		:	/*
								 * Liste
								 */
								if($POST_DO && isset($_POST[$POST_name])) {
									if(is_array($_POST[$POST_name])) {
										$POST_val[$POST_name] = array_map('combined_POST_cleaning', $_POST[$POST_name]);
										$POST_val[$POST_name] = array_diff($POST_val[$POST_name], array(''));
										if(!count($POST_val[$POST_name])) {
											$POST_val[$POST_name] = false;
										}
									} else {
										$POST_val[$POST_name] = remove_unsecure_rptags(clean_slweg($_POST[$POST_name]));
									}
									if($cnt_form["fields"][$key]['required'] && ($POST_val[$POST_name] === false || $POST_val[$POST_name] == '')) {
										$POST_ERR[$key] = $cnt_form["fields"][$key]['error'];
									} else {
										$cnt_form["fields"][$key]['value'] = str_replace(' selected', '', $cnt_form["fields"][$key]['value']);
									}
								}
								//
								$form_field .= '<select id="'.$form_name.'"';
								if($cnt_form["fields"][$key]['size']) {
									$form_field .= ' size="'.$cnt_form["fields"][$key]['size'].'"';
								}
								if($cnt_form["fields"][$key]['max']) {
									$form_field .= ' multiple';
									$form_field .= ' name="'.$form_name.'[]"';
								} else {
									$form_field .= ' name="'.$form_name.'"';
								}
								if($cnt_form["fields"][$key]['class']) {
									$form_field .= ' class="'.$cnt_form["fields"][$key]['class'].'"';
								}
								if($cnt_form["fields"][$key]['style']) {
									$form_field .= ' style="'.$cnt_form["fields"][$key]['style'].'"';
								}
								$form_field .= ">\n";
								$form_value = explode("\n", $cnt_form["fields"][$key]['value']);
								$form_value = array_map('trim', $form_value);
								$form_value = array_diff($form_value, array(''));
								if(count($form_value)) {
									foreach($form_value as $option_value) {
										// try to set given POST var as selected
										if(isset($POST_val[$POST_name])) {
											if(is_array($POST_val[$POST_name])) {
												foreach($POST_val[$POST_name] as $postvar_value) {
													if($postvar_value == $option_value) {
														$option_value .= ' selected';
													}
												}
											} elseif ($POST_val[$POST_name] == $option_value) {
												$option_value .= ' selected';
											}
										}
										
										$option_value = html_specialchars($option_value);
										if(substr($option_value, -9) != ' selected') {
											$form_field .= '<option value="'.$option_value.'"';
										} else {
											$option_value = str_replace(' selected', '', $option_value);
											$form_field .= '<option value="'.$option_value.'" selected="selected"';
										}
										$form_field .= '>'.$option_value."</option>\n";
									}
								}
								$form_field .= '</select>';
								break;

			case 'checkbox'	:	/*
								 * Checkbox
								 */
								if($POST_DO && ($cnt_form["fields"][$key]['required'] || isset($_POST[$POST_name]) ) ) {
									if(isset($_POST[$POST_name]) && is_array($_POST[$POST_name])) {
										$POST_val[$POST_name] = array_map('combined_POST_cleaning', $_POST[$POST_name]);
										$POST_val[$POST_name] = array_diff($POST_val[$POST_name], array(''));
										if(!count($POST_val[$POST_name])) {
											$POST_val[$POST_name] = '';
										}
									} else {
										$POST_val[$POST_name] = isset($_POST[$POST_name]) ? remove_unsecure_rptags(clean_slweg($_POST[$POST_name])) : '';
									}
									if($cnt_form["fields"][$key]['required'] && ($POST_val[$POST_name] === false || $POST_val[$POST_name] == '')) {
										$POST_ERR[$key] = $cnt_form["fields"][$key]['error'];
									} else {
										$cnt_form["fields"][$key]['value'] = str_replace(' checked', '', $cnt_form["fields"][$key]['value']);
									}
								}
								//
								$form_value = explode("\n", $cnt_form["fields"][$key]['value']);
								$form_value = array_map('trim', $form_value);
								$form_value = array_diff($form_value, array(''));
								if($cnt_form["fields"][$key]['class']) {
									$form_field 	.= '<div class="'.$cnt_form["fields"][$key]['class'].'">';
									$checkbox_class  = '</div>';
								} else {
									$checkbox_class  = '';
								}
								if($cnt_form["fields"][$key]['style']) {
									$checkbox_style = ' style="'.$cnt_form["fields"][$key]['style'].'"';
								} else {
									$checkbox_style = '';
								}
								if(count($form_value) == 1 || count($form_value) == 0 || !$form_value) {
									// only 1 checkbox
									$checkbox_value = is_array($form_value) ? implode('', $form_value) : $form_value;
									$checkbox_value = trim($checkbox_value);
									if(isset($POST_val[$POST_name]) && $POST_val[$POST_name] == ($checkbox_value ? $checkbox_value : $form_name)) {
										$checkbox_value .= ' checked';
									}
									$checkbox_value = $checkbox_value ? html_specialchars($checkbox_value) : $form_name;
									$form_field .= '<input type="checkbox" name="'.$form_name.'" id="'.$form_name.'" ';
									if(substr($checkbox_value, -8) != ' checked') {
										$form_field .= 'value="' . $checkbox_value . '" />';
									} else {
										$checkbox_value = str_replace(' checked', '', $checkbox_value);
										$form_field .= 'value="' . $checkbox_value . '" checked="checked" />';
									}
									$form_field .= '<label for="'.$form_name.'"';
									$form_field .= $checkbox_style;
									$form_field .= '>'.$checkbox_value .'</label>';
									
								} else {
									// list of checkboxes
									$checkbox_counter = 0;
									$checkbox_spacer  = $cnt_form["fields"][$key]['size'] ? '<br />' : ' ';
									foreach($form_value as $checkbox_value) {
									
										if(isset($POST_val[$POST_name]) && is_array($POST_val[$POST_name])) {
											foreach($POST_val[$POST_name] as $postvar_value) {
												if($postvar_value == $checkbox_value) {
													$checkbox_value .= ' checked';
												}
											}
										}
									
										$checkbox_value =  html_specialchars(trim($checkbox_value));
										if($checkbox_counter) {
											$form_field .= $checkbox_spacer;
										}
										$form_field .= '<input type="checkbox" name="'.$form_name.'[]" id="'.$form_name.$checkbox_counter.'" ';
										if(substr($checkbox_value, -8) != ' checked') {
											$form_field .= 'value="' . $checkbox_value . '" />';
										} else {
											$checkbox_value = str_replace(' checked', '', $checkbox_value);
											$form_field .= 'value="' . $checkbox_value . '" checked="checked" />';
										}
										$form_field .= '<label for="'.$form_name.$checkbox_counter.'"';
										$form_field .= $checkbox_style;
										$form_field .= '>'.$checkbox_value .'</label>';
										$checkbox_counter++;
									}
								}
								$form_field .= $checkbox_class;
								break;

			case 'radio'	:	/*
								 * Radiobutton
								 */
								if($POST_DO && ( $cnt_form["fields"][$key]['required'] || isset($_POST[$POST_name]) ) ) {
									$POST_val[$POST_name] = isset($_POST[$POST_name]) ? remove_unsecure_rptags(clean_slweg($_POST[$POST_name])) : false;
									if($cnt_form["fields"][$key]['required'] && ($POST_val[$POST_name] === false || $POST_val[$POST_name] == '')) {
										$POST_ERR[$key] = $cnt_form["fields"][$key]['error'];
									} else {
										$cnt_form["fields"][$key]['value'] = str_replace(' checked', '', $cnt_form["fields"][$key]['value']);
									}
								}
								//
								$form_value = explode("\n", $cnt_form["fields"][$key]['value']);
								$form_value = array_map('trim', $form_value);
								$form_value = array_diff($form_value, array(''));
								if($cnt_form["fields"][$key]['class']) {
									$form_field 	.= '<div class="'.$cnt_form["fields"][$key]['class'].'">';
									$checkbox_class  = '</div>';
								} else {
									$checkbox_class  = '';
								}
								if($cnt_form["fields"][$key]['style']) {
									$checkbox_style = ' style="'.$cnt_form["fields"][$key]['style'].'"';
								} else {
									$checkbox_style = '';
								}
								if(count($form_value) == 1 || count($form_value) == 0 || !$form_value) {
									// only 1 checkbox
									$checkbox_value = is_array($form_value) ? implode('', $form_value) : $form_value;
									$checkbox_value = trim($checkbox_value);
									if(isset($POST_val[$POST_name]) && $POST_val[$POST_name] == ($checkbox_value ? $checkbox_value : $form_name)) {
										$checkbox_value .= ' checked';
									}
									$checkbox_value = $checkbox_value ? html_specialchars($checkbox_value) : $form_name;
									$form_field .= '<input type="radio" name="'.$form_name.'" id="'.$form_name.'" ';
									if(substr($checkbox_value, -8) != ' checked') {
										$form_field .= 'value="' . $checkbox_value . '" />';
									} else {
										$checkbox_value = str_replace(' checked', '', $checkbox_value);
										$form_field .= 'value="' . $checkbox_value . '" checked="checked" />';
									}
									$form_field .= '<label for="'.$form_name.'"';
									$form_field .= $checkbox_style;
									$form_field .= '>'.$checkbox_value .'</label>';
									
								} else {
									// list of checkboxes
									$checkbox_counter = 0;
									$checkbox_spacer  = $cnt_form["fields"][$key]['size'] ? '<br />' : ' ';
									foreach($form_value as $checkbox_value) {
										if(isset($POST_val[$POST_name]) && $POST_val[$POST_name] == $checkbox_value) {
											$checkbox_value .= ' checked';
										}
										$checkbox_value =  html_specialchars(trim($checkbox_value));
										if($checkbox_counter) {
											$form_field .= $checkbox_spacer;
										}
										$form_field .= '<input type="radio" name="'.$form_name.'" id="'.$form_name.$checkbox_counter.'" ';
										if(substr($checkbox_value, -8) != ' checked') {
											$form_field .= 'value="' . $checkbox_value . '" />';
										} else {
											$checkbox_value = str_replace(' checked', '', $checkbox_value);
											$form_field .= 'value="' . $checkbox_value . '" checked="checked" />';
										}
										$form_field .= '<label for="'.$form_name.$checkbox_counter.'"';
										$form_field .= $checkbox_style;
										$form_field .= '>'.$checkbox_value .'</label>';
										$checkbox_counter++;
									}
								}
								$form_field .= $checkbox_class;
								break;

			case 'upload'	:	/*
								 * Upload
								 */
								if($cnt_form["fields"][$key]['value']) {
									$cnt_form['upload_value'] = str_replace('"', '', $cnt_form["fields"][$key]['value']);
									$cnt_form['upload_value'] = str_replace("'", '',$cnt_form['upload_value']);
									$cnt_form['upload_value'] = str_replace("\r'", '',$cnt_form['upload_value']);
									$cnt_form['upload_value'] = explode("\n", $cnt_form['upload_value']);
									if(is_array($cnt_form['upload_value']) && count($cnt_form['upload_value'])) {
										foreach($cnt_form['upload_value'] as $cnt_form['upload_key'] => $cnt_form['upload_val']) {
											$temp_array = explode('=', $cnt_form['upload_val']);
											unset($cnt_form['upload_value'][$cnt_form['upload_key']]);
											if(!empty($temp_array[0]) && !empty($temp_array[1])) {
												$cnt_form['upload_value'][$temp_array[0]] = $temp_array[1];
											}
										}
									}
								}
								if(empty($cnt_form['upload_value']['folder'])) {
									$cnt_form['upload_value']['folder'] = 'content/form/';
								}
								if(empty($cnt_form['upload_value']['attachment'])) {
									$cnt_form['upload_value']['attachment'] = 0;
								}
								if(empty($cnt_form['upload_value']['exclude'])) {
									$cnt_form['upload_value']['exclude'] = 'php,asp,php3,php4,php5,aspx,cfm,js';
								}
								//
								if($POST_DO && isset($_FILES[$POST_name])) {
									$POST_val[$POST_name]['folder'] = $cnt_form['upload_value']['folder'];
									$POST_val[$POST_name]['attachment'] = $cnt_form['upload_value']['attachment'];
									$POST_val[$POST_name]['name'] = '';
									$cnt_form['upload_value']['exclude'] = str_replace(' ', '', $cnt_form['upload_value']['exclude']);
									$cnt_form['upload_value']['exclude'] = str_replace('.', '', $cnt_form['upload_value']['exclude']);									
									$cnt_form['upload_value']['exclude'] = explode(',', $cnt_form['upload_value']['exclude']);
									$cnt_form['upload_value']['exclude'] = array_diff($cnt_form['upload_value']['exclude'], array(''));
									$cnt_form['upload_value']['exclude'] = implode('|', $cnt_form['upload_value']['exclude']);
									$cnt_form['upload_value']['exclude'] = strtolower($cnt_form['upload_value']['exclude']);
									$cnt_form['upload_value']['regexp'] = '/(.'.$cnt_form['upload_value']['exclude'].')$/';
									if($cnt_form["fields"][$key]['required'] && empty($_FILES[$POST_name]['name'])) {
										$POST_ERR[$key] = $cnt_form["fields"][$key]['error'];
									} elseif(!empty($_FILES[$POST_name]['name'])) {
										$cnt_form['upload_value']['filename'] = time().'_'.$_FILES[$POST_name]['name'];
										if( (!empty($cnt_form['upload_value']['maxlength']) && $_FILES[$POST_name]['size'] > intval($cnt_form['upload_value']['maxlength']))
											|| preg_match($cnt_form['upload_value']['regexp'], strtolower($_FILES[$POST_name]['name'])) 
											|| !@move_uploaded_file($_FILES[$POST_name]['tmp_name'], 
											   PHPWCMS_ROOT.'/'.$cnt_form['upload_value']['folder'].'/'.$cnt_form['upload_value']['filename'])
											   ) {
											   $POST_ERR[$key]  = $cnt_form["fields"][$key]['error'];
											   $POST_ERR[$key]	= str_replace('{MAXLENGTH}', empty($cnt_form['upload_value']['maxlength']) ? '' : fsize($cnt_form['upload_value']['maxlength'], ' '), $POST_ERR[$key]);
											   $POST_ERR[$key]  = str_replace('{FILESIZE}', !empty($_FILES[$POST_name]['size']) ? fsize($_FILES[$POST_name]['size'], ' ') : '', $POST_ERR[$key]);
											   $POST_ERR[$key]  = str_replace('{FILENAME}', !empty($_FILES[$POST_name]['name']) ? $_FILES[$POST_name]['name'] : '"n.a."', $POST_ERR[$key]);
											   $POST_ERR[$key]  = str_replace('{FILEEXT}', '.'.str_replace('|', ', .', str_replace(',', ', .', $cnt_form['upload_value']['exclude'])), $POST_ERR[$key]);											   
										} else {
											$POST_val[$POST_name]['name'] = $cnt_form['upload_value']['filename'];
										}
									}
									if(isset($POST_ERR[$key])) {
										@unlink($_FILES[$POST_name]['tmp_name']);
										@unlink(PHPWCMS_ROOT.'/'.$cnt_form['upload_value']['folder'].'/'.$cnt_form['upload_value']['filename']);
									}
								}
								//
								$form_field .= '<input type="file" name="'.$form_name.'" id="'.$form_name.'"';
								if(!empty($cnt_form['upload_value']['accept']) ) {
									$form_field .= ' accept="'.$cnt_form['upload_value']['accept'].'"';
								}
								if($cnt_form["fields"][$key]['size']) {
									$form_field .= ' size="'.$cnt_form["fields"][$key]['size'].'"';
								}
								if($cnt_form["fields"][$key]['max']) {
									$form_field .= ' maxlength="'.$cnt_form["fields"][$key]['max'].'"';
								} elseif (!empty($cnt_form['upload_value']['maxlength'])) {
									$form_field .= ' maxlength="'.$cnt_form['upload_value']['maxlength'].'"';
								}
								if($cnt_form["fields"][$key]['class']) {
									$form_field .= ' class="'.$cnt_form["fields"][$key]['class'].'"';
								}
								if($cnt_form["fields"][$key]['style']) {
									$form_field .= ' style="'.$cnt_form["fields"][$key]['style'].'"';
								}
								$form_field .= ' title="';
								if($cnt_form['upload_value']['maxlength']) {
									$form_field .= 'max. '.fsize($cnt_form['upload_value']['maxlength'],' ',1);
								}
								$form_field .= '" />';
								unset($cnt_form['upload_value']);
								break;

			case 'submit'	:	/*
								 * Submit
								 */
								if(strpos(strtolower($cnt_form["fields"][$key]['value']), 'src=') === false) {
									$form_field .= '<input type="submit" name="'.$form_name.'" id="'.$form_name.'" ';
									if($cnt_form["fields"][$key]['value'] != '') {
										$form_field .= 'value="'.html_specialchars($cnt_form["fields"][$key]['value']).'"';
									}
									if($cnt_form["fields"][$key]['class']) {
										$form_field .= ' class="'.$cnt_form["fields"][$key]['class'].'"';
									}
									if($cnt_form["fields"][$key]['style']) {
										$form_field .= ' style="'.$cnt_form["fields"][$key]['style'].'"';
									}
									$form_field .= ' />###RESET###';
								} else {
									$form_field .= '<input type="image" name="'.$form_name.'" id="'.$form_name.'" ';
									$form_field .= $cnt_form["fields"][$key]['value'];
									if($cnt_form["fields"][$key]['class']) {
										$form_field .= ' class="'.$cnt_form["fields"][$key]['class'].'"';
									}
									if($cnt_form["fields"][$key]['style']) {
										$form_field .= ' style="'.$cnt_form["fields"][$key]['style'].'"';
									}
									$form_field .= ' />###RESET###';
								}
								break;
								
			
			case 'reset'	:	/*
								 * Reset
								 */
								if(strpos(strtolower($cnt_form["fields"][$key]['value']), 'src=') === false) {
									$form_field .= '<input type="reset" name="'.$form_name.'" id="'.$form_name.'" ';
									if($cnt_form["fields"][$key]['value'] != '') {
										$form_field .= 'value="'.html_specialchars($cnt_form["fields"][$key]['value']).'"';
									}
									if($cnt_form["fields"][$key]['class']) {
										$form_field .= ' class="'.$cnt_form["fields"][$key]['class'].'"';
									}
									if($cnt_form["fields"][$key]['style']) {
										$form_field .= ' style="'.$cnt_form["fields"][$key]['style'].'"';
									}
									$form_field .= ' />';
								} else {
									$form_field .= '<img name="'.$form_name.'" id="'.$form_name.'" ';
									$form_field .= $cnt_form["fields"][$key]['value'];
									if($cnt_form["fields"][$key]['class']) {
										$form_field .= ' class="'.$cnt_form["fields"][$key]['class'].'"';
									}
									if($cnt_form["fields"][$key]['style']) {
										$form_field .= ' style="'.$cnt_form["fields"][$key]['style'].'"';
									}
									$form_field .= ' border="0" onclick="document.phpwcmsForm'.$crow["acontent_id"].'.reset();" />';
								}
								break;
	
			case 'break'	:	/*
								 * Break
								 */
								if($cnt_form["fields"][$key]['style'] || $cnt_form["fields"][$key]['class']) {
									$form_field .= '<div id="'.$form_name.'"';
									if($cnt_form["fields"][$key]['class']) {
										$form_field .= ' class="'.$cnt_form["fields"][$key]['class'].'"';
									}
									if($cnt_form["fields"][$key]['style']) {
										$form_field .= ' style="'.$cnt_form["fields"][$key]['style'].'"';
									}
									$form_field .= '>';
									$form_field .= $cnt_form["fields"][$key]['value'];
									$form_field .= '</div>';
								} else {
									$form_field .= $cnt_form["fields"][$key]['value'];
								}
								break;
	
			case 'breaktext':	/*
								 * Breaktext
								 */
								if($cnt_form["fields"][$key]['style'] || $cnt_form["fields"][$key]['class']) {
									$form_field .= '<span id="'.$form_name.'"';
									if($cnt_form["fields"][$key]['class']) {
										$form_field .= ' class="'.$cnt_form["fields"][$key]['class'].'"';
									}
									if($cnt_form["fields"][$key]['style']) {
										$form_field .= ' style="'.$cnt_form["fields"][$key]['style'].'"';
									}
									$form_field .= '>';
									$form_field .= nl2br(html_specialchars($cnt_form["fields"][$key]['value']));
									$form_field .= '</span>';
								} else {
									$form_field .= nl2br(html_specialchars($cnt_form["fields"][$key]['value']));
								}
								break;
	
			case 'captchaimg':	/*
								 * Captcha Images
								 */
								if(empty($cnt_form["fields"][$key]['value']) && ($cnt_form["fields"][$key]['style'] || $cnt_form["fields"][$key]['class'])) {
									$form_field .= '<div id="'.$form_name.'"';
									if($cnt_form["fields"][$key]['class']) {
										$form_field .= ' class="'.$cnt_form["fields"][$key]['class'].'"';
									}
									if($cnt_form["fields"][$key]['style']) {
										$form_field .= ' style="'.$cnt_form["fields"][$key]['style'].'"';
									}
									$form_field .= '>{CAPTCHA}</div>';
								} elseif(!empty($cnt_form["fields"][$key]['value'])) {
									$form_field .= $cnt_form["fields"][$key]['value'];
								} else {
									$form_field .= '{CAPTCHA}';
								}
								$form_field = str_replace('{CAPTCHA}', '<img src="img/captcha.php?regen=y&amp;'.time().'" alt="Captcha" border="0" />', $form_field);
								break;
								
			case 'newsletter':	/*
								 * Newsletter
								 */
								
								$form_newletter_setting					= array();
								$form_newletter_setting['double_optin'] = 0;
								$form_value								= array(); 
								
								if($POST_DO && ($cnt_form["fields"][$key]['required'] || isset($_POST[$POST_name]) ) ) {
									if(isset($_POST[$POST_name]) && is_array($_POST[$POST_name])) {
										$POST_val[$POST_name] = array_map('combined_POST_cleaning', $_POST[$POST_name]);
										$POST_val[$POST_name] = array_diff($POST_val[$POST_name], array(''));
										if(!count($POST_val[$POST_name])) {
											$POST_val[$POST_name] = false;
										}
									} else {
										$POST_val[$POST_name] = isset($_POST[$POST_name]) ? remove_unsecure_rptags(clean_slweg($_POST[$POST_name])) : false;
									}
									if($cnt_form["fields"][$key]['required'] && ($POST_val[$POST_name] === false || $POST_val[$POST_name] == '')) {
										$POST_ERR[$key] = $cnt_form["fields"][$key]['error'];
									} else {
										$cnt_form["fields"][$key]['value'] = str_replace(' checked', '', $cnt_form["fields"][$key]['value']);
									}
									
									if(isset($POST_val[$POST_name])) {
										$form_newletter_setting['selection'] = $POST_val[$POST_name];
									} else {
										$form_newletter_setting['selection'] = false;
									}
									
								}
								// prepare default settings for newsletter field
								$form_value_default		= convertStringToArray($cnt_form["fields"][$key]['value'], "\n", 'UNIQUE', false);
								foreach($form_value_default as $form_value_nl) {
								
									$form_value_nl		= explode('=', $form_value_nl, 2);
									$form_value_nl[0]	= trim($form_value_nl[0]);
									$form_value_nl[1]	= empty($form_value_nl[1]) ? '' : trim($form_value_nl[1]);
									
									if(empty($form_value_nl[0]) || empty($form_value_nl[1])) {
									
										continue;
									
									} else {
									
										switch($form_value_nl[0]) {
									
											case 'all':				$form_value[0] 								= $form_value_nl[1];					break;
											case 'email_field':		$form_newletter_setting['email_field'] 		= $form_value_nl[1];					break;
											case 'name_field':		$form_newletter_setting['name_field'] 		= $form_value_nl[1];					break;
											case 'sender_email':	$form_newletter_setting['sender_email'] 	= $form_value_nl[1];					break;
											case 'sender_name':		$form_newletter_setting['sender_name'] 		= $form_value_nl[1];					break;
											case 'url_subscribe':	$form_newletter_setting['url_subscribe'] 	= $form_value_nl[1];					break;
											case 'url_unsubscribe':	$form_newletter_setting['url_unsubscribe']	= $form_value_nl[1];					break;
											case 'subject':			$form_newletter_setting['subject']			= $form_value_nl[1];					break;
											case 'double_optin':	$form_newletter_setting['double_optin'] 	= intval($form_value_nl[1]) ? 1 : 0;	break;
											
											default:	
												if(intval($form_value_nl[0])) {
													$form_value_nl[0]  = intval($form_value_nl[0]);
													$form_value_nl[2]  = "SELECT COUNT(*) FROM ".DB_PREPEND."phpwcms_newsletter WHERE ";
													$form_value_nl[2] .= "newsletter_id=".$form_value_nl[0]." AND newsletter_trashed=0";
													if(_dbQuery($form_value_nl[2], 'COUNT')) {
														$form_value[ $form_value_nl[0] ] = $form_value_nl[1];
													} else {
														continue;
													}
							
												} else {
													continue;
												}
										}
									}
								}
								
								$form_newletter_setting['subscriptions'] = $form_value;
								
								if($cnt_form["fields"][$key]['class']) {
									$form_field 	.= '<div class="'.$cnt_form["fields"][$key]['class'].'">';
									$checkbox_class  = '</div>';
								} else {
									$checkbox_class  = '';
								}
								if($cnt_form["fields"][$key]['style']) {
									$checkbox_style = ' style="'.$cnt_form["fields"][$key]['style'].'"';
								} else {
									$checkbox_style = '';
								}
								// list of checkboxes
								$checkbox_counter = 0;
								$checkbox_spacer  = $cnt_form["fields"][$key]['size'] ? '<br />' : ' ';
								foreach($form_value as $checkbox_key => $checkbox_value) {
								
									if(isset($POST_val[$POST_name]) && is_array($POST_val[$POST_name])) {
										foreach($POST_val[$POST_name] as $postvar_value) {
											if($postvar_value == $checkbox_key) {
												$checkbox_key .= ' checked';
											}
										}
									}

									if($checkbox_counter) {
										$form_field .= $checkbox_spacer;
									}
									$form_field .= '<input type="checkbox" name="'.$form_name.'[]" id="'.$form_name.$checkbox_counter.'" ';
									if(substr($checkbox_key, -8) != ' checked' && substr($checkbox_value, -8) != ' checked') {
										$form_field .= 'value="' . $checkbox_key . '" />';
									} else {
										$checkbox_key   = str_replace(' checked', '', $checkbox_key);
										$checkbox_value = str_replace(' checked', '', $checkbox_value);
										$form_field    .= 'value="' . $checkbox_key . '" checked="checked" />';
									}
									$form_field .= '<label for="'.$form_name.$checkbox_counter.'"';
									$form_field .= $checkbox_style;
									$form_field .= '>'.$checkbox_value .'</label>';
									$checkbox_counter++;
								}
								$form_field .= $checkbox_class;
								break;					
								
			
		}

		// try to find correct sender name
		if($POST_DO && $cnt_form['sendernametype'] == 'formfield_'.$POST_name) {
		
			$cnt_form['sendername']	= cleanUpForEmailHeader($cnt_form["fields"][$key]['value']);
		
		}
		// try to build correct subject
		if($POST_DO && isset($cnt_form['subjectselect']) && $cnt_form['subjectselect'] == 'formfield_'.$POST_name) {
		
			$cnt_form['subject'] .= ' '.cleanUpForEmailHeader($cnt_form["fields"][$key]['value']);
			$cnt_form['subject']  = trim($cnt_form['subject']);
		
		}		

		// Build the form elements

		if($form_field && $cnt_form["fields"][$key]['type'] != 'hidden') {
		
			
			if($cnt_form['labelpos'] == 2) {
			
				// custom form template
				$POST_name_quoted = preg_quote($POST_name, '/');
				
				if(empty($POST_ERR[$key])) {
					// if error for field empty
					$form_cnt = preg_replace('/\[IF_ERROR:'.$POST_name_quoted.'\].*?\[\/IF_ERROR\]/s', '', $form_cnt);
					$form_cnt = preg_replace('/\[ELSE_ERROR:'.$POST_name_quoted.'\](.*?)\[\/ELSE_ERROR\]/s', '$1', $form_cnt);
					$form_cnt = str_replace('{ERROR:'.$POST_name.'}', '', $form_cnt);
				} else {
					// field error available
					$form_cnt = preg_replace('/\[IF_ERROR:'.$POST_name_quoted.'\](.*?)\[\/IF_ERROR\]/s', '$1', $form_cnt);
					$form_cnt = preg_replace('/\[ELSE_ERROR:'.$POST_name_quoted.'\].*?\[\/ELSE_ERROR\]/s', '', $form_cnt);
					$form_cnt = str_replace('{ERROR:'.$POST_name.'}', html_specialchars($POST_ERR[$key]), $form_cnt);
				}
								
				$form_cnt = str_replace('{'.$POST_name_quoted.'}', $form_field, $form_cnt);
				$form_cnt = str_replace('{LABEL:'.$POST_name_quoted.'}', html_specialchars($cnt_form["fields"][$key]['label']), $form_cnt);

			} else {
			
				// default table
				
				if($cnt_form["fields"][$key]['type'] == 'reset' && strpos($form_cnt, '###RESET###')) {
				
					$form_cnt = str_replace('###RESET###', $form_field, $form_cnt);
				
				} else {
				
					if($cnt_form["fields"][$key]['required']) {
						$cnt_form['labelClass']   = 'formLabelRequired';
						$cnt_form['labelReqMark'] = $cnt_form["cform_reqmark"];
					} else {
						$cnt_form['labelClass']   = 'formLabel';
						$cnt_form['labelReqMark'] = '';
					}
				
					if($cnt_form['labelpos'] == 0) {
						// label: field
						if($cnt_form["fields"][$key]['type'] != 'break') {
							$form_cnt .= "<tr>\n".'<td class="'.$cnt_form['labelClass'].'">';
							if($cnt_form["fields"][$key]['label'] != '') {
								$form_cnt .= $cnt_form['label_wrap'][0];
								$form_cnt .= html_specialchars($cnt_form["fields"][$key]['label']);
								$form_cnt .= $cnt_form['labelReqMark'];
								$form_cnt .= $cnt_form['label_wrap'][1];
							} else {
								$form_cnt .= '&nbsp;';
							}
							$form_cnt .= "</td>\n";
							$form_cnt .= '<td class="formField">'.$form_field."</td>\n</tr>\n";
						} else {
							// colspan for break
							$form_cnt .= '<tr><td colspan="2">'.$form_field."</td></tr>\n";
						}
					} else {
						// label:
						// field
						if($cnt_form["fields"][$key]['label'] != '') {
							$form_cnt .= '<tr><td class="'.$cnt_form['labelClass'].'">'.$cnt_form['label_wrap'][0];
							$form_cnt .= html_specialchars($cnt_form["fields"][$key]['label']);
							$form_cnt .= $cnt_form['labelReqMark'];
							$form_cnt .= $cnt_form['label_wrap'][1]."</td></tr>\n";
						}
						$form_cnt .= '<tr><td class="formField">'.$form_field."</td></tr>\n";
					}
				}
			
			}
		}

		$form_counter++;
	}
}

if(!empty($POST_DO) && empty($POST_ERR)) {

	$POST_attach = array();
	$POST_savedb = array();
	
	// now prepare form values for sending or storing
	if(isset($POST_val) && is_array($POST_val) && count($POST_val)) {
	
		foreach($POST_val as $POST_key => $POST_keyval) {
		
			$POST_valurl = '';
			
			if(isset($cnt_form["copyto"]) && $cnt_form["copyto"] == $POST_key) {
				$cnt_form["copyto"] = $POST_keyval;	
			}
		
			if(is_array($POST_keyval) && !isset($POST_keyval['folder'])) {
				// check if this is an array - but no upload value
				$POST_keyval = implode(', ', $POST_keyval);
			
			} elseif(is_array($POST_keyval) && isset($POST_keyval['folder'])) {
				// check if this is an array - and is an upload value
				$POST_valurl = PHPWCMS_URL.$POST_keyval['folder'].'/'.rawurlencode($POST_keyval['name']);
				if(isset($POST_keyval['attachment']) && $POST_keyval['attachment']) {
					$POST_attach[] = PHPWCMS_ROOT.'/'.$POST_keyval['folder'].'/'.$POST_keyval['name'];
				}	
				if(!$cnt_form['template_format']) {
					$POST_keyval = $POST_valurl;
				}
			}
	
			// prepare for storing in database
			if(!empty($cnt_form['savedb'])) {
				
				$POST_savedb[$POST_key] = empty($POST_valurl) ? $POST_keyval : $POST_valurl;
				
			}
			
			if($cnt_form['template_format']) { //HTML
			
				// seems to be wrong --> if(is_array($POST_keyval) && !isset($POST_keyval['folder'])) {
				if(is_string($POST_keyval)) {
					$POST_keyval = html_specialchars($POST_keyval);
				} elseif(is_array($POST_keyval) && isset($POST_keyval['folder'])) {
					$POST_keyval = '<a href="'.$POST_valurl.'" target="_blank">'.html_specialchars($POST_keyval['name']).'</a>';
				}
				
				$cnt_form['is_html_entity'] = true;

			} else {
				
				// remember the HTML entity status
				$cnt_form['is_html_entity'] = false;
			
			}
			
			// replace tags in email form
			$cnt_form['template'] = str_replace('{'. $POST_key . '}', $POST_keyval, $cnt_form['template']);
			
			//replace tags in the success form but not for redirect.
			if($cnt_form["onsuccess_redirect"] !== 1) {
				
				// check if it is htmlentity
				if(!$cnt_form['is_html_entity'] && $cnt_form["onsuccess_redirect"] === 2) {
					$POST_keyval = html_specialchars($POST_keyval);
				}
				$cnt_form["onsuccess"] = str_replace('{'. $POST_key . '}', $POST_keyval, $cnt_form["onsuccess"]);
			
			}
			
		}
		
		$cnt_form['template'] = str_replace('{FORM_URL}', FE_CURRENT_URL, $cnt_form['template']);
		$cnt_form['template'] = str_replace('{REMOTE_IP}', getRemoteIP(), $cnt_form['template']);
		$cnt_form['template'] = preg_replace('/\{DATE:(.*?)\}/e', 'date("$1")', $cnt_form['template']);
		
		if($cnt_form["onsuccess_redirect"] !== 1) {
			
			$cnt_form["onsuccess"] = str_replace('{REMOTE_IP}', getRemoteIP(), $cnt_form["onsuccess"]);
			$cnt_form['onsuccess'] = preg_replace('/\{(.*?)\}/', '', $cnt_form['onsuccess']);
		
		}
		
		$cnt_form['template'] = preg_replace('/\{(.*?)\}/', '', $cnt_form['template']);
		
		// check if there are form values which should be saved in db
		if(count($POST_savedb)) {
			
			$POST_savedb_sql  = 'INSERT INTO '.DB_PREPEND.'phpwcms_formresult ';
			$POST_savedb_sql .= '(formresult_pid, formresult_ip, formresult_content) VALUES (';
			$POST_savedb_sql .= $crow['acontent_id'].", '".aporeplace(getRemoteIP())."', '";
			$POST_savedb_sql .= aporeplace(serialize($POST_savedb))  . "')";
			$POST_savedb_sql  = _dbQuery($POST_savedb_sql, 'INSERT');
				
		}
	
	}


	// send mail
	require_once ('include/inc_ext/phpmailer/class.phpmailer.php');
	$cnt_form["target"]			= explode(';', $cnt_form["target"]);
	if(empty($cnt_form["subject"])) {
		$cnt_form["alt_subj"] = str_replace('http://', '', $phpwcms['site']);
		$cnt_form["alt_subj"] = substr($cnt_form["alt_subj"], 0, strpos($phpwcms['site'], '/')-1);
		$cnt_form["subject"]  = 'Webform: '.$cnt_form["alt_subj"];
	}
	
	// check for BCC Addresses
	$cnt_form['cc'] = empty($cnt_form['cc']) ? array() : convertStringToArray($cnt_form['cc'],';');
	
	
	// first try to send copy message
	if(!empty($cnt_form['sendcopy']) && !empty($cnt_form["copyto"]) && is_valid_email($cnt_form["copyto"])) {
		$cnt_form['cc'][]		= $cnt_form["copyto"];
		$cnt_form["fromEmail"]	= $cnt_form["copyto"];
	}
	
	// now run all CC -> but sent as full email to each CC recipient
	if(count($cnt_form['cc'])) {

		$mail = new PHPMailer();
		$mail->Mailer 			= $phpwcms['SMTP_MAILER'];
		$mail->Host 			= $phpwcms['SMTP_HOST'];
		$mail->Port 			= $phpwcms['SMTP_PORT'];
		if($phpwcms['SMTP_AUTH']) {
			$mail->SMTPAuth 	= 1;
			$mail->Username 	= $phpwcms['SMTP_USER'];
			$mail->Password 	= $phpwcms['SMTP_PASS'];
		}
		$mail->CharSet	 		= $phpwcms["charset"];
		$mail->IsHTML($cnt_form['template_format']);
		$mail->Subject			= $cnt_form["subject"];
		$mail->Body 			= $cnt_form['template'];
		if(!$mail->SetLanguage($phpwcms['default_lang'], '')) {
			$mail->SetLanguage('en');
		}
	
		$mail->From 		= $cnt_form['sender'];
		$mail->FromName		= $cnt_form['sendername'];
		$mail->Sender	 	= $cnt_form['sender'];

		$cnt_form["copytoError"] = array();

		foreach($cnt_form['cc'] as $cc_email) {
		
			$mail->AddAddress($cc_email);
		
			if(!$mail->Send()) {
				$cnt_form["copytoError"][] = html_specialchars($cc_email.' ('.$mail->ErrorInfo.')');
			}
			
			$mail->ClearAddresses();
			
		}
		
		if(count($cnt_form["copytoError"])) {
			$cnt_form["copytoError"] = implode('<br />', $cnt_form["copytoError"]);
		} else {
			unset($cnt_form["copytoError"]);
		}
		
		unset($mail);
	}
	
	// now send original message
	$mail = new PHPMailer();
	$mail->Mailer 			= $phpwcms['SMTP_MAILER'];
	$mail->Host 			= $phpwcms['SMTP_HOST'];
	$mail->Port 			= $phpwcms['SMTP_PORT'];
	if($phpwcms['SMTP_AUTH']) {
		$mail->SMTPAuth 	= 1;
		$mail->Username 	= $phpwcms['SMTP_USER'];
		$mail->Password 	= $phpwcms['SMTP_PASS'];
	}
	$mail->CharSet	 		= $phpwcms["charset"];
	$mail->IsHTML($cnt_form['template_format']);
	$mail->Subject			= $cnt_form["subject"];
	$mail->Body 			= $cnt_form['template'];

	if(!$mail->SetLanguage($phpwcms['default_lang'], '')) {
		$mail->SetLanguage('en');
	}
	if(empty($cnt_form["fromEmail"])) {
		$cnt_form["fromEmail"] = 'noreply@test.com';
	}
	$mail->From 		= $cnt_form['sender'];
	$mail->FromName		= $cnt_form['sendername'];
	$mail->Sender	 	= $cnt_form['sender'];

	if(!empty($cnt_form["target"]) && is_array($cnt_form["target"]) && count($cnt_form["target"])) {
	
		foreach($cnt_form["target"] as $e_value) {
			$mail->AddAddress(trim($e_value));
		}

	} else {
		// use default email address
		$mail->AddAddress($phpwcms['SMTP_FROM_EMAIL']);
	}
	
	if(count($POST_attach)) {
		foreach($POST_attach as $attach_file) {
			$mail->AddAttachment($attach_file);
		}
	}

	if(!$mail->Send()) {
		$CNT_TMP .= '<p>'.html_specialchars($mail->ErrorInfo).'</p>';
	} else {
	
		// check if user should be registered for newsletter
		if(isset($form_newletter_setting['selection']) && count($form_newletter_setting['selection'])) {
		
			// first check if neccessary form field is valid email
			if(isset($POST_val[ $form_newletter_setting['email_field'] ]) && is_valid_email($POST_val[ $form_newletter_setting['email_field'] ])) {
		
				// ok now I know we can store email as newsletter recipient
				$form_newletter_setting['email_field'] = $POST_val[ $form_newletter_setting['email_field'] ];
				
				// now try to find fields to build recipient's name, if empty name is same as email
				if(!empty($form_newletter_setting['name_field'])) {
				
					// split by "+"
					$form_newletter_setting['name_field_tmp'] = explode('+', $form_newletter_setting['name_field']);
					$form_newletter_setting['name_field'] = '';
					foreach($form_newletter_setting['name_field_tmp'] as $form_value_nl) {
					
						// empty - continue
						if(empty($form_value_nl)) continue;
						
						// now check if field name exists and build corresponding name value
						if(empty($POST_val[ trim($form_value_nl) ])) {
							$form_newletter_setting['name_field'] .= $form_value_nl;
						} else {
							$form_value_nl = trim($form_value_nl);
							$form_newletter_setting['name_field'] .= $POST_val[ $form_value_nl ];
						}

					}
					$form_newletter_setting['name_field'] = trim($form_newletter_setting['name_field']);

				}
				
				if(empty($form_newletter_setting['name_field'])) {
					$form_newletter_setting['name_field'] = $form_newletter_setting['email_field'];
				}
				
				$form_newletter_setting['hash'] = shortHash( $form_newletter_setting['email_field'].time() );
				
				// create SQL query to populate recipient into recipients db
				$form_newletter_setting['sql']  = 'INSERT INTO '.DB_PREPEND.'phpwcms_address ';
				$form_newletter_setting['sql'] .= '(address_key, address_email, address_name, address_verified, ';
				$form_newletter_setting['sql'] .= 'address_subscription, address_url1, address_url2) VALUES (';
				$form_newletter_setting['sql'] .= "'".aporeplace($form_newletter_setting['hash'])."', ";
				$form_newletter_setting['sql'] .= "'".aporeplace($form_newletter_setting['email_field'])."', ";
				$form_newletter_setting['sql'] .= "'".aporeplace($form_newletter_setting['name_field'])."', ";
				$form_newletter_setting['sql'] .= (empty($form_newletter_setting['double_optin']) ? 1 : 0) .", ";
				$form_newletter_setting['sql'] .= "'".aporeplace(serialize($form_newletter_setting['selection']))."', ";
				$form_newletter_setting['sql'] .= "'".aporeplace(empty($form_newletter_setting['url_subscribe']) ? '' : $form_newletter_setting['url_subscribe'])."', ";
				$form_newletter_setting['sql'] .= "'".aporeplace(empty($form_newletter_setting['url_unsubscribe']) ? '' : $form_newletter_setting['url_unsubscribe'])."'";
				$form_newletter_setting['sql'] .= ')';
				
				// save recipient in db and send verify message in case of double opt-in
				$form_newletter_setting['query_result'] = @_dbQuery($form_newletter_setting['sql'], 'INSERT');
				
				// now send opt-in email
				if(!empty($form_newletter_setting['double_optin'])) {
				
					if(empty($cnt_form['verifyemail'])) {
						$cnt_form['verifyemail'] = file_get_contents(PHPWCMS_TEMPLATE.'inc_cntpart/newsletter/email/default.opt-in.txt');
						if(empty($cnt_form['verifyemail'])) {
							$cnt_form['verifyemail']  = 'Hi {NEWSLETTER_NAME},'.LF.LF.'Someone (presumably you) on {SITE}'.LF.'subscribed to these newsletters:'.LF;
							$cnt_form['verifyemail'] .= '{SUBSCRIPTIONS}'.LF.LF.'The following email was requested for subscription'.LF.'{NEWSLETTER_EMAIL}'.LF.LF;
							$cnt_form['verifyemail'] .= 'If you requested this subscription, visit the following URL'.LF.'{NEWSLETTER_VERIFY}'.LF.'to verify and activate it.'.LF.LF;
							$cnt_form['verifyemail'] .= 'Ignore the message or visit the following URL'.LF.'{NEWSLETTER_DELETE}'.LF.'and nothing will happen.'.LF.LF.LF;
							$cnt_form['verifyemail'] .= 'With best regards'.LF.'Webmaster'.LF.LF.'--'.LF.'{DATE:m/d/Y H:i:s}, IP: {IP}'.LF;
						}
					}
					
					$form_newletter_setting['hash'] = rawurlencode($form_newletter_setting['hash']);
					
					$form_newletter_setting['selection_text'] = array();
					foreach($form_newletter_setting['selection'] as $form_value_nl) {
						$form_newletter_setting['subscr_text'][] = '[X] '.$form_newletter_setting['subscriptions'][$form_value_nl];
					}
					
					if($form_newletter_setting['email_field'] == $form_newletter_setting['name_field']) $form_newletter_setting['name_field'] = '';
				
					$cnt_form['verifyemail'] = str_replace('{NEWSLETTER_NAME}', $form_newletter_setting['name_field'], $cnt_form['verifyemail']);
					$cnt_form['verifyemail'] = str_replace('{SUBSCRIPTIONS}', implode(LF, $form_newletter_setting['subscr_text']), $cnt_form['verifyemail']);
					$cnt_form['verifyemail'] = str_replace('{NEWSLETTER_EMAIL}', $form_newletter_setting['email_field'], $cnt_form['verifyemail']);
					$cnt_form['verifyemail'] = str_replace('{NEWSLETTER_VERIFY}', PHPWCMS_URL.'verify.php?s='.$form_newletter_setting['hash'], $cnt_form['verifyemail']);
					$cnt_form['verifyemail'] = str_replace('{NEWSLETTER_DELETE}', PHPWCMS_URL.'verify.php?u='.$form_newletter_setting['hash'], $cnt_form['verifyemail']);
					$cnt_form['verifyemail'] = replaceGlobalRT($cnt_form['verifyemail']);
					
					if(empty($form_newletter_setting['sender_email'])) $form_newletter_setting['sender_email'] = $cnt_form['sender'];
					if(empty($form_newletter_setting['sender_name']))  $form_newletter_setting['sender_name']  = $cnt_form['sendername'];
					
					// now send verification email
					@sendEmail(array(	'recipient'	=> $form_newletter_setting['email_field'],
										'toName'	=> $form_newletter_setting['name_field'],
										'subject'	=> $form_newletter_setting['subject'],
										'text'		=> $cnt_form['verifyemail'],
										'from'		=> $form_newletter_setting['sender_email'],
										'fromName'	=> $form_newletter_setting['sender_name'],
										'sender'	=> $form_newletter_setting['sender_email']   ));
				
				}
		
			}
		
		}
	
		if($cnt_form["onsuccess_redirect"] === 1) {
			// redirect on success
			headerRedirect(str_replace('{SITE}', PHPWCMS_URL, $cnt_form["onsuccess"]));
			
		} elseif($cnt_form["onsuccess"]) {
			// success
			
			$CNT_TMP .= '<div';
			$CNT_TMP .= $cnt_form["class"] ? ' class="'.$cnt_form["class"].'">' : '>';
					
			if($cnt_form["onsuccess_redirect"] === 0) {
				$CNT_TMP .= '<p>'.nl2br(html_specialchars($cnt_form["onsuccess"])).'</p>';
			} else {
				$CNT_TMP .= $cnt_form["onsuccess"];
			}
			$CNT_TMP .= '</div>';
		}

	}
	if(!empty($cnt_form["copytoError"])) {
		$CNT_TMP .= '<p>'.$cnt_form["copytoError"].'</p>';
	}
	
	unset($mail);
	
	$form_cnt = '';
	
} elseif(isset($POST_ERR)) {
	// do on POST_ERROR
	
	if(isset($_FILES)) {
		foreach($_FILES as $file_key => $file_val) {
			@unlink($_FILES[$file_key]['tmp_name']);
		}
		if(isset($POST_val) && count($POST_val)) {
			foreach($POST_val as $file_key => $file_val) {
				@unlink(PHPWCMS_ROOT.'/'.$cnt_form['upload_value']['folder'].'/'.$POST_val[$file_key]['name']);
			}
		}
	}
	
	if($cnt_form["onerror_redirect"] === 1) {
	
		headerRedirect(str_replace('{SITE}', PHPWCMS_URL, $cnt_form["onerror"]));
	
	} else {
	
		if($cnt_form["onerror"]) {
		
			if($cnt_form["onerror_redirect"] === 0) {
				$form_error_text = '<p>'.nl2br(html_specialchars($cnt_form["onerror"])).'</p>';
			} else {
				$form_error_text = $cnt_form["onerror"];
			}
		}
	
		$POST_ERR = array_diff(	$POST_ERR , array('') );
		$POST_ERR = array_map( 'html_specialchars', $POST_ERR );
		if($cnt_form['labelpos'] != 2 && count( $POST_ERR ) ) {
			$form_error = "<tr>\n";
			if($cnt_form['labelpos'] == 0) { // label: field
				$form_error .= '<td class="'.$cnt_form['labelClass'].'">'."&nbsp;</td>\n";
			}
			$form_error .= '<td'.(!empty($cnt_form["error_class"]) ? ' class="'.$cnt_form["error_class"].'"' : '').'>';
			$form_error .= implode("<br />", $POST_ERR);
			$form_error .= "</td>\n</tr>\n";
		
			$form_cnt = $form_error.$form_cnt;
			unset($form_error);
		}
		
	}

} else {

	// form was not send yet
	// display startup text	

	if(!empty($cnt_form['startup'])) {

		if(empty($cnt_form['startup_html'])) {
		
			$CNT_TMP .= LF . '<p>'.nl2br(html_specialchars($cnt_form['startup'])).'</p>' . LF;
			
		} else {

			$CNT_TMP .= LF . $cnt_form['startup'] . LF;

		}

	}
}


if($form_cnt) {
	$form_cnt = str_replace('###RESET###', '', $form_cnt);
	$cnt_form["class_close"] = '';
	if($cnt_form["class"]) {
		$CNT_TMP .= '<div class="'.$cnt_form["class"].'">';
		$cnt_form["class_close"] = '</div>';
	}
	$CNT_TMP .= $form_error_text;
	$CNT_TMP .= '<form name="phpwcmsForm'.$crow["acontent_id"].'" id="phpwcmsForm'.$crow["acontent_id"].'" ';
	$CNT_TMP .= 'action="'.FE_CURRENT_URL.'#jumpForm'.$crow["acontent_id"].'" method="post" enctype="multipart/form-data">';

	if($cnt_form['labelpos'] == 2) {
	
		if(isset($POST_ERR) && count($POST_ERR)) {
			$form_cnt = preg_replace('/\[IF_ERROR\](.*?)\[\/IF_ERROR\]/s', '$1', $form_cnt);
			$form_cnt = preg_replace('/\[ELSE_ERROR\].*?\[\/ELSE_ERROR\]/s', '', $form_cnt);
		} else {
			$form_cnt = preg_replace('/\[IF_ERROR\].*?\[\/IF_ERROR\]/s', '', $form_cnt);
			$form_cnt = preg_replace('/\[ELSE_ERROR\](.*?)\[\/ELSE_ERROR\]/s', '$1', $form_cnt);
		}
		$CNT_TMP .= "\n". $form_cnt ."\n";
	} else {
		$CNT_TMP .= '<table cellspacing="0" cellpadding="0" border="0">';
		$CNT_TMP .= "\n".$form_cnt.'</table>';
	}
	
	$CNT_TMP .= '<input type="hidden" name="cpID'.$crow["acontent_id"].'" value="'.$crow["acontent_id"].'" />';
	$CNT_TMP .= $form_field_hidden;
	$CNT_TMP .=	getFormTrackingValue(); //hidden form tracking field
	$CNT_TMP .= '</form>'.$cnt_form["class_close"];
}

unset( $form, $form_cnt, $form_cnt_2, $form_field, $form_field_hidden, $form_counter, $form_error_text, $POST_ERR );

?>