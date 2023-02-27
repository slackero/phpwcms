<?php
/**
 * cmsGO!
 *
 * @author Pixels & Points GmbH <info@pixels-points.ch>
 * @copyright Copyright (c) 2002-2023, Pixels & Points GmbH
 * @license https://www.pixels-points.ch/cmsgo-license.html Pixels & Points cmsGO! license
 **/

// ----------------------------------------------------------------
// obligate check for cmsgo constants
if (!defined('CMSGO_ROOT')) {
    die("You Cannot Access This Script Directly, Have a Nice Day.");
}
// ----------------------------------------------------------------

// Alias Content
$content['alias_link'] = '';

if(empty($content["alias"]['alias_ID'])) {
    $content["alias"]['alias_ID'] = '';
} else {
    $content["alias"]['alias_ID'] = intval($content["alias"]['alias_ID']);
    $sql_cnt  = "SELECT * FROM ".DB_PREPEND."cmsgo_articlecontent WHERE acontent_id=".$content["alias"]['alias_ID']." AND acontent_trash=0";
    $cntresult = _dbQuery($sql_cnt);
    if(isset($cntresult[0]['acontent_id'])) {
        $content['alias_link']  = '<div class="mt-1">'.$BL['be_article_cnt_edit'].':&nbsp;';
        if($cntresult[0]['acontent_type'] == 30 && (!$cntresult[0]['acontent_module'] || !isset($cmsgo['modules'][$cntresult[0]['acontent_module']]))) {
            $content['alias_link'] .= '<div class="alert alert-danger">'.$BL['be_cnt_plugin_n.a.'].'</div>';
        } else {
            $content['alias_link'] .= '<a href="cmsgo.php?do=articles&amp;p=2&amp;s=1&amp;aktion=2&amp;id=';
            $content['alias_link'] .= $cntresult[0]['acontent_aid'].'&amp;acid='.$content["alias"]['alias_ID'];
            $content['alias_link'] .= '" target="_blank" class="btn btn-blue btn-sm">'.$wcs_content_type[$cntresult[0]['acontent_type']].'</a>';
        }
        $content['alias_link'] .= '</div>';
    }
}

$content["alias"]['alias_block']    = empty($content["alias"]['alias_block']) ? 0 : 1;
$content["alias"]['alias_spaces']   = empty($content["alias"]['alias_spaces']) ? 0 : 1;
$content["alias"]['alias_title']    = empty($content["alias"]['alias_title']) ? 0 : 1;
$content["alias"]['alias_toplink']  = empty($content["alias"]['alias_toplink']) ? 0 : 1;
$content["alias"]['alias_status']   = empty($content["alias"]['alias_status']) ? 0 : 1;
?>

<div class="form-group align-items-center form-row">
  <label for="be_alias_ID" class="col-sm-2 col-form-label text-right pt-0"><?php echo $BL['be_alias_ID'] ?></label>
  <div class="col-sm-4">
    <div class="input-group">
      <span class="input-group-prepend">
        <button class="modalButton btn btn-sm btn-secondary sitemap-open" type="button" data-toggle="modal" data-target="#browserModal" data-src="articlebrowser.php?opt=5" ></button>
      </span>
      	<input type="text" name="calias" id="calias" value="<?php echo $content["alias"]['alias_ID'] ?>" class="form-control form-control-sm" maxlength="250" data-toggle="tooltip" title="<?php echo $BL['be_alias_ID'] ?>" />
    </div>
    <?php echo $content['alias_link']; ?>
  </div>
</div>

<div class="form-group form-row">
  <label for="radio" class="col-sm-2 col-form-label text-right pt-0"><?php echo $BL['be_cnt_setting'] ?></label>
  <div class="col">
    <div class="form-check">
      <input class="form-check-input" type="checkbox" name="cablock" id="cablock" value="1" <?php is_checked(1, $content["alias"]['alias_block']); ?>>
      <label class="form-check-label" for="cablock"><?php echo $BL['be_cnt_block'] ?></label>
    </div>
    <div class="form-check">
      <input class="form-check-input" type="checkbox" name="caspaces" id="caspaces" value="1" <?php is_checked(1, $content["alias"]['alias_spaces']); ?>>
      <label class="form-check-label" for="caspaces"><?php echo $BL['be_cnt_spaces'] ?></label>
    </div>
    <div class="form-check">
      <input class="form-check-input" type="checkbox" name="catitle" id="catitle" value="1" <?php is_checked(1, $content["alias"]['alias_title']); ?>>
      <label class="form-check-label" for="catitle"><?php echo $BL['be_cnt_title'] ?></label>
    </div>
    <div class="form-check">
      <input class="form-check-input" type="checkbox" name="catop" id="catop" value="1" <?php is_checked(1, $content["alias"]['alias_toplink']); ?>>
      <label class="form-check-label" for="catop"><?php echo $BL['be_cnt_toplink'] ?></label>
    </div>
    <div class="form-check">
      <input class="form-check-input" type="checkbox" name="castatus" id="castatus" value="1" <?php is_checked(1, $content["alias"]['alias_status']); ?>>
      <label class="form-check-label" for="castatus"><?php echo $BL['be_cnt_status'] ?></label>
    </div>
  </div>
</div>
