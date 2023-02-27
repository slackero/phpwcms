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


//email form

if(!isset($content["mailhtml"])) {
	$content["mailhtml"] = 0;
}

?>

<tr>
	<td align="right"><?php echo $BL['be_cnt_subject'] ?>:&nbsp;</td>
	<td><input name="cmailsubject" type="text" id="cmailsubject" class="f11b" style="width: 440px" value="<?php echo  isset($content["mailsubject"]) ?  html($content["mailsubject"]) : '' ?>" size="40" maxlength="250"></td>
</tr>
<tr>
	<td align="right"><?php echo $BL['be_cnt_recipient'] ?>:&nbsp;</td>
	<td><input name="cmailrecipient" type="text" id="cmailrecipient" class="f11b" style="width: 440px" value="<?php echo  isset($content["mailrecipient"]) ? html($content["mailrecipient"]) : '' ?>" size="40" maxlength="250"></td>
</tr>
<tr>
	<td align="right"><?php echo $BL['be_cnt_buttontext']  ?>:&nbsp;</td>
	<td><input name="cmailbutton" type="text" id="cmailbutton" class="f11b" style="width: 150px" value="<?php echo  isset($content["mailbutton"]) ? html($content["mailbutton"]) : '' ?>" size="20" maxlength="35"></td>
</tr>
<tr>
	<td align="right"><?php echo $BL['be_cnt_sendas'] ?>:&nbsp;</td>
	<td><table class="table-no-border">
		<tr bgcolor="#E7E8EB">
		<td><input name="cmailhtml" type="radio" value="0" <?php is_checked(0, $content["mailhtml"]); ?>></td>
	    <td><?php echo $BL['be_cnt_text'] ?>&nbsp;&nbsp;</td>
	    <td><input name="cmailhtml" type="radio" value="1" <?php is_checked(1, $content["mailhtml"]); ?>></td>
	    <td><?php echo $BL['be_cnt_html'] ?>&nbsp;</td>
		<td></td>
		</tr>
		</table></td>
</tr>

<tr>
	<td align="right" valign="top"><?php echo $BL['be_cnt_formfields'] ?>:&nbsp;</td>
	<td valign="top"><textarea name="cmailform" rows="15" wrap="off" class="form-control" id="cmailform"><?php
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
