<?php
// predefine the default article/content values like space holder images, classes and so on

// simple navigation table defaults
$template_default['nav_table_simple_struct']['width']		= '100%';
$template_default['nav_table_simple_struct']['border']		= '0';
$template_default['nav_table_simple_struct']['cellpadding']	= '0';
$template_default['nav_table_simple_struct']['cellspacing']	= '0';

// row based navigation
$template_default['nav_row']['before']						= '';
$template_default['nav_row']['after']						= '';
$template_default['nav_row']['between']						= ' | ';
$template_default['nav_row']['link_before']					= '';
$template_default['nav_row']['link_after']					= '';
$template_default['nav_row']['link_before_active']			= '<span style="text-decoration:none;font-weight:bold;">';
$template_default['nav_row']['link_after_active']			= '</span>';
$template_default['nav_row']['link_direct_before']			= '';
$template_default['nav_row']['link_direct_after']			= '';
$template_default['nav_row']['link_direct_before_active']	= '';
$template_default['nav_row']['link_direct_after_active']	= '';

// navigation table defaults
$template_default['nav_table_struct']['table_border']			= '0';
$template_default['nav_table_struct']['table_width']			= '100%';
$template_default['nav_table_struct']['table_height']			= '';
$template_default['nav_table_struct']['table_bgcolor']			= '';
$template_default['nav_table_struct']['table_class']			= '';
$template_default['nav_table_struct']['table_cspace']			= '0';
$template_default['nav_table_struct']['table_cpad']				= '0';
//
$template_default['nav_table_struct']['space_width']			= 10;
$template_default['nav_table_struct']['space_left']				= 7;
$template_default['nav_table_struct']['space_right']			= 10;
$template_default['nav_table_struct']['space_celltop']			= 2;
$template_default['nav_table_struct']['space_cellbottom']		= 2;
//
$template_default['nav_table_struct']['cell_width']				= '100%';
$template_default['nav_table_struct']['cell_height']			= '15';
$template_default['nav_table_struct']['cell_class']				= 'nav_table';
//
$template_default['nav_table_struct']['cell_active_width']		= '100%';
$template_default['nav_table_struct']['cell_active_height']		= '15';
$template_default['nav_table_struct']['cell_active_class']		= 'nav_table_active';
//
$template_default['nav_table_struct']['js_over_effects']		= 1;
$template_default['nav_table_struct']['all_nodes_active']		= 1;
//	
$template_default['nav_table_struct']['linkimage_norm']			= '<img src="img/article/nav_link_0.gif" alt="" border="0" />';
$template_default['nav_table_struct']['linkimage_over']			= '<img src="img/article/nav_link_1.gif" alt="" border="0" />';
$template_default['nav_table_struct']['linkimage_active']		= '<img src="img/article/nav_link_2.gif" alt="" border="0" />';
//
$template_default['nav_table_struct']['link_before']			= '';
$template_default['nav_table_struct']['link_after']				= '';
$template_default['nav_table_struct']['link_active_before']		= '';
$template_default['nav_table_struct']['link_active_after']		= '';
//
$template_default['nav_table_struct']['row_norm_bgcolor']		= '#D9DEE3';
$template_default['nav_table_struct']['row_norm_class']			= '';
//	
$template_default['nav_table_struct']['row_over_bgcolor']		= '#D3ED7D'; //#AAB7C1
$template_default['nav_table_struct']['row_active_bgcolor']		= '#FFFFFF';
$template_default['nav_table_struct']['row_active_class']		= '';
//
$template_default['nav_table_struct']['row_space']				= 1;
$template_default['nav_table_struct']['row_space_bgcolor']		= '#4A5966';

