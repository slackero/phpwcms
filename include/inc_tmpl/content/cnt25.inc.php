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


// Flash Media Player

initJQuery(); // We use jQuery here

if(!$content["id"]) {
    include PHPWCMS_ROOT.'/include/inc_lib/content/cnt25.takeval.inc.php';
}

if(!isset($fmp_data['fmp_set_skin_html5'])) {
    $fmp_data['fmp_set_skin_html5'] = '';
}
if(!isset($fmp_data['fmp_int_ext_h264'])) {
    // H.264
    $fmp_data['fmp_int_ext_h264']       = 0;
    $fmp_data['fmp_internal_id_h264']   = 0;
    $fmp_data['fmp_internal_name_h264'] = '';
    $fmp_data['fmp_external_file_h264'] = '';

    // WebM
    $fmp_data['fmp_int_ext_webm']       = 0;
    $fmp_data['fmp_internal_id_webm']   = 0;
    $fmp_data['fmp_internal_name_webm'] = '';
    $fmp_data['fmp_external_file_webm'] = '';

    // Ogg
    $fmp_data['fmp_int_ext_ogg']        = 0;
    $fmp_data['fmp_internal_id_ogg']    = 0;
    $fmp_data['fmp_internal_name_ogg']  = '';
    $fmp_data['fmp_external_file_ogg']  = '';
}

if(!isset($fmp_data['fmp_set_volume'])) {
    $fmp_data['fmp_set_volume'] = 80;
}
if(!isset($fmp_data['fmp_set_preload'])) {
    $fmp_data['fmp_set_preload'] = 'auto';
}
if(!isset($fmp_data['fmp_set_html5only'])) {
    $fmp_data['fmp_set_html5only'] = 0;
}
if(!isset($fmp_data['fmp_set_audio'])) {
    $fmp_data['fmp_set_audio'] = 0;
}
if(!isset($fmp_data['fmp_set_loop'])) {
    $fmp_data['fmp_set_loop'] = 0;
}
if(!isset($fmp_data['fmp_set_downloadbutton'])) {
    $fmp_data['fmp_set_downloadbutton'] = 0;
}

?>
<tr><td colspan="2" class="rowspacer0x7"><img src="img/leer.gif" alt="" width="1" height="1" /></td></tr>

<tr>
    <td align="right" class="chatlist"><?php echo $BL['be_admin_struct_template']; ?>:&nbsp;</td>
    <td><table border="0" cellpadding="0" cellspacing="0" summary="" class="width440">
        <tr>
            <td><select name="fmp_template" id="fmp_template" class="width150">
<?php

    echo '<option value="">'.$BL['be_admin_tmpl_default'].'</option>'.LF;

    // templates for Flash Media Player
    $tmpllist = get_tmpl_files(PHPWCMS_TEMPLATE.'inc_cntpart/flashplayer');
    if(is_array($tmpllist) && count($tmpllist)) {
        foreach($tmpllist as $val) {
            $selected_val = (isset($fmp_data['fmp_template']) && $val == $fmp_data['fmp_template']) ? ' selected="selected"' : '';
            $val = html($val);
            echo '<option value="' . $val . '"' . $selected_val . '>' . $val . '</option>';
        }
    }

?>
            </select></td>

            <td width="20%">&nbsp;</td>

            <td><select name="fmp_width_height" id="fmp_width_height" onchange="setPlayerSize(this);">

                <option><?php echo $BL['be_flashplayer_selectsize'] ?></option>
<?php

    if(empty($template_default['settings']['html5_player']['sizes'])) {

        $template_default['settings']['html5_player']['sizes'] = array(
            '426x240' => '240p',
            '640x360' => '360p',
            '854x480' => '480p',
            '1280x720' => '720p',
            '1920x1080' => '1080p',
            '2560x1440' => '1440p (2k)',
            '3840x2160' => '2160p (4k)',
            '640x267' => '640 x 267 (21:9)',
            '854x356' => '854 x 356 (21:9)',
            '1280x533' => '1280 x 533 (21:9)',
            '1920x800' => '1920 x 800 (21:9)',
            '200x178' => '200 x 178 px',
            '320x240' => '320 x 240 px',
            '380x313' => '380 x 313 px',
            '425x350' => '425 x 350 px',
            '450x338' => '450 x 338 px',
            '500x403' => '500 x 403 px',
            '640x264' => '640 x 264 px',
            '640x480' => '640 x 480 px'
        );

    }

        foreach($template_default['settings']['html5_player']['sizes'] as $val => $option):
