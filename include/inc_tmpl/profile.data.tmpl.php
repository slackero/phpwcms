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


//Auslesen der eventuell f�r den User bereits vorhandenen Detaildaten
//1. Pr�fen, ob �berhaupt ein Profil angelegt ist
$sql = 'SELECT COUNT(*) FROM '.DB_PREPEND.'phpwcms_userdetail WHERE detail_pid='.intval($_SESSION["wcs_user_id"]);

if(_dbQuery($sql, 'COUNT')) {

	//Es sind bereits Daten hinterlegt - diese jetzt auslesen
	$sql = 'SELECT * FROM '.DB_PREPEND.'phpwcms_userdetail WHERE detail_pid='.intval($_SESSION['wcs_user_id']).' LIMIT 1';
	$detail = _dbQuery($sql);
	if(is_array($detail[0])) {
		$detail = $detail[0];
		$form_detail_aktion = 'update_detail';
	} else {
		$form_detail_aktion = 'create_detail';
	}

} else {

	$form_detail_aktion = 'create_detail';

}

if($form_detail_aktion == 'create_detail') {

	$detail = array(

		'detail_title'		=> '',
		'detail_firstname'	=> '',
		'detail_lastname'	=> '',
		'detail_company'	=> '',
		'detail_street'		=> '',
		'detail_add'		=> '',
		'detail_city'		=> '',
		'detail_region'		=> '',
		'detail_zip'		=> '',
		'detail_country'	=> '',
		'detail_fon'		=> '',
		'detail_fax'		=> '',
		'detail_mobile'		=> '',
		'detail_signature'	=> '',
		'detail_notes'		=> '',
		'detail_prof'		=> '',
		'detail_newsletter'	=> 1,
		'detail_public'		=> 1

	);

}

