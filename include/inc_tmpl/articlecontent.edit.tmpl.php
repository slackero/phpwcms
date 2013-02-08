<?php
/**
 * phpwcms content management system
 *
 * @author Oliver Georgi <oliver@phpwcms.de>
 * @copyright Copyright (c) 2002-2013, Oliver Georgi
 * @license http://opensource.org/licenses/GPL-2.0 GNU GPL-2
 * @link http://www.phpwcms.de
 *
 **/

// ----------------------------------------------------------------
// obligate check for phpwcms constants
if (!defined('PHPWCMS_ROOT')) {
   die("You Cannot Access This Script Directly, Have a Nice Day.");
}
// ----------------------------------------------------------------


// OK check article and category information
$sql  = 'SELECT DISTINCT * FROM '.DB_PREPEND.'phpwcms_article ar LEFT JOIN '.DB_PREPEND.'phpwcms_articlecat ac ON ';
$sql .= "ar.article_cid=ac.acat_id WHERE ar.article_id='".$content["aid"]."' LIMIT 1";
$content['article'] = _dbQuery($sql);
$content['article'] = isset($content['article'][0]) ? $content['article'][0] : array('article_title' => '', 'acat_name' => '', 'acat_template'=>1);
$content['cp_setting_mode'] = false;

if(empty($content['article']['acat_id'])) { // Root structure

	$content['article']['acat_name']		= $indexpage['acat_name'];
	$content['article']['acat_id']			= 0;
	$content['article']['acat_template']	= $indexpage['acat_template'];

}

?>

<form action="phpwcms.php?do=articles&amp;p=2&amp;s=1&amp;aktion=2&amp;id=<?php echo $content["aid"]."&amp;acid=".$content["id"] ?>" method="post" name="articlecontent" id="articlecontent" <?php 
			
	switch($content["type"]) { //Übergeben bestimmter onSubmit JavaScript Aktionen
		case  2: echo 'onsubmit="selectAllOptions(this.cimage_list);"'; 		break;
		case 29: echo 'onsubmit="selectAllOptions(this.cimage_list);"'; 		break;
		case 16: echo 'onsubmit="selectAllOptions(this.cimage_list);"'; 		break;
		case 50: echo 'onsubmit="selectAllOptions(this.cimage_list);"'; 		break;
		case  7: echo 'onsubmit="selectAllOptions(this.cfile_list);"'; 			break;
		//case 25: echo 'onsubmit="selectAllOptions(this.cfile_list);"'; 			break;
		case  8: echo 'onsubmit="selectAllOptions(this.calink);"'; 				break;
		case 53: echo 'onsubmit="selectAllOptions(this.cforum_selection);"'; 	break;
		
		//Poll by Jens
		case 89: echo 'onsubmit="selectAllOptions(this.cimage_list);"';			break;
		
		default: echo 'onsubmit="var ct=getElementById(\'target_ctype\');if(ct.disabled)ct.disabled=false;"';
		
	}
	
	if(empty($content["id"]) && empty($content['block'])) {
	
		$sendbutton = $BL['be_article_cnt_button2'];
		
		$content["block"] 			= 'CONTENT';
		$content["before"] 			= '';
		$content["after"] 			= '';
		$content["title"]			= '';
		$content["subtitle"]		= '';
		$content["top"]				= 0;
		$content["visible"]			= 0;
		$content["anchor"]			= 0;
		$content['comment']			= '';
		$content['paginate_title']	= '';
		$content['paginate_page']	= '';
		$content["granted"]			= 0;
	
	} else {
	
		$sendbutton = $BL['be_article_cnt_button1'];
	
	}
	
	?>>
