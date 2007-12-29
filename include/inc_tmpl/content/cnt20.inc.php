<?php
/*************************************************************************************
   Copyright notice
   
   (c) 2002-2007 Oliver Georgi (oliver@phpwcms.de) // All rights reserved.
 
   This script is part of PHPWCMS. The PHPWCMS web content management system is
   free software; you can redistribute it and/or modify it under the terms of
   the GNU General Public License as published by the Free Software Foundation;
   either version 2 of the License, or (at your option) any later version.
  
   The GNU General Public License can be found at http://www.gnu.org/copyleft/gpl.html
   A copy is found in the textfile GPL.txt and important notices to the license 
   from the author is found in LICENSE.txt distributed with these scripts.
  
   This script is distributed in the hope that it will be useful, but WITHOUT ANY 
   WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A
   PARTICULAR PURPOSE.  See the GNU General Public License for more details.
 
   This copyright notice MUST APPEAR in all copies of the script!
*************************************************************************************/

// ----------------------------------------------------------------
// obligate check for phpwcms constants
if (!defined('PHPWCMS_ROOT')) {
   die("You Cannot Access This Script Directly, Have a Nice Day.");
}
// ----------------------------------------------------------------


//bid

if(!isset($content['bid'])) {

	$content['bid']['image_name']	= '';
	$content['bid']['image_id']		= '';
	$content['bid']['image_width']	= '';
	$content['bid']['image_height']	= '';
	$content['bid']['image_zoom']	= 0;
	$content['bid']['start_date']	= '';
	$content['bid']['end_date']		= '';
	$content['bid']['startbid']		= 0;
	$content['bid']['nextbidadd']	= 0;
	$content['bid']['before']		= '';
	$content['bid']['after']		= '';
	
	$content['bid']['text']			= '';
	$content['bid']['form']			= '';
	$content['bid']['sent']			= '';
	$content['bid']['emailfrom']	= '';
	$content['bid']['emailfromname']= '';
	$content['bid']['emailmsg']		= '';
	$content['bid']['verified']		= '';
	$content['bid']['notverified']	= '';
	

}

?>
<tr>
	<td align="right" class="chatlist"><?php echo $BL['be_cnt_image'] ?>:&nbsp;</td>
	<td valign="top"><table border="0" cellpadding="0" cellspacing="0" summary="">
		<tr>
			<td><input name="cimage_name" type="text" id="cimage_name" class="f11b" style="width: 200px; color: #727889;" value="<?php echo html_specialchars($content["bid"]["image_name"]) ?>" size="40" onFocus="this.blur()"></td>
			<td><img src="img/leer.gif" alt="" width="3" height="1"><a href="javascript:;" title="<?php echo $BL['be_cnt_openimagebrowser'] ?>" onclick="tmt_winOpen('filebrowser.php','imageBrowser','width=380,height=300,left=8,top=8,scrollbars=yes,resizable=yes',1)"><img src="img/button/open_image_button.gif" alt="" width="20" height="15" border="0"></a></td>
			<td><img src="img/leer.gif" alt="" width="3" height="1"><a href="javascript:;" alt="<?php echo $BL['be_cnt_delimage'] ?>" onclick="document.articlecontent.cimage_name.value='';document.articlecontent.cimage_id.value='0';this.blur();return false;"><img src="img/button/del_image_button.gif" alt="" width="15" height="15" border="0"></a><input name="cimage_id" type="hidden" value="<?php echo $content["bid"]["image_id"] ?>"></td>
		</tr>
	</table></td>
</tr>
<tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="6"></td></tr>
<tr>
		<td align="right" class="chatlist" valign="top"><img src="img/leer.gif" alt="" width="1" height="14"><?php echo $BL['be_cnt_maxw'] ?>:&nbsp;</td>
		<td valign="top"><table border="0" cellpadding="0" cellspacing="0" summary="">
			<tr>
				<td><input name="cimage_width" type="text" class="f11b" id="cimage_width" style="width: 50px;" size="3" maxlength="3" onKeyUp="if(!parseInt(this.value)) this.value='';" value="<?php echo $content["bid"]["image_width"] ?>"></td>
			    <td class="chatlist">&nbsp;&nbsp;<?php echo $BL['be_cnt_maxh'] ?>:&nbsp; </td>
			    <td><input name="cimage_height" type="text" class="f11b" id="cimage_height" style="width: 50px;" size="3" maxlength="3" onKeyUp="if(!parseInt(this.value)) this.value='';" value="<?php echo $content["bid"]["image_height"] ?>"></td>
			    <td class="chatlist">&nbsp;px&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
				<td bgcolor="#E7E8EB">&nbsp;</td>
				<td bgcolor="#E7E8EB"><input name="cimage_zoom" type="checkbox" id="cimage_zoom" value="1" <?php is_checked(1, $content["bid"]["image_zoom"]); ?>></td>
				<td bgcolor="#E7E8EB" class="v10">&nbsp;<?php echo $BL['be_cnt_enlarge'] ?>&nbsp;</td>
				<td bgcolor="#E7E8EB"><img src="img/leer.gif" alt="" width="6" height="15"></td>
			</tr>
