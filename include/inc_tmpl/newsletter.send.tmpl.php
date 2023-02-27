<?php
/**
 * cmsGO!
 *
 * @author Pixels & Points GmbH <info@pixels-points.ch>
 * @copyright Copyright (c) 2002-2023, Pixels & Points GmbH
 * @license https://www.pixels-points.ch/cmsgo-license.html Pixels & Points cmsGO! license
 *
 **/

// ----------------------------------------------------------------
// obligate check for cmsgo constants
if (!defined('CMSGO_ROOT')) {
  die("You Cannot Access This Script Directly, Have a Nice Day.");
}
// ----------------------------------------------------------------


$count_sent  = _dbQuery('SELECT COUNT(*) FROM '.DB_PREPEND.'cmsgo_newsletterqueue WHERE queue_status=1 AND queue_pid='.$newsletter['newsletter_id'], 'COUNT');
$count_queue = _dbQuery('SELECT COUNT(*) FROM '.DB_PREPEND.'cmsgo_newsletterqueue WHERE queue_status=0 AND queue_pid='.$newsletter['newsletter_id'], 'COUNT');

?>
<div class="card" id="messagesend" style="display:block;">
  <div class="card-header"><h2><i class="fa fa-paper-plane" aria-hidden="true"></i> <?php echo $BL['be_newsletter_sendnow'] ?></h2></div>
  <div class="card-body">

    <div id="messagesend" style="display:block;">
      <form action="include/inc_act/act_sendnewsletter.php" method="get" target="sendframe" id="sendnewsletter" data-csrf="off">
        <input type="hidden" name="csrftoken" value="<?php echo get_token_get_value('csrftoken'); ?>" />
        <input type="hidden" name="newsletter_id" value="<?php echo intval($newsletter['newsletter_id']) ?>" />

        <div class="form-group form-row align-items-center">
            <label for="newsletter_subject" class="col-sm-2 col-form-label text-right"><?php echo $BL['be_msg_subject'] ?></label>
            <div class="col-sm-10"><?php echo html($newsletter["newsletter_subject"]) ?></div>
        </div>

        <div class="form-group form-row align-items-center">
            <label for="send_testemail" class="col-sm-2 col-form-label text-right"><?php echo $BL['be_newsletter_testemail'] ?></label>
            <div class="col-sm-10">
              <input type="text" class="form-control form-control-sm" name="send_testemail" id="send_testemail" value="" maxlength="250" />
            </div>
        </div>

        <div class="form-group form-row align-items-center">
            <label for="loop" class="col-sm-2 col-form-label text-right"><?php echo $BL['be_cnt_recipient'] ?></label>
            <div class="col">
              <select name="loop" id="loop" class="custom-select form-control">
              <?php
              //ini_get('max_execution_time');

              // check against safe_mode -> when safe_mode = On
              // set_time_limit while sending newsletters is not possible
              // and it's recommend to send mails in looped mode
              $max_safe_mode = intval(@ini_get('safe_mode'));
              $max_per_loop  = $max_safe_mode && @ini_get('max_execution_time') ? intval(@ini_get('max_execution_time')) : 0;
              $max_start_at  = 0;
              $max_step      = 25;

              if($max_safe_mode) {
                $max_start_at = 5;
                $max_step   = 5;
                if(!$max_per_loop) {
                  $max_per_loop = 25;
                }
              } elseif(!$max_per_loop) {
                $max_per_loop = 500;
              }

              for($i = $max_start_at; $i <= $max_per_loop; $i+=$max_step) {
                echo '<option value="'.$i.'">';
                echo $i == 0 ? $BL['be_ftptakeover_all'] : $i;
                echo '</option>'.LF;
              }
              ?>

              </select>
            </div>
            <div class="col">&nbsp;/loop&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;loop pause:&nbsp;</div>
            <div class="col">
              <select name="pause" id="pause" class="custom-select form-control">';
              <?php
              for($i = 1; $i < 10; $i++) {

                echo '      <option value="'.$i.'">'.$i.' '.$BL['be_cnt_guestbook_seconds'].'</option>'.LF;

              }
              echo '      <option value="0">0 '.$BL['be_cnt_guestbook_seconds'].'</option>'.LF;
              ?>
              </select>
            </div>
          </div>


        <div class="form-group row form-row align-items-center">
            <label for="send_confirm" class="col-sm-2 col-form-label text-right"><?php echo $BL['be_confirm_sending'] ?></label>
            <div class="col-sm-10">
              <div class="form-check alert alert-danger">
                <label class="form-check-label">
                  <input class="form-check-input" type="checkbox" name="send_confirm" id="send_confirm" value="confirmed"> <?php echo $BL['be_confirm_text'] ?>
                </label>
              </div>
            </div>
        </div>

        <div class="form-group row">
          <div class="offset-sm-2 col-sm-10">
            <input name="sendit" type="submit" class="btn btn-blue btn-small bold" style="color:#CC3300;" value="<?php echo $BL['be_newsletter_sendnlbutton'] ?>" />
              &nbsp;&nbsp;
                <input type="button" class="btn btn-blue btn-small" value="<?php echo $BL['be_newsletter_button_cancel'] ?>" onclick="location.href='cmsgo.php?do=messages&amp;p=3';" />
          </div>
        </div>
      </form>
      <div class="alert alert-warning"><?php echo $BL['be_newsletter_attention1'] ?><br /><?php echo $BL['be_newsletter_attention1'] ?></div>
    </div>
  </div>
</div>

<div class="card" id="sendjobnow" style="display:none;">
  <div class="card-header"><h2><i class="fa fa-paper-plane" aria-hidden="true"></i> <?php echo $BL['be_newsletter_sendprocess'] ?></h2></div>
  <div class="card-body">
    <table class="table">
      <tr bgcolor="#DEF9AC">
        <td align="right"><?php echo $BL['be_msg_subject'] ?>:&nbsp;</td>
        <td><?php echo html($newsletter['newsletter_subject']); ?></td>
      </tr>
      <tr><td colspan="2" class="alert-warning"><?php echo $BL['be_newsletter_attention2'] ?></td></tr>
      <tr>
        <td bgcolor="#F1F3F5" colspan="2"><iframe name="sendframe" width="100%" height="300" scrolling="auto" frameborder="0" id="sendframe"></iframe></td>
      </tr>
      <tr bgcolor="#E6EAED">
        <td align="center" colspan="2">
          <input type="button" class="btn btn-blue btn-small" value="<?php echo $BL['be_newsletter_button_cancel'] ?>" onclick="location.href='cmsgo.php?do=messages&amp;p=3';" />
        </td>
      </tr>
    </table>
  </div>
</div>
