<?php
// ----------------------------------------------------------------
// obligate check for cmsgo constants
if (!defined('CMSGO_ROOT')) {
   die("You Cannot Access This Script Directly, Have a Nice Day.");
}
// ----------------------------------------------------------------

$GLOBALS['BE']['HEADER']['optionselect.js'] = getJavaScriptSourceLink('include/inc_js/optionselect.js');
$GLOBALS['BE']['CSP']['connect-src'][] = 'nominatim.openstreetmap.org';

?>
<h1 class="title address-title mb-3">
    <?php echo $BLM['listing_title'] ?>
</h1>

<div class="card">
	<div class="card-body">
		<form action="<?php echo MODULE_HREF ?>&amp;edit=<?php echo $plugin['data']['detail_id'] ?>" method="post" id="address_form" name="articlecontent" onsubmit="selectAllOptions(document.articlecontent['detail_text3[]']);">
			<input type="hidden" name="detail_id" value="<?php echo $plugin['data']['detail_id'] ?>" />

			<?php if(isset($plugin['error'])): ?>
				<div class="alert alert-danger mb-3">
					<?php echo implode('<br />', $plugin['error']); ?>
				</div>
			<?php endif; ?>

			<div class="alert alert-info py-2 px-3 mb-4">
				<?php echo $BLM['forminfo'] ?>
			</div>

