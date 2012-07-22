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

unset($_SESSION['filebrowser_image_target']);

$template_default['article']['image_default_width']		 = isset($template_default['article']['image_default_width']) ? $template_default['article']['image_default_width'] : '' ;
$template_default['article']['image_default_height']	 = isset($template_default['article']['image_default_height']) ? $template_default['article']['image_default_height'] : '' ;
$template_default['article']['imagelist_default_width']	 = isset($template_default['article']['imagelist_default_width']) ? $template_default['article']['imagelist_default_width'] : '' ;
$template_default['article']['imagelist_default_height'] = isset($template_default['article']['imagelist_default_height']) ? $template_default['article']['imagelist_default_height'] : '' ;

?>
<form action="phpwcms.php?do=articles&amp;p=2&amp;s=1&amp;aktion=1&amp;id=<?php echo $article["article_id"] ?>" method="post" name="article" id="article">
<table width="538" border="0" cellpadding="0" cellspacing="0" summary="">
      		<tr><td colspan="2" class="title"><?php echo $BL['be_article_estitle'] ?></td></tr>
			<tr>
				<td width="88"><img src="img/leer.gif" alt="" width="88" height="4" /></td>
				<td width="450"><img src="img/leer.gif" alt="" width="450" height="1" /></td>
			</tr>
			<tr><td colspan="2"><img src="img/lines/l538_70.gif" alt="" width="538" height="1" /></td></tr>
      		<tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="8" /></td></tr>

		  	<tr>
				<td align="right" class="chatlist"><?php echo $BL['be_article_cat'] ?>:&nbsp;</td>
				<td><select name="article_cid" id="article_cid" style="width: 325px" class="f11b">
      		<?php
				//keine definierte Kategorie = allgemeine Artikelkategorie
				echo "<option value='0'".((!$article["article_catid"])?" selected":"").">".$BL['be_admin_struct_index']."</option>\n";
				struct_select_menu(0, 0, $article["article_catid"]);
				?>
				</select></td>
			</tr>
			
			
			<tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="8" /></td></tr>

			<tr>
				<td align="right" class="chatlist"><?php echo $BL['be_article_atitle'] ?>:&nbsp;</td>
				<td style="padding:2px 0 3px 0;"><table border="0" cellpadding="0" cellspacing="0" summary="">
				 <tr>
					<td><input name="article_title" type="text" class="f11b" id="article_title" style="width: 325px" value="<?php echo html_specialchars($article["article_title"]) ?>" size="40" maxlength="1000" /></td>
					<td>&nbsp;&nbsp;</td>
					<td><input name="article_notitle" id="article_notitle" type="checkbox" value="1" <?php is_checked($article["article_notitle"],1) ?> /></td>
					<td class="v10"><label for="article_notitle"><?php echo $BL['be_admin_struct_hide1'] ?></label></td>
				 </tr>
			  </table></td>
			</tr>
			<tr>
              <td align="right" class="chatlist"><?php echo $BL['be_article_asubtitle'] ?>:&nbsp;</td>
              <td><input name="article_subtitle" type="text" class="f11b width440" id="article_subtitle" value="<?php echo html_specialchars($article["article_subtitle"]) ?>" size="40" maxlength="1000" /></td>
			</tr>
			<tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="3" /></td></tr>
			<tr>
				<td colspan="2"><table border="0" cellpadding="2" cellspacing="0" summary="">
					<tr>
						<td width="84"><img src="img/leer.gif" alt="" width="84" height="1" /></td>
						<td colspan="7"><img src="img/leer.gif" alt="" width="1" height="1" /></td>
					</tr>
					<tr bgcolor="#E7E8EB">
					  <td width="84" align="right" bgcolor="#FFFFFF" class="chatlist" style="width:84px;">&nbsp;<br /><?php echo $BL['be_article_abegin'] ?>:<img src="img/leer.gif" alt="" width="2" height="1" /></td>
						<td class="chatlist">&nbsp;<br />
						  <input name="set_begin" type="checkbox" id="set_begin" value="1"<?php is_checked(1, $set_begin) ?> onclick="if(!this.checked) {document.article.article_begin.value='';}else{document.article.article_begin.value='<?php echo $article["article_begin"] ?>';}" /></td>
						<td class="chatlist" nowrap="nowrap">YYYY-MM-DD HH:MM:SS<br />
						  <input name="article_begin" type="text" id="article_begin" style="width:140px" class="f11" value="<?php echo $article["article_begin"] ?>" /></td>
					  <td class="chatlist" valign="bottom"><script type="text/javascript">
