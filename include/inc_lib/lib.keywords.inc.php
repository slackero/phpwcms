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

// keyword specific functions


function backend_list_keywords() {

	$list  = '<form name="keywordListing" action="' . html(BE_CURRENT_URL) . '" method="post" id="keywordListing">' . LF;
	$list .= '<div class="card shadow-sm mb-4">' . LF;
	$list .= '	<div class="card-header d-flex justify-content-between align-items-center py-2">' . LF;
	$list .= '		<h5 class="mb-0 fw-bold"><i class="fa fa-tags me-2 text-primary"></i>' . ($GLOBALS['BL']['be_admin_keywords'] ?? 'Keywords') . '</h5>' . LF;
	$list .= '		<div>' . LF;
	$list .= '			<button type="button" class="btn btn-sm btn-blue fw-bold me-2" onclick="keyword_submit_action(this, 0, \'edit\');"><i class="fa fa-plus me-1"></i>' . ($GLOBALS['BL']['be_newsletter_new'] ?? 'New Keyword') . '</button>' . LF;
	$list .= '			<button type="button" class="btn btn-sm btn-danger confirm-link" data-confirm="' . ($GLOBALS['BL']['be_cnt_delete_confirm'] ?? 'Delete selected items?') . '" onclick="keyword_submit_action(this, 0, \'delete\');"><i class="far fa-trash-alt me-1"></i>' . ($GLOBALS['BL']['be_cnt_delete'] ?? 'Delete Selected') . '</button>' . LF;
	$list .= '		</div>' . LF;
	$list .= '	</div>' . LF;
	$list .= '	<div class="card-body">' . LF;
	$list .= '		<div class="table-responsive">' . LF;
	$list .= '			<table class="table table-sm table-hover mb-0">' . LF;
	$list .= '				<thead class="thead-light">' . LF;
	$list .= '					<tr>' . LF;
	$list .= '						<th style="width: 40px;" class="text-center"><input type="checkbox" id="checkAllKeywords" onclick="toggleKeywordCheckboxes(this);" /></th>' . LF;
	$list .= '						<th style="width: 60px;">ID</th>' . LF;
	$list .= '						<th>Keyword Name</th>' . LF;
	$list .= '						<th class="text-end" style="width: 100px;">Actions</th>' . LF;
	$list .= '					</tr>' . LF;
	$list .= '				</thead>' . LF;
	$list .= '				<tbody>' . LF;

	$sql		 = "SELECT * FROM ".DB_PREPEND."phpwcms_keyword WHERE keyword_trash=0 ORDER BY keyword_name";
	$keywords	 = _dbQuery($sql);

	if (!empty($keywords[0]['keyword_id'])) {
		foreach ($keywords as $value) {
			$list .= '					<tr>' . LF;
			$list .= '						<td class="text-center"><input type="checkbox" class="keyword-checkbox" value="1" name="check[' . $value['keyword_id'] . ']" id="check_' . $value['keyword_id'] . '" /></td>' . LF;
			$list .= '						<td><span class="badge badge-light border">' . $value['keyword_id'] . '</span></td>' . LF;
			$list .= '						<td><a href="#" onclick="keyword_submit_action(this, ' . $value['keyword_id'] . ', \'edit\'); return false;" class="fw-bold text-dark">' . html($value['keyword_name']) . '</a></td>' . LF;
			$list .= '						<td class="text-end text-nowrap">' . LF;
			$list .= '							<button type="button" class="btn btn-sm btn-blue py-0 px-1 me-1" onclick="keyword_submit_action(this, ' . $value['keyword_id'] . ', \'edit\');" title="Edit"><i class="fa fa-pencil-alt"></i></button>' . LF;
			$list .= '							<button type="button" class="btn btn-sm btn-danger py-0 px-1" onclick="if(confirm(\'' . ($GLOBALS['BL']['be_cnt_delete_confirm'] ?? 'Delete keyword?') . '\')) keyword_submit_action(this, ' . $value['keyword_id'] . ', \'delete_single\');" title="Delete"><i class="far fa-trash-alt"></i></button>' . LF;
			$list .= '						</td>' . LF;
			$list .= '					</tr>' . LF;
		}
	} else {
		$list .= '					<tr><td colspan="4" class="text-muted p-3">No keywords available.</td></tr>' . LF;
	}

	$list .= '				</tbody>' . LF;
	$list .= '			</table>' . LF;
	$list .= '		</div>' . LF;
	$list .= '	</div>' . LF;
	$list .= '</div>' . LF;

	$list .= '<input type="hidden" name="keyword_selected_id" value="0" />' . LF;
	$list .= '<input type="hidden" name="keyword_action" value="" />' . LF;
	$list .= '</form>' . LF;

	return $list;

}

