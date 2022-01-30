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


// Content Type Reference

$content['reference'] = unserialize($crow["acontent_form"]);

if(empty($content['reference']["tmpl"]) && is_file(PHPWCMS_TEMPLATE.'inc_default/reference.tmpl')) {

	$content['reference']["tmpl"] = render_device( @file_get_contents(PHPWCMS_TEMPLATE.'inc_default/reference.tmpl') );

} elseif(is_file(PHPWCMS_TEMPLATE.'inc_cntpart/reference/'.$content['reference']["tmpl"])) {

	$content['reference']["tmpl"] = render_device( @file_get_contents(PHPWCMS_TEMPLATE.'inc_cntpart/reference/'.$content['reference']["tmpl"]) );

} else {

	$content['reference']["tmpl"] = '	<table width="100%"  border="0" cellspacing="0" cellpadding="0">
	  <tr>
	    <td width="1%" valign="top"><table width="100%"  border="0" cellspacing="0" cellpadding="0">
	      <tr><td>[REF]{REF}[/REF]</td></tr>
	      [CAPTION]<tr><td>{CAPTION}</td></tr>[/CAPTION]
	    </table>
	    [LIST]{LIST}[/LIST]</td>
	    <td width="14" valign="top">&nbsp;</td>
	    <td width="98%" valign="top">[TITLE]<h3>{TITLE}</h3>[/TITLE]
	[SUB]<h4>{SUB}</h4>[/SUB]
	[TEXT]<p>{TEXT}</p>[/TEXT]</td>
	  </tr>
	</table>';

}

$content['reference']['ref_caption'] = '';
$content['reference']['ref_image']   = '[NO&nbsp;IMAGE]';
$content['reference']['ref_list']    = '';

