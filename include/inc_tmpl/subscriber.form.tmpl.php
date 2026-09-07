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
<form action="phpwcms.php?do=messages&amp;p=4&amp;s=<?php echo $_userInfo['subscriber_data']['address_id'] ?>&amp;edit=1" method="post" name="editsubscriber" id="editsubscriber">
  <div class="card mb-3">
    <div class="card-body">

      <div class="form-group row g-2 align-items-center">
        <span class="col-sm-2 col-form-label text-end fw-bold"><?php echo $BL['be_cnt_last_edited'] ?></span>
        <div class="col">
         <?php echo html($_userInfo['subscriber_data']['address_tstamp']) ?>
        </div>
      </div>

      <div class="form-group row g-2 align-items-center">
        <label for="subscribe_email" class="col-sm-2 col-form-label text-end"><?php echo $BL['be_profile_label_email'] ?></label>
        <div class="col">
          <input type="email" class="form-control form-control-sm" name="subscribe_email" id="subscribe_email" value="<?php echo html($_userInfo['subscriber_data']['address_email']) ?>" maxlength="250" required />
        </div>
      </div>

      <div class="form-group row g-2 align-items-center">
        <label for="subscribe_name" class="col-sm-2 col-form-label text-end"><?php echo $BL['be_cnt_ecardform_name'] ?></label>
        <div class="col">
          <input type="text" class="form-control form-control-sm" name="subscribe_name" id="subscribe_name" value="<?php echo html($_userInfo['subscriber_data']['address_name']) ?>" maxlength="250" required />
        </div>
      </div>

      <div class="form-group row g-2">
        <label class="col-sm-2 col-form-label text-end pt-0"><?php echo $BL['be_cnt_subscription'] ?></label>
        <div class="col">

				<?php
					//retrieve available subscriptions
					$_userInfo['select_subscr'] = '';
					$_userInfo['subscr_all']  = 1;
					$_userInfo['subscriptions'] = _dbQuery("SELECT * FROM ".DB_PREPEND."subscription ORDER BY subscription_name");

					$_userInfo['subscriber_data']['subscriptions']  = unserialize($_userInfo['subscriber_data']['address_subscription'], ['allowed_classes' => false]);

					if($_userInfo['subscriptions']) {

						foreach($_userInfo['subscriptions'] as $value) {

							$_userInfo['select_subscr'] .= '
								<div class="form-check"><input class="form-check-input" type="checkbox" name="subscribe_to[]" id="subscribe_to'.$value['subscription_id'].'" value="'.$value['subscription_id'].'"';
							if(is_array($_userInfo['subscriber_data']['subscriptions']) && in_array($value['subscription_id'], $_userInfo['subscriber_data']['subscriptions'])) {

								$_userInfo['select_subscr'] .= ' checked="checked"';
								$_userInfo['subscr_all']   = 0;

							}
							$_userInfo['select_subscr'] .= ' /><label class="form-check-label" for="subscribe_to'.$value['subscription_id'].'">'.
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

      <div class="form-group row g-2 align-items-center">
        <label class="col-sm-2 col-form-label text-end" for="subscribe_active"><?php echo $BL['be_ftptakeover_status'] ?></label>
        <div class="col">
          <div class="form-check form-switch">
						<input class="form-check-input" name="subscribe_active" type="checkbox" role="switch" id="subscribe_active" value="1"<?php is_checked($_userInfo['subscriber_data']['address_verified'], 1) ?> />
						<label class="form-check-label" for="subscribe_active"><?php echo $BL['be_cnt_activated']; ?></label>
          </div>
        </div>
      </div>

			<div class="form-group align-items-center mt-4 mb-0">
				<button name="submit" type="submit" class="btn btn-sm btn-blue" value="1"><i class="fa-solid fa-rotate"></i> <?php echo empty($_userInfo['subscriber_data']['address_id']) ? $BL['be_admin_fcat_button2'] : $BL['be_article_cnt_button1'] ?></button>
				<button name="save" type="submit" class="btn btn-sm btn-blue ms-1" value="<?php echo $BL['be_article_cnt_button3'] ?>"><i class="fa-solid fa-check"></i> <?php echo $BL['be_article_cnt_button3'] ?></button>
				<a class="btn btn-sm btn-danger ms-3" href="phpwcms.php?do=messages&amp;p=4"><i class="fa-solid fa-times"></i> <?php echo $BL['be_admin_struct_close'] ?></a>
			</div>

    </div>
  </div>
</form>
