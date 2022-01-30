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

//search form

// necessary JavaScript libraries
initMootools();
initMootoolsAutocompleter();


if(empty($content['search']["text_html"])) {
	$content['search']["text_html"] = 0;
}

$content['search']["search_news"]	= empty($content['search']["search_news"]) ? 0 : 1;

if(!isset($content['search']["news_lang"])) {
	$content['search']["news_lang"] = array();
}
if(!isset($content['search']["news_category"])) {
	$content['search']["news_category"] = array();
}
if(!isset($content['search']["news_andor"])) {
	$content['search']["news_andor"] = 'OR';
}
if(empty($content['search']["news_url"])) {
	$content['search']["news_url"] = '';
}
if(empty($content["search"]["hide_summary"])) {
	$content["search"]["hide_summary"] = 0;
}
if(empty($content["search"]["highlight_result"])) {
	$content["search"]["highlight_result"] = 0;
}
if(empty($content["search"]["newwin"])) {
	$content["search"]["newwin"] = 0;
}
if(empty($content["search"]["no_filenames"])) {
	$content["search"]["no_filenames"] = 0;
}
if(empty($content["search"]["no_username"])) {
	$content["search"]["no_username"] = 0;
}
if(empty($content["search"]["no_caption"])) {
	$content["search"]["no_caption"] = 0;
}
if(empty($content["search"]["no_keyword"])) {
	$content["search"]["no_keyword"] = 0;
}
if(empty($content['search']['type'])) {
	$content['search']['type'] = 'OR';
}

?>
<tr><td colspan="2" class="rowspacer0x7"><img src="img/leer.gif" alt="" width="1" height="1" /></td></tr>

<tr>
	<td align="right" class="chatlist"><?php echo $BL['be_admin_struct_template']; ?>:&nbsp;</td>
	<td><select name="template" id="template">
<?php

	echo '<option value="">'.$BL['be_admin_tmpl_default'].'</option>'.LF;

// templates for search listing
$tmpllist = get_tmpl_files(PHPWCMS_TEMPLATE.'inc_cntpart/search');
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

<tr><td colspan="2" class="rowspacer7x7"><img src="img/leer.gif" alt="" width="1" height="1" /></td></tr>

  <tr>
    <td align="right" class="chatlist" valign="top"><?php echo $BL['be_cnt_results'] ?>:&nbsp;</td>
    <td valign="top"><table border="0" cellpadding="0" cellspacing="0" summary="">
      <tr>
        <td><input name="csearch_result_per_page" type="text" id="csearch_result_per_page" class="f11b" style="width: 30px" value="<?php echo isset($content["search"]["result_per_page"]) ? $content["search"]["result_per_page"] : '' ?>" size="3" maxlength="5" placeholder="25" /></td>
        <td class="v10">&nbsp;<?php echo $BL['be_cnt_results_per_page'] ?>&nbsp;&nbsp;&nbsp;&nbsp;</td>
        <td><input name="csearch_newwin" type="checkbox" id="csearch_newwin" value="1" <?php is_checked(1, $content["search"]["newwin"]) ?> /></td>
        <td class="v10"><label for="csearch_newwin"><?php echo $BL['be_cnt_opennewwin'] ?></label></td>
      </tr>
	  <tr><td colspan="4"><img src="img/leer.gif" alt="" width="1" height="2" /></td>
	  </tr>
	  <tr>
	  	<td><input name="csearch_wordlimit" type="text" id="csearch_wordlimit" class="f11b" style="width: 30px" value="<?php echo isset($content["search"]["wordlimit"]) ? $content["search"]["wordlimit"] : '' ?>" size="3" maxlength="5" placeholder="35" /></td>
      	<td class="v10">&nbsp;<?php echo $BL['be_cnt_results_wordlimit'] ?>&nbsp;&nbsp;&nbsp;&nbsp;</td>
	  	<td><input name="csearch_highlight" type="checkbox" id="csearch_highlight" value="1" <?php is_checked(1, $content["search"]["highlight_result"]) ?> /></td>
      	<td class="v10"><label for="csearch_highlight"><?php echo $BL['be_cnt_search_highlight'] ?></label></td>
	  </tr>
	  <tr><td colspan="4"><img src="img/leer.gif" alt="" width="1" height="2" /></td>
	  </tr>
	  <tr>
	  	<td><input name="csearch_minchar" type="text" id="csearch_minchar" class="f11b" style="width: 30px" value="<?php echo  isset($content["search"]["minchar"]) ? $content["search"]["minchar"] : '3' ?>" size="3" maxlength="5" placeholder="3" /></td>
      	<td class="v10">&nbsp;<?php echo $BL['be_cnt_results_minchar'] ?>&nbsp;&nbsp;&nbsp;&nbsp;</td>
		<td><input name="csearch_hidesummary" type="checkbox" id="csearch_hidesummary" value="1" <?php is_checked(1, $content["search"]["hide_summary"]) ?> /></td>
      	<td class="v10"><label for="csearch_hidesummary"><?php echo $BL['be_cnt_search_hidesummary'] ?></label></td>
	  </tr>
    </table></td>
  </tr>
  <tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="8" /></td></tr>

