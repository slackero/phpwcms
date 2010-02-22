<?php
/*************************************************************************************
   Copyright notice
   
   (c) 2002-2010 Oliver Georgi (oliver@phpwcms.de) // All rights reserved.

This script is part of PHPWCMS. The PHPWCMS web content management system is
free software; you can redistribute it and/or modify it under the terms of
the GNU General Public License as published by the Free Software Foundation;
either version 2 of the License, or (at your option) any later version.

The GNU General Public License can be found at http://www.gnu.org/copyleft/gpl.html
A copy is found in the textfile GPL.txt and important notices to the license
from the author is found in LICENSE.txt distributed with these scripts.

This script is distributed in the hope that it will be useful, but WITHOUT ANY
WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A
PARTICULAR PURPOSE.  See the GNU General Public License for more details.

This copyright notice MUST APPEAR in all copies of the script!
*************************************************************************************/

// ----------------------------------------------------------------
// obligate check for phpwcms constants
if (!defined('PHPWCMS_ROOT')) {
   die("You Cannot Access This Script Directly, Have a Nice Day.");
}
// ----------------------------------------------------------------

// Flash Media Player

$fmp_data	= @unserialize($crow["acontent_form"]);

if(isset($fmp_data['fmp_template'])) {

	// read template
	if(empty($crow["acontent_template"]) && is_file(PHPWCMS_TEMPLATE.'inc_default/flashplayer.tmpl')) {
	
		$fmp_data['fmp_template']	= @file_get_contents(PHPWCMS_TEMPLATE.'inc_default/flashplayer.tmpl');
		
	} elseif(is_file(PHPWCMS_TEMPLATE.'inc_cntpart/flashplayer/'.$crow["acontent_template"])) {
	
		$fmp_data['fmp_template']	= @file_get_contents(PHPWCMS_TEMPLATE.'inc_cntpart/flashplayer/'.$crow["acontent_template"]);
	
	} else {
	
		$fmp_data['fmp_template']	= '[TITLE]<h3>{TITLE}</h3>[/TITLE][SUBTITLE]<h4>{SUBTITLE}</h4>[/SUBTITLE]{PLAYER}';
	
	}
	
	// Load SwfObject 2.1
	initSwfObject();

	// Set some defaults used to build SwfObject Call
	$fmp_data['flashvars'] 		= array();
	$fmp_data['attributes'] 	= array();
	$fmp_data['params'] 		= array();
	$fmp_data['flashvars_type']	= '';


	// set player dimensions first
	if(empty($fmp_data['fmp_width'])) {
		$fmp_data['fmp_width']	= 160;
	}
	// check if controls should be shown and add controls' height to player height
	$fmp_data['fmp_displayheight'] = $fmp_data['fmp_height'];
	
	if(empty($fmp_data['fmp_set_flashversion'])) {
		$fmp_data['fmp_set_flashversion'] = 9;
	}
	
	if(empty($fmp_data['fmp_set_bgcolor'])) {
		$fmp_data['fmp_set_bgcolor'] = '000000';
	}
	if(empty($fmp_data['fmp_set_color'])) {
		$fmp_data['fmp_set_color'] = 'FFFFFF';
	}
	
	// JW Player
	if(empty($fmp_data['fmp_player']) ) {
		
		$fmp_data['fmp_player_dir'] = 'jw_media_player';
	
		if(!$fmp_data['fmp_set_showcontrols'] || $fmp_data['fmp_set_showcontrols'] == 'none') {
			$fmp_data['fmp_set_showcontrols'] = 'none';
		} elseif($fmp_data['fmp_set_showcontrols'] != 'over') {
			$fmp_data['fmp_set_showcontrols'] = 'bottom';
		}
		
		if($fmp_data['fmp_set_showcontrols'] == 'bottom' || empty($fmp_data['fmp_height'])) {
			$fmp_data['fmp_height'] += 20;
		}

	
	
		switch($fmp_data['fmp_set_overstretch']) {
			case 'fit':
			case 'exactfit':
				$fmp_data['fmp_set_overstretch'] = 'exactfit';
				break;
			
			case 'true':
			case 'fill':
				$fmp_data['fmp_set_overstretch'] = 'fill';
				break;
			
			case 'false':
			case 'none':
				$fmp_data['fmp_set_overstretch'] = 'none';
				break;
				
			default:
				$fmp_data['fmp_set_overstretch'] = 'uniform';
		}

	// NonverBlaster:hover
	} else {
		
		$fmp_data['fmp_player_dir'] = 'nonverblaster';
		
		if(empty($fmp_data['fmp_height'])) {
			$fmp_data['fmp_height'] = 17;
		}
		
		if($fmp_data['fmp_set_showcontrols'] == 'none') {
			$fmp_data['fmp_set_showcontrols'] = 'false';
		} else {
			$fmp_data['fmp_set_showcontrols'] = 'true';
		}
		
	}

	
	// file
	if($fmp_data['fmp_int_ext']) {

		// external
		$fmp_data['file'] = $fmp_data['fmp_external_file'];
		
	} else {

		// internal
		$sql = 'SELECT * FROM '.DB_PREPEND.'phpwcms_file WHERE f_aktiv=1 AND f_public=1 AND f_id='.$fmp_data['fmp_internal_id'];
		if( !FEUSER_LOGIN_STATUS ) {
			$sql .= ' AND f_granted=0';
		}
		$fmp_data['file'] = _dbQuery($sql);
		
		if(isset($fmp_data['file'][0])) {
		
			$fmp_data['file']		= $fmp_data['file'][0];
			$fmp_data['fmp_file']	= PHPWCMS_URL.TEMPLATE_PATH . 'lib/'.$fmp_data['fmp_player_dir'].'/stream.php?file='.$fmp_data['file']['f_hash'];
			
			if($fmp_data['file']['f_ext']) {
			
				$fmp_data['flashvars_type'] = $fmp_data['file']['f_ext'];
			
				$fmp_data['fmp_file'] .= '.'.$fmp_data['file']['f_ext'];
				
				switch($fmp_data['file']['f_ext']) {
					case 'jpeg':
					case 'jpg':	$fmp_data['file']['f_type'] = 'image/jpeg';
								$fmp_data['fmp_img_id']		= 0;
								break;
					case 'png':	$fmp_data['file']['f_type'] = 'image/png';
								$fmp_data['fmp_img_id']		= 0;
								break;
					case 'gif':	$fmp_data['file']['f_type'] = 'image/gif';
								$fmp_data['fmp_img_id']		= 0;
								break;
					case 'flv':	$fmp_data['file']['f_type'] = 'video/x-flv';					break;
					case 'swf':	$fmp_data['file']['f_type'] = 'application/x-shockwave-flash';
								$fmp_data['fmp_img_id']		= 0;
								break;
					case 'mp3':	$fmp_data['file']['f_type'] = 'audio/mpeg';						break;	
				}
			}
			
			$fmp_data['fmp_file'] .= '&type='.$fmp_data['file']['f_type'];
			
			$fmp_data['file']  = $fmp_data['fmp_file'];
		
		} else {
			$fmp_data['file'] = '';
		}
	}
	
	// retrieve preview image
	if($fmp_data['fmp_img_id']) {
	
		$sql = 'SELECT * FROM '.DB_PREPEND.'phpwcms_file WHERE f_aktiv=1 AND f_public=1 AND f_id='.$fmp_data['fmp_img_id'];
		if( !FEUSER_LOGIN_STATUS ) {
			$sql .= ' AND f_granted=0';
		}
		$fmp_data['preview'] = _dbQuery($sql);

		if(isset($fmp_data['preview'][0])) {

			$fmp_data['preview']		= $fmp_data['preview'][0];
			$fmp_data['fmp_preview']	= $fmp_data['preview']['f_hash'];
			
			if($fmp_data['preview']['f_ext']) {
				
				$fmp_data['fmp_preview'] .= '.' . $fmp_data['preview']['f_ext'];
				
				switch($fmp_data['preview']['f_ext']) {
					case 'jpeg':
					case 'jpg':	$fmp_data['preview']['f_type'] = 'image/jpeg';						break;
					case 'png':	$fmp_data['preview']['f_type'] = 'image/png';						break;
					case 'gif':	$fmp_data['preview']['f_type'] = 'image/gif';						break;
				}
			
			}
			
			$fmp_data['preview']  = PHPWCMS_URL.TEMPLATE_PATH . 'lib/'.$fmp_data['fmp_player_dir'].'/stream.php?type='.$fmp_data['preview']['f_type'];
			$fmp_data['preview'] .= '&file=' . $fmp_data['fmp_preview'];
		
		} else {
		
			$fmp_data['fmp_img_id'] = 0;
		
		}
	
	}
	
	// Define Flash Vars
	
	// JW Player
	if(empty($fmp_data['fmp_player']) ) {
		
		$fmp_data['player_swf']		= PHPWCMS_URL.TEMPLATE_PATH.'lib/jw_media_player/player.swf';
	
		$fmp_data['flashvars'][]	= 'file: "'.rawurlencode($fmp_data['file']).'"';
		$fmp_data['flashvars'][]	= 'width: ' . $fmp_data['fmp_width'];
		$fmp_data['flashvars'][]	= 'controlbar: "' . $fmp_data['fmp_set_showcontrols'].'"';
		$fmp_data['flashvars'][]	= 'autostart: ' . ($fmp_data['fmp_set_autostart'] ? 'true' : 'false');
		$fmp_data['flashvars'][]	= 'usecaptions: false';
		$fmp_data['flashvars'][]	= 'stretching : "' . $fmp_data['fmp_set_overstretch'] . '"';
		
		if($fmp_data['fmp_img_id'] && isset($fmp_data['preview'])) {
			$fmp_data['flashvars'][] = 'image: "' . rawurlencode($fmp_data['preview']) . '"';
		}
		
		if($fmp_data['flashvars_type']) {
			$fmp_data['flashvars'][] = 'type: "' . $fmp_data['flashvars_type'] . '"';
		}
		
		if($fmp_data['fmp_set_logo']) {
			$fmp_data['flashvars'][] = 'logo: "' . rawurlencode($fmp_data['fmp_set_logo']) . '"';
		}
		
		if($fmp_data['fmp_link']) {
			
			$fmp_data['fmp_link'] = explode(' ', $fmp_data['fmp_link']);
			
			$fmp_data['flashvars'][] = 'link: "' . rawurlencode(trim($fmp_data['fmp_link'][0])) . '"';
			$fmp_data['flashvars'][] = 'linkfromdisplay: true';
			
			if(!empty($fmp_data['fmp_link'][1])) {
				$fmp_data['flashvars'][] = 'linktarget: "' . trim($fmp_data['fmp_link'][1]) . '"';
			}
		}
		
		if($fmp_data['fmp_set_showdownload']) {
			$fmp_data['flashvars'][] = 'showdownload: true';
		}
		
		$fmp_data['flashvars'][] = 'backcolor: "0x' . $fmp_data['fmp_set_bgcolor'] . '"';
	
		if($fmp_data['fmp_set_hcolor']) {
			$fmp_data['flashvars'][] = 'lightcolor: "0x' . $fmp_data['fmp_set_hcolor'] . '"';
		}
		if($fmp_data['fmp_set_color']) {
			$fmp_data['flashvars'][] = 'frontcolor: "0x' . $fmp_data['fmp_set_color'] . '"';
		}
			
		if(!empty($fmp_data['fmp_set_skin']) && is_file(PHPWCMS_TEMPLATE.'lib/jw_media_player/skins/'.$fmp_data['fmp_set_skin'].'.swf')) {
			$fmp_data['flashvars'][] = 'skin: "' . rawurlencode(PHPWCMS_URL.TEMPLATE_PATH.'lib/jw_media_player/skins/'.$fmp_data['fmp_set_skin']).'.swf"';
			if($fmp_data['fmp_set_skin'] == 'stylish') {
				$fmp_data['fmp_displayheight'] += 12;	
			}
		}
		
		$fmp_data['flashvars'][]	= 'displayheight: ' . $fmp_data['fmp_displayheight'];
		
		$fmp_data['params'][]		= 'allowfullscreen: true';
		$fmp_data['params'][]		= 'play: true';
		$fmp_data['params'][]		= 'wmode: "opaque"';
		
		// Licensed Player
		if(!empty($phpwcms['JW_FLV_License'])) {
			$fmp_data['host'] = parse_url(PHPWCMS_URL);
			$fmp_data['flashvars'][] = 'abouttext: "'.$fmp_data['host']['host'].' FLV Player"';
			$fmp_data['flashvars'][] = 'aboutlink: "'.PHPWCMS_URL.'"';
			$block['custom_htmlhead'][ $fmp_data['id'] ] .= '	// JW FLV Media Player licensed for: '.$fmp_data['host']['host'].' ('.$phpwcms['JW_FLV_License'].')' . LF;
		}
	
	// NonverBlaster:hover
	} else {
	
		$fmp_data['player_swf']		= PHPWCMS_URL.TEMPLATE_PATH.'lib/nonverblaster/NonverBlaster.swf';
		
		$fmp_data['flashvars'][]	= 'mediaURL: "'.rawurlencode($fmp_data['file']).'"';
		$fmp_data['flashvars'][]	= 'loop: "false"';
		$fmp_data['flashvars'][]	= 'showScalingButton: "true"';
		$fmp_data['flashvars'][]	= 'scaleIfFullScreen: "true"';
		$fmp_data['flashvars'][]	= 'crop: "false"';
		$fmp_data['flashvars'][]	= 'defaultVolume: "100"';
		$fmp_data['flashvars'][]	= 'buffer: "6"';
		$fmp_data['flashvars'][]	= 'allowSmoothing: "true"';
		$fmp_data['flashvars'][]	= 'controlsEnabled: "'.$fmp_data['fmp_set_showcontrols'].'"';
		$fmp_data['flashvars'][]	= 'autoPlay: "'.($fmp_data['fmp_set_autostart'] ? 'true' : 'false').'"';
		
		$fmp_data['flashvars'][]	= 'controlBackColor: "0x' . $fmp_data['fmp_set_bgcolor'] . '"';
		$fmp_data['flashvars'][]	= 'controlColor: "0x' . $fmp_data['fmp_set_color'] . '"';
	
		if($fmp_data['fmp_img_id'] && isset($fmp_data['preview'])) {
			$fmp_data['flashvars'][] = 'teaserURL: "' . rawurlencode($fmp_data['preview']) . '"';
		}
	
		if($fmp_data['fmp_set_logo']) {
			$fmp_data['flashvars'][] = 'indentImageURL: "' . rawurlencode($fmp_data['fmp_set_logo']) . '"';
		}

		$fmp_data['params'][]		= 'allowfullscreen: "true"';
		$fmp_data['params'][]		= 'menu: "false"';
		$fmp_data['params'][]		= 'wmode: "opaque"';
		$fmp_data['params'][]		= 'allowScriptAccess: "always"';
		
		if($fmp_data['fmp_link']) {
			
			$fmp_data['fmp_link'] = explode(' ', trim($fmp_data['fmp_link']));
			
			$fmp_data['flashvars'][] = 'onClick: "' . rawurlencode(trim($fmp_data['fmp_link'][0])) . '"';

		}
	
	}
	
	// set ID
	$fmp_data['id'] = 'fmp'.$crow["acontent_id"];
	
	$fmp_data['attributes'][]	= 'id: "'.$fmp_data['id'].'"';
	$fmp_data['attributes'][]	= 'name: "'.$fmp_data['id'].'"';
	$fmp_data['attributes'][]	= 'bgcolor: "#'.$fmp_data['fmp_set_bgcolor'].'"';
	

	// build SwfObject Script Block

	$block['custom_htmlhead'][ $fmp_data['id'] ]  = '  <script type="text/javascript">'.LF.SCRIPT_CDATA_START.LF;
	$block['custom_htmlhead'][ $fmp_data['id'] ] .= '	var flashvars_'.$fmp_data['id'].'	= {' . implode(', ', $fmp_data['flashvars']) . '};' . LF;
	$block['custom_htmlhead'][ $fmp_data['id'] ] .= '	var params_'.$fmp_data['id'].'	= {' . implode(', ', $fmp_data['params']) . '};' . LF;
	$block['custom_htmlhead'][ $fmp_data['id'] ] .= '	var attributes_'.$fmp_data['id'].'	= {' . implode(', ', $fmp_data['attributes']) . '};' . LF;
	$block['custom_htmlhead'][ $fmp_data['id'] ] .= '	swfobject.embedSWF("'.$fmp_data['player_swf'].'", "'.$fmp_data['id'].'", "'.$fmp_data['fmp_width'].'", "'.$fmp_data['fmp_height'].'", "'.$fmp_data['fmp_set_flashversion'].'", false, flashvars_'.$fmp_data['id'].', params_'.$fmp_data['id'].', attributes_'.$fmp_data['id'].');';
	$block['custom_htmlhead'][ $fmp_data['id'] ] .= LF.SCRIPT_CDATA_END.LF.'  </script>';


	// add rendering result to current listing
	$fmp_data['fmp_template']  = render_cnt_template($fmp_data['fmp_template'], 'TITLE',    html_specialchars($crow['acontent_title']));
	$fmp_data['fmp_template']  = render_cnt_template($fmp_data['fmp_template'], 'SUBTITLE', html_specialchars($crow['acontent_subtitle']));
	$CNT_TMP				  .= str_replace('{PLAYER}', '<div id="'.$fmp_data['id'].'"></div>', $fmp_data['fmp_template']);

}


?>