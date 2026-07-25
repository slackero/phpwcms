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

//search form

// necessary JavaScript libraries

// todos: replace Ziel suche mit Artikelbrowser
//Sprache Autocmpleter funktioniert noch nicht
//Regex fehler

initJsAutocompleter();


if(empty($content['search']["text_html"])) {
  $content['search']["text_html"] = 0;
}

$content['search']["search_news"] = empty($content['search']["search_news"]) ? 0 : 1;

if(!isset($content['search']["news_lang"])) {
  $content['search']["news_lang"] = array();
}
if(!isset($content['search']["news_category"])) {
  $content['search']["news_category"] = array();
}
if(!isset($content['search']["news_andor"])) {
  $content['search']["news_andor"] = 'OR';
}
if(empty($content['search']["news_url"])) {
  $content['search']["news_url"] = '';
}
if(empty($content["search"]["hide_summary"])) {
  $content["search"]["hide_summary"] = 0;
}
if(empty($content["search"]["highlight_result"])) {
  $content["search"]["highlight_result"] = 0;
}
if(empty($content["search"]["newwin"])) {
  $content["search"]["newwin"] = 0;
}
if(empty($content["search"]["no_filenames"])) {
  $content["search"]["no_filenames"] = 0;
}
if(empty($content["search"]["no_username"])) {
  $content["search"]["no_username"] = 0;
}
if(empty($content["search"]["no_caption"])) {
  $content["search"]["no_caption"] = 0;
}
if(empty($content["search"]["no_keyword"])) {
  $content["search"]["no_keyword"] = 0;
}
if(empty($content['search']['type'])) {
  $content['search']['type'] = 'OR';
}
?>

<div class="form-group align-items-center form-row">
  <label for="template" class="col-sm-2 col-form-label text-right"><?php echo $BL['be_admin_struct_template']; ?></label>
  <div class="col-sm-4">
    <select name="template" id="template" class="custom-select form-control form-control-sm">
		<?php
			echo '<option value="">'.$BL['be_admin_tmpl_default'].'</option>'.LF;
		// templates for search listing
		$tmpllist = get_tmpl_files(PHPWCMS_TEMPLATE.'inc_cntpart/search');
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

<div class="form-group form-row align-items-center">
	<label class="col-sm-2 col-form-label text-right"><?php echo $BL['be_cnt_results'] ?></label>
	<div class="col-sm-2">
		<input name="csearch_result_per_page" type="text" id="csearch_result_per_page" class="form-control form-control-sm" value="<?php echo isset($content["search"]["result_per_page"]) ? $content["search"]["result_per_page"] : '' ?>" size="3" maxlength="5" />
	</div>
	<div class="col-sm">
		<?php echo $BL['be_cnt_results_per_page'] ?>
	</div>
	<div class="col-sm mt-3 mt-sm-0">
		<div class="form-check form-check-inline">
			<input class="form-check-input" name="csearch_newwin" type="checkbox" id="csearch_newwin" value="1" <?php is_checked(1, $content["search"]["newwin"]) ?> />
			<label class="form-check-label" for="csearch_newwin"><?php echo $BL['be_cnt_opennewwin'] ?></label>
		</div>
	</div>
</div>

<div class="form-group form-row align-items-center">
	<label class="col-sm-2 col-form-label"></label>
	<div class="col-sm-2">
		<input name="csearch_wordlimit" type="text" id="csearch_wordlimit" class="form-control form-control-sm" value="<?php echo isset($content["search"]["wordlimit"]) ? $content["search"]["wordlimit"] : '' ?>" maxlength="5" />
	</div>
	<div class="col-sm">
		<?php echo $BL['be_cnt_results_wordlimit'] ?>
	</div>
	<div class="col-sm mt-3 mt-sm-0">
		<div class="form-check form-check-inline">
			<input class="form-check-input" name="csearch_highlight" type="checkbox" id="csearch_highlight" value="1" <?php is_checked(1, $content["search"]["highlight_result"]) ?> />
			<label class="form-check-label" for="csearch_highlight"><?php echo $BL['be_cnt_search_highlight'] ?></label>
		</div>
	</div>
