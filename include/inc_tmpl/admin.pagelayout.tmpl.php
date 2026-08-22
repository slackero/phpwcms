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

if(!isset($_GET["s"])) {
// check if pagelayout should be edited or list should be shown
?>
<h1 class="text-center text-sm-left"><?php echo $BL['be_subnav_admin_pagelayout'] ?></h1>
<div class="card">
<div class="card-header"><h2><i class="fa fa-list"></i> <?php echo $BL['be_admin_page_title'] ?></h2></div>
<div class="card-body">
<table class="table table-striped table-sm table-valign-middle mb-4">
<?php
    // loop listing available pagelayouts
    $sql = "SELECT * FROM ".DB_PREPEND."phpwcms_pagelayout WHERE pagelayout_trash=0 ORDER BY pagelayout_default DESC";
    $result = _dbQuery($sql);
    $row_count = 0;
    if(isset($result[0]['pagelayout_id'])) {
        foreach($result as $row) {

            echo "<tr>\n";

            echo '<td class="dir"><a href="phpwcms.php?do=admin&amp;p=8&amp;s='.$row["pagelayout_id"];
            echo '"><strong>'.html($row["pagelayout_name"])."</strong>";

            echo ($row["pagelayout_default"]) ? " (".$BL['be_admin_tmpl_default'].")" : '';

            echo "</a></td>\n".'<td class="text-right text-nowrap">';

            echo '<a class="btn btn-blue btn-sm mr-1" role="button" data-toggle="tooltip" href="phpwcms.php?do=admin&amp;p=8&amp;s='.$row["pagelayout_id"].'" title="'.$BL['be_admin_page_edit'].'">';
            echo '<i class="fa fa-pencil-alt"></i></a>';

            echo '<a class="btn btn-danger btn-sm" role="button" data-toggle="tooltip" href="include/inc_act/act_frontendsetup.php?do=1|'.$row["pagelayout_id"].'" ';
            echo 'title="'.$BL['be_tt_delete_pagelayout'].'" ';
            echo 'data-confirm-danger="'.html($BL['be_cnt_delete'].":\n[".$row["pagelayout_name"].']').'">';
            echo '<i class="far fa-trash-alt" aria-hidden="true"></i></a>';

            echo "</td>\n</tr>\n";

            $row_count++;
        }
    } // end listing

?>
</table>
<a href="phpwcms.php?do=admin&amp;p=8&amp;s=0" class="btn btn-blue btn-sm" title="<?php echo $BL['be_admin_page_add'] ?>"><i class="fa fa-plus mr-1"></i> <?php echo $BL['be_admin_page_add'] ?></a>
</div>
</div>
<?php

} else {

    $pagelayout["id"] = intval($_GET["s"]);

    if(isset($_POST["layout_id"])) {

        // read the full pagelayout values
        $pagelayout["id"]                         = intval($_POST["layout_id"]);
        $pagelayout["layout_name"]                = empty($_POST["layout_name"]) ? $BL['be_subnav_admin_pagelayout'].' ('.date('Ymd-His', now()).')' : clean_slweg($_POST["layout_name"], 200);
        $pagelayout["layout_default"]             = isset($_POST["layout_default"]) ? intval($_POST["layout_default"]) : 0;

        $pagelayout["layout_align"]               = intval($_POST["layout_align"]);
        $pagelayout["layout_type"]                = intval($_POST["layout_type"]);

        $pagelayout["layout_border_top"]          = intval($_POST["layout_border_top"]);
        $pagelayout["layout_border_bottom"]       = intval($_POST["layout_border_bottom"]);
        $pagelayout["layout_border_left"]         = intval($_POST["layout_border_left"]);
        $pagelayout["layout_border_right"]        = intval($_POST["layout_border_right"]);
        $pagelayout["layout_noborder"]            = isset($_POST["layout_noborder"]) ? 1 : 0;

        $pagelayout["layout_border_top"]          = ($pagelayout["layout_noborder"]) ? "" : strval($_POST["layout_border_top"]);
        $pagelayout["layout_border_bottom"]       = ($pagelayout["layout_noborder"]) ? "" : strval($_POST["layout_border_bottom"]);
        $pagelayout["layout_border_left"]         = ($pagelayout["layout_noborder"]) ? "" : strval($_POST["layout_border_left"]);
        $pagelayout["layout_border_right"]        = ($pagelayout["layout_noborder"]) ? "" : strval($_POST["layout_border_right"]);

        $pagelayout["layout_title"]               = clean_slweg($_POST["layout_title"]);
        $pagelayout["layout_title_order"]         = intval($_POST["layout_title_order"]);
        $pagelayout["layout_title_spacer"]        = slweg($_POST["layout_title_spacer"], 0, false);

        $pagelayout["layout_bgcolor"]             = clean_slweg($_POST["layout_bgcolor"],7);
        $pagelayout["layout_bgimage"]             = clean_slweg($_POST["layout_bgimage"]);

        $pagelayout["layout_jsonload"]            = slweg($_POST["layout_jsonload"]);

        $pagelayout["layout_textcolor"]           = clean_slweg($_POST["layout_textcolor"],7);
        $pagelayout["layout_linkcolor"]           = clean_slweg($_POST["layout_linkcolor"],7);
        $pagelayout["layout_vcolor"]              = clean_slweg($_POST["layout_vcolor"],7);
        $pagelayout["layout_acolor"]              = clean_slweg($_POST["layout_acolor"],7);

        $pagelayout["layout_all_width"]           = get_pix_or_percent($_POST["layout_all_width"]);
        $pagelayout["layout_all_bgcolor"]         = clean_slweg($_POST["layout_all_bgcolor"],7);
        $pagelayout["layout_all_bgimage"]         = clean_slweg($_POST["layout_all_bgimage"]);
        $pagelayout["layout_all_class"]           = clean_slweg($_POST["layout_all_class"]);

        $pagelayout["layout_content_width"]       = get_pix_or_percent($_POST["layout_content_width"]);
        $pagelayout["layout_content_bgcolor"]     = clean_slweg($_POST["layout_content_bgcolor"],7);
        $pagelayout["layout_content_bgimage"]     = clean_slweg($_POST["layout_content_bgimage"]);
        $pagelayout["layout_content_class"]       = clean_slweg($_POST["layout_content_class"]);

        $pagelayout["layout_left_width"]          = get_pix_or_percent($_POST["layout_left_width"]);
        $pagelayout["layout_left_bgcolor"]        = clean_slweg($_POST["layout_left_bgcolor"],7);
        $pagelayout["layout_left_bgimage"]        = clean_slweg($_POST["layout_left_bgimage"]);
        $pagelayout["layout_left_class"]          = clean_slweg($_POST["layout_left_class"]);

        $pagelayout["layout_right_width"]         = get_pix_or_percent($_POST["layout_right_width"]);
        $pagelayout["layout_right_bgcolor"]       = clean_slweg($_POST["layout_right_bgcolor"],7);
        $pagelayout["layout_right_bgimage"]       = clean_slweg($_POST["layout_right_bgimage"]);
        $pagelayout["layout_right_class"]         = clean_slweg($_POST["layout_right_class"]);

        $pagelayout["layout_leftspace_width"]     = get_pix_or_percent($_POST["layout_leftspace_width"]);
        $pagelayout["layout_leftspace_bgcolor"]   = clean_slweg($_POST["layout_leftspace_bgcolor"],7);
        $pagelayout["layout_leftspace_bgimage"]   = clean_slweg($_POST["layout_leftspace_bgimage"]);
        $pagelayout["layout_leftspace_class"]     = clean_slweg($_POST["layout_leftspace_class"]);

        $pagelayout["layout_rightspace_width"]    = get_pix_or_percent($_POST["layout_rightspace_width"]);
        $pagelayout["layout_rightspace_bgcolor"]  = clean_slweg($_POST["layout_rightspace_bgcolor"],7);
        $pagelayout["layout_rightspace_bgimage"]  = clean_slweg($_POST["layout_rightspace_bgimage"]);
        $pagelayout["layout_rightspace_class"]    = clean_slweg($_POST["layout_rightspace_class"]);

        $pagelayout["layout_header_height"]       = get_pix_or_percent($_POST["layout_header_height"]);
        $pagelayout["layout_header_bgcolor"]      = clean_slweg($_POST["layout_header_bgcolor"],7);
        $pagelayout["layout_header_bgimage"]      = clean_slweg($_POST["layout_header_bgimage"]);
        $pagelayout["layout_header_class"]        = clean_slweg($_POST["layout_header_class"]);

        $pagelayout["layout_topspace_height"]     = get_pix_or_percent($_POST["layout_topspace_height"]);
        $pagelayout["layout_topspace_bgcolor"]    = clean_slweg($_POST["layout_topspace_bgcolor"],7);
        $pagelayout["layout_topspace_bgimage"]    = clean_slweg($_POST["layout_topspace_bgimage"]);
        $pagelayout["layout_topspace_class"]      = clean_slweg($_POST["layout_topspace_class"]);

        $pagelayout["layout_bottomspace_height"]  = get_pix_or_percent($_POST["layout_bottomspace_height"]);
        $pagelayout["layout_bottomspace_bgcolor"] = clean_slweg($_POST["layout_bottomspace_bgcolor"],7);
        $pagelayout["layout_bottomspace_bgimage"] = clean_slweg($_POST["layout_bottomspace_bgimage"]);
        $pagelayout["layout_bottomspace_class"]   = clean_slweg($_POST["layout_bottomspace_class"]);

        $pagelayout["layout_footer_height"]       = get_pix_or_percent($_POST["layout_footer_height"]);
        $pagelayout["layout_footer_bgcolor"]      = clean_slweg($_POST["layout_footer_bgcolor"],7);
        $pagelayout["layout_footer_bgimage"]      = clean_slweg($_POST["layout_footer_bgimage"]);
        $pagelayout["layout_footer_class"]        = clean_slweg($_POST["layout_footer_class"]);

        $pagelayout["layout_render"]              = intval($_POST["layout_render"]);

        $pagelayout["layout_customblocks"]      = phpwcms_remove_accents(str_replace(' ', ',', strtoupper(clean_slweg($_POST['layout_customblocks']))));
        $pagelayout["layout_customblocks"]      = convertStringToArray($pagelayout["layout_customblocks"]);
        if(is_array($pagelayout["layout_customblocks"]) && count($pagelayout["layout_customblocks"])) {

            // now remove the default pre-defined block name CONTENT and cut to max length of 50
            if(is_array($pagelayout["layout_customblocks"]) && count($pagelayout["layout_customblocks"])) {
                foreach($pagelayout["layout_customblocks"] as $key => $value) {
                    $value = substr($value, 0, 20);
                    $pagelayout["layout_customblocks"][$key] = $value;
                    if(in_array($value, array('CONTENT', 'LEFT', 'RIGHT', 'HEADER', 'FOOTER', 'CPSET', 'SYSTEM'))) {
                        unset($pagelayout["layout_customblocks"][$key]);
                    }
                }
            }

            $pagelayout["layout_customblocks"] = implode(', ', $pagelayout["layout_customblocks"]);

        } else {

            $pagelayout["layout_customblocks"] = '';

        }

        if($pagelayout["id"]) {
            // if ID <> 0 then update pagelayout
            $query_mode = 'UPDATE';
            $sql =  "UPDATE ".DB_PREPEND."phpwcms_pagelayout SET ".
                    "pagelayout_name='".aporeplace($pagelayout["layout_name"])."', ".
                    "pagelayout_default=".$pagelayout["layout_default"].", ".
                    "pagelayout_var='".aporeplace(serialize($pagelayout))."' ".
                    "WHERE pagelayout_id=".$pagelayout["id"];
        } else {
            // if ID = 0 then create new pagelayout
            $query_mode = 'INSERT';
            $sql =  "INSERT INTO ".DB_PREPEND."phpwcms_pagelayout (".
                    "pagelayout_name, pagelayout_default, pagelayout_var) VALUES ('".
                    aporeplace($pagelayout["layout_name"])."', ".$pagelayout["layout_default"].", '".
                    aporeplace(serialize($pagelayout))."')";
        }

        // update or insert data entry
        $result = _dbQuery($sql, $query_mode);
        if($query_mode === 'INSERT' && !empty($result['INSERT_ID'])) {
            $pagelayout["id"] = $result['INSERT_ID'];
        }

        //now proof for default pagelayout and set
        if($pagelayout["layout_default"]) {
            _dbQuery("UPDATE ".DB_PREPEND."phpwcms_pagelayout SET pagelayout_default=0 WHERE pagelayout_id != ".$pagelayout["id"], 'UPDATE');
        }

        update_cache();

        if($pagelayout["id"]) {
            headerRedirect(PHPWCMS_URL.'phpwcms.php?'.get_token_get_string().'&do=admin&p=8&s='.$pagelayout["id"]);
        }

    }

    if($pagelayout["id"]) {

        // read the given pagelayout from db
        $sql = "SELECT * FROM ".DB_PREPEND."phpwcms_pagelayout WHERE pagelayout_id=".$pagelayout["id"]." LIMIT 1";
        $result = _dbQuery($sql);
        if(isset($result[0]['pagelayout_id'])) {
            $pagelayout = unserialize($result[0]["pagelayout_var"], ['allowed_classes' => false]);
            $pagelayout["id"] = $result[0]["pagelayout_id"];
            $pagelayout["layout_default"] = $result[0]["pagelayout_default"];
        }

    } else {

        // set default pagelayout information
        $pagelayout = array();

        $pagelayout["id"]                         = 0;
        $pagelayout["layout_align"]               = 0;
        $pagelayout["layout_type"]                = 0;
        $pagelayout["layout_border_top"]          = '';
        $pagelayout["layout_border_bottom"]       = '';
        $pagelayout["layout_border_left"]         = '';
        $pagelayout["layout_border_right"]        = '';
        $pagelayout["layout_title"]               = "Pagetitle";
        $pagelayout["layout_title_cat"]           = 1;
        $pagelayout["layout_title_article"]       = 1;
        $pagelayout["layout_bgcolor"]             = '';
        $pagelayout["layout_bgimage"]             = '';
        $pagelayout["layout_jsonload"]            = '';
        $pagelayout["layout_textcolor"]           = '';
        $pagelayout["layout_linkcolor"]           = '';
        $pagelayout["layout_vcolor"]              = '';
        $pagelayout["layout_acolor"]              = '';
        $pagelayout["layout_all_width"]           = '';
        $pagelayout["layout_all_bgcolor"]         = '';
        $pagelayout["layout_all_bgimage"]         = '';
        $pagelayout["layout_all_class"]           = '';
        $pagelayout["layout_content_width"]       = '';
        $pagelayout["layout_content_bgcolor"]     = '';
        $pagelayout["layout_content_bgimage"]     = '';
        $pagelayout["layout_content_class"]       = '';
        $pagelayout["layout_left_width"]          = '';
        $pagelayout["layout_left_bgcolor"]        = '';
        $pagelayout["layout_left_bgimage"]        = '';
        $pagelayout["layout_left_class"]          = '';
        $pagelayout["layout_right_width"]         = '';
        $pagelayout["layout_right_bgcolor"]       = '';
        $pagelayout["layout_right_bgimage"]       = '';
        $pagelayout["layout_right_class"]         = '';
        $pagelayout["layout_leftspace_width"]     = '';
        $pagelayout["layout_leftspace_bgcolor"]   = '';
        $pagelayout["layout_leftspace_bgimage"]   = '';
        $pagelayout["layout_leftspace_class"]     = '';
        $pagelayout["layout_rightspace_width"]    = '';
        $pagelayout["layout_rightspace_bgcolor"]  = '';
        $pagelayout["layout_rightspace_bgimage"]  = '';
        $pagelayout["layout_rightspace_class"]    = '';
        $pagelayout["layout_header_height"]       = '';
        $pagelayout["layout_header_bgcolor"]      = '';
        $pagelayout["layout_header_bgimage"]      = '';
        $pagelayout["layout_header_class"]        = '';
        $pagelayout["layout_topspace_height"]     = '';
        $pagelayout["layout_topspace_bgcolor"]    = '';
        $pagelayout["layout_topspace_bgimage"]    = '';
        $pagelayout["layout_topspace_class"]      = '';
        $pagelayout["layout_bottomspace_height"]  = '';
        $pagelayout["layout_bottomspace_bgcolor"] = '';
        $pagelayout["layout_bottomspace_bgimage"] = '';
        $pagelayout["layout_bottomspace_class"]   = '';
        $pagelayout["layout_footer_height"]       = '';
        $pagelayout["layout_footer_bgcolor"]      = '';
        $pagelayout["layout_footer_bgimage"]      = '';
        $pagelayout["layout_footer_class"]        = '';
        $pagelayout["layout_render"]                = 2;
        $pagelayout["layout_title_order"]           = 4;
        $pagelayout["layout_title_spacer"]          = ' | ';
        $pagelayout["layout_noborder"]              = 1;

    }

    initJQuery(); // switch to jQuery
    $pagelayout['editable_hidden'] = $pagelayout["layout_render"] === 2 ? ' style="display:none"' : '';

?>
<form action="phpwcms.php?do=admin&p=8&s=<?php echo $pagelayout["id"] ?>" method="post" name="pagelayout" target="_self">


<div class="row align-items-center">
	<div class="col col-sm-auto text-center text-sm-left">
		<h1><?php echo $BL['be_subnav_admin_pagelayout'] ?></h1>
	</div>
<div class="col-12 col-sm text-center text-sm-right mb-3">
	 <div class="form-group">
		<input name="layout_id" type="hidden" value="<?php echo $pagelayout["id"] ?>">
		<button name="Submit" type="submit" class="btn btn-sm btn-blue" value="1"><i class="fa fa-save"></i> <?php echo $BL['be_admin_page_button'] ?></button>
		<a href="phpwcms.php?do=admin&amp;p=8" class="btn btn-sm btn-danger ml-3"><i class="fa fa-times"></i> <?php echo $BL['be_admin_struct_close'] ?></a>
     </div>
</div>
</div>

<div class="card">
	<div class="card-header"><h2><?php echo $BL['be_admin_page_title'] ?></h2></div>
	<div class="card-body pb-2">

    <div class="form-group form-row align-items-center">
      <label for="layout_name" class="col-sm-2 col-form-label text-right"><?php echo $BL['be_admin_page_name'] ?></label>
      <div class="col-sm-7">
        <input name="layout_name" type="text" class="form-control form-control-sm" id="layout_name" value="<?php echo  isset($pagelayout["layout_name"]) ? html($pagelayout["layout_name"]) : '' ?>" >
      </div>
      <div class="col-sm-3 mt-2 mt-sm-0">
      	<div class="form-check">
					<input class="form-check-input" name="layout_default" type="checkbox" id="layout_default" value="1" <?php is_checked(isset($pagelayout["layout_default"]) ? $pagelayout["layout_default"] : 0, 1) ?>>
					<label class="form-check-label" for="layout_default"><?php echo $BL['be_admin_tmpl_default'] ?></label>
				</div>
      </div>
    </div>

<hr>

<div class="form-group form-row">
  <label class="col-sm-2 col-form-label text-right pt-0"><?php echo $BL['be_admin_page_render'] ?></label>
  <div class="col-sm-10">
    <div class="form-check">
			<input class="form-check-input" name="layout_render" id="layout_render_2" value="2" type="radio" <?php is_checked(2, $pagelayout["layout_render"]); ?>>
			<label class="form-check-label" for="layout_render_2" ><strong><?php echo $BL['be_admin_page_custom'].'</strong> <span>('.$BL['be_admin_page_custominfo'].')</span>' ?></label>
    </div>
    <div class="form-check">
			<input class="form-check-input" name="layout_render" id="layout_render_0" value="0" type="radio" <?php is_checked(0, $pagelayout["layout_render"]); ?>>
			<label class="form-check-label" for="layout_render_0"><?php echo $BL['be_admin_page_table'] ?></label>
    </div>
    <div class="form-check">
			<input class="form-check-input" name="layout_render" id="layout_render_1" value="1" type="radio" <?php is_checked(1, $pagelayout["layout_render"]); ?>>
			<label class="form-check-label" for="layout_render_1"><?php echo $BL['be_admin_page_div'] ?></label>
    </div>
  </div>
</div>

<div class="form-group form-row align-items-center">
	<label for="layout_customblocks" class="col-sm-2 col-form-label text-right"><?php echo $BL['be_admin_page_blocks'].', '.$BL['be_admin_page_customblocks'] ?></label>
	<div class="col">
		<input type="text" class="form-control form-control-sm" name="layout_customblocks" id="layout_customblocks" value="<?php echo isset($pagelayout["layout_customblocks"]) ? html($pagelayout["layout_customblocks"]) : '' ?>" >
	</div>
</div>

<div class="form-group form-row align-items-center">
	<label for="layout_title" class="col-sm-2 col-form-label text-right"><?php echo $BL['be_admin_page_pagetitle'] ?></label>
	<div class="col">
		<input type="text" class="form-control form-control-sm" name="layout_title" id="layout_title" value="<?php echo html($pagelayout["layout_title"]); ?>" >
	</div>
</div>

<div class="form-group form-row align-items-center">
	<label for="be_admin_page_addtotitle" class="col-sm-2 col-form-label text-right"><?php echo $BL['be_admin_page_addtotitle'] ?></label>
	<div class="col-sm-5">
		<select name="layout_title_order" type="text" class="custom-select form-control form-control-sm" id="layout_title_order" >
					<?php
			if(empty($pagelayout["layout_title_order"])) {
					$pagelayout["layout_title_order"] = 0;
			}
			if(empty($pagelayout["layout_title_spacer"])) {
					$pagelayout["layout_title_spacer"] = ' | ';
			}
			?>
			<option value="0"<?php is_selected(0, $pagelayout["layout_title_order"]) ?>><?php echo $BL['be_admin_page_pagetitle'].', '.$BL['be_admin_page_category'].', '.$BL['be_admin_page_articlename'] ?></option>
			<option value="1"<?php is_selected(1, $pagelayout["layout_title_order"]) ?>><?php echo $BL['be_admin_page_pagetitle'].', '.$BL['be_admin_page_articlename'].', '.$BL['be_admin_page_category'] ?></option>
			<option value="2"<?php is_selected(2, $pagelayout["layout_title_order"]) ?>><?php echo $BL['be_admin_page_category'].', '.$BL['be_admin_page_articlename'].', '.$BL['be_admin_page_pagetitle'] ?></option>
			<option value="3"<?php is_selected(3, $pagelayout["layout_title_order"]) ?>><?php echo $BL['be_admin_page_category'].', '.$BL['be_admin_page_pagetitle'].', '.$BL['be_admin_page_articlename'] ?></option>
			<option value="4"<?php is_selected(4, $pagelayout["layout_title_order"]) ?>><?php echo $BL['be_admin_page_articlename'].', '.$BL['be_admin_page_category'].', '.$BL['be_admin_page_pagetitle'] ?></option>
			<option value="5"<?php is_selected(5, $pagelayout["layout_title_order"]) ?>><?php echo $BL['be_admin_page_articlename'].', '.$BL['be_admin_page_pagetitle'].', '.$BL['be_admin_page_category'] ?></option>

			<option value="6"<?php is_selected(6, $pagelayout["layout_title_order"]) ?>><?php echo $BL['be_admin_page_pagetitle'].', '.$BL['be_admin_page_category'] ?></option>
			<option value="7"<?php is_selected(7, $pagelayout["layout_title_order"]) ?>><?php echo $BL['be_admin_page_pagetitle'].', '.$BL['be_admin_page_articlename'] ?></option>
			<option value="8"<?php is_selected(8, $pagelayout["layout_title_order"]) ?>><?php echo $BL['be_admin_page_category'].', '.$BL['be_admin_page_articlename'] ?></option>
			<option value="9"<?php is_selected(9, $pagelayout["layout_title_order"]) ?>><?php echo $BL['be_admin_page_category'].', '.$BL['be_admin_page_pagetitle'] ?></option>
			<option value="10"<?php is_selected(10, $pagelayout["layout_title_order"]) ?>><?php echo $BL['be_admin_page_articlename'].', '.$BL['be_admin_page_category'] ?></option>
			<option value="11"<?php is_selected(11, $pagelayout["layout_title_order"]) ?>><?php echo $BL['be_admin_page_articlename'].', '.$BL['be_admin_page_pagetitle'] ?></option>

			<option value="12"<?php is_selected(12, $pagelayout["layout_title_order"]) ?>><?php echo $BL['be_admin_page_pagetitle'] ?></option>
			<option value="13"<?php is_selected(13, $pagelayout["layout_title_order"]) ?>><?php echo $BL['be_admin_page_category'] ?></option>
			<option value="14"<?php is_selected(14, $pagelayout["layout_title_order"]) ?>><?php echo $BL['be_admin_page_articlename'] ?></option>
		</select>

		</div>
	<div class="col-sm-5">
		<div class="row align-items-center">
			<label class="col-sm-3 col-form-label text-right"><?php echo $BL['be_cnt_field']['break'] ?></label>
				<div class="col">
					<input class="form-control form-control-sm col" name="layout_title_spacer" type="text" id="layout_title_spacer" value="<?php echo html($pagelayout["layout_title_spacer"]); ?>">
				</div>
		</div>
	</div>
</div>

<hr class="pagelayout-editable" <?php echo  $pagelayout['editable_hidden']; ?>/>

	<fieldset class="form-group pagelayout-editable"<?php echo $pagelayout['editable_hidden']; ?>>
		<div class="form-row align-items-center">
			<label class="col-sm-2 col-form-label text-right"><?php echo $BL['be_admin_page_align']  ?></label>
			<div class="col">
				<div class="form-check form-check-inline">
					<input class="form-check-input" type="radio" name="layout_align" id="layout_align_0" value="0" <?php is_checked(0, $pagelayout["layout_align"]); ?>>
					<label class="form-check-label" for="layout_align_0"><img src="img/symbole/layout_left.svg" alt="<?php echo $BL['be_admin_page_align_left'] ?>" width="56" height="44" border="0"></label>
				</div>
				<div class="form-check form-check-inline">
					<input class="form-check-input" type="radio" name="layout_align" id="layout_align_1" value="1" <?php is_checked(0, $pagelayout["layout_align"]); ?>>
					<label class="form-check-label" for="layout_align_1"><img src="img/symbole/layout_center.svg" alt="<?php echo $BL['be_admin_page_align_center'] ?>" width="56" height="44" border="0"></label>
				</div>
				<div class="form-check form-check-inline">
					<input class="form-check-input" type="radio" name="layout_align" id="layout_align_2" value="2" <?php is_checked(0, $pagelayout["layout_align"]); ?>>
					<label class="form-check-label" for="layout_align_2" ><img src="img/symbole/layout_right.svg" alt="<?php echo $BL['be_admin_page_align_right'] ?>" width="56" height="44" border="0"></label>
				</div>
			</div>
		</div>
	</fieldset>

    <div class="form-group form-row align-items-center pagelayout-editable"<?php echo  $pagelayout['editable_hidden']; ?>>
      <label class="col-sm-2 col-form-label text-right"><?php echo $BL['be_admin_page_margin']  ?></label>
      <div class="col">
        <input type="number" class="form-control form-control-sm" name="layout_border_top" id="layout_border_top" value="<?php echo $pagelayout["layout_border_top"] ?>" placeholder="<?php echo $BL['be_admin_page_top'] ?>">
      </div>
      <div class="col">
        <input type="number" class="form-control form-control-sm" name="layout_border_bottom" id="layout_border_bottom" value="<?php echo $pagelayout["layout_border_bottom"] ?>" placeholder="<?php echo $BL['be_admin_page_bottom'] ?>">
      </div>
      <div class="col">
        <input type="number" class="form-control form-control-sm" name="layout_border_left" id="layout_border_left" value="<?php echo $pagelayout["layout_border_left"] ?>" placeholder="<?php echo $BL['be_admin_page_left'] ?>">
      </div>
      <div class="col">
        <input type="number" class="form-control form-control-sm" name="layout_border_right" id="layout_border_right" value="<?php echo $pagelayout["layout_border_right"] ?>" placeholder="<?php echo $BL['be_admin_page_right'] ?>">
      </div>
      <div class="col">
      	<div class="form-check form-check-inline">
					<input class="form-check-input" name="layout_noborder" type="checkbox" id="layout_noborder" value="1" <?php is_checked(1, isset($pagelayout["layout_noborder"]) ? $pagelayout["layout_noborder"] : 0) ?>>
					<label class="form-check-label" for="layout_noborder"> <?php echo $BL['be_admin_page_disable'] ?></label>
        </div>
      </div>
    </div>


    <div class="form-group form-row align-items-center pagelayout-editable"<?php echo  $pagelayout['editable_hidden']; ?>>
      <label class="col-sm-2 col-form-label text-right"><?php echo $BL['be_admin_page_bg'] ?></label>
        <div class="col">
         	<input class="form-control form-control-sm" name="layout_bgcolor" type="text" id="layout_bgcolor2" value="<?php echo html($pagelayout["layout_bgcolor"]); ?>" placeholder="<?php echo $BL['be_admin_page_color'] ?>">
		</div>
        <div class="col">
        	<input class="form-control form-control-sm" name="layout_bgimage" type="text" id="layout_bgimage" value="<?php echo html($pagelayout["layout_bgimage"]); ?>"  placeholder="<?php echo $BL['be_admin_page_image'] ?>">
		</div>
    </div>

    <div class="form-group form-row align-items-center pagelayout-editable"<?php echo  $pagelayout['editable_hidden']; ?>>
      <label class="col-sm-2 col-form-label text-right"><?php echo $BL['be_admin_page_color'] ?></label>
        <div class="col">
         	<input class="form-control form-control-sm" name="layout_textcolor" type="text" id="layout_textcolor" value="<?php echo html($pagelayout["layout_textcolor"]); ?>" placeholder="<?php echo $BL['be_admin_page_text'] ?>">
		</div>
        <div class="col">
        	<input class="form-control form-control-sm" name="layout_linkcolor" type="text" id="layout_linkcolor" value="<?php echo html($pagelayout["layout_linkcolor"]); ?>"  placeholder="<?php echo $BL['be_admin_page_link'] ?>">
		</div>
        <div class="col">
         	<input class="form-control form-control-sm" name="layout_vcolor" type="text" id="layout_vcolor" value="<?php echo html($pagelayout["layout_vcolor"]); ?>" placeholder="<?php echo $BL['be_admin_page_visited'] ?>">
		</div>
        <div class="col">
        	<input class="form-control form-control-sm" name="layout_acolor" type="text" id="layout_acolor" value="<?php echo html($pagelayout["layout_acolor"]); ?>"  placeholder="<?php echo $BL['be_ftptakeover_active'] ?>">
		</div>
    </div>

		<div class="form-group form-row align-items-center pagelayout-editable"<?php echo  $pagelayout['editable_hidden']; ?>>
			<label class="col-sm-2 col-form-label text-right"><?php echo $BL['be_admin_page_js'] ?></label>
			<label for="layout_name" class="col-sm-auto col-form-label text-right">onload:</label>
			<div class="col">
				<input class="form-control form-control-sm" name="layout_jsonload" type="text" id="layout_jsonload" value="<?php echo html($pagelayout["layout_jsonload"]); ?>">
			</div>
		</div>

<hr class="pagelayout-editable" <?php echo  $pagelayout['editable_hidden']; ?>/>

		<fieldset class="form-group pagelayout-editable"<?php echo  $pagelayout['editable_hidden']; ?>>
			<div class="form-row align-items-center">
				<label class="col-sm-2 col-form-label text-right"><?php echo $BL['be_admin_page_blocks'] ?></label>
				<div class="col-sm-10">
					<div class="form-row">
						<div class="col-sm-auto form-check form-check-inline">
							<input class="form-check-input" type="radio" name="layout_type" id="layout_type_0" value="0" <?php is_checked(0, $pagelayout["layout_type"]); ?>>
							<label class="form-check-label" for="layout_type_0"><img src="img/symbole/3_column_layout.svg" alt="<?php echo $BL['be_admin_page_col1'] ?>" width="56" height="44" border="0"></label>
						</div>
						<div class="col-sm-auto form-check form-check-inline">
							<input class="form-check-input" type="radio" name="layout_type" id="layout_type_1" value="1" <?php is_checked(1, $pagelayout["layout_type"]); ?>>
							<label class="form-check-label" for="layout_type_1"><img src="img/symbole/2_column_layout.svg" alt="<?php echo $BL['be_admin_page_col2'] ?>" width="56" height="44" border="0"></label>
						</div>
						<div class="col-sm-auto form-check form-check-inline">
							<input class="form-check-input" type="radio" name="layout_type" id="layout_type_2" value="2" <?php is_checked(2, $pagelayout["layout_type"]); ?>>
							<label class="form-check-label" for="layout_type_2"><img src="img/symbole/4_column_layout.svg" alt="<?php echo $BL['be_admin_page_col3'] ?>" width="56" height="44" border="0"></label>
						</div>
						<div class="col form-check form-check-inline">
							<input class="form-check-input" type="radio" name="layout_type" id="layout_align_3" value="3" <?php is_checked(3, $pagelayout["layout_type"]); ?>>
							<label class="form-check-label" for="layout_align_3"><img src="img/symbole/1_column_layout.svg" alt="<?php echo $BL['be_admin_page_col4'] ?>" width="56" height="44" border="0"></label>
						</div>
					</div>
				</div>
			</div>
		</fieldset>

    <div class="form-group form-row align-items-center pagelayout-editable"<?php echo  $pagelayout['editable_hidden']; ?>>
      <label class="col-sm-2 col-form-label text-right"><?php echo $BL['be_admin_page_allblocks'] ?></label>
        <div class="col">
         	<input class="form-control form-control-sm" name="layout_all_width" type="text" id="layout_all_width" value="<?php echo $pagelayout["layout_all_width"] ?>" placeholder="<?php echo $BL['be_admin_page_width'] ?>">
		</div>
        <div class="col">
        	<input class="form-control form-control-sm" name="layout_all_bgcolor" type="text" id="layout_all_bgcolor" value="<?php echo html($pagelayout["layout_all_bgcolor"]); ?>" placeholder="<?php echo $BL['be_admin_page_color'] ?>">
		</div>
        <div class="col">
         	<input class="form-control form-control-sm" name="layout_all_bgimage" type="text" id="layout_all_bgimage" value="<?php echo html($pagelayout["layout_all_bgimage"]); ?>" placeholder="<?php echo $BL['be_admin_page_image'] ?>">
		</div>
        <div class="col">
        	<input class="form-control form-control-sm" name="layout_all_class" type="text" id="layout_all_class" value="<?php echo html($pagelayout["layout_all_class"]); ?>" placeholder="<?php echo $BL['be_admin_page_class'] ?>">
		</div>
    </div>

    <div class="form-group form-row align-items-center pagelayout-editable"<?php echo  $pagelayout['editable_hidden']; ?>>
      <label class="col-sm-2 col-form-label text-right"><?php echo $BL['be_admin_page_left'] ?></label>
        <div class="col">
         	<input class="form-control form-control-sm" name="layout_left_width" type="text" id="layout_left_width" value="<?php echo $pagelayout["layout_left_width"] ?>" placeholder="<?php echo $BL['be_admin_page_width'] ?>">
		</div>
        <div class="col">
        	<input class="form-control form-control-sm" name="layout_left_bgcolor" type="text" id="layout_left_bgcolor" value="<?php echo html($pagelayout["layout_left_bgcolor"]); ?>" placeholder="<?php echo $BL['be_admin_page_color'] ?>">
		</div>
        <div class="col">
         	<input class="form-control form-control-sm" name="layout_left_bgimage" type="text" id="layout_left_bgimage" value="<?php echo html($pagelayout["layout_left_bgimage"]); ?>" placeholder="<?php echo $BL['be_admin_page_image'] ?>">
		</div>
        <div class="col">
        	<input class="form-control form-control-sm" name="layout_left_class" type="text" id="layout_left_class" value="<?php echo html($pagelayout["layout_left_class"]); ?>" placeholder="<?php echo $BL['be_admin_page_class'] ?>">
		</div>
    </div>

    <div class="form-group form-row align-items-center pagelayout-editable"<?php echo  $pagelayout['editable_hidden']; ?>>
      <label class="col-sm-2 col-form-label text-right"><?php echo $BL['be_admin_page_leftspace'] ?></label>
        <div class="col">
         	<input class="form-control form-control-sm" name="layout_leftspace_width" type="text" id="layout_leftspace_width" value="<?php echo $pagelayout["layout_leftspace_width"] ?>" placeholder="<?php echo $BL['be_admin_page_width'] ?>">
		</div>
        <div class="col">
        	<input class="form-control form-control-sm" name="layout_leftspace_bgcolor" type="text" id="layout_leftspace_bgcolor" value="<?php echo html($pagelayout["layout_leftspace_bgcolor"]); ?>" placeholder="<?php echo $BL['be_admin_page_color'] ?>">
		</div>
        <div class="col">
         	<input class="form-control form-control-sm" name="layout_leftspace_bgimage" type="text" id="layout_leftspace_bgimage" value="<?php echo html($pagelayout["layout_leftspace_bgimage"]); ?>" placeholder="<?php echo $BL['be_admin_page_image'] ?>">
		</div>
        <div class="col">
        	<input class="form-control form-control-sm" name="layout_leftspace_class" type="text" id="layout_leftspace_class" value="<?php echo html($pagelayout["layout_leftspace_class"]); ?>" placeholder="<?php echo $BL['be_admin_page_class'] ?>">
		</div>
    </div>

    <div class="form-group form-row align-items-center pagelayout-editable"<?php echo  $pagelayout['editable_hidden']; ?>>
      <label class="col-sm-2 col-form-label text-right"><?php echo $BL['be_admin_page_main']."&nbsp;[".$phpwcms["content_width"]?>]</label>
        <div class="col">
         	<input class="form-control form-control-sm" name="layout_content_width" type="text" id="layout_content_width" value="<?php echo $pagelayout["layout_content_width"] ?>" placeholder="<?php echo $BL['be_admin_page_width'] ?>">
		</div>
        <div class="col">
        	<input class="form-control form-control-sm" name="layout_content_bgcolor" type="text" id="layout_content_bgcolor" value="<?php echo html($pagelayout["layout_content_bgcolor"]); ?>" placeholder="<?php echo $BL['be_admin_page_color'] ?>">
		</div>
        <div class="col">
         	<input class="form-control form-control-sm" name="layout_content_bgimage" type="text" id="layout_content_bgimage" value="<?php echo html($pagelayout["layout_content_bgimage"]); ?>" placeholder="<?php echo $BL['be_admin_page_image'] ?>">
		</div>
        <div class="col">
        	<input class="form-control form-control-sm" name="layout_content_class" type="text" id="layout_content_class" value="<?php echo html($pagelayout["layout_content_class"]); ?>" placeholder="<?php echo $BL['be_admin_page_class'] ?>">
		</div>
    </div>

    <div class="form-group form-row align-items-center pagelayout-editable"<?php echo  $pagelayout['editable_hidden']; ?>>
      <label class="col-sm-2 col-form-label text-right"><?php echo $BL['be_admin_page_rightspace'] ?></label>
        <div class="col">
         	<input class="form-control form-control-sm" name="layout_rightspace_width" type="text" id="layout_rightspace_width" value="<?php echo $pagelayout["layout_rightspace_width"] ?>" placeholder="<?php echo $BL['be_admin_page_width'] ?>">
		</div>
        <div class="col">
        	<input class="form-control form-control-sm" name="layout_rightspace_bgcolor" type="text" id="layout_rightspace_bgcolor" value="<?php echo html($pagelayout["layout_rightspace_bgcolor"]); ?>" placeholder="<?php echo $BL['be_admin_page_color'] ?>">
		</div>
        <div class="col">
         	<input class="form-control form-control-sm" name="layout_rightspace_bgimage" type="text" id="layout_rightspace_bgimage" value="<?php echo html($pagelayout["layout_rightspace_bgimage"]); ?>" placeholder="<?php echo $BL['be_admin_page_image'] ?>">
		</div>
        <div class="col">
        	<input class="form-control form-control-sm" name="layout_rightspace_class" type="text" id="layout_rightspace_class" value="<?php echo html($pagelayout["layout_rightspace_class"]); ?>" placeholder="<?php echo $BL['be_admin_page_class'] ?>">
		</div>
    </div>

    <div class="form-group form-row align-items-center pagelayout-editable"<?php echo  $pagelayout['editable_hidden']; ?>>
      <label class="col-sm-2 col-form-label text-right"><?php echo $BL['be_admin_page_right'] ?></label>
        <div class="col">
         	<input class="form-control form-control-sm" name="layout_right_width" type="text" id="layout_right_width" value="<?php echo $pagelayout["layout_right_width"] ?>" placeholder="<?php echo $BL['be_admin_page_width'] ?>">
		</div>
        <div class="col">
        	<input class="form-control form-control-sm" name="layout_right_bgcolor" type="text" id="layout_right_bgcolor" value="<?php echo html($pagelayout["layout_right_bgcolor"]); ?>" placeholder="<?php echo $BL['be_admin_page_color'] ?>">
		</div>
        <div class="col">
         	<input class="form-control form-control-sm" name="layout_right_bgimage" type="text" id="layout_right_bgimage" value="<?php echo html($pagelayout["layout_right_bgimage"]); ?>" placeholder="<?php echo $BL['be_admin_page_image'] ?>">
		</div>
        <div class="col">
        	<input class="form-control form-control-sm" name="layout_right_class" type="text" id="layout_right_class" value="<?php echo html($pagelayout["layout_right_class"]); ?>" placeholder="<?php echo $BL['be_admin_page_class'] ?>">
		</div>
    </div>

