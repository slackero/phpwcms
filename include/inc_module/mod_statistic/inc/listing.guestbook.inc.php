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
$sql  = "SELECT * FROM " . DB_PREPEND . "articlecontent ac ";
$sql .= "INNER JOIN " . DB_PREPEND . "article ar ON ";
$sql .= "ar.article_id = ac.acontent_aid ";
$sql .= " WHERE acontent_trash=0 AND acontent_type=18";

$result = _dbQuery($sql);

?>
<h2 class="mb-3"><?php echo $BLM['listing_guestbook'] ?></h2>

<div class="table-responsive">
  <table class="table table-sm table-striped table-hover table-valign-middle mb-0">
    <thead>
      <tr>
        <th><?php echo $BLM['guestbookname'] ?></th>
        <th><?php echo $BLM['guestbookcounts'] ?></th>
      </tr>
    </thead>
    <tbody>
    <?php
    $x = 0;
    foreach($result as $data) {
      // now add article URL
      echo '	<tr title="'.html_specialchars('[ID:'.$data["acontent_id"].'] '.$data["acontent_title"]).'">';
      echo '		<td width="80%"><a href="phpwcms.php?do=articles&amp;p=2&amp;s=1&amp;id='.$data["acontent_aid"].'" target="_blank">' . html_specialchars($data["article_title"])." - ".html_specialchars($data["acontent_title"]) . "</a>&nbsp;</td>" . LF;
      echo '		<td>'._dbQuery("SELECT guestbook_id FROM ".DB_PREPEND."guestbook  WHERE guestbook_trashed=0 AND guestbook_cid=".$data['acontent_id'], 'COUNT')."&nbsp;</td>" . LF;
      echo '		</tr>' . LF;
      $x++;
    }
    ?>
    </tbody>
  </table>
</div>

</div>
</div>
