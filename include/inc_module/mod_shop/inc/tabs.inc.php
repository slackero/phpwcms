<?php
/**
 * phpwcms content management system
 *
 * @author Oliver Georgi <oliver@phpwcms.org>
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

$_entry['query']			= '';

?>
<h1 class="title" style="margin-bottom:10px"><?php echo $BLM['listing_title'] ?></h1>

<div id="tabsG">
    <ul>
        <!--<li<?php if($controller == 'default') echo ' class="activeTab"'; ?>><a href="<?php echo shop_url() ?>"><span><?php echo $BLM['tab_default'] ?></span></a></li>-->
        <li<?php if($controller == 'orders') echo ' class="activeTab"'; ?>><a href="<?php echo shop_url('controller=order') ?>"><span><?php echo $BLM['tab_orders'] ?></span></a></li>
        <li<?php if($controller == 'products') echo ' class="activeTab"'; ?>><a href="<?php echo shop_url('controller=prod') ?>"><span><?php echo $BLM['tab_products'] ?></span></a></li>
        <li<?php if($controller == 'categories') echo ' class="activeTab"'; ?>><a href="<?php echo shop_url('controller=cat') ?>"><span><?php echo $BLM['tab_categories'] ?></span></a></li>
        <li<?php if($controller == 'preferences') echo ' class="activeTab"'; ?>><a href="<?php echo shop_url('controller=pref') ?>"><span><?php echo $BLM['tab_preferences'] ?></span></a></li>
    </ul>
    <br class="clear" />
</div>