<tr>
	<td align="right" class="chatlist" valign="top"><?php echo $BL['be_cnt_search_startlevel'] ?>:&nbsp;</td>
	<td><select name="csearch_start_at[]" size="10" multiple="multiple" class="width440" id="csearch_start_at">
<?php
				if(!isset($content["search"]["start_at"]) || !is_array($content["search"]["start_at"])) {
					$content["search"]["start_at"] = array();
				}

				echo '<option value="0"';
				if(in_array(0, $content["search"]["start_at"])) {
					echo ' selected="selected"';
				}
				echo '>'.$BL['be_admin_struct_index'].'</option>'.LF;
				struct_select_list(0, 0, $content["search"]["start_at"]);
?>
	</select></td>
</tr>

<tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="4" /></td></tr>

<tr>
	<td align="right" class="chatlist"><?php echo $BL['be_cnt_search_default_type'] ?>:&nbsp;</td>
	<td>
		<select name="csearch_type">
			<option value="OR"<?php is_selected('OR', $content['search']['type']) ?>><?php echo $BL['be_fsearch_or'] ?></option>
			<option value="AND"<?php is_selected('AND', $content['search']['type']) ?>><?php echo $BL['be_fsearch_and'] ?></option>
		</select>
	</td>
</tr>

<tr><td colspan="2" class="rowspacer7x7"><img src="img/leer.gif" alt="" width="1" height="1" /></td></tr>

<tr>
	<td align="right" class="chatlist"><?php echo $BL['be_cnt_search_searchnot'] ?>:&nbsp;</td>
	<td><table border="0" cellpadding="1" cellspacing="0" summary="">
      <tr>
        <td><input name="csearch_nofilenames" type="checkbox" id="csearch_nofilenames" value="1" <?php is_checked(1, $content["search"]["no_filenames"]) ?> /></td>
      	<td class="v10"><label for="csearch_nofilenames"><?php echo $BL['be_fprivedit_filename'] ?></label>&nbsp;&nbsp;</td>

      	<td><input name="csearch_nousername" type="checkbox" id="csearch_nousername" value="1" <?php is_checked(1, $content["search"]["no_username"]) ?> /></td>
      	<td class="v10"><label for="csearch_nousername"><?php echo$BL['be_article_username'] ?></label>&nbsp;&nbsp;</td>

      	<td><input name="csearch_nocaption" type="checkbox" id="csearch_nocaption" value="1" <?php is_checked(1, $content["search"]["no_caption"]) ?> /></td>
      	<td class="v10"><label for="csearch_nocaption"><?php echo$BL['be_cnt_caption'] ?></label>&nbsp;&nbsp;</td>

      	<td><input name="csearch_nokeyword" type="checkbox" id="csearch_nokeyword" value="1" <?php is_checked(1, $content["search"]["no_keyword"]) ?> /></td>
      	<td class="v10"><label for="csearch_nokeyword"><?php echo$BL['be_article_akeywords'] ?></label></td>
	  </tr>
    </table></td>
</tr>

<tr><td colspan="2" class="rowspacer7x7"><img src="img/leer.gif" alt="" width="1" height="1" /></td></tr>

