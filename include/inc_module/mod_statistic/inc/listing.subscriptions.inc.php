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
if (isset($_GET['delete'])) {
    $sql = "DELETE FROM ".DB_PREPEND."phpwcms_log WHERE log_id=".intval($_GET['delete']);
    @_dbQuery($sql, 'DELETE');
}

if (isset($_GET['blacklist'])) {
    $sql = 'DELETE FROM ' . DB_PREPEND . 'phpwcms_log WHERE log_msg LIKE ' . _dbEscapeLike($_GET['blacklist'], true, '%', '');
    @_dbQuery($sql, 'DELETE');
    $sql = 'DELETE FROM ' . DB_PREPEND . 'phpwcms_address WHERE address_email = ' . _dbEscape($_GET['blacklist']) . ' AND address_verified=0';
    @_dbQuery($sql, 'DELETE');
    $sql = 'INSERT INTO ' . DB_PREPEND . "phpwcms_blacklist (blacklist_email, blacklist_tstamp) VALUES (" . _dbEscape($_GET['blacklist']) . ", '" . date('Y-m-d H:i:s') . "')";
    @_dbQuery($sql, 'INSERT');
}
$_controller_link =  statistic_url('controller=subscriptions');
?>

<div class="card mb-3">
  <div class="card-header"><h5 class="mb-0"><?php echo $BLM['listing_subscriptions'] ?></h5></div>
  <div class="card-body">
    <div class="table-responsive">
      <table class="table table-sm table-striped table-hover table-valign-middle mb-0">
        <thead>
          <tr>
            <th><?php echo $BLM['subscriptionsname'] ?></th>
            <th class="text-end" style="width: 150px;"><?php echo $BLM['subscriptionscounts'] ?></th>
          </tr>
        </thead>
        <tbody>
        <?php
        $sql  = "SELECT * FROM " . DB_PREPEND . "phpwcms_subscription ";
        $result = _dbQuery($sql);

        $x = 0;
        foreach($result as $data) {
          echo '  <tr title="'.html_specialchars('[ID:'.$data["subscription_id"].'] '.$data["subscription_name"]).'">';
          echo '    <td><a href="phpwcms.php?do=messages&amp;p=4" target="_blank">' . html_specialchars($data["subscription_name"]). "</a>&nbsp;</td>" . LF;
          echo '    <td class="text-end">'.countNewsletterRecipients(array("0" =>$data["subscription_id"]))."&nbsp;</td>" . LF;
          echo '    </tr>' . LF;
          $x++;
        }
        echo '  <tr title="'.html_specialchars('[ID:'.$data["subscription_id"].'] '.$BLM['subscriptionsall']).'">';
        echo '    <td><a href="phpwcms.php?do=messages&amp;p=4" target="_blank">' . $BLM['subscriptionsall']. "</a>&nbsp;</td>" . LF;
        echo '    <td class="text-end">'.countNewsletterRecipients(0)."&nbsp;</td>" . LF;
        echo '  </tr>' . LF;
        ?>
        </tbody>
      </table>
    </div>
  </div>
</div>

<div class="card mb-3">
  <div class="card-header"><h5 class="mb-0"><?php echo $BLM['listing_activ'] ?></h5></div>
  <div class="card-body">
    <div class="table-responsive">
      <table class="table table-sm table-striped table-hover table-valign-middle mb-0">
        <thead>
          <tr>
            <th><?php echo $BLM['subscriptionstatus'] ?></th>
            <th class="text-end" style="width: 150px;"><?php echo $BLM['subscriptionscounts'] ?></th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td><?php echo $BLM['subscriptionactiv'] ?></td>
            <td class="text-end"><?php
              $sql  = "SELECT COUNT(*) FROM ".DB_PREPEND."phpwcms_address WHERE address_verified=1";
              echo _dbQuery($sql, 'COUNT');
            ?></td>
          </tr>
          <tr>
            <td><?php echo $BLM['subscriptioninactiv'] ?></td>
            <td class="text-end"><?php
              $sql  = "SELECT COUNT(*) FROM ".DB_PREPEND."phpwcms_address WHERE address_verified=0";
              echo _dbQuery($sql, 'COUNT');
            ?></td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</div>

