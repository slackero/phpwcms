// Add FancyBox to fancybox Links
$(function(){

	// set by default
	//var fancy_width  = '75%';
	//var fancy_height = '75%';
	
	// API documentation: http://fancybox.net/api
	$("a.fancybox,a[rel=fancybox]").fancybox({
		//onStart: function(){ fancy_width = Math.max( $(window).width() - 80, 955); fancy_height = Math.max( $(window).height() - 112, 675); },
		width: 950,
		height: 650,
		autoScale: false,
		transitionIn: 'fade',
		transitionOut: 'fade',
		type: 'iframe',
		overlayColor: '#666',
		titleShow: true,
		titlePosition: 'inside', // 'outside', 'inside' or 'over'
		showNavArrows : false,
		scrolling: 'no'
	});

});