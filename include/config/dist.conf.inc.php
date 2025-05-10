<?php
/**
 * cmsGO!
 *
 * @author Pixels & Points GmbH <info@pixels-points.ch>
 * @copyright Copyright (c) 2002-2025, Pixels & Points GmbH
 * @license https://www.pixels-points.ch/cmsgo-license.html Pixels & Points cmsGO! license
 *
 **/

// cmsGO! base values -> needed in any document

// database values
$cmsgo['db_host']              = 'localhost';
$cmsgo['db_port']              = 3306;
$cmsgo['db_user']              = '';
$cmsgo['db_pass']              = '';
$cmsgo['db_table']             = '';
$cmsgo['db_prepend']           = '';
$cmsgo['db_pers']              = 0;
$cmsgo['db_charset']           = 'utf8mb4';
$cmsgo['db_collation']         = 'utf8mb4_general_ci';
$cmsgo['db_version']           = ''; // Version of MySQL Server at the time cmsGO! was installed
$cmsgo['db_timezone']          = ''; // SET MySQL session time zone https://dev.mysql.com/doc/refman/5.7/en/time-zone-support.html
$cmsgo['db_sql_mode']          = 'NO_ENGINE_SUBSTITUTION'; // SET MySQL sql_mode https://dev.mysql.com/doc/refman/5.7/en/sql-mode.html#sql-mode-setting
$cmsgo['db_errorlog']          = false; // Log DB queries - false|true

// site values
$cmsgo['site']                 = ''; // leave empty to auto configure or try 'http://'.$_SERVER['SERVER_NAME'].'/';
$cmsgo['site_ssl_mode']        = 0; // turns the SSL Support of WCMS on (1) or off (0), default value 0
$cmsgo['site_ssl_url']         = ''; // URL assigned to the SSL Certificate. Recommend 'https://'.$_SERVER['SERVER_NAME'].'/'
$cmsgo['site_ssl_port']        = 443; // The Port on which your SSL Service serve the secure Sites, default SSL port is 443

$cmsgo['admin_name']           = 'Webmaster';
$cmsgo['admin_user']           = 'admin';
$cmsgo['admin_pass']           = 'acf977c1cfa27a463246f6963055cb11'; //MD5
$cmsgo['admin_email']          = 'noreply@example.com';

// paths
$cmsgo['DOC_ROOT']             = $_SERVER['DOCUMENT_ROOT'];
$cmsgo['root']                 = '';
$cmsgo['file_path']            = 'filearchive';
$cmsgo['templates']            = 'template';
$cmsgo['content_path']         = 'content';
$cmsgo['cimage_path']          = 'images';
$cmsgo['ftp_path']             = 'upload';
$cmsgo['ads_path']             = 'marketing'; // it's the former 'ads' dir in '/content'

// content values
$cmsgo['file_maxsize']         = 52428800; // Bytes (50 x 1024 x 1024)
$cmsgo['content_width']        = 978; // max width of the article content column - important for rendering multi column images
$cmsgo['img_list_width']       = 100; // max with of the list thumbnail image
$cmsgo['img_list_height']      = 75; // max height of the list thumbnail image
$cmsgo['img_prev_width']       = 978; // max width of the large preview image
$cmsgo['img_prev_height']      = 734; // max height of the large preview image
$cmsgo['max_time']             = 1800; // logout after max_time/60 seconds
$cmsgo['responsive']           = 1; // 0 max. image width = $cmsgo['content_width'], 1 = as given
$cmsgo['preserve_image_name']  = 1; // keep file name for resized versions of the image

