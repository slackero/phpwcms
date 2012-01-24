<?php
/*************************************************************************************
   Copyright notice
   
   (c) 2002-2012 Oliver Georgi <oliver@phpwcms.de> // All rights reserved.
 
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

//link article

if(!isset($content['alink']['alink_id']) && isset($row["acontent_alink"])) {
	$content['alink']['alink_id']	= explode(':', $row["acontent_alink"]);
} elseif(!isset($row["acontent_alink"])) {
	$content['alink']['alink_id']	= array();
}
if(empty($content['alink']['alink_template'])) {
	$content['alink']['alink_template'] = '';
}
if(empty($content['alink']['alink_type'])) {
	$content['alink']['alink_type'] = 0;
}
if(empty($content['alink']['alink_level']) || !is_array($content['alink']['alink_level'])) {
	$content['alink']['alink_level'] = array();
}
if(empty($content['alink']['alink_unique'])) {
	$content['alink']['alink_unique'] = 0;
}
if(!isset($content['alink']['alink_allowedtags'])) {
	$content['alink']['alink_allowedtags']	= '<b><i><u><s><strong>';
}
if(empty($content['alink']['alink_crop'])) {
	$content['alink']['alink_crop'] = 0;
}
if(empty($content['alink']['alink_prio'])) {
	$content['alink']['alink_prio'] = 0;
}
if(empty($content['alink']['alink_category']) || !is_array($content['alink']['alink_category'])) {
	$content['alink']['alink_category'] = array();
}
if(empty($content['alink']['alink_category'])) {
	$content['alink']['alink_andor'] = 'OR';
}
if(empty($content['alink']['alink_columns'])) {
	$content['alink']['alink_columns'] = '';
}
if(empty($content['alink']['alink_categoryalias'])) {
	$content['alink']['alink_categoryalias'] = 0;
}

$BE['HEADER']['contentpart.js'] = getJavaScriptSourceLink('include/inc_js/contentpart.js');

// necessary JavaScript libraries
initMootools();
initMootoolsAutocompleter();

?>
<tr><td colspan="2"><img src="img/lines/l538_70.gif" alt="" width="538" height="1" /></td></tr>
<tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="6" /></td></tr>

<tr>
	<td align="right" class="chatlist"><?php echo $BL['be_admin_struct_template'] ?>:&nbsp;</td>
	<td><select name="calink_template" id="calink_template" class="f11b">
<?php

	echo '<option value="">'.$BL['be_admin_tmpl_default'].' &lt;ul&gt;&lt;li&gt;</option>'.LF;

// templates for forum
$tmpllist = get_tmpl_files(PHPWCMS_TEMPLATE.'inc_cntpart/teaser');
if(is_array($tmpllist) && count($tmpllist)) {
	foreach($tmpllist as $val) {
		$vals = '';
		if($val == $content['alink']['alink_template']) $vals= ' selected="selected"';
		$val = html_specialchars($val);
		echo '<option value="'.$val.'"'.$vals.'>'.$val."</option>\n";
	}
}
				  
?>				  
	</select></td>
</tr>

<tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="6" /></td></tr>

<tr>
	<td align="right" class="chatlist"><?php echo $BL['be_article_rendering'] ?>:&nbsp;</td>
	<td><table border="0" cellpadding="0" cellspacing="0" summary="">
	<tr>
		<td bgcolor="#E7E8EB"><input type="checkbox" name="calink_unique" id="calink_unique" value="1"<?php is_checked(1, $content['alink']['alink_unique']) ?> /></td>
		<td bgcolor="#E7E8EB" class="chatlist"><label for="calink_unique">&nbsp;<?php echo $BL['be_unique_teaser_entry'] ?>&nbsp;&nbsp;</label></td>
		
		<td>&nbsp;&nbsp;&nbsp;</td>
		<td class="chatlist"><?php echo $BL['be_cnt_column'] ?>:&nbsp;</td>
		<td><input name="calink_columns" type="text" id="calink_columns" class="f11b" style="width: 35px" value="<?php echo $content['alink']['alink_columns']; ?>" size="3" maxlength="3" /></td>
		
	</tr>
	</table>
	</td>
</tr>

<tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="6" /></td></tr>

<tr>
	<td align="right" class="chatlist"><?php echo $BL['be_article_morelink'] ?>:&nbsp;</td>
	<td><table border="0" cellpadding="0" cellspacing="0" summary="">
	<tr>
		<td bgcolor="#E7E8EB"><input type="checkbox" name="calink_categoryalias" id="calink_categoryalias" value="1"<?php is_checked(1, $content['alink']['alink_categoryalias']) ?> /></td>
		<td bgcolor="#E7E8EB" class="chatlist"><label for="calink_categoryalias">&nbsp;<?php echo $BL['be_check_against_category_alias'] ?>&nbsp;&nbsp;</label></td>	
	</tr>
	</table>
	</td>
</tr>

<tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="6" /></td></tr>

<tr>
	<td align="right" class="chatlist"><?php echo $BL['be_article_asummary'] ?>:&nbsp;</td>
	<td><table border="0" cellpadding="0" cellspacing="0" summary="">
	<tr>
		<td><input name="calink_wordlimit" type="text" id="calink_wordlimit" class="f11b" style="width: 35px" value="<?php 
			echo empty($content['alink']['alink_wordlimit']) ? '' : $content['alink']['alink_wordlimit']; 
			?>" size="3" maxlength="5" /></td>
		<td class="chatlist">&nbsp;<?php echo $BL['be_cnt_results_wordlimit'] ?></td>
	</tr>
	</table>
	</td>
</tr>

<tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="5" /></td></tr>

<tr>
	<td align="right" class="chatlist"><?php echo $BL['be_allowed_tags'] ?>:&nbsp;</td>
	<td><input name="calink_allowedtags" type="text" id="calink_allowedtags" class="f11b" style="width: 420px" value="<?php echo html_specialchars($content['alink']['alink_allowedtags']); ?>" size="20" /></td>
</tr>

<tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="6" /></td></tr>

<tr>
	<td align="right" class="chatlist"><?php echo $BL['be_cnt_image'] ?>:&nbsp;</td>
	<td valign="top"><table border="0" cellpadding="0" cellspacing="0" summary="">
		<tr>
			<td align="right" class="chatlist"><?php echo $BL['be_cnt_maxw'] ?>:&nbsp;</td>
			<td><input name="calink_width" type="text" class="f11b" id="calink_width" style="width: 35px;" size="3" maxlength="3" onkeyup="if(!parseInt(this.value)) this.value='';" value="<?php echo empty($content['alink']['alink_width']) ? '' : $content['alink']['alink_width']; ?>" /></td>
			<td class="chatlist">&nbsp;&nbsp;<?php echo $BL['be_cnt_maxh'] ?>:&nbsp; </td>
			<td><input name="calink_height" type="text" class="f11b" id="calink_height" style="width: 35px;" size="3" maxlength="3" onkeyup="if(!parseInt(this.value)) this.value='';" value="<?php echo empty($content['alink']['alink_height']) ? '' : $content['alink']['alink_height']; ?>" /></td>
			<td class="chatlist">&nbsp;px&nbsp;&nbsp;&nbsp;</td>
			
			<td><input type="checkbox" name="calink_crop" id="calink_crop" value="1" <?php is_checked(1, $content['alink']['alink_crop']); ?> /></td>
			<td class="v10 chatlist"><label for="calink_crop" class="checkbox"><?php echo $BL['be_image_crop'] ?></label></td>
			
		</tr>
		</table></td>
</tr>

<tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="8" /></td></tr>
<tr><td colspan="2"><img src="img/lines/l538_70.gif" alt="" width="538" height="1" /></td></tr>
<tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="8" /></td></tr>

<tr>
	<td align="right" class="chatlist"><?php echo $BL['be_cnt_ecardform_selector'] ?>:&nbsp;</td>
	<td valign="top"><table cellpadding="0" cellspacing="0" border="0" summary="">
		<tr>
			<td><select name="calink_type" class="f11b" id="calink_type" onchange="showHide_TeaserArticleSelection(this.options[this.selectedIndex].value)">
		
		<optgroup label="<?php echo $BL['be_sorted']; ?>">
		
		<option value="0"<?php is_selected(0, $content['alink']['alink_type']) ?>><?php echo $BL['be_admin_struct_ordermanual'] ?></option>
		<option value="1"<?php is_selected(1, $content['alink']['alink_type']) ?>><?php echo $BL['be_admin_struct_orderdate'].', '.$BL['be_admin_struct_orderdesc'] ?></option>
		<option value="2"<?php is_selected(2, $content['alink']['alink_type']) ?>><?php echo $BL['be_admin_struct_orderdate'].', '.$BL['be_admin_struct_orderasc'] ?></option>
		<option value="3"<?php is_selected(3, $content['alink']['alink_type']) ?>><?php echo $BL['be_admin_struct_orderchangedate'].', '.$BL['be_admin_struct_orderdesc'] ?></option>
		<option value="4"<?php is_selected(4, $content['alink']['alink_type']) ?>><?php echo $BL['be_admin_struct_orderchangedate'].', '.$BL['be_admin_struct_orderasc'] ?></option>
		<option value="5"<?php is_selected(5, $content['alink']['alink_type']) ?>><?php echo $BL['be_article_cnt_start'].', '.$BL['be_admin_struct_orderdesc'] ?></option>
		<option value="6"<?php is_selected(6, $content['alink']['alink_type']) ?>><?php echo $BL['be_article_cnt_start'].', '.$BL['be_admin_struct_orderasc'] ?></option>
		<option value="7"<?php is_selected(7, $content['alink']['alink_type']) ?>><?php echo $BL['be_article_cnt_end'].', '.$BL['be_admin_struct_orderdesc'] ?></option>
		<option value="8"<?php is_selected(8, $content['alink']['alink_type']) ?>><?php echo $BL['be_article_cnt_end'].', '.$BL['be_admin_struct_orderasc'] ?></option>
		
		<option value="18"<?php is_selected(18, $content['alink']['alink_type']) ?>><?php echo $BL['be_article_atitle'].', '.$BL['be_admin_struct_orderdesc'] ?></option>
		<option value="19"<?php is_selected(19, $content['alink']['alink_type']) ?>><?php echo $BL['be_article_atitle'].', '.$BL['be_admin_struct_orderasc'] ?></option>
		
		</optgroup>
		
		<optgroup label="<?php echo $BL['be_random']; ?>">
		
		<option value="9"<?php is_selected(9, $content['alink']['alink_type']) ?>><?php echo $BL['be_random'] ?></option>
		
		</optgroup>

		<optgroup label="<?php echo $BL['be_random'].', '.$BL['be_sorted']; ?>">
		
		<option value="10"<?php is_selected(10, $content['alink']['alink_type']) ?>><?php echo $BL['be_random'].', '.$BL['be_admin_struct_orderdate'].', '.$BL['be_admin_struct_orderdesc'] ?></option>
		<option value="11"<?php is_selected(11, $content['alink']['alink_type']) ?>><?php echo $BL['be_random'].', '.$BL['be_admin_struct_orderdate'].', '.$BL['be_admin_struct_orderasc'] ?></option>
		<option value="12"<?php is_selected(12, $content['alink']['alink_type']) ?>><?php echo $BL['be_random'].', '.$BL['be_admin_struct_orderchangedate'].', '.$BL['be_admin_struct_orderdesc'] ?></option>
		<option value="13"<?php is_selected(13, $content['alink']['alink_type']) ?>><?php echo $BL['be_random'].', '.$BL['be_admin_struct_orderchangedate'].', '.$BL['be_admin_struct_orderasc'] ?></option>
		<option value="14"<?php is_selected(14, $content['alink']['alink_type']) ?>><?php echo $BL['be_random'].', '.$BL['be_article_cnt_start'].', '.$BL['be_admin_struct_orderdesc'] ?></option>
		<option value="15"<?php is_selected(15, $content['alink']['alink_type']) ?>><?php echo $BL['be_random'].', '.$BL['be_article_cnt_start'].', '.$BL['be_admin_struct_orderasc'] ?></option>
		<option value="16"<?php is_selected(16, $content['alink']['alink_type']) ?>><?php echo $BL['be_random'].', '.$BL['be_article_cnt_end'].', '.$BL['be_admin_struct_orderdesc'] ?></option>
		<option value="17"<?php is_selected(17, $content['alink']['alink_type']) ?>><?php echo $BL['be_random'].', '.$BL['be_article_cnt_end'].', '.$BL['be_admin_struct_orderasc'] ?></option>		

		<option value="20"<?php is_selected(20, $content['alink']['alink_type']) ?>><?php echo $BL['be_random'].', '.$BL['be_article_atitle'].', '.$BL['be_admin_struct_orderdesc'] ?></option>
		<option value="21"<?php is_selected(21, $content['alink']['alink_type']) ?>><?php echo $BL['be_random'].', '.$BL['be_article_atitle'].', '.$BL['be_admin_struct_orderasc'] ?></option>

		
		</optgroup>
		
			</select></td>
			<td>&nbsp;&nbsp;</td>		
			<td bgcolor="#e7e8eb" id="prio0"><input type="checkbox" name="calink_prio" id="calink_prio" value="1"<?php is_checked(1, $content['alink']['alink_prio']) ?> /></td>
			<td bgcolor="#e7e8eb" id="prio1"><label for="calink_prio">&nbsp;<?php echo $BL['be_use_prio'] ?>&nbsp;&nbsp;</label></td>
		</tr>
		</table></td>
</tr>

<tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="8" /></td></tr>

<tr id="calink_manual_0"<?php if($content['alink']['alink_type']) echo ' style="display:none"'; ?>>
<td align="right" valign="top" class="chatlist tdtop3"><?php echo $BL['be_selection'] ?>:&nbsp;</td>
<td style="padding-bottom:3px;"><table border="0" cellpadding="0" cellspacing="0" summary="">

	<tr>
		<td rowspan="2"><select name="calink[]" size="8" multiple="multiple" class="f11 listrow" id="calink" style="width: 420px" ondblclick="moveSelectedOptions(teaser_items,source_items,true);">
<?php
	  	//Auslesen der kompletten Public Artikel
	  	$sql  = "SELECT article_id, article_title, acat_name, acat_alias, article_cid ";
		$sql .= "FROM ".DB_PREPEND."phpwcms_article ar ";
		$sql .= "LEFT JOIN ".DB_PREPEND."phpwcms_articlecat ac ON ar.article_cid = ac.acat_id ";
		$sql .= "WHERE ar.article_public = 1 AND ar.article_deleted = 0 ";
		$sql .= "GROUP BY ar.article_id, ar.article_title, ac.acat_name ";
		$sql .= "ORDER BY ar.article_title;";

		$carticle_list = '';
		$carticle_link = $content['alink']['alink_id'];
		if($result = mysql_query($sql, $db) or die("error while reading complete article/articlecategory list")) {
			while($row = mysql_fetch_row($result)) {
				$k  = 0;
				$k1 = $BL['be_cnt_sitelevel'].': '.html_specialchars($row[2]); //$BL['be_admin_page_articlename']
				if(empty($row[4])) {
					$row[2] = $indexpage['acat_name'];
					$row[3] = $indexpage['acat_alias'];
				}
				$alias_add  = ' ('.html_specialchars($row[2]);
				if(!empty($row[3])) {
					$alias_add .= '/'.html_specialchars($row[3]);
				}
				$alias_add .= ')';
				foreach($content['alink']['alink_id'] as $key => $value) {
					
					if($row[0] == $value) {
						$carticle_link[$key]  = '	<option value="'.$row[0].'" title="'.$k1;
						$carticle_link[$key] .= '">'.html_specialchars($row[1]).$alias_add.'</option>'.LF;
						unset($content['alink']['alink_id'][$key]);
						$k = 1;
					} 
				
				}
				
				if(!$k) {
					$carticle_list .= '	<option value="'.$row[0].'" title="'.$k1;
					$carticle_list .= '">'.html_specialchars($row[1]).$alias_add.'</option>'.LF;
				}
			}
	  	}
		
		echo implode(LF, $carticle_link);
		
	  ?>
		</select></td>
	
		<td rowspan="2">&nbsp;</td>
		<td valign="top">
		<a href="#" title="<?php echo $BL['be_cnt_sortup'] ?>" onclick="moveOptionUp(teaser_items);return false;"><img src="img/button/list_pos_up.gif" alt="" width="15" height="15" border="0" /></a>
		<br />
		<a href="#" title="<?php echo $BL['be_cnt_sortdown'] ?>" onclick="moveOptionDown(teaser_items);return false;"><img src="img/button/list_pos_down.gif" alt="" width="15" height="15" border="0" /></a></td>
	</tr>
	<tr>
	  <td valign="bottom"><a href="#" title="<?php echo $BL['be_cnt_removearticleto'] ?>" onclick="moveSelectedOptions(teaser_items,source_items,false);return false;"><img src="img/button/del_image_button.gif" alt="" width="15" height="15" border="0" /></a></td>
	 </tr>
	</table></td>
</tr>
<tr id="calink_manual_1"<?php if($content['alink']['alink_type']) echo ' style="display:none"'; ?>>  
    <td align="right" valign="top" class="chatlist" style="padding-top:3px;"><?php echo $BL['be_cnt_articles'] ?>:&nbsp;</td>
      <td><table border="0" cellpadding="0" cellspacing="0" summary="">

	<tr>
		<td><select name="calinklist" size="16" multiple="multiple" class="f11 listrow" id="calinklist" style="width:420px;" ondblclick="moveSelectedOptions(source_items,teaser_items,false);">
	  <?php echo $carticle_list; ?>
      			</select></td>
	  
	  <td>&nbsp;</td>
	  <td valign="top"><a href="#" title="<?php echo $BL['be_cnt_movearticleto'] ?>" onclick="moveSelectedOptions(source_items,teaser_items,false);return false"><img src="img/button/list_copy.gif" alt="" width="15" height="15" border="0" /></a></td>
	</tr>
	</table></td>
</tr>


<tr id="calink_auto_0"<?php if(!$content['alink']['alink_type']) echo ' style="display:none"'; ?>>
	<td align="right" valign="top" class="chatlist" style="padding-top:3px;"><?php echo $BL['be_cnt_rssfeed_max'] ?>:&nbsp;</td>
	<td><table border="0" cellpadding="0" cellspacing="0" summary="">
	
	<tr>
		<td><input name="calink_max" type="text" id="calink_max" class="f11b" style="width: 35px" value="<?php 
			echo empty($content['alink']['alink_max']) ? '' : $content['alink']['alink_max']; 
			?>" size="5" maxlength="5" /></td>
		<td class="chatlist">&nbsp;<?php echo $BL['be_cnt_articles'] ?></td>
	</tr>
	
	</table></td>
</tr>
<tr id="calink_auto_1"<?php if(!$content['alink']['alink_type']) echo ' style="display:none"'; ?>>
	<td align="right" valign="top" class="chatlist" style="padding-top:6px;"><?php echo $BL['be_cnt_sitelevel'] ?>:&nbsp;</td>
	<td style="padding-top:3px;"><select name="calink_level[]" size="15" multiple="multiple" class="f11 optionhover" id="calink_level" style="width: 440px">
<?php
							
				echo '<option value="0"';
				if(in_array(0, $content['alink']['alink_level'])) echo ' selected="selected"';
				echo '>'.html_specialchars($indexpage['acat_name']).'</option>'.LF;
				struct_select_list(0, 0, $content['alink']['alink_level']);
?>
	</select></td>
</tr>


<tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="6" /></td></tr>

<tr>
	<td align="right" class="chatlist"><?php echo $BL['be_tags'] ?>:&nbsp;</td>
	<td><table cellpadding="0" cellspacing="0" border="0" summary="">
		<tr>
			<td><input type="text" name="calink_category" id="calink_category" value="<?php echo html_specialchars(implode(', ', $content['alink']['alink_category'])) ?>" class="width350 bold" /></td>
			<td>&nbsp;&nbsp;</td>
			<td><select name="calink_andor" id="calink_andor">
				
				<option value="OR"<?php is_selected('OR', $content['alink']['alink_andor']) ?>><?php echo $BL['be_fsearch_or'] ?></option>
				<option value="AND"<?php is_selected('AND', $content['alink']['alink_andor']) ?>><?php echo $BL['be_fsearch_and'] ?></option>
				<option value="NOT"<?php is_selected('NOT', $content['alink']['alink_andor']) ?>><?php echo $BL['be_fsearch_not'] ?></option>
			
			</select></td>
		</tr>
		</table></td>		
</tr>

<tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="8" /><script type="text/javascript">
<!--

window.addEvent('domready', function(){
									 
	/* Autocompleter for categories/tags */
	var searchCategory = $('calink_category');
	var indicator2 = new Element('span', {'class': 'autocompleter-loading', 'styles': {'display': 'none'}}).setHTML('').injectAfter($('calink_andor'));
	var completer2 = new Autocompleter.Ajax.Json(searchCategory, 'include/inc_act/ajax_connector.php', {
		multi: true,
		maxChoices: 30,
		autotrim: true,
		minLength: 0,
		allowDupes: false,
		postData: {action: 'category', method: 'json'},
		onRequest: function(el) {
			indicator2.setStyle('display', '');
		},
		onComplete: function(el) {
			indicator2.setStyle('display', 'none');
		}
	});

});

var teaser_items = $('calink');
var source_items = $('calinklist');

//-->
</script></td></tr>
<tr><td colspan="2"><img src="img/lines/l538_70.gif" alt="" width="538" height="1" /></td></tr>