<div class="card mb-0">
  <div class="card-header"><h5 class="mb-0"><?php echo $BLM['listing_subscriptions2'] ?></h5></div>
  <div class="card-body">
    <div class="alert alert-info py-2 px-3 mb-3">
      <?php
      echo $BLM['listing_Colum2'].': <strong>'.$total[1].'</strong> &nbsp;|&nbsp; ';
      echo $BLM['listing_Colum3'].': <strong>'.$total[2].'</strong> &nbsp;|&nbsp; ';
      echo $BLM['listing_Colum4'].': <strong>'.$total[3].'</strong> &nbsp;|&nbsp; ';
      echo $BLM['listing_Colum5'].': <strong>'.$total[4].'</strong>';
      ?>
    </div>

    <div class="table-responsive mb-3">
      <table class="table table-sm table-striped table-hover table-valign-middle mb-0">
        <thead>
          <tr>
            <th><?php echo $BLM['subscriptionslogdate'] ?></th>
            <th class="text-center" style="width: 80px;"><?php echo $BLM['subscriptionslogtyp'] ?></th>
            <th><?php echo $BLM['subscriptionslogart'] ?></th>
            <th class="text-end" style="width: 150px;">Aktionen</th>
          </tr>
        </thead>
        <tbody>
        <?php
        $sql  = "SELECT * FROM " . DB_PREPEND . "phpwcms_log lo LEFT JOIN " . DB_PREPEND . "phpwcms_address ad ON lo.log_user_id =ad.address_id WHERE log_type = '1' OR log_type = '2'  OR log_type = '3' OR log_type = '4' ORDER BY lo.log_created  DESC LIMIT 0,200";
        $result2 = _dbQuery($sql);

        $x = 0;
        foreach($result2 as $data) {
          echo '  <tr title="'.html_specialchars('[ID:'.$data["log_user_id"].'] '.(isset($data["subscription_name"]) ? $data["subscription_name"] : '')).'">';
          echo '    <td class="align-middle text-nowrap">'.$data["log_created"]."&nbsp;</td>" . LF;
          echo '    <td class="align-middle text-center">'.$data["log_type"]."&nbsp;</td>" . LF;
          if ($data["log_type"] == 1) {
            echo '    <td width="70%" class="align-middle"><a href="phpwcms.php?do=messages&amp;p=4&amp;s='.$data["log_user_id"].'&amp;edit=1" target="_blank">' . html_specialchars($data["log_msg"]). "</a>&nbsp;</td>" . LF;
            echo '    <td class="text-end p-1 align-middle text-nowrap">';
            echo '      <a class="btn btn-sm '.($data["address_verified"] ? 'btn-success' : 'btn-warning').'" href="phpwcms.php?do=messages&amp;p=4&amp;s='.$data["address_id"].'&amp;verify='.($data["address_verified"] ? '0' : '1').'" title="set '.$data["address_email"].' verified/not verified"><i class="fas '.($data["address_verified"] ? 'fa-check' : 'fa-clock').'"></i></a>';
          } else {
            echo '    <td width="70%" class="align-middle">' . html_specialchars($data["log_msg"]). "&nbsp;</td>" . LF;
            echo '    <td class="text-end p-1 align-middle text-nowrap">';
          }

          echo '      <a class="btn btn-sm btn-danger ms-1" href="'.$_controller_link.'&amp;delete='.$data["log_id"].'" title="delete: '.html_specialchars($data["log_msg"]).'" onclick="return confirm(\''.$BLM['log_delete_entry'].' \');"><i class="fas fa-trash-alt"></i></a>';

          if (stristr($data["log_msg"], '::')) {
            $email_array = explode('::', $data["log_msg"]);
            echo '    <a class="btn btn-sm btn-dark ms-1" href="'.$_controller_link.'&amp;blacklist='.$email_array[1].'" title="Add to blacklist: '.html_specialchars($email_array[1]).'" onclick="return confirm(\''.html_specialchars($email_array[1]).' '.$BLM['log_insert_blacklist'].' \');"><i class="fas fa-ban"></i></a>';
          }
          echo '    </td></tr>' . LF;
          $x++;
        }
        ?>
        </tbody>
      </table>
    </div>
    <div class="text-muted small"><?php echo $BLM['subscriptionslegend'] ?></div>
  </div>
</div>

</div>
</div>