// other stuff
$cmsgo['image_library']        = 'GD2'; // GD, GD2, ImageMagick, GraphicsMagick or GM, NetPBM
$cmsgo['library_path']         = ''; // Path to ImageMagick or NetPBM
$cmsgo['rewrite_url']          = 1; // whether URL should be rewritable
$cmsgo['rewrite_ext']          = '.html'; // The extension for URL ReWrite, '.html' -> /alias.html, '/' -> /alias/
$cmsgo['alias_allow_slash']    = 1; // Allow slashes / in ALIAS
$cmsgo['alias_allow_utf8']     = 1; // If charset is utf-8 special chars will survive alias checking
$cmsgo['wysiwyg_editor']       = 1; // 0 = no wysiwyg editor, 1 = CKEditor 4
$cmsgo['default_lang']         = 'de'; // default language
$cmsgo['DOCTYPE_LANG']         = ''; // by default same as $cmsgo['default_lang'], but can be injected by whatever you like
$cmsgo['allowed_lang']         = array('en', 'de', 'fr', 'es'); //array of allowed languages
$cmsgo['use_content_lang']     = false; // if true use content language based on article and/or structure level
$cmsgo['be_lang_parse']        = false; // to disable backend language parsing use false, otherwise 'BBCode' or 'BraceCode'
$cmsgo['charset']              = 'utf-8'; // default charset 'utf-8' do not use soemthing different any longer
$cmsgo['php_charset']          = false; // set PHP default charset to $cmsgo['charset']
$cmsgo['allow_remote_URL']     = 0; // 0 = no remote URL in {PHP:...} replacement tag allowed, 1 = allowed
$cmsgo['jpg_quality']          = 85; // JPG Quality Range 25-100
$cmsgo['webp_enable']          = 1; // Render all images as WebP if the client browser supports it
$cmsgo['webp_quality']         = 85; // Set the WebP quality
$cmsgo['resize_animated_gif']  = true; // Try to resize animated GIF, this can lead to bigger file sizes
$cmsgo['sharpen_level']        = 1; // Sharpen Level - only ImageMagick: 0, 1, 2, 3, 4, 5 -- 0 = no, 5 = extra sharp
$cmsgo['allow_ext_init']       = 1; // allow including of custom external scripts at frontend initialization
$cmsgo['allow_ext_render']     = 1; // allow including of custom external scripts at frontend rendering
$cmsgo['cache_enabled']        = 0; // cache On/Off - 1 = caching On / 0 = caching Off (default)
$cmsgo['cache_timeout']        = 0; // default cache timeout setting in seconds - 0 = caching Off
$cmsgo['imgext_disabled']      = ''; // comma seperated list of imagetypes which should not be handled 'pdf,ps'
$cmsgo['multimedia_ext']       = 'aif,aiff,mov,movie,mp3,mpeg,mpeg4,mpeg2,wav,swf,swc,ram,ra,wma,wmv,avi,au,midi,moov,rm,rpm,mid,midi'; //comma seperated list of file extensiosn allowed for multimedia
$cmsgo['recipient_count']      = 0;
$cmsgo['inline_download']      = 1; // try to open download document in browser window
$cmsgo['sanitize_dlname']      = 0; // if there are problems downloading files with special chars in name try to enable this setting
$cmsgo['form_tracking']        = 1; // make a db entry for each form
$cmsgo['formmailer_set']       = array('allow_send_copy' => 0, 'global_recipient_email' => 'mail@example.com'); //for better security handling
$cmsgo['allow_cntPHP_rt']      = 0; // allow PHP replacement tags and includes in content parts
$cmsgo['BOTS']                 = array('googlebot', 'msnbot', 'bingbot', 'baiduspider', 'yandex', 'sosospider', 'ia_archiver', 'altavista', 'slurp', 'yahoo', 'jeeves', 'teoma', 'lycos', 'crawler');
$cmsgo['mode_XHTML']           = 3; // Doctype: 1 = XHTML 1.0 Transitional, 0 = HTML 4.01 Transitional, 2 = XHTML 1.0 Strict, 3 = HTML5
$cmsgo['header_XML']           = 0; // Content Type: 1 = application/xhtml+xml, 0 = text/html
$cmsgo['IE7-js']               = 0; // load IE7-js - fix for HTML/CSS/PNG bugs in IE
$cmsgo['php_timezone']         = ''; // overwrite PHP default time zone http://php.net/manual/en/timezones.php
$cmsgo['wysiwyg_template']     = array(); // deprecated
$cmsgo['GET_pageinfo']         = 0; // will add "&pageinfo=/cat1/cat2/page-title.htm" based on the breadcrumb information for each site link
$cmsgo['version_check']        = 1; // checks for current release of cmsGO! online
$cmsgo['SESSION_FEinit']       = 0; // set 1 to enable sessions in frontend, 0 to disable sessions in frontend
$cmsgo['Login_IPcheck']        = 0;
$cmsgo['frontend_edit']        = 0; // enable content specific direct links - linking direct into the backend
$cmsgo['gd_memcheck_off']      = 0; // disable GD php memory check before resize an image
$cmsgo['enable_chat']          = 0; // enable or disable chat function, by default it is disabled - not recommend anymore to use it
$cmsgo['enable_messages']      = 0; // enable or disable internal messags, by default it is disabled - not recommend anymore to use it
$cmsgo['enable_seolog']        = 1; // enable or disable logging of search engine referrer data
$cmsgo['i18n_parse']           = 1; // enable|disable browser based language parser - all @@Text@@ will be parsed and checked for translation/var based replacement
$cmsgo['i18n_complex']         = 0; // enable|disable the way browser language setting should be used, false = the easier way (always 2 chars "en"), true - "en-gb"...
$cmsgo['FCK_FileBrowser']      = 1; // enable|disable cmsGO! Filebrowser in FCKeditor instead of built-in FCK file bowser support
$cmsgo['feuser_regkey']        = 'FEUSER';
$cmsgo['edit.php']             = 'edit.php';
$cmsgo['js_lib']               = array(); // extends default lib settings array('jquery'=>'jQuery 1.3','mootools-1.4'=>'MooTools 1.4','mootools-1.1'=>'MooTools 1.1);
$cmsgo['video-js']             = ''; // can be stored locally too 'template/lib/video-js/ (https://vjs.zencdn.net/8.5.2/)
$cmsgo['render_device']        = 0; // allow user agent specific rendering templates <!--if:mobile-->DoMobile<!--/if--><!--!if:mobile-->DoNotMobile<!--/!if--><!--!if:default-->Default<!--/!if-->
$cmsgo['detect_pixelratio']    = 0; // will inject the page with JavaScript to detect Retina devices
$cmsgo['im_fix_colorspace']    = 'RGB'; // newer ImageMagick installs tend to have problems with colorspace setting, if colors are look bad try SRGB
$cmsgo['wkhtmltopdf_path']     = ''; // used for generating PDF, use full path including application name '/usr/bin/wkhtmltopdf'
$cmsgo['render_clean_html']    = 0; // clean up HTML source a bit, experimental can have unexpected side effects
$cmsgo['browser_check']        = array('fe' => false, 'be' => false, 'vs' => '', 'insecure' => true, 'required' => ''); // enable Browser Update check in frontend and/or backend, use "vs" to which browser version, see http://www.browser-update.org/index.html#install
$cmsgo['usergroup_support']    = false; // set true or false to support/disable this feature, is experimental
$cmsgo['force301_id2alias']    = false; // send 301 HTTP Redirect when article/structure has alias but ID is given
$cmsgo['force301_2struct']     = false; // send 301 HTTP Redirect to structure level when only 1 article is inside
$cmsgo['allow_empty_alias']    = false; // do not auto-create (default) alias when alias field is empty
$cmsgo['reserved_alias']       = array(); // use this to block custom alias
$cmsgo['enable_deprecated']    = false; // enable/disable deprecated functionality, enable if you miss things
$cmsgo['canonical_off']        = false; // disable canonical link tag
$cmsgo['viewport']             = 'width=device-width, initial-scale=1'; // set viewport https://developer.mozilla.org/en-US/docs/Web/HTML/Viewport_meta_tag
$cmsgo['X-UA-Compatible']      = ''; // what version of Internet Explorer the page should be rendered as, IE=edge, IE=10...
$cmsgo['base_href']            = true; // set the <base href=""> tag, use string (URL) or bool TRUE/FALSE
$cmsgo['cp_default']           = 0; // set the default CP ID here as used in structure level editor, see http://goo.gl/BVODr
$cmsgo['js_in_body']           = 0; // add <script> direct before </body> instead inside of <head>
$cmsgo['set_article_active']   = 1; // activate (1) or disable (0) article by default on create
$cmsgo['set_category_active']  = 1; // activate (1) or disable (0) category/structure level by default on create
$cmsgo['set_file_active']      = 1; // activate (1) or disable (0) files and folders by default on create
$cmsgo['set_news_active']      = 1; // activate (1) or disable (0) news by default on create
$cmsgo['log_404error']         = false; // log each 404 for redirect edit
$cmsgo['set_sociallink']       = array('article' => false, 'articlecat' => false, 'news' => false, 'shop' => false, 'render' => true); // TRUE/FALSE to enable status for article/articlecat/news/shop by default, render TRUE/FALSE to enable/disable in frontend
$cmsgo['header_comment']       = '';
$cmsgo['cnt_sort']             = 'a-z'; // not set or empty or false like before; 'a-z' or reverse 'z-a'
$cmsgo['cmsimage_redirect']    = false; // redirect to the resized/cropped image if true
$cmsgo['disable_next_prev']    = false; // https://support.google.com/webmasters/answer/1663744
$cmsgo['allowed_upload_ext']   = 'jpg,jpeg,png,webp,gif,tif,tiff,bmp,pic,psd,eps,ai,svg,pdf,ps,doc,docx,xls,xlsx,ppt,pptx,odt,odm,odg,ods,odp,odf,odc,odb,sxw,sxc,sxi,csv,txt,rtf,html,xml,ini,sql,db,zip,rar,7z,s7z,dmg,bz2,gz,tar,tgz,mkv,webm,vob,ogg,ogv,mov,qt,wmv,mpg,mpeg,mp3,mp4,m4p,flv,f4v,f4p,f4a,f4b';
$cmsgo['enable_inline_php']    = false; // disable [PHP] {PHP…} … by default
$cmsgo['parse_html_mode']      = 'before'; // when to parse html: [null|before, after, before+after] frontend render
$cmsgo['trash_delete_files']   = false; // set to true if files should be deleted if trash is emptied
$cmsgo['cmsimage_settings']    = array(); // to prevent flooding dynamic image resizing set which sizes are allowed only: array('500x500x0', 'default'=>'1280x800x1'[, …]), first is used as fallback or 'default' or use 'default'=>'empty' to return empty gif
$cmsgo['opengraph_imagesize']  = '1200x630x0'; // customize the open graph image size (Width x Height [x 1 = Crop], use 500x500 as minimum
$cmsgo['unregister_getVar']    = array(); // array('myvar1', 'myvar2', …) - if there are custom GET vars that should not be registered for global use in rel_url(), abs_url()
$cmsgo['preserve_getVar']      = array(); // cmsGO! removes some internal GET vars by default, add the ones that should be preserved https://github.com/slackero/cmsgo/blob/master/include/inc_lib/default.inc.php#L520
$cmsgo['enable_GDPR']          = true; // Try to handle GDPR inside of cmsGO! by default (anonymize IP...)
$cmsgo['login_autocomplete']   = true; // If true the browser/user can decide to store login/password and/or autofill in credentials
$cmsgo['lazy_loading']         = 'lazy'; // Set how images or iframes should be loaded: lazy (recommend), eager (right away) or auto (let browser decide).
$cmsgo['disable_generator']    = false; // Disable <meta name="generator"> and header `X-cmsgo-Release`
$cmsgo['disable_processed_in'] = false; // Hide header `X-cmsgo-Page-Processed-In`
$cmsgo['session.cookie_httponly.off'] = false; // Set this to `true` if the session Cookie should also be accessible by JavaScript
$cmsgo['session.cookie_samesite'] = 'Lax'; // Define the Cookie sameSite setting None (deprecated), Lax, Strict, use PHP 7.3+ otherwise it's not or not well supported
$cmsgo['enable_backend_newsletter'] = false; // Enable newsletter menu item in the backend, disabled by default
$cmsgo['enable_backend_module'] = false; // Enable module menu item in the backend, disabled by default
$cmsgo['remove_empty_get_vars'] = true; // If true all GET parameters without a value except the alias will be deleted

