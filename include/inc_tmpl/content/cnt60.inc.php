<?php
/**
 * cmsGO!
 *
 * @author Pixels & Points GmbH <info@pixels-points.ch>
 * @copyright Copyright (c) 2002-2025, Pixels & Points GmbH
 * @license https://www.pixels-points.ch/cmsgo-license.html Pixels & Points cmsGO! license
 *
 **/

// ----------------------------------------------------------------
// obligate check for cmsgo constants
if (!defined('CMSGO_ROOT')) {
    die("You Cannot Access This Script Directly, Have a Nice Day.");
}
// ----------------------------------------------------------------


//custom contentpart

// some predefinitions
if(empty($template_default['imagegallery_default_column'])) {
    $template_default['imagegallery_default_column'] = 1;
} else {
    $template_default['imagegallery_default_column'] = intval($template_default['imagegallery_default_column']);
    if(empty($template_default['imagegallery_default_column'])) {
        $template_default['imagegallery_default_column'] = 1;
    }
}
$template_default['imagegallery_default_width']  = isset($template_default['imagegallery_default_width']) ? $template_default['imagegallery_default_width'] : '' ;
$template_default['imagegallery_default_height'] = isset($template_default['imagegallery_default_height']) ? $template_default['imagegallery_default_height'] : '' ;
$template_default['imagegallery_default_space']  = isset($template_default['imagegallery_default_space']) ? $template_default['imagegallery_default_space'] : '' ;

$content['image_default'] = array(

        'pos'           => 0,
        'width'         => $template_default['imagegallery_default_width'],
        'height'        => $template_default['imagegallery_default_height'],
        'width_zoom'    => $cmsgo['img_prev_width'],
        'height_zoom'   => $cmsgo['img_prev_height'],
        'col'           => $template_default['imagegallery_default_column'],
        'space'         => $template_default['imagegallery_default_space'],
        'zoom'          => 0,
        'caption'       => '',
        'lightbox'      => 0,
        'nocaption'     => 0,
        'center'        => 0,
        'crop'          => 0,
        'crop_zoom'     => 0,
        'fx1'           => 0,
        'fx2'           => 0,
        'fx3'           => 0,
        'custom_elements'        => array()

);

$content['custom_form'] = isset($content['custom_form']) ? array_merge($content['image_default'], $content['custom_form']) : $content['image_default'];

$tab_fieldgroup_templates = array();


if(isset($template_default['settings']['customctp_custom_fields']) && is_array($template_default['settings']['customctp_custom_fields']) && count($template_default['settings']['customctp_custom_fields'])) {
    $tab_fieldgroups = $template_default['settings']['customctp_custom_fields'];
    foreach($template_default['settings']['customctp_custom_fields'] as $key => $tab_fieldgroup) {
        $tab_fieldgroup_templates[ $tab_fieldgroup['template'] ] = $key;
    }
} else {
    $tab_fieldgroups = array();
}

?>


<div class="form-group align-items-center form-row">
  <label for="template" class="col-sm-2 col-form-label text-right"><?php echo $BL['be_admin_struct_template']; ?></label>
  <div class="col-sm-4">
    <select name="template" id="template" class="custom-select form-control form-control-sm">
<?php

    $tab_fieldgroups_active = isset($tab_fieldgroup_templates['default']) ? $tab_fieldgroup_templates['default'] : '';

    echo '<option value=""'.(empty($content["custom_template"]) ? ' selected="selected"' : '').'>'.$BL['be_admin_tmpl_default'].'</option>'.LF;

    $tmpllist = get_tmpl_files(CMSGO_TEMPLATE.'inc_cntpart/custom');

    if(is_array($tmpllist) && count($tmpllist)) {
        foreach($tmpllist as $val) {
            // do not show listmode templates
            if(substr($val, 0, 5) == 'list.') {
                continue;
            }

            if(isset($content["custom_template"]) && $val == $content["custom_template"]){
                $selected_val = ' selected="selected"';
                if(isset($tab_fieldgroup_templates[$val])) {
                    $tab_fieldgroups_active = $tab_fieldgroup_templates[$val];
                } else {
                    // Reset
                    $tab_fieldgroups_active = '';
                }
            } else {
                $selected_val = '';
            }

            $val = html($val);
            echo '  <option value="' . $val . '"' . $selected_val . '>' . $val . '</option>' . LF;
        }
    }

?>
    </select>
  </div>
</div>

<div class="form-group align-items-center form-row">
  <label for="template" class="col-sm-2 col-form-label text-right"><?php echo $BL['be_cnt_custom_entry'] ?></label>
  <div class="col-sm-5">
    <button onclick="return addNewElement('top');" class="btn btn-blue btn-sm"><?php echo $BL['be_article_cnt_add'] ?></button>
  </div>
</div>

<ul id="custom_elements" class="dropable-list p-0">

