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
<h2 class="mb-3"><?php echo $BLM['listing_user'] ?></h2>

<div class="table-responsive">
  <table class="table table-sm table-striped table-hover table-valign-middle mb-0">
    <thead>
      <tr>
        <th><?php echo $BLM['username'] ?></th>
        <th><?php echo $BLM['userdatum'] ?></th>
      </tr>
    </thead>
    <tbody>
      <?php
      // now retrieve all users
      $result = _dbQuery('SELECT * FROM '.DB_PREPEND.'userlog ORDER BY logged_start DESC LIMIT 0,100');
      $x = 0;
      if(isset($result[0]['userlog_id'])) {
        foreach($result as $data) {
          // now add article URL
          echo '  <tr title="'.html_specialchars('[ID:'.$data["userlog_id"].'] '.$data["logged_username"]).'">';
          echo '    <td>'.$data["logged_username"]."&nbsp;</td>" . LF;
          echo '    <td nowrap>&nbsp;'.@date($BL['be_fprivedit_dateformat'], $data["logged_start"])."</td>" . LF;
          echo '  </tr>' . LF;
          $x++;
        }
      }
      ?>
    </tbody>
  </table>
</div>

</div>
</div>
