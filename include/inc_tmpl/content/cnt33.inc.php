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

// News
$content['news_default'] = array(
  'news_lang'       => array(),
  'news_category'     => array(),
  'news_sort'       => 5,
  'news_paginate'     => 0,
  'news_paginate_count' => 10,
  'news_limit'      => '',
  'news_archive'      => 1,
  'news_andor'      => 'OR',
  'news_paginate_basis' => 0,
  'news_archive_link'   => '',
  'news_prio'       => 0,
  'news_skip'       => '',
  'news_detail_link'    => ''
);

// set default values or merge with defaults
$content['news'] = $content['id'] > 0 && is_array($content['news']) ? array_merge($content['news_default'], $content['news']) : $content['news_default'];

// necessary JavaScript libraries
initJsAutocompleter();
?>

<div class="form-group align-items-center form-row">
  <label for="template" class="col-sm-2 col-form-label text-right"><?php echo $BL['be_admin_struct_template']; ?></label>
  <div class="col-sm-4">
    <select name="template" id="template" class="custom-select form-control form-control-sm">
    <?php
      echo '<option value="">'.$BL['be_admin_tmpl_default'].'</option>'.LF;
      $tmpllist = get_tmpl_files(PHPWCMS_TEMPLATE.'inc_cntpart/news');
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

<div class="form-group align-items-center form-row">
  <label for="news_sort" class="col-sm-2 col-form-label text-right"><?php echo $BL['be_cnt_sorting'] ?></label>
  <div class="col-sm-4">
      <select name="news_sort" id="calink_type" class="custom-select form-control form-control-sm">
          <option value="9"<?php is_selected(9, $content['news']['news_sort']) ?>><?php echo $BL['be_sort_date'].', '.$BL['be_admin_struct_orderdesc'] ?></option>
          <option value="10"<?php is_selected(10, $content['news']['news_sort']) ?>><?php echo $BL['be_sort_date'].', '.$BL['be_admin_struct_orderasc'] ?></option>
          <option value="1"<?php is_selected(1, $content['news']['news_sort']) ?>><?php echo $BL['be_admin_struct_orderdate'].', '.$BL['be_admin_struct_orderdesc'] ?></option>
          <option value="2"<?php is_selected(2, $content['news']['news_sort']) ?>><?php echo $BL['be_admin_struct_orderdate'].', '.$BL['be_admin_struct_orderasc'] ?></option>
          <option value="3"<?php is_selected(3, $content['news']['news_sort']) ?>><?php echo $BL['be_admin_struct_orderchangedate'].', '.$BL['be_admin_struct_orderdesc'] ?></option>
          <option value="4"<?php is_selected(4, $content['news']['news_sort']) ?>><?php echo $BL['be_admin_struct_orderchangedate'].', '.$BL['be_admin_struct_orderasc'] ?></option>
          <option value="5"<?php is_selected(5, $content['news']['news_sort']) ?>><?php echo $BL['be_article_cnt_start'].', '.$BL['be_admin_struct_orderdesc'] ?></option>
          <option value="6"<?php is_selected(6, $content['news']['news_sort']) ?>><?php echo $BL['be_article_cnt_start'].', '.$BL['be_admin_struct_orderasc'] ?></option>
          <option value="7"<?php is_selected(7, $content['news']['news_sort']) ?>><?php echo $BL['be_article_cnt_end'].', '.$BL['be_admin_struct_orderdesc'] ?></option>
          <option value="8"<?php is_selected(8, $content['news']['news_sort']) ?>><?php echo $BL['be_article_cnt_end'].', '.$BL['be_admin_struct_orderasc'] ?></option>
          <option value="17"<?php is_selected(17, $content['news']['news_sort']) ?>><?php echo $BL['be_article_cnt_ctitle'].', '.$BL['be_admin_struct_orderdesc'] ?></option>
          <option value="18"<?php is_selected(18, $content['news']['news_sort']) ?>><?php echo $BL['be_article_cnt_ctitle'].', '.$BL['be_admin_struct_orderasc'] ?></option>
          <option value="11"<?php is_selected(11, $content['news']['news_sort']) ?>><?php echo $BL['be_article_username'].'/'.$BL['be_sort_date'].', '.$BL['be_admin_struct_orderdesc'] ?></option>
          <option value="12"<?php is_selected(12, $content['news']['news_sort']) ?>><?php echo $BL['be_article_username'].'/'.$BL['be_sort_date'].', '.$BL['be_admin_struct_orderasc'] ?></option>
          <option value="13"<?php is_selected(13, $content['news']['news_sort']) ?>><?php echo $BL['be_title'].', '.$BL['be_admin_struct_orderdesc'] ?></option>
          <option value="14"<?php is_selected(14, $content['news']['news_sort']) ?>><?php echo $BL['be_title'].', '.$BL['be_admin_struct_orderasc'] ?></option>
          <option value="15"<?php is_selected(15, $content['news']['news_sort']) ?>><?php echo $BL['be_place'].'/'.$BL['be_sort_date'].', '.$BL['be_admin_struct_orderdesc'] ?></option>
          <option value="16"<?php is_selected(16, $content['news']['news_sort']) ?>><?php echo $BL['be_place'].'/'.$BL['be_sort_date'].', '.$BL['be_admin_struct_orderasc'] ?></option>
      </select>
  </div>
  <div class="col mt-2 mt-sm-0">
    <div class="form-check">
      <label for="news_prio" class="form-check-label">
        <input class="form-check-input" type="checkbox" name="news_prio" id="news_prio" value="1"<?php is_checked(1, $content['news']['news_prio']) ?> />
        <?php echo $BL['be_use_prio'] ?>
      </label>
    </div>
  </div>
