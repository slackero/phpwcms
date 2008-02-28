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

// News



$news = & new phpwcmsNews();
$news->limit = 50;


?>
<h1 class="title"><?php echo $BL['be_news'] ?></h1>

<?php

	if(isset($_GET['cntid'])) {
		
		$news->edit();
		
	} else {

?>
	<div class="navBarLeft imgButton chatlist">
		&nbsp;&nbsp;
		<a href="<?= $news->base_url ?>&amp;cntid=0&amp;action=edit" title="<?= $BL['be_news_create'] ?>"><img src="img/famfamfam/silk_icons_gif/page_white_add.gif" alt="New" border="0" /><span><?= $BL['be_news_create'] ?></span></a>
	</div>
	
<form action="<?= $news->base_url ?>" method="post" name="paginate" id="paginate">
<table width="100%" border="0" cellpadding="0" cellspacing="0" class="paginate" summary="">
	<tr>
		<td><table border="0" cellpadding="0" cellspacing="0" summary="">
			<tr>
				
				<td><input type="checkbox" name="showactive" id="showactive" value="1" onclick="this.form.submit();"<?php //is_checked(1, $_entry['list_active'], 1) ?> /></td>
				<td><label for="showactive"><img src="img/button/aktiv_12x13_1.gif" alt="" style="margin:1px 1px 0 1px;" /></label></td>
				<td><input type="checkbox" name="showinactive" id="showinactive" value="1" onclick="this.form.submit();"<?php //is_checked(1, $_entry['list_inactive'], 1) ?> /></td>
				<td><label for="showinactive"><img src="img/button/aktiv_12x13_0.gif" alt="" style="margin:1px 1px 0 1px;" /></label></td>

<?php 

$_entry['pages_total'] = 0;

if($_entry['pages_total'] > 1) {

	echo '<td class="chatlist">|&nbsp;</td>';
	echo '<td>';
	if($_SESSION['glossary_page'] > 1) {
		echo '<a href="'.GLOSSARY_HREF.'&amp;page='.($_SESSION['glossary_page']-1).'">';
		echo '<img src="img/famfamfam/mini/action_back.gif" alt="" border="0" /></a>';
	} else {
		echo '<img src="img/famfamfam/mini/action_back.gif" alt="" border="0" class="inactive" />';
	}
	echo '</td>';
	echo '<td><input type="text" name="page" id="page" maxlength="4" size="4" value="'.$_SESSION['glossary_page'];
	echo '"  class="textinput" style="margin:0 3px 0 5px;width:30px;font-weight:bold;" /></td>';
	echo '<td class="chatlist">/'.$_entry['pages_total'].'&nbsp;</td>';
	echo '<td>';
	if($_SESSION['glossary_page'] < $_entry['pages_total']) {
		echo '<a href="'.GLOSSARY_HREF.'&amp;page='.($_SESSION['glossary_page']+1).'">';
		echo '<img src="img/famfamfam/mini/action_forward.gif" alt="" border="0" /></a>';
	} else {
		echo '<img src="img/famfamfam/mini/action_forward.gif" alt="" border="0" class="inactive" />';
	}
	echo '</td><td class="chatlist">&nbsp;|&nbsp;</td>';

} else {

	echo '<td class="chatlist">|&nbsp;</td>';

}
?>
				<td><input type="text" name="filter" id="filter" size="10" value="<?php 
				
				if(isset($_POST['filter']) && is_array($_POST['filter']) ) {
					echo html_specialchars(implode(' ', $_POST['filter']));
				}
				
				?>" class="textinput" style="margin:0 2px 0 0;width:110px;text-align:left;" title="filter results by username, name or email" /></td>
				<td><input type="image" name="gofilter" src="img/famfamfam/mini/action_go.gif" style="margin-right:3px;" /></td>
			
			</tr>
		</table></td>

	<td class="chatlist" align="right">
		<?= getItemsPerPageMenu( $news->base_url ) ?>
	</td>

	</tr>
</table>
</form>
	
	
	
<?php
		echo $news->listBackend();
	
	}

	// Begin news form
	if(count($news->data)) {
		
		// some JavaScripts wee need
		initJsCalendar();
		initJsOptionSelect();
		initMootoolsAutocompleter();

?>
<script type="text/javascript">
<!--

window.addEvent('domready', function(){
									 
	/* setup tooltips */
	var as = [];
	$$('input').each(function(a){ if (a.getAttribute('title')) as.push(a); });
	new Tips(as, { maxTitleChars: 25, offsets: {'x': 2, 'y': 5} });
			 
	/* Autocompleter for categories/tags */
	var searchCategory = $('cnt_category');
	var indicator2 = new Element('span', {'class': 'autocompleter-loading', 'styles': {'display': 'none'}}).setHTML('').injectAfter(searchCategory);
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
	
	var selectLang = $('cnt_lang');
	var indicator1 = new Element('span', {'class': 'autocompleter-loading', 'styles': {'display': 'none'}}).setHTML('').injectAfter(selectLang);
	var completer1 = new Autocompleter.Ajax.Json(selectLang, 'include/inc_act/ajax_connector.php', {
		multi: false,
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
		this.value = this.value.replace(/[^a-z\-]/g, '');
	});
	

});


//-->
</script>
<form action="<?= $news->formAction() ?>" method="post" class="free" onsubmit="selectAllOptions(this.cfile_list);">

	<p>	
		<label><?= $BL['be_title'] ?>/<?= $BL['be_alias'] ?></label>
		<input type="text" name="cnt_name" id="cnt_name" value="<?= html_specialchars($news->data['cnt_name']) ?>" class="text short" maxlength="200" title="<?= $BL['be_title'] ?>" />
		<input type="text" name="cnt_alias" id="cnt_alias" value="<?= html_specialchars($news->data['cnt_alias']) ?>" class="text short" maxlength="200" title="<?= $BL['be_alias'] ?>" />
	</p>
	
	<p>	
		<label><?= $BL['be_admin_page_category'] ?></label>
		<input type="text" name="cnt_category" id="cnt_category" value="<?= html_specialchars($news->data['cnt_category']) ?>" class="text" maxlength="250" />
	</p>

	<p>	
		<label><?= $BL['be_profile_label_lang'] ?></label>
		<input type="text" name="cnt_lang" id="cnt_lang" value="<?= html_specialchars($news->data['cnt_lang']) ?>" class="text short" maxlength="10" />
	</p>

	<p class="break filled important">
		<label><?= $BL['be_article_cnt_ctitle'] ?></label>
		<input type="text" name="cnt_title" id="cnt_title" value="<?= html_specialchars($news->data['cnt_title']) ?>" class="text" maxlength="250" />
	</p>
	
	<p class="important border_bottom">	
		<label><?= $BL['be_article_asubtitle'] ?></label>
		<input type="text" name="cnt_subtitle" id="cnt_subtitle" value="<?= html_specialchars($news->data['cnt_subtitle']) ?>" class="text" maxlength="250" />
	</p>

	<div class="paragraph filled">
	<table border="0" cellpadding="0" cellspacing="0" summary="">
		
			<tr>
				<td class="chatlist">&nbsp;</td>
				<td class="chatlist" style="padding-bottom:2px"><?php echo $BL['default_date_format'] ?></td>
				<td class="chatlist">&nbsp;</td>
				<td colspan="2" class="chatlist" style="padding-bottom:2px"><?php echo $BL['default_time_format'] ?></td>
			</tr>
		
			<tr>
				<td><label><?= $BL['be_article_cnt_start'] ?></label></td>
				<td><input name="calendar_start_date" type="text" id="calendar_start_date" class="v12" style="width:100px;" value="<?= $news->data['cnt_date_start'] ?>" size="30" /></td>
		<td>
		<script language="javascript" type="text/javascript">
		<!--
		function aStart(date, month, year) {
			getFieldById('calendar_start_date').value = subrstr('00' + date, 2) + '<?php echo $BL['default_date_delimiter'] ?>' + subrstr('00' + month, 2) + '<?php echo $BL['default_date_delimiter'] ?>' + year;
		}
		calStart = new dynCalendar('calStart', 'aStart', 'img/dynCal/');
		calStart.setMonthCombo(true);
		calStart.setYearCombo(true);
		//-->
		</script>
		&nbsp;</td>
		<td><input name="calendar_start_time" type="text" id="calendar_start_time" class="v12" style="width:80px;" value="<?= $news->data['cnt_time_start'] ?>" size="30" /></td>
			</tr>
			
		<tr><td colspan="4" style="font:5px;line-height:5px">&nbsp;</td></tr>
		
			<tr>
				<td class="chatlist">&nbsp;</td>
				<td class="chatlist" style="padding-bottom:2px"><?php echo $BL['default_date_format'] ?></td>
				<td class="chatlist">&nbsp;</td>
				<td class="chatlist" style="padding-bottom:2px"><?php echo $BL['default_time_format'] ?></td>
			</tr>
		
			<tr>
				<td><label><?= $BL['be_article_cnt_end'] ?></label></td>
				<td><input name="calendar_end_date" type="text" id="calendar_end_date" class="v12" style="width:100px;" value="<?= $news->data['cnt_date_end'] ?>" size="30" /></td>
		<td>
		<script language="javascript" type="text/javascript">
		<!--
		function aEnd(date, month, year) {
			getFieldById('calendar_end_date').value = subrstr('00' + date, 2) + '<?php echo $BL['default_date_delimiter'] ?>' + subrstr('00' + month, 2) + '<?php echo $BL['default_date_delimiter'] ?>' + year;
		}
		calEnd = new dynCalendar('calEnd', 'aEnd', 'img/dynCal/');
		calEnd.setMonthCombo(true);
		calEnd.setYearCombo(true);
		//-->
		</script>
		&nbsp;</td>
		<td><input name="calendar_end_time" type="text" id="calendar_end_time" class="v12" style="width:80px;" value="<?= $news->data['cnt_time_end'] ?>" size="30" /></td>
			</tr>
		</table>
	</div>
	
	<p class="break">	
		<label><?= $BL['be_article_username'] ?>/<?= $BL['be_place'] ?></label>
		<input type="text" name="cnt_editor" id="cnt_editor" value="<?= html_specialchars($news->data['cnt_editor']) ?>" class="text short" maxlength="250" title="<?= $BL['be_article_username'] ?>" />
		<input type="text" name="cnt_place" id="cnt_place" value="<?= html_specialchars($news->data['cnt_place']) ?>" class="text short" maxlength="250" title="<?= $BL['be_place'] ?>" />
	</p>
	
	<p class="border_bottom">	
		<label><?= $BL['be_teasertext'] ?></label>
		<textarea name="cnt_teasertext" id="cnt_teasertext" class="text" rows="5"><?= html_specialchars($news->data['cnt_teasertext']) ?></textarea>
	</p>
	
	<p class="border_bottom space_top">	
		<label><?= $BL['be_read_more_link'] ?>/<?= $BL['be_admin_page_text'] ?></label>
		<input type="text" name="cnt_link" id="cnt_link" value="<?= html_specialchars($news->data['cnt_link']) ?>" class="text short" maxlength="250" title="<?= $BL['be_read_more_link'] ?>" />
		<input type="text" name="cnt_linktext" id="cnt_linktext" value="<?= html_specialchars($news->data['cnt_linktext']) ?>" class="text short" maxlength="250" title="<?= $BL['be_admin_page_text'] ?>" />
	</p>

	<div class="paragraph filled border_bottom">
		
		<table cellpadding="0" cellspacing="0" border="0" summary="">
	
			<tr>
				<td><label><?= $BL['be_cnt_image'] ?></label></td>
				<td><input type="text" name="cnt_image_name" id="cnt_image_name" value="<?= html_specialchars($news->data['cnt_image']['name']) ?>" class="file" maxlength="250" /></td>
				<td style="padding:2px 0 0 5px" width="100">
					<a href="#" title="<?php echo $BL['be_cnt_openimagebrowser'] ?>" onclick="openFileBrowser('filebrowser.php?opt=7');return false;"><img src="img/button/open_image_button.gif" alt="" width="20" height="15" border="0" /></a>
					<a href="#" title="<?php echo $BL['be_cnt_delimage'] ?>" onclick="setImgIdName();return false;"><img src="img/button/del_image_button.gif" alt="" width="15" height="15" border="0" /></a>
					<input name="cnt_image_id" id="cnt_image_id" type="hidden" value="<?= $news->data['cnt_image']['id'] ?>" />
				</td>
			</tr>

			<tr>
				<td>&nbsp;</td>
				<td colspan="2" class="tdtop5 tdbottom5">
				
				<table border="0" cellpadding="0" cellspacing="0" summary="">
				<tr>
			  <td><input name="cnt_image_zoom" type="checkbox" id="cnt_image_zoom" value="1" <?php is_checked(1, $news->data['cnt_image']['zoom']); ?> /></td>
				  <td><label for="cnt_image_zoom" class="checkbox"><?php echo $BL['be_cnt_enlarge'] ?></label></td>

				  <td><input name="cnt_image_lightbox" type="checkbox" id="cnt_image_lightbox" value="1" <?php is_checked(1, $news->data['cnt_image']['lightbox']); ?> onchange="if(this.checked){getObjectById('cnt_image_zoom').checked=true;}" /></td>
				  <td><label for="cnt_image_lightbox" class="checkbox"><?php echo $BL['be_cnt_lightbox'] ?></label></td>		
				</tr>
				</table>
				
				<div id="cnt_image" style="padding-top:3px;"></div>
				
				</td>
			</tr>
			
		<tr>
				<td class="top"><label><?= $BL['be_cnt_caption'] ?></label></td>
				<td colspan="2" class="tdbottom4">
				<textarea name="cnt_image_caption" id="cnt_image_caption" class="text" rows="2"><?= html_specialchars($news->data['cnt_image']['caption']) ?></textarea>				
				</td>
			</tr>
			
			<tr>
				<td><label><?= $BL['be_profile_label_website'] ?></label></td>
				<td colspan="2"><input type="text" name="cnt_image_link" id="cnt_image_link" class="text" maxlength="500" value="<?= html_specialchars($news->data['cnt_image']['link']) ?>" /></td>
			</tr>
	
		</table>

		<script type="text/javascript">
		<!--
		
		showImage();
		
		function setImgIdName(file_id, file_name) {
			if(file_id == null) file_id=0;
			if(file_name == null) file_name='';
			getObjectById('cnt_image_id').value = file_id;
			getObjectById('cnt_image_name').value = file_name;
			
			showImage();
		}
		
		function showImage() {
			id	= parseInt(getObjectById('cnt_image_id').value);
			img	= getObjectById('cnt_image');
			if(id > 0) {
				img.innerHTML = '<img src="<?= PHPWCMS_URL.'img/cmsimage.php/'.$phpwcms['img_list_width'].'x'.$phpwcms['img_list_height'] ?>/'+id+'" alt="" border="0" />';
				img.style.display = '';
			} else {
				img.style.display = 'none';
			}
		}
		
		function addFile(file_id, file_name) {
			obj = getObjectById('cfile_list');
			if(obj!=null && obj.options!=null) {
				newOpt = new Option(file_name, file_id);
				obj.options.length++;
				obj.options[obj.length-1].text  	= newOpt.text;
				obj.options[obj.length-1].value 	= newOpt.value;
				obj.options[obj.length-1].selected	= false;
				if(obj.options.length > 5) {
					obj.size = obj.options.length;
					getObjectById('cnt_file_caption').rows = obj.size;
				}
			}	
		}
		
		function emptyNews() {
			document.location.href='<?= $news->base_url_decoded ?>&cntid=0&action=edit';
			return false;
		}
		
		function closeForm() {
			document.location.href='<?= $news->base_url_decoded ?>';
			return false;
		}
	
		//-->
		</script>
	</div>
	
	<div class="paragraph border_bottom">
	<table border="0" cellpadding="0" cellspacing="0" summary="">
	<tr>
		<td class="top"><label><?= $BL['be_cnt_files'] ?></label></td>
		<td style="padding:0 5px 5px 0;"><select name="cnt_files[]" size="5" multiple="multiple" id="cfile_list" class="">
<?php
		foreach( $news->getFiles() as $item ) {

			echo '<option value="' . $item['f_id'] . '">' . html_specialchars($item['f_name']) . '</option>' . LF;

		}
?>
		</select></td>
		<td valign="top" width="20">
		<a href="#" title="<?= $BL['be_cnt_openfilebrowser'] ?>" onclick="openFileBrowser('filebrowser.php?opt=9');return false"><img src="img/button/open_image_button.gif" alt="" width="20" height="15" border="0" vspace="2" /></a>
		<a href="#" title="<?= $BL['be_cnt_sortup'] ?>" onclick="moveOptionUp(getObjectById('cfile_list'));return false;"><img src="img/button/image_pos_up.gif" alt="" width="10" height="9" border="0" /></a><a href="#" title="<?= $BL['be_cnt_sortdown'] ?>" onclick="moveOptionDown(getObjectById('cfile_list'));return false;"><img src="img/button/image_pos_down.gif" alt="" width="10" height="9" border="0" /></a>
		<a href="#" onclick="removeSelectedOptions(getObjectById('cfile_list'));return false;" title="<?= $BL['be_cnt_delfile'] ?>"><img src="img/button/del_image_button1.gif" alt="" width="20" height="15" border="0" vspace="2" /></a>
		</td>
	</tr>
	
	<tr>
 		<td class="top"><label><?= $BL['be_cnt_description'] ?></label></td>
  		<td colspan="2"><textarea name="cnt_file_caption" cols="40" rows="5" class="text" id="cnt_file_caption"><?= html_specialchars($news->data['cnt_files']['caption']) ?></textarea></td>
	</tr>
	
  </table>
  	
	</div>

	
	<div class="paragraph">
		<?php

		$wysiwyg_editor = array(
			'value'		=> $news->data['cnt_text'],
			'field'		=> 'cnt_text',
			'height'	=> '350px',
			'width'		=> '536px',
			'rows'		=> '15',
			'editor'	=> $_SESSION["WYSIWYG_EDITOR"],
			'lang'		=> 'en'
		);
		
		include('include/inc_lib/wysiwyg.editor.inc.php');

		?>
	</div>
	
	
	<div class="filled border_top paragraph border_bottom">
	
		<table cellpadding="0" cellspacing="0" border="0" summary="">
		
		<tr>
		
			<td><label><?= $BL['be_ftptakeover_status'] ?></label></td>
			<td><input name="cnt_status" type="checkbox" id="cnt_status" value="1" <?php is_checked(1, $news->data['cnt_status']); ?> /></td>
			<td><label class="checkbox" for="cnt_status"><?= $BL['be_published'] ?></label></td>
		</tr>
		
		<tr>
			<td>&nbsp;</td>
			<td><input name="cnt_archive_status" type="checkbox" id="cnt_archive_status" value="1" <?php is_checked(1, $news->data['cnt_archive_status']); ?> /></td>
			<td><label class="checkbox" for="cnt_archive_status"><?= $BL['be_show_archived'] ?></label></td>
		</tr>
		
		<tr>
			<td colspan="3" style="line-height:5px;font-size:5px;">&nbsp;</td>
		</tr>
		
		<tr>
			<td>&nbsp;</td>
			<td><input name="cnt_duplicate" type="checkbox" id="cnt_duplicate" value="1" <?php is_checked(1, $news->data['cnt_duplicate']); ?> /></td>
			<td><label class="checkbox" for="cnt_duplicate"><?= $BL['be_save_copy'] ?></label></td>
		</tr>
		
		</table>
			
	</div>
	
	<p style="padding:10px 0 10px 0" class="border_bottom">
		
		<label>&nbsp;</label>
		
		<?php if($news->data['cnt_id']) { ?>
		
			<input name="submit" type="submit" class="button10" value="<?= $BL['be_article_cnt_button1'] ?>" />
			<input name="save" type="submit" class="button10" value="<?= $BL['be_article_cnt_button3'] ?>" />

		<?php } else { ?>
		
			<input name="submit" type="submit" class="button10" value="<?= $BL['be_admin_fcat_button2'] ?>" />
			<input name="save" type="submit" class="button10" value="<?= $BL['be_article_cnt_button3'] ?>" />
		
		<?php }	?>
		&nbsp;&nbsp;&nbsp;&nbsp;
		<input name="new" type="button" class="button10" value="<?= ucfirst($BL['be_msg_new']) ?>" onclick="emptyNews();" />
		<input name="close" type="button" class="button10" value="<?= $BL['be_admin_struct_close'] ?>" onclick="closeForm();" />
	
	</p>


</form>
<?php 

	}
	// Stop news form
	
?>