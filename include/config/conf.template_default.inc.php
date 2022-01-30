<?php
// predefine the default article/content values like space holder images, classes and so on

// simple navigation table defaults
$template_default['nav_table_simple_struct']['width']       = '100%';
$template_default['nav_table_simple_struct']['border']      = '0';
$template_default['nav_table_simple_struct']['cellpadding'] = '0';
$template_default['nav_table_simple_struct']['cellspacing'] = '0';

// row based navigation
$template_default['nav_row']['before']                      = '';
$template_default['nav_row']['after']                       = '';
$template_default['nav_row']['between']                     = ' | ';
$template_default['nav_row']['link_before']                 = '';
$template_default['nav_row']['link_after']                  = '';
$template_default['nav_row']['link_before_active']          = '<span style="text-decoration:none;font-weight:bold;">';
$template_default['nav_row']['link_after_active']           = '</span>';
$template_default['nav_row']['link_direct_before']          = '';
$template_default['nav_row']['link_direct_after']           = '';
$template_default['nav_row']['link_direct_before_active']   = '';
$template_default['nav_row']['link_direct_after_active']    = '';

// navigation table defaults
$template_default['nav_table_struct']['table_border']           = '0';
$template_default['nav_table_struct']['table_width']            = '100%';
$template_default['nav_table_struct']['table_height']           = '';
$template_default['nav_table_struct']['table_bgcolor']          = '';
$template_default['nav_table_struct']['table_class']            = '';
$template_default['nav_table_struct']['table_cspace']           = '0';
$template_default['nav_table_struct']['table_cpad']             = '0';
//
$template_default['nav_table_struct']['space_width']            = 10;
$template_default['nav_table_struct']['space_left']             = 7;
$template_default['nav_table_struct']['space_right']            = 10;
$template_default['nav_table_struct']['space_celltop']          = 2;
$template_default['nav_table_struct']['space_cellbottom']       = 2;
//
$template_default['nav_table_struct']['cell_width']             = '100%';
$template_default['nav_table_struct']['cell_height']            = '15';
$template_default['nav_table_struct']['cell_class']             = 'nav-table';
//
$template_default['nav_table_struct']['cell_active_width']      = '100%';
$template_default['nav_table_struct']['cell_active_height']     = '15';
$template_default['nav_table_struct']['cell_active_class']      = 'nav-table-active';
//
$template_default['nav_table_struct']['js_over_effects']        = 1;
$template_default['nav_table_struct']['all_nodes_active']       = 1;
//
$template_default['nav_table_struct']['linkimage_norm']         = '<img src="img/article/nav_link_0.gif" alt="" border="0" />';
$template_default['nav_table_struct']['linkimage_over']         = '<img src="img/article/nav_link_1.gif" alt="" border="0" />';
$template_default['nav_table_struct']['linkimage_active']       = '<img src="img/article/nav_link_2.gif" alt="" border="0" />';
//
$template_default['nav_table_struct']['link_before']            = '';
$template_default['nav_table_struct']['link_after']             = '';
$template_default['nav_table_struct']['link_active_before']     = '';
$template_default['nav_table_struct']['link_active_after']      = '';
//
$template_default['nav_table_struct']['row_norm_bgcolor']       = '#D9DEE3';
$template_default['nav_table_struct']['row_norm_class']         = '';
//
$template_default['nav_table_struct']['row_over_bgcolor']       = '#D3ED7D'; //#AAB7C1
$template_default['nav_table_struct']['row_active_bgcolor']     = '#FFFFFF';
$template_default['nav_table_struct']['row_active_class']       = '';
//
$template_default['nav_table_struct']['row_space']              = 1;
$template_default['nav_table_struct']['row_space_bgcolor']      = '#4A5966';

/*
 * sublevel layout
 * ===============
 * setup unique style for each node/level
 * ['array_struct'][1] => the number represents level ID
 * so root level is [0] and so on...

$template_default['nav_table_struct']['array_struct'][1]['linkimage_norm']      = '<img src="img/article/nav_link_0.gif" alt="" border="0" />';
$template_default['nav_table_struct']['array_struct'][1]['linkimage_over']      = '<img src="img/article/nav_link_1.gif" alt="" border="0" />';
$template_default['nav_table_struct']['array_struct'][1]['linkimage_active']    = '<img src="img/article/nav_link_2.gif" alt="" border="0" />';

$template_default['nav_table_struct']['array_struct'][1]['link_before']         = '';
$template_default['nav_table_struct']['array_struct'][1]['link_after']          = '';
$template_default['nav_table_struct']['array_struct'][1]['link_active_before']  = '';
$template_default['nav_table_struct']['array_struct'][1]['link_active_after']   = '';

$template_default['nav_table_struct']['array_struct'][1]['row_norm_bgcolor']    = '#FAEAC8';
$template_default['nav_table_struct']['array_struct'][1]['row_norm_class']      = $template_default['nav_table_struct']['row_norm_class'];
$template_default['nav_table_struct']['array_struct'][1]['row_over_bgcolor']    = '#FAEAC8';
$template_default['nav_table_struct']['array_struct'][1]['row_active_bgcolor']  = '#FAEAC8';
$template_default['nav_table_struct']['array_struct'][1]['row_active_class']    = $template_default['nav_table_struct']['row_active_class'];

$template_default['nav_table_struct']['array_struct'][1]['space_celltop']       = $template_default['nav_table_struct']['space_celltop'];
$template_default['nav_table_struct']['array_struct'][1]['space_cellbottom']    = $template_default['nav_table_struct']['space_cellbottom'];

$template_default['nav_table_struct']['array_struct'][1]['cell_height']     = $template_default['nav_table_struct']['cell_height'];
$template_default['nav_table_struct']['array_struct'][1]['cell_class']          = 'navLevel1';
$template_default['nav_table_struct']['array_struct'][1]['cell_active_height']  = $template_default['nav_table_struct']['cell_active_height'];
$template_default['nav_table_struct']['array_struct'][1]['cell_active_class']   = 'nav-level-1a';
 */


