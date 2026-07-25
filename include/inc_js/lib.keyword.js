// Keywords: JavaScript

function keyword_submit_action(obj, id, action) {
	var form = obj.form || document.getElementById('keywordListing');
	if (form) {
		form.keyword_selected_id.value = id;
		form.keyword_action.value = action;
		form.submit();
	}
}

function keyword_submit_edit(obj, id) {
	keyword_submit_action(obj, id, 'edit');
}

function keyword_submit_delete(obj) {
	keyword_submit_action(obj, 0, 'delete');
}

function toggleKeywordCheckboxes(master) {
	var checkboxes = document.querySelectorAll('.keyword-checkbox');
	checkboxes.forEach(function(cb) {
		cb.checked = master.checked;
	});
}
