<?php
/**
 * cmsGO!
 *
 * @author Pixels & Points GmbH <info@pixels-points.ch>
 * @copyright Copyright (c) 2002-2026, Pixels & Points GmbH
 * @license https://www.pixels-points.ch/cmsgo-license.html Pixels & Points cmsGO! license
 *
 **/

// ----------------------------------------------------------------
// obligate check for cmsGO! constants
if (!defined('CMSGO_ROOT')) {
    die("You Cannot Access This Script Directly, Have a Nice Day.");
}
// ----------------------------------------------------------------

initJsAutocompleter();
initJsCalendar();

unset($_SESSION['filebrowser_image_target']);

$template_default['article']['image_default_width']    = isset($template_default['article']['image_default_width']) ? $template_default['article']['image_default_width'] : '' ;
$template_default['article']['image_default_height']   = isset($template_default['article']['image_default_height']) ? $template_default['article']['image_default_height'] : '' ;
$template_default['article']['imagelist_default_width']  = isset($template_default['article']['imagelist_default_width']) ? $template_default['article']['imagelist_default_width'] : '' ;
$template_default['article']['imagelist_default_height'] = isset($template_default['article']['imagelist_default_height']) ? $template_default['article']['imagelist_default_height'] : '' ;

$struct_alias = get_struct_alias($article["article_catid"]);
$struct_parental = get_struct_alias($article["article_catid"], true);
$langstr = '';

?>
<form action="cmsgo.php?do=articles&amp;p=2&amp;s=1&amp;aktion=1&amp;id=<?php echo $article["article_id"] ?>" method="post" name="article" id="article" required>
<div class="row align-items-center">
  <div class="col col-sm-auto text-center text-sm-left">
    <h1><?php echo $BL['be_article_title1'] ?></h1>
  </div>
  <div class="col-12 col-sm text-center text-sm-right mb-3">
    <div class="form-group align-items-center">
      <input name="updatesubmit" type="submit" class="btn btn-sm btn-blue" value="<?php echo $article["article_id"] ? $BL['be_article_cnt_button1'] : $BL['be_article_cnt_button2'] ?>" />
      <input name="Submit" type="submit" class="btn btn-sm btn-blue" value="<?php echo $BL['be_article_cnt_button3'] ?>" />
      <input name="donotsubmit" type="submit" class="btn btn-sm btn-blue" value="<?php echo $BL['be_newsletter_button_cancel'] ?>" onclick="return cancelEdit();" />
    </div>
  </div>
</div>

