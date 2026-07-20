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


// PHP variables

?>

<div class="form-group form-row">
  <label for="cvar" class="col-sm-2 col-form-label text-right"><?php echo $BL['be_cnt_vars'] ?></label>
  <div class="col">
    <textarea name="cvar" rows="20" wrap="VIRTUAL" class="form-control form-control-sm" id="cvar"><?php echo isset($content["var"]) ? html($content["var"]) : '' ?></textarea>
    </div>
</div>
