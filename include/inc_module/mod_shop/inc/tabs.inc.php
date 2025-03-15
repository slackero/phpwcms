<?php
/**
 * cmsGO!
 *
 * @author Pixels & Points GmbH <info@pixels-points.ch>
 * @copyright Copyright (c) 2002-2025, Pixels & Points GmbH
 * @license https://www.pixels-points.ch/cmsgo-license.html Pixels & Points cmsGO! license
 *
 **/

// ----------------------------------------------------------------
// obligate check for cmsGO! constants
if (!defined('CMSGO_ROOT')) {
    die("You Cannot Access This Script Directly, Have a Nice Day.");
}
// ----------------------------------------------------------------

$_entry['query']			= '';

?>
<h1 class="text-center text-sm-left"><?php echo $BLM['listing_title'] ?></h1>

<div class="card">
<div class="card-body">

<div id="tabsG" class="mb-4">
  <ul class="nav nav-tabs">
    <li class="nav-item"><a class="nav-link<?php if($controller == 'orders') echo ' active'; ?>" href="<?php echo shop_url('controller=order') ?>"><?php echo $BLM['tab_orders'] ?></a></li>
        <li class="nav-item"><a class="nav-link<?php if($controller == 'products') echo ' active'; ?>" href="<?php echo shop_url('controller=prod') ?>"><?php echo $BLM['tab_products'] ?></a></li>
        <li class="nav-item"><a class="nav-link<?php if($controller == 'categories') echo ' active'; ?>" href="<?php echo shop_url('controller=cat') ?>"><?php echo $BLM['tab_categories'] ?></a></li>
        <li class="nav-item"><a class="nav-link<?php if($controller == 'preferences') echo ' active'; ?>" href="<?php echo shop_url('controller=pref') ?>"><?php echo $BLM['tab_preferences'] ?></a></li>
    </ul>
</div>
