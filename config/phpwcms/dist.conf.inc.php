<?php
/*************************************************************************************
   Copyright notice
   
   (c) 2002-2012 Oliver Georgi <oliver@phpwcms.de> // All rights reserved.
 
   This script is part of PHPWCMS. The PHPWCMS web content management system is
   free software; you can redistribute it and/or modify it under the terms of
   the GNU General Public License as published by the Free Software Foundation;
   either version 2 of the License, or (at your option) any later version.
  
   The GNU General Public License can be found at http://www.gnu.org/copyleft/gpl.html
   A copy is found in the textfile GPL.txt and important notices to the license 
   from the author is found in LICENSE.txt distributed with these scripts.
  
   This script is distributed in the hope that it will be useful, but WITHOUT ANY 
   WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A
   PARTICULAR PURPOSE.  See the GNU General Public License for more details.
 
   This copyright notice MUST APPEAR in all copies of the script!
*************************************************************************************/

// phpwcms base values -> needed in any document


// database values
$phpwcms['db_host']           = 'localhost';
$phpwcms['db_user']           = '';
$phpwcms['db_pass']           = '';
$phpwcms['db_table']          = '';
$phpwcms['db_prepend']        = '';
$phpwcms['db_pers']           = 1;
$phpwcms['db_charset']        = 'latin1';
$phpwcms['db_collation']      = 'latin1_general_ci';
$phpwcms['db_version']        = 0;
$phpwcms['db_timezone']		  = ''; // set MySQL session time zone http://dev.mysql.com/doc/refman/5.5/en/time-zone-support.html

// site values
$phpwcms['site']              = 'http://'.$_SERVER['SERVER_NAME'].'/';
$phpwcms['admin_name']        = 'Webmaster';
$phpwcms['admin_user']        = 'admin';
$phpwcms['admin_pass']        = 'acf977c1cfa27a463246f6963055cb11'; //MD5
$phpwcms['admin_email']       = 'noreply@'.str_replace(array('www.', 'WWW.', '/'), '', $_SERVER["HTTP_HOST"]);

// paths
$phpwcms['DOC_ROOT']          = $_SERVER['DOCUMENT_ROOT'];
$phpwcms['root']              = '';
$phpwcms['file_path']         = 'filearchive';
$phpwcms['templates']         = 'template';
$phpwcms['content_path']      = 'content';
$phpwcms['cimage_path']       = 'images';
$phpwcms['ftp_path']          = 'upload';

// content values
$phpwcms['file_maxsize']      = 52428800; //Bytes (50 x 1024 x 1024)
$phpwcms['content_width']     = 538;      //max width of the article content column - important for rendering multi column images
$phpwcms['img_list_width']    = 100;      //max with of the list thumbnail image
$phpwcms['img_list_height']   = 75;       //max height of the list thumbnail image
$phpwcms['img_prev_width']    = 538;      //max width of the large preview image
$phpwcms['img_prev_height']   = 538;      //max height of the large preview image
$phpwcms['max_time']          = 1800;     //logout after max_time/60 seconds

