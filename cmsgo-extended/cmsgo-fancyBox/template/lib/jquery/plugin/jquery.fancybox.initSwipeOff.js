/**
 * fancyBox without Swipe support for cmsGO!
 **/

// initialize fancyBox with Swipe enabled
$(function() {

	// select all items based on lightbox selector
	var fancyBoxImages		= $("a[rel^='lightbox']");
	var fancyBoxImagesCount	= fancyBoxImages.length;
	// for all options visit http://fancyapps.com/fancybox/#docs

	if(fancyBoxImagesCount) {

		fancyBoxImages.fancybox({
			// openEffect	: 'none',
			// closeEffect	: 'none'
			type: 'image'
		});

	}

	var fancyBoxOthers = $("a.fancybox-custom");
	var fancyBoxOthersCount = fancyBoxOthers.length;

	if(fancyBoxOthersCount) {

		fancyBoxOthers.fancybox({
			// openEffect	: 'none',
			// closeEffect	: 'none'
		});
	}

});