<div class="card">
<div class="card-header"><h1><?php echo $BL['be_article_estitle'] ?></h1></div>
<div class="card-body">
  <div class="form-group align-items-center form-row">
    <label for="article_cid" class="col-sm-2 col-form-label text-right"><?php echo $BL['be_article_cat'] ?></label>
    <div class="col-sm-8">
      <select name="article_cid" id="article_cid" class="custom-select form-control form-control-sm">
      <?php
        //keine definierte Kategorie = allgemeine Artikelkategorie
        echo '<option value="0"'.((!$article["article_catid"])?' selected="selected"':'').">".$BL['be_admin_struct_index']."</option>\n";
        struct_select_menu(0, 0, $article["article_catid"]);
      ?>
      </select>
    </div>
  </div>

  <div class="form-group align-items-center form-row">
    <label for="article_title" class="col-sm-2 col-form-label text-right"><a class="underline" href="#" id="cat-as-articletitle"><?php echo $BL['be_article_atitle'] ?></a></label>
    <div class="col-sm-8">
      <input name="article_title" type="text" class="form-control form-control-sm" id="article_title" value="<?php echo html($article["article_title"]) ?>" size="40" maxlength="5000" required />
    </div>
    <div class="col-sm-2">
      <div class="form-check">
        <input class="form-check-input" name="article_notitle" id="article_notitle" type="checkbox" value="1" <?php is_checked($article["article_notitle"], 1) ?> />
        <label class="form-check-label" for="article_notitle"><?php echo $BL['be_admin_struct_hide1'] ?></label>
      </div>
    </div>
  </div>

  <div class="form-group align-items-center form-row">
    <label for="article_subtitle" class="col-sm-2 col-form-label text-right"><?php echo $BL['be_article_asubtitle'] ?></label>
    <div class="col-sm-8">
      <input name="article_subtitle" type="text" class="form-control form-control-sm" id="article_subtitle" value="<?php echo html($article["article_subtitle"]) ?>" size="40" maxlength="5000" />
    </div>
  </div>

  <div class="form-group align-items-center form-row">
    <label for="article_alias" class="col-sm-2 col-form-label text-right"><?php echo $BL['be_article_urlalias'] ?></label>
    <div class="col-sm-4">
      <input name="article_alias" type="text" class="form-control form-control-sm" id="article_alias" value="<?php echo html($article["article_alias"]) ?>" maxlength="1000"<?php if (empty($cmsgo['allow_empty_alias'])): ?> onfocus="set_article_alias(true);"<?php endif; ?> onchange="this.value=create_alias(this.value);" />
    </div>
    <div class="col-sm-6">
      <a onclick="return set_article_alias();" class="underline text-blue"><?php echo $BL['be_article_urlalias'] ?></a><span class="mx-2">+</span>
      <a id="parent_alias" data-toggle="tooltip" title="<?php echo $struct_parental; ?>" data-alias="<?php echo $struct_parental; ?>" class="underline text-blue"><?php echo $BL['be_parental_alias'] ?></a><span class="mx-2">+</span>
      <a id="struct_alias" data-toggle="tooltip" title="<?php echo $struct_alias; ?>" data-alias="<?php echo $struct_alias; ?>" class="underline text-blue"><?php echo $BL['be_admin_struct_title'] ?></a>
    </div>
  </div>

  <hr />

  <div class="form-group align-items-center form-row">
    <label class="col-sm-2 col-form-label text-right"><?php echo $BL['be_article_abegin'] ?></label>
    <div class="col-sm-10">
      <div class="d-flex flex-wrap align-items-center">
        <div class="my-1 mr-3">
          <div id="article_begin" class="input-group input-group-sm" data-target-input="#article_begin_input">
            <div class="input-group-prepend">
              <div class="input-group-text">
                <input name="set_begin" type="checkbox" id="set_begin" value="1"<?php is_checked(1, $set_begin) ?> onclick="if (this.checked) { var d = '<?php echo cmsgo_strtotime($article['article_begin'], $BL['be_longdatetime'], '') ?>'; $('#article_begin').datetimepicker('date', d || moment()); } else { $('#article_begin').datetimepicker('clear'); }">
              </div>
              <label class="input-group-text" for="article_begin_input"><?php echo $BL['be_msg_from'] ?></label>
            </div>
            <input name="article_begin" type="text" id="article_begin_input" class="form-control form-control-sm datetimepicker datetimepicker-input" placeholder="<?php echo $BL['default_date_format'] . ' ' . $BL['default_time_format'] . ':SS'; ?>" value="<?php echo cmsgo_strtotime($article["article_begin"], $BL['be_longdatetime'], ''); ?>" data-target="#article_begin" autocomplete="off" >
            <div class="input-group-append" data-target="#article_begin" data-toggle="datetimepicker">
              <span class="datepickerbutton input-group-text form-control form-control-sm btn-blue"><i class="far fa-calendar-alt fa-fw"></i></span>
            </div>
          </div>
        </div>
        <div class="my-1">
          <div id="article_end" class="input-group input-group-sm" data-target-input="#article_end_input">
            <div class="input-group-prepend">
              <div class="input-group-text">
                <input name="set_end" type="checkbox" id="set_end" value="1"<?php is_checked(1, $set_end) ?> onclick="if (this.checked) { var d = '<?php echo cmsgo_strtotime($article['article_end'], $BL['be_longdatetime'], '') ?>'; $('#article_end').datetimepicker('date', d || moment()); } else { $('#article_end').datetimepicker('clear'); }">
              </div>
              <label class="input-group-text" for="article_end_input"><?php echo $BL['be_article_aend'] ?></label>
            </div>
            <input name="article_end" type="text" id="article_end_input" class="form-control form-control-sm datetimepicker datetimepicker-input" placeholder="<?php echo $BL['default_date_format'] . ' ' . $BL['default_time_format'] . ':SS'; ?>" value="<?php echo cmsgo_strtotime($article["article_end"], $BL['be_longdatetime'], ''); ?>" data-target="#article_end" autocomplete="off" >
            <div class="input-group-append" data-target="#article_end" data-toggle="datetimepicker">
              <span class="datepickerbutton input-group-text form-control form-control-sm btn-blue"><i class="far fa-calendar-alt fa-fw"></i></span>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <script type="text/javascript">
      $(function () {
          $('#article_begin').datetimepicker({
            locale: '<?php echo $_SESSION['wcs_user_lang'] ?>',
            format: "DD.MM.YYYY HH:mm:ss",
            useCurrent: false,
            buttons: {
              showClose: true
            }
          });
          $("#article_begin").on("change.datetimepicker", function (e) {
            if (e.date !== undefined) {
              document.article.set_begin.checked = !!e.date;
            }
          });

          $('#article_end').datetimepicker({
            locale: '<?php echo $_SESSION['wcs_user_lang'] ?>',
            format: "DD.MM.YYYY HH:mm:ss",
            useCurrent: false,
            buttons: {
              showClose: true
            }
          });
          $("#article_end").on("change.datetimepicker", function (e) {
            if (e.date !== undefined) {
              document.article.set_end.checked = !!e.date;
            }
          });
      });
  </script>
  <hr />

  <div class="form-group align-items-center form-row">
    <label for="article_sort" class="col-sm-2 col-form-label text-right"><?php echo $BL['be_cnt_sortvalue'] ?></label>
    <div class="col-sm-4">
      <input name="article_sort" type="text" id="article_sort" value="<?php echo empty($article["article_sort"]) ? 0 : intval($article["article_sort"]) ?>" class="form-control form-control-sm" maxlength="10" onkeyup="if(!parseInt(this.value,10))this.value='0';" />
    </div>
  </div>

  <div class="form-group align-items-center form-row">
    <label for="article_priorize" class="col-sm-2 col-form-label text-right"><?php echo $BL['be_priorize'] ?></label>
    <div class="col-sm-4">
      <select name="article_priorize" id="article_priorize" class="custom-select form-control form-control-sm">
        <?php
          for ($x=30; $x>=-30; $x--) {
              echo '  <option value="'.$x.'"';
              is_selected($x, $article["article_priorize"]);
              echo '>'. ($x==0 ? $BL['be_cnt_default'] : $x) .'</option>';
          }
        ?>
      </select>
    </div>
  </div>

  <div class="form-group align-items-center form-row">
    <label for="article_aliasid" class="col-sm-2 col-form-label text-right"><?php echo $BL['be_alias_articleID'] ?></label>
    <div class="col-sm-4">
      <input name="article_aliasid" type="text" class="form-control form-control-sm" id="article_aliasid" value="<?php echo $article["article_aliasid"] ? $article["article_aliasid"] : ''; ?>" size="11" maxlength="11" />
    </div>
    <div class="col-sm-6">
      <div class="form-check">
        <input class="form-check-input" name="article_headerdata" id="article_headerdata" type="checkbox" value="1" <?php is_checked($article["article_headerdata"], 1) ?> />
        <label class="form-check-label" for="article_headerdata"><?php echo $BL['be_alias_useAll'] ?></label>
      </div>
    </div>
  </div>