</div>

<div class="form-group form-row align-items-center">
	<label class="col-sm-2 col-form-label"></label>
	<div class="col-sm-2">
		<input name="csearch_minchar" type="text" id="csearch_minchar" class="form-control form-control-sm" value="<?php echo  isset($content["search"]["minchar"]) ? $content["search"]["minchar"] : '3' ?>" maxlength="5" />
	</div>
	<div class="col-sm">
		<?php echo $BL['be_cnt_results_minchar'] ?>
	</div>
	<div class="col-sm mt-3 mt-sm-0">
		<div class="form-check form-check-inline">
			<input class="form-check-input" name="csearch_hidesummary" type="checkbox" id="csearch_hidesummary" value="1" <?php is_checked(1, $content["search"]["hide_summary"]) ?> />
			<label class="form-check-label" for="csearch_hidesummary"><?php echo $BL['be_cnt_search_hidesummary'] ?></label>
		</div>
	</div>
</div>

<div class="form-group form-row">
  <label for="csearch_start_at" class="col-sm-2 col-form-label text-right"><?php echo $BL['be_cnt_search_startlevel'] ?></label>
  <div class="col">
    <select name="csearch_start_at[]" size="10" multiple="multiple" class="custom-select form-control form-control-sm" id="csearch_start_at">
		<?php
			if(!isset($content["search"]["start_at"]) || !is_array($content["search"]["start_at"])) {
				$content["search"]["start_at"] = array();
			}

			echo '<option value="0"';
			if(in_array(0, $content["search"]["start_at"])) {
				echo ' selected="selected"';
			}
			echo '>'.$BL['be_admin_struct_index'].'</option>'.LF;
			struct_select_list(0, 0, $content["search"]["start_at"]);
		?>
    </select>
  </div>
</div>

<div class="form-group form-row align-items-center">
  <label for="csearch_type" class="col-sm-2 col-form-label text-right"><?php echo $BL['be_cnt_search_default_type'] ?></label>
  <div class="col-sm-4">
    <select name="csearch_type" id="csearch_type" class="custom-select form-control form-control-sm">
      <option value="OR"<?php is_selected('OR', $content['search']['type']) ?>><?php echo $BL['be_fsearch_or'] ?></option>
      <option value="AND"<?php is_selected('AND', $content['search']['type']) ?>><?php echo $BL['be_fsearch_and'] ?></option>
    </select>
  </div>
</div>

<div class="form-group align-items-center form-row">
  <label for="csearch_nofilenames" class="col-sm-2 col-form-label text-right"><?php echo $BL['be_cnt_search_searchnot'] ?></label>
  <div class="col">
  <div class="form-check form-check-inline">
		<input class="form-check-input" name="csearch_nofilenames" type="checkbox" id="csearch_nofilenames" value="1" <?php is_checked(1, $content["search"]["no_filenames"]) ?> />
		<label class="form-check-label" for="csearch_nofilenames"><?php echo $BL['be_fprivedit_filename'] ?></label>
  </div>
  <div class="form-check form-check-inline">
		<input class="form-check-input" name="csearch_nousername" type="checkbox" id="csearch_nousername" value="1" <?php is_checked(1, $content["search"]["no_username"]) ?> />
		<label class="form-check-label" for="csearch_nousername"><?php echo $BL['be_article_username'] ?></label>
  </div>
  <div class="form-check form-check-inline">
		<input class="form-check-input" name="csearch_nocaption" type="checkbox" id="csearch_nocaption" value="1" <?php is_checked(1, $content["search"]["no_caption"]) ?> />
		<label class="form-check-label" for="csearch_nocaption"><?php echo $BL['be_cnt_caption'] ?></label>
  </div>
  <div class="form-check form-check-inline">
		<input class="form-check-input" name="csearch_nokeyword" type="checkbox" id="csearch_nokeyword" value="1" <?php is_checked(1, $content["search"]["no_keyword"]) ?> />
		<label class="form-check-label" for="csearch_nokeyword"><?php echo $BL['be_article_akeywords'] ?></label>
  </div>
  </div>
