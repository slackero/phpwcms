<?php
/**
 * phpwcms content management system
 *
 * @author Oliver Georgi <og@phpwcms.org>
 * @copyright Copyright (c) 2002-2022, Oliver Georgi
 * @license http://opensource.org/licenses/GPL-2.0 GNU GPL-2
 * @link http://www.phpwcms.org
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

<tr><td colspan="2" class="rowspacer0x7"><img src="img/leer.gif" alt="" width="1" height="1" /></td></tr>

<tr>
    <td align="right" class="chatlist"><?php echo $BL['be_admin_struct_template']; ?>:&nbsp;</td>
    <td><select name="template" id="template">
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
        </select></td>
</tr>

<tr><td colspan="2" class="rowspacer7x7"><img src="img/leer.gif" alt="" width="1" height="1" /></td></tr>

<tr>
        <td align="right" class="chatlist"><?php echo $BL['be_cnt_directlink'] ?>:&nbsp;</td>
        <td valign="top"><input name="clink" type="text" id="clink" class="f11b" style="width:440px" value="<?php
            if(isset($content["link"])) {
                echo html($content["link"]);
            }
        ?>" size="40" /></td>
    </tr>
    <tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="2"></td></tr>
    <tr>
      <td align="right" class="chatlist"><?php echo $BL['be_cnt_target'] ?>:&nbsp;</td>
      <td valign="top"><select name="ctarget" id="ctarget">
        <option value="" <?php
        if(!isset($content["target"])) {
            $content["target"] = '';
        }
        is_selected("", $content["target"]) ?>> </option>
        <option value="_blank" <?php is_selected("_blank", $content["target"]) ?>><?php echo $BL['be_cnt_target1'] ?></option>
        <option value="_parent" <?php is_selected("_parent", $content["target"]) ?>><?php echo $BL['be_cnt_target2'] ?></option>
        <option value="_top" <?php is_selected("_top", $content["target"]) ?>><?php echo $BL['be_cnt_target3'] ?></option>
        <option value="_self" <?php is_selected("_self", $content["target"]) ?>><?php echo $BL['be_cnt_target4'] ?></option>
      </select></td>
</tr>