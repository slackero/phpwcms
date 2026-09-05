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

?>
<h1 class="title mb-3"><?php echo $BLM['listing_title'] ?></h1>

<div class="card">
	<div class="card-body">
		<form action="<?php echo GLOSSARY_HREF ?>&amp;edit=<?php echo $glossary['data']['glossary_id'] ?>" method="post">
			<input type="hidden" name="glossary_id" value="<?php echo $glossary['data']['glossary_id'] ?>" />

			<div class="form-group row align-items-center">
				<label class="col-sm-2 col-form-label text-sm-end fw-bold"><?php echo $BL['be_cnt_last_edited'] ?></label>
				<div class="col-sm-10">
					<span class="text-muted"><?php echo html(date($BL['be_fprivedit_dateformat'], strtotime($glossary['data']['glossary_changed']))) ?></span>
					<?php if(!empty($glossary['data']['glossary_created'])): ?>
						<span class="text-muted ms-3 small">(<?php echo $BL['be_fprivedit_created'] ?>: <?php echo html(date($BL['be_fprivedit_dateformat'], strtotime($glossary['data']['glossary_created']))) ?>)</span>
					<?php endif; ?>
				</div>
			</div>

			<div class="form-group row">
				<label for="glossary_title" class="col-sm-2 col-form-label text-sm-end fw-bold"><?php echo $BLM['glossary_title'] ?></label>
				<div class="col-sm-10">
					<input name="glossary_title" type="text" id="glossary_title" class="form-control form-control-sm<?php if(!empty($glossary['error']['glossary_title'])) echo ' is-invalid'; ?>" value="<?php echo html($glossary['data']['glossary_title']) ?>" maxlength="1000" />
				</div>
			</div>

			<div class="form-group row">
				<label for="glossary_keyword" class="col-sm-2 col-form-label text-sm-end fw-bold"><?php echo $BLM['glossary_keyword'] ?></label>
				<div class="col-sm-10">
					<input name="glossary_keyword" type="text" id="glossary_keyword" class="form-control form-control-sm<?php if(!empty($glossary['error']['glossary_keyword'])) echo ' is-invalid'; ?>" value="<?php echo html($glossary['data']['glossary_keyword']) ?>" maxlength="200" />
				</div>
			</div>

			<div class="form-group row">
				<label for="glossary_tag" class="col-sm-2 col-form-label text-sm-end fw-bold"><?php echo $BLM['glossary_token'] ?></label>
				<div class="col-sm-10">
					<input name="glossary_tag" type="text" id="glossary_tag" class="form-control form-control-sm" value="<?php echo html($glossary['data']['glossary_tag']) ?>" maxlength="220" />
				</div>
			</div>

			<div class="form-group row">
				<label class="col-sm-2 col-form-label text-sm-end fw-bold"><?php echo $BLM['glossary_text'] ?></label>
				<div class="col-sm-10">
					<?php
					$wysiwyg_editor = array(
						'value'		=> $glossary['data']['glossary_text'],
						'field'		=> 'glossary_text',
						'height'	=> '400px',
						'width'		=> '100%',
						'rows'		=> '15',
						'editor'	=> $_SESSION["WYSIWYG_EDITOR"],
						'lang'		=> 'en'
					);
					include PHPWCMS_ROOT.'/include/inc_lib/wysiwyg.editor.inc.php';
					?>
				</div>
			</div>

			<div class="form-group row">
				<div class="col-sm-10 offset-sm-2">
					<div class="form-check form-switch mb-2">
						<input type="checkbox" class="form-check-input" role="switch" name="glossary_highlight" id="glossary_highlight" value="1"<?php is_checked($glossary['data']['glossary_highlight'], 1) ?> />
						<label class="form-check-label" for="glossary_highlight"><?php echo $BLM['highlight_descr'] ?></label>
					</div>
					<div class="form-check form-switch">
						<input type="checkbox" class="form-check-input" role="switch" name="glossary_status" id="glossary_status" value="1"<?php is_checked($glossary['data']['glossary_status'], 1) ?> />
						<label class="form-check-label" for="glossary_status"><?php echo $BL['be_cnt_activated'] ?></label>
					</div>
				</div>
			</div>

			<div class="form-group row mt-4 mb-0">
				<div class="col-sm-10 offset-sm-2">
					<button name="submit" type="submit" class="btn btn-sm btn-blue me-1"><i class="fa-solid fa-rotate me-1"></i> <?php echo empty($glossary['data']['glossary_id']) ? $BL['be_admin_fcat_button2'] : $BL['be_article_cnt_button1'] ?></button>
					<button name="save" type="submit" class="btn btn-sm btn-blue ms-1"><i class="fa-solid fa-check me-1"></i> <?php echo $BL['be_article_cnt_button3'] ?></button>
					<a href="<?php echo decode_entities(GLOSSARY_HREF) ?>&amp;edit=0" class="btn btn-sm btn-blue ms-3"><i class="fa-solid fa-plus me-1"></i> <?php echo ucfirst($BL['be_msg_new']) ?></a>
					<a href="<?php echo decode_entities(GLOSSARY_HREF) ?>" class="btn btn-sm btn-danger ms-3"><i class="fa-solid fa-times me-1"></i> <?php echo $BL['be_admin_struct_close'] ?></a>
				</div>
			</div>
		</form>
	</div>
</div>
