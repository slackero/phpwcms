$(function() {

	var $glossary_item_active = null;
	var $glossary_list_detail = $('#glossary_list_detail');

	if($glossary_list_detail.length) {

		$('.glossary_list_entry').each(function(index){
			var $this	= $(this);
			var $link	= $('h2:first a', $this);
			var content	= $('.glossary_description', $this).html();

			$link.click(function(event) {
				event.preventDefault();
				if($glossary_item_active !== index) {
					if($glossary_item_active !== null) {
						$glossary_list_detail.fadeOut(400, function() {
							$glossary_list_detail.html('<h3>' + $link.html() + '</h3>' + content);
							$glossary_list_detail.fadeIn();
						});
					} else {
						$glossary_list_detail.hide().html('<h3>' + $link.html() + '</h3>' + content).fadeIn();
					}
					$glossary_item_active = index;
				}
			});

		});
	}

});