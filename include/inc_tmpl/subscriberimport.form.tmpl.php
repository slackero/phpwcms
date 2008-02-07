<?php
/*************************************************************************************
   Copyright notice
   
   (c) 2002-2008 Oliver Georgi (oliver@phpwcms.de) // All rights reserved.
 
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


$_userInfo['max_file_size'] = return_bytes(@ini_get('upload_max_filesize'));

// select channel
$_userInfo['select_subscr'] = '';
$_userInfo['subscriptions'] = _dbQuery("SELECT * FROM ".DB_PREPEND."phpwcms_subscription ORDER BY subscription_name");

if($_userInfo['subscriptions']) {
		
	foreach($_userInfo['subscriptions'] as $value) {
	
		$_userInfo['select_subscr'] .= '		<tr>
			<td><input type="checkbox" name="subscribe_select['.$value['subscription_id'].
			']" id="subscribe_select'.$value['subscription_id'].'" value="'.$value['subscription_id'].'"';
		if(in_array($value['subscription_id'], $_userInfo['subscribe_select'])) {
			$_userInfo['select_subscr'] .= ' checked="checked"';
		}
		$_userInfo['select_subscr'] .= ' /></td>
			<td><label for="subscribe_select'.$value['subscription_id'].'">'.
			html_specialchars($value['subscription_name']).
			'</label></td>
		</tr>
		';
	}
	
	if($_userInfo['select_subscr']) {

		$_userInfo['select_subscr'] = $_userInfo['select_subscr'] . '</table>';
		
		$_userInfo['select_subscr'] = '<table cellpadding="0" cellspacing="0" border="0">
			<tr>
				<td><input type="checkbox" name="subscribe_all" id="subscribe_all" value="1"'.is_checked($_userInfo['subscribe_all'], 1, 1, 0).' /></td>
				<td><label for="subscribe_all">'.$BL['be_newsletter_allsubscriptions'].'</label></td>
			</tr>
			' .  $_userInfo['select_subscr'];					

	}

}


?>
<form action="phpwcms.php?do=messages&amp;p=4&amp;import=1" method="post" name="importsubscriber" id="importsubscriber" enctype="multipart/form-data" style="background:#F3F5F8;border-top:1px solid #92A1AF;border-bottom:1px solid #92A1AF;margin:0 0 5px 0;padding:10px 10px 15px 10px">
<table border="0" cellpadding="0" cellspacing="0" summary="">

	<?php
	if(!empty($_userInfo['csvError'])) {
	
		echo '	<tr> 
		<td align="right" valign="top"><img src="img/famfamfam/mini/icon_alert.gif" alt="Error" />&nbsp;</td>
		<td class="error1" style="padding: 2px 0 10px 0">'.html_specialchars($_userInfo['csvError']).'</td>
	</tr>';
	
	}
	
	?>

	<tr> 
		<td align="right" class="chatlist">&nbsp;</td>
		<td><?php echo $BL['be_newsletter_shouldbe1'] ?><div class="csv">emailA;nameA<br />&quot;emailB&quot;;&quot;nameB&quot;</div></td>
	</tr>

	<tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="10" /></td></tr>

	<tr> 
		<td align="right" class="chatlist" nowrap="nowrap"><?php 
		
		echo $BL['be_newsletter_selectCSV'];
		echo '<input type="hidden" name="MAX_FILE_SIZE" value="'.$_userInfo['max_file_size'].'" />';
		
		?>:&nbsp;</td>
		<td><input name="cvsfile" type="file" class="f11" style="width:300px;" size="35" /></td>
	</tr>
	
	<tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="5" /></td></tr>
	
	<tr>
	  <td align="right" class="chatlist"><?php echo $BL['be_newsletter_delimeter'] ?>:&nbsp;</td>
	  <td><table border="0" cellpadding="0" cellspacing="0" summary="">
	  	<tr>		
			<td><input type="text" class="f11b" style="width:30px;text-align:center;" name="delimeter" size="5" value="<?php echo html_specialchars($_userInfo['delimeter']) ?>" /></td>
			<td class="v10">&nbsp;<?php echo $BL['be_newsletter_shouldbe2'] ?></td>
		</tr>
		</table></td>
    </tr>
	
<?php
	
if($_userInfo['select_subscr']) {
	
	echo '<tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="10" /></td></tr>'.LF;
	echo '<tr>'.LF.'<td class="chatlist" align="right" valign="top" style="padding-top:3px;">'.$BL['be_cnt_subscription'].':&nbsp;</td>'.LF;
	echo '<td>'.$_userInfo['select_subscr'].'</td>'.LF.'</tr>';

}
	
?>

	<tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="8" /></td></tr>
	
	<tr>
		<td align="right" class="chatlist"><?php echo $BL['be_ftptakeover_status'] ?>:&nbsp;</td>
		<td><table border="0" cellpadding="0" cellspacing="0" summary="">		
			<tr>
				<td><input type="checkbox" name="subscribe_active" id="subscribe_active" value="1"<?php is_checked($_userInfo['subscribe_active'], 1) ?> /></td>
				<td><label for="subscribe_active"><?php echo $BL['be_cnt_activated'] ?></label></td>
			</tr>
		</table></td>
	</tr>
	
	
	<tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="12" /></td></tr>
	<tr> 
		<td>&nbsp;</td>
		<td>
			<input type="submit" name="submitimport" id="submitimport" value="<?php echo $BL['be_newsletter_newimport'] ?>" class="button10" />
			&nbsp;&nbsp;&nbsp;&nbsp;
			<input name="close" type="button" class="button10" value="<?php echo $BL['be_admin_struct_close'] ?>" onclick="location.href='phpwcms.php?do=messages&p=4';return false;" />
		</td>
	</tr>
</table>
</form>
