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

?>
<table width="100%" border="0" cellpadding="0" cellspacing="0" summary="" class="shop">
		
	<tr>
		<th>&nbsp;</th>
		<th>&nbsp;<?php echo $BLM['th_ordnr'] ?></th>
		<th>&nbsp;&nbsp;<?php echo $BLM['th_date'] ?></th>
		<th>&nbsp;&nbsp;<?php echo $BLM['th_customer'] ?></th>
		<th>&nbsp;&nbsp;<?php echo $BLM['th_net'] ?>&nbsp;</th>
		<th>&nbsp;&nbsp;<?php echo $BLM['th_gross'] ?>&nbsp;</th>
		<th>&nbsp;<?php echo $BLM['th_payment'] ?></th>
		<th>&nbsp;&nbsp;&nbsp;</th>
	</tr>
	
	
<?php

// loop listing available newsletters
$row_count = 0;                

$sql  = "SELECT *, DATE_FORMAT(order_date,'%e.%m.%y') AS order_fdate FROM ".DB_PREPEND."phpwcms_shop_orders WHERE ";
$sql .= "order_status NOT IN ('ARCHIVED', 'CLOSED') ORDER BY order_date DESC";

$data = _dbQuery($sql);

$_controller_link =  shop_url('controller=order');

foreach($data as $row) {

	echo '<tr'.( ($row_count % 2) ? ' class="adsAltRow"' : '' ).'>'.LF;
	
	echo '<td width="25" style="padding:2px 3px 2px 4px;">';
	echo '<img src="img/famfamfam/silk_icons_gif/cart.gif" alt="'.$BLM['shop_order'].'" /></td>'.LF;
	echo '<td class="dir" width="13%">'.html_specialchars($row['order_number'])."&nbsp;</td>\n";
	echo '<td class="dir" width="13%">&nbsp;'.html_specialchars($row['order_fdate'])."&nbsp;</td>\n";
	echo '<td class="dir" width="50%">&nbsp;<a href="mailto:'.$row['order_email'].'?subject='.rawurlencode($BLM['shopprod_order_subject'].' #'.$row['order_number']).'">';
	echo html_specialchars($row['order_firstname'].' '.$row['order_name'])."</a>&nbsp;</td>\n";
	
	echo '<td class="dir listNumber" width="10%">'.html_specialchars( number_format( round($row['order_net'], 2) , 2, $BLM['dec_point'], $BLM['thousands_sep'] ) )."&nbsp;</td>\n";
	echo '<td class="dir listNumber" width="10%">'.html_specialchars( number_format( round($row['order_gross'], 2) , 2, $BLM['dec_point'], $BLM['thousands_sep'] ) )."&nbsp;</td>\n";

	echo '<td class="dir" width="10%">'.html_specialchars($row['order_payment'])."&nbsp;</td>\n";
	
	echo '<td width="5%" align="right" nowrap="nowrap" class="button_td">';
	
		echo '<a href="'.$_controller_link.'&amp;delete='.$row["order_id"];
		echo '" title="delete: '.html_specialchars($row['order_number']).'"';
		echo ' onclick="return confirm(\''.$BLM['delete_order'].js_singlequote($row['order_number']).'\');">';
		echo '<img src="img/button/trash_13x13_1.gif" border="0" alt="" /></a>';
	
	echo '</td>'.LF;
	
	echo '</tr>'.LF;

	$row_count++;
}

if($row_count) {
	echo '<tr><td colspan="8" bgcolor="#92A1AF"><img src="img/leer.gif" alt="" width="1" height="1" /></td></tr>';
}

?>	

</table>