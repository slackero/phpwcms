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

//link article

if(!isset($content['alink']['alink_id']) && isset($row["acontent_alink"])) {
    $content['alink']['alink_id']   = explode(':', $row["acontent_alink"]);
} elseif(!isset($row["acontent_alink"])) {
    $content['alink']['alink_id']   = array();
}
if(empty($content['alink']['alink_template'])) {
    $content['alink']['alink_template'] = '';
}
if(empty($content['alink']['alink_type'])) {
    $content['alink']['alink_type'] = 0;
}
if(empty($content['alink']['alink_level']) || !is_array($content['alink']['alink_level'])) {
    $content['alink']['alink_level'] = array();
}
if(empty($content['alink']['alink_unique'])) {
    $content['alink']['alink_unique'] = 0;
}
if(!isset($content['alink']['alink_allowedtags'])) {
    $content['alink']['alink_allowedtags']  = '<b><i><u><s><strong>';
}
if(empty($content['alink']['alink_crop'])) {
    $content['alink']['alink_crop'] = 0;
}
if(empty($content['alink']['alink_prio'])) {
    $content['alink']['alink_prio'] = 0;
}
if(empty($content['alink']['alink_category']) || !is_array($content['alink']['alink_category'])) {
    $content['alink']['alink_category'] = array();
}
if(empty($content['alink']['alink_category'])) {
    $content['alink']['alink_andor'] = 'OR';
}
if(empty($content['alink']['alink_columns'])) {
    $content['alink']['alink_columns'] = '';
}
if(empty($content['alink']['alink_categoryalias'])) {
    $content['alink']['alink_categoryalias'] = 0;
}
if(empty($content['alink']['alink_hidesummary'])) {
    $content['alink']['alink_hidesummary'] = 0;
}

// Get/set and reset filter
if(isset($_SESSION['teaser_filter_category'])) {
    $content['alink']['filter_category'] = $_SESSION['teaser_filter_category'];
    $_SESSION['teaser_filter_category'] = '';
    unset($_SESSION['teaser_filter_category']);
} else {
    $content['alink']['filter_category'] = null;
}
if(isset($_SESSION['teaser_filter_category_by_tags'])) {
    $content['alink']['filter_tags'] = $_SESSION['teaser_filter_category_by_tags'] ? $content['alink']['alink_category'] : array();
    $_SESSION['teaser_filter_category_by_tags'] = false;
    unset($_SESSION['teaser_filter_category_by_tags']);
} else {
    $content['alink']['filter_tags'] = null;
}

$BE['HEADER']['contentpart.js'] = getJavaScriptSourceLink('include/inc_js/contentpart.js');

// necessary JavaScript libraries
initJsAutocompleter();

?>

<div class="form-group align-items-center form-row">
  <label for="be_admin_struct_template" class="col-sm-2 col-form-label text-right"><?php echo $BL['be_admin_struct_template'] ?></label>
  <div class="col-sm-4">
    <select name="calink_template" id="calink_template" class="custom-select form-control form-control-sm">
<?php
    echo '<option value="">'.$BL['be_admin_tmpl_default'].' &lt;ul&gt;&lt;li&gt;</option>'.LF;

    // templates for forum
    $tmpllist = get_tmpl_files(CMSGO_TEMPLATE.'inc_cntpart/teaser');
    if(is_array($tmpllist) && count($tmpllist)) {
        foreach($tmpllist as $val) {
            // do not show listmode templates
            if(substr($val, 0, 5) == 'list.') {
                continue;
            }
            $vals = ($val == $content['alink']['alink_template']) ? ' selected="selected"' : '';
            $val = html($val);
            echo '<option value="'.$val.'"'.$vals.'>'.$val."</option>\n";
        }
    }
?>
    </select>
  </div>
</div>

<div class="form-group align-items-center form-row">
  <label for="be_article_rendering" class="col-sm-2 col-form-label text-right"><?php echo $BL['be_article_rendering'] ?></label>
  <div class="col-sm-auto">
    <div class="form-check-inline">
      <input class="form-check-input" type="checkbox" name="calink_unique" id="calink_unique" value="1"<?php is_checked(1, $content['alink']['alink_unique']) ?> />
      <label class="form-check-label" for="calink_unique"><?php echo $BL['be_unique_teaser_entry'] ?></label>
    </div>
  </div>
  <div class="col form-inline ml-sm-3">
    <label for="be_cnt_column" class="col-form-label text-right mx-sm-3"><?php echo $BL['be_cnt_column'] ?></label>
    <input name="calink_columns" type="text" id="calink_columns" class="form-control form-control-sm" value="<?php echo $content['alink']['alink_columns']; ?>" maxlength="3" />
  </div>
