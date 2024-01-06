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

//newsletter subscription
?>

<div class="form-group form-row">
  <label for="cnewsletter_subscription" class="col-sm-2 col-form-label text-right"><?php echo $BL['be_cnt_subscription'] ?></label>
  <div class="col">
      <?php

    $content["newsletter"]['left']  = array();
    $content["newsletter"]['right'] = array();
    if(empty($content["newsletter"]["recaptcha"])) {
        $content["newsletter"]["recaptcha"] = 0;
    }
    if(empty($content["newsletter"]["recaptcha_config"])) {
        $content["newsletter"]["recaptcha_config"] = "site_key = \nsecret_key = \ntype = image";
    }

    // default = all subscriptions
    $content["newsletter"]['right'][0] = $BL['be_newsletter_allsubscriptions'];

    // retrieve all available subscriptions first
    $result = _dbQuery("SELECT * FROM ".DB_PREPEND."cmsgo_subscription ORDER BY subscription_name");
    foreach($result as $row) {
        $content["newsletter"]['right'][ $row["subscription_id"] ] = html($row["subscription_name"]);
    }

    if(isset($content["newsletter"]["subscription"]) && is_array($content["newsletter"]["subscription"])) {
        foreach($content["newsletter"]["subscription"] as $row => $result) {
            if(isset($content["newsletter"]['right'][ $row ])) {
                $content["newsletter"]['left'][ $row ] = $content["newsletter"]['right'][ $row ];
                unset($content["newsletter"]['right'][ $row ]);
            }
        }
    }

    echo createOptionTransferSelectList(
        'cnewsletter_subscription',
        $content["newsletter"]['left'],
        $content["newsletter"]['right'],
        array(
            'class' => 'form-control form-control-sm optionTransfer',
            'formname' => 'articlecontent',
            'rows' => 7
        )
    );

    ?>
   </div>
</div>

<div class="form-group align-items-center form-row">
  <label for="cnewsletter_label_email" class="col-sm-2 col-form-label text-right"><?php echo $BL['be_cnt_labelemail'] ?></label>
  <div class="col-sm-4">
    <input name="cnewsletter_label_email" type="text" id="cnewsletter_label_email" class="form-control form-control-sm" value="<?php echo  isset($content["newsletter"]["label_email"]) ? $content["newsletter"]["label_email"] : '' ?>" maxlength="100">
  </div>
    <label class="col-sm-2 col-form-label text-sm-right mt-3 mt-sm-0">
        <strong><?php
        echo $BL['be_cnt_tablealign'];
        if(empty($content["newsletter"]["pos"])) {
            $content["newsletter"]["pos"] = 0;
        }
        ?></strong>
    </label>
  <div class="col">
    <select name="cnewsletter_pos" id="cnewsletter_pos" class="custom-select form-control form-control-sm">
      <option value="0" <?php is_selected(0, $content["newsletter"]["pos"]) ?>><?php echo $BL['be_cnt_default'] ?></option>
      <option value="1" <?php is_selected(1, $content["newsletter"]["pos"]) ?>><?php echo $BL['be_cnt_left'] ?></option>
      <option value="2" <?php is_selected(2, $content["newsletter"]["pos"]) ?>><?php echo $BL['be_cnt_center'] ?></option>
      <option value="3" <?php is_selected(3, $content["newsletter"]["pos"]) ?>><?php echo $BL['be_cnt_right'] ?></option>
    </select>
  </div>
</div>

<div class="form-group align-items-center form-row">
  <label for="cnewsletter_label_name" class="col-sm-2 col-form-label text-right"><?php echo $BL['be_cnt_labelname'] ?></label>
  <div class="col-sm-4">
    <input name="cnewsletter_label_name" type="text" id="cnewsletter_label_name" class="form-control form-control-sm" value="<?php echo isset($content["newsletter"]["label_name"]) ? html($content["newsletter"]["label_name"]) : ''; ?>" maxlength="100">
  </div>
  <label class="col-sm-2 col-form-label text-sm-right mt-3 mt-sm-0">
    <?php echo $BL['be_cnt_buttontext'] ?>
  </label>
  <div class="col">
    <input name="cnewsletter_button_text" type="text" id="cnewsletter_button_text" class="form-control form-control-sm" value="<?php echo  isset($content["newsletter"]["button_text"]) ? html($content["newsletter"]["button_text"]) : ''; ?>" maxlength="50"></td>
  </div>
</div>

<div class="form-group align-items-center form-row">
  <label for="cnewsletter_label_subscriptions" class="col-sm-2 col-form-label text-right"><?php echo $BL['be_cnt_labelsubsc'] ?></label>
  <div class="col-sm-4">
    <input name="cnewsletter_label_subscriptions" type="text" id="cnewsletter_label_subscriptions" class="form-control form-control-sm" value="<?php echo isset($content["newsletter"]["label_subscriptions"]) ? html($content["newsletter"]["label_subscriptions"]) : ''; ?>" size="20" maxlength="100">
  </div>
  <label class="col-sm-2 col-form-label text-sm-right mt-3 mt-sm-0">
    <?php echo $BL['be_cnt_allsubsc'] ?>
  </label>
  <div class="col-sm-4">
    <input name="cnewsletter_all_subscriptions" type="text" id="cnewsletter_all_subscriptions" class="form-control form-control-sm" value="<?php echo isset($content["newsletter"]["all_subscriptions"]) ? html($content["newsletter"]["all_subscriptions"]) : '' ?>" maxlength="50"></td>
  </div>
