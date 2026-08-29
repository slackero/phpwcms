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

// Article List
$buttonAction  = '<a class="btn btn-sm btn-blue me-2" href="phpwcms.php?do=articles" title="' . html($BL['be_article_cnt_center']) . '">';
$buttonAction .= '<i class="fa fa-list me-1"></i>' . $BL['be_article_cnt_center'] . '</a>';
// Article Preview (new window)
$buttonActionLink = rel_url(array('phpwcms-preview' => 1), array(), empty($article['article_alias']) ? 'aid=' . $article['article_id'] : $article['article_alias']);
$buttonAction .= '<a class="btn btn-sm btn-blue" href="' . html($buttonActionLink) . '" target="articlePreviewWindows" data-bs-toggle="tooltip" title="' . html($BL['be_func_struct_preview']) . '">';
$buttonAction .= '<i class="far fa-eye me-1"></i>' . $BL['be_func_struct_preview'] . '</a>';

?>
<script>
function initSortableList(el, listId) {
    if (!el) return;
    new Sortable(el, {
        handle: '.handle',
        animation: 150,
        ghostClass: 'sortable-ghost',
        chosenClass: 'sortable-chosen',
        dragClass: 'sortable-drag',
        scroll: true,
        scrollSensitivity: 60,
        scrollSpeed: 10,
        onEnd: function (evt) {
            var sort_order = '';
            var listEl = document.getElementById(listId);
            if (listEl) {
                var items = listEl.querySelectorAll('li[id]');
                items.forEach(function(li) {
                    sort_order += li.id + '|';
                });
            }
            $.ajax({
                url: 'include/inc_act/act_articlesort.php?<?php echo get_token_get_string(); ?>&sortid=' + sort_order,
                xhrFields: { withCredentials: true }
            });
        }
    });
}
$(function() {
    var mainList = document.getElementById('sortable-list-0');
    if (mainList) {
        initSortableList(mainList, 'sortable-list-0');
    }
});
</script>

<div class="d-flex align-items-center justify-content-between flex-wrap mb-3">
  <h1 class="mb-0"><?php echo $BL['be_func_struct_edit']; ?></h1>
  <div class="my-1">
    <?php echo $buttonAction; ?>
  </div>
</div>

