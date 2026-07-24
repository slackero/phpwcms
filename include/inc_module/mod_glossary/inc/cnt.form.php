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


// Glossary module content part form fields

if (empty($content['glossary']['glossary_template'])) {
	$content['glossary']['glossary_template'] = '';
}
if (empty($content['glossary']['glossary_filter'])) {
	$content['glossary']['glossary_filter'] = '';
}
if (empty($content['glossary']['glossary_maxwords'])) {
	$content['glossary']['glossary_maxwords'] = '';
}
if (empty($content['glossary']['glossary_tag'])) {
	$content['glossary']['glossary_tag'] = '';
}
if (empty($content['glossary']['glossary_noentry'])) {
	$content['glossary']['glossary_noentry'] = '';
}

$BE['BODY_CLOSE'][] = '<script type="text/javascript">document.getElementById("target_ctype").disabled = true;</script>';

?>

<div class="form-group align-items-center form-row">
	<label for="glossary_template" class="col-sm-2 col-form-label text-right"><?php echo $BL['be_admin_struct_template']; ?></label>
	<div class="col-sm-4">
		<select name="glossary_template" id="glossary_template" class="custom-select form-control form-control-sm">
			<?php
			echo '			<option value="">' . $BL['be_admin_tmpl_default'] . '</option>' . LF;

			$tmpllist = get_tmpl_files($phpwcms['modules'][$content['module']]['path'] . 'template');
			if (is_array($tmpllist) && count($tmpllist)) {
				foreach ($tmpllist as $val) {
					$vals = '';
					if ($val == $content['glossary']['glossary_template']) {
						$vals = ' selected="selected"';
					}
					$val = html($val);
					echo '			<option value="' . $val . '"' . $vals . '>' . $val . '</option>' . LF;
				}
			}
			?>
		</select>
	</div>
</div>

<div class="form-group form-row">
	<label for="glossary_filter" class="col-sm-2 col-form-label text-right"><?php echo $BL['modules'][$content['module']]['input_filter']; ?></label>
	<div class="col-sm-10">
		<input type="text" name="glossary_filter" id="glossary_filter" value="<?php echo html($content['glossary']['glossary_filter']); ?>" class="form-control form-control-sm" maxlength="1000" />
		<small class="form-text text-muted"><?php echo $BL['modules'][$content['module']]['input_filter_descr']; ?></small>
	</div>
</div>

<div class="form-group align-items-center form-row">
	<label for="glossary_maxwords" class="col-sm-2 col-form-label text-right"><?php echo $BL['modules'][$content['module']]['listview']; ?></label>
	<div class="col-sm-10">
		<div class="form-inline">
			<input name="glossary_maxwords" type="text" class="form-control form-control-sm mr-2" id="glossary_maxwords" style="width: 70px;" size="5" maxlength="5" onkeyup="if(!parseInt(this.value,10)) this.value='';" value="<?php echo $content['glossary']['glossary_maxwords']; ?>" />
			<span class="text-muted small"><?php echo $BL['modules'][$content['module']]['max_words']; ?></span>
		</div>
	</div>
</div>

<div class="form-group form-row">
	<label for="glossary_tag" class="col-sm-2 col-form-label text-right"><?php echo $BL['modules'][$content['module']]['glossary_token']; ?></label>
	<div class="col-sm-10">
		<input type="text" name="glossary_tag" id="glossary_tag" value="<?php echo html($content['glossary']['glossary_tag']); ?>" class="form-control form-control-sm" maxlength="1000" />
	</div>
</div>

<div class="form-group form-row">
	<label for="glossary_noentry" class="col-sm-2 col-form-label text-right"><?php echo $BL['modules'][$content['module']]['no_entry']; ?></label>
	<div class="col-sm-10">
		<textarea name="glossary_noentry" id="glossary_noentry" class="form-control form-control-sm field-sizing-content field-sizing-content-5" rows="5"><?php echo html($content['glossary']['glossary_noentry']); ?></textarea>
	</div>
</div>
