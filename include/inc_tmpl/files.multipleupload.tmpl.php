<?php
/*************************************************************************************
   Copyright notice
   
   (c) 2002-2012 Oliver Georgi <oliver@phpwcms.de> // All rights reserved.
 
   This script is part of PHPWCMS. The PHPWCMS web content management system is
   free software; you can redistribute it and/or modify it under the terms of
   the GNU General Public License as published by the Free Software Foundation;
   either version 2 of the License, or (at your option) any later version.
  
   The GNU General Public License can be found at http://www.gnu.org/copyleft/gpl.html
   A copy is found in the textfile GPL.txt and important notices to the license 
   from the author is found in LICENSE.txt distributed with these scripts.
  
   This script is distributed in the hope that it will be useful, but WITHOUT ANY 
   WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A
   PARTICULAR PURPOSE.  See the GNU General Public License for more details.
 
   This copyright notice MUST APPEAR in all copies of the script!
*************************************************************************************/

// ----------------------------------------------------------------
// obligate check for phpwcms constants
if (!defined('PHPWCMS_ROOT')) {
   die("You Cannot Access This Script Directly, Have a Nice Day.");
}
// ----------------------------------------------------------------


// initialize Mootools
initMultipleUpload();

?>
<h1 class="title" id="swiffStore"><?php echo $BL['be_file_multiple_upload'] ?></h1>

<form action="<?php echo PHPWCMS_URL?>include/inc_act/ajax_uploader.php?<?php echo session_name().'='.session_id(); ?>" method="post" enctype="multipart/form-data" id="upload-form">

<table border="0" cellpadding="0" cellspacing="0" summary="" class="width440 tdbottom10">
<tr>
	<td>

	<div id="upload-buttons" style="display:none;">
		<p>
    		<a href="#" id="upload-browse" class="grey_button"><?php echo $BL['be_files_browse']; ?></a>
			<a href="#" id="upload-clear" class="grey_button"><?php echo $BL['fancyupload_clear_list'] ?></a>
			<a href="#" id="upload-start" class="grey_button"><?php echo $BL['be_files_upload']; ?></a>
		</p>
		<div class="upload-title">
			<strong class="overall-title">&nbsp;</strong><br /><img src="img/ajax/bar.gif" class="progress overall-progress" alt="" />
		</div>
		<div class="upload-title">
			<strong class="current-title">&nbsp;</strong><br /><img src="img/ajax/bar.gif" class="progress current-progress" alt="" />
		</div>
		<div class="current-text"></div>
    </div>
	<ul class="upload-queue" id="upload-queue"><li style="display:none">&nbsp;</li></ul>
    
	</td>
	</tr>
</table>

<div id="upload-fallback">
	this text should not be shown...
	<input type="file" name="Filedata" />
</div>
</form>
<script type="text/javascript">
window.addEvent('domready', function(){

	var up = new FancyUpload2( $('upload-buttons'), $('upload-queue'), {
									 
		verbose: true,
		path: '<?php echo PHPWCMS_URL; ?>include/inc_js/mootools/FancyUpload/Swiff.Uploader.swf',
		url: $('upload-form').action,
		typeFilter: {
			'<?php echo $BL['be_ctype_images']; ?> (*.jpg, *.jpeg, *.gif, *.png)': '*.jpg; *.jpeg; *.gif; *.png;',
			'<?php echo $BL['be_ftptakeover_all']; ?> (*.*)': '*.*;'
			},
		target: 'upload-browse',
		fileSizeMax: <?php echo $phpwcms['file_maxsize']; ?>,
		onLoad: function() {
			$('upload-buttons').style.display = 'block';
			$('upload-fallback').destroy();
			
			this.target.addEvents({
				click: function() {
					return false;
				},
				mouseenter: function() {
					this.addClass('hover');
				},
				mouseleave: function() {
					this.removeClass('hover');
					this.blur();
				},
				mousedown: function() {
					this.focus();
				}
			});
			
			$('upload-clear').addEvent('click', function() {
				up.remove();
				return false;
			});

			$('upload-start').addEvent('click', function() {
				up.start(); 
				return false;
			});
		},

		onSelectFail: function(files) {
			files.each(function(file) {
				new Element('li', {
					'class': 'validation-error',
					html: file.validationErrorMessage || file.validationError,
					title: MooTools.lang.get('FancyUpload', 'removeTitle'),
					events: {
						click: function() {
							this.destroy();
						}
					}
				}).inject(this.list, 'top');
			}, this);
		},

		onFileSuccess: function(file, response) {
			var json = new Hash(JSON.decode(response, true) || {});
			
			if (json.get('status') == '1') {
				file.element.addClass('file-success');
				file.info.set('html', '<strong><?php echo $BL['fancyupload_file_uploaded'] ?><'+'/strong>');
			} else {
				file.element.addClass('file-failed');
				file.info.set('html', '<strong><?php echo $BL['fancyupload_file_error'] ?>:<'+'/strong> ' + (json.get('error') ? (json.get('error') + ' (' + json.get('code') + ')') : response));
			}
		},
	
		
		/**
		 * onFail is called when the Flash movie got bashed by some browser plugin
		 * like Adblock or Flashblock.
		 */
		onFail: function(error) {
			switch (error) {
				case 'hidden': // works after enabling the movie and clicking refresh
					alert('<?php echo $BL['fancyupload_adblock_error'] ?>');
					break;
				case 'blocked': // This no *full* fail, it works after the user clicks the button
					alert('<?php echo $BL['fancyupload_flashblock_error'] ?>');
					break;
				case 'empty': // Oh oh, wrong path
					alert('<?php echo $BL['fancyupload_required_error'] ?>');
					break;
				case 'flash': // no flash 9+ :(
					alert('<?php echo $BL['fancyupload_flash_error'] ?>')
			}
		}

	});

});

//-->
</script>
