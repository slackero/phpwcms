<?php
/*************************************************************************************
   Copyright notice
   
   (c) 2002-2010 Oliver Georgi (oliver@phpwcms.de) // All rights reserved.
 
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

<form action="<?php echo PHPWCMS_URL?>include/inc_act/ajax_uploader.php?<?php echo session_name().'='.session_id(); ?>" method="post" id="upload">

<table border="0" cellpadding="0" cellspacing="0" summary="" class="width440 tdbottom10">
<tr>
	<td>
	<ul class="upload-queue" id="upload-queue">
		<li style="display: none">&nbsp;</li>
	</ul>
	</td>
	</tr>
</table>

<div class="upload_button">
	<input type="file" name="Filedata" id="upload-filedata" />
	&nbsp;&nbsp;
	<input name="submit" type="submit" class="v12" id="profile-submit" value="<?php echo $BL['be_files_upload']; ?>" />
</div>

</form>

<script type="text/javascript">
<!--

window.addEvent('domready', function(){

	var upload = new FancyUpload( $('upload-filedata'), {
		swf: '<?php echo PHPWCMS_URL; ?>include/inc_js/mootools/FancyUpload/Swiff.Uploader.swf',
		queueList: 'upload-queue',
		container: $('swiffStore'),
		types: {
			'<?php echo $BL['be_ctype_images']; ?> (*.jpg, *.jpeg, *.gif, *.png)': '*.jpg; *.jpeg; *.gif; *.png;',
			'<?php echo $BL['be_ftptakeover_all']; ?> (*.*)': '*.*;'
			},
		limitSize: <?php echo $phpwcms['file_maxsize']; ?>,
		txtBrowse: '<?php echo $BL['be_files_browse']; ?>'
	});

});

//-->
</script>
