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


// RSS feed

if(!isset($content['rssfeed'])) {
	$content['rssfeed']["rssurl"]		= '';
	$content["rssfeed"]["item"]			= '';
	$content['rssfeed']["cut1st"]		= 0;
	$content['rssfeed']["cacheoff"]		= 0;
	$content['rssfeed']["timeout"]		= 0;
	$content["rssfeed"]['template'] 	= '';
	$content["rssfeed"]['content_type'] = '';
}


?>

<tr><td colspan="2" class="rowspacer0x7"><img src="img/leer.gif" alt="" width="1" height="1" /></td></tr>

<tr>
	<td align="right" class="chatlist"><?php echo $BL['be_admin_struct_template'] ?>:&nbsp;</td>
	<td><select name="crss_template" id="crss_template">
  <?php

	echo '<option value="">'.$BL['be_admin_tmpl_default'].'</option>'.LF;

// templates for RSS feed
$tmpllist = get_tmpl_files(PHPWCMS_TEMPLATE.'inc_cntpart/rssfeed');
if(is_array($tmpllist) && count($tmpllist)) {
	foreach($tmpllist as $val) {
		$vals = '';
		if($val == $content["rssfeed"]['template']) $vals= ' selected="selected"';
		$val = htmlspecialchars($val);
		echo '<option value="'.$val.'"'.$vals.'>'.$val."</option>\n";
	}
}


?>
	  </select></td></tr>
<tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="10"></td></tr>
<tr><td colspan="2"><img src="img/lines/l538_70.gif" alt="" width="538" height="1"></td></tr>
<tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="7"></td></tr>
<tr>
	<td align="right" class="chatlist"><?php echo $BL['be_cnt_rssfeed_url'] ?>:&nbsp;</td>
	<td valign="top"><input name="crss_url" type="text" id="crss_url" class="f11b" style="width:440px" value="<?php echo html($content['rssfeed']["rssurl"]) ?>" size="40"></td>
</tr>

<tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="5"></td></tr>

<tr>
  <td align="right" class="chatlist"><?php echo  $BL['be_cnt_rssfeed_item'] ?>:&nbsp;</td>
  <td><table border="0" cellpadding="0" cellspacing="0" summary="">
	<tr>
	<td><input name="crss_item" type="text" class="f11b" id="crss_item" style="width: 50px;" size="10" maxlength="10" onKeyUp="if(!parseInt(this.value,10))this.value='';" value="<?php echo  $content["rssfeed"]["item"] ?>"></td>
	<td class="f10">&nbsp;<?php echo $BL['be_cnt_rssfeed_max'] ?></td>
	<td>&nbsp;&nbsp;&nbsp;&nbsp;</td>
	<td class="chatlist"><label for="crss_contenttype"><?php echo $BL['be_cnt_source'].' '.$BL['content_type'] ?></label>:&nbsp;</td>
	<td><select name="crss_contenttype" id="crss_contenttype" class="v12">
		<option value=""<?php
if(empty($content["rssfeed"]['content_type'])) {
	echo ' selected="selected"';
}
		?>><?php echo $BL['automatic'] ?></option>
<?php

	foreach($phpwcms['charsets'] as $value) {
		echo '		<option value="'.$value.'"';
		if(!empty($content["rssfeed"]['content_type']) && $value == $content["rssfeed"]['content_type']) {
			echo ' selected="selected"';
		}
		echo '>'.$value.'</option>' . LF;
	}

		?>
	</select></td>

	<!--
	<td class="chatlist"><label for="crss_cut1st"><?php echo  $BL['be_cnt_rssfeed_cut'] ?></label>:&nbsp;</td>
	<td bgcolor="#E7E8EB"><input name="crss_cut1st" type="checkbox" id="crss_cut1st" value="1"<?php echo  is_checked(1, $content['rssfeed']["cut1st"]) ?>></td>

	<td bgcolor="#E7E8EB"><img src="img/leer.gif" alt="" width="1" height="1"></td>
	//-->
	</tr>
	</table></td>
</tr>

<tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="5"></td></tr>

<tr>
  <td align="right" class="chatlist"><?php echo  $BL['be_cache'] ?>:&nbsp;</td>
  <td><table border="0" cellpadding="0" cellspacing="0" bgcolor="#E7E8EB" summary="">
	<tr>
	<td><input name="crss_cacheoff" type="checkbox" id="crss_cacheoff" value="1"<?php echo  is_checked(1, $content['rssfeed']["cacheoff"]) ?>></td>
	<td style="font-size:10px;padding-left:3px;"><label for="crss_cacheoff"><?php echo $BL['be_off'] ?></label>&nbsp;</td>
	<td>&nbsp;</td>
	<td><select name="crss_timeout" style="margin:2px;" onChange="document.articlecontent.crss_cacheoff.checked=false;">
<?php
echo '<option value="0"'.is_selected($content['rssfeed']["timeout"], '0', 0, 0).'>'.$BL['be_admin_tmpl_default']."</option>\n";
echo '<option value="60"'.is_selected($content['rssfeed']["timeout"], '60', 0, 0).'>&nbsp;&nbsp;1 '.$BL['be_date_minute']."</option>\n";
echo '<option value="300"'.is_selected($content['rssfeed']["timeout"], '300', 0, 0).'>&nbsp;&nbsp;5 '.$BL['be_date_minutes']."</option>\n";
echo '<option value="900"'.is_selected($content['rssfeed']["timeout"], '900', 0, 0).'>15 '.$BL['be_date_minutes']."</option>\n";
echo '<option value="1800"'.is_selected($content['rssfeed']["timeout"], '1800', 0, 0).'>30 '.$BL['be_date_minutes']."</option>\n";
echo '<option value="3600"'.is_selected($content['rssfeed']["timeout"], '3600', 0, 0).'>&nbsp;&nbsp;1 '.$BL['be_date_hour']."</option>\n";
echo '<option value="14400"'.is_selected($content['rssfeed']["timeout"], '14400', 0, 0).'>&nbsp;&nbsp;4 '.$BL['be_date_hours']."</option>\n";
echo '<option value="43200"'.is_selected($content['rssfeed']["timeout"], '43200', 0, 0).'>12 '.$BL['be_date_hours']."</option>\n";
echo '<option value="86400"'.is_selected($content['rssfeed']["timeout"], '86400', 0, 0).'>&nbsp;&nbsp;1 '.$BL['be_date_day']."</option>\n";
echo '<option value="172800"'.is_selected($content['rssfeed']["timeout"], '172800', 0, 0).'>&nbsp;&nbsp;2 '.$BL['be_date_days']."</option>\n";
echo '<option value="604800"'.is_selected($content['rssfeed']["timeout"], '604800', 0, 0).'>&nbsp;&nbsp;1 '.$BL['be_date_week']."</option>\n";
echo '<option value="1209600"'.is_selected($content['rssfeed']["timeout"], '1209600', 0, 0).'>&nbsp;&nbsp;2 '.$BL['be_date_weeks']."</option>\n";
echo '<option value="2592000"'.is_selected($content['rssfeed']["timeout"], '2592000', 0, 0).'>&nbsp;&nbsp;1 '.$BL['be_date_month']."</option>\n";
?>
	</select></td>
	<td style="font-size:10px;">&nbsp;<?php echo $BL['be_cache_timeout'] ?>&nbsp;&nbsp;</td>
	</tr>
	</table></td>
</tr>
