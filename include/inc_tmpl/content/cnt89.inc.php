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


// content 89: poll 		jens

$caption_box = '';
$img_thumbs = '';
$imgx = 0;

if(!isset($content['poll_list'])) {
	$content['poll_list'] = array(
			'width' => '',
			'height' => '',
			'zoom' => 0,
		);
}
if(empty($content["poll_form"])) {
	$content["poll_form"] = array();
}


if(!empty($content["poll_form"]["choice"]) && is_array($content["poll_form"]["choice"]) && count($content["poll_form"]["choice"])) {
	foreach($content["poll_form"]["choice"] as $key => $value) {
		$caption_box .= html_specialchars($content["poll_form"]["choice"][$key])."\n";
	}
} else {
	$content["poll_form"]["choice"] = array();
}

?>
<tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="5"></td></tr>
<tr>
	<td align="right" class="chatlist">Class:&nbsp;</td>
	<td><input name="cpoll_buttonstyle" type="text" class="f11" id="cpoll_buttonstyle" style="width: 250px;" value="<?php echo html_specialchars($content['poll_text']['poll_buttonstyle']) ?>" /></td>
</tr>
<tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="1"></td></tr>
<tr>
	<td align="right" class="chatlist">Button:&nbsp;</td>
	<td><input name="cpoll_buttontext" type="text" class="f11" id="cpoll_buttontext" style="width: 250px;" value="<?php echo html_specialchars($content['poll_text']['poll_buttontext']) ?>" /></td>
</tr>
<tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="10"></td></tr>
<tr>
  <td align="right" valign="top" class="chatlist"><img src="img/leer.gif" alt="" width="1" height="13"><?php echo 'choices' ?>:&nbsp;</td>
  <td valign="top"><textarea name="cpoll_caption" cols="40" rows="8" wrap="off" class="f11" id="cpoll_caption" style="width: 440px;"><?php echo $caption_box; ?></textarea></td>
</tr>
<tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="10"></td></tr>
<tr>
	<td align="right" valign="top" class="chatlist"><img src="img/leer.gif" alt="" width="1" height="13"><?php echo $BL['be_cnt_image'] ?>:&nbsp;</td>
	<td valign="top">
	<table border="0" cellpadding="0" cellspacing="0" summary="">
	<tr>
		<td valign="top">
		<select name="cimage_list[]" size="<?php echo is_array($content["poll_select"]) && count($content["poll_select"]) ? count($content["poll_select"])+5 : 5 ?>" multiple class="f11" id="cimage_list" style="width: 300px;">
<?php

if(isset($content['poll_list']['images']) && is_array($content['poll_list']['images']) && count($content['poll_list']['images'])) {
	foreach($content['poll_list']['images'] as $key => $value) {
	
		// 0   :1       :2   :3        :4    :5     :6      :7       :8
		// dbid:filename:hash:extension:width:height:caption:position:zoom
		$thumb_image = get_cached_image(
						array(	"target_ext"	=>	$content['poll_list']['images'][$key][3],
								"poll_name"	=>	$content['poll_list']['images'][$key][2] . '.' . $content['poll_list']['images'][$key][3],
								"thumb_name"	=>	md5(	$content['poll_list']['images'][$key][2].
															$phpwcms["img_list_width"].
															$phpwcms["img_list_height"].
															$phpwcms["sharpen_level"]
														)
        					  )
							);

		if($thumb_image != false) 
		{
			echo '<option value="' . $content['poll_list']['images'][$key][0] . '">';
			$img_name = html_specialchars($content['poll_list']['images'][$key][1]);
			echo $img_name . "</option>\n";

			if($imgx == 4) {
				$img_thumbs .= '<br><img src="img/leer.gif" alt="" border="0" width="1" height="2"><br>';
				$imgx = 0;
			}
			if($imgx) {
				$img_thumbs .= '<img src="img/leer.gif" alt="" border="0" width="2" height="1">';
			}
			$img_thumbs .= '<img src="'.PHPWCMS_IMAGES.$thumb_image[0].'" border="0" '.$thumb_image[3].' alt="'.$img_name.'" title="'.$img_name.'">';

			$imgx++;
		}
	}
}

?>
</select></td>
<td valign="top"><img src="img/leer.gif" alt="" width="5" height="1"></td>
<td valign="top">
<a href="javascript:;" title="<?php echo $BL['be_cnt_openimagebrowser'] ?>" onclick="tmt_winOpen('filebrowser.php?opt=1&amp;target=nolist','imageBrowser','width=380,height=300,left=8,top=8,scrollbars=yes,resizable=yes',1)"> <img src="img/button/open_image_button.gif" alt="" width="20" height="15" border="0"></a><br /><img src="img/leer.gif" alt="" width="1" height="4"><br /><a href="javascript:;" title="<?php echo $BL['be_cnt_sortup'] ?>" onclick="moveOptionUp(document.articlecontent.cimage_list);"><img src="img/button/image_pos_up.gif" alt="" width="10" height="9" border="0"></a><a href="javascript:;" title="<?php echo $BL['be_cnt_sortdown'] ?>" onclick="moveOptionDown(document.articlecontent.cimage_list);"><img src="img/button/image_pos_down.gif" alt="" width="10" height="9" border="0"></a><br /><img src="img/leer.gif" alt="" width="1" height="4"><br /><a href="javascript:;" onclick="removeSelectedOptions(document.articlecontent.cimage_list);" title="<?php echo $BL['be_cnt_delimage'] ?>"><img src="img/button/del_image_button1.gif" alt="" width="20" height="15" border="0"></a></td>
</tr>
</table>
				
<?php

if($img_thumbs) 
{ 
	echo '<table border="0" cellspacing="0" cellpadding="0">
		<tr><td style="padding-bottom:3px;"><img src="img/leer.gif" width="1" height="5"><br>'.$img_thumbs.'</td></tr>
		</table>';
}

?>
</td>
</tr>
<tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="5"></td></tr>
<tr>
  <td align="right" class="chatlist"><?php echo $BL['be_cnt_maxw'] ?>:&nbsp;</td>
  <td valign="top">
   <table border="0" cellpadding="0" cellspacing="0" summary="">
	 <tr>
		<td><input name="cpoll_width" type="text" class="f11b" id="cpoll_width" style="width: 50px;" size="3" maxlength="3" onKeyUp="if(!parseInt(this.value*1)) this.value='';" value="<?php echo $content['poll_list']['width'] ?>"></td>
		<td class="chatlist">&nbsp;&nbsp;<?php echo $BL['be_cnt_maxh'] ?>:&nbsp;</td>
		<td><input name="cpoll_height" type="text" class="f11b" id="cpoll_height" style="width: 50px;" size="3" maxlength="3" onKeyUp="if(!parseInt(this.value*1)) this.value='';" value="<?php echo $content['poll_list']['height'] ?>"></td>
		<td class="chatlist">&nbsp;px&nbsp;&nbsp;</td>
		<td bgcolor="#E7E8EB"><input name="cpoll_zoom" type="checkbox" id="cpoll_zoom" value="1" <?php is_checked(1, $content['poll_list']['zoom']); ?>></td>
	  <td bgcolor="#E7E8EB" class="v10">&nbsp;<?php echo $BL['be_cnt_enlarge'] ?>&nbsp;</td>
	  <td bgcolor="#E7E8EB"><img src="img/leer.gif" alt="" width="6" height="15"></td>
	  </tr>
	</table></td>
</tr>
<tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="10"></td></tr>


