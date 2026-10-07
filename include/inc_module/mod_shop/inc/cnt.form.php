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
$content['shop']['shop_config']		= $content['shop']['shop_config'] ?? '';

$content['shop']['options']			= (isset($content['shop']['options']) && is_array($content['shop']['options'])) ? $content['shop']['options'] : array();

$content['shop']['module_lang'] = $BL['modules'][$content['module']];

$opt_cat_all				= $content['shop']['options']['cat_all'] ?? '';
$opt_cat_all_pos			= $content['shop']['options']['cat_all_pos'] ?? '';
$opt_cat_count_products		= $content['shop']['options']['cat_count_products'] ?? '';
$opt_cat_list_products		= $content['shop']['options']['cat_list_products'] ?? '';
$opt_cat_list_sort_by		= $content['shop']['options']['cat_list_sort_by'] ?? '';
$opt_image_list_width		= $content['shop']['options']['image_list_width'] ?? '';
$opt_image_list_height		= $content['shop']['options']['image_list_height'] ?? '';
$opt_image_list_crop		= $content['shop']['options']['image_list_crop'] ?? '';
$opt_image_list_lightbox	= $content['shop']['options']['image_list_lightbox'] ?? '';
$opt_order_number_style		= $content['shop']['options']['order_number_style'] ?? '';
$opt_cart_url				= $content['shop']['options']['cart_url'] ?? '';

// Determine active template CONFIG block for placeholder/reference
$content['shop']['tmpl_file'] = '';
$tmpl_base = $phpwcms['modules'][$content['module']]['path'] . 'template/';
if ($content['shop']['shop_template'] !== '' && is_file($tmpl_base . $content['shop']['shop_template'])) {
	$content['shop']['tmpl_file'] = $tmpl_base . $content['shop']['shop_template'];
} elseif (is_file($tmpl_base . $phpwcms['default_lang'] . '.html')) {
	$content['shop']['tmpl_file'] = $tmpl_base . $phpwcms['default_lang'] . '.html';
} elseif (is_file($tmpl_base . 'default.html')) {
	$content['shop']['tmpl_file'] = $tmpl_base . 'default.html';
}

$content['shop']['tmpl_config_raw'] = '';
if ($content['shop']['tmpl_file'] !== '') {
	$content['shop']['tmpl_source'] = @file_get_contents($content['shop']['tmpl_file']);
	if ($content['shop']['tmpl_source']) {
		$content['shop']['tmpl_config_raw'] = trim(get_tmpl_section('CONFIG', $content['shop']['tmpl_source']));
	}
}

?>


<div class="form-group align-items-center row g-2">
	<label for="shop_template" class="col-sm-2 col-form-label text-end"><?php echo $content['shop']['module_lang']['cp_template'] ?? $BL['be_admin_struct_template']; ?></label>
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