function aBegin(date, month, year) {
	document.article.article_begin.value = year + '-' + subrstr('00' + month, 2) + '-' + subrstr('00' + date, 2) + ' 00:00:00';
	document.article.set_begin.checked = true;
}
calBegin = new dynCalendar('calBegin', 'aBegin', 'img/dynCal/');
calBegin.setMonthCombo(false);
calBegin.setYearCombo(false);
</script><img src="img/leer.gif" alt="" width="3" height="1" /></td>
						<td align="right" bgcolor="#FFFFFF" class="chatlist">&nbsp;<br />&nbsp;&nbsp;<?php echo $BL['be_article_aend'] ?>:</td>
						<td class="chatlist">&nbsp;<br />
						  <input name="set_end" type="checkbox" id="set_end" value="1"<?php is_checked(1, $set_end) ?> onclick="if(!this.checked) {document.article.article_end.value='';}else{document.article.article_end.value='<?php echo $article["article_end"] ?>';}" /></td>
						<td class="chatlist" nowrap="nowrap">YYYY-MM-DD HH:MM:SS<br />
						  <input name="article_end" type="text" id="article_end" style="width:140px" class="f11" value="<?php echo $article["article_end"] ?>" /></td>
					  <td class="chatlist" valign="bottom"><script type="text/javascript">
function aEnd(date, month, year) {
	document.article.article_end.value = year + '-' + subrstr('00' + month, 2) + '-' + subrstr('00' + date, 2) + ' 23:59:59';
	document.article.set_end.checked = true;
}
calEnd = new dynCalendar('calEnd', 'aEnd', 'img/dynCal/');
calEnd.setMonthCombo(false);
calEnd.setYearCombo(false);
</script><img src="img/leer.gif" alt="" width="3" height="1" /></td>
					</tr>
				</table></td>
			</tr>
			
			
			<tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="7" /></td>
			</tr>
			
			<tr>
				<td align="right" class="chatlist"><?php echo $BL['be_cnt_sortvalue'] ?>:&nbsp;</td>
				<td><table border="0" cellpadding="0" cellspacing="0" summary="">
				 <tr>
					<td><input name="article_sort" type="text" id="article_sort" value="<?php echo empty($article["article_sort"]) ? 0 : intval($article["article_sort"]) ?>" class="v11 width75" maxlength="10" onkeyup="if(!parseInt(this.value))this.value='0';" /></td>
					<td align="right" class="chatlist">&nbsp;&nbsp;&nbsp;<?php echo $BL['be_priorize'] ?>:&nbsp;</td>
					<td><select name="article_priorize" id="article_priorize" class="v11">
					
<?php
	
	for($x=30; $x>=-30; $x--) {				
	
		echo '	<option value="'.$x.'"';
		is_selected($x, $article["article_priorize"]);
		echo '>'. ( $x==0 ? $BL['be_cnt_default'] : $x ) .'</option>'.LF;
	
	}
					
?>					
					</select></td>
				 </tr>
			  </table></td>
			</tr>
			
	<tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="3" /></td></tr>
	
	<tr>
		<td align="right" class="chatlist"><?php echo $BL['be_alias_articleID'] ?>:&nbsp;</td>
		<td><table border="0" cellpadding="0" cellspacing="0" summary="">
		 <tr>
			<td><input name="article_aliasid" type="text" class="f11b width75" id="article_aliasid" value="<?php echo $article["article_aliasid"] ? $article["article_aliasid"] : ''; ?>" size="11" maxlength="11" /></td>
			<td>&nbsp;&nbsp;</td>
			<td><input name="article_headerdata" id="article_headerdata" type="checkbox" value="1" <?php is_checked($article["article_headerdata"],1) ?> /></td>
			<td class="v10"><label for="article_headerdata">&nbsp;<?php echo $BL['be_alias_useAll'] ?></label></td>
		 </tr>
	  </table></td>
	</tr>
	

<?php	if(count($phpwcms['allowed_lang']) > 1):	?>

   		<tr><td><img src="img/leer.gif" alt="" width="1" height="3" /></td></tr>
	
		<tr>
			<td align="right" class="chatlist"><?php echo $BL['be_profile_label_lang'] ?>:&nbsp;</td>
		  	<td><table border="0" cellpadding="0" cellspacing="0" style="background:#E7E8EB;border:2px solid #E7E8EB;">
				<tr>
<?php		foreach($phpwcms['allowed_lang'] as $key => $lang):	
				
				$lang			= strtolower($lang);
				$lang_value		= $lang;
				$lang_default	= '';
				
				if($lang == $phpwcms['default_lang']) {
					$lang_value		= '';
					$lang_default	= ' ('.$BL['be_admin_tmpl_default'].')';
				}

?>
					<td><label for="article_lang<?php echo $key ?>"><input type="radio" name="article_lang" id="article_lang<?php echo $key ?>" value="<?php echo $lang_value ?>"<?php is_checked($lang_value, $article['article_lang']) ?> />
							<img src="img/famfamfam/lang/<?php echo $lang ?>.png" title="<?php echo get_language_name($lang) . $lang_default ?>" /><?php echo $lang_default ?>
							&nbsp;
						</label>
					</td>

<?php		endforeach;	?>

				</tr>
			</table></td>
	</tr>

