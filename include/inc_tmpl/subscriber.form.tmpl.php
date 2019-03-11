<?php
/**
 * cmsGO!
 *
 * @author Pixels & Points GmbH <info@pixels-points.ch>
 * @copyright Copyright (c) 2002-2019, Pixels & Points GmbH
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
<form action="cmsgo.php?do=messages&amp;p=4&amp;s=<?php echo $_userInfo['subscriber_data']['address_id'] ?>&amp;edit=1" method="post" name="editsubscriber" id="editsubscriber">
  <div class="card mb-3">
    <div class="card-body">

      <div class="form-group form-row align-items-center">
        <label for="address_tstamp" class="col-sm-2 col-form-label text-right"><?php echo $BL['be_cnt_last_edited'] ?></label>
        <div class="col">
         <?php echo html($_userInfo['subscriber_data']['address_tstamp']) ?>
        </div>
      </div>

      <div class="form-group form-row align-items-center">
        <label for="subscribe_email" class="col-sm-2 col-form-label text-right"><?php echo $BL['be_profile_label_email'] ?></label>
        <div class="col">
          <input type="email" class="form-control form-control-sm" name="subscribe_email" id="subscribe_email" value="<?php echo html($_userInfo['subscriber_data']['address_email']) ?>" maxlength="250" required />
        </div>
      </div>

      <div class="form-group form-row align-items-center">
        <label for="subscribe_name" class="col-sm-2 col-form-label text-right"><?php echo $BL['be_cnt_ecardform_name'] ?></label>
        <div class="col">
          <input type="text" class="form-control form-control-sm" name="subscribe_name" id="subscribe_name" value="<?php echo html($_userInfo['subscriber_data']['address_name']) ?>" maxlength="250" required />
        </div>
      </div>

      <div class="form-group form-row">
        <label class="col-sm-2 col-form-label text-right pt-0"><?php echo $BL['be_cnt_subscription'] ?></label>
        <div class="col">

				<?php
					//retrieve available subscriptions
					$_userInfo['select_subscr'] = '';
					$_userInfo['subscr_all']  = 1;
					$_userInfo['subscriptions'] = _dbQuery("SELECT * FROM ".DB_PREPEND."cmsgo_subscription ORDER BY subscription_name");

					$_userInfo['subscriber_data']['subscriptions']  = unserialize($_userInfo['subscriber_data']['address_subscription']);

					if($_userInfo['subscriptions']) {

						foreach($_userInfo['subscriptions'] as $value) {

							$_userInfo['select_subscr'] .= ' 
								<div class="form-check"><input class="form-check-input" type="checkbox" name="subscribe_to[]" id="subscribe_to'.$value['subscription_id'].'" value="'.$value['subscription_id'].'"';
							if(is_array($_userInfo['subscriber_data']['subscriptions']) && in_array($value['subscription_id'], $_userInfo['subscriber_data']['subscriptions'])) {

								$_userInfo['select_subscr'] .= ' checked="checked"';
								$_userInfo['subscr_all']   = 0;

							}
							$_userInfo['select_subscr'] .= ' /><label class="form-check-label">'.
								html($value['subscription_name']).
								'</label>
							</div>
							';
						}
					}
				?>

         <div class="form-check">
            <label class="form-check-label align-items-center">
              <input class="form-check-input" name="subscribe_all" type="checkbox" id="subscribe_all" value="1"<?php is_checked($_userInfo['subscr_all'], 1) ?> />
               <?php echo $BL['be_newsletter_allsubscriptions']; ?>
            </label>
          </div>
        <?php echo $_userInfo['select_subscr'] ?>
        </div>
      </div>

      <div class="form-group form-row align-items-center">
        <label class="col-sm-2 col-form-label text-right"><?php echo $BL['be_ftptakeover_status'] ?></label>
        <div class="col">
          <div class="form-check">
						<input class="form-check-input" name="subscribe_active" type="checkbox" id="subscribe_active" value="1"<?php is_checked($_userInfo['subscriber_data']['address_verified'], 1) ?> />
						<label class="form-check-label"><?php echo $BL['be_cnt_activated']; ?></label>
          </div>
        </div>
      </div>

			<div class="text-left">
				<input name="submit" type="submit" class="btn btn-sm btn-blue" value="<?php echo empty($_userInfo['subscriber_data']['address_id']) ? $BL['be_admin_fcat_button2'] : $BL['be_article_cnt_button1'] ?>" />
				<input name="save" type="submit" class="btn btn-sm btn-blue" value="<?php echo $BL['be_article_cnt_button3'] ?>" />
				<input name="close" type="button" class="btn btn-sm btn-blue" value="<?php echo $BL['be_admin_struct_close'] ?>" onclick="location.href='cmsgo.php?do=messages&p=4';return false;" />
			</div>

    </div>
  </div>
</form>
