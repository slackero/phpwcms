<?php
/**
 * cmsGO!
 *
 * @author Pixels & Points GmbH <info@pixels-points.ch>
 * @copyright Copyright (c) 2002-2025, Pixels & Points GmbH
 * @license https://www.pixels-points.ch/cmsgo-license.html Pixels & Points cmsGO! license
 *
 **/

// ----------------------------------------------------------------
// obligate check for cmsgo constants
if (!defined('CMSGO_ROOT')) {
    die("You Cannot Access This Script Directly, Have a Nice Day.");
}
// ----------------------------------------------------------------
?>

<div class="card mt-4">
  <div class="card-header"><h2><?php echo $BLM['listing_overview'] ?></h2></div>
  <div class="card-body">
    <table class="table table-sm mb-0">
      <tr>
        <td align="right"><?php
        echo _dbCount('SELECT COUNT(article_ID) FROM '.DB_PREPEND.'cmsgo_article WHERE article_public=1 AND article_aktiv=1 AND article_deleted=0');?></td>
        <td><?php echo $BLM['overview_aktiv'] ?></td>
      </tr>
      <tr>
        <td colspan="2"><strong><?php echo $BLM['overview_status'] ?></strong></td>
      </tr>
      <tr>
        <td align="right"><?php
        echo _dbCount('SELECT COUNT(article_ID) FROM '.DB_PREPEND.'cmsgo_article WHERE article_public=1 AND article_aktiv=1 AND article_deleted=0 AND (article_end IS NULL OR article_begin>NOW())');?></td>
        <td><?php echo $BLM['overview_start'] ?></td>
      </tr>
      <tr>
        <td align="right"><?php
        echo _dbCount('SELECT COUNT(article_ID) FROM '.DB_PREPEND.'cmsgo_article WHERE article_public=1 AND article_aktiv=1 AND article_deleted=0 AND (article_end IS NULL OR article_end<NOW())'); ?></td>
        <td><?php echo $BLM['overview_endd'] ?></td>
      </tr>
      <tr>
        <td align="right"><?php
         echo _dbCount('SELECT COUNT(article_ID) FROM '.DB_PREPEND."cmsgo_article WHERE article_public=1 AND article_aktiv=1 AND article_deleted=0 AND (article_begin IS NULL OR article_begin<NOW()) AND (article_end IS NULL OR article_end>NOW()) AND (article_alias IS NULL OR article_alias = '')");?></td>
        <td><?php echo $BLM['overview_aalias'] ?></td>
      </tr>
      <tr>
        <td align="right"><?php
        echo _dbCount('SELECT COUNT(article_ID) FROM '.DB_PREPEND."cmsgo_article WHERE article_public=1 AND article_aktiv=1 AND article_deleted=0 AND (article_begin IS NULL OR article_begin<NOW()) AND (article_end IS NULL OR article_end>NOW()) AND (article_description IS NULL OR article_description = '' AND article_nositemap=1)");?></td>
        <td><?php echo $BLM['overview_beschr'] ?></td>
      </tr>
      <tr>
        <td></td>
        <td><a href="cmsgo.php?do=admin&amp;p=13"><?php echo $BLM['overview_fehlende'] ?></a></td>
      </tr>
    </table>
  </div>
</div>

<div class="card mt-4">
  <div class="card-header"><h2><?php echo $BLM['listing_overview_img'] ?></h2></div>
  <div class="card-body">
    <table class="table table-sm mb-0">
       <tr>
        <td><?php echo $BLM['overview_zentrale'] ?></td>
        <td align="right"><?php
        echo _dbCount('SELECT COUNT(f_name) FROM '.DB_PREPEND."cmsgo_file WHERE f_hash <> '' AND f_trash=0 AND f_ext IN ('jpg', 'gif', 'png')");?></td>
      </tr>
       <tr>
        <td><?php echo $BLM['overview_alias'] ?></td>
        <td align="right"><?php
        echo _dbCount('SELECT COUNT(f_id) FROM '.DB_PREPEND."cmsgo_file WHERE f_alias = '' AND f_hash <> '' AND f_trash=0 AND f_ext IN ('jpg', 'gif', 'png')");?></td>
      </tr>
    </table>
  </div>
</div>