?>
                <option value="<?php echo $val ?>"<?php is_selected($val, $fmp_data['fmp_width'].'x'.$fmp_data['fmp_height']); ?>><?php echo html($option); ?></option>

<?php   endforeach; ?>

            </select></td>

            <td>&nbsp;&nbsp;</td>

            <td><input name="fmp_width" type="text" class="width35 center-text" id="fmp_width" size="4" maxlength="4" value="<?php echo $fmp_data['fmp_width']; ?>" /></td>
            <td class="chatlist">&nbsp;x&nbsp;</td>

            <td><input name="fmp_height" type="text" class="width35 center-text" id="fmp_height" size="4" maxlength="4" value="<?php echo $fmp_data['fmp_height']; ?>" /></td>
            <td class="chatlist">&nbsp;px</td>

        </tr>
    </table></td>

</tr>

<tr><td colspan="2" class="rowspacer7x7"><img src="img/leer.gif" alt="" width="1" height="1" /></td></tr>

<tr>
    <td align="right" class="chatlist" valign="top"><strong><?php echo $BL['be_html5_media'] ?></strong>:&nbsp;</td>

    <td><table border="0" cellpadding="0" cellspacing="0" summary="">

        <!-- HTML5 Media H.264 -->
        <tr>
            <td colspan="3" class="chatlist tdbottom3" align="right"><?php echo $BL['be_media_format'] ?>&nbsp;&nbsp;</td>
            <td colspan="3" class="v10 tdbottom3 greyed">&nbsp;<?php echo $BL['be_html5_h264'] ?> &#8212; <i>mp4, m4v, mov, m4p, m4a, mp3, aac, mpeg</i></td>
        </tr>
        <tr>
            <td bgcolor="#E7E8EB"><input name="fmp_int_ext_h264" id="fmp_int_ext0_h264" type="radio" value="0" <?php is_checked(0, $fmp_data['fmp_int_ext_h264']); ?> /></td>
            <td bgcolor="#E7E8EB" class="v10"><label for="fmp_int_ext0_h264"><?php echo $BL['be_cnt_internal'] ?>&nbsp;</label></td>
            <td>&nbsp;<input name="fmp_internal_id_h264" type="hidden" id="fmp_internal_id_h264" value="<?php echo $fmp_data['fmp_internal_id_h264'] ?>" /></td>
            <td><input name="fmp_internal_name_h264" type="text" id="fmp_internal_name_h264" class="width300 greyed" value="<?php echo html($fmp_data['fmp_internal_name_h264']) ?>" size="40" onfocus="this.blur()" onclick="openFileBrowser('filebrowser.php?opt=12');" /></td>
            <td><a href="#" title="<?php echo $BL['be_cnt_openmediabrowser'] ?>" onclick="openFileBrowser('filebrowser.php?opt=12');return false;"><img src="img/button/open_image_button.gif" alt="" border="0" hspace="3" /></a></td>
            <td><a href="#" title="<?php echo $BL['be_cnt_delmedia'] ?>" onclick="getObjectById('fmp_internal_name_h264').value='';getObjectById('fmp_internal_id_h264').value='0';this.blur();return false;"><img src="img/button/del_image_button.gif" alt="" border="0" /></a></td>
        </tr>
        <tr><td colspan="6"><img src="img/leer.gif" alt="" width="1" height="3" /></td></tr>
        <tr>
            <td bgcolor="#E7E8EB"><input name="fmp_int_ext_h264" id="fmp_int_ext1_h264" type="radio" value="1" <?php is_checked(1, $fmp_data['fmp_int_ext_h264']); ?> /></td>
            <td bgcolor="#E7E8EB" class="v10"><label for="fmp_int_ext1_h264"><?php echo $BL['be_cnt_external'] ?>&nbsp;</label></td>
            <td>&nbsp;</td>
            <td colspan="3"><input name="fmp_external_file_h264" type="text" id="fmp_external_file_h264" class="width300" value="<?php echo html($fmp_data['fmp_external_file_h264']) ?>" size="40" /></td>
        </tr>

        <tr><td colspan="6"><img src="img/leer.gif" alt="" width="1" height="6" /></td></tr>

        <!-- HTML5 Media WebM -->
        <tr>
            <td colspan="3" class="chatlist tdbottom3" align="right"><?php echo $BL['be_media_format'] ?>&nbsp;&nbsp;</td>
            <td colspan="3" class="v10 tdbottom3 greyed">&nbsp;<?php echo $BL['be_html5_webm'] ?> &#8212; <i>webm</i></td>
        </tr>
        <tr>
            <td bgcolor="#E7E8EB"><input name="fmp_int_ext_webm" id="fmp_int_ext0_webm" type="radio" value="0" <?php is_checked(0, $fmp_data['fmp_int_ext_webm']); ?> /></td>
            <td bgcolor="#E7E8EB" class="v10"><label for="fmp_int_ext0_webm"><?php echo $BL['be_cnt_internal'] ?>&nbsp;</label></td>
            <td>&nbsp;<input name="fmp_internal_id_webm" type="hidden" id="fmp_internal_id_webm" value="<?php echo $fmp_data['fmp_internal_id_webm'] ?>" /></td>
            <td><input name="fmp_internal_name_webm" type="text" id="fmp_internal_name_webm" class="width300 greyed" value="<?php echo html($fmp_data['fmp_internal_name_webm']) ?>" size="40" onfocus="this.blur()" onclick="openFileBrowser('filebrowser.php?opt=13');" /></td>
            <td><a href="#" title="<?php echo $BL['be_cnt_openmediabrowser'] ?>" onclick="openFileBrowser('filebrowser.php?opt=13');return false;"><img src="img/button/open_image_button.gif" alt="" border="0" hspace="3" /></a></td>
            <td><a href="#" title="<?php echo $BL['be_cnt_delmedia'] ?>" onclick="getObjectById('fmp_internal_name_webm').value='';getObjectById('fmp_internal_id_webm').value='0';this.blur();return false;"><img src="img/button/del_image_button.gif" alt="" border="0" /></a></td>
        </tr>
        <tr><td colspan="6"><img src="img/leer.gif" alt="" width="1" height="3" /></td></tr>
        <tr>
            <td bgcolor="#E7E8EB"><input name="fmp_int_ext_webm" id="fmp_int_ext1_webm" type="radio" value="1" <?php is_checked(1, $fmp_data['fmp_int_ext_webm']); ?> /></td>
            <td bgcolor="#E7E8EB" class="v10"><label for="fmp_int_ext1_webm"><?php echo $BL['be_cnt_external'] ?>&nbsp;</label></td>
            <td>&nbsp;</td>
            <td colspan="3"><input name="fmp_external_file_webm" type="text" id="fmp_external_file_webm" class="width300" value="<?php echo html($fmp_data['fmp_external_file_webm']) ?>" size="40" /></td>
        </tr>

        <tr><td colspan="6"><img src="img/leer.gif" alt="" width="1" height="6" /></td></tr>

        <!-- HTML5 Media Ogg -->
        <tr>
            <td colspan="3" class="chatlist tdbottom3" align="right"><?php echo $BL['be_media_format'] ?>&nbsp;&nbsp;</td>
            <td colspan="3" class="v10 tdbottom3 greyed">&nbsp;<?php echo $BL['be_html5_ogg'] ?> &#8212; <i>.ogg, .ogv, .oga, .ogx</i></td>
        </tr>
        <tr>
            <td bgcolor="#E7E8EB"><input name="fmp_int_ext_ogg" id="fmp_int_ext0_ogg" type="radio" value="0" <?php is_checked(0, $fmp_data['fmp_int_ext_ogg']); ?> /></td>
            <td bgcolor="#E7E8EB" class="v10"><label for="fmp_int_ext0_ogg"><?php echo $BL['be_cnt_internal'] ?>&nbsp;</label></td>
            <td>&nbsp;<input name="fmp_internal_id_ogg" type="hidden" id="fmp_internal_id_ogg" value="<?php echo $fmp_data['fmp_internal_id_ogg'] ?>" /></td>
            <td><input name="fmp_internal_name_ogg" type="text" id="fmp_internal_name_ogg" class="width300 greyed" value="<?php echo html($fmp_data['fmp_internal_name_ogg']) ?>" size="40" onfocus="this.blur()" onclick="openFileBrowser('filebrowser.php?opt=14');" /></td>
            <td><a href="#" title="<?php echo $BL['be_cnt_openmediabrowser'] ?>" onclick="openFileBrowser('filebrowser.php?opt=14');return false;"><img src="img/button/open_image_button.gif" alt="" border="0" hspace="3" /></a></td>
            <td><a href="#" title="<?php echo $BL['be_cnt_delmedia'] ?>" onclick="getObjectById('fmp_internal_name_ogg').value='';getObjectById('fmp_internal_id_ogg').value='0';this.blur();return false;"><img src="img/button/del_image_button.gif" alt="" border="0" /></a></td>
        </tr>
        <tr><td colspan="6"><img src="img/leer.gif" alt="" width="1" height="3" /></td></tr>
        <tr>
            <td bgcolor="#E7E8EB"><input name="fmp_int_ext_ogg" id="fmp_int_ext1_ogg" type="radio" value="1" <?php is_checked(1, $fmp_data['fmp_int_ext_ogg']); ?> /></td>
            <td bgcolor="#E7E8EB" class="v10"><label for="fmp_int_ext1_ogg"><?php echo $BL['be_cnt_external'] ?>&nbsp;</label></td>
            <td>&nbsp;</td>
            <td colspan="3"><input name="fmp_external_file_ogg" type="text" id="fmp_external_file_ogg" class="width300" value="<?php echo html($fmp_data['fmp_external_file_ogg']) ?>" size="40" /></td>
        </tr>

    </table></td>
