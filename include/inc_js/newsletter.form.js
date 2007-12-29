function showNewsletterTemplateData(tvar) {
	
	if(tvar == '' || !nltemplate[tvar]) {
		document.getElementById("newsletterTemplateInfo").innerHTML = '';
		return true;
	}
	
	var tdata = "";
	if(nltemplate[tvar]['imgsrc'] != '') {
		tdata = '<img src="'+nltemplate[tvar]['imgsrc']+'" alt="" border="0" align="left" style="margin:2px 5px 5px 0" />';
	}
	if(nltemplate[tvar]['title'] != '') {
		tdata = tdata+'<strong>'+nltemplate[tvar]['title']+'</strong> <br />';
	}
	if(nltemplate[tvar]['description'] != '') {
		tdata = tdata+nltemplate[tvar]['description'];
	}
	document.getElementById("newsletterTemplateInfo").innerHTML = tdata;
	return true;
}