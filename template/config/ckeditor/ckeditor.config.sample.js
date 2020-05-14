/**
 * @license Copyright (c) 2003-2017, CKSource - Frederico Knabben. All rights reserved.
 * For licensing, see LICENSE.html or http://ckeditor.com/license
 *
 * Adopted for phpwcms, Oliver Georgi
 * Default CKEditor configuration in phpwcms backend
 */

CKEDITOR.editorConfig = function( config ) {
    // Define changes to default configuration here.
    // For the complete reference:
    // http://docs.ckeditor.com/#!/api/CKEDITOR.config

    /* The full default CKEditor toolbar
    config.toolbar = [
        { name: 'document', groups: [ 'mode', 'document', 'doctools' ], items: [ 'Source', '-', 'Save', 'NewPage', 'Preview', 'Print', '-', 'Templates' ] },
        { name: 'clipboard', groups: [ 'clipboard', 'undo' ], items: [ 'Cut', 'Copy', 'Paste', 'PasteText', 'PasteFromWord', '-', 'Undo', 'Redo' ] },
        { name: 'editing', groups: [ 'find', 'selection', 'spellchecker' ], items: [ 'Find', 'Replace', '-', 'SelectAll', '-', 'Scayt' ] },
        { name: 'forms', items: [ 'Form', 'Checkbox', 'Radio', 'TextField', 'Textarea', 'Select', 'Button', 'ImageButton', 'HiddenField' ] },
        '/',
        { name: 'basicstyles', groups: [ 'basicstyles', 'cleanup' ], items: [ 'Bold', 'Italic', 'Underline', 'Strike', 'Subscript', 'Superscript', '-', 'RemoveFormat' ] },
        { name: 'paragraph', groups: [ 'list', 'indent', 'blocks', 'align', 'bidi' ], items: [ 'NumberedList', 'BulletedList', '-', 'Outdent', 'Indent', '-', 'Blockquote', 'CreateDiv', '-', 'JustifyLeft', 'JustifyCenter', 'JustifyRight', 'JustifyBlock', '-', 'BidiLtr', 'BidiRtl', 'Language' ] },
        { name: 'links', items: [ 'Link', 'Unlink', 'Anchor' ] },
        { name: 'insert', items: [ 'Image', 'Flash', 'Table', 'HorizontalRule', 'Smiley', 'SpecialChar', 'PageBreak', 'Iframe' ] },
        '/',
        { name: 'styles', items: [ 'Styles', 'Format', 'Font', 'FontSize' ] },
        { name: 'colors', items: [ 'TextColor', 'BGColor' ] },
        { name: 'tools', items: [ 'Maximize', 'ShowBlocks' ] },
        { name: 'others', items: [ '-' ] },
        { name: 'about', items: [ 'About' ] }
    ];
    */
    /* phpwcms default toolbar setting
    config.toolbar = [
        { name: 'tools', items: ['Maximize', '-', 'Source', '-', 'Undo', 'Redo', '-', 'Paste', 'PasteText', 'PasteFromWord', '-', 'Find', '-', 'ShowBlocks' ] },
        { name: 'links', items: [ 'Link', 'Unlink', 'Anchor' ] },
        { name: 'colors', items: [ 'TextColor', 'BGColor' ] },
        { name: 'basicstyles', groups: [ 'basicstyles', 'cleanup' ], items: [ 'Bold', 'Italic', 'Underline', 'Strike', 'Subscript', 'Superscript', '-', 'RemoveFormat' ] },
        { name: 'paragraph', groups: [ 'align', 'list', 'indent', 'blocks' ], items: [ 'JustifyLeft', 'JustifyCenter', 'JustifyRight', 'JustifyBlock', '-', 'BulletedList', 'NumberedList', '-', 'Outdent', 'Indent', '-', 'Blockquote', 'CreateDiv'] },
        { name: 'insert', items: [ 'Image', 'Table', 'HorizontalRule', 'Iframe', 'SpecialChar' ] },
        { name: 'styles', items: [ 'Styles', 'Format', 'Font' ] },
        { name: 'about', items: [ 'About' ] }
    ];
    */

    // Remove some buttons, provided by the standard plugins, which we don't
    // need to have in the Standard(s) toolbar.
    //config.removeButtons = 'Copy,Iframe,Flash,Smiley,PageBreak,FontSize,Save,Print,NewPage,Preview,Templates,PasteFromWord,PasteText';

    config.width = 800;
    config.height = 400;

    config.extraPlugins = 'magicline,image2';
    //config.removePlugins = 'resize';

    // Allow classes for all tags
    config.extraAllowedContent = '*(*)';

    config.toolbarCanCollapse = true;
    config.toolbarStartupExpanded = true;

    config.forcePasteAsPlainText = true;
    config.pasteFromWordRemoveFontStyles = true;
    config.pasteFromWordRemoveStyles = true;
    config.pasteFromWordPromptCleanup = true;

    //config.contentsCss = 'template/config/ckeditor/ckeditor.custom.css';
    //config.protectedSource.push( /<i[\s\S]*?\>/g ); //allows beginning <i> tag
    //config.protectedSource.push( /<\/i[\s\S]*?\>/g ); //allows ending </i> tag
    config.protectedSource.push( /<button[\s\S]*?\>/g ); //allows beginning <button> tag
    config.protectedSource.push( /<\/button[\s\S]*?\>/g ); //allows ending </button> tag
    config.protectedSource.push( /<span[\s\S]*?\>/g ); //allows beginning <span> tag
    config.protectedSource.push( /<\/span[\s\S]*?\>/g ); //allows ending </span> tag

    //config.contentsCss = 'assets/config/ckeditor/ckeditor.custom.css';
    //config.colorButton_colors = "00F"; //000,FFF,...

    //config.format_uppercase = { element: 'span', attributes: {'class': 'uppercase'}, name: 'UPPERCASE' };
    //config.format_blockquote = { element: 'blockquote', name: 'Blockquote' };

    //config.image2_altRequired = true;
    //config.image2_alignClasses = ['image-left', 'image-center', 'image-right'];
    //config.image2_captionedClass = 'image-captioned';

};