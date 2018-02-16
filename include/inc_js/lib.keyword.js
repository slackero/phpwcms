// Keywords: JavaScript

function keyword_submit_edit(obj, id) {
	obj.form.keyword_selected_id.value = id;
	obj.form.keyword_action.value = "edit";
	obj.form.submit();
}
