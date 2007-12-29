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

// ----------------------------------------------------------------
// obligate check for phpwcms constants
if (!defined('PHPWCMS_ROOT')) {
   die("You Cannot Access This Script Directly, Have a Nice Day.");
}
// ----------------------------------------------------------------

$BE['HEADER']['date.js']			= getJavaScriptSourceLink('include/inc_js/date.js');
$BE['HEADER']['dynCalendar.js']		= getJavaScriptSourceLink('include/inc_js/dynCalendar.js');

?>
<h1 class="title" style="margin-bottom:10px"><?php echo $BLM['listing_title'] ?></h1>

<form action="<?php echo MODULE_HREF ?>&amp;edit=<?php echo $plugin['data']['calendar_id'] ?>" method="post" style="background:#F3F5F8;border-top:1px solid #92A1AF;border-bottom:1px solid #92A1AF;margin:0 0 5px 0;padding:10px 8px 15px 8px">
<input type="hidden" name="calendar_id" value="<?php echo $plugin['data']['calendar_id'] ?>" />
<table border="0" cellpadding="0" cellspacing="0" width="100%" summary="">

	<tr> 
		<td align="right" class="chatlist" nowrap="nowrap"><?php echo $BL['be_cnt_last_edited']  ?>:&nbsp;</td>
		<td class="v10"><?php echo html_specialchars(date($BL['be_fprivedit_dateformat'], strtotime($plugin['data']['calendar_changed']))) ?></td>
	</tr>
	
	<?php if(!empty($plugin['data']['calendar_created'])) { ?>
	
	<tr> 
		<td align="right" class="chatlist" nowrap="nowrap"><?php echo $BL['be_fprivedit_created']  ?>:&nbsp;</td>
		<td class="v10"><?php echo html_specialchars(date($BL['be_fprivedit_dateformat'], strtotime($plugin['data']['calendar_created']))) ?></td>
	</tr>
	
	<?php } ?>
	
	<tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="15" /></td></tr>

	<tr> 
		<td align="right" class="chatlist"><?php echo $BLM['calendar_title'] ?>:&nbsp;</td>
		<td><input name="calendar_title" type="text" id="calendar_title" class="v12<?php 
		
		//error class
		if(!empty($plugin['error']['calendar_title'])) echo ' errorInputText';
		
		?>" style="width:375px;" value="<?php echo html_specialchars($plugin['data']['calendar_title']) ?>" size="30" maxlength="250" /></td>
	</tr>
	
	<tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="8" /></td></tr>


	<tr> 
		<td align="right" class="chatlist" valign="top" style="padding-top:17px"><?php echo $BLM['calendar_start'] ?>:&nbsp;</td>
		<td><table border="0" cellpadding="0" cellspacing="0" summary="">
		
			<tr>
				<td class="chatlist" style="padding-bottom:2px"><?php echo $BLM['date_format'] ?></td>
				<td class="chatlist" id="endDate0">&nbsp;</td>
				<td class="chatlist" id="endDate1" style="padding-bottom:2px"><?php echo $BLM['time_format'] ?></td>
				<td colspan="2">&nbsp;</td>
			</tr>
		
			<tr>
				<td><input name="calendar_start_date" type="text" id="calendar_start_date" class="v12<?php 
		
		//error class
		if(!empty($plugin['error']['calendar_start'])) echo ' errorInputText';
		
		?>" style="width:100px;" value="<?php echo html_specialchars($plugin['data']['calendar_start_date']) ?>" size="30" /></td>
		<td><script language="javascript" type="text/javascript">
		<!--
		function aStart(date, month, year) {
			getFieldById('calendar_start_date').value = subrstr('00' + date, 2) + '<?php echo $BLM['date_delimiter'] ?>' + subrstr('00' + month, 2) + '<?php echo $BLM['date_delimiter'] ?>' + year;
		}
		calStart = new dynCalendar('calStart', 'aStart', 'img/dynCal/');
		calStart.setMonthCombo(false);
		calStart.setYearCombo(false);
		//-->
		</script>&nbsp;</td>
		<td id="endDate2"><input name="calendar_start_time" type="text" id="calendar_start_time" class="v12" style="width:80px;" value="<?php echo html_specialchars($plugin['data']['calendar_start_time']) ?>" size="30" /></td>
		
		<td id="endDate3">&nbsp;</td>
		<td><input type="checkbox" name="calendar_allday" id="calendar_allday" value="1"<?php is_checked(1, $plugin['data']['calendar_allday']) ?> onchange="setCalendarAllDay();" /></td>
		<td><label for="calendar_allday" onclick="setCalendarAllDay()"><?php echo $BLM['all_day'] ?></label></td>
		
			</tr>
		</table></td>
		
	</tr>	
	
	<tr id="endDate4"><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="3" /></td></tr>
	
	<tr id="endDate5"> 
		<td align="right" class="chatlist" valign="top" style="padding-top:17px"><?php echo $BLM['calendar_end'] ?>:&nbsp;</td>
		<td><table border="0" cellpadding="0" cellspacing="0" summary="">
		
			<tr>
				<td class="chatlist" style="padding-bottom:2px"><?php echo $BLM['date_format'] ?></td>
				<td class="chatlist">&nbsp;</td>
				<td class="chatlist" style="padding-bottom:2px"><?php echo $BLM['time_format'] ?></td>
			</tr>
		
			<tr>
				<td><input name="calendar_end_date" type="text" id="calendar_end_date" class="v12<?php 
		
		//error class
		if(!empty($plugin['error']['calendar_end'])) echo ' errorInputText';
		
		?>" style="width:100px;" value="<?php echo html_specialchars($plugin['data']['calendar_end_date']) ?>" size="30" /></td>
		<td><script language="javascript" type="text/javascript">
		<!--
		function aEnd(date, month, year) {
			getFieldById('calendar_end_date').value = subrstr('00' + date, 2) + '<?php echo $BLM['date_delimiter'] ?>' + subrstr('00' + month, 2) + '<?php echo $BLM['date_delimiter'] ?>' + year;
		}
		calEnd = new dynCalendar('calEnd', 'aEnd', 'img/dynCal/');
		calEnd.setMonthCombo(false);
		calEnd.setYearCombo(false);
		//-->
		</script>&nbsp;</td>
		<td><input name="calendar_end_time" type="text" id="calendar_end_time" class="v12" style="width:80px;" value="<?php echo html_specialchars($plugin['data']['calendar_end_time']) ?>" size="30" /></td>
		
			</tr>
		</table></td>
		
	</tr>	
	
	<tr id="endDate0"><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="8" /></td></tr>
	
	
	<tr> 
		<td align="right" class="chatlist"><?php echo $BLM['repeat'] ?>:&nbsp;</td>
		<td><select name="calendar_range" id="calendar_range" class="v12" onchange="setRangeDates(this.options[this.selectedIndex].value)">
		
			<option value="0"<?php is_selected(0, $plugin['data']['calendar_range']) ?>><?php echo $BLM['repeat_0'] ?></option>
			<option value="1"<?php is_selected(1, $plugin['data']['calendar_range']) ?>><?php echo $BLM['repeat_1'] ?></option>
			<option value="2"<?php is_selected(2, $plugin['data']['calendar_range']) ?>><?php echo $BLM['repeat_2'] ?></option>
			<option value="3"<?php is_selected(3, $plugin['data']['calendar_range']) ?>><?php echo $BLM['repeat_3'] ?></option>
			<option value="4"<?php is_selected(4, $plugin['data']['calendar_range']) ?>><?php echo $BLM['repeat_4'] ?></option>
			<option value="5"<?php is_selected(5, $plugin['data']['calendar_range']) ?>><?php echo $BLM['repeat_5'] ?></option>
			<option value="6"<?php is_selected(6, $plugin['data']['calendar_range']) ?>><?php echo $BLM['repeat_6'] ?></option>
			<option value="7"<?php is_selected(7, $plugin['data']['calendar_range']) ?>><?php echo $BLM['repeat_7'] ?></option>
		
		</select></td>
	</tr>
	
	<tr id="rDate0"><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="5" /></td></tr>
	
	<tr id="rDate1"> 
		<td align="right" class="chatlist" valign="top" style="padding-top:17px"><?php echo $BLM['repeat_till'] ?>:&nbsp;</td>
		<td><table border="0" cellpadding="0" cellspacing="0" summary="">
		
			<tr>
				<td class="chatlist" style="padding-bottom:2px"><?php echo $BLM['date_format'] ?></td>
				<td class="chatlist" colspan="2">&nbsp;</td>
				<td class="chatlist" colspan="2" style="padding-bottom:2px"><?php echo $BLM['date_format'] ?></td>
			</tr>
		
			<tr>
				<td><input name="calendar_range_start" type="text" id="calendar_range_start" class="v12" style="width:100px;" value="<?php echo html_specialchars($plugin['data']['calendar_rangestart']) ?>" size="30" /></td>
				<td><script language="javascript" type="text/javascript">
					<!--
					function rStart(date, month, year) {
						getFieldById('calendar_range_start').value = subrstr('00' + date, 2) + '<?php echo $BLM['date_delimiter'] ?>' + subrstr('00' + month, 2) + '<?php echo $BLM['date_delimiter'] ?>' + year;
					}
					rangeStart = new dynCalendar('rangeStart', 'rStart', 'img/dynCal/');
					rangeStart.setMonthCombo(false);
					rangeStart.setYearCombo(false);
					//-->
					</script>&nbsp;</td>
				<td class="chatlist">&nbsp;<?php echo $BLM['till'] ?>:&nbsp;</td>
				<td><input name="calendar_range_end" type="text" id="calendar_range_end" class="v12" style="width:100px;" value="<?php echo html_specialchars($plugin['data']['calendar_rangeend']) ?>" size="30" /></td>
				<td><script language="javascript" type="text/javascript">
					<!--
					function rEnd(date, month, year) {
						getFieldById('calendar_range_end').value = subrstr('00' + date, 2) + '<?php echo $BLM['date_delimiter'] ?>' + subrstr('00' + month, 2) + '<?php echo $BLM['date_delimiter'] ?>' + year;
					}
					rangeEnd = new dynCalendar('rangeEnd', 'rEnd', 'img/dynCal/');
					rangeEnd.setMonthCombo(false);
					rangeEnd.setYearCombo(false);
					//-->
					</script>&nbsp;</td>
	
			</tr>
					
		</table></td>
	</tr>	
	
	
	
	
	<tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="15" /><script type="text/javascript" language="javascript">
	<!--
	
	
	function setCalendarAllDay() {
	
		var calendarAllDay = getFieldById('calendar_allday');
		if(calendarAllDay.checked == true) {
			toggleDisplayById('endDate0', 'none');
			toggleDisplayById('endDate1', 'none');
			toggleDisplayById('endDate2', 'none');
			toggleDisplayById('endDate3', 'none');
			toggleDisplayById('endDate4', 'none');
			toggleDisplayById('endDate5', 'none');
		} else {
			toggleDisplayById('endDate0', '');
			toggleDisplayById('endDate1', '');
			toggleDisplayById('endDate2', '');
			toggleDisplayById('endDate3', '');
			toggleDisplayById('endDate4', '');
			toggleDisplayById('endDate5', '');
		}

	}
	
	function setRangeDates(value) {
		value = parseInt(value);
		if(!value) {
			toggleDisplayById('rDate0', 'none');
			toggleDisplayById('rDate1', 'none');
		} else {
			toggleDisplayById('rDate0', '');
			toggleDisplayById('rDate1', '');
			//getFieldById('calendar_range_start').value = getFieldById('calendar_start_date').value;
			//getFieldById('calendar_range_end').value = getFieldById('calendar_end_date').value;
		}
		
	}
	
	setCalendarAllDay();
	setRangeDates(<?php echo $plugin['data']['calendar_range'] ?>);
	
	
	//-->
	</script></td></tr>
	
	<tr> 
		<td align="right" class="chatlist"><?php echo $BLM['where'] ?>:&nbsp;</td>
		<td><input name="calendar_where" type="text" id="calendar_where" class="v12" style="width:375px;" value="<?php echo html_specialchars($plugin['data']['calendar_where']) ?>" size="30" maxlength="220" /></td>
	</tr>
	
	<tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="5" /></td></tr>
	
	<tr> 
		<td align="right" class="chatlist"><?php echo $BLM['calendar_token'] ?>:&nbsp;</td>
		<td><input name="calendar_tag" type="text" id="calendar_tag" class="v12" style="width:375px;" value="<?php echo html_specialchars(trim($plugin['data']['calendar_tag'])) ?>" size="30" maxlength="220" /></td>
	</tr>


	<tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="5" /></td></tr>
	
	<tr> 
		<td align="right" class="chatlist"><?php echo $BLM['article_link'] ?>:&nbsp;</td>
		<td><table border="0" cellpadding="0" cellspacing="0" summary="">
		
			<tr>
				<td><input name="calendar_refid" type="text" id="calendar_refid" class="v12" style="width:80px;" value="<?php echo empty($plugin['data']['calendar_refid']) ? '' : $plugin['data']['calendar_refid'] ?>" size="11" maxlength="11" /></td>
				<td class="chatlist">&nbsp;<?php echo $BLM['more_info'] ?></td>
			</tr>
		</table></td>
	
	</tr>
	
	<tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="15" /></td></tr>
	
	<tr> 
		<td class="chatlist" colspan="2" style="padding-bottom:4px"><?php echo $BLM['calendar_text'] ?>:&nbsp;</td>
	</tr>
	
	<tr> 
		<td colspan="2" align="center"><?php

		$wysiwyg_editor = array(
			'value'		=> $plugin['data']['calendar_text'],
			'field'		=> 'calendar_text',
			'height'	=> '400px',
			'width'		=> '524px',
			'rows'		=> '15',
			'editor'	=> $_SESSION["WYSIWYG_EDITOR"],
			'lang'		=> 'en'
		);
		
		include(PHPWCMS_ROOT.'/include/inc_lib/wysiwyg.editor.inc.php');


		?></td>
	</tr>
	
	<tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="15" /></td></tr>	
	
	<!-- <tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="5" /></td></tr> -->
	
	<tr>
		<td align="right" class="chatlist"><?php echo $BL['be_ftptakeover_status'] ?>:&nbsp;</td>
		<td><table border="0" cellpadding="0" cellspacing="0" summary="">		
			<tr>
				<td><input type="checkbox" name="calendar_status" id="calendar_status" value="1"<?php is_checked($plugin['data']['calendar_status'], 1) ?> /></td>
				<td><label for="calendar_status"><?php echo $BL['be_cnt_activated'] ?></label></td>
			</tr>
		</table></td>
	</tr>
	<tr>
		<td align="right" class="chatlist">&nbsp;</td>
		<td><table border="0" cellpadding="0" cellspacing="0" summary="">		
			<tr>
				<td><input type="checkbox" name="calendar_duplicate" id="calendar_duplicate" value="1"<?php is_checked(empty($plugin['data']['calendar_duplicate'])?0:1, 1) ?> /></td>
				<td><label for="calendar_duplicate"><?php echo $BLM['save_copy'] ?></label></td>
			</tr>
		</table></td>
	</tr>
	
	<tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="10" /></td>
	</tr>
	<tr> 
		<td>&nbsp;</td>
		<td>
			<input name="submit" type="submit" class="button10" value="<?php echo empty($plugin['data']['calendar_id']) ? $BL['be_admin_fcat_button2'] : $BL['be_article_cnt_button1'] ?>" />
			<input name="save" type="submit" class="button10" value="<?php echo $BL['be_article_cnt_button3'] ?>" />
			&nbsp;&nbsp;&nbsp;&nbsp;
			<input name="new" type="button" class="button10" value="<?php echo ucfirst($BL['be_msg_new']) ?>" onclick="location.href='<?php echo decode_entities(MODULE_HREF) ?>&edit=0';return false;" />
			<input name="close" type="button" class="button10" value="<?php echo $BL['be_admin_struct_close'] ?>" onclick="location.href='<?php echo decode_entities(MODULE_HREF) ?>';return false;" />
		</td>
	</tr>

</table>

</form>