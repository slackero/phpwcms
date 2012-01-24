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

// News

$content['news_default'] = array(
								'news_lang'				=> array(),
								'news_category'			=> array(),
								'news_sort'				=> 5,
								'news_paginate'			=> 0,
								'news_paginate_count'	=> 10,
								'news_limit'			=> '',
								'news_archive'			=> 1,
								'news_andor'			=> 'OR',
								'news_paginate_basis'	=> 3,
								'news_archive_link'		=> '',
								'news_prio'				=> 0,
								'news_skip'				=> '',
								'news_detail_link'		=> ''
								);

// set default values or merge with defaults
$content['news'] = $content['id'] > 0 && is_array($content['news']) ? array_merge($content['news_default'], $content['news']) : $content['news_default'];

// necessary JavaScript libraries
initMootools();
initMootoolsAutocompleter();


?>
<tr><td colspan="2"><img src="img/lines/l538_70.gif" alt="" width="538" height="1" /></td></tr>
<tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="6" /></td></tr>

<tr>
	<td align="right" class="chatlist"><?php echo $BL['be_admin_struct_template'] ?>:&nbsp;</td>
	<td><select name="template" id="template">
<?php
	
	echo '<option value="">'.$BL['be_admin_tmpl_default'].'</option>'.LF;

	$tmpllist = get_tmpl_files(PHPWCMS_TEMPLATE.'inc_cntpart/news');
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

<tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="6" /></td></tr>
<tr><td colspan="2"><img src="img/lines/l538_70.gif" alt="" width="538" height="1" /></td></tr>
<tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="8" /></td></tr>

<tr>
	<td align="right" class="chatlist"><?php echo $BL['be_cnt_sorting'] ?>:&nbsp;</td>
	<td><table cellpadding="0" cellspacing="0" border="0" summary="">
		<tr>
			<td><select name="news_sort" id="calink_type">

		<option value="9"<?php is_selected(9, $content['news']['news_sort']) ?>><?php echo $BL['be_sort_date'].', '.$BL['be_admin_struct_orderdesc'] ?></option>
		<option value="10"<?php is_selected(10, $content['news']['news_sort']) ?>><?php echo $BL['be_sort_date'].', '.$BL['be_admin_struct_orderasc'] ?></option>
		<option value="1"<?php is_selected(1, $content['news']['news_sort']) ?>><?php echo $BL['be_admin_struct_orderdate'].', '.$BL['be_admin_struct_orderdesc'] ?></option>
		<option value="2"<?php is_selected(2, $content['news']['news_sort']) ?>><?php echo $BL['be_admin_struct_orderdate'].', '.$BL['be_admin_struct_orderasc'] ?></option>
		<option value="3"<?php is_selected(3, $content['news']['news_sort']) ?>><?php echo $BL['be_admin_struct_orderchangedate'].', '.$BL['be_admin_struct_orderdesc'] ?></option>
		<option value="4"<?php is_selected(4, $content['news']['news_sort']) ?>><?php echo $BL['be_admin_struct_orderchangedate'].', '.$BL['be_admin_struct_orderasc'] ?></option>
		<option value="5"<?php is_selected(5, $content['news']['news_sort']) ?>><?php echo $BL['be_article_cnt_start'].', '.$BL['be_admin_struct_orderdesc'] ?></option>
		<option value="6"<?php is_selected(6, $content['news']['news_sort']) ?>><?php echo $BL['be_article_cnt_start'].', '.$BL['be_admin_struct_orderasc'] ?></option>
		<option value="7"<?php is_selected(7, $content['news']['news_sort']) ?>><?php echo $BL['be_article_cnt_end'].', '.$BL['be_admin_struct_orderdesc'] ?></option>
		<option value="8"<?php is_selected(8, $content['news']['news_sort']) ?>><?php echo $BL['be_article_cnt_end'].', '.$BL['be_admin_struct_orderasc'] ?></option>

	</select></td>
			<td>&nbsp;&nbsp;</td>		
			<td bgcolor="#e7e8eb"><input type="checkbox" name="news_prio" id="news_prio" value="1"<?php is_checked(1, $content['news']['news_prio']) ?> /></td>
			<td bgcolor="#e7e8eb"><label for="news_prio">&nbsp;<?php echo $BL['be_use_prio'] ?>&nbsp;&nbsp;</label></td>
		</tr>
		</table></td>
