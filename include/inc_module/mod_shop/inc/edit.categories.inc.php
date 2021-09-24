<?php
/**
 * cmsGO!
 *
 * @author Pixels & Points GmbH <info@pixels-points.ch>
 * @copyright Copyright (c) 2002-2021, Pixels & Points GmbH
 * @license https://www.pixels-points.ch/cmsgo-license.html Pixels & Points cmsGO! license
 *
 **/

// ----------------------------------------------------------------
// obligate check for cmsgo constants
if (!defined('CMSGO_ROOT')) {
    die("You Cannot Access This Script Directly, Have a Nice Day.");
}
// ----------------------------------------------------------------
?>
<h1><?php
    echo $BLM['cat_edit'];
    if($plugin['data']['cat_id'] && empty($plugin['data']['cat_pid']) ) {
        echo ' [ID: ' . $plugin['data']['cat_id'] . ']';
    }
?>
</h1>
<hr />

<form action="<?php echo shop_url( array('controller=cat', 'edit='.$plugin['data']['cat_id']) ) ?>" method="post">
<input type="hidden" name="cat_id" value="<?php echo $plugin['data']['cat_id'] ?>" />

  <div class="form-group align-items-center form-row">
            <label class="col-sm-2 col-form-label text-right"></label>
            <div class="col"><?php echo $BL['be_cnt_last_edited']  ?>:&nbsp;
                <?php echo html_specialchars(date($BL['be_fprivedit_dateformat'], $plugin['data']['cat_changedate'])) ;
                    if(!empty($plugin['data']['cat_createdate'])) {
                ?>
            <br /><span class="chatlist"><?php echo $BL['be_fprivedit_created']  ?>:</span>
            <?php
                    echo html_specialchars(date($BL['be_fprivedit_dateformat'], strtotime($plugin['data']['cat_createdate'])));
             }
            ?>
        </div>
  </div>

    <div class="form-group form-row align-items-center">
        <label class="col-sm-2 col-form-label text-right"><?php echo $BL['be_admin_page_category'] ?></label>
        <div class="col-sm-4">
            <input name="cat_name" class="form-control form-control-sm" type="text" id="cat_name" class="v12<?php
            //error class
            if(!empty($plugin['error']['cat_name'])) echo ' errorInputText'; ?>" value="<?php echo html_specialchars($plugin['data']['cat_name']) ?>" size="30"  />
        </div>
    </div>

    <div class="form-group form-row align-items-center">
        <label class="col-sm-2 col-form-label text-right"><?php echo $BLM['shopprod_subcategory'] ?></label>
        <div class="col-sm-4">
            <select name="cat_pid" id="cat_pid" class="form-control custom-select form-control-sm">
            <?php
            //if($plugin['data']['cat_pid'] == 0) {
                echo '<option value="0" selected="selected">&nbsp;</option>' . LF;
            //}
            $sql  = 'SELECT * FROM '.DB_PREPEND."cmsgo_categories WHERE ";
            $sql .= "cat_type='module_shop' AND cat_pid=0 AND cat_status != 9 AND ";
            $sql .= "cat_id != " . $plugin['data']['cat_id'];
            $plugin['data']['subcat'] = _dbQuery($sql);
            foreach($plugin['data']['subcat'] as $value) {

                echo '<option value="' . $value['cat_id'] . '"';
                is_selected($plugin['data']['cat_pid'], $value['cat_id']);
                if($value['cat_status'] = 0) {
                    echo ' style="font-style:italic;"';
                }
                echo '>' . html_specialchars($value['cat_name']) . '</option>' . LF;
            }
            ?>
            </select>
        </div>
    </div>

    <div class="form-group form-row">
        <label class="col-sm-2 col-form-label text-right"><?php echo $BL['be_cnt_infotext'] ?></label>
        <div class="col">
            <textarea name="cat_info" id="cat_info" rows="10" class="form-control form-control-sm"><?php echo html_specialchars($plugin['data']['cat_info']) ?></textarea>
        </div>
  </div>

    <div class="form-group align-items-center form-row ">
    <label class="col-sm-2 col-form-label text-right"><?php echo $BL['be_cnt_sorting'] ?></label>
    <div class="col-sm-auto form-check form-check-inline">
            <input name="cat_sort" type="text" id="cat_sort" class="form-control form-control-sm" value="<?php echo empty($plugin['data']['cat_sort']) ? 0 : intval($plugin['data']['cat_sort']) ?>" />
    </div>
  </div>

    <div class="form-group align-items-center form-row ">
    <label class="col-sm-2 col-form-label text-right"><?php echo $BL['be_ftptakeover_status'] ?></label>
    <div class="col-sm-auto form-check form-check-inline">
            <input class="form-check-input" type="checkbox" name="cat_status" id="cat_status" value="1"<?php is_checked($plugin['data']['cat_status'], 1) ?> />
            <label class="form-check-label" for="cat_status"><?php echo $BL['be_cnt_activated'] ?></label>
    </div>
  </div>

    <div class="form-group text-right mb-0">
        <input name="submit" type="submit" class="btn btn-sm btn-blue" value="<?php echo empty($plugin['data']['cat_id']) ? $BL['be_admin_fcat_button2'] : $BL['be_article_cnt_button1'] ?>" />
        <input name="save" type="submit" class="btn btn-sm btn-blue" value="<?php echo $BL['be_article_cnt_button3'] ?>" />
        <input name="close" type="submit" class="btn btn-sm btn-blue" value="<?php echo $BL['be_admin_struct_close'] ?>" />
    </div>

</form>