<?php
/*************************************************************************************
   Copyright notice

   (c) 2002-2007 Oliver Georgi (oliver@phpwcms.de) // All rights reserved.

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

// image rendering functions
// moved away from front

function imagetable(& $phpwcms, & $image, $rand="0:0:0:0", $align=0) {
	// creates the image tags if text w/image
	// 0   :1       :2   :3        :4    :5     :6      :7       :8
	// dbid:filename:hash:extension:width:height:caption:position:zoom
	
	$cnt_image_lightbox = empty($GLOBALS['cnt_image_lightbox']) ? 0 : 1;
	$crop = empty($image['crop']) ? 0 : 1;

	$thumb_image = get_cached_image(
						array(	"target_ext"	=>	$image[3],
								"image_name"	=>	$image[2] . '.' . $image[3],
								"max_width"		=>	$image[4],
								"max_height"	=>	$image[5],
								"thumb_name"	=>	md5($image[2].$image[4].$image[5].$phpwcms["sharpen_level"].$crop),
								'crop_image'	=>	$crop
        					  )
						);

	if($image[8]) {

		$zoominfo = get_cached_image(
						array(	"target_ext"	=>	$image[3],
								"image_name"	=>	$image[2] . '.' . $image[3],
								"max_width"		=>	$phpwcms["img_prev_width"],
								"max_height"	=>	$phpwcms["img_prev_height"],
								"thumb_name"	=>	md5($image[2].$phpwcms["img_prev_width"].$phpwcms["img_prev_height"].$phpwcms["sharpen_level"])
        					  )
						);

		if($zoominfo == false) $image[8] = 0;

	}

	$table = '';

	if($thumb_image != false) {

		// read content image info
		$table_class 	= $GLOBALS["template_default"]["article"]["image_table_class"];
		$table_class	= ($table_class) ? ' class="'.$table_class.'"' : '';
		$table_bgcolor 	= $GLOBALS["template_default"]["article"]["image_table_bgcolor"];
		$table_bgcolor	= ($table_bgcolor) ? ' bgcolor="'.$table_bgcolor.'"' : '';
		$image_align	= $GLOBALS["template_default"]["article"]["image_align"];
		$image_align	= ($image_align) ? ' align="'.$image_align.'"' : '';
		$image_valign	= $GLOBALS["template_default"]["article"]["image_valign"];
		$image_valign	= ($image_valign) ? ' valign="'.$image_valign.'"' : '';
		$image_border	= ' border="'.intval($GLOBALS["template_default"]["article"]["image_border"]).'"';
		$image_imgclass	= $GLOBALS["template_default"]["article"]["image_imgclass"];
		$image_imgclass	= ($image_imgclass) ? ' class="'.$image_imgclass.'"' : '';
		$image_class 	= $GLOBALS["template_default"]["article"]["image_class"];
		$image_class	= ($image_class) ? ' class="'.$image_class.'"' : '';
		$image_bgcolor 	= $GLOBALS["template_default"]["article"]["image_bgcolor"];
		$image_bgcolor	= ($image_bgcolor) ? ' bgcolor="'.$image_bgcolor.'"' : '';
		$caption_class 	= $GLOBALS["template_default"]["article"]["image_caption_class"];
		$caption_class	= ($caption_class) ? ' class="'.$caption_class.'"' : '';
		$caption_bgcolor= $GLOBALS["template_default"]["article"]["image_caption_bgcolor"];
		$caption_bgcolor= ($caption_bgcolor) ? ' bgcolor="'.$caption_bgcolor.'"' : '';
		$caption_valign	= $GLOBALS["template_default"]["article"]["image_caption_valign"];
		$caption_valign	= ($caption_valign) ? ' valign="'.$caption_valign.'"' : '';
		$caption_align	= $GLOBALS["template_default"]["article"]["image_caption_align"];
		$caption_align	= ($caption_align) ? ' align="'.$caption_align.'"' : '';
		$capt_before 	= $GLOBALS["template_default"]["article"]["image_caption_before"];
		$capt_after 	= $GLOBALS["template_default"]["article"]["image_caption_after"];

		// image caption
		//$caption	= explode('|', base64_decode($image[6]));
		$caption = getImageCaption(base64_decode($image[6]));
		$caption[0]	= html_specialchars($caption[0]);
		$caption[3] = empty($caption[3]) ? '' : ' title="'.html_specialchars($caption[3]).'"'; //title
		$caption[1] = empty($caption[1]) ? html_specialchars($image[1]) : html_specialchars($caption[1]);

		// image source
		$img  = '<img src="'.PHPWCMS_IMAGES.$thumb_image[0].'" '.$thumb_image[3];
		$img .= $image_border.$image_imgclass.' alt="'.$caption[1].'"'.$caption[3].' />';

		$tablewidth = $thumb_image[1];

		// spaces around image table
		$rand = explode(":", $rand);
		if(is_array($rand) && count($rand)) {
			foreach($rand as $key => $value) {
				$rand[$key] = intval($value);
			}
		} else {
			$rand = array(0,0,0,0);
		}
		if($rand[2] && $rand[3]) {
			$colspan = ' colspan="3"';
		} else {
			if($rand[2] || $rand[3]) {
				$colspan = ' colspan="2"';
			} else {
				$colspan = '';
			}
		}
		$tablewidth += $rand[2] + $rand[3];

		$table .= '<table width="'.$tablewidth.'" border="0" cellspacing="0" cellpadding="0" ';
		$table .= ($align) ? 'align="'.$align.'"' : '';
		$table .= $table_bgcolor.$table_class.">\n";
		$table .= ($rand[0]) ? '<tr><td'.$colspan.'>'.spacer(1,$rand[0])."</td></tr>\n" : '';
		$table .= '<tr>';
		$table .= ($rand[2]) ? '<td>'.spacer($rand[2],1).'</td>' : '';
		if($image[8]) {

			$open_popup_link = 'image_zoom.php?'.getClickZoomImageParameter($zoominfo[0].'?'.$zoominfo[3]);
			$table .= '<td'.$image_align.$image_valign.$image_bgcolor.$image_class.">";
			if($caption[2][0]) {
				$open_link = $caption[2][0];
				$return_false = '';
			} else {
				$open_link = $open_popup_link;
				$return_false = 'return false;';
			}
			
			if(!$cnt_image_lightbox || $caption[2][0]) {
			
				$table .= "<a href=\"".$open_link."\" onclick=\"checkClickZoom();clickZoom('".$open_popup_link."','previewpic','width=";
				$table .= $zoominfo[1].",height=".$zoominfo[2]."');".$return_false."\"".$caption[2][1].">";
				
			} else {
			
				$table .= '<a href="'.PHPWCMS_IMAGES.$zoominfo[0].'" rel="lightbox"';
				if($caption[0]) {
					$table .= ' title="'.parseLightboxCaption($caption[0]).'"';
				}
				$table .= ' target="_blank">';
			
			}
			$table .= $img.'</a></td>';
		} else {
			$table .= '<td'.$image_align.$image_valign.$image_bgcolor.$image_class.">";
			if($caption[2][0]) {
				$table .= '<a href="'.$caption[2][0].'"'.$caption[2][1].'>'.$img.'</a>';
			} else {
				$table .= $img;
			}
			$table .= '</td>';
		}
		$table .= ($rand[3]) ? "<td>".spacer($rand[3],1)."</td>" : "";
		$table .= "</tr>\n";
		if($caption[0] && empty($image['nocaption'])) {
			$table .= "<tr>";
			$table .= ($rand[2]) ? "<td>".spacer($rand[2],1)."</td>" : "";
			$table .= '<td'.$caption_valign.$caption_align.$caption_bgcolor.$caption_class.'>'.$capt_before.$caption[0].$capt_after."</td>";
			$table .= ($rand[3]) ? "<td>".spacer($rand[3],1)."</td>" : "";
			$table .= "</tr>\n";
		}
		$table .= ($rand[1]) ? "<tr><td".$colspan.">".spacer(1,$rand[1])."</td></tr>\n" : "";
		$table .= "</table>";

	}

	return $table;
}

function imagediv(& $phpwcms, & $image, $classname='') {
	// creates the image tags if text w/image
	// 0   :1       :2   :3        :4    :5     :6      :7       :8
	// dbid:filename:hash:extension:width:height:caption:position:zoom
	
	$cnt_image_lightbox = empty($GLOBALS['cnt_image_lightbox']) ? 0 : 1;
	$crop = empty($image['crop']) ? 0 : 1;

	$classname = 'imgDIV'.$classname;

	$thumb_image = get_cached_image(
						array(	"target_ext"	=>	$image[3],
								"image_name"	=>	$image[2] . '.' . $image[3],
								"max_width"		=>	$image[4],
								"max_height"	=>	$image[5],
								"thumb_name"	=>	md5($image[2].$image[4].$image[5].$phpwcms["sharpen_level"].$crop),
								'crop_image'	=>	$crop
        					  )
						);

	if($image[8]) {

		$zoominfo = get_cached_image(
						array(	"target_ext"	=>	$image[3],
								"image_name"	=>	$image[2] . '.' . $image[3],
								"max_width"		=>	$phpwcms["img_prev_width"],
								"max_height"	=>	$phpwcms["img_prev_height"],
								"thumb_name"	=>	md5($image[2].$phpwcms["img_prev_width"].$phpwcms["img_prev_height"].$phpwcms["sharpen_level"])
        					  )
						);

		if($zoominfo == false) $image[8] = 0;

	}

	$image_block = '';

	if($thumb_image != false) {

		// read content image info
		$image_border	= ' border="'.intval($GLOBALS["template_default"]["article"]["image_border"]).'"';
		$image_imgclass	= $GLOBALS["template_default"]["article"]["image_imgclass"];
		$image_imgclass	= ($image_imgclass) ? ' class="'.$image_imgclass.'"' : '';
		$image_class 	= $GLOBALS["template_default"]["article"]["image_class"];
		$image_class	= ($image_class) ? ' class="'.$image_class.'"' : ' class="imgClass"';
		$caption_class 	= $GLOBALS["template_default"]["article"]["image_caption_class"];
		$caption_class	= ($caption_class) ? ' class="'.$caption_class.'"' : ' class="caption"';
		$capt_before 	= $GLOBALS["template_default"]["article"]["image_caption_before"];
		$capt_after 	= $GLOBALS["template_default"]["article"]["image_caption_after"];

		// image caption
		//$caption	= explode('|', base64_decode($image[6]));
		$caption = getImageCaption(base64_decode($image[6]));
		$caption[0]	= html_specialchars($caption[0]);
		$caption[3] = empty($caption[3]) ? '' : ' title="'.html_specialchars($caption[3]).'"'; //title
		$caption[1] = empty($caption[1]) ? html_specialchars($image[1]) : html_specialchars($caption[1]);

		// image source
		$img  = '<img src="'.PHPWCMS_IMAGES.$thumb_image[0].'" '.$thumb_image[3];
		$img .= $image_border.$image_imgclass.' alt="'.$caption[1].'"'.$caption[3].' />';

		$image_block .= '<div class="'.$classname.'">';

		if($image[8]) {

			$open_popup_link = 'image_zoom.php?'.getClickZoomImageParameter($zoominfo[0].'?'.$zoominfo[3]);
			$image_block .= '<div'.$image_class.">";
			if($caption[2][0]) {
				$open_link = $caption[2][0];
				$return_false = '';
			} else {
				$open_link = $open_popup_link;
				$return_false = 'return false;';
			}
			
			if(!$cnt_image_lightbox || $caption[2][0]) {
			
				$image_block .= '<a href="'.$open_link."\" onclick=\"checkClickZoom();clickZoom('".$open_popup_link."','previewpic','width=";
				$image_block .= $zoominfo[1].",height=".$zoominfo[2]."');".$return_false."\"".$caption[2][1].">";
			
			} else {
				
				$image_block .= '<a href="'.PHPWCMS_IMAGES.$zoominfo[0].'" rel="lightbox"';
				if($caption[0]) {
					$image_block .= ' title="'.parseLightboxCaption($caption[0]).'"';
				}
				$image_block .= ' target="_blank">';
			
			}
			$image_block .= $img.'</a></div>';
			
		} else {
		
			$image_block .= '<div'.$image_class.">";
			if($caption[2][0]) {
			
				$image_block .= '<a href="'.$caption[2][0].'"'.$caption[2][1].'>'.$img.'</a>';
			
			} else {
			
				$image_block .= $img;
			
			}
			$image_block .= '</div>';
		}
		if($caption[0] && empty($image['nocaption'])) {
			$image_block .= '<p'.$caption_class.'>'.$capt_before.$caption[0].$capt_after."</p>";
		}

		$image_block .= "</div>";

	}

	return $image_block;
}

function imagelisttable($imagelist, $rand="0:0:0:0", $align=0, $type=0) {
	// build imagelist or ecard chooser table
	// image: type = 0
	// ecard: type = 1
	$template_type = (!$type) ? 'imagelist' : 'ecard';
	
	if(empty($GLOBALS['cnt_image_lightbox'])) {
		$lightbox	= 0;
	} else {
		$lightbox	= generic_string(5);
	}
	
	$caption_on = empty($imagelist['nocaption']) ? true : false;
	$crop		= empty($imagelist['crop']) ? 0 : 1;
	

	$table_class 	= $GLOBALS["template_default"]["article"][$template_type."_table_class"];
	if($align) {
		$table_class .= ' imgListTable'.ucfirst($align);
	}
	
	$table_class	= ($table_class) ? ' class="'.$table_class.'"' : '';
	$table_bgcolor 	= $GLOBALS["template_default"]["article"][$template_type."_table_bgcolor"];
	$table_bgcolor	= ($table_bgcolor) ? ' bgcolor="'.$table_bgcolor.'"' : '';
	$image_align	= $GLOBALS["template_default"]["article"][$template_type."_align"];
	$image_align	= ($image_align) ? ' align="'.$image_align.'"' : '';
	$image_valign	= $GLOBALS["template_default"]["article"][$template_type."_valign"];
	$image_valign	= ($image_valign) ? ' valign="'.$image_valign.'"' : '';
	$image_border	= ' border="'.intval($GLOBALS["template_default"]["article"][$template_type."_border"]).'"';
	$image_imgclass	= $GLOBALS["template_default"]["article"][$template_type."_imgclass"];
	$image_imgclass	= ($image_imgclass) ? ' class="'.$image_imgclass.'"' : '';
	$image_class 	= $GLOBALS["template_default"]["article"][$template_type."_class"];
	$image_class	= ($image_class) ? ' class="'.$image_class.'"' : '';
	$image_bgcolor 	= $GLOBALS["template_default"]["article"][$template_type."_bgcolor"];
	$image_bgcolor	= ($image_bgcolor) ? ' bgcolor="'.$image_bgcolor.'"' : '';
	$caption_class 	= $GLOBALS["template_default"]["article"][$template_type."_caption_class"];
	$caption_class	= ($caption_class) ? ' class="'.$caption_class.'"' : '';
	$caption_bgcolor= $GLOBALS["template_default"]["article"][$template_type."_caption_bgcolor"];
	$caption_bgcolor= ($caption_bgcolor) ? ' bgcolor="'.$caption_bgcolor.'"' : '';
	$caption_valign	= $GLOBALS["template_default"]["article"][$template_type."_caption_valign"];
	$caption_valign	= ($caption_valign) ? ' valign="'.$caption_valign.'"' : '';
	$caption_align	= $GLOBALS["template_default"]["article"][$template_type."_caption_align"];
	$caption_align	= ($caption_align) ? ' align="'.$caption_align.'"' : '';
	$capt_before 	= $GLOBALS["template_default"]["article"][$template_type."_caption_before"];
	$capt_after 	= $GLOBALS["template_default"]["article"][$template_type."_caption_after"];
	
	$align = (!$align) ? '' : ' align="'.$align.'"';
	$rand = explode(":", $rand);
	if(count($rand)) {
		foreach($rand as $key => $value) {
			$rand[$key] = intval($value);
		}
	} else {
		$rand = array(0,0,0,0);
	}
	$col_rand = ($rand[2] && $rand[3]) ? 2 : (($rand[2] || $rand[3]) ? 1 : 0 );

	if($count_images = count($imagelist['images'])) {

		//Tabelle starten
		$table = LF.'<table border="0" cellspacing="0" width="10%" cellpadding="0"'.$align.$table_bgcolor.$table_class.' summary="">'.LF;
		$x=0;
		$y=0;
		$z=0;
		foreach($imagelist['images'] as $key => $value) {
			$y++;
			if($z && $x==1) {
				if($col_space) {
					$table .= LF.'<tr>'.LF.'	<td';
					$table .= (($col_total>1)?" colspan=\"".$col_total."\"":"");
					if(!empty($GLOBALS["template_default"]['article']['imagelist_spacerrow_class'])) {
						$table .= ' class="'.$GLOBALS["template_default"]['article']['imagelist_spacerrow_class'].'">';
						$table .= spacer(1,1).'</td>'.LF.'</tr>'.LF;
					} else {
						$table .= '>'.spacer(1,$col_space).'</td>'.LF.'</tr>'.LF;
					}
				}
			}

			if(!$x) {
				//Some default values
				$col_space = $imagelist['space'];	//Space between images
				$col_count = $imagelist['col'];		//columns
				$col_total = $col_count + (($col_space)?($col_count-1):(0)) + $col_rand;
				//Wenn oberer Rand definiert
				if($rand[0]) {
					$table .= '<tr>'.LF.'	<td'.(($col_total>1)?' colspan="'.$col_total.'"':'').'>'.spacer(1,$rand[0]).'</td>'.LF.'</tr>'.LF;
				}
				$x=1;
			}
			if($x==1) {

				// if left border
				$table_tmp	 = ($rand[2]) ? '	<td width="'.$rand[2].'">'.spacer($rand[2],1).'</td>'.LF : '';

				//Neue Tabellenzeile
				$capt_tmp	 = '';
				$capt_row	 = '<tr>'.LF.$table_tmp;
				
				if($caption_on) {
					$table 	.= $capt_row;
				} else {
					$table	.= '<tr>'.LF;
				}

			}
			//Aktuelle Bildspalte ausgeben
			$table .= '	<td'.$image_align.$image_valign.$image_bgcolor.$image_class.'>';
			//width="'.$imagelist[$key]["w"].'" removed because no centered image possible


			$thumb_image = get_cached_image(
						array(	"target_ext"	=>	$imagelist['images'][$key][3],
								"image_name"	=>	$imagelist['images'][$key][2] . '.' . $imagelist['images'][$key][3],
								"max_width"		=>	$imagelist['images'][$key][4],
								"max_height"	=>	$imagelist['images'][$key][5],
								"thumb_name"	=>	md5(	$imagelist['images'][$key][2].$imagelist['images'][$key][4].
															$imagelist['images'][$key][5].$GLOBALS['phpwcms']["sharpen_level"].$crop),
								'crop_image'	=>	$crop
        					  )
						);

			if($imagelist['zoom']) {

				$zoominfo = get_cached_image(
						array(	"target_ext"	=>	$imagelist['images'][$key][3],
								"image_name"	=>	$imagelist['images'][$key][2] . '.' . $imagelist['images'][$key][3],
								"max_width"		=>	$GLOBALS['phpwcms']["img_prev_width"],
								"max_height"	=>	$GLOBALS['phpwcms']["img_prev_height"],
								"thumb_name"	=>	md5(	$imagelist['images'][$key][2].$GLOBALS['phpwcms']["img_prev_width"].
															$GLOBALS['phpwcms']["img_prev_height"].$GLOBALS['phpwcms']["sharpen_level"]
														)
        					  )
						);
			}

			// now try to build caption and if neccessary add alt to image or set external link for image
			$caption = getImageCaption($imagelist['images'][$key][6]);
			// set caption and ALT Image Text for imagelist
			$capt_cur	= !$type ? html_specialchars($caption[0]) : $caption[0];
			$caption[3] = empty($caption[3]) ? '' : ' title="'.html_specialchars($caption[3]).'"'; //title
			$caption[1] = empty($caption[1]) ? html_specialchars($imagelist['images'][$key][1]) : html_specialchars($caption[1]);

			$list_img_temp  = '<img src="'.PHPWCMS_IMAGES.$thumb_image[0].'" '.$thumb_image[3].$image_border.$image_imgclass;
			$list_img_temp .= ' alt="'.$caption[1].'"'.$caption[3].' />';

			if($imagelist['zoom'] && isset($zoominfo) && $zoominfo != false) {
				// if click enlarge the image
				$open_popup_link = 'image_zoom.php?'.getClickZoomImageParameter($zoominfo[0].'?'.$zoominfo[3]);
				if($caption[2][0]) {
					$open_link = $caption[2][0];
					$return_false = '';
				} else {
					$open_link = $open_popup_link;
					$return_false = 'return false;';
				}
				
				if(!$lightbox || $caption[2][0]) {
				
					$table .= "<a href=\"".$open_link."\" onclick=\"checkClickZoom();clickZoom('".$open_popup_link."','previewpic','width=";
					$table .= $zoominfo[1].",height=".$zoominfo[2]."');".$return_false.'"'.$caption[2][1].'>';
					
				} else {
				
					// lightbox
					$table .= '<a href="'.PHPWCMS_IMAGES.$zoominfo[0].'" rel="lightbox['.$lightbox.']"';
					if($capt_cur) {
						$table .= ' title="'.parseLightboxCaption($capt_cur).'"';
					}
					$table .= ' target="_blank">';
				
				}
				
				$table .= $list_img_temp."</a>";
			} else {
				// if not click enlarge
				if($caption[2][0]) {
					$table .= '<a href="'.$caption[2][0].'"'.$caption[2][1].'>'.$list_img_temp.'</a>';
				} else {
					$table .= $list_img_temp;
				}
			}
			$table .= '</td>'.LF;

			$capt_tmp .= $capt_cur;
			$capt_row .= '	<td'.$caption_valign.$caption_align.$caption_bgcolor.$caption_class.'>'.$capt_before.$capt_cur.$capt_after.'</td>'.LF;


			//Gegenchecken wieviele Tabellenspalten als Rest bleiben und ergänzen
			if($y == $count_images && $col_count > 1) {	//wenn eigentlich alle Bilder durchlaufen sind
				if ($col_space && $x<$col_count) {
					$xct = '	<td>'.spacer($col_space,1).'</td>'.LF;
					$table 		.= $xct;
					$capt_row 	.= $xct;
				}
				$rest_image = (ceil($count_images / $col_count) * $col_count) - $count_images;
				for ($i=1; $i <= $rest_image; $i++) {
					$table 		.= '	<td>&nbsp;</td>';
					$capt_row 	.= '	<td>&nbsp;</td>';
					if($i < $rest_image) {
						if($col_space) {
							$xct = '	<td width="'.$col_space.'">'.spacer($col_space,1).'</td>'.LF;
							$table 		.= $xct;
							$capt_row 	.= $xct;
						}
					}
					$x++;
				}
			}

			if($x==$col_count) {	//Wenn maximale Anzahl Bildspalten erreicht
				$xct = ($rand[3]) ? '<td width="'.$rand[3].'">'.spacer($rand[3],1).'</td>'.LF : '';
				$table 		.= $xct;
				$capt_row 	.= $xct;
				$table		.= "</tr>".LF;
				$capt_row	.= "</tr>".LF;
				if($capt_tmp) {
					if($caption_on) {
						$table	.= $capt_row;
					}
					$capt_row = '';
					$capt_tmp = '';
				}
				$x=1; $z++;
			} else {
				$xct 	 	 = ($col_space) ? '	<td width="'.$col_space.'">'.spacer($col_space,1).'</td>'.LF : '';
				$table 		.= $xct;
				$capt_row 	.= $xct;
				$x++;
			}
		}
		
		if($rand[1]) {
			$table .= '<tr>'.LF.'	<td'.(($col_total>1)?" colspan=\"".$col_total."\"":"").">".spacer(1,$rand[1]).'</td>'.LF.'</tr>'.LF;
		}
		$table .= '</table>'.LF;
	}
	return $table;
}

?>