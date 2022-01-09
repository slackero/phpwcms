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


// Content Type Images Special
$content["image_html"]      = slweg($_POST['image_html']);
$content["image_template"]  = clean_slweg($_POST['template']);
$content['image_special']   = array(
    'pos'           => empty($_POST['cimage_pos']) ? 0 : intval($_POST['cimage_pos']),
    'width'         => empty($_POST['cimage_width']) ? '' : intval($_POST['cimage_width']),
    'height'        => empty($_POST['cimage_height']) ? '' : intval($_POST['cimage_height']),
    'width_zoom'    => empty($_POST['cimage_width_zoom']) ? $phpwcms['img_prev_width'] : intval($_POST['cimage_width_zoom']),
    'height_zoom'   => empty($_POST['cimage_height_zoom']) ? $phpwcms['img_prev_height'] : intval($_POST['cimage_height_zoom']),
    'col'           => empty($_POST['cimage_col']) ? 1 : intval($_POST['cimage_col']),
    'space'         => empty($_POST['cimage_space']) ? 0 : intval($_POST['cimage_space']),
    'zoom'          => empty($_POST['cimage_zoom']) ? 0 : 1,
    'lightbox'      => empty($_POST['cimage_lightbox']) ? 0 : 1,
    'nocaption'     => empty($_POST['cimage_nocaption']) ? 0 : 1,
    'center'        => empty($_POST['cimage_center']) ? 0 : intval($_POST['cimage_center']),
    'crop'          => empty($_POST['cimage_crop']) ? 0 : 1,
    'crop_zoom'     => empty($_POST['cimage_crop_zoom']) ? 0 : 1,
    'fx1'           => empty($_POST['cimage_fx1']) ? 0 : 1,
    'fx2'           => empty($_POST['cimage_fx2']) ? 0 : 1,
    'fx3'           => empty($_POST['cimage_fx3']) ? 0 : 1,
    'images'        => array(),
    'fieldgroup'    => ''
);

$tab_fieldgroup_fields = null;
$tab_fieldgroup_field_render = array('html', 'markdown', 'wysiwyg');
if(empty($_POST['tab_fieldgroup'])) {
    $content['tab_fieldgroup'] = '';
} else {
    $content['tab_fieldgroup'] = clean_slweg($_POST['tab_fieldgroup']);
    if($content['tab_fieldgroup'] && isset($template_default['settings']['imagespecial_custom_fields'][ $content['tab_fieldgroup'] ]['fields'])) {
        $tab_fieldgroup_fields =& $template_default['settings']['imagespecial_custom_fields'][ $content['tab_fieldgroup'] ]['fields'];
    }
}

