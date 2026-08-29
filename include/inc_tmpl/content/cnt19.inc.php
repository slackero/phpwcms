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

// Sitemap
if(!isset($content['sitemap'])) {
	$content['sitemap']["before"]			= '';
	$content['sitemap']["after"]			= '';
	$content['sitemap']["catimg"]			= '';
	$content['sitemap']["articleimg"]		= '';
	$content['sitemap']["startid"]			= 0;
	$content['sitemap']["display"]			= 0;
	$content['sitemap']["catclass"]			= '';
	$content['sitemap']["articleclass"]		= '';
	$content['sitemap']["classcount"]		= 0;
	$content['sitemap']["without_parent"]	= 0;
}
?>

<div class="form-group row g-2">
  <label for="csitemap_before" class="col-sm-2 col-form-label text-end"><?php echo $BL['be_cnt_guestbook_before'] ?></label>
  <div class="col">
    <textarea name="csitemap_before" cols="40" rows="3" class="form-control form-control-sm" id="csitemap_before"><?php echo html($content["sitemap"]["before"]) ?></textarea>
  </div>
</div>

<div class="form-group row g-2">
  <label for="csitemap_after" class="col-sm-2 col-form-label text-end"><?php echo $BL['be_cnt_guestbook_after'] ?></label>
  <div class="col"><textarea name="csitemap_after" cols="40" rows="3" class="form-control form-control-sm" id="csitemap_after"><?php echo html($content["sitemap"]["after"]) ?></textarea>
  </div>
</div>

<div class="form-group align-items-center row g-2">
  <label for="csitemap_catimg" class="col-sm-2 col-form-label text-end"><?php echo $BL['be_cnt_sitemap_catimage'] ?></label>
  <div class="col-sm-4"><input name="csitemap_catimg" type="text" id="csitemap_catimg" class="form-control form-control-sm" value="<?php echo html($content["sitemap"]["catimg"]) ?>" >
  <?php if($content["sitemap"]["catimg"]) echo '<img src="'.$content["sitemap"]["catimg"].'" border="0">';
  ?>
  </div>
</div>

<div class="form-group align-items-center row g-2">
  <label for="csitemap_articleimg" class="col-sm-2 col-form-label text-end"><?php echo $BL['be_cnt_sitemap_articleimage'] ?></label>
  <div class="col-sm-4"><input name="csitemap_articleimg" type="text" id="csitemap_articleimg" class="form-control form-control-sm" value="<?php echo html($content["sitemap"]["articleimg"]) ?>" size="40">
  	<?php if($content["sitemap"]["articleimg"]) echo '<img src="'.$content["sitemap"]["articleimg"].'" border="0">'; ?>
  </div>
</div>

<div class="form-group align-items-center row g-2">
  <label for="csitemap_startid" class="col-sm-2 col-form-label text-end"><?php echo $BL['be_cnt_sitemap_startid'] ?></label>
  <div class="col-sm-4">
    <select name="csitemap_startid" id="csitemap_startid" class="form-select form-select-sm">
  <?php
    echo "<option value='0'".((!$content["sitemap"]["startid"])?" selected":"").">".$BL['be_admin_struct_index']."</option>\n";
    struct_select_menu(0, 0, $content["sitemap"]["startid"]);
  ?></select>
  </div>
</div>

<div class="form-group align-items-center row g-2">
  <label for="csitemap_display" class="col-sm-2 col-form-label text-end"><?php echo $BL['be_cnt_sitemap_display'] ?></label>
  <div class="col">
  	<div class="form-check form-check-inline">
			<input class="form-check-input" name="csitemap_display" id="csitemap_display0" type="radio" value="0" <?php is_checked(0, $content["sitemap"]["display"]) ?>>
			<label class="form-check-label" for="csitemap_display0"><?php echo $BL['be_cnt_sitemap_structuronly'] ?>
		</label>
	</div>
  	<div class="form-check form-check-inline">
			<input class="form-check-input" name="csitemap_display" id="csitemap_display1" type="radio" value="1" <?php is_checked(1, $content["sitemap"]["display"]) ?>>
			<label class="form-check-label" for="csitemap_display1"><?php echo $BL['be_cnt_sitemap_structurarticle'] ?>
		</label>
	</div>
  	<div class="form-check form-check-inline">
			<input class="form-check-input" name="csitemap_without_parent" id="csitemap_without_parent" type="checkbox" value="1" <?php is_checked(1, $content["sitemap"]["without_parent"]) ?>>
			<label class="form-check-label" for="csitemap_without_parent"><?php echo $BL['be_cnt_sitemap_without_parent'] ?>
		</label>
	</div>
  </div>
</div>

<div class="form-group align-items-center row g-2">
  <label for="csitemap_catclass" class="col-sm-2 col-form-label text-end"><?php echo $BL['be_cnt_sitemap_catclass'] ?></label>
  <div class="col-sm-4">
    <input name="csitemap_catclass" type="text" id="csitemap_catclass" class="form-control form-control-sm" value="<?php echo html($content["sitemap"]["catclass"]) ?>">
  </div>
</div>

<div class="form-group align-items-center row g-2">
  <label for="csitemap_articleclass" class="col-sm-2 col-form-label text-end"><?php echo $BL['be_cnt_sitemap_articleclass'] ?></label>
  <div class="col-sm-4">
    <input name="csitemap_articleclass" type="text" id="csitemap_articleclass" class="form-control form-control-sm" value="<?php echo html($content["sitemap"]["articleclass"]) ?>">
  </div>
</div>

<div class="form-group align-items-center row g-2">
  <label for="csitemap_display" class="col-sm-2 col-form-label text-end"><?php echo $BL['be_cnt_sitemap_count'] ?></label>
  <div class="col">
  	<div class="form-check form-check-inline">
			<input class="form-check-input" name="csitemap_classcount" id="csitemap_classcount0" type="radio" value="0" <?php is_checked(0, $content["sitemap"]["classcount"]) ?>>
			<label class="form-check-label" for="csitemap_classcount0"><?php echo $BL['be_cnt_sitemap_noclasscount'] ?>
		</label>
	</div>
  	<div class="form-check form-check-inline">
			<input class="form-check-input" name="csitemap_classcount" id="csitemap_classcount1" type="radio" value="1" <?php is_checked(1, $content["sitemap"]["classcount"]) ?>>
			<label class="form-check-label" for="csitemap_classcount1"><?php echo $BL['be_cnt_sitemap_classcount'] ?></label>
	</div>
  </div>
</div>