</div>

<div class="form-group align-items-center form-row">
  <span class="col-sm-2 col-form-label text-right"><?php echo $BL['be_tags'] ?></span>
  <div class="col-sm-4">
   <input type="text" class="form-control form-control-sm" id="news_keyword_autosuggest" aria-label="<?php echo html_specialchars($BL['be_tags']) ?>" /><input type="hidden" name="news_category" id="news_category" value="<?php echo html(implode(', ', $content['news']['news_category'])) ?>" /></td>
  </div>
  <div class="col-sm-auto mt-2 mt-sm-0">
    <select name="news_andor" id="news_andor" class="custom-select form-control form-control-sm">
      <option value="OR"<?php is_selected('OR', $content['news']['news_andor']) ?>><?php echo $BL['be_fsearch_or'] ?></option>
      <option value="AND"<?php is_selected('AND', $content['news']['news_andor']) ?>><?php echo $BL['be_fsearch_and'] ?></option>
      <option value="NOT"<?php is_selected('NOT', $content['news']['news_andor']) ?>><?php echo $BL['be_fsearch_not'] ?></option>
    </select>
  </div>
</div>

<?php if(count($phpwcms['allowed_lang']) > 1):  ?>
<div class="form-group align-items-center form-row">
  <label for="news_lang" class="col-sm-2 col-form-label text-right"><?php echo $BL['be_profile_label_lang'] ?></label>
  <div class="col">
    <div class="form-check form-check-inline">
        <input type="checkbox" name="news_lang[]" class="form-check-input lang-default" id="langAll" value=""<?php
          if(empty($content['news']['news_lang']) || (isset($content['news']['news_lang'][0]) && $content['news']['news_lang'][0] == '')) {
            echo ' checked="checked"';
          }
        ?> />
        <label class="form-check-label mr-2" title="<?php echo $BL['be_admin_tmpl_default'] ?>"><?php echo '<span class="flag-icon flag-icon-eu mt-1" data-toggle="tooltip" title="'. $BL['be_admin_tmpl_default'].'"></span> '; ?>&nbsp;</label>
				<?php foreach($phpwcms['allowed_lang'] as $key => $lang):
					$lang = strtolower($lang);
				?>
        <input class="form-check-input" type="checkbox" name="news_lang[]" class="allowedLang" value="<?php echo $lang ?>"<?php if(in_array($lang, $content['news']['news_lang'])): ?> checked="checked"<?php endif; ?> class="lang-opt" />
        <label class="form-check-label mr-2" title="<?php echo get_language_name($lang) ?>"><?php echo '<span class="flag-icon flag-icon-'.$lang.' mt-1" data-toggle="tooltip" title="'. get_language_name($lang).'"></span>'; ?>&nbsp;</label>
      <?php endforeach; ?>
    </div>
  </div>
</div>
<?php endif; ?>

<div class="form-group align-items-center form-row">
  <label for="news_archive" class="col-sm-2 col-form-label text-right"><?php echo $BL['be_show_content'] ?></label>
  <div class="col-sm-4">
    <select name="news_archive" id="news_archive" class="custom-select form-control form-control-sm">
      <option value="0"<?php is_selected(0, $content['news']['news_archive']) ?>><?php echo $BL['be_archived_items'].': '.$BL['be_include'] ?></option>
      <option value="1"<?php is_selected(1, $content['news']['news_archive']) ?>><?php echo $BL['be_archived_items'].': '.$BL['be_exclude'] ?></option>
      <option value="2"<?php is_selected(2, $content['news']['news_archive']) ?>><?php echo $BL['be_archived_items'].': '.$BL['be_solely'] ?></option>
      <option value="3"<?php is_selected(3, $content['news']['news_archive']) ?>><?php echo $BL['be_cnt_guestbook_listing_all'].' &gt; ' . $BL['be_article_cnt_start'] ?></option>
    </select>
  </div>
