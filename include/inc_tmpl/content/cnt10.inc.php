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

// email form

if (!isset($content['mailhtml'])) {
	$content['mailhtml'] = 0;
}

?>

<div class="form-group form-row">
	<label for="cmailsubject" class="col-sm-2 col-form-label text-right"><?php echo $BL['be_cnt_subject']; ?></label>
	<div class="col-sm-10">
		<input name="cmailsubject" type="text" id="cmailsubject" class="form-control form-control-sm" value="<?php echo isset($content['mailsubject']) ? html($content['mailsubject']) : ''; ?>" maxlength="250" />
	</div>
</div>

<div class="form-group form-row">
	<label for="cmailrecipient" class="col-sm-2 col-form-label text-right"><?php echo $BL['be_cnt_recipient']; ?></label>
	<div class="col-sm-10">
		<input name="cmailrecipient" type="text" id="cmailrecipient" class="form-control form-control-sm" value="<?php echo isset($content['mailrecipient']) ? html($content['mailrecipient']) : ''; ?>" maxlength="250" />
	</div>
</div>

<div class="form-group form-row">
	<label for="cmailbutton" class="col-sm-2 col-form-label text-right"><?php echo $BL['be_cnt_buttontext']; ?></label>
	<div class="col-sm-4">
		<input name="cmailbutton" type="text" id="cmailbutton" class="form-control form-control-sm" value="<?php echo isset($content['mailbutton']) ? html($content['mailbutton']) : ''; ?>" maxlength="35" />
	</div>
	<label class="col-sm-2 col-form-label text-right"><?php echo $BL['be_cnt_sendas']; ?></label>
	<div class="col-sm-4 d-flex align-items-center">
		<div class="form-check form-check-inline">
			<input name="cmailhtml" type="radio" id="cmailhtml_0" value="0" class="form-check-input" <?php is_checked(0, $content['mailhtml']); ?> />
			<label class="form-check-label" for="cmailhtml_0"><?php echo $BL['be_cnt_text']; ?></label>
		</div>
		<div class="form-check form-check-inline">
			<input name="cmailhtml" type="radio" id="cmailhtml_1" value="1" class="form-check-input" <?php is_checked(1, $content['mailhtml']); ?> />
			<label class="form-check-label" for="cmailhtml_1"><?php echo $BL['be_cnt_html']; ?></label>
		</div>
	</div>
</div>

<div class="form-group form-row">
	<label for="cmailform" class="col-sm-2 col-form-label text-right"><?php echo $BL['be_cnt_formfields']; ?></label>
	<div class="col-sm-10">
		<textarea name="cmailform" rows="15" class="form-control form-control-sm field-sizing-content field-sizing-content-15" id="cmailform"><?php
		if (isset($content['mailform'])) {
			if (is_array($content['mailform'])) {
				foreach ($content['mailform'] as $formkey => $valform) {
					echo html($content['mailform'][$formkey]['field']) . "\n";
				}
			} else {
				echo html($content['mailform']);
			}
		} else {
			echo '';
		}
		?></textarea>
	</div>
</div>
