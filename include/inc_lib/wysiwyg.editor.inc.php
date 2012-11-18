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

if(!isset($wysiwyg_editor['value']))	$wysiwyg_editor['value']	= '';
if(!isset($wysiwyg_editor['field']))	$wysiwyg_editor['field']	= 'wysiwyg_editor';
if(!isset($wysiwyg_editor['height']))	$wysiwyg_editor['height']	= '350px';
if(!isset($wysiwyg_editor['width']))	$wysiwyg_editor['width']	= '440px';
if(!isset($wysiwyg_editor['rows']))		$wysiwyg_editor['rows']		= '15';
if(!isset($wysiwyg_editor['editor'])){
	$wysiwyg_editor['editor']	= 1;
	if(isset($_SESSION["WYSIWYG_EDITOR"])) $wysiwyg_editor['editor'] = $_SESSION["WYSIWYG_EDITOR"];
}
$wysiwyg_editor['lang']	= isset($_SESSION["wcs_user_lang"]) ? $_SESSION["wcs_user_lang"] : 'en';

if($wysiwyg_editor['editor'] == 2) {

	//load FCKeditor
	include_once(PHPWCMS_ROOT.'/include/inc_ext/fckeditor/fckeditor.php');

	$oFCKeditor = new FCKeditor($wysiwyg_editor['field']);
	$oFCKeditor->BasePath 							= PHPWCMS_URL.'include/inc_ext/fckeditor/';
	$oFCKeditor->Config['CustomConfigurationsPath']	= PHPWCMS_URL.'include/inc_ext/fckeditor/fckeditor_config.js.php' ;
	
	$oFCKeditor->Value 								= $wysiwyg_editor['value'];
	$oFCKeditor->Width 								= str_replace('px', '', $wysiwyg_editor['width']);
	$oFCKeditor->Height 							= str_replace('px', '', $wysiwyg_editor['height']);
	$oFCKeditor->ToolbarSet							= $_SESSION['WYSIWYG_TEMPLATE'];
	$oFCKeditor->Create();

} elseif($wysiwyg_editor['editor'] == 1) {
	
	$BE['HEADER']['ckeditor.js']	= getJavaScriptSourceLink('include/inc_ext/ckeditor/ckeditor.js');
	$BE['HEADER']['ckeditor.styles.default.js']	= getJavaScriptSourceLink(TEMPLATE_PATH.'config/ckeditor/ckeditor.styles.default.js');
	
	$wysiwyg_editor['template'] = $_SESSION['WYSIWYG_TEMPLATE'];
	if(empty($wysiwyg_editor['template']) || $wysiwyg_editor['template'] == 'phpwcms_basic') {
		$wysiwyg_editor['template'] = 'Basic';
	} elseif($wysiwyg_editor['template'] == 'Default') {
		$wysiwyg_editor['template'] = 'Full';
	}
	
	// simple textarea - no WYSIWYG editor
	echo '<textarea name="'.$wysiwyg_editor['field'].'" rows="'.$wysiwyg_editor['rows'].'" id="'.$wysiwyg_editor['field'].'">';
	echo html_specialchars($wysiwyg_editor['value']).'</textarea>';
	echo '<script type="text/javascript">
		var phpwcms_ckeditor_config = "'.PHPWCMS_URL.TEMPLATE_PATH.'config/ckeditor/";
		CKEDITOR.replace("'.$wysiwyg_editor['field'].'", {
			customConfig: phpwcms_ckeditor_config+"ckeditor.config.js",
			language: "'.$wysiwyg_editor['lang'].'",
			toolbar: "'.$wysiwyg_editor['template'].'"';
	if (!empty($phpwcms['FCK_FileBrowser'])) {
		echo ',
			filebrowserBrowseUrl: "'.PHPWCMS_URL.'/filebrowser.php?opt=16",
			filebrowserImageBrowseUrl : "'.PHPWCMS_URL.'/filebrowser.php?opt=17",
			filebrowserWindowWidth: "640", filebrowserWindowHeight: "480"';
	}
		echo '
		});
	</script>';

} else {

	// simple textarea - no WYSIWYG editor
	echo '<textarea name="'.$wysiwyg_editor['field'].'" rows="'.$wysiwyg_editor['rows'];
	echo '" class="v12" id="'.$wysiwyg_editor['field'].'" ';
	echo 'style="width:'.$wysiwyg_editor['width'].';height:'.$wysiwyg_editor['height'].';">';
	echo html_specialchars($wysiwyg_editor['value']).'</textarea>';

}

?>