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


?>
<table width="100%" border="0" cellpadding="0" cellspacing="0" summary="adplaces" class="ads">

	<tr>
		<th width="25">&nbsp;</th>
		<th><?php echo $BLM['adplace'] ?></th>
		<th class="listFormat" nowrap="nowrap"><?php echo $BLM['ad_format'] ?></th>
		<th class="listFormat">RT</th>
		<th class="listFormat" nowrap="nowrap"><?php echo $BLM['ad_wxh'] ?></th>
		<th>&nbsp;</th>
	</tr>
	
<?php
// loop listing available newsletters
$row_count = 0;                

$sql  = 'SELECT * FROM '.DB_PREPEND.'phpwcms_ads_place ap ';
$sql .= 'LEFT JOIN '.DB_PREPEND.'phpwcms_ads_formats af ON ';
$sql .=	'ap.adplace_format=af.adformat_id ';
$sql .= 'WHERE adplace_status!=9';
//$sql .= 'LIMIT '.(($_SESSION['ads_page']-1) * $_SESSION['list_user_count']).','.$_SESSION['list_user_count'];
$data = _dbQuery($sql);

$sql  = 'SELECT COUNT(*) FROM '.DB_PREPEND.'phpwcms_ads_campaign WHERE adcampaign_status!=9 AND adcampaign_place=';

foreach($data as $row) {

	echo '<tr'.( ($row_count % 2) ? ' class="adsAltRow"' : '' ).'>'.LF;
	echo '	<td width="25" style="padding:2px 3px 2px 4px;"><img src="img/famfamfam/layout.gif" alt="'.$BLM['adplace'].'" /></td>'.LF;

	echo '	<td width="50%">'.html_specialchars($row["adplace_title"])."</td>\n";
	
	echo '	<td class="listFormat" nowrap="nowrap">'.html_specialchars($row["adformat_title"])."</td>\n";
	echo '	<td class="listFormat">{ADS_'.$row["adplace_id"]."}</td>\n";
	echo '	<td class="listFormat">'.$row["adplace_width"].'x'.$row["adplace_height"]."&nbsp;</td>\n";
	
	
	echo '	<td align="right" nowrap="nowrap" class="button_td">';
	
	echo '<a href="'.MODULE_HREF.'&amp;adplace=1&amp;edit='.$row["adplace_id"].'">';		
	echo '<img src="img/button/edit_22x13.gif" border="0" alt="" /></a>';
	
	echo '<a href="'.MODULE_HREF.'&amp;adplace=1&amp;editid='.$row["adplace_id"].'&amp;verify=';
	echo (($row["adplace_status"]) ? '0' : '1').'">';		
	echo '<img src="img/button/aktiv_12x13_'.$row["adplace_status"].'.gif" border="0" alt="" /></a>';
	
	// check if campaign for place is available - then it's not possible t delete place
	if(_dbQuery($sql.$row['adplace_id'], 'COUNT')) {
		echo '<img src="img/button/trash_13x13_1.gif" border="0" alt="" class="inactive" />';
	} else {
		echo '<a href="'.MODULE_HREF.'&amp;adplace=1&amp;delete='.$row["adplace_id"];
		echo '" title="delete: '.html_specialchars($row["adplace_title"]).'"';
		echo ' onclick="return confirm(\''.$BLM['delete_adplace'].js_singlequote($row["adplace_title"]).'\');">';
		echo '<img src="img/button/trash_13x13_1.gif" border="0" alt="" /></a>';
	}

	echo "</td>\n</tr>\n";

	$row_count++;
}


if($row_count) {
	echo '<tr><td colspan="6" bgcolor="#92A1AF"><img src="img/leer.gif" alt="" width="1" height="1"></td></tr>';
}

?>	
</table>