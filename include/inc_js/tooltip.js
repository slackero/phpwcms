// Tooltips for phpwcms Backend

window.addEvent('domready', function(){
									 
	/* setup tooltips */
	var as = [];

	$$('input').each(function(a){
		if (a.getAttribute('title')) as.push(a);
	});
	
	new Tips(as, {
			 maxTitleChars: 25, 
			 offsets: {'x': 2, 'y': 5},
			 });
									 
	});