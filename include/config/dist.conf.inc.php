<?php
/**
 * phpwcms content management system
 *
 * @author Oliver Georgi <og@phpwcms.org>
 * @copyright Copyright (c) 2002-2016, Oliver Georgi
 * @license http://opensource.org/licenses/GPL-2.0 GNU GPL-2
 * @link http://www.phpwcms.org
 *
 **/

// phpwcms base values -> needed in any document


// database values
$phpwcms['db_host']             = 'localhost';
$phpwcms['db_user']             = '';
$phpwcms['db_pass']             = '';
$phpwcms['db_table']            = '';
$phpwcms['db_prepend']          = '';
$phpwcms['db_pers']             = 0;
$phpwcms['db_charset']          = 'utf8';
$phpwcms['db_collation']        = 'utf8_general_ci';
$phpwcms['db_version']          = 0;
$phpwcms['db_timezone']         = ''; // set MySQL session time zone http://dev.mysql.com/doc/refman/5.5/en/time-zone-support.html

// site values
$phpwcms['site']                = ''; // leave empty to auto configure or try 'http://'.$_SERVER['SERVER_NAME'].'/';
$phpwcms['site_ssl_mode']       = 0; // turns the SSL Support of WCMS on (1) or off (0), default value 0
$phpwcms['site_ssl_url']        = ''; // URL assigned to the SSL Certificate. Recommend 'https://'.$_SERVER['SERVER_NAME'].'/'
$phpwcms['site_ssl_port']       = 443; // The Port on which your SSL Service serve the secure Sites, default SSL port is 443

$phpwcms['admin_name']          = 'Webmaster';
$phpwcms['admin_user']          = 'admin';
$phpwcms['admin_pass']          = 'acf977c1cfa27a463246f6963055cb11'; //MD5
$phpwcms['admin_email']         = 'noreply@example.com';

// paths
$phpwcms['DOC_ROOT']            = $_SERVER['DOCUMENT_ROOT'];
$phpwcms['root']                = '';
$phpwcms['file_path']           = 'filearchive';
$phpwcms['templates']           = 'template';
$phpwcms['content_path']        = 'content';
$phpwcms['cimage_path']         = 'images';
$phpwcms['ftp_path']            = 'upload';

// content values
$phpwcms['file_maxsize']        = 52428800; // Bytes (50 x 1024 x 1024)
$phpwcms['content_width']       = 978; // max width of the article content column - important for rendering multi column images
$phpwcms['img_list_width']      = 100; // max with of the list thumbnail image
$phpwcms['img_list_height']     = 75; // max height of the list thumbnail image
$phpwcms['img_prev_width']      = 978; // max width of the large preview image
$phpwcms['img_prev_height']     = 734; // max height of the large preview image
$phpwcms['max_time']            = 1800; // logout after max_time/60 seconds
$phpwcms['responsive']          = 1; // 0 max. image width = $phpwcms['content_width'], 1 = as given

