/*
	Copyright (c) 2003-2009, CKSource - Frederico Knabben. All rights reserved.
	For licensing, see LICENSE.html or http://ckeditor.com/license
	
	Adopted for phpwcms, Oliver Georgi
*/

CKEDITOR.editorConfig = function( config )
{
	// Set CKEditor UI color
	config.uiColor = '#cccccc';
	
	// Set editor default height
	config.height = 400;
	
	// Set AutoGrow: Editor input area will autofit the content
	// http://docs.cksource.com/ckeditor_api/symbols/CKEDITOR.config.html#.autoGrow_maxHeight
	config.autoGrow_maxHeight = 0;
	config.autoGrow_minHeight = 400;
	
	config.toolbar_phpwcms = [
        ['Source','Maximize', 'ShowBlocks'],
		['Undo','Redo','-','Cut','Copy','Paste','PasteText','PasteFromWord','-','Find','Replace','-','SelectAll','RemoveFormat'],
		'/',
		['JustifyLeft','JustifyCenter','JustifyRight','JustifyBlock'],
		['Bold','Italic','Underline','Strike','-','Subscript','Superscript'],
		['NumberedList','BulletedList','-','Outdent','Indent','Blockquote','CreateDiv'],
		['Link','Unlink','Anchor'],
		['Image','Table','HorizontalRule','Smiley','SpecialChar','PageBreak'],
		'/',
		['Styles','Format','Font','FontSize'],
		['TextColor','BGColor']
	];
	
};