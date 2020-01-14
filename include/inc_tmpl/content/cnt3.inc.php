<?php
/**
 * cmsGO!
 *
 * @author Pixels & Points GmbH <info@pixels-points.ch>
 * @copyright Copyright (c) 2002-2020, Pixels & Points GmbH
 * @license https://www.pixels-points.ch/cmsgo-license.html Pixels & Points cmsGO! license
 *
 **/

// ----------------------------------------------------------------
// obligate check for cmsgo constants
if (!defined('CMSGO_ROOT')) {
    die("You Cannot Access This Script Directly, Have a Nice Day.");
}
// ----------------------------------------------------------------


//link & email

?>

<div class="form-group align-items-center form-row">
  <label for="template" class="col-sm-2 col-form-label text-right"><?php echo $BL['be_admin_struct_template']; ?></label>
  <div class="col-sm-4">
    <select name="template" id="template" class="custom-select form-control form-control-sm">
<?php

    echo '<option value="">'.$BL['be_admin_tmpl_default'].'</option>'.LF;

// templates for frontend login
$tmpllist = get_tmpl_files(CMSGO_TEMPLATE.'inc_cntpart/linkemail');
if(is_array($tmpllist) && count($tmpllist)) {
    foreach($tmpllist as $val) {
        $selected_val = (isset($content["template"]) && $val == $content["template"]) ? ' selected="selected"' : '';
        $val = html($val);
        echo '  <option value="' . $val . '"' . $selected_val . '>' . $val . '</option>' . LF;
    }
}

?>
    </select>
  </div>
</div>

<div class="form-group align-items-center form-row">
  <label for="clink" class="col-sm-2 col-form-label text-right"><?php echo $BL['be_cnt_directlink'] ?></label>
  <div class="col"><input name="clink" type="text" id="clink" class="form-control form-control-sm" value="<?php
            if(isset($content["link"])) {
                echo html($content["link"]);
            }
        ?>" />
  </div>
</div>

<div class="form-group align-items-center form-row">
  <label for="clink" class="col-sm-2 col-form-label text-right"><?php echo $BL['be_cnt_target'] ?></label>
  <div class="col-sm-4">
    <select name="ctarget" id="ctarget" class="custom-select form-control form-control-sm">
      <option value="" <?php
        if(!isset($content["target"])) {
            $content["target"] = '';
        }
        is_selected("", $content["target"]) ?>> </option>
      <option value="_blank" <?php is_selected("_blank", $content["target"]) ?>><?php echo $BL['be_cnt_target1'] ?></option>
      <option value="_parent" <?php is_selected("_parent", $content["target"]) ?>><?php echo $BL['be_cnt_target2'] ?></option>
      <option value="_top" <?php is_selected("_top", $content["target"]) ?>><?php echo $BL['be_cnt_target3'] ?></option>
      <option value="_self" <?php is_selected("_self", $content["target"]) ?>><?php echo $BL['be_cnt_target4'] ?></option>
    </select>
  </div>
</div>