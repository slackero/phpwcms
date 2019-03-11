function showImageAds() {
	var obj = getObjectById('adcampaign_image');
	var val = obj.options[obj.selectedIndex].value;
	if(val && val != '') {
		var boxw = getFieldById('adcampaign_width').value;
		var boxh = getFieldById('adcampaign_height').value;
		var set  = true;
		if(boxh+boxw==0) {
			boxh = 350;
			boxw = 770;
			set  = false;
		}

		var wh = parseInt(boxh, 10)+50;
		var ww = parseInt(boxw, 10)+30;

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

		var boxw = getFieldById('adcampaign_width').value;
		var boxh = getFieldById('adcampaign_height').value;
		var set  = true;
		if(boxh+boxw==0) {
			boxh = 350;
			boxw = 770;
			set  = false;
		}

		var wh = parseInt(boxh, 10)+50;
		var ww = parseInt(boxw, 10)+30;

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

function showFlashAds() {
	var obj = getObjectById('adcampaign_flash');
	var val = obj.options[obj.selectedIndex].value;
	if(val && val != '') {

		var boxw = getFieldById('adcampaign_width').value;
		var boxh = getFieldById('adcampaign_height').value;
		var set  = true;
		if(boxh+boxw==0) {
			boxh = 350;
			boxw = 770;
			set  = false;
		}

		var wh = parseInt(boxh, 10)+50;
		var ww = parseInt(boxw, 10)+30;

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
		tmp.write('<div id="box">');

		tmp.write('<object width="'+boxw+'" height="'+boxh+'"><param name="movie" value="'+adsPath+val+'" /><param name="wmode" value="transparent" /><embed src="'+adsPath+val+'" type="application/x-shockwave-flash" wmode="transparent" width="'+boxw+'" height="'+boxh+'" /></object>');

		tmp.write('</div>');
		tmp.write('<p align="center"><a href="javascript:self.close()">close</a> the popup.</p>');
		tmp.write('</body></html>');
		tmp.close();

	}
}