<?php	endif;	?>

	
	<tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="3" /></td></tr>
	
	<tr>
	  <td align="right" class="chatlist"><?php echo $BL['be_article_aredirect'] ?>:&nbsp;</td>
	  <td><input name="article_redirect" type="text" id="article_redirect" class="f11" style="width: 440px" value="<?php echo html_specialchars($article["article_redirect"]) ?>" size="40" /></td>
	</tr>

	<tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="3" /></td></tr>
	<tr>
		<td align="right" class="chatlist"><a href="#" onclick="return set_article_alias();"><?php echo $BL['be_article_urlalias'] ?></a>:&nbsp;</td>
		<td><input name="article_alias" type="text" class="f11b" id="article_alias" style="width: 440px" value="<?php echo html_specialchars($article["article_alias"]) ?>" size="40" maxlength="200" onfocus="set_article_alias(true);" onchange="this.value=create_alias(this.value);" /></td>
	</tr>		
			<tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="3" /></td></tr>
			<tr>
              <td align="right" class="chatlist"><?php echo $BL['be_admin_page_pagetitle'] ?>:&nbsp;</td>
              <td><input name="article_pagetitle" type="text" id="article_pagetitle" class="f11 width440" value="<?php echo html_specialchars($article['article_pagetitle']) ?>" size="40" maxlength="125" /></td>
			</tr>

	<tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="3" /></td></tr>
	
	<tr>
	  <td align="right" class="chatlist"><?php echo $BL['article_menu_title'] ?>:&nbsp;</td>
	  <td><input name="article_menutitle" type="text" id="article_menutitle" class="f11 width440" value="<?php echo html_specialchars($article["article_menutitle"]) ?>" size="40" /></td>
	</tr>			
		
			<tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="5" /></td></tr>
			<tr>
				<td align="right" class="chatlist tdtop3"><?php echo $BL['be_article_akeywords'] ?>:&nbsp;</td>
				<td><textarea name="article_keyword" rows="2" class="f10 width440" id="article_keyword"><?php echo html_specialchars($article["article_keyword"]) ?></textarea></td>
			</tr>
			<tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="5" /></td></tr>
			<tr>
				<td align="right" class="chatlist tdtop3"><?php echo $BL['be_cnt_description'] ?>:&nbsp;</td>
				<td><textarea name="article_description" rows="2" class="f10 width440" id="article_description"><?php echo html_specialchars($article["article_description"]) ?></textarea></td>
			</tr>
			<tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="5" /></td></tr>		
			<tr>
				<td align="right" class="chatlist" valign="top"><?php echo $BL['be_admin_struct_template'] ?>:&nbsp;</td>
				<td valign="top"><table width="440" border="0" cellpadding="0" cellspacing="0" summary="">
				<tr>
					<td width="215" class="chatlist"><?php echo $BL['be_article_forlist'] ?>:&nbsp;</td>
					<td width="10"><img src="img/leer.gif" alt="" width="10" height="1" /></td>
					<td width="215" class="chatlist"><?php echo $BL['be_article_forfull'] ?>:&nbsp;</td></tr>
				<tr><td colspan="3"><img src="img/leer.gif" alt="" width="1" height="2" /></td></tr>
				<tr>
				  <td><select name="article_tmpllist" id="article_tmpllist" style="width: 215px" class="f11">
<?php
// templates for article listing
$tmpllist = get_tmpl_files(PHPWCMS_TEMPLATE.'inc_cntpart/articlesummary/list');
if($article['image']['tmpllist'] == 'default') {
	$vals= ' selected="selected"';
} else {
	$vals = '';
}
echo '<option value="default"'.$vals.'>'.$BL['be_cnt_default']."</option>\n";
if(count($tmpllist)) {
	foreach($tmpllist as $val) {
		$vals = '';
		if($val == $article['image']['tmpllist']) $vals= ' selected="selected"';
		$val = htmlspecialchars($val);
		echo '<option value="'.$val.'"'.$vals.'>'.$val."</option>\n";
	}
}
				  
?>				  
				  </select></td>
				  <td>&nbsp;</td>
				  <td><select name="article_tmplfull" id="article_tmplfull" style="width: 215px" class="f11">
<?php
// templates for full article
$tmpllist = get_tmpl_files(PHPWCMS_TEMPLATE.'inc_cntpart/articlesummary/article');
if($article['image']['tmplfull'] == 'default') $vals= ' selected="selected"';
echo '<option value="default"'.$vals.'>'.$BL['be_cnt_default']."</option>\n";
if(count($tmpllist)) {
	foreach($tmpllist as $val) {
		$vals = '';
		if($val == $article['image']['tmplfull']) $vals= ' selected="selected"';
		$val = htmlspecialchars($val);
		echo '<option value="'.$val.'"'.$vals.'>'.$val."</option>\n";
	}
}
				  
