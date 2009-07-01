<?php

// database values
$phpwcms['db_host']           = 'localhost';
$phpwcms['db_user']           = 'db_user';
$phpwcms['db_pass']           = 'db_pass';
$phpwcms['db_table']          = 'db_table';
$phpwcms['db_prepend']        = '';
$phpwcms['db_pers']           = 1;
$phpwcms['db_charset']        = 'latin1';
$phpwcms['db_collation']      = 'latin1_general_ci';
$phpwcms['db_version']        = 0;

// site values
$phpwcms['site']              = 'http://'.$_SERVER['SERVER_NAME'].'/';
$phpwcms['admin_name']        = 'Webmaster';
$phpwcms['admin_user']        = 'admin';
$phpwcms['admin_pass']        = 'acf977c1cfa27a463246f6963055cb11'; //MD5
$phpwcms['admin_email']       = 'noreply@'.str_replace(array('www.', 'WWW.', '/'), '', $_SERVER["HTTP_HOST"]);

// paths
$ptemp = dirname(__FILE__);
$phpwcms['DOC_ROOT']          = empty($_SERVER['DOCUMENT_ROOT']) ? $ptemp : $_SERVER['DOCUMENT_ROOT'];

$ptemp = dirname($_SERVER['SCRIPT_NAME']);
$ptemp = preg_replace('/\/setup$/i','', $ptemp);
$ptemp = str_replace("\\", '/', $ptemp);
$ptemp = preg_replace('/^\//', '', $ptemp);
$ptemp = preg_replace('/\/$/', '', $ptemp);

$phpwcms['root']              = $ptemp;
$phpwcms['file_path']         = 'filearchive';    //default: 'filearchive'
$phpwcms['templates']         = 'template';    //default: 'template'
$phpwcms['content_path']      = 'content'; //default: 'content'
$phpwcms['cimage_path']       = 'images';  //default: 'images'
$phpwcms['ftp_path']          = 'upload';     //default: 'upload'

// content values
$phpwcms['file_maxsize']      = 2097152; //Bytes (50 x 1024 x 1024)
$phpwcms['content_width']     = 538; //max width of the article content column - important for rendering multi column images
$phpwcms['img_list_width']    = 100; //max with of the list thumbnail image
$phpwcms['img_list_height']   = 75; //max height of the list thumbnail image
$phpwcms['img_prev_width']    = 538; //max width of the large preview image
$phpwcms['img_prev_height']   = 400; //max height of the large preview image
$phpwcms['max_time']          = 1800; //logout after max_time/60 seconds