<div class="card mt-4">
  <div class="card-header"><h2><?php echo $BLM['listing_overview_cnt'] ?></h2></div>
  <div class="card-body">
    <table class="table table-sm mb-0"><?php
  // set module content parts = 30
  if(count($cmsgo['modules'])) {
    foreach($cmsgo['modules'] as $value) {
      if($value['cntp']) {
        $wcs_content_type[30] = $BL['be_ctype_module'];
        break;
      }
    }
  }
  $sql  = "SELECT ar.article_id, ar.article_title ";
  $sql .= "FROM ".DB_PREPEND."cmsgo_article ar LEFT JOIN ".DB_PREPEND."cmsgo_articlecontent ac ON ";
  $sql .= "ar.article_id = ac.acontent_aid WHERE ";
  $sql .= "ar.article_public=1 AND  ar.article_aktiv=1 AND ";
  $sql .= "ar.article_deleted=0 AND ac.acontent_trash=0 AND ac.acontent_type = ";
  $row_count = 0;

  foreach($wcs_content_type as $key => $value) {
    $counter = _dbQuery($sql.$key, "COUNT");
    if ($counter > 0 ) {
   ?>
    <tr <?php echo ( ($row_count % 2) ? ' bgcolor="#F3F5F8"' : '' ) ?> class="listrow">
      <td><a href="<?php echo statistic_url('controller=overview')."&cid=".$key ?>"><?php echo $BLM['overview_mit'].$value ?></a></td>
      <td align="right"><?php echo $counter;?></td>
    </tr>
  <?php
    $row_count++;
    }
  }
  ?>
    </table>

  <?php
  if(isset($_GET['cid'])) {

    echo "<h3>".$BLM['searcharticle'].$wcs_content_type[$_GET['cid']]."</h3>";

    $sql =  "SELECT DISTINCT ar.article_title, ar.article_id FROM ".DB_PREPEND."cmsgo_articlecontent ac ";
    $sql .= "INNER JOIN " . DB_PREPEND . "cmsgo_article ar ON ar.article_id = ac.acontent_aid ";
    $sql .= "WHERE acontent_type=".intval($_GET['cid'])." AND acontent_trash=0 AND article_deleted = 0 AND acontent_visible = 1";

    $result = _dbQuery($sql);
    if(isset($result[0]['article_id'])) {
      foreach($result as $crow) {
        echo "<a href=cmsgo.php?do=articles&p=2&s=1&id=".$crow['article_id']." target=_blank>".$crow['article_title']."</a><br>";
      }
    }
  }
  ?></div>
</div>

<div class="card mt-4">
  <div class="card-header"><h2><?php echo $BLM['listing_overview_del'] ?></h2></div>
  <div class="card-body">
    <table class="table table-sm mb-0">
      <tr>
        <th style="text-align:left"><?php echo $BL['be_article_atitle'] ?></th>
        <th><?php echo $BLM['overview_end'] ?></th>
        <th>&nbsp;</th>
      </tr>
    <?php

      $_asql_1  = "SELECT article_id, article_cid, article_title, article_public, article_aktiv, article_uid, ";
      $_asql_1 .= "date_format(article_end, '".$BL['be_sqlshortdatetime']."') AS article_date ";
      $_asql_1 .= "FROM ".DB_PREPEND."cmsgo_article ";
      $_asql_1 .= 'WHERE article_public=1 AND article_aktiv=1 AND article_deleted=0 ';
      $_asql_1 .= 'AND (article_end IS NULL OR article_end < NOW()) ';
      $_asql_1 .= 'ORDER BY article_end DESC ';
      $_last10_article = _dbQuery($_asql_1);

      $row_count = 0;
      foreach($_last10_article as $value) {

        echo '<tr'.( ($row_count % 2) ? ' bgcolor="#F3F5F8"' : '' ).' class="listrow" style="cursor:pointer" ';
        echo 'onclick="document.location.href=\'cmsgo.php?do=articles&p=2&s=1&id='.$value['article_id'].'\'" title="'.$BL['be_func_struct_edit'].'">'.LF;
        echo '  <td width="80%"><strong>'.html_specialchars($value['article_title']).'</strong></td>'.LF;
        echo '  <td align="center" nowrap="nowrap">&nbsp;'.$value['article_date'].'&nbsp;</td>'.LF;
        echo '  <td style="padding:3px;" nowrap="nowrap">';

        echo '<button class="btn fa btn-sm visible '.($value["article_aktiv"]==0 ? "btn-danger" : "btn-success").' mr-1" data-id="'.$value['article_id'].'" aria-disabled="true" data-toggle="tooltip" title="'.$BL['be_tooltip_visibility'].'"></button>';
        echo '<a class="btn btn-sm btn-blue" role="button" aria-disabled="true" title="'.$BL['be_func_struct_edit'].'" data-toggle="tooltip" href="cmsgo.php?do=articles&amp;p=2&amp;s=1&amp;id='.$value['article_id'].'"><i class="fa fa-pencil-alt"></i></a>';

        echo '</td>'.LF;
        echo '</tr>'.LF;

        $row_count++;
      }
    ?>
    </table>
  </div>
</div>

</div>
</div>
