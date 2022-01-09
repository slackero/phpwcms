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

// Tabs
initMootools();

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

$tmpllist = get_tmpl_files(PHPWCMS_TEMPLATE.'inc_cntpart/tabs');

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
<tr><td colspan="2" class="rowspacer0x7"><img src="img/leer.gif" alt="" width="1" height="1" /></td></tr>

<tr>
	<td align="right" class="chatlist"><?php echo $BL['be_admin_struct_template']; ?>:&nbsp;</td>
	<td>
		<select name="template" id="template" class="width150"<?php if(count($tab_fieldgroups)): ?> onchange="return toggleTabsTemplate(this);"<?php endif; ?>>
			<?php echo $tab_template_options; ?>
		</select>
	</td>
</tr>

<tr><td colspan="2" class="rowspacer7x7"><img src="img/leer.gif" alt="" width="1" height="1" /></td></tr>

<tr>
	<td id="col_1_width">&nbsp;</td>
	<td><table cellpadding="0" cellspacing="0" border="0" summary="">
    	<tr>
    		<td class="tdbottom6"><button class="btn_add_tab" id="btn_add_tab">
				<span><?php echo $BL['be_tab_add'] ?></span>
			</button></td>
            <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
            <td><input type="checkbox" name="tabwysiwygoff" id="tabwysiwygoff" value="1"<?php is_checked(1, $content['tabwysiwygoff']) ?> /></td>
            <td class="chatlist"><label for="tabwysiwygoff">&nbsp;<?php echo $BL['be_cnt_no_wysiwyg_editor'] ?></label></td>
		</tr>
    </table></td>
</tr>

<tr>
	<td colspan="2">

		<div id="tabs">
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
			<div id="tab<?php echo $key ?>" class="tab tab-collapsed">
				<table class="tab-container" cellpadding="0" cellspacing="0">
					<tr>
						<td class="chatlist col1w nowrap" align="right" nowrap="nowrap">
							<a href="#" onclick="return toggleTab('tab<?php echo $key ?>');" class="toggle-item" title="<?php echo $BL['be_tab_toggle']; ?>"></a>
							<?php echo $BL['be_tab_name']; ?>:&nbsp;</td>
						<td class="tdbottom2"><input type="text" name="tabtitle[<?php echo $key ?>]" id="tabtitle<?php echo $key ?>" value="<?php echo html($value['tabtitle']); ?>" class="f11b width400" /></td>
						<td class="nowrap" style="padding-right:5px;">
							<em class="handle" title="<?php echo $sort_up_down; ?>"></em>
							<a href="#" onclick="return deleteTab('tab<?php echo $key ?>');" class="tab-delete"><img src="img/famfamfam/tab_delete.gif" alt="" border="" /></a>
						</td>
					</tr>
					<tr class="tab-collapsable-row">
						<td class="chatlist col1w" align="right"><?php echo $BL['be_headline'] ?>:&nbsp;</td>
						<td colspan="2" class="tdbottom2"><input type="text" name="tabheadline[<?php echo $key ?>]" id="tabheadline<?php echo $key ?>" value="<?php echo html($value['tabheadline']); ?>" class="v11 width400" /></td>
					</tr>
					<tr class="tab-collapsable-row">
						<td class="chatlist col1w" align="right"><?php echo $BL['be_admin_page_link'] ?>:&nbsp;</td>
						<td colspan="2"><input type="text" name="tablink[<?php echo $key ?>]" id="tablink<?php echo $key ?>" value="<?php echo (isset($value['tablink']) ? html($value['tablink']) : ''); ?>" class="v11 width400" /></td>
					</tr>
					<tr class="tab-collapsable-row">
						<td colspan="3" class="tdtop5 tdbottom5 tdleft5 tdright5"><textarea class="width540 autosize" name="tabtext[<?php echo $key ?>]" id="tabtext<?php echo $key ?>" rows="5"><?php echo html($value['tabtext']); ?></textarea></td>
					</tr>
