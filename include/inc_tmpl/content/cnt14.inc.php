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

//WYSIWYG

$cnt_fieldgroup_templates = array();
if(isset($template_default['settings']['wysiwyg_custom_fields']) && is_array($template_default['settings']['wysiwyg_custom_fields']) && count($template_default['settings']['wysiwyg_custom_fields'])) {
    $cnt_fieldgroups = $template_default['settings']['wysiwyg_custom_fields'];
    foreach($template_default['settings']['wysiwyg_custom_fields'] as $key => $cnt_fieldgroup) {
        if(empty($cnt_fieldgroup['template'])) {
            continue;
        }
        $cnt_fieldgroup_templates[ $cnt_fieldgroup['template'] ] = $key;
    }
} else {
    $cnt_fieldgroups = array();
}

$cnt_fieldgroups_active = isset($cnt_fieldgroup_templates['default']) ? $cnt_fieldgroup_templates['default'] : '';

?>

<div class="form-group align-items-center form-row">
  <label for="template" class="col-sm-2 col-form-label text-right"><?php echo $BL['be_admin_struct_template']; ?></label>
  <div class="col-sm-4">
    <select name="template" id="template"<?php if(count($cnt_fieldgroups)): ?> onchange="return toggleTabsTemplate(this);"<?php endif; ?> class="custom-select form-control form-control-sm">
		<?php
    echo '<option value=""'.(empty($content["template"]) ? ' selected="selected"' : '').'>'.$BL['be_admin_tmpl_default'].'</option>'.LF;
    $tmpllist = get_tmpl_files(CMSGO_TEMPLATE.'inc_cntpart/wysiwyg');
    if(is_array($tmpllist) && count($tmpllist)) {
        foreach($tmpllist as $val) {
            if(isset($content["template"]) && $val === $content["template"]) {
                $selected_val = ' selected="selected"';
                if(isset($cnt_fieldgroup_templates[$val])) {
                    $cnt_fieldgroups_active = $cnt_fieldgroup_templates[$val];
                } else {
                    // Reset
                    $cnt_fieldgroups_active = '';
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

<div class="form-group form-row">
  <label for="chtml" class="col-sm-2 col-form-label text-right"><?php echo $BL['be_cnt_htmltext']; ?></label>
  <div class="col">
<?php

$wysiwyg_editor = array(
    'value'     => isset($content["html"]) ? $content["html"] : '',
    'field'     => 'chtml',
    'height'    => '400px',
    'width'     => '100%',
    'rows'      => '15',
    'editor'    => $_SESSION["WYSIWYG_EDITOR"],
    'lang'      => 'en'
);
include CMSGO_ROOT.'/include/inc_lib/wysiwyg.editor.inc.php';

?>  </div>
</div>


<?php
if($cnt_fieldgroups_active && isset($template_default['settings']['wysiwyg_custom_fields'][$cnt_fieldgroups_active])) {
    $cnt_fieldgroup =& $template_default['settings']['wysiwyg_custom_fields'][$cnt_fieldgroups_active];
} else {
    $cnt_fieldgroup = null;
}
if($cnt_fieldgroup !== null && isset($cnt_fieldgroup['fields']) && is_array($cnt_fieldgroup['fields']) && count($cnt_fieldgroup['fields'])) {
    $custom_cnt_fields = array_keys($cnt_fieldgroup['fields']);
} else {
    $custom_cnt_fields = array();
    $cnt_fieldgroup = null;
}

$content['custom_field_items'] = $custom_cnt_fields;
$custom_cnt_fields_hidden = array();
$custom_cnt_field_types = array('str', 'textarea', 'option', 'select', 'int', 'float', 'bool');

if(isset($content['custom_fields']) && is_array($content['custom_fields']) && count($content['custom_fields'])) {
    if(count($custom_cnt_fields)) {
        $content['custom_field_items'] = array_unique( array_merge($custom_cnt_fields, array_keys($content['custom_fields'])) );
    } else {
        $content['custom_field_items'] = array_keys($content['custom_fields']);
    }
} else {
    $content['custom_field_items'] = $custom_cnt_fields;
}

if($content['custom_field_items']): ?>

<?php
    foreach($content['custom_field_items'] as $custom_field_key => $custom_field):

        // send fields not defined as hidden values, should ensure not loosing values
        if(!isset($cnt_fieldgroup['fields'][$custom_field]) && isset($content['custom_fields'][$custom_field])) {
            // do not store if the value is an empty string
            if($content['custom_fields'][$custom_field] !== '') {
                $custom_cnt_fields_hidden[] = '<input type="hidden" name="customfield['.$custom_field.']" value="'.html($content['custom_fields'][$custom_field]).'" />';
            }
            continue;
        }

        // support only type "str" or "textarea" at the moment
        if(empty($cnt_fieldgroup['fields'][$custom_field]['type']) || !in_array($cnt_fieldgroup['fields'][$custom_field]['type'], $custom_cnt_field_types)) {
            $cnt_fieldgroup['fields'][$custom_field]['type'] = 'str';
        }

        $custom_field_placeholder = isset($cnt_fieldgroup['fields'][$custom_field]['placeholder']) && $cnt_fieldgroup['fields'][$custom_field]['placeholder'] !== '' ? ' placeholder="'.html($cnt_fieldgroup['fields'][$custom_field]['placeholder']).'"' : '';
        $is_wysiwyg = $cnt_fieldgroup['fields'][$custom_field]['type'] === 'textarea' && !empty($cnt_fieldgroup['fields'][$custom_field]['render']) && $cnt_fieldgroup['fields'][$custom_field]['render'] === 'wysiwyg' ? true : false;
?>
        <div class="form-group align-items-center form-row">
           <label class="col-sm-2 col-form-label text-right">
            <?php
                if($cnt_fieldgroup['fields'][$custom_field]['type'] !== 'bool') {
                    if(isset($cnt_fieldgroup['fields'][$custom_field]['legend'])) {
                        echo html($cnt_fieldgroup['fields'][$custom_field]['legend']);
                    } else {
                        echo $BL['be_custom_textfield'].' #'.($custom_field_key+1);
                    }

                }
            ?></label>
        <?php if($is_wysiwyg): ?>
             <div class="col">
        <?php else: ?>
             <div class="col">
        <?php endif; ?>
<?php
        if($cnt_fieldgroup['fields'][$custom_field]['type'] === 'str'): ?>
                <input type="text" name="customfield[<?php echo $custom_field; ?>]" value="<?php
                if(isset($content['custom_fields'][$custom_field])) { echo html($content['custom_fields'][$custom_field]); }
                ?>"<?php if(!empty($cnt_fieldgroup['fields'][$custom_field]['maxlength'])): ?> maxlength="<?php echo $cnt_fieldgroup['fields'][$custom_field]['maxlength']; ?>"<?php endif; ?>
                class="form-control form-control-sm"<?php echo $custom_field_placeholder; ?> />
<?php   elseif($cnt_fieldgroup['fields'][$custom_field]['type'] === 'int' || $cnt_fieldgroup['fields'][$custom_field]['type'] === 'float'): ?>
                <input type="number" name="customfield[<?php echo $custom_field; ?>]" value="<?php
                echo isset($content['custom_fields'][$custom_field]) ? $content['custom_fields'][$custom_field] : 0;
                ?>" class="form-control form-control-sm" <?php echo $custom_field_placeholder; ?>
                <?php if(!empty($cnt_fieldgroup['fields'][$custom_field]['min'])): ?> min="<?php echo $cnt_fieldgroup['fields'][$custom_field]['min']; ?>" <?php endif; ?>
                <?php if(!empty($cnt_fieldgroup['fields'][$custom_field]['max'])): ?> max="<?php echo $cnt_fieldgroup['fields'][$custom_field]['max']; ?>" <?php endif; ?>
                <?php if(!empty($cnt_fieldgroup['fields'][$custom_field]['step'])): ?> step="<?php
                    if($cnt_fieldgroup['fields'][$custom_field]['type'] === 'int') {
                        $cnt_fieldgroup['fields'][$custom_field]['step'] = ceil($cnt_fieldgroup['fields'][$custom_field]['step']);
                    } else {
                        $cnt_fieldgroup['fields'][$custom_field]['step'] = floatval($cnt_fieldgroup['fields'][$custom_field]['step']);
                        $cnt_fieldgroup['fields'][$custom_field]['step'] = rtrim(number_format($cnt_fieldgroup['fields'][$custom_field]['step'], 14 - log10($cnt_fieldgroup['fields'][$custom_field]['step'])), '0');
                    }
                    echo $cnt_fieldgroup['fields'][$custom_field]['step'];
                ?>"
                <?php endif; ?>
                />
<?php   elseif($cnt_fieldgroup['fields'][$custom_field]['type'] === 'textarea'):

            if($is_wysiwyg):
                $wysiwyg_editor = array(
                    'value'     => isset($content['custom_fields'][$custom_field]) ? $content['custom_fields'][$custom_field] : '',
                    'field'     => 'customfield['.$custom_field.']',
                    'height'    => empty($cnt_fieldgroup['fields'][$custom_field]['height']) ? '150px' : $cnt_fieldgroup['fields'][$custom_field]['height'],
                    'width'     => '100%',
                    'rows'      => empty($cnt_fieldgroup['fields'][$custom_field]['rows']) ? '5' : $cnt_fieldgroup['fields'][$custom_field]['rows'],
                    'editor'    => $_SESSION["WYSIWYG_EDITOR"],
                    'lang'      => 'en'
                );
                include CMSGO_ROOT.'/include/inc_lib/wysiwyg.editor.inc.php';

            else: ?>
                <textarea name="customfield[<?php echo $custom_field; ?>]" class="form-control form-control-sm"<?php echo $custom_field_placeholder; ?> rows="<?php
                    echo empty($cnt_fieldgroup['fields'][$custom_field]['rows']) ? '3' : $cnt_fieldgroup['fields'][$custom_field]['rows'];
                ?>"><?php if(isset($content['custom_fields'][$custom_field])) { echo html($content['custom_fields'][$custom_field]); } ?></textarea>
<?php       endif;

        elseif($cnt_fieldgroup['fields'][$custom_field]['type'] === 'option' && !empty($cnt_fieldgroup['fields'][$custom_field]['values'])):
            foreach($cnt_fieldgroup['fields'][$custom_field]['values'] as $option_key => $option_label): ?>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="customfield[<?php echo $custom_field; ?>]" value="<?php echo ($option_key === 'empty' ? '' : $option_key); ?>"<?php
                        if(isset($content['custom_fields'][$custom_field]) && $content['custom_fields'][$custom_field] === $option_key):
                    ?> checked="checked"<?php
                        elseif(empty($content['custom_fields'][$custom_field]) && !empty($cnt_fieldgroup['fields'][$custom_field]['default']) && $cnt_fieldgroup['fields'][$custom_field]['default'] === $option_key):
                    ?> checked="checked"<?php endif; ?> /> <label class="form-check-label mr-3"><?php echo html($option_label); ?>
                </label>
              	</div>
<?php       endforeach; ?>
<?php   elseif($cnt_fieldgroup['fields'][$custom_field]['type'] === 'select' && !empty($cnt_fieldgroup['fields'][$custom_field]['values'])): ?>
                <select name="customfield[<?php echo $custom_field; ?>]" class="custom-select form-control form-control-sm">
<?php       foreach($cnt_fieldgroup['fields'][$custom_field]['values'] as $option_key => $option_label): ?>
                    <option value="<?php echo ($option_key === 'empty' ? '' : $option_key); ?>"<?php
                        if(isset($content['custom_fields'][$custom_field]) && $content['custom_fields'][$custom_field] === $option_key):
                    ?> selected="selected"<?php
                        elseif(empty($content['custom_fields'][$custom_field]) && !empty($cnt_fieldgroup['fields'][$custom_field]['default']) && $cnt_fieldgroup['fields'][$custom_field]['default'] === $option_key):
                    ?> selected="selected"<?php endif; ?>><?php echo html($option_label); ?></option>
<?php       endforeach; ?>
                </select>
<?php   elseif($cnt_fieldgroup['fields'][$custom_field]['type'] === 'bool'): ?>
                    <div class="form-check form-check-inline">
                    <input class="form-check-input" type="checkbox" id="customfield[<?php echo $custom_field; ?>]" name="customfield[<?php echo $custom_field; ?>]" value="1"<?php
                        if((!empty($content['custom_fields'][$custom_field])) || (!isset($content['custom_fields'][$custom_field]) && !empty($cnt_fieldgroup['fields'][$custom_field]['default']))):
                    ?> checked="checked"<?php endif; ?> /><label class="form-check-label" for="customfield[<?php echo $custom_field; ?>]"> <?php echo html($cnt_fieldgroup['fields'][$custom_field]['legend']); ?>
                </label></div>
<?php   endif; ?>
            </div>
        </div>
<?php
if($cnt_fieldgroup['fields'][$custom_field]['hr']):?><hr><?php endif;
    endforeach;
endif;
?>


<input type="hidden" name="cnt_fieldgroup" value="<?php echo $cnt_fieldgroups_active; ?>" />
<?php
if(count($custom_cnt_fields_hidden)) {
  echo implode('', $custom_cnt_fields_hidden);
}

if(count($cnt_fieldgroups)): ?>
<script>
function toggleTabsTemplate(e) {
  if(confirm('<?php echo correct_charset($BL['be_tab_template_toggle_warning'], true); ?>')) {
    e.form.submit();
    return true;
  }
  return false;
}
</script>
<?php endif; ?>
