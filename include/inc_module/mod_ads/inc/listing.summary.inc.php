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

echo $BLM['under_construction'];

?>
<form action="https://www.paypal.com/cgi-bin/webscr" method="get" target="_blank" style="margin:20px 0 0 20px;">
<input type="hidden" name="cmd" value="_xclick" />
<input type="hidden" name="business" value="phpwcms-paypal-donation@phpwcms.org" />
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