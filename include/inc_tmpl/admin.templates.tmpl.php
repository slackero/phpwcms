<?php
/*************************************************************************************
   Copyright notice
   
   (c) 2002-2011 Oliver Georgi (oliver@phpwcms.de) // All rights reserved.
 
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

reset($phpwcms['js_lib']);	// reset $phpwcms['js_lib'] to get first element as default

$template = array(	"name" => '', "default" => 0, "layout" => '', "css" => array(), "htmlhead" => '',
					"jsonload" => '', "headertext" => '', "maintext" => '', "footertext" => '', 
					"lefttext" => '', "righttext" => '', "errortext" => '', 'feloginurl' => '',
					'jslib'	=> key($phpwcms['js_lib']), 'jslibload' => 0, 'frontendjs' => 0, 'googleapi' => 1 );

if(!isset($_GET["s"])) { 
// check if template should be edited
?>
<table width="538" border="0" cellpadding="0" cellspacing="0" summary="">
	<tr><td colspan="3" class="title"><?php echo $BL['be_admin_tmpl_title'] ?></td></tr>
	<tr><td colspan="3"><img src="img/leer.gif" alt="" width="1" height="6" /></td>
	</tr>
	<tr><td colspan="3" bgcolor="#92A1AF"><img src="img/leer.gif" alt="" width="1" height="1" /></td>
	</tr>
<?php
// loop listing available templates 
$sql = "SELECT * FROM ".DB_PREPEND."phpwcms_template WHERE template_trash=0 ORDER BY template_default DESC, template_name"; //AND template_type=0 
if($result = mysql_query($sql, $db) or die("error while listing templates")) {
	$row_count = 0;
	while($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
	
		$edit_link = 'do=admin&amp;p=11&amp;s='.$row["template_id"].'&amp;t='.$row["template_type"];
	
		echo "<tr".( ($row_count % 2) ? " bgcolor=\"#F3F5F8\"" : "" ).">\n<td width=\"28\">"; //#F9FAFB
		echo '<img src="img/symbole/template_list_icon.gif" width="28" height="18"></td>'."\n";
		echo '<td width="470" class="dir"><a href="phpwcms.php?'.$edit_link;
		echo '"><strong>'.html_specialchars($row["template_name"])."</strong>";
		echo ($row["template_default"]) ? " (".$BL['be_admin_tmpl_default'].")" : "";
		echo "</a></td>\n".'<td width="60" align="right">';
		echo '<a href="phpwcms.php?'.$edit_link;
		echo '"><img src="img/button/edit_22x11.gif" width="22" height="11" border="0"></a>';
		echo '<img src="img/leer.gif" width="2" height="1">';

               // ERICH COPY TEMPLATE 7.6.2005
		echo '<a href="phpwcms.php?'.$edit_link.'&amp;c=1'; // c=1 -> do copy
		echo '" title="copy template"><img src="img/button/copy_11x11_0.gif" width="11" height="11" border="0"></a>';
		echo '<img src="img/leer.gif" width="2" height="1">';
                // ERICH COPY TEMPLATE END 7.6.2005

		echo '<a href="include/inc_act/act_frontendsetup.php?do=2|'.$row["template_id"].'" ';
		echo 'title="delete template: '.html_specialchars($row["template_name"]).'">';
		echo '<img src="img/button/del_11x11.gif" width="11" height="11" border="0"></a>';
		echo '<img src="img/leer.gif" width="2" height="1">'."</td>\n</tr>\n";
		$row_count++;
	}
	mysql_free_result($result);
} // end listing
		
?>
	<tr><td colspan="3" bgcolor="#92A1AF"><img src="img/leer.gif" alt="" width="1" height="1" /></td>
	</tr>
	<tr><td colspan="3"><img src="img/leer.gif" alt="" width="1" height="8" /></td>
	</tr>
	<tr><td colspan="3"><form action="phpwcms.php?do=admin&amp;p=11&amp;s=0" method="post">
	  <input type="submit" value="<?php echo $BL['be_admin_tmpl_add'] ?>" class="button10" title="<?php echo $BL['be_admin_tmpl_add'] ?>" />
	</form></td>
	</tr>
</table>
<?php

} else {

// should the edit template dialog
	$template["id"] = intval($_GET["s"]);

	$createcopy = isset($_GET["c"]) ? intval($_GET["c"]) : 0;
	
	if(isset($_POST["template_id"])) {
		
		
		$createcopy = empty($_POST["c"]) ? 0 : intval($_POST["c"]); // ERICH COPY TEMPLATE 08.06.2005
		
		// read the create or edit template form data
		$template["id"]			= intval($_POST["template_id"]);
		$template["default"]	= empty($_POST["template_setdefault"]) ? 0 : 1;
		$template["layout"]		= intval($_POST["template_layout"]);
		$template["name"]		= clean_slweg($_POST["template_name"], 150);
		if(empty($template["name"])) {
			$template["name"] = "template_".randpassword(3);
		}
		if(is_array($_POST["template_css"])) {
			$template["css"] = $_POST["template_css"];
		} else {
			$template["css"] = array();
		}
		$template["htmlhead"]	= slweg($_POST["template_htmlhead"]);
		$template["jsonload"]	= slweg($_POST["template_jsonload"]);		
		$template["headertext"]	= slweg($_POST["template_block_header"]);
		$template["maintext"]	= slweg($_POST["template_block_main"]);
		$template["footertext"]	= slweg($_POST["template_block_footer"]);
		$template["lefttext"]	= slweg($_POST["template_block_left"]);
		$template["righttext"]	= slweg($_POST["template_block_right"]);
		$template["errortext"]	= slweg($_POST["template_block_error"]);
		$template["feloginurl"]	= slweg($_POST["template_felogin_url"]);
		$template["overwrite"]	= clean_slweg($_POST["template_overwrite"]);
		$template['jslib']		= clean_slweg($_POST["template_jslib"]);
		$template['jslibload']	= empty($_POST["template_jslibload"]) ? 0 : 1;
		$template['frontendjs']	= empty($_POST["template_frontendjs"]) ? 0 : 1;
		$template['googleapi']	= empty($_POST["template_googleapi"]) ? 0 : 1;
		
		// now browse custom blocks if available
		if(!empty($_POST['customblock'])) {
		
			$template['customblock'] = clean_slweg($_POST["customblock"]);
			$temp_customblock = explode(',', $template['customblock']);
			foreach($temp_customblock as $value) {
			
				$template['customblock_'.$value] = slweg($_POST['template_customblock_'.$value]);
	
			}
		}

		if($template["id"] && empty($createcopy)) {
		// if ID <> 0 then get template info from database
			$sql =  "UPDATE ".DB_PREPEND."phpwcms_template SET ".
					"template_name='".aporeplace($template["name"])."', ".
					"template_default=".$template["default"].", ".
					"template_var='".aporeplace(serialize($template))."' ".
					"WHERE template_id=".$template["id"];	
		} else {
		// if ID = 0 then show create new template form
			$sql =  "INSERT INTO ".DB_PREPEND."phpwcms_template (".
					"template_name, template_default, template_var) VALUES ('".
					aporeplace($template["name"])."', ".$template["default"].", '".
					aporeplace(serialize($template))."')";
		}
		// update or insert data entry
		@mysql_query($sql, $db) or die("error while updating or inserting template datas");

		if(empty($template["id"]) || $createcopy == 1) {
			$template["id"] = mysql_insert_id($db);
		}

		//now proof for default template definition
		if($template["default"]) {
			mysql_query("UPDATE ".DB_PREPEND."phpwcms_template SET template_default=0 ".
						"WHERE template_id != ".$template["id"], $db);
		}
		update_cache();
		headerRedirect(PHPWCMS_URL."phpwcms.php?do=admin&p=11&s=".$template["id"]);
	}

	if($template["id"]) {
	// read the given template datas from db
		$sql = "SELECT * FROM ".DB_PREPEND."phpwcms_template WHERE template_id=".$template["id"]." LIMIT 1";
		if($result = mysql_query($sql, $db)) {
			if($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
				unset($template);
				$template = unserialize($row["template_var"]);
				$template["id"] = $row["template_id"];
				$template["default"] = $row["template_default"];
				// compatibility for older releases where only 
				// 1 css file could be stored per template
				if(is_string($template['css'])) {
					$template['css'] = array($template['css']);
				}
				if(empty($template['jslib'])) {
					$template['jslib'] = key($phpwcms['js_lib']);
				}
				if(empty($template['jslibload'])) {
					$template['jslibload'] = 0;
				}
				if(empty($template['frontendjs'])) {
					$template['frontendjs'] = 0;
				}
				if(!isset($template['googleapi'])) {
					$template['googleapi'] = 1;
				} elseif(empty($template['googleapi'])) {
					$template['googleapi'] = 0;
				}
				
			}
			mysql_free_result($result);
		}
	}

	// show form
?><script language="JavaScript" type="text/javascript">
<!--
function doPageLayoutChange() {
	var returnValue = confirm('<?php echo $BL['be_admin_template_jswarning'] ?>');
	if(returnValue) {
		document.blocks.submit();
		return true;
	} else {
		return false;
	}
}
//-->
</script><form action="phpwcms.php?do=admin&amp;p=11&amp;s=<?php echo $template["id"] ?>" method="post" name="blocks" target="_self" id="blocks">
  <table width="100%" border="0" cellpadding="0" cellspacing="0" summary="">
	
	<tr><td colspan="2" class="title"><?php echo (empty($createcopy) ? $BL['be_admin_tmpl_edit'] : $BL['be_admin_tmpl_copy']) ?>: <?php echo ($template["id"]) ? html_specialchars($template["name"]) : $BL['be_admin_tmpl_new']; ?>
	    <input type="hidden" name="c" value="<?php echo $createcopy; ?>" /></td></tr>
	<tr><td colspan="2"><img src="img/lines/l538_70.gif" alt="" width="538" height="1" /></td>
	</tr>
	<tr bgcolor="#E6EAED"><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="8" /></td>
	</tr>
	<tr bgcolor="#E6EAED">
		<td align="right" class="chatlist"><?php echo $BL['be_admin_tmpl_name'] ?>:&nbsp;</td>
		<td><table border="0" cellpadding="0" cellspacing="0" summary="">
		  <tr>
		    <td><?php
// ERICH COPY TEMPLATE 08.06.2005
if(empty($createcopy)) {
	echo '<input name="template_name" type="text" class="f11b width350" id="template_name" value="'.html_specialchars($template["name"]).'" size="50" maxlength="150">';
} else {    
	echo '<img src="img/symbole/achtung.gif" width="13" height="11" alt="" border="0" style="margin-right:2px;" /><input name="template_name" type="text" class="f11b width350" id="template_name" style="color:FF3300" value="'.html_specialchars($template["name"]).'_'.randpassword(2).'" size="50" maxlength="150">';
}
?></td>
		    <td>&nbsp;</td>
			<td><input name="template_setdefault" id="template_setdefault" type="checkbox" value="1" <?php is_checked(empty($createcopy) ? $template["default"] : 0, 1) ?> /></td>
		    <td class="v10"><label for="template_setdefault"><?php echo $BL['be_admin_tmpl_default'] ?></label></td>
	      </tr>
		  </table></td>
	</tr>
	<tr bgcolor="#E6EAED"><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="3" /></td></tr>
	<tr bgcolor="#E6EAED">
		<td align="right" class="chatlist"><?php echo $BL['be_admin_tmpl_layout'] ?>:&nbsp;</td>
		<td><?php
// get available page layout list
$jsOnChange = '';
$opt = "";
$sql = "SELECT * FROM ".DB_PREPEND."phpwcms_pagelayout ".
	   "WHERE pagelayout_trash=0 ORDER BY pagelayout_default DESC";
if($result = mysql_query($sql, $db) or die("error while listing pagelayouts")) {
	while($row = mysql_fetch_assoc($result)) {
		$opt .= '<option value="'.$row['pagelayout_id'].'"';
		if($row['pagelayout_id'] == $template["layout"]) {
			$opt .= ' selected';
			// try to get additional custom blocks from selected page layout
			$custom_blocks = unserialize($row['pagelayout_var']);
			$custom_blocks = explode(', ', trim($custom_blocks['layout_customblocks']));

			if(is_array($custom_blocks) && count($custom_blocks) && $custom_blocks[0] != '') {
				$jsOnChange = ' onChange="doPageLayoutChange();"';
			} else {
				$jsOnChange = '';
			}
		}
		$opt .= '>'.html_specialchars($row['pagelayout_name']).'</option>'."\n";	
	}
	mysql_free_result($result);
}

if($opt) {
	echo '<select name="template_layout" class="f11b width350" id="template_layout"'.$jsOnChange.'>'.LF;
	echo $opt;
	echo '</select>';
} else {
	echo $BL['be_admin_tmpl_nolayout'].' (<a href="phpwcms.php?do=admin&p=8&s=0">'.$BL['be_admin_page_add'].'</a>)';
}

?></td>
	</tr>

	
	<tr bgcolor="#E6EAED"><td colspan="2" class="rowspacer7x7"><img src="img/leer.gif" alt="" width="1" height="1" /></td></tr>

	<tr bgcolor="#E6EAED">
		<td>&nbsp;</td>
		<td class="chatlist tdbottom3"><?php echo $BL['be_overwrite_default'] ?>
		<br  /><strong>config/phpwcms/conf.template_default.inc.php</strong></td>	
	</tr>


	<tr bgcolor="#E6EAED">
		<td align="right" class="chatlist" style="padding-left:2px"><?php echo $BL['be_settings'] ?>:&nbsp;</td>
		<td><select name="template_overwrite" id="template_overwrite" class="f11b">
			<option value="" style="font-weight:normal;font-style:italic;"><?php echo $BL['be_admin_tmpl_default']; ?></option>
<?php
	
// templates for frontend login
$tmpllist = get_tmpl_files(PHPWCMS_TEMPLATE.'inc_settings/template_default', 'php');
if(is_array($tmpllist) && count($tmpllist)) {
	foreach($tmpllist as $val) {
		$selected_val = (isset($template["overwrite"]) && $val == $template["overwrite"]) ? ' selected="selected"' : '';
		$val = html_specialchars($val);
		echo '	<option value="' . $val . '"' . $selected_val . '>' . $val . '</option>' . LF;
	}
}

?>				  
		</select></td>
	</tr>


	<tr bgcolor="#E6EAED"><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="8" /></td></tr>
	<tr><td colspan="2"><img src="img/lines/l538_70.gif" alt="" width="538" height="1" /></td></tr>
	<tr bgcolor="#F3F5F8"><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="8" /></td></tr>
	
	
	<tr bgcolor="#F3F5F8">
		<td align="right" class="chatlist" valign="top"><?php echo $BL['be_admin_tmpl_css'] ?>:<img src="img/leer.gif" alt="" width="4" height="14" /></td>
		<td><table border="0" cellpadding="0" cellspacing="0" summary="">
			<tr>
			<td valign="top"><select name="template_css[]" size="6" multiple="multiple" class="code" id="template_css">
<?php

$unselected_css = array();

// get css file list
if(is_dir(PHPWCMS_TEMPLATE."inc_css")) {
	
	$css_handle 		= opendir(PHPWCMS_TEMPLATE."inc_css" );

	// browse template CSS diretory and list all available CSS files
	while($css_file = readdir( $css_handle )) {
		 
		if( $css_file != "." && $css_file != ".." && preg_match('/^[a-z0-9\. \-_]+\.css$/i', $css_file) ) {

			$unselected_css[$css_file] = $css_file;
				
		}
	}
	closedir( $css_handle );
}

// now run the css information
foreach($template["css"] as $value) {
	if(isset($unselected_css[$value])) {
		$css_file = html_entities($value);
		echo '		<option value="'.$css_file.'" selected="selected" style="font-weight: bold;">'.$css_file.'&nbsp;&nbsp;</option>'.LF;
		unset($unselected_css[$value]);
	}
}
foreach($unselected_css as $value) {
	$css_file = html_entities($value);
	echo '		<option value="'.$css_file.'">'.$css_file.'&nbsp;&nbsp;</option>'.LF;
}

?>
		    </select></td>
		  
		  <td valign="top" align="center">
		<img src="img/button/list_pos_up.gif" alt="" width="15" height="15" border="0" onclick="moveOptionUp(document.blocks.template_css);" /><br />
		<img src="img/leer.gif" width="23" height="3" alt="" /><br />
		<img src="img/button/list_pos_down.gif" alt="" width="15" height="15" border="0" onclick="moveOptionDown(document.blocks.template_css);" /></td>
		  <td valign="top">&nbsp;</td>
		  
		  </tr>
		  </table></td>
	</tr>

	<tr bgcolor="#F3F5F8"><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="4" /></td></tr>
	
	<tr bgcolor="#F3F5F8">
		<td align="right" valign="top" class="chatlist tdtop4"><?php echo $BL['be_admin_tmpl_head'] ?>:&nbsp;<br />&lt;head&gt; &nbsp;</td>
		<td><textarea name="template_htmlhead" cols="35" rows="5" class="code width440" id="template_htmlhead"><?php echo html_entities($template["htmlhead"]); ?></textarea></td>
	</tr>
	<tr bgcolor="#F3F5F8"><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="3" /></td></tr>
	
	
	<tr bgcolor="#F3F5F8">
      <td align="right" class="chatlist"><?php echo $BL['js_lib'] ?>:&nbsp;</td>
      <td><table cellpadding="0" cellspacing="0" border="0" summary="">
	  
	  	<tr>
	  		<td><select name="template_jslib" id="template_jslib" class="f11b">
<?php
foreach($phpwcms['js_lib'] as $key => $value) {
	
	echo '		<option value="' . $key . '"';
	is_selected($template['jslib'], $key);
	echo '>' . html_specialchars($value) . '</option>' . LF;

}
?>
			</select></td>
			<td>&nbsp;</td>
			<td><input type="checkbox" name="template_jslibload" id="template_jslibload" value="1"<?php is_checked($template['jslibload'], 1); ?> /></td>
			<td class="v10"><label for="template_jslibload"><?php echo $BL['js_lib_alwaysload'] ?></label></td>
			<td>&nbsp;&nbsp;</td>
			<td><input type="checkbox" name="template_googleapi" id="template_googleapi" value="1"<?php is_checked($template['googleapi'], 1); ?> /></td>
			<td class="v10"><label for="template_googleapi"><?php echo $BL['googleapi_load'] ?></label></td>
		</tr>
	</table></td>
	</tr>
	
	<tr bgcolor="#F3F5F8"><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="3" /></td></tr>
	
	<tr bgcolor="#F3F5F8">
      <td align="right" class="chatlist">&nbsp;</td>
      <td><table cellpadding="0" cellspacing="0" border="0" summary="">
	  
	  	<tr>
			<td><input type="checkbox" name="template_frontendjs" id="template_frontendjs" value="1"<?php is_checked($template['frontendjs'], 1); ?> /></td>
			<td class="v10"><label for="template_frontendjs"><?php echo $BL['frontendjs_load'] ?></label></td>
		</tr>
	</table></td>
	</tr>	
	
	
	<tr bgcolor="#F3F5F8"><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="3" /></td></tr>
	
	<tr bgcolor="#F3F5F8">
      <td align="right" class="chatlist"><?php echo $BL['be_admin_tmpl_js'] ?>:&nbsp;</td>
      <td><input name="template_jsonload" type="text" class="code width440" id="template_jsonload" value="<?php echo html_entities($template["jsonload"]) ?>" size="50" /></td>
	</tr>
	
	
	<tr bgcolor="#F3F5F8"><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="3" /></td></tr>
	
	
	<tr bgcolor="#F3F5F8">
      <td align="right" class="chatlist" nowrap="nowrap">&nbsp;<?php echo $BL['be_fe_login_url'] ?>:&nbsp;</td>
      <td><input name="template_felogin_url" type="text" class="code width440" id="template_felogin_url" value="<?php echo empty($template["feloginurl"]) ? '' : html_entities($template["feloginurl"]) ?>" size="50" /></td>
	</tr>
	<tr bgcolor="#F3F5F8"><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="8" /></td>
	</tr>
	<tr><td colspan="2"><img src="img/lines/l538_70.gif" alt="" width="538" height="1" /></td></tr>
	
	<tr>
		<td>&nbsp;</td>
		<td style="padding:7px 0 7px 0">
			<input name="Submit" type="submit" class="button10" value="<?php echo $BL['be_admin_tmpl_button'] ?>" />
			&nbsp;&nbsp;
			<input type="button" class="button10" value="<?php echo $BL['be_admin_struct_close'] ?>" onclick="location.href='phpwcms.php?do=admin&amp;p=11';" />
		</td>
	</tr>
	<tr><td colspan="2"><img src="img/lines/l538_70.gif" alt="" width="538" height="1" /></td>
	</tr>
	<tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="10" /></td>
	</tr>
	<tr>
		<td align="right" valign="top" class="chatlist tdtop4"><?php echo $BL['be_admin_page_header'] ?>:&nbsp;</td>
		<td><textarea name="template_block_header" cols="35" rows="8" class="code width440" id="template_block_header"><?php echo html_entities($template["headertext"]); ?></textarea></td>
	</tr>
	<tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="3" /></td>
	</tr>
	<tr>
		<td align="right" valign="top" class="chatlist tdtop4"><?php echo $BL['be_admin_page_main'] ?>:&nbsp;</td>
		<td><textarea name="template_block_main" cols="35" rows="20" class="code width440" id="template_block_main"><?php echo html_entities($template["maintext"]); ?></textarea></td>
	</tr>
	<tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="3" /></td>
	</tr>
	<tr>
		<td align="right" valign="top" class="chatlist tdtop4"><?php echo $BL['be_admin_page_footer'] ?>:&nbsp;</td>
		<td><textarea name="template_block_footer" cols="35" rows="8" class="code width440" id="template_block_footer"><?php echo html_entities($template["footertext"]); ?></textarea></td>
	</tr>
	<tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="3" /></td>
	</tr>
	<tr>
		<td align="right" valign="top" class="chatlist tdtop4"><?php echo $BL['be_admin_page_left'] ?>:&nbsp;</td>
		<td><textarea name="template_block_left" cols="35" rows="8" class="code" id="template_block_left" style="width:440px"><?php echo html_entities($template["lefttext"]); ?></textarea></td>
	</tr>
	<tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="3" /></td>
	</tr>
	<tr>
		<td align="right" valign="top" class="chatlist tdtop4"><?php echo $BL['be_admin_page_right'] ?>:&nbsp;</td>
		<td><textarea name="template_block_right" cols="35" rows="8" class="code width440" id="template_block_right"><?php echo html_entities($template["righttext"]); ?></textarea></td>
	</tr>
	<tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="3" /></td>
	</tr>
	
<?php	
if(!empty($jsOnChange))  {

	echo '<tr><td colspan="2"><img src="img/leer.gif" width="1" height="5" alt="" /></td></tr>';
	echo '<tr><td colspan="2"><img src="img/lines/l538_70.gif" width="538" height="1" alt="" /></td></tr>';	
	echo '<tr bgcolor="#F3F5F8"><td colspan="2"><img src="img/leer.gif" width="1" height="8" alt="" />';
	echo '<input type="hidden" name="customblock" value="'.html_specialchars(implode(',', $custom_blocks)).'" />';
	echo "</td></tr>\n";
	// list custom blocks	
	foreach($custom_blocks as $value) {
	
		$custom_block = html_specialchars($value);
		
		echo '<tr bgcolor="#F3F5F8"><td><img src="img/leer.gif" width="1" height="14" alt="" /></td>';
		echo '<td class="chatlist" valign="top">'.$custom_block." {".$custom_block."}</td>\n</tr>\n";
		echo '<tr bgcolor="#F3F5F8"><td>&nbsp;</td>';
		echo '<td><textarea name="template_customblock_'.$custom_block;
		echo '" cols="35" rows="8" class="code width440">';
		echo isset($template['customblock_'.$value]) ? html_entities($template['customblock_'.$value]) : '';
		echo "</textarea></td>\n</tr>\n";
		echo '<tr bgcolor="#F3F5F8"><td colspan="2"><img src="img/leer.gif" width="1" height="3" alt="" /></td></tr>'."\n";

	}
	
	echo '<tr bgcolor="#F3F5F8"><td colspan="2"><img src="img/leer.gif" width="1" height="5" alt="" /></td></tr>
	<tr><td colspan="2"><img src="img/lines/l538_70.gif" width="538" height="1" alt="" /></td></tr>
	<tr><td colspan="2"><img src="img/leer.gif" width="1" height="8" alt="" /></td></tr>';
	
} 
?>
	<tr>
      <td align="right" valign="top" class="chatlist tdtop4"><?php echo $BL['be_admin_tmpl_error'] ?>:&nbsp;</td>
      <td><textarea name="template_block_error" cols="35" rows="5" class="code width440" id="template_block_error"><?php echo html_entities($template["errortext"]); ?></textarea></td>
	</tr>
	
	<tr><td colspan="2" class="rowspacer7x7"><img src="img/leer.gif" alt="" width="1" height="1" /></td></tr>
	
	<tr>
		<td>&nbsp;<input name="template_id" type="hidden" value="<?php echo $template["id"] ?>" /></td>
		<td style="padding-bottom:10px;">
		<input name="Submit" type="submit" class="button10" value="<?php echo $BL['be_admin_tmpl_button'] ?>" />
		&nbsp;&nbsp;
		<input type="button" class="button10" value="<?php echo $BL['be_admin_struct_close'] ?>" onclick="location.href='phpwcms.php?do=admin&amp;p=11';" /></td>
	</tr>
	
</table></form><?php	
}
?>