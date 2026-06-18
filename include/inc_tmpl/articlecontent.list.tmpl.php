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
// obligate check for cmsgo constants
if (!defined('CMSGO_ROOT')) {
    die("You Cannot Access This Script Directly, Have a Nice Day.");
}
// ----------------------------------------------------------------

// Article List
$buttonAction  = '<input class="btn btn-sm btn-blue mr-1" type="button" value="'.$BL['be_article_cnt_center'];
$buttonAction .= '" title="'.$BL['be_article_cnt_center'].'" onclick="';
$buttonAction .= "location.href='cmsgo.php?do=articles';return false;\">";
// Article Preview (new window)
$buttonActionLink = rel_url(array('cmsgo-preview'=>1), array(), empty($article["article_alias"]) ? 'aid='.$article["article_id"] : $article["article_alias"]);
$buttonAction .= '<input class="btn btn-sm btn-blue" type="button" value="'.$BL['be_func_struct_preview'].'" data-toggle="tooltip" title="'.$BL['be_func_struct_preview'].'" onclick="';
$buttonAction .= "window.open('".$buttonActionLink."', 'articlePreviewWindows');return false;\">";

?>
<script>
$(function() {
    $("ul.dropable-list").sortable({
        group: 'no-drop',
        handle: 'span.handle',
        onDrop: function ($item, container, _super, event) {
            $item.removeClass(container.group.options.draggedClass).removeAttr("style");
            $("body").removeClass(container.group.options.bodyClass);
            var sort_order = '';
            $('#sortable-list-0 li').each(function() {
                sort_order = sort_order + $(this).attr('id') + '|';
            });
            $.ajax({
                url: 'include/inc_act/act_articlesort.php?<?php echo get_token_get_string(); ?>&sortid=' + sort_order,
                xhrFields: {
                    withCredentials: true
                }
            });
        }
  });
});
</script>

<div class="row">
  <div class="col col-sm-auto text-center text-sm-left">
    <h1><?php echo $BL['be_func_struct_edit'] ?></h1>
  </div>
  <div class="col-12 col-sm text-center text-sm-right mb-3">
    <div class="form-group align-items-center">
      <?php echo $buttonAction; ?>
    </div>
  </div>
</div>

<div class="card">
  <div class="card-header"><h1><?php echo $BL['be_article_cnt_ltitle'] ?></h1></div>
  <div class="card-body">
    <form action="cmsgo.php?do=articles&amp;p=2&amp;s=1&amp;aktion=2&amp;id=<?php echo $article["article_id"] ?>" method="post" name="addcontent" id="addcontent">
      <div class="row">
        <div class="col">
          <a class="mr-3" href="cmsgo.php?do=articles&amp;p=2&amp;s=1&amp;aktion=1&amp;id=<?php echo $article["article_id"] ?>"><strong><?php echo html($article["article_title"]) ?></strong></a>&nbsp;[AID:<?php echo $article["article_id"] ?>]
          <?php if(count($cmsgo['allowed_lang']) == 0): ?>
          <?php else: ?>
              <span class="ml-3 flag-icon flag-icon-<?php echo ($lang = strtolower(empty($article["article_lang"]) ? $cmsgo['default_lang'] : $article["article_lang"])); ?>" data-toggle="tooltip" title="<?php echo get_language_name($lang) ?>"></span>
          <?php endif; ?>
        </div>
        <div class="col text-right">
          <a class="btn btn-sm btn-blue" role="button" aria-disabled="true" title="<?php echo $BL['be_article_cnt_ledit'] ?>" data-toggle="tooltip" href="cmsgo.php?do=articles&amp;p=2&amp;s=1&amp;aktion=1&amp;id=<?php echo $article["article_id"] ?>"><i class="fa fa-pencil-alt"></i></a>
          <a id="abtnarticle<?php echo $article["article_id"]?>" class="btn fa btn-sm visible <?php echo ($article["article_aktiv"]==0 ? "btn-danger" : "btn-success")?>" data-id="<?php echo $article["article_id"]?>" data-type="article" data-table="article" data-field="article_aktiv" data-fieldid="article_id" aria-disabled="true" data-toggle="tooltip" title="<?php echo $BL['be_article_cnt_lvisible'] ?>"></a>
          <a class="btn btn-sm btn-danger" role="button" aria-disabled="true" title="<?php echo $BL['be_article_cnt_ldel'] ?>" data-toggle="tooltip" href="include/inc_act/act_articlecontent.php?do=<?php echo "1,".$article["article_id"]; ?>" onclick="return confirm('<?php echo $BL['be_article_cnt_ldeljs'].'\n'.html($article["article_title"]); ?>');"><i class="far fa-trash-alt"></i></a>
        </div>
      </div>

