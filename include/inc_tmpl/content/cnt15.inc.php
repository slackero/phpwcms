<?php
/**
 * cmsGO!
 *
 * @author Pixels & Points GmbH <info@pixels-points.ch>
 * @copyright Copyright (c) 2002-2022, Pixels & Points GmbH
 * @license https://www.pixels-points.ch/cmsgo-license.html Pixels & Points cmsGO! license
 *
 **/

// ----------------------------------------------------------------
// obligate check for cmsgo constants
if (!defined('CMSGO_ROOT')) {
    die("You Cannot Access This Script Directly, Have a Nice Day.");
}
// ----------------------------------------------------------------

//article menu
if(!isset($content["alist"]["cat"])) {
    $content["alist"]["cat"] = 0;
}
if(!isset($content["alist"]["catid"])) {
    $content["alist"]["catid"] = 0;
}
if(!isset($content["alist"]["headertext"])) {
    $content["alist"]["headertext"] = 0;
}
if(!isset($content["alist"]["div"])) {
    $content["alist"]["div"] = 0;
}
if(!isset($content["alist"]["ul"])) {
    $content["alist"]["ul"] = 0;
}
if(!isset($content["alist"]["class"])) {
    $content["alist"]["class"] = '';
}
if(empty($content["alist"]["maxchar"])) {
    $content["alist"]["maxchar"] = '';
}
if(empty($content["alist"]["morelink"])) {
    $content["alist"]["morelink"] = '';
}
if(empty($content["alist"]["titlewrap"])) {
    $content["alist"]["titlewrap"] = '';
}
if(!isset($content["alist"]["hideactive"])) {
    $content["alist"]["hideactive"] = 0;
}
if(!isset($content["alist"]["titleasnumber"])) {
    $content["alist"]["titleasnumber"] = 0;
}
if(empty($content["alist"]["break"])) {
    $content["alist"]["break"] = '';
}
if(empty($content["alist"]["label"])) {
    $content["alist"]["label"] = '';
}
?>

<div class="form-group align-items-center form-row">
	<label class="col-sm-2 col-form-label text-right" for="be_cnt_sitelevel"><?php echo $BL['be_cnt_sitelevel'] ?></label>
	<div class="col">
		<div class="form-check form-check-inline">
			<input class="form-check-input" name="calist_cat" type="radio" value="0" <?php is_checked(0, intval($content["alist"]["cat"])) ?>>
			<label class="form-check-label"><strong><?php echo $BL['be_cnt_sitecurrent'] ?></strong></label>
		</div>
	</div>
</div>

<div class="form-group align-items-center form-row">
	<label class="col-sm-2 col-form-label"></label>
	<div class="col">
		<div class="form-check">
			<input class="form-check-input mt-2" name="calist_cat" type="radio" value="1" <?php is_checked(1, intval($content["alist"]["cat"])) ?>>
			<label class="form-check-label">
				<select name="calist_catid" class="custom-select form-control form-control-sm">
					<?php echo "<option value='0'".((!$content["alist"]["catid"])?" selected":"").">".$BL['be_admin_struct_index']."</option>\n"; struct_select_menu(0, 0, $content["alist"]["catid"]); ?>
				</select>
			</label>
    </div>
  </div>
</div>

<div class="form-group align-items-center form-row my-sm-3">
	<label class="col-sm-2 col-form-label text-right" for="be_show_content"><?php echo $BL['be_show_content'] ?></label>
	<div class="col-sm-auto">
		<div class="form-check form-check-inline">
			<input class="form-check-input" type="radio" name="calist_ul" id="calist_ul1" value="1" <?php is_checked(1, intval($content["alist"]["ul"])) ?>>
			<label class="form-check-label" id="calist_ul1">&lt;ul&gt;&nbsp;</label>
		</div>
		<div class="form-check form-check-inline">
			<input class="form-check-input" type="radio" name="calist_ul" id="calist_ul2" value="2" <?php is_checked(2, intval($content["alist"]["ul"])) ?>>
			<label class="form-check-label" for="calist_ul2">&lt;div&gt;&nbsp;</label>
		</div>
		<div class="form-check form-check-inline">
			<input class="form-check-input" type="radio" name="calist_ul" id="calist_ul3" value="3" <?php is_checked(3, intval($content["alist"]["ul"])) ?>>
			<label class="form-check-label" for="calist_ul3">&lt;dl&gt;&nbsp;</label>
		</div>
		<div class="form-check form-check-inline">
			<input class="form-check-input" type="radio" name="calist_ul" id="calist_ul4" value="4" <?php is_checked(4, intval($content["alist"]["ul"])) ?>>
			<label class="form-check-label" for="calist_ul4">&lt;span&gt;&nbsp;</label>
		</div>
		<div class="form-check form-check-inline">
			<input class="form-check-input" type="radio" name="calist_ul" id="calist_ul0" value="5" <?php is_checked(0, intval($content["alist"]["ul"])) ?>>
			<label class="form-check-label" for="calist_ul0"><?php echo $BL['be_admin_page_table'] ?></label>
		</div>
	</div>
	<div class="col">
		<div class="form-group align-items-center form-row mb-0 ml-sm-3">
  			<div class="col-form-label font-weight-normal"><?php echo $BL['be_cnt_css_class'] ?></div>
    		<div class="col-sm-auto"><input type="text" name="calist_class" id="calist_class" class="form-control form-control-sm" value="<?php echo html($content["alist"]["class"]) ?>" ></div>
  		</div>
	</div>
