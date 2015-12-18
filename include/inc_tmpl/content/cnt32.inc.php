<?php
/**
 * phpwcms content management system
 *
 * @author Oliver Georgi <og@phpwcms.org>
 * @copyright Copyright (c) 2002-2015, Oliver Georgi
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
	$BE['HEADER']['ckeditor.js'] = getJavaScriptSourceLink('include/inc_ext/ckeditor/ckeditor.js');
	$content['wysiwyg'] = true;
} else {
	$content['wysiwyg'] = false;
}

?>

<tr><td colspan="2" class="rowspacer0x7"><img src="img/leer.gif" alt="" width="1" height="1" /></td></tr>

<tr>
	<td align="right" class="chatlist"><?php echo $BL['be_admin_struct_template']; ?>:&nbsp;</td>
	<td><table border="0" cellpadding="0" cellspacing="0" summary="">
		<tr>
			<td><select name="template" id="template" class="width150">
<?php

	echo '<option value="">'.$BL['be_admin_tmpl_default'].'</option>'.LF;

	$tmpllist = get_tmpl_files(PHPWCMS_TEMPLATE.'inc_cntpart/tabs');
	if(is_array($tmpllist) && count($tmpllist)) {
		foreach($tmpllist as $val) {
			$selected_val = (isset($content["tabs_template"]) && $val == $content["tabs_template"]) ? ' selected="selected"' : '';
			$val = html($val);
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
	<td colspan="2">

		<ul id="tabs">
<?php

	// Sort/Up Down Title
	$sort_up_down = $BL['be_func_struct_sort_up'] . ' / '. $BL['be_func_struct_sort_down'];
	if(isset($template_default['settings']['tabs_custom_fields']) && is_array($template_default['settings']['tabs_custom_fields']) && count($template_default['settings']['tabs_custom_fields'])) {
		$custom_tab_fields = array_keys($template_default['settings']['tabs_custom_fields']);
	} else {
		$custom_tab_fields = array();
	}

	$value['custom_field_items'] = $custom_tab_fields;

	if(!empty($content['tabs'])):
		foreach($content['tabs'] as $key => $value):

			if(isset($value['custom_fields']) && is_array($value['custom_fields']) && count($value['custom_fields'])) {

				if(count($custom_tab_fields)) {
					$value['custom_field_items'] = array_unique( array_merge($custom_tab_fields, array_keys($value['custom_fields'])) );
				} else {
					$value['custom_field_items'] = array_keys($value['custom_fields']);
				}

			} else {

				$value['custom_field_items'] = $custom_tab_fields;

			}
?>
			<li id="tab<?php echo $key ?>" class="tab tab-collapsed">
				<table class="tab-container" cellpadding="0" cellspacing="0">
					<tr>
						<td class="chatlist col1w" align="right" nowrap="nowrap">
							<a href="#" onclick="return toggleTab('tab<?php echo $key ?>');" class="toggle-item" title="<?php echo $BL['be_tab_toggle']; ?>"></a>
							<?php echo $BL['be_tab_name']; ?>:&nbsp;</td>
						<td class="tdbottom2"><input type="text" name="tabtitle[<?php echo $key ?>]" id="tabtitle<?php echo $key ?>" value="<?php echo html($value['tabtitle']); ?>" class="f11b width400" /></td>
						<td nowrap="nowrap" style="padding-right:5px;">
							<em class="handle" title="<?php echo $sort_up_down; ?>"></em>
							<a href="#" onclick="return deleteTab('tab<?php echo $key ?>');" class="tab-delete"><img src="img/famfamfam/tab_delete.gif" alt="" border="" /></a>
						</td>
					</tr>
					<tr class="tab-collapsable-row">
						<td class="chatlist col1w" align="right"><?php echo $BL['be_headline'] ?>:&nbsp;</td>
						<td colspan="2" class="tdbottom2"><input type="text" name="tabheadline[<?php echo $key ?>]" id="tabheadline<?php echo $key ?>" value="<?php echo html($value['tabheadline']); ?>" class="v11 width400" /></td>
					</tr>
					<tr class="tab-collapsable-row">
						<td class="chatlist col1w" align="right"><?php echo $BL['be_admin_page_link'] ?>:&nbsp;</td>
						<td colspan="2"><input type="text" name="tablink[<?php echo $key ?>]" id="tablink<?php echo $key ?>" value="<?php echo (isset($value['tablink']) ? html($value['tablink']) : ''); ?>" class="v11 width400" /></td>
					</tr>
					<tr class="tab-collapsable-row">
						<td colspan="3" class="tdtop5 tdbottom5"><textarea class="width540" name="tabtext[<?php echo $key ?>]" id="tabtext<?php echo $key ?>" rows="10"><?php echo html($value['tabtext']); ?></textarea></td>
					</tr>
<?php
			if($value['custom_field_items']):
				foreach($value['custom_field_items'] as $custom_field_key => $custom_field):
?>
					<tr class="tab-collapsable-row">
						<td class="chatlist col1w" align="right" nowrap="nowrap">&nbsp;&nbsp;<?php
							if(isset($template_default['settings']['tabs_custom_fields'][$custom_field])) {
								echo html($template_default['settings']['tabs_custom_fields'][$custom_field]);
							} else {
								echo $BL['be_custom_textfield'].' #'.($custom_field_key+1);
							}

						?>:&nbsp;</td>
						<td colspan="2" class="tdtop2"><input type="text" name="customfield[<?php echo $key; ?>][<?php echo $custom_field; ?>]" value="<?php
							if(isset($value['custom_fields'][$custom_field])) {
								echo html($value['custom_fields'][$custom_field]);
							}
						?>" class="v11 width400" /></td>
					</tr>
<?php
				endforeach;
			endif;
?>
				</table>
			</li>
<?php
		endforeach;
	endif;
?>
		</ul>
	</td>
</tr>

<tr>
	<td colspan="2" class="rowspacer0x7"><script type="text/javascript">

	var entries = 0;

	window.addEvent('domready', function() {

		entries = $('tabs').getChildren().length;

		$('btn_add_tab').addEvent('click', function(event) {
			event = new Event(event).stop();

			var entry = '<table class="tab-container" cellpadding="0" cellspacing="0">';
			entry    +=	'<tr><td class="chatlist col1w" align="right"> ';
			entry    +=	'<a href="#" onclick="return toggleTab(\'tab' + entries + '\');" class="toggle-item" title="<?php echo $BL['be_tab_toggle']; ?>"><'+'/a> ';
			entry    +=	'<?php echo $BL['be_tab_name'] ?>:&nbsp;<'+'/td>';
			entry    +=	'<td class="tdbottom2"><input type="text" name="tabtitle[' + entries + ']" id="tabtitle' + entries + '" value="" class="f11b width400" /'+'><'+'/td>';
			entry    +=	'<td style="padding-right:5px;"><a href="#" onclick="return deleteTab(\'tab' + entries + '\');" class="tab-delete"><img src="img/famfamfam/tab_delete.gif" alt="" border="" /><'+'/a> <'+'/td><'+'/tr>';
			entry    +=	'<tr class="tab-collapsable-row"><td class="chatlist col1w" align="right"><?php echo $BL['be_headline'] ?>:&nbsp;<'+'/td>';
			entry    +=	'<td colspan="2" class="tdbottom2"><input type="text" name="tabheadline[' + entries + ']" id="tabheadline' + entries + '" value="" class="v11 width400" /'+'><'+'/td><'+'/tr>';
			entry    +=	'<tr class="tab-collapsable-row"><td class="chatlist col1w" align="right"><?php echo $BL['be_admin_page_link'] ?>:&nbsp;<'+'/td>';
			entry    +=	'<td colspan="2"><input type="text" name="tablink[' + entries + ']" id="tablink' + entries + '" value="" class="v11 width400" /'+'><'+'/td><'+'/tr>';
			entry    +=	'<tr class="tab-collapsable-row"><td colspan="3" class="tdtop5 tdbottom10"><textarea name="tabtext[' + entries + ']" id="tabtext' + entries + '" rows="10" class="width540">';
			entry    +=	'<'+'/textarea><'+'/td><'+'/tr>';
<?php
			if(!empty($value['custom_field_items'])):
				foreach($value['custom_field_items'] as $custom_field_key => $custom_field):
?>
			entry    +=	'<tr class="tab-collapsable-row">';
			entry    +=	'<td class="chatlist col1w" align="right" nowrap="nowrap">&nbsp;&nbsp;<?php
							if(isset($template_default['settings']['tabs_custom_fields'][$custom_field])) {
								echo html($template_default['settings']['tabs_custom_fields'][$custom_field]);
							} else {
								echo $BL['be_custom_textfield'].' #'.($custom_field_key+1);
							}
						?>:&nbsp;<'+'/td>';
			entry    +=	'<td colspan="2" class="tdbottom2"><input type="text" name="customfield[' + entries + '][<?php echo $custom_field; ?>]" value="" class="v11 width400" /><'+'/td><'+'/tr>';
<?php
				endforeach;
			endif;
?>
			entry    += '<'+'/table>';

			var tab = new Element('li', {
				'id': 'tab'+entries,
				'class': 'tab nomove'
			}).setHTML(entry).injectInside($('tabs'));

<?php if($content['wysiwyg']): ?>
			EnableCKEditor(entries);<?php endif; ?>
			window.scrollTo(0, tab.getCoordinates()['top']);
			entries++;
		});

<?php if($content['wysiwyg']): ?>
		if(entries > 0) {
			for(x = 0; x < entries; x++) {
				EnableCKEditor(x);
			}
		}
<?php endif; ?>

		var s = new Sortables( $('tabs'), { handles: 'em' } );
	});

<?php if($content['wysiwyg']):

	// CKEditor Tabs configuration
	$content['ckconfig'] = array();

	if(isset($_SESSION["wcs_user_lang"])) {
		$content['ckconfig'][] = "language: '" . $_SESSION["wcs_user_lang"] ."'";
	}
	if(is_file(PHPWCMS_TEMPLATE.'config/ckeditor/ckeditor.config-tabs.js')) {
		$content['ckconfig'][] = 'customConfig: "' . PHPWCMS_URL.TEMPLATE_PATH . 'config/ckeditor/ckeditor.config-tabs.js"';
	} else {
		$content['ckconfig'][] = "toolbar: [
			{name: 'tools', items: ['Maximize', '-', 'Source', '-', 'Undo', 'Redo', '-', 'Paste', 'PasteText', 'PasteFromWord', '-', 'Find', '-', 'ShowBlocks']},
			{name: 'links', items: ['Link', 'Unlink', 'Anchor']},
			{name: 'colors', items: ['TextColor', 'BGColor']},
			{name: 'basicstyles', groups: ['basicstyles', 'cleanup'], items: ['Bold', 'Italic', 'Underline', 'Strike', 'Subscript', 'Superscript', '-', 'RemoveFormat']},
			{name: 'paragraph', groups: ['align', 'list', 'indent', 'blocks'], items: ['JustifyLeft', 'JustifyCenter', 'JustifyRight', 'JustifyBlock', '-', 'BulletedList', 'NumberedList', '-', 'Outdent', 'Indent', '-', 'Blockquote', 'CreateDiv']},
			{name: 'insert', items: ['Image', 'Table', 'HorizontalRule', 'Iframe', 'SpecialChar']},
			{name: 'styles', items: ['Styles', 'Format', 'Font']},
			{name: 'about', items: ['About']}
		]";

		$content['ckconfig'][] = 'width: 538';
		$content['ckconfig'][] = 'height: 150';
		$content['ckconfig'][] = 'toolbarCanCollapse: true';
		$content['ckconfig'][] = 'toolbarStartupExpanded: false';
		$content['ckconfig'][] = 'forcePasteAsPlainText: true';
		$content['ckconfig'][] = 'pasteFromWordRemoveFontStyles: true';
		$content['ckconfig'][] = 'pasteFromWordRemoveStyles: true';
		$content['ckconfig'][] = 'pasteFromWordPromptCleanup: true';
	}
	if(!empty($GLOBALS['phpwcms']['FCK_FileBrowser'])) {
		$content['ckconfig'][] = 'filebrowserBrowseUrl: "'.PHPWCMS_URL.'filebrowser.php?opt=16"';
		$content['ckconfig'][] = 'filebrowserImageBrowseUrl: "'.PHPWCMS_URL.'filebrowser.php?opt=17"';
		$content['ckconfig'][] = 'filebrowserWindowWidth: 640';
		$content['ckconfig'][] = 'filebrowserWindowHeight: 480';
	}

	$content['ckconfig'] = ', {' . implode(',', $content['ckconfig']) . '}';

?>
	function EnableCKEditor(x) {
		if( $('tabtext'+x) ) {
			CKEDITOR.replace('tabtext'+x<?php echo $content['ckconfig'] ?>);
		}
	}
<?php endif; ?>

	function deleteTab(e) {
		if(confirm('<?php echo $BL['be_tab_delete_js'] ?>')) {
			$(e).remove();
		}
		return false;
	}
	function toggleTab(e) {
		if($(e).hasClass('tab-collapsed')) {
			$(e).removeClass('tab-collapsed');
		} else {
			$(e).addClass('tab-collapsed');
		};
		return false;
	}

	</script></td>
</tr>