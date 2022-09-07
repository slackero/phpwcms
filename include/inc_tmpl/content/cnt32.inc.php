<?php
/**
 * cmsGO!
 *
 * @author Pixels & Points GmbH <info@pixels-points.ch>
 * @copyright Copyright (c) 2002-2022, Pixels & Points GmbH
 * @license https://www.pixels-points.ch/cmsgo-license.html Pixels & Points cmsGO! license
 *
 **/

// ----------------------------------------------------------------
// obligate check for cmsgo constants
if (!defined('CMSGO_ROOT')) {
  die("You Cannot Access This Script Directly, Have a Nice Day.");
}
// ----------------------------------------------------------------

// Tabs
// set default values
if(empty($content['tabs']) || !is_array($content['tabs'])) {
  $content['tabs'] = array();
}
$content['tabwysiwygoff'] = empty($content['tabs']['tabwysiwygoff']) ? 0 : 1;
unset($content['tabs']['tabwysiwygoff'], $content['tabs']['tab_fieldgroup']);

// load WYSIWYG editor
if(!empty($_SESSION["WYSIWYG_EDITOR"]) && !$content['tabwysiwygoff']) {
  $BE['HEADER']['ckeditor.js'] = getJavaScriptSourceLink('include/inc_ext/ckeditor/ckeditor.js');
  $content['wysiwyg'] = true;
} else {
  $content['wysiwyg'] = false;
}

$tab_fieldgroup_templates = array();
if(isset($template_default['settings']['tabs_custom_fields']) && is_array($template_default['settings']['tabs_custom_fields']) && count($template_default['settings']['tabs_custom_fields'])) {
  $tab_fieldgroups = $template_default['settings']['tabs_custom_fields'];
  foreach($template_default['settings']['tabs_custom_fields'] as $key => $tab_fieldgroup) {
    $tab_fieldgroup_templates[ $tab_fieldgroup['template'] ] = $key;
  }
} else {
  $tab_fieldgroups = array();
}

$tab_template_options = '<option value=""'.(empty($content["tabs_template"]) ? ' selected="selected"' : '').'>'.$BL['be_admin_tmpl_default'].'</option>';

$tab_fieldgroups_active = isset($tab_fieldgroup_templates['default']) ? $tab_fieldgroup_templates['default'] : '';

$tmpllist = get_tmpl_files(CMSGO_TEMPLATE.'inc_cntpart/tabs');