<?php
foreach($plugin['fields'] as $key => $value) {

	switch($value) {

		case 'STRING':
			echo '<div class="form-group row align-items-center">';
			echo '  <label for="'.$key.'" class="col-sm-2 col-form-label text-sm-right font-weight-bold">'.$BLM[$key].'</label>';
			echo '  <div class="col-sm-10"><input name="'.$key.'" type="text" id="'.$key.'" class="form-control form-control-sm" value="'.html_specialchars($plugin['data'][$key]).'" maxlength="200" /></div>';
			echo '</div>';
			break;

		case 'TEXTAREA':
			echo '<div class="form-group row">';
			echo '  <label for="'.$key.'" class="col-sm-2 col-form-label text-sm-right font-weight-bold">'.$BLM[$key].'</label>';
			echo '  <div class="col-sm-10"><textarea name="'.$key.'" id="'.$key.'" class="form-control form-control-sm" rows="4">'.html_specialchars($plugin['data'][$key]).'</textarea></div>';
			echo '</div>';
			break;

		case 'FLOAT':
			if ($key === 'detail_float2') {
				break;
			}
			echo '<div class="form-group row align-items-center">';
			echo '  <label class="col-sm-2 col-form-label text-sm-right font-weight-bold">' . $BLM['geo_pos'] . '</label>';
			echo '  <div class="col-sm-10">';
			echo '    <div class="form-row align-items-center">';
			echo '      <div class="col-sm-3 mb-sm-1">';
			echo '        <div class="input-group input-group-sm">';
			echo '          <div class="input-group-prepend"><span class="input-group-text">Lat</span></div>';
			echo '          <input name="detail_float1" type="text" id="detail_float1" class="form-control" value="'.html_specialchars($plugin['data']['detail_float1']).'" maxlength="200" />';
			echo '        </div>';
			echo '      </div>';
			echo '      <div class="col-sm-3 mb-sm-1">';
			echo '        <div class="input-group input-group-sm">';
			echo '          <div class="input-group-prepend"><span class="input-group-text">Lng</span></div>';
			echo '          <input name="detail_float2" type="text" id="detail_float2" class="form-control" value="'.html_specialchars($plugin['data']['detail_float2']).'" maxlength="200" />';
			echo '        </div>';
			echo '      </div>';
			echo '      <div class="col-auto mb-sm-1">';
			echo '        <button type="button" class="btn btn-sm btn-outline-secondary mr-1" onclick="getLocation(true);return false;"><i class="fas fa-map-marker-alt mr-1"></i> '.$BLM['get_coordinates'].'</button>';
			echo '        <button type="button" class="btn btn-sm btn-outline-secondary" onclick="showMap();return false;"><i class="fas fa-globe mr-1"></i> '.$BLM['show_map'].'</button>';
			echo '      </div>';
			echo '    </div>';
			echo '  </div>';
			echo '</div>';
			break;

 		case 'INT':
			echo '<div class="form-group row align-items-center">';
			echo '  <label for="'.$key.'" class="col-sm-2 col-form-label text-sm-right font-weight-bold">'.$BLM[$key].'</label>';
			echo '  <div class="col-sm-4"><input name="'.$key.'" type="text" id="'.$key.'" class="form-control form-control-sm" value="'.html_specialchars($plugin['data'][$key]).'" maxlength="10" /></div>';
			echo '</div>';
			break;

		case 'CHECK':
			echo '<div class="form-group row">';
			echo '  <div class="col-sm-10 offset-sm-2">';
			echo '    <div class="custom-control custom-checkbox">';
			echo '      <input type="checkbox" class="custom-control-input" name="'.$key.'" id="'.$key.'" value="1"';
			is_checked($plugin['data'][$key], 1);
			echo ' />';
			echo '      <label class="custom-control-label" for="'.$key.'">'.$BLM[$key].'</label>';
			echo '    </div>';
			echo '  </div>';
			echo '</div>';
			break;

		case 'SELECT':
		case 'MULTISELECT':
			$plugin['select'] = array();

			if(isset( $plugin['fields_' . $key ] )) {

				if(is_array($plugin['fields_' . $key ])) {

					$plugin['select'] = $plugin['fields_' . $key ];

				} elseif(is_string($plugin['fields_' . $key ])) {

					if(function_exists($plugin['fields_' . $key ])) {

						$plugin_function = $plugin['fields_' . $key ];

						$plugin['select'] = $plugin_function();

					}
				}
			}

			echo '<div class="form-group row">';
			echo '  <label for="'.$key.'" class="col-sm-2 col-form-label text-sm-right font-weight-bold">'.$BLM[$key].'</label>';
			echo '  <div class="col-sm-10">';
			echo '    <select id="'.$key.'" class="form-control form-control-sm" style="min-width:75px;" name="'.$key;
			echo $value === 'MULTISELECT' ? '[]" multiple="multiple" size="6"' : '"';
			echo '>';

			$_options_pre = array();
			$_options_end = array();
			$_option_remember = array();

			foreach($plugin['select'] as $item => $row) {

				$_selected = false;
				$_option  = '   <option value="' . html_specialchars($item) .'"';
				if($value === 'MULTISELECT') {
					if( in_array($item, $plugin['data'][$key]) ) {
						$_option .= ' selected="selected"';
						$_option_remember[] = $row;
						$_selected = true;
					}
				} else {
					if( $plugin['data'][$key] == $item ) {
						$_option .= ' selected="selected"';
						$_selected = true;
					}
				}
				$_option .= '>' . html_specialchars(trim($row)) . '</option>';

				if($_selected) {
					$_options_pre[] = $_option;
				} else {
					$_options_end[] = $_option;
				}

			}

			echo implode(LF, $_options_pre) . implode(LF, $_options_end);
			echo '    </select>';

			if(count($_option_remember)) {
				echo '    <div class="text-muted small mt-1"><em>'.html_specialchars(implode(', ', $_option_remember)).'</em></div>';
			}
			echo '  </div>';
			echo '</div>';

			break;

		case 'MULTICHECK':
			$plugin['multicheck'] = array();

			if(isset( $plugin['fields_' . $key ] )) {

				if(is_array($plugin['fields_' . $key ])) {

					$plugin['multicheck'] = $plugin['fields_' . $key ];

				} elseif(is_string($plugin['fields_' . $key ])) {

					if(function_exists($plugin['fields_' . $key ])) {

						$plugin_function = $plugin['fields_' . $key ];

						$plugin['multicheck'] = $plugin_function();

					}
				}
			}

			echo '<div class="form-group row">';
			echo '  <label class="col-sm-2 col-form-label text-sm-right font-weight-bold">'.$BLM[$key].'</label>';
			echo '  <div class="col-sm-10">';
			echo '    <ul class="multicheck list-unstyled p-2 border bg-white rounded" style="height: 21em; overflow: auto; max-width: 450px;">';

			$_options_pre = array();
			$_options_end = array();

			foreach($plugin['multicheck'] as $item => $row) {

				$_selected = false;
				$_option  = '   <li><div class="custom-control custom-checkbox">';
				$_option .= '     <input type="checkbox" class="custom-control-input" id="'.$key.'_'.html_specialchars($item).'" name="'.$key.'[]" value="' . html_specialchars($item) .'"';
				if( in_array($item, $plugin['data'][$key]) ) {
						$_option .= ' checked="checked"';
						$_selected = true;
				}
				$_option .= ' />';
				$_option .= '     <label class="custom-control-label" for="'.$key.'_'.html_specialchars($item).'">' . html_specialchars(trim($row)) . '</label>';
				$_option .= '   </div></li>';

				if($_selected) {
					$_options_pre[] = $_option;
				} else {
					$_options_end[] = $_option;
				}

			}

			echo implode(LF, $_options_pre) . implode(LF, $_options_end);
			echo '    </ul>';
			echo '  </div>';
			echo '</div>';

			break;

		case 'FILES':
			$plugin['count_file_items'] = (isset($plugin['data'][$key]['files']) && is_array($plugin['data'][$key]['files'])) ? count($plugin['data'][$key]['files']) : 0;

			echo '<div class="form-group row">';
			echo '  <label for="cfile_list" class="col-sm-2 col-form-label text-sm-right font-weight-bold">'.$BLM[$key].'</label>';
			echo '  <div class="col-sm-10">';
			echo '    <div class="form-row">';
			echo '      <div class="col mb-2 mb-sm-0">';
			echo '        <select name="'.$key.'[]" size="'.max(5, 3 + $plugin['count_file_items']).'" multiple class="form-control form-control-sm" id="cfile_list">';
			if($plugin['count_file_items']) {
				$file_sql = "SELECT f_id, f_name FROM ".DB_PREPEND.'cmsgo_file WHERE f_public=1 AND f_aktiv=1 AND f_kid=1 AND f_trash=0 AND f_id IN (' . implode(',', $plugin['data'][$key]['files']) . ')';
				$file_result = _dbQuery($file_sql);
				if(isset($file_result[0]['f_id'])) {
					foreach($plugin['data'][$key]['files'] as $file_id) {
						foreach($file_result as $file_row) {
							if(intval($file_id) === intval($file_row['f_id'])) {
								echo '<option value="'.$file_row['f_id'].'">'.html($file_row['f_name']).'</option>';
								break;
							}
						}
					}
				}
			}
			echo '        </select>';
			echo '      </div>';
			echo '      <div class="col-sm-auto">';
			echo '          <button type="button" class="modalButton btn btn-sm btn-blue mb-1" data-toggle="modal" data-target="#browserModal" data-src="filebrowser.php?opt=4&amp;target=nolist" title="'.$BL['be_cnt_openfilebrowser'].'"><i class="fa fa-folder-open fa-fw" aria-hidden="true"></i></button><br>';
			echo '          <button type="button" class="btn btn-sm btn-secondary mb-1" title="'.$BL['be_cnt_sortup'].'" onclick="moveOptionUp(document.articlecontent[\''.$key.'[]\']);return false;"><i class="fa fa-angle-up fa-fw" aria-hidden="true"></i></button><br>';
			echo '          <button type="button" class="btn btn-sm btn-secondary mb-1" title="'.$BL['be_cnt_sortdown'].'" onclick="moveOptionDown(document.articlecontent[\''.$key.'[]\']);return false;"><i class="fa fa-angle-down fa-fw" aria-hidden="true"></i></button><br>';
			echo '          <button type="button" class="btn btn-sm btn-danger mb-1" onclick="removeSelectedOptions(document.articlecontent[\''.$key.'[]\']);return false;" title="'.$BL['be_cnt_delfile'].'"><i class="far fa-trash-alt fa-fw" aria-hidden="true"></i></button>';
			echo '      </div>';
			echo '    </div>';
			echo '  </div>';
			echo '</div>';

			echo '<div class="form-group row">';
			echo '  <label for="'.$key.'_description" class="col-sm-2 col-form-label text-sm-right font-weight-bold">'.$BL['be_cnt_description'].'</label>';
			echo '  <div class="col-sm-10">';
			echo '    <textarea name="'.$key.'_description" id="'.$key.'_description" class="form-control form-control-sm autosize" rows="'.max(5, 3 + $plugin['count_file_items']).'" cols="40" >';
			if(isset($plugin['data'][$key]['descriptions']) && is_array($plugin['data'][$key]['descriptions']) && count($plugin['data'][$key]['descriptions'])) {
				foreach($plugin['data'][$key]['descriptions'] as $file_description) {
					echo html(implode(' | ', $file_description)) . LF;
				}
			}
			echo '    </textarea>';
			echo '    <small class="form-text text-muted mt-1">';
			echo $BL['be_cnt_description'] . ' | ' . $BL['be_fprivedit_filename'] . ' | ' . $BL['be_caption_file_title'] . ' | ' . $BL['be_admin_page_link'] . ' <em>' . $BL['be_cnt_target'] . '</em> | ' . $BL['be_caption_file_imagesize'] . ' | ' . $BL['be_copyright'] . '&nbsp;&crarr;&nbsp;&hellip;';
			echo '    </small>';
			echo '  </div>';
			echo '</div>';
			break;
	}
}
?>
			<div class="form-group row mt-4 mb-0">
				<div class="col-sm-10 offset-sm-2">
					<button name="submit" type="submit" class="btn btn-sm btn-blue mr-1"><i class="fas fa-save mr-1"></i> <?php echo empty($plugin['data']['detail_id']) ? $BL['be_admin_fcat_button2'] : $BL['be_article_cnt_button1'] ?></button>
					<button name="save" type="submit" class="btn btn-sm btn-success mr-1"><i class="fas fa-check mr-1"></i> <?php echo $BL['be_article_cnt_button3'] ?></button>
					<a href="<?php echo MODULE_HREF ?>&amp;edit=0" class="btn btn-sm btn-info mr-1"><i class="fas fa-address-card mr-1"></i> <?php echo ucfirst($BL['be_msg_new']) ?></a>
					<a href="<?php echo MODULE_HREF ?>" class="btn btn-sm btn-secondary mr-1"><i class="fas fa-times mr-1"></i> <?php echo $BL['be_admin_struct_close'] ?></a>
					<button type="reset" class="btn btn-sm btn-dark"><i class="fas fa-undo mr-1"></i> <?php echo $BL['be_cnt_field']['reset'] ?></button>
				</div>
			</div>
		</form>
	</div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    tryToSetLocation();
});

