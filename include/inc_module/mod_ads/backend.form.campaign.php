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

// Module/Plug-in Ads/Banner Management


$BE['HEADER']['date.js']			= getJavaScriptSourceLink('include/inc_js/date.js');
$BE['HEADER']['dynCalendar.js']		= getJavaScriptSourceLink('include/inc_js/dynCalendar.js');
$BE['HEADER']['ads.js']				= getJavaScriptSourceLink($phpwcms['modules'][$module]['dir'].'template/ads.js');

?>
<h1 class="title" style="margin-bottom:10px"><?php echo $BLM['form_title'] ?></h1>

<form action="<?php echo MODULE_HREF ?>&amp;campaign=1&amp;edit=<?php echo $plugin['data']['adcampaign_id'] ?>" method="post" enctype="multipart/form-data" style="background:#F3F5F8;border-top:1px solid #92A1AF;border-bottom:1px solid #92A1AF;margin:0 0 5px 0;padding:10px 8px 15px 8px">
<input type="hidden" name="adcampaign_id" value="<?php echo $plugin['data']['adcampaign_id'] ?>" />
<table border="0" cellpadding="0" cellspacing="0" width="100%" summary="">

	<tr>
		<td align="right" class="chatlist nowrap" nowrap="nowrap"><?php echo $BL['be_cnt_last_edited']  ?>:&nbsp;</td>
		<td class="v10"><?php

		echo html(date($BL['be_fprivedit_dateformat'], strtotime($plugin['data']['adcampaign_changed'])));

		if(!empty($plugin['data']['adcampaign_created'])) {

		?>
		&nbsp;&nbsp;&nbsp;<span class="chatlist"><?php echo $BL['be_fprivedit_created']  ?>:</span>
		<?php
				echo html(date($BL['be_fprivedit_dateformat'], strtotime($plugin['data']['adcampaign_created'])));
		}

		?>
		</td>
	</tr>

	<tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="10" /></td></tr>

	<tr>
		<td align="right" class="chatlist"><?php echo $BLM['campaign_entry'] ?>:&nbsp;</td>
		<td><input name="adcampaign_title" type="text" id="adcampaign_title" class="v12<?php

		//error class
		if(!empty($plugin['error']['adcampaign_title'])) echo ' errorInputText';

		?>" style="width:400px;" value="<?php echo html($plugin['data']['adcampaign_title']) ?>" size="30" maxlength="200" /></td>
	</tr>


	<tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="10" /></td></tr>

	<tr>
		<td align="right" class="chatlist"><?php echo $BLM['adplace'] ?>:&nbsp;</td>
		<td><select name="adcampaign_place" id="adcampaign_place" class="v12" onchange="setFormat(this.options[this.selectedIndex].value);">

<?php

	$sql  = 'SELECT * FROM '.DB_PREPEND.'phpwcms_ads_place ap ';
	$sql .= 'LEFT JOIN '.DB_PREPEND.'phpwcms_ads_formats af ON ';
	$sql .=	'ap.adplace_format=af.adformat_id  ';
	$sql .= 'WHERE adplace_status!=9';

	$plugin['ad_place']		= _dbQuery($sql);
	$plugin['ad_place_js']	= array();
	foreach($plugin['ad_place'] as $_entry['value']) {

		echo '	<option value="'.$_entry['value']['adplace_id'].'"';
		if($_entry['value']['adplace_id'] == $plugin['data']['adcampaign_place']) {

			$plugin['data']['adcampaign_data']['width']		= $_entry['value']['adplace_width'];
			$plugin['data']['adcampaign_data']['height']	= $_entry['value']['adplace_height'];

			echo ' selected="selected"';

		}
		echo '>';
		echo html($_entry['value']['adplace_title'].' / '.$_entry['value']['adformat_title'].' '.$_entry['value']['adplace_width'].'x'.$_entry['value']['adplace_height']);
		echo '</option>'.LF;

		$plugin['ad_place_js'][ $_entry['value']['adplace_id'] ]  = '		ad_place['.$_entry['value']['adplace_id'].'] = ';
		$plugin['ad_place_js'][ $_entry['value']['adplace_id'] ] .= '["'.$_entry['value']['adplace_width'].'", "';
		$plugin['ad_place_js'][ $_entry['value']['adplace_id'] ] .= $_entry['value']['adplace_height'].'"];';

	}

