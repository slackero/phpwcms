<?php
/*************************************************************************************
   Copyright notice
   
   (c) 2002-2008 Oliver Georgi (oliver@phpwcms.de) // All rights reserved.

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

// News






?>
<h1 class="title" style="margin-bottom:10px"><?php echo $BL['be_news'] ?></h1>

<div class="navBarLeft imgButton chatlist">
	&nbsp;&nbsp;
	<a href="<?php echo NEWS_HREF ?>&amp;edit=0" title="<?php echo $BL['be_news_create'] ?>">
		<img src="img/famfamfam/silk_icons_gif/page_white_add.gif" alt="<?php echo $BL['be_news_create'] ?>" border="0" />
		<span><?php echo $BL['be_news_create'] ?></span>
	</a>
</div>


<form action="<?php echo NEWS_HREF ?>" method="post" name="paginate" id="paginate"><input type="hidden" name="do_pagination" value="1" />
<table width="100%" border="0" cellpadding="0" cellspacing="0" class="paginate" summary="">
	<tr>
		<td><table border="0" cellpadding="0" cellspacing="0" summary="">
			<tr>
				
				<td><input type="checkbox" name="showactive" id="showactive" value="1" onclick="this.form.submit();"<?php is_checked(1, $_entry['list_active'], 1) ?> /></td>
				<td><label for="showactive"><img src="img/button/aktiv_12x13_1.gif" alt="" style="margin:1px 1px 0 1px;" /></label></td>
				<td><input type="checkbox" name="showinactive" id="showinactive" value="1" onclick="this.form.submit();"<?php is_checked(1, $_entry['list_inactive'], 1) ?> /></td>
				<td><label for="showinactive"><img src="img/button/aktiv_12x13_0.gif" alt="" style="margin:1px 1px 0 1px;" /></label></td>

<?php 
if($_entry['pages_total'] > 1) {

	echo '<td class="chatlist">|&nbsp;</td>';
	echo '<td>';
	if($_SESSION['glossary_page'] > 1) {
		echo '<a href="'.GLOSSARY_HREF.'&amp;page='.($_SESSION['glossary_page']-1).'">';
		echo '<img src="img/famfamfam/mini/action_back.gif" alt="" border="0" /></a>';
	} else {
		echo '<img src="img/famfamfam/mini/action_back.gif" alt="" border="0" class="inactive" />';
	}
	echo '</td>';
	echo '<td><input type="text" name="page" id="page" maxlength="4" size="4" value="'.$_SESSION['glossary_page'];
	echo '"  class="textinput" style="margin:0 3px 0 5px;width:30px;font-weight:bold;" /></td>';
	echo '<td class="chatlist">/'.$_entry['pages_total'].'&nbsp;</td>';
	echo '<td>';
	if($_SESSION['glossary_page'] < $_entry['pages_total']) {
		echo '<a href="'.GLOSSARY_HREF.'&amp;page='.($_SESSION['glossary_page']+1).'">';
		echo '<img src="img/famfamfam/mini/action_forward.gif" alt="" border="0" /></a>';
	} else {
		echo '<img src="img/famfamfam/mini/action_forward.gif" alt="" border="0" class="inactive" />';
	}
	echo '</td><td class="chatlist">&nbsp;|&nbsp;</td>';

} else {

	echo '<td class="chatlist">|&nbsp;<input type="hidden" name="page" id="page" value="1" /></td>';

}
?>
				<td><input type="text" name="filter" id="filter" size="10" value="<?php 
				
				if(isset($_POST['filter']) && is_array($_POST['filter']) ) {
					echo html_specialchars(implode(' ', $_POST['filter']));
				}
				
				?>" class="textinput" style="margin:0 2px 0 0;width:110px;text-align:left;" title="filter results by username, name or email" /></td>
				<td><input type="image" name="gofilter" src="img/famfamfam/mini/action_go.gif" style="margin-right:3px;" /></td>
			
			</tr>
		</table></td>

	<td class="chatlist" align="right">
		<a href="<?php echo GLOSSARY_HREF ?>&amp;c=10">10</a>
		<a href="<?php echo GLOSSARY_HREF ?>&amp;c=25">25</a>
		<a href="<?php echo GLOSSARY_HREF ?>&amp;c=50">50</a>
		<a href="<?php echo GLOSSARY_HREF ?>&amp;c=100">100</a>
		<a href="<?php echo GLOSSARY_HREF ?>&amp;c=250">250</a>
		<a href="<?php echo GLOSSARY_HREF ?>&amp;c=all"><?php echo $BL['be_ftptakeover_all'] ?></a>
	</td>

	</tr>
</table>
</form>

<table width="100%" border="0" cellpadding="0" cellspacing="0" summary="">
		
	<tr><td colspan="5"><img src="img/leer.gif" alt="" width="1" height="3"></td></tr>
	<tr><td colspan="5" bgcolor="#92A1AF"><img src="img/leer.gif" alt="" width="1" height="1"></td></tr>
	
<?php
// loop listing available newsletters
$row_count = 0;                

$sql  = 'SELECT * FROM '.DB_PREPEND.'phpwcms_glossary WHERE '.$_entry['query'].' ';
$sql .= 'LIMIT '.(($_SESSION['glossary_page']-1) * $_SESSION['list_user_count']).','.$_SESSION['list_user_count'];
$data = _dbQuery($sql);

foreach($data as $row) {

	echo '<tr'.( ($row_count % 2) ? ' bgcolor="#F3F5F8"' : '' ).'>'.LF.'<td width="20" style="width:20px;padding:2px 1px 2px 3px;">';
	echo '<img src="img/famfamfam/';
	echo $row["glossary_highlight"] ? 'customized/tag_blue_key.gif' : 'silk_icons_gif/tag_blue.gif';
	echo '" alt="'.$BLM['glossary_entry'].'" /></td>'.LF;
	echo '<td class="dir" width="50%">'.html_specialchars($row["glossary_title"])."&nbsp;</td>\n";
	
	echo '<td class="dir">'.html_specialchars($row["glossary_keyword"])."&nbsp;</td>\n";
	
	echo '<td class="dir">'.html_specialchars($row["glossary_tag"])."&nbsp;</td>\n";
	
	echo '<td align="right" nowrap="nowrap" class="button_td">';
	
	echo '<a href="'.GLOSSARY_HREF.'&amp;edit='.$row["glossary_id"].'">';		
	echo '<img src="img/button/edit_22x13.gif" border="0" alt="" /></a>';
	
	echo '<a href="'.GLOSSARY_HREF.'&amp;editid='.$row["glossary_id"].'&amp;verify=';
	echo (($row["glossary_status"]) ? '0' : '1').'">';		
	echo '<img src="img/button/aktiv_12x13_'.$row["glossary_status"].'.gif" border="0" alt="" /></a>';
	
	echo '<a href="'.GLOSSARY_HREF.'&amp;delete='.$row["glossary_id"];
	echo '" title="delete: '.html_specialchars($row["glossary_title"]).'"';
	echo ' onclick="return confirm(\''.$BLM['delete_entry'].' '.js_singlequote($row["glossary_title"]).'\');">';
	echo '<img src="img/button/trash_13x13_1.gif" border="0" alt=""></a>';

	echo "</td>\n</tr>\n";

	$row_count++;
}

if($row_count) {
	echo '<tr><td colspan="5" bgcolor="#92A1AF"><img src="img/leer.gif" alt="" width="1" height="1"></td></tr>';
}

?>	

	<tr><td colspan="5"><img src="img/leer.gif" alt="" width="1" height="15"></td></tr>
</table>