</div>

<div class="form-group align-items-center form-row">
  <label for="be_article_morelink" class="col-sm-2 col-form-label text-right"><?php echo $BL['be_article_morelink'] ?></label>
  <div class="col-sm-auto">
    <div class="form-check-inline">
      <input class="form-check-input" type="checkbox" name="calink_categoryalias" id="calink_categoryalias" value="1"<?php is_checked(1, $content['alink']['alink_categoryalias']) ?> />
      <label class="form-check-label" for="calink_categoryalias"><?php echo $BL['be_check_against_category_alias'] ?></label>
    </div>
  </div>
</div>

<div class="form-group align-items-center form-row">
  <label for="be_article_asummary" class="col-sm-2 col-form-label text-right"><?php echo $BL['be_article_asummary'] ?></label>
  <div class="col-sm-auto">
    <div class="form-inline">
      <input name="calink_wordlimit" type="text" id="calink_wordlimit" class="form-control form-control-sm mr-sm-3" value="<?php echo empty($content['alink']['alink_wordlimit']) ? '' : $content['alink']['alink_wordlimit']; ?>" maxlength="5" />
      <?php echo $BL['be_cnt_results_wordlimit'] ?>
    </div>
  </div>
  <div class="col mt-2 mt-sm-0">
    <div class="form-inline">
			<input class="form-check-input ml-sm-3" name="calink_hidesummary" type="checkbox" id="calink_hidesummary" value="1"<?php is_checked(1, $content['alink']['alink_hidesummary']); ?> />
			<label class="form-check-label" for="calink_hidesummary"><?php echo $BL['be_article_nosummary'] ?></label>
    </div>
  </div>
</div>

<div class="form-group align-items-center form-row">
  <label for="ctitle" class="col-sm-2 col-form-label text-right"><?php echo $BL['be_allowed_tags'] ?></label>
  <div class="col-sm-4">
    <input name="calink_allowedtags" type="text" id="calink_allowedtags" class="form-control form-control-sm" value="<?php echo html($content['alink']['alink_allowedtags']); ?>" />
  </div>
</div>

<div class="form-group align-items-center form-row">
  <label for="template" class="col-sm-2 col-form-label text-right"><?php echo $BL['be_ftptakeover_size'] ?></label>

  <div class="col-sm-auto my-2 my-sm-0">
    <div class="input-group input-group-sm">
			<div class="input-group-prepend">
				<span class="input-group-text"><?php echo $BL['be_cnt_maxw'] ?></span>
			</div>
			<input name="calink_width" type="text" class="form-control form-control-sm" id="calink_width" maxlength="4" onkeyup="if(!parseInt(this.value,10)) this.value='';" value="<?php echo empty($content['alink']['alink_width']) ? '' : $content['alink']['alink_width']; ?>" />
			<div class="input-group-append">
				<span class="input-group-text">px</span>
			</div>
		</div>
  </div>

  <div class="col-sm-auto my-2 my-sm-0 ml-sm-3">
    <div class="input-group input-group-sm">
			<div class="input-group-prepend">
				<span class="input-group-text"><?php echo $BL['be_cnt_maxh'] ?></span>
			</div>
			<input name="calink_height" type="text" class="form-control form-control-sm" id="calink_height" maxlength="4" onkeyup="if(!parseInt(this.value,10)) this.value='';" value="<?php echo empty($content['alink']['alink_height']) ? '' : $content['alink']['alink_height']; ?>" />
			<div class="input-group-append">
				<span class="input-group-text">px</span>
			</div>
		</div>
  </div>

  <div class="col my-2 my-sm-0 ml-sm-3">
    <div class="form-check form-check-inline">
			<input class="form-check-input ml-sm-3" name="calink_crop" type="checkbox" id="calink_crop" value="1"<?php is_checked(1, $content['alink']['alink_crop']); ?> />
			<label class="form-check-label" for="calink_crop"><?php echo $BL['be_image_crop'] ?></label>
		</div>
	</div>