// article listing with summary
$template_default['space_top']              = '';
$template_default['space_bottom']           = '';
$template_default['space_aftertop_text']    = '';
$template_default['space_between_list']     = '';
$template_default['space_between_sum']      = '';
//
$template_default['top_listentry_before']   = '<div class="list-item">';
$template_default['top_listentry_after']    = '</div>';
$template_default['top_headline_before']    = '<h1>';
$template_default['top_headline_after']     = '</h1>';
$template_default['top_subheadline_before'] = '<h2>';
$template_default['top_subheadline_after']  = '</h2>';
$template_default['top_text_before']        = '<div class="text-top">';
$template_default['top_text_after']         = '</div>';
$template_default['top_readmore_before']    = ' ';
$template_default['top_readmore_link']      = 'more&#8230;';
$template_default['top_readmore_after']     = '';
$template_default['top_headline_space']     = '';
$template_default['top_subheadline_space']  = '';
$template_default['top_count']              = 1;
$template_default['article_order']          = 1; // 0 = manual, 2 = creation date, 4 = start date -> + 0 = ASC, + 1 = DESC
$template_default['article_paginate_navi']  = '<div class="paginate paginate-{POS}">{PREV:&laquo;} {NEXT:&raquo;} page #/##, result ###-####, {NAVI:1-3, |<span>|</span>}</div>'; //
$template_default['article_paginate_show']  = 'top bottom rt{RT}'; //where should the navi be shown - possible values: top and/or bottom and/or rt:{RT}
$template_default['article_render_anchor']  = false; // render article jumpID anchor true||false

//
$template_default['list_startimage']        = '';
$template_default['list_headline_before']   = '<div class="articleListListhead">';
$template_default['list_headline_after']    = '</div>';

// breadcrumb
$template_default['breadcrumb_spacer']      = ' <span class="breadcrumb-spacer">&gt;</span> ';
$template_default['breadcrumb_active_prefix'] = '<strong>';
$template_default['breadcrumb_active_suffix'] = '</strong>';
$template_default['breadcrumb_nolink_prefix'] = '<span>';
$template_default['breadcrumb_nolink_suffix'] = '</span>';

// article default
$template_default['article']['title_before']        = '<h1>';
$template_default['article']['title_after']         = '</h1>';
$template_default['article']['subtitle_before']     = '<h2>';
$template_default['article']['subtitle_after']      = '</h2>';
$template_default['article']['summary_before']      = '<div class="article-summary">';
$template_default['article']['summary_after']       = '</div>';

$template_default['article']['div_spacer'] = true; //if true or 'div' = <div>..., if false or not set <br><img...>
$template_default['article']['div_spacer_tag'] = 'div'; // like div, span
$template_default['article']['div_spacer_style'] = 'height'; // can be margin, padding or height
$template_default['article']['div_spacer_unit'] = 'px'; // like px, em

$template_default['article']['head_after']          = '';

$template_default['article']['content_head_before']     = '<h3>';
$template_default['article']['content_head_after']      = '</h3>';
$template_default['article']['content_subhead_before']  = '<h4>';
$template_default['article']['content_subhead_after']   = '</h4>';

$template_default['article']['text_class']              = 'articleText';
$template_default['article']['code_class']              = 'articleCode';

$template_default['article']['link_email_before']       = '<div class="link-email">';
$template_default['article']['link_email_after']        = '</div>';

$template_default['article']['bullet_sign']             = '&gt; ';
$template_default['article']['link_sign']               = '&gt; ';
$template_default['article']['back_sign']               = '&lt; ';
$template_default['article']['top_sign']                = '<img src="img/article/top_link_0.gif" border="0" alt="" />';
$template_default['article']['top_sign_before']         = '<div class="link-top">';
$template_default['article']['top_sign_after']          = '</div>';

