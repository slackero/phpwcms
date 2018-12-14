<?php
/**
 * cmsGo!
 *
 * @author Pixels & Points GmbH <info@pixels-points.ch>
 * @copyright Copyright (c) 2002-2018, Pixels & Points GmbH
 * @license https://www.pixels-points.ch/cmsgo-license.html Pixels & Points cmsGo! license
 *
 **/

// ----------------------------------------------------------------
// obligate check for cmsgo constants
if (!defined('CMSGO_ROOT')) {
    die("You Cannot Access This Script Directly, Have a Nice Day.");
}
// ----------------------------------------------------------------
// now retrieve all downloads
$sql  = "SELECT * FROM " . DB_PREPEND . "cmsgo_articlecontent ac";
$sql .= "INNER JOIN " . DB_PREPEND . "cmsgo_article ar ON ";
$sql .= "ar.article_id = ac.acontent_aid ";
$sql .= " WHERE acontent_trash=0 AND acontent_type=18";

$result = _dbQuery($sql);

?>
<div class="card mt-4">
  <div class="card-header"><h2><?php echo $BLM['listing_guestbook'] ?></h2></div>
  <div class="card-body">

  <table class="table table-sm">
    <tr class="header">
      <th><?php echo $BLM['guestbookname'] ?></th>
      <th><?php echo $BLM['guestbookcounts'] ?></th>
    </tr>
  <?php

  $x = 0;

  foreach($result as $data) {
    // now add article URL
    echo '	<tr class="row'.($x%2?' alt': '').'" title="'.html_specialchars('[ID:'.$data["acontent_id"].'] '.$data["acontent_title"]).'">';
      echo '		<td width="80%"><a href="cmsgo.php?do=articles&p=2&s=1&id='.$data["acontent_aid"].'" target="_blank">' . html_specialchars($data["article_title"])." - ".html_specialchars($data["acontent_title"]) . "</a>&nbsp;</td>" . LF;
    

    
    echo '		<td>'._dbQuery("SELECT guestbook_id FROM ".DB_PREPEND."cmsgo_guestbook  WHERE guestbook_trashed=0 AND guestbook_cid=".$data['acontent_id'], 'COUNT')."&nbsp;</td>" . LF;
    echo '		</tr>' . LF;  
    
    $x++;
  }
  ?>
  </table>
  </div>
</div>

</div>
</div>
