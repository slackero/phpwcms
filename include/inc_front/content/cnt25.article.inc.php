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

if(!function_exists('get_mediaplayer_stream')) {

    function get_mediaplayer_stream($fileid=0, $flash=false) {

        $fileid = intval($fileid);

        if(!$fileid) {
            return '';
        }

        // internal
        $sql = 'SELECT * FROM '.DB_PREPEND.'phpwcms_file WHERE f_aktiv=1 AND f_public=1 AND f_id='.$fileid;
        if( !FEUSER_LOGIN_STATUS ) {
            $sql .= ' AND f_granted=0';
        }
        $file = _dbQuery($sql);

        if(isset($file[0])) {

            global $fmp_data;

            $file = $file[0];
            $file['fmp_file'] = PHPWCMS_URL. 'download.php?file='.$file['f_hash'];

            if($file['f_ext']) {

                $file['fmp_file'] .= '.'.$file['f_ext'];
                $file['f_type'] = get_mimetype_by_extension($file['f_ext']);
                $fmp_data['fmp_file_ext'] = $file['f_ext'];

                if($flash) {
                    $fmp_data['flashvars_type'] = $file['f_ext'];

                    if(in_array($file['f_ext'], array('jpeg', 'jpg', 'png', 'gif', 'swf'))) {
                        $fmp_data['fmp_img_id'] = 0;
                    }
                } else {

                    $fmp_data['video_type'] = $file['f_type'];

                }

            }

            $file['fmp_file'] .= '&type='.urlencode($file['f_type']);

            if(BROWSER_OS == 'iOS') {
                $file['fmp_file'] .= '&ios=/'.$file['f_name'];
            }

            return $file['fmp_file'];

        }

        return '';

    }

}

$fmp_data = @unserialize($crow["acontent_form"]);