?>

				</select></td>
	</tr>

	<tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="5" /></td></tr>


	<tr>

		<td>&nbsp;</td>
		<td><table summary="" cellpadding="0" cellspacing="0" border="0">
			<tr>
				<td class="chatlist"><?php echo $BL['be_admin_page_width'] ?>:&nbsp;</td>
				<td><input type="text" name="adcampaign_width" id="adcampaign_width" value="<?php echo $plugin['data']['adcampaign_data']['width'] ?>" class="v11 disabled width40" onfocus="this.blur()" /></td>
				<td class="chatlist">&nbsp;<?php echo $BLM['pixel'] ?>&nbsp;/&nbsp;<?php echo $BL['be_admin_page_height'] ?>:&nbsp;</td>
				<td><input type="text" name="adcampaign_height" id="adcampaign_height" value="<?php echo $plugin['data']['adcampaign_data']['height'] ?>" class="v11 disabled width40" onfocus="this.blur()" /></td>
				<td class="chatlist">&nbsp;<?php echo $BLM['pixel'] ?></td>
			</tr>
		</table><script type="text/javascript">
		var ad_place = [];
<?php

echo implode(LF, $plugin['ad_place_js']);
if(empty($plugin['data']['adcampaign_place']) && count($plugin['ad_place_js'])) {
	echo LF.LF.'	 	setFormat('.key($plugin['ad_place_js']).');';
}

