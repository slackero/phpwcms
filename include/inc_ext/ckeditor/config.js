/**
 * @license Copyright (c) 2003-2022, CKSource Holding sp. z o.o. All rights reserved.
 * For licensing, see https://ckeditor.com/legal/ckeditor-oss-license
 */

CKEDITOR.editorConfig = function( config ) {
    // Define changes to default configuration here. For example:
    // config.language = 'fr';
    // config.uiColor = '#AADC6E';

    // For the complete reference:
    // http://docs.ckeditor.com/#!/api/CKEDITOR.config

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

    // Remove some buttons, provided by the standard plugins, which we don't
    // need to have in the Standard(s) toolbar.
    //config.removeButtons = 'Underline,Subscript,Superscript';

    // set some recommend defaults
    config.extraPlugins = 'magicline,image2';
    config.removePlugins = 'image';
    config.extraAllowedContent = 'div;p;span;ul;ol;li;table;td;style;*[id];*(*);*{*}';
    config.forcePasteAsPlainText = true;
    config.pasteFromWordRemoveFontStyles = true;
    config.pasteFromWordRemoveStyles = true;
    config.pasteFromWordPromptCleanup = true;
    config.protectedSource.push(/<i[^>]*><\/i>/g);
    config.protectedSource.push(/<span[^>]*><\/span>/g);

    config.image2_altRequired = true;
    config.image2_alignClasses = ['image-left', 'image-center', 'image-right'];
    config.image2_captionedClass = 'image-captioned';

};