</tr>

<tr><td colspan="2" class="rowspacer7x7"><img src="img/leer.gif" alt="" width="1" height="1" /></td></tr>

<tr>
    <td align="right" class="chatlist" valign="top"><strong><?php echo $BL['be_flash_media'] ?></strong>:&nbsp;</td>

    <td><table border="0" cellpadding="0" cellspacing="0" summary="">

        <!-- Flash Media Fallback -->
        <tr>
            <td colspan="3" class="chatlist tdbottom3" align="right"><?php echo $BL['be_media_format'] ?>&nbsp;&nbsp;</td>
            <td colspan="3" class="v10 tdbottom3 greyed">&nbsp;Flash &#8212; <i>mp4, mp3, flv, mov, swf, f4v, m4v, jpg, png</i></td>
        </tr>
        <tr>
            <td bgcolor="#E7E8EB"><input name="fmp_int_ext" id="fmp_int_ext0" type="radio" value="0" <?php is_checked(0, $fmp_data['fmp_int_ext']); ?> /></td>
            <td bgcolor="#E7E8EB" class="v10"><label for="fmp_int_ext0"><?php echo $BL['be_cnt_internal'] ?>&nbsp;</label></td>
            <td>&nbsp;<input name="fmp_internal_id" type="hidden" id="fmp_internal_id" value="<?php echo $fmp_data['fmp_internal_id'] ?>" /></td>
            <td><input name="fmp_internal_name" type="text" id="fmp_internal_name" class="width300 greyed" value="<?php echo html($fmp_data['fmp_internal_name']) ?>" size="40" onfocus="this.blur()" onclick="openFileBrowser('filebrowser.php?opt=6');" /></td>
            <td><a href="#" title="<?php echo $BL['be_cnt_openmediabrowser'] ?>" onclick="openFileBrowser('filebrowser.php?opt=6');return false;"><img src="img/button/open_image_button.gif" alt="" border="0" hspace="3" /></a></td>
            <td><a href="#" title="<?php echo $BL['be_cnt_delmedia'] ?>" onclick="getObjectById('fmp_internal_name').value='';getObjectById('fmp_internal_id').value='0';this.blur();return false;"><img src="img/button/del_image_button.gif" alt="" border="0" /></a></td>
        </tr>
        <tr><td colspan="6"><img src="img/leer.gif" alt="" width="1" height="3" /></td></tr>
        <tr>
            <td bgcolor="#E7E8EB"><input name="fmp_int_ext" id="fmp_int_ext1" type="radio" value="1" <?php is_checked(1, $fmp_data['fmp_int_ext']); ?> /></td>
            <td bgcolor="#E7E8EB" class="v10"><label for="fmp_int_ext1"><?php echo $BL['be_cnt_external'] ?>&nbsp;</label></td>
            <td>&nbsp;</td>
            <td colspan="3"><input name="fmp_external_file" type="text" id="fmp_external_file" class="width300" value="<?php echo html($fmp_data['fmp_external_file']) ?>" size="40" /></td>
        </tr>

    </table></td>
