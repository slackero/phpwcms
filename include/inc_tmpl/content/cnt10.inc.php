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


//email form

if(!isset($content["mailhtml"])) {
	$content["mailhtml"] = 0;
}

?>

<tr><td colspan="2" class="rowspacer0x7"><img src="img/leer.gif" alt="" width="1" height="1"></td></tr>

<tr>
	<td align="right" class="chatlist"><?php echo $BL['be_cnt_subject'] ?>:&nbsp;</td>
	<td><input name="cmailsubject" type="text" id="cmailsubject" class="f11b" style="width: 440px" value="<?php echo  isset($content["mailsubject"]) ?  html($content["mailsubject"]) : '' ?>" size="40" maxlength="250"></td>
</tr>
<tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="3"></td></tr>
<tr>
	<td align="right" class="chatlist"><?php echo $BL['be_cnt_recipient'] ?>:&nbsp;</td>
	<td><input name="cmailrecipient" type="text" id="cmailrecipient" class="f11b" style="width: 440px" value="<?php echo  isset($content["mailrecipient"]) ? html($content["mailrecipient"]) : '' ?>" size="40" maxlength="250"></td>
</tr>
<tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="3"></td></tr>
<tr>
	<td align="right" class="chatlist"><?php echo $BL['be_cnt_buttontext']  ?>:&nbsp;</td>
	<td><input name="cmailbutton" type="text" id="cmailbutton" class="f11b" style="width: 150px" value="<?php echo  isset($content["mailbutton"]) ? html($content["mailbutton"]) : '' ?>" size="20" maxlength="35"></td>
</tr>
<tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="3"></td></tr>
<tr>
	<td align="right" class="chatlist"><?php echo $BL['be_cnt_sendas'] ?>:&nbsp;</td>
	<td><table border="0" cellpadding="0" cellspacing="0" summary="">
		<tr bgcolor="#E7E8EB">
		<td><input name="cmailhtml" type="radio" value="0" <?php is_checked(0, $content["mailhtml"]); ?>></td>
	    <td class="v10"><?php echo $BL['be_cnt_text'] ?>&nbsp;&nbsp;</td>
	    <td><input name="cmailhtml" type="radio" value="1" <?php is_checked(1, $content["mailhtml"]); ?>></td>
	    <td class="v10"><?php echo $BL['be_cnt_html'] ?>&nbsp;</td>
		<td><img src="img/leer.gif" alt="" width="6" height="15"></td>
		</tr>
		</table></td>
</tr>
<tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="6"></td></tr>
<tr>
	<td align="right" valign="top" class="chatlist"><img src="img/leer.gif" alt="" width="1" height="13"><?php echo $BL['be_cnt_formfields'] ?>:&nbsp;</td>
	<td valign="top"><textarea name="cmailform" rows="15" wrap="off" class="width440 autosize" id="cmailform"><?php

	if(isset($content["mailform"])) {
		if(is_array($content["mailform"])) {
			foreach($content["mailform"] as $formkey => $valform) {
				echo html($content["mailform"][$formkey]['field'])."\n";
			}
		} else {
			echo html($content["mailform"]);
		}
	} else {
		echo '';
	}

	 ?></textarea></td>
</tr>