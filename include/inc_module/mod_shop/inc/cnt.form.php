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


// Shop module content part form fields

if (empty($content['shop']) || !is_array($content['shop'])) {
	$content['shop'] = array();
}

$content['shop']['shop_action']		= $content['shop']['shop_action'] ?? 'categories';
$content['shop']['shop_category']	= intval($content['shop']['shop_category'] ?? 0);

$content['shop']['module_lang'] = $BL['modules'][$content['module']];

?>

<div class="form-group align-items-center row g-2">
	<label for="shop_action" class="col-sm-2 col-form-label text-end"><?php echo $content['shop']['module_lang']['cp_action']; ?></label>
	<div class="col-sm-4">
		<select name="shop_action" id="shop_action" class="form-select form-select-sm">
			<option value="categories"<?php is_selected('categories', $content['shop']['shop_action']) ?>><?php echo $content['shop']['module_lang']['cp_action_categories'] ?></option>
			<option value="category"<?php is_selected('category', $content['shop']['shop_action']) ?>><?php echo $content['shop']['module_lang']['cp_action_category'] ?></option>
			<option value="productlist"<?php is_selected('productlist', $content['shop']['shop_action']) ?>><?php echo $content['shop']['module_lang']['cp_action_productlist'] ?></option>
			<option value="order"<?php is_selected('order', $content['shop']['shop_action']) ?>><?php echo $content['shop']['module_lang']['cp_action_order'] ?></option>
			<option value="smallcart"<?php is_selected('smallcart', $content['shop']['shop_action']) ?>><?php echo $content['shop']['module_lang']['cp_action_smallcart'] ?></option>
		</select>
	</div>
</div>

<div class="form-group align-items-center row g-2" id="shop_category_group">
	<label for="shop_category" class="col-sm-2 col-form-label text-end"><?php echo $content['shop']['module_lang']['cp_category_id']; ?></label>
	<div class="col-sm-auto">
		<input type="text" name="shop_category" id="shop_category" class="form-control form-control-sm" size="6" maxlength="10" value="<?php echo $content['shop']['shop_category'] ?: ''; ?>" />
	</div>
	<div class="col">
		<small class="form-text text-muted"><?php echo $content['shop']['module_lang']['cp_category_id_hint']; ?></small>
	</div>
</div>

<?php $BE['BODY_CLOSE'][] = '<script type="text/javascript">
function toggleShopCategory() {
	$("#shop_category_group").toggle($("#shop_action").prop("value") === "category");
}
$(function(){
	$("#shop_action").on("change", toggleShopCategory);
	toggleShopCategory();
});
</script>'; ?>
