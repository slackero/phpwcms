<?php
/**
 * phpwcms
 *
 * @author Oliver Georgi <og@phpwcms.org>
 * @copyright Copyright (c) 2002-2026, Oliver Georgi
 * @license http://opensource.org/licenses/GPL-2.0 GNU GPL-2
 *
 **/

 //$output = div/js

  //custom field code for input text
function get_customfield_str($output, $key, $custom_field ,$fields, $value, $custom_field_placeholder) {
  if ($output == 'div') {
    //build html code
    $srccode = '<input type="text" name="customfield';
    if(isset($key)) {
      $srccode .= '['.$key.']';
    }
    $srccode .= '['.$custom_field.']" value="';
    if(isset($custom_field)) {
      $srccode .= html($custom_field);
    }
    $srccode .= '"';
    if(!empty($fields['maxlength'])) {
      $srccode .= ' maxlength="'.$fields['maxlength'].'"';
    }
    $srccode .= ' class="form-control form-control-sm" '.$custom_field_placeholder.' />';

  } else {
    //build javascript code

    $srccode = "  new_entry += '<input type=\"text\" name=\"customfield[' + entry_number + '][".$custom_field."]\" value=\"\"";
    if(!empty($fields['maxlength'])) {
      $srccode .= " maxlength=\"".$fields['maxlength']."\"";
    }
    $srccode .= " class=\"form-control\" ". $custom_field_placeholder." '+'/>';";

  }
  return $srccode;
}

 //custom field code for single checkbox (visible)
function get_customfield_bool($output, $key, $custom_field ,$fields, $value, $custom_field_placeholder) {
  if ($output == 'div') {
    //build html code
    $srccode = '<label class="form-check-label">';
    $srccode .= '<input class="form-check-input" type="checkbox" name="customfield';
    if(isset($key)) {
      $srccode .= '['.$key.']';
    }
    $srccode .= '['.$custom_field.']" value="1"';
    if((!empty($value)) || (!isset($value) && !empty($fields['default']))) {
      $srccode .=' checked="checked"';
    }
    $srccode .= '/> '.html($fields['legend']).'</label>';

  } else {
    //build javascript code
    $srccode = "  new_entry += '<label class=\"form-check-label\">';";
    $srccode .= "  new_entry += '<input class=\"form-check-input\" type=\"checkbox\" name=\"customfield[' + entry_number + '][".$custom_field."]\" value=\"1\"";
    if(!empty($fields['default'])) {
      $srccode .= ' checked="checked" ';
    }
    $srccode .= "'+'/> ".html($fields['legend'])."</label>';";
  }
  return $srccode;
}

//custom field code for select
function get_customfield_select($output, $key, $custom_field ,$fields, $value, $custom_field_placeholder) {
  if ($output == 'div') {
    //build html code
    $srccode ='<select name="customfield';
    if(isset($key)) {
      $srccode .= '['.$key.']';
    }
    $srccode .= '['.$custom_field.']" class="custom-select form-control form-control-sm">';
    foreach($fields['values'] as $option_key => $option_label) {
      $srccode .= '<option value="' . ($option_key === 'empty' ? '' : $option_key) .'"';
      if(isset($value) && $value === $option_key) {
        $srccode .= 'selected="selected"';
      } elseif(empty($value) && !empty($fields['default']) && $fields['default'] === $option_key) {
        $srccode .= ' selected="selected"';
      }
      $srccode .= '> '. html($option_label).'</option>';
    }
    $srccode .='</select>';
  } else {
    //build javascript code
    $srccode = "  new_entry += '<select name=\"customfield[' + entry_number + '][".$custom_field.">]\" class=\"form-control custom-select form-control-sm\">';";
    foreach($fields['values'] as $option_key => $option_label){
      $srccode .= "  new_entry += '<option value=\"".($option_key === 'empty' ? '' : $option_key)."\"";
      if(!empty($fields['default']) && $fields['default'] === $option_key) {
        $srccode .= " selected=\"selected\"";
      }
      $srccode .= ">". html($option_label) ."<'+'/option>';";
    }
    $srccode .= "  new_entry += '</select>';";
  }
  return $srccode;
}
