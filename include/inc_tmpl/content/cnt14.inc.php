<?php
/**
 * phpwcms content management system
 *
 * @author Oliver Georgi <oliver@phpwcms.de>
 * @copyright Copyright (c) 2002-2014, Oliver Georgi
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


//WYSIWYG

?>

<tr><td colspan="2"><img src="img/lines/l538_70.gif" alt="" /></td></tr>
<tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="5" /></td></tr>

<tr>
	<td align="right" class="chatlist"><?php echo $BL['be_admin_struct_template']; ?>:&nbsp;</td>
	<td><select name="template" id="template" class="f11b">
<?php

	echo '<option value="">'.$BL['be_admin_tmpl_default'].'</option>'.LF;

	$tmpllist = get_tmpl_files(PHPWCMS_TEMPLATE.'inc_cntpart/wysiwyg');
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


<tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="5" /></td></tr>
<tr><td colspan="2"><img src="img/lines/l538_70.gif" alt="" /></td></tr>
<tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="5" /></td></tr>


<tr><td colspan="2" class="chatlist">&nbsp;<?php echo $BL['be_cnt_plainhtml'] ?>:&nbsp;</td></tr>
<tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="1" /></td></tr>
<tr><td colspan="2" align="center"><?php

$wysiwyg_editor = array(
	'value'		=> isset($content["html"]) ? $content["html"] : '',
	'field'		=> 'chtml',
	'height'	=> '700px',
	'width'		=> '536px',
	'rows'		=> '15',
	'editor'	=> $_SESSION["WYSIWYG_EDITOR"],
	'lang'		=> 'en'
);
include(PHPWCMS_ROOT.'/include/inc_lib/wysiwyg.editor.inc.php');

?></td></tr>
<tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="10" /></td></tr>
<tr><td colspan="2"><img src="img/lines/l538_70.gif" alt="" /></td></tr>
<tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="5" /></td></tr>