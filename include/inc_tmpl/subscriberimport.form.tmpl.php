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


$_userInfo['max_file_size'] = return_bytes(@ini_get('upload_max_filesize'));
// select channel
$_userInfo['select_subscr'] = '';
$_userInfo['subscriptions'] = _dbQuery("SELECT * FROM ".DB_PREPEND."phpwcms_subscription ORDER BY subscription_name");

if($_userInfo['subscriptions']) {
  foreach($_userInfo['subscriptions'] as $value) {
    $_userInfo['select_subscr'] .= '
      <div class="form-check">
      <input class="form-check-input" type="checkbox" name="subscribe_select['.$value['subscription_id'].
      ']" id="subscribe_select'.$value['subscription_id'].'" value="'.$value['subscription_id'].'"';
    if(in_array($value['subscription_id'], $_userInfo['subscribe_select'])) {
      $_userInfo['select_subscr'] .= ' checked="checked"';
    }
    $_userInfo['select_subscr'] .= ' />
    	<label class="form-check-label" for="subscribe_select'.$value['subscription_id'].'">
      '.
      html($value['subscription_name']).
      '</label>
      </div>
    ';
  }

  if($_userInfo['select_subscr']) {
    $_userInfo['select_subscr'] = '
        <div class="form-check">
        <input class="form-check-input" type="checkbox" name="subscribe_all" id="subscribe_all" value="1"'.is_checked($_userInfo['subscribe_all'], 1, 1, 0).' />
        <label class="form-check-label" for="subscribe_all">'.$BL['be_newsletter_allsubscriptions'].'</label></div>

      ' .  $_userInfo['select_subscr'];
  }
}

?>
<form action="phpwcms.php?do=messages&amp;p=4&amp;import=1" method="post" name="importsubscriber" id="importsubscriber" enctype="multipart/form-data">

<div class="card mb-4">
<div class="card-header"><h2><i class="fa fa-upload"></i> <?php echo $BL['be_newsletter_importtitle'] ?></h2></div>
<div class="card-body">

  <?php
  if(!empty($_userInfo['csvError'])) {
    echo '<div class="alert alert-danger">'.html($_userInfo['csvError']).'</div>';
  }
  ?>

  <div class="alert alert-info"><strong><?php echo $BL['be_newsletter_shouldbe1'] ?></strong><br />emailA;nameA<br />&quot;emailB&quot;;&quot;nameB&quot;</div>

  <hr />


<!--
ORIGINAL

 <div class="form-group mb-3"><?php
    echo $BL['be_newsletter_selectCSV'];
    echo '<input type="hidden" name="MAX_FILE_SIZE" value="'.$_userInfo['max_file_size'].'" />';
    ?>
    <input name="cvsfile" type="file" style="width:300px;" size="35" required/>
  </div>

-->
  <div class="form-group row g-2 align-items-center">
      <label class="col-sm-2 col-form-label text-end">
        <?php echo $BL['be_newsletter_selectCSV']; echo '<input type="hidden" name="MAX_FILE_SIZE" value="'.$_userInfo['max_file_size'].'" />'; ?>
      </label>
      <div class="col-sm-3">
        <input name="cvsfile" type="file" class="custom-file-input" id="csvfile" required />
        <label class="custom-file-label" for="csvfile"></label>
      </div>
  </div>

  <div class="form-group align-items-center row g-2 has-danger">
      <label for="delimeter" class="col-sm-2 col-form-label text-end">
        <?php echo $BL['be_newsletter_delimeter'] ?>
      </label>
    <div class="col-sm-auto">
      <input name="delimeter" class="form-control form-control-sm form-control-danger" id="delimeter" value="<?php echo html($_userInfo['delimeter']) ?>" size="5"  type="text" required>
    </div>
    <div class="col">
        <label class="col-form-label">
        <?php echo $BL['be_newsletter_shouldbe2'] ?>
      </label>
    </div>
  </div>

<?php
if($_userInfo['select_subscr']) {
  echo '<div class="form-group row g-2">'.LF.'<div class="col-form-label col-sm-2 text-end pt-0">'.$BL['be_cnt_subscription'].'</div>'.LF;
  echo '<div class="col">'.$_userInfo['select_subscr'].'</div>'.LF.'</div>';
}
?>

  <div class="form-group row g-2 align-items-center">
    <label class="col-sm-2 col-form-label text-end"><?php echo $BL['be_ftptakeover_status'] ?></label>
      <div class="col">
      	<div class="form-check">
					<input class="form-check-input" name="subscribe_active" id="subscribe_active" type="checkbox" value="1" <?php is_checked($_userInfo['subscribe_active'], 1) ?>/>
					<label class="form-check-label" for="subscribe_active"><?php echo $BL['be_cnt_activated'] ?></label>
				</div>
      </div>
    </div>

    <div class="form-group align-items-center mt-4 mb-0 text-center text-sm-start">
      <button type="submit" name="submitimport" id="submitimport" value="1" class="btn btn-sm btn-blue"><i class="fa fa-file-import me-1"></i> <?php echo $BL['be_newsletter_newimport'] ?></button>
      <a class="btn btn-sm btn-danger ms-3" href="phpwcms.php?do=messages&amp;p=4"><i class="fa fa-times"></i> <?php echo $BL['be_admin_struct_close'] ?></a>
    </div>

  </div>
</div>
</form>

<script>
$('input:file').change(
  function(e){
    $("label[for='csvfile']").text(e.target.files[0].name);
});
</script>