// Geocoding Functions
function tryToSetLocation() {
    if(document.getElementById('detail_float1')) {
        const lat = parseFloat(document.getElementById('detail_float1').value.trim(), 10);
        const long = parseFloat(document.getElementById('detail_float2').value.trim(), 10);
        if(isNaN(lat) || isNaN(long) || lat === 0 || long === 0 ) {
            getLocation();
        }
    }
}

function showLoader() {
    if (document.getElementById('geoLoader')) return;
    var loader = document.createElement('div');
    loader.id = 'geoLoader';
    loader.setAttribute('style', 'position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 9999; display: flex; align-items: center; justify-content: center;');
    loader.innerHTML = '<div class="spinner-border text-light" style="width: 3rem; height: 3rem;" role="status"><span class="sr-only">Loading...</span></div>';
    document.body.appendChild(loader);
}

function hideLoader() {
    var loader = document.getElementById('geoLoader');
    if (loader) {
        loader.parentNode.removeChild(loader);
    }
}

function getLocation(btn) {
    var address_line = getAddress();

    if (address_line == '') {
        if (btn != undefined || btn != null) {
            alert('<?php echo $BLM['proof_address_alert2']; ?>');
        }
        return;
    }

    if (btn) {
        showLoader();
    }

    // Call Nominatim geocoder
    geocodeWithNominatim(address_line, btn);
}

