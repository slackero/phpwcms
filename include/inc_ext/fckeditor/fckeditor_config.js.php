<?php

// The base file for implementing 
// a custom FCKeditor config
// optimized for phpwcms
$phpwcms = array();
$_root = rtrim( str_replace("\\", '/', dirname(dirname(dirname(dirname(__FILE__)))) ), '/' );

// send correct content-type/mime-type
define('CUSTOM_CONTENT_TYPE', 'Content-Type: application/x-javascript');

require_once ($_root.'/config/phpwcms/conf.inc.php');
require_once ($_root.'/include/inc_lib/default.inc.php');


?>
FCKConfig.BaseHref = '<?php echo PHPWCMS_URL ?>' ;
FCKConfig.ProcessHTMLEntities    = <?php echo (PHPWCMS_CHARSET != 'iso-8859-1' && PHPWCMS_CHARSET != 'iso-8859-15') ? 'false' : 'true' ?> ;
FCKConfig.Plugins.Add( 'autogrow' );
FCKConfig.AutoGrowMax = 500;

// default FCKeditor toolbars, but removed unnecessary buttons
FCKConfig.ToolbarSets["Default"] = [

	['Source','Preview','-','Templates'],

	['Cut','Copy','Paste','PasteText','PasteWord'],

	['Undo','Redo','-','Find','Replace','-','SelectAll','RemoveFormat'],

	['Form','Checkbox','Radio','TextField','Textarea','Select','Button','ImageButton','HiddenField'],
	'/',
	['Bold','Italic','Underline','StrikeThrough','-','Subscript','Superscript'],
	['OrderedList','UnorderedList','-','Outdent','Indent','Blockquote','CreateDiv'],
	['JustifyLeft','JustifyCenter','JustifyRight','JustifyFull'],
	['Link','Unlink','Anchor'],
	['Image','Flash','Table','Rule','Smiley','SpecialChar','PageBreak'],
	'/',
	['Style','FontFormat','FontName','FontSize'],
	['TextColor','BGColor'],
	['FitWindow','ShowBlocks','-','About']		// No comma for the last row.
] ;

FCKConfig.ToolbarSets["Basic"] = [
	['Bold','Italic','-','OrderedList','UnorderedList','-','Link','Unlink','-','About']
] ;

// an optimized FCKeditor Toolbar for phpwcms
FCKConfig.ToolbarSets["phpwcms_default"] = [
	['Source','FitWindow','Preview','ShowBlocks','-','Templates'],
	['Cut','Copy','Paste','PasteText','PasteWord'],
	['Undo','Redo','-','Find','Replace','-','SelectAll','RemoveFormat'],
	['About'],
	'/',
	['Bold','Italic','Underline','StrikeThrough','-','Subscript','Superscript','-',
	'TextColor','BGColor','-',
	'OrderedList','UnorderedList','Outdent','Indent','-',
	'JustifyLeft','JustifyCenter','JustifyRight','JustifyFull','Blockquote','CreateDiv'],
	['Link','Unlink','Anchor'],
	['Rule','Image','Flash','Table','SpecialChar'],
	['Style'],
	'/',
	['FontFormat','FontName','FontSize']
] ;

// a minimized FCKeditor Toolbar for phpwcms
FCKConfig.ToolbarSets["phpwcms_basic"] = [
	['Bold','Italic','Underline','OrderedList','UnorderedList','Outdent','Indent','JustifyLeft','JustifyCenter','JustifyRight','JustifyFull','Link','Unlink','TextColor','BGColor','Image'],
	['FitWindow','Source']
] ;

<?php

	// enhancement for phpwcms filebrowser support
	// based on concept and work of Markus Köhl <www.pagewerkstatt.ch> (Sept. 2009)

	if(!empty($phpwcms['FCK_FileBrowser'])) {

?>
FCKConfig.LinkBrowserURL = '<?php echo PHPWCMS_URL ?>filebrowser.php?opt=10';
FCKConfig.LinkBrowserWindowWidth	= 380;
FCKConfig.ImageBrowserURL = '<?php echo PHPWCMS_URL ?>filebrowser.php?opt=11';
FCKConfig.ImageBrowserWindowWidth  = 380;
FCKConfig.FlashBrowserURL = '<?php echo PHPWCMS_URL ?>filebrowser.php?opt=10';
FCKConfig.FlashBrowserWindowWidth  = 380;

<?php	}	?>