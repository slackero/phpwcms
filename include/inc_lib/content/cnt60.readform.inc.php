<?php
/**
 * cmsGo!
 *
 * @author Pixels & Points GmbH <info@pixels-points.ch>
 * @copyright Copyright (c) 2002-2017, Pixels & Points GmbH
 * @license https://www.pixels-points.ch/cmsgo-license.html Pixels & Points cmsGo! license
 **/

// ----------------------------------------------------------------
// obligate check for cmsgo constants
if (!defined('CMSGO_ROOT')) {
    die("You Cannot Access This Script Directly, Have a Nice Day.");
}
// ----------------------------------------------------------------


// Content Type Images Special
$content["custom_html"]      = slweg($_POST['custom_html']);
$content["custom_template"]  = clean_slweg($_POST['template']);
$content['custom_form']   = array(
        
        'custom_elements'        => array(),
        'fieldgroup'    => ''
        
);

$tab_fieldgroup_fields = null;
$tab_fieldgroup_field_render = array('html', 'markdown');
if(empty($_POST['tab_fieldgroup'])) {
    $content['tab_fieldgroup'] = '';
} else {
    $content['tab_fieldgroup'] = clean_slweg($_POST['tab_fieldgroup']);
    if($content['tab_fieldgroup'] && isset($template_default['settings']['customctp_custom_fields'][ $content['tab_fieldgroup'] ]['fields'])) {
        $tab_fieldgroup_fields =& $template_default['settings']['customctp_custom_fields'][ $content['tab_fieldgroup'] ]['fields'];
    }
}