?>
				  </select></td>
				  </tr>
				  
	<tr>
	<td colspan="3"><img src="img/leer.gif" alt="" width="1" height="2" /></td>
	</tr>
	<tr>
		<td valign="top"><table border="0" cellpadding="0" cellspacing="0" summary="">
			<tr>
				<td><input name="article_listmaxwords" type="text" id="article_listmaxwords" class="f11" style="width: 25px;" value="<?php echo empty($article['image']['list_maxwords']) ? '' : intval($article['image']['list_maxwords']) ?>" size="10" maxlength="6" /></td>
				<td class="v10">&nbsp;<?php echo $BL['be_cnt_results_wordlimit'] ?></td>
			</tr>
		</table></td>
	    <td>&nbsp;</td>
	    <td valign="top"><table border="0" cellpadding="0" cellspacing="0" summary="">
          <tr>
            <td><input name="article_hidesummary" type="checkbox" id="article_hidesummary" value="1"<?php is_checked(1, $article["article_hidesummary"]); ?> /></td>
            <td class="v10"><label for="article_hidesummary">&nbsp;<?php echo $BL['be_article_nosummary'] ?></label></td>
          </tr>
        </table></td>
	</tr>
	<tr><td colspan="3"><img src="img/leer.gif" alt="" width="1" height="2" /></td>
	</tr>
	<tr>
	  <td><table border="0" cellpadding="0" cellspacing="0" summary="">
			<tr>
				<td><input name="article_morelink" type="checkbox" id="article_morelink" value="1"<?php is_checked(1, $article['article_morelink']); ?> /></td>
				<td class="v10"><label for="article_morelink">&nbsp;<?php echo $BL['be_article_morelink'] ?></label></td>
			</tr>
			</table></td>
	  <td>&nbsp;</td>
	  <td><table border="0" cellpadding="0" cellspacing="0" summary="">
          <tr>
            <td><input name="article_paginate" type="checkbox" id="article_paginate" value="1"<?php is_checked(1, $article["article_paginate"]); ?> /></td>
            <td class="v10"><label for="article_paginate">&nbsp;<?php echo $BL['be_cnt_pagination'] ?></label></td>
          </tr>
        </table></td>
	</tr>
	
				</table></td>
			</tr>
			
			
			<tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="10" /></td>
			</tr>

			<tr>
			  
			  <td colspan="2"><?php

$wysiwyg_editor = array(
	'value'		=> $article["article_summary"],
	'field'		=> 'article_summary',
	'height'	=> '450px',
	'width'		=> '536px',
	'rows'		=> '15',
	'editor'	=> $_SESSION["WYSIWYG_EDITOR"],
	'lang'		=> 'en'
);
include(PHPWCMS_ROOT.'/include/inc_lib/wysiwyg.editor.inc.php');

?></td>
    </tr>
<tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="10" /></td>
</tr>
<tr><td colspan="2"><img src="img/lines/l538_70.gif" alt="" /></td>
</tr>
<tr bgcolor="#F3F6F9"><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="8" /></td>
</tr>
<tr bgcolor="#F3F6F9">
	<td align="right" class="chatlist"><?php echo $BL['be_cnt_image'] ?>:&nbsp;</td>
	<td class="chatlist"><?php echo $BL['be_article_forfull'] ?></td>
</tr>
<tr bgcolor="#F3F6F9"><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="5" /></td></tr>
<tr bgcolor="#F3F6F9">
	<td align="right" class="chatlist">&nbsp;</td>
	<td valign="top"><table border="0" cellpadding="0" cellspacing="0" summary="">
		<tr>
			<td><input name="cimage_name" type="text" id="cimage_name" class="f11b" style="width: 300px; color: #727889;" value="<?php echo html_specialchars($article['image']['name']) ?>" size="40" maxlength="250" onfocus="this.blur()" /></td>
			                                                                                                                                              <!-- browser_image.php //-->
			<td><img src="img/leer.gif" alt="" width="3" height="1" /><a href="#" title="<?php echo $BL['be_cnt_openimagebrowser'] ?>" onclick="openFileBrowser('filebrowser.php?opt=0&amp;target=summary');return false;"><img src="img/button/open_image_button.gif" alt="" width="20" height="15" border="0" /></a></td>
			<td><img src="img/leer.gif" alt="" width="3" height="1" /><a href="#" title="<?php echo $BL['be_cnt_delimage'] ?>" onclick="document.article.cimage_name.value='';document.article.cimage_id.value='0';this.blur();return false;"><img src="img/button/del_image_button.gif" alt="" width="15" height="15" border="0" /></a>
		      <input name="cimage_id" type="hidden" value="<?php echo $article['image']['id'] ?>" /></td>
		</tr>
	</table></td>
