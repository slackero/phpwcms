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

?>
<h2 class="mb-3"><?php echo $BLM['listing_user'] ?></h2>

<div class="table-responsive">
  <table class="table table-sm table-striped table-hover mb-0">
    <thead>
      <tr>
        <th><?php echo $BLM['username'] ?></th>
        <th><?php echo $BLM['userdatum'] ?></th>
      </tr>
    </thead>
    <tbody>
      <?php
      // now retrieve all users
      $result = _dbQuery('SELECT * FROM '.DB_PREPEND.'cmsgo_userlog ORDER BY logged_start DESC LIMIT 0,100');
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