<?php
			if($value['custom_field_items']):
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
?>
					<tr class="tab-collapsable-row">
						<td class="chatlist tdtop8 nowrap" align="right" nowrap="nowrap">&nbsp;&nbsp;<?php
							if($tab_fieldgroup['fields'][$custom_field]['type'] !== 'bool') {
								if(isset($tab_fieldgroup['fields'][$custom_field]['legend'])) {
									echo html($tab_fieldgroup['fields'][$custom_field]['legend']);
								} else {
									echo $BL['be_custom_textfield'].' #'.($custom_field_key+1);
								}
								echo ':';
							}
						?>&nbsp;</td>
						<td colspan="2" class="tdtop5">
			<?php
					// support only type "str" or "textarea" at the moment
					if(empty($tab_fieldgroup['fields'][$custom_field]['type']) || !in_array($tab_fieldgroup['fields'][$custom_field]['type'], $custom_tab_field_types)) {
						$tab_fieldgroup['fields'][$custom_field]['type'] = 'str';
					}

					if($tab_fieldgroup['fields'][$custom_field]['type'] === 'str'):	?>
							<input type="text" name="customfield[<?php echo $key; ?>][<?php echo $custom_field; ?>]" value="<?php
							if(isset($value['custom_fields'][$custom_field])) { echo html($value['custom_fields'][$custom_field]); }
							?>"<?php if(!empty($tab_fieldgroup['fields'][$custom_field]['maxlength'])): ?> maxlength="<?php echo $tab_fieldgroup['fields'][$custom_field]['maxlength']; ?>"<?php endif; ?>
							class="v11 width400"<?php echo $custom_field_placeholder; ?> />
			<?php	elseif($tab_fieldgroup['fields'][$custom_field]['type'] === 'int' || $tab_fieldgroup['fields'][$custom_field]['type'] === 'float'):	?>
							<input type="number" name="customfield[<?php echo $key; ?>][<?php echo $custom_field; ?>]" value="<?php
							echo isset($value['custom_fields'][$custom_field]) ? $value['custom_fields'][$custom_field] : 0;
							?>" class="v11 width100" <?php echo $custom_field_placeholder; ?>
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
			<?php	elseif($tab_fieldgroup['fields'][$custom_field]['type'] === 'textarea'): ?>
							<textarea name="customfield[<?php echo $key; ?>][<?php echo $custom_field; ?>]" class="v11 width400 autosize"<?php echo $custom_field_placeholder; ?> rows="3"><?php if(isset($value['custom_fields'][$custom_field])) { echo html($value['custom_fields'][$custom_field]); } ?></textarea>
			<?php	elseif($tab_fieldgroup['fields'][$custom_field]['type'] === 'option' && !empty($tab_fieldgroup['fields'][$custom_field]['values'])):
						foreach($tab_fieldgroup['fields'][$custom_field]['values'] as $option_key => $option_label): ?>
							<label class="radio tab-option-radio">
								<input type="radio" name="customfield[<?php echo $key; ?>][<?php echo $custom_field; ?>]" value="<?php echo ($option_key === 'empty' ? '' : $option_key); ?>"<?php
									if(isset($value['custom_fields'][$custom_field]) && $value['custom_fields'][$custom_field] === $option_key):
								?> checked="checked"<?php
									elseif(empty($value['custom_fields'][$custom_field]) && !empty($tab_fieldgroup['fields'][$custom_field]['default']) && $tab_fieldgroup['fields'][$custom_field]['default'] === $option_key):
								?> checked="checked"<?php endif; ?> /> <?php echo html($option_label); ?>
							</label>
			<?php		endforeach; ?>
			<?php	elseif($tab_fieldgroup['fields'][$custom_field]['type'] === 'select' && !empty($tab_fieldgroup['fields'][$custom_field]['values'])): ?>
							<select name="customfield[<?php echo $key; ?>][<?php echo $custom_field; ?>]">
			<?php		foreach($tab_fieldgroup['fields'][$custom_field]['values'] as $option_key => $option_label): ?>
								<option value="<?php echo ($option_key === 'empty' ? '' : $option_key); ?>"<?php
									if(isset($value['custom_fields'][$custom_field]) && $value['custom_fields'][$custom_field] === $option_key):
								?> selected="selected"<?php
									elseif(empty($value['custom_fields'][$custom_field]) && !empty($tab_fieldgroup['fields'][$custom_field]['default']) && $tab_fieldgroup['fields'][$custom_field]['default'] === $option_key):
								?> selected="selected"<?php endif; ?>><?php echo html($option_label); ?></option>
			<?php		endforeach; ?>
							</select>
			<?php	elseif($tab_fieldgroup['fields'][$custom_field]['type'] === 'bool'): ?>
							<label class="checkbox tab-option-checkbox">
								<input type="checkbox" name="customfield[<?php echo $key; ?>][<?php echo $custom_field; ?>]" value="1"<?php
									if((!empty($value['custom_fields'][$custom_field])) || (!isset($value['custom_fields'][$custom_field]) && !empty($tab_fieldgroup['fields'][$custom_field]['default']))):
								?> checked="checked"<?php endif; ?> /> <?php echo html($tab_fieldgroup['fields'][$custom_field]['legend']); ?>
							</label>
            <?php   elseif($tab_fieldgroup['fields'][$custom_field]['type'] === 'file'): ?>

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
                                            class="width375 greyed"
                                            value="<?php
                                            if(isset($value['custom_fields'][$custom_field]['name'])) {
                                                echo html($value['custom_fields'][$custom_field]['name']);
                                            }
                                            ?>"
                                            size="40"
                                            onfocus="this.blur()"
                                        />
                                    </td>
                                    <td><a
                                        href="#"
                                        title="<?php echo $BL['be_cnt_openmediabrowser'] ?>"
                                        onclick="openFileBrowser('filebrowser.php?opt=19&field=<?php echo $custom_field.'_'.$key; ?>&allowed=<?php echo $tab_fieldgroup['fields'][$custom_field]['filetypes']; ?>');return false;"
                                        ><img src="img/button/open_image_button.gif" alt="" border="0" hspace="3" /></a></td>
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
                                            class="width375 autosize"
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

            <?php   endif; ?>
						</td>
					</tr>