// other stuff
$phpwcms['image_library']     = 'GD2';    //GD, GD2, ImageMagick, NetPBM
$phpwcms['library_path']      = '';       //Path to ImageMagick or NetPBM
$phpwcms['rewrite_url']       = 0;        //whether URL should be rewritable
$phpwcms['wysiwyg_editor']    = 1;        //0 = no wysiwyg editor, 1 = CKEditor, 2 = FCKeditor
$phpwcms['phpmyadmin']        = 0;        //enable/disable phpmyadmin in Admin section
$phpwcms['default_lang']      = 'en';     //default language
$phpwcms['DOCTYPE_LANG']      = '';       //by default same as $phpwcms['default_lang'], but can be injected by whatever you like
$phpwcms['allowed_lang']      = array('en', 'de', 'fr', 'es');     //array of allowed languages
$phpwcms['be_lang_parse']     = false; // to disable backend language parsing use false, otherwise 'BBCode' or 'BraceCode'
$phpwcms['charset']           = 'utf-8';       //default charset 'utf-8' do not use soemthing different any longer
$phpwcms['allow_remote_URL']  = 0;        //0 = no remote URL in {PHP:...} replacement tag allowed, 1 = allowed
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
$phpwcms['formmailer_set']    = array('allow_send_copy' => 0, 'global_recipient_email' => 'mail@example.com'); //for better security handling
$phpwcms['allow_cntPHP_rt']   = 0; //allow PHP replacement tags and includes in content parts
$phpwcms['BOTS']			  = array('googlebot', 'msnbot', 'bingbot', 'ia_archiver', 'altavista', 'slurp', 'yahoo', 'jeeves', 'teoma', 'lycos', 'crawler');
$phpwcms['mode_XHTML']        = 1; // Doctype: 1 = XHTML 1.0 Transitional, 0 = HTML 4.01 Transitional, 2 = XHTML 1.0 Strict, 3 = HTML5
$phpwcms['header_XML']        = 0; // Content Type: 1 = application/xhtml+xml, 0 = text/html
$phpwcms['IE7-js']        	  = 0; // load IE7-js - fix for HTML/CSS/PNG bugs in IE
$phpwcms['php_timezone']  	  = ''; // overwrite PHP default time zone http://php.net/manual/en/timezones.php
$phpwcms['wysiwyg_template']  = array( 'FCKeditor' => 'phpwcms_basic,phpwcms_default,Default,Basic', 'CKEditor' => 'phpwcms,Default,Basic' );								   
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
$phpwcms['FCK_FileBrowser']   = 1; // enable|disable phpwcms Filebrowser in FCKeditor instead of built-in FCK file bowser support
$phpwcms['JW_FLV_License']    = ''; // insert your JW FLV Media Player License Code here - License warning will no longer displayed
$phpwcms['feuser_regkey']	  = 'FEUSER';
$phpwcms['login.php']	  	  = 'login.php';
$phpwcms['rewrite_ext']	  	  = '.html'; // The file extension used while URL is rewritten
$phpwcms['js_lib']			  = array('mootools-1.4'=>'MooTools 1.4','mootools-1.4-compat'=>'MooTools 1.4 Compat','mootools-1.2'=>'MooTools 1.2','mootools-1.1'=>'MooTools 1.1','jquery-1.7'=>'jQuery 1.7','jquery-1.6'=>'jQuery 1.6','jquery-1.5'=>'jQuery 1.5','jquery-1.4'=>'jQuery 1.4','jquery'=>'jQuery 1.3');
$phpwcms['video-js']          = 'http://vjs.zencdn.net/c/'; // can be stored locally too 'template/lib/video-js/ (http://videojs.com/)
$phpwcms['render_device']     = 0; // allow user agent specific rendering templates <!--if:mobile-->DoMobile<!--/if--><!--!if:mobile-->DoNotMobile<!--/!if--><!--!if:default-->Default<!--/!if-->

// dynamic ssl encryption engine
$phpwcms['site_ssl_mode'] 	  = '0'; // tuns the SSL Support of WCMS on(1) or off (0) DEFAULT '0'
$phpwcms['site_ssl_url'] 	  = ''; //URL assigned to the SSL Certificate. DON'T add a slash at the End! Exp. 'https://www.yourdomainhere.tld'
$phpwcms['site_ssl_port'] 	  = '443'; //The Port on which you SSL Service serve the secure Sites. Servers DEFAULT is '443'

// smtp values
$phpwcms['SMTP_FROM_EMAIL']   = 'info@localhost'; // reply/from email address
$phpwcms['SMTP_FROM_NAME']    = 'My Name'; // reply/from name
$phpwcms['SMTP_HOST']         = 'localhost'; // SMTP server (host/IP)
$phpwcms['SMTP_PORT']         = 25; // SMTP-Server port (default 25)
$phpwcms['SMTP_MAILER']       = 'mail'; // default phpMailer: smtp, mail (default), sendmail
$phpwcms['SMTP_AUTH']         = 0; // sets SMTP_AUTH to ON/OFF
$phpwcms['SMTP_USER']         = 'user'; // default SMTP login (user) name
$phpwcms['SMTP_PASS']         = 'pass'; // default SMTP password

define('PHPWCMS_INCLUDE_CHECK', true);

?>