</tr>

<tr><td colspan="2" class="rowspacer7x7"><img src="img/leer.gif" alt="" width="1" height="1" /></td></tr>

<tr>
    <td align="right" class="chatlist tdtop3"><?php echo $BL['be_flashplayer_caption'] ?>:&nbsp;</td>
    <td><textarea name="fmp_caption" cols="40" rows="2" class="width440 autosize" id="fmp_caption"><?php echo html($fmp_data['fmp_caption']) ?></textarea></td>
</tr>

<tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="3"></td></tr>

<tr>
    <td align="right" class="chatlist tdtop3"><?php echo $BL['be_admin_page_link'] ?>:&nbsp;</td>
    <td><input name="fmp_link" type="text" id="fmp_link" class="width440" value="<?php echo html($fmp_data['fmp_link']) ?>" size="40" /></td>
</tr>

<tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="3"></td></tr>

<tr>
    <td align="right" class="chatlist"><?php echo $BL['be_flashplayer_thumbnail'] ?>:&nbsp;</td>

    <td><table border="0" cellpadding="0" cellspacing="0" summary="">

        <tr>
            <td><input name="fmp_img_name" type="text" id="fmp_img_name" class="width300 greyed" value="<?php echo html($fmp_data['fmp_img_name']) ?>" size="40" onfocus="this.blur()" onclick="openFileBrowser('filebrowser.php?opt=7');" /></td>
            <td><a href="#" title="<?php echo $BL['be_cnt_openmediabrowser'] ?>" onclick="openFileBrowser('filebrowser.php?opt=7');return false;"><img src="img/button/open_image_button.gif" alt="" border="0" hspace="3" /></a></td>
            <td><a href="#" title="<?php echo $BL['be_cnt_delmedia'] ?>" onclick="getObjectById('fmp_img_name').value='';getObjectById('fmp_img_id').value='0';this.blur();return false;"><img src="img/button/del_image_button.gif" alt="" border="0" /></a>
            <input name="fmp_img_id" type="hidden" id="fmp_img_id" value="<?php echo $fmp_data['fmp_img_id'] ?>" />
            </td>
        </tr>

    </table></td>