</tr>
<tr bgcolor="#F3F6F9"><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="5" /></td></tr>
	<tr bgcolor="#F3F6F9">
			  <td align="right" class="chatlist"><?php echo $BL['be_cnt_maxw'] ?>:&nbsp;</td>
			  <td valign="top"><table border="0" cellpadding="0" cellspacing="0" summary="">
			    <tr>
			      <td><input name="cimage_width" type="text" class="f11b" id="cimage_width" style="width: 40px;" size="4" maxlength="4" onkeyup="if(!parseInt(this.value)) this.value='';" value="<?php echo empty($article['image']['width']) ? $template_default['article']['image_default_width'] : $article['image']['width']; ?>" /></td>
			      <td class="chatlist">&nbsp;&nbsp;<?php echo $BL['be_cnt_maxh'] ?>:&nbsp; </td>
			      <td><input name="cimage_height" type="text" class="f11b" id="cimage_height" style="width: 40px;" size="4" maxlength="4" onkeyup="if(!parseInt(this.value)) this.value='';" value="<?php echo empty($article['image']['height']) ? $template_default['article']['image_default_height'] : $article['image']['height']; ?>" /></td>
			      <td class="chatlist">&nbsp;px&nbsp;&nbsp;&nbsp;</td>
				  
				  <td>&nbsp;</td>
				  <td><input name="cimage_zoom" type="checkbox" id="cimage_zoom" value="1" <?php is_checked(1, $article['image']['zoom']); ?> /></td>
				  <td class="v10"><label for="cimage_zoom">&nbsp;<?php echo $BL['be_cnt_enlarge'] ?></label>&nbsp;</td>
				  
				  <td>&nbsp;</td>
				  <td><input name="cimage_lightbox" type="checkbox" id="cimage_lightbox" value="1" <?php is_checked(1, empty($article['image']['lightbox']) ? 0 : 1); ?> onchange="if(this.checked){getObjectById('cimage_zoom').checked=true;}" /></td>
				  <td class="v10">&nbsp;<label for="cimage_lightbox"><?php echo $BL['be_cnt_lightbox'] ?></label>&nbsp;</td>
				  
		        </tr>
		      </table></td>
	</tr>
			<tr bgcolor="#F3F6F9"><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="6" /></td>
			</tr>
			<tr bgcolor="#F3F6F9">
			  <td align="right" valign="top" class="chatlist"><img src="img/leer.gif" alt="" width="1" height="13" /><?php echo $BL['be_cnt_caption'] ?>:&nbsp;</td>
			  <td valign="top"><table border="0" cellpadding="0" cellspacing="0" summary="">
			      <tr>
			        <td valign="top"><textarea name="cimage_caption" cols="30" rows="3" class="f11" id="cimage_caption" style="width: 300px;"><?php echo html_specialchars($article['image']['caption']) ?></textarea></td>
			        <td valign="top"><img src="img/leer.gif" alt="" width="15" height="1" /></td>
			        <td valign="top"><?php
					
					$_SESSION['image_browser_article'] = 1;
					
				$thumb_image = false;
				if(!empty($article["image"]["hash"])) {
					$thumb_image = get_cached_image(
				 					array(	"target_ext"	=>	$article['image']['ext'],
											"image_name"	=>	$article['image']['hash'] . '.' . $article['image']['ext'],
											"thumb_name"	=>	md5($article['image']['hash'].$phpwcms["img_list_width"].$phpwcms["img_list_height"].$phpwcms["sharpen_level"])
        							  )
								);
				}
				if($thumb_image != false) {
					echo '<img src="'.PHPWCMS_IMAGES . $thumb_image[0] .'" border="0" '.$thumb_image[3].' alt="" />';
				} else {
					echo '&nbsp;';
				}
					
					?></td>
		          </tr>
	          </table></td>
			</tr>
			
			<tr bgcolor="#F3F6F9"><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="5" /></td></tr>
			<tr><td colspan="2"><img src="img/lines/l538_70.gif" alt="" /></td></tr>
			<tr bgcolor="#F3F6F9"><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="8" /></td></tr>
<tr bgcolor="#F3F6F9">
	<td align="right" class="chatlist"><?php echo $BL['be_cnt_image'] ?>:&nbsp;</td>
	<td class="chatlist"><?php echo $BL['be_article_forlist'] ?></td>
</tr>
<tr bgcolor="#F3F6F9"><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="5" /></td>
</tr>		
<tr bgcolor="#F3F6F9">
	<td align="right" class="chatlist">&nbsp;</td>
	<td valign="top"><table border="0" cellpadding="0" cellspacing="0" summary="">
		<tr>
<?php

// set default list values
if(!isset($article['image']['list_usesummary'])) {
	$article['image']['list_usesummary']	= 0;
	$article['image']['list_name']			= '';
	$article['image']['list_id']			= 0;
	$article['image']['list_width']			= '';
	$article['image']['list_height']		= '';
	$article['image']['list_zoom']			= 0;
	$article['image']['list_caption']		= '';
}

?>
		<td><input name="cimage_usesummary" type="checkbox" id="cimage_usesummary" value="1" <?php is_checked(1, $article['image']['list_usesummary']); ?> /></td>
		<td class="v10"><label for="cimage_usesummary">&nbsp;<?php echo $BL['be_cnt_same_as_summary'] ?></label>&nbsp;</td>
		</tr>
	</table></td>
