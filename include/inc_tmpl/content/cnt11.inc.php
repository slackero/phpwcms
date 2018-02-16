<?php
/**
 * cmsGO!
 *
 * @author Pixels & Points GmbH <info@pixels-points.ch>
 * @copyright Copyright (c) 2002-2017, Pixels & Points GmbH
 * @license https://www.pixels-points.ch/cmsgo-license.html Pixels & Points cmsGo! license
 *
 **/

// ----------------------------------------------------------------
// obligate check for cmsgo constants
if (!defined('CMSGO_ROOT')) {
	die("You Cannot Access This Script Directly, Have a Nice Day.");
}
// ----------------------------------------------------------------

//code
?>

<div class="form-group align-items-center form-row">
  <label for="template" class="col-sm-2 col-form-label text-right"><?php echo $BL['be_admin_struct_template']; ?></label>
  <div class="col-sm-4">
    <select name="template" id="template" class="custom-select form-control form-control-sm">
<?php

	echo '<option value="">'.$BL['be_admin_tmpl_default'].'</option>'.LF;

// templates for frontend login
$tmpllist = get_tmpl_files(CMSGO_TEMPLATE.'inc_cntpart/code');
if(is_array($tmpllist) && count($tmpllist)) {
	foreach($tmpllist as $val) {
		$selected_val = (isset($content["template"]) && $val == $content["template"]) ? ' selected="selected"' : '';
		$val = html($val);
		echo '	<option value="' . $val . '"' . $selected_val . '>' . $val . '</option>' . LF;
	}
}
?>
    </select>
  </div>
</div>

<div class="form-group form-row">
  <label for="ccode" class="col-sm-2 col-form-label text-right"><?php echo $BL['be_cnt_code'] ?></label>
  <div class="col">
    <textarea name="ccode" rows="15" class="form-control form-control-sm" id="ccode"><?php

	if(!empty($content["code"])) {

		$firstchar = substr($content["code"], 0, 1);

		if($firstchar == "\r" || $firstchar == "\n") {
			$content["code"] = ' '.$content["code"];
		}
		echo html($content["code"], true);
	}

	?></textarea>
    </div>
</div>