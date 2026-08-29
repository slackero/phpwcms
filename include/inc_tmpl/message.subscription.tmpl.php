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

// newsletter subscription

if(isset($_GET["s"]) && isset($_GET['edit'])) {
    echo '<h1 class="text-center text-sm-start">'.$BL['be_newsletter_title'].'</h1>';
    include_once PHPWCMS_ROOT.'/include/inc_tmpl/subscription.form.tmpl.php';
}

// delete subscription
if(isset($_GET["del"]) && isset($_GET["s"]) && $_GET["del"] == $_GET["s"]) {
  _dbQuery("DELETE FROM ".DB_PREPEND."phpwcms_subscription WHERE subscription_id=".intval($_GET["del"])." LIMIT 1", 'DELETE');
}

if(!isset($_GET["edit"])) {
?>
<div class="row">
  <div class="col">
    <h1 class="text-center text-sm-start"><?php echo $BL['be_newsletter_title'] ?></h1>
  </div>
  <div class="col text-center text-sm-end mb-3">
    <a class="btn btn-sm btn-blue" href="phpwcms.php?do=messages&amp;p=2&amp;s=0&amp;edit=1" title="<?php echo $BL['be_newsletter_add'] ?>"><i class="fa fa-plus me-1"></i> <?php echo $BL['be_mailinglist_new'] ?></a>
  </div>
</div>
<?php } ?>

<div class="card">
  <div class="card-header"><h2><i class="fa fa-list" aria-hidden="true"></i> <?php echo $BL['be_cnt_title_overview'] ?> <?php echo $BL['be_newsletter_title'] ?></h2></div>
    <div class="card-body">
    <div class="table-responsive">
    <table class="table table-sm table-valign-middle mb-0">
    <?php
    // loop listing available subscriptions
    $sql = "SELECT * FROM ".DB_PREPEND."phpwcms_subscription ORDER BY subscription_name";
    $result = _dbQuery($sql);
    if(isset($result[0]['subscription_id'])) {
        $row_count = 0;
        echo '<tr'.( ($row_count % 2) ? ' bgcolor="#f4f4f4"' : '' ).">\n".LF;
        echo '<td>'.html($BL['be_newsletter_allsubscriptions'])."</td>\n";
        echo '<td class="text-end text-nowrap">'.countNewsletterRecipients(0)." ".$BL['be_mailinglist_overview_subscribers']."</td>\n<td></td>\n</tr>\n";
        $row_count++;
        foreach($result as $row) {
            echo '<tr'.( ($row_count % 2) ? ' bgcolor="#f4f4f4"' : '' ).">\n".LF;
            echo '<td>';
            echo '<a href="phpwcms.php?do=messages&amp;p=2&amp;s='.$row["subscription_id"].'&amp;edit=1">';
            echo '<strong>'.html($row["subscription_name"])."</strong></a></td>\n";
            echo '<td class="text-end text-nowrap">';
            $subscribers = countNewsletterRecipients(array("0" =>$row["subscription_id"]));
            if ($subscribers>0) {
              echo $subscribers." ".$BL['be_mailinglist_overview_subscribers'];
            } else {
              echo "0 ".$BL['be_mailinglist_overview_subscribers'];
            }
            echo "</td>\n";
            echo '<td class="text-end text-nowrap">';
            echo '<div class="btn-group btn-group-sm" role="group" aria-label="subscr-actions-'.$row["subscription_id"].'">';
            echo '<a class="btn btn-sm btn-blue" role="button" aria-disabled="true" title="'.$BL['be_tt_edit'].'" data-bs-toggle="tooltip" href="phpwcms.php?do=messages&amp;p=2&amp;s='.$row["subscription_id"].'&amp;edit=1"><i class="fa fa-pencil-alt"></i></a>';
            echo '<button id="abtnsubscription'.$row["subscription_id"].'" class="btn fa btn-sm visible '.($row["subscription_active"]==0 ? "btn-warning" : "btn-success").'" data-id="'.$row["subscription_id"].'" data-type="subscription"  data-table="subscription" data-field="subscription_active" data-fieldid="subscription_id" aria-disabled="true" data-bs-toggle="tooltip" title="set '.$row["subscription_name"].' verified/not verified"></button>';
            echo '</div>';
            if ($subscribers>0) {
              echo '<div class="btn btn-sm btn-danger disabled ms-1" role="button" aria-disabled="true" title="'.$BL['be_mailinglist_cannotdelete_list'].': '.html_specialchars($row["subscription_name"]).'" data-bs-toggle="tooltip" href="#"><i class="far fa-trash-alt"></i></div>';
            } else {
              echo '<a class="btn btn-sm btn-danger ms-1" role="button" aria-disabled="true" title="'.$BL['be_mailinglist_delete_list'].': '.html_specialchars($row["subscription_name"]).'" data-bs-toggle="tooltip" href="phpwcms.php?do=messages&amp;p=2&amp;s='.$row["subscription_id"].'&amp;del='.$row["subscription_id"]. '" onclick="return confirm(\''.$BL['be_mailinglist_delete_list'].' '.js_singlequote($row["subscription_name"]).'\');"><i class="far fa-trash-alt"></i></a>';
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
<div class="form-group text-center text-sm-end mt-4">
  <a class="btn btn-sm btn-blue" href="phpwcms.php?do=messages&amp;p=2&amp;s=0&amp;edit=1" title="<?php echo $BL['be_newsletter_add'] ?>"><i class="fa fa-plus me-1"></i> <?php echo $BL['be_mailinglist_new'] ?></a>
</div>
<?php } ?>
