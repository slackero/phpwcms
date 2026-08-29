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

// Page / ext. Content
if(!isset($content["page_file"])) {

	$content["page_file"]["source"] = 0;
	$content["page_file"]["pfile"] = '';

}
?>

<div class="form-group align-items-center row g-2">
  <label for="cpage_source_0" class="col-sm-2 col-form-label text-end"><?php echo $BL['be_cnt_pages_from'] ?></label>
  <div class="col">
  <div class="form-check form-check-inline">
      <label class="form-check-label">
		<input class="form-check-input" type="radio" name="cpage_source" id="cpage_source_0" value="0" <?php is_checked(0, $content["page_file"]["source"]) ?>>
		<?php echo $BL['be_cnt_pages_fromfile'] ?>
      </label>
	</div>
  <div class="form-check form-check-inline">
  	<label class="form-check-label">
    	<input class="form-check-input" type="radio" name="cpage_source" value="1" <?php is_checked(1, $content["page_file"]["source"]) ?>>
    	<?php echo $BL['be_cnt_pages_manually'] ?>
  	</label>
	</div>
  </div>
</div>

<div class="form-group align-items-center row g-2">
  <label for="cpage_custom" class="col-sm-2 col-form-label text-end"><?php echo $BL['be_cnt_pages_cust'] ?></label>
  <div class="col">
    <input name="cpage_custom" type="text" class="form-control form-control-sm" id="cpage_custom" value="<?php echo  html($content["page_file"]["pfile"]) ?>">
  </div>
</div>

<div class="form-group row g-2">
  <label class="col-sm-2 col-form-label text-end"><?php echo $BL['be_cnt_pages_select'] ?></label>
  <div class="col">
    <div style="width:100%; height:200px; overflow:auto; border: 1px solid #d9d9d9;"><?php

echo '<table class="table table-sm">';

// browse pages subdirectory

browse_pages_dir($phpwcms['content_path'].'pages');

function browse_pages_dir($dir) {

	$pc = 0;
	$da = array(); //directory array
	$fa = array(); //file array
	if(is_dir($dir)) {
		$ph = opendir($dir);
		while($pf = readdir($ph)) {
   			if(substr($pf, 0, 1) !== '.') {

				if(is_dir($dir.'/'.$pf)) {

					$da[] = $pf; //add $pf to folder array for current dir

				} elseif( preg_match('/(\.html|\.htm|\.txt|\.php|\.inc|\.tmpl)$/i', $pf) ) {

					$fa[] = $pf; //add $pf to file array for current dir

				}
			}
		}
		closedir($ph);

		// list files
		if(count($fa)) {
			$x = 0;
			foreach($fa as $value) {
				if(!$x) {
					echo "\n<tr bgcolor=\"#E7E8EB\"><td colspan=\"2\">";
					echo '&nbsp;&nbsp;<strong>'.html($dir);
					echo "</strong></td></tr>\n";
					echo '';
				}
				echo "\n<tr><td align=\"center\">";
				echo '<input name="cpage_file" type="radio" value="'.html($dir.'/'.$value).'" ';

				if($GLOBALS['content']['page_file']['pfile'] == ($dir.'/'.$value)) {
					echo 'checked="checked" ';
				}
				echo '/>';
				echo '</td><td><strong>';
				echo str_replace(' ', '&nbsp;', html($value));
				echo '</strong></td></tr>';
				$x++;
			}
			echo '';
		}

		// check all subdirs
		if(count($da)) {
			foreach($da as $value) browse_pages_dir($dir.'/'.$value);
		}

	}
}

echo "\n<tr><td width=\"25\">";
echo '</td><td width="99%"></td></tr>';
echo "\n</table>";

?></div>
      <?php if (empty($phpwcms['enable_inline_php'])): ?>
      <div class="text-danger mt-2"><?php echo $BL['be_cnt_pages_php_render_warning']; ?></div>
      <?php endif; ?>
  </div>
</div>