<?php if (count($cmsgo['allowed_lang']) > 1):  ?>
  <div class="form-group align-items-center form-row">
      <span class="col-sm-2 col-form-label text-right"><?php echo $BL['be_profile_label_lang'] ?></span>
      <div class="col-sm-4">
			<div class="lang-select">
					<div class="form-check form-check-inline">
						<input class="form-check-input lang-default" name="article_lang" id="article_lang_default" type="radio" value=""<?php is_checked('', $article['article_lang']); ?> />
						<label class="form-check-label" for="article_lang_default">
							<span class="flag-icon flag-icon-<?php echo $cmsgo['default_lang'] ?> mt-1" data-toggle="tooltip" title="<?php echo get_language_name($cmsgo['default_lang']) . ' ('.$BL['be_admin_tmpl_default'].')' ?>"></span>
                            <?php echo '('.$BL['be_admin_tmpl_default'].')'; ?>
						</label>
					</div>

				<?php foreach ($cmsgo['allowed_lang'] as $key => $lang):
                        $lang = strtolower($lang);
                        if ($lang == $cmsgo['default_lang']) {
                            continue;
                        }
				?>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input lang-opt" name="article_lang" id="article_lang_<?php echo $lang ?>" type="radio" value="<?php echo $lang ?>"<?php is_checked($lang, $article['article_lang']) ?> />
                        <label class="form-check-label" for="article_lang_<?php echo $lang ?>">
                            <span class="flag-icon flag-icon-<?php echo $lang ?>" data-toggle="tooltip" title="<?php echo get_language_name($lang) ?>"></span>
                        </label>
                    </div>
                <?php endforeach; ?>

     <div style="margin:10px 0;border-top:1px solid #e5e5e5;padding-top:5px;<?php if ($article['article_lang'] == ''): ?>display:none;<?php endif; ?>" id="lang-id-select">
        <div class="form-check form-check-inline">
          <input class="form-check-input" name="article_lang_type" id="article_lang_type_category" type="radio" value="category"<?php is_checked('category', $article['article_lang_type']); ?> />
          <label class="form-check-label" for="article_lang_type_category">
             <?php echo $BL['be_article_cat'] ?> ID
          </label>
        </div>
        <div class="form-check form-check-inline mb-3">
          <input class="form-check-input" name="article_lang_type" id="article_lang_type_article" type="radio" value="article"<?php is_checked('article', $article['article_lang_type']); ?> />
          <label class="form-check-label" for="article_lang_type_article">
            <?php echo $BL['be_cnt_articles'] ?> ID&nbsp;
            <span class="flag-icon flag-icon-<?php echo $cmsgo['default_lang'] ?>" data-toggle="tooltip" title="<?php echo get_language_name($cmsgo['default_lang']) . ' ('.$BL['be_admin_tmpl_default'].')' ?>"></span>
          </label>
        </div>
        <div class="input-group">
          <div class="input-group-prepend">
            <button class="modalButton btn btn-sm btn-blue sitemap-open" type="button" data-toggle="modal" data-target="#browserModal" data-src="articlebrowser.php?opt=2" ></button>
          </div>
          <input name="article_lang_id" type="text" id="article_lang_id" class="form-control form-control-sm" value="<?php echo $article['article_lang_id'] ? $article['article_lang_id'] : ''; ?>" maxlength="10" onfocus="this.blur()" />
        </div>
      </div>

  <?php
    if (intval($article['article_lang_id'])> 0 && $article['article_lang_type'] == 'article') {
        $where = 'article_id = '.$article['article_lang_id'];
        $adata = _dbGet('cmsgo_article', 'article_id, article_alias, article_title', $where, '', '', 1);
        echo '<br /><span class="font-weight-bold">' . $BL['be_cnt_target'] .' ID['.$adata[0]['article_id'].']:</span> ';
        if (is_array($adata)) {
            echo '<a href="cmsgo.php?&do=articles&p=2&s=1&id=' . $adata[0]['article_id'] . '" target="_blank" data-toggle="tooltip" title="' . $adata[0]['article_title'] . '">' . $adata[0]['article_alias'] . $cmsgo['rewrite_ext'] .'</a>';
        } else {
            echo $BL['be_admin_usr_err'];
        }

        foreach ($cmsgo['allowed_lang'] as $key => $lang) {
            $lang = strtolower($lang);

            if ($lang == $cmsgo['default_lang'] || $lang == $article['article_lang']) {
                continue;
            }
            $where = 'article_lang_id = '.$article['article_lang_id'].' AND article_lang LIKE '._dbEscape($lang);
            $adata = _dbGet('cmsgo_article', 'article_id, article_alias, article_title', $where, '', '', 1);
            if (isset($adata[0]['article_id'])) {
                $langstr .= '<br /><span class="flag-icon flag-icon-'.$lang.' mt-1" data-toggle="tooltip" title="'. get_language_name($lang).'"></span> <a href="cmsgo.php?&do=articles&p=2&s=1&aktion=1&id=' . $adata[0]['article_id'] . '" target="_blank" data-toggle="tooltip" title="' . $adata[0]['article_title'] . '">' . $adata[0]['article_alias'] . $cmsgo['rewrite_ext'] .'</a>';
                unset($adata);
            }
        }
    } else {
        foreach ($cmsgo['allowed_lang'] as $key => $lang) {
            $lang = strtolower($lang);

            if ($lang == $cmsgo['default_lang']) {
                continue;
            }

            $where = 'article_lang_id = '.$article['article_id'].' AND article_lang LIKE '._dbEscape($lang);
            $adata = _dbGet('cmsgo_article', 'article_id, article_alias, article_title', $where, '', '', 1);
            if (isset($adata[0]['article_id'])) {
                $langstr .= '<br /><span class="flag-icon flag-icon-'.$lang.'" data-toggle="tooltip" title="'. get_language_name($lang).'"></span> <a href="cmsgo.php?&do=articles&p=2&s=1&aktion=1&id=' . $adata[0]['article_id'] . '" target="_blank" data-toggle="tooltip" title="' . $adata[0]['article_title'] . '">' . $adata[0]['article_alias'] . $cmsgo['rewrite_ext'] .'</a>';
                unset($adata);
            }
        }
    }
  ?>

<?php echo $langstr; ?>
            </div>
        </div>
   </div>
