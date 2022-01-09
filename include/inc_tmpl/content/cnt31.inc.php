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


//images special

initMootools();

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
    'width_zoom'    => $phpwcms['img_prev_width'],
    'height_zoom'   => $phpwcms['img_prev_height'],
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
    'freetext'      => '',
    'images'        => array()
);

$content['image_special'] = isset($content['image_special']) ? array_merge($content['image_default'], $content['image_special']) : $content['image_default'];

$tab_fieldgroup_templates = array();
if(isset($template_default['settings']['imagespecial_custom_fields']) && is_array($template_default['settings']['imagespecial_custom_fields']) && count($template_default['settings']['imagespecial_custom_fields'])) {
    $tab_fieldgroups = $template_default['settings']['imagespecial_custom_fields'];
    foreach($template_default['settings']['imagespecial_custom_fields'] as $key => $tab_fieldgroup) {
        $tab_fieldgroup_templates[ $tab_fieldgroup['template'] ] = $key;
    }
} else {
    $tab_fieldgroups = array();
}

?>
<tr><td colspan="2" class="rowspacer0x7"><img src="img/leer.gif" alt="" width="1" height="1" /></td></tr>

<tr>
    <td align="right" class="chatlist"><?php echo $BL['be_admin_struct_template']; ?>:&nbsp;</td>
    <td><table border="0" cellpadding="0" cellspacing="0" summary="">
        <tr>
            <td><select name="template" id="template" class="width150">