// set JavaScript sourcePath
if(!empty($plugin['data']['adcampaign_id'])) {
	echo LF.LF.'	 	var adsPath = "'.CONTENT_PATH.PHPWCMS_ADS_DIR.'/'.$plugin['data']['adcampaign_id'].'/";'.LF.LF;
}
?>

		function setFormat(value) {

			if(ad_place[value]) {
				getFieldById('adcampaign_width').value  = ad_place[value][0];
				getFieldById('adcampaign_height').value = ad_place[value][1];
			}

		}

		</script></td>

	</tr>


	<tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="10" /></td></tr>


	<tr>

		<td align="right" class="chatlist"><?php echo $BLM['target_url'] ?>:&nbsp;</td>
		<td><table summary="" cellpadding="0" cellspacing="0" border="0" class="width400">
			<tr>
				<td><input type="text" name="adcampaign_url" id="adcampaign_url" value="<?php

					if(!empty($plugin['data']['adcampaign_data']['url'])) {
						$plugin['data']['adcampaign_data']['url_html'] = html($plugin['data']['adcampaign_data']['url']);
						echo $plugin['data']['adcampaign_data']['url_html'];
						echo '" title="'.$plugin['data']['adcampaign_data']['url_html'];
					}

					?>" class="v12 width250" /></td>
				<td align="right" class="chatlist">&nbsp;&nbsp;<?php echo $BLM['open_in'] ?>:&nbsp;</td>
				<td width="20"><select name="adcampaign_target" id="adcampaign_target" class="v12">
		<option value=""<?php is_selected('', $plugin['data']['adcampaign_data']['target']) ?>>&nbsp;</option>
		<option value="_blank"<?php is_selected('_blank', $plugin['data']['adcampaign_data']['target']) ?>>_blank</option>
		<option value="_top"<?php is_selected('_top', $plugin['data']['adcampaign_data']['target']) ?>>_top</option>
		<option value="_self"<?php is_selected('_self', $plugin['data']['adcampaign_data']['target']) ?>>_self</option>
		<option value="_parent"<?php is_selected('_parent', $plugin['data']['adcampaign_data']['target']) ?>>_parent</option>
		</select></td>
			</tr>
		</table></td>
	</tr>




	<tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="15" /></td></tr>

	<tr>
		<td align="right" class="chatlist" valign="top" style="padding-top:18px"><?php echo $BLM['calendar_start'] ?>:&nbsp;</td>
		<td><table border="0" cellpadding="0" cellspacing="0" summary="">

			<tr>
				<td class="chatlist" style="padding-bottom:2px"><?php echo $BLM['date_format'] ?></td>
				<td class="chatlist">&nbsp;</td>
				<td class="chatlist" style="padding-bottom:2px"><?php echo $BLM['time_format'] ?></td>
				<td colspan="2">&nbsp;</td>
			</tr>

			<tr>
				<td><input name="adcampaign_date_start" type="text" id="adcampaign_date_start" class="v12 width100<?php

		//error class
		if(!empty($plugin['error']['adcampaign_date_start'])) echo ' errorInputText';

		?>" value="<?php echo html($plugin['data']['adcampaign_date_start']) ?>" size="30" /></td>
		<td>&nbsp;</td>
		<td><input name="adcampaign_time_start" type="text" id="adcampaign_time_start" class="v12 width100" value="<?php echo html($plugin['data']['adcampaign_time_start']) ?>" size="30" /></td>
		<td>&nbsp;<script type="text/javascript">
		function aStart(date, month, year) {
			getFieldById('adcampaign_date_start').value = subrstr('00' + date, 2) + '<?php echo $BLM['date_delimiter'] ?>' + subrstr('00' + month, 2) + '<?php echo $BLM['date_delimiter'] ?>' + year;

			var timestart = getFieldById('adcampaign_time_start');
			if(Trim(timestart.value) === '') {
				timestart.value = '00:00';
			}
		}
		calStart = new dynCalendar('calStart', 'aStart', 'img/dynCal/');
		calStart.setMonthCombo(false);
		calStart.setYearCombo(false);
		</script></td>

			</tr>
		</table></td>

	</tr>

	<tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="3" /></td></tr>

	<tr>
		<td align="right" class="chatlist" valign="top" style="padding-top:18px"><?php echo $BLM['calendar_end'] ?>:&nbsp;</td>
		<td><table border="0" cellpadding="0" cellspacing="0" summary="">

			<tr>
				<td class="chatlist" style="padding-bottom:2px"><?php echo $BLM['date_format'] ?></td>
				<td class="chatlist">&nbsp;</td>
				<td class="chatlist" style="padding-bottom:2px"><?php echo $BLM['time_format'] ?></td>
			</tr>

			<tr>
				<td><input name="adcampaign_date_end" type="text" id="adcampaign_date_end" class="v12 width100<?php

		//error class
		if(!empty($plugin['error']['adcampaign_date_end'])) echo ' errorInputText';

		?>" value="<?php echo html($plugin['data']['adcampaign_date_end']) ?>" size="30" /></td>
		<td>&nbsp;</td>
		<td><input name="adcampaign_time_end" type="text" id="adcampaign_time_end" class="v12 width100" value="<?php echo html($plugin['data']['adcampaign_time_end']) ?>" size="30" /></td>
		<td>&nbsp;<script type="text/javascript">
		function aEnd(date, month, year) {
			getFieldById('adcampaign_date_end').value = subrstr('00' + date, 2) + '<?php echo $BLM['date_delimiter'] ?>' + subrstr('00' + month, 2) + '<?php echo $BLM['date_delimiter'] ?>' + year;
			var timeend = getFieldById('adcampaign_time_end');
			if(Trim(timeend.value) === '') {
				timeend.value = '23:59';
			}
		}
		calEnd = new dynCalendar('calEnd', 'aEnd', 'img/dynCal/');
		calEnd.setMonthCombo(false);
		calEnd.setYearCombo(false);
		</script></td>

			</tr>
		</table></td>

	</tr>


	<tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="3" /></td></tr>

	<tr>

		<td align="right" class="chatlist" valign="top" style="padding-top:18px"><?php echo $BLM['tracking_base'] ?>:&nbsp;</td>
		<td><table summary="" cellpadding="0" cellspacing="0" border="0">
			<tr>
				<td class="chatlist" style="padding-bottom:2px"><?php echo $BLM['max_view'] ?></td>
				<td class="chatlist">&nbsp;</td>
				<td class="chatlist" style="padding-bottom:2px"><?php echo $BLM['max_click'] ?></td>
				<td class="chatlist">&nbsp;</td>
				<td class="chatlist" style="padding-bottom:2px"><?php echo $BLM['max_view_user'] ?></td>
			</tr>
			<tr>
				<td><input type="text" name="adcampaign_max_views" id="adcampaign_max_views" value="<?php echo empty($plugin['data']['adcampaign_maxview']) ? '' : $plugin['data']['adcampaign_maxview'] ?>" class="v12 width100" /></td>
				<td>&nbsp;</td>
				<td><input type="text" name="adcampaign_max_click" id="adcampaign_max_click" value="<?php echo empty($plugin['data']['adcampaign_maxclick']) ? '' : $plugin['data']['adcampaign_maxclick'] ?>" class="v12 width100" /></td>
				<td>&nbsp;</td>
				<td><input type="text" name="adcampaign_max_viewuser" id="adcampaign_max_viewuser" value="<?php echo empty($plugin['data']['adcampaign_maxviewuser']) ? '' : $plugin['data']['adcampaign_maxviewuser'] ?>" class="v12 width100" /></td>
			</tr>
		</table></td>

	</tr>




	<tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="15" /></td></tr>




	<tr>

		<td align="right" class="chatlist"><?php echo $BLM['ad_type'] ?>:&nbsp;</td>
		<td class="v12 inlineCheckbox">
		<input type="radio" name="adcampaign_type" id="adcampaign_type_0" value="0"<?php is_checked(0, $plugin['data']['adcampaign_type']) ?> /><label for="adcampaign_type_0"><?php echo $BLM['ad_type_0'] ?></label>&nbsp;
		<input type="radio" name="adcampaign_type" id="adcampaign_type_1" value="1"<?php is_checked(1, $plugin['data']['adcampaign_type']) ?> /><label for="adcampaign_type_1"><?php echo $BLM['ad_type_1'] ?></label>&nbsp;
		<input type="radio" name="adcampaign_type" id="adcampaign_type_2" value="2"<?php is_checked(2, $plugin['data']['adcampaign_type']) ?> /><label for="adcampaign_type_2"><?php echo $BLM['ad_type_2'] ?></label>&nbsp;
		<input type="radio" name="adcampaign_type" id="adcampaign_type_3" value="3"<?php is_checked(3, $plugin['data']['adcampaign_type']) ?> /><label for="adcampaign_type_3"><?php echo $BLM['ad_type_3'] ?></label>&nbsp;
		<input type="radio" name="adcampaign_type" id="adcampaign_type_4" value="4"<?php is_checked(4, $plugin['data']['adcampaign_type']) ?> /><label for="adcampaign_type_4"><?php echo $BLM['ad_type_6'] ?></label>
		</td>

	</tr>

	<tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="10" /></td></tr>