</tr>

<tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="3"></td></tr>

<tr>
    <td align="right" class="chatlist tdtop3"><?php echo $BL['be_flashplayer_marker'] ?>:&nbsp;</td>
    <td>
        <textarea name="fmp_marker" cols="40" rows="2" class="width440 autosize" id="fmp_marker"><?php echo html($fmp_data['fmp_marker']) ?></textarea>
        <span class="caption width440">
            <?php echo $BL['be_marker_time']; ?>
            |
            <?php echo $BL['be_marker_text']; ?>
            |
            <?php echo $BL['be_marker_overlaytext']; ?>
            |
            <?php echo $BL['be_cnt_css_class']; ?>&nbsp;&crarr;&nbsp;&hellip;
        </span>
        <div id="fmp_marker_links" style="display:none;" class="tdtop5"></div>
    </td>
</tr>


<tr><td colspan="2" class="rowspacer10x10"><img src="img/leer.gif" alt="" width="1" height="1" /></td></tr>


<tr>
    <td align="right" class="chatlist tdtop3"><?php echo $BL['be_settings'] ?>:&nbsp;</td>

    <td><table border="0" cellpadding="0" cellspacing="0" summary="" class="settings">

        <tr>
            <td class="chatlist" align="right"><label for="fmp_set_html5only"><?php echo $BL['be_display_html5_only'] ?>:&nbsp;</label></td>
            <td><input type="checkbox" name="fmp_set_html5only" id="fmp_set_html5only" value="1"<?php is_checked(1, $fmp_data['fmp_set_html5only']) ?> /></td>
        </tr>

        <tr>
            <td class="chatlist" align="right"><label for="fmp_set_audio"><?php echo $BL['be_audio_only'] ?>:&nbsp;</label></td>
            <td><input type="checkbox" name="fmp_set_audio" id="fmp_set_audio" value="1"<?php is_checked(1, $fmp_data['fmp_set_audio']) ?> /></td>
        </tr>

        <tr>
            <td class="chatlist" align="right"><label for="fmp_set_autostart">Autostart:&nbsp;</label></td>
            <td><input type="checkbox" name="fmp_set_autostart" id="fmp_set_autostart" value="1"<?php is_checked(1, $fmp_data['fmp_set_autostart']) ?> /></td>
        </tr>

        <tr>
            <td class="chatlist" align="right"><label for="fmp_set_loop">Loop:&nbsp;</label></td>
            <td><input type="checkbox" name="fmp_set_loop" id="fmp_set_loop" value="1"<?php is_checked(1, $fmp_data['fmp_set_loop']) ?> /></td>
        </tr>

        <tr>
            <td class="chatlist" align="right"><label for="fmp_set_downloadbutton"><?php echo $BL['be_hide_downloadbutton'] ?>:&nbsp;</label></td>
            <td><input type="checkbox" name="fmp_set_downloadbutton" id="fmp_set_downloadbutton" value="1"<?php is_checked(1, $fmp_data['fmp_set_downloadbutton']) ?> /></td>
        </tr>

        <tr>
            <td class="chatlist" align="right"><label for="fmp_set_preload">Preload:&nbsp;</label></td>
            <td><select name="fmp_set_preload" id="fmp_set_preload">
                <option value="auto"<?php is_selected('auto', $fmp_data['fmp_set_preload']) ?>><?php echo $BL['automatic']; ?></option>
                <option value="metadata"<?php is_selected('metadata', $fmp_data['fmp_set_preload']) ?>>Metadata</option>
                <option value="none"<?php is_selected('none', $fmp_data['fmp_set_preload']) ?>><?php echo $BL['be_off'] ?></option>
            </select></td>
        </tr>

        <tr>
            <td class="chatlist" align="right"><label for="fmp_set_skin"><?php echo $BL['be_skin'].' '.$BL['be_html5_media'] ?>:&nbsp;</label></td>
            <td><select name="fmp_set_skin_html5">
            <option value="default"<?php is_selected('', $fmp_data['fmp_set_skin_html5']) ?>><?php echo $BL['be_admin_tmpl_default'] ?></option>
