<?php
/**
 * cmsGO!
 *
 * @author Pixels & Points GmbH <info@pixels-points.ch>
 * @copyright Copyright (c) 2002-2021, Pixels & Points GmbH
 * @license https://www.pixels-points.ch/cmsgo-license.html Pixels & Points cmsGO! license
 *
 **/

// ----------------------------------------------------------------
// obligate check for cmsgo constants
if (!defined('CMSGO_ROOT')) {
    die("You Cannot Access This Script Directly, Have a Nice Day.");
}
// ----------------------------------------------------------------

$subscription["id"] = intval($_GET["s"]);

if(isset($_POST["subscription_id"])) {

    // read the create or edit subscription form data
    $subscription["id"]         = intval($_POST["subscription_id"]);
    $subscription["name"]       = clean_slweg($_POST["subscription_name"]);
    if(empty($subscription["name"])) {
        $subscription["name"] = "subscription_".generic_string(3);
    }
    $subscription["info"]       = clean_slweg($_POST["subscription_info"]);

    if($subscription["id"]) {
        $query_mode = 'UPDATE';
        $sql =  "UPDATE ".DB_PREPEND."cmsgo_subscription SET ".
                "subscription_name='".aporeplace($subscription["name"])."', ".
                "subscription_info='".aporeplace($subscription["info"])."' ".
                "WHERE subscription_id=".$subscription["id"];
    } else {
        $query_mode = 'INSERT';
        $sql =  "INSERT INTO ".DB_PREPEND."cmsgo_subscription (".
                "subscription_name, subscription_info) VALUES ('".
                aporeplace($subscription["name"])."', '".
                aporeplace($subscription["info"])."')";
    }
    // update or insert data entry
    $result = _dbQuery($sql, $query_mode);

    if($query_mode === 'INSERT' && isset($result['INSERT_ID'])) {
        $subscription["id"] = $result['INSERT_ID'];
    }

    if($subscription["id"]) {
        headerRedirect(CMSGO_URL.'cmsgo.php?'.get_token_get_string().'&do=messages&p=2&s='.$subscription["id"]);
    }
}

if($subscription["id"]) {
// read the given subscription datas from db
    $sql = "SELECT * FROM ".DB_PREPEND."cmsgo_subscription WHERE subscription_id=".$subscription["id"]." LIMIT 1";
    $result = _dbQuery($sql);
    if(isset($result[0]['subscription_id'])) {
        $subscription["id"] = $result[0]["subscription_id"];
        $subscription["name"] = html($result[0]["subscription_name"]);
        $subscription["info"] = html($result[0]["subscription_info"]);
    }
}

// show form
?>
<form action="cmsgo.php?do=messages&amp;p=2&amp;s=<?php echo $subscription["id"] ?>&amp;edit=1" method="post" name="subscriptions" id="subscriptions">
<input name="subscription_id" type="hidden" value="<?php echo $subscription["id"] ?>" />
  <div class="card mb-2">
    <div class="card-header"><h2><?php echo ($subscription["id"] == 0 ? $BL['be_newsletter_add'] : $BL['be_newsletter_titleedit']) ?></h2></div>
    <div class="card-body">

      <div class="form-group form-row align-items-center">
          <label for="subscription_name" class="col-sm-2 col-form-label text-right"><?php echo $BL['be_newsletter_name'] ?></label>
          <div class="col-sm-10">
            <input type="text" class="form-control form-control-sm" name="subscription_name" id="subscription_name" value="<?php echo  empty($subscription["name"]) ? '' : html($subscription["name"]) ?>" size="50" maxlength="250"  required />
          </div>
      </div>

      <div class="form-group form-row">
          <label for="subscription_name" class="col-sm-2 col-form-label text-right"><?php echo $BL['be_newsletter_info'] ?></label>
          <div class="col-sm-10">
            <textarea name="subscription_info" cols="35" rows="6" class="form-control form-control-sm autosize" id="subscription_info"><?php echo empty($subscription["info"]) ? '' : html($subscription["info"]); ?></textarea>
          </div>
      </div>

      <div class="form-group row">
        <div class="col-sm-2"></div>
        <div class="col-sm-10">
          <input name="Submit" type="submit" class="btn btn-sm btn-blue mt-1" value="<?php echo ($subscription["id"] == 0 ? $BL['be_newsletter_add'] : $BL['be_newsletter_button_save']) ?>" />
          <input type="button" class="btn btn-sm btn-blue mt-1" value="<?php echo $BL['be_newsletter_button_cancel'] ?>" onclick="location.href='cmsgo.php?do=messages&amp;p=2';" />
        </div>
      </div>

    </div>
  </div>
</form>