<?php

// now check media

// try to load image files
$plugin['data']['files'] = returnFileListAsArray(PHPWCMS_CONTENT.PHPWCMS_ADS_DIR.'/'.$plugin['data']['adcampaign_id'], 'gif,jpg,png');
$plugin['data']['image'] = '';
if(is_array($plugin['data']['files']) && count($plugin['data']['files'])) {

	foreach($plugin['data']['files'] as $_entry['value']) {

		$c = html($_entry['value']['filename']);
		$plugin['data']['image'] .= '			<option value="'.$c.'"';
		if($_entry['value']['filename'] == $plugin['data']['adcampaign_data']['image']) {
			$plugin['data']['image'] .= ' selected="selected"';
		}
		$plugin['data']['image'] .= '>'.$c.'</option>'.LF;

	}
}
// try to load flash files
$plugin['data']['files'] = returnFileListAsArray(PHPWCMS_CONTENT.PHPWCMS_ADS_DIR.'/'.$plugin['data']['adcampaign_id'], 'swf');
$plugin['data']['flash'] = '';
if(is_array($plugin['data']['files']) && count($plugin['data']['files'])) {

	foreach($plugin['data']['files'] as $_entry['value']) {

		$c = html($_entry['value']['filename']);
		$plugin['data']['flash'] .= '			<option value="'.$c.'"';
		if($_entry['value']['filename'] == $plugin['data']['adcampaign_data']['flash']) {
			$plugin['data']['flash'] .= ' selected="selected"';
		}
		$plugin['data']['flash'] .= '>'.$c.'</option>'.LF;

	}
}
// try to load css files
$plugin['data']['files'] = returnFileListAsArray(PHPWCMS_CONTENT.PHPWCMS_ADS_DIR.'/'.$plugin['data']['adcampaign_id'], 'css');
$plugin['data']['css'] = '';
if(is_array($plugin['data']['files']) && count($plugin['data']['files'])) {

	foreach($plugin['data']['files'] as $_entry['value']) {

		$c = html($_entry['value']['filename']);
		$plugin['data']['css'] .= '			<option value="'.$c.'"';
		if($_entry['value']['filename'] == $plugin['data']['adcampaign_data']['css']) {
			$plugin['data']['css'] .= ' selected="selected"';
		}
		$plugin['data']['css'] .= '>'.$c.'</option>'.LF;

	}
}


