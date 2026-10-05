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
$content['shop']['shop_template']	= $content['shop']['shop_template'] ?? '';

$content['shop']['module_lang'] = $BL['modules'][$content['module']];

?>

<div class="form-group align-items-center row g-2">
	<label for="shop_template" class="col-sm-2 col-form-label text-end"><?php echo $BL['be_admin_struct_template']; ?></label>
	<div class="col-sm-4">
		<select name="shop_template" id="shop_template" class="form-select form-select-sm">
			<?php
			echo '<option value="">' . $BL['be_admin_tmpl_default'] . '</option>' . LF;

			$tmpllist = get_tmpl_files($phpwcms['modules'][$content['module']]['path'] . 'template', 'html');
			if (is_array($tmpllist) && count($tmpllist)) {
				foreach ($tmpllist as $val) {
					$selected_val = ($val == $content['shop']['shop_template']) ? ' selected="selected"' : '';
					$val = html($val);
					echo '<option value="' . $val . '"' . $selected_val . '>' . $val . '</option>' . LF;
				}
			}
			?>
		</select>
	</div>
</div>


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

<?php
// shop categories for the selector
$content['shop']['categories'] = _dbQuery('SELECT cat_id, cat_pid, cat_name FROM ' . DB_PREPEND . "categories WHERE cat_type='module_shop' AND cat_status=1 ORDER BY cat_sort, cat_name");

$content['shop']['category_tree'] = array();
if (is_array($content['shop']['categories'])) {
	foreach ($content['shop']['categories'] as $content['shop']['category']) {
		$content['shop']['category_tree'][$content['shop']['category']['cat_pid']][] = $content['shop']['category'];
	}
}

$content['shop']['category_options'] = function ($parent, $depth) use (&$content) {
	if (empty($content['shop']['category_tree'][$parent])) {
		return;
	}
	foreach ($content['shop']['category_tree'][$parent] as $cat) {
		$selected = ($cat['cat_id'] == $content['shop']['shop_category']) ? ' selected="selected"' : '';
		echo '<option value="' . $cat['cat_id'] . '"' . $selected . '>' . str_repeat('&nbsp;&nbsp;', $depth) . html($cat['cat_name']) . '</option>';
		$content['shop']['category_options']($cat['cat_id'], $depth + 1);
	}
};
?>
<div class="form-group align-items-center row g-2" id="shop_category_group">
	<label for="shop_category" class="col-sm-2 col-form-label text-end"><?php echo $content['shop']['module_lang']['cp_category_id']; ?></label>
	<div class="col-sm-4">
		<select name="shop_category" id="shop_category" class="form-select form-select-sm">
			<option value="0"><?php echo $BL['be_admin_tmpl_default']; ?></option>
			<?php $content['shop']['category_options'](0, 0); ?>
		</select>
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
