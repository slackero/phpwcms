/*
	Copyright (c) 2003-2009, CKSource - Frederico Knabben. All rights reserved.
	For licensing, see LICENSE.html or http://ckeditor.com/license
	
	Adopted for phpwcms, Oliver Georgi
*/

CKEDITOR.editorConfig = function( config )
{
	// Set CKEditor UI color
	config.uiColor = '#cccccc';
	
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