<?php

    // Sort/Up Down Title
    $sort_up_down = $BL['be_func_struct_sort_up'] . ' / '. $BL['be_func_struct_sort_down'];

    if($tab_fieldgroups_active && isset($template_default['settings']['customctp_custom_fields'][$tab_fieldgroups_active])) {
        $tab_fieldgroup =& $template_default['settings']['customctp_custom_fields'][$tab_fieldgroups_active];
    } else {
        $tab_fieldgroup = null;
    }
    if($tab_fieldgroup !== null && isset($tab_fieldgroup['fields']) && is_array($tab_fieldgroup['fields']) && count($tab_fieldgroup['fields'])) {
        $custom_tab_fields = array_keys($tab_fieldgroup['fields']);
    } else {
        $custom_tab_fields = array();
        $tab_fieldgroup = null;
    }

    $value['custom_field_items'] = $custom_tab_fields;
    $custom_tab_fields_hidden = array();
    $custom_tab_field_types = array('str', 'textarea', 'option', 'select', 'int', 'float', 'bool', 'file', 'image');

    // loop available image entries
    foreach($content['custom_form']['custom_elements'] as $key => $value) {

        if(isset($value['custom_fields']) && is_array($value['custom_fields']) && count($value['custom_fields'])) {

            if(count($custom_tab_fields)) {
                $value['custom_field_items'] = array_unique( array_merge($custom_tab_fields, array_keys($value['custom_fields'])) );
            } else {
                $value['custom_field_items'] = array_keys($value['custom_fields']);
            }

        } else {
            $value['custom_field_items'] = $custom_tab_fields;
        }

?>
  <li id="custom_element_<?php echo $key ?>" class="card my-3 p-0 sortme">

    <div class="card-header p-1 border-1 bg-grey" role="tab" id="heading_<?php echo $key ?>">
      <div class="row align-items-center">
        <div class="col-sm-auto text-right pr-0"><em data-toggle="tooltip" title="<?php echo $sort_up_down; ?>" class="handle text-success"><span class="fa-stack"><i class="fa fa-circle fa-stack-2x"></i><i class="fa fa-sort fa-stack-1x fa-inverse"></i></span></em></div>
        <div class="col"><h2># <?php echo $key ?></h2></div>
            <div class="col-sm-auto text-right">
              <a class="btn btn-sm btn-blue" data-toggle="collapse" href="#collapse_<?php echo $key ?>" aria-expanded="<?php echo (0 == $key) ? 'true' : 'false'; ?>" aria-controls="collapse_<?php echo $key ?>">
                <i class="fa fa-ellipsis-h" aria-hidden="true"></i>
              </a>
              <a class="btn btn-sm btn-danger" role="button" aria-disabled="true" href="#" onclick="return deleteImgElement('custom_element_<?php echo $key ?>');"><i class="far fa-trash-alt"></i></a></div>
      </div>
    </div>

    <div id="collapse_<?php echo $key ?>" class="collapse p-1 <?php echo (0 !== $key) ?: 'show'; ?>" role="tabpanel" aria-labelledby="heading_<?php echo $key ?>" data-parent="#custom_elements">

<?php
if($value['custom_field_items']):
?>
<?php

    foreach($value['custom_field_items'] as $custom_field_key => $custom_field):

        // send fields not defined as hidden values, should ensure not loosing values
        if(!isset($tab_fieldgroup['fields'][$custom_field]) && isset($value['custom_fields'][$custom_field])) {
            // do not store if the value is an empty string
            if($value['custom_fields'][$custom_field] !== '') {
                $custom_tab_fields_hidden[] = '<input type="hidden" name="customfield['.$key.']['.$custom_field.']" value="'.html($value['custom_fields'][$custom_field]).'" />';
            }
            continue;
        }

        $custom_field_placeholder = isset($tab_fieldgroup['fields'][$custom_field]['placeholder']) && $tab_fieldgroup['fields'][$custom_field]['placeholder'] !== '' ? ' placeholder="'.html($tab_fieldgroup['fields'][$custom_field]['placeholder']).'"' : '';
        $is_wysiwyg = $tab_fieldgroup['fields'][$custom_field]['type'] === 'textarea' && !empty($tab_fieldgroup['fields'][$custom_field]['render']) && $tab_fieldgroup['fields'][$custom_field]['render'] === 'wysiwyg' ? true : false;
?>

      <div class="form-group form-row">
        <label class="col-sm-2 col-form-label text-right"><?php
          if($tab_fieldgroup['fields'][$custom_field]['type'] !== 'bool') {
              if(isset($tab_fieldgroup['fields'][$custom_field]['legend'])) {
                  echo html($tab_fieldgroup['fields'][$custom_field]['legend']);
              } else {
                  echo $BL['be_custom_textfield'].' #'.($custom_field_key+1);
              }
              echo ':';
          }
      ?></label>
        <?php if($is_wysiwyg): ?>
             <div class="col">
        <?php else: ?>
             <div class="col">
        <?php endif;

// support only type "str" or "textarea" at the moment
if(empty($tab_fieldgroup['fields'][$custom_field]['type']) || !in_array($tab_fieldgroup['fields'][$custom_field]['type'], $custom_tab_field_types)) {
    $tab_fieldgroup['fields'][$custom_field]['type'] = 'str';
}

if($tab_fieldgroup['fields'][$custom_field]['type'] === 'str'):

  echo get_customfield_str('div', $key, $custom_field, $tab_fieldgroup['fields'][$custom_field], $value['custom_fields'][$custom_field], $custom_field_placeholder);

elseif($tab_fieldgroup['fields'][$custom_field]['type'] === 'int' || $tab_fieldgroup['fields'][$custom_field]['type'] === 'float'): ?>

            <input type="number" name="customfield[<?php echo $key; ?>][<?php echo $custom_field; ?>]" value="<?php
            echo isset($value['custom_fields'][$custom_field]) ? $value['custom_fields'][$custom_field] : 0;
            ?>" class="form-control" <?php echo $custom_field_placeholder; ?>
            <?php if(!empty($tab_fieldgroup['fields'][$custom_field]['min'])): ?> min="<?php echo $tab_fieldgroup['fields'][$custom_field]['min']; ?>" <?php endif; ?>
            <?php if(!empty($tab_fieldgroup['fields'][$custom_field]['max'])): ?> max="<?php echo $tab_fieldgroup['fields'][$custom_field]['max']; ?>" <?php endif; ?>
            <?php if(!empty($tab_fieldgroup['fields'][$custom_field]['step'])): ?> step="<?php
                if($tab_fieldgroup['fields'][$custom_field]['type'] === 'int') {
                    $tab_fieldgroup['fields'][$custom_field]['step'] = ceil($tab_fieldgroup['fields'][$custom_field]['step']);
                } else {
                    $tab_fieldgroup['fields'][$custom_field]['step'] = floatval($tab_fieldgroup['fields'][$custom_field]['step']);
                    $tab_fieldgroup['fields'][$custom_field]['step'] = rtrim(number_format($tab_fieldgroup['fields'][$custom_field]['step'], 14 - log10($tab_fieldgroup['fields'][$custom_field]['step'])), '0');
                }
                echo $tab_fieldgroup['fields'][$custom_field]['step'];
            ?>"
            <?php endif; ?>
            />

    <?php   elseif($tab_fieldgroup['fields'][$custom_field]['type'] === 'textarea'):

    if($is_wysiwyg):
        $wysiwyg_editor = array(
            'value'     => isset($value['custom_fields'][$custom_field]) ? $value['custom_fields'][$custom_field] : '',
            'field'     => 'customfield['.$key.']['.$custom_field.']',
            'height'    => empty($tab_fieldgroup['fields'][$custom_field]['height']) ? '150px' : $tab_fieldgroup['fields'][$custom_field]['height'],
            'width'     => '100%',
            'rows'      => empty($tab_fieldgroup['fields'][$custom_field]['rows']) ? '5' : $tab_fieldgroup['fields'][$custom_field]['rows'],
            'editor'    => $_SESSION["WYSIWYG_EDITOR"],
            'lang'      => 'en'
        );
        include CMSGO_ROOT.'/include/inc_lib/wysiwyg.editor.inc.php';

    else: ?>
                    <textarea name="customfield[<?php echo $key; ?>][<?php echo $custom_field; ?>]" class="form-control"<?php echo $custom_field_placeholder; ?> rows="<?php
                        echo empty($tab_fieldgroup['fields'][$custom_field]['rows']) ? '3' : $tab_fieldgroup['fields'][$custom_field]['rows'];
                    ?>"><?php if(isset($value['custom_fields'][$custom_field])) { echo html($value['custom_fields'][$custom_field]); } ?></textarea>
<?php       endif;

elseif($tab_fieldgroup['fields'][$custom_field]['type'] === 'option' && !empty($tab_fieldgroup['fields'][$custom_field]['values'])):
        foreach($tab_fieldgroup['fields'][$custom_field]['values'] as $option_key => $option_label): ?>
            <div class="form-check form-check-inline col-sm-auto">
                <label class="form-check-label">
                <input type="radio" class="form-check-input" name="customfield[<?php echo $key; ?>][<?php echo $custom_field; ?>]" value="<?php echo ($option_key === 'empty' ? '' : $option_key); ?>"<?php
                    if(isset($value['custom_fields'][$custom_field]) && $value['custom_fields'][$custom_field] === $option_key):
                ?> checked="checked"<?php
                    elseif(empty($value['custom_fields'][$custom_field]) && !empty($tab_fieldgroup['fields'][$custom_field]['default']) && $tab_fieldgroup['fields'][$custom_field]['default'] === $option_key):
                ?> checked="checked"<?php endif; ?> /> <?php echo html($option_label); ?>
            </label>
                </div>
<?php       endforeach; ?>

<?php   elseif($tab_fieldgroup['fields'][$custom_field]['type'] === 'select' && !empty($tab_fieldgroup['fields'][$custom_field]['values'])):

  echo get_customfield_select('div', $key, $custom_field, $tab_fieldgroup['fields'][$custom_field], $value['custom_fields'][$custom_field], $custom_field_placeholder);

elseif($tab_fieldgroup['fields'][$custom_field]['type'] === 'bool'):

  echo get_customfield_bool('div', $key, $custom_field, $tab_fieldgroup['fields'][$custom_field], $value['custom_fields'][$custom_field], $custom_field_placeholder);

elseif($tab_fieldgroup['fields'][$custom_field]['type'] === 'file'): ?>

            <table border="0" cellpadding="0" cellspacing="0">
                <tr>
                    <td>
                        <input
                            name="customfield[<?php echo $key; ?>][<?php echo $custom_field; ?>][id]"
                            type="hidden"
                            id="customfield_<?php echo $custom_field.'_'.$key; ?>_id"
                            value="<?php
                            if(isset($value['custom_fields'][$custom_field]['id'])) {
                                echo $value['custom_fields'][$custom_field]['id'];
                            }
                            ?>"
                        />
                        <input
                            name="customfield[<?php echo $key; ?>][<?php echo $custom_field; ?>][name]"
                            type="text"
                            id="customfield_<?php echo $custom_field.'_'.$key; ?>_name"
                            class="form-control"
                            value="<?php
                            if(isset($value['custom_fields'][$custom_field]['name'])) {
                                echo html($value['custom_fields'][$custom_field]['name']);
                            }
                            ?>"
                            size="40"
                            onfocus="this.blur()"
                        />
                    </td>
                    <td><img src="img/button/open_image_button.gif" title="<?php echo $BL['be_cnt_openmediabrowser'] ?>" alt="<?php echo $BL['be_cnt_openmediabrowser'] ?>" border="0" hspace="3" data-toggle="modal" data-target="#browserModal" data-src="filebrowser.php?opt=19&field=<?php echo $custom_field.'_'.$key; ?>&allowed=<?php echo $tab_fieldgroup['fields'][$custom_field]['filetypes']; ?>" class="modalButton" /></td>
                    <td><a
                        href="#"
                        title="<?php echo $BL['be_cnt_delmedia'] ?>"
                        onclick="getObjectById('customfield_<?php
                            echo $custom_field.'_'.$key; ?>_name').value='';getObjectById('customfield_<?php
                            echo $custom_field.'_'.$key; ?>_id').value='';getObjectById('customfield_<?php
                            echo $custom_field.'_'.$key; ?>_description').value='';this.blur();return false;"
                        ><img src="img/button/del_image_button.gif" alt="" border="0" /></a></td>
                </tr>
                <tr>
                    <td colspan="3" class="tdtop5">
                        <textarea
                            name="customfield[<?php echo $key; ?>][<?php echo $custom_field; ?>][description]"
                            cols="40"
                            rows="2"
                            class="form-control"
                            id="customfield_<?php echo $custom_field.'_'.$key; ?>_description"><?php
                            if(isset($value['custom_fields'][$custom_field]['description'])) {
                                echo html($value['custom_fields'][$custom_field]['description']);
                            }
                            ?></textarea>
                        <span class="caption width400">
                            <?php echo $BL['be_cnt_description']; ?>
                            |
                            <?php echo $BL['be_fprivedit_filename']; ?>
                            |
                            <?php echo $BL['be_caption_file_title']; ?>
                            |
                            <?php echo $BL['be_cnt_target']; ?>
                            |
                            <?php echo $BL['be_caption_file_imagesize']; ?>
                            |
                            <?php echo $BL['be_copyright']; ?>
                        </span>
                    </td>
                </tr>
            </table>
            <?php   elseif($tab_fieldgroup['fields'][$custom_field]['type'] === 'image'): ?>
            <div id="image_<?php echo $custom_field.'_'.$key; ?>">
              <table border="0" cellpadding="0" cellspacing="0">
                  <tr>
                      <td id="img_preview_<?php echo $custom_field.'_'.$key; ?>" rowspan="3" class="backend_preview_img"></td>
                      <td>
                          <input
                              name="customfield[<?php echo $key; ?>][<?php echo $custom_field; ?>][id]"
                              type="hidden"
                              id="customfield_<?php echo $custom_field.'_'.$key; ?>_id"
                              class="form-control"
                              value="<?php
                              if(isset($value['custom_fields'][$custom_field]['id'])) {
                                  echo $value['custom_fields'][$custom_field]['id'];
                              }
                              ?>"
                          />
                          <input
                              name="customfield[<?php echo $key; ?>][<?php echo $custom_field; ?>][name]"
                              type="text"
                              id="customfield_<?php echo $custom_field.'_'.$key; ?>_name"
                              class="form-control"
                              value="<?php
                              if(isset($value['custom_fields'][$custom_field]['name'])) {
                                  echo html($value['custom_fields'][$custom_field]['name']);
                              }
                              ?>"
                              size="40"
                              onfocus="this.blur()"
                          />
                      </td>
                      <td><img src="img/button/open_image_button.gif" title="<?php echo $BL['be_cnt_openmediabrowser'] ?>" alt="<?php echo $BL['be_cnt_openmediabrowser'] ?>" border="0" hspace="3" data-toggle="modal" data-target="#browserModal" data-src="filebrowser.php?opt=8&target=nolist&entry_id=<?php echo $custom_field.'_'.$key; ?>&allowed=<?php echo $tab_fieldgroup['fields'][$custom_field]['filetypes']; ?>" class="modalButton" /></td>
                      <td><a
                          href="#"
                          title="<?php echo $BL['be_cnt_delmedia'] ?>"
                          onclick="getObjectById('customfield_<?php
                              echo $custom_field.'_'.$key; ?>_name').value='';getObjectById('customfield_<?php
                              echo $custom_field.'_'.$key; ?>_id').value='';getObjectById('customfield_<?php
                              echo $custom_field.'_'.$key; ?>_description').value='';this.blur();return false;"
                          ><img src="img/button/del_image_button.gif" alt="" border="0" /></a></td>
                  </tr>
                 <tr>
                    <td><div class="form-group"><?php echo html($tab_fieldgroup['fields'][$custom_field]['alt-label']);?>:<input type="text" name="customfield[<?php echo $key; ?>][<?php echo $custom_field; ?>][alt]" value="<?php
                              if(isset($value['custom_fields'][$custom_field]['alt'])) {
                                  echo html($value['custom_fields'][$custom_field]['alt']);
                              } ?>" class="form-control" /></div></td>
                    <td colspan="2"></td>
                </tr>
                <tr>
                    <td><div class="form-group"><?php echo html($tab_fieldgroup['fields'][$custom_field]['title-label']);?>:<input type="text" name="customfield[<?php echo $key; ?>][<?php echo $custom_field; ?>][title]" value="<?php
                              if(isset($value['custom_fields'][$custom_field]['title'])) {
                                  echo html($value['custom_fields'][$custom_field]['title']);
                              } ?>" class="form-control" /></div></td>
                    <td colspan="2"></td>
                </tr>
             </table>
           </div>
           <?php endif; ?>
        </div>
      </div>
<?php
    endforeach;
endif;
?>
    </div>
  </li>

<?php

    }
    // close custom entry looping