/*
 * sublevel layout
 * ===============
 * setup unique style for each node/level
 * ['array_struct'][1] => the number represents level ID
 * so root level is [0] and so on...  
 
$template_default['nav_table_struct']['array_struct'][1]['linkimage_norm']		= '<img src="img/article/nav_link_0.gif" alt="" border="0" />';
$template_default['nav_table_struct']['array_struct'][1]['linkimage_over']		= '<img src="img/article/nav_link_1.gif" alt="" border="0" />';
$template_default['nav_table_struct']['array_struct'][1]['linkimage_active']	= '<img src="img/article/nav_link_2.gif" alt="" border="0" />';

$template_default['nav_table_struct']['array_struct'][1]['link_before']			= '';
$template_default['nav_table_struct']['array_struct'][1]['link_after']			= '';
$template_default['nav_table_struct']['array_struct'][1]['link_active_before']	= '';
$template_default['nav_table_struct']['array_struct'][1]['link_active_after']	= '';

$template_default['nav_table_struct']['array_struct'][1]['row_norm_bgcolor']	= '#FAEAC8';
$template_default['nav_table_struct']['array_struct'][1]['row_norm_class']		= $template_default['nav_table_struct']['row_norm_class'];
$template_default['nav_table_struct']['array_struct'][1]['row_over_bgcolor']	= '#FAEAC8';
$template_default['nav_table_struct']['array_struct'][1]['row_active_bgcolor']	= '#FAEAC8';
$template_default['nav_table_struct']['array_struct'][1]['row_active_class']	= $template_default['nav_table_struct']['row_active_class'];
			
$template_default['nav_table_struct']['array_struct'][1]['space_celltop']		= $template_default['nav_table_struct']['space_celltop'];
$template_default['nav_table_struct']['array_struct'][1]['space_cellbottom']	= $template_default['nav_table_struct']['space_cellbottom'];

$template_default['nav_table_struct']['array_struct'][1]['cell_height']		= $template_default['nav_table_struct']['cell_height'];
$template_default['nav_table_struct']['array_struct'][1]['cell_class']			= 'navLevel1';
$template_default['nav_table_struct']['array_struct'][1]['cell_active_height']	= $template_default['nav_table_struct']['cell_active_height'];
$template_default['nav_table_struct']['array_struct'][1]['cell_active_class']	= 'navLevel1a';
 */


// article listing with summary
$template_default['space_top']				= '';
$template_default['space_bottom']			= '';
$template_default['space_aftertop_text']	= '';
$template_default['space_between_list']		= '';
$template_default['space_between_sum']		= '';
//
$template_default['top_listentry_before']   = '<div class="listEntry">';
$template_default['top_listentry_after']    = '</div>';
$template_default['top_headline_before']    = '<h1>';
$template_default['top_headline_after']     = '</h1>';
$template_default['top_subheadline_before'] = '<h2>';
$template_default['top_subheadline_after']  = '</h2>';
$template_default['top_text_before']        = '<div class="topText">';
$template_default['top_text_after']         = '</div>';
$template_default['top_readmore_before']	= ' ';
$template_default['top_readmore_link']		= 'more&#8230;';
$template_default['top_readmore_after']		= '';
$template_default['top_headline_space']		= '';
$template_default['top_subheadline_space']	= '';
$template_default['top_count']				= 1;
$template_default['article_order']			= 1; // 0 = manual, 2 = creation date, 4 = start date -> + 0 = ASC, + 1 = DESC
$template_default['article_paginate_navi']	= '<div class="paginate paginate-{POS}">{PREV:&laquo;} {NEXT:&raquo;} page #/##, result ###-####, {NAVI:1-3, |<span>|</span>}</div>'; //
$template_default['article_paginate_show']	= 'top bottom rt{RT}'; //where should the navi be shown - possible values: top and/or bottom and/or rt:{RT}
$template_default['article_render_anchor']	= false; // render article jumpID anchor true||false

//
$template_default['list_startimage']		= '<img src="img/article/list_startimage.gif" width="11" height="7" border="0" alt="" />';
$template_default['list_headline_before']   = '<div class="articleListListhead">';
$template_default['list_headline_after']    = '</div>';