// check if there is an image
$content['reference']['ref_count'] = count($content['reference']["list"]);
if($content['reference']['ref_count']) {

	// caption
	$content['reference']['caption_list'] = explode("\n", $content['reference']['caption']);
	$ci = 0;
	if(is_array($content['reference']['caption_list']) && count($content['reference']['caption_list'])) {
		foreach($content['reference']['caption_list'] as $captkey => $captvalue) {
			$content['reference']['caption_list'][$captkey] = html_specialchars(trim($captvalue));
			$ci++;
		}
		if($content['reference']['caption_list'][0]) {
			$content['reference']['ref_caption']  = '<div id="refcaptid'.$crow['acontent_id'].'" style="display:inline;">';
			$content['reference']['ref_caption'] .= $content['reference']['caption_list'][0];
			$content['reference']['ref_caption'] .= '</div>';
		}
	}
	for($ci; $ci < $content['reference']['ref_count']; $ci++) {
		$content['reference']['caption_list'][$ci] = '';
	}

	// javascript ID
	$content['reference']['ref_id'] = 'refimgid'.$crow['acontent_id'];

	// starting large image
	$thumb_image = get_cached_image(array(
		"target_ext"	=>	$content['reference']["list"][0][3],
		"image_name"	=>	$content['reference']["list"][0][2] . '.' . $content['reference']["list"][0][3],
		"max_width"		=>	$content['reference']["list"][0][4],
		"max_height"	=>	$content['reference']["list"][0][5],
		"thumb_name"	=>	md5($content['reference']["list"][0][2].$content['reference']["list"][0][4].$content['reference']["list"][0][5].$phpwcms["sharpen_level"].$phpwcms['colorspace'])
	));

	if($thumb_image != false) {
		$content['reference']['ref_image']  = '<img src="' . $thumb_image['src'] . '"';
		$content['reference']['ref_image'] .= ' border="' . $content['reference']['border'] . '" ';
		$content['reference']['ref_image'] .= ' alt=""';
		$content['reference']['ref_image'] .= ' id="' . $content['reference']['ref_id'] . '" name="'.$content['reference']['ref_id'] . '"';
		$content['reference']['ref_image'] .= PHPWCMS_LAZY_LOADING . HTML_TAG_CLOSE;
	}

	if($content['reference']['ref_count'] > 1) {
		$ci = 0;
		// open table row if horizontal
		if($content['reference']['basis']) {
			$content['reference']['x1'] = "<tr>\n";
			$content['reference']['x2'] = "</tr>\n";
			$content['reference']['x3'] = '';
			$content['reference']['x4'] = "</tr>\n";
		} else {
			$content['reference']['ref_list'] .= "<tr>\n";
			$content['reference']['x1'] = '';
			$content['reference']['x2'] = "\n";
			$content['reference']['x3'] = "</tr>\n";
			$content['reference']['x4'] = '';
		}
		$content['reference']['x5'] = '';
		$content['reference']['x6'] = '';

		$content['reference']['x8'] = 0;
		if(preg_match('/\[CAPTION\](.*?)\[\/CAPTION\]/is', $content['reference']["tmpl"])) {
			$content['reference']['x8'] = 1;
		}

		// loop images
		foreach($content['reference']["list"] as $captkey => $captvalue) {

			$content['reference']['x5'] = '';
			$content['reference']['x6'] = '';

			// space between images
			if($ci && $content['reference']['space']) {

				$content['reference']['ref_list'] .= $content['reference']['x1'];
				$content['reference']['ref_list'] .= '<td><img src="img/leer.gif" alt="" ';
				$content['reference']['ref_list'] .= 'width="'.$content['reference']['space'].'" height="';
				$content['reference']['ref_list'] .= $content['reference']['space'].'" /></td>';
				$content['reference']['ref_list'] .= $content['reference']['x2'];

			}
			$content['reference']['ref_list'] .= $content['reference']['x1'];
			$content['reference']['ref_list'] .= '<td';
			switch($content['reference']["pos"]) {
				case 1:	$content['reference']['ref_list'] .= ' align="left" valign="top"';		break;
				case 2:	$content['reference']['ref_list'] .= ' align="left" valign="middle"';	break;
				case 3:	$content['reference']['ref_list'] .= ' align="left" valign="bottom"';	break;
				case 4:	$content['reference']['ref_list'] .= ' align="center" valign="top"';	break;
				case 5:	$content['reference']['ref_list'] .= ' align="center" valign="middle"';	break;
				case 6:	$content['reference']['ref_list'] .= ' align="center" valign="bottom"';	break;
				case 7:	$content['reference']['ref_list'] .= ' align="right" valign="top"';		break;
				case 8:	$content['reference']['ref_list'] .= ' align="right" valign="middle"';	break;
				case 9:	$content['reference']['ref_list'] .= ' align="right" valign="bottom"';	break;
			}
			$content['reference']['ref_list'] .= '>';

			if($content['reference']["zoom"]) {

				// build additional reference popup images
				$zoominfo = get_cached_image(array(
					"target_ext"	=>	$content['reference']["list"][$captkey][3],
					"image_name"	=>	$content['reference']["list"][$captkey][2] . '.' . $content['reference']["list"][$captkey][3],
					"max_width"		=>	$phpwcms["img_prev_width"],
					"max_height"	=>	$phpwcms["img_prev_height"],
					"thumb_name"	=>	md5($content['reference']["list"][$captkey][2].$phpwcms["img_prev_width"].$phpwcms["img_prev_height"].$phpwcms["sharpen_level"].$phpwcms['colorspace'])
				));


				if($zoominfo != false) {
					$popup_link  = 'image_zoom.php?'.getClickZoomImageParameter($zoominfo['src'], $zoominfo[3], $content['reference']["list"][$captkey][1]);

					$content['reference']['x5'] = '<a href="'.$popup_link.'" onclick="window.open(\''.$popup_link."','previewpic','width=".$zoominfo[1].",height=".$zoominfo[2]."');return false;\">";
					$content['reference']['x6'] = '</a>';
				}

			}

			$content['reference']['ref_list'] .= $content['reference']['x5'];

			$over_image = get_cached_image(array(
				"target_ext"	=>	$content['reference']["list"][$captkey][3],
				"image_name"	=>	$content['reference']["list"][$captkey][2] . '.' . $content['reference']["list"][$captkey][3],
				"max_width"		=>	$content['reference']["list"][$captkey][4],
				"max_height"	=>	$content['reference']["list"][$captkey][5],
				"thumb_name"	=>	md5($content['reference']["list"][$captkey][2].$content['reference']["list"][$captkey][4].$content['reference']["list"][$captkey][5].$phpwcms["sharpen_level"].$phpwcms['colorspace'])
			));

			$thumb_image = get_cached_image(array(
				"target_ext"	=>	$content['reference']["list"][$captkey][3],
				"image_name"	=>	$content['reference']["list"][$captkey][2] . '.' . $content['reference']["list"][$captkey][3],
				"max_width"		=>	$content['reference']["temp_list_width"],
				"max_height"	=>	$content['reference']["temp_list_height"],
				"thumb_name"	=>	md5($content['reference']["list"][$captkey][2].$content['reference']["temp_list_width"].$content['reference']["temp_list_height"].$phpwcms["sharpen_level"].$phpwcms['colorspace'])
			));

			if($thumb_image != false) {

				initFrontendJS();

				if(!empty($content['reference']['caption_list'][$captkey])) {
					$img_name = $content['reference']['caption_list'][$captkey];
				} else {
					$img_name = html_specialchars($content['reference']["list"][$captkey][1]);
				}
				$content['reference']['ref_list'] .= '<img src="' . $thumb_image['src'] . '"' ;
				$content['reference']['ref_list'] .= ' border="'.$content['reference']['border'].'" ';
				$content['reference']['ref_list'] .= $thumb_image[3].' alt="'.$img_name.'" title="'.$img_name;
				$content['reference']['ref_list'] .= '" id="'.$content['reference']['ref_id'].'a'.$captkey;
				$content['reference']['ref_list'] .= '" name="'.$content['reference']['ref_id'].'a'.$captkey.'" ';

				// switch large image onmouseover
				$content['reference']['ref_list'] .= 'onmouseover="';
				if($over_image != false) {
					$content['reference']['ref_list'] .= "MM_swapImage('".$content['reference']['ref_id'];
					$content['reference']['ref_list'] .= "','','".$over_image['src']."',1);";
				}
				// make single quotes js compatible
				$content['reference']['x7'] = js_singlequote($content['reference']['caption_list'][$captkey]);
				// check if layer for caption available
				if($content['reference']['x8'] && $content['reference']['caption_list'][$captkey]) {
					$content['reference']['ref_list'] .= "addText('refcaptid".$crow['acontent_id']."','";
					$content['reference']['ref_list'] .= $content['reference']['x7']."');";
				}
				$content['reference']['ref_list'] .= "MM_displayStatusMsg('".$content['reference']['x7']."');return ";
				$content['reference']['ref_list'] .= 'document.MM_returnValue;"' . PHPWCMS_LAZY_LOADING . HTML_TAG_CLOSE;
				$content['reference']['ref_list'] .= $content['reference']['x6'] . "</td>\n" . $content['reference']['x4'];

			}
			$ci++;
		}
		// close table row if horizontal
		$content['reference']['ref_list'] .= $content['reference']['x3'];
		// wrap it in the table
		$content['reference']['ref_list']  = '<table border="0" cellspacing="0" cellpadding="0">'.$content['reference']['ref_list'].'</table>';
	}

}