</div>

<div class="form-group align-items-center form-row">
  <label for="news_limit" class="col-sm-2 col-form-label text-right"><?php echo $BL['be_cnt_rssfeed_item'] ?></label>
		<div class="col-sm-auto my-2 my-sm-0 mr-sm-3">
			<div class="input-group input-group-sm">
				<input type="text" name="news_limit" id="news_limit" size="5" maxlength="5" value="<?php echo html($content['news']['news_limit']) ?>" class="form-control form-control-sm" />
				<div class="input-group-append">
					<span class="input-group-text"><?php echo $BL['be_cnt_rssfeed_max'] ?></span>
				</div>
			</div>
		</div>
		<div class="col-sm-auto my-2 my-sm-0">
			<div class="input-group input-group-sm">
				<input type="text" name="news_skip" id="news_skip" size="5" maxlength="5" value="<?php echo html($content['news']['news_skip']) ?>" class="form-control form-control-sm" />
				<div class="input-group-append">
					<span class="input-group-text"><?php echo $BL['be_skip_first_items'] ?></span>
				</div>
			</div>
		</div>
</div>

<div class="form-group align-items-center form-row">
  <label for="news_paginate" class="col-sm-2 col-form-label text-right"></label>
  <div class="col-sm-auto">
  	<div class="form-check form-check-inline">
      <input class="form-check-input" type="checkbox" name="news_paginate" id="news_paginate" value="1"<?php is_checked(1, $content['news']['news_paginate']) ?> />
      <label for="news_paginate" class="form-check-label"><?php echo $BL['be_pagination'] ?></label>
    </div>
  </div>
  <input type="hidden" name="news_paginate_basis" id="news_paginate_basis" value="<?php echo $content['news']['news_paginate_basis'] ?? 0; ?>" />
  <div class="col-sm-auto">
		<div class="input-group input-group-sm">
			<input type="text" name="news_paginate_count" id="news_paginate_count" size="5" maxlength="5"  class="form-control form-control-sm" value="<?php echo html($content['news']['news_paginate_count']) ?>"  />
			<div class="input-group-append">
				<span class="input-group-text"><?php echo $BL['be_cnt_rssfeed_item'] ?></span>
			</div>
		</div>
  </div>
</div>

<div class="form-group align-items-center form-row">
  <label for="news_archive_link" class="col-sm-2 col-form-label text-right"><?php echo $BL['be_archive'] ?></label>
  <div class="col-sm-auto">
    <div class="input-group input-group-sm">
      <input type="text" name="news_archive_link" id="news_archive_link" value="<?php echo html($content['news']['news_archive_link']) ?>" class="form-control" maxlength="250" data-toggle="tooltip" title="<?php echo $BL['be_func_struct_articleID'] ?>" />
      <span class="input-group-append">
        <button class="modalButton btn btn-blue sitemap-open" type="button" data-toggle="modal" data-target="#browserModal" data-src="articlebrowser.php?opt=4&field=news_archive_link" ></button>
      </span>
    </div>
  </div>
  <div class="col">
    <?php echo $BL['be_article_urlalias'].'/'.$BL['be_func_struct_articleID'] ?>
  </div>
</div>

<div class="form-group align-items-center form-row">
  <label for="news_detail_link" class="col-sm-2 col-form-label text-right"><?php echo $BL['be_news_detail_link'] ?></label>
  <div class="col-sm-auto">
    <div class="input-group input-group-sm">
      <input type="text" name="news_detail_link" id="news_detail_link" value="<?php echo html($content['news']['news_detail_link']) ?>" class="form-control" maxlength="250" data-toggle="tooltip" title="<?php echo $BL['be_func_struct_articleID'] ?>" />
      <span class="input-group-append">
        <button class="modalButton btn btn-blue sitemap-open" type="button" data-toggle="modal" data-target="#browserModal" data-src="articlebrowser.php?opt=4&field=news_detail_link" ></button>
      </span>
    </div>
  </div>
  <div class="col">
      <?php echo $BL['be_article_urlalias'].'/'.$BL['be_func_struct_articleID'] ?>
  </div>
</div>


<script type="text/javascript">

	function setPaginateBasis() {
		$('#news_paginate_count').css('visibility', $('#news_paginate_basis').prop('selectedIndex') ? 'hidden' : 'visible');
	}

  $(function(){
    initTomSelectTagAutosuggest('#news_keyword_autosuggest', '#news_category', 'newstags');


		setPaginateBasis();

		var allowedLang = $('input.allowedLang'),
            langAll = $('#langAll');

    langAll.change(function(){
      if($(this).is(':checked')) {
        allowedLang.attr('checked', false);
      }
    });

    allowedLang.change(function(){
      if($(this).is(':checked')) {
        langAll.attr('checked', false);
      }
    });

  });
</script>