// breadcrumb
$template_default['breadcrumb_spacer']		= ' <span class="breadcrumb_spacer">&gt;</span> ';
$template_default['breadcrumb_active_prefix'] = '<strong>';
$template_default['breadcrumb_active_suffix'] = '</strong>';

// article default
$template_default['article']['title_before']		= '<h1>';
$template_default['article']['title_after']			= '</h1>';
$template_default['article']['subtitle_before']		= '<h2>';
$template_default['article']['subtitle_after']		= '</h2>';
$template_default['article']['summary_before']		= '<div class="articleSummary">';
$template_default['article']['summary_after']		= '</div>';

$template_default['article']['div_spacer']			= true; //if true or 'div' = <div>..., if false or not set <br><img...>

$template_default['article']['head_after']			= '';

$template_default['article']['content_head_before']		= '<h3>';
$template_default['article']['content_head_after']		= '</h3>';
$template_default['article']['content_subhead_before']	= '<h4>';
$template_default['article']['content_subhead_after']	= '</h4>';

$template_default['article']['text_class']				= 'articleText';
$template_default['article']['code_class']				= 'articleCode';

$template_default['article']['link_email_before']		= '<div class="linkEmail"><img src="img/article/extlink_1.gif" alt="" /><img src="img/leer.gif" width="1" height="11" alt="" />';
$template_default['article']['link_email_after']		= '</div>';

$template_default['article']['bullet_sign']				= '<img src="img/article/bullet_1.gif" alt="" /><img src="img/leer.gif" width="1" height="1" alt="" />';
$template_default['article']['link_sign']				= '<img src="img/article/extlink_1.gif" alt="" /><img src="img/leer.gif" width="1" height="11" alt="" />';
$template_default['article']['back_sign']				= '<img src="img/article/back_link_0.gif" border="0" alt="" />';
$template_default['article']['top_sign']				= '<img src="img/article/top_link_0.gif" border="0" alt="" />';
$template_default['article']['top_sign_before']			= '<div align="right">';
$template_default['article']['top_sign_after']			= '</div>';

$template_default['article']['file_name_cell_class']	= 'v11';
$template_default['article']['file_size_cell_class']	= 'v10';
$template_default['article']['file_size_info_class']	= 'v09';

$template_default['article']['form_align']              = '';

$template_default['article']['link_article_sign']		= '<img src="img/article/intlink_1.gif" alt="" /><img src="img/leer.gif" width="1" height="11" alt="" />';
$template_default['article']['link_article_class']		= 'articleLinkInternal';

$template_default['article']['image_table_class']		= '';
$template_default['article']['image_table_bgcolor']		= '';
$template_default['article']['image_bgcolor']			= '';
$template_default['article']['image_align']				= '';
$template_default['article']['image_valign']			= '';
$template_default['article']['image_border']			= 0;
$template_default['article']['image_class']				= "image_td";
$template_default['article']['image_imgclass']			= "image_img";
$template_default['article']['image_caption_class']		= "image_caption";
$template_default['article']['image_caption_bgcolor']	= '';
$template_default['article']['image_caption_valign']	= '';
$template_default['article']['image_caption_align']		= 'center';
$template_default['article']['image_caption_before']	= '';
$template_default['article']['image_caption_after']		= '';
$template_default['article']['image_div']				= true;

$template_default['article']['image_default_width']		= '200';
$template_default['article']['image_default_height']	= '200';

$template_default['article']['imagelist_table_class']		= '';
$template_default['article']['imagelist_table_bgcolor']		= '';
$template_default['article']['imagelist_spacerrow_class']	= 'imagelistSpacerRow';
$template_default['article']['imagelist_bgcolor']			= '';
$template_default['article']['imagelist_align']				= '';
$template_default['article']['imagelist_valign']			= '';
$template_default['article']['imagelist_border']			= 0;
$template_default['article']['imagelist_class']				= 'imagelisttd';
$template_default['article']['imagelist_imgclass']			= 'imagelistimg';
$template_default['article']['imagelist_caption_class']		= 'imglistcaption';
$template_default['article']['imagelist_caption_bgcolor']	= '';
$template_default['article']['imagelist_caption_valign']	= '';
$template_default['article']['imagelist_caption_align']		= '';
$template_default['article']['imagelist_caption_before']	= '';
$template_default['article']['imagelist_caption_after']		= '';

