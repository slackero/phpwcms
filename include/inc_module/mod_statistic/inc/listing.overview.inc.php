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
?>

<div class="card mb-3">
  <div class="card-header"><h5 class="mb-0"><?php echo $BLM['listing_overview'] ?></h5></div>
  <div class="card-body">
    <div class="table-responsive">
      <table class="table table-sm table-striped table-hover table-valign-middle mb-0">
        <tr>
          <td align="right" style="width: 80px;"><?php
          echo _dbCount('SELECT COUNT(article_ID) FROM '.DB_PREPEND.'phpwcms_article WHERE article_public=1 AND article_aktiv=1 AND article_deleted=0');?></td>
          <td><?php echo $BLM['overview_aktiv'] ?></td>
        </tr>
        <tr>
          <td colspan="2"><strong><?php echo $BLM['overview_status'] ?></strong></td>
        </tr>
        <tr>
          <td align="right"><?php
          echo _dbCount('SELECT COUNT(article_ID) FROM '.DB_PREPEND.'phpwcms_article WHERE article_public=1 AND article_aktiv=1 AND article_deleted=0 AND (article_end IS NULL OR article_begin>NOW())');?></td>
          <td><?php echo $BLM['overview_start'] ?></td>
        </tr>
        <tr>
          <td align="right"><?php
          echo _dbCount('SELECT COUNT(article_ID) FROM '.DB_PREPEND.'phpwcms_article WHERE article_public=1 AND article_aktiv=1 AND article_deleted=0 AND (article_end IS NULL OR article_end<NOW())'); ?></td>
          <td><?php echo $BLM['overview_endd'] ?></td>
        </tr>
        <tr>
          <td align="right"><?php
           echo _dbCount('SELECT COUNT(article_ID) FROM '.DB_PREPEND."phpwcms_article WHERE article_public=1 AND article_aktiv=1 AND article_deleted=0 AND (article_begin IS NULL OR article_begin<NOW()) AND (article_end IS NULL OR article_end>NOW()) AND (article_alias IS NULL OR article_alias = '')");?></td>
          <td><?php echo $BLM['overview_aalias'] ?></td>
        </tr>
        <tr>
          <td align="right"><?php
          echo _dbCount('SELECT COUNT(article_ID) FROM '.DB_PREPEND."phpwcms_article WHERE article_public=1 AND article_aktiv=1 AND article_deleted=0 AND (article_begin IS NULL OR article_begin<NOW()) AND (article_end IS NULL OR article_end>NOW()) AND (article_description IS NULL OR article_description = '' AND article_nositemap=1)");?></td>
          <td><?php echo $BLM['overview_beschr'] ?></td>
        </tr>
        <tr>
          <td></td>
          <td><a href="phpwcms.php?do=admin&amp;p=13"><?php echo $BLM['overview_fehlende'] ?></a></td>
        </tr>
      </table>
    </div>
  </div>
</div>

<div class="card mb-3">
  <div class="card-header"><h5 class="mb-0"><?php echo $BLM['listing_overview_img'] ?></h5></div>
  <div class="card-body">
    <div class="table-responsive">
      <table class="table table-sm table-striped table-hover table-valign-middle mb-0">
         <tr>
          <td><?php echo $BLM['overview_zentrale'] ?></td>
          <td align="right" style="width: 80px;"><?php
          echo _dbCount('SELECT COUNT(f_name) FROM '.DB_PREPEND."phpwcms_file WHERE f_hash <> '' AND f_trash=0 AND f_ext IN ('jpg', 'gif', 'png')");?></td>
        </tr>
         <tr>
          <td><?php echo $BLM['overview_alias'] ?></td>
          <td align="right"><?php
          echo _dbCount('SELECT COUNT(f_id) FROM '.DB_PREPEND."phpwcms_file WHERE f_alias = '' AND f_hash <> '' AND f_trash=0 AND f_ext IN ('jpg', 'gif', 'png')");?></td>
        </tr>
      </table>
    </div>
  </div>