function geocodeWithNominatim(address_line, btn) {
    fetch('https://nominatim.openstreetmap.org/search?q=' + encodeURIComponent(address_line) + '&format=json&limit=1')
        .then(function(response) {
            return response.json();
        })
        .then(function(data) {
            hideLoader();
            if (data && data.length > 0) {
                document.getElementById('detail_float1').value = data[0].lat;
                document.getElementById('detail_float2').value = data[0].lon;
            } else {
                if(btn) {
                    alert(('<?php echo $BLM['proof_address_alert1']; ?>').replace('%s', address_line));
                }
            }
        })
        .catch(function(err) {
            hideLoader();
            if(btn) {
                alert("<?php echo $BLM['geocoding_error']; ?>" + err.message);
            }
        });
}

function getAddress() {
    let address = '';
    const street1 = document.getElementById('detail_street').value.trim();
    const street2 = document.getElementById('detail_add').value.trim();
    const zipcode = document.getElementById('detail_zip').value.trim();
    const cityname = document.getElementById('detail_city').value.trim();

    if(street1 === '' || zipcode === '' || cityname === '') {
        return '';
    }

    if(street1 !== '') {
        address += street1 + ', ';
    }
    if(street2 !== '') {
        address += street2 + ', ';
    }
    if(zipcode !== '') {
        address += zipcode + ' ';
    }
    if(cityname !== '') {
        address += cityname;
    }
    address += ', ' + document.getElementById('detail_country').options[ document.getElementById('detail_country').selectedIndex ].value;

    return address;
}

function showMap() {
    const lat = document.getElementById('detail_float1').value;
    const lng = document.getElementById('detail_float2').value;
    const adr = getAddress();

    if(lat && lng && adr) {
        let mapurl = 'http://maps.google.de/?hl=de&cd=1&q=';
        mapurl += encodeURIComponent( adr );
        mapurl += '&sll=' + lat + ',' + lng + '&z=16&iwloc=addr&om=1';
        window.open(mapurl, 'GoogleMap');
    } else {
        alert('<?php echo $BLM['proof_address']; ?>');
    }
}
</script>
