<?php
/**
 * cmsGO!
 *
 * @author Pixels & Points GmbH <info@pixels-points.ch>
 * @copyright Copyright (c) 2002-2023, Pixels & Points GmbH
 * @license https://www.pixels-points.ch/cmsgo-license.html Pixels & Points cmsGO! license
 *
 **/

// ----------------------------------------------------------------
// obligate check for cmsgo constants
if (!defined('CMSGO_ROOT')) {
    die("You Cannot Access This Script Directly, Have a Nice Day.");
}
// ----------------------------------------------------------------
// now retrieve all downloads
$sql  = "SELECT * FROM " . DB_PREPEND . "cmsgo_articlecontent ac ";
$sql .= "INNER JOIN " . DB_PREPEND . "cmsgo_article ar ON ";
$sql .= "ar.article_id = ac.acontent_aid WHERE ac.acontent_trash=0 AND ac.acontent_type=89";
$result = _dbQuery($sql);

?>
<div class="card mt-4">
  <div class="card-header"><h2><?php echo $BLM['listing_polls'] ?></h2></div>
  <div class="card-body">
    <table class="table table-sm">
      <tr class="header">
        <th class="column"><?php echo $BLM['pollname'] ?></th>
        <th class="column"><?php echo $BLM['pollcounts'] ?></th>
      </tr>
      <?php

    $x = 0;

    foreach($result as $data) {

      // now add article URL
      echo '	<tr class="row'.($x%2?' alt': '').'" title="'.html_specialchars('[ID:'.$data["acontent_id"].'] '.$data["acontent_title"]).'">';
        echo '		<td width="80%"><a href="cmsgo.php?do=articles&p=2&s=1&id='.$data["acontent_aid"].'" target="_blank">' . html_specialchars($data["article_title"])." - ".html_specialchars($data["acontent_title"]) . "</a>&nbsp;</td>" . LF;

      $poll_form			= @unserialize($data["acontent_form"], ['allowed_classes' => false]);

      $poll_total_votes = 0;
      foreach($poll_form["count"] as $key => $value) {
        $poll_total_votes += $value;
      }

      echo '		<td>'.$poll_total_votes."&nbsp;</td>" . LF;
      echo '		</tr>' . LF;

      $x++;
    }

    ?>
    </table>
  </div>
</div>

</div>
</div>
