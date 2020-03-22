<?php
/**
 * cmsGO!
 *
 * @author Pixels & Points GmbH <info@pixels-points.ch>
 * @copyright Copyright (c) 2002-2020, Pixels & Points GmbH
 * @license https://www.pixels-points.ch/cmsgo-license.html Pixels & Points cmsGO! license
 *
 **/

// ----------------------------------------------------------------
// obligate check for cmsgo constants
if (!defined('CMSGO_ROOT')) {
  die("You Cannot Access This Script Directly, Have a Nice Day.");
}
// ----------------------------------------------------------------

// Plain Text
if(empty($content['ctext_format'])) {
  $content['ctext_format'] = 'plain';
}

?>

<div class="form-group align-items-center form-row">
  <label for="template" class="col-sm-2 col-form-label text-right"><?php echo $BL['be_admin_struct_template']; ?></label>
  <div class="col-sm-4">
    <select name="template" id="template" class="custom-select form-control form-control-sm">
<?php
  echo '<option value="">'.$BL['be_admin_tmpl_default'].'</option>'.LF;

// templates for frontend login
$tmpllist = get_tmpl_files(CMSGO_TEMPLATE.'inc_cntpart/plaintext');
if(is_array($tmpllist) && count($tmpllist)) {
  foreach($tmpllist as $val) {
    $selected_val = (isset($content["template"]) && $val == $content["template"]) ? ' selected="selected"' : '';
    $val = html($val);
    echo '  <option value="' . $val . '"' . $selected_val . '>' . $val . '</option>' . LF;
  }
}
?>
    </select>
  </div>
</div>

<div class="form-group align-items-center form-row">
  <label for="radio" class="col-sm-2 col-form-label text-right"><?php echo $BL['be_media_format']; ?></label>
  <div class="col">
  	<div class="form-check form-check-inline">
      <input name="ctext_format" type="radio" id="ctext_format0" value="plain" class="form-check-input" <?php is_checked('plain', $content['ctext_format']); ?> />
      <label class="form-check-label" for="ctext_format0"><?php echo $BL['be_ctype_plaintext'] ?></label>
		</div>
		<div class="form-check form-check-inline">
			<input name="ctext_format" type="radio" id="ctext_format1" value="markdown" class="form-check-input" <?php is_checked('markdown', $content['ctext_format']); ?> />
			<label class="form-check-label" for="ctext_format1">MarkDown <a href="http://en.wikipedia.org/wiki/Markdown" data-toggle="tooltip" target="_blank" title="Wikipedia: Markdown"><i class="fa fa-info-circle text-blue" aria-hidden="true"></i></a></label>
		</div>
		<div class="form-check form-check-inline">
    	<input name="ctext_format" type="radio" id="ctext_format2" value="textile" class="form-check-input" <?php is_checked('textile', $content['ctext_format']); ?> />
      <label class="form-check-label" for="ctext_format2">Textile <a href="http://en.wikipedia.org/wiki/Textile_%28markup_language%29" data-toggle="tooltip" target="_blank" title="Wikipedia: Textile"><i class="fa fa-info-circle text-blue" aria-hidden="true"></i></a></label>
		</div>
  </div>
</div>

<div class="form-group form-row">
  <label for="ctext" class="col-sm-2 col-form-label text-right"><?php echo $BL['be_cnt_plaintext']; ?></label>
  <div class="col">
    <textarea name="ctext" rows="10" class="form-control form-control-sm" id="ctext"><?php
    if(empty($content["text"])) {

      echo '';

    } else {

      if(substr($content["text"], 0, 1) === LF || substr($content["text"], 0, 1) === "\r") {
        echo ' '; // keep 1st linebreak;
      }
      echo html($content["text"]);

    }

  ?></textarea>
  </div>
</div>
