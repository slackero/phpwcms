<?php
/**
 * cmsGO!
 *
 * @author Pixels & Points GmbH <info@pixels-points.ch>
 * @copyright Copyright (c) 2002-2023, Pixels & Points GmbH
 * @license https://www.pixels-points.ch/cmsgo-license.html Pixels & Points cmsGO! license
 *
 **/

// ----------------------------------------------------------------
// obligate check for cmsgo constants
if (!defined('CMSGO_ROOT')) {
  die("You Cannot Access This Script Directly, Have a Nice Day.");
}
// ----------------------------------------------------------------

// List
if(empty($content['bulletlist']["list_type"])) $content['bulletlist']["list_type"] = 0;
?>

<div class="form-group align-items-center form-row">
  <label for="template" class="col-sm-2 col-form-label text-right"><?php echo $BL['be_cnt_ullist']; ?></label>
  <div class="col">
  	<div class="form-check form-check-inline">
			<input class="form-check-input mr-1" type="radio" name="clist_type" id="clist_type0" value="0"<?php echo is_checked('0', $content['bulletlist']["list_type"], 0, 0) ?> />
			<label class="form-check-label" for="clist_type0">&lt;ul&gt;</label>
    </div>
    <div class="form-check form-check-inline">
			<input class="form-check-input mr-1" type="radio" name="clist_type" id="clist_type1" value="1"<?php echo is_checked('1', $content['bulletlist']["list_type"], 0, 0) ?> />
			<label class="form-check-label" for="clist_type1">&lt;ol&gt;</label>
  	</div>
  	<div class="form-check form-check-inline">
			<input class="form-check-input mr-1" type="radio" name="clist_type" id="clist_type2" value="2"<?php echo is_checked('2', $content['bulletlist']["list_type"], 0, 0) ?> />
			<label class="form-check-label" for="clist_type2">&lt;dl&gt;</label>
    </div>
  </div>
</div>

<div class="form-group align-items-center form-row">
  <div class="col-sm-2"></div>
  <div class="col">
    <?php echo $BL['be_cnt_ullist_desc'] ?>
  </div>
</div>

<div class="form-group form-row">
  <label for="ctext" class="col-sm-2 col-form-label text-right"></label>
  <div class="col">
    <textarea name="ctext" rows="20" class="form-control form-control-sm" id="ctext"><?php echo  isset($content["text"]) ? $content["text"] : '' ?></textarea>
  </div>
</div>