</div>

<div class="form-group form-row">
  <label for="csearch_news" class="col-sm-2 col-form-label text-right pt-0"><?php echo $BL['be_module_search'] ?></label>
  <div class="col-sm-auto mr-sm-5">
  	<div class="form-check form-check-inline">
			<input class="form-check-input" name="csearch_news" type="checkbox" id="csearch_news" value="1"<?php is_checked(1, $content['search']["search_news"]) ?> />
			<label class="form-check-label" for="csearch_news"><?php echo $BL['be_news'] ?></label>
		</div>
  </div>
	<div class="col">
		<div class="form-group">
		<span class="col-form-label pt-2 pt-sm-0"><?php echo $BL['be_profile_label_lang'] ?> <i class="fas fa-info-circle text-blue" data-toggle="tooltip" data-container="body" title="<?php echo $BL['be_input_text_tab'] ?>"></i></span>
		<input type="text" id="news_lang_autosuggest" class="form-control form-control-sm" aria-label="<?php echo html_specialchars($BL['be_profile_label_lang']) ?>" /><input type="hidden" name="csearch_news_lang" id="news_lang" value="<?php echo html(implode(', ', $content['search']["news_lang"])) ?>" class="form-control" />
	</div>

    <div class="form-group">
      <span class="col-form-label pt-2 pt-sm-0"><?php echo $BL['be_tags'] ?> <i class="fas fa-info-circle text-blue" data-toggle="tooltip" data-container="body" title="<?php echo $BL['be_input_text_tab'] ?>"></i></span>
        <div class="form-row">
          <div class="col mb-3 mb-sm-0">
            <input type="text" id="news_category_autosuggest" class="form-control form-control-sm" aria-label="<?php echo html_specialchars($BL['be_tags']) ?>" /><input type="hidden" name="csearch_news_category" id="news_category" value="<?php echo html(implode(', ', $content['search']["news_category"])) ?>" class="form-control" />
          </div>
          <div class="col-sm-auto">
            <select name="csearch_news_andor" id="news_andor" class="custom-select form-control form-control-sm">
              <option value="OR"<?php is_selected('OR', $content['search']['news_andor']) ?>><?php echo $BL['be_fsearch_or'] ?></option>
              <option value="AND"<?php is_selected('AND', $content['search']['news_andor']) ?>><?php echo $BL['be_fsearch_and'] ?></option>
              <option value="NOT"<?php is_selected('NOT', $content['search']['news_andor']) ?>><?php echo $BL['be_fsearch_not'] ?></option>
            </select>
          </div>
        </div>
    </div>

    <div class="form-group">
        <label class="col-form-label pt-2 pt-sm-0"><?php echo $BL['be_cnt_target'].' ('.$BL['be_alias'] ?>/aid=1/id=3)</label>
        <div class="input-group">
          <span class="input-group-prepend">
            <button class="modalButton btn btn-sm btn-secondary sitemap-open" type="button" data-toggle="modal" data-target="#browserModal" data-src="articlebrowser.php?opt=6&field=csearch_news_url" ></button>
          </span>
          <input type="text" name="csearch_news_url" id="news_url" value="<?php echo html($content['search']["news_url"]) ?>" class="form-control form-control-sm" data-toggle="tooltip" title="<?php echo $BL['be_read_more_link'] ?>" />
        </div>
    </div>

  <script type="text/javascript">
  $(function(){

    $("#news_category_autosuggest").autoSuggest('<?php echo PHPWCMS_URL ?>include/inc_act/ajax_connector.php', {
      selectedItemProp: "cat_name",
      selectedValuesProp: 'cat_name',
      searchObjProps: "cat_name",
      queryParam: 'value',
      extraParams: '&method=json&action=category&<?php echo get_token_get_string(); ?>',
      startText: '',
      preFill: $("#news_category").val(),
      neverSubmit: true,
      asHtmlID: 'keyword-autosuggest1'
    });

    $("#news_lang_autosuggest").autoSuggest('<?php echo PHPWCMS_URL ?>include/inc_act/ajax_connector.php', {
      selectedItemProp: "allowed_lang",
      selectedValuesProp: 'allowed_lang',
      searchObjProps: "allowed_lang",
      queryParam: 'value',
      extraParams: '&method=json&action=lang&<?php echo get_token_get_string(); ?>',
      startText: '',
      preFill: $("#news_lang").val(),
      neverSubmit: true,
      asHtmlID: 'keyword-autosuggest2'
    });

    $('#articlecontent').submit(function(event){
      $("#news_category").val($('#as-values-keyword-autosuggest1').val());
      $("#news_lang").val($('#as-values-keyword-autosuggest2').val());
    });

    $("#keyword-autosuggest2").keyup(function(event) {
      $(this).val( $(this).val().replace(/[^a-z]/g,'') );
    });

  });
  </script>
  </div>