</tr>
<tr bgcolor="#F3F6F9"><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="5" /></td>
</tr>	
<tr bgcolor="#F3F6F9">
	<td align="right" class="chatlist">&nbsp;</td>
	<td valign="top"><table border="0" cellpadding="0" cellspacing="0" summary="">
		<tr>
			<td><input name="cimage_list_name" type="text" id="cimage_list_name" class="f11b" style="width: 300px; color: #727889;" value="<?php echo html_specialchars($article['image']['list_name']) ?>" size="40" maxlength="250" onfocus="this.blur()" /></td>
			<td><img src="img/leer.gif" alt="" width="3" height="1" /><a href="#" title="<?php echo $BL['be_cnt_openimagebrowser'] ?>" onclick="openFileBrowser('filebrowser.php?opt=0&amp;target=list');return false;"><img src="img/button/open_image_button.gif" alt="" width="20" height="15" border="0" /></a></td>
			<td><img src="img/leer.gif" alt="" width="3" height="1" /><a href="#" title="<?php echo $BL['be_cnt_delimage'] ?>" onclick="document.article.cimage_list_name.value='';document.article.cimage_list_id.value='0';this.blur();return false;"><img src="img/button/del_image_button.gif" alt="" width="15" height="15" border="0" /></a>
		      <input name="cimage_list_id" type="hidden" value="<?php echo $article['image']['list_id'] ?>" /></td>
		</tr>
	</table></td>
</tr>

<tr bgcolor="#F3F6F9"><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="5" /></td>
</tr>		

<tr bgcolor="#F3F6F9">
	<td align="right" class="chatlist"><?php echo $BL['be_cnt_maxw'] ?>:&nbsp;</td>
	<td valign="top"><table border="0" cellpadding="0" cellspacing="0" summary="">
	<tr>
	<td><input name="cimage_list_width" type="text" class="f11b" id="cimage_list_width" style="width: 40px;" size="4" maxlength="4" onkeyup="if(!parseInt(this.value)) this.value='';" value="<?php echo empty($article['image']['list_width']) ? $template_default['article']['imagelist_default_width'] : $article['image']['list_width']; ?>" /></td>
	<td class="chatlist">&nbsp;&nbsp;<?php echo $BL['be_cnt_maxh'] ?>:&nbsp; </td>
	<td><input name="cimage_list_height" type="text" class="f11b" id="cimage_list_height" style="width: 40px;" size="4" maxlength="4" onkeyup="if(!parseInt(this.value)) this.value='';" value="<?php echo empty($article['image']['list_height']) ? $template_default['article']['imagelist_default_height'] : $article['image']['list_height']; ?>" /></td>
	<td class="chatlist">&nbsp;px&nbsp;&nbsp;&nbsp;</td>
	<td>&nbsp;</td>
	<td><input name="cimage_list_zoom" type="checkbox" id="cimage_list_zoom" value="1" <?php is_checked(1, $article['image']['list_zoom']); ?> /></td>
	<td class="v10"><label for="cimage_list_zoom">&nbsp;<?php echo $BL['be_cnt_enlarge'] ?></label>&nbsp;</td>
	
	<td>&nbsp;</td>
	<td><input name="cimage_list_lightbox" type="checkbox" id="cimage_list_lightbox" value="1" <?php is_checked(1, empty($article['image']['list_lightbox']) ? 0 : 1); ?> onchange="if(this.checked){getObjectById('cimage_list_zoom').checked=true;}" /></td>
	<td class="v10"><label for="cimage_list_lightbox">&nbsp;<?php echo $BL['be_cnt_lightbox'] ?></label>&nbsp;</td>
	
	</tr>
	</table></td>
</tr>
<tr bgcolor="#F3F6F9"><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="6" /></td>
</tr>
<tr bgcolor="#F3F6F9">
	<td align="right" valign="top" class="chatlist"><img src="img/leer.gif" alt="" width="1" height="13" /><?php echo $BL['be_cnt_caption'] ?>:&nbsp;</td>
	<td valign="top"><table border="0" cellpadding="0" cellspacing="0" summary="">
	<tr>
	<td valign="top"><textarea name="cimage_list_caption" cols="30" rows="3" class="f11" id="cimage_list_caption" style="width: 300px;"><?php echo html_specialchars($article['image']['list_caption']) ?></textarea></td>
	<td valign="top"><img src="img/leer.gif" alt="" width="15" height="1" /></td>
	<td valign="top"><?php
	
	$_SESSION['image_browser_article'] = 1;
	
	$thumb_image = false;
	if(!empty($article["image"]["list_hash"])) {
	$thumb_image = get_cached_image(
					array(	"target_ext"	=>	$article['image']['list_ext'],
							"image_name"	=>	$article['image']['list_hash'] . '.' . $article['image']['list_ext'],
							"thumb_name"	=>	md5($article['image']['list_hash'].$phpwcms["img_list_width"].$phpwcms["img_list_height"].$phpwcms["sharpen_level"])
					  	 )
					);
	}
	if($thumb_image != false) {
		echo '<img src="'.PHPWCMS_IMAGES . $thumb_image[0] .'" border="0" '.$thumb_image[3].' alt="" />';
	} else {
		echo '&nbsp;';
	}
	
	?></td>
	</tr>
	</table></td>