<tr>
	<td align="right" class="chatlist tdtop4"><?php echo $BL['be_module_search'] ?>:&nbsp;</td>
	<td valign="top">
		<table border="0" cellpadding="1" cellspacing="0" summary="">
			<tr>
				<td><input name="csearch_news" type="checkbox" id="csearch_news" value="1"<?php is_checked(1, $content['search']["search_news"]) ?> /></td>
				<td class="v10"><label for="csearch_news"><strong><?php echo $BL['be_news'] ?></strong></label>&nbsp;&nbsp;</td>
				<td align="right" class="chatlist"><?php echo $BL['be_profile_label_lang'] ?>:&nbsp;</td>
				<td colspan="2"><input type="text" name="csearch_news_lang" id="news_lang" value="<?php echo html(implode(', ', $content['search']["news_lang"])) ?>" class="width175" /></td>
			</tr>
			<tr>
				<td colspan="2">&nbsp;</td>
				<td align="right" class="chatlist"><?php echo $BL['be_tags'] ?>:&nbsp;</td>
				<td><input type="text" name="csearch_news_category" id="news_category" value="<?php echo html(implode(', ', $content['search']["news_category"])) ?>" class="width175" /></td>
				<td><select name="csearch_news_andor" id="news_andor">

					<option value="OR"<?php is_selected('OR', $content['search']['news_andor']) ?>><?php echo $BL['be_fsearch_or'] ?></option>
					<option value="AND"<?php is_selected('AND', $content['search']['news_andor']) ?>><?php echo $BL['be_fsearch_and'] ?></option>
					<option value="NOT"<?php is_selected('NOT', $content['search']['news_andor']) ?>><?php echo $BL['be_fsearch_not'] ?></option>

				</select></td>
			</tr>
			<tr>
				<td colspan="2">&nbsp;</td>
				<td align="right" class="chatlist"><?php echo $BL['be_cnt_target'].' ('.$BL['be_alias'] ?>/aid=1/id=3):&nbsp;</td>
				<td><input type="text" name="csearch_news_url" id="news_url" value="<?php echo html($content['search']["news_url"]) ?>" class="width175" /></td>
				<td>&nbsp;</td>
			</tr>
		</table>
	<script type="text/javascript">
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

	});

	//-->
	</script>
	</td>
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

	echo '<tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="5" /></td></tr>';
	echo '<tr><td>&nbsp;</td>';
	echo '<td valign="top">';
	echo '<table border="0" cellpadding="1" cellspacing="0" summary="">';
	echo implode(LF, $content['search']['module_search'])	;
	echo '</table></td></tr>';
}
?>

 <tr><td colspan="2" class="rowspacer7x7"><img src="img/leer.gif" alt="" width="1" height="1" /></td></tr>

   <tr>
    <td align="right" class="chatlist">&nbsp;</td>
    <td valign="top" class="chatlist wrap"><?php echo $BL['be_cnt_searchlabeltext'] ?></td>
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
        <td><input name="csearch_label_result" type="text" id="csearch_label_result" class="f11b" style="width: 150px" value="<?php echo  isset($content["search"]["label_result"]) ? html($content["search"]["label_result"]) : '' ?>" size="10" maxlength="250" /></td>
        <td class="chatlist">&nbsp;&nbsp;&nbsp;<?php echo $BL['be_cnt_css_class'] ?>:&nbsp;</td>
        <td><input name="csearch_style_result" type="text" id="csearch_style_result" class="f11b" style="width: 200px" value="<?php echo  isset($content["search"]["style_result"]) ? $content["search"]["style_result"] : '' ?>" size="20" /></td>
      </tr>
    </table>
    </td>
  </tr>

 <tr><td colspan="2" class="rowspacer7x7"><img src="img/leer.gif" alt="" width="1" height="1" /></td></tr>

  <tr>
    <td align="right" class="chatlist" valign="top"><?php echo $BL['be_cnt_page_of_pages'] ?>:&nbsp;</td>
    <td valign="top"><div class="chatlist wrap"><?php echo $BL['be_cnt_page_of_pages_descr'] ?></div>

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
    <td><textarea name="csearch_label_pages" rows="4" class="width440 autosize" id="csearch_label_pages"><?php echo  isset($content["search"]["label_pages"]) ? html($content["search"]["label_pages"]) : '' ?></textarea></td>
  </tr>
  <tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="8" /></td>
</tr>
  <tr>
    <td align="right" class="chatlist wrap"><?php echo $BL['be_cnt_align'] ?>:&nbsp;</td>
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
    <td align="right" class="chatlist">&nbsp;</td>
    <td valign="top" class="chatlist wrap"><?php echo $BL['be_cnt_searchformtext'] ?></td>
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
    <td valign="top"><textarea name="csearch_text_intro" rows="6" class="width440 autosize" id="csearch_text_intro"><?php echo isset($content["search"]["text_intro"]) ? $content["search"]["text_intro"] : '' ?></textarea></td>
  </tr>
  <tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="3" /></td>
</tr>
  <tr>
    <td align="right" class="chatlist tdtop3"><?php echo $BL['be_cnt_result'] ?>:&nbsp;</td>
    <td valign="top"><textarea name="csearch_text_result" rows="6" class="width440 autosize" id="csearch_text_result"><?php echo isset($content["search"]["text_result"]) ? $content["search"]["text_result"] : '' ?></textarea></td>
  </tr>
  <tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="3" /></td>
</tr>
  <tr>
    <td align="right" class="chatlist tdtop3 nowrap" nowrap="nowrap"><?php echo $BL['be_cnt_noresult'] ?>:&nbsp;</td>
    <td valign="top"><textarea name="csearch_text_noresult" rows="6" class="width440 autosize" id="csearch_text_noresult"><?php echo isset($content["search"]["text_noresult"]) ? $content["search"]["text_noresult"] : '' ?></textarea></td>
  </tr>
