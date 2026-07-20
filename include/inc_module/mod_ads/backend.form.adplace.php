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

?>
<h1 class="title mb-3"><?php echo $BLM['form_adplace_title'] ?></h1>

<div class="card">
	<div class="card-body">
		<form action="<?php echo MODULE_HREF ?>&amp;adplace=1&amp;edit=<?php echo $plugin['data']['adplace_id'] ?>" method="post">
			<input type="hidden" name="adplace_id" value="<?php echo $plugin['data']['adplace_id'] ?>" />

			<div class="form-group row align-items-center">
				<label class="col-sm-2 col-form-label text-sm-right font-weight-bold"><?php echo $BL['be_cnt_last_edited'] ?></label>
				<div class="col-sm-10">
					<span class="text-muted"><?php echo html(date($BL['be_fprivedit_dateformat'], strtotime($plugin['data']['adplace_changed']))) ?></span>
					<?php if(!empty($plugin['data']['adplace_created'])): ?>
						<span class="text-muted ml-3 small">(<?php echo $BL['be_fprivedit_created'] ?>: <?php echo html(date($BL['be_fprivedit_dateformat'], strtotime($plugin['data']['adplace_created']))) ?>)</span>
					<?php endif; ?>
				</div>
			</div>

			<div class="form-group row">
				<label for="adplace_title" class="col-sm-2 col-form-label text-sm-right font-weight-bold"><?php echo $BLM['adplace_title'] ?></label>
				<div class="col-sm-10">
					<input name="adplace_title" type="text" id="adplace_title" class="form-control form-control-sm<?php if(!empty($plugin['error']['adplace_title'])) echo ' is-invalid'; ?>" value="<?php echo html($plugin['data']['adplace_title']) ?>" maxlength="200" />
				</div>
			</div>

			<div class="form-group row">
				<label for="adplace_format" class="col-sm-2 col-form-label text-sm-right font-weight-bold"><?php echo $BLM['ad_format'] ?></label>
				<div class="col-sm-10">
					<select name="adplace_format" id="adplace_format" class="custom-select form-control form-control-sm" onchange="setFormat(this.options[this.selectedIndex].value);">
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
							<input type="text" name="adplace_width" id="adplace_width" value="<?php echo $plugin['data']['adplace_width'] ?>" class="form-control form-control-sm d-inline-block text-center" style="width: 60px;" readonly onfocus="this.blur()" />
							<span class="small text-muted ml-1"><?php echo $BLM['pixel'] ?></span>
						</div>
						<div class="col-auto">
							<span class="small text-muted font-weight-bold mx-2">/</span>
						</div>
						<div class="col-auto">
							<span class="small text-muted font-weight-bold mr-1"><?php echo $BL['be_admin_page_height'] ?>:</span>
							<input type="text" name="adplace_height" id="adplace_height" value="<?php echo $plugin['data']['adplace_height'] ?>" class="form-control form-control-sm d-inline-block text-center" style="width: 60px;" readonly onfocus="this.blur()" />
							<span class="small text-muted ml-1"><?php echo $BLM['pixel'] ?></span>
						</div>
					</div>
					<script type="text/javascript">
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
					</script>
				</div>
			</div>

			<div class="form-group row">
				<label for="adplace_prefix" class="col-sm-2 col-form-label text-sm-right font-weight-bold"><?php echo $BLM['adplace_prefix'] ?></label>
				<div class="col-sm-10">
					<textarea name="adplace_prefix" id="adplace_prefix" rows="3" class="form-control form-control-sm code"><?php echo html($plugin['data']['adplace_prefix']) ?></textarea>
				</div>
			</div>

			<div class="form-group row">
				<label for="adplace_suffix" class="col-sm-2 col-form-label text-sm-right font-weight-bold"><?php echo $BLM['adplace_suffix'] ?></label>
				<div class="col-sm-10">
					<textarea name="adplace_suffix" id="adplace_suffix" rows="3" class="form-control form-control-sm code"><?php echo html($plugin['data']['adplace_suffix']) ?></textarea>
				</div>
			</div>

			<?php if(!empty($plugin['data']['adplace_id'])): ?>
				<div class="form-group row align-items-center">
					<label for="banner_rt" class="col-sm-2 col-form-label text-sm-right font-weight-bold"><?php echo $BLM['ad_template_code'] ?></label>
					<div class="col-sm-10">
						<div class="input-group input-group-sm" style="max-width: 250px;">
							<input type="text" name="banner_rt" id="banner_rt" value="{ADS_<?php echo $plugin['data']['adplace_id'] ?>}" class="form-control text-center font-weight-bold" readonly onfocus="this.select();" onclick="this.select();" />
							<div class="input-group-append">
								<span class="input-group-text"><?php echo $BLM['ad_template_code_info'] ?></span>
							</div>
						</div>
					</div>
				</div>
			<?php endif; ?>

			<div class="form-group row">
				<div class="col-sm-10 offset-sm-2">
					<div class="custom-control custom-checkbox">
						<input type="checkbox" class="custom-control-input" name="adplace_status" id="adplace_status" value="1"<?php is_checked($plugin['data']['adplace_status'], 1) ?> />
						<label class="custom-control-label" for="adplace_status"><?php echo $BL['be_cnt_activated'] ?></label>
					</div>
				</div>
			</div>

			<div class="form-group row mt-4 mb-0">
				<div class="col-sm-10 offset-sm-2">
					<button name="submit" type="submit" class="btn btn-sm btn-blue mr-1"><i class="fas fa-save mr-1"></i> <?php echo empty($plugin['data']['adplace_id']) ? $BL['be_admin_fcat_button2'] : $BL['be_article_cnt_button1'] ?></button>
					<button name="save" type="submit" class="btn btn-sm btn-success mr-1"><i class="fas fa-check mr-1"></i> <?php echo $BL['be_article_cnt_button3'] ?></button>
					<a href="<?php echo decode_entities(MODULE_HREF) ?>&amp;adplace=1&amp;edit=0" class="btn btn-sm btn-info mr-1"><i class="fas fa-plus-circle mr-1"></i> <?php echo ucfirst($BL['be_msg_new']) ?></a>
					<a href="<?php echo decode_entities(MODULE_HREF) ?>&amp;listadplace=1" class="btn btn-sm btn-secondary"><i class="fas fa-times mr-1"></i> <?php echo $BL['be_admin_struct_close'] ?></a>
				</div>
			</div>
		</form>
	</div>
</div>