?><form action="phpwcms.php?do=profile&amp;p=1" method="post" name="formprofiledetail" id="formprofiledetail">
<div class="card shadow-sm mb-4">
    <div class="card-header fw-bold py-2">
        <?php echo $BL['be_profile_data_title']; ?>
    </div>
    <div class="card-body">
        <p class="text-muted mb-4"><?php echo $BL['be_profile_data_text']; ?></p>

        <?php if (!empty($detail_updated)): ?>
            <div class="alert alert-danger mb-4">
                <?php echo nl2br(chop($detail_updated)); ?>
            </div>
        <?php endif; ?>

        <div class="form-group row">
            <label for="form_title" class="col-sm-3 col-form-label text-sm-end"><?php echo $BL['be_profile_label_title']; ?>:</label>
            <div class="col-sm-6">
                <input name="form_title" type="text" id="form_title" class="form-control form-control-sm" value="<?php echo html($detail["detail_title"]); ?>" maxlength="50">
            </div>
        </div>

        <div class="form-group row">
            <label for="form_firstname" class="col-sm-3 col-form-label text-sm-end"><?php echo $BL['be_profile_label_firstname']; ?>:</label>
            <div class="col-sm-6">
                <input name="form_firstname" type="text" id="form_firstname" class="form-control form-control-sm" value="<?php echo html($detail["detail_firstname"]); ?>" maxlength="100">
            </div>
        </div>

        <div class="form-group row">
            <label for="form_lastname" class="col-sm-3 col-form-label text-sm-end"><?php echo $BL['be_profile_label_name']; ?>:</label>
            <div class="col-sm-6">
                <input name="form_lastname" type="text" id="form_lastname" class="form-control form-control-sm" value="<?php echo html($detail["detail_lastname"]); ?>" maxlength="100">
            </div>
        </div>

        <div class="form-group row">
            <label for="form_company" class="col-sm-3 col-form-label text-sm-end"><?php echo $BL['be_profile_label_company']; ?>:</label>
            <div class="col-sm-6">
                <input name="form_company" type="text" id="form_company" class="form-control form-control-sm" value="<?php echo html($detail["detail_company"]); ?>" maxlength="100">
            </div>
        </div>

        <div class="form-group row">
            <label for="form_street" class="col-sm-3 col-form-label text-sm-end"><?php echo $BL['be_profile_label_street']; ?>:</label>
            <div class="col-sm-6">
                <input name="form_street" type="text" id="form_street" class="form-control form-control-sm mb-2" value="<?php echo html($detail["detail_street"]); ?>" maxlength="100">
                <input name="form_add" type="text" id="form_add" class="form-control form-control-sm" value="<?php echo html($detail["detail_add"]); ?>" maxlength="100">
            </div>
        </div>

        <div class="form-group row">
            <label for="form_city" class="col-sm-3 col-form-label text-sm-end"><?php echo $BL['be_profile_label_city']; ?>:</label>
            <div class="col-sm-6">
                <input name="form_city" type="text" id="form_city" class="form-control form-control-sm" value="<?php echo html($detail["detail_city"]); ?>" maxlength="100">
            </div>
        </div>

        <div class="form-group row">
            <label for="form_region" class="col-sm-3 col-form-label text-sm-end"><?php echo $BL['be_profile_label_state']; ?>:</label>
            <div class="col-sm-6">
                <input name="form_region" type="text" id="form_region" class="form-control form-control-sm" value="<?php echo html($detail["detail_region"]); ?>" maxlength="100">
            </div>
        </div>

        <div class="form-group row">
            <label for="form_zip" class="col-sm-3 col-form-label text-sm-end"><?php echo $BL['be_profile_label_zip']; ?>:</label>
            <div class="col-sm-3">
                <input name="form_zip" type="text" id="form_zip" class="form-control form-control-sm" value="<?php echo html($detail["detail_zip"]); ?>" maxlength="50">
            </div>
        </div>

        <div class="form-group row">
            <label for="form_country" class="col-sm-3 col-form-label text-sm-end"><?php echo $BL['be_profile_label_country']; ?>:</label>
            <div class="col-sm-6">
                <select name="form_country" id="form_country" class="form-select form-select-sm">
                    <?php echo list_country($detail["detail_country"]); ?>
                </select>
            </div>
        </div>

        <hr class="my-4">

        <div class="form-group row">
            <label for="form_fon" class="col-sm-3 col-form-label text-sm-end"><?php echo $BL['be_profile_label_phone']; ?>:</label>
            <div class="col-sm-6">
                <input name="form_fon" type="text" id="form_fon" class="form-control form-control-sm" value="<?php echo html($detail["detail_fon"]); ?>" maxlength="30">
            </div>
        </div>

        <div class="form-group row">
            <label for="form_fax" class="col-sm-3 col-form-label text-sm-end"><?php echo $BL['be_profile_label_fax']; ?>:</label>
            <div class="col-sm-6">
                <input name="form_fax" type="text" id="form_fax" class="form-control form-control-sm" value="<?php echo html($detail["detail_fax"]); ?>" maxlength="30">
            </div>
        </div>

        <div class="form-group row">
            <label for="form_mobile" class="col-sm-3 col-form-label text-sm-end"><?php echo $BL['be_profile_label_cellphone']; ?>:</label>
            <div class="col-sm-6">
                <input name="form_mobile" type="text" id="form_mobile" class="form-control form-control-sm" value="<?php echo html($detail["detail_mobile"]); ?>" maxlength="30">
            </div>
        </div>

        <hr class="my-4">

        <div class="form-group row">
            <label for="form_signature" class="col-sm-3 col-form-label text-sm-end"><?php echo $BL['be_profile_label_signature']; ?>:</label>
            <div class="col-sm-8">
                <textarea name="form_signature" cols="30" rows="3" id="form_signature" class="form-control form-control-sm"><?php echo html($detail["detail_signature"]); ?></textarea>
            </div>
        </div>

        <div class="form-group row">
            <label for="form_notes" class="col-sm-3 col-form-label text-sm-end"><?php echo $BL['be_profile_label_notes']; ?>:</label>
            <div class="col-sm-8">
                <textarea name="form_notes" cols="30" rows="5" id="form_notes" class="form-control form-control-sm"><?php echo html($detail["detail_notes"]); ?></textarea>
            </div>
        </div>

        <div class="form-group row">
            <label for="select2" class="col-sm-3 col-form-label text-sm-end"><?php echo $BL['be_profile_label_profession']; ?>:</label>
            <div class="col-sm-6">
                <select name="form_prof" id="select2" class="form-select form-select-sm">
                    <?php list_profession($detail["detail_prof"]); ?>
                </select>
            </div>
        </div>

        <hr class="my-4">

        <div class="form-group row">
            <div class="col-sm-3 text-sm-end fw-bold">
                <?php echo $BL['be_profile_label_newsletter']; ?>:
            </div>
            <div class="col-sm-9">
                <div class="form-check">
                    <input name="form_newsletter" type="checkbox" id="form_newsletter" value="1" class="form-check-input" <?php is_checked($detail["detail_newsletter"], "1"); ?>>
                    <label class="form-check-label" for="form_newsletter"><?php echo $BL['be_profile_text_newsletter']; ?></label>
                </div>
            </div>
        </div>

        <div class="form-group row">
            <div class="col-sm-3 text-sm-end fw-bold">
                <?php echo $BL['be_profile_label_public']; ?>:
            </div>
            <div class="col-sm-9">
                <div class="form-check">
                    <input name="form_public" type="checkbox" id="form_public" value="1" class="form-check-input" <?php is_checked($detail["detail_public"], "1"); ?>>
                    <label class="form-check-label" for="form_public"><?php echo $BL['be_profile_text_public']; ?></label>
                </div>
            </div>
        </div>

        <input name="form_aktion" type="hidden" id="form_aktion" value="<?php echo $form_detail_aktion; ?>">
    </div>
    <div class="card-footer text-end">
        <button type="submit" name="Submit" class="btn btn-sm btn-blue fw-bold">
            <i class="fa-solid fa-rotate me-1"></i><?php echo $BL['be_profile_label_button']; ?>
        </button>
    </div>
</div>
</form>

