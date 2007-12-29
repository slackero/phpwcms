
function addFile(obj,text,value) {
	if(obj!=null && obj.options!=null) {
		newOpt = new Option(text, value);
		obj.options.length++;
		obj.options[obj.length-1].text  = newOpt.text;
		obj.options[obj.length-1].value = newOpt.value;
		obj.options[obj.length-1].selected = false;
	}
}
