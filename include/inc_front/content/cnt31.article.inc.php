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

// 2009-07-22 Thumbnail max width and max height replacement tag implemented
//            Thanks to Gerd Müller for proposal and code sample

// ----------------------------------------------------------------
// obligate check for phpwcms constants
if (!defined('PHPWCMS_ROOT')) {
   die("You Cannot Access This Script Directly, Have a Nice Day.");
}
// ----------------------------------------------------------------


//images (gallery)

$image	= @unserialize($crow["acontent_form"]);

// read template
if(empty($crow["acontent_template"]) && is_file(PHPWCMS_TEMPLATE.'inc_default/imagespecial.tmpl')) {

	$image['template']	= @file_get_contents(PHPWCMS_TEMPLATE.'inc_default/imagespecial.tmpl');
	
} elseif(is_file(PHPWCMS_TEMPLATE.'inc_cntpart/imagespecial/'.$crow["acontent_template"])) {

	$image['template']	= @file_get_contents(PHPWCMS_TEMPLATE.'inc_cntpart/imagespecial/'.$crow["acontent_template"]);

} else {

	$image['template']	= '';

}

if($image['template']) {

	$image['tmpl_header']			= get_tmpl_section('IMAGES_HEADER', $image['template']);
	$image['tmpl_footer']			= get_tmpl_section('IMAGES_FOOTER', $image['template']);
	$image['tmpl_entry']			= get_tmpl_section('IMAGES_ENTRY', $image['template']);
	$image['tmpl_entry_space']		= get_tmpl_section('IMAGES_ENTRY_SPACER', $image['template']);
	$image['tmpl_row_space']		= get_tmpl_section('IMAGES_ROW_SPACER', $image['template']);
	$image['tmpl_thumb_width_max']	= 0;
	$image['tmpl_thumb_height_max']	= 0;
	$image['tmpl_images']			= array();
	
	$image['template']  = $image['tmpl_header'];

	if(is_array($image['images']) && ($image['count'] = count($image['images']))) {

		// Start lightbox
		if(empty($image['lightbox'])) {
			$image['lightbox'] = false;
		} else {
			initSlimbox();
			$image['lightbox'] = generic_string(5);
		}
		
		switch($image['center']) {
		
			case 1:		// center hor/vert
						if(!$image['width'] && !$image['height']) {
							$image['center'] = 0;
						} elseif(!$image['width']) {
							$image['center'] = 3;
						} elseif(!$image['height']) {
							$image['center'] = 2;
						}
						break;
						
			case 2:		// center hor
						if(!$image['width']) {
							$image['center'] = 0;
						}
						break;
			
			case 3:		// center vert
						if(!$image['height']) {
							$image['center'] = 0;
						}
						break;
			
			default:	$image['center'] = 0;
		
		
		}
		
		$x   = 0;
		$col = 0;

		foreach($image['images'] as $key => $value) {
		
			$thumb_image		= false;
			$zoominfo			= false;
			
			if($value['thumb_hash']) {

				$thumb_image = get_cached_image(
							array(	"target_ext"	=>	$value['thumb_ext'],
									"image_name"	=>	$value['thumb_hash'] . '.' . $value['thumb_ext'],
									"max_width"		=>	$image['width'],
									"max_height"	=>	$image['height'],
									"thumb_name"	=>	md5(	$value['thumb_hash'].$image['width'].
																$image['height'].$phpwcms["sharpen_level"].
																$image['crop']
															),
									'crop_image'	=>	$image['crop']
								  )
							);
			}
			
			if(!$value['thumb_hash'] || !$thumb_image) {
				continue;
			}
			
			$img_zoom_id		= '';
			$img_zoom_hash		= '';
			$img_zoom_ext		= '';
			$img_zoom_name		= '';
			$img_zoom_filename	= '';
			$img_zoom_rel		= '';
			$img_zoom_abs		= '';
			$img_zoom_width		= '';
			$img_zoom_height	= '';
			
			$col++;
			
			// put spacer content between images
			if($col > 1) {
			
				$image['tmpl_images'][$x] .= $image['tmpl_entry_space'];
			
			} else {
			
				$image['tmpl_images'][$x]  = '';
			
			}

			if($value['zoom_hash'] && $image['zoom']) {

				$zoominfo = get_cached_image(
						array(	"target_ext"	=>	$value['zoom_ext'],
								"image_name"	=>	$value['zoom_hash'] . '.' . $value['zoom_ext'],
								"max_width"		=>	$image['width_zoom'],
								"max_height"	=>	$image['height_zoom'],
								"thumb_name"	=>	md5(	$value['zoom_hash'].$image['width_zoom'].
															$image['height_zoom'].$phpwcms["sharpen_level"].
															$image['crop_zoom']
														),
								'crop_image'	=>	$image['crop_zoom']
        					  )
						);
			}

			// set caption and ALT Image Text for imagelist
			$capt_cur			= html_specialchars($value['caption']);
			$caption			= array();
			$caption[1] 		= html_specialchars($value['thumb_name']);
			$caption[2]			= explode(' ', $value['url']);
			$caption[2][1]		= empty($caption[2][1]) ? '' : ' target="'.$caption[2][1].'"';
			$caption[3] 		= empty($value['caption']) ? '' : ' title="'.$capt_cur.'"'; //title

			$list_img_temp  	= '<img src="'.PHPWCMS_IMAGES.$thumb_image[0].'" ';
			
			$img_thumb_name		= $thumb_image[0];
			$img_thumb_rel		= PHPWCMS_IMAGES.$thumb_image[0];
			$img_thumb_abs		= PHPWCMS_URL.PHPWCMS_IMAGES.$thumb_image[0];
			$img_thumb_width	= $thumb_image[1];
			$img_thumb_height	= $thumb_image[2];
			$img_thumb_filename	= $value['thumb_name'];
			
			if($image['center']) {
			
				$img_margin_left	= 0;
				$img_margin_right	= 0;
				$img_margin_top		= 0;
				$img_margin_bottom	= 0;

				// center hor/vert
				if($image['center'] == 1 || $image['center'] == 2) {
					$img_margin_left	= ceil( ($image['width'] - $thumb_image[1]) / 2 );				
					$img_margin_right	= $image['width'] - $thumb_image[1] - $img_margin_left;
				}
				if($image['center'] == 1 || $image['center'] == 3) {
					$img_margin_top		= ceil( ($image['height'] - $thumb_image[2]) / 2 );
					$img_margin_bottom	= $image['height'] - $thumb_image[2] - $img_margin_top;
				}
				
				$list_img_style		= 'style="margin:'.$img_margin_top.'px '.$img_margin_right.'px '.$img_margin_bottom.'px '.$img_margin_left.'px;" ';
				$list_ahref_style	= '';
				$list_img_temp	   .= $list_img_style;
				
			} else {
				$list_img_style		= '';
				$list_ahref_style	= '';
			}
			$list_img_temp .= $thumb_image[3].' alt="'.$caption[1].'"'.$caption[3].' border="0" />';
			$img_a			= '';
			$lightbox_capt  = '';

			if($image['zoom'] && isset($zoominfo) && $zoominfo != false) {
				// if click enlarge the image
				$open_popup_link = 'image_zoom.php?'.getClickZoomImageParameter($zoominfo[0].'?'.$zoominfo[3]);
				if($caption[2][0]) {
					$open_link = $caption[2][0];
					$return_false = '';
				} else {
					$open_link = $open_popup_link;
					$return_false = 'return false;';
				}
				
				if($image['lightbox'] && $capt_cur) {
					$lightbox_capt = 'title="'.parseLightboxCaption($capt_cur).'" ';
				}
				
				if(!$image['lightbox'] || $caption[2][0]) {
				
					$img_a .= '<a href="'.$open_link."\" onclick=\"checkClickZoom();clickZoom('".$open_popup_link."','previewpic','width=";
					$img_a .= $zoominfo[1].",height=".$zoominfo[2]."');".$return_false.'"'.$caption[2][1];
					$img_a .= $list_ahref_style.'>';
					
				} else {
				
					// lightbox
					$img_a .= '<a href="'.PHPWCMS_IMAGES.$zoominfo[0].'" rel="lightbox['.$image['lightbox'].']" ';
					$img_a .= $lightbox_capt;
					$img_a .= $list_ahref_style.'target="_blank">';
				
				}
				
				$img_a .= $list_img_temp.'</a>';
				
				$img_zoom_id		= $value['zoom_id'];
				$img_zoom_hash		= $value['zoom_hash'];
				$img_zoom_ext		= $value['zoom_ext'];
				$img_zoom_name		= $zoominfo[0];
				$img_zoom_rel		= PHPWCMS_IMAGES.$zoominfo[0];
				$img_zoom_abs		= PHPWCMS_URL.PHPWCMS_IMAGES.$zoominfo[0];
				$img_zoom_width		= $zoominfo[1];
				$img_zoom_height	= $zoominfo[2];
				$img_zoom_filename	= $value['zoom_name'];
				
			} else {
				// if not click enlarge
				if($caption[2][0]) {
					$img_a .= '<a href="'.$caption[2][0].'" '.$list_ahref_style.$caption[2][1].'>'.$list_img_temp.'</a>';
				} else {
					$img_a .= $list_img_temp;
				}
			}
			
			$img_a = str_replace('{IMAGE}', $img_a, $image['tmpl_entry']);
			$img_a = str_replace('{IMGID}', $key, $img_a);
			$img_a = str_replace('{IMGNAME}', html_specialchars($image['images'][$key]['thumb_name']), $img_a);
		
			// replace thumbnail and zoom image information
			$img_a = str_replace('{THUMB_ID}',			$value['thumb_id'], $img_a);
			$img_a = str_replace('{THUMB_HASH}',		$value['thumb_hash'], $img_a);
			$img_a = str_replace('{THUMB_EXT}',			$value['thumb_ext'], $img_a);
			$img_a = str_replace('{THUMB_NAME}',		$img_thumb_name, $img_a);
			$img_a = str_replace('{THUMB_FILENAME}',	$img_thumb_filename, $img_a);
			$img_a = str_replace('{THUMB_REL}',			$img_thumb_rel, $img_a);
			$img_a = str_replace('{THUMB_ABS}',			$img_thumb_abs, $img_a);
			$img_a = str_replace('{THUMB_WIDTH}',		$img_thumb_width, $img_a);
			$img_a = str_replace('{THUMB_HEIGHT}',		$img_thumb_height, $img_a);
			
			$image['tmpl_thumb_width_max']	= max($image['tmpl_thumb_width_max'], $img_thumb_width);
			$image['tmpl_thumb_height_max']	= max($image['tmpl_thumb_height_max'], $img_thumb_height);
			
			$img_a = str_replace('{IMAGE_ID}',			$img_zoom_id, $img_a);
			$img_a = str_replace('{IMAGE_HASH}',		$img_zoom_hash, $img_a);
			$img_a = str_replace('{IMAGE_EXT}',			$img_zoom_ext, $img_a);
			$img_a = str_replace('{IMAGE_NAME}',		$img_zoom_name, $img_a);
			$img_a = str_replace('{IMAGE_FILENAME}',	$img_zoom_filename, $img_a);
			$img_a = str_replace('{IMAGE_REL}',			$img_zoom_rel, $img_a);
			$img_a = str_replace('{IMAGE_ABS}',			$img_zoom_abs, $img_a);
			$img_a = str_replace('{IMAGE_WIDTH}',		$img_zoom_width, $img_a);
			$img_a = str_replace('{IMAGE_HEIGHT}',		$img_zoom_height, $img_a);
			
			$img_a = render_cnt_template($img_a, 'IMAGE_URL', $caption[2][0]);
			$img_a = str_replace('{IMAGE_TARGET}',	$caption[2][1], $img_a);
			$img_a = str_replace('{LIGHTBOX}',	($image['lightbox'] !== false ? ' rel="lightbox['.$image['lightbox'].']"' : '' ), $img_a);
			$img_a = str_replace('{LIGHTBOX_CAPTION}', $lightbox_capt, $img_a);
			
			$img_a = render_cnt_template($img_a, 'ZOOM', ($img_zoom_name ? '<!-- Zoomed -->' : '') );
			$img_a = render_cnt_template($img_a, 'COPYRIGHT', $caption[4] );
			
			// new freetext value
			$value['freetext'] = empty($value['freetext']) ? '' : trim($value['freetext']);
			$img_a = render_cnt_template($img_a, 'INFOTEXT', plaintext_htmlencode($value['freetext'], 'html_entities') );
			$img_a = render_cnt_template($img_a, 'INFOHTML', $value['freetext'] );
			
			if($image['nocaption']) {
				$img_a = render_cnt_template($img_a, 'CAPTION_ELSE', '');
				$img_a = render_cnt_template($img_a, 'CAPTION', '');
			} else {
				$img_a = render_cnt_template($img_a, 'CAPTION', $capt_cur);
			}
			
			$image['tmpl_images'][$x] .= $img_a;
			
			// check if this is the last image in row
			if($image['col'] == $col) {
				$x++;
				$col = 0;
			}
			
		}
		
		$image['template'] .= implode($image['tmpl_row_space'], $image['tmpl_images']);
	
	}
	
	$image['template'] .= $image['tmpl_footer'];
	
	// now do main replacements
	$image['template']  = str_replace('{ID}', $crow['acontent_id'], $image['template']);
	$image['template']  = str_replace('{SPACE}', $image['space'], $image['template']);
	$image['template']  = str_replace('{THUMB_WIDTH_MAX}', $image['tmpl_thumb_width_max'], $image['template']);
	$image['template']  = str_replace('{THUMB_HEIGHT_MAX}', $image['tmpl_thumb_height_max'], $image['template']);
	$image['template']  = str_replace('{THUMB_COLUMNS}', $image['col'], $image['template']);
	$image['template']  = render_cnt_template($image['template'], 'TITLE', html_specialchars($crow['acontent_title']));
	$image['template']  = render_cnt_template($image['template'], 'SUBTITLE', html_specialchars($crow['acontent_subtitle']));
	$image['template']  = render_cnt_template($image['template'], 'TEXT', $crow['acontent_html']);
	$image['template']  = render_cnt_template($image['template'], 'EFFECT_1', ($image['fx1'] ? '<!-- FX 1 -->' : '') );
	$image['template']  = render_cnt_template($image['template'], 'EFFECT_2', ($image['fx2'] ? '<!-- FX 2 -->' : '') );
	$image['template']  = render_cnt_template($image['template'], 'EFFECT_3', ($image['fx3'] ? '<!-- FX 3 -->' : '') );
	
	$CNT_TMP .= $image['template'];

}

unset($image);

?>