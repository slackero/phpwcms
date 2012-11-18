<?php
/**
 * phpwcms content management system
 *
 * @author Oliver Georgi <oliver@phpwcms.de>
 * @copyright Copyright (c) 2002-2012, Oliver Georgi
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


// Tabs

initMootools();

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
	$content['wysiwyg_toolbar']		= $_SESSION["WYSIWYG_EDITOR"] == 2 ? $_SESSION['WYSIWYG_TEMPLATE'] : 'Basic';

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
	<td colspan="2" class="rowspacer0x7"><script type="text/javascript">

	var entries = 0, FCK = [];
	
	window.addEvent('domready', function() {

		entries = $('tabs').getChildren().length;
		
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

<?php if($content['wysiwyg']): ?>			EnableFCK(entries);<?php endif; ?>
			
			window.scrollTo(0, tab.getCoordinates()['top']);
			
			entries++;			
		});

<?php if($content['wysiwyg']): ?>		
		if(entries > 0) {
			for(x = 0; x < entries; x++) {
				EnableFCK(x);
			}
		}
<?php endif; ?>
		
		var s = new Sortables( $('tabs'), { handles: 'em' } );
	});

<?php if($content['wysiwyg']): ?>
	function EnableFCK(x) {

		if( $('tabtext'+x) ) {
	
			FCK[x] = new FCKeditor('tabtext'+x);
					
			FCK[x].BasePath = '<?php echo PHPWCMS_URL.'include/inc_ext/fckeditor/'; ?>';
			FCK[x].Config['CustomConfigurationsPath'] = '<?php echo PHPWCMS_URL.'include/inc_ext/fckeditor/'; ?>fckeditor_config.js.php';
			FCK[x].Config['StartupFocus'] = false;
			FCK[x].Width = 536;
			FCK[x].Height = 150;
			FCK[x].ToolbarSet = '<?php echo $content['wysiwyg_toolbar']; ?>';	
								
			FCK[x].ReplaceTextarea() ;
		}
	
	}
<?php endif; ?>
	
	function deleteTab(e) {
		if(confirm('<?php echo $BL['be_tab_delete_js'] ?>')) {
			$(e).remove();
		}
		return false;
	}

	</script></td>
</tr>