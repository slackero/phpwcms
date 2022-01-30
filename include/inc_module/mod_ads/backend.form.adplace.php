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

?>
<h1 class="title" style="margin-bottom:10px"><?php echo $BLM['form_adplace_title'] ?></h1>

<form action="<?php echo MODULE_HREF ?>&amp;adplace=1&amp;edit=<?php echo $plugin['data']['adplace_id'] ?>" method="post" style="background:#F3F5F8;border-top:1px solid #92A1AF;border-bottom:1px solid #92A1AF;margin:0 0 5px 0;padding:10px 8px 15px 8px">
<input type="hidden" name="adplace_id" value="<?php echo $plugin['data']['adplace_id'] ?>" />
<table border="0" cellpadding="0" cellspacing="0" width="100%" summary="">

	<tr>
		<td align="right" class="chatlist"><?php echo $BL['be_cnt_last_edited']  ?>:&nbsp;</td>
		<td class="v10" width="410"><?php

		echo html(date($BL['be_fprivedit_dateformat'], strtotime($plugin['data']['adplace_changed']))) ;

		if(!empty($plugin['data']['adplace_created'])) {
		?>
		&nbsp;&nbsp;&nbsp;<span class="chatlist"><?php echo $BL['be_fprivedit_created']  ?>:</span>
		<?php
				echo html(date($BL['be_fprivedit_dateformat'], strtotime($plugin['data']['adplace_created'])));
		}

		?></td>
	</tr>

	<tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="10" /></td></tr>

	<tr>
		<td align="right" class="chatlist"><?php echo $BLM['adplace_title'] ?>:&nbsp;</td>
		<td><input name="adplace_title" type="text" id="adplace_title" class="v12<?php

		//error class
		if(!empty($plugin['error']['adplace_title'])) echo ' errorInputText';

		?>" style="width:400px;" value="<?php echo html($plugin['data']['adplace_title']) ?>" size="30" maxlength="200" /></td>
	</tr>

	<tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="10" /></td></tr>

	<tr>
		<td align="right" class="chatlist"><?php echo $BLM['ad_format'] ?>:&nbsp;</td>
		<td><select name="adplace_format" id="adplace_format" class="v12" onchange="setFormat(this.options[this.selectedIndex].value);">

<?php

	$sql = 'SELECT * FROM '.DB_PREPEND.'phpwcms_ads_formats WHERE adformat_status=1';
	$plugin['ad_formats']		= _dbQuery($sql);
	$plugin['ad_formats_js']	= array();
	foreach($plugin['ad_formats'] as $_entry['value']) {

		echo '	<option value="'.$_entry['value']['adformat_id'].'"';
		if($_entry['value']['adformat_id'] == $plugin['data']['adplace_format']) {

			$plugin['data']['adplace_width']	= $_entry['value']['adformat_width'];
			$plugin['data']['adplace_height']	= $_entry['value']['adformat_height'];

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
				<td><input type="text" name="adplace_width" id="adplace_width" value="<?php echo $plugin['data']['adplace_width'] ?>" class="v11 disabled width45" onfocus="this.blur()" /></td>
				<td class="chatlist">&nbsp;<?php echo $BLM['pixel'] ?>&nbsp;/&nbsp;<?php echo $BL['be_admin_page_height'] ?>:&nbsp;</td>
				<td><input type="text" name="adplace_height" id="adplace_height" value="<?php echo $plugin['data']['adplace_height'] ?>" class="v11 disabled width45" onfocus="this.blur()" /></td>
				<td class="chatlist">&nbsp;<?php echo $BLM['pixel'] ?></td>
			</tr>
		</table><script type="text/javascript">
		var ad_formats = [];
<?php

echo implode(LF, $plugin['ad_formats_js']);
if(empty($plugin['data']['adplace_format']) && count($plugin['ad_formats_js'])) {
	echo LF.LF.'	 	setFormat('.key($plugin['ad_formats_js']).');';
}

?>
		function setFormat(value) {

			if(ad_formats[value]) {
				getFieldById('adplace_width').value = ad_formats[value][0];
				getFieldById('adplace_height').value = ad_formats[value][1];
			}

		}
		</script></td>
	</tr>

	<tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="10" /></td></tr>

	<tr>
		<td align="right" class="chatlist" style="padding-top:4px;vertical-align:top;"><?php echo $BLM['adplace_prefix'] ?>:&nbsp;</td>
		<td colspan="2"><textarea name="adplace_prefix" id="adplace_prefix" rows="3" class="code width400"><?php echo html($plugin['data']['adplace_prefix']) ?></textarea></td>
	</tr>

	<tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="5" /></td></tr>

	<tr>
		<td align="right" class="chatlist" style="padding-top:4px;vertical-align:top;"><?php echo $BLM['adplace_suffix'] ?>:&nbsp;</td>
		<td colspan="2"><textarea name="adplace_suffix" id="adplace_suffix" rows="3" class="code width400"><?php echo html($plugin['data']['adplace_suffix']) ?></textarea></td>
	</tr>

<?php

if(!empty($plugin['data']['adplace_id'])) {

?>
	<tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="10" /></td></tr>

	<tr>
		<td align="right" class="chatlist tdtop5"><?php echo $BLM['ad_template_code'] ?>:&nbsp;</td>
		<td class="v12 tdtop3">
			<?php echo $BLM['ad_template_code_info'] ?>
			<br />
			<input type="text" name="banner_rt" value="{ADS_<?php echo $plugin['data']['adplace_id'] ?>}" class="v12 rt width100" onfocus="this.select();" onclick="this.select();" /></td>
	</tr>


<?php

}

?>

	<tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="18" /></td></tr>

	<tr>
		<td align="right" class="chatlist"><?php echo $BL['be_ftptakeover_status'] ?>:&nbsp;</td>
		<td><table border="0" cellpadding="0" cellspacing="0" summary="">
			<tr>
				<td><input type="checkbox" name="adplace_status" id="adplace_status" value="1"<?php is_checked($plugin['data']['adplace_status'], 1) ?> /></td>
				<td><label for="adplace_status"><?php echo $BL['be_cnt_activated'] ?></label></td>
			</tr>
		</table></td>
	</tr>

	<tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="10" /></td>
	</tr>
	<tr>
		<td>&nbsp;</td>
		<td>
			<input name="submit" type="submit" class="button" value="<?php echo empty($plugin['data']['adplace_id']) ? $BL['be_admin_fcat_button2'] : $BL['be_article_cnt_button1'] ?>" />
			<input name="save" type="submit" class="button" value="<?php echo $BL['be_article_cnt_button3'] ?>" />
			&nbsp;&nbsp;&nbsp;&nbsp;
			<input name="new" type="button" class="button" value="<?php echo ucfirst($BL['be_msg_new']) ?>" onclick="location.href='<?php echo decode_entities(MODULE_HREF) ?>&adplace=1&edit=0';return false;" />
			<input name="close" type="button" class="button" value="<?php echo $BL['be_admin_struct_close'] ?>" onclick="location.href='<?php echo decode_entities(MODULE_HREF) ?>&listadplace=1';return false;" />		</td>
	</tr>
</table>

</form>