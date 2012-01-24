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


// Tabs

initMootools();
$BE['HEADER']['tabs.css']	= '	<link href="include/inc_css/tabs.css" rel="stylesheet" type="text/css" />';

// set default values
if(empty($content['tabs']) || !is_array($content['tabs'])) {
	$content['tabs'] = array();
}
$content['tabwysiwygoff'] = empty($content['tabs']['tabwysiwygoff']) ? 0 : 1;
unset($content['tabs']['tabwysiwygoff']);

// check which WYSIWYG editor to load
// only FCKeditor is supported here
// or WYSIWYG disabled
if(!empty($_SESSION["WYSIWYG_EDITOR"]) && !$content['tabwysiwygoff']) {

	$BE['HEADER']['fckeditor.js']	= '	<script type="text/javascript" src="include/inc_ext/fckeditor/fckeditor.js"></script>';
	$content['wysiwyg']				= true;
	
	// check if FCKeditor is enabled
	$content['wysiwyg_toolbar']		= $_SESSION["WYSIWYG_EDITOR"] == 2 ? $_SESSION['WYSIWYG_TEMPLATE'] : 'phpwcms_basic';

} else {

	$content['wysiwyg']				= false;
	$content['wysiwyg_toolbar']		= '';

}

?>

<tr><td colspan="2" class="rowspacer0x7"><img src="img/leer.gif" alt="" width="1" height="1" /></td></tr>

<tr>
	<td align="right" class="chatlist"><?php echo $BL['be_admin_struct_template']; ?>:&nbsp;</td>
	<td><table border="0" cellpadding="0" cellspacing="0" summary="">
		<tr>
			<td><select name="template" id="template" class="f11b width150">
<?php
	
	echo '<option value="">'.$BL['be_admin_tmpl_default'].'</option>'.LF;

	$tmpllist = get_tmpl_files(PHPWCMS_TEMPLATE.'inc_cntpart/tabs');
	if(is_array($tmpllist) && count($tmpllist)) {
		foreach($tmpllist as $val) {
			$selected_val = (isset($content["tabs_template"]) && $val == $content["tabs_template"]) ? ' selected="selected"' : '';
			$val = html_specialchars($val);
			echo '	<option value="' . $val . '"' . $selected_val . '>' . $val . '</option>' . LF;
		}
	}

?>				  
			</select></td>
		
		</tr>
		
	</table></td>		
		
</tr>

<tr><td colspan="2" class="rowspacer7x7"><img src="img/leer.gif" alt="" width="1" height="1" /></td></tr>

<tr>
	<td id="col_1_width">&nbsp;</td>
	<td><table cellpadding="0" cellspacing="0" border="0" summary="">
    	<tr>
    		<td class="tdbottom6"><button class="btn_add_tab" id="btn_add_tab">
				<span><?php echo $BL['be_tab_add'] ?></span>
			</button></td>
            <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
            <td><input type="checkbox" name="tabwysiwygoff" id="tabwysiwygoff" value="1"<?php is_checked(1, $content['tabwysiwygoff']) ?> /></td>
            <td class="chatlist"><label for="tabwysiwygoff">&nbsp;<?php echo $BL['be_cnt_no_wysiwyg_editor'] ?></label></td>
		</tr>        
    </table></td>
</tr>

<tr>
	<td colspan="2"><ul id="tabs">

<?php

	// Sort/Up Down Title
	$sort_up_down = $BL['be_func_struct_sort_up'] . ' / '. $BL['be_func_struct_sort_down'];

	foreach($content['tabs'] as $key => $value) {

?>

			<li id="tab<?php echo $key ?>" class="tab">
			<table cellpadding="0" cellspacing="0" border="0" summary="">
			
				<tr>
					<td class="chatlist col1w" align="right"><em class="handle" title="<?php echo $sort_up_down; ?>">&nbsp;</em><?php echo $BL['be_tab_name'] ?>:&nbsp;</td>
					<td class="tdbottom2"><input type="text" name="tabtitle[<?php echo $key ?>]" id="tabtitle<?php echo $key ?>" value="<?php echo html_specialchars($value['tabtitle']) ?>" class="f11b width400" /></td>			
					<td><a href="#" onclick="return deleteTab('tab<?php echo $key ?>');"><img src="img/famfamfam/tab_delete.gif" alt="" border="" /></a></td>
				</tr>				
				<tr>
					<td class="chatlist col1w" align="right"><?php echo $BL['be_headline'] ?>:&nbsp;</td>
					<td colspan="2"><input type="text" name="tabheadline[<?php echo $key ?>]" id="tabheadline<?php echo $key ?>" value="<?php echo html_specialchars($value['tabheadline']) ?>" class="v11 width400" /></td>			
				</tr>
				<tr>
					<td colspan="3" class="tdtop5"><textarea name="tabtext[<?php echo $key ?>]" id="tabtext<?php echo $key ?>" rows="10" class="v12" style="width:536px;height:150px;"><?php echo html_specialchars($value['tabtext']) ?></textarea></td>
				</tr>
			
			</table>
			</li>	

<?php

	}
