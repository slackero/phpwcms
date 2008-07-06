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


//link & email

?>

<tr><td colspan="2" class="rowspacer0x7"><img src="img/leer.gif" alt="" width="1" height="1" /></td></tr>

<tr>
	<td align="right" class="chatlist"><?php echo $BL['be_admin_struct_template']; ?>:&nbsp;</td>
	<td><select name="template" id="template" class="f11b">
<?php
	
	echo '<option value="">'.$BL['be_admin_tmpl_default'].'</option>'.LF;

// templates for frontend login
$tmpllist = get_tmpl_files(PHPWCMS_TEMPLATE.'inc_cntpart/linkemail');
if(is_array($tmpllist) && count($tmpllist)) {
	foreach($tmpllist as $val) {
		$selected_val = (isset($content["template"]) && $val == $content["template"]) ? ' selected="selected"' : '';
		$val = html_specialchars($val);
		echo '	<option value="' . $val . '"' . $selected_val . '>' . $val . '</option>' . LF;
	}
}

?>				  
		</select></td>
</tr>

<tr><td colspan="2" class="rowspacer7x7"><img src="img/leer.gif" alt="" width="1" height="1" /></td></tr>


<tr>
            	<td align="right" class="chatlist"><?php echo $BL['be_cnt_directlink'] ?>:&nbsp;</td>
            	<td valign="top"><input name="clink" type="text" id="clink" class="f11b" style="width:440px" value="<?php echo  isset($content["link"]) ? html_specialchars($content["link"]) : '' ?>" size="40"></td>
			</tr>
			<tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="2"></td></tr>
			<tr>
			  <td align="right" class="chatlist"><?php echo $BL['be_cnt_target'] ?>:&nbsp;</td>
			  <td valign="top"><select name="ctarget" class="f11" id="ctarget">
                <option value="" <?php 
				if(!isset($content["target"])) {
					$content["target"] = '';
				}
				is_selected("", $content["target"]) ?>> </option>
                <option value="_blank" <?php is_selected("_blank", $content["target"]) ?>><?php echo $BL['be_cnt_target1'] ?></option>
                <option value="_parent" <?php is_selected("_parent", $content["target"]) ?>><?php echo $BL['be_cnt_target2'] ?></option>
                <option value="_top" <?php is_selected("_top", $content["target"]) ?>><?php echo $BL['be_cnt_target3'] ?></option>
                <option value="_self" <?php is_selected("_self", $content["target"]) ?>><?php echo $BL['be_cnt_target4'] ?></option>
              </select></td>
</tr>