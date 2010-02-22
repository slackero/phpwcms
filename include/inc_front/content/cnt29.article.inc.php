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
if(empty($crow["acontent_template"]) && is_file(PHPWCMS_TEMPLATE.'inc_default/images.tmpl')) {

	$image['template']	= @file_get_contents(PHPWCMS_TEMPLATE.'inc_default/images.tmpl');
	
} elseif(is_file(PHPWCMS_TEMPLATE.'inc_cntpart/images/'.$crow["acontent_template"])) {

	$image['template']	= @file_get_contents(PHPWCMS_TEMPLATE.'inc_cntpart/images/'.$crow["acontent_template"]);

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
		
		if(empty($image['center_image'])) {
			$image['center_image'] = 0;
		}
		
		$image['crop'] = empty($image['crop']) ? 0 : 1;
		
		switch($image['center_image']) {
		
			case 1:		// center hor/vert
						if(!$image['width'] && !$image['height']) {
							$image['center_image'] = 0;
						} elseif(!$image['width']) {
							$image['center_image'] = 3;
						} elseif(!$image['height']) {
							$image['center_image'] = 2;
						}
						break;
						
			case 2:		// center hor
						if(!$image['width']) {
							$image['center_image'] = 0;
						}
						break;
			
			case 3:		// center vert
						if(!$image['height']) {
							$image['center_image'] = 0;
						}
						break;
			
			default:	$image['center_image'] = 0;
		
		
		}
		
		$x   = 0;
		$col = 0;

		foreach($image['images'] as $key => $value) {
		
			$col++;
			
			// put spacer content between images
			if($col > 1) {
			
				$image['tmpl_images'][$x] .= $image['tmpl_entry_space'];
			
			} else {
			
				$image['tmpl_images'][$x]  = '';
			
			}

			$thumb_image = get_cached_image(
						array(	"target_ext"	=>	$image['images'][$key][3],
								"image_name"	=>	$image['images'][$key][2] . '.' . $image['images'][$key][3],
								"max_width"		=>	$image['images'][$key][4],
								"max_height"	=>	$image['images'][$key][5],
								"thumb_name"	=>	md5(	$image['images'][$key][2].$image['images'][$key][4].
															$image['images'][$key][5].$phpwcms["sharpen_level"].
															$image['crop']
														),
								'crop_image'	=>	$image['crop']
        					  )
						);

			if($image['zoom']) {

				$zoominfo = get_cached_image(
						array(	"target_ext"	=>	$image['images'][$key][3],
								"image_name"	=>	$image['images'][$key][2] . '.' . $image['images'][$key][3],
								"max_width"		=>	$phpwcms["img_prev_width"],
								"max_height"	=>	$phpwcms["img_prev_height"],
								"thumb_name"	=>	md5(	$image['images'][$key][2].$phpwcms["img_prev_width"].
															$phpwcms["img_prev_height"].$phpwcms["sharpen_level"]
														)
        					  )
						);
			}

			// now try to build caption and if neccessary add alt to image or set external link for image
			$caption	= getImageCaption($image['images'][$key][6]);
			// set caption and ALT Image Text for imagelist
			$capt_cur	= html_specialchars($caption[0]);
			$caption[3] = empty($caption[3]) ? '' : ' title="'.html_specialchars($caption[3]).'"'; //title
			$caption[1] = html_specialchars(empty($caption[1]) ? $image['images'][$key][1] : $caption[1]);

			$list_img_temp  = '<img src="'.PHPWCMS_IMAGES.$thumb_image[0].'" ';
			
			$img_thumb_name		= $thumb_image[0];
			$img_thumb_rel		= PHPWCMS_IMAGES.$thumb_image[0];
			$img_thumb_abs		= PHPWCMS_URL.PHPWCMS_IMAGES.$thumb_image[0];
			$img_thumb_width	= $thumb_image[1];
			$img_thumb_height	= $thumb_image[2];
			
			if($image['center_image']) {
			
				$img_margin_left	= 0;
				$img_margin_right	= 0;
				$img_margin_top		= 0;
				$img_margin_bottom	= 0;

				// center hor/vert
				if($image['center_image'] == 1 || $image['center_image'] == 2) {
					$img_margin_left	= ceil( ($image['width'] - $thumb_image[1]) / 2 );				
					$img_margin_right	= $image['width'] - $thumb_image[1] - $img_margin_left;
				}
				if($image['center_image'] == 1 || $image['center_image'] == 3) {
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
				
				if(!$image['lightbox'] || $caption[2][0]) {
				
					$img_a .= '<a href="'.$open_link."\" onclick=\"checkClickZoom();clickZoom('".$open_popup_link."','previewpic','width=";
					$img_a .= $zoominfo[1].",height=".$zoominfo[2]."');".$return_false.'"'.$caption[2][1];
					$img_a .= $list_ahref_style.'>';
					
				} else {
				
					// lightbox
					$img_a .= '<a href="'.PHPWCMS_IMAGES.$zoominfo[0].'" rel="lightbox['.$image['lightbox'].']" ';
					if($capt_cur) {
						$img_a .= 'title="'.parseLightboxCaption($capt_cur).'" ';
					} elseif(strpos($image['tmpl_entry'], '{IMGNAME}')) {
						$img_a .= 'title="'.parseLightboxCaption( $image['images'][$key][1] ).'" ';
					}
					
					$img_a .= $list_ahref_style.'target="_blank">';
				
				}
				
				$img_a .= $list_img_temp.'</a>';
				
				$img_zoom_name		= $zoominfo[0];
				$img_zoom_rel		= PHPWCMS_IMAGES.$zoominfo[0];
				$img_zoom_abs		= PHPWCMS_URL.PHPWCMS_IMAGES.$zoominfo[0];
				$img_zoom_width		= $zoominfo[1];
				$img_zoom_height	= $zoominfo[2];
				
			} else {
				// if not click enlarge
				if($caption[2][0]) {
					$img_a .= '<a href="'.$caption[2][0].'" '.$list_ahref_style.$caption[2][1].'>'.$list_img_temp.'</a>';
				} else {
					$img_a .= $list_img_temp;
				}
				
				$img_zoom_name		= '';
				$img_zoom_rel		= '';
				$img_zoom_abs		= '';
				$img_zoom_width		= 0;
				$img_zoom_height	= 0;
			}
			
			$img_a = str_replace('{IMAGE}', $img_a, $image['tmpl_entry']);
			$img_a = str_replace('{IMGID}', $image['images'][$key][0], $img_a);
			$img_a = str_replace('{IMAGE_ID}', $image['images'][$key][0], $img_a);
			$img_a = str_replace('{IMAGE_HASH}', $image['images'][$key][2], $img_a);
			$img_a = str_replace('{IMGNAME}', html_specialchars($image['images'][$key][1]), $img_a);
			
			// replace thumbnail and zoom image information
			$img_a = str_replace('{THUMB_NAME}',	$img_thumb_name, $img_a);
			$img_a = str_replace('{THUMB_REL}',		$img_thumb_rel, $img_a);
			$img_a = str_replace('{THUMB_ABS}',		$img_thumb_abs, $img_a);
			$img_a = str_replace('{THUMB_WIDTH}',	$img_thumb_width, $img_a);
			$img_a = str_replace('{THUMB_HEIGHT}',	$img_thumb_height, $img_a);
			
			$image['tmpl_thumb_width_max']	= max($image['tmpl_thumb_width_max'], $img_thumb_width);
			$image['tmpl_thumb_height_max']	= max($image['tmpl_thumb_height_max'], $img_thumb_height);
			
			$img_a = str_replace('{IMAGE_NAME}',	$img_zoom_name, $img_a);
			$img_a = str_replace('{IMAGE_REL}',		$img_zoom_rel, $img_a);
			$img_a = str_replace('{IMAGE_ABS}',		$img_zoom_abs, $img_a);
			$img_a = str_replace('{IMAGE_WIDTH}',	$img_zoom_width, $img_a);
			$img_a = str_replace('{IMAGE_HEIGHT}',	$img_zoom_height, $img_a);
			
			$img_a = render_cnt_template($img_a, 'ZOOM', ($img_zoom_name ? '<!-- Zoomed -->' : '') );
			$img_a = render_cnt_template($img_a, 'COPYRIGHT', $caption[4] );
			
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
	$image['template']  = render_cnt_template($image['template'], 'TEXT', $crow['acontent_text']);
	
	$CNT_TMP .= $image['template'];

}

unset($image);

?>