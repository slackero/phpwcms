function showImageAds() {
	var obj = document.getElementById('adcampaign_image');
	var val = obj.options[obj.selectedIndex].value;
	if(val && val != '') {
		var boxw = parseInt(getFieldById('adcampaign_width').value, 10) || 0;
		var boxh = parseInt(getFieldById('adcampaign_height').value, 10) || 0;
		var set  = true;
		if(boxh+boxw==0) {
			boxh = 350;
			boxw = 770;
			set  = false;
		}

		var wh = boxh+50;
		var ww = boxw+30;

		var newwindow2 = window.open('','name','width='+ww+',height='+wh+',scrollbars=yes,resizable=yes');
		var tmp = newwindow2.document;
		tmp.write('<html><head><title>banner image</title>');
		tmp.write('<style type="text/css">\n');
		tmp.write('body{font-family:Verdana,Arial,Helvetica,sans-serif;font-size:11px;color:#000000;background-color:#FFFFFF;}\n');
		tmp.write('a{color:#000000;}');
		tmp.write('\n</style></head><body>');
		tmp.write('<p align="center"><img src="'+adsPath+val+'" border="0" ');
		if(set) {
			tmp.write('width="'+boxw+'" height="'+boxh+'" ');
		}
		tmp.write('alt="" /></p>');
		tmp.write('<p align="center"><a href="javascript:self.close()">close</a> the popup.</p>');
		tmp.write('</body></html>');
		tmp.close();
	}
}

function showHtmlAds() {
	var val = getFieldById('adcampaign_html').value;
	if(val && val != '') {

		var boxw = parseInt(getFieldById('adcampaign_width').value, 10) || 0;
		var boxh = parseInt(getFieldById('adcampaign_height').value, 10) || 0;
		var set  = true;
		if(boxh+boxw==0) {
			boxh = 350;
			boxw = 770;
			set  = false;
		}

		var wh = boxh+50;
		var ww = boxw+30;

		var newwindow2 = window.open('','name','width='+ww+',height='+wh+',scrollbars=yes,resizable=yes');
		var tmp = newwindow2.document;
		tmp.write('<html><head><title>banner image</title>');
		tmp.write('<style type="text/css">\n');
		tmp.write('body{font-family:Verdana,Arial,Helvetica,sans-serif;font-size:11px;color:#000000;background-color:#FFFFFF;}\n');
		tmp.write('a{color:#000000;}\n');
		if(set) {
			tmp.write('#box{width:'+boxw+'px;height:'+boxh+'px;border:1px solid #000000;margin:0 auto 0 auto;padding:0;overflow:hidden;}');
		} else {
			tmp.write('#box{display:compact;border:1px solid #000000;margin:0 auto 0 auto;padding:5px;overflow:hidden;}');
		}
		tmp.write('\n</style></head><body>');
		tmp.write('<div id="box">'+val+'</div>');
		tmp.write('<p align="center"><a href="javascript:self.close()">close</a> the popup.</p>');
		tmp.write('</body></html>');
		tmp.close();

	}
}

function showVideoAds() {
	var obj = document.getElementById('adcampaign_video');
	var val = obj ? obj.options[obj.selectedIndex].value : '';
	if(val && val != '') {

		var boxw = parseInt(getFieldById('adcampaign_width').value, 10) || 0;
		var boxh = parseInt(getFieldById('adcampaign_height').value, 10) || 0;
		var set  = true;
		if(boxh+boxw==0) {
			boxh = 350;
			boxw = 770;
			set  = false;
		}

		var wh = boxh+50;
		var ww = boxw+30;

		var newwindow2 = window.open('','name','width='+ww+',height='+wh+',scrollbars=yes,resizable=yes');
		var tmp = newwindow2.document;
		tmp.write('<html><head><title>video banner</title>');
		tmp.write('<style type="text/css">\n');
		tmp.write('body{font-family:Verdana,Arial,Helvetica,sans-serif;font-size:11px;color:#000000;background-color:#FFFFFF;margin:0;padding:10px;text-align:center;}\n');
		tmp.write('a{color:#000000;}\n');
		if(set) {
			tmp.write('video{width:'+boxw+'px;height:'+boxh+'px;object-fit:contain;background:#000;}');
		} else {
			tmp.write('video{max-width:100%;height:auto;background:#000;}');
		}
		tmp.write('\n</style></head><body>');
		tmp.write('<p><video src="'+adsPath+val+'" autoplay muted loop playsinline controls></video></p>');
		tmp.write('<p align="center"><a href="javascript:self.close()">close</a> the popup.</p>');
		tmp.write('</body></html>');
		tmp.close();

	}
}

function showHtml5Ads() {
	var obj = document.getElementById('adcampaign_html5');
	var val = obj ? obj.options[obj.selectedIndex].value : '';
	if(val && val != '') {

		var boxw = parseInt(getFieldById('adcampaign_width').value, 10) || 0;
		var boxh = parseInt(getFieldById('adcampaign_height').value, 10) || 0;
		var set  = true;
		if(boxh+boxw==0) {
			boxh = 350;
			boxw = 770;
			set  = false;
		}

		var wh = boxh+50;
		var ww = boxw+30;

		var newwindow2 = window.open('','name','width='+ww+',height='+wh+',scrollbars=yes,resizable=yes');
		var tmp = newwindow2.document;
		tmp.write('<html><head><title>HTML5 banner</title>');
		tmp.write('<style type="text/css">\n');
		tmp.write('body{font-family:Verdana,Arial,Helvetica,sans-serif;font-size:11px;color:#000000;background-color:#FFFFFF;margin:0;padding:10px;text-align:center;}\n');
		tmp.write('a{color:#000000;}\n');
		if(set) {
			tmp.write('iframe{width:'+boxw+'px;height:'+boxh+'px;border:0;overflow:hidden;}');
		} else {
			tmp.write('iframe{width:100%;height:350px;border:0;overflow:hidden;}');
		}
		tmp.write('\n</style></head><body>');
		tmp.write('<p><iframe src="'+adsPath+val+'" frameborder="0" scrolling="no"></iframe></p>');
		tmp.write('<p align="center"><a href="javascript:self.close()">close</a> the popup.</p>');
		tmp.write('</body></html>');
		tmp.close();

	}
}
