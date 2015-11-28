<?php
/**
 * phpwcms content management system
 *
 * @author Oliver Georgi <og@phpwcms.org>
 * @copyright Copyright (c) 2002-2015, Oliver Georgi
 * @license http://opensource.org/licenses/GPL-2.0 GNU GPL-2
 * @link http://www.phpwcms.de
 *
 **/

// ----------------------------------------------------------------
// obligate check for phpwcms constants
if (!defined('PHPWCMS_ROOT')) {
	die("You Cannot Access This Script Directly, Have a Nice Day.");
}
// ----------------------------------------------------------------



//file list

// if $IS_NEWS_CP = true then file list content part rendere is
// included by news content part

// default cp rendering
if( empty($IS_NEWS_CP) ) {

	$crow["acontent_files"]		= explode(':', $crow["acontent_files"]);
	$crow["acontent_text"]		= explode("\n", $crow["acontent_text"]);
	$crow["acontent_form"]		= unserialize($crow["acontent_form"]);
	$crow['file_cp_title']		= $crow['acontent_title'];
	$crow['file_cp_subtitle']	= $crow['acontent_subtitle'];
	$content['files_direct']	= empty($crow["acontent_form"]['direct_download']) ? 0 : 1;

} else {

	// news cp rendering
	// take some default values by news
	$crow["acontent_files"]		= $value['cnt_object']['cnt_files']['id'];
	$crow["acontent_text"]		= is_string($value['cnt_object']['cnt_files']['caption']) ? explode("\n", $value['cnt_object']['cnt_files']['caption']) : $value['cnt_object']['cnt_files']['caption'];
	$content['files_direct']	= $value['files_direct_download'];
	$crow["acontent_template"]	= $value['files_template'];
	$crow["acontent_html"]		= '';
	$crow['file_cp_title']		= '';
	$crow['file_cp_subtitle']	= '';

}

$content['files']			= array();
$content['files_sql']		= array();

// build file id query first
foreach($crow["acontent_files"] as $fkey => $value) {

	$value										= intval($value);

	if($value) {
		$content['files'][$fkey]['file_id']		= $value;
		$content['files'][$fkey]['file_info']	= empty($crow["acontent_text"][$fkey]) ? '' : trim($crow["acontent_text"][$fkey]);
		$content['files_sql'][$fkey]			= $value;
	}
}

// create where query part for file ID
$content['files_sql'] = is_array($content['files_sql']) && count($content['files_sql']) ? 'f_id IN(' . implode(',', $content['files_sql']).')' : '';