// as long as no ID defined hide upload content
if(empty($plugin['data']['adcampaign_id'])) {

	echo LF.'<tr><td>&nbsp;</td><td class="warning">'.$BLM['ad_info'].'</td></tr>'.LF;


	echo LF.'<!--'.LF;
}



if(isset($plugin['error']['image'])) {

	echo LF.'<tr><td>&nbsp;</td><td class="warning1">'.$plugin['error']['image'].'</td></tr>'.LF;
}

?>
	<tr>

		<td align="right" class="chatlist"><a href="#" onclick="showImageAds();return false;" title="Preview"><?php echo $BLM['ad_type_0'] ?><img src="img/symbole/redirect.gif" alt="" border="0" style="margin:0 1px 0 4px;position:relative;top:1px;" /></a>:&nbsp;</td>
		<td><table summary="" cellpadding="0" cellspacing="0" border="0">
			<tr>
				<td><select name="adcampaign_image" id="adcampaign_image" class="v12 width175">
			<option value="">&nbsp;</option>
<?php echo $plugin['data']['image'] ?>
			</select></td>
			<td>&nbsp;</td>
			<td class="upload newimage"><input type="file" name="adcampaign_upload_image" id="adcampaign_upload_image" title="<?php echo $BLM['ad_upload_image'] ?>" accept="image/png,image/jpeg,image/gif,image/webp,.gif,.png,.webp,.jpeg,.jpg" /></td>
			</tr>
			</table></td>

	</tr>

	<tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="6" /></td></tr>
<?php
if(isset($plugin['error']['flash'])) {

	echo LF.'<tr><td>&nbsp;</td><td class="warning1">'.$plugin['error']['flash'].'</td></tr>'.LF;
}
?>

	<tr>

		<td align="right" class="chatlist"><a href="#" onclick="showFlashAds();return false;" title="Preview"><?php echo $BLM['ad_type_1'] ?><img src="img/symbole/redirect.gif" alt="" border="0" style="margin:0 1px 0 4px;position:relative;top:1px;" /></a>:&nbsp;</td>
		<td><table summary="" cellpadding="0" cellspacing="0" border="0">
			<tr>
				<td><select name="adcampaign_flash" id="adcampaign_flash" class="v12 width175">
			<option value="">&nbsp;</option>
