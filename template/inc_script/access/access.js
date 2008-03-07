window.addEvent('domready', function() {
	
	
	if(Cookie.get('phpwcmsAgree') == '1') {
	
		hasAgreed();
		
	} else {
		
		
		var overlay = new Element('div', {'id': 'accessOverlay'}).injectInside(document.body);
		$('access_dialog').addClass('accessDialog');
		
		$('access_save').setStyle('display','');
		overlay.setStyles({'top': 0, 'height': window.getScrollHeight(), 'opacity': .75});
		window.addEvent('resize', function(){ overlay.setStyles({'top': 0, 'height': window.getScrollHeight()}); });
	
		var agree_checkbox = $('access_agree');
		agree_checkbox.rel = 0;
										 
		$('agree_button_reject').addEvent('click', function(){agree_checkbox.rel = 1} );
		$('agree_button_agree').addEvent('click', function(){agree_checkbox.rel = 2} );
										 
		$('access_form').addEvent('submit', function(r) {
													 
			
			var r = new Event(r).stop();
													 
			if(agree_checkbox.rel == 1) {
	
				Cookie.remove('phpwcmsAgree');
				document.location.href = redirect;
			
			} else if(agree_checkbox.rel == 2 && agree_checkbox.checked==false) {
			
				alert(erroralert);
			
			} else {
			
				Cookie.set('phpwcmsAgree', '1', {duration: 0, path: '/'});
				window.removeEvent('resize');
				hasAgreed();
				overlay.remove();
				
			}
			
		});
	
	}

});


function hasAgreed() {
	
	$('access_dialog').setStyle('display','none');
	$('access_save').setStyle('display','');

}
