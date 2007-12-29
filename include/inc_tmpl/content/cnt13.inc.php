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

//search form

if(empty($content['search']["text_html"])) {
	$content['search']["text_html"] = 0;
}


?>
<tr><td colspan="2" class="rowspacer0x7"></td></tr>

  <tr>
    <td align="right" class="chatlist" valign="top"><?php echo $BL['be_cnt_results'] ?>:&nbsp;</td>
    <td valign="top"><table border="0" cellpadding="0" cellspacing="0" summary="">
      <tr>
        <td><input name="csearch_result_per_page" type="text" id="csearch_result_per_page" class="f11b" style="width: 30px" value="<?php echo  isset($content["search"]["result_per_page"]) ? $content["search"]["result_per_page"] : '' ?>" size="3" maxlength="5" /></td>
        <td class="v10">&nbsp;<?php echo $BL['be_cnt_results_per_page'] ?>&nbsp;&nbsp;&nbsp;&nbsp;</td>
        <td><input name="csearch_newwin" type="checkbox" id="csearch_newwin" value="1" <?php is_checked(1, isset($content["search"]["newwin"]) ? $content["search"]["newwin"] : 0) ?> /></td>
        <td class="v10"><?php echo $BL['be_cnt_opennewwin'] ?></td>
      </tr>
	  <tr><td colspan="4"><img src="img/leer.gif" alt="" width="1" height="2" /></td>
	  </tr>
	  <tr>
	  <td><input name="csearch_wordlimit" type="text" id="csearch_wordlimit" class="f11b" style="width: 30px" value="<?php echo  isset($content["search"]["wordlimit"]) ? $content["search"]["wordlimit"] : '' ?>" size="3" maxlength="5" /></td>
      <td class="v10">&nbsp;<?php echo $BL['be_cnt_results_wordlimit'] ?>&nbsp;&nbsp;&nbsp;&nbsp;</td>  
	  <td><input name="csearch_highlight" type="checkbox" id="csearch_highlight" value="1" <?php is_checked(1, isset($content["search"]["highlight_result"]) ? $content["search"]["highlight_result"] : 0) ?> /></td>
      <td class="v10"><?php echo $BL['be_cnt_search_highlight'] ?></td>
	  </tr>
	  <tr><td colspan="4"><img src="img/leer.gif" alt="" width="1" height="2" /></td>
	  </tr>
	  <tr>
	  <td><input name="csearch_minchar" type="text" id="csearch_minchar" class="f11b" style="width: 30px" value="<?php echo  isset($content["search"]["minchar"]) ? $content["search"]["minchar"] : '3' ?>" size="3" maxlength="5" /></td>
      <td class="v10" colspan="3">&nbsp;<?php echo $BL['be_cnt_results_minchar'] ?>&nbsp;&nbsp;&nbsp;&nbsp;</td>
	  </tr>
    </table></td>
  </tr>
  <tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="8" /></td></tr>
  
<tr>
				<td align="right" class="chatlist" valign="top"><?php echo $BL['be_cnt_search_startlevel'] ?>:&nbsp;</td>
				<td><select name="csearch_start_at[]" size="10" multiple="multiple" class="f11" id="csearch_start_at" style="width: 440px">
<?php
				if(!isset($content["search"]["start_at"]) || !is_array($content["search"]["start_at"])) $content["search"]["start_at"] = array();
								
				echo '<option value="0"';
				if(in_array(0, $content["search"]["start_at"])) echo ' selected';
				echo '>'.$BL['be_admin_struct_index'].'</option>'.LF;
				struct_select_list(0, 0, $content["search"]["start_at"]);
?>
				</select></td>
			</tr>
  
<?php

$content['search']['module_search'] = array();

