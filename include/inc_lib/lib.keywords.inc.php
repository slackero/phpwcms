<?php
/**
 * phpwcms content management system
 *
 * @author Oliver Georgi <og@phpwcms.org>
 * @copyright Copyright (c) 2002-2022, Oliver Georgi
 * @license http://opensource.org/licenses/GPL-2.0 GNU GPL-2
 * @link http://www.phpwcms.org
 *
 **/

// ----------------------------------------------------------------
// obligate check for phpwcms constants
if (!defined('PHPWCMS_ROOT')) {
	die("You Cannot Access This Script Directly, Have a Nice Day.");
}
// ----------------------------------------------------------------

// keyword specific functions

$BE['HEADER'][]  = getJavaScriptSourceLink('include/inc_js/lib.keyword.js');

function backend_list_keywords() {

	$list		 = '<form name="keywordListing" action="'.html(BE_CURRENT_URL).'" method="post">' . LF;
	$list		.= LF . '<table cellspacing="0" cellpadding="0" border="0" class="listingTable">' . LF;
	$list		.= '	<tr>' . LF;
	$list		.= '		<th class="checkbox">All</th>' . LF;
	$list		.= '		<th class="entry">Keyword Name</th>' . LF;
	$list		.= '		<th class="actions">&nbsp;</th>' . LF;
	$list		.= '	</tr>' . LF;

	$sql		 = "SELECT * FROM ".DB_PREPEND."phpwcms_keyword WHERE keyword_trash=0 ORDER BY keyword_name";
	$keywords	 = _dbQuery($sql);

	$c			 = 0;

	foreach($keywords as $value) {

		// set alternating class name
		$aclass  = ($c % 2) ? ' class="alternating"' : '';

		$list	.= '	<tr'.$aclass.'>' . LF;
		$list	.= '		<td class="checkbox"><input type="checkbox" value="1" name="check['.$value['keyword_id'].']" id="check_'.$value['keyword_id'].'" /></td>' . LF;
		$list	.= '		<td class="entry">' . html($value['keyword_name']) . '</td>' . LF;
		$list	.= '		<td class="actions"><button type="button" onclick="keyword_submit_edit(this, '.$value['keyword_id'].');">Edit</button></td>' .LF;
		$list	.= '	</tr>' . LF;

		$c++;

	}

	$list		.= '</table>' . LF;
	$list		.= '<input type="hidden" name="keyword_selected_id" value="0" />';
	$list 		.= '<input type="hidden" name="keyword_action" value="" />';
	$list		.= LF . '</form>' . LF;

	return $list;

}

function backend_edit_keywords() {

	$list		 = '';
	$keyword_id	 = empty($_POST['keyword_selected_id']) ? 0 : intval($_POST['keyword_selected_id']);

	// UPDATE keyword
	if(isset($_POST['send_update'])) {

		$update = backend_getKeywordPostValues();

		if(empty($update['keyword_name'])) {
			// False, empty Keyword Name
			$list .= '<p>Proof your input. Keyword name had no value. Value was reset.</p>';
		} else {

			$sql 	 = "UPDATE ".DB_PREPEND."phpwcms_keyword SET ";
			$sql	.= "keyword_name=" . _dbEscape($update['keyword_name']) ." ";
			$sql	.= "WHERE keyword_id=".$keyword_id." ";
			$sql	.= "AND keyword_name!=" . _dbEscape($update['keyword_name']) ." LIMIT 1";

			$update['result'] = _dbQuery($sql, 'UPDATE');

		}

	// INSERT keyword
	} elseif(isset($_POST['send_insert'])) {

		$insert = backend_getKeywordPostValues();

		if(empty($insert['keyword_name'])) {
			// False, empty Keyword Name
			$list .= '<p>Proof your input. Keyword name had no value. Value was reset.</p>';
		} else {

			// 1st check if keyword does not exist
			$sql  	 = "SELECT * FROM ".DB_PREPEND."phpwcms_keyword ";
			$sql	.= "WHERE keyword_trash=0 AND keyword_name=" . _dbEscape($insert['keyword_name']);
			$check	 = _dbQuery($sql);

			if(empty($check[0])) {

				$sql  = "INSERT INTO ".DB_PREPEND."phpwcms_keyword SET ";
				$sql .= "keyword_name=" . _dbEscape($insert['keyword_name']);

				$insert['result'] = _dbQuery($sql, 'INSERT');
				$keyword_id		  = $insert['result']['INSERT_ID'];

			} else {

				$list .= '<p>No new keyword created. Keyword name must be unique.</p>';

			}
		}

	}

	$sql		 = "SELECT * FROM ".DB_PREPEND."phpwcms_keyword WHERE keyword_trash=0 AND keyword_id=" . $keyword_id." LIMIT 1";
	$keyword	 = _dbQuery($sql);

	if(!$keyword) return '<p>No keyword could be found for the given ID</p>';

	$list		.= '<form name="keywordEditing" action="'.html(BE_CURRENT_URL).'" method="post">' . LF;

	// edit values
	$list		.= '<div class="inputText">';
	$list		.= '<label for="keyword_name">Keyword name:</label>';
	$list		.= '<input type="text" name="keyword_name" id="keyword_name" value="'.html($keyword[0]['keyword_name']).'" />';
	$list		.= '</div>' . LF;

	$list		.= '<div class="inputButton">';
	$list		.= '<button type="submit" name="send_update">Update</button>';
	$list		.= '<button type="submit" name="send_insert">New</button>';
	$list		.= '</div>' . LF;

	// hidden values
	$list		.= '<input type="hidden" name="keyword_selected_id" value="'.$keyword_id.'" />';
	$list 		.= '<input type="hidden" name="keyword_action" value="edit" />';
	$list		.= LF . '</form>' . LF;

	return $list;

}

function backend_getKeywordPostValues() {

	$value = array();
	$value['keyword_name']	= isset($_POST['keyword_name']) ? clean_slweg($_POST['keyword_name']) : '';
	return $value;

}
