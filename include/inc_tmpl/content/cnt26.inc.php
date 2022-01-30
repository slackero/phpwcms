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


//recipe

unset($_SESSION['filebrowser_image_target']);

// base values
if(empty($content['recipe']['calorificvalue_add']))		$content['recipe']['calorificvalue_add']	= '';
if(empty($content['recipe']['preparation']))			$content['recipe']['preparation']			= '';
if(empty($content['recipe']['ingredients']))			$content['recipe']['ingredients']			= '';
if(empty($content['recipe']['time_add']))				$content['recipe']['time_add']				= '';
if(empty($content['recipe']['category']))				$content['recipe']['category']				= '';
if(empty($content['recipe']['severity']))				$content['recipe']['severity']				= 1;


// retrieve all available keywords
$content['recipe']['get_keywords'] = _dbQuery('SELECT acontent_text FROM '.DB_PREPEND.'phpwcms_articlecontent WHERE acontent_type=26 AND acontent_trash=0');
$content['recipe']['all_keywords'] = '';
if($content['recipe']['get_keywords']) {
	foreach($content['recipe']['get_keywords'] as $temp_val) {
		if($temp_val['acontent_text']) {
			if($content['recipe']['all_keywords']) $content['recipe']['all_keywords'] .= ', ';
			$content['recipe']['all_keywords'] .= $temp_val['acontent_text'];
		}
	}
}
$content['recipe']['all_keywords'] = convertStringToArray($content['recipe']['all_keywords']);

?>

<tr><td colspan="2" class="rowspacer0x7"><img src="img/leer.gif" alt="" width="1" height="1"></td></tr>

<?php

if(count($content['recipe']['all_keywords'])) {

	echo '<tr><td>&nbsp;</td><td>';
	echo '<table cellpadding="0" cellspacing="0" border="0" bgcolor="#E7E8EB"><tr><td style="padding:2px;">';
	echo '<select name="ph1" id="ph1" class="v10" ';
	echo 'onChange="insertAtCursorPos(document.articlecontent.recipe_category, ';
	echo '\', \'+document.articlecontent.ph1.options[document.articlecontent.ph1.selectedIndex].value);">';

	foreach($content['recipe']['all_keywords'] as $temp_val) {
		$temp_val = html($temp_val);
		echo '	<option value="'.$temp_val.'">'.$temp_val.'</option>'.LF;
	}

	echo '</select></td>';
	echo '<td><img src="img/button/go04.gif" width="15" height="15" title="Insert" border="0" ';
	echo 'onclick="insertAtCursorPos(document.articlecontent.recipe_category, ';
	echo '\', \'+document.articlecontent.ph1.options[document.articlecontent.ph1.selectedIndex].value);" style="margin:3px;">';
	echo '</td></tr></table></td></tr>'.LF;

}
?>
<tr>
  <td align="right" valign="top" class="chatlist"><img src="img/leer.gif" alt="" width="1" height="13"><?php echo $BL['be_ftptakeover_keywords'] ?>:&nbsp;</td>
  <td valign="top"><textarea name="recipe_category" id="recipe_category" cols="40" rows="2" class="width440 autosize"><?php echo html($content['recipe']['category']) ?></textarea></td>
</tr>
<tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="6"></td></tr>
<tr>
	<td align="right" class="chatlist"><?php echo $BL['be_admin_struct_template'] ?>:&nbsp;</td>
	<td><select name="recipe_template" id="recipe_template">
<?php

// templates for recipes
$tmpllist = get_tmpl_files(PHPWCMS_TEMPLATE.'inc_cntpart/recipe');
if(is_array($tmpllist) && count($tmpllist)) {
	foreach($tmpllist as $val) {
		if(isset($content['recipe']['template']) && $val == $content['recipe']['template']) {
			$selected_val = ' selected="selected"';
		} else {
			$selected_val = '';
		}
		$val = htmlspecialchars($val);
		echo '	<option value="' . $val . '"' . $selected_val . '>' . $val . '</option>' . LF;
	}
}

?>
		</select></td>
</tr>

<tr><td colspan="2" class="rowspacer7x7"><img src="img/leer.gif" alt="" width="1" height="1"></td></tr>

<tr>
  <td align="right" valign="top" class="chatlist"><img src="img/leer.gif" alt="" width="1" height="13">Zutaten:&nbsp;</td>
  <td valign="top"><textarea name="recipe_ingredients" id="recipe_ingredients" cols="40" rows="6" class="width440 autosize"><?php echo html($content['recipe']['ingredients']) ?></textarea></td>
