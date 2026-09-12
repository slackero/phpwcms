<?php
/**
 * phpwcms
 *
 * @author Oliver Georgi <og@phpwcms.org>
 * @copyright Copyright (c) 2002-2026, Oliver Georgi
 * @license http://opensource.org/licenses/GPL-2.0 GNU GPL-2
 *
 **/

// ----------------------------------------------------------------
// obligate check for phpwcms constants
if (!defined('PHPWCMS_ROOT')) {
    die("You Cannot Access This Script Directly, Have a Nice Day.");
}
// ----------------------------------------------------------------

//multimedia

// read template
if(empty($crow["acontent_template"]) && is_file(PHPWCMS_TEMPLATE.'inc_default/multimedia.tmpl')) {

    $crow["acontent_template"] = render_device( @file_get_contents(PHPWCMS_TEMPLATE.'inc_default/multimedia.tmpl') );

} elseif(is_file(PHPWCMS_TEMPLATE.'inc_cntpart/multimedia/'.$crow["acontent_template"])) {

    $crow["acontent_template"] = render_device( @file_get_contents(PHPWCMS_TEMPLATE.'inc_cntpart/multimedia/'.$crow["acontent_template"]) );

} else {

    $crow['acontent_template'] = '[ATTR_CLASS]<div class="{ATTR_CLASS}"[ATTR_ID] id="{ATTR_ID}"[/ATTR_ID]>[/ATTR_CLASS][ATTR_CLASS_ELSE][ATTR_ID]<div id="{ATTR_ID}">[/ATTR_ID][/ATTR_CLASS_ELSE][MULTIMEDIA]<div class="multimedia">{MULTIMEDIA}</div>[/MULTIMEDIA][ATTR_CLASS]</div>[/ATTR_CLASS][ATTR_CLASS_ELSE][ATTR_ID]</div>[/ATTR_ID][/ATTR_CLASS_ELSE]';

}

$media              = @unserialize($crow['acontent_form'], ['allowed_classes' => false]);
$media["source"]    = '';
$media["code"]      = '';
$media["alt"]       = '';

$media["media_control"] = $media["media_control"] ? 'true' : 'false';
$media["media_auto"]    = $media["media_auto"] ? 'true' : 'false';

if($media["media_src"]) {

    $media["source"] = $media['media_extern'];

} elseif($media["media_id"]) {

    $media["sql"]  = "SELECT * FROM ".DB_PREPEND."file WHERE f_public=1 AND f_aktiv=1 AND f_id=".intval($media["media_id"])." AND ";
    if( !FEUSER_LOGIN_STATUS ) {
        $media["sql"] .= 'f_granted=0 AND ';
    }
    $media["sql"] .= "f_name="._dbEscape($media["media_name"])." LIMIT 1";

    $media["result"] = _dbQuery($media["sql"]);

    if(isset($media["result"][0])) {

        $media["mime"]   = $media["result"][0]["f_type"];
        $media["source"] = PHPWCMS_FILES.$media["result"][0]["f_hash"];

        if($media["result"][0]["f_ext"]) {
            $media["source"] .= '.'.$media["result"][0]["f_ext"];
        }
    }
}

if(!empty($media["image_id"])) {

    $media["alt"] .= '<div class="alt-image">';
    $media["alt"] .= '<img src="' . PHPWCMS_RESIZE_IMAGE . '/' . $media["media_width"] . 'x' . $media["media_height"] . 'x1/' . $media["image_id"] . '/' . rawurlencode($media["image_name"]) . '" ';
    $media["alt"] .= 'alt="' . html($media["image_name"]) . '"' . PHPWCMS_LAZY_LOADING . HTML_TAG_CLOSE;
    $media["alt"] .= '</div>';

}
if(!empty($media["image_caption"])) {

    $media["alt"] .= plaintext_htmlencode($media["image_caption"]);

}

if($media["alt"]) {
    $media["alt"] = '   ' . $media["alt"] . LF;
}