<?php endif; ?>
<!-- Sprachwechsel ende-->

  <hr />

  <div class="form-group align-items-center form-row">
    <label for="article_pagetitle" class="col-sm-2 col-form-label text-right"><?php echo $BL['be_admin_page_pagetitle'] ?></label>
    <div class="col">
      <input name="article_pagetitle" type="text" id="article_pagetitle" class="form-control form-control-sm" value="<?php echo html($article['article_pagetitle']) ?>" size="40" maxlength="2000" />
    </div>
  </div>

  <div class="form-group align-items-center form-row">
    <label for="article_menutitle" class="col-sm-2 col-form-label text-right"><?php echo $BL['article_menu_title'] ?></label>
    <div class="col">
      <input name="article_menutitle" type="text" id="article_menutitle" class="form-control form-control-sm" value="<?php echo html($article["article_menutitle"]) ?>" size="40" />
    </div>
  </div>

  <div class="form-group align-items-center form-row">
    <label for="article_redirect" class="col-sm-2 col-form-label text-right"><?php echo $BL['be_article_aredirect'] ?></label>
    <div class="col">
      <input name="article_redirect" type="text" id="article_redirect" class="form-control form-control-sm" value="<?php echo html($article["article_redirect"]) ?>" size="40" />
    </div>
  </div>

  <div class="form-group align-items-center form-row">
    <label for="article_canonical" class="col-sm-2 col-form-label text-right"><?php echo $BL['be_canonical'] ?></label>
    <div class="col">
      <input name="article_canonical" type="text" id="article_canonical" class="form-control form-control-sm" value="<?php echo html($article["article_canonical"]) ?>" size="40" maxlength="2000" />
    </div>
  </div>

  <div class="form-group align-items-center form-row">
    <span class="col-sm-2 col-form-label text-right"><?php echo $BL['be_article_akeywords'] ?> <i class="fas fa-info-circle text-blue" data-toggle="tooltip" title="<?php echo $BL['be_input_text_tab'] ?>"></i></span>
    <div class="col">
      <input class="form-control form-control-sm border py-3 px-2" type="text" id="article_keyword_autosuggest" aria-label="<?php echo html_specialchars($BL['be_article_akeywords']) ?>" /><input type="hidden" name="article_keyword" id="article_keyword" value="<?php echo html($article["article_keyword"]) ?>" />
    </div>
  </div>

  <div class="form-group form-row">
    <label for="article_description" class="col-sm-2 col-form-label text-right"><?php echo $BL['be_cnt_description'] ?></label>
    <div class="col">
      <textarea name="article_description" rows="4" class="form-control form-control-sm" id="article_description"><?php echo html($article["article_description"]) ?></textarea>
    </div>
  </div>

  <div class="form-group align-items-center form-row">
    <label for="article_tmpllist" class="col-sm-2 col-form-label text-right"><?php echo $BL['be_admin_struct_template'] ?> <?php echo $BL['be_article_forlist'] ?></label>
    <div class="col-sm-4">
      <select name="article_tmpllist" id="article_tmpllist" class="custom-select form-control form-control-sm">
        <?php
        // templates for article listing
        $tmpllist = get_tmpl_files(CMSGO_TEMPLATE.'inc_cntpart/articlesummary/list');
        if ($article['image']['tmpllist'] == 'default') {
            $vals= ' selected="selected"';
        } else {
            $vals = '';
        }
        echo '<option value="default"'.$vals.'>'.$BL['be_cnt_default']."</option>\n";
        if (count($tmpllist)) {
            foreach ($tmpllist as $val) {
                $vals = '';
                if ($val == $article['image']['tmpllist']) {
                    $vals= ' selected="selected"';
                }
                $val = htmlspecialchars($val);
                echo '<option value="'.$val.'"'.$vals.'>'.$val."</option>\n";
            }
        }
        ?>
        </select>
    </div>

    <label for="article_tmplfull" class="col-sm-2 col-form-label text-right"><?php echo $BL['be_admin_struct_template'] ?> <?php echo $BL['be_article_forfull'] ?></label>
    <div class="col-sm-4">
      <select name="article_tmplfull" id="article_tmplfull" class="custom-select form-control form-control-sm">
        <?php
        // templates for full article
        $tmpllist = get_tmpl_files(CMSGO_TEMPLATE.'inc_cntpart/articlesummary/article');
        if ($article['image']['tmplfull'] == 'default') {
            $vals= ' selected="selected"';
        }
        echo '<option value="default"'.$vals.'>'.$BL['be_cnt_default']."</option>\n";
        if (count($tmpllist)) {
            foreach ($tmpllist as $val) {
                $vals = '';
                if ($val == $article['image']['tmplfull']) {
                    $vals= ' selected="selected"';
                }
                $val = htmlspecialchars($val);
                echo '<option value="'.$val.'"'.$vals.'>'.$val."</option>\n";
            }
        }
        ?>
      </select>
    </div>
  </div>


  <div class="form-group align-items-center form-row bg-grey py-2">
    <label for="article_listmaxwords" class="col-sm-2 col-form-label text-right"><?php echo $BL['be_cnt_results_wordlimit'] ?></label>
    <div class="col-sm-4">
      <input class="form-control form-control-sm" name="article_listmaxwords" type="text" id="article_listmaxwords" value="<?php echo empty($article['image']['list_maxwords']) ? '' : intval($article['image']['list_maxwords']) ?>" size="10" maxlength="6" />
    </div>

      <label for="article_meta_class" class="col-sm-2 col-form-label text-right"><?php echo $BL['be_cnt_css_class'] ?></label>
      <div class="col-sm-4">
          <input class="form-control form-control-sm" name="article_meta_class" type="text" id="article_meta_class" value="<?php echo html($article["article_meta"]['class']) ?>" size="40" maxlength="255" />
      </div>
  </div>

  <div class="form-group form-row bg-grey py-2">
    <span class="col-sm-2 col-form-label text-right pt-0"><?php echo $BL['be_cnt_several'] ?></span>
    <div class="col">

      <div class="form-check">
          <input class="form-check-input" name="article_hidesummary" type="checkbox" id="article_hidesummary" value="1"<?php is_checked(1, $article["article_hidesummary"]); ?> />
          <label class="form-check-label align-items-center" for="article_hidesummary">
          <?php echo $BL['be_article_nosummary'] ?>
        </label>
      </div>

      <div class="form-check">
          <input class="form-check-input" name="article_morelink" type="checkbox" id="article_morelink" value="1"<?php is_checked(1, $article["article_morelink"]); ?> />
          <label class="form-check-label align-items-center" for="article_morelink">
          <?php echo $BL['be_article_morelink'] ?>
        </label>
      </div>

      <div class="form-check">
          <input class="form-check-input" name="article_noteaser" type="checkbox" id="article_noteaser" value="1"<?php is_checked(1, $article['article_noteaser']); ?> />
          <label class="form-check-label align-items-center" for="article_noteaser">
          <?php echo $BL['be_article_noteaser'] ?>
        </label>
      </div>

      <div class="form-check">
          <input class="form-check-input" name="article_paginate" type="checkbox" id="article_paginate" value="1"<?php is_checked(1, $article["article_paginate"]); ?> />
          <label class="form-check-label align-items-center" for="article_paginate">
          <?php echo $BL['be_cnt_pagination'] ?>
        </label>
      </div>
    </div>
  </div>
  <div class="row">
    <div class="col-sm-12">
    <?php

