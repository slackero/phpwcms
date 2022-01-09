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


// Plain Text

initMootools('1.2');

if(empty($content['ctext_format'])) {
	$content['ctext_format'] = 'plain';
}

?>
<tr><td colspan="2" class="rowspacer0x7"><img src="img/leer.gif" alt="" width="1" height="1" /></td></tr>

<tr>
	<td align="right" class="chatlist"><?php echo $BL['be_admin_struct_template']; ?>:&nbsp;</td>
	<td><select name="template" id="template">
<?php

	echo '<option value="">'.$BL['be_admin_tmpl_default'].'</option>'.LF;

// templates for frontend login
$tmpllist = get_tmpl_files(PHPWCMS_TEMPLATE.'inc_cntpart/plaintext');
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
	<td align="right" class="chatlist"><?php echo $BL['be_media_format'] ?>:&nbsp;</td>
	<td valign="top"><table border="0" cellpadding="0" cellspacing="0" summary="">
			<tr>
				<td><input name="ctext_format" type="radio" id="ctext_format0" value="plain" <?php is_checked('plain', $content['ctext_format']); ?> /></td>
				<td class="v10"><label for="ctext_format0" class="checkbox"><?php echo $BL['be_ctype_plaintext'] ?></label></td>

				<td>&nbsp;</td>
				<td><input name="ctext_format" type="radio" id="ctext_format1" value="markdown" <?php is_checked('markdown', $content['ctext_format']); ?> /></td>
				<td class="v10"><label for="ctext_format1" class="checkbox">MarkDown</label>(<a href="http://en.wikipedia.org/wiki/Markdown" target="_blank" title="Wikipedia: Markdown">?</a>)</td>

				<td>&nbsp;</td>
				<td><input name="ctext_format" type="radio" id="ctext_format2" value="textile" <?php is_checked('textile', $content['ctext_format']); ?> /></td>
				<td class="v10"><label for="ctext_format2" class="checkbox">Textile</label>(<a href="http://en.wikipedia.org/wiki/Textile_%28markup_language%29" target="_blank" title="Wikipedia: Textile">?</a>)</td>

			</tr>
		</table>
	</td>
</tr>

<tr><td colspan="2" class="rowspacer7x7"><img src="img/leer.gif" alt="" width="1" height="1" /></td></tr>

<tr>
	<td align="right" valign="top" class="chatlist tdtop4"><?php echo $BL['be_cnt_plaintext'] ?>:&nbsp;</td>
	<td valign="top"><textarea name="ctext" rows="20" class="code width440 autosize" id="ctext"><?php
		if(empty($content["text"])) {

			echo '';

		} else {

			if(substr($content["text"], 0, 1) === LF || substr($content["text"], 0, 1) === "\r") {
				echo ' '; // keep 1st linebreak;
			}
			echo html($content["text"]);

		}

	?></textarea></td>
</tr>
