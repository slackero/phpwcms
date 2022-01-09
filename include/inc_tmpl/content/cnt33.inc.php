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
	'news_paginate_basis'	=> 0,
	'news_archive_link'		=> '',
	'news_prio'				=> 0,
	'news_skip'				=> '',
	'news_detail_link'		=> ''
);

// set default values or merge with defaults
$content['news'] = $content['id'] > 0 && is_array($content['news']) ? array_merge($content['news_default'], $content['news']) : $content['news_default'];

// necessary JavaScript libraries
initJsAutocompleter();

?>
<tr><td colspan="2" class="rowspacer0x7"><img src="img/leer.gif" alt="" width="1" height="1"></td></tr>

<tr>
	<td align="right" class="chatlist"><?php echo $BL['be_admin_struct_template'] ?>:&nbsp;</td>
	<td><select name="template" id="template">
<?php

	echo '<option value="">'.$BL['be_admin_tmpl_default'].'</option>'.LF;

	$tmpllist = get_tmpl_files(PHPWCMS_TEMPLATE.'inc_cntpart/news');
	if(is_array($tmpllist) && count($tmpllist)) {
		foreach($tmpllist as $val) {
			$selected_val = (isset($content["template"]) && $val == $content["template"]) ? ' selected="selected"' : '';
			$val = html($val);
			echo '	<option value="' . $val . '"' . $selected_val . '>' . $val . '</option>' . LF;
		}
	}

?>
			</select></td>
</tr>

<tr><td colspan="2" class="rowspacer7x7"><img src="img/leer.gif" alt="" width="1" height="1"></td></tr>

<tr>
	<td align="right" class="chatlist"><?php echo $BL['be_cnt_sorting'] ?>:&nbsp;</td>
	<td><table cellpadding="0" cellspacing="0" border="0" summary="">
		<tr>
			<td>
                <select name="news_sort" id="calink_type">
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
                    <option value="17"<?php is_selected(17, $content['news']['news_sort']) ?>><?php echo $BL['be_article_cnt_ctitle'].', '.$BL['be_admin_struct_orderdesc'] ?></option>
                    <option value="18"<?php is_selected(18, $content['news']['news_sort']) ?>><?php echo $BL['be_article_cnt_ctitle'].', '.$BL['be_admin_struct_orderasc'] ?></option>
                    <option value="11"<?php is_selected(11, $content['news']['news_sort']) ?>><?php echo $BL['be_article_username'].'/'.$BL['be_sort_date'].', '.$BL['be_admin_struct_orderdesc'] ?></option>
                    <option value="12"<?php is_selected(12, $content['news']['news_sort']) ?>><?php echo $BL['be_article_username'].'/'.$BL['be_sort_date'].', '.$BL['be_admin_struct_orderasc'] ?></option>
                    <option value="13"<?php is_selected(13, $content['news']['news_sort']) ?>><?php echo $BL['be_title'].', '.$BL['be_admin_struct_orderdesc'] ?></option>
                    <option value="14"<?php is_selected(14, $content['news']['news_sort']) ?>><?php echo $BL['be_title'].', '.$BL['be_admin_struct_orderasc'] ?></option>
                    <option value="15"<?php is_selected(15, $content['news']['news_sort']) ?>><?php echo $BL['be_place'].'/'.$BL['be_sort_date'].', '.$BL['be_admin_struct_orderdesc'] ?></option>
                    <option value="16"<?php is_selected(16, $content['news']['news_sort']) ?>><?php echo $BL['be_place'].'/'.$BL['be_sort_date'].', '.$BL['be_admin_struct_orderasc'] ?></option>
                </select>
            </td>
			<td>&nbsp;&nbsp;</td>
			<td bgcolor="#e7e8eb"><input type="checkbox" name="news_prio" id="news_prio" value="1"<?php is_checked(1, $content['news']['news_prio']) ?> /></td>
			<td bgcolor="#e7e8eb"><label for="news_prio">&nbsp;<?php echo $BL['be_use_prio'] ?>&nbsp;&nbsp;</label></td>
		</tr>
		</table></td>
</tr>

<tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="5" /></td></tr>

<tr>
	<td align="right" class="chatlist"><?php echo $BL['be_tags'] ?>:&nbsp;</td>
	<td><table cellpadding="0" cellspacing="0" border="0" summary="" width="100%">
		<tr>
			<td width="95%"><input type="text" id="news_keyword_autosuggest" /><input type="hidden" name="news_category" id="news_category" value="<?php echo html(implode(', ', $content['news']['news_category'])) ?>" /></td>
			<td style="padding-right:4px"><select name="news_andor" id="news_andor">

				<option value="OR"<?php is_selected('OR', $content['news']['news_andor']) ?>><?php echo $BL['be_fsearch_or'] ?></option>
				<option value="AND"<?php is_selected('AND', $content['news']['news_andor']) ?>><?php echo $BL['be_fsearch_and'] ?></option>
				<option value="NOT"<?php is_selected('NOT', $content['news']['news_andor']) ?>><?php echo $BL['be_fsearch_not'] ?></option>

			</select></td>
		</tr>
		</table></td>
