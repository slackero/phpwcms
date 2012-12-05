/**
 * @license Copyright (c) 2003-2012, CKSource - Frederico Knabben. All rights reserved.
 * For licensing, see LICENSE.html or http://ckeditor.com/license
 *
 * CP Tabs loads this configration
 */

CKEDITOR.editorConfig = function( config ) {
	// Define changes to default configuration here.
	// For the complete reference:
	// http://docs.ckeditor.com/#!/api/CKEDITOR.config
	//config.plugins = 'dialogui,dialog,about,basicstyles,clipboard,button,toolbar,list,indent,enterkey,entities,floatingspace,wysiwygarea,fakeobjects,link,pastetext,undo';

	// The toolbar groups arrangement, optimized for two toolbar rows.
	// http://nightly.ckeditor.com/latest/ckeditor/samples/plugins/toolbar/toolbar.html
	config.toolbarGroups = [
		{ name: 'clipboard',   groups: [ 'clipboard', 'undo' ] },
		{ name: 'editing',     groups: [ 'find', 'selection' ] }, //, 'spellchecker'
		{ name: 'links' },
		{ name: 'tools' },
		{ name: 'about' },
		//{ name: 'forms' },
		//{ name: 'document',	   groups: [ 'mode', 'document', 'doctools' ] },
		//'/',
		{ name: 'basicstyles', groups: [ 'basicstyles', 'cleanup' ] },
		{ name: 'paragraph',   groups: [ 'align', 'list', 'indent', 'blocks'] },
		//{ name: 'others' },
		//'/',
		{ name: 'insert' },
		{ name: 'colors' },
		{ name: 'styles' }
		
	];
	
	// Remove some buttons, provided by the standard plugins, which we don't
	// need to have in the Standard(s) toolbar.
	config.removeButtons = 'Iframe,Flash,Smiley,PageBreak,FontSize';
	config.toolbarCanCollapse = true;
	config.toolbarStartupExpanded = false;
	//config.removePlugins = 'elementspath,resize';
		
	config.width = 538;
	config.height = 200;
	
	//config.forcePasteAsPlainText = true;
	//config.pasteFromWordRemoveFontStyles = true;
	//config.pasteFromWordRemoveStyles = true;
	//config.pasteFromWordPromptCleanup = true;
	
	//config.contentsCss = 'assets/config/ckeditor/ckeditor.custom.css';
};
