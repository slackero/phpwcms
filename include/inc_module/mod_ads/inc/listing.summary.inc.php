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

echo $BLM['under_construction'];

?>
<form action="https://www.paypal.com/cgi-bin/webscr" method="get" target="_blank" style="margin:20px 0 0 20px;">
<input type="hidden" name="cmd" value="_xclick" />
<input type="hidden" name="business" value="phpwcms-paypal-donation@phpwcms.de" />
<input type="hidden" name="item_name" value="phpwcms Donation" />
<input type="hidden" name="no_shipping" value="1" />
<input type="hidden" name="no_note" value="1" />
<input type="hidden" name="bn" value="PP-DonationsBF" />
<input type="hidden" name="charset" value="UTF-8" />
<strong>PayPal: </strong>
<select name="amount" class="v12">
	<option value="5">5</option>
	<option value="10">10</option>
	<option value="15">15</option>
	<option value="20">20</option>
	<option value="25" selected="selected">25</option>
	<option value="30">30</option>
	<option value="35">35</option>
	<option value="40">40</option>
	<option value="45">45</option>
	<option value="50">50</option>
	<option value="75">75</option>
	<option value="100">100</option>
	<option value="125">125</option>
	<option value="150">150</option>
	<option value="175">175</option>
	<option value="200">200</option>
	<option value="250">250</option>
	<option value="300">300</option>
	<option value="400">400</option>
	<option value="500">500</option>
	<option value="">&nbsp;</option>
</select>
<select name="currency_code" class="v12">
	<option value="EUR" selected="selected">EUR</option>
	<option value="USD">USD</option>
</select>
<input type="submit" value="<?php echo $BLM['donate'] ?>" class="v12" />
</form>