// other stuff
$phpwcms['compress_page']     = 0;        //wenn 1 = Seite komprimieren, 0 = Kompresion aus
$phpwcms['imagick']           = 0;        //if 0 = GD, 1 = ImageMagick convert, 2 = ImageMagick 4.2.9
$phpwcms['imagick_path']      = '';       //Path to ImageMagick
$phpwcms['use_gd2']           = 1;        //if 0 = GD1, 1 = GD2
$phpwcms['rewrite_url']       = 0;        //whether URL should be rewritable
$phpwcms['wysiwyg_editor']    = 2;        //0 = no wysiwyg editor, 2 = FCKeditor, 4 = spaw
$phpwcms['phpmyadmin']        = 0;        //enable/disable phpmyadmin in Admin section
$phpwcms['default_lang']      = 'en';     //default language
$phpwcms['DOCTYPE_LANG']      = '';		  //by default same as $phpwcms['default_lang'], but can be injected by whatever you like
$phpwcms['allowed_lang']      = array('en');     //array of allowed languages: array('en', 'de', 'fr', 'es')
$phpwcms['charset']           = 'ISO-8859-1';       //default charset 'iso-8859-1'
$phpwcms['allow_remote_URL']  = 0;        //0 = no remote URL in {PHP:...} replacement tag allowed, 1 = allowed
$phpwcms['gt_mod']            = 0;        //0 = Graphical Text MOD disabled, 1 = enabled
$phpwcms['jpg_quality']       = 85;		  //JPG Quality Range 25-100
$phpwcms['sharpen_level']     = 1;        //Sharpen Level - only ImageMagick: 0, 1, 2, 3, 4, 5 -- 0 = no, 5 = extra sharp
$phpwcms['allow_ext_init']    = 1;        //allow including of custom external scripts at frontend initialization
$phpwcms['allow_ext_render']  = 1;        //allow including of custom external scripts at frontend rendering
$phpwcms['cache_enabled']     = 0;        //cache On/Off - 1 = caching On / 0 = caching Off (default)
$phpwcms['cache_timeout']     = 14400;    //default cache timeout setting in seconds - 0 = caching Off
$phpwcms['imgext_disabled']   = ''; //comma seperated list of imagetypes which should not be handled 'pdf,ps'
$phpwcms['multimedia_ext']    = 'aif,aiff,mov,movie,mp3,mpeg,mpeg4,mpeg2,wav,swf,swc,ram,ra,wma,wmv,avi,au,midi,moov,rm,rpm,mid,midi'; //comma seperated list of file extensiosn allowed for multimedia
$phpwcms['recipient_count']   = 0;
$phpwcms['inline_download']   = 1; //try to open download document in browser window
$phpwcms['form_tracking']     = 1; //make a db entry for each form
$phpwcms['formmailer_set']    = array('allow_send_copy' => 0, 'global_recipient_email' => 'form@localhost'); //for better security handling
$phpwcms['allow_cntPHP_rt']   = 0; //allow PHP replacement tags and includes in content parts
$phpwcms['GETparameterName']  = 'id'; //must have a minimum of 2 chars
$phpwcms['BOTS']			  = array('googlebot', 'msnbot', 'ia_archiver', 'altavista', 'slurp', 'yahoo', 'jeeves', 'teoma', 'lycos', 'crawler');
$phpwcms['mode_XHTML']        = 1; // Doctype: 1 = XHTML 1.0 Transitional, 0 = HTML 4.01 Transitional
$phpwcms['header_XML']        = 0; // Content Type: 1 = application/xhtml+xml, 0 = text/html
$phpwcms['IE_htc_hover']      = 1; // enables HTC Hover for IE < 7 - has no effect in other browsers
$phpwcms['IE_htc_png']        = 1; // enables HTC pngbehavior for IE < 7 - has no effect in other browsers
$phpwcms['timezone_GMT']  	  = '+1';
$phpwcms['Bad_Behavior']      = 1; // enables spam blocking by Bad Behavior
$phpwcms['wysiwyg_template']  = array( 'FCKeditor' => 'phpwcms_basic,phpwcms_default,Default,Basic', 'SPAW2' => 'standard,all,mini' );
$phpwcms['GET_pageinfo']      = 0; // will add "&pageinfo=/cat1/cat2/page-title.htm" based on the breadcrumb information for each site link
$phpwcms['version_check']     = 1; // checks for current release of phpwcms online
$phpwcms['SESSION_FEinit']    = 0; // set 1 to enable sessions in frontend, 0 to disable sessions in frontend
$phpwcms['Login_IPcheck']     = 0;
$phpwcms['frontend_edit']	  = 0; // enable content specific direct links - linking direct into the backend
$phpwcms['gd_memcheck_off']   = 0; // disable GD php memory check before resize an image
$phpwcms['enable_chat']		  = 0; // enable or disable chat function, by default it is disabled - not recommend anymore to use it
$phpwcms['enable_messages']	  = 0; // enable or disable internal messags, by default it is disabled - not recommend anymore to use it
$phpwcms['enable_seolog']	  = 1; // enable or disable logging of search engine referrer data
$phpwcms['i18n_parse']	  	  = 1; // enable|disable browser based language parser - all @@Text@@ will be parsed and checked for translation/var based replacement
$phpwcms['i18n_complex']	  = 0; // enable|disable the way browser language setting should be used, false = the easier way (always 2 chars "en"), true - "en-gb"...
$phpwcms['FCK_FileBrowser']   = 0; // enable|disable phpwcms Filebrowser in FCKeditor instead of built-in FCK file bowser support
$phpwcms['JW_FLV_License']    = ''; // insert your JW FLV Media Player License Code here - License warning will no longer displayed
$phpwcms['feuser_regkey']	  = 'FEUSER';

// dynamic ssl encryption engine
$phpwcms['site_ssl_mode']     = '0'; // tuns the SSL Support of WCMS on(1) or off (0) DEFAULT '0'
$phpwcms['site_ssl_url']      = '';  //URL assigned to the SSL Certificate. DON'T add a slash at the End! Exp. 'https://www.yourdomainhere.tld'
$phpwcms['site_ssl_port']     = '443'; //The Port on which you SSL Service serve the secure Sites. Servers DEFAULT is '443'

// smtp values
$phpwcms['SMTP_FROM_EMAIL']   = ''; // reply/from email address
$phpwcms['SMTP_FROM_NAME']    = 'phpwcms webmaster'; // reply/from name
$phpwcms['SMTP_HOST']         = ''; // SMTP server (host/IP)
$phpwcms['SMTP_PORT']         = 25; // SMTP-Server port (default 25)
$phpwcms['SMTP_MAILER']       = 'mail'; // default phpMailer: smtp, mail (default), sendmail
$phpwcms['SMTP_AUTH']         = 0; // sets SMTP_AUTH to ON/OFF
$phpwcms['SMTP_USER']         = ''; // default SMTP login (user) name
$phpwcms['SMTP_PASS']         = ''; // default SMTP password


?>