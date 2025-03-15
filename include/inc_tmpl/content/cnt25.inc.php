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

// HTML5 and Flash Media Player

if(!$content["id"]) {
    include CMSGO_ROOT.'/include/inc_lib/content/cnt25.takeval.inc.php';
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
<div class="form-group align-items-center form-row">
    <label for="template" class="col-sm-2 col-form-label text-right"><?php echo $BL['be_admin_struct_template']; ?></label>
    <div class="col-sm-4">
        <select name="fmp_template" id="fmp_template" class="custom-select form-control form-control-sm">
<?php

    echo '<option value="">'.$BL['be_admin_tmpl_default'].'</option>'.LF;

    // templates for Flash Media Player
    $tmpllist = get_tmpl_files(CMSGO_TEMPLATE.'inc_cntpart/flashplayer');
    if(is_array($tmpllist) && count($tmpllist)) {
        foreach($tmpllist as $val) {
            $selected_val = (isset($fmp_data['fmp_template']) && $val == $fmp_data['fmp_template']) ? ' selected="selected"' : '';
            $val = html($val);
            echo '<option value="' . $val . '"' . $selected_val . '>' . $val . '</option>';
        }
    }

?>
            </select>
    </div>
    <div class="col-sm-3">
        <select class="custom-select form-control form-control-sm" name="fmp_width_height" id="fmp_width_height" onchange="setPlayerSize();">
            <option value=""><?php echo $BL['be_flashplayer_selectsize'] ?></option>
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

            </select>
    </div>
    <div class="col-auto">
        <input name="fmp_width" type="text" class="form-control form-control-sm" id="fmp_width" size="4" maxlength="4" value="<?php echo $fmp_data['fmp_width']; ?>" />
    </div>
    <div class="col-auto">x</div>
    <div class="col-auto">
        <input name="fmp_height" type="text" class="form-control form-control-sm" id="fmp_height" size="4" maxlength="4" value="<?php echo $fmp_data['fmp_height']; ?>" />
    </div>
</div>

<hr>

<div class="form-group align-items-center form-row">
    <label class="col-sm-2 col-form-label text-right">
        <?php echo $BL['be_html5_media'] ?>
    </label>
    <div class="col-sm-9">
        <?php echo $BL['be_media_format'] ?>
        <?php echo $BL['be_html5_h264'] ?> <span class="text-muted">&#8212; <i>mp4, m4v, mov, m4p, m4a, mp3, aac, mpeg</i></span>
    </div>
</div>
<div class="form-group align-items-center form-row">
    <div class="col-sm-1 offset-sm-2">
        <div class="form-check form-check-inline">
            <input name="fmp_int_ext_h264" id="fmp_int_ext0_h264" type="radio" value="0" <?php is_checked(0, $fmp_data['fmp_int_ext_h264']); ?> class="form-check-input" />
            <label for="fmp_int_ext0_h264" class="form-check-label"><?php echo $BL['be_cnt_internal'] ?></label>
        </div>
    </div>
    <div class="col-sm-6">
        <input name="fmp_internal_id_h264" type="hidden" id="fmp_internal_id_h264" value="<?php echo $fmp_data['fmp_internal_id_h264'] ?>" />
        <input name="fmp_internal_name_h264" type="text" id="fmp_internal_name_h264" class="form-control form-control-sm greyed modalButton" value="<?php echo html($fmp_data['fmp_internal_name_h264']) ?>" size="40" onfocus="this.blur()" data-toggle="modal" data-target="#browserModal" data-src="filebrowser.php?opt=12" />
    </div>
    <div class="auto">
        <button type="button" class="btn btn-sm btn-blue modalButton" title="<?php echo $BL['be_cnt_openmediabrowser'] ?>" data-toggle="modal" data-target="#browserModal" data-src="filebrowser.php?opt=12">
            <i class="fa fa-fw fa-folder-open"></i>
        </button>
        <button type="button" class="btn btn-sm btn-danger" title="<?php echo $BL['be_cnt_delmedia'] ?>" onclick="getObjectById('fmp_internal_name_h264').value='';getObjectById('fmp_internal_id_h264').value='0';this.blur();return false;">
            <i class="far fa-fw fa-trash-alt"></i>
        </button>
    </div>
</div>
<div class="form-group align-items-center form-row">
    <div class="col-sm-1 offset-sm-2">
        <div class="form-check form-check-inline">
            <input name="fmp_int_ext_h264" id="fmp_int_ext1_h264" type="radio" value="1" <?php is_checked(1, $fmp_data['fmp_int_ext_h264']); ?> class="form-check-input" />
            <label for="fmp_int_ext1_h264" class="form-check-label"><?php echo $BL['be_cnt_external'] ?></label>
        </div>
    </div>
    <div class="col-sm-6">
        <input name="fmp_external_file_h264" type="text" id="fmp_external_file_h264" class="form-control form-control-sm" value="<?php echo html($fmp_data['fmp_external_file_h264']) ?>" size="40" />
    </div>
</div>

<!-- HTML5 Media WebM -->
<div class="form-group align-items-center form-row">
    <div class="col-sm-9 offset-sm-2">
        <?php echo $BL['be_media_format'] ?>
        <?php echo $BL['be_html5_webm'] ?> <span class="text-muted">&#8212; <i>webm</i></span>
    </div>
</div>
<div class="form-group align-items-center form-row">
    <div class="col-sm-1 offset-sm-2">
        <div class="form-check form-check-inline">
            <input name="fmp_int_ext_webm" id="fmp_int_ext0_webm" type="radio" value="0" <?php is_checked(0, $fmp_data['fmp_int_ext_webm']); ?> class="form-check-input" />
            <label for="fmp_int_ext0_webm" class="form-check-label"><?php echo $BL['be_cnt_internal'] ?></label>
        </div>
    </div>
    <div class="col-sm-6">
        <input name="fmp_internal_id_webm" type="hidden" id="fmp_internal_id_webm" value="<?php echo $fmp_data['fmp_internal_id_webm'] ?>" />
        <input name="fmp_internal_name_webm" type="text" id="fmp_internal_name_webm" class="form-control form-control-sm greyed modalButton" value="<?php echo html($fmp_data['fmp_internal_name_webm']) ?>" size="40" onfocus="this.blur()" data-toggle="modal" data-target="#browserModal" data-src="filebrowser.php?opt=13" data-title="<?php echo $BL['be_cnt_openmediabrowser'] ?>" />
    </div>
    <div class="auto">
        <button type="button" class="btn btn-sm btn-blue modalButton" title="<?php echo $BL['be_cnt_openmediabrowser'] ?>" data-toggle="modal" data-target="#browserModal" data-src="filebrowser.php?opt=13">
            <i class="fa fa-fw fa-folder-open"></i>
        </button>
        <button type="button" class="btn btn-sm btn-danger" title="<?php echo $BL['be_cnt_delmedia'] ?>" onclick="getObjectById('fmp_internal_name_webm').value='';getObjectById('fmp_internal_id_webm').value='0';this.blur();return false;">
            <i class="far fa-fw fa-trash-alt"></i>
        </button>
    </div>
</div>
<div class="form-group align-items-center form-row">
    <div class="col-sm-1 offset-sm-2">
        <div class="form-check form-check-inline">
            <input name="fmp_int_ext_webm" id="fmp_int_ext1_webm" type="radio" value="1" <?php is_checked(1, $fmp_data['fmp_int_ext_webm']); ?> class="form-check-input" />
            <label for="fmp_int_ext1_webm" class="form-check-label"><?php echo $BL['be_cnt_external'] ?></label>
        </div>
    </div>
    <div class="col-sm-6">
        <input name="fmp_external_file_webm" type="text" id="fmp_external_file_webm" class="form-control form-control-sm" value="<?php echo html($fmp_data['fmp_external_file_webm']) ?>" size="40" />
    </div>
</div>

<!-- HTML5 Media Ogg -->
<div class="form-group align-items-center form-row">
    <div class="col-sm-9 offset-sm-2">
        <?php echo $BL['be_media_format'] ?>
        <?php echo $BL['be_html5_ogg'] ?> <span class="text-muted">&#8212; <i>.ogg, .ogv, .oga, .ogx</i></span>
    </div>
</div>
<div class="form-group align-items-center form-row">
    <div class="col-sm-1 offset-sm-2">
        <div class="form-check form-check-inline">
            <input name="fmp_int_ext_ogg" id="fmp_int_ext0_ogg" type="radio" value="0" <?php is_checked(0, $fmp_data['fmp_int_ext_ogg']); ?> class="form-check-input" />
            <label for="fmp_int_ext0_ogg" class="form-check-label"><?php echo $BL['be_cnt_internal'] ?></label>
        </div>
    </div>
    <div class="col-sm-6">
        <input name="fmp_internal_id_ogg" type="hidden" id="fmp_internal_id_ogg" value="<?php echo $fmp_data['fmp_internal_id_ogg'] ?>" />
        <input name="fmp_internal_name_ogg" type="text" id="fmp_internal_name_ogg" class="form-control form-control-sm greyed modalButton" value="<?php echo html($fmp_data['fmp_internal_name_ogg']) ?>" size="40" onfocus="this.blur()" data-toggle="modal" data-target="#browserModal" data-src="filebrowser.php?opt=14" data-title="<?php echo $BL['be_cnt_openmediabrowser'] ?>" />
    </div>
    <div class="auto">
        <button type="button" class="btn btn-sm btn-blue modalButton" title="<?php echo $BL['be_cnt_openmediabrowser'] ?>" data-toggle="modal" data-target="#browserModal" data-src="filebrowser.php?opt=14">
            <i class="fa fa-fw fa-folder-open"></i>
        </button>
        <button type="button" class="btn btn-sm btn-danger" title="<?php echo $BL['be_cnt_delmedia'] ?>" onclick="getObjectById('fmp_internal_name_ogg').value='';getObjectById('fmp_internal_id_ogg').value='0';this.blur();return false;">
            <i class="far fa-fw fa-trash-alt"></i>
        </button>
    </div>
</div>
<div class="form-group align-items-center form-row">
    <div class="col-sm-1 offset-sm-2">
        <div class="form-check form-check-inline">
            <input name="fmp_int_ext_ogg" id="fmp_int_ext1_ogg" type="radio" value="1" <?php is_checked(1, $fmp_data['fmp_int_ext_ogg']); ?> class="form-check-input" />
            <label for="fmp_int_ext1_webm" class="form-check-label"><?php echo $BL['be_cnt_external'] ?></label>
        </div>
    </div>
    <div class="col-sm-6">
        <input name="fmp_external_file_ogg" type="text" id="fmp_external_file_ogg" class="form-control form-control-sm" value="<?php echo html($fmp_data['fmp_external_file_ogg']) ?>" size="40" />
    </div>
</div>

<hr>

<!-- Flash Media Fallback -->
<div class="form-group align-items-center form-row">
    <label class="col-sm-2 col-form-label text-right">
        <?php echo $BL['be_flash_media'] ?>
    </label>
    <div class="col-sm-9">
        <?php echo $BL['be_media_format'] ?>
        Flash <span class="text-muted">&#8212; <i>mp4, mp3, flv, mov, swf, f4v, m4v, jpg, png</i></span>
    </div>
</div>
<div class="form-group align-items-center form-row">
    <div class="col-sm-1 offset-sm-2">
        <div class="form-check form-check-inline">
            <input name="fmp_int_ext" id="fmp_int_ext0" type="radio" value="0" <?php is_checked(0, $fmp_data['fmp_int_ext']); ?> class="form-check-input" />
            <label for="fmp_int_ext0" class="form-check-label"><?php echo $BL['be_cnt_internal'] ?></label>
        </div>
    </div>
    <div class="col-sm-6">
        <input name="fmp_internal_id" type="hidden" id="fmp_internal_id" value="<?php echo $fmp_data['fmp_internal_id'] ?>" />
        <input name="fmp_internal_name" type="text" id="fmp_internal_name" class="form-control form-control-sm greyed modalButton" value="<?php echo html($fmp_data['fmp_internal_name']) ?>" size="40" onfocus="this.blur()" data-toggle="modal" data-target="#browserModal" data-src="filebrowser.php?opt=6" class="modalButton" data-title="<?php echo $BL['be_cnt_openmediabrowser'] ?>" />
    </div>
    <div class="auto">
        <button type="button" class="btn btn-sm btn-blue modalButton" title="<?php echo $BL['be_cnt_openmediabrowser'] ?>" data-toggle="modal" data-target="#browserModal" data-src="filebrowser.php?opt=6">
            <i class="fa fa-fw fa-folder-open"></i>
        </button>
        <button type="button" class="btn btn-sm btn-danger" title="<?php echo $BL['be_cnt_delmedia'] ?>" onclick="getObjectById('fmp_internal_name').value='';getObjectById('fmp_internal_id').value='0';this.blur();return false;">
            <i class="far fa-fw fa-trash-alt"></i>
        </button>
    </div>
</div>
<div class="form-group align-items-center form-row">
    <div class="col-sm-1 offset-sm-2">
        <div class="form-check form-check-inline">
            <input name="fmp_int_ext" id="fmp_int_ext1" type="radio" value="1" <?php is_checked(1, $fmp_data['fmp_int_ext']); ?> class="form-check-input" />
            <label for="fmp_int_ext1" class="form-check-label"><?php echo $BL['be_cnt_external'] ?></label>
        </div>
    </div>
    <div class="col-sm-6">
        <input name="fmp_external_file" type="text" id="fmp_external_file" class="form-control form-control-sm" value="<?php echo html($fmp_data['fmp_external_file']) ?>" size="40" />
    </div>
</div>

<div class="form-group form-row">
    <label class="col-sm-2 col-form-label text-right">
        <?php echo $BL['be_flashplayer_caption'] ?>
    </label>
    <div class="col-sm-6">
        <textarea name="fmp_caption" cols="40" rows="3" class="form-control form-control-sm autosize" id="fmp_caption"><?php echo html($fmp_data['fmp_caption']) ?></textarea>
    </div>
</div>

<div class="form-group align-items-center form-row">
    <label class="col-sm-2 col-form-label text-right">
        <?php echo $BL['be_admin_page_link'] ?>
    </label>
    <div class="col-sm-6">
        <input name="fmp_link" type="text" id="fmp_link" class="form-control form-control-sm" value="<?php echo html($fmp_data['fmp_link']) ?>" size="40" />
    </div>
</div>

<div class="form-group align-items-center form-row">
    <label class="col-sm-2 col-form-label text-right">
        <?php echo $BL['be_flashplayer_thumbnail'] ?>
    </label>
    <div class="col-sm-6">
        <input name="fmp_img_id" type="hidden" id="fmp_img_id" value="<?php echo $fmp_data['fmp_img_id'] ?>" />
        <input name="fmp_img_name" type="text" id="fmp_img_name" class="form-control form-control-sm greyed modalButton" value="<?php echo html($fmp_data['fmp_img_name']) ?>" size="40" onfocus="this.blur()" data-toggle="modal" data-target="#browserModal" data-src="filebrowser.php?opt=7" class="modalButton" data-title="<?php echo $BL['be_cnt_openmediabrowser'] ?>" />
    </div>
    <div class="auto">
        <button type="button" class="btn btn-sm btn-blue modalButton" title="<?php echo $BL['be_cnt_openmediabrowser'] ?>" data-toggle="modal" data-target="#browserModal" data-src="filebrowser.php?opt=7">
            <i class="fa fa-fw fa-folder-open"></i>
        </button>
        <button type="button" class="btn btn-sm btn-danger" title="<?php echo $BL['be_cnt_delmedia'] ?>" onclick="getObjectById('fmp_img_name').value='';getObjectById('fmp_img_id').value='0';this.blur();return false;">
            <i class="far fa-fw fa-trash-alt"></i>
        </button>
    </div>
</div>

<div class="form-group form-row">
    <label class="col-sm-2 col-form-label text-right">
        <?php echo $BL['be_flashplayer_marker'] ?>
    </label>
    <div class="col-sm-6">
        <textarea name="fmp_marker" cols="40" rows="2" class="form-control form-control-sm autosize" id="fmp_marker"><?php echo html($fmp_data['fmp_marker']) ?></textarea>
        <label class="col-sm-12 col-form-label pl-0">
            <?php echo $BL['be_marker_time']; ?>
            |
            <?php echo $BL['be_marker_text']; ?>
            |
            <?php echo $BL['be_marker_overlaytext']; ?>
            |
            <?php echo $BL['be_cnt_css_class']; ?>&nbsp;&crarr;&nbsp;&hellip;
        </label>
        <div id="fmp_marker_links" style="display:none;" class="tdtop5"></div>
    </div>
</div>

<hr>

<div class="form-group align-items-center form-row">
    <label class="col-sm-2 col-form-label text-info">
        <?php echo $BL['be_settings'] ?>
    </label>
</div>

<div class="form-group align-items-center form-row">
    <label class="col-sm-2 col-form-label text-right" for="fmp_set_html5only">
        <?php echo $BL['be_display_html5_only'] ?>
    </label>
    <div class="col-sm-8">
        <div class="form-check form-check-inline">
            <input type="checkbox" name="fmp_set_html5only" id="fmp_set_html5only" class="form-check-input" value="1"<?php is_checked(1, $fmp_data['fmp_set_html5only']) ?> />
        </div>
    </div>
</div>

<div class="form-group align-items-center form-row">
    <label class="col-sm-2 col-form-label text-right" for="fmp_set_audio">
        <?php echo $BL['be_audio_only'] ?>
    </label>
    <div class="col-sm-8">
        <div class="form-check form-check-inline">
            <input type="checkbox" name="fmp_set_audio" id="fmp_set_audio"class="form-check-input" value="1"<?php is_checked(1, $fmp_data['fmp_set_audio']) ?> />
        </div>
    </div>
</div>

<div class="form-group align-items-center form-row">
    <label class="col-sm-2 col-form-label text-right" for="fmp_set_autostart">
        Autostart
    </label>
    <div class="col-sm-8">
        <div class="form-check form-check-inline">
            <input type="checkbox" name="fmp_set_autostart" id="fmp_set_autostart" class="form-check-input" value="1"<?php is_checked(1, $fmp_data['fmp_set_autostart']) ?> />
        </div>
    </div>
</div>

<div class="form-group align-items-center form-row">
    <label class="col-sm-2 col-form-label text-right" for="fmp_set_loop">
        Loop
    </label>
    <div class="col-sm-8">
        <div class="form-check form-check-inline">
            <input type="checkbox" name="fmp_set_loop" id="fmp_set_loop" class="form-check-input" value="1"<?php is_checked(1, $fmp_data['fmp_set_loop']) ?> />
        </div>
    </div>
</div>

<div class="form-group align-items-center form-row">
    <label class="col-sm-2 col-form-label text-right" for="fmp_set_downloadbutton">
        <?php echo $BL['be_hide_downloadbutton'] ?>
    </label>
    <div class="col-sm-8">
        <div class="form-check form-check-inline">
            <input type="checkbox" name="fmp_set_downloadbutton" id="fmp_set_downloadbutton" class="form-check-input" value="1"<?php is_checked(1, $fmp_data['fmp_set_downloadbutton']) ?> />
        </div>
    </div>
</div>

<div class="form-group align-items-center form-row">
    <label for="fmp_set_preload" class="col-sm-2 col-form-label text-right">
        Preload
    </label>
    <div class="col-auto">
        <select class="custom-select form-control form-control-sm" name="fmp_set_preload" id="fmp_set_preload">
            <option value="auto"<?php is_selected('auto', $fmp_data['fmp_set_preload']) ?>><?php echo $BL['automatic']; ?></option>
            <option value="metadata"<?php is_selected('metadata', $fmp_data['fmp_set_preload']) ?>>Metadata</option>
            <option value="none"<?php is_selected('none', $fmp_data['fmp_set_preload']) ?>><?php echo $BL['be_off'] ?></option>
        </select>
    </div>
</div>

<div class="form-group align-items-center form-row">
    <label for="fmp_set_skin_html5" class="col-sm-2 col-form-label text-right">
        <?php echo $BL['be_skin'].' '.$BL['be_html5_media'] ?>
    </label>
    <div class="col-auto">
        <select class="custom-select form-control form-control-sm" name="fmp_set_skin_html5" id="fmp_set_skin_html5">
            <option value="default"<?php is_selected('', $fmp_data['fmp_set_skin_html5']) ?>><?php echo $BL['be_admin_tmpl_default'] ?></option>
            <?php
                // skins for HTML5 Media Player
                $skins = returnFileListAsArray(CMSGO_TEMPLATE.'lib/video-js/skins', 'css');
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
        </select>
    </div>
</div>

<div class="form-group align-items-center form-row">
    <label for="fmp_set_showcontrols" class="col-sm-2 col-form-label text-right">
        Controlbar
    </label>
    <div class="col-auto">
        <select class="custom-select form-control form-control-sm" name="fmp_set_showcontrols" id="fmp_set_showcontrols">
            <option value="bottom"<?php is_selected('bottom', $fmp_data['fmp_set_showcontrols']) ?>><?php echo $BL['be_admin_tmpl_default'] ?></option>
            <option value="none"<?php is_selected('none', $fmp_data['fmp_set_showcontrols']) ?>><?php echo $BL['be_admin_struct_hide1'] ?></option>
            <option value="over"<?php is_selected('over', $fmp_data['fmp_set_showcontrols']) ?>><?php echo $BL['over'] ?></option>
        </select>
        <input type="hidden" name="fmp_set_largecontrols" id="fmp_set_largecontrols" value="0" />
        <input type="hidden" name="fmp_set_showdigits" id="fmp_set_showdigits" value="0" />
        <input type="hidden" name="fmp_set_showeq" id="fmp_set_showeq" value="0" />
        <input type="hidden" name="fmp_set_showvolume" id="fmp_set_showvolume" value="0" />
        <input type="hidden" name="fmp_set_showdownload" id="fmp_set_showdownload" value="0" />
    </div>
</div>

<div class="form-group align-items-center form-row">
    <label for="fmp_set_volume" class="col-sm-2 col-form-label text-right">
        <?php echo $BL['be_player_volume'] ?>
    </label>
    <div class="col-auto">
        <select class="custom-select form-control form-control-sm" name="fmp_set_volume" id="fmp_set_volume">
            <?php for($x=0; $x<=100; $x+=5): ?>
                <option value="<?php echo $x ?>"<?php is_selected($x, $fmp_data['fmp_set_volume']) ?>>
                    <?php echo ($x ? $x.' %' : $BL['be_player_volume_muted']) ?>
                </option>
            <?php endfor; ?>
        </select>
    </div>
</div>

<div class="form-group align-items-center form-row">
    <label class="col-sm-2 col-form-label text-right" for="fmp_set_bgcolor">
        <?php echo $BL['be_background_color'].' '.$BL['be_flash_media'] ?> (HEX)
    </label>
    <div class="col-sm-1">
        <input name="fmp_set_bgcolor" type="text" id="fmp_set_bgcolor" class="form-control form-control-sm" value="<?php echo html($fmp_data['fmp_set_bgcolor']) ?>" size="40" maxlength="7" />
    </div>
</div>

<div class="form-group align-items-center form-row">
    <label class="col-sm-2 col-form-label text-right" for="fmp_set_color">
        <?php echo $BL['be_foreground_color'].' '.$BL['be_flash_media'] ?> (HEX)
    </label>
    <div class="col-sm-1">
        <input name="fmp_set_color" type="text" id="fmp_set_color" class="form-control form-control-sm" value="<?php echo html($fmp_data['fmp_set_color']) ?>" size="40" maxlength="7" />
    </div>
</div>

<div class="form-group align-items-center form-row">
    <label class="col-sm-2 col-form-label text-right" for="fmp_set_logo">
        <?php echo $BL['be_media_watermark'].' '.$BL['be_flash_media'].' ('.$BL['be_cnt_pages_cust'].')' ?>
    </label>
    <div class="col-sm-6">
        <input name="fmp_set_logo" type="text" id="fmp_set_logo" class="form-control form-control-sm" value="<?php echo html($fmp_data['fmp_set_logo']) ?>" size="40" />
    </div>
</div>

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
        $('#browserModal').modal('hide');
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
        $('#browserModal').modal('hide');
    }
    function setPlayerSize() {
        var sval = $('#fmp_width_height');
        var val = sval.val().split('x');
        sval.val('');
        sval.blur();
        $('#fmp_width').val(parseInt(val[0], 10));
        $('#fmp_height').val(parseInt(val[1], 10));
        $('#browserModal').modal('hide');
    }


    $(function () {
        let cid = $('#cid');

        if (cid.length) {
            let fmpMarker = $('#fmp_marker'),
                fmpMarkerLinks = $('#fmp_marker_links'),
                fmpId = parseInt(cid.val(), 10),
                eventLastTime = 0,
                eventDelay = 250,
                setMarkerLinks = function (str) {
                    if (!fmpId) {
                        return;
                    }
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
                                                link += '<a href="#" onclick="copyToClipboard(\'' + anchor + 'm' + markerNum + '\');return false;" title="<?php echo $BL['copy_to_clipboard']; ?>">' + anchor + 'm' + markerNum + '</a>';
                                                link += ' / <?php echo $BL['url_parameter']; ?> ';
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
