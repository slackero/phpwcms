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

//recipe

unset($_SESSION['filebrowser_image_target']);

// base values
if(empty($content['recipe']['calorificvalue_add']))		$content['recipe']['calorificvalue_add']	= '';
if(empty($content['recipe']['preparation']))			$content['recipe']['preparation']			= '';
if(empty($content['recipe']['ingredients']))			$content['recipe']['ingredients']			= '';
if(empty($content['recipe']['time_add']))				$content['recipe']['time_add']				= '';
if(empty($content['recipe']['category']))				$content['recipe']['category']				= '';
if(empty($content['recipe']['severity']))				$content['recipe']['severity']				= 1;


// retrieve all available keywords
$content['recipe']['get_keywords'] = _dbQuery('SELECT acontent_text FROM '.DB_PREPEND.'phpwcms_articlecontent WHERE acontent_type=26 AND acontent_trash=0');
$content['recipe']['all_keywords'] = '';
if($content['recipe']['get_keywords']) {
	foreach($content['recipe']['get_keywords'] as $temp_val) {
		if($temp_val['acontent_text']) {
			if($content['recipe']['all_keywords']) $content['recipe']['all_keywords'] .= ', ';
			$content['recipe']['all_keywords'] .= $temp_val['acontent_text'];
		}
	}
}
$content['recipe']['all_keywords'] = convertStringToArray($content['recipe']['all_keywords']);

?>

<?php if(count($content['recipe']['all_keywords'])): ?>
	<div class="form-group form-row">
		<div class="col-sm-10 offset-sm-2">
			<div class="form-inline">
				<select name="ph1" id="ph1" class="custom-select custom-select-sm mr-2" onchange="insertAtCursorPos(document.articlecontent.recipe_category, ', ' + document.articlecontent.ph1.options[document.articlecontent.ph1.selectedIndex].value);">
					<?php
					foreach($content['recipe']['all_keywords'] as $temp_val) {
						$temp_val = html($temp_val);
						echo '					<option value="' . $temp_val . '">' . $temp_val . '</option>' . LF;
					}
					?>
				</select>
				<button type="button" class="btn btn-sm btn-light border" onclick="insertAtCursorPos(document.articlecontent.recipe_category, ', ' + document.articlecontent.ph1.options[document.articlecontent.ph1.selectedIndex].value);"><i class="fas fa-plus"></i></button>
			</div>
		</div>
	</div>
<?php endif; ?>

<div class="form-group form-row">
	<label for="recipe_category" class="col-sm-2 col-form-label text-right"><?php echo $BL['be_ftptakeover_keywords']; ?></label>
	<div class="col-sm-10">
		<textarea name="recipe_category" id="recipe_category" rows="2" class="form-control form-control-sm field-sizing-content field-sizing-content-2"><?php echo html($content['recipe']['category']); ?></textarea>
	</div>
</div>

<div class="form-group form-row">
	<label for="recipe_template" class="col-sm-2 col-form-label text-right"><?php echo $BL['be_admin_struct_template']; ?></label>
	<div class="col-sm-4">
		<select name="recipe_template" id="recipe_template" class="custom-select form-control form-control-sm">
			<?php
			$tmpllist = get_tmpl_files(PHPWCMS_TEMPLATE . 'inc_cntpart/recipe');
			if (is_array($tmpllist) && count($tmpllist)) {
				foreach ($tmpllist as $val) {
					if (isset($content['recipe']['template']) && $val == $content['recipe']['template']) {
						$selected_val = ' selected="selected"';
					} else {
						$selected_val = '';
					}
					$val = htmlspecialchars($val);
					echo '			<option value="' . $val . '"' . $selected_val . '>' . $val . '</option>' . LF;
				}
			}
			?>
		</select>
	</div>
</div>

<hr />

<div class="form-group form-row">
	<label for="recipe_ingredients" class="col-sm-2 col-form-label text-right">Zutaten</label>
	<div class="col-sm-10">
		<textarea name="recipe_ingredients" id="recipe_ingredients" rows="6" class="form-control form-control-sm field-sizing-content field-sizing-content-6"><?php echo html($content['recipe']['ingredients']); ?></textarea>
	</div>
</div>

<div class="form-group form-row">
	<label for="recipe_time" class="col-sm-2 col-form-label text-right">Zuber.Zeit</label>
	<div class="col-sm-4">
		<div class="form-inline">
			<input name="recipe_time" type="text" id="recipe_time" class="form-control form-control-sm mr-1" style="width: 50px;" value="<?php echo empty($content['recipe']['time']) ? '' : intval($content['recipe']['time']) ?>" onkeyup="this.value=int_only(this.value);" size="5" />
			<span class="mr-2 text-muted small"><?php echo $BL['be_date_minutes'] ?></span>
			<input name="recipe_time_add" type="text" id="recipe_time_add" class="form-control form-control-sm" style="width: 120px;" value="<?php echo html($content['recipe']['time_add']) ?>" placeholder="<?php echo $BL['be_cnt_additional'] ?>" />
		</div>
	</div>
	<label for="recipe_calorificvalue" class="col-sm-2 col-form-label text-right">N&auml;hrwert</label>
	<div class="col-sm-4">
		<div class="form-inline">
			<input name="recipe_calorificvalue" type="text" id="recipe_calorificvalue" class="form-control form-control-sm mr-1" style="width: 50px;" value="<?php echo empty($content['recipe']['calorificvalue']) ? '' : intval($content['recipe']['calorificvalue']) ?>" size="5" onkeyup="this.value=int_only(this.value);" />
			<span class="mr-2 text-muted small">kJ</span>
			<input name="recipe_calorificvalue_add" type="text" id="recipe_calorificvalue_add" class="form-control form-control-sm" style="width: 120px;" value="<?php echo html($content['recipe']['calorificvalue_add']) ?>" placeholder="<?php echo $BL['be_cnt_additional'] ?>" />
		</div>
	</div>
</div>

<div class="form-group form-row">
	<label class="col-sm-2 col-form-label text-right">Schwierigkeit</label>
	<div class="col-sm-10">
		<?php for ($i = 1; $i <= 5; $i++): ?>
			<div class="form-check form-check-inline">
				<input name="recipe_severity" id="recipe_severity_<?php echo $i; ?>" type="radio" value="<?php echo $i; ?>" class="form-check-input" <?php is_checked($i, $content['recipe']['severity']); ?> />
				<label class="form-check-label" for="recipe_severity_<?php echo $i; ?>"><?php echo $i; ?></label>
			</div>
		<?php endfor; ?>
	</div>
</div>

<hr />

<div class="form-group form-row">
	<label class="col-sm-2 col-form-label text-right">Zubereitung</label>
	<div class="col-sm-10">
		<?php
		$wysiwyg_editor = array(
			'value'		=> $content['recipe']['preparation'],
			'field'		=> 'recipe_preparation',
			'height'	=> '250px',
			'width'		=> '100%',
			'rows'		=> '10',
			'editor'	=> $_SESSION['WYSIWYG_EDITOR'],
			'lang'		=> 'en'
		);
		include PHPWCMS_ROOT . '/include/inc_lib/wysiwyg.editor.inc.php';
		?>
	</div>
</div>