<div class="form-group row g-2">
	<label class="col-sm-2 col-form-label text-end"><?php echo $content['shop']['module_lang']['cp_config']; ?></label>
	<div class="col-sm-10">
		<div class="accordion accordion-flush border rounded-2" id="shop_config_accordion">

			<!-- Accordion Item 1: Action-specific settings -->
			<div class="accordion-item" id="shop_accordion_options_item">
				<h2 class="accordion-header" id="heading_shop_options">
					<button class="accordion-button py-2 px-3 fw-semibold" type="button" data-bs-toggle="collapse" data-bs-target="#collapse_shop_options" aria-expanded="true" aria-controls="collapse_shop_options">
						<i class="fa-solid fa-sliders me-2 text-primary"></i> <?php echo $content['shop']['module_lang']['cp_config_options']; ?>
					</button>
				</h2>
				<div id="collapse_shop_options" class="accordion-collapse collapse show" aria-labelledby="heading_shop_options" data-bs-parent="#shop_config_accordion">
					<div class="accordion-body p-3">

						<!-- Action: categories / category -->
						<div class="shop-action-group" data-shop-action="categories category">
							<div class="form-group align-items-center row g-2 mb-2">
								<label for="shop_opt_cat_all" class="col-sm-3 col-form-label text-end"><?php echo $content['shop']['module_lang']['cp_cat_all']; ?></label>
								<div class="col-sm-5">
									<input type="text" name="shop_opt_cat_all" id="shop_opt_cat_all" value="<?php echo html($opt_cat_all); ?>" class="form-control form-control-sm" placeholder="<?php echo $BL['be_admin_tmpl_default']; ?>" />
								</div>
							</div>

							<div class="form-group align-items-center row g-2 mb-2">
								<label for="shop_opt_cat_all_pos" class="col-sm-3 col-form-label text-end"><?php echo $content['shop']['module_lang']['cp_cat_all_pos']; ?></label>
								<div class="col-sm-5">
									<select name="shop_opt_cat_all_pos" id="shop_opt_cat_all_pos" class="form-select form-select-sm">
										<option value=""><?php echo $BL['be_admin_tmpl_default']; ?></option>
										<option value="bottom"<?php is_selected('bottom', $opt_cat_all_pos); ?>><?php echo $content['shop']['module_lang']['cp_cat_all_pos_bottom']; ?></option>
										<option value="top"<?php is_selected('top', $opt_cat_all_pos); ?>><?php echo $content['shop']['module_lang']['cp_cat_all_pos_top']; ?></option>
										<option value="none"<?php is_selected('none', $opt_cat_all_pos); ?>><?php echo $content['shop']['module_lang']['cp_cat_all_pos_none']; ?></option>
									</select>
								</div>
							</div>

							<div class="form-group align-items-center row g-2 mb-2">
								<label for="shop_opt_cat_count_products" class="col-sm-3 col-form-label text-end"><?php echo $content['shop']['module_lang']['cp_cat_count_products']; ?></label>
								<div class="col-sm-5">
									<select name="shop_opt_cat_count_products" id="shop_opt_cat_count_products" class="form-select form-select-sm">
										<option value=""><?php echo $BL['be_admin_tmpl_default']; ?></option>
										<option value="1"<?php is_selected('1', $opt_cat_count_products); ?>><?php echo $BL['be_yes']; ?></option>
										<option value="0"<?php is_selected('0', $opt_cat_count_products); ?>><?php echo $BL['be_no']; ?></option>
									</select>
								</div>
							</div>

							<div class="form-group align-items-center row g-2">
								<label for="shop_opt_cat_list_products" class="col-sm-3 col-form-label text-end"><?php echo $content['shop']['module_lang']['cp_cat_list_products']; ?></label>
								<div class="col-sm-5">
									<select name="shop_opt_cat_list_products" id="shop_opt_cat_list_products" class="form-select form-select-sm">
										<option value=""><?php echo $BL['be_admin_tmpl_default']; ?></option>
										<option value="1"<?php is_selected('1', $opt_cat_list_products); ?>><?php echo $BL['be_yes']; ?></option>
										<option value="0"<?php is_selected('0', $opt_cat_list_products); ?>><?php echo $BL['be_no']; ?></option>
									</select>
								</div>
							</div>
						</div>

						<!-- Action: productlist -->
						<div class="shop-action-group" data-shop-action="productlist">
							<div class="form-group align-items-center row g-2 mb-2">
								<label for="shop_opt_cat_list_sort_by" class="col-sm-3 col-form-label text-end"><?php echo $content['shop']['module_lang']['cp_cat_list_sort_by']; ?></label>
								<div class="col-sm-5">
									<select name="shop_opt_cat_list_sort_by" id="shop_opt_cat_list_sort_by" class="form-select form-select-sm">
										<option value=""><?php echo $BL['be_admin_tmpl_default']; ?></option>
										<option value="shopprod_name1 ASC"<?php is_selected('shopprod_name1 ASC', $opt_cat_list_sort_by); ?>><?php echo $content['shop']['module_lang']['cp_sort_name_asc']; ?></option>
										<option value="shopprod_name1 DESC"<?php is_selected('shopprod_name1 DESC', $opt_cat_list_sort_by); ?>><?php echo $content['shop']['module_lang']['cp_sort_name_desc']; ?></option>
										<option value="shopprod_ordernumber ASC"<?php is_selected('shopprod_ordernumber ASC', $opt_cat_list_sort_by); ?>><?php echo $content['shop']['module_lang']['cp_sort_ordernumber_asc']; ?></option>
										<option value="shopprod_ordernumber DESC"<?php is_selected('shopprod_ordernumber DESC', $opt_cat_list_sort_by); ?>><?php echo $content['shop']['module_lang']['cp_sort_ordernumber_desc']; ?></option>
										<option value="shopprod_created DESC"<?php is_selected('shopprod_created DESC', $opt_cat_list_sort_by); ?>><?php echo $content['shop']['module_lang']['cp_sort_created_desc']; ?></option>
										<option value="shopprod_created ASC"<?php is_selected('shopprod_created ASC', $opt_cat_list_sort_by); ?>><?php echo $content['shop']['module_lang']['cp_sort_created_asc']; ?></option>
										<option value="shopprod_track_view DESC"<?php is_selected('shopprod_track_view DESC', $opt_cat_list_sort_by); ?>><?php echo $content['shop']['module_lang']['cp_sort_views_desc']; ?></option>
									</select>
								</div>
							</div>

							<div class="form-group align-items-center row g-2 mb-2">
								<label for="shop_opt_image_list_width" class="col-sm-3 col-form-label text-end"><?php echo $content['shop']['module_lang']['cp_image_list_size']; ?></label>
								<div class="col-sm-5">
									<div class="input-group input-group-sm">
										<input type="text" name="shop_opt_image_list_width" id="shop_opt_image_list_width" value="<?php echo html($opt_image_list_width); ?>" class="form-control form-control-sm text-center" placeholder="<?php echo $BL['be_cnt_width']; ?>" size="4" />
										<span class="input-group-text">&times;</span>
										<input type="text" name="shop_opt_image_list_height" id="shop_opt_image_list_height" value="<?php echo html($opt_image_list_height); ?>" class="form-control form-control-sm text-center" placeholder="<?php echo $BL['be_cnt_height']; ?>" size="4" />
										<span class="input-group-text">px</span>
									</div>
								</div>
							</div>

							<div class="form-group align-items-center row g-2 mb-2">
								<label for="shop_opt_image_list_crop" class="col-sm-3 col-form-label text-end"><?php echo $content['shop']['module_lang']['cp_image_list_crop']; ?></label>
								<div class="col-sm-5">
									<select name="shop_opt_image_list_crop" id="shop_opt_image_list_crop" class="form-select form-select-sm">
										<option value=""><?php echo $BL['be_admin_tmpl_default']; ?></option>
										<option value="1"<?php is_selected('1', $opt_image_list_crop); ?>><?php echo $BL['be_yes']; ?></option>
										<option value="0"<?php is_selected('0', $opt_image_list_crop); ?>><?php echo $BL['be_no']; ?></option>
									</select>
								</div>
							</div>

							<div class="form-group align-items-center row g-2">
								<label for="shop_opt_image_list_lightbox" class="col-sm-3 col-form-label text-end"><?php echo $content['shop']['module_lang']['cp_image_list_lightbox']; ?></label>
								<div class="col-sm-5">
									<select name="shop_opt_image_list_lightbox" id="shop_opt_image_list_lightbox" class="form-select form-select-sm">
										<option value=""><?php echo $BL['be_admin_tmpl_default']; ?></option>
										<option value="1"<?php is_selected('1', $opt_image_list_lightbox); ?>><?php echo $BL['be_yes']; ?></option>
										<option value="0"<?php is_selected('0', $opt_image_list_lightbox); ?>><?php echo $BL['be_no']; ?></option>
									</select>
								</div>
							</div>
						</div>

						<!-- Action: order -->
						<div class="shop-action-group" data-shop-action="order">
							<div class="form-group align-items-center row g-2">
								<label for="shop_opt_order_number_style" class="col-sm-3 col-form-label text-end"><?php echo $content['shop']['module_lang']['cp_order_number_style']; ?></label>
								<div class="col-sm-5">
									<input type="text" name="shop_opt_order_number_style" id="shop_opt_order_number_style" value="<?php echo html($opt_order_number_style); ?>" class="form-control form-control-sm" placeholder="<?php echo $content['shop']['module_lang']['cp_order_number_style_hint']; ?>" />
								</div>
								<div class="col">
									<small class="form-text text-muted"><?php echo $content['shop']['module_lang']['cp_order_number_style_hint']; ?></small>
								</div>
							</div>
						</div>

						<!-- Action: smallcart -->
						<div class="shop-action-group" data-shop-action="smallcart">
							<div class="form-group align-items-center row g-2">
								<label for="shop_opt_cart_url" class="col-sm-3 col-form-label text-end"><?php echo $content['shop']['module_lang']['cp_cart_url']; ?></label>
								<div class="col-sm-5">
									<input type="text" name="shop_opt_cart_url" id="shop_opt_cart_url" value="<?php echo html($opt_cart_url); ?>" class="form-control form-control-sm" placeholder="<?php echo $BL['be_admin_tmpl_default']; ?>" />
								</div>
							</div>
						</div>

					</div>
				</div>
			</div>

			<!-- Accordion Item 2: Custom INI overrides -->
			<div class="accordion-item">
				<h2 class="accordion-header" id="heading_shop_custom_ini">
					<button class="accordion-button collapsed py-2 px-3 fw-semibold" type="button" data-bs-toggle="collapse" data-bs-target="#collapse_shop_custom_ini" aria-expanded="false" aria-controls="collapse_shop_custom_ini">
						<i class="fa-solid fa-code me-2 text-secondary"></i> <?php echo $content['shop']['module_lang']['cp_config_custom']; ?>
						<?php if (trim($content['shop']['shop_config']) !== ''): ?>
							<span class="badge bg-primary ms-2 rounded-pill font-monospace" style="font-size: 10px;">active</span>
						<?php endif; ?>
					</button>
				</h2>
				<div id="collapse_shop_custom_ini" class="accordion-collapse collapse" aria-labelledby="heading_shop_custom_ini" data-bs-parent="#shop_config_accordion">
					<div class="accordion-body p-3">
						<textarea name="shop_config" id="shop_config" class="form-control form-control-sm font-monospace field-sizing-content field-sizing-content-5" rows="4" placeholder="<?php echo html($content['shop']['tmpl_config_raw'] !== '' ? $content['shop']['tmpl_config_raw'] : "cat_all = \"All products\"\ncat_all_pos = bottom\nimage_list_width = 120"); ?>"><?php echo html($content['shop']['shop_config']); ?></textarea>
						<small class="form-text text-muted"><?php echo $content['shop']['module_lang']['cp_config_hint']; ?></small>
						<?php if ($content['shop']['tmpl_config_raw'] !== ''): ?>
							<div class="mt-3">
								<a class="text-decoration-none small text-muted" data-bs-toggle="collapse" href="#collapse_tmpl_config_ref" role="button" aria-expanded="false" aria-controls="collapse_tmpl_config_ref">
									<i class="fa-regular fa-file-code me-1"></i> <?php echo $content['shop']['module_lang']['cp_config_tmpl_keys']; ?> <i class="fa-solid fa-angle-down fa-xs"></i>
								</a>
								<div class="collapse mt-2" id="collapse_tmpl_config_ref">
									<pre class="bg-light p-2 rounded border small text-muted font-monospace mb-0" style="max-height: 200px; overflow-y: auto; user-select: all;"><?php echo html($content['shop']['tmpl_config_raw']); ?></pre>
								</div>
							</div>
						<?php endif; ?>
					</div>
				</div>
			</div>


		</div>
	</div>
</div>

<?php $BE['BODY_CLOSE'][] = '<script type="text/javascript">
function updateShopActionFields() {
	var currentAction = $("#shop_action").val();
	$("#shop_category_group").toggle(currentAction === "category");
	$(".shop-action-group").each(function() {
		var actions = ($(this).data("shop-action") || "").split(" ");
		$(this).toggle(actions.indexOf(currentAction) !== -1);
	});
}
$(function(){
	$("#shop_action").on("change", updateShopActionFields);
	updateShopActionFields();
});
</script>'; ?>