$template_default['article']['file_name_cell_class']    = 'v11';
$template_default['article']['file_size_cell_class']    = 'v10';
$template_default['article']['file_size_info_class']    = 'v09';

$template_default['article']['form_align']              = '';

$template_default['article']['link_article_sign']       = '&gt; ';
$template_default['article']['link_article_class']      = 'article-link-internal';

$template_default['article']['image_table_class']       = '';
$template_default['article']['image_table_bgcolor']     = '';
$template_default['article']['image_bgcolor']           = '';
$template_default['article']['image_align']             = '';
$template_default['article']['image_valign']            = '';
$template_default['article']['image_border']            = 0;
$template_default['article']['image_class']             = "image-td";
$template_default['article']['image_imgclass']          = "image-img";
$template_default['article']['image_caption_class']     = "image-caption";
$template_default['article']['image_caption_bgcolor']   = '';
$template_default['article']['image_caption_valign']    = '';
$template_default['article']['image_caption_align']     = 'center';
$template_default['article']['image_caption_before']    = '';
$template_default['article']['image_caption_after']     = '';
$template_default['article']['image_div']               = true;

$template_default['article']['image_default_width']     = '490';
$template_default['article']['image_default_height']    = '370';

$template_default['article']['imagelist_table_class']       = 'imagelist-table';
$template_default['article']['imagelist_table_bgcolor']     = '';
$template_default['article']['imagelist_spacerrow_class']   = 'imagelist-spacer-row';
$template_default['article']['imagelist_bgcolor']           = '';
$template_default['article']['imagelist_align']             = '';
$template_default['article']['imagelist_valign']            = '';
$template_default['article']['imagelist_border']            = 0;
$template_default['article']['imagelist_class']             = 'imagelist-td';
$template_default['article']['imagelist_imgclass']          = 'imagelist-img';
$template_default['article']['imagelist_caption_class']     = 'imglist-caption';
$template_default['article']['imagelist_caption_bgcolor']   = '';
$template_default['article']['imagelist_caption_valign']    = '';
$template_default['article']['imagelist_caption_align']     = '';
$template_default['article']['imagelist_caption_before']    = '';
$template_default['article']['imagelist_caption_after']     = '';

$template_default['article']['imagelist_default_width']     = 245;
$template_default['article']['imagelist_default_height']    = 185;

$template_default['imagegallery_default_width']             = 490;
$template_default['imagegallery_default_height']            = 370;
$template_default['imagegallery_default_space']             = 0;
$template_default['imagegallery_default_column']            = 1;


$template_default['article']['keyword_before']          = '<span class="keywords">';
$template_default['article']['keyword_after']           = '</span>';
$template_default['article']['newsletter_error']        = '@@Please check email address!@@';

// date and time formatting
$template_default['date']['language']       = 'EN';         // DE=German, IT=Italian, FR=French
$template_default['date']['long']           = 'l, j. F Y';  // (Monday, 1. October 2003)
$template_default['date']['medium']         = 'D, j. M y';  // (Mon, 1. Oct 03)
$template_default['date']['short']          = 'Y/m/d';      // (2003/12/25)
$template_default['date']['article']        = 'Y/m/d';      // (2003/12/25)
$template_default['time']['long']           = 'H:i:s';      // 15:25:45
$template_default['time']['short']          = 'H:i';        // 15:25

// rss image
$template_default['rss']['image']   = '<img src="img/article/rss_valid.gif" width="64" height="13" border="0" alt="Valid RSS" />';

// related articles based on keywords
$template_default['related']['before']          = '<div class="related">';
$template_default['related']['after']           = '</div>';
$template_default['related']['link_before']     = '<p>';
$template_default['related']['link_after']      = "</p>";
$template_default['related']['link_symbol']     = '';
$template_default['related']['link_target']     = '';
$template_default['related']['link_length']     = 0; //if 0 no limit
$template_default['related']['cut_title_add']   = '&#8230;';
$template_default['related']['sort_by']         = '';

// new articles
$template_default['news']['before']         = '<div class="news">';
$template_default['news']['after']          = '</div>';
$template_default['news']['link_before']    = '<p>';
$template_default['news']['link_after']     = '</p>';
$template_default['news']['link_symbol']    = '';
$template_default['news']['link_target']    = '';
$template_default['news']['link_length']    = 0; //if 0 no limit
$template_default['news']['cut_title_add']  = '&#8230;';
$template_default['news']['date_language']  = 'EN'; // DE=German, IT=Italian, FR=French, ES = Spanish, DA = Danish, NO = Norwegian
$template_default['news']['date_format']    = 'Y/m/d'; //if empty -> no Date
$template_default['news']['date_before']    = '<span class="datelink">';
$template_default['news']['date_after']     = ' - </span>';
$template_default['news']['sort_by']        = 'cdate'; // 'cdate' = Creation date, or 'udate' = update date, ldate = start date, kdate = end date