</tr>

<tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="5" /></td></tr>

<tr>
	<td align="right" class="chatlist"><?php echo $BL['be_tags'] ?>:&nbsp;</td>
	<td><table cellpadding="0" cellspacing="0" border="0" summary="">
		<tr>
			<td><input type="text" name="news_category" id="news_category" value="<?php echo html_specialchars(implode(', ', $content['news']['news_category'])) ?>" class="width350 bold" /></td>
			<td>&nbsp;</td>
			<td><select name="news_andor" id="news_andor">
				
				<option value="OR"<?php is_selected('OR', $content['news']['news_andor']) ?>><?php echo $BL['be_fsearch_or'] ?></option>
				<option value="AND"<?php is_selected('AND', $content['news']['news_andor']) ?>><?php echo $BL['be_fsearch_and'] ?></option>
				<option value="NOT"<?php is_selected('NOT', $content['news']['news_andor']) ?>><?php echo $BL['be_fsearch_not'] ?></option>
			
			</select></td>
		</tr>
		</table></td>		
</tr>

<tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="5" /></td></tr>

<tr>
	<td align="right" class="chatlist"><?php echo $BL['be_profile_label_lang'] ?>:&nbsp;</td>
	<td><input type="text" name="news_lang" id="news_lang" value="<?php echo html_specialchars(implode(', ', $content['news']['news_lang'])) ?>" class="width200" /></td>
</tr>

<tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="8" /></td></tr>
<tr><td colspan="2"><img src="img/lines/l538_70.gif" alt="" width="538" height="1" /></td></tr>
<tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="8" /></td></tr>

<tr>
	<td align="right" class="chatlist"><?php echo $BL['be_show_content'] ?>:&nbsp;</td>
	<td><select name="news_archive" id="news_archive">
			
			<option value="0"<?php is_selected(0, $content['news']['news_archive']) ?>><?php echo $BL['be_archived_items'].': '.$BL['be_include'] ?></option>
			<option value="1"<?php is_selected(1, $content['news']['news_archive']) ?>><?php echo $BL['be_archived_items'].': '.$BL['be_exclude'] ?></option>
			<option value="2"<?php is_selected(2, $content['news']['news_archive']) ?>><?php echo $BL['be_archived_items'].': '.$BL['be_solely'] ?></option>
			<option value="3"<?php is_selected(3, $content['news']['news_archive']) ?>><?php echo $BL['be_cnt_guestbook_listing_all'].' &gt; ' . $BL['be_article_cnt_start'] ?></option>
				
		</select></td>
</tr>

<tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="6" /></td></tr>

<tr>
	<td align="right" class="chatlist"><?php echo $BL['be_cnt_rssfeed_item'] ?>:&nbsp;</td>
	<td><table cellpadding="0" cellspacing="0" border="0" summary="">
		<tr>
			<td><input type="text" name="news_limit" id="news_limit" value="<?php echo html_specialchars($content['news']['news_limit']) ?>" class="width50" /></td>
			<td>&nbsp;<?php echo $BL['be_cnt_rssfeed_max'] ?>&nbsp;&nbsp;&nbsp;&nbsp;</td>
			<td><input type="text" name="news_skip" id="news_skip" value="<?php echo html_specialchars($content['news']['news_skip']) ?>" class="width50" />
			<td>&nbsp;<?php echo $BL['be_skip_first_items'] ?></td>
		</tr>
		</table></td>
</tr>


<tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="6" /></td></tr>