</div>

<div class="card mb-3">
  <div class="card-header"><h5 class="mb-0"><?php echo $BLM['listing_overview_cnt'] ?></h5></div>
  <div class="card-body">
    <div class="table-responsive">
      <table class="table table-sm table-striped table-hover table-valign-middle mb-0">
     <?php
    $wcs_content_type = array(
       0 => $BL['be_ctype_plaintext'] ,
       6 => $BL['be_ctype_html'],
      14 => $BL['be_ctype_wysiwyg'],
      11 => $BL['be_ctype_code'],
       1 => $BL['be_ctype_textimage'],
      29 => $BL['be_ctype_imagesdiv'],
      31 => $BL['be_ctype_imagesspecial'],
      32 => $BL['be_ctype_tabs'],
       2 => $BL['be_ctype_images'],
       4 => $BL['be_ctype_bulletlist'],
       100 => $BL['be_ctype_ullist'],
       3 => $BL['be_ctype_link'],
       5 => $BL['be_ctype_linklist'],
       8 => $BL['be_ctype_linkarticle'],
      33 => $BL['be_news'],
      15 => $BL['be_ctype_articlemenu'],
       9 => $BL['be_ctype_multimedia'],
       7 => $BL['be_ctype_filelist'],
      16 => $BL['be_ctype_ecard'],
      23 => $BL['be_ctype_simpleform'],
      10 => $BL['be_ctype_emailform'].' [old]',
      12 => $BL['be_ctype_newsletter'],
      13 => $BL['be_ctype_search'],
      18 => $BL['be_ctype_guestbook'],
      19 => $BL['be_ctype_sitemap'],
      21 => $BL['be_ctype_pages'],
      22 => $BL['be_ctype_rssfeed'],
      50 => $BL['be_ctype_reference'],
      51 => $BL['be_ctype_map'],
      52 => $BL['be_ctype_phpvar'],
      24 => $BL['be_ctype_alias'],
      89 => $BL['be_ctype_poll'], // jens poll
      26 => $BL['be_ctype_recipe'],
      27 => $BL['be_ctype_faq'],
      28 => $BL['be_ctype_felogin'],
      25 => $BL['be_ctype_flashplayer']
    );

    // set module content parts = 30
    if(count($phpwcms['modules'])) {
      foreach($phpwcms['modules'] as $value) {
        if($value['cntp']) {
          $wcs_content_type[30] = $BL['be_ctype_module'];
          break;
        }
      }
    }
    $sql  = "SELECT ar.article_id, ar.article_title ";
    $sql .= "FROM ".DB_PREPEND."phpwcms_article ar LEFT JOIN ".DB_PREPEND."phpwcms_articlecontent ac ON ";
    $sql .= "ar.article_id = ac.acontent_aid WHERE ";
    $sql .= "ar.article_public=1 AND  ar.article_aktiv=1 AND ";
    $sql .= "ar.article_deleted=0 AND ac.acontent_trash=0 AND ac.acontent_type = ";
    $row_count = 0;

    foreach($wcs_content_type as $key => $value) {
      $counter = _dbQuery($sql.$key, "COUNT");
      if ($counter > 0 ) {
     ?>
      <tr class="listrow">
        <td><a href="<?php echo statistic_url('controller=overview')."&amp;cid=".$key ?>"><?php echo $BLM['overview_mit'].$value ?></a></td>
        <td align="right" style="width: 80px;"><?php echo $counter;?></td>
      </tr>
    <?php
      $row_count++;
      }
    }
    ?>
      </table>
    </div>

    <?php
    if(isset($_GET['cid'])) {
      echo "<h5 class=\"mt-3 mb-2\">".$BLM['searcharticle'].$wcs_content_type[$_GET['cid']]."</h5>";
      $sql =  "SELECT DISTINCT ar.article_title, ar.article_id FROM ".DB_PREPEND."phpwcms_articlecontent ac ";
      $sql .= "INNER JOIN " . DB_PREPEND . "phpwcms_article ar ON ar.article_id = ac.acontent_aid ";
      $sql .= "WHERE acontent_type=".intval($_GET['cid'])." AND acontent_trash=0 AND article_deleted = 0 AND acontent_visible = 1";

      $result = _dbQuery($sql);
      if(isset($result[0]['article_id'])) {
        echo '<div class="list-group list-group-flush">';
        foreach($result as $crow) {
          echo "<a class=\"list-group-item list-group-item-action py-1 px-0 text-primary\" href=\"phpwcms.php?do=articles&amp;p=2&amp;s=1&amp;id=".$crow['article_id']."\" target=\"_blank\">".html_specialchars($crow['article_title'])."</a>";
        }
        echo '</div>';
      }
    }
    ?>
  </div>
