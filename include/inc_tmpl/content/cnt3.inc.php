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


//link & email

?>

<div class="form-group align-items-center row g-2">
  <label for="template" class="col-sm-2 col-form-label text-end"><?php echo $BL['be_admin_struct_template']; ?></label>
  <div class="col-sm-4">
    <select name="template" id="template" class="form-select form-select-sm">
<?php

    echo '<option value="">'.$BL['be_admin_tmpl_default'].'</option>'.LF;

// templates for frontend login
$tmpllist = get_tmpl_files(PHPWCMS_TEMPLATE.'inc_cntpart/linkemail');
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

<div class="form-group align-items-center row g-2">
  <label for="clink" class="col-sm-2 col-form-label text-end"><?php echo $BL['be_cnt_directlink'] ?></label>
  <div class="col"><input name="clink" type="text" id="clink" class="form-control form-control-sm" value="<?php
            if(isset($content["link"])) {
                echo html($content["link"]);
            }
        ?>" />
  </div>
</div>

<div class="form-group align-items-center row g-2">
  <label for="clink" class="col-sm-2 col-form-label text-end"><?php echo $BL['be_cnt_target'] ?></label>
  <div class="col-sm-4">
    <select name="ctarget" id="ctarget" class="form-select form-select-sm">
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