$template_default['article']['imagelist_default_width']		= 100;
$template_default['article']['imagelist_default_height']	= 100;

$template_default['imagegallery_default_width']				= 200;
$template_default['imagegallery_default_height']			= 175;
$template_default['imagegallery_default_space']				= 3;
$template_default['imagegallery_default_column']			= 2;


$template_default['article']['keyword_before']          = '<span class="phpwcmsKeywords">';
$template_default['article']['keyword_after']           = '</span>';
$template_default['article']['newsletter_error']		= 'Please check email address!';

// date and time formatting
$template_default['date']['language']		= 'EN';         // DE=German, IT=Italian, FR=French
$template_default['date']['long']			= 'l, j. F Y';  // (Monday, 1. October 2003)
$template_default['date']['medium']			= 'D, j. M y';  // (Mon, 1. Oct 03)
$template_default['date']['short']			= 'Y/m/d';      // (2003/12/25)
$template_default['date']['article']		= 'Y/m/d';      // (2003/12/25)
$template_default['time']['long']			= 'H:i:s';      // 15:25:45
$template_default['time']['short']			= 'H:i';        // 15:25

// rss image
$template_default['rss']['image']	= '<img src="img/article/rss_valid.gif" width="64" height="13" border="0" alt="Valid RSS" />';

// related articles based on keywords
$template_default['related']['before']			= '<div class="related">';
$template_default['related']['after']			= '</div>';
$template_default['related']['link_before']		= '<p>';
$template_default['related']['link_after']		= "</p>";
$template_default['related']['link_symbol']		= '';
$template_default['related']['link_target']		= '';
$template_default['related']['link_length']		= 0; //if 0 no limit
$template_default['related']['cut_title_add']	= '&#8230;';
$template_default['related']['sort_by']			= '';

// new articles
$template_default['news']['before']			= '<div class="news">';
$template_default['news']['after']			= '</div>';
$template_default['news']['link_before']	= '<p>';
$template_default['news']['link_after']		= '</p>';
$template_default['news']['link_symbol']	= '';
$template_default['news']['link_target']	= '';
$template_default['news']['link_length']	= 0; //if 0 no limit
$template_default['news']['cut_title_add']	= '&#8230;';
$template_default['news']['date_language']	= 'EN'; // DE=German, IT=Italian, FR=French, ES = Spanish, DA = Danish, NO = Norwegian
$template_default['news']['date_format']	= 'Y/m/d'; //if empty -> no Date
$template_default['news']['date_before']	= '<span class="datelink">';
$template_default['news']['date_after']		= ' - </span>';
$template_default['news']['sort_by']		= 'cdate'; // 'cdate' = Creation date, or 'udate' = update date, ldate = start date, kdate = end date

// ecards
$template_default['article']['ecard_table_class']		= "image_table";
$template_default['article']['ecard_table_bgcolor']		= '';
$template_default['article']['ecard_bgcolor']			= '';
$template_default['article']['ecard_align']				= '';
$template_default['article']['ecard_valign']			= '';
$template_default['article']['ecard_border']			= 0;
$template_default['article']['ecard_imgclass']			= '';
$template_default['article']['ecard_class']				= 'image_td';
$template_default['article']['ecard_caption_class']		= 'image_caption';
$template_default['article']['ecard_caption_bgcolor']	= '';
$template_default['article']['ecard_caption_valign']	= '';
$template_default['article']['ecard_caption_align']		= 'center';
$template_default['article']['ecard_caption_before']	= '';
$template_default['article']['ecard_caption_after']		= '';
$template_default['article']['ecard_chooser_css']		= 'style="margin:3px 0 3px 0;"';
$template_default['article']['ecard_chooser_text']		= 'style="font-weight:bold;"';

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


?>