<!--top table start -->
      <div class="row">
        <div class="col-lg-6">
          <div class="table-responsive">
          <table class="table table-sm mt-3 tdMorepace">
            <tr>
              <td class="text-secondary"><?php echo $BL['be_article_urlalias'] ?>:&nbsp;</td>
              <td><strong><?php echo html($article["article_alias"]); ?></strong></td>
            </tr>
            <?php

            $langstr = '';

            //show connected language articles
            if(count($cmsgo['allowed_lang']) > 0) {
              if (intval($article['article_lang_id'])> 0 && $article['article_lang_type'] == 'article') {
                $where = 'article_id = '.$article['article_lang_id'];
                $adata = _dbGet('cmsgo_article', 'article_id, article_alias, article_title', $where, '', '', 1);
                if (is_array($adata)) {
                  $langstr = '<span class="flag-icon flag-icon-de" data-toggle="tooltip" title="'. get_language_name('de').'"></span> <a href="cmsgo.php?&do=articles&p=2&s=1&id=' . $adata[0]['article_id'] . '" data-toggle="tooltip" title="' . $adata[0]['article_title'] . '">' . $adata[0]['article_alias'] . $cmsgo['rewrite_ext'] .'</a><br>';
                } else {
                  $langstr = $BL['be_admin_usr_err'];
                }
                foreach($cmsgo['allowed_lang'] as $key => $lang) {
                  $lang = strtolower($lang);

                  if($lang == $cmsgo['default_lang'] || $lang == $article['article_lang']) {
                    continue;
                  }
                  $where = 'article_lang_id = '.$article['article_lang_id'].' AND article_lang LIKE '._dbEscape($lang);
                  $adata = _dbGet('cmsgo_article', 'article_id, article_alias, article_title', $where, '', '', 1);
                  if (isset($adata[0]['article_id'])) {
                    $langstr .= '<span class="flag-icon flag-icon-'.$lang.'" data-toggle="tooltip" title="'. get_language_name($lang).'"></span> <a href="cmsgo.php?&do=articles&p=2&s=1&id=' . $adata[0]['article_id'] . '" target="_blank" data-toggle="tooltip" title="' . $adata[0]['article_title'] . '">' . $adata[0]['article_alias'] . $cmsgo['rewrite_ext'] .'</a><br>';
                    unset($adata);
                  }
                }

              } else  {
                foreach($cmsgo['allowed_lang'] as $key => $lang) {
                  $lang = strtolower($lang);

                  if($lang == $cmsgo['default_lang']) {
                    continue;
                  }

                  $where = 'article_lang_id = '.$article['article_id'].' AND article_lang LIKE '._dbEscape($lang);
                  $adata = _dbGet('cmsgo_article', 'article_id, article_alias, article_title', $where, '', '', 1);
                  if (isset($adata[0]['article_id'])) {
                    $langstr .= '<span class="flag-icon flag-icon-'.$lang.'" data-toggle="tooltip" title="'. get_language_name($lang).'"></span> <a href="cmsgo.php?&do=articles&p=2&s=1&id=' . $adata[0]['article_id'] . '" target="_blank" data-toggle="tooltip" title="' . $adata[0]['article_title'] . '">' . $adata[0]['article_alias'] . $cmsgo['rewrite_ext'] .'</a><br>';
                    unset($adata);
                  }
                }

              }
            ?>
            <tr>
              <td class="text-secondary"><?php echo $BL['be_article_opposite_lang'] ?>:&nbsp;</td>
              <td><?php echo $langstr; ?></td>
            </tr>

          <?php
            } ?>

            <?php if($article["article_subtitle"]) { ?>
            <tr>
              <td class="text-secondary"><?php echo $BL['be_article_asubtitle'] ?>:&nbsp;</td>
              <td><strong><?php echo html($article["article_subtitle"]); ?></strong></td>
            </tr>

            <?php } ?>

            <?php if(!empty($article["article_summary"])) { ?>
            <tr>
              <td class="text-secondary"><?php echo $BL['be_article_asummary'] ?>:&nbsp;</td>
              <td><?php echo html(getCleanSubString(strip_tags($article["article_summary"]), 250, '&#8230;'), false); ?></td>
            </tr>

            <?php   } ?>
            <tr>
              <td class="text-secondary"><?php echo $BL['be_article_cat'] ?>:&nbsp;</td>
              <td><?php echo html($article["article_cat"]) ?></td>
            </tr>

            <tr>
              <td class="text-secondary"><?php echo $BL['be_article_akeywords'] ?>:&nbsp;</td>
              <td><?php if($article["article_keyword"]) {echo html($article["article_keyword"]);}else{echo "not defined/completed";} ?></td>
            </tr>
            <?php
            if($article["article_canonical"]) {
            ?>

            <tr>
              <td class="text-secondary"><?php echo $BL['be_canonical'] ?>:&nbsp;</td>
              <td><?php echo html($article["article_canonical"]); ?></td>
            </tr>
            <?php
            }

            if($article["article_redirect"]) {
            ?>

            <tr>
              <td class="text-secondary"><?php echo $BL['be_article_cnt_redirect'] ?>:&nbsp;</td>
              <td><?php echo html($article["article_redirect"]); ?></td>
            </tr>
            <?php
            }

                $thumb_image = false;
                if(!empty($article["image"]["hash"])) {
                    $thumb_image = get_cached_image(array(
                        "target_ext"    =>  $article['image']['ext'],
                        "image_name"    =>  $article['image']['hash'] . '.' . $article['image']['ext'],
                        "thumb_name"    =>  md5($article['image']['hash'].$cmsgo["img_list_width"].$cmsgo["img_list_height"].$cmsgo["sharpen_level"].$cmsgo['colorspace'])
                    ));
                }

                $thumb_list_image = false;
                if(!empty($article["image"]["list_hash"])) {
                    $thumb_list_image = get_cached_image(array(
                        "target_ext"    =>  $article['image']['list_ext'],
                        "image_name"    =>  $article['image']['list_hash'] . '.' . $article['image']['list_ext'],
                        "thumb_name"    =>  md5($article['image']['list_hash'].$cmsgo["img_list_width"].$cmsgo["img_list_height"].$cmsgo["sharpen_level"].$cmsgo['colorspace'])
                    ));
                }

                if($thumb_image || $thumb_list_image) {

                ?>

                <tr>
                  <td class="text-secondary"><?php echo $BL['be_cnt_image'] ?>:&nbsp;</td>
                  <td><?php

                if($thumb_image) {
                    echo '<img src="'. $thumb_image['src'] .'" '.$thumb_image[3].' alt="" style="margin-right:5px;" />';
                }
                if($thumb_list_image) {
                    echo '<img src="'. $thumb_list_image['src'] .'" '.$thumb_list_image[3].' alt=""';
                    if(!empty($article['image']['list_usesummary'])) {
                        echo ' class="inactive"';
                    }
                    echo ' />';
                }

                ?></td>
                </tr>
                <?php

                }

                ?>

                <tr>
                  <td class="text-secondary"><?php echo $BL['be_article_username']; ?>:&nbsp;</td>
                  <td><?php echo $article["article_username"] ?></td>
                </tr>

                <tr>
                  <td class="text-secondary text-nowrap"><?php echo $BL['be_ftptakeover_status'] ?>:&nbsp;</td>
                  <td>
                      <span class="badge <?php echo ($article["article_nositemap"] ? 'badge-success' : 'badge-secondary') ?> mt-1"><?php echo $BL['be_ctype_sitemap'] ?></span>
                      <span class="badge <?php echo ($article["article_nosearch"] ? 'badge-secondary' : 'badge-success') ?> mt-1"><?php echo $BL['be_fsearch_searchlabel'] ?></span>
                      <span class="badge <?php echo ($article["article_norss"] ? 'badge-success' : 'badge-secondary') ?> mt-1"><?php echo $BL['be_no_rss'] ?></span>
                      <span class="badge <?php echo ($article["article_opengraph"] ? 'badge-success' : 'badge-secondary') ?> mt-1"><?php echo $BL['be_opengraph_support'] ?></span>
                      <span class="badge <?php echo ($article["article_archive_status"] ? 'badge-success' : 'badge-secondary') ?> mt-1"><?php echo $BL['be_show_archived'] ?></span>
                      <span class="badge <?php echo ($article["article_meta"]['noindex'] ? 'badge-success' : 'badge-secondary') ?> mt-1"><?php echo $BL['be_robots_noindex'] ?></span>
                      <span class="badge <?php echo ($article["article_meta"]['nofollow'] ? 'badge-success' : 'badge-secondary') ?> mt-1"><?php echo $BL['be_robots_nofollow'] ?></span>
                  </td>
                </tr>
          </table>
        </div>
        </div>

        <div class="col-lg-6">
          <div class="table-responsive">
          <table class="table table-sm mt-3 tdMorepace">
            <tr>
              <td class="text-secondary"><?php echo $BL['be_article_eslastedit'] ?>:&nbsp;</td>
              <td><?php echo cmsgo_strtotime($article["article_date"], $BL['be_longdatetime'], '') ?></td>
            </tr>
            <tr>
              <td class="text-secondary"><?php echo $BL['be_fprivedit_created'] ?>:&nbsp;</td>
              <td><?php echo date($BL['be_longdatetime'], $article["article_created"]) ?></td>
            </tr>
            <tr>
              <td class="text-secondary text-nowrap"><?php echo $BL['be_article_cnt_start'] ?>:&nbsp;</td>
              <td><?php echo $set_begin ? cmsgo_strtotime($article["article_begin"], $BL['be_longdatetime'], '') : $BL['be_not_set']; ?></td>
            </tr>
            <tr>
              <td class="text-secondary"><?php echo $BL['be_article_cnt_end'] ?>:</td>
              <td><?php echo $set_end ? cmsgo_strtotime($article["article_end"], $BL['be_longdatetime'], '') : $BL['be_not_set']; ?></td>
            </tr>
            <tr>
              <td class="text-secondary text-nowrap"><?php echo $BL['be_cnt_sortvalue'] ?>:&nbsp;</td>
              <td><?php echo $article["article_sort"] ?></td>
            </tr>
            <tr>
              <td class="text-secondary"><?php echo $BL['be_priorize'] ?>:</td>
              <td><?php echo $article["article_priorize"] ?></td>
            </tr>
          </table>
        </div>
        </div>

      </div>