?>
</ul>

<?php
// second button to add custom element at bottom of list
if (count($content['custom_form']['custom_elements'])) {
?>
<div class="form-group align-items-center form-row">
  <label for="template" class="col-sm-2 col-form-label text-right"><?php echo $BL['be_cnt_custom_entry'] ?></label>
  <div class="col-sm-5">
    <button onclick="return addNewElement('top');" class="btn btn-blue btn-sm"><?php echo $BL['be_article_cnt_add'] ?></button>
  </div>
</div>
<?php
}
?>

<input type="hidden" name="tab_fieldgroup" value="<?php echo $tab_fieldgroups_active; ?>" />
<?php
if(count($custom_tab_fields_hidden)) {
    echo implode('', $custom_tab_fields_hidden);
}
?>
<script type="text/javascript">

var site_url    = '<?php echo CMSGO_URL; ?>';
var max_img_w   = <?php echo $cmsgo['img_list_width']; ?>;
var max_img_h   = <?php echo $cmsgo['img_list_height']; ?>;
var custom_entry = [];

function setImgIdName(field, file_id, file_name) {

    if(file_id == null || file_name == null) return null;
    $('#customfield_'+field+'_id').val(file_id);
    $('#customfield_'+field+'_name').val(file_name);
    updatePreviewImage(field, file_id);

}