// ecards
$template_default['article']['ecard_table_class']       = "image-table";
$template_default['article']['ecard_table_bgcolor']     = '';
$template_default['article']['ecard_bgcolor']           = '';
$template_default['article']['ecard_align']             = '';
$template_default['article']['ecard_valign']            = '';
$template_default['article']['ecard_border']            = 0;
$template_default['article']['ecard_imgclass']          = '';
$template_default['article']['ecard_class']             = 'image-td';
$template_default['article']['ecard_caption_class']     = 'image-caption';
$template_default['article']['ecard_caption_bgcolor']   = '';
$template_default['article']['ecard_caption_valign']    = '';
$template_default['article']['ecard_caption_align']     = 'center';
$template_default['article']['ecard_caption_before']    = '';
$template_default['article']['ecard_caption_after']     = '';
$template_default['article']['ecard_chooser_css']       = 'style="margin:3px 0 3px 0;"';
$template_default['article']['ecard_chooser_text']      = 'style="font-weight:bold;"';

// this is used to inject <body> Tag by attribute "id" and/or "class"
// if value is empty '' body tag will not be injected, otherwise it will use
// current category "valueID"
$template_default['body']['id'] = '';
$template_default['body']['class'] = '';

// target URL for login form - this is where the user is redirected to
$template_default['login_form_url'] = PHPWCMS_URL;

// some texts are cut by default and if cutted the missing part is
// shorten by "..." - that sign is defined here - default "&#8230;"
$template_default['ellipse_sign'] = '&#8230;';

// some more default classes
$template_default['classes'] = array(
    'link-top'                      => 'link-top',
    'link-internal'                 => 'link-internal',
    'link-external'                 => 'link-external',
    'link-rss'                      => 'link-rss',
    'link-back'                     => 'link-back',
    'link-anchor'                   => 'link-anchor',
    'link-email'                    => 'link-email',
    'link-bookmark'                 => 'link-bookmark',
    'spaceholder'                   => 'spaceholder',
    'spaceholder-cp-after'          => 'spaceAfterCP',
    'spaceholder-cp-before'         => 'spaceBeforeCP',
    'img-list-right'                => 'img-list-right',
    'search-nextprev'               => 'search-nextprev',
    'search-result'                 => 'search-result',
    'search-result-item'            => 'search-result-item',
    'article-list-paginate'         => 'article-list-paginate',
    'tab-container'                 => 'tab-container',
    'tab-navigation'                => 'tab-navigation',
    'tab-first'                     => 'tab-first',
    'tab-last'                      => 'tab-last',
    'tab-content'                   => 'tab-content',
    'tab-content-item'              => 'tab-content-item',
    'tab-container-clear'           => '', //tab-container-clear
    'tab-item'                      => 'tab-item',
    'navlist-sub_ul_true'           => 'sub_ul_true',
    'navlist-sub_ul'                => 'sub_ul',
    'navlist-sub_no'                => 'sub_no',
    'navlist-sub_first'             => 'sub_first',
    'navlist-sub_last'              => 'sub_last',
    'navlist-sub_parent'            => 'sub_parent',
    'navlist-asub_no'               => 'asub_no',
    'navlist-asub_first'            => 'asub_first',
    'navlist-asub_last'             => 'asub_last',
    'navlist-link-class'            => 'nav-link',
    'navlist-navLevel'              => 'nav-level-',
    'navlist-bs-link'               => 'nav-link',
    'navlist-bs-dropdown'           => 'dropdown',
    'navlist-bs-dropdown-toggle'    => 'dropdown-toggle',
    'breadcrumb-active'             => 'active',
    'cp-anchor'                     => 'anchor-cp',
    'jump-anchor'                   => 'anchor-article',
    'image-thumb'                   => 'image-thumb',
    'image-center-center'           => 'image-center-center',
    'image-center-horizontal'       => 'image-center-horizontal',
    'image-center-vertical'         => 'image-center-vertical',
    'image-article-summary'         => 'image-article-summary',
    'image-article-list'            => 'image-article-list',
    'image-wrapper'                 => 'image-wrapper',
    'image-link'                    => 'image-link',
    'image-zoom'                    => 'image-zoom',
    'image-lightbox'                => 'image-lightbox',
    'image-parse-inline'            => 'img-bbcode',
    'imgtxt-top-left'               => 'imgtxt-top-left',
    'imgtxt-top-center'             => 'imgtxt-top-center',
    'imgtxt-top-right'              => 'imgtxt-top-right',
    'imgtxt-bottom-left'            => 'imgtxt-bottom-left',
    'imgtxt-bottom-center'          => 'imgtxt-bottom-center',
    'imgtxt-bottom-right'           => 'imgtxt-bottom-right',
    'imgtxt-left'                   => 'imgtxt-left',
    'imgtxt-right'                  => 'imgtxt-right',
    'imgtxt-column-left'            => 'imgtxt-column-left',
    'imgtxt-column-right'           => 'imgtxt-column-right',
    'imgtxt-column-left-image'      => 'imgtxt-column-left-image',
    'imgtxt-column-right-image'     => 'imgtxt-column-right-image',
    'imgtxt-column-left-text'       => 'imgtxt-column-left-text',
    'imgtxt-column-right-text'      => 'imgtxt-column-right-text',
    'copyright'                     => 'copyright',
    'image-list-table'              => 'image-list-table-',
    'link-article-listing'          => 'article-listing',
    'link-print'                    => 'print',
    'link-print-pdf'                => 'print-pdf',
    'imgtable-top-left'             => 'imgtable-top-left',
    'imgtable-top-center'           => 'imgtable-top-center',
    'imgtable-top-right'            => 'imgtable-top-right',
    'imgtable-bottom-left'          => 'imgtable-bottom-left',
    'imgtable-bottom-center'        => 'imgtable-bottom-center',
    'imgtable-bottom-right'         => 'imgtable-bottom-right',
    'imgtable-left'                 => 'imgtable-left',
    'imgtable-right'                => 'imgtable-right',
    'cpgroup-container'             => 'cpgroup-container',
    'cpgroup-title'                 => 'cpgroup-title',
    'cpgroup-first'                 => 'cpgroup-first',
    'cpgroup-last'                  => 'cpgroup-last',
    'cpgroup'                       => 'cpgroup',
    'cpgroup-container-clear'       => '', //cpgroup-container-clear
    'cpgroup-content'               => 'cpgroup-content',
    'shop-category-menu'            => 'shop-categories',
    'shop-products-menu'            => 'shop-products',
    'cp-paginate-link'              => 'paginate-link',
    'cp-paginate-link-active'       => 'paginate-link active',
    'cp-paginate-link-disabled'     => 'paginate-link disabled',
    'search-paginate-link'          => 'paginate-link',
    'search-paginate-link-active'   => 'paginate-link active',
    'search-paginate-link-disabled' => 'paginate-link disabled',
    'newsletter-table'              => 'table table-newsletter',
    'newsletter-table-subscription' => 'table table-subscriptions',
    'newsletter-input-email'        => 'form-control',
    'newsletter-input-name'         => 'form-control',
    'newsletter-checkbox-item'      => 'form-row-checkbox',
    'newsletter-submit-button'      => 'btn btn-primary',
);