$wysiwyg_editor = array(
'value'   => $article["article_summary"],
'field'   => 'article_summary',
'height'  => '350px',
'width'   => '100%',
'rows'    => '10',
'editor'  => $_SESSION["WYSIWYG_EDITOR"],
'lang'    => 'en'
);
include CMSGO_ROOT.'/include/inc_lib/wysiwyg.editor.inc.php';

?>
    </div>
  </div>

  <hr />

  <div class="form-group align-items-center form-row mt-2">
    <label for="cimage_name" class="col-sm-2 col-form-label text-right"><?php echo $BL['be_cnt_image'] ?>: <?php echo $BL['be_article_forfull'] ?></label>
    <div class="col-sm-4">
      <div class="input-group">
        <span class="input-group-prepend">
          <button class="modalButton btn btn-sm btn-blue folder-open" type="button" data-toggle="modal" data-target="#browserModal" data-src="filebrowser.php?opt=0&amp;target=summary" ></button>
        </span>
        <input name="cimage_name" type="text" id="cimage_name" class="form-control form-control-sm" value="<?php echo html($article['image']['name']) ?>" onfocus="this.blur()" />
        <span class="input-group-append">
          <a href="#" class="btn btn-sm btn-danger trash"  type="button" data-toggle="tooltip" title="<?php echo $BL['be_cnt_delimage'] ?>" onclick="bootstrapConfirm('<?php echo js_singlequote($BL['be_image_delete_js']); ?>' + (document.article.cimage_name.value ? '\n[' + document.article.cimage_name.value + ']' : ''), function() { document.article.cimage_name.value='';document.article.cimage_id.value='0'; if (typeof onImageSelected === 'function') onImageSelected('_', '0', ''); }, '<?php echo js_singlequote($BL['be_cnt_delimage']); ?>', 'danger'); this.blur();return false;"></a>
        </span>
      </div>
      <input name="cimage_id" type="hidden" value="<?php echo $article['image']['id'] ?>" />
    </div>
  </div>

  <div class="form-group align-items-center form-row">
    <label for="cimage_width" class="col-sm-2 col-form-label text-right"><?php echo $BL['be_cnt_maxw'] ?></label>
    <div class="col-sm-4">
      <input name="cimage_width" type="text" class="form-control form-control-sm" id="cimage_width" size="4" maxlength="4" onkeyup="if(!parseInt(this.value,10)) this.value='';" value="<?php echo empty($article['image']['width']) ? $template_default['article']['image_default_width'] : $article['image']['width']; ?>" />
    </div>
  </div>

  <div class="form-group align-items-center form-row">
    <label for="cimage_height" class="col-sm-2 col-form-label text-right"><?php echo $BL['be_cnt_maxh'] ?></label>
    <div class="col-sm-4">
      <input name="cimage_height" type="text" class="form-control form-control-sm" id="cimage_height" size="4" maxlength="4" onkeyup="if(!parseInt(this.value,10)) this.value='';" value="<?php echo empty($article['image']['height']) ? $template_default['article']['image_default_height'] : $article['image']['height']; ?>" />
    </div>
  </div>

  <div class="form-group form-row align-items-center bg-grey py-2">
    <span class="col-sm-2 col-form-label text-right"><?php echo $BL['be_cnt_several'] ?></span>
    <div class="col">
      <div class="form-check form-check-inline">
        <input class="form-check-input" name="cimage_zoom" type="checkbox" id="cimage_zoom" value="1" <?php is_checked(1, $article['image']['zoom']); ?> />
        <label class="form-check-label" for="cimage_zoom"><?php echo $BL['be_cnt_enlarge'] ?></label>
      </div>
      <div class="form-check form-check-inline">
        <input class="form-check-input" name="cimage_lightbox" type="checkbox" id="cimage_lightbox" value="1" <?php is_checked(1, empty($article['image']['lightbox']) ? 0 : 1); ?> onchange="if(this.checked){document.getElementById('cimage_zoom').checked=true;}" />
        <label class="form-check-label" for="cimage_lightbox"><?php echo $BL['be_cnt_lightbox'] ?></label>
      </div>
    </div>
  </div>
  <div class="form-group form-row">
    <label for="cimage_caption" class="col-sm-2 col-form-label text-right"><?php echo $BL['be_cnt_description'] ?></label>
    <div class="col">
      <textarea name="cimage_caption" cols="30" rows="3" class="form-control form-control-sm" id="cimage_caption"><?php echo html($article['image']['caption']) ?></textarea>
      <span class="col-sm-12 col-form-label pl-0">
        <?php echo $BL['be_cnt_caption']; ?>
        |
        <?php echo $BL['be_caption_alt']; ?>
        |
        <?php echo $BL['be_admin_page_link']; ?> <em><?php echo $BL['be_cnt_target']; ?></em>
        |
        <?php echo $BL['be_caption_title']; ?>
        |
        <?php echo $BL['be_copyright']; ?>
      </span>
      <div class="form-check">
				<input class="form-check-input" type="checkbox" name="cimage_caption_suppress" id="cimage_caption_suppress" value="1" <?php is_checked(1, empty($article['image']['caption_suppress']) ? 0 : 1); ?> />
				<label class="form-check-label" for="cimage_caption_suppress"><?php echo $BL['be_suppress_render_caption']; ?></label>
      </div>
    </div>
    <div id="cimage_preview_container" class="col-sm-2 text-right">
    <?php
      $_SESSION['image_browser_article'] = 1;
      $thumb_image = false;
      if (!empty($article["image"]["hash"])) {
          $thumb_image = get_cached_image(array(
        "target_ext"  =>  $article['image']['ext'],
        "image_name"  =>  $article['image']['hash'] . '.' . $article['image']['ext'],
        "thumb_name"  =>  md5($article['image']['hash'].$cmsgo["img_list_width"].$cmsgo["img_list_height"].$cmsgo["sharpen_level"].$cmsgo['colorspace'])
          ));
      }
      echo $thumb_image ? '<img src="'. $thumb_image['src'] .'" '.$thumb_image[3].' alt="" />' : '&nbsp;';
      ?>
    </div>
  </div>

  <hr />

  <?php
  // set default list values
  if (!isset($article['image']['list_usesummary'])) {
      $article['image']['list_usesummary']  = 0;
      $article['image']['list_name']      = '';
      $article['image']['list_id']      = 0;
      $article['image']['list_width']     = '';
      $article['image']['list_height']    = '';
      $article['image']['list_zoom']      = 0;
      $article['image']['list_caption']   = '';
  }
  ?>
  <div class="form-group align-items-center form-row">
    <label for="cimage_usesummary" class="col-sm-2 col-form-label text-right"><?php echo $BL['be_cnt_same_as_summary'] ?></label>
    <div class="col-sm-4">
      <input name="cimage_usesummary" type="checkbox" id="cimage_usesummary" value="1" <?php is_checked(1, $article['image']['list_usesummary']); ?> />
    </div>
  </div>

  <div class="form-group align-items-center form-row">
    <label for="cimage_list_name" class="col-sm-2 col-form-label text-right"><?php echo $BL['be_cnt_image'] ?>: <?php echo $BL['be_article_forlist'] ?></label>
    <div class="col-sm-4">
      <div class="input-group">
        <div class="input-group-prepend">
          <button class="modalButton btn btn-sm btn-blue folder-open" type="button" data-toggle="modal" data-target="#browserModal" data-src="filebrowser.php?opt=0&amp;target=list" ></button>
        </div>
        <input name="cimage_list_name" type="text" id="cimage_list_name" class="form-control form-control-sm" value="<?php echo html($article['image']['list_name']) ?>" onfocus="this.blur()" />
        <div class="input-group-append">
          <a href="#" class="btn btn-sm btn-danger trash"  type="button" data-toggle="tooltip" title="<?php echo $BL['be_cnt_delimage'] ?>" onclick="bootstrapConfirm('<?php echo js_singlequote($BL['be_image_delete_js']); ?>' + (document.article.cimage_list_name.value ? '\n[' + document.article.cimage_list_name.value + ']' : ''), function() { document.article.cimage_list_name.value='';document.article.cimage_list_id.value='0'; if (typeof onImageSelected === 'function') onImageSelected('_list_', '0', ''); }, '<?php echo js_singlequote($BL['be_cnt_delimage']); ?>', 'danger'); this.blur();return false;"></a>
        </div>
      </div>
      <input name="cimage_list_id" type="hidden" value="<?php echo $article['image']['list_id'] ?>" />
    </div>
  </div>

  <div class="form-group align-items-center form-row">
    <label for="cimage_list_width" class="col-sm-2 col-form-label text-right"><?php echo $BL['be_cnt_maxw'] ?></label>
    <div class="col-sm-4">
      <input name="cimage_list_width" type="text" class="form-control form-control-sm" id="cimage_list_width" size="4" maxlength="4" onkeyup="if(!parseInt(this.value,10)) this.value='';" value="<?php echo empty($article['image']['list_width']) ? $template_default['article']['imagelist_default_width'] : $article['image']['list_width']; ?>" />
    </div>
  </div>

  <div class="form-group align-items-center form-row">
    <label for="cimage_list_height" class="col-sm-2 col-form-label text-right"><?php echo $BL['be_cnt_maxh'] ?></label>
    <div class="col-sm-4">
      <input name="cimage_list_height" type="text" class="form-control form-control-sm" id="cimage_list_height" size="4" maxlength="4" onkeyup="if(!parseInt(this.value,10)) this.value='';" value="<?php echo empty($article['image']['list_height']) ? $template_default['article']['imagelist_default_height'] : $article['image']['list_height']; ?>" />
    </div>
  </div>

  <div class="form-group form-row align-items-center bg-grey py-2">
    <span class="col-sm-2 col-form-label text-right"><?php echo $BL['be_cnt_several'] ?></span>
    <div class="col">
      <div class="form-check form-check-inline">
				<input class="form-check-input" name="cimage_list_zoom" type="checkbox" id="cimage_list_zoom" value="1" <?php is_checked(1, $article['image']['list_zoom']); ?> />
				<label class="form-check-label" for="cimage_list_zoom"><?php echo $BL['be_cnt_enlarge'] ?></label>
      </div>
      <div class="form-check form-check-inline">
          <input class="form-check-input" name="cimage_list_lightbox" type="checkbox" id="cimage_list_lightbox" value="1" <?php is_checked(1, empty($article['image']['list_lightbox']) ? 0 : 1); ?> onchange="if(this.checked){document.getElementById('cimage_list_zoom').checked=true;}" />
          <label class="form-check-label" for="cimage_list_lightbox">
          <?php echo $BL['be_cnt_lightbox'] ?>
        </label>
      </div>
    </div>
  </div>
  <div class="form-group form-row">
    <label for="cimage_list_caption" class="col-sm-2 col-form-label text-right"><?php echo $BL['be_cnt_description'] ?></label>
    <div class="col">
      <textarea name="cimage_list_caption" cols="30" rows="3" class="form-control form-control-sm" id="cimage_list_caption"><?php echo html($article['image']['list_caption']) ?></textarea>
      <span class="col col-form-label pl-0">
        <?php echo $BL['be_cnt_caption']; ?>
        |
        <?php echo $BL['be_caption_alt']; ?>
        |
        <?php echo $BL['be_admin_page_link']; ?> <em><?php echo $BL['be_cnt_target']; ?></em>
        |
        <?php echo $BL['be_caption_title']; ?>
        |
        <?php echo $BL['be_copyright']; ?>
      </span>

       <div class="form-check">
				<input class="form-check-input" type="checkbox" name="cimage_list_caption_suppress" id="cimage_list_caption_suppress" value="1" <?php is_checked(1, empty($article['image']['list_caption_suppress']) ? 0 : 1); ?> />
				<label class="form-check-label" for="cimage_list_caption_suppress"><?php echo $BL['be_suppress_render_caption']; ?></label>
      </div>
    </div>
    <div id="cimage_list_preview_container" class="col-sm-2 text-right">
    <?php

    $_SESSION['image_browser_article'] = 1;

    $thumb_image = false;
    if (!empty($article["image"]["list_hash"])) {
        $thumb_image = get_cached_image(array(
        "target_ext"  =>  $article['image']['list_ext'],
        "image_name"  =>  $article['image']['list_hash'] . '.' . $article['image']['list_ext'],
        "thumb_name"  =>  md5($article['image']['list_hash'].$cmsgo["img_list_width"].$cmsgo["img_list_height"].$cmsgo["sharpen_level"].$cmsgo['colorspace'])
      ));
    }
      echo $thumb_image ? '<img src="'. $thumb_image['src'] .'" '.$thumb_image[3].' alt="" />' : '&nbsp;';

    ?>
    </div>
  </div>

  <div class="form-group align-items-center form-row">
    <label for="article_uid" class="col-sm-2 col-form-label text-right"><?php echo $BL['be_article_articleowner'] ?></label>
    <div class="col-sm-4">
      <div class="input-group">
      <select name="article_uid" id="article_uid" class="custom-select form-control form-control-sm">
        <?php
        $u_sql = "SELECT usr_id, usr_name, usr_login, usr_admin FROM ".DB_PREPEND."cmsgo_user WHERE usr_aktiv=1 ORDER BY usr_admin DESC, usr_name";
        $u_result = _dbQuery($u_sql);
        if (isset($u_result[0]['usr_id'])) {
            foreach ($u_result as $u_row) {
                echo '<option value="'.$u_row['usr_id'].'"';
                if ($u_row['usr_id'] == $article["article_uid"]) {
                    echo ' selected="selected"';
                }
                if (intval($u_row['usr_admin'])) {
                    echo ' class="option-admin"';
                }
                echo '>'.html(($u_row['usr_name']) ? $u_row['usr_name'] : $u_row['usr_login']).'</option>';
            }
        }
        ?>
      </select>
      <div class="input-group-append">
        <span class="input-group-text form-control-sm py-1"><?php echo $BL['be_article_adminuser'] ?></span>
      </div>
      </div>
    </div>
  </div>

  <div class="form-group align-items-center form-row">
    <label for="article_username" class="col-sm-2 col-form-label text-right"><?php echo $BL['be_article_username'] ?></label>
    <div class="col-sm-4">
      <input name="article_username" type="text" id="article_username" class="form-control form-control-sm" value="<?php echo html($article["article_username"]) ?>" size="40" maxlength="200" />
    </div>
  </div>

  <hr />

  <div class="form-group align-items-center form-row">
    <label for="article_cacheoff" class="col-sm-2 col-form-label text-right"><?php echo $BL['be_cache'] ?> <?php echo $BL['be_off'] ?></label>
    <div class="col-sm-4">
      <input name="article_cacheoff" type="checkbox" id="article_cacheoff" value="1" <?php if ($article["article_timeout"] === '0') {
            echo "checked";
        } ?> />
    </div>
  </div>

  <div class="form-group align-items-center form-row">
    <label for="article_timeout" class="col-sm-2 col-form-label text-right"><?php echo $BL['be_cache'] ?></label>
    <div class="col-sm-4">
      <div class="input-group">
        <select name="article_timeout" id="article_timeout" class="custom-select form-control form-control-sm" onchange="document.article.article_cacheoff.checked=false;">
            <?php
            echo '<option value=" ">'.$BL['be_admin_tmpl_default']."</option>\n";
            echo '<option value="60"'.is_selected($article["article_timeout"], '60', 0, 0).'>&nbsp;&nbsp;1 '.$BL['be_date_minute']."</option>\n";
            echo '<option value="300"'.is_selected($article["article_timeout"], '300', 0, 0).'>&nbsp;&nbsp;5 '.$BL['be_date_minutes']."</option>\n";
            echo '<option value="900"'.is_selected($article["article_timeout"], '900', 0, 0).'>15 '.$BL['be_date_minutes']."</option>\n";
            echo '<option value="1800"'.is_selected($article["article_timeout"], '1800', 0, 0).'>30 '.$BL['be_date_minutes']."</option>\n";
            echo '<option value="3600"'.is_selected($article["article_timeout"], '3600', 0, 0).'>&nbsp;&nbsp;1 '.$BL['be_date_hour']."</option>\n";
            echo '<option value="14400"'.is_selected($article["article_timeout"], '14400', 0, 0).'>&nbsp;&nbsp;4 '.$BL['be_date_hours']."</option>\n";
            echo '<option value="43200"'.is_selected($article["article_timeout"], '43200', 0, 0).'>12 '.$BL['be_date_hours']."</option>\n";
            echo '<option value="86400"'.is_selected($article["article_timeout"], '86400', 0, 0).'>&nbsp;&nbsp;1 '.$BL['be_date_day']."</option>\n";
            echo '<option value="172800"'.is_selected($article["article_timeout"], '172800', 0, 0).'>&nbsp;&nbsp;2 '.$BL['be_date_days']."</option>\n";
            echo '<option value="604800"'.is_selected($article["article_timeout"], '604800', 0, 0).'>&nbsp;&nbsp;1 '.$BL['be_date_week']."</option>\n";
            echo '<option value="1209600"'.is_selected($article["article_timeout"], '1209600', 0, 0).'>&nbsp;&nbsp;2 '.$BL['be_date_weeks']."</option>\n";
            echo '<option value="2592000"'.is_selected($article["article_timeout"], '2592000', 0, 0).'>&nbsp;&nbsp;1 '.$BL['be_date_month']."</option>\n";

            ?>
        </select>
        <div class="input-group-append">
          <span class="input-group-text form-control-sm py-1"><?php echo $BL['be_cache_timeout'] ?></span>
        </div>
      </div>
    </div>
  </div>

  <hr />

    <div class="form-group align-items-center form-row">
        <span class="col-sm-2 col-form-label text-right"><?php echo $BL['be_robots'] ?></span>
        <div class="col">
            <div class="form-check">
                <input class="form-check-input" name="article_meta_noindex" type="checkbox" id="article_meta_noindex" value="1"<?php is_checked(1, $article["article_meta"]['noindex']); ?> />
                <label class="form-check-label" for="article_meta_noindex"><?php echo $BL['be_robots_noindex'] ?></label>
            </div>
        </div>
        <div class="col">
            <div class="form-check">
                <input class="form-check-input" name="article_meta_nofollow" type="checkbox" id="article_meta_nofollow" value="1"<?php is_checked(1, $article["article_meta"]['nofollow']); ?> />
                <label class="form-check-label" for="article_meta_nofollow"><?php echo $BL['be_robots_nofollow'] ?></label>
            </div>
        </div>
    </div>

    <hr />

  <div class="form-group form-row bg-grey py-2">
    <span class="col-sm-2 col-form-label text-right pt-0"><?php echo $BL['be_ftptakeover_status'] ?></span>
    <div class="col">
      <div class="form-check">
        <input class="form-check-input" name="article_nositemap" type="checkbox" id="article_nositemap" value="1"<?php is_checked(1, $article["article_nositemap"]); ?> />
        <label class="form-check-label" for="article_nositemap"><?php echo  $BL['be_ctype_sitemap'] ?> </label>
      </div>
      <div class="form-check">
        <input class="form-check-input"  name="article_nosearch" type="checkbox" id="article_nosearch" value="1" <?php is_checked(1, $article['article_nosearch']); ?> />
        <label class="form-check-label" for="article_nosearch"><?php echo $BL['be_no_search'] ?></label>
      </div>
      <div class="form-check">
        <input class="form-check-input"  name="article_norss" type="checkbox" id="article_norss" value="1" <?php is_checked(1, $article['article_norss']); ?> />
        <label class="form-check-label" for="article_norss"><?php echo $BL['be_no_rss'] ?></label>
      </div>
      <?php
				// Opengraph fallback when creating a new article
				if (!isset($_POST['article_title']) && empty($article["article_id"]) && defined('ACAT_OPENGRAPH_STATUS') && ACAT_OPENGRAPH_STATUS === false) {
						$article['article_opengraph'] = 0;
				}
      ?>
      <div class="form-check">
        <input class="form-check-input"  name="article_opengraph" type="checkbox" id="article_opengraph" value="1" <?php is_checked(1, $article['article_opengraph']); ?> />
        <label class="form-check-label" for="article_opengraph"><?php echo $BL['be_opengraph_support'] ?></label>
      </div>
      <div class="form-check">
        <input class="form-check-input" name="article_aktiv" type="checkbox" id="article_aktiv" value="1"<?php is_checked(1, $article["article_aktiv"]); ?> />
        <label class="form-check-label" for="article_aktiv"><?php echo $BL['be_admin_struct_visible'] ?></label>
      </div>
      <div class="form-check">
        <input class="form-check-input" name="article_archive" type="checkbox" id="article_archive" value="1" <?php is_checked(1, $article['article_archive_status']); ?> />
        <label class="form-check-label" for="article_archive"><?php echo $BL['be_show_archived'] ?></label>
      </div>
    </div>
  </div>