// Email specific settings (based on phpMailer)
$cmsgo['SMTP_FROM_EMAIL']      = 'info@localhost'; // reply/from email address
$cmsgo['SMTP_FROM_NAME']       = 'My Name'; // reply/from name
$cmsgo['SMTP_HOST']            = 'localhost'; // SMTP server (host/IP)
$cmsgo['SMTP_PORT']            = 25; // SMTP server port (default 25)
$cmsgo['SMTP_MAILER']          = 'mail'; // mail method: mail (default), smtp, sendmail, qmail
$cmsgo['SMTP_USER']            = 'user'; // default SMTP login (user) name
$cmsgo['SMTP_PASS']            = 'pass'; // default SMTP password
$cmsgo['SMTP_SECURE']          = ''; // secure connection, phpMailer options: '', 'ssl' or 'tls'
$cmsgo['SMTP_AUTH']            = 0; // SMTP authentication, ON=1/OFF=0
$cmsgo['SMTP_AUTH_TYPE']       = ''; // sets SMTP auth type: CRAM-MD5, LOGIN, PLAIN, XOAUTH2
$cmsgo['SMTP_XOAUTH_PROVIDER'] = ''; // XOAUTH2 authentication provider, currently 'Google', 'Microsoft' or 'Azure' are supported
$cmsgo['SMTP_CLIENT_ID']       = ''; // The client ID for OAuth2 authentication
$cmsgo['SMTP_CLIENT_SECRET']   = ''; // The client secret for OAuth2 authentication
$cmsgo['SMTP_TENANT_ID']       = ''; // The tenant ID for Microsoft OAuth2 authentication
$cmsgo['SMTP_REFRESH_TOKEN']   = ''; // The OAuth2 refresh token (see the backend to obtain it)

define('CMSGO_INCLUDE_CHECK', true);