function backend_edit_keywords() {

	$list		 = '';
	$keyword_id	 = empty($_POST['keyword_selected_id']) ? 0 : intval($_POST['keyword_selected_id']);
	$msg         = '';
	$msg_type    = 'info';

	// UPDATE or INSERT keyword
	if (isset($_POST['send_update']) || isset($_POST['send_insert'])) {

		$input = backend_getKeywordPostValues();

		if (empty($input['keyword_name'])) {
			$msg = 'Please enter a valid keyword name.';
			$msg_type = 'danger';
		} elseif (!empty($_POST['send_update']) && $keyword_id > 0) {

			$sql 	 = "UPDATE ".DB_PREPEND."phpwcms_keyword SET ";
			$sql	.= "keyword_name=" . _dbEscape($input['keyword_name']) ." ";
			$sql	.= "WHERE keyword_id=".$keyword_id." ";
			$sql	.= "AND keyword_name!=" . _dbEscape($input['keyword_name']) ." LIMIT 1";

			_dbQuery($sql, 'UPDATE');
			headerRedirect(PHPWCMS_URL . 'phpwcms.php?' . get_token_get_string() . '&do=admin&p=8');

		} else {

			// Check uniqueness
			$sql  	 = "SELECT * FROM ".DB_PREPEND."phpwcms_keyword WHERE keyword_trash=0 AND keyword_name=" . _dbEscape($input['keyword_name']);
			$check	 = _dbQuery($sql);

			if (empty($check[0])) {

				$sql  = "INSERT INTO ".DB_PREPEND."phpwcms_keyword SET ";
				$sql .= "keyword_name=" . _dbEscape($input['keyword_name']);

				$result = _dbQuery($sql, 'INSERT');
				if (isset($result['INSERT_ID'])) {
					headerRedirect(PHPWCMS_URL . 'phpwcms.php?' . get_token_get_string() . '&do=admin&p=8');
				}

			} else {
				$msg = 'No new keyword created. Keyword name must be unique.';
				$msg_type = 'danger';
			}
		}

	}

	$keyword_name = '';

	if ($keyword_id > 0) {
		$sql		 = "SELECT * FROM ".DB_PREPEND."phpwcms_keyword WHERE keyword_trash=0 AND keyword_id=" . $keyword_id." LIMIT 1";
		$keyword	 = _dbQuery($sql);
		if (isset($keyword[0]['keyword_name'])) {
			$keyword_name = $keyword[0]['keyword_name'];
		}
	}

	$list .= '<form name="keywordEditing" action="' . html(BE_CURRENT_URL) . '" method="post">' . LF;
	$list .= '<div class="card shadow-sm mb-4">' . LF;
	$list .= '	<div class="card-header fw-bold py-2">' . LF;
	$list .= '		<i class="fa fa-tag me-2 text-primary"></i>' . ($keyword_id ? 'Edit Keyword' : 'New Keyword') . LF;
	$list .= '	</div>' . LF;
	$list .= '	<div class="card-body">' . LF;

	if (!empty($msg)) {
		$list .= '		<div class="alert alert-' . $msg_type . ' mb-3">' . $msg . '</div>' . LF;
	}

	$list .= '		<div class="form-group row mb-0">' . LF;
	$list .= '			<label for="keyword_name" class="col-sm-3 col-form-label text-sm-end fw-bold">Keyword Name:</label>' . LF;
	$list .= '			<div class="col-sm-7">' . LF;
	$list .= '				<input type="text" name="keyword_name" id="keyword_name" class="form-control form-control-sm" value="' . html($keyword_name) . '" maxlength="250" autofocus />' . LF;
	$list .= '			</div>' . LF;
	$list .= '		</div>' . LF;

	$list .= '	</div>' . LF;
	$list .= '	<div class="card-footer text-end">' . LF;

	if ($keyword_id > 0) {
		$list .= '		<button type="submit" name="send_update" class="btn btn-sm btn-blue fw-bold me-2"><i class="fa fa-rotate me-1"></i>Update</button>' . LF;
	} else {
		$list .= '		<button type="submit" name="send_insert" class="btn btn-sm btn-blue fw-bold me-2"><i class="fa fa-plus me-1"></i>Create</button>' . LF;
	}

	$list .= '		<button type="button" class="btn btn-sm btn-danger ms-3" onclick="location.href=\'phpwcms.php?do=admin&amp;p=8\';"><i class="fa fa-times me-1"></i>' . ($GLOBALS['BL']['be_newsletter_button_cancel'] ?? 'Cancel') . '</button>' . LF;
	$list .= '	</div>' . LF;
	$list .= '</div>' . LF;

	$list .= '<input type="hidden" name="keyword_selected_id" value="' . $keyword_id . '" />' . LF;
	$list .= '<input type="hidden" name="keyword_action" value="edit" />' . LF;
	$list .= '</form>' . LF;

	return $list;

}

function backend_delete_keywords() {

	if (!empty($_POST['keyword_selected_id'])) {

		$delete_id = intval($_POST['keyword_selected_id']);
		$sql = "UPDATE ".DB_PREPEND."phpwcms_keyword SET keyword_trash=1 WHERE keyword_id=".$delete_id." LIMIT 1";
		_dbQuery($sql, 'UPDATE');

	} elseif (!empty($_POST['check']) && is_array($_POST['check'])) {

		$delete_ids = array_map('intval', array_keys($_POST['check']));
		if (!empty($delete_ids)) {
			$sql = "UPDATE ".DB_PREPEND."phpwcms_keyword SET keyword_trash=1 WHERE keyword_id IN (" . implode(',', $delete_ids) . ")";
			_dbQuery($sql, 'UPDATE');
		}

	}

	headerRedirect(PHPWCMS_URL . 'phpwcms.php?' . get_token_get_string() . '&do=admin&p=8');

}

function backend_getKeywordPostValues() {

	$value = array();
	$value['keyword_name']	= isset($_POST['keyword_name']) ? clean_slweg($_POST['keyword_name']) : '';
	return $value;

}

