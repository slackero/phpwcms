<?php
/*************************************************************************************
   Copyright notice
   
   (c) 2002-2010 Oliver Georgi (oliver@phpwcms.de) // All rights reserved.
 
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


// Forum

?><!--
<link href="../../inc_css/phpwcms.css" rel="stylesheet" type="text/css">
<table cellpadding="0" cellspacing="0" border="0" bgcolor="#FFFFFF"m width="440">
  //-->


<tr>
	<td align="right" class="chatlist"><?php echo $BL['be_admin_struct_template'] ?>:&nbsp;</td>
	<td><select name="cforum_template" id="cforum_template" class="f11b">
<?php
// templates for forum
$tmpllist = get_tmpl_files(PHPWCMS_TEMPLATE.'inc_cntpart/forum');
if(is_array($tmpllist) && count($tmpllist)) {
	foreach($tmpllist as $val) {
		$vals = '';
		if($val == $content["forum"]['template']) $vals= ' selected="selected"';
		$val = htmlspecialchars($val);
		echo '<option value="'.$val.'"'.$vals.'>'.$val."</option>\n";
	}
}
				  
?>				  
		</select></td>
</tr>
<tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="10"></td></tr>
<tr><td colspan="2"><img src="img/lines/l538_70.gif" alt="" width="538" height="1"></td></tr>
<tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="5"></td></tr>
<tr>
	<td align="right" class="chatlist" valign="top"><?php echo $BL['be_subnav_msg_forum'] ?>:&nbsp;</td>
	<td><table border="0" cellpadding="0" cellspacing="0" summary="">
	<tr>
	  <td valign="top" class="v10"><?php echo $BL['be_cnt_activated']	?></td>
	  <td class="chatlist" style="width:30px;">&nbsp;</td>
	  <td valign="top" class="v10"><?php echo $BL['be_cnt_available']	?></td>
	</tr>
	<tr>
	  <td colspan="3"><img src="img/leer.gif" alt="" width="1" height="2"></td>
	</tr>
	<tr>
	<td valign="top"><select name="cforum_selection[]" size="8" multiple class="f11b" id="cforum_selection" style="width:205px;" onDblClick="moveSelectedOptions(document.articlecontent.cforum_selection,document.articlecontent.cforum_available,false);">
<?php
$content["forum"]['available'] = '';

if(!isset($content["forum"]['selection']) || !is_array($content["forum"]['selection'])) {
	$content["forum"]['selection'] = array();
}
$content["forum"]['selected'] = array();
foreach($content["forum"]['selection'] as $selected_value) {
	$content["forum"]['selected'][intval($selected_value)] = '';
}

$sqlf = "SELECT * FROM ".DB_PREPEND."phpwcms_forum WHERE forum_entry=0 AND forum_deleted=0 ORDER BY forum_title;";
if($result = mysql_query($sqlf, $db) or die("error while listing forums")) {
	while($row = mysql_fetch_assoc($result)) {
		if(isset($content["forum"]['selected'][$row["forum_id"]])) {
			$content["forum"]['selected'][$row["forum_id"]] = '<option value="'.$row["forum_id"].'">'.html_specialchars($row["forum_title"])."</option>\n";
		} else {
			$content["forum"]['available'] .= '<option value="'.$row["forum_id"].'">'.html_specialchars($row["forum_title"])."</option>\n";
		}
	}
	mysql_free_result($result);
} // end listing

echo implode("\n", $content["forum"]['selected']);

?>	
	</select></td>
	<td valign="top" align="center" width="30"><a href="javascript:;" onclick="moveSelectedOptions(document.articlecontent.cforum_available,document.articlecontent.cforum_selection,false);"><img src="img/button/list_move_left.gif" border="0" alt="" width="20" height="15" border="0"></a><br><img src="img/leer.gif" alt="" width="1" height="3"><br><a href="javascript:;" onclick="moveSelectedOptions(document.articlecontent.cforum_selection,document.articlecontent.cforum_available,false);"><img src="img/button/list_move_right.gif" alt="" width="20" height="15" border="0"></a><br><img src="img/leer.gif" alt="" width="1" height="6"><br><a href="javascript:;" 
				  title="<?php echo $BL['be_cnt_sortup'] ?>" onclick="moveOptionUp(document.articlecontent.cforum_selection);return false;"><img src="img/button/image_pos_up.gif" alt="" width="10" height="9" border="0"></a><a href="javascript:;" 
				  title="<?php echo $BL['be_cnt_sortdown'] ?>" onclick="moveOptionDown(document.articlecontent.cforum_selection);return false;"><img src="img/button/image_pos_down.gif" alt="" width="10" height="9" border="0"></a></td>
	<td valign="top"><select name="cforum_available" size="8" class="f11" id="cforum_available" style="width:205px;" onDblClick="moveSelectedOptions(document.articlecontent.cforum_available,document.articlecontent.cforum_selection,false);">
<?php echo $content["forum"]['available'] ?>
	</select></td>
	</tr>
	</table></td>
</tr>
<tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="10"></td></tr>
<tr>
<td align="right" class="chatlist" valign="top"><?php echo  $BL['be_cnt_access'] ?>:&nbsp;</td>
<td valign="top"><table border="0" cellpadding="0" cellspacing="0" summary="">
<?php
if(!isset($content['forum']['permissions'])) {

	$content['forum']['permissions'] = array(	
		'read'		=> array('admin' => 1, 'user' => 1, 'guest' => 0),
		'write'		=> array('admin' => 1, 'user' => 0, 'guest' => 0),
		'delete'	=> array('admin' => 1, 'user' => 0, 'guest' => 0)
	);
}

?>
	<tr>
		<td>&nbsp;</td>
		<td class="chatlist" align="center">&nbsp;<?php echo $BL['be_cnt_admin'] ?>&nbsp;</td>
		<td class="chatlist" align="center">&nbsp;<?php echo $BL["login_username"] ?>&nbsp;</td>
		<td class="chatlist" align="center">&nbsp;<?php echo $BL['be_cnt_guests'] ?>&nbsp;</td>
	</tr>
	<tr>
		<td align="right" class="v10"><?php echo $BL['be_cnt_read'] ?>:</td>
		<td align="center"><input type="checkbox" name="cforum_permission_admin_read" value="1"<?php echo is_checked('1', $content['forum']['permissions']['read']['admin'],0,0)?>></td>
		<td align="center"><input type="checkbox" name="cforum_permission_user_read" value="1"<?php echo is_checked('1', $content['forum']['permissions']['read']['user'],0,0)?>></td>
		<td align="center"><input type="checkbox" name="cforum_permission_guest_read" value="1"<?php echo is_checked('1', $content['forum']['permissions']['read']['guest'],0,0)?>></td>
	</tr>
	<tr>
		<td align="right" class="v10"><?php echo $BL['be_cnt_write'] ?>:</td>
		<td align="center"><input type="checkbox" name="cforum_permission_admin_write" value="1"<?php echo is_checked('1', $content['forum']['permissions']['write']['admin'],0,0)?>></td>
		<td align="center"><input type="checkbox" name="cforum_permission_user_write" value="1"<?php echo is_checked('1', $content['forum']['permissions']['write']['user'],0,0)?>></td>
		<td align="center"><input type="checkbox" name="cforum_permission_guest_write" value="1"<?php echo is_checked('1', $content['forum']['permissions']['write']['guest'],0,0)?>></td>
	</tr>
	<tr>
		<td align="right" class="v10"><?php echo $BL['be_cnt_delete'] ?>:</td>
		<td align="center"><input type="checkbox" name="cforum_permission_admin_delete" value="1"<?php echo is_checked('1', $content['forum']['permissions']['delete']['admin'],0,0)?>></td>
		<td align="center"><input type="checkbox" name="cforum_permission_user_delete" value="1"<?php echo is_checked('1', $content['forum']['permissions']['delete']['user'],0,0)?>></td>
		<td align="center"><input type="checkbox" name="cforum_permission_guest_delete" value="1"<?php echo is_checked('1', $content['forum']['permissions']['delete']['guest'],0,0)?>></td>
	</tr>
</table></td>
</tr>

<tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="10"></td></tr>
<tr><td colspan="2"><img src="img/lines/l538_70.gif" alt="" width="538" height="1"></td></tr>
<tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="5"></td></tr>

<!--  
</table>//-->