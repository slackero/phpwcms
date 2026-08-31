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
    die('You Cannot Access This Script Directly, Have a Nice Day.');
}
// ----------------------------------------------------------------

initJsAutocompleter();
initJsCalendar();

unset($_SESSION['filebrowser_image_target']);

$template_default['article']['image_default_width']    = $template_default['article']['image_default_width'] ?? '';
$template_default['article']['image_default_height']   = $template_default['article']['image_default_height'] ?? '';
$template_default['article']['imagelist_default_width']  = $template_default['article']['imagelist_default_width'] ?? '';
$template_default['article']['imagelist_default_height'] = $template_default['article']['imagelist_default_height'] ?? '';

$struct_alias = get_struct_alias($article['article_catid']);
$struct_parental = get_struct_alias($article['article_catid'], true);
$langstr = '';

?>
<form action="phpwcms.php?do=articles&amp;p=2&amp;s=1&amp;aktion=1&amp;id=<?php echo $article['article_id'] ?>" method="post" name="article" id="article" required>
<div class="row align-items-center">
  <div class="col col-sm-auto text-center text-sm-start">
    <h1><?php echo $BL['be_article_title1'] ?></h1>
  </div>
  <div class="col-12 col-sm text-center text-sm-end mb-3">
    <div class="form-group align-items-center">
      <button name="updatesubmit" type="submit" class="btn btn-sm btn-blue" value="1"><i class="fa-solid fa-rotate"></i> <?php echo $article['article_id'] ? $BL['be_article_cnt_button1'] : $BL['be_article_cnt_button2'] ?></button>
      <button name="Submit" type="submit" class="btn btn-sm btn-blue ms-1" value="<?php echo $BL['be_article_cnt_button3'] ?>"><i class="fa-solid fa-check"></i> <?php echo $BL['be_article_cnt_button3'] ?></button>
      <button name="donotsubmit" type="button" class="btn btn-sm btn-danger ms-3" onclick="return cancelEdit();"><i class="fa-solid fa-times"></i> <?php echo $BL['be_newsletter_button_cancel'] ?></button>
    </div>
  </div>
</div>