<hr class="pagelayout-editable" <?php echo  $pagelayout['editable_hidden']; ?>/>

    <div class="form-group form-row align-items-center pagelayout-editable"<?php echo  $pagelayout['editable_hidden']; ?>>
      <label class="col-sm-2 col-form-label text-right"><?php echo $BL['be_admin_page_header'] ?></label>
        <div class="col">
         	<input class="form-control form-control-sm" name="layout_header_height" type="text" id="layout_header_height" value="<?php echo $pagelayout["layout_header_height"] ?>" placeholder="<?php echo $BL['be_admin_page_height'] ?>">
		</div>
        <div class="col">
        	<input class="form-control form-control-sm" name="layout_header_bgcolor" type="text" id="layout_header_bgcolor" value="<?php echo html($pagelayout["layout_header_bgcolor"]); ?>" placeholder="<?php echo $BL['be_admin_page_color'] ?>">
		</div>
        <div class="col">
         	<input class="form-control form-control-sm" name="layout_header_bgimage" type="text" id="layout_header_bgimage" value="<?php echo html($pagelayout["layout_header_bgimage"]); ?>" placeholder="<?php echo $BL['be_admin_page_image'] ?>">
		</div>
        <div class="col">
        	<input class="form-control form-control-sm" name="layout_header_class" type="text" id="layout_header_class" value="<?php echo html($pagelayout["layout_header_class"]); ?>" placeholder="<?php echo $BL['be_admin_page_class'] ?>">
		</div>
    </div>

    <div class="form-group form-row align-items-center pagelayout-editable"<?php echo  $pagelayout['editable_hidden']; ?>>
      <label class="col-sm-2 col-form-label text-right"><?php echo $BL['be_admin_page_topspace'] ?></label>
        <div class="col">
         	<input class="form-control form-control-sm" name="layout_topspace_height" type="text" id="layout_topspace_height" value="<?php echo $pagelayout["layout_topspace_height"] ?>" placeholder="<?php echo $BL['be_admin_page_height'] ?>">
		</div>
        <div class="col">
        	<input class="form-control form-control-sm" name="layout_topspace_bgcolor" type="text" id="layout_topspace_bgcolor" value="<?php echo html($pagelayout["layout_topspace_bgcolor"]); ?>" placeholder="<?php echo $BL['be_admin_page_color'] ?>">
		</div>
        <div class="col">
         	<input class="form-control form-control-sm" name="layout_topspace_bgimage" type="text" id="layout_topspace_bgimage" value="<?php echo html($pagelayout["layout_topspace_bgimage"]); ?>" placeholder="<?php echo $BL['be_admin_page_image'] ?>">
		</div>
        <div class="col">
        	<input class="form-control form-control-sm" name="layout_topspace_class" type="text" id="layout_topspace_class" value="<?php echo html($pagelayout["layout_topspace_class"]); ?>" placeholder="<?php echo $BL['be_admin_page_class'] ?>">
		</div>
    </div>

    <div class="form-group form-row align-items-center pagelayout-editable"<?php echo  $pagelayout['editable_hidden']; ?>>
      <label class="col-sm-2 col-form-label text-right"><?php echo $BL['be_admin_page_bottomspace'] ?></label>
        <div class="col">
         	<input class="form-control form-control-sm" name="layout_bottomspace_height" type="text" id="layout_bottomspace_height" value="<?php echo $pagelayout["layout_bottomspace_height"] ?>" placeholder="<?php echo $BL['be_admin_page_height'] ?>">
		</div>
        <div class="col">
        	<input class="form-control form-control-sm" name="layout_bottomspace_bgcolor" type="text" id="layout_bottomspace_bgcolor" value="<?php echo html($pagelayout["layout_bottomspace_bgcolor"]); ?>" placeholder="<?php echo $BL['be_admin_page_color'] ?>">
		</div>
        <div class="col">
         	<input class="form-control form-control-sm" name="layout_bottomspace_bgimage" type="text" id="layout_bottomspace_bgimage" value="<?php echo html($pagelayout["layout_bottomspace_bgimage"]); ?>" placeholder="<?php echo $BL['be_admin_page_image'] ?>">
		</div>
        <div class="col">
        	<input class="form-control form-control-sm" name="layout_bottomspace_class" type="text" id="layout_bottomspace_class" value="<?php echo html($pagelayout["layout_bottomspace_class"]); ?>" placeholder="<?php echo $BL['be_admin_page_class'] ?>">
		</div>
    </div>

    <div class="form-group form-row align-items-center pagelayout-editable"<?php echo  $pagelayout['editable_hidden']; ?>>
      <label class="col-sm-2 col-form-label text-right"><?php echo $BL['be_admin_page_footer'] ?></label>
        <div class="col">
         	<input class="form-control form-control-sm" name="layout_footer_height" type="text" id="layout_footer_height" value="<?php echo $pagelayout["layout_footer_height"] ?>" placeholder="<?php echo $BL['be_admin_page_height'] ?>">
		</div>
        <div class="col">
        	<input class="form-control form-control-sm" name="layout_footer_bgcolor" type="text" id="layout_footer_bgcolor" value="<?php echo html($pagelayout["layout_footer_bgcolor"]); ?>" placeholder="<?php echo $BL['be_admin_page_color'] ?>">
		</div>
        <div class="col">
         	<input class="form-control form-control-sm" name="layout_footer_bgimage" type="text" id="layout_footer_bgimage" value="<?php echo html($pagelayout["layout_footer_bgimage"]); ?>" placeholder="<?php echo $BL['be_admin_page_image'] ?>">
		</div>
        <div class="col">
        	<input class="form-control form-control-sm" name="layout_footer_class" type="text" id="layout_footer_class" value="<?php echo html($pagelayout["layout_footer_class"]); ?>" placeholder="<?php echo $BL['be_admin_page_class'] ?>">
		</div>
    </div>

    </div>