// check modules for frontend search
foreach($phpwcms['modules'] as $value) {

	// check if module is fe searchable
	if($value['search'] === true && is_file($value['path'].'frontend.search.php')) {
		
		$value['tr']  = '<tr>';
		$value['tr'] .= '<td><input name="csearch_module['.$value['name'].']" type="checkbox" ';
		$value['tr'] .= 'id="csearch_module_'.$value['name'].'" value="1"';
		if( !empty( $content['search']['module'][ $value['name'] ] ) ) {
			$value['tr'] .= ' checked="checked"';
		}
		$value['tr'] .= ' /></td>';
        $value['tr'] .= '<td class="v10"><label for="csearch_module_'.$value['name'].'">';
		$value['tr'] .= $BL['be_ctype_module'].': '.$BL['modules'][ $value['name'] ]['backend_menu'] . '</label></td>';		
		$value['tr'] .= '</tr>';
		
		$content['search']['module_search'][] = $value['tr'];
	
	}

}

if(count($content['search']['module_search'])) {

	echo '<tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="6" /></td></tr>';
	echo '<tr><td align="right" class="chatlist tdtop4">'.$BL['be_module_search'].':&nbsp;</td>';
	echo '<td valign="top">';
	echo '<table border="0" cellpadding="1" cellspacing="0" summary="">';
	echo implode(LF, $content['search']['module_search'])	;
	echo '</table></td></tr>';
}
?>

 <tr><td colspan="2" class="rowspacer7x7"></td></tr>
  
   <tr>
    <td align="right" class="chatlist">&nbsp;&nbsp;</td>
    <td valign="top" class="chatlist"><?php echo $BL['be_cnt_searchlabeltext'] ?></td>
  </tr>
  <tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="4" /></td></tr>


  <tr>
    <td align="right" class="chatlist"><?php echo $BL['be_cnt_input'] ?>:&nbsp;</td>
    <td valign="top"><table border="0" cellpadding="0" cellspacing="0" summary="">
      <tr>
        <td><input name="csearch_label_input" type="text" class="f11b" id="csearch_label_input" style="width: 150px" value="<?php echo  isset($content["search"]["label_input"]) ? $content["search"]["label_input"] : '' ?>" size="10" maxlength="250" /></td>
        <td class="chatlist">&nbsp;&nbsp;&nbsp;<?php echo $BL['be_cnt_css_class'] ?>:&nbsp;</td>
        <td><input name="csearch_style_input" type="text" id="csearch_style_input" class="f11b" style="width: 200px" value="<?php echo  isset($content["search"]["style_input"]) ? $content["search"]["style_input"] : '' ?>" size="20" /></td>
      </tr>
    </table></td>
  </tr>
  <tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="2" /></td></tr>
  <tr>
    <td align="right" class="chatlist"><?php echo $BL['be_cnt_buttontext'] ?>:&nbsp;</td>
    <td valign="top"><table border="0" cellpadding="0" cellspacing="0" summary="">
      <tr>
        <td><input name="csearch_label_button" type="text" id="csearch_label_button" class="f11b" style="width: 150px" value="<?php echo  isset($content["search"]["label_button"]) ? $content["search"]["label_button"] : '' ?>" size="10" maxlength="75" /></td>
        <td class="chatlist">&nbsp;&nbsp;&nbsp;<?php echo $BL['be_cnt_css_class'] ?>:&nbsp;</td>
        <td><input name="csearch_style_button" type="text" id="csearch_style_button" class="f11b" style="width: 200px" value="<?php echo  isset($content["search"]["style_button"]) ? $content["search"]["style_button"] : '' ?>" size="20" /></td>
      </tr>
    </table></td>
  </tr>
  <tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="2" /></td>
