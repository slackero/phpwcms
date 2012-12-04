<?php
/**
 * phpwcms content management system
 *
 * @author Oliver Georgi <oliver@phpwcms.de>
 * @copyright Copyright (c) 2002-2012, Oliver Georgi
 * @license http://opensource.org/licenses/GPL-2.0 GNU GPL-2
 * @link http://www.phpwcms.de
 *
 **/

// wysiwyg editor

if(empty($wysiwyg_editor['value']))		$wysiwyg_editor['value']	= '';
if(!isset($wysiwyg_editor['field']))	$wysiwyg_editor['field']	= 'wysiwyg_editor';
if(empty($wysiwyg_editor['height']))	$wysiwyg_editor['height']	= '400px';
if(empty($wysiwyg_editor['width']))		$wysiwyg_editor['width']	= '440px';
if(empty($wysiwyg_editor['rows']))		$wysiwyg_editor['rows']		= '15';

if(!isset($wysiwyg_editor['editor'])) {
	$wysiwyg_editor['editor'] = 1;
	if(isset($_SESSION["WYSIWYG_EDITOR"])) {
		$wysiwyg_editor['editor'] = $_SESSION["WYSIWYG_EDITOR"];
	}
}

$wysiwyg_editor['lang']	= isset($_SESSION["wcs_user_lang"]) ? $_SESSION["wcs_user_lang"] : 'en';

if($wysiwyg_editor['editor']) {
	
	$BE['HEADER']['ckeditor.js']		 = getJavaScriptSourceLink('include/inc_ext/ckeditor/ckeditor.js');
	
	// simple textarea - no WYSIWYG editor
	echo '<textarea class="ckeditor" name="'.$wysiwyg_editor['field'].'" rows="'.$wysiwyg_editor['rows'].'" id="'.$wysiwyg_editor['field'].'">';
	echo html_specialchars($wysiwyg_editor['value']).'</textarea>';
	
	echo '<script type="text/javascript">' . LF;
	echo '	CKEDITOR.replace("'.$wysiwyg_editor['field'].'", {' . LF;
	if(is_file(PHPWCMS_TEMPLATE.'config/ckeditor/ckeditor.config.js')) {
		echo '		customConfig: "'.PHPWCMS_URL.TEMPLATE_PATH.'config/ckeditor/ckeditor.config.js",' . LF;
	}
	echo '		language: "'.$wysiwyg_editor['lang'].'"';

	if (!empty($phpwcms['FCK_FileBrowser'])) {
		echo ',' . LF;
		echo '		filebrowserBrowseUrl: "'.PHPWCMS_URL.'filebrowser.php?opt=16",' . LF;
		echo '		filebrowserImageBrowseUrl : "'.PHPWCMS_URL.'filebrowser.php?opt=17",' . LF;
		echo '		filebrowserWindowWidth: "640",' . LF;
		echo '		filebrowserWindowHeight: "480"' . LF;
	}
	echo '	});' . LF;
	echo '</script>';
	

} else {

	// simple textarea - no WYSIWYG editor
	echo '<textarea name="'.$wysiwyg_editor['field'].'" rows="'.$wysiwyg_editor['rows'];
	echo '" class="v12 editor-textarea" id="'.$wysiwyg_editor['field'].'" ';
	echo 'style="width:'.$wysiwyg_editor['width'].';height:'.$wysiwyg_editor['height'].';">';
	echo html_specialchars($wysiwyg_editor['value']).'</textarea>';

}

?>