//Aufbauen der Plugin-Codeteile
if($media["source"]) {

    $randomID = 'mediaID'.$crow['acontent_id'];

    switch($media["media_player"]) {

        case 0: // QuickTime / HTML5 Video & Audio
        case 1: // RealPlayer fallback to HTML5
        case 2: // Windows Media Player fallback to HTML5
                $is_audio = ($media["media_type"] == 1) || preg_match('/\.(mp3|m4a|aac|wav|oga|ogg|flac|wma|ra)$/i', $media["source"]);
                $controls = ($media["media_control"] == "true") ? ' controls' : '';
                $autoplay = ($media["media_auto"] == "true") ? ' autoplay' : '';

                if ($is_audio) {
                    $audio_mime = !empty($media["mime"]) ? $media["mime"] : 'audio/mpeg';
                    $media["code"]  = '<audio id="'.$randomID.'"'.$controls.$autoplay.' preload="metadata">'.LF;
                    $media["code"] .= '  <source src="'.html($media["source"]).'" type="'.html($audio_mime).'">'.LF;
                    $media["code"] .= $media["alt"];
                    $media["code"] .= '</audio>'.LF;
                } else {
                    $width_attr = $media["media_width"] ? ' width="'.intval($media["media_width"]).'"' : '';
                    $height_attr = $media["media_height"] ? ' height="'.intval($media["media_height"]).'"' : '';
                    $video_mime = !empty($media["mime"]) ? $media["mime"] : 'video/mp4';
                    if ($video_mime === 'video/quicktime' || preg_match('/\.(mov|mp4|m4v)$/i', $media["source"])) {
                        $video_mime = 'video/mp4';
                    } elseif (preg_match('/\.webm$/i', $media["source"])) {
                        $video_mime = 'video/webm';
                    } elseif (preg_match('/\.(ogv|ogg)$/i', $media["source"])) {
                        $video_mime = 'video/ogg';
                    }
                    $media["code"]  = '<video id="'.$randomID.'"'.$width_attr.$height_attr.$controls.$autoplay.' playsinline preload="metadata" style="max-width:100%;height:auto;">'.LF;
                    $media["code"] .= '  <source src="'.html($media["source"]).'" type="'.html($video_mime).'">'.LF;
                    $media["code"] .= $media["alt"];
                    $media["code"] .= '</video>'.LF;
                }
                break;


        case 3: //Flash Player/Plugin
                if(!$media["media_src"] && (!$media["media_width"] || !$media["media_height"])) {
                    $media['local'] = @getimagesize(PHPWCMS_ROOT.'/'.$media["source"]);
                    if(is_array($media['local'])) {
                        $media["media_width"]  = $media['local'][0];
                        $media["media_height"] = $media['local'][1];
                    }
                }

                $media["width"]  = $media["media_width"]  ? ' width="'.$media["media_width"].'"' : '';
                $media["height"] = $media["media_height"] ? ' height="'.$media["media_height"].'"' : '';

                $media["param"]  = '    <param name="movie" value="'.$media["source"].'" />'.LF;
                $media["param"] .= '    <param name="quality" value="autohigh" />'.LF;
                $media["param"] .= '    <param name="scale" value="noborder" />'.LF;
                $media["param"] .= '    <param name="loop" value="false" />'.LF;
                $media["param"] .= '    <param name="play" value="'.$media["media_auto"].'" />'.LF;

                if($media["media_transparent"]) {
                    $media["param"] .= '    <param name="wmode" value="transparent" />'.LF;
                } else {
                    $media["param"] .= '    <param name="wmode" value="opaque" />'.LF;
                }


                $media["code"]  = LF . '<object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" id="'.$randomID.'"'.$media["width"].$media["height"].'>' . LF;
                $media["code"] .= $media["param"];
                $media["code"] .= ' <!--[if !IE]>--><object type="application/x-shockwave-flash" data="'.$media["source"].'"'.$media["width"].$media["height"].'><!--<![endif]-->' . LF;
                $media["code"] .= $media["param"];

                $media["code"] .= $media["alt"];

                $media["code"] .= ' <!--[if !IE]>--></object><!--<![endif]-->' . LF;
                $media["code"] .= '</object>' . LF;

                initSwfObject();

                $block['custom_htmlhead'][$randomID]  = '  <script'.SCRIPT_ATTRIBUTE_TYPE.'>'.LF.SCRIPT_CDATA_START.LF;
                $block['custom_htmlhead'][$randomID] .= '   swfobject.registerObject("'.$randomID.'", "9.0.0", "'.PHPWCMS_URL.TEMPLATE_PATH.'inc_js/swfobject/2.1/expressInstall.swf");';
                $block['custom_htmlhead'][$randomID] .= LF.SCRIPT_CDATA_END.LF.'  </script>';

                break;
    }
}

