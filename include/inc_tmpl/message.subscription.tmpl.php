<?php
/**
 * cmsGO!
 *
 * @author Pixels & Points GmbH <info@pixels-points.ch>
 * @copyright Copyright (c) 2002-2018, Pixels & Points GmbH
 * @license https://www.pixels-points.ch/cmsgo-license.html Pixels & Points cmsGO! license
 *
 **/

// ----------------------------------------------------------------
// obligate check for cmsgo constants
if (!defined('CMSGO_ROOT')) {
    die("You Cannot Access This Script Directly, Have a Nice Day.");
}
// ----------------------------------------------------------------

// newsletter subscription

if(isset($_GET["s"]) && isset($_GET['edit'])) {
    echo '<h1 class="text-center text-sm-left">'.$BL['be_newsletter_title'].'</h1>';
    include_once CMSGO_ROOT.'/include/inc_tmpl/subscription.form.tmpl.php';
}

// delete subscription
if(isset($_GET["del"]) && isset($_GET["s"]) && $_GET["del"] == $_GET["s"]) {
  _dbQuery("DELETE FROM ".DB_PREPEND."cmsgo_subscription WHERE subscription_id=".intval($_GET["del"])." LIMIT 1", 'DELETE');
}

if(!isset($_GET["edit"])) {
?>
<div class="row">
  <div class="col">
    <h1 class="text-center text-sm-left"><?php echo $BL['be_newsletter_title'] ?></h1>
  </div>
  <div class="col text-center text-sm-right mb-3">
    <div class="form-group align-items-center">
        <form action="cmsgo.php?do=messages&amp;p=2&amp;s=0&amp;edit=1" method="post"><input type="submit" value="<?php echo $BL['be_mailinglist_new'] ?>" class="btn btn-sm btn-blue" title="<?php echo $BL['be_newsletter_add'] ?>"></form>
    </div>
  </div>
</div>
<?php } ?>

<div class="card">
  <div class="card-header"><h2><i class="fa fa-list" aria-hidden="true"></i> <?php echo $BL['be_cnt_title_overview'] ?> <?php echo $BL['be_newsletter_title'] ?></h2></div>
    <div class="card-body">
    <div class="table-responsive">
    <table class="table table-sm mb-0">
    <?php
    // loop listing available subscriptions
    $sql = "SELECT * FROM ".DB_PREPEND."cmsgo_subscription ORDER BY subscription_name";
    $result = _dbQuery($sql);
    if(isset($result[0]['subscription_id'])) {
        $row_count = 0;
        echo '<tr'.( ($row_count % 2) ? ' bgcolor="#f4f4f4"' : '' ).">\n".LF;
        echo '<td>'.html($BL['be_newsletter_allsubscriptions'])."</td>\n";
        echo '<td nowrap="nowrap" class="text-right">'.countNewsletterRecipients(0)." ".$BL['be_mailinglist_overview_subscribers']."</td>\n<td></td>\n</tr>\n";
        $row_count++;
        foreach($result as $row) {
            echo '<tr'.( ($row_count % 2) ? ' bgcolor="#f4f4f4"' : '' ).">\n".LF;
            echo '<td>';
            echo '<a href="cmsgo.php?do=messages&amp;p=2&amp;s='.$row["subscription_id"].'&amp;edit=1">';
            echo '<strong>'.html($row["subscription_name"])."</strong></a></td>\n";
            echo '<td nowrap="nowrap" class="text-right">';
            $subscribers = countNewsletterRecipients(array("0" =>$row["subscription_id"]));
            if ($subscribers>0) {
              echo $subscribers." ".$BL['be_mailinglist_overview_subscribers'];
            } else {
              echo "0 ".$BL['be_mailinglist_overview_subscribers'];
            }
            echo "</td>\n";
            echo '<td nowrap="nowrap" class="text-right">';
            echo '<a class="btn btn-sm btn-blue mr-1" role="button" aria-disabled="true" title="'.$BL['be_tt_edit'].'" data-toggle="tooltip" href="cmsgo.php?do=messages&amp;p=2&amp;s='.$row["subscription_id"].'&amp;edit=1"><i class="fa fa-pencil"></i></a>';
            echo '<button id="abtnsubscription'.$row["subscription_id"].'" class="btn fa btn-sm visible '.($row["subscription_active"]==0 ? "btn-danger" : "btn-success").' mr-1" data-id="'.$row["subscription_id"].'" data-type="subscription"  data-table="subscription" data-field="subscription_active" data-fieldid="subscription_id" aria-disabled="true" data-toggle="tooltip" title="set '.$row["subscription_name"].' verified/not verified"></button>';
            if ($subscribers>0) {
              echo '<div class="btn btn-sm btn-danger disabled" role="button" aria-disabled="true" title="'.$BL['be_mailinglist_cannotdelete_list'].': '.html_specialchars($row["subscription_name"]).'" data-toggle="tooltip" href="#"><i class="fa fa-trash"></i></div>';
            } else {
              echo '<a class="btn btn-sm btn-danger" role="button" aria-disabled="true" title="'.$BL['be_mailinglist_delete_list'].': '.html_specialchars($row["subscription_name"]).'" data-toggle="tooltip" href="cmsgo.php?do=messages&amp;p=2&amp;s='.$row["subscription_id"].'&amp;del='.$row["subscription_id"]. '" onclick="return confirm(\''.$BL['be_mailinglist_delete_list'].' '.js_singlequote($row["subscription_name"]).'\');"><i class="fa fa-trash"></i></a>';
            }

            echo "</td>\n</tr>\n";
            $row_count++;
        }
    } // end listing
    ?>
    </table>
    </div>

  </div>
</div>

<?php if(!isset($_GET["edit"])) {
?>
<div class="form-group text-center text-sm-right mt-4">
  <form action="cmsgo.php?do=messages&amp;p=2&amp;s=0&amp;edit=1" method="post"><input type="submit" value="<?php echo $BL['be_mailinglist_new'] ?>" class="btn btn-sm btn-blue" title="<?php echo $BL['be_newsletter_add'] ?>"></form>
</div>
<?php } ?>