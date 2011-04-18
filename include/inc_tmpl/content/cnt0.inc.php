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


// Plain Text

initMootools('1.2');
$GLOBALS['BE']['HEADER']['flext.js'] = getJavaScriptSourceLink(TEMPLATE_PATH.'lib/mootools/plugin-1.2/mootools.flext.js');

if(empty($content['ctext_format'])) {
	$content['ctext_format'] = 'plain';
}

?>
<tr><td colspan="2" class="rowspacer0x7"><img src="img/leer.gif" alt="" width="1" height="1" /></td></tr>

<tr>
	<td align="right" class="chatlist"><?php echo $BL['be_admin_struct_template']; ?>:&nbsp;</td>
	<td><select name="template" id="template" class="f11b">
<?php
	
	echo '<option value="">'.$BL['be_admin_tmpl_default'].'</option>'.LF;

// templates for frontend login
$tmpllist = get_tmpl_files(PHPWCMS_TEMPLATE.'inc_cntpart/plaintext');
if(is_array($tmpllist) && count($tmpllist)) {
	foreach($tmpllist as $val) {
		$selected_val = (isset($content["template"]) && $val == $content["template"]) ? ' selected="selected"' : '';
		$val = html_specialchars($val);
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
	<td valign="top"><textarea name="ctext" rows="20" class="code width440 flext growme" id="ctext"><?php 
		if(empty($content["text"])) {
		
			echo '';
		
		} else {
			
			if($content["text"]{0} == LF || $content["text"]{0} == "\r") echo ' '; // keep 1st linebreak;
			echo html_specialchars($content["text"]);
			
		}
	
	?></textarea></td>
</tr>

<tr><td colspan="2" class="rowspacer7x0"><img src="img/leer.gif" alt="" width="1" height="1" /></td></tr>