</div>


<div class="form-group form-row align-items-center">
	<label class="col-sm-2 col-form-label"></label>
    <div class="col-sm-auto">
    	<div class="form-check mb-2 mb-sm-0">
				<input name="calist_titleasnumber" id="calist_titleasnumber" class="form-check-input" type="checkbox" value="1" <?php is_checked(1, intval($content["alist"]["titleasnumber"])) ?> >
				<label class="form-check-label" for="calist_titleasnumber" ><?php echo $BL['numerize_title'] ?></label>
     	</div>
    </div>
    <div class="col-sm-auto">
    	<div class="form-row form-inline ml-sm-3">
			<label class="col-form-label font-weight-normal"><?php echo $BL['be_cnt_label'] ?></label>
			<input type="text" name="calist_label" id="calist_label" class="form-control form-control-sm" value="<?php echo html($content["alist"]["label"]) ?>">
		</div>
    </div>
    <div class="col-sm-auto">
    	<div class="form-row form-inline ml-sm-3">
			<label class="col-form-label font-weight-normal"><?php echo $BL['be_cnt_field']['break'] ?></label>
			<input type="text" name="calist_break" id="calist_break" class="form-control form-control-sm" value="<?php echo html($content["alist"]["break"]) ?>">
		</div>
    </div>
</div>

<div class="form-group form-row align-items-center">
	<label class="col-sm-2 col-form-label"></label>
    <div class="col-sm-auto">
    	<div class="form-check mb-2 mb-sm-0">
				<input name="calist_headertext" id="calist_headertext" class="form-check-input" type="checkbox" value="1" <?php is_checked(1, intval($content["alist"]["headertext"])) ?> >
				<label class="form-check-label" for="calist_headertext"><?php echo $BL['be_article_asummary'] ?></label>
     	</div>
    </div>
    <div class="col-sm-auto">
    	<div class="form-row form-inline ml-sm-3">
			<label class="col-form-label font-weight-normal"><?php echo $BL['be_cnt_articlemenu_maxchar'] ?></label>
			<input type="text" name="calist_maxchar" id="calist_maxchar" class="form-control form-control-sm" value="<?php echo $content["alist"]["maxchar"] ?>">
		</div>
    </div>
    <div class="col-sm-auto">
    	<div class="form-row form-inline ml-sm-3">
			<label class="col-form-label font-weight-normal"><?php echo $BL['be_article_morelink'] ?></label>
			<input type="text" name="calist_morelink" id="calist_morelink" class="form-control form-control-sm" value="<?php echo html($content["alist"]["morelink"]) ?>">
		</div>
    </div>
</div>

<div class="form-group align-items-center form-row">
	<label class="col-sm-2 col-form-label text-right" for="be_cnt_sitelevel"><?php echo $BL['be_title_wrap'] ?></label>
	<div class="col-sm-auto">
		<select name="calist_titlewrap" id="calist_titlewrap" class="custom-select form-control form-control-sm">
		<?php
    	echo '  <option value=""';
    	is_selected(0, $content["alist"]["titlewrap"]);
    	echo '>'.$BL['be_cnt_default'].' ('.$BL['be_func_struct_empty'].')</option>'.LF;

    	foreach(array('p','div','span','h1','h2','h3','h4','h5','h6','pre','blockquote','em') as $value) {

        echo '  <option value="'.$value.'"';
        is_selected($value, $content["alist"]["titlewrap"]);
        echo '>'.strtoupper($value).'</option>'.LF;
    	}
		?>
        </select>
	</div>
	<div class="col-sm-auto">
		<div class="form-check mt-3 mt-sm-0">
			<input name="calist_hideactive" id="calist_hideactive" class="form-check-input" type="checkbox" value="1" <?php is_checked(1, intval($content["alist"]["hideactive"])) ?> >
			<label class="form-check-label" for="calist_hideactive"><?php echo $BL['be_hide_active_articlelink'] ?></label>
		</div>
	</div>

</div>