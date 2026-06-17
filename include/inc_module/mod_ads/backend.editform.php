<?php
/**
 * cmsGO!
 *
 * @author Pixels & Points GmbH <info@pixels-points.ch>
 * @copyright Copyright (c) 2002-2026, Pixels & Points GmbH
 * @license https://www.pixels-points.ch/cmsgo-license.html Pixels & Points cmsGO! license
 *
 * **/

// ----------------------------------------------------------------
// obligate check for cmsGO! constants
if (!defined('CMSGO_ROOT')) {
	die("You Cannot Access This Script Directly, Have a Nice Day.");
}
// ----------------------------------------------------------------

// Module/Plug-in Ads/Banner Management

initJsCalendar();

?>
<h1 class="title mb-3"><?php echo $BLM['form_title'] ?></h1>

<div class="card">
	<div class="card-body">
		<form action="<?php echo MODULE_HREF ?>&amp;campaign=1&amp;edit=<?php echo $plugin['data']['adcampaign_id'] ?>" method="post">
			<input type="hidden" name="adcampaign_id" value="<?php echo $plugin['data']['adcampaign_id'] ?>" />

			<div class="form-group row align-items-center">
				<label class="col-sm-2 col-form-label text-sm-right font-weight-bold"><?php echo $BL['be_cnt_last_edited'] ?></label>
				<div class="col-sm-10">
					<span class="text-muted"><?php echo html(cmsgo_strtotime($plugin['data']['adcampaign_changed'], $BL['be_fprivedit_dateformat'], '')) ?></span>
					<?php if(!empty($plugin['data']['adcampaign_created'])): ?>
						<span class="text-muted ml-3 small">(<?php echo $BL['be_fprivedit_created'] ?>: <?php echo html(cmsgo_strtotime($plugin['data']['adcampaign_created'], $BL['be_fprivedit_dateformat'], '')) ?>)</span>
					<?php endif; ?>
				</div>
			</div>

			<div class="form-group row">
				<label for="adcampaign_title" class="col-sm-2 col-form-label text-sm-right font-weight-bold"><?php echo $BLM['campaign_entry'] ?></label>
				<div class="col-sm-10">
					<input name="adcampaign_title" type="text" id="adcampaign_title" class="form-control form-control-sm<?php if(!empty($plugin['error']['adcampaign_title'])) echo ' is-invalid'; ?>" value="<?php echo html($plugin['data']['adcampaign_title']) ?>" maxlength="200" />
				</div>
			</div>

			<div class="form-group row align-items-center">
				<label for="adcampaign_date_start" class="col-sm-2 col-form-label text-sm-right font-weight-bold"><?php echo $BLM['calendar_start'] ?></label>
				<div class="col-sm-auto">
					<div class="date input-group input-group-sm" id="datetimepickerstartdate">
						<input type="text" class="form-control datetimepicker" name="adcampaign_date_start" id="adcampaign_date_start" value="<?php echo html($plugin['data']['adcampaign_date_start']) ?>" maxlength="10" placeholder="<?php echo $BL['default_date_format'] ?>" />
						<div class="input-group-append">
							<span class="input-group-text btn-blue"><i class="far fa-calendar-alt fa-fw"></i></span>
						</div>
					</div>
				</div>
				<div class="col-sm-auto">
					<div class="date input-group input-group-sm" id="datetimepickerstarttime">
						<input type="text" class="form-control datetimepicker" name="adcampaign_time_start" id="adcampaign_time_start" value="<?php echo html($plugin['data']['adcampaign_time_start']) ?>" maxlength="5" placeholder="<?php echo $BL['default_time_format'] ?>" />
						<div class="input-group-append">
							<span class="input-group-text btn-blue"><i class="far fa-clock fa-fw"></i></span>
						</div>
					</div>
				</div>
			</div>

			<div class="form-group row align-items-center">
				<label for="adcampaign_date_end" class="col-sm-2 col-form-label text-sm-right font-weight-bold"><?php echo $BLM['calendar_end'] ?></label>
				<div class="col-sm-auto">
					<div class="date input-group input-group-sm" id="datetimepickerenddate">
						<input type="text" class="form-control datetimepicker" name="adcampaign_date_end" id="adcampaign_date_end" value="<?php echo html($plugin['data']['adcampaign_date_end']) ?>" maxlength="10" placeholder="<?php echo $BL['default_date_format'] ?>" />
						<div class="input-group-append">
							<span class="input-group-text btn-blue"><i class="far fa-calendar-alt fa-fw"></i></span>
						</div>
					</div>
				</div>
				<div class="col-sm-auto">
					<div class="date input-group input-group-sm" id="datetimepickerendtime">
						<input type="text" class="form-control datetimepicker" name="adcampaign_time_end" id="adcampaign_time_end" value="<?php echo html($plugin['data']['adcampaign_time_end']) ?>" maxlength="5" placeholder="<?php echo $BL['default_time_format'] ?>" />
						<div class="input-group-append">
							<span class="input-group-text btn-blue"><i class="far fa-clock fa-fw"></i></span>
						</div>
					</div>
				</div>
			</div>

			<div class="form-group row">
				<label for="adcampaign_format" class="col-sm-2 col-form-label text-sm-right font-weight-bold"><?php echo $BLM['ad_format'] ?></label>
				<div class="col-sm-10">
					<select name="adcampaign_format" id="adcampaign_format" class="custom-select custom-select-sm" onchange="setFormat(this.options[this.selectedIndex].value);">
						<?php
						$sql = 'SELECT * FROM '.DB_PREPEND.'cmsgo_ads_formats WHERE adformat_status=1';
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
							$_format_key = 'format_' . strtolower(str_replace(array(' ', '-'), '_', $_entry['value']['adformat_title']));
							$_format_title = isset($BLM[$_format_key]) ? $BLM[$_format_key] : $_entry['value']['adformat_title'];
							echo html($_format_title.' ('.$_entry['value']['adformat_width'].'x'.$_entry['value']['adformat_height'].')');
							echo '</option>'.LF;

							$plugin['ad_formats_js'][ $_entry['value']['adformat_id'] ]  = '		ad_formats['.$_entry['value']['adformat_id'].'] = ';
							$plugin['ad_formats_js'][ $_entry['value']['adformat_id'] ] .= '["'.$_entry['value']['adformat_width'].'", "';
							$plugin['ad_formats_js'][ $_entry['value']['adformat_id'] ] .= $_entry['value']['adformat_height'].'"];';
						}
						?>
					</select>
				</div>
			</div>

			<div class="form-group row">
				<div class="col-sm-10 offset-sm-2">
					<div class="form-row align-items-center">
						<div class="col-auto">
							<span class="small text-muted font-weight-bold mr-1"><?php echo $BL['be_admin_page_width'] ?>:</span>
							<input type="text" name="adcampaign_width" id="adcampaign_width" value="<?php echo $plugin['data']['adcampaign_data']['width'] ?>" class="form-control form-control-sm d-inline-block text-center" style="width: 60px;" readonly onfocus="this.blur()" />
							<span class="small text-muted ml-1"><?php echo $BLM['pixel'] ?></span>
						</div>
						<div class="col-auto">
							<span class="small text-muted font-weight-bold mx-2">/</span>
						</div>
						<div class="col-auto">
							<span class="small text-muted font-weight-bold mr-1"><?php echo $BL['be_admin_page_height'] ?>:</span>
							<input type="text" name="adcampaign_height" id="adcampaign_height" value="<?php echo $plugin['data']['adcampaign_data']['height'] ?>" class="form-control form-control-sm d-inline-block text-center" style="width: 60px;" readonly onfocus="this.blur()" />
							<span class="small text-muted ml-1"><?php echo $BLM['pixel'] ?></span>
						</div>
					</div>
					<script type="text/javascript">
					var ad_formats = [];
					<?php echo implode(LF, $plugin['ad_formats_js']) ?>

					function setFormat(value) {
						if(ad_formats[value]) {
							getFieldById('adcampaign_width').value = ad_formats[value][0];
							getFieldById('adcampaign_height').value = ad_formats[value][1];
						}
					}
					</script>
				</div>
			</div>

			<div class="form-group row">
				<label class="col-sm-2 col-form-label text-sm-right font-weight-bold"><?php echo $BLM['tracking_base'] ?></label>
				<div class="col-sm-10">
					<div class="form-row">
						<div class="col-auto">
							<div class="input-group input-group-sm">
								<input type="text" name="adcampaign_max_views" id="adcampaign_max_views" value="<?php echo empty($plugin['data']['adcampaign_data']['max_views']) ? '' : $plugin['data']['adcampaign_data']['max_views'] ?>" class="form-control" style="width: 80px;" />
								<div class="input-group-append">
									<span class="input-group-text"><?php echo $BLM['max_view'] ?></span>
								</div>
							</div>
						</div>
						<div class="col-auto">
							<div class="input-group input-group-sm">
								<input type="text" name="adcampaign_max_click" id="adcampaign_max_click" value="<?php echo empty($plugin['data']['adcampaign_data']['max_click']) ? '' : $plugin['data']['adcampaign_data']['max_click'] ?>" class="form-control" style="width: 80px;" />
								<div class="input-group-append">
									<span class="input-group-text"><?php echo $BLM['max_click'] ?></span>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>

			<div class="form-group row">
				<label for="adcampaign_url" class="col-sm-2 col-form-label text-sm-right font-weight-bold"><?php echo $BLM['target_url'] ?></label>
				<div class="col-sm-10">
					<input type="text" name="adcampaign_url" id="adcampaign_url" value="<?php echo empty($plugin['data']['adcampaign_data']['max_views']) ? '' : $plugin['data']['adcampaign_data']['url'] ?>" class="form-control form-control-sm" />
				</div>
			</div>

			<div class="form-group row">
				<label for="adcampaign_target" class="col-sm-2 col-form-label text-sm-right font-weight-bold"><?php echo $BLM['open_in'] ?></label>
				<div class="col-sm-10">
					<select name="adcampaign_target" id="adcampaign_target" class="custom-select custom-select-sm" style="max-width: 200px;">
						<option value=""<?php is_selected('', $plugin['data']['adcampaign_data']['target']) ?>>&nbsp;</option>
						<option value="_blank"<?php is_selected('_blank', $plugin['data']['adcampaign_data']['target']) ?>>_blank</option>
						<option value="_top"<?php is_selected('_top', $plugin['data']['adcampaign_data']['target']) ?>>_top</option>
						<option value="_self"<?php is_selected('_self', $plugin['data']['adcampaign_data']['target']) ?>>_self</option>
						<option value="_parent"<?php is_selected('_parent', $plugin['data']['adcampaign_data']['target']) ?>>_parent</option>
					</select>
				</div>
			</div>

			<div class="form-group row">
				<label for="adcampaign_comment" class="col-sm-2 col-form-label text-sm-right font-weight-bold"><?php echo $BLM['comment'] ?></label>
				<div class="col-sm-10">
					<textarea name="adcampaign_comment" id="adcampaign_comment" rows="5" class="form-control form-control-sm"><?php echo html($plugin['data']['adcampaign_comment']) ?></textarea>
				</div>
			</div>

			<div class="form-group row">
				<div class="col-sm-10 offset-sm-2">
					<div class="custom-control custom-checkbox">
						<input type="checkbox" class="custom-control-input" name="adcampaign_status" id="adcampaign_status" value="1"<?php is_checked($plugin['data']['adcampaign_status'], 1) ?> />
						<label class="custom-control-label" for="adcampaign_status"><?php echo $BL['be_cnt_activated'] ?></label>
					</div>
				</div>
			</div>

			<div class="form-group row mt-4 mb-0">
				<div class="col-sm-10 offset-sm-2">
					<button name="submit" type="submit" class="btn btn-sm btn-blue mr-1"><i class="fas fa-save mr-1"></i> <?php echo empty($plugin['data']['adcampaign_id']) ? $BL['be_admin_fcat_button2'] : $BL['be_article_cnt_button1'] ?></button>
					<button name="save" type="submit" class="btn btn-sm btn-success mr-1"><i class="fas fa-check mr-1"></i> <?php echo $BL['be_article_cnt_button3'] ?></button>
					<a href="<?php echo decode_entities(MODULE_HREF) ?>&amp;campaign=1&amp;edit=0" class="btn btn-sm btn-info mr-1"><i class="fas fa-plus-circle mr-1"></i> <?php echo ucfirst($BL['be_msg_new']) ?></a>
					<a href="<?php echo decode_entities(MODULE_HREF) ?>" class="btn btn-sm btn-secondary"><i class="fas fa-times mr-1"></i> <?php echo $BL['be_admin_struct_close'] ?></a>
				</div>
			</div>
		</form>
	</div>
</div>

<script type="text/javascript">
$(function () {
	$('#datetimepickerstartdate').datetimepicker({
		locale: 'de-ch',
		format: "DD.MM.YYYY",
		showClose: true
	});

	$('#datetimepickerstarttime').datetimepicker({
		locale: 'de-ch',
		format: "H:mm",
		showClose: true
	});

	$('#datetimepickerenddate').datetimepicker({
		locale: 'de-ch',
		format: "DD.MM.YYYY",
		showClose: true
	});

	$('#datetimepickerendtime').datetimepicker({
		locale: 'de-ch',
		format: "H:mm",
		showClose: true
	});
});
</script>