<?php echo $plugin['data']['flash'] ?>
			</select></td>
				<td>&nbsp;</td>
				<td class="upload newflash"><input type="file" name="adcampaign_upload_flash" id="adcampaign_upload_flash" title="<?php echo $BLM['ad_upload_flash'] ?>" accept=".swf" /></td>
			</tr>
			</table></td>

	</tr>

	<tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="6" /></td></tr>
<?php
if(isset($plugin['error']['css'])) {

	echo LF.'<tr><td>&nbsp;</td><td class="warning1">'.$plugin['error']['css'].'</td></tr>'.LF;
}
?>

	<tr>

		<td align="right" class="chatlist">CSS:&nbsp;</td>
		<td><table summary="" cellpadding="0" cellspacing="0" border="0">
			<tr>
				<td><select name="adcampaign_css" id="adcampaign_css" class="v12 width175">
			<option value="">&nbsp;</option>
<?php echo $plugin['data']['css'] ?>
			</select></td>
				<td>&nbsp;</td>
				<td class="upload newcss"><input type="file" name="adcampaign_upload_css" id="adcampaign_upload_css" title="<?php echo $BLM['ad_upload_css'] ?>" accept="text/css,.css" /></td>
			</tr>
			</table></td>

	</tr>

	<tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="6" /></td></tr>

<?php
// as long as no ID defined hide upload content
if(empty($plugin['data']['adcampaign_id'])) {
	echo LF.'// -->'.LF;
}


?>


	<tr>

		<td align="right" class="chatlist tdtop3"><a href="#" onclick="showHtmlAds();return false;" title="Preview"><?php echo $BLM['ad_type_2'] ?><img src="img/symbole/redirect.gif" alt="" border="0" style="margin:0 1px 0 4px;position:relative;top:1px;" /></a>:&nbsp;</td>
		<td><textarea name="adcampaign_html" id="adcampaign_html" rows="5" class="width400 code"><?php echo html($plugin['data']['adcampaign_data']['html']) ?></textarea></td>

	</tr>

	<tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="6" /></td></tr>

	<tr>
		<td align="right" class="chatlist"><?php echo $BLM['ad_alt_text'] ?>:&nbsp;</td>
		<td><input name="adcampaign_alt_text" type="text" id="adcampaign_alt_text" class="v12" style="width:400px;" value="<?php echo html($plugin['data']['adcampaign_data']['alt_text']) ?>" size="30" maxlength="200" /></td>
	</tr>

	<tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="6" /></td></tr>

	<tr>
		<td align="right" class="chatlist"><?php echo $BLM['ad_title_text'] ?>:&nbsp;</td>
		<td><input name="adcampaign_title_text" type="text" id="adcampaign_title_text" class="v12" style="width:400px;" value="<?php echo html($plugin['data']['adcampaign_data']['title_text']) ?>" size="30" maxlength="200" /></td>
	</tr>

	<tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="6" /></td></tr>

	<tr>

		<td align="right" class="chatlist" valign="top" style="padding-top:18px"><?php echo $BL['be_settings'] ?>:&nbsp;</td>
		<td><table summary="" cellpadding="0" cellspacing="0" border="0">
			<tr>
				<td class="chatlist" colspan="2" style="padding-bottom:2px"><?php echo $BLM['ad_bgcolor'] ?></td>
				<td class="chatlist">&nbsp;</td>
				<td class="chatlist" colspan="2" style="padding-bottom:2px"><?php echo $BLM['ad_bordercolor'] ?></td>
				<td class="chatlist">&nbsp;</td>
				<td class="chatlist" colspan="2" style="padding-bottom:2px"><?php echo $BLM['ad_flashversion'] ?></td>
			</tr>
			<tr>
				<td style="padding-right:3px;"><input type="text" name="adcampaign_bgcolor" id="adcampaign_bgcolor" value="<?php echo $plugin['data']['adcampaign_data']['bgcolor'] ?>" class="v12 width77" maxlength="7" /></td>
				<td class="colorfield" width="20" id="bgcolor"<?php if(!empty($plugin['data']['adcampaign_data']['bgcolor'])) echo ' style="background-color:'.$plugin['data']['adcampaign_data']['bgcolor'].'"' ?>>&nbsp;</td>
				<td>&nbsp;</td>
				<td style="padding-right:3px;"><input type="text" name="adcampaign_bordercolor" id="adcampaign_bordercolor" value="<?php echo $plugin['data']['adcampaign_data']['bordercolor'] ?>" class="v12 width77" maxlength="7" /></td>
				<td class="colorfield" width="20" id="bordercolor"<?php if(!empty($plugin['data']['adcampaign_data']['bordercolor'])) echo ' style="background-color:'.$plugin['data']['adcampaign_data']['bordercolor'].'"' ?>>&nbsp;</td>
				<td>&nbsp;</td>
				<td><input type="text" name="adcampaign_flashversion" id="adcampaign_flashversion" value="<?php echo $plugin['data']['adcampaign_data']['flashversion'] ?>" class="v12 width75" /></td>
			</tr>
		</table></td>

	</tr>


	<tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="25" /></td></tr>

	<tr>
		<td align="right" class="chatlist tdtop4"><?php echo $BLM['comment'] ?>:&nbsp;</td>
		<td colspan="2"><textarea name="adcampaign_comment" id="adcampaign_comment" rows="5" class="width400"><?php echo html($plugin['data']['adcampaign_comment']) ?></textarea></td>
	</tr>

	<tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="15" /></td></tr>

	<tr>
		<td align="right" class="chatlist"><?php echo $BL['be_ftptakeover_status'] ?>:&nbsp;</td>
		<td><table border="0" cellpadding="0" cellspacing="0" summary="">