?>	

	</ul></td>
</tr>

<tr>
	<td colspan="2" class="rowspacer0x7">
	<script type="text/javascript">
	<!--

	var wysiwyg	= <?php echo $content['wysiwyg'] ? 'true' : 'false'; ?>;
	var fckbase	= '<?php echo PHPWCMS_URL.'include/inc_ext/fckeditor/'; ?>';
	var toolbar	= '<?php echo $content['wysiwyg_toolbar']; ?>';
	var entries = 0;
	var FCK		= new Array();
	
	window.addEvent('domready', function() {

		var head	= document.getElementsByTagName('head');
		//new Element('style', {'type': 'text/css'} ).setText('td.col1w {width: ' + col1w['width'] + 'px;}').injectInside( head[0] );
		
		var entries = $('tabs').getChildren().length;
		
		$('btn_add_tab').addEvent('click', function(event) {
			event = new Event(event).stop();
			
			var entry = '<table cellpadding="0" cellspacing="0" border="0" summary="">';
			entry    +=	'<tr><td class="chatlist col1w" align="right"><?php echo $BL['be_tab_name'] ?>:&nbsp;<'+'/td>';
			entry    +=	'<td class="tdbottom2"><input type="text" name="tabtitle[' + entries + ']" id="tabtitle' + entries + '" value="" class="f11b width400" /'+'><'+'/td>';
			entry    +=	'<td><a href="#" onclick="return deleteTab(\'tab' + entries + '\');"><img src="img/famfamfam/tab_delete.gif" alt="" border="" /><'+'/a><'+'/td><'+'/tr>';
			entry    +=	'<tr><td class="chatlist col1w" align="right"><?php echo $BL['be_headline'] ?>:&nbsp;<'+'/td>';
			entry    +=	'<td colspan="2"><input type="text" name="tabheadline[' + entries + ']" id="tabheadline' + entries + '" value="" class="v11 width400" /'+'><'+'/td><'+'/tr>';
			entry    +=	'<tr><td colspan="3" class="tdtop5"><textarea name="tabtext[' + entries + ']" id="tabtext' + entries + '" rows="10" class="v12" ';
			entry    +=	'style="width:536px;height:150px;"><'+'/textarea><'+'/td><'+'/tr><'+'/table>';
			
			var tab = new Element('li', {'id': 'tab'+entries, 'class': 'tab nomove'} ).setHTML( entry ).injectInside( $('tabs') );
						
			if(wysiwyg) {
				EnableFCK(entries);
			}
			
			//makeSortable();
	
			window.scrollTo(0, tab.getCoordinates()['top']);
			
			entries++;
			
		});
		
		if(wysiwyg && entries > 0) {

			for(x = 0; x < entries; x++) {
				EnableFCK(x);
			}
		}
		
		makeSortable();
		
		/*
		var col1w	= $('col_1_width').getCoordinates();
		
		alert(col1w['width']);
		$$('td.col1w').each( function(el) {
			el.setStyle('width', col1w['width'] + 'px');
		});
		*/
	
	});
	
	function makeSortable() {
		var s = new Sortables( $('tabs'), { handles: 'em' } );
	}
	
	function EnableFCK(x) {

		if( $('tabtext'+x) ) {
	
			FCK[x] = new FCKeditor('tabtext'+x);
					
			FCK[x].BasePath = fckbase;
			FCK[x].Config['CustomConfigurationsPath'] = fckbase+'fckeditor_config.js.php';
			FCK[x].Config['StartupFocus'] = false;
			FCK[x].Width = 536;
			FCK[x].Height = 150;
			FCK[x].ToolbarSet = toolbar;	
								
			FCK[x].ReplaceTextarea() ;
		}
	
	}
	
	function deleteTab(e) {
		if(confirm('<?php echo $BL['be_tab_delete_js'] ?>')) {
			$(e).remove();
		}
		return false;
	}

	
	//-->
	</script>
	</td>
</tr>