<?php
            // skins for HTML5 Media Player
            $skins = returnFileListAsArray(PHPWCMS_TEMPLATE.'lib/video-js/skins', 'css');
            if(is_array($skins) && count($skins)):
                foreach($skins as $skin):
                    $skin = cut_ext($skin['filename']);
?>
            <option value="<?php
                echo html($skin)
            ?>"<?php is_selected($skin, $fmp_data['fmp_set_skin_html5']) ?>><?php
                echo html(ucwords(str_replace('_', ' ', $skin)))
            ?></option>
<?php
                endforeach;
            endif;
?>
            </select></td>
        </tr>

        <tr>
            <td class="chatlist" align="right"><label for="fmp_set_showcontrols">Controlbar:&nbsp;</label></td>
            <td><select name="fmp_set_showcontrols" id="fmp_set_showcontrols">

                <option value="bottom"<?php is_selected('bottom', $fmp_data['fmp_set_showcontrols']) ?>><?php echo $BL['be_admin_tmpl_default'] ?></option>
                <option value="none"<?php is_selected('none', $fmp_data['fmp_set_showcontrols']) ?>><?php echo $BL['be_admin_struct_hide1'] ?></option>
                <option value="over"<?php is_selected('over', $fmp_data['fmp_set_showcontrols']) ?>><?php echo $BL['over'] ?></option>

            </select>

            <input type="hidden" name="fmp_set_largecontrols" id="fmp_set_largecontrols" value="0" />
            <input type="hidden" name="fmp_set_showdigits" id="fmp_set_showdigits" value="0" />
            <input type="hidden" name="fmp_set_showeq" id="fmp_set_showeq" value="0" />
            <input type="hidden" name="fmp_set_showvolume" id="fmp_set_showvolume" value="0" />
            <input type="hidden" name="fmp_set_showdownload" id="fmp_set_showdownload" value="0" />

            </td>
        </tr>

        <tr>
            <td class="chatlist" align="right"><label for="fmp_set_volume"><?php echo $BL['be_player_volume'] ?>:&nbsp;</label></td>
            <td><select name="fmp_set_volume" id="fmp_set_volume">