</tr>
	

			<tr bgcolor="#F3F6F9"><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="5" /></td>
			</tr>
			<tr><td colspan="2"><img src="img/lines/l538_70.gif" alt="" /></td>
			</tr>
			<tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="10" /></td>
			</tr>
			<?php 
			if($_SESSION["wcs_user_admin"]) {
			
			?>
			<tr>
				<td align="right" class="chatlist"><?php echo $BL['be_article_articleowner'] ?>:&nbsp;</td>
				<td><table border="0" cellpadding="0" cellspacing="0" summary="">
				<tr>
					<td><select name="article_uid" id="article_uid" style="width: 300px" class="f11b">
				<?php
				$u_sql = "SELECT usr_id, usr_name, usr_login, usr_admin FROM ".DB_PREPEND."phpwcms_user WHERE usr_aktiv=1 ORDER BY usr_admin DESC, usr_name";
				if($u_result = mysql_query($u_sql, $db)) {
					while($u_row = mysql_fetch_row($u_result)) {
						echo '<option value="'.$u_row[0].'"';
						if($u_row[0] == $article["article_uid"]) echo ' selected';
						if(intval($u_row[3])) echo ' style="background-color: #FFC299;"';
						echo '>'.html_specialchars(($u_row[1]) ? $u_row[1] : $u_row[2]).'</option>'."\n";
					}
					mysql_free_result($u_result);
				}
				
				?>
				    </select></td>
				<td>&nbsp;&nbsp;&nbsp;</td>
				<td bgcolor="#FFC299"><img src="img/leer.gif" alt="" width="15" height="10" /></td>
				<td class="chatlist">&nbsp;<?php echo $BL['be_article_adminuser'] ?></td>
				</tr></table></td>
			</tr>
			<tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="3" /></td>
			</tr>
			<?php
			}
			?>			
			<tr>
              <td align="right" class="chatlist"><?php echo $BL['be_article_username'] ?>:&nbsp;</td>
              <td><input name="article_username" type="text" id="article_username" class="f11" style="width: 300px" value="<?php echo html_specialchars($article["article_username"]) ?>" size="40" maxlength="100" /></td>
			</tr>
			<tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="15" /></td>
			</tr>
			
	
			<tr>
				<td align="right" class="chatlist inactive"><?php echo $BL['be_cache'] ?>:&nbsp;</td>
				<td><table border="0" cellpadding="0" cellspacing="0" bgcolor="#E7E8EB" summary="" class="inactive">
					<tr>
						<td><input name="article_cacheoff" type="checkbox" id="article_cacheoff" value="1" <?php if($article["article_timeout"] === '0') echo "checked"; ?> /></td>
						<td><label for="article_cacheoff">&nbsp;<?php echo $BL['be_off'] ?></label>&nbsp;&nbsp;</td>
						<td>&nbsp;</td>
						<td><select name="article_timeout" class="f11" style="margin:1px;" onchange="document.article.article_cacheoff.checked=false;">
