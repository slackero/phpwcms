<?php
/**
 * cmsGo!
 *
 * @author Pixels & Points GmbH <info@pixels-points.ch>
 * @copyright Copyright (c) 2002-2018, Pixels & Points GmbH
 * @license https://www.pixels-points.ch/cmsgo-license.html Pixels & Points cmsGo! license
 *
 **/

// ----------------------------------------------------------------
// obligate check for cmsgo constants
if (!defined('CMSGO_ROOT')) {
    die("You Cannot Access This Script Directly, Have a Nice Day.");
}
// ----------------------------------------------------------------

echo '<h1>'.$BLM['listing_title'].'</h1>';
echo '<p>'.$BLM['listing_intro'].'</p>';
?>

<div class="card mb-3">
  <div class="card-header"><h2><?php echo $BLM['listing_shop'] ?><h2></div>
  <div class="card-body">
    <?php echo $BLM['promotext_shop'] ?>
  </div>
</div>

<div class="card mb-3">
  <div class="card-header"><h2><?php echo $BLM['listing_calendar'] ?><h2></div>
  <div class="card-body">
    <?php echo $BLM['promotext_calendar'] ?>
  </div>
</div>

<div class="card mb-3">
  <div class="card-header"><h2><?php echo $BLM['listing_user'] ?><h2></div>
  <div class="card-body">
    <?php echo $BLM['promotext_user'] ?>
  </div>
</div>