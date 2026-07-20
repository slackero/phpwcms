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
// now retrieve all downloads
$sql  = "SELECT * FROM " . DB_PREPEND . "phpwcms_articlecontent ac ";
$sql .= "INNER JOIN " . DB_PREPEND . "phpwcms_article ar ON ";
$sql .= "ar.article_id = ac.acontent_aid WHERE ac.acontent_trash=0 AND ac.acontent_type=89";
$result = _dbQuery($sql);

?>
<h2 class="mb-3"><?php echo $BLM['listing_polls'] ?></h2>

<div class="table-responsive">
  <table class="table table-sm table-striped table-hover mb-0">
    <thead>
      <tr>
        <th><?php echo $BLM['pollname'] ?></th>
        <th><?php echo $BLM['pollcounts'] ?></th>
      </tr>
    </thead>
    <tbody>
    <?php
    $x = 0;
    foreach($result as $data) {
      // now add article URL
      echo '	<tr title="'.html_specialchars('[ID:'.$data["acontent_id"].'] '.$data["acontent_title"]).'">';
      echo '		<td width="80%"><a href="phpwcms.php?do=articles&amp;p=2&amp;s=1&amp;id='.$data["acontent_aid"].'" target="_blank">' . html_specialchars($data["article_title"])." - ".html_specialchars($data["acontent_title"]) . "</a>&nbsp;</td>" . LF;

      $poll_form			= @unserialize($data["acontent_form"], ['allowed_classes' => false]);

      $poll_total_votes = 0;
      if (isset($poll_form["count"]) && is_array($poll_form["count"])) {
        foreach($poll_form["count"] as $key => $value) {
          $poll_total_votes += $value;
        }
      }

      echo '		<td>'.$poll_total_votes."&nbsp;</td>" . LF;
      echo '		</tr>' . LF;
      $x++;
    }
    ?>
    </tbody>
  </table>
</div>

</div>
</div>
