<?php
/**
 * phpwcms
 *
 * @author Oliver Georgi <og@phpwcms.org>
 * @copyright Copyright (c) 2002-2026, Oliver Georgi
 * @license http://opensource.org/licenses/GPL-2.0 GNU GPL-2
 *
 **/

// ----------------------------------------------------------------
// obligate check for phpwcms constants
if (!defined('PHPWCMS_ROOT')) {
    die("You Cannot Access This Script Directly, Have a Nice Day.");
}
// ----------------------------------------------------------------

$_entry['query']			= '';

?>
<h1 class="text-center text-sm-start"><?php echo $BLM['listing_title'] ?></h1>

<div class="card">
	<div class="card-header">
		<ul class="nav nav-tabs card-header-tabs">
			<li class="nav-item">
				<a class="nav-link<?php if($controller == 'orders') echo ' active'; ?>" href="<?php echo shop_url('controller=order') ?>"><?php echo $BLM['tab_orders'] ?></a>
			</li>
			<li class="nav-item">
				<a class="nav-link<?php if($controller == 'products') echo ' active'; ?>" href="<?php echo shop_url('controller=prod') ?>"><?php echo $BLM['tab_products'] ?></a>
			</li>
			<li class="nav-item">
				<a class="nav-link<?php if($controller == 'categories') echo ' active'; ?>" href="<?php echo shop_url('controller=cat') ?>"><?php echo $BLM['tab_categories'] ?></a>
			</li>
			<li class="nav-item">
				<a class="nav-link<?php if($controller == 'preferences') echo ' active'; ?>" href="<?php echo shop_url('controller=pref') ?>"><?php echo $BLM['tab_preferences'] ?></a>
			</li>
		</ul>
	</div>
	<div class="card-body">