<?php
	//show preview of selected image
	if($content["bid"]["image_name"]) {
		echo '<tr><td colspan="8"><img src="img/leer.gif" width="6" height="5"></td></tr>';
		echo '<tr><td colspan="8">';
		echo '<img src="'.$phpwcms["file_tmp"].$phpwcms["dir_thlist"].$content['bid']["image_prev"].'" border="0">';
		echo '</td></tr>';
	}

?>
	</table></td>
</tr>
<tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="8"></td></tr>
<tr>
	<td align="right" class="chatlist"><?php echo $BL['be_article_cnt_start'] ?>:&nbsp;</td>
	<td><table border="0" cellpadding="0" cellspacing="0" summary="">
			<tr>
				<td><input name="cbid_startdate" type="text" class="f11" id="cbid_startdate" style="width: 130px;" size="19" maxlength="20" value="<?php if($content["bid"]["start_date"]) echo date('Y-m-d H:i:s', $content["bid"]["start_date"]); ?>"></td>
			    <td class="chatlist" width="100" align="right">&nbsp;&nbsp;<?php echo $BL['be_article_cnt_end'] ?>:&nbsp; </td>
			    <td><input name="cbid_enddate" type="text" class="f11" id="cbid_enddate" style="width: 130px;" size="19" maxlength="20" value="<?php if($content["bid"]["end_date"]) echo date('Y-m-d H:i:s', $content["bid"]["end_date"]); ?>"></td>
			</tr>
		</table></td>
</tr>
<tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="5"></td></tr>
<tr>
  <td align="right" class="chatlist"><?php echo $BL['be_cnt_bid_startbid'] ?>:&nbsp;</td>
  <td><table border="0" cellpadding="0" cellspacing="0" summary="">
			<tr>
				<td><input name="cbid_startbid" type="text" class="f11" id="cbid_startbid" style="width: 130px;" value="<?php echo floatval($content["bid"]["startbid"]) ?>"></td>
			    <td class="chatlist" width="100" align="right">&nbsp;&nbsp;<?php echo $BL['be_cnt_bid_nextbidadd'] ?>:&nbsp; </td>
			    <td><input name="cbid_nextbidadd" type="text" class="f11" id="cbid_nextbidadd" style="width: 130px;" value="<?php echo floatval($content["bid"]["nextbidadd"]) ?>"></td>
			</tr>
		</table></td>
</tr>

<tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="10"></td></tr>
<tr><td colspan="2"><img src="img/lines/l538_70.gif" alt="" width="538" height="1"></td></tr>
<tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="6"></td></tr>
<tr>
  <td align="right" class="chatlist" valign="top"><img src="img/leer.gif" alt="" width="1" height="15"><?php echo $BL['be_cnt_guestbook_before'] ?>:&nbsp;</td>
  <td valign="top"><textarea name="cbid_before" cols="40" rows="3" class="code" id="cbid_before" style="width: 440px"><?php echo html_specialchars($content["bid"]["before"]) ?></textarea></td>
</tr>
<tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="4"></td></tr>
<tr>
  <td align="right" class="chatlist">&nbsp;</td>
  <td class="chatlist">###BID_IMG:ALIGN###, ###BID_START:FORMAT###, ###BID_END:FORMAT###, ###START_BID###, ###BID_CURRENT###, ###BID_FORM###</td>
</tr>
<tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="1"></td></tr>
<tr>
  <td align="right" class="chatlist" valign="top"><img src="img/leer.gif" alt="" width="1" height="15"><?php echo $BL['be_cnt_bid_bidtext'] ?>:&nbsp;</td>
  <td valign="top"><textarea name="cbid_text" cols="40" rows="10" class="code" id="cbid_text" style="width: 440px"><?php echo html_specialchars($content["bid"]["text"]) ?></textarea></td>
</tr>
<tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="4"></td></tr>
<tr>
  <td align="right" class="chatlist">&nbsp;</td>
  <td class="chatlist">###BID_EMAIL###, ###BID_AMOUNT###, &lt;!--FORM_ERROR_START--&gt;&lt;!--FORM_ERROR_END--&gt;</td>
</tr>
<tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="1"></td></tr>
<tr>
  <td align="right" class="chatlist" valign="top"><img src="img/leer.gif" alt="" width="1" height="15"><?php echo $BL['be_cnt_ecardform'] ?>:&nbsp;</td>
  <td valign="top"><textarea name="cbid_form" cols="40" rows="5" class="code" id="cbid_form" style="width: 440px"><?php echo html_specialchars($content["bid"]["form"]) ?></textarea></td>