<?php if (isset($article["article_date"])) {
          ?>
  <div class="form-group align-items-center form-row">
    <span class="col-sm-2 col-form-label text-right"><?php echo $BL['be_article_eslastedit'] ?>: </span>
    <div class="col-sm-4">
      <?php echo (empty($_POST["article_update"]) || !intval($_POST["article_update"])) ? $article["article_date"] : $BL['be_article_esnoupdate']; ?>
    </div>
  </div>
<?php
      } ?>
  <div class="form-group align-items-center form-row">
    <div class="col-sm-2">
      <input name="article_update" type="hidden" id="article_update" value="1" />
    </div>
  </div>
</div>
</div>

<div class="form-group align-items-center text-center text-sm-right mt-4">
  <input name="updatesubmit" type="submit" class="btn btn-sm btn-blue" value="<?php echo $article["article_id"] ? $BL['be_article_cnt_button1'] : $BL['be_article_cnt_button2'] ?>" />
  <input name="Submit" type="submit" class="btn btn-sm btn-blue" value="<?php echo $BL['be_article_cnt_button3'] ?>" />
  <input name="donotsubmit" type="submit" class="btn btn-sm btn-blue" value="<?php echo $BL['be_newsletter_button_cancel'] ?>" onclick="return cancelEdit();" />
