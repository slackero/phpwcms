<?php
/**
 * cmsGo!
 *
 * @author Pixels & Points GmbH <info@pixels-points.ch>
 * @copyright Copyright (c) 2002-2017, Pixels & Points GmbH
 * @license https://www.pixels-points.ch/cmsgo-license.html Pixels & Points cmsGo! license
 *
 **/

// ----------------------------------------------------------------
// obligate check for cmsgo constants
if (!defined('CMSGO_ROOT')) {
    die("You Cannot Access This Script Directly, Have a Nice Day.");
}
// ----------------------------------------------------------------


// PHP variables

?>

<div class="form-group form-row">
  <label for="cvar" class="col-sm-2 col-form-label text-right"><?php echo $BL['be_cnt_vars'] ?></label>
  <div class="col">
    <textarea name="cvar" rows="20" wrap="VIRTUAL" class="form-control form-control-sm" id="cvar"><?php echo isset($content["var"]) ? html($content["var"]) : '' ?></textarea>
    </div>
</div>