// other stuff
$phpwcms['image_library']       = 'GD2'; // GD, GD2, ImageMagick, GraphicsMagick or GM, NetPBM
$phpwcms['library_path']        = ''; // Path to ImageMagick or NetPBM
$phpwcms['rewrite_url']         = 0; // whether URL should be rewritable
$phpwcms['rewrite_ext']         = '.html'; // The extension for URL ReWrite, '.html' -> /alias.html, '/' -> /alias/
$phpwcms['alias_allow_slash']   = 0; // Allow slashes / in ALIAS
$phpwcms['wysiwyg_editor']      = 1; // 0 = no wysiwyg editor, 1 = CKEditor 4
$phpwcms['default_lang']        = 'en'; // default language
$phpwcms['DOCTYPE_LANG']        = ''; // by default same as $phpwcms['default_lang'], but can be injected by whatever you like
$phpwcms['allowed_lang']        = array('en', 'de', 'fr', 'es'); //array of allowed languages
$phpwcms['be_lang_parse']       = false; // to disable backend language parsing use false, otherwise 'BBCode' or 'BraceCode'
$phpwcms['charset']             = 'utf-8'; // default charset 'utf-8' do not use soemthing different any longer
$phpwcms['php_charset']         = false; // set PHP default charset to $phpwcms['charset']
$phpwcms['allow_remote_URL']    = 0; // 0 = no remote URL in {PHP:...} replacement tag allowed, 1 = allowed
$phpwcms['jpg_quality']         = 85; // JPG Quality Range 25-100
$phpwcms['sharpen_level']       = 1; // Sharpen Level - only ImageMagick: 0, 1, 2, 3, 4, 5 -- 0 = no, 5 = extra sharp
$phpwcms['allow_ext_init']      = 1; // allow including of custom external scripts at frontend initialization
$phpwcms['allow_ext_render']    = 1; // allow including of custom external scripts at frontend rendering
$phpwcms['cache_enabled']       = 0; // cache On/Off - 1 = caching On / 0 = caching Off (default)
$phpwcms['cache_timeout']       = 0; // default cache timeout setting in seconds - 0 = caching Off
$phpwcms['imgext_disabled']     = ''; // comma seperated list of imagetypes which should not be handled 'pdf,ps'
$phpwcms['multimedia_ext']      = 'aif,aiff,mov,movie,mp3,mpeg,mpeg4,mpeg2,wav,swf,swc,ram,ra,wma,wmv,avi,au,midi,moov,rm,rpm,mid,midi'; //comma seperated list of file extensiosn allowed for multimedia
$phpwcms['recipient_count']     = 0;
$phpwcms['inline_download']     = 1; // try to open download document in browser window
$phpwcms['sanitize_dlname']     = 0; // if there are problems downloading files with special chars in name try to enable this setting
$phpwcms['form_tracking']       = 1; // make a db entry for each form
$phpwcms['formmailer_set']      = array('allow_send_copy' => 0, 'global_recipient_email' => 'mail@example.com'); //for better security handling
$phpwcms['allow_cntPHP_rt']     = 0; // allow PHP replacement tags and includes in content parts
$phpwcms['BOTS']                = array('googlebot', 'msnbot', 'bingbot', 'baiduspider', 'yandex', 'sosospider', 'ia_archiver', 'altavista', 'slurp', 'yahoo', 'jeeves', 'teoma', 'lycos', 'crawler');
$phpwcms['mode_XHTML']          = 3; // Doctype: 1 = XHTML 1.0 Transitional, 0 = HTML 4.01 Transitional, 2 = XHTML 1.0 Strict, 3 = HTML5
$phpwcms['header_XML']          = 0; // Content Type: 1 = application/xhtml+xml, 0 = text/html
$phpwcms['IE7-js']              = 0; // load IE7-js - fix for HTML/CSS/PNG bugs in IE
$phpwcms['php_timezone']        = ''; // overwrite PHP default time zone http://php.net/manual/en/timezones.php
$phpwcms['wysiwyg_template']    = array(); // deprecated
$phpwcms['GET_pageinfo']        = 0; // will add "&pageinfo=/cat1/cat2/page-title.htm" based on the breadcrumb information for each site link
$phpwcms['version_check']       = 1; // checks for current release of phpwcms online
$phpwcms['SESSION_FEinit']      = 0; // set 1 to enable sessions in frontend, 0 to disable sessions in frontend
$phpwcms['Login_IPcheck']       = 0;
$phpwcms['frontend_edit']       = 0; // enable content specific direct links - linking direct into the backend
$phpwcms['gd_memcheck_off']     = 0; // disable GD php memory check before resize an image
$phpwcms['enable_chat']         = 0; // enable or disable chat function, by default it is disabled - not recommend anymore to use it
$phpwcms['enable_messages']     = 0; // enable or disable internal messags, by default it is disabled - not recommend anymore to use it
$phpwcms['enable_seolog']       = 1; // enable or disable logging of search engine referrer data
$phpwcms['i18n_parse']          = 1; // enable|disable browser based language parser - all @@Text@@ will be parsed and checked for translation/var based replacement
$phpwcms['i18n_complex']        = 0; // enable|disable the way browser language setting should be used, false = the easier way (always 2 chars "en"), true - "en-gb"...
$phpwcms['FCK_FileBrowser']     = 1; // enable|disable phpwcms Filebrowser in FCKeditor instead of built-in FCK file bowser support
$phpwcms['JW_FLV_License']      = ''; // insert your JW FLV Media Player License Code here - License warning will no longer displayed
$phpwcms['feuser_regkey']       = 'FEUSER';
$phpwcms['login.php']           = 'login.php';
$phpwcms['js_lib']              = array(); // extends default lib settings array('jquery'=>'jQuery 1.3','mootools-1.4'=>'MooTools 1.4','mootools-1.1'=>'MooTools 1.1);
$phpwcms['video-js']            = ''; // can be stored locally too 'template/lib/video-js/ (//vjs.zencdn.net/5.12/)
$phpwcms['render_device']       = 0; // allow user agent specific rendering templates <!--if:mobile-->DoMobile<!--/if--><!--!if:mobile-->DoNotMobile<!--/!if--><!--!if:default-->Default<!--/!if-->
$phpwcms['detect_pixelratio']   = 0; // will inject the page with JavaScript to detect Retina devices
$phpwcms['im_fix_colorspace']   = 'RGB'; // some ImageMagick installs (on Mac) might have problems with colorspace setting, if colors are not good try SRGB
$phpwcms['wkhtmltopdf_path']    = ''; // used for generating PDF, use full path including application name '/usr/bin/wkhtmltopdf'
$phpwcms['render_clean_html']   = 0; // clean up HTML source a bit, experimental can have unexpected side effects
$phpwcms['browser_check']       = array('fe'=>false, 'be'=>true, 'vs' => ''); // enable Browser Update check in frontend and/or backend, use "vs" to which browser version, see http://www.browser-update.org/index.html#install
$phpwcms['usergroup_support']   = false; // set true or false to support/disable this feature, is experimental
$phpwcms['force301_id2alias']   = false; // send 301 HTTP Redirect when article/structure has alias but ID is given
$phpwcms['force301_2struct']    = false; // send 301 HTTP Redirect to structure level when only 1 article is inside
$phpwcms['allow_empty_alias']   = false; // do not auto-create (default) alias when alias field is empty
$phpwcms['reserved_alias']      = array(); // use this to block custom alias
$phpwcms['enable_deprecated']   = false; // enable/disable deprecated functionality, enable if you miss things
$phpwcms['canonical_off']       = false; // disable canonical link tag
$phpwcms['viewport']            = ''; // set viewport like "width=device-width, initial-scale=1.0, user-scalable=no"
$phpwcms['X-UA-Compatible']     = 'IE=Edge'; // set browser compatibility mode using meta tag X-UA-Compatible
$phpwcms['base_href']           = false; // set the <base href=""> tag, use string (URL) or bool TRUE/FALSE
$phpwcms['cp_default']          = 0; // set the default CP ID here as used in structure level editor, see http://goo.gl/BVODr
$phpwcms['js_in_body']          = 0; // add <script> direct before </body> instead inside of <head>
$phpwcms['set_article_active']  = 1; // activate (1) or disable (0) article by default on create
$phpwcms['set_category_active'] = 1; // activate (1) or disable (0) category/structure level by default on create
$phpwcms['set_file_active']     = 1; // activate (1) or disable (0) files and folders by default on create
$phpwcms['set_news_active']     = 1; // activate (1) or disable (0) news by default on create
$phpwcms['log_404error']        = false; // log each 404 for redirect edit
$phpwcms['set_sociallink']      = array('article' => false, 'articlecat' => false, 'news' => false, 'shop' => false, 'render' => true); // TRUE/FALSE to enable status for article/articlecat/news/shop by default, render TRUE/FALSE to enable/disable in frontend
$phpwcms['header_comment']      = '';
$phpwcms['cnt_sort']            = 'a-z'; // not set or empty or false like before; 'a-z' or reverse 'z-a'
$phpwcms['cmsimage_redirect']   = false; // redirect to the resized/cropped image if true
$phpwcms['disable_next_prev']   = false; // https://support.google.com/webmasters/answer/1663744
$phpwcms['allowed_upload_ext']  = 'jpg,jpeg,png,gif,tif,tiff,bmp,pic,psd,eps,ai,svg,pdf,ps,doc,docx,xls,xlsx,ppt,pptx,odt,odm,odg,ods,odp,odf,odc,odb,sxw,sxc,sxi,csv,txt,rtf,html,xml,ini,sql,db,zip,rar,7z,s7z,dmg,bz2,gz,tar,tgz,mkv,webm,vob,ogg,ogv,mov,qt,wmv,mpg,mpeg,mp4,m4p,flv,f4v,f4p,f4a,f4b';
$phpwcms['enable_inline_php']   = false; // disable [PHP] {PHP…} … by default
$phpwcms['parse_html_mode']     = 'before'; // when to parse html: [null|before, after, before+after] frontend render
$phpwcms['trash_delete_files']  = false; // set to true if files should be deleted if trash is emptied
$phpwcms['cmsimage_settings']   = array(); // to prevent flooding dynamic image resizing set which sizes are allowed only: array('500x500x0', 'default'=>'1280x800x1'[, …]), first is used as fallback or 'default' or use 'default'=>'empty' to return empty gif
$phpwcms['recaptcha_pu']   = ''; // google recapcha public key
$phpwcms['recaptcha_pr']   = ''; // google recapcha private key
// Email specific settings (based on phpMailer)
$phpwcms['SMTP_FROM_EMAIL']     = 'info@localhost'; // reply/from email address
$phpwcms['SMTP_FROM_NAME']      = 'My Name'; // reply/from name
$phpwcms['SMTP_HOST']           = 'localhost'; // SMTP server (host/IP)
$phpwcms['SMTP_PORT']           = 25; // SMTP server port (default 25)
$phpwcms['SMTP_MAILER']         = 'mail'; // mail method: mail (default), smtp, sendmail
$phpwcms['SMTP_USER']           = 'user'; // default SMTP login (user) name
$phpwcms['SMTP_PASS']           = 'pass'; // default SMTP password
$phpwcms['SMTP_SECURE']         = ''; // secure connection, phpMailer options: '', 'ssl' or 'tls'
$phpwcms['SMTP_AUTH']           = 0; // SMTP authentication, ON=1/OFF=0
$phpwcms['SMTP_AUTH_TYPE']      = ''; // sets SMTP auth type: LOGIN (default), PLAIN, NTLM, CRAM-MD5
$phpwcms['SMTP_REALM']          = ''; // SMTP realm, used for NTLM auth type
$phpwcms['SMTP_WORKSTATION']    = ''; // SMTP workstation, used for NTLM auth type

define('PHPWCMS_INCLUDE_CHECK', true);