</div>

	<div class="form-group align-items-center mt-4 mb-0">
		<input name="layout_id" type="hidden" value="<?php echo $pagelayout["id"] ?>">
		<button name="Submit" type="submit" class="btn btn-sm btn-blue" value="1"><i class="fa fa-save"></i> <?php echo $BL['be_admin_page_button'] ?></button>
		<a href="phpwcms.php?do=admin&amp;p=8" class="btn btn-sm btn-danger ml-3"><i class="fa fa-times"></i> <?php echo $BL['be_admin_struct_close'] ?></a>
	</div>

</form>

<script type="text/javascript">
$(function(){
    var $pagelayout_editable_items = $('.pagelayout-editable'),
        $pagelayout_radio_group = $('#radio-group-layout-render'),
        $pagelayout_radio_group_items = $("input[name='layout_render']"),
        $pagelayout_layout_render_value = <?php echo $pagelayout["layout_render"]; ?>,
        togglePagelayoutEditableItems = function() {
            var layout_render_value = parseInt($pagelayout_radio_group_items.filter(':checked').val(), 10);

            if(layout_render_value !== $pagelayout_layout_render_value) {
                $pagelayout_layout_render_value = layout_render_value;
                if($pagelayout_layout_render_value === 2) {
                    $pagelayout_editable_items.hide();
                } else {
                    $pagelayout_editable_items.show();
                }
            }
        };

    $pagelayout_radio_group_items.on({
        change: togglePagelayoutEditableItems,
        click: togglePagelayoutEditableItems
    });

});
</script>
<?php

}
