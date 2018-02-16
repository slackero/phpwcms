<?php
/**
 * cmsGo!
 *
 * @author Pixels & Points GmbH <info@pixels-points.ch>
 * @copyright Copyright (c) 2002-2017, Pixels & Points GmbH
 * @license https://www.pixels-points.ch/cmsgo-license.html Pixels & Points cmsGo! license
 **/

// ----------------------------------------------------------------
// obligate check for cmsgo constants
if (!defined('CMSGO_ROOT')) {
    die("You Cannot Access This Script Directly, Have a Nice Day.");
}
// ----------------------------------------------------------------

?>

<div class="card mt-4">
  <div class="card-header"><h2><?php echo $BLM['listing_user'] ?></h2></div>
    <div class="card-body">
      <table class="table table-sm table-striped mb-0" summary="">
        <tr>
          <th><?php echo $BLM['username'] ?></th>
          <th><?php echo $BLM['userdatum'] ?></th>
        </tr>
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
      </table>
    </div>
  </div>


  </div>
</div>