</div>

<hr />

<div class="form-group align-items-center form-row">
  <label for="be_article_asummary" class="col-sm-2 col-form-label text-right"><?php echo $BL['be_cnt_ecardform_selector'] ?></label>
  <div class="col-sm-auto">
    <select name="calink_type" id="calink_type"  class="custom-select form-control form-control-sm" onchange="showHide_TeaserArticleSelection(this.options[this.selectedIndex].value)">
        <optgroup label="<?php echo $BL['be_sorted']; ?>">
            <option value="0"<?php is_selected(0, $content['alink']['alink_type']) ?>><?php echo $BL['be_admin_struct_ordermanual'] ?></option>
            <option value="1"<?php is_selected(1, $content['alink']['alink_type']) ?>><?php echo $BL['be_admin_struct_orderdate'].', '.$BL['be_admin_struct_orderdesc'] ?></option>
            <option value="2"<?php is_selected(2, $content['alink']['alink_type']) ?>><?php echo $BL['be_admin_struct_orderdate'].', '.$BL['be_admin_struct_orderasc'] ?></option>
            <option value="3"<?php is_selected(3, $content['alink']['alink_type']) ?>><?php echo $BL['be_admin_struct_orderchangedate'].', '.$BL['be_admin_struct_orderdesc'] ?></option>
            <option value="4"<?php is_selected(4, $content['alink']['alink_type']) ?>><?php echo $BL['be_admin_struct_orderchangedate'].', '.$BL['be_admin_struct_orderasc'] ?></option>
            <option value="5"<?php is_selected(5, $content['alink']['alink_type']) ?>><?php echo $BL['be_article_cnt_start'].', '.$BL['be_admin_struct_orderdesc'] ?></option>
            <option value="6"<?php is_selected(6, $content['alink']['alink_type']) ?>><?php echo $BL['be_article_cnt_start'].', '.$BL['be_admin_struct_orderasc'] ?></option>
            <option value="7"<?php is_selected(7, $content['alink']['alink_type']) ?>><?php echo $BL['be_article_cnt_end'].', '.$BL['be_admin_struct_orderdesc'] ?></option>
            <option value="8"<?php is_selected(8, $content['alink']['alink_type']) ?>><?php echo $BL['be_article_cnt_end'].', '.$BL['be_admin_struct_orderasc'] ?></option>
            <option value="18"<?php is_selected(18, $content['alink']['alink_type']) ?>><?php echo $BL['be_article_atitle'].', '.$BL['be_admin_struct_orderdesc'] ?></option>
            <option value="19"<?php is_selected(19, $content['alink']['alink_type']) ?>><?php echo $BL['be_article_atitle'].', '.$BL['be_admin_struct_orderasc'] ?></option>
            <option value="22"<?php is_selected(22, $content['alink']['alink_type']) ?>><?php echo $BL['be_tags'].', '.$BL['be_admin_struct_orderdesc'] ?></option>
            <option value="23"<?php is_selected(23, $content['alink']['alink_type']) ?>><?php echo $BL['be_tags'].', '.$BL['be_admin_struct_orderasc'] ?></option>
            <option value="24"<?php is_selected(24, $content['alink']['alink_type']) ?>><?php echo $BL['be_cnt_sorting'].', '.$BL['be_admin_struct_orderdesc'] ?></option>
            <option value="25"<?php is_selected(25, $content['alink']['alink_type']) ?>><?php echo $BL['be_cnt_sorting'].', '.$BL['be_admin_struct_orderasc'] ?></option>
        </optgroup>
        <optgroup label="<?php echo $BL['be_random']; ?>">
            <option value="9"<?php is_selected(9, $content['alink']['alink_type']) ?>><?php echo $BL['be_random'] ?></option>
        </optgroup>
        <optgroup label="<?php echo $BL['be_random'].', '.$BL['be_sorted']; ?>">
            <option value="10"<?php is_selected(10, $content['alink']['alink_type']) ?>><?php echo $BL['be_random'].', '.$BL['be_admin_struct_orderdate'].', '.$BL['be_admin_struct_orderdesc'] ?></option>
            <option value="11"<?php is_selected(11, $content['alink']['alink_type']) ?>><?php echo $BL['be_random'].', '.$BL['be_admin_struct_orderdate'].', '.$BL['be_admin_struct_orderasc'] ?></option>
            <option value="12"<?php is_selected(12, $content['alink']['alink_type']) ?>><?php echo $BL['be_random'].', '.$BL['be_admin_struct_orderchangedate'].', '.$BL['be_admin_struct_orderdesc'] ?></option>
            <option value="13"<?php is_selected(13, $content['alink']['alink_type']) ?>><?php echo $BL['be_random'].', '.$BL['be_admin_struct_orderchangedate'].', '.$BL['be_admin_struct_orderasc'] ?></option>
            <option value="14"<?php is_selected(14, $content['alink']['alink_type']) ?>><?php echo $BL['be_random'].', '.$BL['be_article_cnt_start'].', '.$BL['be_admin_struct_orderdesc'] ?></option>
            <option value="15"<?php is_selected(15, $content['alink']['alink_type']) ?>><?php echo $BL['be_random'].', '.$BL['be_article_cnt_start'].', '.$BL['be_admin_struct_orderasc'] ?></option>
            <option value="16"<?php is_selected(16, $content['alink']['alink_type']) ?>><?php echo $BL['be_random'].', '.$BL['be_article_cnt_end'].', '.$BL['be_admin_struct_orderdesc'] ?></option>
            <option value="17"<?php is_selected(17, $content['alink']['alink_type']) ?>><?php echo $BL['be_random'].', '.$BL['be_article_cnt_end'].', '.$BL['be_admin_struct_orderasc'] ?></option>
            <option value="20"<?php is_selected(20, $content['alink']['alink_type']) ?>><?php echo $BL['be_random'].', '.$BL['be_article_atitle'].', '.$BL['be_admin_struct_orderdesc'] ?></option>
            <option value="21"<?php is_selected(21, $content['alink']['alink_type']) ?>><?php echo $BL['be_random'].', '.$BL['be_article_atitle'].', '.$BL['be_admin_struct_orderasc'] ?></option>
            <option value="26"<?php is_selected(26, $content['alink']['alink_type']) ?>><?php echo $BL['be_random'].', '.$BL['be_cnt_sorting'].', '.$BL['be_admin_struct_orderdesc'] ?></option>
            <option value="27"<?php is_selected(27, $content['alink']['alink_type']) ?>><?php echo $BL['be_random'].', '.$BL['be_cnt_sorting'].', '.$BL['be_admin_struct_orderasc'] ?></option>
        </optgroup>
    </select>
  </div>
  <div class="col mt-2 mt-sm-0">
    <div class="form-check form-check-inline" id="prio0">
			<input class="form-check-input ml-sm-3" type="checkbox" name="calink_prio" id="calink_prio" value="1"<?php is_checked(1, $content['alink']['alink_prio']) ?> />
			<label class="form-check-label" for="calink_prio"><?php echo $BL['be_use_prio'] ?></label>
    </div>
  </div>
