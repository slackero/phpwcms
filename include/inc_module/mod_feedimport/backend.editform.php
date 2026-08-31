<?php
// ----------------------------------------------------------------
// obligate check for phpwcms constants
if (!defined('PHPWCMS_ROOT')) {
	die("You Cannot Access This Script Directly, Have a Nice Day.");
}
// ----------------------------------------------------------------

?>
<h1 class="title mb-3"><i class="fas fa-rss text-warning me-2"></i><?php echo $BLM['listing_title'] ?></h1>

<div class="card">
	<div class="card-body">
		<form action="<?php echo MODULE_HREF ?>&amp;edit=<?php echo $plugin['data']['id'] ?>" method="post" id="address_form">
			<input type="hidden" name="id" value="<?php echo $plugin['data']['id'] ?>" />
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

		echo '<div class="form-group row">'.LF;
		echo '  <label for="file_'.$key.'" class="col-sm-2 col-form-label text-sm-end fw-bold">'.$BLM[$key].'</label>'.LF;
		echo '  <div class="col-sm-10">'.LF;
		echo '    <div class="input-group input-group-sm" style="max-width: 450px;">'.LF;
		echo '      <input name="file_'.$key.'" type="text" id="file_'.$key.'" class="form-control text-muted bg-light" value="'.$plugin['file_'.$key].'" readonly />'.LF;
		echo '      <input type="hidden" name="'.$key.'" id="fileid_'.$key.'" value="'.html($plugin['data'][$key]).'" />'.LF;
		echo '      '.LF;
		echo '        <button class="btn btn-secondary" type="button" title="'.$BL['be_cnt_openfilebrowser'].'" onclick="openFileBrowser(\''.$key.'\');"><i class="fas fa-folder-open"></i></button>'.LF;
		echo '        <button class="btn btn-danger" type="button" title="'.$BL['be_cnt_delfile'].'" onclick="deleteIdData(\''.$key.'\',this);"><i class="fas fa-trash-alt"></i></button>'.LF;
		echo '      '.LF;
		echo '    </div>'.LF;
		echo '  </div>'.LF;
		echo '</div>'.LF;
							break;


			case 'HIDDEN':
		$plugin['hidden_fields'] .= '<input type="hidden" name="'.$key.'" id="'.$key.'" value="'.html($plugin['data'][$key]).'" />';
							break;


			case 'HIDDENINT':
		$plugin['hidden_fields'] .= '<input type="hidden" name="'.$key.'" id="'.$key.'" value="'.(empty($plugin['data'][$key]) ? 0 : intval($plugin['data'][$key])).'" />';
							break;


			case 'STRING':
		echo '<div class="form-group row">'.LF;
		echo '  <label for="'.$key.'" class="col-sm-2 col-form-label text-sm-end fw-bold">'.$BLM[$key].'</label>'.LF;
		echo '  <div class="col-sm-10">'.LF;
		echo '    <input name="'.$key.'" type="text" id="'.$key.'" class="form-control form-control-sm" value="'.html($plugin['data'][$key]).'" maxlength="200" style="max-width: 450px;" />'.LF;
		echo '  </div>'.LF;
		echo '</div>'.LF;
							break;

			case 'STRING-DISABLED':
		echo '<div class="form-group row">'.LF;
		echo '  <label for="'.$key.'" class="col-sm-2 col-form-label text-sm-end fw-bold">'.$BLM[$key].'</label>'.LF;
		echo '  <div class="col-sm-10">'.LF;
		echo '    <input name="'.$key.'" type="text" id="'.$key.'" class="form-control form-control-sm bg-light" value="'.html($plugin['data'][$key]).'" readonly style="max-width: 450px;" />'.LF;
		echo '  </div>'.LF;
		echo '</div>'.LF;
							break;

			case 'TEXTAREA-DISABLED':
		echo '<div class="form-group row">'.LF;
		echo '  <label class="col-sm-2 col-form-label text-sm-end fw-bold">'.$BLM[$key].'</label>'.LF;
		echo '  <div class="col-sm-10">'.LF;
		echo '    <textarea class="form-control form-control-sm bg-light" rows="2" readonly onclick="this.focus();this.select();" style="max-width: 450px;">'.html($plugin['data'][$key]).'</textarea>'.LF;
		echo '  </div>'.LF;
		echo '</div>'.LF;
							break;


			case 'TEXTAREA':
		echo '<div class="form-group row">'.LF;
		echo '  <label for="'.$key.'" class="col-sm-2 col-form-label text-sm-end fw-bold">'.$BLM[$key].'</label>'.LF;
		echo '  <div class="col-sm-10">'.LF;
		echo '    <textarea name="'.$key.'" id="'.$key.'" class="form-control form-control-sm" rows="4" style="max-width: 450px;">'.html($plugin['data'][$key]).'</textarea>'.LF;
		echo '  </div>'.LF;
		echo '</div>'.LF;
							break;


			case 'INT':
			case 'FLOAT':
		echo '<div class="form-group row">'.LF;
		echo '  <label for="'.$key.'" class="col-sm-2 col-form-label text-sm-end fw-bold">'.$BLM[$key].'</label>'.LF;
		echo '  <div class="col-sm-10">'.LF;
		echo '    <input name="'.$key.'" type="text" id="'.$key.'" class="form-control form-control-sm" value="'.html($plugin['data'][$key]).'" maxlength="200" style="max-width: 150px;" />'.LF;
		echo '  </div>'.LF;
		echo '</div>'.LF;
							break;


			case 'CHECK':
		echo '<div class="form-group row">'.LF;
		echo '  <div class="col-sm-10 offset-sm-2">'.LF;
		echo '    <div class="form-check">'.LF;
		echo '      <input type="checkbox" class="form-check-input" name="'.$key.'" id="'.$key.'" value="1"';
		is_checked($plugin['data'][$key], 1);
		echo ' />';
		echo '      <label class="form-check-label" for="'.$key.'">'.$BLM[$key].'</label>'.LF;
		echo '    </div>'.LF;
		echo '  </div>'.LF;
		echo '</div>'.LF;
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

		echo '<div class="form-group row">'.LF;
		echo '  <label for="'.$key.'" class="col-sm-2 col-form-label text-sm-end fw-bold">'.$BLM[$key].'</label>'.LF;
		echo '  <div class="col-sm-10">'.LF;
		if($value == 'MULTISELECT') {
			echo '    <select id="'.$key.'" class="form-select form-select-sm" style="max-width:450px;" name="'.$key.'[]" multiple="multiple" size="6">'.LF;
		} else {
			echo '    <select id="'.$key.'" class="form-select form-select-sm" style="max-width:450px;" name="'.$key.'">'.LF;
		}

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

		echo '    </select>'.LF;

		if(count($_option_remember)) {
			echo '    <div class="text-muted small mt-1"><em>'.html(implode(', ', $_option_remember)).'</em></div>'.LF;
		}
		echo '  </div>'.LF;
		echo '</div>'.LF;

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

		echo '<div class="form-group row">'.LF;
		echo '  <label class="col-sm-2 col-form-label text-sm-end fw-bold">'.$BLM[$key].'</label>'.LF;
		echo '  <div class="col-sm-10">'.LF;
		echo '    <ul class="list-unstyled p-2 border bg-white rounded" style="max-height: 200px; overflow-y: auto; max-width: 450px;">' . LF;

		$_options_pre = array();
		$_options_end = array();

		foreach($plugin['multicheck'] as $item => $row) {

			$_selected = false;
			$_option  = '	<li><div class="form-check">';
			$_option .= '     <input type="checkbox" class="form-check-input" id="'.$key.'_'.html($item).'" name="'.$key.'[]" value="' . html($item) .'"';
			if( in_array($item, $plugin['data'][$key]) ) {
					$_option .= ' checked="checked"';
					$_selected = true;
			}
			$_option .= ' />';
			$_option .= '     <label class="form-check-label" for="'.$key.'_'.html($item).'">' . html(trim($row)) . '</label>';
			$_option .= '   </div></li>';

			if($_selected) {
				$_options_pre[] = $_option;
			} else {
				$_options_end[] = $_option;
			}

		}

		echo implode(LF, $_options_pre) . LF . implode(LF, $_options_end) . LF;

		echo '    </ul>'.LF;
		echo '  </div>'.LF;
		echo '</div>'.LF;

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

		echo '<div class="form-group row">'.LF;
		echo '  <label class="col-sm-2 col-form-label text-sm-end fw-bold">'.$BLM[$key].'</label>'.LF;
		echo '  <div class="col-sm-10 pt-1">'.LF;

		foreach($plugin['radiobutton'] as $item => $row) {

			echo '    <div class="form-check form-check-inline">'.LF;
			echo '      <input type="radio" class="form-check-input" id="'.$key.'_'.html($item).'" name="'.$key.'" value="' . html($item) .'"';
			if( strval($item) == strval($plugin['data'][$key]) ) {
					echo ' checked="checked"';
			}
			echo ' />'.LF;
			echo '      <label class="form-check-label" for="'.$key.'_'.html($item).'">' . html(trim($row)) . '</label>'.LF;
			echo '    </div>'.LF;

		}

		echo '  </div>'.LF;
		echo '</div>'.LF;

							break;


			case 'DATESELECT':

		initJsCalendar();

		echo '<div class="form-group row align-items-center">'.LF;
		echo '  <label for="'.$key.'" class="col-sm-2 col-form-label text-sm-end fw-bold">'.$BLM[$key].'</label>'.LF;
		echo '  <div class="col-sm-10">'.LF;
		echo '    <div class="input-group input-group-sm" style="max-width: 220px;">'.LF;
		echo '      <input type="text" class="form-control datetimepicker-input" name="'.$key.'" id="'.$key.'" value="'.html($plugin['data'][$key]).'" maxlength="10" placeholder="'.$BL['default_date_format'].'" autocomplete="off" />'.LF;
		echo '      <span class="input-group-text btn-blue" style="cursor:pointer;" onclick="document.getElementById(\''. $key .'\')._flatpickr&&document.getElementById(\''. $key .'\')._flatpickr.open();"><i class="far fa-calendar-alt fa-fw"></i></span>'.LF;
		echo '    </div>'.LF;
		echo '    <script type="text/javascript">'.LF;
		echo '    $(function () {'.LF;
		echo '      flatpickr("#'.$key.'", { dateFormat: \'d.m.Y\', allowInput: true });'.LF;
		echo '    });'.LF;
		echo '    </script>'.LF;
		echo '  </div>'.LF;
		echo '</div>'.LF;
				break;

			case 'DECIMAL':

		// needs load decimal mask (MooTools removed)
		if(empty($plugin['meio.mask_loaded'])) {
			$plugin['meio.mask_loaded'] = true;
		}

		echo '<div class="form-group row">'.LF;
		echo '  <label for="'.$key.'" class="col-sm-2 col-form-label text-sm-end fw-bold">'.$BLM[$key].'</label>'.LF;
		echo '  <div class="col-sm-10">'.LF;
		echo '    <div class="form-inline">'.LF;
		echo '      <input name="'.$key.'" type="text" id="'.$key.'" class="form-control form-control-sm me-2 '.$BLM[$key.'_class'].'" value="'.html(decformat($plugin['data'][$key])).'" maxlength="200" style="max-width: 150px;" /> '.$BLM[$key.'_add'].LF;
		echo '    </div>'.LF;
		echo '  </div>'.LF;
		echo '</div>'.LF;
							break;


		}

	}
