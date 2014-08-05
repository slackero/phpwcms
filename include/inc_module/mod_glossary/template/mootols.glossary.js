window.addEvent('domready', function() {

	$$('div.glossary_list_entry').each(function(r){ 

		var h2     = r.getFirst();
		var ahref  = h2.getFirst();
		var div    = h2.getNext();
		
		if(div) {
			var inner  = div.innerHTML;
		
			ahref.addEvent('click', function(e){
				e = new Event(e);
				e.stop();
				$('onclicktext').innerHTML = '<h3>'+ahref.innerHTML+'</h3>'+inner;
			});
		
			div.remove();
		}
	});

});