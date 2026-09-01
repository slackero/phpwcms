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

// Plain Text
if(empty($content['ctext_format'])) {
  $content['ctext_format'] = 'plain';
}

initAceEditor();
$ace_mode = ($content['ctext_format'] === 'markdown') ? 'markdown' : (($content['ctext_format'] === 'textile') ? 'textile' : 'text');

?>

<div class="form-group align-items-center row g-2">
  <label for="template" class="col-sm-2 col-form-label text-end"><?php echo $BL['be_admin_struct_template']; ?></label>
  <div class="col-sm-4">
    <select name="template" id="template" class="form-select form-select-sm">
<?php
  echo '<option value="">'.$BL['be_admin_tmpl_default'].'</option>'.LF;

// templates for frontend login
$tmpllist = get_tmpl_files(PHPWCMS_TEMPLATE.'inc_cntpart/plaintext');
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

<div class="form-group align-items-center row g-2">
  <label class="col-sm-2 col-form-label text-end"><?php echo $BL['be_media_format']; ?></label>
  <div class="col">
    <div class="btn-group btn-group-sm" role="group" aria-label="ctext_format">
      <input name="ctext_format" type="radio" id="ctext_format0" value="plain" class="btn-check" autocomplete="off" <?php is_checked('plain', $content['ctext_format']); ?> />
      <label class="btn btn-outline-blue" for="ctext_format0"><?php echo $BL['be_ctype_plaintext'] ?></label>

      <input name="ctext_format" type="radio" id="ctext_format1" value="markdown" class="btn-check" autocomplete="off" <?php is_checked('markdown', $content['ctext_format']); ?> />
      <label class="btn btn-outline-blue" for="ctext_format1">MarkDown</label>

      <input name="ctext_format" type="radio" id="ctext_format2" value="textile" class="btn-check" autocomplete="off" <?php is_checked('textile', $content['ctext_format']); ?> />
      <label class="btn btn-outline-blue" for="ctext_format2">Textile</label>
    </div>
  </div>
</div>

<div class="form-group row g-2">
  <label for="ctext" class="col-sm-2 col-form-label text-end"><?php echo $BL['be_cnt_plaintext']; ?></label>
  <div class="col">
    <textarea name="ctext" rows="12" class="form-control form-control-sm field-sizing-content field-sizing-content-10 code-editor" data-mode="<?php echo $ace_mode; ?>" id="ctext"><?php
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