</div>

<?php

$content['search']['module_search'] = array();

// check modules for frontend search
foreach($phpwcms['modules'] as $value) {

  // check if module is fe searchable
  if($value['search'] === true && is_file($value['path'].'frontend.search.php')) {

    $value['tr']  = '<tr>';
    $value['tr'] .= '<td><input name="csearch_module['.$value['name'].']" type="checkbox" ';
    $value['tr'] .= 'id="csearch_module_'.$value['name'].'" value="1"';
    if( !empty( $content['search']['module'][ $value['name'] ] ) ) {
      $value['tr'] .= ' checked="checked"';
    }
    $value['tr'] .= ' /></td>';
        $value['tr'] .= '<td><label for="csearch_module_'.$value['name'].'">';
    $value['tr'] .= $BL['be_ctype_module'].': '.$BL['modules'][ $value['name'] ]['backend_menu'] . '</label></td>';
    $value['tr'] .= '</tr>';

    $content['search']['module_search'][] = $value['tr'];
  }
}

if(count($content['search']['module_search'])) {

  echo '';
  echo '<div class="form-group align-items-center form-row">';
  echo '<label class="col-sm-2 col-form-label text-right pt-0"></label>';
  echo '<div class="col">';
  echo '<table class="table-borderless">';
  echo implode(LF, $content['search']['module_search']) ;
  echo '</table></div></div>';
}
?>
<hr />
<div class="bg-grey my-3 p-2"><?php echo $BL['be_cnt_searchlabeltext'] ?></div>

<div class="form-group align-items-center form-row">
  <label for="csearch_label_input" class="col-sm-2 col-form-label text-right"><?php echo $BL['be_cnt_input'] ?></label>
    <div class="col-sm-4">
      <input name="csearch_label_input" type="text" class="form-control form-control-sm" id="csearch_label_input" value="<?php echo  isset($content["search"]["label_input"]) ? $content["search"]["label_input"] : '' ?>" maxlength="250" />
    </div>
  <label for="csearch_style_input" class="col-sm-2 col-form-label text-right font-weight-normal"><?php echo $BL['be_cnt_css_class'] ?></label>
    <div class="col-sm-4">
      <input name="csearch_style_input" type="text" id="csearch_style_input" class="form-control form-control-sm" value="<?php echo  isset($content["search"]["style_input"]) ? $content["search"]["style_input"] : '' ?>" />
    </div>
</div>

<div class="form-group align-items-center form-row">
  <label for="csearch_label_button" class="col-sm-2 col-form-label text-right"><?php echo $BL['be_cnt_buttontext'] ?></label>
    <div class="col-sm-4">
      <input name="csearch_label_button" type="text" id="csearch_label_button" class="form-control form-control-sm" value="<?php echo  isset($content["search"]["label_button"]) ? $content["search"]["label_button"] : '' ?>" maxlength="75" />
    </div>
  <label for="csearch_style_button" class="col-sm-2 col-form-label text-right font-weight-normal"><?php echo $BL['be_cnt_css_class'] ?></label>
    <div class="col-sm-4">
    	<input name="csearch_style_button" type="text" id="csearch_style_button" class="form-control form-control-sm" value="<?php echo  isset($content["search"]["style_button"]) ? $content["search"]["style_button"] : '' ?>" />
    </div>