<input type="hidden" name="ctype_module" value="<?php echo html_specialchars($content["module"]) ?>" />
<table summary="" width="100%" border="0" cellpadding="0" cellspacing="0" style="margin-bottom:13px;">
	
	<tr><td colspan="2" class="title"><?php 
	
	echo $BL['be_article_cnt_title'].' &#8212; <span style="text-transform: uppercase;">';
	echo $wcs_content_type[$content["type"]];
	if(!empty($content["module"])) {
		
		echo ': '.$BL['modules'][$content["module"]]['listing_title'];
		
		// check if Module is in setting mode
		if(!empty($phpwcms['modules'][$content["module"]]['setting'])) {
			$content['cp_setting_mode'] = true;
		}
		
	}
	echo '</span>';
	
	
	?></td></tr>
	<tr><td colspan="2" class="rowspacer5x0"><img src="img/leer.gif" alt="" width="1" height="1" /></td></tr>
	<tr bgcolor="#D9DEE3"><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="4" /></td></tr>
	
	<tr bgcolor="#D9DEE3">
		<td align="right" class="chatlist" nowrap="nowrap"><a href="phpwcms.php?do=admin&amp;p=6&amp;struct=<?php
		
		if(empty($content['article']['acat_id'])) {
			echo 'index';
		} else {
			echo $content['article']['acat_struct'], '&amp;cat=', $content['article']['acat_id'];
		}
		
		?>" onclick="return confirm('<?php echo $BL['be_dialog_warn_nosave']; 
		?>');"><?php echo $BL['be_article_cat'];
		
		?><img src="img/symbole/redirect.gif" alt="" border="0" style="margin:0 0 0 2px;position:relative;top:1px;" /></a>:&nbsp;</td>
		<td><strong><?php echo html_specialchars($content["article"]['acat_name']).' [ID:'.$content['article']['acat_id'].']' ?></strong></td>
	</tr>
	<tr bgcolor="#D9DEE3"><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="2" /></td>
	</tr>
	<tr bgcolor="#D9DEE3">
		<td align="right" class="chatlist" nowrap="nowrap"><a href="phpwcms.php?do=articles&amp;p=2&amp;s=1&amp;aktion=1&amp;id=<?php
		
		echo $content["aid"];
		?>" onclick="return confirm('<?php echo $BL['be_dialog_warn_nosave']; 
		?>');"><?php echo $BL['be_article_atitle'];
		
		?><img src="img/symbole/redirect.gif" alt="" border="0" style="margin:0 0 0 2px;position:relative;top:1px;" /></a>:&nbsp;</td>
		<td onclick="showEditArticleID(this);" onmouseover="this.ttOffsetY=0;Tip('<?php echo $BL['be_change_articleID'].'<br />'.$BL['be_cnt_default'].': '.$content["aid"] ?>')" class="linkcursor"><strong><?php echo html_specialchars($content["article"]['article_title']) ?></strong></td>
	</tr>
	
	
	<tr bgcolor="#D9DEE3"><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="4" /><script type="text/javascript">

	var istuff_done = false;
	function showEditArticleID(istuff) {
		if(istuff_done) return false;
		istuff.innerHTML += '&nbsp; <'+'span class="chatlist"'+'><?php echo $BL['be_func_struct_articleID'] ?>:&nbsp;<'+'/span><'+'input type="text" name="ctype_change_aid" value="<?php echo $content["aid"] ?>" class="v11 width35" /'+'>';
		istuff_done = true;
	}	

	</script></td></tr>
	<tr><td colspan="2" class="rowspacer"><img src="img/leer.gif" alt="" width="1" height="1" /></td></tr>
	<tr bgcolor="#F3F5F8"><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="6" /></td></tr>
	
	<tr bgcolor="#F3F5F8">
	  <td align="right" class="chatlist"><?php 
	
		echo $BL['be_article_cnt_type'];
		$enable_disable = '';
			
	?>:&nbsp;</td>
	  <td><table summary="" border="0" cellspacing="0" cellpadding="0">
		  <tr>
			<td><select name="target_ctype" id="target_ctype" onchange="if(confirm('<?php

	// echo Message for JS dialog
	echo $BL['be_func_switch_contentpart'];
	
	// Menü mit Content Typen erstellen
	// build select box options and remember the "old" value for javascript
	$temp_select				= '';
	$temp_count    				= 0;
	$contentpart_temp_selected	= 0;
	$user_selected_cp			= isset($_SESSION["wcs_user_cp"]) && count($_SESSION["wcs_user_cp"])  ? true : false;
	
	if(is_array($article["article_cntpart"]) && count($article["article_cntpart"])) {
		
		if(!in_array($content['type'], $article["article_cntpart"])) {
			$article["article_cntpart"][] = $content['type'];
		}	
	
		// list all content parts usable for this article category
		foreach($article["article_cntpart"] as $value) {
			
			if($user_selected_cp && !isset($_SESSION["wcs_user_cp"][$value]) && $value != $content['type']) {
				continue;
			}
			
			if(isset($wcs_content_type[$value])) {
				
				$temp_select .= getContentPartOptionTag($value, $wcs_content_type[$value], $content['type'], $content['module']);
				$temp_count++;
			}
			$value1 = $value * (-1);
			if(isset($BL['be_admin_optgroup_label'][$value1]) && $value) {
				$temp_select .= '<optgroup label="[ '.$BL['be_admin_optgroup_label'][$value1].' ]" class="cntOptGroup"></optgroup>'."\n";
			}
		} 
	
	}
	if(!$temp_count) {
		//list all available content parts
		foreach($wcs_content_type as $key => $value) {
		
			if($user_selected_cp && !isset($_SESSION["wcs_user_cp"][$key]) && $key != $content['type']) {
				continue;
			}
		
			$temp_select .= getContentPartOptionTag($key, $value, $content['type'], $content['module']);
			$temp_count++;
		}	
	}
	
?>')){ this.form.submit(); } else {	this.form.target_ctype.selectedIndex = <?php echo $contentpart_temp_selected; ?>; return false; }">
<?php

	//Menü mit Content Typen erstellen
	echo $temp_select
					
?>
                      </select>
                    </td>
			<?php echo $enable_disable; ?>
            
			      </tr>
                </table></td>
   	 </tr>

	<tr bgcolor="#F3F5F8"><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="6" /><?php
	
// Content Part Setting Mode — hide all settings related to article and content part rendering
if($content['cp_setting_mode']):
		// some hidden fields with default content

?>
		<input type="hidden" name="cblock" value="CPSET" />
		<input type="hidden" name="csorting" value="0" />
		<input type="hidden" name="cbefore" value="" />
		<input type="hidden" name="ctab_title" value="" />
		<input type="hidden" name="ctab_number" value="" />
		<input type="hidden" name="ctitle" value="" />
		<input type="hidden" name="csubtitle" value="" />
		<input type="hidden" name="cpaginate_title" value="" />
		<input type="hidden" name="cpaginate_page" value="" />

<?php

	// normal contentpart edit mode
	else:
	
	?></td></tr>
	
	<tr><td colspan="2" class="rowspacer0x7"><img src="img/leer.gif" alt="" width="1" height="1" /></td></tr>

	<tr>
		<td align="right" class="chatlist"><?php echo $BL['be_show_content'] ?>:&nbsp;</td>
		  <td><table summary="" border="0" cellspacing="0" cellpadding="0" width="440">
		  	<tr>
		  		<td width="75%"><select name="cblock" id="cblock"<?php if($content['article']['article_paginate']) echo ' onchange="checkCntBlockPaginate(this);"' ?>>
				<option value="CONTENT"<?php echo  is_selected('CONTENT', $content["block"]) ?>><?php echo  $BL['be_main_content'] ?> (CONTENT)</option>
				<option value="LEFT"<?php echo  is_selected('LEFT', $content["block"]) ?>><?php echo  $BL['be_cnt_left'] ?> (LEFT)</option>
				<option value="RIGHT"<?php echo  is_selected('RIGHT', $content["block"]) ?>><?php echo  $BL['be_cnt_right'] ?> (RIGHT)</option>
				<option value="HEADER"<?php echo  is_selected('HEADER', $content["block"]) ?>><?php echo  $BL['be_admin_page_header'] ?> (HEADER)</option>
				<option value="FOOTER"<?php echo  is_selected('FOOTER', $content["block"]) ?>><?php echo  $BL['be_admin_page_footer'] ?> (FOOTER)</option>
<?php

$sql = "SELECT * FROM ".DB_PREPEND."phpwcms_template WHERE template_id=".$content['article']['acat_template']." LIMIT 1";
$result = _dbQuery($sql);
if(isset($result[0]['template_var'])) {
	$result = unserialize($result[0]['template_var']);
	if(isset($result['customblock'])) {
		$result = explode(',', $result['customblock']);
		foreach($result as $value) {
			$value = trim($value);
			if($value != '') {
				$valhtml = html_specialchars($value);
				echo '<option value="'.$valhtml.'"'.is_selected($value, $content["block"], 0, 0).'>'.$valhtml.'</option>';				
			}
		}
	}
}

?>
				</select></td>
				
				<td class="chatlist" width="100" align="right">&nbsp;&nbsp;<?php echo $BL['be_cnt_sortvalue'] ?>:&nbsp;</td>
				<td><input name="csorting" type="text" id="csorting" value="<?php echo $content["sorting"] ?>" class="width30" maxlength="10" onkeyup="if(!parseInt(this.value))this.value='0';" /></td>
				
			</tr>
		</table></td>
    </tr>
	<tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="4" /></td></tr>
	
	<tr>
	  <td align="right" class="chatlist"><?php echo $BL['be_article_cnt_space'] ?>:&nbsp;</td>
	  <td><table summary="" border="0" cellspacing="0" cellpadding="0">
		  <tr>
			<td class="v10" bgcolor="#E7E8EB"><label for="ccb">&nbsp;<?php echo $BL['be_article_cnt_before'] ?></label></td>
			<td bgcolor="#E7E8EB"><input name="ccb" type="checkbox" id="ccb" value="1" <?php if(intval($content["before"])) echo "checked"; ?> onclick="if(!this.checked){this.form.cbefore.value='';}else{ if(this.form.cbefore.value=='') this.checked=false;}" /></td>
			<td bgcolor="#E7E8EB"><input name="cbefore" type="text" id="cbefore" class="width20" value="<?php echo $content["before"] ?>" size="2" maxlength="4" onkeyup="if(parseInt(this.value)){this.form.ccb.checked=true;}else{this.form.ccb.checked=false;this.value=''}" /></td>
			<td class="v10" bgcolor="#E7E8EB"><label for="cca">&nbsp;<?php echo $BL['be_article_cnt_after'] ?></label></td>
			<td bgcolor="#E7E8EB"><input name="cca" type="checkbox" id="cca" value="1" <?php if(intval($content["after"])) echo "checked"; ?> onclick="if(!this.checked){this.form.cafter.value='';}else{ if(this.form.cafter.value=='') this.checked=false;}" /></td>
			<td style="padding:1px 1px 1px 0" bgcolor="#E7E8EB"><input name="cafter" type="text" id="cafter" class="width20" value="<?php echo $content["after"] ?>" size="2" maxlength="4" onkeyup="if(parseInt(this.value)){this.form.cca.checked=true;}else{this.form.cca.checked=false;this.value=''}" /></td>
			<td class="chatlist"><label for="ctop">&nbsp;&nbsp;<?php echo $BL['be_article_cnt_toplink'] ?>:</label></td>
			<td><input name="ctop" type="checkbox" id="ctop" value="1"<?php is_checked(1, $content["top"]); ?> /></td>
<?php

	$anchor_title = empty($content["id"]) ? '' : ' title="cpid'.$content["id"].'"';
	
	
	// handle tab settings
	$content["tab_style"] = ' style="display:none"';
	
	if(empty($content["tab"])) {
		
		$content["tab"]				= '';
		$content["tab_number"]		= '';
		$content["tab_title"]		= '';
		
	} else {
		
		$content["tab"]				= explode('_', $content["tab"], 2);
		$content["tab_title"]		= empty($content["tab"][1]) ? '' : $content["tab"][1];
		$content["tab_number"]		= empty($content["tab"][0]) ? '' : intval($content["tab"][0]);
		
		if($content["tab_number"].$content["tab_title"]) {
			$content["tab"]			= 1;
			$content["tab_style"]	= '';
		}
	
	}		
			
?>
			<td class="chatlist"><label for="canchor"<?php echo $anchor_title ?>>&nbsp;&nbsp;<?php echo $BL['be_article_cnt_anchor'] ?>:</label></td>
			<td><input name="canchor" type="checkbox" id="canchor" value="1"<?php is_checked(1, $content["anchor"]); ?><?php echo $anchor_title ?> /></td>

			<td class="chatlist"><label for="ctab">&nbsp;&nbsp;<?php echo $BL['be_ctype_tabs'] ?>:</label></td>
			<td><input name="ctab" type="checkbox" id="ctab" value="1"<?php is_checked(1, $content["tab"]); ?> onclick="checkTabStatus(this);" /></td>
		
		</tr>
		</table><script type="text/javascript">

	var cTabStatus = <?php echo $content["tab"] ? 'true' : 'false' ?>;
	
	function checkTabStatus(tabVal) {
		
		cTabStatus = tabVal.checked;
		
		if(cTabStatus == false) {
		
			$('ctab1').setStyle('display', 'none');
			$('ctab2').setStyle('display', 'none');
			$('ctab3').setStyle('display', 'none');
			
		} else {
			
			$('ctab1').setStyle('display', '');
			$('ctab2').setStyle('display', '');
			$('ctab3').setStyle('display', '');

		}
	
	}

		</script></td>
    </tr>
	
	<!-- ctab section -->
	<tr id="ctab1"<?php echo $content["tab_style"] ?>><td colspan="2" class="rowspacer7x0"><img src="img/leer.gif" alt="" width="1" height="1" /></td></tr>
	<tr id="ctab2"<?php echo $content["tab_style"] ?>><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="6" /></td></tr>
	<tr id="ctab3"<?php echo $content["tab_style"] ?>>
	  <td align="right" class="chatlist"><?php echo $BL['be_tab_name'] ?>:&nbsp;</td>
	  <td><table summary="" border="0" cellspacing="0" cellpadding="0">
		  <tr>
		  	<td><input name="ctab_title" type="text" id="ctab_title" class="f11b width225" value="<?php echo html_specialchars($content["tab_title"]) ?>" size="40" maxlength="100" /></td>
			<td class="chatlist">&nbsp;&nbsp;<?php echo $BL['be_cnt_paginate_subsection'] ?>:&nbsp;</td>
			<td><input name="ctab_number" type="text" id="ctab_number" class="v11 width25" value="<?php echo $content["tab_number"] ?>" size="3" maxlength="4" onkeyup="if(!parseInt(this.value))this.value='';" /></td>
		  </tr>
		</table></td>
	</tr>
	<!-- ctab section end -->
	

	<tr><td colspan="2" class="rowspacer7x0"><img src="img/leer.gif" alt="" width="1" height="1" /></td></tr>
	<tr bgcolor="#F3F5F8"><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="6" /></td></tr>

	
   		  <?php
		  if(isset($content["error"])) {
		  ?>
	<tr bgcolor="#F3F5F8">
		<td align="right" valign="top" bgcolor="#FFE9D2"><strong style="color:#FF6600"><?php echo $BL['be_admin_usr_err'] ?>:&nbsp;</strong></td>
		<td valign="top" bgcolor="#FFE9D2"><strong style="color:#FF6600"><?php
			//Fehlerdarstellung
			$content["error_result"]="";
			foreach($content["error"] as $value) {
				$content["error_result"] .= "> ".$value."\n";
			}
			echo nl2br(html_specialchars(chop($content["error_result"])));
			unset($content["error_result"]);
		
		?></strong></td>
	</tr>
	<tr bgcolor="#F3F5F8"><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="6" /></td></tr>
		  <?php
		  }
		  ?>
	<tr bgcolor="#F3F5F8">
	  <td align="right" class="chatlist"><?php echo $BL['be_article_cnt_ctitle'] ?>:&nbsp;</td>
	  <td><input name="ctitle" type="text" id="ctitle" class="width440p5" value="<?php echo html_specialchars($content["title"]) ?>" size="40" maxlength="250" /></td>
	</tr>
	<tr bgcolor="#F3F5F8"><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="2" /></td></tr>
	<tr bgcolor="#F3F5F8">
	  <td align="right" class="chatlist"><?php echo $BL['be_article_asubtitle'] ?>:&nbsp;</td>
	  <td><input name="csubtitle" type="text" id="csubtitle" class="width440p5" value="<?php echo html_specialchars($content["subtitle"]) ?>" size="40" maxlength="250" /></td>
	</tr>
	
<?php

	// check if it is necessary to display paginate stuff 
	// in case no content part pagination isset for current article
	
	$content["paginate_page"] = empty($content["paginate_page"]) ? 0 : intval($content["paginate_page"]);

	if(empty($content['article']['article_paginate'])) {
	
		echo '<tr bgcolor="#F3F5F8"><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="7" />';
		echo '<input name="cpaginate_title" type="hidden" id="cpaginate_title" value="'.html_specialchars($content["paginate_title"]).'" />';
		echo '<input name="cpaginate_page" type="hidden" id="cpaginate_page" value="'.$content["paginate_page"].'" />';
		echo '</td></tr>';
	
	} else {

?>

	<tr bgcolor="#F3F5F8"><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="6" /></td></tr>
	<tr><td colspan="2" class="rowspacer0x7"><img src="img/leer.gif" alt="" width="1" height="1" /></td></tr>

	<tr>
	  <td align="right" class="chatlist"><?php echo $BL['be_cnt_paginate_subsection'] ?>:&nbsp;</td>
	  <td><table summary="" border="0" cellspacing="0" cellpadding="0" width="444">
		  <tr>
		  	<td><input name="cpaginate_page" type="text" id="cpaginate_page" class="v11 width25" value="<?php echo $content["paginate_page"] ?>" size="3" maxlength="3" onkeyup="if(!parseInt(this.value))this.value='0';" /></td>
			<td align="right" class="chatlist">&nbsp;&nbsp;<?php echo $BL['be_cnt_subsection_tite'].' ('.$BL['be_pagination'].')' ?>:&nbsp;</td>
			<td width="200"><input name="cpaginate_title" type="text" id="cpaginate_title" class="f11b width225" value="<?php echo html_specialchars($content["paginate_title"]) ?>" size="40" maxlength="200" /></td>
		  </tr>
		</table><script language="javascript" type="text/javascript">

	var loadblock  = true;
	checkCntBlockPaginate(getObjectById("cblock"));
	loadblock      = false;

	function checkCntBlockPaginate(obj) {
		var paginate = getObjectById("cpaginate_page");
		if(obj.options[obj.selectedIndex].value != "CONTENT") {
			if(paginate.value != "0" && loadblock == false) {
				if(!confirm("<?php echo $BL['be_cnt_subsection_warning'] ?>")) {
					getObjectById("cblock").selectedIndex = 0;
					return false;
				}
			}
			paginate.disabled = true;
		} else {
			paginate.disabled = false;
		}
	}
	
		</script></td>
	</tr>
	
	<tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="7" /></td></tr>

<?php

	}
	// end paginate check


// end non content part setting mode
endif;

?>

	<tr><td colspan="2" class="rowspacer"><img src="img/leer.gif" alt="" width="1" height="1" /></td></tr>

	<tr bgcolor="#F3F5F8">
		<td>&nbsp;</td>
		<td style="padding:7px 0 7px 0;">
<?php

// render buttons only once and save the buffer
if(!empty($content["id"])) {
	$buttonActionLink	= 'index.php?id='.$content['article']['acat_id'].','.$content["aid"].',0,0,1,0';
	$buttonAction  = '	<div style="float:right;margin-right:5px;padding:0;">';
	$buttonAction .= '	<button type="button" value="'.$BL['be_func_struct_preview'].'" class="button10" title="'.$BL['be_func_struct_preview'].'" ';
	$buttonAction .= 'onclick="window.open(\''.$buttonActionLink."', 'articlePreviewWindows');return false;\">";
	$buttonAction .= $BL['be_func_struct_preview']."</button></div>" . LF;
} else {
	$buttonAction  = '';
}


ob_start();

?>		
<div>
	<?php echo $buttonAction; ?>
	<div style="float:left;padding:0;">
	<input name="Submit" type="submit" class="button10" value="<?php echo  $sendbutton ?>" />
	<input name="SubmitClose" type="submit" class="button10" value="<?php echo $BL['be_article_cnt_button3'] ?>" />
	&nbsp; &nbsp;
	<input name="donotsubmit" type="button" class="button10" value="<?php echo  $BL['be_newsletter_button_cancel'] ?>" onclick="location.href='phpwcms.php?do=articles&amp;p=2&amp;s=1&amp;id=<?php echo $content["aid"] ?>'" />
	</div>
</div>		
<?php

$_save_close_buttons = ob_get_clean();

echo $_save_close_buttons;

?>	
		</td>
	</tr>

<?php

	// show content part specific form elements
	if($content['type'] != 30 && file_exists(PHPWCMS_ROOT.'/include/inc_tmpl/content/cnt'.$content['type'].'.inc.php')) {
	
		include_once(PHPWCMS_ROOT.'/include/inc_tmpl/content/cnt'.$content['type'].'.inc.php');
		
	} elseif($content['type'] == 30 && file_exists($phpwcms['modules'][$content["module"]]['path'].'inc/cnt.form.php')) {
	
		include_once($phpwcms['modules'][$content["module"]]['path'].'inc/cnt.form.php');
		
	} else {

		include_once(PHPWCMS_ROOT.'/include/inc_tmpl/content/cnt0.inc.php');

	}

?>

	<tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="8" /></td></tr>

	<tr>
		<td align="right" class="chatlist"><?php echo $BL['be_ftptakeover_status'] ?>:&nbsp;</td>
		<td><table summary="" border="0" cellpadding="1" cellspacing="0" bgcolor="#E7E8EB">
			<tr>

				<td><input name="cvisible" type="checkbox" id="cvisible" value="1"<?php is_checked(1, $content["visible"]); ?> /></td>
				<td><label for="cvisible"><?php echo $BL['be_admin_struct_visible'] ?>&nbsp;&nbsp;</label></td>
				
				<td bgcolor="#FFFFFF">&nbsp;</td>
				
				<td><input name="cgranted" type="checkbox" id="cgranted" value="1"<?php is_checked(1, $content["granted"]); ?> /></td>
				<td><label for="cgranted"><?php echo $BL['be_granted_feuser'] ?>&nbsp;&nbsp;</label></td>
				
			</tr>
		</table></td>
	</tr>
	
	<tr>

	<td>&nbsp;</td>
	<td style="padding: 12px 0 12px 0;">
<input name="caktion" type="hidden" id="caktion" value="1" />
<input name="caid" type="hidden" id="caid" value="<?php echo $article["article_id"] ?>" />
<input name="cid" type="hidden" id="cid" value="<?php echo  $content["id"] ?>" />
<input name="ctype" type="hidden" id="ctype" value="<?php echo  $content["type"] ?>" />
<?php echo $_save_close_buttons ?>
		</td>
	</tr>
	
	<tr><td colspan="2" class="rowspacer0x7"><img src="img/leer.gif" alt="" width="1" height="1" /></td></tr>
	
	<tr>
	  <td align="right" class="chatlist tdtop3"><?php echo $BL['be_profile_label_notes'] ?>:&nbsp;</td>
	  <td><textarea name="ccomment" id="ccomment" class="v11 width440" rows="5"><?php echo html_specialchars($content["comment"]) ?></textarea></td>
	</tr>
	
</table>
</form>