function setIdName(field, file_id, file_name) {
    if(file_id == null || file_name == null || field == null) {
        return null;
    }
    $('#customfield_'+field+'_name').val(file_name);
    $('#customfield_'+field+'_id').val(file_id);
    $('#browserModal').modal('hide');
}

function deleteImageData(image_number, e) {
    $('#cimage_name_'+image_number).val('');
    $('#cimage_id_'+image_number).val('0');
    e.blur();
    image_number = image_number.split('_');
    if(image_number[1]) {
        alert(image_number[1]);
        updatePreviewImage(image_number[1]);
    }
    return false;
}

function updatePreviewImage(field, file_id) {

    var preview = '';
    preview += getBackendImgSrc( file_id );
    $('#img_preview_'+field).html(preview);
}

function getBackendImgSrc(file_id) {
    var image_file_id = parseInt(file_id, 10);
    if(image_file_id) {
        return '<'+'img src="'+site_url+'<?php echo CMSGO_RESIZE_IMAGE; ?>/'+max_img_w+'x'+max_img_h+'/'+image_file_id+'" border="0" alt="" /'+'> ';
    }
    return '';
}

function updatePreviewImageAll() {

    $("div[id*='image_']").each(function() {
      var custom_number = $(this).attr('id').split('_')
      var field = custom_number[1]+'_'+custom_number[2]
      if(custom_number[1]) {
          updatePreviewImage(field,$('#customfield_'+field+'_id').val());
      }
    });
}