// get image entry POST values
if(isset($_POST['cimage_id_thumb']) && is_array($_POST['cimage_id_thumb']) && count($_POST['cimage_id_thumb'])) {

    $x = 0;

    foreach($_POST['cimage_id_thumb'] as $key => $value) {

        $image_entry = array();

        $image_entry['thumb_id']    = intval($_POST['cimage_id_thumb'][$key]);
        $image_entry['zoom_id']     = intval($_POST['cimage_id_zoom'][$key]);

        if(!$image_entry['thumb_id'] && !$image_entry['zoom_id']) {
            continue;
        }

        $image_entry['thumb_name']  = clean_slweg($_POST['cimage_name_thumb'][$key]);
        $image_entry['zoom_name']   = clean_slweg($_POST['cimage_name_zoom'][$key]);
        $image_entry['sort']        = $x;
        $image_entry['caption']     = clean_slweg($_POST['cimage_caption'][$key]);
        $image_entry['freetext']    = slweg($_POST['cimage_freetext'][$key]);
        $image_entry['url']         = clean_slweg($_POST['cimage_url'][$key]);

        if(!$image_entry['thumb_id']) {
            $image_entry['thumb_id']    = '';
            $image_entry['thumb_name']  = '';
            $image_entry['thumb_hash']  = '';
            $image_entry['thumb_ext']   = '';
        } else {
            $sql   = 'SELECT f_hash, f_ext FROM '.DB_PREPEND.'phpwcms_file WHERE ';
            $sql  .= 'f_id='.$image_entry['thumb_id'].' AND ';
            $sql  .= 'f_trash=0 AND f_aktiv=1 AND f_public=1';
            $image_data = _dbQuery($sql);
            if(isset($image_data[0]['f_hash'])) {
                $image_entry['thumb_hash']  = $image_data[0]['f_hash'];
                $image_entry['thumb_ext']   = $image_data[0]['f_ext'];
            }
        }
        if(!$image_entry['zoom_id']) {
            $image_entry['zoom_id']     = '';
            $image_entry['zoom_name']   = '';
            $image_entry['zoom_hash']   = '';
            $image_entry['zoom_ext']    = '';
        } else {
            $sql   = 'SELECT f_hash, f_ext FROM '.DB_PREPEND.'phpwcms_file WHERE ';
            $sql  .= 'f_id='.$image_entry['zoom_id'].' AND ';
            $sql  .= 'f_trash=0 AND f_aktiv=1 AND f_public=1';
            $image_data = _dbQuery($sql);
            if(isset($image_data[0]['f_hash'])) {
                $image_entry['zoom_hash']   = $image_data[0]['f_hash'];
                $image_entry['zoom_ext']    = $image_data[0]['f_ext'];
            }
        }

        $image_entry['custom_fields'] = array();

        // first read all defined custom field values
        if(!empty($tab_fieldgroup_fields)) {
            foreach($tab_fieldgroup_fields as $custom_field => $custom_field_definition) {

                $custom_field_value = isset($_POST['customfield'][$key][$custom_field]) ? $_POST['customfield'][$key][$custom_field] : null;

                $_POST['customfield'][$key][$custom_field] = null;
                unset($_POST['customfield'][$key][$custom_field]);

                if(isset($tab_fieldgroup_fields[$custom_field]['render']) && in_array($tab_fieldgroup_fields[$custom_field]['render'], $tab_fieldgroup_field_render)) {

                    $image_entry['custom_fields'][$custom_field] = slweg($custom_field_value);

                } elseif($tab_fieldgroup_fields[$custom_field]['type'] === 'int') {

                    $image_entry['custom_fields'][$custom_field] = intval($custom_field_value);

                } elseif($tab_fieldgroup_fields[$custom_field]['type'] === 'float') {

                    $image_entry['custom_fields'][$custom_field] = floatval($custom_field_value);

                } elseif($tab_fieldgroup_fields[$custom_field]['type'] === 'bool') {

                    $image_entry['custom_fields'][$custom_field] = empty($custom_field_value) ? 0 : 1;

                } elseif($tab_fieldgroup_fields[$custom_field]['type'] === 'file') {

                    $image_entry['custom_fields'][$custom_field] = array('id' => '', 'name' => '', 'description' => '');

                    if(!empty($custom_field_value['id']) && ($custom_field_value['id'] = intval($custom_field_value['id']))) {
                        $image_entry['custom_fields'][$custom_field]['id'] = $custom_field_value['id'];
                    }
                    if(!empty($custom_field_value['name']) && $image_entry['custom_fields'][$custom_field]['id']) {
                        $image_entry['custom_fields'][$custom_field]['name'] = clean_slweg($custom_field_value['name']);
                    }
                    if(!empty($custom_field_value['description']) && $image_entry['custom_fields'][$custom_field]['id']) {
                        $image_entry['custom_fields'][$custom_field]['description'] = clean_slweg($custom_field_value['description']);
                    }

                } else {

                    $image_entry['custom_fields'][$custom_field] = clean_slweg($custom_field_value);

                }
            }
        }

        // parse all non-defined custom fields (maybe left over from old definitions)
        if(!empty($_POST['customfield'][$key]) && count($_POST['customfield'][$key])) {
            foreach($_POST['customfield'][$key] as $custom_field => $custom_field_value) {
                if($custom_field_value === null) {
                    continue;
                }
                $image_entry['custom_fields'][$custom_field] = slweg($custom_field_value); // keep the value as is
            }
        }

        $content['image_special']['images'][$x] = $image_entry;

        $x++;

    }

}

$content['image_special']['fieldgroup'] = $content['tab_fieldgroup'];
