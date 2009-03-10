<?php
/*************************************************************************************
   Copyright notice
   
   (c) 2002-2008 Oliver Georgi (oliver@phpwcms.de) // All rights reserved.

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
		$fmp_data['fmp_width']	= 320;
	}
	// check if controls should be shown and add controls' height to player height
	$fmp_data['fmp_displayheight'] = $fmp_data['fmp_height'];
	if($fmp_data['fmp_set_showcontrols'] || empty($fmp_data['fmp_height'])) {
		$fmp_data['fmp_height'] += ($fmp_data['fmp_set_largecontrols'] ? 40 : 20);
	}

	if(empty($fmp_data['fmp_set_flashversion'])) {
		$fmp_data['fmp_set_flashversion'] = 7;
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
			$fmp_data['fmp_file']	= PHPWCMS_URL.TEMPLATE_PATH . 'jw_media_player/stream.php?file='.$fmp_data['file']['f_hash'];
			
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
			
			$fmp_data['preview']  = PHPWCMS_URL.TEMPLATE_PATH . 'jw_media_player/stream.php?type='.$fmp_data['preview']['f_type'];
			$fmp_data['preview'] .= '&file=' . $fmp_data['fmp_preview'];
		
		} else {
		
			$fmp_data['fmp_img_id'] = 0;
		
		}
	
	}
	
	// Define Flash Vars
	$fmp_data['flashvars'][]	= 'file: "'.rawurlencode($fmp_data['file']).'"';
	$fmp_data['flashvars'][]	= 'width: ' . $fmp_data['fmp_width'];
	$fmp_data['flashvars'][]	= 'showeq: ' . ($fmp_data['fmp_set_showeq'] ? 'true' : 'false');
	$fmp_data['flashvars'][]	= 'showdigits: ' . ($fmp_data['fmp_set_showdigits'] ? 'true' : 'false');
	$fmp_data['flashvars'][]	= 'showvolume: ' . ($fmp_data['fmp_set_showvolume'] ? 'true' : 'false');
	$fmp_data['flashvars'][]	= 'largecontrols: ' . ($fmp_data['fmp_set_largecontrols'] ? 'true' : 'false');
	$fmp_data['flashvars'][]	= 'autostart: ' . ($fmp_data['fmp_set_autostart'] ? 'true' : 'false');
	$fmp_data['flashvars'][]	= 'usecaptions: false';
	
	if(isset($fmp_data['fmp_set_overstretch']) && $fmp_data['fmp_set_overstretch'] != 'default') {
		$fmp_data['flashvars'][] = 'overstretch: "' . $fmp_data['fmp_set_overstretch'] . '"';
	}
	
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
	
	if(empty($fmp_data['fmp_set_bgcolor'])) {
		$fmp_data['fmp_set_bgcolor'] = 'FFFFFF';
	}
	
	$fmp_data['flashvars'][] = 'backcolor: "0x' . $fmp_data['fmp_set_bgcolor'] . '"';

	if($fmp_data['fmp_set_hcolor']) {
		$fmp_data['flashvars'][] = 'lightcolor: "0x' . $fmp_data['fmp_set_hcolor'] . '"';
	}
	if($fmp_data['fmp_set_color']) {
		$fmp_data['flashvars'][] = 'frontcolor: "0x' . $fmp_data['fmp_set_color'] . '"';
	}
		
	if(!empty($fmp_data['fmp_set_skin']) && is_file(PHPWCMS_TEMPLATE.'jw_media_player/skins/'.$fmp_data['fmp_set_skin'].'.swf')) {
		$fmp_data['flashvars'][] = 'skin: "' . rawurlencode(PHPWCMS_URL.TEMPLATE_PATH.'jw_media_player/skins/'.$fmp_data['fmp_set_skin']).'.swf"';
		if($fmp_data['fmp_set_skin'] == 'stylish') {
			$fmp_data['fmp_displayheight'] += 12;	
		}
	}
	
	$fmp_data['flashvars'][]	= 'displayheight: ' . $fmp_data['fmp_displayheight'];
	
	$fmp_data['params'][]		= 'allowfullscreen: true';
	$fmp_data['params'][]		= 'play: true';
	$fmp_data['params'][]		= 'wmode: "opaque"';
	
	
	$fmp_data['attributes'][]	= '';
	

	// build SwfObject Sccript Block

	// set ID
	$fmp_data['id'] = 'fmp'.$crow["acontent_id"];
	

	$block['custom_htmlhead'][ $fmp_data['id'] ]  = '  <script type="text/javascript">'.LF.SCRIPT_CDATA_START.LF;
	
	// Licensed Player
	if(!empty($phpwcms['JW_FLV_License'])) {
		$fmp_data['host'] = parse_url(PHPWCMS_URL);
		$fmp_data['flashvars'][] = 'abouttext: "'.$fmp_data['host']['host'].' FLV Player"';
		$fmp_data['flashvars'][] = 'aboutlink: "'.PHPWCMS_URL.'"';
		$block['custom_htmlhead'][ $fmp_data['id'] ] .= '	// JW FLV Media Player licensed for: '.$fmp_data['host']['host'].' ('.$phpwcms['JW_FLV_License'].')' . LF;
	}
	
	$block['custom_htmlhead'][ $fmp_data['id'] ] .= '	var flashvars_'.$fmp_data['id'].'	= {' . implode(', ', $fmp_data['flashvars']) . '};' . LF;
	$block['custom_htmlhead'][ $fmp_data['id'] ] .= '	var params_'.$fmp_data['id'].'	= {' . implode(', ', $fmp_data['params']) . '};' . LF;
	$block['custom_htmlhead'][ $fmp_data['id'] ] .= '	var attributes_'.$fmp_data['id'].'	= {' . implode(', ', $fmp_data['attributes']) . '};' . LF;
	
	$block['custom_htmlhead'][ $fmp_data['id'] ] .= '	swfobject.embedSWF("'.PHPWCMS_URL.TEMPLATE_PATH.'jw_media_player/mediaplayer.swf", "'.$fmp_data['id'].'", "'.$fmp_data['fmp_width'].'", "'.$fmp_data['fmp_height'].'", "'.$fmp_data['fmp_set_flashversion'].'", false, flashvars_'.$fmp_data['id'].', params_'.$fmp_data['id'].', attributes_'.$fmp_data['id'].');';
	
	$block['custom_htmlhead'][ $fmp_data['id'] ] .= LF.SCRIPT_CDATA_END.LF.'  </script>';


	// add rendering result to current listing
	$fmp_data['fmp_template']  = render_cnt_template($fmp_data['fmp_template'], 'TITLE',    html_specialchars($crow['acontent_title']));
	$fmp_data['fmp_template']  = render_cnt_template($fmp_data['fmp_template'], 'SUBTITLE', html_specialchars($crow['acontent_subtitle']));
	$CNT_TMP				  .= str_replace('{PLAYER}', '<div id="'.$fmp_data['id'].'"></div>', $fmp_data['fmp_template']);

}


?>