</div>

<div class="card mb-0">
  <div class="card-header"><h5 class="mb-0"><?php echo $BLM['listing_overview_del'] ?></h5></div>
  <div class="card-body">
    <div class="table-responsive">
      <table class="table table-sm table-striped table-hover table-valign-middle mb-0">
        <thead>
          <tr>
            <th style="text-align:left"><?php echo $BL['be_article_atitle'] ?></th>
            <th class="text-center" style="width: 150px;"><?php echo $BLM['overview_end'] ?></th>
            <th class="text-right" style="width: 100px;">&nbsp;</th>
          </tr>
        </thead>
        <tbody>
        <?php
          $_asql_1  = "SELECT article_id, article_cid, article_title, article_public, article_aktiv, article_uid, ";
          $_asql_1 .= "date_format(article_end, '".$BL['be_sqlshortdatetime']."') AS article_date ";
          $_asql_1 .= "FROM ".DB_PREPEND."phpwcms_article ";
          $_asql_1 .= 'WHERE article_public=1 AND article_aktiv=1 AND article_deleted=0 ';
          $_asql_1 .= 'AND (article_end IS NULL OR article_end < NOW()) ';
          $_asql_1 .= 'ORDER BY article_end DESC ';
          $_last10_article = _dbQuery($_asql_1);

          $row_count = 0;
          foreach($_last10_article as $value) {
            echo '<tr style="cursor:pointer" onclick="document.location.href=\'phpwcms.php?do=articles&amp;p=2&amp;s=1&amp;id='.$value['article_id'].'\'" title="'.$BL['be_func_struct_edit'].'">'.LF;
            echo '  <td><strong>'.html_specialchars($value['article_title']).'</strong></td>'.LF;
            echo '  <td align="center" class="text-nowrap">&nbsp;'.$value['article_date'].'&nbsp;</td>'.LF;
            echo '  <td class="text-right text-nowrap p-1">';
            echo '<div class="btn-group btn-group-sm" role="group" aria-label="stat-overview-actions-'.$value['article_id'].'">';
            echo '<button class="btn btn-sm '.($value["article_aktiv"]==0 ? "btn-warning" : "btn-success").'" data-id="'.$value['article_id'].'" aria-disabled="true" data-toggle="tooltip" title="'.$BL['be_tooltip_visibility'].'"><i class="fas '.($value["article_aktiv"]==0 ? "fa-eye-slash" : "fa-eye").'"></i></button>';
            echo '<a class="btn btn-sm btn-blue" role="button" aria-disabled="true" title="'.$BL['be_func_struct_edit'].'" data-toggle="tooltip" href="phpwcms.php?do=articles&amp;p=2&amp;s=1&amp;id='.$value['article_id'].'"><i class="fa fa-pencil-alt"></i></a>';
            echo '</div>';
            echo '</td>'.LF;
            echo '</tr>'.LF;
            $row_count++;
          }
        ?>
        </tbody>
      </table>
    </div>
  </div>
</div>

</div>
</div>
