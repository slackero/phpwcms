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

//email form

$crow['attr_class_id'] = array();
if($crow['acontent_attr_class']) {
    $crow['attr_class_id'][] = 'class="'.html($crow['acontent_attr_class']).'"';
}
if($crow['acontent_attr_id']) {
    $crow['attr_class_id'][] = 'id="'.html($crow['acontent_attr_id']).'"';
}

if(($crow['attr_class_id'] = implode(' ', $crow['attr_class_id']))) {
    $CNT_TMP .= '<div '.$crow['attr_class_id'].'>';
    $crow['attr_class_id_close'] = '</div>';
} else {
    $crow['attr_class_id_close'] = '';
}

$CNT_TMP .= headline($crow["acontent_title"], $crow["acontent_subtitle"], $template_default["article"]);
$cform = explode("#:#", $crow["acontent_form"]);
if(trim($cform[0])) {
	$form_name = "form_".generic_string(6);
	$cform_fields	= explode("\n", base64_decode($cform[0]));
	$form_hidden_field = '';

	$CNT_TMP .= "<form action=\"include/inc_act/act_formmailer.php\" method=\"post\" name=\"";
	$CNT_TMP .= $form_name."\" target=\"_self\" id=\"".$form_name;
	$CNT_TMP .= "\" style=\"margin:0px 0px 0px 0px; padding: 0px 0px 0px 0px;\">";
	$CNT_TMP .= "<table border=\"0\" cellspacing=\"2\" cellpadding=\"0\"";
	$CNT_TMP .= ($template_default["article"]["form_align"]) ? ' align="'.$template_default["article"]["form_align"].'"' : '';
	$CNT_TMP .= ">\n";


	foreach($cform_fields as $key => $value) {
		$cfield = explode("|", $value);
		list($cfield_length, $cfield_max_height) = explode(",", $cfield[4]);
		$cfield_length = intval($cfield_length); $cfield_max_height = intval($cfield_max_height);
		$cfield[6] = intval($cfield[6]);
		if($cfield[2]) $cfield_required[] = $cfield[1]; //Required Fields

		switch($cfield[0]) {
						//INPUT TEXT
			case "IT":	$CNT_TMP .= "<tr><td class=\"formLabel\" align=\"right\">".must_filled($cfield[2]).$cfield[3]."</td><td>";
						$CNT_TMP .= "<input type=\"text\" name=\"".$cfield[1]."\" value=\"".html_specialchars(trim($cfield[5]))."\" size=\"".$cfield_length."\" ";
						$CNT_TMP .= ($cfield_max_height) ? "maxlength=\"".$cfield_max_height."\" " : "";
						$CNT_TMP .= "id=\"".$cfield[1]."\" class=\"inputText\"".(($cfield[6])?" style=\"width:".$cfield[6]."px\"":"")." /></td></tr>\n";
						break;
			case "IN":	$CNT_TMP .= "<tr><td class=\"formLabel\" align=\"right\">".must_filled($cfield[2]).$cfield[3]."</td><td>";
						$CNT_TMP .= "<input type=\"text\" name=\"".$cfield[1]."\" value=\"".html_specialchars(trim($cfield[5]))."\" size=\"".$cfield_length."\" ";
						$CNT_TMP .= "onKeyUp=\"if(!parseInt(this.value,10)) {var x=this.value; this.value=x.substr(0, x.length-1)}\" ";
						$CNT_TMP .= ($cfield_max_height) ? "maxlength=\"".$cfield_max_height."\" " : "";
						$CNT_TMP .= "id=\"".$cfield[1]."\" class=\"inputText\"".(($cfield[6])?" style=\"width:".$cfield[6]."px\"":"")." /></td></tr>\n";
						break;
						//INPUT PASSWORD
			case "IP":	$CNT_TMP .= "<tr><td class=\"formLabel\" align=\"right\">".must_filled($cfield[2]).$cfield[3]."</td><td>";
						$CNT_TMP .= "<input type=\"password\" name=\"".$cfield[1]."\" size=\"".$cfield_length."\" ";
						$CNT_TMP .= ($cfield_max_height) ? "maxlength=\"".$cfield_max_height."\" " : "";
						$CNT_TMP .= "id=\"".$cfield[1]."\" class=\"inputText\"".(($cfield[6])?" style=\"width:".$cfield[6]."px\"":"")." /></td></tr>\n";
						break;
						//INPUT HIDDEN
			case "IH":	$form_hidden_field .= "<input type=\"hidden\" name=\"".$cfield[1]."\" ";
						$form_hidden_field .= "value=\"".html_specialchars(trim($cfield[5]))."\" id=\"".$cfield[1]."\" />";
						break;
						//TEXTAREA
			case "TA":	$CNT_TMP .= "<tr><td class=\"formLabel\" align=\"right\" valign=\"top\">".spacer(1,14).must_filled($cfield[2]).$cfield[3]."</td><td>";
						$CNT_TMP .= "<textarea name=\"".$cfield[1]."\" cols=\"".$cfield_length."\" rows=\"".$cfield_max_height."\" ";
						$CNT_TMP .= "id=\"".$cfield[1]."\" class=\"formTextArea\"".(($cfield[6])?" style=\"width:".$cfield[6]."px\"":"").">";
						$CNT_TMP .= html_specialchars(trim($cfield[5]))."</textarea></td></tr>\n";
						break;
						//SELECT MENU
			case "SM":	$cfield_select = explode("#", $cfield[5]);
						if(sizeof($cfield_select)) {
							$CNT_TMP .= "<tr><td class=\"formLabel\" align=\"right\">".must_filled($cfield[2]).$cfield[3]."</td><td>";
							$CNT_TMP .= "<select name=\"".$cfield[1]."\" id=\"".$cfield[1]."\" class=\"selectMenu\"";
							$CNT_TMP .= (($cfield[6])?" style=\"width:".$cfield[6]."px\"":"").">\n";
							foreach($cfield_select as $select_key => $select_value) {
								$option = explode("%", $select_value);
								$option_value	= trim($option[0]);
								$option_text	= (!empty($option[1])) ? trim($option[1]) : $option_value;
								if($option_value) {
									$CNT_TMP .= "<option value=\"".html_specialchars($option_value)."\">".html_specialchars($option_text)."</option>\n";
								}
							}
							$CNT_TMP .= "</select></td></tr>\n";
						}
						break;
						//SELECT MENU
			case "SL":	$cfield_select = explode("#", $cfield[5]);
						if(sizeof($cfield_select)) {
							$cfield[1] = $cfield[1].(($cfield_max_height) ? "[]" : "");
							$CNT_TMP .= "<tr><td class=\"formLabel\" align=\"right\" valign=\"top\">".spacer(1,13).must_filled($cfield[2]).$cfield[3]."</td><td>";
							$CNT_TMP .= "<select name=\"".$cfield[1]."\" id=\"".$cfield[1]."\" class=\"selectList\"";
							$CNT_TMP .= (($cfield[6])?" style=\"width:".$cfield[6]."px\"":"");
							$CNT_TMP .= " size=\"".$cfield_length."\"".(($cfield_max_height) ? " multiple" : "").">\n";
							foreach($cfield_select as $select_key => $select_value) {
								$option = explode("%", $select_value);
								$option_value	= trim($option[0]);
								$option_text	= (!empty($option[1])) ? trim($option[1]) : $option_value;
								if($option_value) {
									$CNT_TMP .= "<option value=\"".html_specialchars($option_value)."\">".html_specialchars($option_text)."</option>\n";
								}
							}
							$CNT_TMP .= "</select></td></tr>\n";
						}
						break;
						//CHECKBOX
			case "IC":	$cfield_check = explode("#", $cfield[5]);
						if(sizeof($cfield_check) && trim($cfield[5])) {
							$cfield[1] = $cfield[1]."[]";
							$CNT_TMP .= "<tr><td class=\"formLabel\" align=\"right\" valign=\"top\">".spacer(1,14).must_filled($cfield[2]).$cfield[3]."</td><td>";
							$CNT_TMP .= "<table border=\"0\" cellspacing=\"0\" cellpadding=\"0\">\n";
							$count_cell = 0;

							foreach($cfield_check as $check_value) {

								$check = explode("%", $check_value);
								//list($check_text, $check_marked, $check_val) = explode("%", $check_value);
								$check_marked = (!empty($check[1]) && intval($check[1])) ? 1 : 0;
								$check_val = (empty($check[2])) ? 1 : html_specialchars(trim($check[2]));

								$CNT_TMP .= (!$count_cell) ? "<tr>" : "";
								$CNT_TMP .= "<td><input type=\"checkbox\" name=\"".$cfield[1]."\" value=\"".$check_val."\"";
								$CNT_TMP .= (($check_marked)?' checked="checked"':"")." /></td><td class=\"checkLabel\"";
								$CNT_TMP .= (($cfield_length) ? " width=\"".$cfield_length."\"" : "").">";
								$CNT_TMP .= html_specialchars($check[0])."</td>";

								$count_cell++;

								if($count_cell == $cfield_max_height) {
									$CNT_TMP .= "</tr>\n";
									$count_cell = 0;
								}

							}
							if(!$cfield_max_height) $CNT_TMP .= "</tr>\n"; //if only 1 row
							if($count_cell < $cfield_max_height && $count_cell) {
								for($i=$count_cell; $count_cell < $cfield_max_height; $count_cell++) {
									$CNT_TMP .= "<td colspan=\"2\">".spacer(1,1)."</td>";
								}
								$CNT_TMP .= "</tr>\n";
							}
							$CNT_TMP .= "</table></td></tr>\n";
						}
						break;
						//RADIO BUTTON
			case "IR":	$cfield_radio = explode("#", $cfield[5]);
						if(sizeof($cfield_radio) && trim($cfield[5])) {
							$CNT_TMP .= "<tr><td class=\"formLabel\" align=\"right\" valign=\"top\">".spacer(1,14).must_filled($cfield[2]).$cfield[3]."</td><td>";
							$CNT_TMP .= "<table border=\"0\" cellspacing=\"0\" cellpadding=\"0\">\n";
							$count_cell = 0;

							foreach($cfield_radio as $radio_value) {

								$radio = explode("%", $radio_value);
								$radio_marked = (!empty($radio[1]) && intval($radio[1])) ? 1 : 0;
								$radio_val = (empty($radio[2])) ? 1 : html_specialchars(trim($radio[2]));

								$CNT_TMP .= (!$count_cell) ? "<tr>" : "";
								$CNT_TMP .= "<td><input type=\"radio\" name=\"".$cfield[1]."\" value=\"".$radio_val."\"";
								$CNT_TMP .= (($radio_marked)?' checked="checked"':"")." /></td><td class=\"radioLabel\"";
								$CNT_TMP .= (($cfield_length) ? " width=\"".$cfield_length."\"" : "").">";
								$CNT_TMP .= html_specialchars($radio[0])."</td>";

								$count_cell++;

								if($count_cell == $cfield_max_height) {
									$CNT_TMP .= "</tr>\n";
									$count_cell = 0;
								}

							}
							if(!$cfield_max_height) $CNT_TMP .= "</tr>\n"; //if only 1 row
							if($count_cell < $cfield_max_height && $count_cell) {
								for($i=$count_cell; $count_cell < $cfield_max_height; $count_cell++) {
									$CNT_TMP .= "<td colspan=\"2\">".spacer(1,1)."</td>";
								}
								$CNT_TMP .= "</tr>\n";
							}
							$CNT_TMP .= "</table></td></tr>\n";
						}
						break;
						//COUNTRY MENU
			case "SC":	$CNT_TMP .= "<tr><td class=\"formLabel\" align=\"right\">".must_filled($cfield[2]).$cfield[3]."</td><td>";
						$CNT_TMP .= "<select name=\"".$cfield[1]."\" id=\"".$cfield[1]."\" class=\"selectMenu\"";
						$CNT_TMP .= (($cfield[6])?" style=\"width:".$cfield[6]."px\"":"").">\n";
						$CNT_TMP .= list_country($cfield[5]);
						$CNT_TMP .= "</select></td></tr>\n";
						break;
						// Captcha Image
			case "CA":	$CNT_TMP .= "<tr><td class=\"formLabel\" align=\"right\">".must_filled($cfield[2]).$cfield[3]."</td><td>";
						$CNT_TMP .= '<table cellpadding="0" cellspacing="0" border="0"><tr><td>';
						$CNT_TMP .= "<input type=\"text\" name=\"".$cfield[1]."\" value=\"".html_specialchars(trim($cfield[5]))."\" size=\"".$cfield_length."\" ";
						$CNT_TMP .= ($cfield_max_height) ? "maxlength=\"".$cfield_max_height."\" " : "";
						$CNT_TMP .= "id=\"".$cfield[1]."\" class=\"inputText\"".(($cfield[6])?" style=\"width:".$cfield[6]."px\"":"")." />";
						$CNT_TMP .= "</td><td>&nbsp;&nbsp;</td><td>".'<img src="img/captcha.php?regen=y&amp;'.time().'" alt="Captcha" />'."</tr></table></td></tr>\n";
						break;
		}
	}

	$CNT_TMP .= "<tr><td colspan=\"2\">".spacer(1,10)."</td></tr>\n";
	$CNT_TMP .= "<tr><td>".spacer(1,1)."</td><td>".$form_hidden_field;
	$CNT_TMP .= "<input type=\"submit\" name=\"submit\" value=\"".html_specialchars(trim($cform[3]))."\" class=\"formButton\" />";
	$CNT_TMP .= "<input type=\"hidden\" name=\"subject\" value=\"".$cform[1]."\" />";
	$CNT_TMP .= "<input type=\"hidden\" name=\"recipient\" value=\"".$cform[2]."\" />";
	$CNT_TMP .=	getFormTrackingValue();
	$CNT_TMP .= (sizeof($cfield_required)) ? "<input type=\"hidden\" name=\"required\" value=\"".implode(",", $cfield_required)."\" />" : "";
	$CNT_TMP .= "<input type=\"hidden\" name=\"type\" value=\"".$cform[4]."\" /></td></tr>\n";
	$CNT_TMP .= "</table></form>\n";

}

$CNT_TMP .= $crow['attr_class_id_close'];