if(is_array($tmpllist) && count($tmpllist)) {

  foreach($tmpllist as $val) {
    if(!empty($content["tabs_template"]) && $val === $content["tabs_template"]) {
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
    $tab_template_options .= '<option value="' . $val . '"' . $selected_val . '>' . $val . '</option>';
  }
}
?>

<div class="form-group align-items-center form-row">
  <label for="template" class="col-sm-2 col-form-label text-right"><?php echo $BL['be_admin_struct_template']; ?></label>
  <div class="col-sm-4">
    <select name="template" id="template" class="custom-select form-control"<?php if(count($tab_fieldgroups)): ?> onchange="return toggleTabsTemplate(this);"<?php endif; ?>>
      <?php echo $tab_template_options; ?>
    </select>
  </div>
</div>

<div class="form-group align-items-center form-row">
  <label for="template" class="col-sm-2 col-form-label text-right"></label>
  	<div class="form-check form-check-inline col-sm-auto">
      <input class="form-check-input" type="checkbox" name="tabwysiwygoff" id="tabwysiwygoff" value="1"<?php is_checked(1, $content['tabwysiwygoff']) ?> />
      <label class="form-check-label" for="tabwysiwygoff">&nbsp;<?php echo $BL['be_cnt_no_wysiwyg_editor'] ?></label>
  	</div>
</div>

<hr />

<div class="form-group align-items-center form-row">
	<label class="col-sm-2 col-form-label text-right"></label>
	<div class="col">
		<button type="button" class="btn btn-sm btn-blue" id="btn_add_tab_top" onclick="return addNewTab('top');">
			<i class="fa fa-plus"></i>
            <?php echo $BL['be_tab_add'] ?>
        </button>
	</div>
</div>

<ul id="tabs" role="tablist" class="dropable-list p-0">
<?php

  // Sort/Up Down Title
  $sort_up_down = $BL['be_func_struct_sort_up'] . ' / '. $BL['be_func_struct_sort_down'];
  if($tab_fieldgroups_active && isset($template_default['settings']['tabs_custom_fields'][$tab_fieldgroups_active])) {
    $tab_fieldgroup =& $template_default['settings']['tabs_custom_fields'][$tab_fieldgroups_active];
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
  $custom_tab_field_types = array('str', 'textarea', 'option', 'select', 'int', 'float', 'bool', 'file');

  if(!empty($content['tabs'])):
    foreach($content['tabs'] as $key => $value):

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
    <li id="tab_<?php echo $key ?>" class="card my-3 p-0">

        <div class="card-header p-2 border-1" role="tab" id="heading_<?php echo $key ?>">
          <div class="row">
            <div class="col-sm-auto">
              <em data-toggle="tooltip" title="<?php echo $sort_up_down; ?>" class="handle text-success">
                  <span class="fa-stack">
                      <i class="fa fa-circle fa-stack-2x"></i>
                      <i class="fa fa-sort fa-stack-1x fa-inverse"></i>
                  </span>
              </em>
            </div>
            <div class="col text-right">
              <a class="btn btn-sm btn-blue" data-toggle="collapse" href="#collapse_<?php echo $key ?>" aria-expanded="<?php echo (0 == $key) ? 'true' : 'false'; ?>" aria-controls="collapse_<?php echo $key ?>"><i class="fa fa-ellipsis-h" aria-hidden="true"></i></a>
              <a class="btn btn-sm btn-danger" role="button" aria-disabled="true" href="#" onclick="return deleteTab('tab_<?php echo $key ?>');"><i class="far fa-trash-alt"></i></a>
            </div>
          </div>
        </div>

        <div class="card-body pb-1">
            <div class="form-group align-items-center form-row">
                <label class="col-sm-2 col-form-label text-right"><?php echo $BL['be_tab_name']; ?></label>
                <div class="col"><input type="text" name="tabtitle[<?php echo $key ?>]" id="tabtitle<?php echo $key ?>" value="<?php echo html($value['tabtitle']); ?>" class="form-control form-control-sm" /></div>
            </div>

            <div id="collapse_<?php echo $key ?>" class="collapse <?php echo (0 !== $key) ?: 'show'; ?>" role="tabpanel" aria-labelledby="heading_<?php echo $key ?>" data-parent="#tabs">
                <div class="form-group align-items-center form-row">
					<label for="be_headline" class="col-sm-2 col-form-label text-right"><?php echo $BL['be_headline'] ?></label>
					<div class="col-sm-4">
						<input type="text" name="tabheadline[<?php echo $key ?>]" id="tabheadline<?php echo $key ?>" value="<?php echo html($value['tabheadline']); ?>" class="form-control form-control-sm" />
					</div>
					<label for="be_admin_page_link" class="col-sm-2 col-form-label text-right"><?php echo $BL['be_admin_page_link'] ?></label>
					<div class="col-sm-4">
						<input type="text" name="tablink[<?php echo $key ?>]" id="tablink<?php echo $key ?>" value="<?php echo (isset($value['tablink']) ? html($value['tablink']) : ''); ?>" class="form-control form-control-sm" />
					</div>
				</div>
				<div class="form-group form-row">
					<?php if($content['tabwysiwygoff']): ?>
					<label class="col-sm-2 col-form-label text-right"><?php echo $BL['be_ctype_wysiwyg']; ?></label>
                    <div class="col">
                        <textarea class="form-control autosize" name="tabtext[<?php echo $key ?>]" id="tabtext<?php echo $key ?>" rows="5"><?php echo html($value['tabtext']); ?></textarea>
                    </div>
                    <?php else: ?>
                    <label class="col-sm-2 col-form-label text-right"><?php echo $BL['be_cnt_field']['textarea'] ?></label>
                    <div class="col">
                        <textarea class="form-control" name="tabtext[<?php echo $key ?>]" id="tabtext<?php echo $key ?>" rows="5"><?php echo html($value['tabtext']); ?></textarea>
                    </div>
					<?php endif; ?>
				</div>

		<?php
      if($value['custom_field_items']):
        foreach($value['custom_field_items'] as $custom_field_key => $custom_field):
            // send fields not defined as hidden values, should ensure not loosing values
            if(isset($value['custom_fields'][$custom_field])) {
                if(!isset($tab_fieldgroup['fields'][$custom_field])) {
                    // do not store if the value is an empty string
                    if(is_array($value['custom_fields'][$custom_field]) && $value['custom_fields'][$custom_field]) {
                        $custom_tab_fields_hidden[] = '<input type="hidden" name="customfield['.$key.']['.$custom_field.']" value="'.html(serialize($value['custom_fields'][$custom_field])).'" />';
                    } elseif($value['custom_fields'][$custom_field] !== '') {
                        $custom_tab_fields_hidden[] = '<input type="hidden" name="customfield['.$key.']['.$custom_field.']" value="'.html($value['custom_fields'][$custom_field]).'" />';
                    }
                    continue;
                } elseif(is_string($value['custom_fields'][$custom_field]) && substr($value['custom_fields'][$custom_field], 0, 2) === 'a:') {
                    $_unserialze = @unserialize($value['custom_fields'][$custom_field]);
                    if ($_unserialze === false) {
                        continue;
                    }
                    $value['custom_fields'][$custom_field] = $_unserialze;
                }
           }

           $custom_field_placeholder = isset($tab_fieldgroup['fields'][$custom_field]['placeholder']) && $tab_fieldgroup['fields'][$custom_field]['placeholder'] !== '' ? ' placeholder="'.html($tab_fieldgroup['fields'][$custom_field]['placeholder']).'"' : '';
           $custom_field_class = empty($cnt_fieldgroup['fields'][$custom_field]['class']) ? '' : ' ' . $cnt_fieldgroup['fields'][$custom_field]['class'];
?>
 			<div class="form-group align-items-center form-row tab-collapsable-row<?= $custom_field_class; ?>">
                <label class="col-sm-2 col-form-label text-right"><?php
                  if($tab_fieldgroup['fields'][$custom_field]['type'] !== 'bool') {
                    if(isset($tab_fieldgroup['fields'][$custom_field]['legend'])) {
                      echo html($tab_fieldgroup['fields'][$custom_field]['legend']);
                    } else {
                      echo $BL['be_custom_textfield'].' #'.($custom_field_key+1);
                    }
                  }
                ?></label>

            <div class="col">
<?php
        // support only type "str" or "textarea" at the moment
        if(empty($tab_fieldgroup['fields'][$custom_field]['type']) || !in_array($tab_fieldgroup['fields'][$custom_field]['type'], $custom_tab_field_types)) {
            $tab_fieldgroup['fields'][$custom_field]['type'] = 'str';
        }

        if($tab_fieldgroup['fields'][$custom_field]['type'] === 'str'): ?>
              <input type="text" name="customfield[<?php echo $key; ?>][<?php echo $custom_field; ?>]" value="<?php
              if(isset($value['custom_fields'][$custom_field])) { echo html($value['custom_fields'][$custom_field]); }
              ?>"<?php if(!empty($tab_fieldgroup['fields'][$custom_field]['maxlength'])): ?> maxlength="<?php echo $tab_fieldgroup['fields'][$custom_field]['maxlength']; ?>"<?php endif; ?>
              class="form-control form-control-sm"<?php echo $custom_field_placeholder; ?> />
      <?php elseif($tab_fieldgroup['fields'][$custom_field]['type'] === 'int' || $tab_fieldgroup['fields'][$custom_field]['type'] === 'float'): ?>
              <input type="number" name="customfield[<?php echo $key; ?>][<?php echo $custom_field; ?>]" value="<?php
              echo isset($value['custom_fields'][$custom_field]) ? $value['custom_fields'][$custom_field] : 0;
              ?>" class="form-control form-control-sm" <?php echo $custom_field_placeholder; ?>
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
      <?php elseif($tab_fieldgroup['fields'][$custom_field]['type'] === 'textarea'): ?>
              <textarea name="customfield[<?php echo $key; ?>][<?php echo $custom_field; ?>]" class="form-control form-control-sm autosize"<?php echo $custom_field_placeholder; ?> rows="3"><?php if(isset($value['custom_fields'][$custom_field])) { echo html($value['custom_fields'][$custom_field]); } ?></textarea>
      <?php elseif($tab_fieldgroup['fields'][$custom_field]['type'] === 'option' && !empty($tab_fieldgroup['fields'][$custom_field]['values'])):
            foreach($tab_fieldgroup['fields'][$custom_field]['values'] as $option_key => $option_label): ?>
              <div class="form-check form-check-inline col-sm-auto">
								<input class="form-check-input" type="radio" name="customfield[<?php echo $key; ?>][<?php echo $custom_field; ?>]" value="<?php echo ($option_key === 'empty' ? '' : $option_key); ?>"<?php
										if(isset($value['custom_fields'][$custom_field]) && $value['custom_fields'][$custom_field] === $option_key):
									?> checked="checked"<?php
										elseif(empty($value['custom_fields'][$custom_field]) && !empty($tab_fieldgroup['fields'][$custom_field]['default']) && $tab_fieldgroup['fields'][$custom_field]['default'] === $option_key):
									?> checked="checked"<?php endif; ?> />
								<label class="form-check-label"><?php echo html($option_label); ?></label>
              </div>
      <?php   endforeach; ?>
      <?php elseif($tab_fieldgroup['fields'][$custom_field]['type'] === 'select' && !empty($tab_fieldgroup['fields'][$custom_field]['values'])): ?>
              <select class="custom-select form-control form-control-sm" name="customfield[<?php echo $key; ?>][<?php echo $custom_field; ?>]">
      <?php   foreach($tab_fieldgroup['fields'][$custom_field]['values'] as $option_key => $option_label): ?>
                <option value="<?php echo ($option_key === 'empty' ? '' : $option_key); ?>"<?php
                  if(isset($value['custom_fields'][$custom_field]) && $value['custom_fields'][$custom_field] === $option_key):
                ?> selected="selected"<?php
                  elseif(empty($value['custom_fields'][$custom_field]) && !empty($tab_fieldgroup['fields'][$custom_field]['default']) && $tab_fieldgroup['fields'][$custom_field]['default'] === $option_key):
                ?> selected="selected"<?php endif; ?>><?php echo html($option_label); ?></option>
      <?php   endforeach; ?>
              </select>
      <?php elseif($tab_fieldgroup['fields'][$custom_field]['type'] === 'bool'): ?>
              <div class="form-check form-check-inline col-sm-auto">
                    <input class="form-check-input" type="checkbox" name="customfield[<?php echo $key; ?>][<?php echo $custom_field; ?>]" value="1"<?php
                        if((!empty($value['custom_fields'][$custom_field])) || (!isset($value['custom_fields'][$custom_field]) && !empty($tab_fieldgroup['fields'][$custom_field]['default']))):
                    ?> checked="checked"<?php endif; ?> />
                    <label class="form-check-label"><?php echo html($tab_fieldgroup['fields'][$custom_field]['legend']); ?></label>
              </div>
      <?php   elseif($tab_fieldgroup['fields'][$custom_field]['type'] === 'file'): ?>

          <div class="input-group mb-3">
              <span class="input-group-prepend">
                  <button class="modalButton btn btn-sm btn-blue folder-open" type="button" data-toggle="modal" data-target="#browserModal" data-src="filebrowser.php?opt=19&field=<?php echo $custom_field.'_'.$key; ?>&allowed=<?php echo $tab_fieldgroup['fields'][$custom_field]['filetypes']; ?>" ></button>
              </span>
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
                  class="form-control form-control-sm"
                  value="<?php
                  if(isset($value['custom_fields'][$custom_field]['name'])) {
                      echo html($value['custom_fields'][$custom_field]['name']);
                  }
                  ?>"
                  size="40"
                  onfocus="this.blur()"
              />
              <span class="input-group-append ">
                  <a class="btn btn-sm btn-danger trash"
                     href="#"
                     type="button"
                     data-toggle="tooltip" title="<?php echo $BL['be_cnt_delmedia'] ?>"
                     onclick="getObjectById('customfield_<?php
                     echo $custom_field.'_'.$key; ?>_name').value='';getObjectById('customfield_<?php
                     echo $custom_field.'_'.$key; ?>_id').value='';getObjectById('customfield_<?php
                     echo $custom_field.'_'.$key; ?>_description').value='';this.blur();return false;"
                  ></a>
              </span>
          </div>

          <textarea
              name="customfield[<?php echo $key; ?>][<?php echo $custom_field; ?>][description]"
              cols="40"
              rows="2"
              class="form-control form-control-sm mb-2"
              id="customfield_<?php echo $custom_field.'_'.$key; ?>_description"><?php
              if(isset($value['custom_fields'][$custom_field]['description'])) {
                  echo html($value['custom_fields'][$custom_field]['description']);
              }
              ?></textarea>
          <span class="small">
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

      <?php endif; ?>
            </div>
            </div>

<?php
        endforeach;
      endif;
?>

      </div>

        </div>
    </li>

<?php



    endforeach;
  endif;
?>
</ul>


<div>
    <input type="hidden" name="tab_fieldgroup" value="<?php echo $tab_fieldgroups_active; ?>" /><?php
    if(count($custom_tab_fields_hidden)) {
        echo implode('', $custom_tab_fields_hidden);
    }
    ?>
<script type="text/javascript">

var entries = 0;

function addNewTab(pos) {
    entries++;

  var entry = `
        <div class="card-header p-2 border-1" role="tab" id="heading_${entries}">
            <div class="row">
                <div class="col-sm-auto">
                    <em data-toggle="tooltip" title="<?php echo $sort_up_down; ?>" class="handle text-success">
                        <span class="fa-stack"><i class="fa fa-circle fa-stack-2x"></i><i class="fa fa-sort fa-stack-1x fa-inverse"></i></span>
                    </em>
                </div>
                <div class="col text-right">
                    <a class="btn btn-sm btn-blue" data-toggle="collapse" href="#collapse_${entries}"><i class="fa fa-ellipsis-h"></i></a>
                    <a class="btn btn-sm btn-danger" role="button" href="#" onclick="return deleteTab('tab_${entries}');"><i class="far fa-trash-alt"></i></a>
                </div>
            </div>
        </div>
        <div class="card-body pb-1">
            <div class="form-group align-items-center form-row">
                <label class="col-sm-2 col-form-label text-right"><?php echo $BL['be_tab_name']; ?></label>
                <div class="col"><input type="text" name="tabtitle[${entries}]" id="tabtitle${entries}" value="" class="form-control form-control-sm" /></div>
            </div>
            <div id="collapse_${entries}" class="collapse show" role="tabpanel" aria-labelledby="heading_${entries}" data-parent="#tabs">
                <div class="form-group align-items-center form-row">
					<label for="be_headline" class="col-sm-2 col-form-label text-right"><?php echo $BL['be_headline'] ?></label>
					<div class="col-sm-4">
						<input type="text" name="tabheadline[${entries}]" id="tabheadline${entries}" value="" class="form-control form-control-sm" />
					</div>
					<label for="be_admin_page_link" class="col-sm-2 col-form-label text-right"><?php echo $BL['be_admin_page_link'] ?></label>
					<div class="col-sm-4">
						<input type="text" name="tablink[${entries}]" id="tablink${entries}" value="" class="form-control form-control-sm" />
					</div>
				</div>
                <div class="form-group form-row">
                    <label class="col-sm-2 col-form-label text-right"><?php echo $BL['be_cnt_field']['textarea'] ?></label>
                    <div class="col"><textarea class="form-control" name="tabtext[${entries}]" id="tabtext${entries}" rows="5"></textarea></div>
				</div>
<?php
        if(!empty($value['custom_field_items'])):
            foreach($value['custom_field_items'] as $custom_field_key => $custom_field):

                // send fields not defined as hidden values, should ensure not loosing values
                if(!isset($tab_fieldgroup['fields'][$custom_field]) && isset($value['custom_fields'][$custom_field])) {
                    continue;
                }

                $custom_field_placeholder = isset($tab_fieldgroup['fields'][$custom_field]['placeholder']) && $tab_fieldgroup['fields'][$custom_field]['placeholder'] !== '' ? ' placeholder="'.html($tab_fieldgroup['fields'][$custom_field]['placeholder']).'"' : '';

                // support defined types only
                if(empty($tab_fieldgroup['fields'][$custom_field]['type']) || !in_array($tab_fieldgroup['fields'][$custom_field]['type'], $custom_tab_field_types)) {
                    $tab_fieldgroup['fields'][$custom_field]['type'] = 'str';
                }
?>
                <hr />
                <div class="form-group align-items-center form-row tab-collapsable-row">
                    <label class="col-sm-2 col-form-label text-right">
                        <?php
                        if($tab_fieldgroup['fields'][$custom_field]['type'] !== 'bool') {
                            echo isset($tab_fieldgroup['fields'][$custom_field]['legend']) ? html($tab_fieldgroup['fields'][$custom_field]['legend']) : $BL['be_custom_textfield'] . ' #' . ($custom_field_key + 1);
                        }
                        ?>
                    </label>
                    <div class="col">
                        <?php if($tab_fieldgroup['fields'][$custom_field]['type'] === 'str'): ?>
                            <input type="text" name="customfield[${entries}][<?php echo $custom_field; ?>]" value="" class="form-control form-control-sm"<?php echo $custom_field_placeholder; ?>
                            <?php if(!empty($tab_fieldgroup['fields'][$custom_field]['maxlength'])): ?> maxlength="<?php echo $tab_fieldgroup['fields'][$custom_field]['maxlength']; ?>"<?php endif; ?> />

                        <?php elseif($tab_fieldgroup['fields'][$custom_field]['type'] === 'int' || $tab_fieldgroup['fields'][$custom_field]['type'] === 'float'): ?>
                            <input type="number" name="customfield[${entries}][<?php echo $custom_field; ?>]" value="" class="form-control form-control-sm"<?php echo $custom_field_placeholder; ?>
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
                                <?php endif; ?> />

                        <?php elseif($tab_fieldgroup['fields'][$custom_field]['type'] === 'textarea'): ?>
                            <textarea name="customfield[${entries}][<?php echo $custom_field; ?>]" class="form-control form-control-sm autosize"<?php echo $custom_field_placeholder; ?> rows="3"></textarea>

                        <?php elseif($tab_fieldgroup['fields'][$custom_field]['type'] === 'option' && !empty($tab_fieldgroup['fields'][$custom_field]['values'])):
                            foreach($tab_fieldgroup['fields'][$custom_field]['values'] as $option_key => $option_label): ?>
                            <div class="form-check form-check-inline col-sm-auto">
								<input class="form-check-input" type="radio" name="customfield[${entries}][<?php echo $custom_field; ?>]" value="<?php echo ($option_key === 'empty' ? '' : $option_key); ?>"
								<?php if(!empty($tab_fieldgroup['fields'][$custom_field]['default']) && $tab_fieldgroup['fields'][$custom_field]['default'] === $option_key): ?> checked="checked"<?php endif; ?> />
								<label class="form-check-label"><?php echo html($option_label); ?></label>
                            </div><?php
                            endforeach; ?>

                        <?php elseif($tab_fieldgroup['fields'][$custom_field]['type'] === 'select' && !empty($tab_fieldgroup['fields'][$custom_field]['values'])): ?>
                            <select class="custom-select form-control form-control-sm" name="customfield[${entries}][<?php echo $custom_field; ?>]">
                            <?php foreach($tab_fieldgroup['fields'][$custom_field]['values'] as $option_key => $option_label): ?>
                                <option value="<?php echo ($option_key === 'empty' ? '' : $option_key); ?>"
                                <?php if(!empty($tab_fieldgroup['fields'][$custom_field]['default']) && $tab_fieldgroup['fields'][$custom_field]['default'] === $option_key): ?> selected="selected"<?php endif; ?>>
                                    <?php echo html($option_label); ?>
                                </option>
                            <?php endforeach; ?>
                            </select>

                        <?php elseif($tab_fieldgroup['fields'][$custom_field]['type'] === 'bool'): ?>
                            <div class="form-check form-check-inline col-sm-auto">
                                <input class="form-check-input" type="checkbox" name="customfield[${entries}][<?php echo $custom_field; ?>]" value="1" />
                                <label class="form-check-label"><?php echo html($tab_fieldgroup['fields'][$custom_field]['legend']); ?></label>
                            </div>

                        <?php elseif($tab_fieldgroup['fields'][$custom_field]['type'] === 'file'): ?>
                            <div class="input-group mb-3">
                                <span class="input-group-prepend">
                                    <button class="modalButton btn btn-sm btn-blue folder-open" type="button" data-toggle="modal" data-target="#browserModal" data-src="filebrowser.php?opt=19&field=<?php echo $custom_field ?>_${entries}&allowed=<?php echo $tab_fieldgroup['fields'][$custom_field]['filetypes']; ?>"></button>
                                </span>
                                <input type="hidden" name="customfield[${entries}][<?php echo $custom_field; ?>][id]" id="customfield_<?php echo $custom_field; ?>_${entries}_id" value="" />
                                <input type="text" name="customfield[${entries}][<?php echo $custom_field; ?>][name]" id="customfield_<?php echo $custom_field; ?>_${entries}_name" class="form-control form-control-sm" value="" size="40" onfocus="this.blur()" />
                                <span class="input-group-append ">
                                    <a class="btn btn-sm btn-danger trash" href="#" type="button" data-toggle="tooltip" title="<?php echo $BL['be_cnt_delmedia'] ?>" onclick="getObjectById('customfield_<?php
                                      echo $custom_field; ?>_${entries}_name').value='';getObjectById('customfield_<?php
                                      echo $custom_field; ?>_${entries}_id').value='';getObjectById('customfield_<?php
                                      echo $custom_field; ?>_${entries}_description').value='';this.blur();return false;"></a>
                                </span>
                            </div>
                            <textarea name="customfield[${entries}][<?php echo $custom_field; ?>][description]" cols="40" rows="2" class="form-control form-control-sm mb-2" id="customfield_<?php echo $custom_field; ?>_${entries}_description"></textarea>
                            <span class="small">
                                <?php echo $BL['be_cnt_description']; ?> | <?php echo $BL['be_fprivedit_filename']; ?> |
                                <?php echo $BL['be_caption_file_title']; ?> | <?php echo $BL['be_cnt_target']; ?> |
                                <?php echo $BL['be_caption_file_imagesize']; ?> | <?php echo $BL['be_copyright']; ?>
                            </span>

                        <?php endif; ?>
                    </div>
                </div>
<?php
            endforeach;
        endif;
?>
            </div>
        </div>
`;

        var $li = $("<li>", {id: 'tab_'+entries, "class": "card my-3 p-0"});
        if (pos === 'top') {
            $("#tabs").prepend($li);
        } else {
            $("#tabs").append($li);
        }

        $li.html(entry);
        <?php if($content['wysiwyg']): ?>EnableCKEditor(entries);<?php endif; ?>

        $('button.modalButton').on('click', function() {
            $("#browserModal iframe").attr({'src': $(this).data('src'), 'height': '100%', 'width': '100%'});
        });
        return false;
    }

<?php if($content['wysiwyg']): ?>

$( function() {
    entries = $("ul#tabs").children().length;
    if(entries > 0) {
        for(var x = 0; x < entries; x++) {
            EnableCKEditor(x);
        }
    }
});

<?php

  // CKEditor Tabs configuration
  $content['ckconfig'] = array();

  if(isset($_SESSION["wcs_user_lang"])) {
    $content['ckconfig'][] = "language: '" . $_SESSION["wcs_user_lang"] ."'";
  }
  if(is_file(CMSGO_TEMPLATE.'config/ckeditor/ckeditor.config-tabs.js')) {
    $content['ckconfig'][] = 'customConfig: "' . CMSGO_URL.TEMPLATE_PATH . 'config/ckeditor/ckeditor.config-tabs.js"';
  } else {
    $content['ckconfig'][] = "toolbar: [
      {name: 'tools', items: ['Maximize', '-', 'Source', '-', 'Undo', 'Redo', '-', 'Paste', 'PasteText', 'PasteFromWord', '-', 'Find', '-', 'ShowBlocks']},
      {name: 'links', items: ['Link', 'Unlink', 'Anchor']},
      {name: 'colors', items: ['TextColor', 'BGColor']},
      {name: 'basicstyles', groups: ['basicstyles', 'cleanup'], items: ['Bold', 'Italic', 'Underline', 'Strike', 'Subscript', 'Superscript', '-', 'RemoveFormat']},
      {name: 'paragraph', groups: ['align', 'list', 'indent', 'blocks'], items: ['JustifyLeft', 'JustifyCenter', 'JustifyRight', 'JustifyBlock', '-', 'BulletedList', 'NumberedList', '-', 'Outdent', 'Indent', '-', 'Blockquote', 'CreateDiv']},
      {name: 'insert', items: ['Image', 'Table', 'HorizontalRule', 'Iframe', 'SpecialChar']},
      {name: 'styles', items: ['Styles', 'Format', 'Font']},
      {name: 'about', items: ['About']}
    ]";

    //$content['ckconfig'][] = 'width: 538';
    //$content['ckconfig'][] = 'height: 150';
    $content['ckconfig'][] = 'toolbarCanCollapse: true';
    $content['ckconfig'][] = 'toolbarStartupExpanded: true';
    $content['ckconfig'][] = 'forcePasteAsPlainText: true';
    $content['ckconfig'][] = 'pasteFromWordRemoveFontStyles: true';
    $content['ckconfig'][] = 'pasteFromWordRemoveStyles: true';
    $content['ckconfig'][] = 'pasteFromWordPromptCleanup: true';
  }
  if(!empty($GLOBALS['cmsgo']['FCK_FileBrowser'])) {
    $content['ckconfig'][] = 'filebrowserBrowseUrl: "'.CMSGO_URL.'filebrowser.php?opt=16"';
    $content['ckconfig'][] = 'filebrowserImageBrowseUrl: "'.CMSGO_URL.'filebrowser.php?opt=17"';
    $content['ckconfig'][] = 'filebrowserWindowWidth: 640';
    $content['ckconfig'][] = 'filebrowserWindowHeight: 480';
  }

  $content['ckconfig'] = ', {' . implode(',', $content['ckconfig']) . '}';

?>

  function EnableCKEditor(x) {
    if( $('tabtext'+x) && !CKEDITOR.instances['tabtext'+x]) {
      CKEDITOR.replace('tabtext'+x<?php echo $content['ckconfig'] ?>);
    }
  }
<?php endif; ?>

  function deleteTab(id) {
    if(confirm('<?php echo $BL['be_tab_delete_js'] ?>')) {
      $("#" + id).remove();
    }
    return false;
  }
  function toggleTabsTemplate(e) {
    if(confirm('<?php echo correct_charset($BL['be_tab_template_toggle_warning'], true); ?>')) {
      e.form.submit();
      return true;
    }
    return false;
  }

  function setIdName(field, file_id, file_name) {
      if(file_id == null || file_name == null || field == null) {
          return null;
      }
      $('#customfield_'+field+'_name').val(file_name);
      $('#customfield_'+field+'_id').val(file_id);
      $('#browserModal').modal('hide');
  }

  $(function(){
      $("ul.dropable-list").sortable({
        group: 'no-drop',
        handle: 'em.handle',
        onDrag: function ($item, container, _super, event) {
          $(".collapse").collapse('hide');
        },
        onDrop: function ($item, container, _super, event) {
          $item.removeClass(container.group.options.draggedClass).removeAttr("style");
          $("body").removeClass(container.group.options.bodyClass);
        }
      });
  });

  </script>
</div>

<div class="form-group align-items-center form-row">
	<label class="col-sm-2 col-form-label text-right"></label>
	<div class="col">
		<button type="button" class="btn btn-sm btn-blue" id="btn_add_tab_bottom" onclick="return addNewTab('bottom');">
			<i class="fa fa-plus"></i>
            <?php echo $BL['be_tab_add'] ?>
        </button>
	</div>
</div>