</div>

</form>

<script type="text/javascript">
$(function(){

  $("#article_keyword_autosuggest").autoSuggest('<?php echo CMSGO_URL ?>include/inc_act/ajax_connector.php', {
    selectedItemProp: "cat_name",
    selectedValuesProp: 'cat_name',
    searchObjProps: "cat_name",
    queryParam: 'value',
    extraParams: '&method=json&action=category&<?php echo get_token_get_string(); ?>',
    startText: '',
    preFill: $("#article_keyword").val(),
    neverSubmit: true,
    asHtmlID: 'keyword-autosuggest'
  });

  $('#article').submit(function(){
    $("#article_keyword").val($('#as-values-keyword-autosuggest').val());
  });

  // Handle language switch click
  var langIdSelect = $('#lang-id-select');

  $('input.lang-opt').change(function(){
    langIdSelect.show();
  });

  $('input.lang-default').change(function(){
    langIdSelect.hide();
    $('#article_lang_id').val('');
    $('input[name="article_lang_type"]').prop('checked', false);
  });

  $('#struct_alias,#parent_alias').click(function() {

    var struct = $(this).data('alias') || $('#article_cid option:selected').text();
    var title = $.trim($('#article_title').val());

    if(struct.length) {
      struct = struct.replace(/^-+/gi, '').trim();

      if(title) {
        struct += '<?php if ($cmsgo['alias_allow_slash']): ?>/<?php else: ?>-<?php endif; ?>'+title;
      }
    } else {
      struct = title;
    }

    $('#article_alias').val( create_alias(struct) );
  });

  $('#cat-as-articletitle').click(function(evnt){
    evnt.preventDefault();
    var currentCat = $('#article_cid option:selected').text();
    if(currentCat) {
      $('#article_title').val(currentCat.replace(/^-+ /, ''));
    }
  });
});

function cancelEdit() {
    document.location.href='cmsgo.php'+'?<?php echo CSRF_GET_TOKEN; ?>&do=articles<?php echo $article["article_id"] ? '&p=2&s=1&id='.$article["article_id"] : '' ?>';
  return false;
}

function onImageSelected(target, id, name) {
    if (target === '_') {
        var container = $('#cimage_preview_container');
        if (container.length) {
            if (id && parseInt(id, 10) > 0) {
                container.html('<img src="img/cmsimage.php/200x200/' + id + '" alt="" />');
            } else {
                container.html('&nbsp;');
            }
        }
    } else if (target === '_list_') {
        var container = $('#cimage_list_preview_container');
        if (container.length) {
            if (id && parseInt(id, 10) > 0) {
                container.html('<img src="img/cmsimage.php/200x200/' + id + '" alt="" />');
            } else {
                container.html('&nbsp;');
            }
        }
    }
}

</script>
