<?php
/*************************************************************************************
   Copyright notice
   
   (c) 2002-2007 Oliver Georgi (oliver@phpwcms.de) // All rights reserved.
 
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

$order_process  = str_replace('{CURRENCY_SYMBOL}', @htmlentities($_shopPref['shop_pref_currency'], ENT_QUOTES, PHPWCMS_CHARSET), $order_process);

$subtotal['float_net']		= $subtotal['net'];
$subtotal['float_gross']	= $subtotal['gross'];

$subtotal['net']	= number_format($subtotal['net'], $_tmpl['config']['price_decimals'], $_tmpl['config']['dec_point'], $_tmpl['config']['thousands_sep']);
$subtotal['vat']	= number_format($subtotal['vat'], $_tmpl['config']['price_decimals'], $_tmpl['config']['dec_point'], $_tmpl['config']['thousands_sep']);
$subtotal['gross']	= number_format($subtotal['gross'], $_tmpl['config']['price_decimals'], $_tmpl['config']['dec_point'], $_tmpl['config']['thousands_sep']);

$order_process  = str_replace('{SUBTOTAL_NET}', $subtotal['net'], $order_process);
$order_process  = str_replace('{SUBTOTAL_VAT}', $subtotal['vat'], $order_process);
$order_process  = str_replace('{SUBTOTAL_GROSS}', $subtotal['gross'], $order_process);

?>