<div class="card">
<div class="card-header"><h1><?php echo $BL['be_article_estitle'] ?></h1></div>
<div class="card-body">
  <ul class="nav nav-tabs mb-3" id="articleTabs" role="tablist">
    <li class="nav-item"><a class="nav-link active" id="content-tab" data-bs-toggle="tab" href="#content-sect" role="tab" aria-controls="content-sect" aria-selected="true"><?php echo $BL['be_content'] ?></a></li>
    <li class="nav-item"><a class="nav-link" id="image-tab" data-bs-toggle="tab" href="#image-sect" role="tab" aria-controls="image-sect" aria-selected="false"><?php echo $BL['be_images'] ?></a></li>
    <li class="nav-item"><a class="nav-link" id="meta-tab" data-bs-toggle="tab" href="#meta-sect" role="tab" aria-controls="meta-sect" aria-selected="false"><?php echo $BL['be_metadata'] ?></a></li>
    <li class="nav-item"><a class="nav-link" id="settings-tab" data-bs-toggle="tab" href="#settings-sect" role="tab" aria-controls="settings-sect" aria-selected="false"><?php echo $BL['be_settings'] ?></a></li>
  </ul>

  <div class="tab-content" id="articleTabsContent">
    <!-- CONTENT TAB -->
    <div class="tab-pane fade show active" id="content-sect" role="tabpanel" aria-labelledby="content-tab">
      <div class="form-group align-items-center row g-2">
        <label for="article_cid" class="col-sm-2 col-form-label text-sm-end"><?php echo $BL['be_article_cat'] ?></label>
        <div class="col-sm-8">
          <select name="article_cid" id="article_cid" class="form-select form-select-sm">
          <?php
            //keine definierte Kategorie = allgemeine Artikelkategorie
            echo '<option value="0"'.((!$article['article_catid'])?' selected="selected"':''). '>' .$BL['be_admin_struct_index']."</option>\n";
            struct_select_menu(0, 0, $article['article_catid']);
          ?>
          </select>
        </div>
      </div>

      <div class="form-group align-items-center row g-2">
        <label for="article_title" class="col-sm-2 col-form-label text-sm-end"><a class="underline" href="#" id="cat-as-articletitle"><?php echo $BL['be_article_atitle'] ?></a></label>
        <div class="col-sm-10">
          <div class="input-group input-group-sm">
            <input name="article_title" type="text" class="form-control" id="article_title" value="<?php echo html($article['article_title']) ?>" size="40" maxlength="5000" required />
            
              <div class="input-group-text">
                <input class="me-1" name="article_notitle" id="article_notitle" type="checkbox" value="1" <?php is_checked($article['article_notitle'], 1) ?> />
                <label class="form-check-label" for="article_notitle"><?php echo $BL['be_admin_struct_hide1'] ?></label>
              
            </div>
          </div>
        </div>
      </div>

      <div class="form-group align-items-center row g-2">
        <label for="article_subtitle" class="col-sm-2 col-form-label text-sm-end"><?php echo $BL['be_article_asubtitle'] ?></label>
        <div class="col-sm-10">
          <input name="article_subtitle" type="text" class="form-control form-control-sm" id="article_subtitle" value="<?php echo html($article['article_subtitle']) ?>" size="40" maxlength="5000" />
        </div>
      </div>

      <div class="form-group align-items-center row g-2">
        <label for="article_alias" class="col-sm-2 col-form-label text-sm-end"><?php echo $BL['be_article_urlalias'] ?></label>
        <div class="col-sm-10">
          <div class="input-group input-group-sm">
            <input name="article_alias" type="text" class="form-control" id="article_alias" value="<?php echo html($article['article_alias']) ?>" maxlength="1000"<?php if (empty($phpwcms['allow_empty_alias'])): ?> onfocus="set_article_alias(true);"<?php endif; ?> onchange="this.value=create_alias(this.value);" />
            
              <button type="button" id="btn_generate_alias" onclick="return set_article_alias();" class="btn btn-outline-light btn-alias-action" data-bs-toggle="tooltip" title=""><?php echo $BL['be_article_urlalias'] ?></button>
              <button type="button" id="parent_alias" class="btn btn-outline-light btn-alias-action" data-bs-toggle="tooltip" title=""><?php echo $BL['be_parental_alias'] ?></button>
              <button type="button" id="struct_alias" class="btn btn-outline-light btn-alias-action" data-bs-toggle="tooltip" title=""><?php echo $BL['be_admin_struct_title'] ?></button>
            
          </div>
        </div>
      </div>

    <?php if (count($phpwcms['allowed_lang']) > 1):  ?>
      <div class="form-group align-items-center row g-2">
          <span class="col-sm-2 col-form-label text-sm-end"><?php echo $BL['be_profile_label_lang'] ?></span>
          <div class="col-sm-10">
              <div class="input-group input-group-sm col-sm-10 px-0">
                  <select class="form-select form-select-sm" name="article_lang" id="article_lang" style="max-width: 200px;">
                      <option value=""<?php echo $article['article_lang'] === '' ? ' selected="selected"' : ''; ?>>
                          <?php echo get_language_name($phpwcms['default_lang']) . ' (' . $BL['be_admin_tmpl_default'] . ')'; ?>
                      </option>
                      <?php foreach ($phpwcms['allowed_lang'] as $key => $lang):
                          $lang = strtolower($lang);
                          if ($lang === $phpwcms['default_lang']) {
                              continue;
                          }
                          ?>
                          <option value="<?php echo $lang ?>"<?php echo $article['article_lang'] === $lang ? ' selected="selected"' : ''; ?>>
                              <?php echo get_language_name($lang); ?>
                          </option>
                      <?php endforeach; ?>
                  </select>

                  <div class="input-group-text lang-id-select-part border-start-0<?php if ($article['article_lang'] === ''): ?> text-muted<?php endif; ?>">
                      <input class="me-1" name="article_lang_type" id="article_lang_type_category" type="radio" value="category"<?php is_checked('category', $article['article_lang_type']); ?><?php if ($article['article_lang'] === ''): ?> disabled<?php endif; ?> />
                      <label class="form-check-label mb-0" for="article_lang_type_category">
                         <?php echo $BL['be_article_cat'] ?> ID
                      </label>
                  </div>
                  <div class="input-group-text lang-id-select-part<?php if ($article['article_lang'] === ''): ?> text-muted<?php endif; ?>">
                       <input class="me-1" name="article_lang_type" id="article_lang_type_article" type="radio" value="article"<?php is_checked('article', $article['article_lang_type']); ?><?php if ($article['article_lang'] === ''): ?> disabled<?php endif; ?> />
                        <label class="form-check-label text-nowrap mb-0" for="article_lang_type_article">
                          <?php echo $BL['be_cnt_articles'] ?> ID
                          <span class="flag-icon flag-icon-<?php echo $phpwcms['default_lang'] ?>" data-bs-toggle="tooltip" title="<?php echo get_language_name($phpwcms['default_lang']) . ' ('.$BL['be_admin_tmpl_default'].')' ?>"></span>
                        </label>
                   </div>
                   <input name="article_lang_id" type="number" id="article_lang_id" class="form-control form-control-sm" style="max-width: 100px;" value="<?php echo $article['article_lang_id'] ?: ''; ?>" maxlength="10" onfocus="this.blur()"<?php if ($article['article_lang'] === ''): ?> disabled<?php endif; ?> />
                   <button class="modalButton btn btn-sm btn-blue sitemap-open" type="button" id="article_lang_browser" data-bs-toggle="modal" data-bs-target="#browserModal" data-src="articlebrowser.php?opt=2<?php echo $article['article_lang_type'] === 'article' ? '&amp;idtype=article' : ($article['article_lang_type'] === 'category' ? '&amp;idtype=category' : '') ?>" title="<?php echo $BL['be_cnt_openarticlebrowser'] ?>"<?php if ($article['article_lang'] === ''): ?> disabled<?php endif; ?>><i class="fa-solid fa-sitemap fa-fw" aria-hidden="true"></i></button>
                   <script>
                   var $langIdInput = $('#article_lang_id');
                   $langIdInput.data('lang-type', $('input:radio[name="article_lang_type"]:checked').val() || '');
                   $('input:radio[name="article_lang_type"]').on('change', function() {
                       $('#article_lang_browser').attr('data-src', 'articlebrowser.php?opt=2&idtype=' + this.value);
                       // stash the ID of the previous type, restore the new type's ID if known
                       var prevType = $langIdInput.data('lang-type');
                       if (prevType && prevType !== this.value && $langIdInput.val() !== '') {
                           $langIdInput.data('stash-' + prevType, $langIdInput.val());
                       }
                       $langIdInput.val($langIdInput.data('stash-' + this.value) || '');
                       $langIdInput.data('lang-type', this.value);
                   });
                   </script>
               </div>
              <?php
              $article_lang_data = [];
              if ((int)$article['article_lang_id'] > 0 && $article['article_lang_type'] === 'article') {
                  $where = 'article_id = '.$article['article_lang_id'];
                  $adata = _dbGet('phpwcms_article', 'article_id, article_alias, article_title', $where, '', '', 1);
                  $article_lang_item = '<strong>' . $BL['be_cnt_target'] .'</strong> ID['.$adata[0]['article_id'].']: ';
                  if (is_array($adata)) {
                      $article_lang_item .= '<a href="phpwcms.php?&do=articles&p=2&s=1&id=' . $adata[0]['article_id'] . '" target="_blank" data-bs-toggle="tooltip" title="' . $adata[0]['article_title'] . '">' . $adata[0]['article_alias'] . $phpwcms['rewrite_ext'] .'</a>';
                  } else {
                      $article_lang_item .= $BL['be_admin_usr_err'];
                  }
                  $article_lang_data[] = $article_lang_item;

                  foreach ($phpwcms['allowed_lang'] as $key => $lang) {
                      $lang = strtolower($lang);
                      if ($lang === $phpwcms['default_lang'] || $lang === $article['article_lang']) {
                          continue;
                      }
                      $where = 'article_lang_id = '.$article['article_lang_id'].' AND article_lang LIKE '._dbEscape($lang);
                      $adata = _dbGet('phpwcms_article', 'article_id, article_alias, article_title', $where, '', '', 1);
                      if (isset($adata[0]['article_id'])) {
                          $article_lang_data[] = '<span class="flag-icon flag-icon-'.$lang.' mt-1" data-bs-toggle="tooltip" title="'. get_language_name($lang).'"></span> <a href="phpwcms.php?&do=articles&p=2&s=1&aktion=1&id=' . $adata[0]['article_id'] . '" target="_blank" data-bs-toggle="tooltip" title="' . $adata[0]['article_title'] . '">' . $adata[0]['article_alias'] . $phpwcms['rewrite_ext'] .'</a>';
                          unset($adata);
                      }
                  }
              } else {
                  foreach ($phpwcms['allowed_lang'] as $key => $lang) {
                      $lang = strtolower($lang);
                      if ($lang === $phpwcms['default_lang']) {
                          continue;
                      }
                      $where = 'article_lang_id = '.$article['article_id'].' AND article_lang LIKE '._dbEscape($lang);
                      $adata = _dbGet('phpwcms_article', 'article_id, article_alias, article_title', $where, '', '', 1);
                      if (isset($adata[0]['article_id'])) {
                          $article_lang_data[] = '<span class="flag-icon flag-icon-'.$lang.'" data-bs-toggle="tooltip" title="'. get_language_name($lang).'"></span> <a href="phpwcms.php?&do=articles&p=2&s=1&aktion=1&id=' . $adata[0]['article_id'] . '" target="_blank" data-bs-toggle="tooltip" title="' . $adata[0]['article_title'] . '">' . $adata[0]['article_alias'] . $phpwcms['rewrite_ext'] .'</a>';
                          unset($adata);
                      }
                  }
              }

              if (count($article_lang_data) > 0) {
                  echo '<div class="mt-1" id="article_lang_div">';
                  echo implode(', ', $article_lang_data);
                  echo '</div>';
              }
              ?>
          </div>
      </div>
    <?php endif; ?>

      <div class="form-group align-items-center row g-2">
        <label for="article_aliasid" class="col-sm-2 col-form-label text-sm-end"><?php echo $BL['be_alias_articleID'] ?></label>
        <div class="col-sm-10">
          <div class="input-group input-group-sm">
            <input name="article_aliasid" type="text" class="form-control" id="article_aliasid" value="<?php echo $article['article_aliasid'] ?: ''; ?>" size="11" maxlength="11" />
            <div class="input-group-text">
              <input class="me-1" name="article_headerdata" id="article_headerdata" type="checkbox" value="1" <?php is_checked($article['article_headerdata'], 1) ?> />
              <label class="form-check-label" for="article_headerdata"><?php echo $BL['be_alias_useAll'] ?></label>
            </div>
          </div>
        </div>
      </div>

      <div class="row mt-2">
        <div class="col-sm-12">
        <?php
        $wysiwyg_editor = [
        'value'   => $article['article_summary'],
        'field'   => 'article_summary',
        'height'  => '350px',
        'width'   => '100%',
        'rows'    => '10',
        'editor'  => $_SESSION['WYSIWYG_EDITOR'],
        'lang'    => 'en'
        ];
        include PHPWCMS_ROOT.'/include/inc_lib/wysiwyg.editor.inc.php';
        ?>
        </div>
      </div>
    </div>

    <!-- IMAGE TAB -->
    <div class="tab-pane fade" id="image-sect" role="tabpanel" aria-labelledby="image-tab">
      <div class="form-group align-items-center row g-2">
        <label for="cimage_name" class="col-sm-2 col-form-label text-sm-end"><?php echo $BL['be_article_forfull'] ?></label>
        <div class="col-sm-10">
          <div class="row g-2">
            <div class="col-12 col-lg-7 mb-2 mb-lg-0">
              <div class="input-group input-group-sm">
                
                <button class="modalButton btn btn-sm btn-blue folder-open" type="button" data-bs-toggle="modal" data-bs-target="#browserModal" data-src="filebrowser.php?opt=0&amp;target=summary" title="<?php echo $BL['be_cnt_openimagebrowser'] ?>"><i class="fa-solid fa-folder-open fa-fw" aria-hidden="true"></i></button>
                <input name="cimage_name" type="text" id="cimage_name" class="form-control form-control-sm" value="<?php echo html($article['image']['name']) ?>" onfocus="this.blur()" />
                <a href="#" id="cimage_delete_button" class="btn btn-sm btn-danger trash<?php echo empty($article['image']['id']) ? ' disabled' : '' ?>" role="button"<?php echo empty($article['image']['id']) ? ' aria-disabled="true"' : '' ?> data-bs-toggle="tooltip" title="<?php echo $BL['be_cnt_delimage'] ?>" onclick="if ($(this).hasClass('disabled')) return false; bsConfirmDanger('<?php echo js_singlequote($BL['be_image_delete_js']); ?>' + (document.article.cimage_name.value ? '\n[' + document.article.cimage_name.value + ']' : ''), function() { document.article.cimage_name.value='';document.article.cimage_id.value='0'; if (typeof onImageSelected === 'function') onImageSelected('_', '0', ''); }, '<?php echo js_singlequote($BL['be_yes']); ?>', '<?php echo js_singlequote($BL['be_no']); ?>'); this.blur();return false;"><i class="fa-solid fa-trash-alt fa-fw" aria-hidden="true"></i></a>
                
              </div>
            </div>
            <div class="col-12 col-lg-5">
              <div class="row g-2">
                <div class="col-6">
                  <div class="input-group input-group-sm">
                    
                      <span class="input-group-text"><?php echo $BL['be_cnt_maxw'] ?></span>
                    
                    <input name="cimage_width" type="text" class="form-control form-control-sm" id="cimage_width" size="4" maxlength="4" onkeyup="if(!parseInt(this.value,10)) this.value='';" value="<?php echo empty($article['image']['width']) ? $template_default['article']['image_default_width'] : $article['image']['width']; ?>" />
                  </div>
                </div>
                <div class="col-6">
                  <div class="input-group input-group-sm">
                    
                      <span class="input-group-text"><?php echo $BL['be_cnt_maxh'] ?></span>
                    
                    <input name="cimage_height" type="text" class="form-control form-control-sm" id="cimage_height" size="4" maxlength="4" onkeyup="if(!parseInt(this.value,10)) this.value='';" value="<?php echo empty($article['image']['height']) ? $template_default['article']['image_default_height'] : $article['image']['height']; ?>" />
                  </div>
                </div>
              </div>
            </div>
          </div>
          <input name="cimage_id" type="hidden" value="<?php echo $article['image']['id'] ?>" />
        </div>
      </div>
      <div class="form-group row g-2 align-items-center">
        <span class="col-sm-2 col-form-label text-sm-end"><?php echo $BL['be_cnt_several'] ?></span>
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

      <div class="form-group row g-2">
        <label for="cimage_caption" class="col-sm-2 col-form-label text-sm-end"><?php echo $BL['be_cnt_description'] ?></label>
        <div class="col">
          <textarea name="cimage_caption" cols="30" rows="3" class="form-control form-control-sm" id="cimage_caption"><?php echo html($article['image']['caption']) ?></textarea>
          <span class="col-sm-12 col-form-label ps-0">
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
        <div id="cimage_preview_container" class="col-sm-2 text-end">
        <?php
          $_SESSION['image_browser_article'] = 1;
          $thumb_image = false;
          if (!empty($article['image']['hash'])) {
              $thumb_image = get_cached_image([
            'target_ext' =>  $article['image']['ext'],
            'image_name' =>  $article['image']['hash'] . '.' . $article['image']['ext'],
            'thumb_name' =>  md5($article['image']['hash'].$phpwcms['img_list_width'].$phpwcms['img_list_height'].$phpwcms['sharpen_level'].$phpwcms['colorspace'])
              ]);
          }
          echo $thumb_image ? '<img src="'. $thumb_image['src'] .'" '.$thumb_image[3].' alt="" />' : '&nbsp;';
          ?>
        </div>
      </div>

      <hr>

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
      <div class="form-group row g-2">
        <div class="col-sm-10 offset-sm-2">
          <div class="form-check">
            <input class="form-check-input" name="cimage_usesummary" type="checkbox" id="cimage_usesummary" value="1" <?php is_checked(1, $article['image']['list_usesummary']); ?> />
            <label class="form-check-label" for="cimage_usesummary"><?php echo $BL['be_cnt_same_as_summary'] ?></label>
          </div>
        </div>
      </div>

      <div class="form-group align-items-center row g-2">
        <label for="cimage_list_name" class="col-sm-2 col-form-label text-sm-end"><?php echo $BL['be_article_forlist'] ?></label>
        <div class="col-sm-10">
          <div class="row g-2">
            <div class="col-12 col-lg-7 mb-2 mb-lg-0">
              <div class="input-group input-group-sm">
                
                <button class="modalButton btn btn-sm btn-blue folder-open" type="button" data-bs-toggle="modal" data-bs-target="#browserModal" data-src="filebrowser.php?opt=0&amp;target=list" title="<?php echo $BL['be_cnt_openimagebrowser'] ?>"><i class="fa-solid fa-folder-open fa-fw" aria-hidden="true"></i></button>
                <input name="cimage_list_name" type="text" id="cimage_list_name" class="form-control form-control-sm" value="<?php echo html($article['image']['list_name']) ?>" onfocus="this.blur()" />
                <a href="#" id="cimage_list_delete_button" class="btn btn-sm btn-danger trash<?php echo empty($article['image']['list_id']) ? ' disabled' : '' ?>" role="button"<?php echo empty($article['image']['list_id']) ? ' aria-disabled="true"' : '' ?> data-bs-toggle="tooltip" title="<?php echo $BL['be_cnt_delimage'] ?>" onclick="if ($(this).hasClass('disabled')) return false; bsConfirmDanger('<?php echo js_singlequote($BL['be_image_delete_js']); ?>' + (document.article.cimage_list_name.value ? '\n[' + document.article.cimage_list_name.value + ']' : ''), function() { document.article.cimage_list_name.value='';document.article.cimage_list_id.value='0'; if (typeof onImageSelected === 'function') onImageSelected('_list_', '0', ''); }, '<?php echo js_singlequote($BL['be_yes']); ?>', '<?php echo js_singlequote($BL['be_no']); ?>'); this.blur();return false;"><i class="fa-solid fa-trash-alt fa-fw" aria-hidden="true"></i></a>
                
              </div>
            </div>
            <div class="col-12 col-lg-5">
              <div class="row g-2">
                <div class="col-6">
                  <div class="input-group input-group-sm">
                    
                      <span class="input-group-text"><?php echo $BL['be_cnt_maxw'] ?></span>
                    
                    <input name="cimage_list_width" type="text" class="form-control form-control-sm" id="cimage_list_width" size="4" maxlength="4" onkeyup="if(!parseInt(this.value,10)) this.value='';" value="<?php echo empty($article['image']['list_width']) ? $template_default['article']['imagelist_default_width'] : $article['image']['list_width']; ?>" />
                  </div>
                </div>
                <div class="col-6">
                  <div class="input-group input-group-sm">
                    
                      <span class="input-group-text"><?php echo $BL['be_cnt_maxh'] ?></span>
                    
                    <input name="cimage_list_height" type="text" class="form-control form-control-sm" id="cimage_list_height" size="4" maxlength="4" onkeyup="if(!parseInt(this.value,10)) this.value='';" value="<?php echo empty($article['image']['list_height']) ? $template_default['article']['imagelist_default_height'] : $article['image']['list_height']; ?>" />
                  </div>
                </div>
              </div>
            </div>
          </div>
          <input name="cimage_list_id" type="hidden" value="<?php echo $article['image']['list_id'] ?>" />
        </div>
      </div>
      <div class="form-group row g-2 align-items-center">
        <span class="col-sm-2 col-form-label text-sm-end"><?php echo $BL['be_cnt_several'] ?></span>
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

      <div class="form-group row g-2">
        <label for="cimage_list_caption" class="col-sm-2 col-form-label text-sm-end"><?php echo $BL['be_cnt_description'] ?></label>
        <div class="col">
          <textarea name="cimage_list_caption" cols="30" rows="3" class="form-control form-control-sm" id="cimage_list_caption"><?php echo html($article['image']['list_caption']) ?></textarea>
          <span class="col col-form-label ps-0">
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
        <div id="cimage_list_preview_container" class="col-sm-2 text-end">
        <?php
        $_SESSION['image_browser_article'] = 1;
        $thumb_image = false;
        if (!empty($article['image']['list_hash'])) {
            $thumb_image = get_cached_image([
            'target_ext' =>  $article['image']['list_ext'],
            'image_name' =>  $article['image']['list_hash'] . '.' . $article['image']['list_ext'],
            'thumb_name' =>  md5($article['image']['list_hash'].$phpwcms['img_list_width'].$phpwcms['img_list_height'].$phpwcms['sharpen_level'].$phpwcms['colorspace'])
            ]);
        }
        echo $thumb_image ? '<img src="'. $thumb_image['src'] .'" '.$thumb_image[3].' alt="" />' : '&nbsp;';
        ?>
        </div>
      </div>
    </div>

    <!-- META DATA TAB -->
    <div class="tab-pane fade" id="meta-sect" role="tabpanel" aria-labelledby="meta-tab">
      <div class="form-group align-items-center row g-2">
        <label for="article_pagetitle" class="col-sm-2 col-form-label text-sm-end"><?php echo $BL['be_admin_page_pagetitle'] ?></label>
        <div class="col">
          <input name="article_pagetitle" type="text" id="article_pagetitle" class="form-control form-control-sm" value="<?php echo html($article['article_pagetitle']) ?>" size="40" maxlength="2000" />
        </div>
      </div>

      <div class="form-group align-items-center row g-2">
        <label for="article_menutitle" class="col-sm-2 col-form-label text-sm-end"><?php echo $BL['article_menu_title'] ?></label>
        <div class="col">
          <input name="article_menutitle" type="text" id="article_menutitle" class="form-control form-control-sm" value="<?php echo html($article['article_menutitle']) ?>" size="40" />
        </div>
      </div>

      <div class="form-group align-items-center row g-2">
        <label for="article_redirect" class="col-sm-2 col-form-label text-sm-end"><?php echo $BL['be_article_aredirect'] ?></label>
        <div class="col">
          <input name="article_redirect" type="text" id="article_redirect" class="form-control form-control-sm" value="<?php echo html($article['article_redirect']) ?>" size="40" />
        </div>
      </div>

      <div class="form-group align-items-center row g-2">
        <label for="article_canonical" class="col-sm-2 col-form-label text-sm-end"><?php echo $BL['be_canonical'] ?></label>
        <div class="col">
          <input name="article_canonical" type="text" id="article_canonical" class="form-control form-control-sm" value="<?php echo html($article['article_canonical']) ?>" size="40" maxlength="2000" />
        </div>
      </div>

      <div class="form-group align-items-center row g-2">
        <span class="col-sm-2 col-form-label text-sm-end"><?php echo $BL['be_article_akeywords'] ?> <i class="fas fa-info-circle text-blue" data-bs-toggle="tooltip" title="<?php echo $BL['be_input_text_tab'] ?>"></i></span>
        <div class="col">
          <input class="form-control form-control-sm" type="text" id="article_keyword_autosuggest" aria-label="<?php echo html_specialchars($BL['be_article_akeywords']) ?>" /><input type="hidden" name="article_keyword" id="article_keyword" value="<?php echo html($article['article_keyword']) ?>" />
        </div>
      </div>

      <div class="form-group row g-2">
        <label for="article_description" class="col-sm-2 col-form-label text-sm-end"><?php echo $BL['be_cnt_description'] ?></label>
        <div class="col">
          <textarea name="article_description" rows="4" class="form-control form-control-sm" id="article_description"><?php echo html($article['article_description']) ?></textarea>
        </div>
      </div>

      <hr>
      <div class="form-group align-items-center row g-2">
        <label for="article_username" class="col-sm-2 col-form-label text-sm-end"><?php echo $BL['be_article_username'] ?></label>
        <div class="col-sm-10">
          <input name="article_username" type="text" id="article_username" class="form-control form-control-sm" value="<?php echo html($article['article_username']) ?>" size="40" maxlength="200" />
        </div>
      </div>

      <hr>
      <div class="form-group align-items-center row g-2">
        <span class="col-sm-2 col-form-label text-sm-end"><?php echo $BL['be_robots'] ?></span>
        <div class="col">
          <div class="form-check">
            <input class="form-check-input" name="article_meta_noindex" type="checkbox" id="article_meta_noindex" value="1"<?php is_checked(1, $article['article_meta']['noindex']); ?> />
            <label class="form-check-label" for="article_meta_noindex"><?php echo $BL['be_robots_noindex'] ?></label>
          </div>
        </div>
        <div class="col">
          <div class="form-check">
            <input class="form-check-input" name="article_meta_nofollow" type="checkbox" id="article_meta_nofollow" value="1"<?php is_checked(1, $article['article_meta']['nofollow']); ?> />
            <label class="form-check-label" for="article_meta_nofollow"><?php echo $BL['be_robots_nofollow'] ?></label>
          </div>
        </div>
      </div>
      <hr>
    <?php if (!empty($article['article_created']) || isset($article['article_date'])) { ?>
      <div class="form-group align-items-center row g-2">
        <span class="col-sm-2 col-form-label text-sm-end"><?php echo $BL['be_article_created_at'] ?>: </span>
        <div class="col-sm-10 d-flex flex-wrap align-items-center">
          <?php if (!empty($article['article_created'])) { ?>
            <div class="my-1 me-4">
              <?php echo date($BL['be_longdatetime'], $article['article_created']); ?>
            </div>
          <?php } ?>
          <?php if (isset($article['article_date'])) { ?>
            <div class="my-1">
              <strong class="me-1"><?php echo $BL['be_article_updated_at'] ?>:</strong>
              <?php echo (empty($_POST['article_update']) || !intval($_POST['article_update'])) ? phpwcms_strtotime($article['article_date'], $BL['be_longdatetime'], '') : $BL['be_article_esnoupdate']; ?>
            </div>
          <?php } ?>
        </div>
      </div>
    <?php } ?>
    </div>

    <!-- SETTINGS TAB -->
    <div class="tab-pane fade" id="settings-sect" role="tabpanel" aria-labelledby="settings-tab">
      <div class="form-group align-items-center row g-2">
        <label for="article_aktiv" class="col-sm-2 col-form-label text-sm-end"><?php echo $BL['be_article_show'] ?></label>
        <div class="col-sm-10">
          <div class="form-check">
            <input class="form-check-input" name="article_aktiv" type="checkbox" id="article_aktiv" value="1"<?php is_checked(1, $article['article_aktiv']); ?> />
            <label class="form-check-label fw-bold" for="article_aktiv"><?php echo $BL['be_active'] ?></label>
          </div>
        </div>
      </div>

      <div class="form-group align-items-center row g-2">
        <div class="col-sm-10 offset-sm-2">
          <div class="d-flex flex-wrap flex-lg-nowrap align-items-center gap-2">
            <div id="article_begin" class="input-group input-group-sm" style="max-width: 280px;">
              <div class="input-group-text">
                <input name="set_begin" type="checkbox" id="set_begin" value="1"<?php is_checked(1, $set_begin) ?>>
              </div>
              <label class="input-group-text" for="article_begin_input"><?php echo $BL['be_msg_from'] ?></label>
              <input name="article_begin" type="text" id="article_begin_input" class="form-control form-control-sm datetimepicker-input" placeholder="<?php echo $BL['default_date_format'] . ' ' . $BL['default_time_format'] . ':SS'; ?>" value="<?php echo phpwcms_strtotime($article['article_begin'], $BL['be_longdatetime'], ''); ?>" autocomplete="off" >
              <span class="datepickerbutton input-group-text btn-blue" style="cursor:pointer;" onclick="document.getElementById('article_begin_input')._flatpickr&&document.getElementById('article_begin_input')._flatpickr.open();"><i class="far fa-calendar-alt fa-fw"></i></span>
            </div>
            <div id="article_end" class="input-group input-group-sm" style="max-width: 280px;">
              <div class="input-group-text">
                <input name="set_end" type="checkbox" id="set_end" value="1"<?php is_checked(1, $set_end) ?>>
              </div>
              <label class="input-group-text" for="article_end_input"><?php echo $BL['be_article_aend'] ?></label>
              <input name="article_end" type="text" id="article_end_input" class="form-control form-control-sm datetimepicker-input" placeholder="<?php echo $BL['default_date_format'] . ' ' . $BL['default_time_format'] . ':SS'; ?>" value="<?php echo phpwcms_strtotime($article['article_end'], $BL['be_longdatetime'], ''); ?>" autocomplete="off" >
              <span class="datepickerbutton input-group-text btn-blue" style="cursor:pointer;" onclick="document.getElementById('article_end_input')._flatpickr&&document.getElementById('article_end_input')._flatpickr.open();"><i class="far fa-calendar-alt fa-fw"></i></span>
            </div>
          </div>
        </div>
      </div>

      <script type="text/javascript">
          $(function () {
              var fpBegin = flatpickr('#article_begin_input', {
                  enableTime: true,
                  enableSeconds: true,
                  dateFormat: 'd.m.Y H:i:S',
                  time_24hr: true,
                  allowInput: true,
                  onChange: function(sel, str) {
                      document.article.set_begin.checked = str !== '';
                  }
              });
              $('#set_begin').on('change', function() {
                  if (this.checked) {
                      var d = '<?php echo phpwcms_strtotime($article['article_begin'], $BL['be_longdatetime'], '') ?>';
                      fpBegin.setDate(d || new Date(), true);
                  } else {
                      fpBegin.clear();
                  }
              });

              var fpEnd = flatpickr('#article_end_input', {
                  enableTime: true,
                  enableSeconds: true,
                  dateFormat: 'd.m.Y H:i:S',
                  time_24hr: true,
                  allowInput: true,
                  onChange: function(sel, str) {
                      document.article.set_end.checked = str !== '';
                  }
              });
              $('#set_end').on('change', function() {
                  if (this.checked) {
                      var d = '<?php echo phpwcms_strtotime($article['article_end'], $BL['be_longdatetime'], '') ?>';
                      fpEnd.setDate(d || new Date(), true);
                  } else {
                      fpEnd.clear();
                  }
              });
          });
      </script>

      <hr>

      <div class="form-group align-items-center row g-2">
        <label for="article_sort" class="col-sm-2 col-form-label text-sm-end"><?php echo $BL['be_cnt_sortvalue'] ?></label>
        <div class="col-sm-4">
          <input name="article_sort" type="number" id="article_sort" value="<?php echo empty($article['article_sort']) ? 0 : intval($article['article_sort']) ?>" class="form-control form-control-sm" maxlength="10" onkeyup="if(!parseInt(this.value,10))this.value='0';" />
        </div>

        <label for="article_priorize" class="col-sm-2 col-form-label text-sm-end"><?php echo $BL['be_priorize'] ?></label>
        <div class="col-sm-4">
          <select name="article_priorize" id="article_priorize" class="form-select form-select-sm">
            <?php
              for ($x=30; $x>=-30; $x--) {
                  echo '  <option value="'.$x.'"';
                  is_selected($x, $article['article_priorize']);
                  echo '>'. ($x==0 ? $BL['be_cnt_default'] : $x) .'</option>';
              }
            ?>
          </select>
        </div>
      </div>

      <hr>
      <div class="form-group align-items-center row g-2">
        <span class="col-sm-2 col-form-label text-sm-end"><?php echo $BL['be_admin_struct_template'] ?></span>
        <div class="col-sm-5 mb-1 mb-sm-0">
          <div class="input-group input-group-sm">
            
              <label class="input-group-text" for="article_tmpllist"><?php echo $BL['be_article_forlist'] ?></label>
            
            <select name="article_tmpllist" id="article_tmpllist" class="form-select form-select-sm">
              <?php
              // templates for article listing
              $tmpllist = get_tmpl_files(PHPWCMS_TEMPLATE.'inc_cntpart/articlesummary/list');
              if ($article['image']['tmpllist'] === 'default') {
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
        </div>

        <div class="col-sm-5">
          <div class="input-group input-group-sm">
            
              <label class="input-group-text" for="article_tmplfull"><?php echo $BL['be_article_forfull'] ?></label>
            
            <select name="article_tmplfull" id="article_tmplfull" class="form-select form-select-sm">
              <?php
              // templates for full article
              $tmpllist = get_tmpl_files(PHPWCMS_TEMPLATE.'inc_cntpart/articlesummary/article');
              if ($article['image']['tmplfull'] === 'default') {
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
      </div>

      <div class="form-group align-items-center row g-2">
        <label for="article_meta_class" class="col-sm-2 col-form-label text-sm-end"><?php echo $BL['be_cnt_css_class'] ?></label>
        <div class="col-sm-10">
          <input class="form-control form-control-sm" name="article_meta_class" type="text" id="article_meta_class" value="<?php echo html($article['article_meta']['class']) ?>" size="40" maxlength="255" />
        </div>
      </div>

      <hr>
      <div class="form-group align-items-center row g-2">
        <label for="article_listmaxwords" class="col-sm-2 col-form-label text-sm-end"><?php echo $BL['be_cnt_summary_label'] ?></label>
        <div class="col-sm-4">
          <div class="input-group input-group-sm">
              
                  <span class="input-group-text"><?php echo $BL['be_cnt_max_words'] ?></span>
              
            <input class="form-control" name="article_listmaxwords" type="number" id="article_listmaxwords" value="<?php echo empty($article['image']['list_maxwords']) ? '' : (int)$article['image']['list_maxwords'] ?>" size="10" maxlength="6" />
          </div>
        </div>
      </div>

      <div class="form-group row g-2">
        <div class="col-sm-10 offset-sm-2">
          <div class="row g-2">
            <div class="col-sm-6">
              <div class="form-check">
                  <input class="form-check-input" name="article_hidesummary" type="checkbox" id="article_hidesummary" value="1"<?php is_checked(1, $article['article_hidesummary']); ?> />
                  <label class="form-check-label align-items-center" for="article_hidesummary">
                  <?php echo $BL['be_article_nosummary'] ?>
                </label>
              </div>

              <div class="form-check">
                  <input class="form-check-input" name="article_morelink" type="checkbox" id="article_morelink" value="1"<?php is_checked(1, $article['article_morelink']); ?> />
                  <label class="form-check-label align-items-center" for="article_morelink">
                  <?php echo $BL['be_article_morelink'] ?>
                </label>
              </div>
            </div>
            <div class="col-sm-6">
              <div class="form-check">
                  <input class="form-check-input" name="article_noteaser" type="checkbox" id="article_noteaser" value="1"<?php is_checked(1, $article['article_noteaser']); ?> />
                  <label class="form-check-label align-items-center" for="article_noteaser">
                  <?php echo $BL['be_article_noteaser'] ?>
                </label>
              </div>

              <div class="form-check">
                  <input class="form-check-input" name="article_paginate" type="checkbox" id="article_paginate" value="1"<?php is_checked(1, $article['article_paginate']); ?> />
                  <label class="form-check-label align-items-center" for="article_paginate">
                  <?php echo $BL['be_cnt_pagination'] ?>
                </label>
              </div>
            </div>
          </div>
        </div>
      </div>

      <hr>

      <div class="form-group align-items-center row g-2">
        <label for="article_uid" class="col-sm-2 col-form-label text-sm-end"><?php echo $BL['be_article_articleowner'] ?></label>
        <div class="col-sm-10 d-flex align-items-center">
          <select name="article_uid" id="article_uid" class="form-select form-select-sm me-2" style="max-width: 250px;">
            <?php
            $u_sql = 'SELECT usr_id, usr_name, usr_login, usr_admin FROM ' .DB_PREPEND. 'phpwcms_user WHERE usr_aktiv=1 ORDER BY usr_admin DESC, usr_name';
            $u_result = _dbQuery($u_sql);
            if (isset($u_result[0]['usr_id'])) {
                foreach ($u_result as $u_row) {
                    echo '<option value="'.$u_row['usr_id'].'"';
                    if ($u_row['usr_id'] == $article['article_uid']) {
                        echo ' selected="selected"';
                    }
                    if ((int)$u_row['usr_admin']) {
                        echo ' class="option-admin"';
                    }
                    echo '>'.html($u_row['usr_name'] ?: $u_row['usr_login']).'</option>';
                }
            }
            ?>
          </select>
          <span class="small text-muted text-nowrap"><?php echo $BL['be_article_adminuser'] ?></span>
        </div>
      </div>

      <hr>

      <div class="form-group align-items-center row g-2">
        <label for="article_timeout" class="col-sm-2 col-form-label text-sm-end"><?php echo $BL['be_cache'] ?></label>
        <div class="col-sm-4">
          <div class="input-group input-group-sm">
            <div class="input-group-text">
              <input class="me-1" name="article_cacheoff" id="article_cacheoff" type="checkbox" value="1" <?php if ($article['article_timeout'] === '0') {
                    echo 'checked';
                } ?> />
              <label class="mb-0" for="article_cacheoff"><?php echo $BL['be_off'] ?></label>
            </div>
            <select name="article_timeout" id="article_timeout" class="form-select form-select-sm" onchange="document.article.article_cacheoff.checked=false;">
                <?php
                echo '<option value=" ">'.$BL['be_admin_tmpl_default']."</option>\n";
                echo '<option value="60"'.is_selected($article['article_timeout'], '60', 0, 0).'>&nbsp;&nbsp;1 '.$BL['be_date_minute']."</option>\n";
                echo '<option value="300"'.is_selected($article['article_timeout'], '300', 0, 0).'>&nbsp;&nbsp;5 '.$BL['be_date_minutes']."</option>\n";
                echo '<option value="900"'.is_selected($article['article_timeout'], '900', 0, 0).'>15 '.$BL['be_date_minutes']."</option>\n";
                echo '<option value="1800"'.is_selected($article['article_timeout'], '1800', 0, 0).'>30 '.$BL['be_date_minutes']."</option>\n";
                echo '<option value="3600"'.is_selected($article['article_timeout'], '3600', 0, 0).'>&nbsp;&nbsp;1 '.$BL['be_date_hour']."</option>\n";
                echo '<option value="14400"'.is_selected($article['article_timeout'], '14400', 0, 0).'>&nbsp;&nbsp;4 '.$BL['be_date_hours']."</option>\n";
                echo '<option value="43200"'.is_selected($article['article_timeout'], '43200', 0, 0).'>12 '.$BL['be_date_hours']."</option>\n";
                echo '<option value="86400"'.is_selected($article['article_timeout'], '86400', 0, 0).'>&nbsp;&nbsp;1 '.$BL['be_date_day']."</option>\n";
                echo '<option value="172800"'.is_selected($article['article_timeout'], '172800', 0, 0).'>&nbsp;&nbsp;2 '.$BL['be_date_days']."</option>\n";
                echo '<option value="604800"'.is_selected($article['article_timeout'], '604800', 0, 0).'>&nbsp;&nbsp;1 '.$BL['be_date_week']."</option>\n";
                echo '<option value="1209600"'.is_selected($article['article_timeout'], '1209600', 0, 0).'>&nbsp;&nbsp;2 '.$BL['be_date_weeks']."</option>\n";
                echo '<option value="2592000"'.is_selected($article['article_timeout'], '2592000', 0, 0).'>&nbsp;&nbsp;1 '.$BL['be_date_month']."</option>\n";
                ?>
            </select>
            
              <span class="input-group-text"><?php echo $BL['be_cache_timeout'] ?></span>
            
          </div>
        </div>
      </div>

      <hr>
      <div class="form-group row g-2">
        <span class="col-sm-2 col-form-label text-sm-end pt-0"><?php echo $BL['be_ftptakeover_status'] ?></span>
        <div class="col">
          <div class="row g-2">
            <div class="col-sm-6">
              <div class="form-check">
                <input class="form-check-input" name="article_nositemap" type="checkbox" id="article_nositemap" value="1"<?php is_checked(1, $article['article_nositemap']); ?> />
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
            </div>
            <div class="col-sm-6">
              <?php
    					// Opengraph fallback when creating a new article
    					if (!isset($_POST['article_title']) && empty($article['article_id']) && defined('ACAT_OPENGRAPH_STATUS') && ACAT_OPENGRAPH_STATUS === false) {
    							$article['article_opengraph'] = 0;
    					}
              ?>
              <div class="form-check">
                <input class="form-check-input"  name="article_opengraph" type="checkbox" id="article_opengraph" value="1" <?php is_checked(1, $article['article_opengraph']); ?> />
                <label class="form-check-label" for="article_opengraph"><?php echo $BL['be_opengraph_support'] ?></label>
              </div>

              <div class="form-check">
                <input class="form-check-input" name="article_archive" type="checkbox" id="article_archive" value="1" <?php is_checked(1, $article['article_archive_status']); ?> />
                <label class="form-check-label" for="article_archive"><?php echo $BL['be_show_archived'] ?></label>
              </div>
            </div>
          </div>
        </div>
      </div>


    </div>
  </div>

  <div class="form-group align-items-center row g-2">
    <div class="col-sm-2">
      <input name="article_update" type="hidden" id="article_update" value="1" />
    </div>
  </div>
</div>
</div>

<div class="form-group align-items-center mt-4">
  <button name="updatesubmit" type="submit" class="btn btn-sm btn-blue" value="1"><i class="fa-solid fa-rotate"></i> <?php echo $article['article_id'] ? $BL['be_article_cnt_button1'] : $BL['be_article_cnt_button2'] ?></button>
  <button name="Submit" type="submit" class="btn btn-sm btn-blue ms-1" value="<?php echo $BL['be_article_cnt_button3'] ?>"><i class="fa-solid fa-check"></i> <?php echo $BL['be_article_cnt_button3'] ?></button>
  <button name="donotsubmit" type="button" class="btn btn-sm btn-danger ms-3" onclick="return cancelEdit();"><i class="fa-solid fa-times"></i> <?php echo $BL['be_newsletter_button_cancel'] ?></button>
</div>

</form>

<script type="text/javascript">
var initialFormData = '';
$(function(){

  initTomSelectTagAutosuggest('#article_keyword_autosuggest', '#article_keyword', 'category');


  // Handle language switch change
  const langIdSelectParts = $('.lang-id-select-part');

  $('#article_lang').change(function(){
    const isDefault = $(this).val() === '';
    langIdSelectParts.find('input, button').prop('disabled', isDefault);
    $('#article_lang_id').prop('disabled', isDefault);
    $('#article_lang_id').siblings('button.modalButton').prop('disabled', isDefault);
    if (isDefault) {
      langIdSelectParts.addClass('text-muted');
      $('#article_lang_div').hide();
    } else {
      langIdSelectParts.removeClass('text-muted');
      $('#article_lang_div').show();
    }
  });

  function updateAliasTooltips() {
    var title = $.trim($('#article_title').val());

    // Generate base/standard alias
    var genAlias = create_alias(title);
    $('#btn_generate_alias').attr('title', genAlias).tooltip('dispose').tooltip();

    // Parental path alias
    var parentPath = '<?php echo $struct_parental; ?>';
    if (!parentPath) {
      parentPath = $('#article_cid option:selected').text();
    }
    if (parentPath.length) {
      parentPath = parentPath.replace(/^-+/gi, '').trim();
      if (title) {
        parentPath += '<?php if ($phpwcms['alias_allow_slash']): ?>/<?php else: ?>-<?php endif; ?>' + title;
      }
    } else {
      parentPath = title;
    }
    var parentVal = create_alias(parentPath);
    $('#parent_alias').data('alias', '<?php echo $struct_parental; ?>').attr('title', parentVal).tooltip('dispose').tooltip();

    // Structural path alias
    var structPath = '<?php echo $struct_alias; ?>';
    if (!structPath) {
      structPath = $('#article_cid option:selected').text();
    }
    if (structPath.length) {
      structPath = structPath.replace(/^-+/gi, '').trim();
      if (title) {
        structPath += '<?php if ($phpwcms['alias_allow_slash']): ?>/<?php else: ?>-<?php endif; ?>' + title;
      }
    } else {
      structPath = title;
    }
    var structVal = create_alias(structPath);
    $('#struct_alias').data('alias', '<?php echo $struct_alias; ?>').attr('title', structVal).tooltip('dispose').tooltip();
  }

  // Run on load and bind to inputs
  updateAliasTooltips();
  $('#article_title, #article_cid').on('input change', updateAliasTooltips);

  $('#struct_alias,#parent_alias').click(function() {
    var $btn = $(this);
    var struct = $btn.data('alias') || $('#article_cid option:selected').text();
    var title = $.trim($('#article_title').val());

    if(struct.length) {
      struct = struct.replace(/^-+/gi, '').trim();

      if(title) {
        struct += '<?php if ($phpwcms['alias_allow_slash']): ?>/<?php else: ?>-<?php endif; ?>'+title;
      }
    } else {
      struct = title;
    }

    $('#article_alias').val( create_alias(struct) ).trigger('change');
    $btn.tooltip('hide').blur();
  });

  $('#btn_generate_alias').click(function() {
    $(this).tooltip('hide').blur();
  });

  $('#cat-as-articletitle').click(function(evnt){
    evnt.preventDefault();
    var currentCat = $('#article_cid option:selected').text();
    if(currentCat) {
      $('#article_title').val(currentCat.replace(/^-+ /, '')).trigger('change');
      updateAliasTooltips();
    }
  });

  initialFormData = $('#article').serialize();
});

function cancelEdit() {
    if ($('#article').serialize() !== initialFormData) {
        bsConfirmWarning('<?php echo js_singlequote($BL['be_dialog_warn_nosave']); ?>', function() {
            document.location.href='phpwcms.php'+'?<?php echo CSRF_GET_TOKEN; ?>&do=articles<?php echo $article['article_id'] ? '&p=2&s=1&id='.$article['article_id'] : '' ?>';
        }, '<?php echo js_singlequote($BL['be_yes']); ?>', '<?php echo js_singlequote($BL['be_no']); ?>');
    } else {
        document.location.href='phpwcms.php'+'?<?php echo CSRF_GET_TOKEN; ?>&do=articles<?php echo $article['article_id'] ? '&p=2&s=1&id='.$article['article_id'] : '' ?>';
    }
    return false;
}

function onImageSelected(target, id, name) {
    var hasImage = (id && parseInt(id, 10) > 0);
    if (target === '_') {
        var container = $('#cimage_preview_container');
        if (container.length) {
            if (hasImage) {
                container.html('<img src="img/cmsimage.php/200x200/' + id + '" alt="" />');
            } else {
                container.html('&nbsp;');
            }
        }
        var btn = $('#cimage_delete_button');
        if (btn.length) {
            if (hasImage) {
                btn.removeClass('disabled').css({'opacity': '', 'pointer-events': ''});
            } else {
                btn.addClass('disabled').css({'opacity': '0.5', 'pointer-events': 'none'});
            }
        }
    } else if (target === '_list_') {
        var container = $('#cimage_list_preview_container');
        if (container.length) {
            if (hasImage) {
                container.html('<img src="img/cmsimage.php/200x200/' + id + '" alt="" />');
            } else {
                container.html('&nbsp;');
            }
        }
        var btn = $('#cimage_list_delete_button');
        if (btn.length) {
            if (hasImage) {
                btn.removeClass('disabled').css({'opacity': '', 'pointer-events': ''});
            } else {
                btn.addClass('disabled').css({'opacity': '0.5', 'pointer-events': 'none'});
            }
        }
    }
}

</script>