$template_default['search_highlight'] = array(
    'prefix' => '<em class="highlight">',
    'suffix' => '</em>'
);

$template_default['attributes'] = array(
    'navlist-bs-dropdown-data'  => 'data-toggle="dropdown"',
    'navlist-bs-dropdown-caret' => ' <b class="caret"></b>',
    'cpgroup'                   => 'data', // data = <span>, href = <a>
    'cpgroup_custom'            => array(
        'bs-row-container' => array( // No underscore allowed here for group index!!!
            'title'     => 'Bootstrap Container/Row',
            'prefix'    => '<div class="container"><div class="row">',
            'suffix'    => '</div></div>'
        ) /* ,
        'sample' => array( // No underscore allowed here for group index!!!
            'title'     => 'Wrapper',
            'prefix'    => '%1$s<div class="wrapper">', // optional: %1$s = title, %2$s = tab id
            'suffix'    => '</div>' // optional: %1$s, %2$s
        ) */
    ),
    'cp-paginate' => array(
        'wrap-prefix' => '<ul>',
        'wrap-suffix' => '</ul>',
        'link-prefix' => '<li>',
        'link-suffix' => '</li>',
        'value-prefix' => '',
        'value-suffix' => '',
        'href-disabled' => ''
    ),
    /*
     * The deprecated solution is using `lightbox` in combination with attribute `rel`
     * For example the setting 'gallery' will result in attributes `data-gallery="1"`
     * and if it's grouped `data-gallerygroup="1"` or `data-gallerygroup="groupname"`
     */
    'data-gallery' => 'gallery',
);