$content['reference']["tmpl"] = render_cnt_template($content['reference']["tmpl"], 'ATTR_CLASS', html($crow['acontent_attr_class']));
$content['reference']["tmpl"] = render_cnt_template($content['reference']["tmpl"], 'ATTR_ID', html($crow['acontent_attr_id']));
$content['reference']["tmpl"] = render_cnt_template($content['reference']["tmpl"], 'TITLE', html($crow["acontent_title"]));
$content['reference']["tmpl"] = render_cnt_template($content['reference']["tmpl"], 'SUB', html($crow["acontent_subtitle"]));
$content['reference']["tmpl"] = render_cnt_template($content['reference']["tmpl"], 'TEXT', nl2br($content['reference']["text"]));
$content['reference']["tmpl"] = render_cnt_template($content['reference']["tmpl"], 'CAPTION', $content['reference']['ref_caption']);
$content['reference']["tmpl"] = render_cnt_template($content['reference']["tmpl"], 'LIST', $content['reference']['ref_list']);
$content['reference']["tmpl"] = render_cnt_template($content['reference']["tmpl"], 'REF', $content['reference']['ref_image']);
$content['reference']["tmpl"] = str_replace('{ID}', $crow['acontent_id'], $content['reference']["tmpl"]);

$CNT_TMP .= $content['reference']["tmpl"];