<div class="card mb-3">
  <div class="card-header py-2 d-flex align-items-center justify-content-between flex-wrap">
    <h1 class="mb-0"><?php echo $BL['be_article_cnt_ltitle']; ?></h1>
  </div>
  <div class="card-body">
    <form action="phpwcms.php?do=articles&amp;p=2&amp;s=1&amp;aktion=2&amp;id=<?php echo $article['article_id']; ?>" method="post" name="addcontent" id="addcontent">
      <div class="row align-items-center mb-3">
        <div class="col">
          <div class="d-flex align-items-center flex-wrap">
            <h4 class="mb-0 fw-bold me-2">
              <a class="text-dark" href="phpwcms.php?do=articles&amp;p=2&amp;s=1&amp;aktion=1&amp;id=<?php echo $article['article_id']; ?>" title="<?php echo $BL['be_article_cnt_ledit']; ?>"><?php echo html($article['article_title']); ?></a>
            </h4>
            <span class="badge badge-secondary fw-normal">AID: <?php echo $article['article_id']; ?></span>
            <?php if(!empty($phpwcms['allowed_lang']) && count($phpwcms['allowed_lang']) > 0): ?>
              <span class="ms-2 flag-icon flag-icon-<?php echo ($lang = strtolower(empty($article['article_lang']) ? $phpwcms['default_lang'] : $article['article_lang'])); ?>" data-bs-toggle="tooltip" title="<?php echo get_language_name($lang); ?>"></span>
            <?php endif; ?>
          </div>
          <?php if(!empty($article['article_subtitle'])): ?>
            <div class="text-muted small mt-1"><?php echo html($article['article_subtitle']); ?></div>
          <?php endif; ?>
        </div>
        <div class="col-auto text-end">
          <div class="btn-group btn-group-sm" role="group" aria-label="article-hdr-actions">
            <a class="btn btn-sm btn-blue" role="button" aria-disabled="true" title="<?php echo $BL['be_article_cnt_ledit']; ?>" data-bs-toggle="tooltip" href="phpwcms.php?do=articles&amp;p=2&amp;s=1&amp;aktion=1&amp;id=<?php echo $article['article_id']; ?>"><i class="fa fa-pencil-alt"></i></a>
            <a id="abtnarticle<?php echo $article['article_id']; ?>" class="btn fa btn-sm visible <?php echo ($article['article_aktiv'] == 0 ? 'btn-warning' : 'btn-success'); ?>" data-id="<?php echo $article['article_id']; ?>" data-type="article" data-table="article" data-field="article_aktiv" data-fieldid="article_id" aria-disabled="true" data-bs-toggle="tooltip" title="<?php echo $BL['be_article_cnt_lvisible']; ?>"></a>
          </div>
          <a class="btn btn-sm btn-danger ms-1" role="button" aria-disabled="true" title="<?php echo $BL['be_article_cnt_ldel']; ?>" data-bs-toggle="tooltip" href="include/inc_act/act_articlecontent.php?do=<?php echo '1,' . $article['article_id']; ?>" data-confirm-danger="<?php echo html($BL['be_article_cnt_ldeljs'] . "\n[" . $article['article_title'] . ']'); ?>"><i class="far fa-trash-alt"></i></a>
        </div>
      </div>

      <?php if(!empty($article['article_summary'])): ?>
        <div class="alert alert-light border p-2 mb-3 text-muted small">
          <strong><?php echo $BL['be_article_asummary']; ?>:</strong>
          <?php echo html(getCleanSubString(strip_tags($article['article_summary']), 300, '&#8230;'), false); ?>
        </div>
      <?php endif; ?>

<!-- Top Metadata Start -->
      <div class="row">
        <div class="col-lg-6">
          <div class="table-responsive">
            <table class="table table-sm table-borderless mb-0">
              <tr>
                <td class="text-secondary text-nowrap fw-bold" style="width: 140px;"><?php echo $BL['be_article_urlalias']; ?>:</td>
                <td>
                  <?php if(!empty($article['article_alias'])): ?>
                    <code><?php echo html($article['article_alias']); ?></code>
                  <?php else: ?>
                    <span class="text-muted">–</span>
                  <?php endif; ?>
                </td>
              </tr>
              <tr>
                <td class="text-secondary text-nowrap fw-bold"><?php echo $BL['be_article_cat']; ?>:</td>
                <td>
                    <i class="fa fa-folder-open text-muted me-1"></i><?php echo html(!empty($article['article_cat_name']) ? $article['article_cat_name'] : $article['article_cat']); ?>
                    <span class="badge badge-secondary fw-normal badge-align badge-align-t3 ms-1">ID: <?php echo $article['article_catid']; ?></span>
                </td>
              </tr>
              <?php
              // Show connected language articles
              if(!empty($phpwcms['allowed_lang']) && count($phpwcms['allowed_lang']) > 0) {
                $langstr = '';
                if (intval($article['article_lang_id']) > 0 && $article['article_lang_type'] === 'article') {
                  $where = 'article_id = ' . intval($article['article_lang_id']);
                  $adata = _dbGet('phpwcms_article', 'article_id, article_alias, article_title', $where, '', '', 1);
                  if (is_array($adata) && isset($adata[0]['article_id'])) {
                    $langstr = '<span class="flag-icon flag-icon-de" data-bs-toggle="tooltip" title="' . get_language_name('de') . '"></span> <a href="phpwcms.php?&do=articles&p=2&s=1&id=' . $adata[0]['article_id'] . '" data-bs-toggle="tooltip" title="' . html($adata[0]['article_title']) . '">' . html($adata[0]['article_alias']) . $phpwcms['rewrite_ext'] . '</a><br>';
                  } else {
                    $langstr = $BL['be_admin_usr_err'];
                  }
                  foreach($phpwcms['allowed_lang'] as $key => $lang) {
                    $lang = strtolower($lang);
                    if($lang === $phpwcms['default_lang'] || $lang === $article['article_lang']) {
                      continue;
                    }
                    $where = 'article_lang_id = ' . intval($article['article_lang_id']) . ' AND article_lang LIKE ' . _dbEscape($lang);
                    $adata = _dbGet('phpwcms_article', 'article_id, article_alias, article_title', $where, '', '', 1);
                    if (isset($adata[0]['article_id'])) {
                      $langstr .= '<span class="flag-icon flag-icon-' . $lang . '" data-bs-toggle="tooltip" title="' . get_language_name($lang) . '"></span> <a href="phpwcms.php?&do=articles&p=2&s=1&id=' . $adata[0]['article_id'] . '" target="_blank" data-bs-toggle="tooltip" title="' . html($adata[0]['article_title']) . '">' . html($adata[0]['article_alias']) . $phpwcms['rewrite_ext'] . '</a><br>';
                      unset($adata);
                    }
                  }
                } else {
                  foreach($phpwcms['allowed_lang'] as $key => $lang) {
                    $lang = strtolower($lang);
                    if($lang === $phpwcms['default_lang']) {
                      continue;
                    }
                    $where = 'article_lang_id = ' . intval($article['article_id']) . ' AND article_lang LIKE ' . _dbEscape($lang);
                    $adata = _dbGet('phpwcms_article', 'article_id, article_alias, article_title', $where, '', '', 1);
                    if (isset($adata[0]['article_id'])) {
                      $langstr .= '<span class="flag-icon flag-icon-' . $lang . '" data-bs-toggle="tooltip" title="' . get_language_name($lang) . '"></span> <a href="phpwcms.php?&do=articles&p=2&s=1&id=' . $adata[0]['article_id'] . '" target="_blank" data-bs-toggle="tooltip" title="' . html($adata[0]['article_title']) . '">' . html($adata[0]['article_alias']) . $phpwcms['rewrite_ext'] . '</a><br>';
                      unset($adata);
                    }
                  }
                }
                if ($langstr !== '') {
                  echo '<tr><td class="text-secondary text-nowrap fw-bold">' . $BL['be_article_opposite_lang'] . ':</td><td>' . $langstr . '</td></tr>';
                }
              }
              ?>
              <tr>
                <td class="text-secondary text-nowrap fw-bold"><?php echo $BL['be_article_akeywords']; ?>:</td>
                <td><?php
                if (!empty($article['article_keyword'])) {
                    $keywords = convertStringToArray($article['article_keyword'], ',');
                    foreach ($keywords as $keyword) {
                        echo '<span class="badge badge-light border fw-normal me-1">' . html($keyword) . '</span>';
                    }
                } else {
                    echo '<span class="text-muted">–</span>';
                }
                ?></td>
              </tr>
              <?php if(!empty($article['article_canonical'])): ?>
                <tr>
                  <td class="text-secondary text-nowrap fw-bold"><?php echo $BL['be_canonical']; ?>:</td>
                  <td><code><?php echo html($article['article_canonical']); ?></code></td>
                </tr>
              <?php endif; ?>
              <?php if(!empty($article['article_redirect'])): ?>
                <tr>
                  <td class="text-secondary text-nowrap fw-bold"><?php echo $BL['be_article_cnt_redirect']; ?>:</td>
                  <td><a href="<?php echo html($article['article_redirect']); ?>" target="_blank"><?php echo html($article['article_redirect']); ?></a></td>
                </tr>
              <?php endif; ?>
              <?php
              $thumb_image = false;
              if(!empty($article['image']['hash'])) {
                  $thumb_image = get_cached_image(array(
                      'target_ext' => $article['image']['ext'],
                      'image_name' => $article['image']['hash'] . '.' . $article['image']['ext'],
                      'thumb_name' => md5($article['image']['hash'] . $phpwcms['img_list_width'] . $phpwcms['img_list_height'] . $phpwcms['sharpen_level'] . $phpwcms['colorspace'])
                  ));
              }

              $thumb_list_image = false;
              if(!empty($article['image']['list_hash'])) {
                  $thumb_list_image = get_cached_image(array(
                      'target_ext' => $article['image']['list_ext'],
                      'image_name' => $article['image']['list_hash'] . '.' . $article['image']['list_ext'],
                      'thumb_name' => md5($article['image']['list_hash'] . $phpwcms['img_list_width'] . $phpwcms['img_list_height'] . $phpwcms['sharpen_level'] . $phpwcms['colorspace'])
                  ));
              }

              if($thumb_image || $thumb_list_image): ?>
                <tr>
                  <td class="text-secondary text-nowrap fw-bold"><?php echo $BL['be_cnt_image']; ?>:</td>
                  <td>
                    <?php
                    if($thumb_image) {
                        echo '<img class="img-thumbnail article-thumb me-1" src="' . $thumb_image['src'] . '" alt="" />';
                    }
                    if($thumb_list_image) {
                        echo '<img class="img-thumbnail article-thumb' . (!empty($article['image']['list_usesummary']) ? ' opacity-50' : '') . '" src="' . $thumb_list_image['src'] . '" alt="" />';
                    }
                    ?>
                  </td>
                </tr>
              <?php endif; ?>
              <tr>
                <td class="text-secondary text-nowrap fw-bold"><?php echo $BL['be_ftptakeover_status']; ?>:</td>
                <td>
                  <div class="d-flex flex-wrap" style="gap: 4px;">
                    <span class="badge <?php echo empty($article['article_nositemap']) ? 'badge-success' : 'badge-secondary'; ?>"><?php echo $BL['be_ctype_sitemap']; ?></span>
                    <span class="badge <?php echo empty($article['article_nosearch']) ? 'badge-success' : 'badge-secondary'; ?>"><?php echo $BL['be_fsearch_searchlabel']; ?></span>
                    <span class="badge <?php echo empty($article['article_norss']) ? 'badge-success' : 'badge-secondary'; ?>"><?php echo $BL['be_no_rss']; ?></span>
                    <?php if(!empty($article['article_opengraph'])): ?>
                      <span class="badge badge-info"><?php echo $BL['be_opengraph_support']; ?></span>
                    <?php endif; ?>
                    <?php if(!empty($article['article_archive_status'])): ?>
                      <span class="badge badge-warning"><?php echo $BL['be_show_archived']; ?></span>
                    <?php endif; ?>
                    <?php if(!empty($article['article_meta']['noindex'])): ?>
                      <span class="badge badge-danger"><?php echo $BL['be_robots_noindex']; ?></span>
                    <?php endif; ?>
                    <?php if(!empty($article['article_meta']['nofollow'])): ?>
                      <span class="badge badge-danger"><?php echo $BL['be_robots_nofollow']; ?></span>
                    <?php endif; ?>
                  </div>
                </td>
              </tr>
            </table>
          </div>
        </div>

        <div class="col-lg-6">
          <div class="table-responsive">
            <table class="table table-sm table-borderless mb-0">
              <tr>
                <td class="text-secondary text-nowrap fw-bold" style="width: 140px;"><?php echo $BL['be_article_username']; ?>:</td>
                <td><i class="fa fa-user text-muted me-1"></i><?php echo html($article['article_username']); ?></td>
              </tr>
              <tr>
                <td class="text-secondary text-nowrap fw-bold"><?php echo $BL['be_article_created_at']; ?>:</td>
                <td><i class="fa fa-calendar-plus text-muted me-1"></i><?php echo date($BL['be_longdatetime'], $article['article_created']); ?></td>
              </tr>
              <tr>
                <td class="text-secondary text-nowrap fw-bold"><?php echo $BL['be_article_updated_at']; ?>:</td>
                <td><i class="fa fa-history text-muted me-1"></i><?php echo phpwcms_strtotime($article['article_date'], $BL['be_longdatetime'], ''); ?></td>
              </tr>
              <tr>
                <td class="text-secondary text-nowrap fw-bold"><?php echo $BL['be_article_cnt_start']; ?>:</td>
                <td><?php echo $set_begin ? '<i class="fa fa-clock text-muted me-1"></i>' . phpwcms_strtotime($article['article_begin'], $BL['be_longdatetime'], '') : '<span class="text-muted">' . $BL['be_not_set'] . '</span>'; ?></td>
              </tr>
              <tr>
                <td class="text-secondary text-nowrap fw-bold"><?php echo $BL['be_article_cnt_end']; ?>:</td>
                <td><?php echo $set_end ? '<i class="fa fa-clock text-muted me-1"></i>' . phpwcms_strtotime($article['article_end'], $BL['be_longdatetime'], '') : '<span class="text-muted">' . $BL['be_not_set'] . '</span>'; ?></td>
              </tr>
              <tr>
                <td class="text-secondary text-nowrap fw-bold"><?php echo $BL['be_cnt_sortvalue']; ?>:</td>
                <td><span class="badge badge-light border"><?php echo (int) $article['article_sort']; ?></span></td>
              </tr>
              <tr>
                <td class="text-secondary text-nowrap fw-bold"><?php echo $BL['be_priorize']; ?>:</td>
                <td><span class="badge badge-light border"><?php echo (int) $article['article_priorize']; ?></span></td>
              </tr>
            </table>
          </div>
        </div>
      </div>
<!-- Top Metadata End -->

<!-- New Content start-->
      <div class="card-header border-0 mt-3 pt-3 border-top">
        <div class="row g-2 align-items-center">
          <div class="col-sm-auto">
            <select name="ctype" class="form-select form-select-sm" id="ctype" onchange="this.form.submit();">
              <?php

              $temp_count = 0;
              $user_selected_cp = isset($_SESSION["wcs_user_cp"]) && count($_SESSION["wcs_user_cp"]);

              if(is_array($article["article_cntpart"]) && count($article["article_cntpart"])) {

                // list all content parts usable for this article category
                foreach($article["article_cntpart"] as $value) {

                    if($user_selected_cp && !isset($_SESSION["wcs_user_cp"][$value])) {
                        continue;
                    }

                    if(isset($wcs_content_type[$value])) {

                        echo getContentPartOptionTag($value, $wcs_content_type[$value], $article['article_cpdefault']);
                        $temp_count++;

                    }
                    $value1 = $value * (-1);
                    if(isset($BL['be_admin_optgroup_label'][$value1]) && $value) {
                        echo '<optgroup label="[ '.$BL['be_admin_optgroup_label'][$value1].' ]" class="cntOptGroup"></optgroup>'.LF;
                    }
                }

              }
              if(!$temp_count) {
                  //list all available content parts
                  foreach($wcs_content_type as $key => $value) {

                      if($user_selected_cp && !isset($_SESSION["wcs_user_cp"][$key])) {
                          continue;
                      }
                      echo getContentPartOptionTag($key, $value, $article['article_cpdefault']);
                  }
              }
              ?>
            </select>
          </div>
          <div class="col">
            <button type="submit" class="btn btn-sm btn-blue" value="<?php echo  $BL['be_article_cnt_add'] ?>">
                <i class="fa fa-plus" aria-hidden="true"></i>
                <span class="d-none d-sm-inline"><?php echo  $BL['be_article_cnt_addtitle'] ?></span>
            </button>
          </div>
        </div>
      </div>
<!-- New Content end-->
          <?php
            // Sorting
            $sc = 0;
            $scc = 0;

            $sql  = "SELECT acontent_id, acontent_sorting, acontent_trash, acontent_block FROM ".DB_PREPEND."phpwcms_articlecontent ";
            $sql .= "WHERE acontent_aid=".$article["article_id"]." ORDER BY acontent_block, acontent_sorting, acontent_id";
            $result = _dbQuery($sql);

            if(isset($result[0]['acontent_id'])) {
                foreach($result as $row) {
                    $scc++;
                    if($row['acontent_trash'] == 0) {
                        $sc++;
                        $sbutton[$sc]["id"]    = $row['acontent_id'];
                        $sbutton[$sc]["sort"]  = $row['acontent_sorting'];
                        $sbutton[$sc]["block"] = $row['acontent_block'];
                    }
                }
            }
            if($sc) {
                  //Jetzt aufbauen der Sortieranweisung
                  foreach($sbutton as $key => $value) {
                      if($key == 1) {
                          // if 1st content part in list
                          $sbutton[$key]["top"] = '<button class="btn btn-xs btn-light py-0 px-1" disabled><i class="fa fa-chevron-up fa-fw text-muted" aria-hidden="true"></i></button>';
                      } elseif(isset($sbutton[$key-1]["block"]) && $sbutton[$key-1]["block"] != $sbutton[$key]["block"]) {
                          // if this content part is selected for different block than previous
                          $sbutton[$key]["top"] = '<button class="btn btn-xs btn-light py-0 px-1" disabled><i class="fa fa-chevron-up fa-fw text-muted" aria-hidden="true"></i></button>';
                      } else {
                          $sbutton[$key]["top"] = "<a class=\"btn btn-xs btn-light py-0 px-1\" href=\"include/inc_act/act_articlecontent.php?sort=".
                          $sbutton[$key]["id"].":".$sbutton[$key-1]["sort"]."|".
                          $sbutton[$key-1]["id"].":".$sbutton[$key]["sort"].
                          "\" data-bs-toggle=\"tooltip\" title=\"".$BL['be_article_cnt_up']."\"><i class=\"fa fa-chevron-up fa-fw text-dark\" aria-hidden=\"true\"></i></a>";
                      }
                      if($key == $sc) {
                          // if this is the last content part in list
                          $sbutton[$key]["bottom"] = '<button class="btn btn-xs btn-light py-0 px-1" disabled><i class="fa fa-chevron-down fa-fw text-muted" aria-hidden="true"></i></button>';
                      } elseif(isset($sbutton[$key+1]["block"]) && $sbutton[$key+1]["block"] != $sbutton[$key]["block"]) {
                          // if this is the last content part in current block and next is different
                          $sbutton[$key]["bottom"] = '<button class="btn btn-xs btn-light py-0 px-1" disabled><i class="fa fa-chevron-down fa-fw text-muted" aria-hidden="true"></i></button>';
                      } else {
                          $sbutton[$key]["bottom"] = "<a class=\"btn btn-xs btn-light py-0 px-1\" href=\"include/inc_act/act_articlecontent.php?sort=".
                          $sbutton[$key]["id"].":".$sbutton[$key+1]["sort"]."|".
                          $sbutton[$key+1]["id"].":".$sbutton[$key]["sort"].
                          "\" data-bs-toggle=\"tooltip\" title=\"".$BL['be_article_cnt_down']."\"><i class=\"fa fa-chevron-down fa-fw text-dark\" aria-hidden=\"true\"></i></a>";
                      }
                      $sbutton_string[$sbutton[$key]["id"]] = '<div class="btn-group" role="group">' . $sbutton[$key]["top"] . $sbutton[$key]["bottom"] . '</div>';
              }
              unset($sbutton);
            }

            //Listing zugehöriger Artikel Content Teile
            $sortierwert      = 1;
            $contentpart_block    = ' ';
            $contentpart_block_name = '';
            $contentpart_block_old = '';
            $listingflag = 0;
            $contentpart_tab    = '';
            $contentpart_tab_close  = '';

            $sql = "SELECT *, UNIX_TIMESTAMP(acontent_tstamp) as acontent_date FROM ".DB_PREPEND."phpwcms_articlecontent ".
                "WHERE acontent_aid=".$article["article_id"]." AND acontent_trash=0 ".
                "ORDER BY acontent_block, acontent_sorting, acontent_tab, acontent_id";

            $result = _dbQuery($sql);
            if(isset($result[0]['acontent_id'])) {
              foreach($result as $row) {

                // if type of content part not enabled available
                if(!isset($wcs_content_type[ $row["acontent_type"] ]) || ($row["acontent_type"] == 30 && !isset($phpwcms['modules'][$row["acontent_module"]]))) {
                  continue;
                }

                if($contentpart_tab_close) {
                  echo $contentpart_tab_close;
                  $contentpart_tab_close = '';
                }

                // now show current block name
                if($contentpart_block != $row['acontent_block']) {
                  if($contentpart_block_old == "") {
                    $contentpart_block_old = $row['acontent_block'];
                  }
                  $contentpart_block_old = $contentpart_block;
                  $contentpart_block = $row['acontent_block'];
                  $contentpart_block_name = html(' {'.$row['acontent_block'].'}');
                  $contentpart_block_class = 'cp-block-default';

                  switch($contentpart_block) {
                    case '':
                    case 'CONTENT':
                      $contentpart_block_name = $BL['be_main_content'].$contentpart_block_name;
                      if($article['article_paginate']) {
                        $contentpart_block_name .= ' / <i class="fas fa-indent text-muted me-1"></i>';
                        $contentpart_block_name .= $BL['be_cnt_pagination'];
                      }
                      $contentpart_block_class = 'cp-block-content';
                      break;

                    case 'LEFT':
                      $contentpart_block_name = $BL['be_cnt_left'].$contentpart_block_name;
                      $contentpart_block_class = 'cp-block-left';
                      break;

                    case 'RIGHT':
                      $contentpart_block_name = $BL['be_cnt_right'].$contentpart_block_name;
                      $contentpart_block_class = 'cp-block-right';
                      break;

                    case 'HEADER':
                      $contentpart_block_name = $BL['be_admin_page_header'].$contentpart_block_name;
                      $contentpart_block_class = 'cp-block-header';
                      break;

                    case 'FOOTER':
                      $contentpart_block_name = $BL['be_admin_page_footer'].$contentpart_block_name;
                      $contentpart_block_class = 'cp-block-footer';
                      break;

                    case 'CPSET':
                      $contentpart_block_name = $BL['be_settings'].' <span class="fw-normal">('.$BL['be_system_container_norender'].')</span>';
                      $contentpart_block_class = 'cp-block-cpset';
                      break;

                    case 'SYSTEM':
                      $contentpart_block_name = $BL['be_system_container'].' <span class="fw-normal">('.$BL['be_system_container_norender'].')</span>';
                      $contentpart_block_class = 'cp-block-system';
                      break;
                  }
                  if ($listingflag > 0) {
                    echo '</ul></div>';
                  }
          ?>
      <div class="card articlelist rounded-0 my-3">
        <div class="card-header border-0 py-1 cp-block-hdr <?php echo $contentpart_block_class ?>">
          <span class="fw-bold"><i class="fa fa-<?php echo $contentpart_block === 'CPSET' ? 'list-ul' : 'columns' ?>" aria-hidden="true"></i> <?php echo $contentpart_block_name ?></span>
        </div>
    <?php
    if ($listingflag == 0) {
      echo '<ul id="sortable-list-'. $listingflag .'" class="list-group list-group-flush dropable-list ps-0">';
    } else {
      echo '<script>
      $(function() {
        var el = document.getElementById("sortable-list-' . $listingflag . '");
        if (el) { initSortableList(el, "sortable-list-' . $listingflag . '"); }
      });
      </script>';
      echo '<ul id="sortable-list-'. $listingflag .'" class="list-group list-group-flush dropable-list ps-0 '. $listingflag .'">';
    }
      $listingflag = $listingflag+1;
    }

    // now check if content part is tabbed
    if($row['acontent_tab'] && $contentpart_tab != $row['acontent_tab']) {
      $contentpart_tab = $row['acontent_tab'];
      $contentpart_tabbed = explode('_', $contentpart_tab, 2);
      $contentpart_tab_title = isset($contentpart_tabbed[1]) ? trim($contentpart_tabbed[1]) : '';
      $contentpart_tab_number = explode('|', $contentpart_tabbed[0]);
      $contentpart_tab_type = empty($contentpart_tab_number[1]) ? 1 : $contentpart_tab_number[1];
      $contentpart_tab_number = intval($contentpart_tab_number[0]);

      ?>
      <div class="cp-block-subhdr <?php echo $contentpart_block_class ?>"><div class="ps-3 py-1"><small>{<?php
            if($contentpart_tab_type == 2) {
              echo $BL['be_ctype_accordion'];
            } elseif(isset($template_default['attributes']['cpgroup_custom'][$contentpart_tab_type])) {
              echo html($template_default['attributes']['cpgroup_custom'][$contentpart_tab_type]['title']);
            } else {
              echo $BL['be_ctype_tabs'];
            }
            echo ' / ' . $BL['be_cnt_paginate_subsection'] . ': ';
              if($contentpart_tab_title !== '') {
                  echo html($contentpart_tab_title) . ' ';
              }
              echo '[' . $contentpart_tab_number . ']';

      ?>}</small></div>
<?php
    } elseif($contentpart_tab && empty($row['acontent_tab'])) {
      // not the same tab but following cp is not tabbed
      $contentpart_tab = '';
    }
?>

  <li class="rounded-0 list-group-item" id="<?php echo $row["acontent_id"]; ?>">
    <div class="row">
      <div class="col-sm-auto align-self-center">
        <span data-bs-toggle="tooltip" title="<?php echo $BL['be_func_struct_sort_up'].' / '.$BL['be_func_struct_sort_down'] ?>" class="handle text-muted"><i class="fa fa-grip-vertical"></i></span>
      </div>
      <div class="col">
        <div class="row">
          <div class="col small fw-bold text-uppercase"><?php
            $cntpart_title = $wcs_content_type[$row["acontent_type"]];
            if(!empty($row["acontent_module"])) {
              if($row["acontent_type"] == 30 && isset($BL['modules'][$row["acontent_module"]]['listing_title'])) {
                $cntpart_title .= ': '.$BL['modules'][$row["acontent_module"]]['listing_title'];
              } elseif($row["acontent_type"] == 60 && function_exists('get_custom_contentpart_title')) {
                $cntpart_title .= ': '.get_custom_contentpart_title($row["acontent_module"]);
              }
            }
            echo $cntpart_title;
           ?>
          </div>

          <?php if($row["acontent_block"] === 'SYSTEM') {
          echo '<div class="col">';
          echo '<span class="greyed">', $BL['be_article_rendering'], ':</span> <span class="tool-title">';
          if(empty($row["acontent_tid"])) {
            echo $BL['be_custom_scriptlogic'];
          } elseif($row["acontent_tid"] == 3) {
            echo $BL['be_article_forlist'].' + '.$BL['be_article_forfull'];
          } elseif($row["acontent_tid"] == 2) {
            echo $BL['be_article_forfull'];
          } else { // == 1
            echo $BL['be_article_forlist'];
          }
          echo '</span>';
          echo '</div>';
        }
        ?>
          <div class="col-sm-auto align-self-center justify-content-end">
          <?php
          //Anzeigen der Space Before/After Info
          if(intval($row['acontent_before'])) {
            echo '<small><span class="mx-2"><i class="fa fa-arrow-up" aria-hidden="true"></i> ' . $row['acontent_before'];
            echo '</span></small>';
          }
          if(intval($row['acontent_after'])) {
            echo '<small><span class="mx-2"><i class="fa fa-arrow-down" aria-hidden="true"></i> ' . $row['acontent_after'];
            echo '</span></small>';
          }
          if($row['acontent_top']) {
            echo '<small><i class="far fa-caret-square-up fa-fw mx-1" aria-hidden="true" data-bs-toggle="tooltip" title="TOP"></i></small>';
          }
          if($row['acontent_anchor']) {
            echo '<small><i class="fa fa-anchor fa-fw mx-1" aria-hidden="true" data-bs-toggle="tooltip" title="Anchor"></i></small>';
          }
          ?>
          </div>

          <div class="col-sm-auto align-self-center justify-content-end text-nowrap">
            <?php echo $sbutton_string[$row['acontent_id']]; ?>
          </div>

          <div class="col-sm-auto align-self-center justify-content-end">
            <span class="badge badge-secondary fw-normal badge-align">ID: <?php echo $row['acontent_id']; ?></span>
          </div>

          <div class="col-sm-auto align-self-center justify-content-end text-nowrap">
              <?php
                  echo date($BL['be_shortdatetime'], $row['acontent_date']) . '&nbsp;';
                  if($contentpart_block !== 'CPSET' && $article['article_paginate']) {
                      //Display cp paginate page number
                      echo '<i class="fas fa-indent text-muted" data-bs-toggle="tooltip" title="subsection"></i>';
                      echo $row['acontent_paginate_page'] == 0 ? 1 : $row['acontent_paginate_page'];
                  }
              ?>
          </div>

          <div class="col-sm-auto align-self-center justify-content-end">
            <div class="btn-group btn-group-sm" role="group" aria-label="cp-actions-<?php echo $row['acontent_id']; ?>">
              <a class="btn btn-sm btn-blue" role="button" aria-disabled="true" data-bs-toggle="tooltip" title="<?php echo $BL['be_article_cnt_edit']; ?>" href="phpwcms.php?do=articles&amp;p=2&amp;s=1&amp;aktion=2&amp;id=<?php echo $article['article_id'] . '&amp;acid=' . $row['acontent_id']; ?>">
                <i class="fa fa-pencil-alt"></i>
              </a>
              <?php
              // duplicate content part
              echo '<a href="include/inc_act/act_structure.php?do=8%7C' . $row['acontent_id'] . '%7C' . $article['article_id'] . '%7C' . ($row['acontent_sorting'] + 5) . '" class="btn btn-sm btn-blue" role="button" aria-disabled="true" title="' . $BL['be_func_content_copy'] . ' [ID:' . $row['acontent_id'] . ']" data-bs-toggle="tooltip" data-confirm-type="info" data-confirm-action="' . html($BL['be_func_content_copy']) . '" data-confirm="' . html($BL['be_func_content_copy'] . ': ' . $cntpart_title . ' [ID:' . $row['acontent_id'] . ']') . '"><i class="fa fa-copy"></i></a>';

              echo '<a id="abtnacontent' . $row['acontent_id'] . '" class="btn fa btn-sm visible ' . ($row['acontent_visible'] == 0 ? 'btn-warning' : 'btn-success') . '" data-id="' . $row['acontent_id'] . '" data-type="acontent" data-table="articlecontent" data-field="acontent_visible" data-fieldid="acontent_id" aria-disabled="true" data-bs-toggle="tooltip" title="aktivieren/deaktivieren"></a>';
              ?>
            </div>
            <a class="btn btn-sm btn-danger ms-1" role="button" aria-disabled="true" title="<?php echo $BL['be_article_cnt_ldel']; ?>" data-bs-toggle="tooltip" href="include/inc_act/act_articlecontent.php?do=<?php echo '9,' . $article['article_id'] . ',' . $row['acontent_id']; ?>" data-confirm-danger="<?php echo html($BL['be_article_cnt_delpartjs'] . ' [ID: ' . $row['acontent_id'] . ']'); ?>"><i class="far fa-trash-alt"></i></a>
          </div>
        </div>
        <?php
        $acontent_livedate = is_null($row['acontent_livedate']) ? false : phpwcms_strtotime($row['acontent_livedate'], $BL['be_longdatetime'], '');
        $acontent_killdate = is_null($row['acontent_killdate']) ? false : phpwcms_strtotime($row['acontent_killdate'], $BL['be_longdatetime'], '');

        if($acontent_livedate || $acontent_killdate) {
        ?>
        <div class="row">
          <div class="col-sm-auto">
            <small><?php echo $BL['be_article_cnt_start']; ?>: <?php echo $acontent_livedate ? $acontent_livedate : $BL['be_not_set']; ?></small>
            &nbsp;&nbsp;
            <small><?php echo $BL['be_article_cnt_end']; ?>: <?php echo $acontent_killdate ? $acontent_killdate : $BL['be_not_set']; ?></small>
          </div>
        </div>
      <?php } ?>
        <div class="row mt-1">
          <?php
            // list content type overview
            $cinfo = NULL;
            // check default content parts (system internals
            if($row['acontent_type'] != 30 && file_exists('include/inc_tmpl/content/cnt' . $row['acontent_type'] . '.list.inc.php')) {
              include PHPWCMS_ROOT . '/include/inc_tmpl/content/cnt' . $row['acontent_type'] . '.list.inc.php';
            } elseif($row['acontent_type'] == 30 && file_exists($phpwcms['modules'][$row['acontent_module']]['path'] . 'inc/cnt.list.php')) {
              // custom module
              include $phpwcms['modules'][$row['acontent_module']]['path'] . 'inc/cnt.list.php';
            } else {
              // default fallback
              include PHPWCMS_ROOT . '/include/inc_tmpl/content/cnt0.list.inc.php';
            }
            // end list
          ?>
        </div>
      </div>
    </div>
  </li>

        <?php
          }
        } //Ende Listing Artikel Content Teile
        ?>
  <input name="csorting" type="hidden" id="csorting" value="<?php echo ($scc*10); ?>" />
</ul>
</div>
</form>
</div>


  <?php
   if ($listingflag > 0) {
   echo '</div>';
   }
  ?>

  <div class="d-flex justify-content-end mt-3 mb-4">
    <div>
      <?php echo $buttonAction; ?>
    </div>
  </div>
