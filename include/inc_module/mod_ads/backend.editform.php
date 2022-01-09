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

?>
<h1 class="title" style="margin-bottom:10px"><?php echo $BLM['form_title'] ?></h1>

<form action="<?php echo MODULE_HREF ?>&amp;campaign=1&amp;edit=<?php echo $plugin['data']['adcampaign_id'] ?>" method="post" style="background:#F3F5F8;border-top:1px solid #92A1AF;border-bottom:1px solid #92A1AF;margin:0 0 5px 0;padding:10px 8px 15px 8px">
<input type="hidden" name="adcampaign_id" value="<?php echo $plugin['data']['adcampaign_id'] ?>" />
<table border="0" cellpadding="0" cellspacing="0" width="100%" summary="">

	<tr>
		<td align="right" class="chatlist"><?php echo $BL['be_cnt_last_edited']  ?>:&nbsp;</td>
		<td class="v10"><?php echo html(phpwcms_strtotime($plugin['data']['adcampaign_changed'], $BL['be_fprivedit_dateformat'], '')) ?></td>
	</tr>

	<?php if(!empty($plugin['data']['adcampaign_created'])) { ?>

	<tr>
		<td align="right" class="chatlist"><?php echo $BL['be_fprivedit_created']  ?>:&nbsp;</td>
		<td class="v10"><?php echo html(phpwcms_strtotime($plugin['data']['adcampaign_created'], $BL['be_fprivedit_dateformat'], '')) ?></td>
	</tr>

	<?php } ?>

	<tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="10" /></td></tr>

	<tr>
		<td align="right" class="chatlist"><?php echo $BLM['campaign_entry'] ?>:&nbsp;</td>
		<td><input name="adcampaign_title" type="text" id="adcampaign_title" class="v12<?php

		//error class
		if(!empty($plugin['error']['adcampaign_title'])) echo ' errorInputText';

		?>" style="width:400px;" value="<?php echo html($plugin['data']['adcampaign_title']) ?>" size="30" maxlength="200" /></td>
	</tr>

	<tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="5" /></td></tr>

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
				<td><input name="adcampaign_date_start" type="text" id="adcampaign_date_start" class="v12<?php

		//error class
		if(!empty($plugin['error']['adcampaign_date_start'])) echo ' errorInputText';

		?>" style="width:100px;" value="<?php echo html($plugin['data']['adcampaign_date_start']) ?>" size="30" /></td>
		<td>&nbsp;</td>
		<td><input name="adcampaign_time_start" type="text" id="adcampaign_time_start" class="v12" style="width:80px;" value="<?php echo html($plugin['data']['adcampaign_time_start']) ?>" size="30" /></td>
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
				<td><input name="adcampaign_date_end" type="text" id="adcampaign_date_end" class="v12<?php

		//error class
		if(!empty($plugin['error']['adcampaign_date_end'])) echo ' errorInputText';

		?>" style="width:100px;" value="<?php echo html($plugin['data']['adcampaign_date_end']) ?>" size="30" /></td>
		<td>&nbsp;</td>
		<td><input name="adcampaign_time_end" type="text" id="adcampaign_time_end" class="v12" style="width:80px;" value="<?php echo html($plugin['data']['adcampaign_time_end']) ?>" size="30" /></td>
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

	<tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="8" /></td></tr>

	<tr>
		<td align="right" class="chatlist"><?php echo $BLM['ad_format'] ?>:&nbsp;</td>
		<td><select name="adcampaign_format" id="adcampaign_format" class="v12" onchange="setFormat(this.options[this.selectedIndex].value);">