<?php       for($x=0; $x<=100; $x+=5) : ?>
                <option value="<?php echo $x ?>"<?php is_selected($x, $fmp_data['fmp_set_volume']) ?>><?php echo ($x ? $x.' %' : $BL['be_player_volume_muted']) ?></option>
<?php       endfor; ?>
            </select>
            </td>
        </tr>

        <tr>
            <td class="chatlist" align="right"><label for="fmp_set_bgcolor"><?php echo $BL['be_background_color'].' '.$BL['be_flash_media'] ?> (HEX):&nbsp;</label></td>
            <td><input name="fmp_set_bgcolor" type="text" id="fmp_set_bgcolor" class="width75" value="<?php echo html($fmp_data['fmp_set_bgcolor']) ?>" size="40" maxlength="7" /></td>
        </tr>

        <tr>
            <td class="chatlist" align="right"><label for="fmp_set_color"><?php echo $BL['be_foreground_color'].' '.$BL['be_flash_media'] ?> (HEX):&nbsp;</label></td>
            <td><input name="fmp_set_color" type="text" id="fmp_set_color" class="width75" value="<?php echo html($fmp_data['fmp_set_color']) ?>" size="40" maxlength="7" /></td>
        </tr>

        <tr>
            <td class="chatlist" align="right"><label for="fmp_set_logo"><?php echo $BL['be_media_watermark'].' '.$BL['be_flash_media'].' ('.$BL['be_cnt_pages_cust'].')' ?>:&nbsp;</label></td>
            <td><input name="fmp_set_logo" type="text" id="fmp_set_logo" class="width200" value="<?php echo html($fmp_data['fmp_set_logo']) ?>" size="40" /></td>
        </tr>

    </table>

