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


//article menu

if(!isset($content["alist"]["cat"])) {
	$content["alist"]["cat"] = 0;
}
if(!isset($content["alist"]["catid"])) {
	$content["alist"]["catid"] = 0;
}
if(!isset($content["alist"]["headertext"])) {
	$content["alist"]["headertext"] = 0;
}
if(!isset($content["alist"]["div"])) {
	$content["alist"]["div"] = 0;
}
if(!isset($content["alist"]["ul"])) {
	$content["alist"]["ul"] = 0;
}
if(!isset($content["alist"]["class"])) {
	$content["alist"]["class"] = '';
}
if(empty($content["alist"]["maxchar"])) {
	$content["alist"]["maxchar"] = '';
}
if(empty($content["alist"]["morelink"])) {
	$content["alist"]["morelink"] = '';
}
if(empty($content["alist"]["titlewrap"])) {
	$content["alist"]["titlewrap"] = '';
}
if(!isset($content["alist"]["hideactive"])) {
	$content["alist"]["hideactive"] = 0;
}


?>
<tr><td colspan="2" class="rowspacer0x7"><img src="img/leer.gif" alt="" width="1" height="1" /></td></tr>
<tr>
<td align="right" valign="top" class="chatlist tdtop3"><?php echo $BL['be_cnt_sitelevel'] ?>:&nbsp;</td>
<td valign="top"><table border="0" cellpadding="0" cellspacing="0" summary="">
  <tr>
    <td valign="top"><input name="calist_cat" type="radio" value="0" <?php is_checked(0, intval($content["alist"]["cat"])) ?>></td>
	<td>&nbsp;</td>
    <td class="tdtop3 tdbottom5"><strong><?php echo $BL['be_cnt_sitecurrent'] ?></strong></td>
  </tr>
  <tr>
    <td><input name="calist_cat" type="radio" value="1" <?php is_checked(1, intval($content["alist"]["cat"])) ?>></td>
	<td>&nbsp;</td>
    <td><select name="calist_catid" style="width: 325px" class="f11b">
<?php
	echo "<option value='0'".((!$content["alist"]["catid"])?" selected":"").">".$BL['be_admin_struct_index']."</option>\n";
	struct_select_menu($db, 0, 0, $content["alist"]["catid"]);
?></select></td>
  </tr>

</table></td>
</tr>

<tr><td colspan="2" class="rowspacer7x7"><img src="img/leer.gif" alt="" width="1" height="1" /></td></tr>

<tr>
<td class="chatlist tdtop4" align="right" valign="top"><?php echo $BL['be_show_content'] ?>:&nbsp;</td>
<td colspan="6"><table border="0" cellpadding="0" cellspacing="0" summary="">
	<tr>

	<td><input name="calist_ul" id="calist_ul0" type="radio" value="0" <?php is_checked(0, intval($content["alist"]["ul"])) ?> class="radioButton"></td>
	<td><label for="calist_ul0"><?php echo $BL['be_admin_page_table'] ?>&nbsp;</label></td>

	<td><input name="calist_ul" id="calist_ul1" type="radio" value="1" <?php is_checked(1, intval($content["alist"]["ul"])) ?> class="radioButton"></td>
	<td><label for="calist_ul1">&lt;UL&gt;&nbsp;</label></td>

	<td><input name="calist_ul" id="calist_ul2" type="radio" value="2" <?php is_checked(2, intval($content["alist"]["ul"])) ?> class="radioButton"></td>
	<td><label for="calist_ul2">&lt;DIV&gt;&nbsp;</label></td>
	
	<td>&nbsp;&nbsp;&nbsp;</td>
	
	<td class="chatlist" align="right">&nbsp;<?php echo $BL['be_cnt_css_class'] ?>:&nbsp;</td>
	<td><input type="text" name="calist_class" id="calist_class" class="v11" style="width:110px;" value="<?php echo html_specialchars($content["alist"]["class"]) ?>"></td>
	
	</tr>
	</table>
	
	
	<table border="0" cellpadding="0" cellspacing="0" style="margin-top:8px;" summary="">
	<tr>
	<td><input name="calist_headertext" id="calist_headertext" type="checkbox" value="1" <?php is_checked(1, intval($content["alist"]["headertext"])) ?> class="checkBox"></td>
	<td><label for="calist_headertext"><?php echo $BL['be_article_asummary'] ?>&nbsp;</label></td>
	
	<td>&nbsp;&nbsp;&nbsp;</td>

	<td class="chatlist">&nbsp;<?php echo $BL['be_cnt_articlemenu_maxchar'] ?>:&nbsp;</td>
	<td><input type="text" name="calist_maxchar" id="calist_maxchar" class="v11" style="width:35px;" value="<?php echo $content["alist"]["maxchar"] ?>"></td>
	<td>&nbsp;</td>
	<td class="chatlist">&nbsp;<?php echo $BL['be_article_morelink'] ?>:&nbsp;</td>
	<td><input type="text" name="calist_morelink" id="calist_morelink" class="v11 width100" value="<?php echo html_specialchars($content["alist"]["morelink"]) ?>"></td>
	
	</tr>
	</table>
	
	<table border="0" cellpadding="0" cellspacing="0" style="margin-top:8px;" summary="">
	<tr>
		<td class="chatlist">&nbsp;<?php echo $BL['be_title_wrap'] ?>:&nbsp;</td>
		<td><select name="calist_titlewrap" id="calist_titlewrap" class="v11">
<?php

	echo '	<option value=""';
	is_selected(0, $content["alist"]["titlewrap"]);
	echo '>'.$BL['be_cnt_default'].' ('.$BL['be_func_struct_empty'].')</option>'.LF;
	
	foreach(array('p','div','span','h1','h2','h3','h4','h5','h6','pre','blockquote','em') as $value) {		
	
		echo '	<option value="'.$value.'"';
		is_selected($value, $content["alist"]["titlewrap"]);
		echo '>'.strtoupper($value).'</option>'.LF;
	
	}
					
?>					
		</select></td>
	</tr>

	</table>
	
	<table border="0" cellpadding="0" cellspacing="0" style="margin-top:8px;" summary="">
	<tr>
	<td><input name="calist_hideactive" id="calist_hideactive" type="checkbox" value="1" <?php is_checked(1, intval($content["alist"]["hideactive"])) ?> class="checkBox"></td>
	<td><label for="calist_hideactive"><?php echo $BL['be_hide_active_articlelink'] ?>&nbsp;</label></td>
	
	</tr>
	</table>
	
	
	</td>
</tr>

<tr><td colspan="2" class="rowspacer7x0"><img src="img/leer.gif" alt="" width="1" height="1" /></td></tr>