<?php
				endforeach;
			endif;
?>
				</table>
			</div>
<?php
		endforeach;
	endif;
?>
		</div>
	</td>
</tr>

<tr>
	<td colspan="2" class="rowspacer0x7" style="font-size:1px;height:0;overflow:hidden;">
		<input type="hidden" name="tab_fieldgroup" value="<?php echo $tab_fieldgroups_active; ?>" /><?php
		if(count($custom_tab_fields_hidden)) {
			echo implode('', $custom_tab_fields_hidden);
		}
		?><script type="text/javascript">

	var entries = 0;

    function setIdName(field, file_id, file_name) {
        if(typeof file_id === 'undefined' || typeof file_name === 'undefined' || typeof field === 'undefined') {
            return null;
        }
        var field_id = $('customfield_'+field+'_id');
        if(!field_id) {
            return null;
        }
        field_id.value = file_id;
        $('customfield_'+field+'_name').value = file_name;
    }

	window.addEvent('domready', function() {

		var $tabs = $('tabs');
		entries = $tabs.getChildren().length;

		$('btn_add_tab').addEvent('click', function(event) {
			event = new Event(event).stop();

			var entry = '<table class="tab-container" cellpadding="0" cellspacing="0">';
			entry += '<tr><td class="chatlist col1w" align="right"> ';
			entry += '<a href="#" onclick="return toggleTab(\'tab' + entries + '\');" class="toggle-item" title="<?php echo $BL['be_tab_toggle']; ?>"><'+'/a> ';
			entry += '<?php echo $BL['be_tab_name'] ?>:&nbsp;<'+'/td>';
			entry += '<td class="tdbottom2"><input type="text" name="tabtitle[' + entries + ']" id="tabtitle' + entries + '" value="" class="f11b width400" /'+'><'+'/td>';
			entry += '<td style="padding-right:5px;"><a href="#" onclick="return deleteTab(\'tab' + entries + '\');" class="tab-delete"><img src="img/famfamfam/tab_delete.gif" alt="" border="" /><'+'/a> <'+'/td><'+'/tr>';
			entry += '<tr class="tab-collapsable-row"><td class="chatlist col1w" align="right"><?php echo $BL['be_headline'] ?>:&nbsp;<'+'/td>';
			entry += '<td colspan="2" class="tdbottom2"><input type="text" name="tabheadline[' + entries + ']" id="tabheadline' + entries + '" value="" class="v11 width400" /'+'><'+'/td><'+'/tr>';
			entry += '<tr class="tab-collapsable-row"><td class="chatlist col1w" align="right"><?php echo $BL['be_admin_page_link'] ?>:&nbsp;<'+'/td>';
			entry += '<td colspan="2"><input type="text" name="tablink[' + entries + ']" id="tablink' + entries + '" value="" class="v11 width400" /'+'><'+'/td><'+'/tr>';
			entry += '<tr class="tab-collapsable-row"><td colspan="3" class="tdtop5 tdbottom10 tdleft5 tdright5"><textarea name="tabtext[' + entries + ']" id="tabtext' + entries + '" rows="10" class="width540 autosize">';
			entry += '<'+'/textarea><'+'/td><'+'/tr>';
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
			entry += '<tr class="tab-collapsable-row">';
			entry += '<td class="chatlist tdtop4 nowrap" align="right" nowrap="nowrap">&nbsp;&nbsp;<?php
						if($tab_fieldgroup['fields'][$custom_field]['type'] !== 'bool') {
							if(isset($tab_fieldgroup['fields'][$custom_field]['legend'])) {
								echo html($tab_fieldgroup['fields'][$custom_field]['legend']);
							} else {
								echo $BL['be_custom_textfield'].' #'.($custom_field_key+1);
							}
							echo ':';
						}
					?>&nbsp;<'+'/td>';
			entry += '<td colspan="2" class="tdbottom2">';
<?php	if($tab_fieldgroup['fields'][$custom_field]['type'] === 'str'):	?>
			entry += '<input type="text" name="customfield[' + entries + '][<?php echo $custom_field; ?>]" value=""<?php if(!empty($tab_fieldgroup['fields'][$custom_field]['maxlength'])): ?> maxlength="<?php echo $tab_fieldgroup['fields'][$custom_field]['maxlength']; ?>"<?php endif; ?> class="v11 width400"<?php echo $custom_field_placeholder; ?> '+'/>';
<?php	elseif($tab_fieldgroup['fields'][$custom_field]['type'] === 'textarea'): ?>
			entry += '<textarea name="customfield[' + entries + '][<?php echo $custom_field; ?>]" class="v11 width400 autosize" rows="<?php echo empty($tab_fieldgroup['fields'][$custom_field]['rows']) ? '3' : $tab_fieldgroup['fields'][$custom_field]['rows']; ?>"<?php echo $custom_field_placeholder; ?>><'+'/textarea>';
<?php	elseif($tab_fieldgroup['fields'][$custom_field]['type'] === 'option' && !empty($tab_fieldgroup['fields'][$custom_field]['values'])):
			foreach($tab_fieldgroup['fields'][$custom_field]['values'] as $option_key => $option_label): ?>
			entry += '<label class="radio tab-option-radio"><input type="radio" name="customfield[' + entries + '][<?php echo $custom_field; ?>]" value="<?php echo $option_key; ?>"<?php if(!empty($tab_fieldgroup['fields'][$custom_field]['default']) && $tab_fieldgroup['fields'][$custom_field]['default'] === $option_key): ?> checked="checked"<?php endif; ?>'+'/> <?php echo html($option_label); ?><'+'/label> ';
<?php		endforeach;
		elseif($tab_fieldgroup['fields'][$custom_field]['type'] === 'int' || $tab_fieldgroup['fields'][$custom_field]['type'] === 'float'):	?>
			entry += '<input type="number" name="customfield[' + entries + '][<?php echo $custom_field; ?>]" value="0" class="v11 width100"<?php echo $custom_field_placeholder; ?>';
			<?php if(!empty($tab_fieldgroup['fields'][$custom_field]['min'])): ?>entry += ' min="<?php echo $tab_fieldgroup['fields'][$custom_field]['min']; ?>"';<?php endif; ?>
			<?php if(!empty($tab_fieldgroup['fields'][$custom_field]['max'])): ?>entry += ' max="<?php echo $tab_fieldgroup['fields'][$custom_field]['max']; ?>"';<?php endif; ?>
			<?php if(!empty($tab_fieldgroup['fields'][$custom_field]['step'])): ?>entry += ' step="<?php echo $tab_fieldgroup['fields'][$custom_field]['step']; ?>"';<?php endif; ?>
			entry += ' />';
<?php	elseif($tab_fieldgroup['fields'][$custom_field]['type'] === 'select' && !empty($tab_fieldgroup['fields'][$custom_field]['values'])): ?>
			entry += '<select name="customfield[' + entries + '][<?php echo $custom_field; ?>]">';
			<?php		foreach($tab_fieldgroup['fields'][$custom_field]['values'] as $option_key => $option_label): ?>
			entry += '<option value="<?php echo ($option_key === 'empty' ? '' : $option_key); ?>"<?php if(!empty($tab_fieldgroup['fields'][$custom_field]['default']) && $tab_fieldgroup['fields'][$custom_field]['default'] === $option_key): ?> selected="selected"<?php endif; ?>><?php echo html($option_label); ?><'+'/option>';
			<?php		endforeach; ?>
			entry += '</select>';
<?php	elseif($tab_fieldgroup['fields'][$custom_field]['type'] === 'bool'): ?>
			entry += '<label class="checkbox tab-option-checkbox">';
			entry += '<input type="checkbox" name="customfield[' + entries + '][<?php echo $custom_field; ?>]" value="1"<?php if(!empty($tab_fieldgroup['fields'][$custom_field]['default'])): ?> checked="checked"<?php endif; ?>'+'/> ';
			entry += '<?php echo html($tab_fieldgroup['fields'][$custom_field]['legend']); ?></label>';
<?php   elseif($tab_fieldgroup['fields'][$custom_field]['type'] === 'file'): ?>

            entry += '<table border="0" cellpadding="0" cellspacing="0">';
            entry += '  <tr>';
            entry += '      <td>';
            entry += '          <input';
            entry += '              name="customfield[' + entries + '][<?php echo $custom_field; ?>][id]"';
            entry += '              type="hidden"';
            entry += '              id="customfield_<?php echo $custom_field; ?>_' + entries + '_id"';
            entry += '              value=""';
            entry += '          />';
            entry += '          <input';
            entry += '              name="customfield[' + entries + '][<?php echo $custom_field; ?>][name]"';
            entry += '              type="text"';
            entry += '              id="customfield_<?php echo $custom_field; ?>_' + entries + '_name"';
            entry += '              class="width375 greyed"';
            entry += '              value=""';
            entry += '              size="40"';
            entry += '              onfocus="this.blur()"';
            entry += '          />';
            entry += '      </td>';
            entry += '      <td><a';
            entry += '              href="#"';
            entry += '              title="<?php echo $BL['be_cnt_openmediabrowser'] ?>"';
            entry += '              onclick="openFileBrowser(\'filebrowser.php?opt=19&field=<?php echo $custom_field; ?>_' + entries + '&allowed=<?php echo $tab_fieldgroup['fields'][$custom_field]['filetypes']; ?>\');return false;"';
            entry += '          ><img src="img/button/open_image_button.gif" alt="" border="0" hspace="3" /><'+'/a><'+'/td>';
            entry += '      <td><a';
            entry += '              href="#"';
            entry += '              title="<?php echo $BL['be_cnt_delmedia'] ?>"';
            entry += '              onclick="getObjectById(\'customfield_<?php
                                        echo $custom_field; ?>_' + entries + '_name\').value=\'\';getObjectById(\'customfield_<?php
                                        echo $custom_field; ?>_' + entries + '_id\').value=\'\';getObjectById(\'customfield_<?php
                                        echo $custom_field; ?>_' + entries + '_description\').value=\'\';this.blur();return false;"';
            entry += '          ><img src="img/button/del_image_button.gif" alt="" border="0" /><'+'/a><'+'/td>';
            entry += '  <'+'/tr>';
            entry += '  <tr>';
            entry += '      <td colspan="3" class="tdtop5">';
            entry += '          <textarea';
            entry += '              name="customfield[' + entries + '][<?php echo $custom_field; ?>][description]"';
            entry += '              cols="40"';
            entry += '              rows="2"';
            entry += '              class="width375 autosize"';
            entry += '              id="customfield_<?php echo $custom_field; ?>_' + entries + '_description"></textarea>';
            entry += '          <span class="caption width400">';
            entry += '              <?php echo $BL['be_cnt_description']; ?> |';
            entry += '              <?php echo $BL['be_fprivedit_filename']; ?> |';
            entry += '              <?php echo $BL['be_caption_file_title']; ?> |';
            entry += '              <?php echo $BL['be_cnt_target']; ?> |';
            entry += '              <?php echo $BL['be_caption_file_imagesize']; ?> |';
            entry += '              <?php echo $BL['be_copyright']; ?>';
            entry += '          <'+'/span>';
            entry += '      <'+'/td>';
            entry += '  <'+'/tr>';
            entry += '<'+'/table>';

<?php   endif; ?>
			entry += '<'+'/td><'+'/tr>';
<?php
				endforeach;
			endif;
?>
			entry    += '<'+'/table>';

			var tab = new Element('div', {
				'id': 'tab'+entries,
				'class': 'tab nomove'
			}).setHTML(entry).injectInside($('tabs'));

<?php if($content['wysiwyg']): ?>
			EnableCKEditor(entries);<?php endif; ?>
			window.scrollTo(0, tab.getCoordinates()['top']);
			entries++;
		});

<?php if($content['wysiwyg']): ?>
		if(entries > 0) {
			for(var x = 0; x < entries; x++) {
				EnableCKEditor(x);
			}
		}
<?php endif; ?>

		new Sortables($tabs, {
            handles: 'em.handle'
		});
	});