function updateCustomSort() {

    $("li[id*='custom_element_']").each(function() {
      var custom_number = $(this).attr('id').split('_')
      if(custom_number[2]) {
        custom_entry[ custom_number[2] ] = $('custom_sort['+custom_number[2]+']').value;
      }
    });
}

function addNewElement(where) {

    updatePreviewImageAll();
    updateCustomSort();

    var entry_number = custom_entry.length;
    var new_entry = '';
    new_entry += '<'+'div class="card-header p-1 border-1 bg-grey" role="tab" id="heading_'+entry_number+'">';
    new_entry += '<'+'div class="row align-items-center">';
    new_entry += '<'+'div class="col-sm-auto text-right pr-0"><em data-toggle="tooltip" title="<?php echo $sort_up_down; ?>" class="handle text-success"><span class="fa-stack"><i class="fa fa-circle fa-stack-2x"><'+'/i><i class="fa fa-sort fa-stack-1x fa-inverse"><'+'/i><'+'/span><'+'/em><'+'/div>';
    new_entry += '<'+'div class="col"><h2># '+entry_number+'<'+'/h2><'+'/div>';
    new_entry += '<'+'div class="col-sm-auto text-right">';
    new_entry += '<'+'a class="btn btn-sm btn-blue" data-toggle="collapse" href="#collapse_'+entry_number+'" aria-expanded="true" aria-controls="collapse_'+entry_number+'">';
    new_entry += '<'+'i class="fa fa-ellipsis-h" aria-hidden="true"><'+'/i>';
    new_entry += '<'+'/a>';
    new_entry += '<'+'a class="btn btn-sm btn-danger" role="button" aria-disabled="true" href="#" onclick="return deleteImgElement(\'image_'+entry_number+'\'"><i class="far fa-trash-alt"><'+'/i><'+'/a><'+'/div>';
    new_entry += '<'+'/div>';
    new_entry += '<'+'/div>';

    new_entry += '<'+'div id="collapse_'+entry_number+'" class="collapse show p-1" role="tabpanel" aria-labelledby="heading_'+entry_number+'" data-parent="#custom_elements">';

<?php
    if(!empty($value['custom_field_items'])):
        foreach($value['custom_field_items'] as $custom_field_key => $custom_field):

            // send fields not defined as hidden values, should ensure not loosing values
            if(!isset($tab_fieldgroup['fields'][$custom_field]) && isset($value['custom_fields'][$custom_field])) {
                continue;
            }

            $custom_field_placeholder = isset($tab_fieldgroup['fields'][$custom_field]['placeholder']) && $tab_fieldgroup['fields'][$custom_field]['placeholder'] !== '' ? ' placeholder="'.html($tab_fieldgroup['fields'][$custom_field]['placeholder']).'"' : '';

            // support only type "str" or "textarea" at the moment
            if(empty($tab_fieldgroup['fields'][$custom_field]['type']) || !in_array($tab_fieldgroup['fields'][$custom_field]['type'], $custom_tab_field_types)) {
                $tab_fieldgroup['fields'][$custom_field]['type'] = 'str';
            }

?>

    new_entry += '<div class="form-group form-row">';
    new_entry += '<label class="col-sm-2 col-form-label text-right">';
     <?php
        if($tab_fieldgroup['fields'][$custom_field]['type'] !== 'bool') {
            if(isset($tab_fieldgroup['fields'][$custom_field]['legend'])) {
                echo "new_entry += '".html($tab_fieldgroup['fields'][$custom_field]['legend'])."';";
            } else {
                echo "new_entry += '".$BL['be_custom_textfield'].' #'.($custom_field_key+1)."';";
            }
            echo "new_entry += ':';";
        }
    ?>
    new_entry += '<'+'/label>';
<?php if($is_wysiwyg): ?>
    new_entry += '<div class="col">';
<?php else: ?>
    new_entry += '<div class="col">';
<?php endif; ?>

<?php   if($tab_fieldgroup['fields'][$custom_field]['type'] === 'str'):
        echo get_customfield_str('js', $key, $custom_field, $tab_fieldgroup['fields'][$custom_field], $value['custom_fields'][$custom_field], $custom_field_placeholder);

  elseif($tab_fieldgroup['fields'][$custom_field]['type'] === 'textarea'): ?>
    new_entry += '<textarea name="customfield[' + entry_number + '][<?php echo $custom_field; ?>]" class="form-control autosize" rows="<?php echo empty($tab_fieldgroup['fields'][$custom_field]['rows']) ? '3' : $tab_fieldgroup['fields'][$custom_field]['rows']; ?>"<?php echo $custom_field_placeholder; ?>><'+'/textarea>';

<?php   elseif($tab_fieldgroup['fields'][$custom_field]['type'] === 'option' && !empty($tab_fieldgroup['fields'][$custom_field]['values'])):
    foreach($tab_fieldgroup['fields'][$custom_field]['values'] as $option_key => $option_label): ?>
    new_entry += '<label class="radio tab-option-radio"><input type="radio" name="customfield[' + entry_number + '][<?php echo $custom_field; ?>]" value="<?php echo $option_key; ?>"<?php if(!empty($tab_fieldgroup['fields'][$custom_field]['default']) && $tab_fieldgroup['fields'][$custom_field]['default'] === $option_key): ?> checked="checked"<?php endif; ?>'+'/> <?php echo html($option_label); ?><'+'/label> ';
<?php   endforeach;

    elseif($tab_fieldgroup['fields'][$custom_field]['type'] === 'int' || $tab_fieldgroup['fields'][$custom_field]['type'] === 'float'): ?>
    new_entry += '<input type="number" name="customfield[' + entry_number + '][<?php echo $custom_field; ?>]" value="0" class="v11 width100"<?php echo $custom_field_placeholder; ?>';
    <?php if(!empty($tab_fieldgroup['fields'][$custom_field]['min'])): ?>new_entry += ' min="<?php echo $tab_fieldgroup['fields'][$custom_field]['min']; ?>"';<?php endif; ?>
    <?php if(!empty($tab_fieldgroup['fields'][$custom_field]['max'])): ?>new_entry += ' max="<?php echo $tab_fieldgroup['fields'][$custom_field]['max']; ?>"';<?php endif; ?>
    <?php if(!empty($tab_fieldgroup['fields'][$custom_field]['step'])): ?>new_entry += ' step="<?php echo $tab_fieldgroup['fields'][$custom_field]['step']; ?>"';<?php endif; ?>

    new_entry += ' />';

<?php

elseif($tab_fieldgroup['fields'][$custom_field]['type'] === 'select' && !empty($tab_fieldgroup['fields'][$custom_field]['values'])):

    echo get_customfield_select('js', $key, $custom_field, $tab_fieldgroup['fields'][$custom_field], $value['custom_fields'][$custom_field], $custom_field_placeholder);

elseif($tab_fieldgroup['fields'][$custom_field]['type'] === 'bool'):

    echo get_customfield_bool('js', $key, $custom_field, $tab_fieldgroup['fields'][$custom_field], $value['custom_fields'][$custom_field], $custom_field_placeholder);

elseif($tab_fieldgroup['fields'][$custom_field]['type'] === 'file'): ?>

    new_entry += '<table border="0" cellpadding="0" cellspacing="0">';
    new_entry += '  <tr>';
    new_entry += '      <td>';
    new_entry += '          <input';
    new_entry += '              name="customfield[' + entry_number + '][<?php echo $custom_field; ?>][id]"';
    new_entry += '              type="hidden"';
    new_entry += '              id="customfield_<?php echo $custom_field; ?>_' + entry_number + '_id"';
    new_entry += '              value=""';
    new_entry += '          />';
    new_entry += '          <input';
    new_entry += '              name="customfield[' + entry_number + '][<?php echo $custom_field; ?>][name]"';
    new_entry += '              type="text"';
    new_entry += '              id="customfield_<?php echo $custom_field; ?>_' + entry_number + '_name"';
    new_entry += '              class="width375 greyed"';
    new_entry += '              value=""';
    new_entry += '              size="40"';
    new_entry += '              onfocus="this.blur()"';
    new_entry += '          />';
    new_entry += '      </td>';
    new_entry += '      <td><img src="img/button/open_image_button.gif" title="<?php echo $BL['be_cnt_openmediabrowser'] ?>" alt="<?php echo $BL['be_cnt_openmediabrowser'] ?>" border="0" hspace="3" data-toggle="modal" data-target="#browserModal" data-src="filebrowser.php?opt=19&field=<?php echo $custom_field; ?>_' + entry_number + '&allowed=<?php echo $tab_fieldgroup['fields'][$custom_field]['filetypes']; ?>" class="modalButton" /><'+'/td>';
    new_entry += '      <td><a';
    new_entry += '              href="#"';
    new_entry += '              title="<?php echo $BL['be_cnt_delmedia'] ?>"';
    new_entry += '              onclick="getObjectById(\'customfield_<?php
                                echo $custom_field; ?>_' + entry_number + '_name\').value=\'\';getObjectById(\'customfield_<?php
                                echo $custom_field; ?>_' + entry_number + '_id\').value=\'\';getObjectById(\'customfield_<?php
                                echo $custom_field; ?>_' + entry_number + '_description\').value=\'\';this.blur();return false;"';
    new_entry += '          ><img src="img/button/del_image_button.gif" alt="" border="0" /><'+'/a><'+'/td>';
    new_entry += '  <'+'/tr>';
    new_entry += '  <tr>';
    new_entry += '      <td colspan="3" class="tdtop5">';
    new_entry += '          <textarea';
    new_entry += '              name="customfield[' + entry_number + '][<?php echo $custom_field; ?>][description]"';
    new_entry += '              cols="40"';
    new_entry += '              rows="2"';
    new_entry += '              class="width375 autosize"';
    new_entry += '              id="customfield_<?php echo $custom_field; ?>_' + entry_number + '_description"></textarea>';
    new_entry += '          <span class="caption width400">';
    new_entry += '              <?php echo $BL['be_cnt_description']; ?> |';
    new_entry += '              <?php echo $BL['be_fprivedit_filename']; ?> |';
    new_entry += '              <?php echo $BL['be_caption_file_title']; ?> |';
    new_entry += '              <?php echo $BL['be_cnt_target']; ?> |';
    new_entry += '              <?php echo $BL['be_caption_file_imagesize']; ?> |';
    new_entry += '              <?php echo $BL['be_copyright']; ?>';
    new_entry += '          <'+'/span>';
    new_entry += '      <'+'/td>';
    new_entry += '  <'+'/tr>';
    new_entry += '<'+'/table>';
    <?php   elseif($tab_fieldgroup['fields'][$custom_field]['type'] === 'image'): ?>

    new_entry += '<table border="0" cellpadding="0" cellspacing="0">';
    new_entry += '  <tr>';
    new_entry += '      <td>';
    new_entry += '          <input';
    new_entry += '              name="customfield[' + entry_number + '][<?php echo $custom_field; ?>][id]"';
    new_entry += '              type="hidden"';
    new_entry += '              id="customfield_<?php echo $custom_field; ?>_' + entry_number + '_id"';
    new_entry += '              value=""';
    new_entry += '          />';
    new_entry += '          <input';
    new_entry += '              name="customfield[' + entry_number + '][<?php echo $custom_field; ?>][name]"';
    new_entry += '              type="text"';
    new_entry += '              id="customfield_<?php echo $custom_field; ?>_' + entry_number + '_name"';
    new_entry += '              class="width375 greyed"';
    new_entry += '              value=""';
    new_entry += '              size="40"';
    new_entry += '              onfocus="this.blur()"';
    new_entry += '          />';
    new_entry += '      </td>';
    new_entry += '      <td><img src="img/button/open_image_button.gif" title="<?php echo $BL['be_cnt_openmediabrowser'] ?>" alt="<?php echo $BL['be_cnt_openmediabrowser'] ?>" border="0" hspace="3" data-toggle="modal" data-target="#browserModal" data-src="filebrowser.phpopt=8&target=nolist&entry_id=<?php echo $custom_field; ?>_' + entry_number + '&allowed=<?php echo $tab_fieldgroup['fields'][$custom_field]['filetypes']; ?>" class="modalButton" /><'+'/td>';
    new_entry += '      <td><a';
    new_entry += '              href="#"';
    new_entry += '              title="<?php echo $BL['be_cnt_delmedia'] ?>"';
    new_entry += '              onclick="getObjectById(\'customfield_<?php
                                echo $custom_field; ?>_' + entry_number + '_name\').value=\'\';getObjectById(\'customfield_<?php
                                echo $custom_field; ?>_' + entry_number + '_id\').value=\'\';getObjectById(\'customfield_<?php
                                echo $custom_field; ?>_' + entry_number + '_description\').value=\'\';this.blur();return false;"';
    new_entry += '          ><img src="img/button/del_image_button.gif" alt="" border="0" /><'+'/a><'+'/td>';
    new_entry += '  <'+'/tr>';
    new_entry += '  <tr>';
    new_entry += '      <td colspan="3" class="tdtop5">';
    new_entry += '          <textarea';
    new_entry += '              name="customfield[' + entry_number + '][<?php echo $custom_field; ?>][description]"';
    new_entry += '              cols="40"';
    new_entry += '              rows="2"';
    new_entry += '              class="width375 autosize"';
    new_entry += '              id="customfield_<?php echo $custom_field; ?>_' + entry_number + '_description"></textarea>';
    new_entry += '          <span class="caption width400">';
    new_entry += '              <?php echo $BL['be_cnt_description']; ?> |';
    new_entry += '              <?php echo $BL['be_fprivedit_filename']; ?> |';
    new_entry += '              <?php echo $BL['be_caption_file_title']; ?> |';
    new_entry += '              <?php echo $BL['be_cnt_target']; ?> |';
    new_entry += '              <?php echo $BL['be_caption_file_imagesize']; ?> |';
    new_entry += '              <?php echo $BL['be_copyright']; ?>';
    new_entry += '          <'+'/span>';
    new_entry += '      <'+'/td>';
    new_entry += '  <'+'/tr>';
    new_entry += '  <'+'tr>';
    new_entry += '  <'+'td class="spacerrow"><'+'/td>';
    new_entry += '  <'+'td id="img_preview_<?php echo $custom_field; ?>'+entry_number+'" colspan="3" class="backend_preview_img"><'+'/td>';
    new_entry += '  <'+'/tr>';
    new_entry += '  <'+'/table>';
<?php   endif; ?>
    new_entry += '<'+'/div><'+'/div>';
<?php
        endforeach;
    endif;
?>


    var $li = $("<li>", {id: 'custom_element_'+entry_number, "class": "card sortme nomove", "style": "margin:5px 0"});
    $("#custom_elements").append($li);
    $('#custom_element_'+entry_number).html(new_entry);
    window.location.hash='custom_element_'+entry_number;

    $('img.modalButton').on('click', function(e) {
      var src = $(this).attr('data-src');
      $("#browserModal iframe").attr({'src':src, 'height': '100%', 'width': '100%'});
    });
    return false;
}

function deleteImgElement(id) {
    if(confirm('<?php echo $BL['be_image_delete_js'] ?>')) {
        $("#" + id).remove();
    }
    return false;
}

$(function(){

    updatePreviewImageAll();
    updateCustomSort();

    $("ul.dropable-list").sortable({
      group: 'no-drop',
      handle: 'em.handle',
      onDrop: function ($item, container, _super, event) {
        $item.removeClass(container.group.options.draggedClass).removeAttr("style");
        $("body").removeClass(container.group.options.bodyClass);
      }
    });

});

</script>
