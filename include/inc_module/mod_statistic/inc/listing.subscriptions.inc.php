<?php
/**
 * cmsGO!
 *
 * @author Pixels & Points GmbH <info@pixels-points.ch>
 * @copyright Copyright (c) 2002-2024, Pixels & Points GmbH
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
if (isset($_GET['delete'])) {
    $sql = "DELETE FROM ".DB_PREPEND."cmsgo_log WHERE log_id=".intval($_GET['delete']);
    @_dbQuery($sql, 'DELETE');
}

if (isset($_GET['blacklist'])) {
    $sql = "DELETE FROM ".DB_PREPEND."cmsgo_log WHERE log_msg like '%".aporeplace($_GET['blacklist'])."'";
    @_dbQuery($sql, 'DELETE');
    $sql = "DELETE FROM ".DB_PREPEND."cmsgo_address WHERE address_email like '".aporeplace($_GET['blacklist'])."' AND address_verified=0";
    @_dbQuery($sql, 'DELETE');
    $sql  = 'INSERT INTO '.DB_PREPEND."cmsgo_blacklist (blacklist_email, blacklist_tstamp) VALUES ('".aporeplace($_GET['blacklist'])."','".date('Y-m-d H:m:s')."')";
    @_dbQuery($sql, 'INSERT');
}
$_controller_link =  statistic_url('controller=subscriptions');
?>



<div class="card mt-4">
  <div class="card-header"><h2><?php echo $BLM['listing_subscriptions'] ?></h2></div>
  <div class="card-body">
    <table class="table table-sm mb-0">
      <tr>
        <th><?php echo $BLM['subscriptionsname'] ?></th>
        <th><?php echo $BLM['subscriptionscounts'] ?></th>
      </tr>
<?php
$sql  = "SELECT * FROM " . DB_PREPEND . "cmsgo_subscription ";
$result = _dbQuery($sql);

$x = 0;

foreach($result as $data) {
  // now add article URL
  echo '  <tr title="'.html_specialchars('[ID:'.$data["subscription_id"].'] '.$data["subscription_name"]).'">';
    echo '    <td><a href="cmsgo.php?do=messages&p=4" target="_blank">' . html_specialchars($data["subscription_name"]). "</a>&nbsp;</td>" . LF;
  echo '    <td>'.countNewsletterRecipients(array("0" =>$data["subscription_id"]))."&nbsp;</td>" . LF;
  echo '    </tr>' . LF;
  $x++;
}
echo '  <tr title="'.html_specialchars('[ID:'.$data["subscription_id"].'] '.$BLM['subscriptionsall']).'">';
echo '    <td><a href="cmsgo.php?do=messages&p=4" target="_blank">' . $BLM['subscriptionsall']. "</a>&nbsp;</td>" . LF;
echo '    <td>'.countNewsletterRecipients(0)."&nbsp;</td>" . LF;
echo '  </tr>' . LF;
?>
    </table>
  </div>
</div>

<div class="card mt-4">
  <div class="card-header"><h2><?php echo $BLM['listing_activ'] ?></h2></div>
  <div class="card-body">
    <table class="table table-sm mb-0">
      <tr>
        <th><?php echo $BLM['subscriptionstatus'] ?></th>
        <th><?php echo $BLM['subscriptionscounts'] ?></th>
      </tr>
      <tr>
          <td><?php echo $BLM['subscriptionactiv'] ?></td>
          <td><?php
          $sql  = "SELECT COUNT(*) FROM ".DB_PREPEND."cmsgo_address WHERE address_verified=1";
          echo _dbQuery($sql, 'COUNT');
          ?></td>
      </tr>
      <tr>
          <td><?php echo $BLM['subscriptioninactiv'] ?></td>
          <td><?php
          $sql  = "SELECT COUNT(*) FROM ".DB_PREPEND."cmsgo_address WHERE address_verified=0";
          echo _dbQuery($sql, 'COUNT');
          ?></td>
      </tr>
    </table>
  </div>
</div>

<div class="card mt-4">
  <div class="card-header"><h2><?php echo $BLM['listing_subscriptions2'] ?></h2></div>
  <div class="card-body">
    <?php
    echo $BLM['listing_Colum2'].': '.$total[1].'<br>';
    echo $BLM['listing_Colum3'].': '.$total[2].'<br>';
    echo $BLM['listing_Colum4'].': '.$total[3].'<br>';
    echo $BLM['listing_Colum5'].': '.$total[4].'<br>';
    ?>

    <table class="table table-sm mb-0">
      <tr>
        <th><?php echo $BLM['subscriptionslogdate'] ?></th>
        <th><?php echo $BLM['subscriptionslogtyp'] ?></th>
        <th><?php echo $BLM['subscriptionslogart'] ?></th>
        <th></th>
        <th></th>
        <th><?php echo $BLM['log_blacklist'] ?></th>
      </tr>
    <?php
    $sql  = "SELECT * FROM " . DB_PREPEND . "cmsgo_log lo LEFT JOIN " . DB_PREPEND . "cmsgo_address ad ON lo.log_user_id =ad.address_id WHERE log_type = '1' OR log_type = '2'  OR log_type = '3' OR log_type = '4' ORDER BY lo.log_created  DESC LIMIT 0,200";
    $result2 = _dbQuery($sql);

    $x = 0;

    foreach($result2 as $data) {

      // now add article URL
      echo '  <tr title="'.html_specialchars('[ID:'.$data["log_user_id"].'] '.$data["subscription_name"]).'">';
      echo '    <td nowrap="nowrap">'.$data["log_created"]."&nbsp;</td>" . LF;
      echo '    <td>'.$data["log_type"]."&nbsp;</td>" . LF;
      if ($data["log_type"] == 1) {
          echo '    <td width="70%"><a href="cmsgo.php?do=messages&p=4&s='.$data["log_user_id"].'&edit=1" target="_blank">' . html_specialchars($data["log_msg"]). "</a>&nbsp;</td>" . LF;
      echo '<td align="right" nowrap="nowrap" class="button_td">';

      echo '<a href="cmsgo.php?do=messages&amp;p=4&amp;s='.$data["address_id"].'&amp;verify=';
      echo ($row["address_verified"]) ? '0' : '1';
      echo '" title="set '.$data["address_email"].' verified/not verified">';
      echo '<img src="img/button/aktiv_12x13_'.$data["address_verified"].'.gif" border="0" alt=""></a>';

      } else {
          echo '    <td width="70%">' . html_specialchars($data["log_msg"]). "&nbsp;</td><td>&nbsp;</td>" . LF;
      }
      echo '<td><a href="'.$_controller_link.'&amp;delete='.$data["log_id"];
      echo '" title="delete: '.html_specialchars($data["log_msg"]).'"';
      echo ' onclick="return confirm(\''.$BLM['log_delete_entry'].' \');">';
      echo '<img src="img/button/trash_13x13_1.gif" border="0" alt=""></a></td><td>';
      if (stristr($data["log_msg"], '::')) {
        $email_array = explode('::', $data["log_msg"]);

        echo '<a href="'.$_controller_link.'&amp;blacklist='.$email_array[1];
        echo '" title="Add to blacklist: '.html_specialchars($email_array[1]).'"';
        echo ' onclick="return confirm(\''.html_specialchars($email_array[1]).' '.$BLM['log_insert_blacklist'].' \');">';
        echo '<img src="img/famfamfam/delete.gif" border="0" alt=""></a>';
      }
      echo '    </td></tr>' . LF;

      $x++;
    }
    ?>
    </table>

<?php echo $BLM['subscriptionslegend'] ?>
  </div>
</div>

</div>
</div>