<?php
echo '<option value=" ">'.$BL['be_admin_tmpl_default']."</option>\n";
echo '<option value="60"'.is_selected($article["article_timeout"], '60', 0, 0).'>&nbsp;&nbsp;1 '.$BL['be_date_minute']."</option>\n";
echo '<option value="300"'.is_selected($article["article_timeout"], '300', 0, 0).'>&nbsp;&nbsp;5 '.$BL['be_date_minutes']."</option>\n";
echo '<option value="900"'.is_selected($article["article_timeout"], '900', 0, 0).'>15 '.$BL['be_date_minutes']."</option>\n";
echo '<option value="1800"'.is_selected($article["article_timeout"], '1800', 0, 0).'>30 '.$BL['be_date_minutes']."</option>\n";
echo '<option value="3600"'.is_selected($article["article_timeout"], '3600', 0, 0).'>&nbsp;&nbsp;1 '.$BL['be_date_hour']."</option>\n";
echo '<option value="14400"'.is_selected($article["article_timeout"], '14400', 0, 0).'>&nbsp;&nbsp;4 '.$BL['be_date_hours']."</option>\n";
echo '<option value="43200"'.is_selected($article["article_timeout"], '43200', 0, 0).'>12 '.$BL['be_date_hours']."</option>\n";
echo '<option value="86400"'.is_selected($article["article_timeout"], '86400', 0, 0).'>&nbsp;&nbsp;1 '.$BL['be_date_day']."</option>\n";
echo '<option value="172800"'.is_selected($article["article_timeout"], '172800', 0, 0).'>&nbsp;&nbsp;2 '.$BL['be_date_days']."</option>\n";
echo '<option value="604800"'.is_selected($article["article_timeout"], '604800', 0, 0).'>&nbsp;&nbsp;1 '.$BL['be_date_week']."</option>\n";
echo '<option value="1209600"'.is_selected($article["article_timeout"], '1209600', 0, 0).'>&nbsp;&nbsp;2 '.$BL['be_date_weeks']."</option>\n";
echo '<option value="2592000"'.is_selected($article["article_timeout"], '2592000', 0, 0).'>&nbsp;&nbsp;1 '.$BL['be_date_month']."</option>\n";
?>
				        </select></td>
				  <td>&nbsp;<?php echo $BL['be_cache_timeout'] ?>&nbsp;&nbsp;</td>
								  
					</tr>
				</table></td>
			</tr>

			<tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="10" /></td></tr>
			
			<tr>
				<td align="right" class="chatlist tdtop4"><?php echo $BL['be_ftptakeover_status'] ?>:&nbsp;</td>
				<td><table border="0" cellpadding="0" cellspacing="0" bgcolor="#E7E8EB" summary="" class="nowrap">				
		
					<tr>
						<td><input name="article_nositemap" type="checkbox" id="article_nositemap" value="1"<?php is_checked(1, $article["article_nositemap"]); ?> /></td>
						<td><label for="article_nositemap">&nbsp;<?php echo  $BL['be_ctype_sitemap'] ?></label>&nbsp;&nbsp;</td>
						
						<td><input name="article_nosearch" type="checkbox" id="article_nosearch" value="1" <?php is_checked(1, $article['article_nosearch']); ?> /></td>
						<td style="padding:1px 5px 1px 0;"><label for="article_nosearch">&nbsp;<?php echo $BL['be_no_search'] ?></label></td>
						
						<td><input name="article_norss" type="checkbox" id="article_norss" value="1" <?php is_checked(1, $article['article_norss']); ?> /></td>
						<td style="padding:1px 5px 1px 0;"><label for="article_norss">&nbsp;<?php echo $BL['be_no_rss'] ?></label></td>
						
						<td colspan="2" style="background-color:#FFFFFF" width="200">&nbsp;</td>
					</tr>
					
					<tr><td colspan="8" style="background-color:#FFFFFF"><img src="img/leer.gif" alt="" width="1" height="3" /></td></tr>
					
					<tr>
						<td style="padding:1px 0 1px 0;"><input name="article_aktiv" type="checkbox" id="article_aktiv" value="1"<?php is_checked(1, $article["article_aktiv"]); ?> /></td>
						<td><label for="article_aktiv">&nbsp;<?php echo $BL['be_admin_struct_visible'] ?></label>&nbsp;&nbsp;</td>
						
						<td><input name="article_public" type="checkbox" id="article_public" value="1"<?php is_checked(1, $article["article_public"]); ?> /></td>
						<td><label for="article_public">&nbsp;<?php echo $BL['be_ftptakeover_public'] ?></label>&nbsp;&nbsp;</td>
						
						<td><input name="article_archive" type="checkbox" id="article_archive" value="1" <?php is_checked(1, $article['article_archive_status']); ?> /></td>
						<td style="padding:1px 5px 1px 0;" colspan="3"><label for="article_archive">&nbsp;<?php echo $BL['be_show_archived'] ?>&nbsp;</label></td>
					</tr>
					
				</table></td>
			</tr>


<?php if(isset($article["article_date"])) { ?>				
			<tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="8" /></td></tr>
	
			<tr>
			<td align="right" class="chatlist"><?php echo $BL['be_article_eslastedit'] ?>:&nbsp;</td>
			<td><?php
			echo (empty($_POST["article_update"]) || !intval($_POST["article_update"])) ? $article["article_date"] : $BL['be_article_esnoupdate'];
			?></td>
			</tr>
<?php } ?>
			
			<tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="15" /></td>
			</tr>
			<tr>
				<td><input name="article_update" type="hidden" id="article_update" value="1" /></td>
				<td><table border="0" cellpadding="0" cellspacing="0" summary="">
					<tr>
					<td><input name="updatesubmit" type="submit" class="button10" value="<?php echo $article["article_id"] ? $BL['be_article_cnt_button1'] : $BL['be_article_cnt_button2'] ?>" /></td>
					<td>&nbsp;</td>
					<td><input name="Submit" type="submit" class="button10" value="<?php echo $BL['be_article_cnt_button3'] ?>" /></td>
					<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
					<td><input name="donotsubmit" type="submit" class="button10" value="<?php echo $BL['be_newsletter_button_cancel'] ?>" onclick="return cancelEdit();" /></td>
					</tr></table></td>
			</tr>
			<tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="10" /></td></tr>
			<tr><td colspan="2"><img src="img/lines/l538_70.gif" alt="" width="538" height="1" /></td></tr>
</table>
</form>
<script type="text/javascript">
window.addEvent('domready', function(){
									 
	/* Autocompleter for keywords (=tags) */
	var searchKeyword = $('article_keyword');
	var completer = new Autocompleter.Ajax.Json(searchKeyword, 'include/inc_act/ajax_connector.php', {
		multi: true,
		maxChoices: 30,
		autotrim: true,
		minLength: 0,
		allowDupes: false,
		postData: {action: 'category', method: 'json'},
		onRequest: function(el) {
			searchKeyword.addClass('ajax-loading');
		},
		onComplete: function(el) {
			searchKeyword.removeClass('ajax-loading');
		}
	});
	

});

function cancelEdit() {
	document.location.href='phpwcms.php?do=articles<?php echo $article["article_id"] ? '&p=2&s=1&id='.$article["article_id"] : '' ?>';
	return false;
}

</script>