</tr>
  <tr>
    <td align="right" class="chatlist"><?php echo $BL['be_cnt_result'] ?>:&nbsp;</td>
    <td valign="top"><table border="0" cellpadding="0" cellspacing="0" summary="">
      <tr>
        <td><input name="csearch_label_result" type="text" id="csearch_label_result" class="f11b" style="width: 150px" value="<?php echo  isset($content["search"]["label_result"]) ? html_specialchars($content["search"]["label_result"]) : '' ?>" size="10" maxlength="250" /></td>
        <td class="chatlist">&nbsp;&nbsp;&nbsp;<?php echo $BL['be_cnt_css_class'] ?>:&nbsp;</td>
        <td><input name="csearch_style_result" type="text" id="csearch_style_result" class="f11b" style="width: 200px" value="<?php echo  isset($content["search"]["style_result"]) ? $content["search"]["style_result"] : '' ?>" size="20" /></td>
      </tr>
    </table>
    </td>
  </tr>
  
 <tr><td colspan="2" class="rowspacer7x7"></td></tr>
  
  <tr>
    <td align="right" class="chatlist" valign="top"><?php echo $BL['be_cnt_page_of_pages'] ?>:&nbsp;</td>
    <td valign="top"><table border="0" cellpadding="0" cellspacing="0" summary="">
	
	  <tr>
	  <td class="chatlist"><?php echo $BL['be_cnt_page_of_pages_descr'] ?></td>
	  </tr>
	</table>
	
	<table border="0" cellpadding="1" cellspacing="0" style="margin-top:3px;margin-bottom:1px;" summary="">
		<tr bgcolor="#E7E8EB">
	  <?php
	  
	  if(!isset($content["search"]["show_always"]))		$content["search"]["show_always"] 	= 1;
	  if(!isset($content["search"]["show_top"]))		$content["search"]["show_top"] 		= 1;
	  if(!isset($content["search"]["show_bottom"]))		$content["search"]["show_bottom"] 	= 1;
	  if(!isset($content["search"]["show_next"]))		$content["search"]["show_next"] 	= 1;
	  if(!isset($content["search"]["show_prev"]))		$content["search"]["show_prev"] 	= 1;	  
	  
	  ?>
        <td><input name="csearch_show_always" id="csearch_show_always" type="checkbox" value="1" <?php is_checked(1, $content["search"]["show_always"]) ?> /></td>
        <td class="v10"><label for="csearch_show_always"><?php echo $BL['be_cnt_search_show_forall'] ?></label>&nbsp;</td>
		
		<td><input name="csearch_show_top" id="csearch_show_top" type="checkbox" value="1" <?php is_checked(1, $content["search"]["show_top"]) ?> /></td>
        <td class="v10"><label for="csearch_show_top"><?php echo $BL['be_cnt_search_show_top'] ?></label>&nbsp;</td>
		
		<td><input name="csearch_show_bottom" id="csearch_show_bottom" type="checkbox" value="1" <?php is_checked(1, $content["search"]["show_bottom"]) ?> /></td>
        <td class="v10"><label for="csearch_show_bottom"><?php echo $BL['be_cnt_search_show_bottom'] ?></label>&nbsp;</td>
	</tr>
	</table>
	<table border="0" cellpadding="1" cellspacing="0" summary="">
		<tr bgcolor="#E7E8EB">	
		<td><input name="csearch_show_prev" id="csearch_show_prev" type="checkbox" value="1" <?php is_checked(1, $content["search"]["show_prev"]) ?> /></td>
        <td class="v10"><label for="csearch_show_prev"><?php echo $BL['be_cnt_search_show_prev'] ?></label>&nbsp;</td>
		
		<td><input name="csearch_show_next" id="csearch_show_next" type="checkbox" value="1" <?php is_checked(1, $content["search"]["show_next"]) ?> /></td>
        <td class="v10"><label for="csearch_show_next"><?php echo $BL['be_cnt_search_show_next'] ?></label>&nbsp;</td>
		
      </tr>
    </table></td>
  </tr> 
    
  <tr>
    <td align="right" class="chatlist" valign="top" >&nbsp;</td>
    <td><textarea name="csearch_label_pages" rows="4" class="f11" id="csearch_label_pages" style="width: 440px"><?php echo  isset($content["search"]["label_pages"]) ? html_specialchars($content["search"]["label_pages"]) : '' ?></textarea></td>
  </tr>
  <tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="8" /></td>
