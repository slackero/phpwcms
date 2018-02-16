<?php
/*

Use this to overwrite the default "template_default" settings.
It allows to set alternative behavior on a per structure level basis.
The defaults are located here "include/config/conf.template_default.inc.php"

Set only those values which should overwrite the defaults.
There is no need to use the complete set of vars.
Both arrays will be merged while frontend rendering process.

*/

// Have a look at the <body> Tag in the source view of your website
$template_default['body']['class'] = 'custom';
$template_default['article']['newsletter_error']		= 'Bitte prüfen Sie die Mailadresse! Geben Sie einen gültigen Namen/ Mailadresse an';

// date and time formatting
$template_default['date']['language']		= 'DE';         // DE=German, IT=Italian, FR=French
$template_default['date']['short']			= 'd.m.Y';      // (2003/12/25)
$template_default['date']['article']		= 'd.m.Y';      // (2003/12/25)

// new articles
$template_default['news']['date_language']	= 'DE'; // DE=German, IT=Italian, FR=French, ES = Spanish, DA = Danish, NO = Norwegian
$template_default['news']['date_format']	= 'd.m.Y'; //if empty -> no Date

$template_default['settings']['customctp_custom_fields'] = array(
        // Enable and customise to enable additional input fields for each image special item:
        'fieldgroup1' => array(
            'legend' => 'Field group name',
            'template' => 'default.tmpl', // bind the fieldgroup to a specific template, or default
            'fields' => array(
                // [CUSTCTP_STRING1]{CUSTCTP_STRING1}[/CUSTCTP_STRING1]
                'string1' => array(
                    'legend' => 'field 1',
                    'type' => 'str',
                    'render' => 'html',
                    'maxlength' => 100,
                    'placeholder' => ''
                ),
                // [CUSTCTP_TEXTAREA1]{CUSTCTP_TEXTAREA1}[/CUSTCTP_TEXTAREA1]
                'textarea1' => array(
                    'legend' => 'field 2',
                    'type' => 'textarea',
                    'render' => '',
                    'rows' => 3,
                    'placeholder' => ''
                ),
                // [CUSTCTP_OPTIONNAME1]
                //    Will be the selected value {CUSTCTP_OPTIONNAME1}
                //    [CUSTCTP_OPTIONNAME1_OPTION1]{CUSTCTP_OPTIONNAME1_OPTION1}[/CUSTCTP_OPTIONNAME1_OPTION1]
                //    [CUSTCTP_OPTIONNAME1_OPTION2]{CUSTCTP_OPTIONNAME1_OPTION2}[/CUSTCTP_OPTIONNAME1_OPTION2]
                // [/CUSTCTP_OPTIONNAME1]
                'optionname1' => array(
                    'legend' => 'choose',
                    'type' => 'option',
                    'render' => '',
                    'values' => array(
                        'option1' => 'value option 1',
                        'option2' => 'value option 2',
                        'empty' => 'nothing',
                    ),
                    'default' => 'empty'
                ),
                // [CUSTCTP_INTEGER1]{CUSTCTP_INTEGER1}[/CUSTCTP_INTEGER1]
                'integer1' => array(
                    'legend' => 'integer 1',
                    'type' => 'int',
                    'render' => '',
                    'max' => 1000,
                    'min' => -1000,
                    'step' => 1,
                    'placeholder' => ''
                ),
                // [CUSTCTP_FLOAT1]{CUSTCTP_FLOAT1}[/CUSTCTP_FLOAT1]
                'float1' => array(
                    'legend' => 'float 1',
                    'type' => 'float',
                    'render' => '',
                    'max' => 1000,
                    'min' => -1000,
                    'step' => .1,
                    'placeholder' => ''
                ),
                // [CUSTCTP_SELECT1]
                //    Will be the selected value {CUSTCTP_SELECT1}
                //    [CUSTCTP_SELECT1_OPTION1]{CUSTCTP_SELECT1_OPTION1}[/CUSTCTP_SELECT1_OPTION1]
                //    [CUSTCTP_SELECT1_OPTION2]{CUSTCTP_SELECT1_OPTION2}[/CUSTCTP_SELECT1_OPTION2]
                // [/CUSTCTP_SELECT1]
                'select1' => array(
                    'legend' => 'choose',
                    'type' => 'select',
                    'render' => '',
                    'values' => array(
                        'empty' => 'choose a value or this for nothing',
                        'option1' => 'value option 1',
                        'option2' => 'value option 2',
                    ),
                    'default' => 'empty'
                ),
                // [CUSTCTP_BOOL1]True[/CUSTCTP_BOOL1][CUSTCTP_BOOL1_ELSE]False[/CUSTCTP_BOOL1_ELSE]
                'bool1' => array(
                    'legend' => 'Enable (to be true)',
                    'type' => 'bool',
                    'default' => false, // or true to be enabled by default
                ),
                'file1' => array(
                    'legend' => 'file 1',
                    'type' => 'file',
                    'template' => '', // if empty the default file list template is used
                    'filetypes' => 'pdf,txt', // comma separated allowed filetypes 'xls,docx,vcf'
                    'direct' => 0 // direct file download 1 or not 0
                ),
                // [CUSTCTP_IMAGE1]{CUSTCTP_IMAGE1}[/CUSTCTP_IMAGE1]
                'image1' => array(
                    'legend' => 'image 1',
                    'type' => 'image',
                    'template' => '',
                    'alt-label' => 'Zus�tzliche Bildinfo',
                    'title-label' => 'Titel Bild',
                    'width_zoom' => '200',
                    'height_zoom' => '200',
                    'sharpen_level' => '1',
                    'crop_zoom' => '0',
                    'crop' => '0',
                    'class' => 'img-responsive'
                ),
                // [CUSTCTP_WYSIWYG1]{CUSTCTP_WYSIWYG1}[/CUSTCTP_WYSIWYG1][CUSTCTP_WYSIWYG1_ELSE]<!--nada-->[/CUSTCTP_WYSIWYG1_ELSE]
                'wysiwyg1' => array(
                    'legend' => 'WYSIWYG editor',
                    'type' => 'textarea',
                    'render' => 'wysiwyg',
                    'rows' => 10,
                    'height' => '175px',
                    'placeholder' => ''
                )
            )
        ),
        'fieldgroup2' => array(
            'legend' => 'Vorlage Tabelle',
            'template' => 'tabelle.tmpl', // bind the fieldgroup to a specific template, or default
            'fields' => array(
                // [CUSTCTP_STRING1]{CUSTCTP_STRING1}[/CUSTCTP_STRING1]
                'string1' => array(
                    'legend' => 'Name',
                    'type' => 'str',
                    'render' => 'html',
                    'maxlength' => 100,
                    'placeholder' => ''
                ),
                // [CUSTCTP_STRING2]{CUSTCTP_STRING2}[/CUSTCTP_STRING2]
                'string2' => array(
                    'legend' => 'Funktion',
                    'type' => 'str',
                    'render' => 'html',
                    'maxlength' => 100,
                    'placeholder' => ''
                ),
                // [CUSTCTP_STRING3]{CUSTCTP_STRING3}[/CUSTCTP_STRING3]
                'string3' => array(
                    'legend' => 'E-Mail',
                    'type' => 'str',
                    'render' => 'html',
                    'maxlength' => 100,
                    'placeholder' => ''
                ),
                // [CUSTCTP_TEXTAREA1]{CUSTCTP_TEXTAREA1}[/CUSTCTP_TEXTAREA1]
                'textarea1' => array(
                    'legend' => 'Beschreibung',
                    'type' => 'textarea',
                    'render' => '',
                    'rows' => 3,
                    'placeholder' => ''
                ),
                // [CUSTCTP_IMAGE1]{CUSTCTP_IMAGE1}[/CUSTCTP_IMAGE1]
                'image1' => array(
                    'legend' => 'Profilbild',
                    'type' => 'image',
                    'template' => '',
                    'alt-label' => 'Zusätzliche Bildinfo',
                    'title-label' => 'Titel Bild',
                ),
                // [CUSTCTP_WYSIWYG1]{CUSTCTP_WYSIWYG1}[/CUSTCTP_WYSIWYG1][CUSTCTP_WYSIWYG1_ELSE]<!--nada-->[/CUSTCTP_WYSIWYG1_ELSE]
                'wysiwyg1' => array(
                    'legend' => 'WYSIWYG editor',
                    'type' => 'textarea',
                    'render' => 'wysiwyg',
                    'rows' => 5,
                    'height' => '175px',
                    'placeholder' => 'Irgendwas eintragen'
                )
            )
        ),
      'fieldgroup3' => array(
            'legend' => 'Teaserboxen',
            'template' => 'boxen.tmpl', // bind the fieldgroup to a specific template, or default
            'fields' => array(
                // [CUSTCTP_STRING1]{CUSTCTP_STRING1}[/CUSTCTP_STRING1]
                'string1' => array(
                    'legend' => 'Titel',
                    'type' => 'str',
                    'render' => 'html',
                    'maxlength' => 100,
                    'placeholder' => ''
                ),
                // [CUSTCTP_STRING2]{CUSTCTP_STRING2}[/CUSTCTP_STRING2]
                'string2' => array(
                    'legend' => 'E-Mail',
                    'type' => 'str',
                    'render' => 'html',
                    'maxlength' => 100,
                    'placeholder' => ''
                ),
                // [CUSTCTP_TEXTAREA1]{CUSTCTP_TEXTAREA1}[/CUSTCTP_TEXTAREA1]
                'textarea1' => array(
                    'legend' => 'Teasertext',
                    'type' => 'textarea',
                    'render' => '',
                    'rows' => 3,
                    'placeholder' => ''
                ),
                'file1' => array(
                    'legend' => 'Download',
                    'type' => 'file',
                    'template' => 'small-pdf.html', // if empty the default file list template is used
                    'filetypes' => 'pdf,txt', // comma separated allowed filetypes 'xls,docx,vcf'
                    'direct' => 0 // direct file download 1 or not 0
                ),
                // [CUSTCTP_IMAGE1]{CUSTCTP_IMAGE1}[/CUSTCTP_IMAGE1]
                'image1' => array(
                    'legend' => 'Teaserbild',
                    'type' => 'image',
                    'template' => '',
                    'alt-label' => 'Zusätzliche Bildinfo',
                    'title-label' => 'Titel Bild',
                    'width_zoom' => '200',
                    'height_zoom' => '200',
                    'sharpen_level' => '1',
                    'crop_zoom' => '0',
                    'crop' => '0',
                    'class' => 'img-responsive'
                )
            )
        ),
      'fieldgroup4' => array(
            'legend' => 'FAQ',
            'template' => 'faq.tmpl', // bind the fieldgroup to a specific template, or default
            'fields' => array(
                // [CUSTCTP_STRING1]{CUSTCTP_STRING1}[/CUSTCTP_STRING1]
                'string1' => array(
                    'legend' => 'Frage',
                    'type' => 'str',
                    'render' => 'html',
                    'maxlength' => 200,
                    'placeholder' => ''
                ),
                // [CUSTCTP_WYSIWYG1]{CUSTCTP_WYSIWYG1}[/CUSTCTP_WYSIWYG1][CUSTCTP_WYSIWYG1_ELSE]<!--nada-->[/CUSTCTP_WYSIWYG1_ELSE]
                'wysiwyg1' => array(
                    'legend' => 'Antwort',
                    'type' => 'textarea',
                    'render' => 'wysiwyg',
                    'rows' => 10,
                    'height' => '75px',
                    'placeholder' => ''
                )
            )
        )
    );