<script type="text/javascript">

    function setIdName(file_id, file_name, file_type) {
        if(!file_id) {
            file_id = 0;
        }
        if(!file_name) {
            file_name = '';
        }
        if(file_type == 6 || file_type == null) {
            $('#fmp_internal_id').val(file_id);
            $('#fmp_internal_name').val(file_name);
        } else if(file_type == 12) { // H.264
            $('#fmp_internal_id_h264').val(file_id);
            $('#fmp_internal_name_h264').val(file_name);
        } else if(file_type == 13) { // WebM
            $('#fmp_internal_id_webm').val(file_id);
            $('#fmp_internal_name_webm').val(file_name);
        } else if(file_type == 14) { // Ogg
            $('#fmp_internal_id_ogg').val(file_id);
            $('#fmp_internal_name_ogg').val(file_name);
        }
    }
    function setImgIdName(file_id, file_name) {
        if(!file_id) {
            file_id = 0;
        }
        if(!file_name) {
            file_name = '';
        }
        $('#fmp_img_id').val(file_id);
        $('#fmp_img_name').val(file_name);
    }
    function setPlayerSize(sval) {
        var indx = sval.selectedIndex;
        if(indx > 0) {
            var val = sval.options[indx].value.split('x');
            $('#fmp_width').val(parseInt(val[0], 10) || 426);
            $('#fmp_height').val(parseInt(val[1], 10) || 240);
        }
        sval.options[0].selected = true;
        sval.blur();
    }

    $(function () {
        let cid = $('#cid');

        if (cid.length) {
            let fmpMarker = $('#fmp_marker'),
                fmpMarkerLinks = $('#fmp_marker_links'),
                fmpId = cid.val(),
                eventLastTime = 0,
                eventDelay = 750,
                setMarkerLinks = function (str) {
                    if (str) {
                        let marker = $.trim(str).split('\n'),
                            markerLinks = [];
                        if (marker.length) {
                            for (let i = 0; i < marker.length; i++) {
                                let item = $.trim(marker[i]);
                                if (item) {
                                    item = item.split('|');
                                    if (item.length) {
                                        if (typeof item[0] !== 'undefined') {
                                            let timer = parseFloat($.trim(item[0]));
                                            if (timer) {
                                                let anchor = '#fmp' + fmpId + '-',
                                                    get = 'fmp=' + fmpId + '-',
                                                    markerNum = markerLinks.length + 1;
                                                    link = '<strong class="chatlist"><?php echo $BL['be_flashplayer_marker']; ?> ' + markerNum + ':</strong><br>';

                                                link += '<?php echo $BL['be_article_cnt_anchor']; ?> ';
                                                link += '<a href="#" onclick="copyToClipboard(\'' + anchor + timer + '\');return false;" title="<?php echo $BL['copy_to_clipboard']; ?>">' + anchor + timer + '</a>, ';
                                                link += '<a href="#" onclick="copyToClipboard(\'' + anchor + 'm' + markerNum + '\');return false;" title="<?php echo $BL['copy_to_clipboard']; ?>">' + anchor + 'm' + markerNum + '</a><br>';
                                                link += '<?php echo $BL['url_parameter']; ?> ';
                                                link += '<a href="#" onclick="copyToClipboard(\'' + get + timer + '\');return false;" title="<?php echo $BL['copy_to_clipboard']; ?>">' + get + timer + '</a>, ';
                                                link += '<a href="#" onclick="copyToClipboard(\'' + get + 'm' + markerNum + '\');return false;" title="<?php echo $BL['copy_to_clipboard']; ?>">' + get + 'm' + markerNum + '</a>';

                                                markerLinks.push(link);
                                            }
                                        }
                                    }
                                }
                            }
                        }
                        fmpMarkerLinks.html(markerLinks.join('<br>'));
                        fmpMarkerLinks.show();
                    } else {
                        fmpMarkerLinks.html('');
                        fmpMarkerLinks.hide();
                    }
                };

            fmpMarker.on('change keyup', function (event) {
                if (event.type === 'keyup') {
                    let dateObject = new Date();
                    if ((dateObject.getTime() - eventLastTime) > eventDelay) {
                        eventLastTime = dateObject.getTime();
                        setMarkerLinks($(this).val());
                    }
                } else {
                    setMarkerLinks($(this).val());
                }
            });

            setMarkerLinks(fmpMarker.val());
        }
    });

 </script>

    </td>
</tr>
