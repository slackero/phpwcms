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


//bullet list table

?>

<tr><td colspan="2" class="rowspacer0x7"><img src="img/leer.gif" alt="" width="1" height="1" /></td></tr>

<tr>
	<td align="right" class="chatlist"><?php echo $BL['be_admin_struct_template']; ?>:&nbsp;</td>
	<td><select name="template" id="template">
<?php

	echo '<option value="">'.$BL['be_admin_tmpl_default'].'</option>'.LF;

// templates for frontend login
$tmpllist = get_tmpl_files(PHPWCMS_TEMPLATE.'inc_cntpart/bulletlist');
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
<td align="right" valign="top" class="chatlist"><img src="img/leer.gif" alt="" width="1" height="13"><?php echo $BL['be_cnt_bullet'] ?>:&nbsp;</td>
<td valign="top"><textarea name="ctext" rows="20" class="width440 autosize" id="ctext"><?php echo  isset($content["text"]) ? $content["text"] : '' ?></textarea></td>
</tr>