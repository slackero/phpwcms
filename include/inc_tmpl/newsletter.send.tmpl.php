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


$count_sent  = _dbQuery('SELECT COUNT(*) FROM '.DB_PREPEND.'phpwcms_newsletterqueue WHERE queue_status=1 AND queue_pid='.$newsletter['newsletter_id'], 'COUNT');
$count_queue = _dbQuery('SELECT COUNT(*) FROM '.DB_PREPEND.'phpwcms_newsletterqueue WHERE queue_status=0 AND queue_pid='.$newsletter['newsletter_id'], 'COUNT');

?>
<div id="messagesend" style="display:block;">
<form action="include/inc_act/act_sendnewsletter.php" method="get" target="sendframe" id="sendnewsletter" onsubmit="hideLayer('messagesend');showLayer('sendjobnow');" data-csrf="off">
	<input type="hidden" name="csrftoken" value="<?php echo get_token_get_value(); ?>" />
	<input type="hidden" name="newsletter_id" value="<?php echo intval($newsletter['newsletter_id']) ?>" />
<table border="0" cellpadding="0" cellspacing="0" summary="">
	<tr><td colspan="2" class="title" style="padding-bottom:5px"><?php echo $BL['be_newsletter_sendnow'] ?></td></tr>
	<tr><td colspan="2"><img src="img/lines/l538_70.gif" alt="" width="538" height="1" /></td></tr>
	<tr bgcolor="#DEF9AC"><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="5" /></td></tr>

	<tr bgcolor="#DEF9AC">
		<td align="right" class="chatlist" style="padding-top: 1px;"><?php echo $BL['be_msg_subject'] ?>:&nbsp;</td>
		<td class="title"><?php echo html($newsletter['newsletter_subject']); ?></td>
	</tr>

	<tr bgcolor="#DEF9AC"><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="5" /></td></tr>
	<tr><td colspan="2"><img src="img/lines/l538_70.gif" alt="" width="538" height="1" /></td></tr>
	<tr><td bgcolor="#E6EAED" colspan="2" style="padding:8px"><?php echo $BL['be_newsletter_attention'] ?><br />
				  <img src="img/leer.gif" alt="" width="1" height="4" /><br />
				    <?php echo $BL['be_newsletter_attention1'] ?></td></tr>
	<tr><td colspan="2"><img src="img/lines/l538_70.gif" alt="" width="538" height="1" /></td></tr>
	<tr bgcolor="#DEF9AC"><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="5" /></td></tr>
	<tr bgcolor="#DEF9AC">
		<td align="right" class="chatlist">&nbsp;<?php echo $BL['be_newsletter_testemail'] ?>:&nbsp;</td>
		<td><input name="send_testemail" type="text" class="f11b" id="send_testemail" style="width:350px" size="50" maxlength="250" /></td>
	</tr>
	<tr bgcolor="#DEF9AC"><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="5" /></td></tr>
	<tr><td colspan="2"><img src="img/lines/l538_70.gif" alt="" width="538" height="1" /></td></tr>
	<tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="5" /></td></tr>

	<?php
	//ini_get('max_execution_time');

	// check against safe_mode -> when safe_mode = On
	// set_time_limit while sending newsletters is not possible
	// and it's recommend to send mails in looped mode
	$max_safe_mode = intval(@ini_get('safe_mode'));
	$max_per_loop  = $max_safe_mode && @ini_get('max_execution_time') ? intval(@ini_get('max_execution_time')) : 0;
	$max_start_at  = 0;
	$max_step      = 25;

	echo '<tr>
		<td align="right" class="chatlist">'.$BL['be_cnt_recipient'].':&nbsp;</td>
		<td><table cellpadding="0" cellspacing="0" border="0" summary="">
				<tr>
					<td><select name="loop" id="loop" class="v11">';

	if($max_safe_mode) {
		$max_start_at	= 5;
		$max_step		= 5;
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

	echo '				</select></td>
					<td class="chatlist">&nbsp;/loop&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;loop pause:&nbsp;</td>
					<td><select name="pause" id="pause" class="v11">';

	for($i = 1; $i < 10; $i++) {

		echo '			<option value="'.$i.'">'.$i.' '.$BL['be_cnt_guestbook_seconds'].'</option>'.LF;

	}
	echo '			<option value="0">0 '.$BL['be_cnt_guestbook_seconds'].'</option>'.LF;

	echo '				</select></td>
				</tr>
	</table></td></tr>';


	?>

	<tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="5" /></td></tr>
	<tr><td colspan="2"><img src="img/lines/l538_70.gif" alt="" width="538" height="1" /></td></tr>
	<tr bgcolor="#FCD1CD"><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="5" /></td>
	</tr>
	<tr bgcolor="#FCD1CD">
		<td align="right" class="chatlist"><?php echo $BL['be_confirm_sending'] ?>:&nbsp;</td>
		<td><table cellpadding="0" cellspacing="0" border="0" summary="">
				<tr>
					<td><input type="checkbox" name="send_confirm" id="send_confirm" value="confirmed" /></td>
					<td><label for="send_confirm">&nbsp;<strong><?php echo $BL['be_confirm_text'] ?></strong></label></td>
				</tr>
	</table></td>
	</tr>

	<tr bgcolor="#FCD1CD"><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="5" /></td></tr>
	<tr><td colspan="2"><img src="img/lines/l538_70.gif" alt="" width="538" height="1" /></td></tr>
	<tr><td colspan="2" bgcolor="#E6EAED"><img src="img/leer.gif" alt="" width="1" height="8" /></td></tr>
	<tr bgcolor="#E6EAED">
		<td>&nbsp;</td>
		<td><input name="sendit" type="submit" class="button bold" style="color:#CC3300;" value="<?php echo $BL['be_newsletter_sendnlbutton'] ?>" />
			&nbsp;&nbsp;
	    	<input type="button" class="button" value="<?php echo $BL['be_newsletter_button_cancel'] ?>" onclick="location.href='phpwcms.php?do=messages&amp;p=3';" />
		</td>
	</tr>
	<tr><td colspan="2" bgcolor="#E6EAED"><img src="img/leer.gif" alt="" width="1" height="5" /></td></tr>
	<tr><td colspan="2"><img src="img/lines/l538_70.gif" alt="" width="538" height="1" /></td></tr>
	<tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="5" /></td></tr>
</table>
</form>
</div>
<div id="sendjobnow" style="display:none;">
<table border="0" cellpadding="0" cellspacing="0" summary="">
	<tr><td class="title" style="padding-bottom:5px" colspan="2"><?php echo $BL['be_newsletter_sendprocess'] ?></td></tr>
	<tr><td colspan="2"><img src="img/lines/l538_70.gif" alt="" width="538" height="1" /></td></tr>
	<tr bgcolor="#DEF9AC"><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="5" /></td></tr>

	<tr bgcolor="#DEF9AC">
		<td align="right" class="chatlist" style="padding-top: 1px;width:100px;"><?php echo $BL['be_msg_subject'] ?>:&nbsp;</td>
		<td class="title"><?php echo html($newsletter['newsletter_subject']); ?></td>
	</tr>

	<tr bgcolor="#DEF9AC"><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="5" /></td></tr>
	<tr><td colspan="2"><img src="img/lines/l538_70.gif" alt="" width="538" height="1" /></td></tr>
	<tr><td bgcolor="#E6EAED" colspan="2" style="padding:8px"><?php echo $BL['be_newsletter_attention2'] ?></td></tr>
	<tr><td colspan="2"><img src="img/lines/l538_70.gif" alt="" width="538" height="1" /></td></tr>
	<tr>
		<td colspan="2"><iframe name="sendframe" width="100%" height="300" scrolling="auto" frameborder="0" id="sendframe"></iframe></td>
	</tr>
	<tr><td colspan="2"><img src="img/lines/l538_70.gif" alt="" width="538" height="1" /></td></tr>
	<tr><td bgcolor="#E6EAED" colspan="2"><img src="img/leer.gif" alt="" width="1" height="8" /></td></tr>
	<tr bgcolor="#E6EAED">
		<td align="center" colspan="2">
			<input type="button" class="button" value="<?php echo $BL['be_newsletter_button_cancel'] ?>" onclick="location.href='phpwcms.php?do=messages&amp;p=3';" />
		</td>
	</tr>
	<tr><td bgcolor="#E6EAED" colspan="2"><img src="img/leer.gif" alt="" width="1" height="8" /></td></tr>
	<tr><td colspan="2"><img src="img/lines/l538_70.gif" alt="" width="538" height="1" /></td></tr>
</table>
</div>