<?php

	echo '	<tr><td><input type="checkbox" name="adcampaign_status" id="adcampaign_status" value="1"';
	if(empty($plugin['data']['adcampaign_id'])) {
		echo ' disabled="disabled" /></td><td class="inactive">'.$BL['be_cnt_activated'].'</td></tr>';
	} else {
		is_checked($plugin['data']['adcampaign_status'], 1);
		echo ' /></td><td><label for="adcampaign_status">'.$BL['be_cnt_activated'].'</label></td></tr>';
	}

?>
		</table></td>
	</tr>
<?php
	if(!empty($plugin['data']['adcampaign_id'])) {
?>
	<tr>
		<td align="right" class="chatlist">&nbsp;</td>
		<td><table border="0" cellpadding="0" cellspacing="0" summary="">
			<tr>
				<td><input type="checkbox" name="adcampaign_duplicate" id="adcampaign_duplicate" value="1"<?php is_checked(empty($plugin['data']['adcampaign_duplicate'])?0:1, 1) ?> /></td>
				<td><label for="adcampaign_duplicate"><?php echo $BLM['save_copy'] ?></label></td>
			</tr>
		</table></td>
	</tr>
<?php
	}
?>

	<tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="10" /></td>
	</tr>
	<tr>
		<td>&nbsp;</td>
		<td>
		<?php
		if(empty($plugin['data']['adcampaign_id'])) {
			echo '<input name="submit" type="submit" class="button" value="'.$BL['be_admin_fcat_button2'].'" />'.LF;
		} else {
			echo '<input name="submit" type="submit" class="button" value="'.$BL['be_article_cnt_button1'].'" />'.LF;
			echo '<input name="save" type="submit" class="button" value="'.$BL['be_article_cnt_button3'].'" />'.LF;
		}
		?>
			&nbsp;&nbsp;&nbsp;&nbsp;
			<input name="new" type="button" class="button" value="<?php echo ucfirst($BL['be_msg_new']) ?>" onclick="location.href='<?php echo decode_entities(MODULE_HREF) ?>&campaign=1&edit=0';return false;" />
			<input name="close" type="button" class="button" value="<?php echo $BL['be_admin_struct_close'] ?>" onclick="location.href='<?php echo decode_entities(MODULE_HREF) ?>&listcampaign=1';return false;" />
		</td>
	</tr>

</table>

</form>