</tr>
  <tr>
    <td align="right" class="chatlist"><?php echo $BL['be_cnt_align'] ?>:&nbsp;</td>
    <td valign="top"><table border="0" cellpadding="0" cellspacing="0" summary="">
      <tr>
	  <?php
	  
	  if(!isset($content["search"]["align"])) {
	  	$content["search"]["align"] = 0;
	  }
	  
	  ?>
        <td><input name="csearch_align" id="csearch_align0" type="radio" value="0" <?php is_checked(0, $content["search"]["align"]) ?> /></td>
        <td class="v10">&nbsp;<label for="csearch_align0"><?php echo $BL['be_cnt_mediapos0'] ?></label>&nbsp;&nbsp;&nbsp;</td>
        <td><input name="csearch_align" id="csearch_align1" type="radio" value="1" <?php is_checked(1, $content["search"]["align"]) ?> /></td>
        <td class="v10">&nbsp;<label for="csearch_align1"><?php echo $BL['be_cnt_right'] ?></label>&nbsp;&nbsp;&nbsp;</td>
        <td><input name="csearch_align" id="csearch_align2" type="radio" value="2" <?php is_checked(2, $content["search"]["align"]) ?> /></td>
        <td class="v10">&nbsp;<label for="csearch_align2"><?php echo $BL['be_cnt_center'] ?></label></td>
      </tr>
    </table></td>
  </tr>
  <tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="8" /></td>
</tr>
  <tr>
    <td align="right" class="chatlist">&nbsp;&nbsp;</td>
    <td valign="top" class="chatlist"><?php echo $BL['be_cnt_searchformtext'] ?></td>
  </tr>
  <tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="4" /></td>
</tr>
	<tr>
		<td>&nbsp;</td>
		<td><table border="0" cellpadding="1" cellspacing="0" summary="">
			<tr bgcolor="#E7E8EB">
				<td><input type="radio" name="csearch_text_html" id="csearch_text_html0" value="0"<?php echo is_checked('0', $content['search']["text_html"], 0, 0) ?> title="redirect on success" /></td>
				<td class="v10"><label for="csearch_text_html0">Text&nbsp;</label>&nbsp;</td>
				<td><input type="radio" name="csearch_text_html" id="csearch_text_html1" value="1"<?php echo is_checked('1', $content['search']["text_html"], 0, 0) ?> title="redirect on success" /></td>
				<td class="v10"><label for="csearch_text_html1">HTML&nbsp;</label>&nbsp;</td>
			</tr>
		</table>
		</td>
	</tr>  
  <tr>
    <td align="right" class="chatlist tdtop3"><?php echo $BL['be_cnt_intro'] ?>:&nbsp;</td>
    <td valign="top"><textarea name="csearch_text_intro" rows="6" class="f10" id="csearch_text_intro" style="width: 440px"><?php echo  isset($content["search"]["text_intro"]) ? $content["search"]["text_intro"] : '' ?></textarea></td>
  </tr>
  <tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="3" /></td>
</tr>
  <tr>
    <td align="right" class="chatlist tdtop3"><?php echo $BL['be_cnt_result'] ?>:&nbsp;</td>
    <td valign="top"><textarea name="csearch_text_result" rows="6" class="f10"  id="csearch_text_result" style="width: 440px"><?php echo  isset($content["search"]["text_result"]) ? $content["search"]["text_result"] : '' ?></textarea></td>
  </tr>
  <tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="3" /></td>
</tr>
  <tr>
    <td align="right" class="chatlist tdtop3" nowrap="nowrap"><?php echo $BL['be_cnt_noresult'] ?>:&nbsp;</td>
    <td valign="top"><textarea name="csearch_text_noresult" rows="6" class="f10"  id="csearch_text_noresult" style="width: 440px"><?php echo  isset($content["search"]["text_noresult"]) ? $content["search"]["text_noresult"] : '' ?></textarea></td>
  </tr>
  
 <tr><td colspan="2" class="rowspacer7x0"></td></tr>