</tr>
<tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="4"></td></tr>
<tr>
    <td align="right" class="chatlist">Zuber.Zeit:&nbsp;</td>
	<td><table border="0" cellpadding="0" cellspacing="0" summary="">
		<tr>
			<td><input name="recipe_time" type="text" id="recipe_time" class="f11" style="width:35px;" value="<?php echo empty($content['recipe']['time']) ? '' : intval($content['recipe']['time']) ?>" onkeyup="this.value=int_only(this.value);" size="5" /></td>
			<td class="chatlist" style="width:60px;">&nbsp;<?php echo $BL['be_date_minutes'] ?>&nbsp;</td>
			<td align="right" class="chatlist" style="width:75px;"><?php echo $BL['be_cnt_additional'] ?>:&nbsp;</td>
			<td><input name="recipe_time_add" type="text" id="recipe_time_add" class="f11" style="width:250px;" value="<?php echo html($content['recipe']['time_add']) ?>" size="40" /></td>
		</tr>
	</table></td>
</tr>
<tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="4"></td></tr>
<tr>
    <td align="right" class="chatlist">N&auml;hrwert:&nbsp;</td>
	<td><table border="0" cellpadding="0" cellspacing="0" summary="">
		<tr>
			<td><input name="recipe_calorificvalue" type="text" id="recipe_calorificvalue" class="f11" style="width:35px;" value="<?php echo empty($content['recipe']['calorificvalue']) ? '' : intval($content['recipe']['calorificvalue']) ?>" size="5" onkeyup="this.value=int_only(this.value);" /></td>
			<td class="chatlist" style="width:60px;">&nbsp;kJ&nbsp;</td>
			<td align="right" class="chatlist" style="width:75px;"><?php echo $BL['be_cnt_additional'] ?>:&nbsp;</td>
			<td><input name="recipe_calorificvalue_add" type="text" id="recipe_calorificvalue_add" class="f11" style="width:250px;" value="<?php echo html($content['recipe']['calorificvalue_add']) ?>" size="40" /></td>
		</tr>
	</table></td>
</tr>
<tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="4"></td></tr>
<tr>
  <td align="right" class="chatlist">Schwierigkeit:&nbsp;</td>
  <td valign="top"><table border="0" cellpadding="0" cellspacing="0" bgcolor="#E7E8EB" summary="">
      <tr>
        <td><input name="recipe_severity" id="recipe_severity_1" type="radio" value="1" <?php is_checked(1, $content['recipe']['severity']); ?>></td>
        <td class="v10"><label for="recipe_severity_1">1</label>&nbsp;&nbsp;</td>
		<td><input name="recipe_severity" id="recipe_severity_2" type="radio" value="2" <?php is_checked(2, $content['recipe']['severity']); ?>></td>
        <td class="v10"><label for="recipe_severity_2">2</label>&nbsp;&nbsp;</td>
		<td><input name="recipe_severity" id="recipe_severity_3" type="radio" value="3" <?php is_checked(3, $content['recipe']['severity']); ?>></td>
        <td class="v10"><label for="recipe_severity_3">3</label>&nbsp;&nbsp;</td>
		<td><input name="recipe_severity" id="recipe_severity_4" type="radio" value="4" <?php is_checked(4, $content['recipe']['severity']); ?>></td>
        <td class="v10"><label for="recipe_severity_4">4</label>&nbsp;&nbsp;</td>
		<td><input name="recipe_severity" id="recipe_severity_5" type="radio" value="5" <?php is_checked(5, $content['recipe']['severity']); ?>></td>
        <td class="v10"><label for="recipe_severity_5">5</label>&nbsp;</td>
		<td style="height: 22px;">&nbsp;</td>
      </tr>
    </table></td>
</tr>
<tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="8"></td></tr>
<tr><td colspan="2"><img src="img/lines/l538_70.gif" alt=""></td></tr>
<tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="4"></td></tr>
<tr><td colspan="2" class="chatlist">Zubereitung:&nbsp;</td></tr>
<tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="2"></td></tr>
<tr><td colspan="2" align="center"><?php

$wysiwyg_editor = array(
	'value'		=> $content['recipe']['preparation'],
	'field'		=> 'recipe_preparation',
	'height'	=> '250px',
	'width'		=> '100%',
	'rows'		=> '10',
	'editor'	=> $_SESSION["WYSIWYG_EDITOR"],
	'lang'		=> 'en'
);

include PHPWCMS_ROOT.'/include/inc_lib/wysiwyg.editor.inc.php';

?></td></tr>