// if $content['files_sql'] is empty makes no sense to continue
if($content['files_sql']) {

	if(empty($content['file_static_result'][0])) {

		$content['files_sql']  = "SELECT * FROM ".DB_PREPEND."phpwcms_file WHERE f_public=1 AND f_aktiv=1 AND f_kid=1 AND f_trash=0 AND " . $content['files_sql'];
		if( !FEUSER_LOGIN_STATUS ) {
			$content['files_sql'] .= ' AND f_granted=0';
		}

		$content['files_result'] = _dbQuery($content['files_sql']);

	} else {

		$content['files_result'] = $content['file_static_result'];

	}

	if(is_array($content['files_result']) && count($content['files_result'])) {

		if($crow["acontent_template"] == 'download-inline' && !is_file(PHPWCMS_TEMPLATE.'inc_default/filelist_inline.tmpl')) {
			$crow["acontent_template"] = '';
		}

		// get filelist template
		if(empty($crow["acontent_template"]) && is_file(PHPWCMS_TEMPLATE.'inc_default/filelist.tmpl')) {

			$crow["acontent_template"] = render_device( @file_get_contents(PHPWCMS_TEMPLATE.'inc_default/filelist.tmpl') );

		} elseif($crow["acontent_template"] == 'download-inline') {

			$crow["acontent_template"] = render_device( @file_get_contents(PHPWCMS_TEMPLATE.'inc_default/filelist_inline.tmpl') );

		} elseif(is_file(PHPWCMS_TEMPLATE.'inc_cntpart/filelist/'.$crow["acontent_template"])) {

			$crow["acontent_template"] = render_device( @file_get_contents(PHPWCMS_TEMPLATE.'inc_cntpart/filelist/'.$crow["acontent_template"]) );

		} else {

			$crow["acontent_template"]  = '[TITLE]<h4>{TITLE}</h4>[/TITLE][SUBTITLE]<h5>{SUBTITLE}</h5>[/SUBTITLE][TEXT]{TEXT}[/TEXT]'.LF;
			$crow["acontent_template"] .= '<ul class="fileDownload">' . LF . '<!--FILE_ENTRY_START//-->' . LF;
			$crow["acontent_template"] .= '	<li><a href="{FILE_LINK}"{FILE_TARGET}>{FILE_NAME}</a></li>' . LF;
			$crow["acontent_template"] .= '<!--FILE_ENTRY_END//-->' . LF . '</ul>' . LF;

		}

		$_files_settings = get_tmpl_section('FILE_SETTINGS', $crow["acontent_template"]);
		$_files_settings = parse_ini_str($_files_settings, false);
		$_files_settings = array_merge(
			array(
				'icon_path' => 'img/icons/',
				'icon_name' => 'small_icon_{FILE_EXT}.gif',
				'thumbnail'	=> 0,
				'thumbnail_width' => 50,
				'thumbnail_height' => 50,
				'thumbnail_crop' => 1,
				'lightbox_init' => 0,
				'file_size_round' => 3,
				'file_size_space' => ' ',
				'date_format' => "%m/%d/%y",
				'set_locale' => ''
			),
			$_files_settings
		);

		$crow["acontent_template"]	= replace_tmpl_section('FILE_SETTINGS', $crow["acontent_template"]);
		$content['template_file']	= get_tmpl_section('FILE_ENTRY', $crow["acontent_template"]);

		if($_files_settings['set_locale']) {
			$_files_old_locale				= setlocale(LC_ALL, "0");
			setlocale(LC_ALL, $_files_settings['set_locale']);
		}
		if(!empty($_files_settings['lightbox_init'])) {
			initSlimbox();
		}

		$_files_count 					= count($content['files_result']);
		$_files_entries					= array();
		$_files_get_imagesize			= strpos($content['template_file'], '{FILE_IMAGE_') === FALSE ? false : true; // check if necessary to check for image type and sizes

		foreach($content['files'] as $fkey => $value) {

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
					// [5] = copyright information

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
												1 => intval($_files_settings['thumbnail_height']),
												2 => intval($_files_settings['thumbnail_crop']) ? 1 : 0
											   );

					}

					// language specific long description and copyright
					if($content['files_result'][ $_files_x ]['f_vars'] && count($phpwcms['allowed_lang']) > 1) {

						$content['files_result'][ $_files_x ]['f_vars'] = @unserialize($content['files_result'][ $_files_x ]['f_vars']);

						if(!empty($content['files_result'][ $_files_x ]['f_vars'][ $phpwcms['default_lang'] ]['longinfo'])) {
							$content['files_result'][ $_files_x ]['f_longinfo'] = $content['files_result'][ $_files_x ]['f_vars'][ $phpwcms['default_lang'] ]['longinfo'];
						}
						if(!empty($content['files_result'][ $_files_x ]['f_vars'][ $phpwcms['default_lang'] ]['copyright'])) {
							$content['files_result'][ $_files_x ]['f_copyright'] = $content['files_result'][ $_files_x ]['f_vars'][ $phpwcms['default_lang'] ]['copyright'];
						}

					}

					$_file_info[5] = empty($_file_info[5]) ? $content['files_result'][ $_files_x ]['f_copyright'] : trim($_file_info[5]);


					$_files_entries[$fkey]  = $content['template_file'];
					$_files_entries[$fkey]  = str_replace('{FILE_ID}', $content['files_result'][ $_files_x ]['f_id'], $_files_entries[$fkey]);
					$_files_entries[$fkey]  = str_replace('{FILE_TARGET}', $_file_info[3], $_files_entries[$fkey]);
					$_files_entries[$fkey]  = render_cnt_template($_files_entries[$fkey], 'FILE_EXT', $content['files_result'][ $_files_x ]['f_ext']);
					$_files_entries[$fkey]  = str_replace('{FILE_DOWNLOADS}', $content['files_result'][ $_files_x ]['f_dlfinal'], $_files_entries[$fkey]);
					$_files_entries[$fkey]  = str_replace('{FILE_SIZE}', return_bytes_shorten($content['files_result'][ $_files_x ]['f_size'], $_files_settings['file_size_round'], $_files_settings['file_size_space']), $_files_entries[$fkey]);

					$content['files_result'][ $_files_x ]['f_created'] = intval($content['files_result'][ $_files_x ]['f_created']);
					if($content['files_result'][ $_files_x ]['f_created'] <= 0) {
						$content['files_result'][ $_files_x ]['f_created'] = filectime($_file_current);
					}
					$_files_entries[$fkey]  = str_replace('{FILE_DATE}', strftime($_files_settings['date_format'], $content['files_result'][ $_files_x ]['f_created']), $_files_entries[$fkey]);

					if($content['files_direct'] && $content['files_result'][ $_files_x ]['f_ext']) {

						$_files_entries[$fkey]  = str_replace('{FILE_LINK}', 'download.php?f='.$content['files_result'][ $_files_x ]['f_hash'].'&amp;countonly=1', $_files_entries[$fkey]);

					} else {
						$_files_entries[$fkey]  = str_replace('{FILE_LINK}', 'download.php?f='.$content['files_result'][ $_files_x ]['f_hash'], $_files_entries[$fkey]);
					}


					if($_file_info[1]) {
						$_files_entries[$fkey]  = str_replace('{FILE_NAME}', html_specialchars($_file_info[1]), $_files_entries[$fkey]);
					} else {
						$_files_entries[$fkey]  = str_replace('{FILE_NAME}', html_specialchars($content['files_result'][ $_files_x ]['f_name']), $_files_entries[$fkey]);
					}

					$_files_entries[$fkey]  = render_cnt_template($_files_entries[$fkey], 'FILE_TITLE', html_specialchars($_file_info[2]));
					$_files_entries[$fkey]  = render_cnt_template($_files_entries[$fkey], 'FILE_LONGINFO', empty($content['files_result'][ $_files_x ]['f_longinfo']) ? '' : plaintext_htmlencode($content['files_result'][ $_files_x ]['f_longinfo']));
					$_files_entries[$fkey]  = render_cnt_template($_files_entries[$fkey], 'FILE_DESCRIPTION', html_specialchars($_file_info[0]));
					$_files_entries[$fkey]  = render_cnt_template($_files_entries[$fkey], 'FILE_COPYRIGHT', html_specialchars($_file_info[5]));

					// now check file for possible thumbnail image
					$_files_image = false;

					if($_file_info[4] && strpos($_files_entries[$fkey], 'FILE_IMAGE') !== false) {

						$target_ext = $content['files_result'][ $_files_x ]['f_ext'];

						// do it for jpg, png or gif only
						switch($content['files_result'][ $_files_x ]['f_ext']) {

							case 'tif':
							case 'tiff':
							case 'pdf':
							case 'psd':
							case 'eps':		if($GLOBALS['phpwcms']['image_library'] == 'gd2' || $GLOBALS['phpwcms']['image_library'] == 'gd') {
												break;
											}

							case 'bmp':		$target_ext = 'jpg';

							case 'gif':
							case 'jpg':
							case 'png':

								$_files_image = get_cached_image(array(
									"target_ext"	=>	$target_ext,
									"image_name"	=>	$content['files_result'][ $_files_x ]['f_hash'] . '.' . $content['files_result'][ $_files_x ]['f_ext'],
									"max_width"		=>	$_file_info[4][0],
									"max_height"	=>	$_file_info[4][1],
									"thumb_name"	=>	md5($content['files_result'][ $_files_x ]['f_hash'].$_file_info[4][0].$_file_info[4][1].$phpwcms["sharpen_level"].$_file_info[4][2].$phpwcms['colorspace']),
									'crop_image'	=>	$_file_info[4][2]
								));
								break;

						}

					}

					// render {FILE_IMAGE_%} RT
					if($_files_get_imagesize) {

							$_files_get_imagesize = @getimagesize(PHPWCMS_ROOT.'/'.PHPWCMS_FILES.$content['files_result'][$_files_x ]['f_hash'].'.'.$content['files_result'][ $_files_x ]['f_ext']);

							if(isset($_files_get_imagesize[0])) {

								$_files_entries[$fkey]	= render_cnt_template($_files_entries[$fkey], 'FILE_IMAGE_WIDTH', $_files_get_imagesize[0]);
								$_files_entries[$fkey]	= render_cnt_template($_files_entries[$fkey], 'FILE_IMAGE_HEIGHT', $_files_get_imagesize[1]);
								$_files_entries[$fkey]	= render_cnt_template($_files_entries[$fkey], 'FILE_IMAGE_MIME', isset($_files_get_imagesize['mime']) ? $_files_get_imagesize['mime'] : '');
								if(isset($_files_get_imagesize['channels'])) {
									switch($_files_get_imagesize['channels']) {
										case 3:		$_files_get_imagesize['channels'] = 'RGB';	break;
										case 4:		$_files_get_imagesize['channels'] = 'CMYK';	break;
										default:	$_files_get_imagesize['channels'] = '@@unknown@@';
									}
								} else {
									$_files_get_imagesize['channels'] = '';
								}

								$_files_entries[$fkey]	= render_cnt_template($_files_entries[$fkey], 'FILE_IMAGE_CHANNEL', $_files_get_imagesize['channels']);
								$_files_entries[$fkey]	= render_cnt_template($_files_entries[$fkey], 'FILE_IMAGE_LANDSCAPE', $_files_get_imagesize[0] >= $_files_get_imagesize[1] ? '@@landscape@@' : '');
								$_files_entries[$fkey]	= render_cnt_template($_files_entries[$fkey], 'FILE_IMAGE_PORTRAIT', $_files_get_imagesize[1] > $_files_get_imagesize[0] ? '@@portrait@@' : '');

							} else {

								$_files_entries[$fkey]	= render_cnt_template($_files_entries[$fkey], 'FILE_IMAGE_WIDTH', '');
								$_files_entries[$fkey]	= render_cnt_template($_files_entries[$fkey], 'FILE_IMAGE_HEIGHT', '');
								$_files_entries[$fkey]	= render_cnt_template($_files_entries[$fkey], 'FILE_IMAGE_MIME', '');
								$_files_entries[$fkey]	= render_cnt_template($_files_entries[$fkey], 'FILE_IMAGE_CHANNEL', '');
								$_files_entries[$fkey]	= render_cnt_template($_files_entries[$fkey], 'FILE_IMAGE_LANDSCAPE', '');
								$_files_entries[$fkey]	= render_cnt_template($_files_entries[$fkey], 'FILE_IMAGE_PORTRAIT', '');
							}

					} else {

						$_files_entries[$fkey]	= render_cnt_template($_files_entries[$fkey], 'FILE_IMAGE_WIDTH', '');
						$_files_entries[$fkey]	= render_cnt_template($_files_entries[$fkey], 'FILE_IMAGE_HEIGHT', '');
						$_files_entries[$fkey]	= render_cnt_template($_files_entries[$fkey], 'FILE_IMAGE_MIME', '');
						$_files_entries[$fkey]	= render_cnt_template($_files_entries[$fkey], 'FILE_IMAGE_CHANNEL', '');
						$_files_entries[$fkey]	= render_cnt_template($_files_entries[$fkey], 'FILE_IMAGE_LANDSCAPE', '');
						$_files_entries[$fkey]	= render_cnt_template($_files_entries[$fkey], 'FILE_IMAGE_PORTRAIT', '');

					}

					$_files_image			= ($_files_image != false) ? PHPWCMS_IMAGES . $_files_image[0] : '';
					$_files_entries[$fkey]	= render_cnt_template($_files_entries[$fkey], 'FILE_IMAGE', $_files_image);

					// now replace a possible icon image
					$_files_entries[$fkey]  = render_cnt_template($_files_entries[$fkey], 'FILE_ICON', str_replace('{FILE_EXT}', $content['files_result'][ $_files_x ]['f_ext'], $_files_settings['icon_path'].$_files_settings['icon_name']));

					break;

				}
			}

		}

		$crow["acontent_template"] = replace_tmpl_section('FILE_ENTRY', $crow["acontent_template"], implode(LF, $_files_entries));
		$crow["acontent_template"] = render_cnt_template($crow["acontent_template"], 'TITLE', html_specialchars($crow['file_cp_title']));
		$crow["acontent_template"] = render_cnt_template($crow["acontent_template"], 'SUBTITLE', html_specialchars($crow['file_cp_subtitle']));
		$crow["acontent_template"] = render_cnt_template($crow["acontent_template"], 'TEXT', $crow["acontent_html"]);

		// cleanup left over FILE_IMAGE sections
		$crow["acontent_template"] = replace_cnt_template($crow["acontent_template"], 'FILE_IMAGE', '');

		// return result
		if( empty($IS_NEWS_CP) ) {
			$CNT_TMP .= LF.trim($crow["acontent_template"]).LF;
		} else {
			$news['files_result'] = trim($crow["acontent_template"]);
		}

		// reset locale settings
		if(!empty($_files_old_locale)) {
			setlocale(LC_ALL, $_files_old_locale);
		}

		unset($_files_count, $_files_entries, $_files_old_locale);

	}

}


?>