<?php
/**
 * phpwcms
 *
 * @author Oliver Georgi <og@phpwcms.org>
 * @copyright Copyright (c) 2002-2026, Oliver Georgi
 * @license http://opensource.org/licenses/GPL-2.0 GNU GPL-2
 *
 * **/

// ----------------------------------------------------------------
// obligate check for phpwcms constants
if (!defined('PHPWCMS_ROOT')) {
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
				<label class="col-sm-3 col-form-label text-sm-end fw-bold"><?php echo $BL['be_cnt_last_edited'] ?></label>
				<div class="col-sm-9">
					<span class="text-muted"><?php echo html(phpwcms_strtotime($plugin['data']['adcampaign_changed'], $BL['be_fprivedit_dateformat'], '')) ?></span>
					<?php if(!empty($plugin['data']['adcampaign_created'])): ?>
						<span class="text-muted ms-3 small">(<?php echo $BL['be_fprivedit_created'] ?>: <?php echo html(phpwcms_strtotime($plugin['data']['adcampaign_created'], $BL['be_fprivedit_dateformat'], '')) ?>)</span>
					<?php endif; ?>
				</div>
			</div>

			<div class="form-group row">
				<label for="adcampaign_title" class="col-sm-3 col-form-label text-sm-end fw-bold"><?php echo $BLM['campaign_entry'] ?></label>
				<div class="col-sm-9">
					<input name="adcampaign_title" type="text" id="adcampaign_title" class="form-control form-control-sm<?php if(!empty($plugin['error']['adcampaign_title'])) echo ' is-invalid'; ?>" value="<?php echo html($plugin['data']['adcampaign_title']) ?>" maxlength="200" />
				</div>
			</div>

			<div class="form-group row align-items-center">
				<label class="col-sm-3 col-form-label text-sm-end fw-bold"><?php echo $BLM['calendar_start'] ?></label>
				<div class="col-sm-9">
					<div class="d-flex flex-wrap flex-lg-nowrap align-items-center gap-2">
						<div class="input-group input-group-sm datetime-picker-group">
							<span class="input-group-text"><?php echo $BL['be_msg_from'] ?></span>
							<input type="text" class="form-control datetimepicker-input" name="adcampaign_date_start" id="adcampaign_date_start" value="<?php echo html($plugin['data']['adcampaign_date_start']) ?>" maxlength="10" placeholder="<?php echo $BL['default_date_format'] ?>" autocomplete="off" />
							<span class="input-group-text btn-blue" style="cursor:pointer;" onclick="document.getElementById('adcampaign_date_start')._flatpickr&&document.getElementById('adcampaign_date_start')._flatpickr.open();"><i class="fa-regular fa-calendar-alt fa-fw"></i></span>
							<input type="text" class="form-control datetimepicker-input" name="adcampaign_time_start" id="adcampaign_time_start" value="<?php echo html($plugin['data']['adcampaign_time_start']) ?>" maxlength="5" placeholder="<?php echo $BL['default_time_format'] ?>" autocomplete="off" />
							<span class="input-group-text btn-blue" style="cursor:pointer;" onclick="document.getElementById('adcampaign_time_start')._flatpickr&&document.getElementById('adcampaign_time_start')._flatpickr.open();"><i class="fa-regular fa-clock fa-fw"></i></span>
						</div>
						<div class="input-group input-group-sm datetime-picker-group">
							<span class="input-group-text"><?php echo $BL['be_article_aend'] ?></span>
							<input type="text" class="form-control datetimepicker-input" name="adcampaign_date_end" id="adcampaign_date_end" value="<?php echo html($plugin['data']['adcampaign_date_end']) ?>" maxlength="10" placeholder="<?php echo $BL['default_date_format'] ?>" autocomplete="off" />
							<span class="input-group-text btn-blue" style="cursor:pointer;" onclick="document.getElementById('adcampaign_date_end')._flatpickr&&document.getElementById('adcampaign_date_end')._flatpickr.open();"><i class="fa-regular fa-calendar-alt fa-fw"></i></span>
							<input type="text" class="form-control datetimepicker-input" name="adcampaign_time_end" id="adcampaign_time_end" value="<?php echo html($plugin['data']['adcampaign_time_end']) ?>" maxlength="5" placeholder="<?php echo $BL['default_time_format'] ?>" autocomplete="off" />
							<span class="input-group-text btn-blue" style="cursor:pointer;" onclick="document.getElementById('adcampaign_time_end')._flatpickr&&document.getElementById('adcampaign_time_end')._flatpickr.open();"><i class="fa-regular fa-clock fa-fw"></i></span>
						</div>
					</div>
				</div>
			</div>

			<div class="form-group row">
				<label for="adcampaign_format" class="col-sm-3 col-form-label text-sm-end fw-bold"><?php echo $BLM['ad_format'] ?></label>
				<div class="col-sm-9">
					<select name="adcampaign_format" id="adcampaign_format" class="form-select form-select-sm" onchange="setFormat(this.options[this.selectedIndex].value);">
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
				<div class="col-sm-9 offset-sm-3">
					<div class="row g-2 align-items-center">
						<div class="col-auto">
							<span class="small text-muted fw-bold me-1"><?php echo $BL['be_admin_page_width'] ?>:</span>
							<input type="text" name="adcampaign_width" id="adcampaign_width" value="<?php echo $plugin['data']['adcampaign_data']['width'] ?>" class="form-control form-control-sm d-inline-block text-center" style="width: 60px;" readonly onfocus="this.blur()" />
							<span class="small text-muted ms-1"><?php echo $BLM['pixel'] ?></span>
						</div>
						<div class="col-auto">
							<span class="small text-muted fw-bold mx-2">/</span>
						</div>
						<div class="col-auto">
							<span class="small text-muted fw-bold me-1"><?php echo $BL['be_admin_page_height'] ?>:</span>
							<input type="text" name="adcampaign_height" id="adcampaign_height" value="<?php echo $plugin['data']['adcampaign_data']['height'] ?>" class="form-control form-control-sm d-inline-block text-center" style="width: 60px;" readonly onfocus="this.blur()" />
							<span class="small text-muted ms-1"><?php echo $BLM['pixel'] ?></span>
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

			<div class="form-group row align-items-center">
				<label class="col-sm-3 col-form-label text-sm-end fw-bold"><?php echo $BLM['tracking_base'] ?></label>
				<div class="col-sm-9">
					<div class="row g-2 align-items-center">
						<div class="col-auto">
							<div class="input-group input-group-sm">
								<span class="input-group-text"><?php echo $BLM['max_view'] ?></span>
								<input type="number" name="adcampaign_max_views" id="adcampaign_max_views" value="<?php echo empty($plugin['data']['adcampaign_data']['max_views']) ? '' : $plugin['data']['adcampaign_data']['max_views'] ?>" class="form-control" style="width: 80px;" />
							</div>
						</div>
						<div class="col-auto">
							<div class="input-group input-group-sm">
								<span class="input-group-text"><?php echo $BLM['max_click'] ?></span>
								<input type="number" name="adcampaign_max_click" id="adcampaign_max_click" value="<?php echo empty($plugin['data']['adcampaign_data']['max_click']) ? '' : $plugin['data']['adcampaign_data']['max_click'] ?>" class="form-control" style="width: 80px;" />
							</div>
						</div>
					</div>
				</div>
			</div>

			<div class="form-group row">
				<label for="adcampaign_url" class="col-sm-3 col-form-label text-sm-end fw-bold"><?php echo $BLM['target_url'] ?></label>
				<div class="col-sm-9">
					<input type="text" name="adcampaign_url" id="adcampaign_url" value="<?php echo empty($plugin['data']['adcampaign_data']['max_views']) ? '' : $plugin['data']['adcampaign_data']['url'] ?>" class="form-control form-control-sm" />
				</div>
			</div>

			<div class="form-group row">
				<label for="adcampaign_target" class="col-sm-3 col-form-label text-sm-end fw-bold"><?php echo $BLM['open_in'] ?></label>
				<div class="col-sm-9">
					<select name="adcampaign_target" id="adcampaign_target" class="form-select form-select-sm" style="max-width: 200px;">
						<option value=""<?php is_selected('', $plugin['data']['adcampaign_data']['target']) ?>>&nbsp;</option>
						<option value="_blank"<?php is_selected('_blank', $plugin['data']['adcampaign_data']['target']) ?>>_blank</option>
						<option value="_top"<?php is_selected('_top', $plugin['data']['adcampaign_data']['target']) ?>>_top</option>
						<option value="_self"<?php is_selected('_self', $plugin['data']['adcampaign_data']['target']) ?>>_self</option>
						<option value="_parent"<?php is_selected('_parent', $plugin['data']['adcampaign_data']['target']) ?>>_parent</option>
					</select>
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
					<div class="form-check form-switch">
						<input type="checkbox" class="form-check-input" role="switch" name="adcampaign_status" id="adcampaign_status" value="1"<?php is_checked($plugin['data']['adcampaign_status'], 1) ?> />
						<label class="form-check-label" for="adcampaign_status"><?php echo $BL['be_cnt_activated'] ?></label>
					</div>
				</div>
			</div>

			<div class="form-group row mt-4 mb-0">
				<div class="col-sm-9 offset-sm-3">
					<button name="submit" type="submit" class="btn btn-sm btn-blue me-1"><i class="fa-solid fa-rotate me-1"></i> <?php echo empty($plugin['data']['adcampaign_id']) ? $BL['be_admin_fcat_button2'] : $BL['be_article_cnt_button1'] ?></button>
					<button name="save" type="submit" class="btn btn-sm btn-blue ms-1"><i class="fa-solid fa-check me-1"></i> <?php echo $BL['be_article_cnt_button3'] ?></button>
					<a href="<?php echo decode_entities(MODULE_HREF) ?>&amp;campaign=1&amp;edit=0" class="btn btn-sm btn-blue ms-3"><i class="fa-solid fa-plus me-1"></i> <?php echo ucfirst($BL['be_msg_new']) ?></a>
					<a href="<?php echo decode_entities(MODULE_HREF) ?>" class="btn btn-sm btn-danger ms-3"><i class="fa-solid fa-times me-1"></i> <?php echo $BL['be_admin_struct_close'] ?></a>
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