</div>

<div class="form-group align-items-center form-row">
  <label for="csearch_label_result" class="col-sm-2 col-form-label text-right"><?php echo $BL['be_cnt_result'] ?></label>
    <div class="col-sm-4">
      <input name="csearch_label_result" type="text" id="csearch_label_result" class="form-control form-control-sm" value="<?php echo  isset($content["search"]["label_result"]) ? html($content["search"]["label_result"]) : '' ?>" maxlength="250" />
    </div>
  <label for="csearch_style_result" class="col-sm-2 col-form-label text-right font-weight-normal"><?php echo $BL['be_cnt_css_class'] ?></label>
    <div class="col-sm-4">
      <input name="csearch_style_result" type="text" id="csearch_style_result" class="form-control form-control-sm" value="<?php echo  isset($content["search"]["style_result"]) ? $content["search"]["style_result"] : '' ?>" />
    </div>
</div>

<div class="form-group form-row">
  <label for="csearch_show_always" class="col-sm-2 col-form-label text-right pt-0"><?php echo $BL['be_cnt_page_of_pages'] ?></label>
  <div class="col">
		<?php
		if(!isset($content["search"]["show_always"])) $content["search"]["show_always"] = 1;
		if(!isset($content["search"]["show_top"])) $content["search"]["show_top"] = 1;
		if(!isset($content["search"]["show_bottom"])) $content["search"]["show_bottom"] = 1;
		if(!isset($content["search"]["show_next"])) $content["search"]["show_next"] = 1;
		if(!isset($content["search"]["show_prev"])) $content["search"]["show_prev"] = 1;
		?>
		<div class="form-group mb-0">
			<div class="form-check form-check-inline">
				<input class="form-check-input" name="csearch_show_always" type="checkbox" id="csearch_show_always" value="1" <?php is_checked(1, $content["search"]["show_always"]) ?> />
				<label class="form-check-label" for="csearch_show_always"><?php echo $BL['be_cnt_search_show_forall'] ?></label>
			</div>
			<div class="form-check form-check-inline">
				<input class="form-check-input" name="csearch_show_top" type="checkbox" id="csearch_show_top" value="1" <?php is_checked(1, $content["search"]["show_top"]) ?> />
				<label class="form-check-label" for="csearch_show_top"><?php echo $BL['be_cnt_search_show_top'] ?></label>
			</div>
			<div class="form-check form-check-inline">
				<input class="form-check-input" name="csearch_show_bottom" type="checkbox" id="csearch_show_bottom" value="1" <?php is_checked(1, $content["search"]["show_bottom"]) ?> />
				<label class="form-check-label" for="csearch_show_bottom"><?php echo $BL['be_cnt_search_show_bottom'] ?></label>
			</div>
		</div>
    <div class="form-group mb-0">
			<div class="form-check form-check-inline">
				<input class="form-check-input" name="csearch_show_prev" type="checkbox" id="csearch_show_prev" value="1" <?php is_checked(1, $content["search"]["show_prev"]) ?> />
				<label class="form-check-label" for="csearch_show_prev"><?php echo $BL['be_cnt_search_show_prev'] ?></label>
			</div>
			<div class="form-check form-check-inline">
				<input class="form-check-input" name="csearch_show_next" type="checkbox" id="csearch_show_next" value="1" <?php is_checked(1, $content["search"]["show_next"]) ?> />
				<label class="form-check-label" for="csearch_show_next"><?php echo $BL['be_cnt_search_show_next'] ?></label>
			</div>
    </div>
  </div>
</div>

