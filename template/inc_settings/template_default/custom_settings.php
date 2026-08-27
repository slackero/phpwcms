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
$template_default['article']['newsletter_error']		= '@@Please check the email address! Enter a valid name and email address@@';

// date and time formatting
$template_default['date']['language']		= 'EN';         // EN=English, DE=German, IT=Italian, FR=French
$template_default['date']['short']			= 'Y-m-d';      // (2003/12/25)
$template_default['date']['article']		= 'Y-m-d';      // (2003/12/25)

// new articles
$template_default['news']['date_language']	= 'EN'; // EN=English, DE=German, IT=Italian, FR=French, ES = Spanish, DA = Danish, NO = Norwegian
$template_default['news']['date_format']	= 'Y-m-d'; //if empty -> no Date

$template_default['settings']['customctp_custom_fields'] = [
    // Enable and customise to enable additional input fields for each image special item:
    'fieldgroup1' => [
        'legend' => '@@Field group name@@',
        'template' => 'default.tmpl', // bind the fieldgroup to a specific template, or default
        'fields' => [
            // [CUSTCTP_STRING1]{CUSTCTP_STRING1}[/CUSTCTP_STRING1]
            'string1' => [
                'legend' => '@@Field 1@@',
                'type' => 'str',
                'render' => 'html',
                'maxlength' => 100,
                'placeholder' => ''
            ],
            // [CUSTCTP_TEXTAREA1]{CUSTCTP_TEXTAREA1}[/CUSTCTP_TEXTAREA1]
            'textarea1' => [
                'legend' => '@@Field 2@@',
                'type' => 'textarea',
                'render' => '',
                'rows' => 3,
                'placeholder' => ''
            ],
            // [CUSTCTP_OPTIONNAME1]
            //    Will be the selected value {CUSTCTP_OPTIONNAME1}
            //    [CUSTCTP_OPTIONNAME1_OPTION1]{CUSTCTP_OPTIONNAME1_OPTION1}[/CUSTCTP_OPTIONNAME1_OPTION1]
            //    [CUSTCTP_OPTIONNAME1_OPTION2]{CUSTCTP_OPTIONNAME1_OPTION2}[/CUSTCTP_OPTIONNAME1_OPTION2]
            // [/CUSTCTP_OPTIONNAME1]
            'optionname1' => [
                'legend' => '@@Choose@@',
                'type' => 'option',
                'render' => '',
                'values' => [
                    'option1' => '@@Option 1@@',
                    'option2' => '@@Option 2@@',
                    'empty' => '@@None@@',
                ],
                'default' => 'empty'
            ],
            // [CUSTCTP_INTEGER1]{CUSTCTP_INTEGER1}[/CUSTCTP_INTEGER1]
            'integer1' => [
                'legend' => '@@Integer 1@@',
                'type' => 'int',
                'render' => '',
                'max' => 1000,
                'min' => -1000,
                'step' => 1,
                'placeholder' => ''
            ],
            // [CUSTCTP_FLOAT1]{CUSTCTP_FLOAT1}[/CUSTCTP_FLOAT1]
            'float1' => [
                'legend' => '@@Float 1@@',
                'type' => 'float',
                'render' => '',
                'max' => 1000,
                'min' => -1000,
                'step' => .1,
                'placeholder' => ''
            ],
            // [CUSTCTP_SELECT1]
            //    Will be the selected value {CUSTCTP_SELECT1}
            //    [CUSTCTP_SELECT1_OPTION1]{CUSTCTP_SELECT1_OPTION1}[/CUSTCTP_SELECT1_OPTION1]
            //    [CUSTCTP_SELECT1_OPTION2]{CUSTCTP_SELECT1_OPTION2}[/CUSTCTP_SELECT1_OPTION2]
            // [/CUSTCTP_SELECT1]
            'select1' => [
                'legend' => '@@Choose@@',
                'type' => 'select',
                'render' => '',
                'values' => [
                    'empty' => '@@Choose a value or leave empty@@',
                    'option1' => '@@Option 1@@',
                    'option2' => '@@Option 2@@',
                ],
                'default' => 'empty'
            ],
            // [CUSTCTP_BOOL1]True[/CUSTCTP_BOOL1][CUSTCTP_BOOL1_ELSE]False[/CUSTCTP_BOOL1_ELSE]
            'bool1' => [
                'legend' => '@@Enable@@',
                'type' => 'bool',
                'default' => false, // or true to be enabled by default
            ],
            'file1' => [
                'legend' => '@@File 1@@',
                'type' => 'file',
                'template' => '', // if empty the default file list template is used
                'filetypes' => 'pdf,txt', // comma separated allowed filetypes 'xls,docx,vcf'
                'direct' => 0 // direct file download 1 or not 0
            ],
            // [CUSTCTP_IMAGE1]{CUSTCTP_IMAGE1}[/CUSTCTP_IMAGE1]
            'image1' => [
                'legend' => '@@Image 1@@',
                'type' => 'image',
                'template' => '',
                'alt-label' => '@@Additional image info@@',
                'title-label' => '@@Image title@@',
                'width_zoom' => '200',
                'height_zoom' => '200',
                'sharpen_level' => '1',
                'crop_zoom' => '0',
                'crop' => '0',
                'class' => 'img-responsive'
            ],
            // [CUSTCTP_WYSIWYG1]{CUSTCTP_WYSIWYG1}[/CUSTCTP_WYSIWYG1][CUSTCTP_WYSIWYG1_ELSE]<!--nada-->[/CUSTCTP_WYSIWYG1_ELSE]
            'wysiwyg1' => [
                'legend' => '@@WYSIWYG editor@@',
                'type' => 'textarea',
                'render' => 'wysiwyg',
                'rows' => 10,
                'height' => '175px',
                'placeholder' => ''
            ]
        ]
    ],
    'fieldgroup2' => [
        'legend' => '@@Table template@@',
        'template' => 'tabelle.tmpl', // bind the fieldgroup to a specific template, or default
        'fields' => [
            // [CUSTCTP_STRING1]{CUSTCTP_STRING1}[/CUSTCTP_STRING1]
            'string1' => [
                'legend' => '@@Name@@',
                'type' => 'str',
                'render' => 'html',
                'maxlength' => 100,
                'placeholder' => ''
            ],
            // [CUSTCTP_STRING2]{CUSTCTP_STRING2}[/CUSTCTP_STRING2]
            'string2' => [
                'legend' => '@@Function@@',
                'type' => 'str',
                'render' => 'html',
                'maxlength' => 100,
                'placeholder' => ''
            ],
            // [CUSTCTP_STRING3]{CUSTCTP_STRING3}[/CUSTCTP_STRING3]
            'string3' => [
                'legend' => '@@Email@@',
                'type' => 'str',
                'render' => 'html',
                'maxlength' => 100,
                'placeholder' => ''
            ],
            // [CUSTCTP_TEXTAREA1]{CUSTCTP_TEXTAREA1}[/CUSTCTP_TEXTAREA1]
            'textarea1' => [
                'legend' => '@@Description@@',
                'type' => 'textarea',
                'render' => '',
                'rows' => 3,
                'placeholder' => ''
            ],
            // [CUSTCTP_IMAGE1]{CUSTCTP_IMAGE1}[/CUSTCTP_IMAGE1]
            'image1' => [
                'legend' => '@@Profile image@@',
                'type' => 'image',
                'template' => '',
                'alt-label' => '@@Additional image info@@',
                'title-label' => '@@Image title@@',
            ],
            // [CUSTCTP_WYSIWYG1]{CUSTCTP_WYSIWYG1}[/CUSTCTP_WYSIWYG1][CUSTCTP_WYSIWYG1_ELSE]<!--nada-->[/CUSTCTP_WYSIWYG1_ELSE]
            'wysiwyg1' => [
                'legend' => '@@WYSIWYG editor@@',
                'type' => 'textarea',
                'render' => 'wysiwyg',
                'rows' => 5,
                'height' => '175px',
                'placeholder' => '@@Enter text here@@'
            ]
        ]
    ],
    'fieldgroup3' => [
        'legend' => '@@Teaser boxes@@',
        'template' => 'boxen.tmpl', // bind the fieldgroup to a specific template, or default
        'fields' => [
            // [CUSTCTP_STRING1]{CUSTCTP_STRING1}[/CUSTCTP_STRING1]
            'string1' => [
                'legend' => '@@Title@@',
                'type' => 'str',
                'render' => 'html',
                'maxlength' => 100,
                'placeholder' => ''
            ],
            // [CUSTCTP_STRING2]{CUSTCTP_STRING2}[/CUSTCTP_STRING2]
            'string2' => [
                'legend' => '@@Email@@',
                'type' => 'str',
                'render' => 'html',
                'maxlength' => 100,
                'placeholder' => ''
            ],
            // [CUSTCTP_TEXTAREA1]{CUSTCTP_TEXTAREA1}[/CUSTCTP_TEXTAREA1]
            'textarea1' => [
                'legend' => '@@Teaser text@@',
                'type' => 'textarea',
                'render' => '',
                'rows' => 3,
                'placeholder' => ''
            ],
            'file1' => [
                'legend' => '@@Download@@',
                'type' => 'file',
                'template' => 'small-pdf.html', // if empty the default file list template is used
                'filetypes' => 'pdf,txt', // comma separated allowed filetypes 'xls,docx,vcf'
                'direct' => 0 // direct file download 1 or not 0
            ],
            // [CUSTCTP_IMAGE1]{CUSTCTP_IMAGE1}[/CUSTCTP_IMAGE1]
            'image1' => [
                'legend' => '@@Teaser image@@',
                'type' => 'image',
                'template' => '',
                'alt-label' => '@@Additional image info@@',
                'title-label' => '@@Image title@@',
                'width_zoom' => '200',
                'height_zoom' => '200',
                'sharpen_level' => '1',
                'crop_zoom' => '0',
                'crop' => '0',
                'class' => 'img-responsive'
            ]
        ]
    ],
    'fieldgroup4' => [
        'legend' => '@@FAQ@@',
        'template' => 'faq.tmpl', // bind the fieldgroup to a specific template, or default
        'fields' => [
            // [CUSTCTP_STRING1]{CUSTCTP_STRING1}[/CUSTCTP_STRING1]
            'string1' => [
                'legend' => '@@Question@@',
                'type' => 'str',
                'render' => 'html',
                'maxlength' => 200,
                'placeholder' => ''
            ],
            // [CUSTCTP_WYSIWYG1]{CUSTCTP_WYSIWYG1}[/CUSTCTP_WYSIWYG1][CUSTCTP_WYSIWYG1_ELSE]<!--nada-->[/CUSTCTP_WYSIWYG1_ELSE]
            'wysiwyg1' => [
                'legend' => '@@Answer@@',
                'type' => 'textarea',
                'render' => 'wysiwyg',
                'rows' => 10,
                'height' => '75px',
                'placeholder' => ''
            ]
        ]
    ]
];