<?php

    $tab_fieldgroups_active = isset($tab_fieldgroup_templates['default']) ? $tab_fieldgroup_templates['default'] : '';

    echo '<option value=""'.(empty($content["image_template"]) ? ' selected="selected"' : '').'>'.$BL['be_admin_tmpl_default'].'</option>'.LF;

    $tmpllist = get_tmpl_files(PHPWCMS_TEMPLATE.'inc_cntpart/imagespecial');

    if(is_array($tmpllist) && count($tmpllist)) {
        foreach($tmpllist as $val) {
            // do not show listmode templates
            if(substr($val, 0, 5) == 'list.') {
                continue;
            }

            if(isset($content["image_template"]) && $val == $content["image_template"]){
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
            </select></td>

        <td class="chatlist">&nbsp;&nbsp;&nbsp;<?php echo $BL['be_image_align'] ?>:&nbsp;</td>

         <td>
            <select name="cimage_center" id="cimage_center" class="v11 width150">
                <option value="0"<?php is_selected(0, $content['image_special']['center']); ?>><?php echo $BL['be_cnt_imagenocenter'] ?></option>
                <option value="1"<?php is_selected(1, $content['image_special']['center']); ?>><?php echo $BL['be_cnt_imagecenter'] ?></option>
                <option value="2"<?php is_selected(2, $content['image_special']['center']); ?>><?php echo $BL['be_cnt_imagecenterh'] ?></option>
                <option value="3"<?php is_selected(3, $content['image_special']['center']); ?>><?php echo $BL['be_cnt_imagecenterv'] ?></option>
            </select>
        </td>

        </tr>

    </table></td>

</tr>

<tr><td colspan="2" class="rowspacer7x7"><img src="img/leer.gif" alt="" width="1" height="1" /></td></tr>

<tr>
    <td align="right" class="chatlist"><?php echo $BL['be_flashplayer_thumbnail'] ?>:&nbsp;</td>
    <td valign="top"><table border="0" cellpadding="0" cellspacing="0" summary="">
        <tr>
            <td><input name="cimage_width" type="text" class="f11b" id="cimage_width" style="width: 50px;" size="4" maxlength="4" onkeyup="setCimageCenterInactive();" value="<?php echo $content['image_special']['width']; ?>" /></td>
            <td class="chatlist">&nbsp;x&nbsp;</td>

            <td><input name="cimage_height" type="text" class="f11b" id="cimage_height" style="width: 50px;" size="4" maxlength="4" onkeyup="setCimageCenterInactive();" value="<?php echo $content['image_special']['height']; ?>" /></td>
            <td class="chatlist">&nbsp;<?php echo $BL['be_image_WxHpx'] ?>&nbsp;&nbsp;&nbsp;</td>

            <td><input type="checkbox" name="cimage_crop" id="cimage_crop" value="1" <?php is_checked(1, $content['image_special']['crop']); ?> /></td>
            <td class="v10"><label for="cimage_crop" class="checkbox"><?php echo $BL['be_image_crop'] ?></label></td>
        </tr>
    </table></td>
</tr>

<tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="5" /></td></tr>

<tr>
    <td align="right" class="chatlist">&nbsp;</td>
    <td valign="top"><table border="0" cellpadding="0" cellspacing="0" summary="">
        <tr>
            <td class="chatlist"><?php echo $BL['be_cnt_column'] ?>:&nbsp;</td>
            <td><select name="cimage_col" id="cimage_col">
<?php

// list select menu for max image columns
for($max_image_col = 1; $max_image_col <= 25; $max_image_col++) {

    echo '<option value="'.$max_image_col.'" ';
    is_selected($max_image_col, $content['image_special']['col']);
    echo '>'.$max_image_col.'</option>'.LF;

}

?>
              </select></td>
              <td class="chatlist">&nbsp;&nbsp;<?php echo $BL['be_cnt_imagespace'] ?>:&nbsp;</td>
              <td><input name="cimage_space" type="text" class="f11b" id="cimage_space" style="width: 50px;" size="2" maxlength="3" onkeyup="if(!parseInt(this.value,10)) this.value='';" value="<?php echo $content['image_special']['space']; ?>" /></td>
              <td class="chatlist">&nbsp;px&nbsp;&nbsp;&nbsp;</td>

            </tr>
        </table></td>
</tr>

<tr><td colspan="2" class="rowspacer7x7"><img src="img/leer.gif" alt="" width="1" height="1" /></td></tr>

<tr>
    <td align="right" class="chatlist"><?php echo $BL['be_cnt_reference_zoom'] ?>:&nbsp;</td>
    <td valign="top"><table border="0" cellpadding="0" cellspacing="0" summary="">
        <tr>
            <td><input name="cimage_width_zoom" type="text" class="f11b" id="cimage_width_zoom" style="width: 50px;" size="4" maxlength="4" value="<?php echo $content['image_special']['width_zoom']; ?>" /></td>
            <td class="chatlist">&nbsp;x&nbsp;</td>

            <td><input name="cimage_height_zoom" type="text" class="f11b" id="cimage_height_zoom" style="width: 50px;" size="4" maxlength="4" value="<?php echo $content['image_special']['height_zoom']; ?>" /></td>
            <td class="chatlist">&nbsp;<?php echo $BL['be_image_WxHpx'] ?>&nbsp;&nbsp;&nbsp;</td>

            <td><input type="checkbox" name="cimage_crop_zoom" id="cimage_crop_zoom" value="1" <?php is_checked(1, $content['image_special']['crop_zoom']); ?> /></td>
            <td class="v10"><label for="cimage_crop_zoom" class="checkbox"><?php echo $BL['be_image_cropit'] ?></label></td>
        </tr>
    </table></td>
</tr>

<tr><td colspan="2" class="rowspacer7x7"><img src="img/leer.gif" alt="" width="1" height="1" /></td></tr>

<tr>
    <td align="right" class="chatlist tdtop3"><?php echo $BL['be_cnt_behavior'] ?>:&nbsp;</td>
    <td><table border="0" cellpadding="0" cellspacing="0" summary="">
            <tr>
                <td><input name="cimage_zoom" type="checkbox" id="cimage_zoom" value="1" <?php is_checked(1, $content['image_special']['zoom']); ?> /></td>
                <td class="v10"><label for="cimage_zoom" class="checkbox"><?php echo $BL['be_cnt_enlarge'] ?></label></td>

                <td>&nbsp;</td>
                <td><input name="cimage_lightbox" type="checkbox" id="cimage_lightbox" value="1" <?php is_checked(1, $content['image_special']['lightbox']); ?> onchange="if(this.checked){getObjectById('cimage_zoom').checked=true;}" /></td>
                <td class="v10"><label for="cimage_lightbox" class="checkbox"><?php echo $BL['be_cnt_lightbox'] ?></label></td>

                <td>&nbsp;</td>
                <td><input name="cimage_nocaption" type="checkbox" id="cimage_nocaption" value="1" <?php is_checked(1, $content['image_special']['nocaption']); ?> /></td>
                <td class="v10"><label for="cimage_nocaption" class="checkbox"><?php echo $BL['be_cnt_imglist_nocaption'] ?></label></td>
            </tr>
            <tr>
                <td><input name="cimage_fx1" type="checkbox" id="cimage_fx1" value="1" <?php is_checked(1, $content['image_special']['fx1']); ?> /></td>
                <td class="v10"><label for="cimage_fx1" class="checkbox"><?php echo $BL['be_fx_1'] ?></label></td>

                <td>&nbsp;</td>
                <td><input name="cimage_fx2" type="checkbox" id="cimage_fx2" value="1" <?php is_checked(1, $content['image_special']['fx2']); ?> /></td>
                <td class="v10"><label for="cimage_fx2" class="checkbox"><?php echo $BL['be_fx_2'] ?></label></td>

                <td>&nbsp;</td>
                <td><input name="cimage_fx3" type="checkbox" id="cimage_fx3" value="1" <?php is_checked(1, $content['image_special']['fx3']); ?> /></td>
                <td class="v10"><label for="cimage_fx3" class="checkbox"><?php echo $BL['be_fx_3'] ?></label></td>
            </tr>
        </table>
    </td>
</tr>

<tr><td colspan="2" class="rowspacer7x7"><img src="img/leer.gif" alt="" width="1" height="1" /></td></tr>

<tr>
    <td class="chatlist tdtop5" align="right"><?php echo $BL['be_ctype_images'] ?>:&nbsp;</td>
    <td class="tdbottom4">
        <button onclick="return addNewImage('top');">
            <span class="btn_image_add"><?php echo $BL['be_article_cnt_add'] ?></span>
        </button>
    </td>
</tr>

<tr>
    <td>&nbsp;</td>
    <td>

    <div id="images">

<?php

    // Sort/Up Down Title
    $sort_up_down = $BL['be_func_struct_sort_up'] . ' / '. $BL['be_func_struct_sort_down'];

    if($tab_fieldgroups_active && isset($template_default['settings']['imagespecial_custom_fields'][$tab_fieldgroups_active])) {
        $tab_fieldgroup =& $template_default['settings']['imagespecial_custom_fields'][$tab_fieldgroups_active];
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

    // loop available image entries
    foreach($content['image_special']['images'] as $key => $value) {

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
        <div id="image_<?php echo $key ?>">

            <table border="0" cellpadding="0" cellspacing="0" summary="">

                <tr>
                    <td class="tdbottom5"><em title="<?php echo $sort_up_down; ?>" class="handle" style="margin:0 5px;">&nbsp;</em></td>
                    <td colspan="2"></td>
                    <td class="tdbottom5" colspan="3"><a href="#" onclick="return deleteImgElement('image_<?php echo $key ?>');"><img src="img/famfamfam/image_delete.gif" alt="" border="" /></a></td>
                </tr>

                <tr>
                    <td class="chatlist right">
                        <input name="cimage_id_thumb[<?php echo $key ?>]" id="cimage_id_thumb_<?php echo $key ?>" type="hidden" value="<?php echo $value['thumb_id'] ?>" />
                        <input name="cimage_sort[<?php echo $key ?>]" id="cimage_sort_<?php echo $key ?>" type="hidden" value="<?php echo $value['sort'] ?>" />
                        <?php echo $BL['be_flashplayer_thumbnail'] ?>:&nbsp;</td>
                    <td><input name="cimage_name_thumb[<?php echo $key ?>]" type="text" id="cimage_name_thumb_<?php echo $key ?>" class="f11b imagename" value="<?php echo html($value['thumb_name']) ?>" size="30" onfocus="this.blur();" /></td>
                    <td><a href="#" title="<?php echo $BL['be_cnt_openimagebrowser'] ?>" onclick="return openImageFileBrowser('thumb_<?php echo $key ?>');"><img src="img/button/open_image_button.gif" alt="" width="20" height="15" border="0" /></a></td>
                    <td><a href="#" title="<?php echo $BL['be_cnt_delimage'] ?>" onclick="return deleteImageData('thumb_<?php echo $key ?>', this);"><img src="img/button/del_image_button.gif" alt="" width="15" height="15" border="0" /></a></td>
                </tr>

                <tr><td colspan="3" class="spacerrow"></td></tr>

                <tr>
                    <td class="chatlist right">
                        <input name="cimage_id_zoom[<?php echo $key ?>]" id="cimage_id_zoom_<?php echo $key ?>" type="hidden" value="<?php echo $value['zoom_id'] ?>" />
                        <?php echo $BL['be_image_zoom'] ?>:&nbsp;</td>
                    <td><input name="cimage_name_zoom[<?php echo $key ?>]" type="text" id="cimage_name_zoom_<?php echo $key ?>" class="f11b imagename" value="<?php echo html($value['zoom_name']) ?>" size="30" onfocus="this.blur();" /></td>
                    <td><a href="#" title="<?php echo $BL['be_cnt_openimagebrowser'] ?>" onclick="return openImageFileBrowser('zoom_<?php echo $key ?>');"><img src="img/button/open_image_button.gif" alt="" width="20" height="15" border="0" /></a></td>
                    <td><a href="#" title="<?php echo $BL['be_cnt_delimage'] ?>" onclick="return deleteImageData('zoom_<?php echo $key ?>', this);"><img src="img/button/del_image_button.gif" alt="" width="15" height="15" border="0" /></a></td>
                </tr>

                <tr>
                    <td class="spacerrow"></td>
                    <td id="img_preview_<?php echo $key ?>" colspan="3" class="backend_preview_img"></td>
                </tr>

                <tr>
                    <td class="chatlist right tdtop3"><?php echo $BL['be_cnt_caption'] ?>:&nbsp;</td>
                    <td colspan="3">
                        <textarea name="cimage_caption[<?php echo $key ?>]" id="cimage_caption_<?php echo $key ?>" class="width300 autosize" cols="30" rows="2"><?php echo html($value['caption']) ?></textarea>
                        <span class="caption width440">
                            <?php echo $BL['be_cnt_caption']; ?>
                            |
                            <?php echo $BL['be_caption_alt']; ?>
                            |
                            <?php echo $BL['be_admin_page_link']; ?> <em><?php echo $BL['be_cnt_target']; ?></em>
                            |
                            <?php echo $BL['be_caption_title']; ?>
                            |
                            <?php echo $BL['be_copyright']; ?>
                        </span>
                    </td>
                </tr>

                <tr><td colspan="3" class="spacerrow"></td></tr>

                <tr>
                    <td class="chatlist right tdtop3"><?php echo $BL['be_cnt_infotext'] ?>:&nbsp;</td>
                    <td colspan="3"><textarea name="cimage_freetext[<?php echo $key ?>]" id="cimage_freetext_<?php echo $key ?>" class="w300 autosize" cols="30" rows="2"><?php echo html(empty($value['freetext']) ? '' : $value['freetext']) ?></textarea></td>
                </tr>

                <tr><td colspan="3" class="spacerrow"></td></tr>

                <tr>
                    <td class="chatlist right"><?php echo $BL['be_profile_label_website'] ?>:&nbsp;</td>
                    <td colspan="3"><input type="text" name="cimage_url[<?php echo $key ?>]" id="cimage_url_<?php echo $key ?>" class="v11 w300" size="30" value="<?php echo html($value['url']) ?>" /></td>
                </tr>

<?php
            if($value['custom_field_items']):

?>
                <tr><td colspan="3" class="spacerrow"></td></tr>
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
                    $is_wysiwyg = $tab_fieldgroup['fields'][$custom_field]['type'] === 'textarea' && !empty($tab_fieldgroup['fields'][$custom_field]['render']) && $tab_fieldgroup['fields'][$custom_field]['render'] === 'wysiwyg';
?>
                    <tr>
                        <td class="chatlist right tdtop8 nowrap" nowrap="nowrap"><?php
                            if($tab_fieldgroup['fields'][$custom_field]['type'] !== 'bool') {
                                if(isset($tab_fieldgroup['fields'][$custom_field]['legend'])) {
                                    echo html($tab_fieldgroup['fields'][$custom_field]['legend']);
                                } else {
                                    echo $BL['be_custom_textfield'].' #'.($custom_field_key+1);
                                }
                                echo ':';
                            }
                        ?>&nbsp;</td>
                        <td colspan="3" class="tdtop5">
            <?php
                    // support only type "str" or "textarea" at the moment
                    if(empty($tab_fieldgroup['fields'][$custom_field]['type']) || !in_array($tab_fieldgroup['fields'][$custom_field]['type'], $custom_tab_field_types)) {
                        $tab_fieldgroup['fields'][$custom_field]['type'] = 'str';
                    }

                    if($tab_fieldgroup['fields'][$custom_field]['type'] === 'str'): ?>
                            <input type="text" name="customfield[<?php echo $key; ?>][<?php echo $custom_field; ?>]" value="<?php
                            if(isset($value['custom_fields'][$custom_field])) { echo html($value['custom_fields'][$custom_field]); }
                            ?>"<?php if(!empty($tab_fieldgroup['fields'][$custom_field]['maxlength'])): ?> maxlength="<?php echo $tab_fieldgroup['fields'][$custom_field]['maxlength']; ?>"<?php endif; ?>
                            class="v11 width400"<?php echo $custom_field_placeholder; ?> />

            <?php   elseif($tab_fieldgroup['fields'][$custom_field]['type'] === 'int' || $tab_fieldgroup['fields'][$custom_field]['type'] === 'float'): ?>
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

            <?php   elseif($tab_fieldgroup['fields'][$custom_field]['type'] === 'textarea'):

                        if ($is_wysiwyg):
                            $wysiwyg_editor = array(
                                'value' => isset($value['custom_fields'][$custom_field]) ? $value['custom_fields'][$custom_field] : '',
                                'field' => 'customfield[' . $key . '][' . $custom_field . ']',
                                'height' => empty($tab_fieldgroup['fields'][$custom_field]['height']) ? '150px' : $tab_fieldgroup['fields'][$custom_field]['height'],
                                'width' => '100%',
                                'rows' => empty($tab_fieldgroup['fields'][$custom_field]['rows']) ? '5' : $tab_fieldgroup['fields'][$custom_field]['rows'],
                                'editor' => $_SESSION["WYSIWYG_EDITOR"],
                                'lang' => 'en',
                                'config' => 'tabs'
                            );

                            include PHPWCMS_ROOT . '/include/inc_lib/wysiwyg.editor.inc.php';

                        else: ?>
                            <textarea name="customfield[<?php echo $key; ?>][<?php echo $custom_field; ?>]" class="v11 width400 autosize"<?php echo $custom_field_placeholder; ?> rows="<?php
                                echo empty($tab_fieldgroup['fields'][$custom_field]['rows']) ? '3' : $tab_fieldgroup['fields'][$custom_field]['rows'];
                            ?>"><?php if(isset($value['custom_fields'][$custom_field])) { echo html($value['custom_fields'][$custom_field]); } ?></textarea><?php
                        endif;

                    elseif($tab_fieldgroup['fields'][$custom_field]['type'] === 'option' && !empty($tab_fieldgroup['fields'][$custom_field]['values'])):
                        foreach($tab_fieldgroup['fields'][$custom_field]['values'] as $option_key => $option_label): ?>
                            <label class="radio tab-option-radio">
                                <input type="radio" name="customfield[<?php echo $key; ?>][<?php echo $custom_field; ?>]" value="<?php echo ($option_key === 'empty' ? '' : $option_key); ?>"<?php
                                    if(isset($value['custom_fields'][$custom_field]) && $value['custom_fields'][$custom_field] === $option_key):
                                ?> checked="checked"<?php
                                    elseif(empty($value['custom_fields'][$custom_field]) && !empty($tab_fieldgroup['fields'][$custom_field]['default']) && $tab_fieldgroup['fields'][$custom_field]['default'] === $option_key):
                                ?> checked="checked"<?php endif; ?> /> <?php echo html($option_label); ?>
                            </label>
            <?php       endforeach; ?>

            <?php   elseif($tab_fieldgroup['fields'][$custom_field]['type'] === 'select' && !empty($tab_fieldgroup['fields'][$custom_field]['values'])): ?>
                            <select name="customfield[<?php echo $key; ?>][<?php echo $custom_field; ?>]">
            <?php       foreach($tab_fieldgroup['fields'][$custom_field]['values'] as $option_key => $option_label): ?>
                                <option value="<?php echo ($option_key === 'empty' ? '' : $option_key); ?>"<?php
                                    if(isset($value['custom_fields'][$custom_field]) && $value['custom_fields'][$custom_field] === $option_key):
                                ?> selected="selected"<?php
                                    elseif(empty($value['custom_fields'][$custom_field]) && !empty($tab_fieldgroup['fields'][$custom_field]['default']) && $tab_fieldgroup['fields'][$custom_field]['default'] === $option_key):
                                ?> selected="selected"<?php endif; ?>><?php echo html($option_label); ?></option>
            <?php       endforeach; ?>
                            </select>

            <?php   elseif($tab_fieldgroup['fields'][$custom_field]['type'] === 'bool'): ?>
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

    }
    // close image entry looping

?>
    </div>

    </td>
</tr>

<?php
    // second button to add images at bottom of list
    if (count($content['image_special']['images'])) {
?>
<tr>
    <td class="chatlist tdtop5" align="right"><?php echo $BL['be_ctype_images'] ?>:&nbsp;</td>
    <td class="tdbottom4">

    <button onclick="return addNewImage('bottom');">
        <span class="btn_image_add"><?php echo $BL['be_article_cnt_add'] ?></span>
    </button>

    </td>

</tr>
<?php
    }
?>
<tr><td colspan="2" class="rowspacer7x7"><img src="img/leer.gif" alt="" width="1" height="1" /></td></tr>

<tr><td colspan="2" align="center"><?php

$wysiwyg_editor = array(
    'value'     => isset($content["image_html"]) ? $content["image_html"] : '',
    'field'     => 'image_html',
    'height'    => '300px',
    'width'     => '100%',
    'rows'      => '15',
    'editor'    => $_SESSION["WYSIWYG_EDITOR"],
    'lang'      => 'en'
);

include PHPWCMS_ROOT.'/include/inc_lib/wysiwyg.editor.inc.php';

?></td></tr>

<tr>
    <td colspan="2">
        <input type="hidden" name="tab_fieldgroup" value="<?php echo $tab_fieldgroups_active; ?>" /><?php
            if(count($custom_tab_fields_hidden)) {
                echo implode('', $custom_tab_fields_hidden);
            }
        ?>
    <script type="text/javascript">

    var site_url    = '<?php echo PHPWCMS_URL; ?>';
    var max_img_w   = <?php echo $phpwcms['img_list_width']; ?>;
    var max_img_h   = <?php echo $phpwcms['img_list_height']; ?>;
    var image_entry = [];

    function setCimageCenterInactive() {
        var cih = $('cimage_width');
        var ciw = $('cimage_height');
        var cic = $('cimage_center');
        var ccp = $('cimage_crop');
        var dis = false;
        if(!parseInt(cih.value, 10)) {
            cih.value = '';
            dis = true;
        }
        if(!parseInt(ciw.value, 10)) {
            ciw.value = '';
            dis = true;
        }
        if(dis) {
            cic.disabled = true;
            ccp.disabled = true;
        } else {
            cic.disabled = false;
            ccp.disabled = false;
        }
    }

    function openImageFileBrowser(image_number) {
        openFileBrowser('filebrowser.php?opt=8&target=nolist&entry_id='+image_number);
        return false;
    }

    function setImgIdName(image_number, file_id, file_name) {
        if(file_id == null || file_name == null) return null;
        $('cimage_id_'+image_number).value = file_id;
        $('cimage_name_'+image_number).value = file_name;
        image_number = image_number.split('_');
        if(image_number[1]) {
            updatePreviewImage(image_number[1]);
        }
    }

    function setIdName(field, file_id, file_name) {
        if(file_id == null || file_name == null || field == null) {
            return null;
        }
        var field_id = $('customfield_'+field+'_id');
        if(!field_id) {
             return null;
        }
        field_id.value = file_id;
        $('customfield_'+field+'_name').value = file_name;
    }

    function deleteImageData(image_number, e) {
        $('cimage_name_'+image_number).value='';
        $('cimage_id_'+image_number).value='0';
        e.blur();
        image_number = image_number.split('_');
        if(image_number[1]) {
            updatePreviewImage(image_number[1]);
        }
        return false;
    }

    function updatePreviewImage(image_number) {
        var preview = '';
        var cimage_id_thumb = $('cimage_id_thumb_'+image_number);
        var cimage_id_zoom = $('cimage_id_zoom_'+image_number);
        if(cimage_id_thumb) {
            preview += getBackendImgSrc( cimage_id_thumb.value );
        }
        if(cimage_id_zoom) {
            preview += getBackendImgSrc( cimage_id_zoom.value );
        }
        $('img_preview_'+image_number).setHTML(preview);
    }

    function getBackendImgSrc(image_file_id) {
        image_file_id = parseInt(image_file_id, 10);
        if(image_file_id) {
            return '<img src="'+site_url+'<?php echo PHPWCMS_RESIZE_IMAGE; ?>/'+max_img_w+'x'+max_img_h+'/'+image_file_id+'" alt="" style="width:auto" height="'+max_img_h+'"> ';
        }
        return '';
    }

    function updatePreviewImageAll() {
        var all_images = $('images').getElements('div[id^=image_]');
        if(all_images.length > 0) {
            all_images.each(function(e) {
                var image_number = e.id.split('_');
                if(image_number[1]) {
                    updatePreviewImage(image_number[1]);
                    image_entry[ image_number[1] ] = $('cimage_sort_'+image_number[1]).value;
                }
            });
        }
    }

    function addNewImage(where) {

        updatePreviewImageAll();

        var entry_number = image_entry.length;
        var new_entry = '';
        new_entry += '<'+'table border="0" cellpadding="0" cellspacing="0" summary=""'+'>';
        new_entry += '<'+'tr>';
        new_entry += '<'+'td colspan="3"><'+'/td>';
        new_entry += '<'+'td class="tdbottom5"><a href="#" onclick="return deleteImgElement(\'image_'+entry_number+'\');"><img src="img/famfamfam/image_delete.gif" alt="" border="" /'+'><'+'/a></'+'td>';
        new_entry += '<'+'/tr>';
        new_entry += '<'+'tr>';
        new_entry += '<'+'td class="chatlist right">';
        new_entry += '<'+'input name="cimage_id_thumb['+entry_number+']" id="cimage_id_thumb_'+entry_number+'" type="hidden" value="" /'+'>';
        new_entry += '<'+'input name="cimage_sort['+entry_number+']" id="cimage_sort_'+entry_number+'" type="hidden" value="'+entry_number+'" /'+'>';
        new_entry += '<?php echo $BL['be_flashplayer_thumbnail'] ?>:&nbsp;<'+'/td'+'>';
        new_entry += '<'+'td><input name="cimage_name_thumb['+entry_number+']" type="text" id="cimage_name_thumb_'+entry_number+'" class="f11b imagename" value="" size="30" onfocus="this.blur();" /><'+'/td>';
        new_entry += '<'+'td><a href="#" title="<?php echo $BL['be_cnt_openimagebrowser'] ?>" onclick="return openImageFileBrowser(\'thumb_'+entry_number+'\');"><img src="img/button/open_image_button.gif" alt="" width="20" height="15" border="0" /'+'><'+'/a><'+'/td>';
        new_entry += '<'+'td><a href="#" title="<?php echo $BL['be_cnt_delimage'] ?>" onclick="return deleteImageData(\'thumb_'+entry_number+'\', this);"><img src="img/button/del_image_button.gif" alt="" width="15" height="15" border="0" /><'+'/a><'+'/td>';
        new_entry += '<'+'/tr>';
        new_entry += '<'+'tr><td colspan="3" class="spacerrow"><'+'/td><'+'/tr>';
        new_entry += '<'+'tr>';
        new_entry += '<'+'td class="chatlist right">';
        new_entry += '<'+'input name="cimage_id_zoom['+entry_number+']" id="cimage_id_zoom_'+entry_number+'" type="hidden" value="" /'+'>';
        new_entry += '<?php echo $BL['be_image_zoom'] ?>:&nbsp;<'+'/td>';
        new_entry += '<'+'td><input name="cimage_name_zoom['+entry_number+']" type="text" id="cimage_name_zoom_'+entry_number+'" class="f11b imagename" value="" size="30" onfocus="this.blur();" /><'+'/td>';
        new_entry += '<'+'td><a href="#" title="<?php echo $BL['be_cnt_openimagebrowser'] ?>" onclick="return openImageFileBrowser(\'zoom_'+entry_number+'\');"><img src="img/button/open_image_button.gif" alt="" width="20" height="15" border="0" /><'+'/a><'+'/td>';
        new_entry += '<'+'td><a href="#" title="<?php echo $BL['be_cnt_delimage'] ?>" onclick="return deleteImageData(\'zoom_'+entry_number+'\', this);"><img src="img/button/del_image_button.gif" alt="" width="15" height="15" border="0" /><'+'/a><'+'/td>';
        new_entry += '<'+'/tr>';
        new_entry += '<'+'tr>';
        new_entry += '<'+'td class="spacerrow"><'+'/td>';
        new_entry += '<'+'td id="img_preview_'+entry_number+'" colspan="3" class="backend_preview_img"><'+'/td>';
        new_entry += '<'+'/tr>';
        new_entry += '<'+'tr>';
        new_entry += '<'+'td class="chatlist right tdtop3"><?php echo $BL['be_cnt_caption'] ?>:&nbsp;<'+'/td>';
        new_entry += '<'+'td colspan="3"><textarea name="cimage_caption['+entry_number+']" id="cimage_caption_'+entry_number+'" class="width300 autosize" cols="30" rows="2"><'+'/textarea>';
        new_entry += '<span class="caption width300"><?php echo $BL['be_cnt_caption']; ?> | <?php echo $BL['be_caption_alt']; ?> | <?php echo $BL['be_admin_page_link']; ?> <em><?php echo $BL['be_cnt_target']; ?></em> | <?php echo $BL['be_caption_title']; ?> | <?php echo $BL['be_copyright']; ?></span>';
        new_entry += '<'+'/td>';
        new_entry += '<'+'/tr>';
        new_entry += '<'+'tr><td colspan="3" class="spacerrow"><'+'/td><'+'/tr>';
        new_entry += '<'+'tr>';
        new_entry += '<'+'td class="chatlist right tdtop3"><?php echo $BL['be_cnt_infotext'] ?>:&nbsp;<'+'/td>';
        new_entry += '<'+'td colspan="3"><textarea name="cimage_freetext['+entry_number+']" id="cimage_freetext_'+entry_number+'" class="w300 autosize" cols="30" rows="2"><'+'/textarea><'+'/td>';
        new_entry += '<'+'/tr>';
        new_entry += '<'+'tr><td colspan="3" class="spacerrow"><'+'/td><'+'/tr>';
        new_entry += '<'+'tr>';
        new_entry += '<'+'td class="chatlist right"><?php echo $BL['be_profile_label_website'] ?>:&nbsp;<'+'/td>';
        new_entry += '<'+'td colspan="3"><input type="text" name="cimage_url['+entry_number+']" id="cimage_url_'+entry_number+'" class="v11 w300" size="30" value="" /><'+'/td>';
        new_entry += '<'+'/tr>';

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
        new_entry += '<tr class="tab-collapsable-row">';
        new_entry += '<td class="chatlist tdtop4 nowrap" align="right" nowrap="nowrap">&nbsp;&nbsp;<?php
                if($tab_fieldgroup['fields'][$custom_field]['type'] !== 'bool') {
                    if(isset($tab_fieldgroup['fields'][$custom_field]['legend'])) {
                        echo html($tab_fieldgroup['fields'][$custom_field]['legend']);
                    } else {
                        echo $BL['be_custom_textfield'].' #'.($custom_field_key+1);
                    }
                    echo ':';
                }
        ?>&nbsp;<'+'/td>';
        new_entry += '<td colspan="2" class="tdbottom2">';
<?php   if($tab_fieldgroup['fields'][$custom_field]['type'] === 'str'): ?>
        new_entry += '<input type="text" name="customfield[' + entry_number + '][<?php echo $custom_field; ?>]" value=""<?php if(!empty($tab_fieldgroup['fields'][$custom_field]['maxlength'])): ?> maxlength="<?php echo $tab_fieldgroup['fields'][$custom_field]['maxlength']; ?>"<?php endif; ?> class="v11 width400"<?php echo $custom_field_placeholder; ?> '+'/>';

<?php   elseif($tab_fieldgroup['fields'][$custom_field]['type'] === 'textarea'): ?>
        new_entry += '<textarea name="customfield[' + entry_number + '][<?php echo $custom_field; ?>]" class="v11 width400 autosize" rows="<?php echo empty($tab_fieldgroup['fields'][$custom_field]['rows']) ? '3' : $tab_fieldgroup['fields'][$custom_field]['rows']; ?>"<?php echo $custom_field_placeholder; ?>><'+'/textarea>';

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

<?php   elseif($tab_fieldgroup['fields'][$custom_field]['type'] === 'select' && !empty($tab_fieldgroup['fields'][$custom_field]['values'])): ?>
        new_entry += '<select name="customfield[' + entry_number + '][<?php echo $custom_field; ?>]">';
        <?php       foreach($tab_fieldgroup['fields'][$custom_field]['values'] as $option_key => $option_label): ?>
        new_entry += '<option value="<?php echo ($option_key === 'empty' ? '' : $option_key); ?>"<?php if(!empty($tab_fieldgroup['fields'][$custom_field]['default']) && $tab_fieldgroup['fields'][$custom_field]['default'] === $option_key): ?> selected="selected"<?php endif; ?>><?php echo html($option_label); ?><'+'/option>';
        <?php       endforeach; ?>
        new_entry += '</select>';

<?php   elseif($tab_fieldgroup['fields'][$custom_field]['type'] === 'bool'): ?>
        new_entry += '<label class="checkbox tab-option-checkbox">';
        new_entry += '<input type="checkbox" name="customfield[' + entry_number + '][<?php echo $custom_field; ?>]" value="1"<?php if(!empty($tab_fieldgroup['fields'][$custom_field]['default'])): ?> checked="checked"<?php endif; ?>'+'/> ';
        new_entry += '<?php echo html($tab_fieldgroup['fields'][$custom_field]['legend']); ?></label>';

<?php   elseif($tab_fieldgroup['fields'][$custom_field]['type'] === 'file'): ?>

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
        new_entry += '      <td><a';
        new_entry += '              href="#"';
        new_entry += '              title="<?php echo $BL['be_cnt_openmediabrowser'] ?>"';
        new_entry += '              onclick="openFileBrowser(\'filebrowser.php?opt=19&field=<?php echo $custom_field; ?>_' + entry_number + '&allowed=<?php echo $tab_fieldgroup['fields'][$custom_field]['filetypes']; ?>\');return false;"';
        new_entry += '          ><img src="img/button/open_image_button.gif" alt="" border="0" hspace="3" /><'+'/a><'+'/td>';
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

<?php   endif; ?>
        new_entry += '<'+'/td><'+'/tr>';
<?php
            endforeach;
        endif;
?>
        new_entry += '<'+'/table>';

        var new_element = new Element('div', {'id': 'image_'+entry_number, 'class': 'nomove', 'style': 'margin:5px 0'}).inject($('images'),where);
        new_element.innerHTML = new_entry;
        window.location.hash='image_'+entry_number;
        return false;
    }

    function deleteImgElement(e) {
        if(confirm('<?php echo $BL['be_image_delete_js'] ?>')) {
            $(e).remove();
        }
        return false;
    }

    window.addEvent('domready', function() {

        setCimageCenterInactive();
        updatePreviewImageAll();

        new Sortables($('images'), {
            handles: 'em.handle'
        });

    });

    </script>
    </td>
</tr>
