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



//file list

//$CNT_TMP .= headline($crow["acontent_title"], $crow["acontent_subtitle"], $template_default["article"]);

$crow["acontent_files"]		= explode(':', $crow["acontent_files"]);
$crow["acontent_text"]		= explode("\n", $crow["acontent_text"]);
$crow["acontent_form"]		= unserialize($crow["acontent_form"]);
$content['files_direct']	= empty($crow["acontent_form"]['direct_download']) ? 0 : 1;

$content['files']			= array();
$content['files_sql']		= array();

// build file id query first
foreach($crow["acontent_files"] as $key => $value) {

	$value										= intval($value);

	if($value) {
		$content['files'][$key]['file_id']		= $value;
		$content['files'][$key]['file_info']	= empty($crow["acontent_text"][$key]) ? '' : trim($crow["acontent_text"][$key]);
		$content['files_sql'][$key]				= $value;
	}
}

// create where query part for file ID
$content['files_sql'] = count($content['files_sql']) ? 'f_id=' . implode(' OR f_id=', $content['files_sql']) : '';

// if $content['files_sql'] is empty makes no sense to continue
if($content['files_sql']) {

	$content['files_sql'] 		=	"SELECT * FROM ".DB_PREPEND."phpwcms_file ".
									"WHERE f_public=1 AND f_aktiv=1 AND f_kid=1 AND f_trash=0 AND ".
									"(".$content['files_sql'].")";

	if($content['files_result']	=	_dbQuery($content['files_sql'])) {
	
		// get filelist template
		if(empty($crow["acontent_template"]) && is_file(PHPWCMS_TEMPLATE.'inc_default/filelist.tmpl')) {

			$crow["acontent_template"]	= @file_get_contents(PHPWCMS_TEMPLATE.'inc_default/filelist.tmpl');
	
		} elseif(is_file(PHPWCMS_TEMPLATE.'inc_cntpart/filelist/'.$crow["acontent_template"])) {
			
			$crow["acontent_template"] = @file_get_contents(PHPWCMS_TEMPLATE.'inc_cntpart/filelist/'.$crow["acontent_template"]);
			
		} else {
			
			$crow["acontent_template"]  = '[TITLE]<h4>{TITLE}</h4>[/TITLE][SUBTITLE]<h5>{SUBTITLE}</h5>[/SUBTITLE][TEXT]{TEXT}[/TEXT]'.LF;
			$crow["acontent_template"] .= '<ul class="fileDownload">' . LF . '<!--FILE_ENTRY_START//-->' . LF;
			$crow["acontent_template"] .= '	<li><a href="{FILE_LINK}"{FILE_TARGET}>{FILE_NAME}</a></li>' . LF;
			$crow["acontent_template"] .= '<!--FILE_ENTRY_END//-->' . LF . '</ul>' . LF;
			
		}
		
		$_files_settings				= get_tmpl_section('FILE_SETTINGS', $crow["acontent_template"]);
		$_files_settings				= parse_ini_str($_files_settings, false);
		$_files_settings				= array_merge(	array(	'icon_path' => 'img/icons/', 
																'icon_name' => 'small_icon_{FILE_EXT}.gif',
																'thumbnail'	=> 0,
																'thumbnail_width' => 50,
																'thumbnail_height' => 50,
																'thumbnail_crop' => 1,
																'file_size_round' => 3, 
																'file_size_space' => ' ',
																'date_format' => "%m/%d/%y",
																'set_locale' => '')
														, $_files_settings );

		$crow["acontent_template"]		= replace_tmpl_section('FILE_SETTINGS', $crow["acontent_template"]);
		$content['template_file']		= get_tmpl_section('FILE_ENTRY', $crow["acontent_template"]);
		
		if($_files_settings['set_locale']) {
			$_files_old_locale				= setlocale(LC_ALL, "0");
			setlocale(LC_ALL, $_files_settings['set_locale']);
		}
		
		$_files_count 					= count($content['files_result']);
		$_files_entries					= array();
		
		foreach($content['files'] as $key => $value) {
		
			for($_files_x = 0; $_files_x < $_files_count; $_files_x++) {
				
				$_file_current = PHPWCMS_ROOT.$phpwcms["file_path"].$content['files_result'][ $_files_x ]['f_hash'];
				if($content['files_result'][ $_files_x ]['f_ext']) {
					$_file_current .= '.'.$content['files_result'][ $_files_x ]['f_ext'];
				}
				
				// compare query result against content part file IDs
				if($content['files_result'][ $_files_x ]['f_id'] == $value['file_id'] && is_file($_file_current) ) {
					
					// check if info for the file is available
					// [0] = normal file description like before
					// [1] = name the file (it's not the file name)
					// [2] = title
					// [3] = target (where to open a new file -> default is _blank even if empty
					// [4] = if it is an image try to show a thumbnail instead of the file icon -> here thumbnail WIDTHxHEIGHT
					
					if($value['file_info']) {
					
						$_file_info = explode('|', $value['file_info']);
						
						$_file_info[0] = trim($_file_info[0]);
						$_file_info[1] = empty($_file_info[1]) ? '' : trim($_file_info[1]);
						$_file_info[2] = empty($_file_info[2]) ? '' : trim($_file_info[2]);
						$_file_info[3] = ' target="'.( empty($_file_info[3]) ? '_blank' : trim($_file_info[3]) ) .'"';
						
						// only when height/width is given
						if(empty($_file_info[4])) {
					
							$_file_info[4] = '';
					
						} else {

							$_file_info[4] = explode('x', $_file_info[4]);
							$_file_info[4][0] = intval($_file_info[4][0]);
							if(empty($_file_info[4][0])) $_file_info[4][0] = '';
							
							if(empty($_file_info[4][1])) {
								$_file_info[4][1] = '';
							} else {
								$_file_info[4][1] = intval($_file_info[4][1]);
								if(empty($_file_info[4][1])) $_file_info[4][1] = '';
							}
							$_file_info[4][2] = empty($_file_info[4][2]) ? 0 : 1;
	
							if(!($_file_info[4][0].$_file_info[4][1])) $_file_info[4] = '';
						}
					
					} else {
					
						$_file_info = array(0 => '', 1 => '', 2 => '', 3 => ' target="_blank"', 4 => '');
		
					}
					
		
					if(empty($_file_info[4]) && $_files_settings['thumbnail'] == 1) {
					
						$_file_info[4] = array(	0 => intval($_files_settings['thumbnail_width']),
												1 => intval($_files_settings['thumbnail_heighth']),
												2 => intval($_files_settings['thumbnail_crop']) ? 1 : 0
											   );
					
					}
				
					$_files_entries[$key]  = $content['template_file'];
					$_files_entries[$key]  = str_replace('{FILE_TARGET}',		$_file_info[3], $_files_entries[$key]);
					$_files_entries[$key]  = render_cnt_template($_files_entries[$key], 'FILE_EXT', $content['files_result'][ $_files_x ]['f_ext']);
					$_files_entries[$key]  = str_replace('{FILE_DOWNLOADS}',	$content['files_result'][ $_files_x ]['f_dlfinal'], $_files_entries[$key]);
					$_files_entries[$key]  = str_replace('{FILE_SIZE}', 		return_bytes_shorten($content['files_result'][ $_files_x ]['f_size'], $_files_settings['file_size_round'], $_files_settings['file_size_space']), $_files_entries[$key]);
					
					$content['files_result'][ $_files_x ]['f_created'] = intval($content['files_result'][ $_files_x ]['f_created']);
					if($content['files_result'][ $_files_x ]['f_created'] <= 0) {
						$content['files_result'][ $_files_x ]['f_created'] = filectime($_file_current);
					}
					$_files_entries[$key]  = str_replace('{FILE_DATE}', 		strftime($_files_settings['date_format'], $content['files_result'][ $_files_x ]['f_created']), $_files_entries[$key]);
					
					if($content['files_direct'] && $content['files_result'][ $_files_x ]['f_ext']) {

						$_files_entries[$key]  = str_replace('{FILE_LINK}', 	'download.php?f='.$content['files_result'][ $_files_x ]['f_hash'].'&amp;countonly=1', $_files_entries[$key]);

					} else {
						$_files_entries[$key]  = str_replace('{FILE_LINK}', 	'download.php?f='.$content['files_result'][ $_files_x ]['f_hash'], $_files_entries[$key]);
					}
					
					
					if($_file_info[1]) {
						$_files_entries[$key]  = str_replace('{FILE_NAME}', 	html_specialchars($_file_info[1]), $_files_entries[$key]);
					} else {
						$_files_entries[$key]  = str_replace('{FILE_NAME}', 	html_specialchars($content['files_result'][ $_files_x ]['f_name']), $_files_entries[$key]);
					}
					
					$_files_entries[$key]  = render_cnt_template($_files_entries[$key], 'FILE_TITLE', html_specialchars($_file_info[2]));
					$_files_entries[$key]  = render_cnt_template($_files_entries[$key], 'FILE_DESCRIPTION', html_specialchars($_file_info[0]));
					
					
					// now check file for possible thumbnail image
					$_files_image = false;
					
					if($_file_info[4] && strpos($_files_entries[$key], 'FILE_IMAGE') !== false) {
					
						// do it for jpg, png or gif only
						switch($content['files_result'][ $_files_x ]['f_ext']) {
							
							case 'gif':
							case 'jpg':
							case 'png':		
							
								$_files_image = get_cached_image(
												array(	"target_ext"	=>	$content['files_result'][ $_files_x ]['f_ext'],
														"image_name"	=>	$content['files_result'][ $_files_x ]['f_hash'] . '.' . $content['files_result'][ $_files_x ]['f_ext'],
														"max_width"		=>	$_file_info[4][0],
														"max_height"	=>	$_file_info[4][1],
														"thumb_name"	=>	md5(	$content['files_result'][ $_files_x ]['f_hash'].
																					$_file_info[4][0].$_file_info[4][1].
																					$GLOBALS['phpwcms']["sharpen_level"].
																					$_file_info[4][2]
																				),
														'crop_image'	=>	$_file_info[4][2]
												));
								break;
					
						}
					
					}
					$_files_image			= ($_files_image != false) ? PHPWCMS_IMAGES . $_files_image[0] : '';
					$_files_entries[$key]	= render_cnt_template($_files_entries[$key], 'FILE_IMAGE', $_files_image);
					
					// now replace a possible icon image
					$_files_entries[$key]  = render_cnt_template($_files_entries[$key], 'FILE_ICON', str_replace('{FILE_EXT}', $content['files_result'][ $_files_x ]['f_ext'], $_files_settings['icon_path'].$_files_settings['icon_name']));
					
					break;
				
				}
			}
			
		}

		$crow["acontent_template"] = replace_tmpl_section('FILE_ENTRY', $crow["acontent_template"], implode(LF, $_files_entries));
		$crow["acontent_template"] = render_cnt_template($crow["acontent_template"], 'TITLE', html_specialchars($crow['acontent_title']));
		$crow["acontent_template"] = render_cnt_template($crow["acontent_template"], 'SUBTITLE', html_specialchars($crow['acontent_subtitle']));
		$crow["acontent_template"] = render_cnt_template($crow["acontent_template"], 'TEXT', $crow["acontent_html"]);

		$CNT_TMP .= LF.trim($crow["acontent_template"]).LF;
		
		// reset locale settings
		if(!empty($_files_old_locale)) {
			setlocale(LC_ALL, $_files_old_locale);
		}
		
		unset($_files_count, $_files_entries, $_files_old_locale);

	}


}

							
?>