?>

			<div class="form-group row mt-4 mb-0">
				<div class="col-sm-10 offset-sm-2">
					<button name="submit" type="submit" class="btn btn-sm btn-blue me-1"><i class="fa-solid fa-rotate me-1"></i> <?php echo empty($plugin['data']['id']) ? $BL['be_admin_fcat_button2'] : $BL['be_article_cnt_button1'] ?></button>
					<button name="save" type="submit" class="btn btn-sm btn-blue ms-1"><i class="fa-solid fa-check me-1"></i> <?php echo $BL['be_article_cnt_button3'] ?></button>
					<a href="<?php echo decode_entities(MODULE_HREF) ?>&amp;edit=0" class="btn btn-sm btn-blue ms-3"><i class="fa-solid fa-plus me-1"></i> <?php echo ucfirst($BL['be_msg_new']) ?></a>
					<a href="<?php echo decode_entities(MODULE_HREF) ?>" class="btn btn-sm btn-danger ms-3"><i class="fa-solid fa-times me-1"></i> <?php echo $BL['be_admin_struct_close'] ?></a>
					<button type="reset" class="btn btn-sm btn-dark ms-1"><i class="fa-solid fa-undo me-1"></i> <?php echo $BL['be_cnt_field']['reset'] ?></button>
				</div>
			</div>

			<?php echo $plugin['hidden_fields'] ?>
		</form>
	</div>
</div>