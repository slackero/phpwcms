<?php
/**
 * phpwcms content management system
 *
 * @author Oliver Georgi <og@phpwcms.org>
 * @copyright Copyright (c) 2002-2022, Oliver Georgi
 * @license http://opensource.org/licenses/GPL-2.0 GNU GPL-2
 * @link http://www.phpwcms.org
 *
 **/

// ----------------------------------------------------------------
// obligate check for phpwcms constants
if (!defined('PHPWCMS_ROOT')) {
    die("You Cannot Access This Script Directly, Have a Nice Day.");
}
// ----------------------------------------------------------------

//newsletter subscription
?>
<tr><td colspan="2" class="rowspacer0x7"><img src="img/leer.gif" alt="" width="1" height="1" /></td></tr>
<tr>
  <td align="right" valign="top" class="chatlist tdtop3"><?php echo $BL['be_cnt_subscription'] ?>:&nbsp;</td>
  <td valign="top"><?php

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
    $result = _dbQuery("SELECT * FROM ".DB_PREPEND."phpwcms_subscription ORDER BY subscription_name");
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
            'class' => 'optionTransfer',
            'formname' => 'articlecontent',
            'rows' => 7
        )
    );

    ?></td>
  <td>&nbsp;</td>
</tr>

<tr><td colspan="2" class="rowspacer7x7"></td></tr>

<tr>
    <td align="right" class="chatlist"><?php echo $BL['be_cnt_labelemail'] ?>:&nbsp;</td>
    <td class="tdbottom3"><table width="450" border="0" cellpadding="0" cellspacing="0" summary="">
      <tr>
        <td width="188"><input name="cnewsletter_label_email" type="text" id="cnewsletter_label_email" class="width180" value="<?php echo  isset($content["newsletter"]["label_email"]) ? $content["newsletter"]["label_email"] : '' ?>" size="20" maxlength="100"></td>
        <td width="70" align="right" class="chatlist">&nbsp;<?php

        echo $BL['be_cnt_tablealign'];
        if(empty($content["newsletter"]["pos"])) {
            $content["newsletter"]["pos"] = 0;
        }

        ?>:&nbsp;</td>
        <td><select name="cnewsletter_pos" id="cnewsletter_pos">
          <option value="0" <?php is_selected(0, $content["newsletter"]["pos"]) ?>><?php echo $BL['be_cnt_default'] ?></option>
          <option value="1" <?php is_selected(1, $content["newsletter"]["pos"]) ?>><?php echo $BL['be_cnt_left'] ?></option>
          <option value="2" <?php is_selected(2, $content["newsletter"]["pos"]) ?>><?php echo $BL['be_cnt_center'] ?></option>
          <option value="3" <?php is_selected(3, $content["newsletter"]["pos"]) ?>><?php echo $BL['be_cnt_right'] ?></option>
        </select></td>
      </tr>
    </table></td>
</tr>
<tr>
    <td align="right" class="chatlist"><?php echo $BL['be_cnt_labelname'] ?>:&nbsp;</td>
    <td class="tdbottom3"><table width="450" border="0" cellpadding="0" cellspacing="0" summary="">
      <tr>
        <td width="188"><input name="cnewsletter_label_name" type="text" id="cnewsletter_label_name" class="width180" value="<?php echo isset($content["newsletter"]["label_name"]) ? html($content["newsletter"]["label_name"]) : ''; ?>" size="20" maxlength="100"></td>
        <td width="70" align="right" class="chatlist">&nbsp;<?php echo $BL['be_cnt_buttontext'] ?>:&nbsp;</td>
        <td><input name="cnewsletter_button_text" type="text" id="cnewsletter_button_text" class="width180" value="<?php echo  isset($content["newsletter"]["button_text"]) ? html($content["newsletter"]["button_text"]) : ''; ?>" size="40" maxlength="50"></td>
      </tr>
    </table></td>
</tr>
<tr>
    <td align="right" class="chatlist"><?php echo $BL['be_cnt_labelsubsc'] ?>:&nbsp;</td>
    <td class="tdbottom5"><table width="450" border="0" cellpadding="0" cellspacing="0" summary="">
      <tr>
        <td width="188"><input name="cnewsletter_label_subscriptions" type="text" id="cnewsletter_label_subscriptions" class="width180" value="<?php echo isset($content["newsletter"]["label_subscriptions"]) ? html($content["newsletter"]["label_subscriptions"]) : ''; ?>" size="20" maxlength="100"></td>
        <td width="70" align="right" class="chatlist">&nbsp;<?php echo $BL['be_cnt_allsubsc'] ?>:&nbsp;</td>
        <td><input name="cnewsletter_all_subscriptions" type="text" id="cnewsletter_all_subscriptions" class="width180" value="<?php echo isset($content["newsletter"]["all_subscriptions"]) ? html($content["newsletter"]["all_subscriptions"]) : '' ?>" size="40" maxlength="50"></td>
      </tr>
    </table></td>
