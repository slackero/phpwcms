<?php
// ----------------------------------------------------------------
// obligate check for phpwcms constants
if (!defined('PHPWCMS_ROOT')) {
	die("You Cannot Access This Script Directly, Have a Nice Day.");
}
// ----------------------------------------------------------------

?>
<h1 class="title" style="margin-bottom:10px;padding-left:21px;background:url(img/famfamfam/rss.png) no-repeat left center;"><?php echo $BLM['listing_title'] ?></h1>

<form action="<?php echo MODULE_HREF ?>&amp;edit=<?php echo $plugin['data']['id'] ?>" method="post" id="address_form" style="background:#F3F5F8;border-top:1px solid #92A1AF;border-bottom:1px solid #92A1AF;margin:0 0 5px 0;padding:10px 8px 15px 8px">
<input type="hidden" name="id" value="<?php echo $plugin['data']['id'] ?>" />
<table border="0" cellpadding="0" cellspacing="0" width="100%" summary="">
<?php

	$BE['HEADER']['form_css']  = '  <style type="text/css">
	ul.radiobutton {list-style:none;margin:0;padding:0;}
	ul.radiobutton li {list-style:none;margin:0;padding:0;}
	</style>';

	$plugin['hidden_fields'] = '';

	foreach($plugin['fields'] as $key => $value) {

		switch($value) {

			case 'FILE':

		if(empty($plugin['filebrowser_js'])) {

			$BE['HEADER']['fbjs']  = '  <script type="text/javascript">' . LF;
			$BE['HEADER']['fbjs'] .= "	var fbw = 400, fbh = 575;" . LF;
			$BE['HEADER']['fbjs'] .= "	if(screen.width !== undefined) fbw = Math.ceil(Math.max(screen.width / 6, fbw));" . LF;
			$BE['HEADER']['fbjs'] .= "	if(screen.height !== undefined) fbh = Math.ceil(Math.max(screen.height / 1.5, fbh));" . LF;
			$BE['HEADER']['fbjs'] .= "	function openFileBrowser(image_number){";
			$BE['HEADER']['fbjs'] .= "tmt_winOpen('filebrowser.php?opt=15&target=nolist&entry_id='+image_number,'imageBrowser',";
			$BE['HEADER']['fbjs'] .= "'width='+fbw+',height='+fbh+',left=8,top=8,scrollbars=yes,resizable=yes',1);return false;}".LF;
			$BE['HEADER']['fbjs'] .= "	function setIdName(image_number, file_id, file_name){";
			$BE['HEADER']['fbjs'] .= "if(file_id == null || file_name == null) return null;imageBrowser.close();";
			$BE['HEADER']['fbjs'] .= "$('fileid_'+image_number).value = file_id;$('file_'+image_number).value = file_name;}".LF;
			$BE['HEADER']['fbjs'] .= "	function deleteIdData(image_number, e) {"."$('file_'+image_number).value='';";
			$BE['HEADER']['fbjs'] .= "$('fileid_'+image_number).value='0';e.blur();return false;}".LF;
			$BE['HEADER']['fbjs'] .= '  </script>';

		}

		if(empty($plugin['file_'.$key]) && empty($plugin['data'][$key])) {
			$plugin['file_'.$key] = '';
		} elseif(!empty($plugin['file_'.$key])) {
			$plugin['file_'.$key] = html($plugin['file_'.$key]);
		} elseif($plugin['data'][$key]) {
			$plugin['file_'.$key] = getFileInformation($plugin['data'][$key]);
			$plugin['file_'.$key] = empty($plugin['file_'.$key][0]['f_name']) ? '' : html($plugin['file_'.$key][0]['f_name']);
		} else {
			$plugin['file_'.$key] = '';
		}

		echo '<tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="5" /></td></tr>'.LF;
		echo '<tr>'.LF;
		echo '<td align="right" class="chatlist">'.$BLM[$key].':&nbsp;</td>'.LF.'<td><table border="0" cellpadding="0" cellspacing="0" summary=""><tr><td>';
		echo '<input name="file_'.$key.'" type="text" id="file_'.$key.'" class="v12 width300 greyed" value="'.$plugin['file_'.$key].'" onfocus="this.blur();" />';
		echo '<input type="hidden" name="'.$key.'" id="fileid_'.$key.'" value="'.html($plugin['data'][$key]).'" /></td>';
		echo '<td>&nbsp;<a href="#" title="'.$BL['be_cnt_openfilebrowser'].'" onclick="return openFileBrowser(\''.$key.'\');"><img src="img/button/open_image_button.gif" alt="" width="20" height="15" border="0" /></a></td>';
		echo '<td>&nbsp;<a href="#" title="'.$BL['be_cnt_delfile'].'" onclick="return deleteIdData(\''.$key.'\',this);"><img src="img/button/del_image_button.gif" alt="" width="15" height="15" border="0" /></a></td>';

		echo '</tr></table></td>'.LF.'</tr>'.LF;
							break;


			case 'HIDDEN':
		$plugin['hidden_fields'] .= '<input type="hidden" name="'.$key.'" id="'.$key.'" value="'.html($plugin['data'][$key]).'" />';
							break;


			case 'HIDDENINT':
		$plugin['hidden_fields'] .= '<input type="hidden" name="'.$key.'" id="'.$key.'" value="'.(empty($plugin['data'][$key]) ? 0 : intval($plugin['data'][$key])).'" />';
							break;


			case 'STRING':

		echo '<tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="5" /></td></tr>'.LF;
		echo '<tr>'.LF;
		echo '<td align="right" class="chatlist">'.$BLM[$key].':&nbsp;</td>'.LF;
		echo '<td><input name="'.$key.'" type="text" id="'.$key.'" class="v12 width400" value="'.html($plugin['data'][$key]).'" size="30" maxlength="200" /></td>'.LF;
		echo '</tr>'.LF;
							break;

			case 'STRING-DISABLED':

		echo '<tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="5" /></td></tr>'.LF;
		echo '<tr>'.LF;
		echo '<td align="right" class="chatlist">'.$BLM[$key].':&nbsp;</td>'.LF;
		echo '<td><input name="'.$key.'" type="text" id="'.$key.'" class="v12 width400" value="'.html($plugin['data'][$key]).'" size="30" maxlength="200" disabled="disabled" /></td>'.LF;
		echo '</tr>'.LF;
							break;

			case 'TEXTAREA-DISABLED':

		echo '<tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="5" /></td></tr>'.LF;
		echo '<tr>'.LF;
		echo '<td align="right" class="chatlist tdtop6">'.$BLM[$key].':&nbsp;</td>'.LF;
		echo '<td><textarea class="width400" cols="30" rows="2" readonly="readonly" style="text-wrap:unrestricted" onclick="this.focus();this.select();">'.html($plugin['data'][$key]).'</textarea></td>'.LF;
		echo '</tr>'.LF;
							break;


			case 'TEXTAREA':

		echo '<tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="5" /></td></tr>'.LF;
		echo '<tr>'.LF;
		echo '<td align="right" class="chatlist tdtop6">'.$BLM[$key].':&nbsp;</td>'.LF;
		echo '<td><textarea name="'.$key.'" id="'.$key.'" class="width400" rows="4">'.html($plugin['data'][$key]).'</textarea></td>'.LF;
		echo '</tr>'.LF;
							break;


			case 'INT':
			case 'FLOAT':

		echo '<tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="5" /></td></tr>'.LF;
		echo '<tr>'.LF;
		echo '<td align="right" class="chatlist">'.$BLM[$key].':&nbsp;</td>'.LF;
		echo '<td><input name="'.$key.'" type="text" id="'.$key.'" class="v12 width150" value="'.html($plugin['data'][$key]).'" size="30" maxlength="200" /></td>'.LF;
		echo '</tr>'.LF;
							break;


			case 'CHECK':

		echo '<tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="5" /></td></tr>'.LF;
		echo '<tr>'.LF;
		echo '<td>&nbsp;</td>'.LF;
		echo '<td><table border="0" cellpadding="0" cellspacing="0" summary=""><tr><td><input type="checkbox" name="'.$key.'" id="'.$key.'" value="1"';
		is_checked($plugin['data'][$key], 1);
		echo ' /></td><td><label for="'.$key.'">'.$BLM[$key].'</label></td></tr></table></td>'.LF;
		echo '</tr>'.LF;
							break;


			case 'SELECT':
			case 'MULTISELECT':

		$plugin['select'] = array();

		if(isset( $plugin['fields_' . $key ] )) {

			if(is_array($plugin['fields_' . $key ])) {

				$plugin['select'] = $plugin['fields_' . $key ];

			// check if the string is a valid function to retrieve field options/values
			} elseif(is_string($plugin['fields_' . $key ])) {

				if(function_exists($plugin['fields_' . $key ])) {

					$plugin_function = $plugin['fields_' . $key ];

					$plugin['select'] = $plugin_function();

				}
				// maybe elseif here could be used to check string against imploded
				// array members separated by "," or any other delimeter

			}

		}

		echo '<tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="5" /></td></tr>'.LF;
		echo '<tr>'.LF;
		echo '<td align="right" class="chatlist tdtop4">'.$BLM[$key].':&nbsp;</td>'.LF;
		echo '<td><select id="'.$key.'" class="v12" style="min-width:75px;max-width:400px;" name="'.$key;
		echo $value == 'MULTISELECT' ? '[]" multiple="multiple" size="6"' : '"';
		echo '>' . LF;

		$_options_pre = array();
		$_options_end = array();
		$_option_remember = array();

		foreach($plugin['select'] as $item => $row) {

			$_selected = false;
			$_option  = '	<option value="' . html($item) .'"';
			if($value == 'MULTISELECT') {
				if( in_array($item, $plugin['data'][$key]) ) {
					$_option .= ' selected="selected"';
					$_option_remember[] = $row;
					$_selected = true;
				}
			} elseif( $plugin['data'][$key] == $item ) {
                $_option .= ' selected="selected"';
                $_selected = true;
            }
			$_option .= '>' . html(trim($row)) . '</option>';

			if($value == 'MULTISELECT' && $_selected) {
				$_options_pre[] = $_option;
			} else {
				$_options_end[] = $_option;
			}

		}

		echo implode(LF, $_options_pre) . LF . implode(LF, $_options_end) . LF;

		echo '</select></td>'.LF.'</tr>'.LF;

		if(count($_option_remember)) {
			echo '<tr>'.LF;
			echo '<td align="right">&nbsp;</td>'.LF;
			echo '<td class="tdtop3"><em>'.html(implode(', ', $_option_remember)).'</em></td>'.LF.'</tr>'.LF;
		}

							break;


			case 'MULTICHECK':

		$plugin['multicheck'] = array();

		if(isset( $plugin['fields_' . $key ] )) {

			if(is_array($plugin['fields_' . $key ])) {

				$plugin['multicheck'] = $plugin['fields_' . $key ];

			// check if the string is a valid function to retrieve field options/values
			} elseif(is_string($plugin['fields_' . $key ])) {

				if(function_exists($plugin['fields_' . $key ])) {

					$plugin_function = $plugin['fields_' . $key ];

					$plugin['multicheck'] = $plugin_function();

				}
				// maybe elseif here could be used to check string against imploded
				// array members separated by "," or any other delimeter

			}

		}

		echo '<tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="5" /></td></tr>'.LF;
		echo '<tr>'.LF;
		echo '<td align="right" class="chatlist tdtop4">'.$BLM[$key].':&nbsp;</td>'.LF;
		echo '<td><ul class="multicheck">' . LF;

		$_options_pre = array();
		$_options_end = array();

		foreach($plugin['multicheck'] as $item => $row) {

			$_selected = false;
			$_option  = '	<li><label><input type="checkbox" name="'.$key.'[]" value="' . html($item) .'"';
			if( in_array($item, $plugin['data'][$key]) ) {
					$_option .= ' checked="checked"';
					$_selected = true;
			}
			$_option .= ' />' . html(trim($row)) . '</label></li>';

			if($_selected) {
				$_options_pre[] = $_option;
			} else {
				$_options_end[] = $_option;
			}

		}

		echo implode(LF, $_options_pre) . LF . implode(LF, $_options_end) . LF;

		echo '</ul></td>'.LF.'</tr>'.LF;

							break;

			case 'RADIO':

		$plugin['radiobutton'] = array();

		if(isset( $plugin['fields_' . $key ] )) {

			if(is_array($plugin['fields_' . $key ])) {

				$plugin['radiobutton'] = $plugin['fields_' . $key ];

			// check if the string is a valid function to retrieve field options/values
			} elseif(is_string($plugin['fields_' . $key ])) {

				if(function_exists($plugin['fields_' . $key ])) {

					$plugin_function = $plugin['fields_' . $key ];

					$plugin['radiobutton'] = $plugin_function();

				}

			}

		}

		echo '<tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="5" /></td></tr>'.LF;
		echo '<tr>'.LF;
		echo '<td align="right" class="chatlist tdtop4">'.$BLM[$key].':&nbsp;</td>'.LF;
		echo '<td><ul class="radiobutton">' . LF;

		$_options_pre = array();

		foreach($plugin['radiobutton'] as $item => $row) {

			$_selected = false;
			$_option  = '	<li><label><input type="radio" name="'.$key.'" value="' . html($item) .'"';
			if( strval($item) == strval($plugin['data'][$key]) ) {
					$_option .= ' checked="checked"';
					$_selected = true;
			}
			$_option .= ' />' . html(trim($row)) . '</label></li>';

			$_options_pre[] = $_option;

		}

		echo implode(LF, $_options_pre) . LF;

		echo '</ul></td>'.LF.'</tr>'.LF;

							break;


			case 'DATESELECT':

		// needs MooTools 1.2 and load datepicker
		if(empty($plugin['date_select_loaded'])) {

			initMootools('1.2');

			$BE['HEADER']['datepicker.js']					= getJavaScriptSourceLink(TEMPLATE_PATH.'lib/datepicker/datepicker.js');
			$BE['HEADER']['datepicker.css']					= '  <link href="'.TEMPLATE_PATH.'lib/datepicker/datepicker.css" rel="stylesheet" type="text/css" />';
			$BE['HEADER']['datepicker_vista.css']			= '  <link href="'.TEMPLATE_PATH.'lib/datepicker/datepicker_vista/datepicker_vista.css" rel="stylesheet" type="text/css" />';
			$BE['HEADER']['datepicker']  = '  <script type="text/javascript">' . LF;
			$BE['HEADER']['datepicker'] .= '	window.addEvent("domready", function(){';
			$BE['HEADER']['datepicker'] .= "new DatePicker($$('.dateselect'), {";
			$BE['HEADER']['datepicker'] .= 	"pickerClass:'datepicker_vista', ";
			$BE['HEADER']['datepicker'] .= 	"positionOffset: {x: 110, y: -30}, ";
			$BE['HEADER']['datepicker'] .= 	"format: 'd.m.Y', allowEmpty: true, ";
			$BE['HEADER']['datepicker'] .= 	"inputOutputFormat: 'U', ";
			$BE['HEADER']['datepicker'] .= 	"days: ".$BLM['days'].", ";
			$BE['HEADER']['datepicker'] .= 	"months: ".$BLM['months'].", ";
			$BE['HEADER']['datepicker'] .= '});});' . LF;
			$BE['HEADER']['datepicker'] .= '  </script>';

			$plugin['date_select_loaded'] = true;
		}

		echo '<tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="5" /></td></tr>'.LF;
		echo '<tr>'.LF;
		echo '<td align="right" class="chatlist">'.$BLM[$key].':&nbsp;</td>'.LF;
		echo '<td><input name="'.$key.'" type="text" id="'.$key.'" class="v12 dateselect" value="'.html($plugin['data'][$key]).'" size="30" maxlength="10" /></td>'.LF;
		echo '</tr>'.LF;
							break;

			case 'DECIMAL':

		// needs MooTools 1.2 and MooTools More
		if(empty($plugin['meio.mask_loaded'])) {

			initMootools('1.2', array('Element/Element.Forms')); // need MooTools More and Element.Forms
			$BE['HEADER']['Meio.Mask.min.js'] = getJavaScriptSourceLink(TEMPLATE_PATH.'lib/meio.mask/Meio.Mask.min.js');

			$BE['HEADER']['meio.mask']  = '  <script type="text/javascript">' . LF;
			$BE['HEADER']['meio.mask'] .= '	window.addEvent("domready", function() {
			var meiomasks_eur = $$("input.decimal-eur");
			var meiomasks_int = $$("input.decimal-int");
			var meiomasks_cent = $$("input.decimal-cent");
			if(meiomasks_eur.length > 0) {
				meiomasks_eur.each(function(el){
					el.meiomask("' . $BLM['meio.mask'] .'", {
						autoEmpty: true,
						autoTab: true,
						alignText: true,
						decimal: "' . $BLM['price_dec_point'] . '",
						thousands: "' . $BLM['price_thousands_sep'] . '",
						precision: ' . $BLM['price_decimals'] . ',
						symbol: "€ "
					});
				});
			}
			if(meiomasks_int.length > 0) {
				meiomasks_int.each(function(el){
					el.meiomask("' . $BLM['meio.mask'] .'", {
						autoEmpty: true,
						autoTab: true,
						alignText: true,
						decimal: "' . $BLM['price_dec_point'] . '",
						thousands: "' . $BLM['price_thousands_sep'] . '",
						precision: ' . $BLM['price_decimalint'] . ',
						symbol: ""
					});
				});
			}
			if(meiomasks_cent.length > 0) {
				meiomasks_cent.each(function(el){
					el.meiomask("' . $BLM['meio.mask'] .'", {
						autoEmpty: true,
						autoTab: true,
						alignText: true,
						decimal: "' . $BLM['price_dec_point'] . '",
						thousands: "' . $BLM['price_thousands_sep'] . '",
						precision: ' . $BLM['price_decimals'] . ',
						symbol: "Cent "
					});
				});
			}
		});' . LF;
			$BE['HEADER']['meio.mask'] .= '  </script>';

			$plugin['meio.mask_loaded'] = true;
		}

		echo '<tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="5" /></td></tr>'.LF;
		echo '<tr>'.LF;
		echo '<td align="right" class="chatlist">'.$BLM[$key].':&nbsp;</td>'.LF;
		echo '<td><input name="'.$key.'" type="text" id="'.$key.'" class="v12 width100 '.$BLM[$key.'_class'].'" ';
		echo 'value="'.html(decformat($plugin['data'][$key])).'" size="30" maxlength="200" /> '.$BLM[$key.'_add'].'</td>'.LF;
		echo '</tr>'.LF;
							break;


		}

	}
?>

	<tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="10" /></td></tr>

	<tr>
		<td>&nbsp;</td>
		<td>
			<input name="submit" type="submit" class="button" value="<?php echo empty($plugin['data']['id']) ? $BL['be_admin_fcat_button2'] : $BL['be_article_cnt_button1'] ?>" />
			<input name="save" type="submit" class="button" value="<?php echo $BL['be_article_cnt_button3'] ?>" />
			&nbsp;&nbsp;&nbsp;&nbsp;
			<input name="new" type="button" class="button" value="<?php echo ucfirst($BL['be_msg_new']) ?>" onclick="location.href='<?php echo MODULE_HREF ?>&amp;edit=0';return false;" />
			<input name="close" type="button" class="button" value="<?php echo $BL['be_admin_struct_close'] ?>" onclick="location.href='<?php echo MODULE_HREF ?>';return false;" />
			<input type="reset" class="button" value="<?php echo $BL['be_cnt_field']['reset'] ?>" />
		</td>
	</tr>

</table>

<?php echo $plugin['hidden_fields'] ?>

</form>