if(isset($fmp_data['fmp_template'])) {

    // read template
    if(empty($crow["acontent_template"]) && is_file(PHPWCMS_TEMPLATE.'inc_default/flashplayer.tmpl')) {

        $fmp_data['fmp_template']   = render_device( @file_get_contents(PHPWCMS_TEMPLATE.'inc_default/flashplayer.tmpl') );

    } elseif(is_file(PHPWCMS_TEMPLATE.'inc_cntpart/flashplayer/'.$crow["acontent_template"])) {

        $fmp_data['fmp_template']   = render_device( @file_get_contents(PHPWCMS_TEMPLATE.'inc_cntpart/flashplayer/'.$crow["acontent_template"]) );

    } else {

        $fmp_data['fmp_template']   = '[TITLE]<h3>{TITLE}</h3>[/TITLE][SUBTITLE]<h4>{SUBTITLE}</h4>[/SUBTITLE]{PLAYER}';

    }

    $fmp_data['fmp_set_html5only']  = empty($fmp_data['fmp_set_html5only']) ? false : true;
    $fmp_data['fmp_set_audio']      = empty($fmp_data['fmp_set_audio']) ? 'video' : 'audio';

    // Set some defaults used to build SwfObject Call
    $fmp_data['flashvars']          = array();
    $fmp_data['attributes']         = array();
    $fmp_data['params']             = array();
    $fmp_data['flashvars_type']     = '';

    // set player dimensions first
    if(empty($fmp_data['fmp_width'])) {
        $fmp_data['fmp_width']      = 320;
    }
    // check if controls should be shown and add controls' height to player height
    $fmp_data['fmp_displayheight']  = $fmp_data['fmp_height'];

    if(empty($fmp_data['fmp_set_flashversion'])) {
        $fmp_data['fmp_set_flashversion'] = 11;
    }

    $fmp_data['fmp_set_bgcolor']    = empty($fmp_data['fmp_set_bgcolor']) ? '000000' : trim($fmp_data['fmp_set_bgcolor'], '#');
    $fmp_data['fmp_set_color']      = empty($fmp_data['fmp_set_color']) ? 'FFFFFF' : trim($fmp_data['fmp_set_color'], '#');

    // NonverBlaster:hover
    $fmp_data['fmp_player_dir'] = 'nonverblaster';

    if(empty($fmp_data['fmp_height'])) {
        $fmp_data['fmp_height'] = 17;
    }

    $fmp_data['fmp_set_showcontrols'] = $fmp_data['fmp_set_showcontrols'] == 'none' ? 'false' : 'true';

    // file
    if($fmp_data['fmp_int_ext']) {

        // external
        $fmp_data['file'] = $fmp_data['fmp_external_file'];

    } elseif($fmp_data['fmp_internal_id']) {

        // internal
        $sql = 'SELECT * FROM '.DB_PREPEND.'phpwcms_file WHERE f_aktiv=1 AND f_public=1 AND f_id='.$fmp_data['fmp_internal_id'];
        if(!FEUSER_LOGIN_STATUS) {
            $sql .= ' AND f_granted=0';
        }
        $fmp_data['file'] = _dbQuery($sql);

        if(isset($fmp_data['file'][0])) {

            $fmp_data['file']           = $fmp_data['file'][0];
            $fmp_data['fmp_file']       = PHPWCMS_URL . 'download.php?file='.$fmp_data['file']['f_hash'];

            if($fmp_data['file']['f_ext']) {

                $fmp_data['flashvars_type']  = $fmp_data['file']['f_ext'];
                $fmp_data['fmp_file']       .= '.'.$fmp_data['file']['f_ext'];
                $fmp_data['file']['f_type']  = get_mimetype_by_extension($fmp_data['file']['f_ext']);

                if(in_array($fmp_data['file']['f_ext'], array('jpeg', 'jpg', 'png', 'gif', 'swf'))) {
                    $fmp_data['fmp_img_id']  = 0;
                }
            }

            $fmp_data['fmp_file'] .= '&type='.urlencode($fmp_data['file']['f_type']);
            $fmp_data['file'] = $fmp_data['fmp_file'];

        } else {
            $fmp_data['file'] = '';
        }
    } else {

        $fmp_data['file'] = '';

    }

    // retrieve preview image
    if($fmp_data['fmp_img_id']) {

        $sql = 'SELECT * FROM '.DB_PREPEND.'phpwcms_file WHERE f_aktiv=1 AND f_public=1 AND f_id='.$fmp_data['fmp_img_id'];
        if( !FEUSER_LOGIN_STATUS ) {
            $sql .= ' AND f_granted=0';
        }
        $fmp_data['preview'] = _dbQuery($sql);

        if(isset($fmp_data['preview'][0])) {

            $fmp_data['preview']     = $fmp_data['preview'][0];
            $fmp_data['fmp_preview'] = $fmp_data['preview']['f_hash'];

            if($fmp_data['preview']['f_ext']) {

                $fmp_data['fmp_preview'] .= '.' . $fmp_data['preview']['f_ext'];
                $fmp_data['preview']['f_type'] = get_mimetype_by_extension($fmp_data['preview']['f_ext']);

            }

            $fmp_data['preview'] = PHPWCMS_URL . PHPWCMS_RESIZE_IMAGE . '/' . $fmp_data['fmp_width'].'x'.$fmp_data['fmp_height'].'x1x85/'.$fmp_data['fmp_preview'].'/'.rawurlencode($fmp_data['preview']['f_name']);

        } else {

            $fmp_data['fmp_img_id'] = 0;

        }

    }

    // set ID
    $fmp_data['id'] = 'fmp'.$crow["acontent_id"];

    if(empty($fmp_data['fmp_link'])) {
        $fmp_data['fmp_link'] = '';
    } else {
        $fmp_data['fmp_link'] = explode(' ', trim($fmp_data['fmp_link']));
        $fmp_data['flashvars']['onClick'] = rawurlencode(trim($fmp_data['fmp_link'][0]));
    }

    if($fmp_data['file']) {
        // Define Flash Vars

        // NonverBlaster:hover
        $fmp_data['player_swf'] = PHPWCMS_URL.TEMPLATE_PATH.'lib/nonverblaster/NonverBlaster.swf';

        $fmp_data['flashvars']['mediaURL']          = rawurlencode($fmp_data['file']);
        $fmp_data['flashvars']['loop']              = 'false';
        $fmp_data['flashvars']['showScalingButton'] = 'true';
        $fmp_data['flashvars']['scaleIfFullScreen'] = 'true';
        $fmp_data['flashvars']['crop']              = 'false';
        $fmp_data['flashvars']['defaultVolume']     = isset($fmp_data['fmp_set_volume']) ? $fmp_data['fmp_set_volume'] : '100';
        $fmp_data['flashvars']['buffer']            = '6';
        $fmp_data['flashvars']['allowSmoothing']    = 'true';
        $fmp_data['flashvars']['controlsEnabled']   = $fmp_data['fmp_set_showcontrols'];
        $fmp_data['flashvars']['autoPlay']          = $fmp_data['fmp_set_autostart'] ? 'true' : 'false';
        $fmp_data['flashvars']['controlBackColor']  = '0x' . $fmp_data['fmp_set_bgcolor'];
        $fmp_data['flashvars']['controlColor']      = '0x' . $fmp_data['fmp_set_color'];
        $fmp_data['flashvars']['loop']              = empty($fmp_data['fmp_set_loop']) ? 'false' : 'true';

        if($fmp_data['fmp_img_id'] && isset($fmp_data['preview'])) {
            $fmp_data['flashvars']['teaserURL']     = rawurlencode($fmp_data['preview']);
        }

        if($fmp_data['fmp_set_logo']) {
            $fmp_data['flashvars']['indentImageURL'] = rawurlencode($fmp_data['fmp_set_logo']);
        }

        $fmp_data['params']['allowfullscreen']      = 'true';
        $fmp_data['params']['menu']                 = 'false';
        $fmp_data['params']['wmode']                = 'opaque';
        $fmp_data['params']['allowScriptAccess']    = 'always';

        $fmp_data['attributes'][] = 'id: "'.$fmp_data['id'].'"';
        $fmp_data['attributes'][] = 'name: "'.$fmp_data['id'].'"';
        $fmp_data['attributes'][] = 'bgcolor: "#'.$fmp_data['fmp_set_bgcolor'].'"';

        // Prepare Fallback Flash Object
        $fmp_data['fallback'] = array();

        $fmp_data['fallback']['object_header']  = ' <object class="vjs-flash-fallback" type="application/x-shockwave-flash"';
        $fmp_data['fallback']['object_header'] .= ' width="'.$fmp_data['fmp_width'].'" height="'.$fmp_data['fmp_height'].'" data="'.$fmp_data['player_swf'].'">';

        $fmp_data['fallback']['param_movie']     = '        <param name="movie" value="'.$fmp_data['player_swf'].'" />';

        foreach($fmp_data['params'] as $param_name => $param_value) {
            $fmp_data['fallback'][] = '     <param name="'.$param_name.'" value="'.$param_value.'" />';
            $fmp_data['params'][$param_name] = $param_name.': "'.$param_value.'"';
        }

        $fmp_data['fallback']['flashvars'] = array();

        foreach($fmp_data['flashvars'] as $param_name => $param_value) {
            $fmp_data['fallback']['flashvars'][] = $param_name.'='.$param_value;
            $fmp_data['flashvars'][$param_name] = $param_name.': "'.$param_value.'"';
        }

        $fmp_data['fallback']['flashvars'] = '      <param name="flashvars" value="'.implode('&amp;', $fmp_data['fallback']['flashvars']).'" />';

        if(!empty($fmp_data['fmp_preview'])) {
            $fmp_data['fallback']['poster']  = '        <img alt="Poster Image" title="@@No video playback capabilities.@@" src="'.$fmp_data['preview'].'" ';
            $fmp_data['fallback']['poster'] .= 'width="'.$fmp_data['fmp_width'].'" height="'.$fmp_data['fmp_height'].'" />';
        }

        $fmp_data['fallback']['object_footer']  = ' </object>';
        $fmp_data['fallback'] = implode(LF, $fmp_data['fallback']);

    } else {

        $fmp_data['fallback'] = '';

    }

    // Set Video-JS
    $fmp_data['video'] = array();
    $fmp_data['video_type'] = '';
    $fmp_data['fmp_file_ext'] = '';
    $fmp_data['format_types'] = array(
        'video' => array(
            'h264' => 'video/mp4',
            'webm' => 'video/webm',
            'ogg' => 'video/ogg'
        ),
        'audio' => array(
            'h264' => 'audio/mpeg',
            'm4a' => 'audio/mp4',
            'webm' => 'audio/webm',
            'ogg' => 'audio/ogg'
        )
    );

	if(!isset($fmp_data['fmp_int_ext_h264'])) {

    	$fmp_data = array_merge(
    	    array(
            	'fmp_int_ext_h264' => 0,
            	'fmp_external_file_h264' => '',
            	'fmp_internal_id_h264' => '',
            	'fmp_int_ext_webm' => 0,
            	'fmp_external_file_webm' => '',
            	'fmp_internal_id_webm' => '',
            	'fmp_int_ext_ogg' => 0,
            	'fmp_external_file_ogg' => '',
            	'fmp_internal_id_ogg' => ''
        	),
        	$fmp_data
        );

	}

    // H.264
    if($fmp_data['fmp_int_ext_h264'] == 1 && $fmp_data['fmp_external_file_h264']) {
        $fmp_data['video'][ $fmp_data['format_types'][$fmp_data['fmp_set_audio']]['h264'] ] = $fmp_data['fmp_external_file_h264'];
    } elseif($fmp_data['fmp_int_ext_h264'] == 0 && $fmp_data['fmp_internal_id_h264']) {
        $fmp_data['stream'] = get_mediaplayer_stream($fmp_data['fmp_internal_id_h264']);
        if($fmp_data['stream']) {
            if(!empty($fmp_data['fmp_file_ext']) && isset($fmp_data['format_types'][$fmp_data['fmp_set_audio']][$fmp_data['fmp_file_ext']])) {
                $fmp_data['video'][ $fmp_data['format_types'][$fmp_data['fmp_set_audio']][$fmp_data['fmp_file_ext']] ] = $fmp_data['stream'];
            } else {
                $fmp_data['video'][ $fmp_data['format_types'][$fmp_data['fmp_set_audio']]['h264'] ] = $fmp_data['stream'];
            }
        }
    }

    // WebM
    if($fmp_data['fmp_int_ext_webm'] == 1 && $fmp_data['fmp_external_file_webm']) {
        $fmp_data['video'][ $fmp_data['format_types'][$fmp_data['fmp_set_audio']]['webm'] ] = $fmp_data['fmp_external_file_webm'];
    } elseif($fmp_data['fmp_int_ext_webm'] == 0 && $fmp_data['fmp_internal_id_webm']) {
        $fmp_data['stream'] = get_mediaplayer_stream($fmp_data['fmp_internal_id_webm']);
        if($fmp_data['stream']) {
            $fmp_data['video'][ $fmp_data['format_types'][$fmp_data['fmp_set_audio']]['webm'] ] = $fmp_data['stream'];
        }
    }

    // Ogg
    if($fmp_data['fmp_int_ext_ogg'] == 1 && $fmp_data['fmp_external_file_ogg']) {
        $fmp_data['video'][ $fmp_data['format_types'][$fmp_data['fmp_set_audio']]['ogg'] ] = $fmp_data['fmp_external_file_ogg'];
    } elseif($fmp_data['fmp_int_ext_ogg'] == 0 && $fmp_data['fmp_internal_id_ogg']) {
        $fmp_data['stream'] = get_mediaplayer_stream($fmp_data['fmp_internal_id_ogg']);
        if($fmp_data['stream']) {
            $fmp_data['video'][ $fmp_data['format_types'][$fmp_data['fmp_set_audio']]['ogg'] ] = $fmp_data['stream'];
        }
    }

    // Video JS
    if(count($fmp_data['video'])) {

        $fmp_data['video_tag'] = array();
        $fmp_data['video_tag']['header']  = '<'.$fmp_data['fmp_set_audio'].' ';

        if(!$fmp_data['fmp_set_html5only']) {

            initVideoJs();

            // check for video-js Skin
            if($fmp_data['fmp_set_skin_html5'] && $fmp_data['fmp_set_skin_html5'] != 'default' && is_file(PHPWCMS_TEMPLATE.'lib/video-js/skins/'.$fmp_data['fmp_set_skin_html5'].'.css')) {
                $block['custom_htmlhead']['skin_html5.css']  = '  <link rel="stylesheet" type="text/css" href="' . TEMPLATE_PATH . 'lib/video-js/skins/'.$fmp_data['fmp_set_skin_html5'].'.css" />';
                $fmp_data['fmp_set_skin_html5'] = ' ' . strtolower($fmp_data['fmp_set_skin_html5']).'-css';
                $fmp_data['fmp_set_skin_video'] = strtolower($fmp_data['fmp_set_skin_html5']).' vjs-default-skin';
            } else {
                $fmp_data['fmp_set_skin_html5'] = '';
                $fmp_data['fmp_set_skin_video'] = 'vjs-default-skin';
            }

            if(empty($fmp_data['fmp_marker'])) {
                $_fmp_marker = array();
                $fmp_data['fmp_marker'] = '{}';
            } else {
                $_fmp_marker = explode(LF, $fmp_data['fmp_marker']);
                $fmp_data['fmp_marker'] = array();
                if(count($_fmp_marker)) {
                    foreach($_fmp_marker as $_marker) {
                        $_marker = convertStringToArray($_marker, '|', '');
                        if(!empty($_marker[0]) && $_marker[0] = floatval($_marker[0])) {
                            $fmp_data['fmp_marker'][] = array(
                                'time' => $_marker[0],
                                'text' => isset($_marker[1]) ? $_marker[1] : '',
                                'overlayText' => isset($_marker[2]) ? $_marker[2] : '',
                                'class' => isset($_marker[3]) ? $_marker[3] : ''
                            );
                        }
                    }
                }
                $_fmp_marker = $fmp_data['fmp_marker'];
                $fmp_data['fmp_marker'] = json_encode($fmp_data['fmp_marker']);
            }

            // Put Video JS scripts to the body end
            $block['custom_htmlhead']['video.js'] = '  <script'.SCRIPT_ATTRIBUTE_TYPE.' src="' . $phpwcms['video-js'] . 'video.min.js" charset="utf-8"></script>';

            $fmp_data['video_js_attributes'] = 'id="video-js-'.$fmp_data['id'].'" class="video-js '.$fmp_data['fmp_set_skin_video'].'" ';

            $fmp_data['init_videojs']  = '<script'.SCRIPT_ATTRIBUTE_TYPE.'>' . LF;
            $fmp_data['init_videojs'] .= '  var videoJS_' . $fmp_data['id'] . ' = videojs("video-js-' . $fmp_data['id'] . '"),' . LF;
            $fmp_data['init_videojs'] .= '      videoJS_' . $fmp_data['id'] . '_marker = ' . $fmp_data['fmp_marker'] . ';' . LF;
            $fmp_data['init_videojs'] .= '  if(typeof videoJsInstances === "undefined") {var videoJsInstances = [];}' . LF;
            $fmp_data['init_videojs'] .= '  videoJsInstances.push({id: "'. $fmp_data['id'] . '", instance: "videoJS_' . $fmp_data['id']  . '"});';
            $fmp_data['init_ready'] = array();

            if(isset($_getVar['fmp'])) {
                $_fmp_time = explode('-', $_getVar['fmp']);
                $_fmp_time[0] = intval($_fmp_time[0]);
                if ($_fmp_time[0] && isset($_fmp_time[1]) && $_fmp_time[0] == $crow["acontent_id"]) {
                    $fmp_data['init_videojs'] .= LF . "  var videoJS_scrollTo = '" . $fmp_data['id'] ."';";
                    if (substr($_fmp_time[1], 0, 1) === 'm') {
                        $_fmp_time[1] = intval(substr($_fmp_time[1], 1));
                        if ($_fmp_time[1] && count($_fmp_marker) >= $_fmp_time[1]) {
                            $_fmp_time[1] = $_fmp_marker[ $_fmp_time[1] - 1 ]['time'];
                        } else {
                            $_fmp_time[1] = 0;
                        }
                        //$_fmp_marker
                    } else {
                        $_fmp_time[1] = floatval($_fmp_time[1]);
                    }
                    if ($_fmp_time[1]) {
                        $fmp_data['init_ready'][] = 'this.currentTime(' . $_fmp_time[1] . ');';
                    }
                }
            }

            if(isset($fmp_data['fmp_set_volume'])) {
                $fmp_data['init_ready'][] = 'this.volume(' . ($fmp_data['fmp_set_volume'] / 100) . ');';

                if(!$fmp_data['fmp_set_volume']) {
                    $fmp_data['video_tag']['header'] .= 'muted ';
                }
            }

            if(count($fmp_data['init_ready'])) {
                $fmp_data['init_videojs'] .= LF . '  videoJS_' . $fmp_data['id'] . '.ready(function(){' . implode('', $fmp_data['init_ready']) . '});';
            }

            $fmp_data['init_videojs'] .= LF . '  </script>';

        } else {

            $fmp_data['video_js_attributes'] = '';

            if(isset($fmp_data['fmp_set_volume']) && !$fmp_data['fmp_set_volume']) {
                $fmp_data['video_tag']['header'] .= 'muted ';
            }

        }

        // build Video JS leading tag
        $fmp_data['video_tag']['header'] .= $fmp_data['video_js_attributes'];
        if($fmp_data['fmp_set_audio'] === 'video') {
            $fmp_data['video_tag']['header'] .= 'width="'.$fmp_data['fmp_width'].'" height="'.$fmp_data['fmp_height'].'" ';
            if(!empty($fmp_data['fmp_preview'])) {
                $fmp_data['video_tag']['header'] .= 'poster="'.$fmp_data['preview'].'" ';
            }
        }
        if($fmp_data['fmp_set_autostart']) {
            $fmp_data['video_tag']['header'] .= 'autoplay="autoplay" ';
        }
        if(!empty($fmp_data['fmp_set_loop'])) {
            $fmp_data['video_tag']['header'] .= 'loop="loop" ';
        }
        if($fmp_data['fmp_set_showcontrols'] !== 'none') {
            $fmp_data['video_tag']['header'] .= 'controls="controls" ';

            if(!empty($fmp_data['fmp_set_downloadbutton'])) {
                $fmp_data['video_tag']['header'] .= 'controlsList="nodownload" ';
            }
        }

        $fmp_data['video_tag']['header'] .= 'preload="' . (isset($fmp_data['fmp_set_preload']) ? $fmp_data['fmp_set_preload'] : 'auto') . '">';

        foreach($fmp_data['video'] as $param_name => $param_value) {
            $fmp_data['video_tag'][] = '    <source src="'.html_specialchars($param_value).'" type="'.$param_name.'" />';
        }


        if($fmp_data['fmp_set_html5only']) {

            $fmp_data['video_tag']['footer'] = '</'.$fmp_data['fmp_set_audio'].'>';

        } else {

            $fmp_data['video_tag']['fallback']  = $fmp_data['fallback'];
            $fmp_data['video_tag']['footer']    = '</'.$fmp_data['fmp_set_audio'].'>';

            if(empty($phpwcms['js_in_body'])) {
                $fmp_data['video_tag']['footer'] .= $fmp_data['init_videojs'];
            } else {
                $block['custom_htmlhead']['videojs_'.$fmp_data['id']] = '  ' . $fmp_data['init_videojs'];
            }

        }

        $fmp_data['fallback'] = '   ' . implode(LF.'    ', $fmp_data['video_tag']);

        unset($fmp_data['video'], $fmp_data['video_tag']);

    // Flash Video Fallback
    } elseif($fmp_data['fallback']) {

        // Load SwfObject 2.1
        initSwfObject();

        // build SwfObject Script Block
        $block['custom_htmlhead'][ $fmp_data['id'] ]  = '  <script'.SCRIPT_ATTRIBUTE_TYPE.'>'.LF.SCRIPT_CDATA_START.LF;
        $block['custom_htmlhead'][ $fmp_data['id'] ] .= '   var flashvars_'.$fmp_data['id'].'   = {' . implode(', ', $fmp_data['flashvars']) . '};' . LF;
        $block['custom_htmlhead'][ $fmp_data['id'] ] .= '   var params_'.$fmp_data['id'].'  = {' . implode(', ', $fmp_data['params']) . '};' . LF;
        $block['custom_htmlhead'][ $fmp_data['id'] ] .= '   var attributes_'.$fmp_data['id'].'  = {' . implode(', ', $fmp_data['attributes']) . '};' . LF;
        $block['custom_htmlhead'][ $fmp_data['id'] ] .= '   swfobject.embedSWF("'.$fmp_data['player_swf'].'", "'.$fmp_data['id'].'", "'.$fmp_data['fmp_width'].'", "'.$fmp_data['fmp_height'].'", "'.$fmp_data['fmp_set_flashversion'].'", false, flashvars_'.$fmp_data['id'].', params_'.$fmp_data['id'].', attributes_'.$fmp_data['id'].');';
        $block['custom_htmlhead'][ $fmp_data['id'] ] .= LF.SCRIPT_CDATA_END.LF.'  </script>';

        $fmp_data['fmp_set_skin_html5'] = '';

    }

    // add rendering result to current listing
    $fmp_data['fmp_template']  = render_cnt_template($fmp_data['fmp_template'], 'ATTR_CLASS', html($crow['acontent_attr_class']));
    $fmp_data['fmp_template']  = render_cnt_template($fmp_data['fmp_template'], 'ATTR_ID', html($crow['acontent_attr_id']));
    $fmp_data['fmp_template']  = render_cnt_template($fmp_data['fmp_template'], 'TITLE',    html_specialchars($crow['acontent_title']));
    $fmp_data['fmp_template']  = render_cnt_template($fmp_data['fmp_template'], 'SUBTITLE', html_specialchars($crow['acontent_subtitle']));
    $fmp_data['fmp_template']  = render_cnt_template($fmp_data['fmp_template'], 'CAPTION', html_specialchars($fmp_data['fmp_caption']));
    $fmp_data['fmp_template']  = render_cnt_template($fmp_data['fmp_template'], 'TARGET', empty($fmp_data['fmp_link'][1]) ? '' : html_specialchars($fmp_data['fmp_link'][1]));
    $fmp_data['fmp_template']  = render_cnt_template($fmp_data['fmp_template'], 'LINK', empty($fmp_data['fmp_link'][0]) ? '' : html_specialchars($fmp_data['fmp_link'][0]));
    $fmp_data['fmp_template']  = render_cnt_template($fmp_data['fmp_template'], 'PLAYER', $fmp_data['fallback']);
    $CNT_TMP                  .= str_replace('{ID}', $fmp_data['id'], $fmp_data['fmp_template']);

}