</div>

<div class="form-group form-row" id="calink_manual_0"<?php if($content['alink']['alink_type']) echo ' style="display:none"'; ?>>
  <label for="be_article_morelink" class="col-sm-2 col-form-label text-right"><?php echo $BL['be_selection'] ?></label>
  <div class="col">
    <select name="calink[]" size="15" multiple="multiple" class="custom-select form-control form-control-sm" id="calink" ondblclick="moveSelectedOptions(teaser_items,source_items,true);">
    <?php
        //Auslesen der kompletten Public Artikel
        $sql  = "SELECT article_id, article_title, acat_name, acat_alias, article_cid, article_aktiv, article_keyword ";
        $sql .= "FROM ".DB_PREPEND."cmsgo_article ar ";
        $sql .= "LEFT JOIN ".DB_PREPEND."cmsgo_articlecat ac ON ar.article_cid = ac.acat_id ";
        $sql .= "WHERE ar.article_deleted = 0 AND ar.article_noteaser = 0 ";
        $sql .= "GROUP BY ar.article_id, ar.article_title, ac.acat_name ";
        $sql .= "ORDER BY ar.article_title;";

        $carticle_list = '';
        $carticle_link = $content['alink']['alink_id'];

        $result = _dbQuery($sql);

        if(isset($result[0]['article_id'])) {
            foreach($result as $row) {
                $k  = 0;
                $k1 = $BL['be_cnt_sitelevel'].': '.html($row['acat_name']);
                if(empty($row['article_cid'])) {
                    $row['acat_name'] = $indexpage['acat_name'];
                    $row['acat_alias'] = $indexpage['acat_alias'];
                }
                $alias_add  = ' ('.html($row['acat_name']);
                if(!empty($row['acat_alias'])) {
                    $alias_add .= '/'.html($row['acat_alias']);
                }
                $alias_add .= ')';
                foreach($content['alink']['alink_id'] as $key => $value) {

                    if($row['article_id'] == $value) {
                        $carticle_link[$key]  = '<option value="'.$row['article_id'].'" title="'.$k1.'">'.html($row['article_title']).$alias_add.'</option>'.LF;
                        unset($content['alink']['alink_id'][$key]);
                        $k = 1;
                    }
                }

                if(!$k) {
                    // filter by category
                    if($content['alink']['filter_category'] !== null && $content['alink']['filter_category'] !== intval($row['article_cid'])) {
                        continue;
                    }
                    // filter by tag
                    if(is_array($content['alink']['filter_tags']) && count($content['alink']['filter_tags'])) {
                        $content['alink']['filter_tags_active'] = false;
                        foreach($content['alink']['filter_tags'] as $_tag) {
                            if(strpos($row['article_keyword'], $_tag) !== false) {
                                $content['alink']['filter_tags_active'] = true;
                                break;
                            }
                        }
                        if($content['alink']['filter_tags_active'] === false) {
                            continue;
                        }
                    }
                    $carticle_list .= '<option value="'.$row['article_id'].'" title="'.$k1.'">'.html($row['article_title']).$alias_add.'</option>'.LF;
                }
            }
        }
        echo implode(LF, $carticle_link);
      ?>
    </select>
  </div>
  <div class="col-sm-auto">
    <a class="btn btn-secondary btn-sm mb-1" href="#" title="<?php echo $BL['be_cnt_sortup'] ?>" onclick="moveOptionUp(teaser_items);return false;"><i class="fa fa-angle-up fa-fw" aria-hidden="true"></i></a>
    <br />
    <a class="btn btn-secondary btn-sm mb-3" href="#" title="<?php echo $BL['be_cnt_sortdown'] ?>" onclick="moveOptionDown(teaser_items);return false;"><i class="fa fa-angle-down fa-fw" aria-hidden="true"></i></a></td>
    <br />
    <a class="btn btn-danger btn-sm" href="#" title="<?php echo $BL['be_cnt_removearticleto'] ?>" onclick="moveSelectedOptions(teaser_items,source_items,false);return false;"><i class="far fa-trash-alt fa-fw" aria-hidden="true"></i></a>
  </div>