</tr>

<?php	if(count($phpwcms['allowed_lang']) > 1):	?>

<tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="5" /></td></tr>

<tr>
	<td align="right" class="chatlist"><?php echo $BL['be_profile_label_lang'] ?>:&nbsp;</td>
	<td class="v11">
			<label title="<?php echo $BL['be_admin_tmpl_default'] ?>">
				<input type="checkbox" name="news_lang[]" class="lang-default" id="langAll" value=""<?php
					if(empty($content['news']['news_lang']) || (isset($content['news']['news_lang'][0]) && $content['news']['news_lang'][0] == '')) {
						echo ' checked="checked"';
					}
				?> />
				<img src="img/famfamfam/lang/all.png" /><?php echo ' '.$BL['be_admin_tmpl_default'] ?>&nbsp;
			</label>

			<?php foreach($phpwcms['allowed_lang'] as $key => $lang):

				$lang = strtolower($lang);

			?>
			<label title="<?php echo get_language_name($lang) ?>">
				<input type="checkbox" name="news_lang[]" class="allowedLang" value="<?php echo $lang ?>"<?php if(in_array($lang, $content['news']['news_lang'])): ?> checked="checked"<?php endif; ?> class="lang-opt" />
				<img src="img/famfamfam/lang/<?php echo $lang ?>.png" />&nbsp;
			</label>

			<?php endforeach; ?>
	</td>
</tr>

<?php	endif; ?>

<tr><td colspan="2" class="rowspacer7x7"><img src="img/leer.gif" alt="" width="1" height="1"></td></tr>

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
			<td><input type="text" name="news_limit" id="news_limit" value="<?php echo html($content['news']['news_limit']) ?>" class="width50" /></td>
			<td>&nbsp;<?php echo $BL['be_cnt_rssfeed_max'] ?>&nbsp;&nbsp;&nbsp;&nbsp;</td>
			<td><input type="text" name="news_skip" id="news_skip" value="<?php echo html($content['news']['news_skip']) ?>" class="width50" />
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
				<option value="1"<?php is_selected(1, $content['news']['news_paginate_basis']) ?> disabled="disabled"><?php echo $BL['be_date_day'] ?></option>
				<option value="2"<?php is_selected(2, $content['news']['news_paginate_basis']) ?> disabled="disabled"><?php echo $BL['be_date_week'] ?></option>
				<option value="3"<?php is_selected(3, $content['news']['news_paginate_basis']) ?> disabled="disabled"><?php echo $BL['be_date_month'] ?></option>
				<option value="4"<?php is_selected(4, $content['news']['news_paginate_basis']) ?> disabled="disabled"><?php echo $BL['be_date_year'] ?></option>

			</select></td>
			<td>&nbsp;&nbsp;</td>
			<td><input type="text" name="news_paginate_count" id="news_paginate_count" value="<?php echo html($content['news']['news_paginate_count']) ?>" class="width25" /></td>
		</tr>
		</table></td>
</tr>

<tr><td colspan="2" class="rowspacer7x7"><img src="img/leer.gif" alt="" width="1" height="1"></td></tr>

<tr>
	<td align="right" class="chatlist"><?php echo $BL['be_archive'] ?>:&nbsp;</td>
	<td><table cellpadding="0" cellspacing="0" border="0" summary="">
		<tr>
			<td><input type="text" name="news_archive_link" id="news_archive_link" value="<?php echo html($content['news']['news_archive_link']) ?>" class="width250" /></td>
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
			<td><input type="text" name="news_detail_link" id="news_detail_link" value="<?php echo html($content['news']['news_detail_link']) ?>" class="width250" /></td>
			<td>&nbsp;<?php echo $BL['be_article_urlalias'].'/'.$BL['be_func_struct_articleID'] ?></td>
		</tr>
		</table>

<script type="text/javascript">

	function setPaginateBasis() {
		$('#news_paginate_count').css('visibility', $('#news_paginate_basis').prop('selectedIndex') ? 'hidden' : 'visible');
	}

	$(function(){
		$("#news_keyword_autosuggest").autoSuggest('<?php echo PHPWCMS_URL ?>include/inc_act/ajax_connector.php', {
			selectedItemProp: "cat_name",
			selectedValuesProp: 'cat_name',
			searchObjProps: "cat_name",
			queryParam: 'value',
			extraParams: '&method=json&action=newstags',
			startText: '',
			preFill: $("#news_category").val(),
			neverSubmit: true,
			asHtmlID: 'keyword-autosuggest'
		});

		$('#articlecontent').submit(function(event){
			$("#news_category").val($('#as-values-keyword-autosuggest').val());
		});

		setPaginateBasis();

		var allowedLang = $('input.allowedLang'),
            langAll = $('#langAll');

		langAll.change(function(){
			if($(this).is(':checked')) {
				allowedLang.attr('checked', false);
			}
		});

		allowedLang.change(function(){
			if($(this).is(':checked')) {
				langAll.attr('checked', false);
			}
		});

	});

</script>

	</td>
</tr>