<?php

	$sql = 'SELECT * FROM '.DB_PREPEND.'phpwcms_ads_formats WHERE adformat_status=1';
	$plugin['ad_formats']		= _dbQuery($sql);
	$plugin['ad_formats_js']	= array();
	foreach($plugin['ad_formats'] as $_entry['value']) {

		echo '	<option value="'.$_entry['value']['adformat_id'].'"';
		if($_entry['value']['adformat_id'] == $plugin['data']['adcampaign_format']) {

			$plugin['data']['adcampaign_data']['width']		= $_entry['value']['adformat_width'];
			$plugin['data']['adcampaign_data']['height']	= $_entry['value']['adformat_height'];

			echo ' selected="selected"';

		}
		echo '>';
		echo html($_entry['value']['adformat_title'].' ('.$_entry['value']['adformat_width'].'x'.$_entry['value']['adformat_height'].')');
		echo '</option>'.LF;

		$plugin['ad_formats_js'][ $_entry['value']['adformat_id'] ]  = '		ad_formats['.$_entry['value']['adformat_id'].'] = ';
		$plugin['ad_formats_js'][ $_entry['value']['adformat_id'] ] .= '["'.$_entry['value']['adformat_width'].'", "';
		$plugin['ad_formats_js'][ $_entry['value']['adformat_id'] ] .= $_entry['value']['adformat_height'].'"];';

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
				<td><input type="text" name="adcampaign_width" id="adcampaign_width" value="<?php echo $plugin['data']['adcampaign_data']['width'] ?>" class="v11 disabled width45" onfocus="this.blur()" /></td>
				<td class="chatlist">&nbsp;<?php echo $BLM['pixel'] ?>&nbsp;/&nbsp;<?php echo $BL['be_admin_page_height'] ?>:&nbsp;</td>
				<td><input type="text" name="adcampaign_height" id="adcampaign_height" value="<?php echo $plugin['data']['adcampaign_data']['height'] ?>" class="v11 disabled width45" onfocus="this.blur()" /></td>
				<td class="chatlist">&nbsp;<?php echo $BLM['pixel'] ?></td>
			</tr>
		</table><script type="text/javascript">
		var ad_formats = [];
<?php echo implode(LF, $plugin['ad_formats_js']) ?>

		function setFormat(value) {

			if(ad_formats[value]) {
				getFieldById('adcampaign_width').value = ad_formats[value][0];
				getFieldById('adcampaign_height').value = ad_formats[value][1];
			}

		}
		</script></td>

	</tr>

	<tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="10" /></td></tr>

	<tr>

		<td align="right" class="chatlist"><?php echo $BLM['tracking_base'] ?>:&nbsp;</td>
		<td><table summary="" cellpadding="0" cellspacing="0" border="0">
			<tr>
				<td><input type="text" name="adcampaign_max_views" id="adcampaign_max_views" value="<?php echo empty($plugin['data']['adcampaign_data']['max_views']) ? '' : $plugin['data']['adcampaign_data']['max_views'] ?>" class="v12 width60" /></td>
				<td class="chatlist">&nbsp;<?php echo $BLM['max_view'] ?>&nbsp;&nbsp;&nbsp;</td>
				<td><input type="text" name="adcampaign_max_click" id="adcampaign_max_click" value="<?php echo empty($plugin['data']['adcampaign_data']['max_click']) ? '' : $plugin['data']['adcampaign_data']['max_click'] ?>" class="v12 width60" /></td>
				<td class="chatlist">&nbsp;<?php echo $BLM['max_click'] ?></td>
			</tr>
		</table></td>

	</tr>

	<tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="10" /></td></tr>


	<tr>

		<td align="right" class="chatlist"><?php echo $BLM['target_url'] ?>:&nbsp;</td>
		<td><input type="text" name="adcampaign_url" id="adcampaign_url" value="<?php echo empty($plugin['data']['adcampaign_data']['max_views']) ? '' : $plugin['data']['adcampaign_data']['url'] ?>" class="v12 width400" /></td>

	</tr>

	<tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="3" /></td></tr>

	<tr>

		<td align="right" class="chatlist"><?php echo $BLM['open_in'] ?>:&nbsp;</td>
		<td><select name="adcampaign_target" id="adcampaign_target" class="v12">
		<option value=""<?php is_selected('', $plugin['data']['adcampaign_data']['target']) ?>>&nbsp;</option>
		<option value="_blank"<?php is_selected('_blank', $plugin['data']['adcampaign_data']['target']) ?>>_blank</option>
		<option value="_top"<?php is_selected('_top', $plugin['data']['adcampaign_data']['target']) ?>>_top</option>
		<option value="_self"<?php is_selected('_self', $plugin['data']['adcampaign_data']['target']) ?>>_self</option>
		<option value="_parent"<?php is_selected('_parent', $plugin['data']['adcampaign_data']['target']) ?>>_parent</option>
		</select></td>

	</tr>



	<tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="10" /></td></tr>

	<tr>
		<td align="right" class="chatlist" style="padding-top:4px;vertical-align:top;"><?php echo $BLM['comment'] ?>:&nbsp;</td>
		<td colspan="2"><textarea name="adcampaign_comment" id="adcampaign_comment" rows="5" class="width400"><?php echo html($plugin['data']['adcampaign_comment']) ?></textarea></td>
	</tr>

	<tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="15" /></td></tr>

	<tr>
		<td align="right" class="chatlist"><?php echo $BL['be_ftptakeover_status'] ?>:&nbsp;</td>
		<td><table border="0" cellpadding="0" cellspacing="0" summary="">
			<tr>
				<td><input type="checkbox" name="adcampaign_status" id="adcampaign_status" value="1"<?php is_checked($plugin['data']['adcampaign_status'], 1) ?> /></td>
				<td><label for="adcampaign_status"><?php echo $BL['be_cnt_activated'] ?></label></td>
			</tr>
		</table></td>
	</tr>

	<tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="10" /></td>
	</tr>
	<tr>
		<td>&nbsp;</td>
		<td>
			<input name="submit" type="submit" class="button" value="<?php echo empty($plugin['data']['adcampaign_id']) ? $BL['be_admin_fcat_button2'] : $BL['be_article_cnt_button1'] ?>" />
			<input name="save" type="submit" class="button" value="<?php echo $BL['be_article_cnt_button3'] ?>" />
			&nbsp;&nbsp;&nbsp;&nbsp;
			<input name="new" type="button" class="button" value="<?php echo ucfirst($BL['be_msg_new']) ?>" onclick="location.href='<?php echo decode_entities(MODULE_HREF) ?>&campaign=1&edit=0';return false;" />
			<input name="close" type="button" class="button" value="<?php echo $BL['be_admin_struct_close'] ?>" onclick="location.href='<?php echo decode_entities(MODULE_HREF) ?>';return false;" />
		</td>
	</tr>

</table>

</form>