</div>

<div class="form-group form-row" id="calink_manual_1"<?php if($content['alink']['alink_type']) echo ' style="display:none"'; ?>>
  <label for="be_cnt_articles" class="col-sm-2 col-form-label text-right"><?php echo $BL['be_cnt_articles'] ?></label>
  <div class="col">
    <select name="calinklist" size="15" multiple="multiple" class="custom-select form-control form-control-sm" id="calinklist" ondblclick="moveSelectedOptions(source_items,teaser_items,false);">
          <?php echo $carticle_list; ?>
    </select>
  </div>
  <div class="col-sm-auto">
    <a class="btn btn-secondary btn-sm" href="#" title="<?php echo $BL['be_cnt_movearticleto'] ?>" onclick="moveSelectedOptions(source_items,teaser_items,false);return false"><i class="fa fa-angle-double-up fa-fw" aria-hidden="true"></i></a>
  </div>
</div>

<div class="form-group align-items-center form-row" id="calink_manual_2"<?php if($content['alink']['alink_type']) echo ' style="display:none"'; ?>>
  <label for="be_filter" class="col-sm-2 col-form-label text-right"><?php echo $BL['be_filter'] ?></label>
  <div class="col-sm-auto">
    <select name="teaser_filter_category" class="custom-select form-control form-control-sm">
      <option value=""><?php echo $BL['be_filter_not_selected'] ?></option>
      <option value="0"<?php
        if($content['alink']['filter_category'] !== null) {
          is_selected(0, $content['alink']['filter_category']);
          $content['alink']['filter_category'] = array($content['alink']['filter_category']);
            } else {
              $content['alink']['filter_category'] = array();
            }
          ?>><?php echo html($indexpage['acat_name']) ?></option>
       <?php struct_select_list(0, 0, $content['alink']['filter_category'], true); ?>
     </select>
  </div>
  <div class="col">
    <div class="form-inline">
			<input class="form-check-input ml-sm-3" type="checkbox" name="teaser_filter_category_by_tags" id="filter_category_by_tags" value="1"<?php if($content['alink']['filter_tags'] !== null) echo ' checked="checked"'; ?> />
			<label class="form-check-label" for="filter_category_by_tags"><?php echo $BL['be_filter_with_tags'] ?><button class="btn btn-blue btn-sm ml-sm-1" name="Submit"><i class="fa fa-search"></i></button></label>
    </div>
  </div>