// get custom element entry POST values
if(isset($_POST['customfield']) && is_array($_POST['customfield']) && count($_POST['customfield'])) {
    
    $x = 0;
    
    foreach($_POST['customfield'] as $key => $value) {
        
        $custom_entry = array();
        $custom_entry['sort']        = $x;
        /*$custom_entry['thumb_id']    = intval($_POST['cimage_id_thumb'][$key]);
         $custom_entry['zoom_id']     = intval($_POST['cimage_id_zoom'][$key]);
         
         if(!$custom_entry['thumb_id'] && !$custom_entry['zoom_id']) {
         continue;
         }
         
         $custom_entry['thumb_name']  = clean_slweg($_POST['cimage_name_thumb'][$key]);
         $custom_entry['zoom_name']   = clean_slweg($_POST['cimage_name_zoom'][$key]);
         
         $custom_entry['caption']     = clean_slweg($_POST['cimage_caption'][$key]);
         $custom_entry['freetext']    = slweg($_POST['cimage_freetext'][$key]);
         $custom_entry['url']         = clean_slweg($_POST['cimage_url'][$key]);
         
         if(!$custom_entry['thumb_id']) {
         $custom_entry['thumb_id']    = '';
         $custom_entry['thumb_name']  = '';
         $custom_entry['thumb_hash']  = '';
         $custom_entry['thumb_ext']   = '';
         } else {
         $sql   = 'SELECT f_hash, f_ext FROM '.DB_PREPEND.'cmsgo_file WHERE ';
         $sql  .= 'f_id='.$custom_entry['thumb_id'].' AND ';
         $sql  .= 'f_trash=0 AND f_aktiv=1 AND f_public=1';
         $image_data = _dbQuery($sql);
         if(isset($image_data[0]['f_hash'])) {
         $custom_entry['thumb_hash']  = $image_data[0]['f_hash'];
         $custom_entry['thumb_ext']   = $image_data[0]['f_ext'];
         }
         }
         if(!$custom_entry['zoom_id']) {
         $custom_entry['zoom_id']     = '';
         $custom_entry['zoom_name']   = '';
         $custom_entry['zoom_hash']   = '';
         $custom_entry['zoom_ext']    = '';
         } else {
         $sql   = 'SELECT f_hash, f_ext FROM '.DB_PREPEND.'cmsgo_file WHERE ';
         $sql  .= 'f_id='.$custom_entry['zoom_id'].' AND ';
         $sql  .= 'f_trash=0 AND f_aktiv=1 AND f_public=1';
         $image_data = _dbQuery($sql);
         if(isset($image_data[0]['f_hash'])) {
         $custom_entry['zoom_hash']   = $image_data[0]['f_hash'];
         $custom_entry['zoom_ext']    = $image_data[0]['f_ext'];
         }
         }*/
        
        $custom_entry['custom_fields'] = array();
        
        // first read all defined custom field values
        if(!empty($tab_fieldgroup_fields)) {
            foreach($tab_fieldgroup_fields as $custom_field => $custom_field_definition) {
                
                $custom_field_value = isset($_POST['customfield'][$key][$custom_field]) ? $_POST['customfield'][$key][$custom_field] : null;
                
                $_POST['customfield'][$key][$custom_field] = null;
                unset($_POST['customfield'][$key][$custom_field]);
                
                if(isset($tab_fieldgroup_fields[$custom_field]['render']) && in_array($tab_fieldgroup_fields[$custom_field]['render'], $tab_fieldgroup_field_render)) {
                    
                    $custom_entry['custom_fields'][$custom_field] = slweg($custom_field_value);
                    
                } elseif($tab_fieldgroup_fields[$custom_field]['type'] === 'int') {
                    
                    $custom_entry['custom_fields'][$custom_field] = intval($custom_field_value);
                    
                } elseif($tab_fieldgroup_fields[$custom_field]['type'] === 'float') {
                    
                    $custom_entry['custom_fields'][$custom_field] = floatval($custom_field_value);
                    
                } elseif($tab_fieldgroup_fields[$custom_field]['type'] === 'bool') {
                    
                    $custom_entry['custom_fields'][$custom_field] = empty($custom_field_value) ? 0 : 1;
                    
                } elseif($tab_fieldgroup_fields[$custom_field]['type'] === 'file') {
                    
                    $custom_entry['custom_fields'][$custom_field] = array('id' => '', 'name' => '', 'description' => '');
                    
                    if(!empty($custom_field_value['id']) && ($custom_field_value['id'] = intval($custom_field_value['id']))) {
                        $custom_entry['custom_fields'][$custom_field]['id'] = $custom_field_value['id'];
                    }
                    if(!empty($custom_field_value['name']) && $custom_entry['custom_fields'][$custom_field]['id']) {
                        $custom_entry['custom_fields'][$custom_field]['name'] = clean_slweg($custom_field_value['name']);
                    }
                    if(!empty($custom_field_value['description']) && $custom_entry['custom_fields'][$custom_field]['id']) {
                        $custom_entry['custom_fields'][$custom_field]['description'] = clean_slweg($custom_field_value['description']);
                    }
                    
                } elseif($tab_fieldgroup_fields[$custom_field]['type'] === 'image') {
                    
                    $custom_entry['custom_fields'][$custom_field] = array('id' => '', 'name' => '', 'alt' => '', 'title' => '', 'f_hash' => '', 'f_ext' => '');
                    
                    if(!empty($custom_field_value['id']) && ($custom_field_value['id'] = intval($custom_field_value['id']))) {
                        $custom_entry['custom_fields'][$custom_field]['id'] = $custom_field_value['id'];
                        
                        $sql   = 'SELECT f_hash, f_ext FROM '.DB_PREPEND.'cmsgo_file WHERE ';
                        $sql  .= 'f_id='.$custom_field_value['id'].' AND ';
                        $sql  .= 'f_trash=0 AND f_aktiv=1 AND f_public=1';
                        $image_data = _dbQuery($sql);
                        if(isset($image_data[0]['f_hash'])) {
                            $custom_entry['custom_fields'][$custom_field]['f_hash']  = $image_data[0]['f_hash'];
                            $custom_entry['custom_fields'][$custom_field]['f_ext']   = $image_data[0]['f_ext'];
                        }
                    }
                    if(!empty($custom_field_value['name']) && $custom_entry['custom_fields'][$custom_field]['id']) {
                        $custom_entry['custom_fields'][$custom_field]['name'] = clean_slweg($custom_field_value['name']);
                    }
                    if(!empty($custom_field_value['alt']) && $custom_entry['custom_fields'][$custom_field]['id']) {
                        $custom_entry['custom_fields'][$custom_field]['alt'] = clean_slweg($custom_field_value['alt']);
                    }
                    if(!empty($custom_field_value['title']) && $custom_entry['custom_fields'][$custom_field]['id']) {
                        $custom_entry['custom_fields'][$custom_field]['title'] = clean_slweg($custom_field_value['title']);
                    }
                    
                    
                } else {
                    
                    $custom_entry['custom_fields'][$custom_field] = clean_slweg($custom_field_value);
                    
                }
            }
        }
        
        // parse all non-defined custom fields (maybe left over from old definitions)
        if(!empty($_POST['customfield'][$key]) && count($_POST['customfield'][$key])) {
            foreach($_POST['customfield'][$key] as $custom_field => $custom_field_value) {
                if($custom_field_value === null) {
                    continue;
                }
                $custom_entry['custom_fields'][$custom_field] = slweg($custom_field_value); // keep the value as is
            }
        }
        
        $content['custom_form']['custom_elements'][$x] = $custom_entry;
        
        $x++;
        
    }
    
}

$content['custom_form']['fieldgroup'] = $content['tab_fieldgroup'];
