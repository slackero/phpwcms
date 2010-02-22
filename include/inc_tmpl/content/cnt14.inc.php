<?php
/*************************************************************************************
   Copyright notice
   
   (c) 2002-2010 Oliver Georgi (oliver@phpwcms.de) // All rights reserved.
 
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
			$val = html_specialchars($val);
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