</div>

<div class="form-group form-row" id="calink_auto_0"<?php if(!$content['alink']['alink_type']) echo ' style="display:none"'; ?>>
  <label class="col-sm-2 col-form-label text-right"></label>
  <div class="col">
    <div class="form-inline">
      <?php echo $BL['be_cnt_rssfeed_max'] ?>
      <input name="calink_max" type="text" id="calink_max" class="form-control form-control-sm mx-sm-2" value="<?php echo empty($content['alink']['alink_max']) ? '' : $content['alink']['alink_max']; ?>" size="5" maxlength="5" />
      <?php echo $BL['be_cnt_articles'] ?>
    </div>
  </div>
</div>

<div class="form-group form-row" id="calink_auto_1"<?php if(!$content['alink']['alink_type']) echo ' style="display:none"'; ?>>
  <label class="col-sm-2 col-form-label text-right"><?php echo $BL['be_cnt_sitelevel'] ?></label>
  <div class="col">
    <select name="calink_level[]" size="15" multiple="multiple" class="custom-select optionhover form-control form-control-sm" id="calink_level">
      <?php
        echo '<option value="0"';
        if(in_array(0, $content['alink']['alink_level'])) {
            echo ' selected="selected"';
        }
        echo '>'.html($indexpage['acat_name']).'</option>'.LF;
        struct_select_list(0, 0, $content['alink']['alink_level'], true);
      ?>
    </select>
  </div>
</div>

<hr />

<div class="form-group form-row">
  <label for="be_tags" class="col-sm-2 col-form-label text-right"><?php echo $BL['be_tags'] ?> <i class="fas fa-info-circle text-blue" data-toggle="tooltip" title="<?php echo $BL['be_input_text_tab'] ?>"></i></label>
  <div class="col">
    <input type="text" id="calink_category_autosuggest" class="form-control form-control-sm" />
    <input type="hidden" name="calink_category" id="calink_category" value="<?php echo html(implode(', ', $content['alink']['alink_category'])) ?>" />
  </div>
  <div class="col-sm-auto">
    <select name="calink_andor" id="calink_andor" class="custom-select form-control form-control-sm">
      <option value="OR"<?php is_selected('OR', $content['alink']['alink_andor']) ?>><?php echo $BL['be_fsearch_or'] ?></option>
      <option value="AND"<?php is_selected('AND', $content['alink']['alink_andor']) ?>><?php echo $BL['be_fsearch_and'] ?></option>
      <option value="NOT"<?php is_selected('NOT', $content['alink']['alink_andor']) ?>><?php echo $BL['be_fsearch_not'] ?></option>
      <option value="NOR"<?php is_selected('NOR', $content['alink']['alink_andor']) ?>><?php echo $BL['be_fsearch_nor'] ?></option>
    </select>
  </div>
</div>

<script type="text/javascript">

$(function(){

    $("#calink_category_autosuggest").autoSuggest('<?php echo CMSGO_URL ?>include/inc_act/ajax_connector.php', {
        selectedItemProp: "calink_category",
        selectedValuesProp: 'calink_category',
        searchObjProps: "calink_category",
        queryParam: 'value',
        extraParams: '&method=json&action=category',
        startText: '',
        preFill: $("#calink_category").val(),
        neverSubmit: true,
        asHtmlID: 'keyword-autosuggest'
    });

    $('#articlecontent').submit(function(event){
        $("#calink_category").val($('#as-values-keyword-autosuggest').val());
    });
});

var teaser_items = document.getElementById('calink');
var source_items = document.getElementById('calinklist');

</script>