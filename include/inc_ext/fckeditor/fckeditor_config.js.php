<?php

// The base file for implementing 
// a custom FCKeditor config
// optimized for phpwcms
$phpwcms = array();
$_root = realpath(dirname(__FILE__).'/../../../');
require_once ($_root.'/config/phpwcms/conf.inc.php');
require_once ($_root.'/include/inc_lib/default.inc.php');

// send correct content-type/mime-type
header('Content-Type: application/x-javascript');


?>
FCKConfig.BaseHref = '<?php echo PHPWCMS_URL ?>' ;
FCKConfig.ProcessHTMLEntities    = <?php echo (PHPWCMS_CHARSET != 'iso-8859-1' && PHPWCMS_CHARSET != 'iso-8859-15') ? 'false' : 'true' ?> ;

// default FCKeditor toolbars, but removed unnecessary buttons
FCKConfig.ToolbarSets["Default"] = [
	['Source','Preview','-','Templates'],
	['Cut','Copy','Paste','PasteText','PasteWord','-','SpellCheck'],
	['Undo','Redo','-','Find','Replace','-','SelectAll','RemoveFormat'],
	['Form','Checkbox','Radio','TextField','Textarea','Select','Button','ImageButton','HiddenField'],
	['Bold','Italic','Underline','StrikeThrough','-','Subscript','Superscript'],
	['OrderedList','UnorderedList','-','Outdent','Indent'],
	['JustifyLeft','JustifyCenter','JustifyRight','JustifyFull'],
	['Link','Unlink','Anchor'],
	['Image','Flash','Table','Rule','Smiley','SpecialChar','PageBreak'],
	'/',
	['Style'],
	['TextColor','BGColor'],
	['FitWindow','-','About'],
	['FontFormat','FontName','FontSize']	
] ;

FCKConfig.ToolbarSets["Basic"] = [
	['Bold','Italic','-','OrderedList','UnorderedList','-','Link','Unlink','-','About']
] ;

// an optimized FCKeditor Toolbar for phpwcms
FCKConfig.ToolbarSets["phpwcms_default"] = [
	['Source','-','FitWindow','Preview','-','Templates'],
	['Cut','Copy','Paste','PasteText','PasteWord'],
	['Undo','Redo','-','Find','Replace','-','SelectAll','RemoveFormat'],
	['About'],
	'/',
	['Bold','Italic','Underline','StrikeThrough','-','Subscript','Superscript','-',
	'TextColor','BGColor','-',
	'OrderedList','UnorderedList','Outdent','Indent','-',
	'JustifyLeft','JustifyCenter','JustifyRight','JustifyFull','-',
	'Link','Unlink','Anchor'],
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