<?php if($content['wysiwyg']):

	// CKEditor Tabs configuration
	$content['ckconfig'] = array();

	if(isset($_SESSION["wcs_user_lang"])) {
		$content['ckconfig'][] = "language: '" . $_SESSION["wcs_user_lang"] ."'";
	}
	if(is_file(PHPWCMS_TEMPLATE.'config/ckeditor/ckeditor.config-tabs.js')) {
		$content['ckconfig'][] = 'customConfig: "' . PHPWCMS_URL.TEMPLATE_PATH . 'config/ckeditor/ckeditor.config-tabs.js"';
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

		$content['ckconfig'][] = 'width: 538';
		$content['ckconfig'][] = 'height: 150';
		$content['ckconfig'][] = 'toolbarCanCollapse: true';
		$content['ckconfig'][] = 'toolbarStartupExpanded: false';
		$content['ckconfig'][] = 'forcePasteAsPlainText: true';
		$content['ckconfig'][] = 'pasteFromWordRemoveFontStyles: true';
		$content['ckconfig'][] = 'pasteFromWordRemoveStyles: true';
		$content['ckconfig'][] = 'pasteFromWordPromptCleanup: true';
	}
	if(!empty($GLOBALS['phpwcms']['FCK_FileBrowser'])) {
		$content['ckconfig'][] = 'filebrowserBrowseUrl: "'.PHPWCMS_URL.'filebrowser.php?opt=16"';
		$content['ckconfig'][] = 'filebrowserImageBrowseUrl: "'.PHPWCMS_URL.'filebrowser.php?opt=17"';
		$content['ckconfig'][] = 'filebrowserWindowWidth: 640';
		$content['ckconfig'][] = 'filebrowserWindowHeight: 480';
	}

	$content['ckconfig'] = ', {' . implode(',', $content['ckconfig']) . '}';

?>
	function EnableCKEditor(x) {
		if( $('tabtext'+x) ) {
			CKEDITOR.replace('tabtext'+x<?php echo $content['ckconfig'] ?>);
		}
	}
<?php endif; ?>

	function deleteTab(e) {
		if(confirm('<?php echo $BL['be_tab_delete_js'] ?>')) {
			$(e).remove();
		}
		return false;
	}
	function toggleTab(e) {
		if($(e).hasClass('tab-collapsed')) {
			$(e).removeClass('tab-collapsed');
		} else {
			$(e).addClass('tab-collapsed');
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

	</script></td>
</tr>