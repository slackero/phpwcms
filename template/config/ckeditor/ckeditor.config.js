/**
 * @license Copyright (c) 2003-2012, CKSource - Frederico Knabben. All rights reserved.
 * For licensing, see LICENSE.html or http://ckeditor.com/license
 *
 * Adopted for phpwcms, Oliver Georgi
 * Default CKEditor configuration in phpwcms backend
 */

CKEDITOR.editorConfig = function( config ) {
	// Define changes to default configuration here.
	// For the complete reference:
	// http://docs.ckeditor.com/#!/api/CKEDITOR.config

	// The toolbar groups arrangement, optimized for two toolbar rows.
	// http://nightly.ckeditor.com/latest/ckeditor/samples/plugins/toolbar/toolbar.html
	config.toolbarGroups = [
		{ name: 'clipboard',   groups: [ 'clipboard', 'undo' ] },
		{ name: 'editing',     groups: [ 'find', 'selection' ] }, //, 'spellchecker'
		{ name: 'links' },
		{ name: 'document',	   groups: [ 'mode', 'document', 'doctools' ] },
		{ name: 'tools' },
		{ name: 'about' },

		//{ name: 'forms' },

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
	config.removeButtons = 'Copy,Iframe,Flash,Smiley,PageBreak,FontSize,Save,Print,NewPage,Preview,Templates,PasteFromWord,PasteText';

	config.width = 538;
	config.height = 400;

	config.extraPlugins = 'magicline';

	config.toolbarCanCollapse = true;
	config.toolbarStartupExpanded = true;

	//config.removePlugins = 'resize';


	config.forcePasteAsPlainText = true;
	config.pasteFromWordRemoveFontStyles = true;
	config.pasteFromWordRemoveStyles = true;
	config.pasteFromWordPromptCleanup = true;

	//config.contentsCss = 'template/config/ckeditor/ckeditor.custom.css';
	//config.protectedSource.push( /<i[\s\S]*?\>/g ); //allows beginning <i> tag
	//config.protectedSource.push( /<\/i[\s\S]*?\>/g ); //allows ending </i> tag

};