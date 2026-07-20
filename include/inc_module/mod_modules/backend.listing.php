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