<!-- Top Table End-->

<!-- New Content start-->
      <div class="card-header border-0">
        <div class="form-row align-items-center">
          <div class="col-sm-auto">
            <select name="ctype" class="custom-select form-control form-control-sm" id="ctype" onchange="this.form.submit();">
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

            $sql  = "SELECT acontent_id, acontent_sorting, acontent_trash, acontent_block FROM ".DB_PREPEND."cmsgo_articlecontent ";
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
                          "\" data-toggle=\"tooltip\" title=\"".$BL['be_article_cnt_up']."\"><i class=\"fa fa-chevron-up fa-fw text-dark\" aria-hidden=\"true\"></i></a>";
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
                          "\" data-toggle=\"tooltip\" title=\"".$BL['be_article_cnt_down']."\"><i class=\"fa fa-chevron-down fa-fw text-dark\" aria-hidden=\"true\"></i></a>";
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

            $sql = "SELECT *, UNIX_TIMESTAMP(acontent_tstamp) as acontent_date FROM ".DB_PREPEND."cmsgo_articlecontent ".
                "WHERE acontent_aid=".$article["article_id"]." AND acontent_trash=0 ".
                "ORDER BY acontent_block, acontent_sorting, acontent_tab, acontent_id";

            $result = _dbQuery($sql);
            if(isset($result[0]['acontent_id'])) {
              foreach($result as $row) {

                // if type of content part not enabled available
                if(!isset($wcs_content_type[ $row["acontent_type"] ]) || ($row["acontent_type"] == 30 && !isset($cmsgo['modules'][$row["acontent_module"]]))) {
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
                  $contentpart_block_color = '#E0D6EB';

                  switch($contentpart_block) {
                    case '':
                    case 'CONTENT':
                      $contentpart_block_name = $BL['be_main_content'].$contentpart_block_name;
                      if($article['article_paginate']) {
                        $contentpart_block_name .= ' / <img src="img/symbole/content_cppaginate.gif" alt="" style="margin-right:2px;" />';
                        $contentpart_block_name .= $BL['be_cnt_pagination'];
                      }
                      $contentpart_block_color = '#F5CCCC';
                      break;

                    case 'LEFT':
                      $contentpart_block_name = $BL['be_cnt_left'].$contentpart_block_name;
                      $contentpart_block_color = '#E0EBD6';
                      break;

                    case 'RIGHT':
                      $contentpart_block_name = $BL['be_cnt_right'].$contentpart_block_name;
                      $contentpart_block_color = '#FFF5CC';
                      break;

                    case 'HEADER':
                      $contentpart_block_name = $BL['be_admin_page_header'].$contentpart_block_name;
                      $contentpart_block_color = '#EBEBD6';
                      break;

                    case 'FOOTER':
                      $contentpart_block_name = $BL['be_admin_page_footer'].$contentpart_block_name;
                      $contentpart_block_color = '#E1E8F7';
                      break;

                    case 'CPSET':
                      $contentpart_block_name = $BL['be_settings'].' <span style="font-weight:normal">('.$BL['be_system_container_norender'].')</span>';
                      $contentpart_block_color = '#cceaf5';
                      break;

                    case 'SYSTEM':
                      $contentpart_block_name = $BL['be_system_container'].' <span style="font-weight:normal">('.$BL['be_system_container_norender'].')</span>';
                      $contentpart_block_color = '#ffdc9d';
                      break;
                  }
                  if ($listingflag > 0) {
                    echo '</ul></div>';
                  }
          ?>
      <div class="card articlelist rounded-0 my-3">
        <div class="card-header border-0 py-1" style="background-color:<?php echo $contentpart_block_color ?>;">
          <span style="font-size:0.875em;font-weight:bold;"><i class="fa fa-<?php echo $contentpart_block === 'CPSET' ? 'list-ul ' : 'columns' ?>" aria-hidden="true"></i> <?php echo $contentpart_block_name ?></span>
        </div>
    <?php
    if ($listingflag == 0) {
      echo '<ul id="sortable-list-'. $listingflag .'" class="list-group list-group-flush dropable-list pl-0">';
    } else {
      echo '<script>
      $(function() {
        $("ul.dropable-list'. $listingflag .'").sortable({
        group: \'no-drop'. $listingflag .'\',
        handle: \'em.handle\',
        onDrop: function ($item, container, _super, event) {
          $item.removeClass(container.group.options.draggedClass).removeAttr("style");
          $("body").removeClass(container.group.options.bodyClass);
          var sort_order = \'\';
          $(\'#sortable-list-'. $listingflag .' li\').each(function() {
            sort_order = sort_order +  $(this).attr(\'id\')  + \'|\';
          });
          $.ajax({url: \'include/inc_act/act_articlesort.php?' . get_token_get_string() . '&sortid=\' + sort_order, xhrFields: {withCredentials: true}});
        }
        });
      });
      </script>
        <ul id="sortable-list-'. $listingflag .'" class="list-group list-group-flush dropable-list pl-0 '. $listingflag .'">';
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
      <div style="background-color: <?php echo $contentpart_block_color ?>"><div class="pl-3 py-1"><small>{<?php
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
        <span data-toggle="tooltip" title="<?php echo $BL['be_func_struct_sort_up'].' / '.$BL['be_func_struct_sort_down'] ?>" class="handle fa-stack"><i class="fa fa-circle fa-stack-2x"></i><i class="fa fa-sort fa-stack-1x fa-inverse"></i></span>
      </div>
      <div class="col">
        <div class="row">
          <div class="col small font-weight-bold text-uppercase"><?php
            $cntpart_title = $wcs_content_type[$row["acontent_type"]];
            if(!empty($row["acontent_module"])) {
              $cntpart_title .= ': '.$BL['modules'][$row["acontent_module"]]['listing_title'];
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
          if(intval($row["acontent_before"])) {
            echo '<small><span class="mx-2"><i class="fa fa-long-arrow-up" aria-hidden="true"></i> '.$row["acontent_before"];
            echo '</span></small>';
          }
          if(intval($row["acontent_after"])) {
            echo '<small><span class="mx-2"><i class="fa fa-long-arrow-down" aria-hidden="true"></i> '.$row["acontent_after"];
            echo '</span></small>';
          }
          if($row["acontent_top"]) {
            echo '<small><i class="fa fa-caret-square-o-up fa-fw mx-1" aria-hidden="true" data-toggle="tooltip" title="TOP"></i></small>';
          }
          if($row["acontent_anchor"]) {
            echo '<small><i class="fa fa-anchor fa-fw mx-1" aria-hidden="true" data-toggle="tooltip" title="Anchor"></i></small>';
          }
          ?>
          </div>

          <div class="col-sm-auto align-self-center justify-content-end text-nowrap">
            <?php echo $sbutton_string[$row["acontent_id"]]; ?>
          </div>

          <div class="col-sm-auto align-self-center justify-content-end text-muted">
            [ID:<?php echo $row["acontent_id"] ?>]
          </div>

          <div class="col-sm-auto align-self-center justify-content-end text-nowrap">
              <?php
                  echo date($BL['be_shortdatetime'], $row["acontent_date"]).'&nbsp;';
                  if($contentpart_block != 'CPSET') {
                    //Display cp paginate page number
                    if($article["article_paginate"]) {
                      echo '<img src="img/symbole/content_cppaginate.gif" alt="subsection" data-toggle="tooltip" title="subsection" />';
                      echo $row["acontent_paginate_page"] == 0 ? 1 : $row["acontent_paginate_page"];
                    }
                  }
              ?>
          </div>

          <div class="col-sm-auto align-self-center justify-content-end" >
            <a class="btn btn-sm btn-blue" role="button" aria-disabled="true" data-toggle="tooltip" title="<?php echo $BL['be_article_cnt_edit'] ?>" href="cmsgo.php?do=articles&amp;p=2&amp;s=1&amp;aktion=2&amp;id=<?php echo $article["article_id"]."&amp;acid=".$row["acontent_id"]; ?>">
              <i class="fa fa-pencil-alt"></i>
            </a>
            <?php
            // duplicate content part
            echo '<a href="include/inc_act/act_structure.php?do=8%7C'.$row["acontent_id"].'%7C'.$article["article_id"].'%7C'.($row["acontent_sorting"]+5).'" class="btn btn-sm btn-blue mr-1" role="button" aria-disabled="true" title="'.$BL['be_func_content_copy'].' [ID:'.$row["acontent_id"].']" data-toggle="tooltip" onclick="return confirm(\''.js_singlequote($BL['be_func_content_copy']).': \n'.js_singlequote($cntpart_title.' [ID:'.$row["acontent_id"].']').'\');"><i class="fa fa-copy"></i></a>';

            echo '<a id="abtnacontent'.$row["acontent_id"].'" class="btn fa btn-sm visible '.($row["acontent_visible"]==0 ? "btn-danger" : "btn-success").'" data-id="'.$row["acontent_id"].'" data-type="acontent" data-table="articlecontent" data-field="acontent_visible" data-fieldid="acontent_id" aria-disabled="true" data-toggle="tooltip" title="aktivieren/deaktivieren"></a>';

            ?>
            <a class="btn btn-sm btn-danger" role="button" aria-disabled="true" title="<?php echo $BL['be_article_cnt_ldel'] ?>" data-toggle="tooltip" href="include/inc_act/act_articlecontent.php?do=<?php echo "9,".$article["article_id"].",".$row["acontent_id"]?>" onclick="return confirm('<?php echo $BL['be_article_cnt_delpartjs'] ?> \n[ID: <?php echo $row["acontent_id"]?>]\n ');"><i class="far fa-trash-alt"></i></a>
          </div>
        </div>
        <?php
        $acontent_livedate = is_null($row['acontent_livedate']) ? false : cmsgo_strtotime($row['acontent_livedate'], $BL['be_longdatetime'], '');
        $acontent_killdate = is_null($row['acontent_killdate']) ? false : cmsgo_strtotime($row['acontent_killdate'], $BL['be_longdatetime'], '');

        if($acontent_livedate || $acontent_killdate) {
        ?>
        <div class="row">
          <div class="col-sm-auto">
            <small><?php echo $BL['be_article_cnt_start'] ?>: <?php echo $acontent_livedate ? $acontent_livedate : $BL['be_not_set']; ?></small>
            &nbsp;&nbsp;
            <small><?php echo $BL['be_article_cnt_end'] ?>: <?php echo $acontent_killdate ? $acontent_killdate : $BL['be_not_set']; ?></small>
          </div>
        </div>
      <?php } ?>
        <div class="row">
          <?php
            // list content type overview
            $cinfo = NULL;
            // check default content parts (system internals
            if($row['acontent_type'] != 30 && file_exists('include/inc_tmpl/content/cnt'.$row['acontent_type'].'.list.inc.php')) {
              include CMSGO_ROOT.'/include/inc_tmpl/content/cnt'.$row['acontent_type'].'.list.inc.php';
            } elseif($row['acontent_type'] == 30 && file_exists($cmsgo['modules'][$row['acontent_module']]['path'].'inc/cnt.list.php')) {
              // custom module
              include $cmsgo['modules'][$row['acontent_module']]['path'].'inc/cnt.list.php';
            } else {
              // default fallback
              include CMSGO_ROOT.'/include/inc_tmpl/content/cnt0.list.inc.php';
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

  <div class="row mt-3">
    <div class="col text-right">
      <div class="form-group align-items-center">
        <?php echo $buttonAction; ?>
      </div>
     </div>
  </div>
