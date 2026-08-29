<?php
/**
 * phpwcms
 *
 * @author Oliver Georgi <og@phpwcms.org>
 * @copyright Copyright (c) 2002-2026, Oliver Georgi
 * @license http://opensource.org/licenses/GPL-2.0 GNU GPL-2
 *
 **/

// ----------------------------------------------------------------
// obligate check for phpwcms constants
if (!defined('PHPWCMS_ROOT')) {
	die("You Cannot Access This Script Directly, Have a Nice Day.");
}
// ----------------------------------------------------------------

// Module/Plug-in Ads/Banner Management


initJsCalendar();
$BE['HEADER']['ads.js']				= getJavaScriptSourceLink($phpwcms['modules'][$module]['dir'].'template/ads.js');

?>
<h1 class="title mb-3"><?php echo $BLM['form_title'] ?></h1>

<div class="card">
	<div class="card-body">
		<form action="<?php echo MODULE_HREF ?>&amp;campaign=1&amp;edit=<?php echo $plugin['data']['adcampaign_id'] ?>" method="post" enctype="multipart/form-data">
			<input type="hidden" name="adcampaign_id" value="<?php echo $plugin['data']['adcampaign_id'] ?>" />

			<div class="form-group row align-items-center">
				<label class="col-sm-3 col-form-label text-sm-end fw-bold"><?php echo $BL['be_cnt_last_edited'] ?></label>
				<div class="col-sm-9">
					<span class="text-muted"><?php echo html(date($BL['be_fprivedit_dateformat'], strtotime($plugin['data']['adcampaign_changed']))) ?></span>
					<?php if(!empty($plugin['data']['adcampaign_created'])): ?>
						<span class="text-muted ms-3 small">(<?php echo $BL['be_fprivedit_created'] ?>: <?php echo html(date($BL['be_fprivedit_dateformat'], strtotime($plugin['data']['adcampaign_created']))) ?>)</span>
					<?php endif; ?>
				</div>
			</div>

			<div class="form-group row">
				<label for="adcampaign_title" class="col-sm-3 col-form-label text-sm-end fw-bold"><?php echo $BLM['campaign_entry'] ?></label>
				<div class="col-sm-9">
					<input name="adcampaign_title" type="text" id="adcampaign_title" class="form-control form-control-sm<?php if(!empty($plugin['error']['adcampaign_title'])) echo ' is-invalid'; ?>" value="<?php echo html($plugin['data']['adcampaign_title']) ?>" maxlength="200" />
				</div>
			</div>

			<div class="form-group row">
				<label for="adcampaign_place" class="col-sm-3 col-form-label text-sm-end fw-bold"><?php echo $BLM['adplace'] ?></label>
				<div class="col-sm-9">
					<select name="adcampaign_place" id="adcampaign_place" class="form-select form-select-sm" onchange="setFormat(this.options[this.selectedIndex].value);">
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
							$_format_key = 'format_' . strtolower(str_replace(array(' ', '-'), '_', $_entry['value']['adformat_title']));
							$_format_title = isset($BLM[$_format_key]) ? $BLM[$_format_key] : $_entry['value']['adformat_title'];
							echo html($_entry['value']['adplace_title'].' / '.$_format_title.' '.$_entry['value']['adplace_width'].'x'.$_entry['value']['adplace_height']);
							echo '</option>'.LF;

							$plugin['ad_place_js'][ $_entry['value']['adplace_id'] ]  = '		ad_place['.$_entry['value']['adplace_id'].'] = ';
							$plugin['ad_place_js'][ $_entry['value']['adplace_id'] ] .= '["'.$_entry['value']['adplace_width'].'", "';
							$plugin['ad_place_js'][ $_entry['value']['adplace_id'] ] .= $_entry['value']['adplace_height'].'"];';
						}
						?>
					</select>
				</div>
			</div>

			<div class="form-group row align-items-center">
				<label class="col-sm-3 col-form-label text-sm-end fw-bold"><?php echo $BL['be_ftptakeover_size'] ?></label>
				<div class="col-sm-9">
					<div class="row g-2 align-items-center">
						<div class="col-auto">
							<div class="input-group input-group-sm">
								<span class="input-group-text"><?php echo $BL['be_cnt_maxw'] ?></span>
								<input type="text" name="adcampaign_width" id="adcampaign_width" value="<?php echo $plugin['data']['adcampaign_data']['width'] ?>" class="form-control text-center" style="width: 60px;" readonly onfocus="this.blur()" />
								<span class="input-group-text">px</span>
							</div>
						</div>
						<div class="col-auto">
							<div class="input-group input-group-sm">
								<span class="input-group-text"><?php echo $BL['be_cnt_maxh'] ?></span>
								<input type="text" name="adcampaign_height" id="adcampaign_height" value="<?php echo $plugin['data']['adcampaign_data']['height'] ?>" class="form-control text-center" style="width: 60px;" readonly onfocus="this.blur()" />
								<span class="input-group-text">px</span>
							</div>
						</div>
					</div>
					<script type="text/javascript">
					var ad_place = [];
					<?php
					echo implode(LF, $plugin['ad_place_js']);
					if(empty($plugin['data']['adcampaign_place']) && count($plugin['ad_place_js'])) {
						echo LF.LF.'	 	setFormat('.key($plugin['ad_place_js']).');';
					}
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
					</script>
				</div>
			</div>

			<div class="form-group row">
				<label for="adcampaign_url" class="col-sm-3 col-form-label text-sm-end fw-bold"><?php echo $BLM['target_url'] ?></label>
				<div class="col-sm-9">
					<div class="row g-2">
						<div class="col-sm-8 mb-2 mb-sm-0">
							<input type="text" name="adcampaign_url" id="adcampaign_url" value="<?php
								if(!empty($plugin['data']['adcampaign_data']['url'])) {
									$plugin['data']['adcampaign_data']['url_html'] = html($plugin['data']['adcampaign_data']['url']);
									echo $plugin['data']['adcampaign_data']['url_html'];
								}
								?>" class="form-control form-control-sm" />
						</div>
						<div class="col-sm-4">
							<div class="input-group input-group-sm">
								
									<span class="input-group-text"><?php echo $BLM['open_in'] ?></span>
								
								<select name="adcampaign_target" id="adcampaign_target" class="form-select form-select-sm">
									<option value=""<?php is_selected('', $plugin['data']['adcampaign_data']['target']) ?>>&nbsp;</option>
									<option value="_blank"<?php is_selected('_blank', $plugin['data']['adcampaign_data']['target']) ?>>_blank</option>
									<option value="_top"<?php is_selected('_top', $plugin['data']['adcampaign_data']['target']) ?>>_top</option>
									<option value="_self"<?php is_selected('_self', $plugin['data']['adcampaign_data']['target']) ?>>_self</option>
									<option value="_parent"<?php is_selected('_parent', $plugin['data']['adcampaign_data']['target']) ?>>_parent</option>
								</select>
							</div>
						</div>
					</div>
				</div>
			</div>

			<div class="form-group row align-items-center">
				<label class="col-sm-3 col-form-label text-sm-end fw-bold"><?php echo $BLM['calendar_start'] ?></label>
				<div class="col-sm-9">
					<div class="d-flex flex-wrap flex-lg-nowrap align-items-center gap-2">
						<div class="input-group input-group-sm datetime-picker-group">
							<span class="input-group-text"><?php echo $BL['be_msg_from'] ?></span>
							<input type="text" class="form-control datetimepicker-input" name="adcampaign_date_start" id="adcampaign_date_start" value="<?php echo html($plugin['data']['adcampaign_date_start']) ?>" maxlength="10" placeholder="<?php echo $BL['default_date_format'] ?>" autocomplete="off" />
							<span class="input-group-text btn-blue" style="cursor:pointer;" onclick="document.getElementById('adcampaign_date_start')._flatpickr&&document.getElementById('adcampaign_date_start')._flatpickr.open();"><i class="far fa-calendar-alt fa-fw"></i></span>
							<input type="text" class="form-control datetimepicker-input" name="adcampaign_time_start" id="adcampaign_time_start" value="<?php echo html($plugin['data']['adcampaign_time_start']) ?>" maxlength="5" placeholder="<?php echo $BL['default_time_format'] ?>" autocomplete="off" />
							<span class="input-group-text btn-blue" style="cursor:pointer;" onclick="document.getElementById('adcampaign_time_start')._flatpickr&&document.getElementById('adcampaign_time_start')._flatpickr.open();"><i class="far fa-clock fa-fw"></i></span>
						</div>
						<div class="input-group input-group-sm datetime-picker-group">
							<span class="input-group-text"><?php echo $BL['be_article_aend'] ?></span>
							<input type="text" class="form-control datetimepicker-input" name="adcampaign_date_end" id="adcampaign_date_end" value="<?php echo html($plugin['data']['adcampaign_date_end']) ?>" maxlength="10" placeholder="<?php echo $BL['default_date_format'] ?>" autocomplete="off" />
							<span class="input-group-text btn-blue" style="cursor:pointer;" onclick="document.getElementById('adcampaign_date_end')._flatpickr&&document.getElementById('adcampaign_date_end')._flatpickr.open();"><i class="far fa-calendar-alt fa-fw"></i></span>
							<input type="text" class="form-control datetimepicker-input" name="adcampaign_time_end" id="adcampaign_time_end" value="<?php echo html($plugin['data']['adcampaign_time_end']) ?>" maxlength="5" placeholder="<?php echo $BL['default_time_format'] ?>" autocomplete="off" />
							<span class="input-group-text btn-blue" style="cursor:pointer;" onclick="document.getElementById('adcampaign_time_end')._flatpickr&&document.getElementById('adcampaign_time_end')._flatpickr.open();"><i class="far fa-clock fa-fw"></i></span>
						</div>
					</div>
				</div>
			</div>

			<div class="form-group row align-items-center">
				<label class="col-sm-3 col-form-label text-sm-end fw-bold"><?php echo $BLM['tracking_base'] ?></label>
				<div class="col-sm-9">
					<div class="row g-2 align-items-center">
						<div class="col-auto">
							<div class="input-group input-group-sm">
								<span class="input-group-text"><?php echo $BLM['max_view'] ?></span>
								<input type="number" name="adcampaign_max_views" id="adcampaign_max_views" value="<?php echo empty($plugin['data']['adcampaign_maxview']) ? '' : $plugin['data']['adcampaign_maxview'] ?>" class="form-control" style="width: 80px;" />
							</div>
						</div>
						<div class="col-auto">
							<div class="input-group input-group-sm">
								<span class="input-group-text"><?php echo $BLM['max_click'] ?></span>
								<input type="number" name="adcampaign_max_click" id="adcampaign_max_click" value="<?php echo empty($plugin['data']['adcampaign_maxclick']) ? '' : $plugin['data']['adcampaign_maxclick'] ?>" class="form-control" style="width: 80px;" />
							</div>
						</div>
						<div class="col-auto">
							<div class="input-group input-group-sm">
								<span class="input-group-text"><?php echo $BLM['max_view_user'] ?></span>
								<input type="number" name="adcampaign_max_viewuser" id="adcampaign_max_viewuser" value="<?php echo empty($plugin['data']['adcampaign_maxviewuser']) ? '' : $plugin['data']['adcampaign_maxviewuser'] ?>" class="form-control" style="width: 80px;" />
							</div>
						</div>
					</div>
				</div>
			</div>

			<div class="form-group row">
				<label class="col-sm-3 col-form-label text-sm-end fw-bold"><?php echo $BLM['ad_type'] ?></label>
				<div class="col-sm-9 pt-1">
					<div class="form-check form-check-inline">
						<input type="radio" id="adcampaign_type_0" name="adcampaign_type" class="form-check-input" value="0"<?php is_checked(0, $plugin['data']['adcampaign_type']) ?> />
						<label class="form-check-label" for="adcampaign_type_0"><?php echo $BLM['ad_type_0'] ?></label>
					</div>
					<div class="form-check form-check-inline">
						<input type="radio" id="adcampaign_type_1" name="adcampaign_type" class="form-check-input" value="1"<?php is_checked(1, $plugin['data']['adcampaign_type']) ?> />
						<label class="form-check-label" for="adcampaign_type_1"><?php echo $BLM['ad_type_1'] ?></label>
					</div>
					<div class="form-check form-check-inline">
						<input type="radio" id="adcampaign_type_2" name="adcampaign_type" class="form-check-input" value="2"<?php is_checked(2, $plugin['data']['adcampaign_type']) ?> />
						<label class="form-check-label" for="adcampaign_type_2"><?php echo $BLM['ad_type_2'] ?></label>
					</div>
					<div class="form-check form-check-inline">
						<input type="radio" id="adcampaign_type_3" name="adcampaign_type" class="form-check-input" value="3"<?php is_checked(3, $plugin['data']['adcampaign_type']) ?> />
						<label class="form-check-label" for="adcampaign_type_3"><?php echo $BLM['ad_type_3'] ?></label>
					</div>
					<div class="form-check form-check-inline">
						<input type="radio" id="adcampaign_type_4" name="adcampaign_type" class="form-check-input" value="4"<?php is_checked(4, $plugin['data']['adcampaign_type']) ?> />
						<label class="form-check-label" for="adcampaign_type_4"><?php echo $BLM['ad_type_6'] ?></label>
					</div>
				</div>
			</div>

			<?php
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
			?>

			<?php if(empty($plugin['data']['adcampaign_id'])): ?>
				<div class="form-group row">
					<div class="col-sm-9 offset-sm-3">
						<div class="p-3 mb-0 rounded" style="background-color: #fff3cd; border: 1px solid #ffeeba; color: #856404 !important; font-weight: bold;">
							<i class="fas fa-exclamation-triangle me-2" style="color: #856404 !important;"></i> <?php echo $BLM['ad_info'] ?>
						</div>
					</div>
				</div>
			<?php else: ?>
				<?php if(isset($plugin['error']['image'])): ?>
					<div class="form-group row">
						<div class="col-sm-9 offset-sm-3 text-danger">
							<?php echo $plugin['error']['image'] ?>
						</div>
					</div>
				<?php endif; ?>
				<div class="form-group row">
					<label class="col-sm-3 col-form-label text-sm-end fw-bold">
						<a href="#" onclick="showImageAds();return false;" title="Preview"><?php echo $BLM['ad_type_0'] ?> <i class="fas fa-external-link-alt small"></i></a>
					</label>
					<div class="col-sm-9">
						<div class="row g-2 align-items-center">
							<div class="col-auto">
								<select name="adcampaign_image" id="adcampaign_image" class="form-select form-select-sm" style="width: 200px;">
									<option value="">&nbsp;</option>
									<?php echo $plugin['data']['image'] ?>
								</select>
							</div>
							<div class="col-auto upload newimage">
								<input type="file" name="adcampaign_upload_image" id="adcampaign_upload_image" class="form-control-file form-control-sm" title="<?php echo $BLM['ad_upload_image'] ?>" accept="image/png,image/jpeg,image/gif,image/webp,.gif,.png,.webp,.jpeg,.jpg" />
							</div>
						</div>
					</div>
				</div>

				<?php if(isset($plugin['error']['flash'])): ?>
					<div class="form-group row">
						<div class="col-sm-9 offset-sm-3 text-danger">
							<?php echo $plugin['error']['flash'] ?>
						</div>
					</div>
				<?php endif; ?>
				<div class="form-group row">
					<label class="col-sm-3 col-form-label text-sm-end fw-bold">
						<a href="#" onclick="showFlashAds();return false;" title="Preview"><?php echo $BLM['ad_type_1'] ?> <i class="fas fa-external-link-alt small"></i></a>
					</label>
					<div class="col-sm-9">
						<div class="row g-2 align-items-center">
							<div class="col-auto">
								<select name="adcampaign_flash" id="adcampaign_flash" class="form-select form-select-sm" style="width: 200px;">
									<option value="">&nbsp;</option>
									<?php echo $plugin['data']['flash'] ?>
								</select>
							</div>
							<div class="col-auto upload newflash">
								<input type="file" name="adcampaign_upload_flash" id="adcampaign_upload_flash" class="form-control-file form-control-sm" title="<?php echo $BLM['ad_upload_flash'] ?>" accept=".swf" />
							</div>
						</div>
					</div>
				</div>

				<?php if(isset($plugin['error']['css'])): ?>
					<div class="form-group row">
						<div class="col-sm-9 offset-sm-3 text-danger">
							<?php echo $plugin['error']['css'] ?>
						</div>
					</div>
				<?php endif; ?>
				<div class="form-group row">
					<label class="col-sm-3 col-form-label text-sm-end fw-bold">CSS</label>
					<div class="col-sm-9">
						<div class="row g-2 align-items-center">
							<div class="col-auto">
								<select name="adcampaign_css" id="adcampaign_css" class="form-select form-select-sm" style="width: 200px;">
									<option value="">&nbsp;</option>
									<?php echo $plugin['data']['css'] ?>
								</select>
							</div>
							<div class="col-auto upload newcss">
								<input type="file" name="adcampaign_upload_css" id="adcampaign_upload_css" class="form-control-file form-control-sm" title="<?php echo $BLM['ad_upload_css'] ?>" accept="text/css,.css" />
							</div>
						</div>
					</div>
				</div>
			<?php endif; ?>

			<div class="form-group row">
				<label for="adcampaign_html" class="col-sm-3 col-form-label text-sm-end fw-bold">
					<a href="#" onclick="showHtmlAds();return false;" title="Preview"><?php echo $BLM['ad_type_2'] ?> <i class="fas fa-external-link-alt small"></i></a>
				</label>
				<div class="col-sm-9">
					<textarea name="adcampaign_html" id="adcampaign_html" rows="5" class="form-control form-control-sm code"><?php echo html($plugin['data']['adcampaign_data']['html']) ?></textarea>
				</div>
			</div>

			<div class="form-group row">
				<label for="adcampaign_alt_text" class="col-sm-3 col-form-label text-sm-end fw-bold"><?php echo $BLM['ad_alt_text'] ?></label>
				<div class="col-sm-9">
					<input name="adcampaign_alt_text" type="text" id="adcampaign_alt_text" class="form-control form-control-sm" value="<?php echo html($plugin['data']['adcampaign_data']['alt_text']) ?>" maxlength="200" />
				</div>
			</div>

			<div class="form-group row">
				<label for="adcampaign_title_text" class="col-sm-3 col-form-label text-sm-end fw-bold"><?php echo $BLM['ad_title_text'] ?></label>
				<div class="col-sm-9">
					<input name="adcampaign_title_text" type="text" id="adcampaign_title_text" class="form-control form-control-sm" value="<?php echo html($plugin['data']['adcampaign_data']['title_text']) ?>" maxlength="200" />
				</div>
			</div>

			<div class="form-group row align-items-center">
				<label class="col-sm-3 col-form-label text-sm-end fw-bold"><?php echo $BL['be_settings'] ?></label>
				<div class="col-sm-9">
					<div class="row g-2 align-items-center">
						<div class="col-auto">
							<div class="input-group input-group-sm">
								<span class="input-group-text"><?php echo $BLM['ad_bgcolor'] ?></span>
								<input type="text" name="adcampaign_bgcolor" id="adcampaign_bgcolor" value="<?php echo $plugin['data']['adcampaign_data']['bgcolor'] ?>" class="form-control" style="width: 80px;" maxlength="7" />
								<span class="input-group-text colorfield" id="bgcolor"<?php if(!empty($plugin['data']['adcampaign_data']['bgcolor'])) echo ' style="background-color:'.$plugin['data']['adcampaign_data']['bgcolor'].'"' ?>>&nbsp;&nbsp;&nbsp;</span>
							</div>
						</div>
						<div class="col-auto">
							<div class="input-group input-group-sm">
								<span class="input-group-text"><?php echo $BLM['ad_bordercolor'] ?></span>
								<input type="text" name="adcampaign_bordercolor" id="adcampaign_bordercolor" value="<?php echo $plugin['data']['adcampaign_data']['bordercolor'] ?>" class="form-control" style="width: 80px;" maxlength="7" />
								<span class="input-group-text colorfield" id="bordercolor"<?php if(!empty($plugin['data']['adcampaign_data']['bordercolor'])) echo ' style="background-color:'.$plugin['data']['adcampaign_data']['bordercolor'].'"' ?>>&nbsp;&nbsp;&nbsp;</span>
							</div>
						</div>
						<div class="col-auto">
							<div class="input-group input-group-sm">
								<span class="input-group-text"><?php echo $BLM['ad_flashversion'] ?></span>
								<input type="text" name="adcampaign_flashversion" id="adcampaign_flashversion" value="<?php echo $plugin['data']['adcampaign_data']['flashversion'] ?>" class="form-control text-center" style="width: 60px;" />
							</div>
						</div>
					</div>
				</div>
			</div>

			<div class="form-group row">
				<label for="adcampaign_comment" class="col-sm-3 col-form-label text-sm-end fw-bold"><?php echo $BLM['comment'] ?></label>
				<div class="col-sm-9">
					<textarea name="adcampaign_comment" id="adcampaign_comment" rows="5" class="form-control form-control-sm"><?php echo html($plugin['data']['adcampaign_comment']) ?></textarea>
				</div>
			</div>

			<div class="form-group row">
				<div class="col-sm-9 offset-sm-3">
					<div class="form-check mb-2">
						<?php if(empty($plugin['data']['adcampaign_id'])): ?>
							<input type="checkbox" class="form-check-input" name="adcampaign_status" id="adcampaign_status" value="1" disabled />
							<label class="form-check-label text-muted" for="adcampaign_status"><?php echo $BL['be_cnt_activated'] ?></label>
						<?php else: ?>
							<input type="checkbox" class="form-check-input" name="adcampaign_status" id="adcampaign_status" value="1"<?php is_checked($plugin['data']['adcampaign_status'], 1) ?> />
							<label class="form-check-label" for="adcampaign_status"><?php echo $BL['be_cnt_activated'] ?></label>
						<?php endif; ?>
					</div>
					<?php if(!empty($plugin['data']['adcampaign_id'])): ?>
						<div class="form-check">
							<input type="checkbox" class="form-check-input" name="adcampaign_duplicate" id="adcampaign_duplicate" value="1"<?php is_checked(empty($plugin['data']['adcampaign_duplicate'])?0:1, 1) ?> />
							<label class="form-check-label" for="adcampaign_duplicate"><?php echo $BLM['save_copy'] ?></label>
						</div>
					<?php endif; ?>
				</div>
			</div>

			<div class="form-group row mt-4 mb-0">
				<div class="col-sm-9 offset-sm-3">
					<?php if(empty($plugin['data']['adcampaign_id'])): ?>
						<button name="submit" type="submit" class="btn btn-sm btn-blue me-1"><i class="fa fa-rotate me-1"></i> <?php echo $BL['be_admin_fcat_button2'] ?></button>
					<?php else: ?>
						<button name="submit" type="submit" class="btn btn-sm btn-blue me-1"><i class="fa fa-rotate me-1"></i> <?php echo $BL['be_article_cnt_button1'] ?></button>
						<button name="save" type="submit" class="btn btn-sm btn-blue ms-1"><i class="fa fa-check me-1"></i> <?php echo $BL['be_article_cnt_button3'] ?></button>
					<?php endif; ?>
					<a href="<?php echo decode_entities(MODULE_HREF) ?>&amp;campaign=1&amp;edit=0" class="btn btn-sm btn-blue ms-3"><i class="fa fa-plus me-1"></i> <?php echo ucfirst($BL['be_msg_new']) ?></a>
					<a href="<?php echo decode_entities(MODULE_HREF) ?>&amp;listcampaign=1" class="btn btn-sm btn-danger ms-3"><i class="fa fa-times me-1"></i> <?php echo $BL['be_admin_struct_close'] ?></a>
				</div>
			</div>
		</form>
	</div>
</div>

<script type="text/javascript">
$(function () {
	flatpickr('#adcampaign_date_start', { dateFormat: 'd.m.Y', allowInput: true });
	flatpickr('#adcampaign_time_start', { enableTime: true, noCalendar: true, dateFormat: 'H:i', time_24hr: true, allowInput: true });
	flatpickr('#adcampaign_date_end',   { dateFormat: 'd.m.Y', allowInput: true });
	flatpickr('#adcampaign_time_end',   { enableTime: true, noCalendar: true, dateFormat: 'H:i', time_24hr: true, allowInput: true });
});
</script>
