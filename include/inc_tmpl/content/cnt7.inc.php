<?php
/*************************************************************************************
   Copyright notice
   
   (c) 2002-2011 Oliver Georgi (oliver@phpwcms.de) // All rights reserved.
 
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


//file list

if(empty($content["file_descr"])) $content["file_descr"] = '';
$content['file']['direct_download'] = empty($content['file']['direct_download']) ? 0 : 1;


?>
<tr>
	<td align="right" class="chatlist"><?php echo $BL['be_admin_struct_template'] ?>:&nbsp;</td>
	<td><select name="cfile_template" id="cfile_template" class="f11b">

<?php

echo '<option value="">'.$BL['be_admin_tmpl_default'].'</option>'.LF;

// templates for recipes
$tmpllist = get_tmpl_files(PHPWCMS_TEMPLATE.'inc_cntpart/filelist');
if(is_array($tmpllist) && count($tmpllist)) {
	foreach($tmpllist as $val) {
		if(isset($content['file_template']) && $val == $content['file_template']) {
			$selected_val = ' selected="selected"';
		} else {
			$selected_val = '';
		}
		$val = htmlspecialchars($val);
		echo '	<option value="' . $val . '"' . $selected_val . '>' . $val . '</option>' . LF;
	}
}

?>				  
		</select></td>
</tr>
<tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="8"></td></tr>
<tr><td colspan="2"><img src="img/lines/l538_70.gif" alt=""></td></tr>
<tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="8"></td></tr>

<tr>
  <td align="right" class="chatlist"><?php echo $BL['be_cnt_download'] ?>:&nbsp;</td>
  <td><table border="0" cellpadding="0" cellspacing="0" bgcolor="#E7E8EB" summary="">
      <tr>
	   <td><input name="cfile_direct" id="cfile_direct" type="checkbox" value="1" <?php is_checked(1, $content['file']['direct_download']); ?>></td>
	   <td class="v10"><label for="cfile_direct">&nbsp;<?php echo $BL['be_cnt_download_direct'] ?></label>&nbsp;&nbsp;</td>
	   <td><img src="img/leer.gif" alt="" width="1" height="22"></td>	
	  </tr>
	  </table></td>
</tr>

<tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="8"></td></tr>

<tr>
  <td align="right" valign="top" class="chatlist"><img src="img/leer.gif" alt="" width="1" height="13"><?php echo $BL['be_cnt_files'] ?>:&nbsp;</td>
  <td valign="top"><table border="0" cellpadding="0" cellspacing="0" summary="">
	<tr>
	  <td valign="top"><select name="cfile_list[]" size="8" multiple class="f11" id="cfile_list" style="width: 300px;">
	  <?php
	  if(isset($content["file_list"]) && is_array($content["file_list"]) && count($content["file_list"])) {
		$fx  = 0;
		$fxa = "";
		$fxb = array();
		foreach($content["file_list"] as $key => $value) {
			if($fx) $fxa .= " OR ";
			$fxa .= "f_id=".intval($value);
			$fxb[$key]["fid"] = intval($value);
			$fx++;
		}
		if($fx) {
			$file_sql = "SELECT f_id, f_name FROM ".DB_PREPEND."phpwcms_file WHERE f_public=1 AND f_aktiv=1".
						" AND f_kid=1 AND f_trash=0 AND (".$fxa.");"; //f_uid=".$_SESSION["wcs_user_id"]
			if($file_result = mysql_query($file_sql, $db) or die ("error while retrieving file list file's info")) {
				while($file_row = mysql_fetch_row($file_result)) {
					foreach($fxb as $key => $value) {
						if($fxb[$key]["fid"] == $file_row[0]) {
							$fxb[$key]["fname"] = html_specialchars($file_row[1]);
						}
					}
				}
			}
			foreach($fxb as $key => $value) {
				if(!empty($fxb[$key]["fname"])) {
					echo "<option value=\"".$fxb[$key]["fid"]."\">".$fxb[$key]["fname"]."</option>\n";
				}
			}
			unset($fxb); unset($content["file_list"]);
		}
	  }
	  
	  
	  ?>
	  </select></td>
	  <td valign="top"><img src="img/leer.gif" alt="" width="5" height="1"></td>                                           <!-- browser_file.php //-->
	  <td valign="top"><a href="javascript:;" title="<?php echo $BL['be_cnt_openfilebrowser'] ?>" onclick="tmt_winOpen('filebrowser.php?opt=4&amp;target=nolist','imageBrowser','width=380,height=300,left=8,top=8,scrollbars=yes,resizable=yes,status=yes',1)"><img src="img/button/open_image_button.gif" alt="" width="20" height="15" border="0"></a><br />
	  <img src="img/leer.gif" alt="" width="1" height="4"><br />
	  <a href="javascript:;" title="<?php echo $BL['be_cnt_sortup'] ?>" onclick="moveOptionUp(document.articlecontent.cfile_list);"><img src="img/button/image_pos_up.gif" alt="" width="10" height="9" border="0"></a><a href="javascript:;" title="<?php echo $BL['be_cnt_sortdown'] ?>" onclick="moveOptionDown(document.articlecontent.cfile_list);"><img src="img/button/image_pos_down.gif" alt="" width="10" height="9" border="0"></a><br />
	  <img src="img/leer.gif" alt="" width="1" height="4"><br />
	  <a href="javascript:;" onclick="removeSelectedOptions(document.articlecontent.cfile_list);" title="<?php echo $BL['be_cnt_delfile'] ?>"><img src="img/button/del_image_button1.gif" alt="" width="20" height="15" border="0"></a></td>
	</tr>
  </table></td>
  </tr>
<tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="5"></td></tr>
<tr>
  <td align="right" valign="top" class="chatlist"><img src="img/leer.gif" alt="" width="1" height="13"><?php echo $BL['be_cnt_description'] ?>:&nbsp;</td>
  <td valign="top"><textarea name="cfile_descr" cols="40" rows="8" class="f11" id="cfile_descr" style="width: 440px;"><?php 
  
	if(!empty($content["file_descr"]) && ($content["file_descr"]{0} == "\r" || $content["file_descr"]{0} == "\n")) {
		echo ' ';
	}
	echo html_specialchars($content["file_descr"]); 
  
  ?></textarea></td>
</tr>


<tr><td colspan="2" class="rowspacer7x7"><img src="img/leer.gif" alt="" width="1" height="1" /></td></tr>

<tr><td colspan="2" align="center"><?php

$wysiwyg_editor = array(
	'value'		=> isset($content["html"]) ? $content["html"] : '',
	'field'		=> 'chtml',
	'height'	=> '250px',
	'width'		=> '536px',
	'rows'		=> '15',
	'editor'	=> $_SESSION["WYSIWYG_EDITOR"],
	'lang'		=> 'en'
);

include(PHPWCMS_ROOT.'/include/inc_lib/wysiwyg.editor.inc.php');


?></td></tr>

<tr><td colspan="2" class="rowspacer7x7"><img src="img/leer.gif" alt="" width="1" height="1" /></td></tr>