<tr>
	<td>&nbsp;</td>
	<td><table cellpadding="0" cellspacing="0" border="0" summary="">
		<tr>
			<td bgcolor="#e7e8eb"><input type="checkbox" name="news_paginate" id="news_paginate" value="1"<?php is_checked(1, $content['news']['news_paginate']) ?> /></td>
			<td bgcolor="#e7e8eb"><label for="news_paginate">&nbsp;<?php echo $BL['be_pagination'] ?>&nbsp;&nbsp;</label></td>
			<td>&nbsp;&nbsp;</td>
			<td><select name="news_paginate_basis" id="news_paginate_basis" onchange="setPaginateBasis();">
			
				<option value="0"<?php is_selected(0, $content['news']['news_paginate_basis']) ?>><?php echo $BL['be_pagniate_count'] ?></option>
				<option value="1"<?php is_selected(1, $content['news']['news_paginate_basis']) ?>><?php echo $BL['be_date_day'] ?></option>
				<option value="2"<?php is_selected(2, $content['news']['news_paginate_basis']) ?>><?php echo $BL['be_date_week'] ?></option>
				<option value="3"<?php is_selected(3, $content['news']['news_paginate_basis']) ?>><?php echo $BL['be_date_month'] ?></option>
				<option value="4"<?php is_selected(4, $content['news']['news_paginate_basis']) ?>><?php echo $BL['be_date_year'] ?></option>
			
			</select></td>
			<td>&nbsp;&nbsp;</td>
			<td><input type="text" name="news_paginate_count" id="news_paginate_count" value="<?php echo html_specialchars($content['news']['news_paginate_count']) ?>" class="width25" /></td>
		</tr>
		</table></td>
</tr>

<tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="8" /></td></tr>
<tr><td colspan="2"><img src="img/lines/l538_70.gif" alt="" width="538" height="1" /></td></tr>
<tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="8" /></td></tr>

<tr>
	<td align="right" class="chatlist"><?php echo $BL['be_archive'] ?>:&nbsp;</td>
	<td><table cellpadding="0" cellspacing="0" border="0" summary="">
		<tr>
			<td><input type="text" name="news_archive_link" id="news_archive_link" value="<?php echo html_specialchars($content['news']['news_archive_link']) ?>" class="width250" /></td>
			<td>&nbsp;<?php echo $BL['be_article_urlalias'].'/'.$BL['be_func_struct_articleID'] ?></td>
		</tr>
		</table>
	</td>
</tr>

<tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="6" /></td></tr>

<tr>
	<td align="right" class="chatlist"><?php echo $BL['be_news_detail_link'] ?>:&nbsp;</td>
	<td><table cellpadding="0" cellspacing="0" border="0" summary="">
		<tr>
			<td><input type="text" name="news_detail_link" id="news_detail_link" value="<?php echo html_specialchars($content['news']['news_detail_link']) ?>" class="width250" /></td>
			<td>&nbsp;<?php echo $BL['be_article_urlalias'].'/'.$BL['be_func_struct_articleID'] ?></td>
		</tr>
		</table>
	</td>
</tr>


<tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="8" /><script type="text/javascript">
<!--

window.addEvent('domready', function(){
									 
	/* Autocompleter for categories/tags */
	var searchCategory = $('news_category');
	var indicator2 = new Element('span', {'class': 'autocompleter-loading', 'styles': {'display': 'none'}}).setHTML('').injectAfter(searchCategory);
	var completer2 = new Autocompleter.Ajax.Json(searchCategory, 'include/inc_act/ajax_connector.php', {
		multi: true,
		maxChoices: 30,
		autotrim: true,
		minLength: 0,
		allowDupes: false,
		postData: {action: 'newstags', method: 'json'},
		onRequest: function(el) {
			indicator2.setStyle('display', '');
		},
		onComplete: function(el) {
			indicator2.setStyle('display', 'none');
		}
	});
	
	var selectLang = $('news_lang');
	var indicator1 = new Element('span', {'class': 'autocompleter-loading', 'styles': {'display': 'none'}}).setHTML('').injectAfter(selectLang);
	var completer1 = new Autocompleter.Ajax.Json(selectLang, 'include/inc_act/ajax_connector.php', {
		multi: true,
		allowDupes: false,
		autotrim: true,
		minLength: 0,
		maxChoices: 20,
		postData: {action: 'lang', method: 'json'},
		onRequest: function(el) {
			indicator1.setStyle('display', '');
		},
		onComplete: function(el) {
			indicator1.setStyle('display', 'none');
		}
	});
	
	selectLang.addEvent('keyup', function(){
		this.value = this.value.replace(/[^a-z\-\, ]/g, '');
	});
	
	setPaginateBasis();

});

function setPaginateBasis() {
	if($('news_paginate_basis').selectedIndex == 0) {
		$('news_paginate_count').setStyle('visibility', 'visible');
	} else {
		$('news_paginate_count').setStyle('visibility', 'hidden');
	}
}

//-->
</script></td></tr>
<tr><td colspan="2"><img src="img/lines/l538_70.gif" alt="" width="538" height="1" /></td></tr>