</div>

<div class="form-group form-row">
  <label for="cnewsletter_text" class="col-sm-2 col-form-label text-right"><?php echo $BL['be_cnt_infotext'] ?></label>
  <div class="col">
      <textarea name="cnewsletter_text" rows="4" class="form-control form-control-sm" id="cnewsletter_text"><?php echo isset($content["newsletter"]["text"]) ? html($content["newsletter"]["text"]) : ''; ?></textarea>
  </div>
</div>

<div class="form-group form-row">
  <label for="cnewsletter_success_text" class="col-sm-2 col-form-label text-right"><?php echo $BL['be_cnt_successtext'] ?></label>
  <div class="col">
     <textarea name="cnewsletter_success_text" rows="4" class="form-control form-control-sm" id="cnewsletter_success_text"><?php echo isset($content["newsletter"]["success_text"]) ? html($content["newsletter"]["success_text"]) : ''; ?></textarea>
  </div>
</div>

<div class="form-group align-items-center form-row">
  <label for="cnewsletter_url1" class="col-sm-2 col-form-label text-right">URL 1</label>
  <div class="col-sm-4">
     <input name="cnewsletter_url1" type="text" id="cnewsletter_url1" class="form-control form-control-sm" value="<?php echo isset($content["newsletter"]["url1"]) ? html($content["newsletter"]["url1"]) : '' ?>" />
  </div>
</div>

<div class="form-group align-items-center form-row">
  <label for="cnewsletter_url2" class="col-sm-2 col-form-label text-right">URL 2</label>
  <div class="col-sm-4">
    <input name="cnewsletter_url2" type="text" id="cnewsletter_url2" class="form-control form-control-sm" value="<?php echo isset($content["newsletter"]["url2"]) ? html($content["newsletter"]["url2"]) : '' ?>"  />
  </div>
</div>

<div class="form-group align-items-center form-row">
  <label for="cnewsletter_recaptcha" class="col-sm-2 col-form-label text-right"><?php echo $BL['be_cnt_field']['recaptcha']; ?></label>
  <div class="col-sm-4">
    <select name="cnewsletter_recaptcha" class="custom-select form-control form-control-sm">
      <option value="0" <?php is_selected(0, $content["newsletter"]["recaptcha"]) ?>><?php echo $BL['be_off']; ?></option>
      <option value="2" <?php is_selected(2, $content["newsletter"]["recaptcha"]) ?>><?php echo $BL['be_cnt_field']['recaptchainv']; ?></option>
      <option value="1" <?php is_selected(1, $content["newsletter"]["recaptcha"]) ?>><?php echo $BL['be_cnt_field']['recaptcha']; ?> v2</option>
    </select>
  </div>
</div>
<div class="form-group form-row">
  <label for="cnewsletter_recaptcha_config" class="col-sm-2 col-form-label text-right"><?php echo $BL['be_settings']; ?></label>
  <div class="col">
    <textarea name="cnewsletter_recaptcha_config" rows="3" class="form-control form-control-sm" placeholder="site_key = &#10;secret_key = &#10;type = image"><?php echo html($content["newsletter"]["recaptcha_config"]); ?></textarea>
    <div class="mt-2">
      <a href="https://www.google.com/recaptcha/admin" target="_blank" style="text-decoration:underline;"><?php echo $BL['be_cnt_field']['recaptcha_signapikey']; ?></a>
    </div>
  </div>
</div>
<hr />
<div class="bg-grey p-2 mb-3">{NEWSLETTER_NAME}, {NEWSLETTER_EMAIL}, {NEWSLETTER_VERIFY}, {NEWSLETTER_DELETE}, {IP}, {DATE:m/d/Y}, [SUBJECT][/SUBJECT]</div>

<div class="form-group form-row">
  <label for="cnewsletter_reg_text" class="col-sm-2 col-form-label text-right"><?php echo $BL['be_cnt_regmail'] ?></label>
  <div class="col">
    <textarea name="cnewsletter_reg_text" rows="4" wrap="off" class="form-control form-control-sm" id="cnewsletter_reg_text"><?php echo isset($content["newsletter"]["reg_text"]) ? html($content["newsletter"]["reg_text"]) : ''; ?></textarea>
  </div>
</div>
<div class="form-group form-row">
  <label for="cnewsletter_logoff_text" class="col-sm-2 col-form-label text-right"><?php echo $BL['be_cnt_logoffmail'] ?></label>
  <div class="col">
    <textarea name="cnewsletter_logoff_text" rows="4" wrap="off" class="form-control form-control-sm" id="cnewsletter_logoff_text"><?php echo isset($content["newsletter"]["logoff_text"]) ? html($content["newsletter"]["logoff_text"]) : ''; ?></textarea>
  </div>
</div>

<div class="form-group form-row">
  <label for="cnewsletter_change_text" class="col-sm-2 col-form-label text-right"><?php echo $BL['be_cnt_changemail'] ?></label>
  <div class="col">
    <textarea name="cnewsletter_change_text" rows="4" wrap="off" class="form-control form-control-sm" id="cnewsletter_change_text"><?php echo isset($content["newsletter"]["change_text"]) ? html($content["newsletter"]["change_text"]) : ''; ?></textarea>
  </div>
</div>