</tr>
<tr>
    <td align="right" valign="top" class="chatlist tdtop3"><?php echo $BL['be_cnt_infotext'] ?>:&nbsp;</td>
    <td valign="top" class="tdbottom3"><textarea name="cnewsletter_text" rows="4" class="width440 autosize" id="cnewsletter_text"><?php echo isset($content["newsletter"]["text"]) ? html($content["newsletter"]["text"]) : ''; ?></textarea></td>
</tr>
<tr>
    <td align="right" valign="top" class="chatlist tdtop3"><?php echo $BL['be_cnt_successtext'] ?>:&nbsp;</td>
    <td valign="top" class="tdbottom3"><textarea name="cnewsletter_success_text" rows="4" class="width440 autosize" id="cnewsletter_success_text"><?php echo isset($content["newsletter"]["success_text"]) ? html($content["newsletter"]["success_text"]) : ''; ?></textarea></td>
</tr>
<tr>
    <td align="right" class="chatlist">URL 1:&nbsp;</td>
    <td><input name="cnewsletter_url1" type="text" id="cnewsletter_url1" class="width440" value="<?php echo isset($content["newsletter"]["url1"]) ? html($content["newsletter"]["url1"]) : '' ?>" size="20" /></td>
</tr>
<tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="3"></td></tr>
<tr>
    <td align="right" class="chatlist">URL 2:&nbsp;</td>
    <td><input name="cnewsletter_url2" type="text" id="cnewsletter_url2" class="width440" value="<?php echo isset($content["newsletter"]["url2"]) ? html($content["newsletter"]["url2"]) : '' ?>" size="20" /></td>
</tr>

<tr><td colspan="2" class="rowspacer7x7"></td></tr>

<tr>
    <td align="right" class="chatlist"><?php echo $BL['be_cnt_field']['recaptcha']; ?>:&nbsp;</td>
    <td>
        <select name="cnewsletter_recaptcha">
            <option value="0" <?php is_selected(0, $content["newsletter"]["recaptcha"]) ?>><?php echo $BL['be_off']; ?></option>
            <option value="2" <?php is_selected(2, $content["newsletter"]["recaptcha"]) ?>><?php echo $BL['be_cnt_field']['recaptchainv']; ?></option>
            <option value="1" <?php is_selected(1, $content["newsletter"]["recaptcha"]) ?>><?php echo $BL['be_cnt_field']['recaptcha']; ?> v2</option>
        </select>
    </td>
</tr>
<tr>
    <td align="right" valign="top" class="chatlist tdtop6"><?php echo $BL['be_settings']; ?>:&nbsp;</td>
    <td valign="top" class="tdtop3">
        <textarea name="cnewsletter_recaptcha_config" rows="3" class="width440 code autosize" placeholder="site_key = &#10;secret_key = &#10;type = image"><?php echo html($content["newsletter"]["recaptcha_config"]); ?></textarea>
        <div class="tdtop3">
            <a href="https://www.google.com/recaptcha/admin" target="_blank" style="text-decoration:underline;"><?php echo $BL['be_cnt_field']['recaptcha_signapikey']; ?></a>
        </div>
    </td>
</tr>

<tr><td colspan="2" class="rowspacer7x7"></td></tr>

<tr>
    <td>&nbsp;</td>
    <td class="v10 tdbottom3">{NEWSLETTER_NAME}, {NEWSLETTER_EMAIL}, {NEWSLETTER_VERIFY}, {NEWSLETTER_DELETE}, {IP}, {DATE:m/d/Y}, [SUBJECT][/SUBJECT]</td>
</tr>

  <tr>
    <td align="right" valign="top" class="chatlist tdtop3"><?php echo $BL['be_cnt_regmail'] ?>:&nbsp;</td>
    <td valign="top" class="tdbottom3"><textarea name="cnewsletter_reg_text" rows="4" wrap="off" class="code width440 autosize" id="cnewsletter_reg_text"><?php echo isset($content["newsletter"]["reg_text"]) ? html($content["newsletter"]["reg_text"]) : ''; ?></textarea></td>
  </tr>
  <tr>
    <td align="right" valign="top" class="chatlist tdtop3"><?php echo $BL['be_cnt_logoffmail'] ?>:&nbsp;</td>
    <td valign="top" class="tdbottom3"><textarea name="cnewsletter_logoff_text" rows="4" wrap="off" class="code width440 autosize" id="cnewsletter_logoff_text"><?php echo isset($content["newsletter"]["logoff_text"]) ? html($content["newsletter"]["logoff_text"]) : ''; ?></textarea></td>
  </tr>
  <tr>
    <td align="right" valign="top" class="chatlist tdtop3"><?php echo $BL['be_cnt_changemail'] ?>:&nbsp;</td>
    <td valign="top"><textarea name="cnewsletter_change_text" rows="4" wrap="off" class="code width440 autosize" id="cnewsletter_change_text"><?php echo isset($content["newsletter"]["change_text"]) ? html($content["newsletter"]["change_text"]) : ''; ?></textarea></td>
  </tr>