$template_default['settings'] = array(
     // the default behavior is to switch to article detail
     // mode if there is only 1 article left in the category
    'force_article_list_mode' => 0,

    'html5_player' => array(
        'sizes' => array(
            '426x240' => '240p',
            '640x360' => '360p',
            '854x480' => '480p',
            '1280x720' => '720p',
            '1920x1080' => '1080p',
            '2560x1440' => '1440p (2k)',
            '3840x2160' => '2160p (4k)',
            '640x267' => '640 x 267 (21:9)',
            '854x356' => '854 x 356 (21:9)',
            '1280x533' => '1280 x 533 (21:9)',
            '1920x800' => '1920 x 800 (21:9)',
            // Outdated, former hard coded size
            //'200x178' => '200 x 178 px',
            //'320x240' => '320 x 240 px',
            //'380x313' => '380 x 313 px',
            //'425x350' => '425 x 350 px',
            //'450x338' => '450 x 338 px',
            //'500x403' => '500 x 403 px',
            //'640x264' => '640 x 264 px',
            //'640x480' => '640 x 480 px',
        )
    ),

    'tabs_custom_fields' => array(
        // Enable and customise to enable additional tab input fields:
        /*
        'fieldgroup1' => array(
            'legend' => 'Field group name',
            'template' => 'default', // bind the fieldgroup to a specific template, or default
            'fields' => array(
                // [TAB_STRING1]{TAB_STRING1}[/TAB_STRING1]
                'string1' => array(
                    'legend' => 'field 1',
                    'type' => 'str',
                    'render' => 'html',
                    'maxlength' => 100,
                    'placeholder' => ''
                ),
                // [TAB_TEXTAREA1]{TAB_TEXTAREA1}[/TAB_TEXTAREA1]
                'textarea1' => array(
                    'legend' => 'field 2',
                    'type' => 'textarea',
                    'render' => '',
                    'rows' => 3,
                    'placeholder' => ''
                ),
                // [TAB_OPTIONNAME1]
                //    Will be the selected value {TAB_OPTIONNAME1}
                //    [TAB_OPTIONNAME1_OPTION1]{TAB_OPTIONNAME1_OPTION1}[/TAB_OPTIONNAME1_OPTION1]
                //    [TAB_OPTIONNAME1_OPTION2]{TAB_OPTIONNAME1_OPTION2}[/TAB_OPTIONNAME1_OPTION2]
                // [/TAB_OPTIONNAME1]
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
                // [TAB_INTEGER1]{TAB_INTEGER1}[/TAB_INTEGER1]
                'integer1' => array(
                    'legend' => 'integer 1',
                    'type' => 'int',
                    'render' => '',
                    'max' => 1000,
                    'min' => -1000,
                    'step' => 1,
                    'placeholder' => ''
                ),
                // [TAB_FLOAT1]{TAB_FLOAT1}[/TAB_FLOAT1]
                'float1' => array(
                    'legend' => 'float 1',
                    'type' => 'float',
                    'render' => '',
                    'max' => 1000,
                    'min' => -1000,
                    'step' => .1,
                    'placeholder' => ''
                ),
                // [TAB_IMAGE]{TAB_IMAGE}[/TAB_IMAGE]
                'image' => array(
                    'legend' => 'image',
                    'type' => 'file',
                    'template' => 'tab-image.html', // if empty the default file list template is used
                    'filetypes' => 'jpg,png,jpeg', // comma separated allowed filetypes 'xls,docx,vcf'
                    'direct' => 0 // direct file download 1 or not 0
                ),
                // [TAB_SELECT1]
                //    Will be the selected value {TAB_SELECT1}
                //    [TAB_SELECT1_OPTION1]{TAB_SELECT1_OPTION1}[/TAB_SELECT1_OPTION1]
                //    [TAB_SELECT1_OPTION2]{TAB_SELECT1_OPTION2}[/TAB_SELECT1_OPTION2]
                // [/TAB_SELECT1]
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
                // [TAB_BOOL1]True[/TAB_BOOL1][TAB_BOOL1_ELSE]False[/TAB_BOOL1_ELSE]
                'bool1' => array(
                    'legend' => 'Enable (to be true)',
                    'type' => 'bool',
                    'default' => false, // or true to be enabled by default
                ),
            )
        ),
        */
        'gmaps' => array(
            'legend' => 'Google Maps',
            'template' => 'simplegmaps.tmpl', // bind the fieldgroup to a specific template, or default
            'fields' => array(
                // [TAB_GEOLOCATION]{TAB_GEOLOCATION}[/TAB_GEOLOCATION]
                'geolocation' => array(
                    'legend' => 'Geo-Location/Adresse',
                    'type' => 'str',
                    'render' => 'html',
                    'maxlength' => 250,
                    'placeholder' => 'Strasse, PLZ Ort, Land'
                ),
                // [TAB_LAT]{TAB_LAT}[/TAB_LAT]
                'lat' => array(
                    'legend' => 'Latitude',
                    'type' => 'float',
                    'render' => '',
                    'max' => 90,
                    'min' => -90,
                    'step' => .0000001
                ),
                // [TAB_LON]{TAB_LON}[/TAB_LON]
                'lon' => array(
                    'legend' => 'Longitude',
                    'type' => 'float',
                    'render' => '',
                    'max' => 180,
                    'min' => -180,
                    'step' => .0000001
                ),
                // [TAB_MARKERICON]
                //    Will be the selected value {TAB_MARKERICON}
                //    [TAB_MARKERICON_MARKERICON1]{TAB_MARKERICON_MARKERICON1}[/TAB_MARKERICON_MARKERICON1]
                //    [TAB_MARKERICON_MARKERICON2]{TAB_MARKERICON_MARKERICON2}[/TAB_MARKERICON_MARKERICON2]
                // [/TAB_MARKERICON]
                'markericon' => array(
                    'legend' => 'Markierung',
                    'type' => 'select',
                    'render' => '',
                    'values' => array(
                        'empty' => 'Google Standard (red Drop)',
                        'marker_01' => 'Black Marker, white filled',
                        'marker_02' => 'Black Marker, transparent',
                        'marker_03' => 'Blue Marker, white filled',
                        'marker_04' => 'Blue Marker, transparent',

                    ),
                    'default' => 'empty'
                ),
                // [TAB_INFOBOX]True[/TAB_INFOBOX][TAB_INFOBOX_ELSE]False[/TAB_INFOBOX_ELSE]
                'infobox' => array(
                    'legend' => 'Infobox aktiv',
                    'type' => 'bool',
                    'default' => true, // or true to be enabled by default
                ),
            )
        ),
    ),
    'imagespecial_custom_fields' => array(
        // Enable and customise to enable additional input fields for each image special item:
        /*
        'fieldgroup1' => array(
            'legend' => 'Field group name',
            'template' => 'default', // bind the fieldgroup to a specific template, or default
            'fields' => array(
                // [IMGSPCL_STRING1]{IMGSPCL_STRING1}[/IMGSPCL_STRING1]
                'string1' => array(
                    'legend' => 'field 1',
                    'type' => 'str',
                    'render' => 'html',
                    'maxlength' => 100,
                    'placeholder' => ''
                ),
                // [IMGSPCL_TEXTAREA1]{IMGSPCL_TEXTAREA1}[/IMGSPCL_TEXTAREA1]
                'textarea1' => array(
                    'legend' => 'field 2',
                    'type' => 'textarea',
                    'render' => '',
                    'rows' => 3,
                    'placeholder' => ''
                ),
                // [IMGSPCL_OPTIONNAME1]
                //    Will be the selected value {IMGSPCL_OPTIONNAME1}
                //    [IMGSPCL_OPTIONNAME1_OPTION1]{IMGSPCL_OPTIONNAME1_OPTION1}[/IMGSPCL_OPTIONNAME1_OPTION1]
                //    [IMGSPCL_OPTIONNAME1_OPTION2]{IMGSPCL_OPTIONNAME1_OPTION2}[/IMGSPCL_OPTIONNAME1_OPTION2]
                // [/IMGSPCL_OPTIONNAME1]
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
                // [IMGSPCL_INTEGER1]{IMGSPCL_INTEGER1}[/IMGSPCL_INTEGER1]
                'integer1' => array(
                    'legend' => 'integer 1',
                    'type' => 'int',
                    'render' => '',
                    'max' => 1000,
                    'min' => -1000,
                    'step' => 1,
                    'placeholder' => ''
                ),
                // [IMGSPCL_FLOAT1]{IMGSPCL_FLOAT1}[/IMGSPCL_FLOAT1]
                'float1' => array(
                    'legend' => 'float 1',
                    'type' => 'float',
                    'render' => '',
                    'max' => 1000,
                    'min' => -1000,
                    'step' => .1,
                    'placeholder' => ''
                ),
                // [IMGSPCL_SELECT1]
                //    Will be the selected value {IMGSPCL_SELECT1}
                //    [IMGSPCL_SELECT1_OPTION1]{IMGSPCL_SELECT1_OPTION1}[/IMGSPCL_SELECT1_OPTION1]
                //    [IMGSPCL_SELECT1_OPTION2]{IMGSPCL_SELECT1_OPTION2}[/IMGSPCL_SELECT1_OPTION2]
                // [/IMGSPCL_SELECT1]
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
                // [IMGSPCL_BOOL1]True[/IMGSPCL_BOOL1][IMGSPCL_BOOL1_ELSE]False[/IMGSPCL_BOOL1_ELSE]
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
                // [IMGSPCL_WYSIWYG1]{IMGSPCL_WYSIWYG1}[/IMGSPCL_WYSIWYG1][IMGSPCL_WYSIWYG1_ELSE]<!--nada-->[/IMGSPCL_WYSIWYG1_ELSE]
                'wysiwyg1' => array(
                    'legend' => 'WYSIWYG editor',
                    'type' => 'textarea',
                    'render' => 'wysiwyg',
                    'rows' => 5,
                    'height' => '75px',
                    'placeholder' => ''
                )
            )
        ),
        */
    ),

    'wysiwyg_custom_fields' => array(
        // Enable and customise to enable additional input fields to WYSIWYG content part:
        /*
        'fieldgroup1' => array(
            'legend' => 'Field group name',
            'template' => 'default', // bind the fieldgroup to a specific template, or default
            'fields' => array(
                // [WYSIWYG_STRING1]{WYSIWYG_STRING1}[/WYSIWYG_STRING1]
                'string1' => array(
                    'legend' => 'field 1',
                    'type' => 'str',
                    'render' => 'html',
                    'maxlength' => 100,
                    'placeholder' => ''
                ),
                // [WYSIWYG_TEXTAREA1]{WYSIWYG_TEXTAREA1}[/WYSIWYG_TEXTAREA1]
                'textarea1' => array(
                    'legend' => 'field 2',
                    'type' => 'textarea',
                    'render' => '',
                    'rows' => 3,
                    'placeholder' => ''
                ),
                // [WYSIWYG_OPTIONNAME1]
                //    Will be the selected value {WYSIWYG_OPTIONNAME1}
                //    [WYSIWYG_OPTIONNAME1_OPTION1]{WYSIWYG_OPTIONNAME1_OPTION1}[/WYSIWYG_OPTIONNAME1_OPTION1]
                //    [WYSIWYG_OPTIONNAME1_OPTION2]{WYSIWYG_OPTIONNAME1_OPTION2}[/WYSIWYG_OPTIONNAME1_OPTION2]
                // [/WYSIWYG_OPTIONNAME1]
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
                // [WYSIWYG_INTEGER1]{WYSIWYG_INTEGER1}[/WYSIWYG_INTEGER1]
                'integer1' => array(
                    'legend' => 'integer 1',
                    'type' => 'int',
                    'render' => '',
                    'max' => 1000,
                    'min' => -1000,
                    'step' => 1,
                    'placeholder' => ''
                ),
                // [WYSIWYG_FLOAT1]{WYSIWYG_FLOAT1}[/WYSIWYG_FLOAT1]
                'float1' => array(
                    'legend' => 'float 1',
                    'type' => 'float',
                    'render' => '',
                    'max' => 1000,
                    'min' => -1000,
                    'step' => .1,
                    'placeholder' => ''
                ),
                // [WYSIWYG_SELECT1]
                //    Will be the selected value {WYSIWYG_SELECT1}
                //    [WYSIWYG_SELECT1_OPTION1]{WYSIWYG_SELECT1_OPTION1}[/WYSIWYG_SELECT1_OPTION1]
                //    [WYSIWYG_SELECT1_OPTION2]{WYSIWYG_SELECT1_OPTION2}[/WYSIWYG_SELECT1_OPTION2]
                // [/WYSIWYG_SELECT1]
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
                // [WYSIWYG_BOOL1]True[/WYSIWYG_BOOL1][WYSIWYG_BOOL1_ELSE]False[/WYSIWYG_BOOL1_ELSE]
                'bool1' => array(
                    'legend' => 'Enable (to be true)',
                    'type' => 'bool',
                    'default' => false, // or true to be enabled by default
                ),
                // [WYSIWYG_WYSIWYG1]{WYSIWYG_WYSIWYG1}[/WYSIWYG_WYSIWYG1][WYSIWYG_WYSIWYG1_ELSE]<!--nada-->[/WYSIWYG_WYSIWYG1_ELSE]
                'wysiwyg1' => array(
                    'legend' => 'WYSIWYG editor',
                    'type' => 'textarea',
                    'render' => 'wysiwyg',
                    'rows' => 10,
                    'height' => '175px',
                    'placeholder' => ''
                ),
            )
        ),
        */
    ),

    'tracking' => array(

        // Tracking code and/or position can be overwritten, not recommend!!!
        // You should know what you are doing here!

        // Google Analytics Tracking Code
        /*
        'ga' => array(
            'position' => 'head',
            'code' => "  <script".SCRIPT_ATTRIBUTE_TYPE." src=\"https://www.googletagmanager.com/gtag/js?id=%1\$s\" async></script>
  <script".SCRIPT_ATTRIBUTE_TYPE.">
    window.dataLayer = window.dataLayer || [];
    function gtag(){dataLayer.push(arguments);}
    gtag('js', new Date());
    gtag('config', '%1\$s'%2\$s);
  </script>",
           'optout' => "  <script".SCRIPT_ATTRIBUTE_TYPE.">
    var gaOptOutCookie = 'ga-disable-%1\$s';
    if (document.cookie.indexOf(gaOptOutCookie + '=true') > -1) {
        window[gaOptOutCookie] = true;
    }
    function gaOptout() {
        document.cookie = gaOptOutCookie + '=true; Expires=Thu, 31 Dec 2099 23:59:59 UTC; Path=/%2\$s';
        window[gaOptOutCookie] = true;
    }
  </script>"
        ),
        */

        /*
        'gtm' => array(
            'position' => 'head',
            'code' => "  <script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
  new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
  j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
  'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
  })(window,document,'script','dataLayer','%1\$s');</script>",
           'body' => '<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=%s" height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>'
        ),
        */

        // Matomo/Piwik Tracking Code http://developer.piwik.org/guides/tracking-javascript-guide
        /*
        'piwik' => array(
            'position' => 'head',
            'code' => '<script'.SCRIPT_ATTRIBUTE_TYPE.'>
  var _paq = window._paq = window._paq || [];
  _paq.push(["trackPageView"]);
  _paq.push(["enableLinkTracking"]);
  (function() {
    var u="//%1$s/";
    _paq.push(["setTrackerUrl", u+"matomo.php"]);
    _paq.push(["setSiteId", %2$d]);
    var d=document, g=d.createElement("script"), s=d.getElementsByTagName("script")[0];
    g.type="text/javascript"; g.async=true; g.src=u+"matomo.js"; s.parentNode.insertBefore(g,s);
  })();
</script>'
        ),
        */
    ),
);