<div class="form-group align-items-center form-row">
  <label for="csearch_label_pages" class="col-sm-2 col-form-label text-right pt-0"></label>
  <div class="col"><textarea name="csearch_label_pages" rows="4" class="form-control form-control-sm" id="csearch_label_pages"><?php echo  isset($content["search"]["label_pages"]) ? html($content["search"]["label_pages"]) : '' ?></textarea>
  <div class="mt-1" ><?php echo $BL['be_cnt_page_of_pages_descr'] ?></div>
  </div>
</div>

<div class="form-group form-row">
  <label for="csearch_align0" class="col-sm-2 col-form-label text-right pt-0"><?php echo $BL['be_cnt_align'] ?></label>
  <div class="col">
    <?php
    if(!isset($content["search"]["align"])) {
      $content["search"]["align"] = 0;
    }
    ?>
		<div class="form-check form-check-inline">
			<input class="form-check-input" name="csearch_align" type="radio" id="csearch_align0" value="0" <?php is_checked(0, $content["search"]["align"]) ?> />
			<label class="form-check-label" for="csearch_align0"><?php echo $BL['be_cnt_mediapos0'] ?></label>
		</div>
		<div class="form-check form-check-inline">
			<input class="form-check-input" name="csearch_align" type="radio" id="csearch_align1" value="1" <?php is_checked(1, $content["search"]["align"]) ?> />
			<label class="form-check-label" for="csearch_align1"><?php echo $BL['be_cnt_right'] ?></label>
		</div>
		<div class="form-check form-check-inline">
			<input class="form-check-input" name="csearch_align" type="radio" id="csearch_align2" value="2" <?php is_checked(2, $content["search"]["align"]) ?> />
			<label class="form-check-label" for="csearch_align2"><?php echo $BL['be_cnt_center'] ?></label>
		</div>
	</div>
</div>

<hr />
<div class="bg-grey my-3 p-2"><?php echo $BL['be_cnt_searchformtext'] ?></div>

<div class="form-group form-row">
  <label class="col-sm-2 col-form-label"></label>
  <div class="col">
		<div class="form-check form-check-inline">
			<input class="form-check-input" name="csearch_text_html" type="radio" id="csearch_text_html0" value="0"<?php echo is_checked('0', $content['search']["text_html"], 0, 0) ?> title="redirect on success" />
			<label class="form-check-label" for="csearch_text_html0">Text</label>
		</div>
		<div class="form-check form-check-inline">
			<input class="form-check-input" name="csearch_text_html" type="radio" id="csearch_text_html1" value="1"<?php echo is_checked('1', $content['search']["text_html"], 0, 0) ?> title="redirect on success" />
			<label class="form-check-label" for="csearch_text_html1">HTML</label>
		</div>
  </div>
</div>

<div class="form-group form-row">
  <label for="csearch_text_intro" class="col-sm-2 col-form-label text-right pt-0"><?php echo $BL['be_cnt_intro'] ?></label>
  <div class="col">
    <textarea name="csearch_text_intro" rows="6" class="form-control form-control-sm" id="csearch_text_intro"><?php echo isset($content["search"]["text_intro"]) ? $content["search"]["text_intro"] : '' ?></textarea>
  </div>
</div>

<div class="form-group form-row">
  <label for="csearch_text_result" class="col-sm-2 col-form-label text-right pt-0"><?php echo $BL['be_cnt_result'] ?></label>
  <div class="col">
    <textarea name="csearch_text_result" rows="6" class="form-control form-control-sm" id="csearch_text_result"><?php echo isset($content["search"]["text_result"]) ? $content["search"]["text_result"] : '' ?></textarea>
  </div>
</div>

<div class="form-group form-row">
  <label for="csearch_text_noresult" class="col-sm-2 col-form-label text-right pt-0"><?php echo $BL['be_cnt_noresult'] ?></label>
  <div class="col">
    <textarea name="csearch_text_noresult" rows="6" class="form-control form-control-sm" id="csearch_text_noresult"><?php echo isset($content["search"]["text_noresult"]) ? $content["search"]["text_noresult"] : '' ?></textarea>
  </div>
</div>