</tr>
<tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="2"></td></tr>
<tr>
  <td align="right" class="chatlist" valign="top"><img src="img/leer.gif" alt="" width="1" height="15"><?php echo $BL['be_cnt_bid_sendtext'] ?>:&nbsp;</td>
  <td valign="top"><textarea name="cbid_sent" cols="40" rows="3" class="code" id="cbid_sent" style="width: 440px"><?php echo html_specialchars($content["bid"]["sent"]) ?></textarea></td>
</tr>
<tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="6"></td></tr>
<tr><td colspan="2"><img src="img/lines/l538_70.gif" alt="" width="538" height="1"></td></tr>
<tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="6"></td></tr>

<tr>
  <td align="right" class="chatlist"><?php echo $BL['be_newsletter_fromemail'] ?>:&nbsp;</td>
  <td><input name="cbid_emailfrom" type="text" class="code" id="cbid_emailfrom" style="width: 440px" value="<?php echo html_specialchars($content["bid"]["emailfrom"]) ?>" size="40"></td>
</tr>
<tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="2"></td></tr>
<tr>
  <td align="right" class="chatlist"><?php echo $BL['be_newsletter_fromname'] ?>:&nbsp;</td>
  <td><input name="cbid_emailfromname" type="text" class="code" id="cbid_emailfromname" style="width: 440px" value="<?php echo html_specialchars($content["bid"]["emailfromname"]) ?>" size="40"></td>
</tr>
<tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="2"></td></tr>
<tr>
  <td align="right" class="chatlist">&nbsp;</td>
  <td class="chatlist">###START_BID###, ###VERIFY_LINK###, ###DELETE_LINK###, ###EMAIL###, ###BID###, ###BID_START:FORMAT###, ###BID_END:FORMAT###, ###BID_URL###</td>
</tr>
<tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="1"></td></tr>
<tr>
  <td align="right" class="chatlist" valign="top"><img src="img/leer.gif" alt="" width="1" height="15"><?php echo $BL['be_cnt_bid_verifyemail'] ?>:&nbsp;</td>
  <td valign="top"><textarea name="cbid_emailmsg" cols="40" rows="5" class="code" id="cbid_emailmsg" style="width: 440px"><?php echo html_specialchars($content["bid"]["emailmsg"]) ?></textarea></td>
</tr>
<tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="2"></td></tr>
<tr>
  <td align="right" class="chatlist" valign="top"><img src="img/leer.gif" alt="" width="1" height="15"><?php echo $BL['be_cnt_bid_verifiedtext'] ?>:&nbsp;</td>
  <td valign="top"><textarea name="cbid_verified" cols="40" rows="3" class="code" id="cbid_verified" style="width: 440px"><?php echo html_specialchars($content["bid"]["verified"]) ?></textarea></td>
</tr>
<tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="2"></td></tr>
<tr>
  <td align="right" class="chatlist" valign="top"><img src="img/leer.gif" alt="" width="1" height="15"><?php echo $BL['be_cnt_bid_errortext'] ?>:&nbsp;</td>
  <td valign="top"><textarea name="cbid_notverified" cols="40" rows="3" class="code" id="cbid_notverified" style="width: 440px"><?php echo html_specialchars($content["bid"]["notverified"]) ?></textarea></td>
</tr>
<tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="6"></td></tr>
<tr><td colspan="2"><img src="img/lines/l538_70.gif" alt="" width="538" height="1"></td></tr>
<tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="6"></td></tr>
<tr>
  <td align="right" class="chatlist" valign="top"><img src="img/leer.gif" alt="" width="1" height="15"><?php echo $BL['be_cnt_guestbook_after'] ?>:&nbsp;</td>
  <td valign="top"><textarea name="cbid_after" cols="40" rows="3" class="code" id="cbid_after" style="width: 440px"><?php echo html_specialchars($content["bid"]["after"]) ?></textarea></td>
</tr>

<tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="6"></td></tr>
<tr><td colspan="2"><img src="img/lines/l538_70.gif" alt="" width="538" height="1"></td></tr>
<tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="10"></td></tr>

<?php 
// show possibility to edit guestbook entries
// if content part is created
if($content["id"]) {
?>
<tr>
  <td align="right" valign="top" class="chatlist"><img src="img/leer.gif" alt="" width="1" height="15"><?php echo $BL['be_cnt_guestbook_edit'] ?>:&nbsp;</td>
  <td><iframe height="200" width="440" frameborder="0" scrolling="auto" src="include/inc_act/act_bid.php?cid=<?php echo $content["id"] ?>"></iframe></td>
</tr>
<tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="10"></td></tr>
<tr><td colspan="2"><img src="img/lines/l538_70.gif" alt="" width="538" height="1"></td></tr>
<tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="10"></td></tr>
<?php
}
?>