if($media["code"]) {

    $media["result"] = '';

    switch($media["media_pos"]) {

        case 0: $media["result"] .= headline($crow["acontent_title"], $crow["acontent_subtitle"], $template_default["article"]);
                $media["result"] .= $media["code"];
                break;

        case 1: $media["result"] .= headline($crow["acontent_title"], $crow["acontent_subtitle"], $template_default["article"]);
                $media["result"] .= "<div align=\"center\">".$media["code"]."</div>";
                break;

        case 2: $media["result"] .= headline($crow["acontent_title"], $crow["acontent_subtitle"], $template_default["article"]);
                $media["result"] .= "<div align=\"right\">".$media["code"]."</div>";
                break;

        case 3: $media["result"] .= "<table align=\"left\">\n";
                $media["result"] .= "<tr><td colspan=\"2\">".spacer(1,3)."</td></tr>\n";
                $media["result"] .= ($crow["acontent_title"]) ? "<tr><td class=\"tableHead\">".html_specialchars($crow["acontent_title"])."</td><td>".
                                                        spacer(5,1)."</td></tr>\n<tr><td colspan=\"2\">".spacer(1,3)."</td></tr>\n" : "";
                $media["result"] .= ($crow["acontent_subtitle"]) ?  "<tr><td class=\"tableSubHead\">".html_specialchars($crow["acontent_subtitle"])."</td><td>".
                                                            spacer(5,1)."</td></tr>\n<tr><td colspan=\"2\">".spacer(1,3)."</td></tr>\n" : "";
                $media["result"] .= "<tr><td>".$media["code"]."</td><td>".spacer(5,1)."</td></tr>\n";
                $media["result"] .= "<tr><td colspan=\"2\">".spacer(1,3)."</td></tr>\n";
                $media["result"] .= "</table>\n";
                break;

        case 4: $media["result"] .= "<table align=\"right\">\n";
                $media["result"] .= "<tr><td colspan=\"2\">".spacer(1,3)."</td></tr>\n";
                $media["result"] .= ($crow["acontent_title"]) ? "<tr><td>".spacer(5,1)."</td><td class=\"tableHead\">".html_specialchars($crow["acontent_title"])."</td></tr>\n<tr><td colspan=\"2\">".spacer(1,3)."</td></tr>\n" : "";
                $media["result"] .= ($crow["acontent_subtitle"]) ?  "<tr><td>".spacer(5,1)."</td><td class=\"tableSubHead\">".html_specialchars($crow["acontent_subtitle"])."</td></tr>\n<tr><td colspan=\"2\">".spacer(1,3)."</td></tr>\n" : "";
                $media["result"] .= "<tr><td>".spacer(5,1)."</td><td>".$media["code"]."</td></tr>\n";
                $media["result"] .= "<tr><td colspan=\"2\">".spacer(1,3)."</td></tr>\n";
                $media["result"] .= "</table>\n";
                break;
    }

} else {

    $media["result"] = headline($crow["acontent_title"], $crow["acontent_subtitle"], $template_default["article"]);

}

$crow["acontent_template"] = render_cnt_template($crow["acontent_template"], 'MULTIMEDIA', trim($media["result"]) );
$crow["acontent_template"] = render_cnt_template($crow["acontent_template"], 'ATTR_CLASS', html($crow['acontent_attr_class']));
$crow["acontent_template"] = render_cnt_template($crow["acontent_template"], 'ATTR_ID', html($crow['acontent_attr_id']));
$crow["acontent_template"] = str_replace('{ID}', $crow['acontent_id'], $crow["acontent_template"]);

unset($media);

$CNT_TMP .= $crow["acontent_template"];
