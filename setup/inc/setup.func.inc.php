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

//setup functions

$DOCROOT = rtrim(str_replace('\\', '/', dirname(dirname(dirname(__FILE__)))), '/');
include($DOCROOT.'/include/inc_lib/revision/revision.php');

if(empty($_SERVER['DOCUMENT_ROOT'])) {
	$_SERVER['DOCUMENT_ROOT'] = $DOCROOT;
}

$phpwcms_version		= PHPWCMS_VERSION;
$phpwcms_release_date	= PHPWCMS_RELEASE_DATE;
$phpwcms_revision		= PHPWCMS_REVISION;

function read_textfile($filename) {
	if(is_file($filename)) {
		$fd = @fopen($filename, "rb");
		$text = fread($fd, filesize($filename));
		fclose($fd);
		return $text;				
	} else {
		return false;
	}
}

function write_textfile($filename, $text) {
	if($fp = @fopen($filename, "w+b")) {;
		fwrite($fp, $text);
		fclose($fp);
		return true;	
	} else {
		return false;
	}
}

function set_chmod($path, $rights, $status, $file_folder=0) {

	$Cpath = $_SERVER['DOCUMENT_ROOT'].$path;
	if(@file_exists($Cpath)) {
		if(@chmod($Cpath, $rights)) {
			$status = $file_folder ? check_path_status($path) : check_file_status($path);
		}
	}
	return $status;
}

function check_path_status($path) {
	$path = $_SERVER['DOCUMENT_ROOT'].$path;
	$status = 0;
	$status += (is_dir($path)) ? 1 : 0;
	if($status) {
		$status += (is_writable($path)) ? 1 : 0;
	}
	return $status;
}

function check_file_status($path) {
	$path = $_SERVER['DOCUMENT_ROOT'].$path;
	$status = 0;
	$status += (is_file($path)) ? 1 : 0;
	if($status) {
		$status += (is_writable($path)) ? 1 : 0;
	}
	return $status;
}

function gib_bg_color($status) {
	$color = ' bgcolor="#FF3300"';
	switch($status) {
		case 2: $color = ' bgcolor="#99CC00"';
				break;
		case 1: $color = ' bgcolor="#99CC00"';
				break;
	}
	return $color;
	
}

function gib_status_text($status) {
	$msg = "&nbsp;<b>FALSE</b> (not existing)";
	switch($status) {
		case 2: $msg = "&nbsp;<b>OK</b> (exists + writable)";
				break;
		case 1: $msg = "&nbsp;<b>FALSE</b> (exists + not writable)";
				break;
		case 3: $msg = "&nbsp;<b>OK</b> (exists + not writable)";
				break;
	}
	return $msg;
}

function slweg($string_wo_slashes_weg, $string_laenge=0) {
	// Falls die Serverfunktion magic_quotes_gpc aktiviert ist, so
	// sollen die Slashes herausgenommen werden, anderenfalls nicht
	$string_wo_slashes_weg = trim($string_wo_slashes_weg);
	if( get_magic_quotes_gpc() ) $string_wo_slashes_weg = stripslashes ($string_wo_slashes_weg);
	if($string_laenge) $string_wo_slashes_weg = substr($string_wo_slashes_weg, 0, $string_laenge);
	return $string_wo_slashes_weg;
}

function clean_slweg($string_wo_slashes_weg, $string_laenge=0) {
	// Falls die Serverfunktion magic_quotes_gpc aktiviert ist, so
	// sollen die Slashes herausgenommen werden, anderenfalls nicht
	$string_wo_slashes_weg = trim($string_wo_slashes_weg);
	if( get_magic_quotes_gpc() ) $string_wo_slashes_weg = stripslashes ($string_wo_slashes_weg);
	$string_wo_slashes_weg = strip_tags($string_wo_slashes_weg);
	if($string_laenge) $string_wo_slashes_weg = substr($string_wo_slashes_weg, 0, $string_laenge);
	return $string_wo_slashes_weg;
}

function write_conf_file($val) {

	$conf_file  = '<?'."php\n\n";
	$conf_file .= "// database values\n";
	$conf_file .= "\$phpwcms['db_host']           = '".$val["db_host"]."';\n";
	$conf_file .= "\$phpwcms['db_user']           = '".$val["db_user"]."';\n";
	$conf_file .= "\$phpwcms['db_pass']           = '".$val["db_pass"]."';\n";
	$conf_file .= "\$phpwcms['db_table']          = '".$val["db_table"]."';\n";
	$conf_file .= "\$phpwcms['db_prepend']        = '".$val["db_prepend"]."';\n";
	$conf_file .= "\$phpwcms['db_pers']           = ".intval($val["db_pers"]).";\n";
	$conf_file .= "\$phpwcms['db_charset']        = '".$val["db_charset"]."';\n";
	$conf_file .= "\$phpwcms['db_collation']      = '".$val["db_collation"]."';\n";
	$conf_file .= "\$phpwcms['db_version']        = ".intval($val["db_version"]).";\n";
	$conf_file .= "\$phpwcms['db_timezone']       = '".trim($val["db_timezone"])."';\n // set MySQL session time zone http://dev.mysql.com/doc/refman/5.5/en/time-zone-support.html";
	
	$conf_file .= "\n// site values\n";
	$conf_file .= "\$phpwcms['site']              = '".$val["site"]."';\n";
	$conf_file .= "\$phpwcms['admin_name']        = '".$val["admin_name"]."'; //default: Webmaster\n";
	$conf_file .= "\$phpwcms['admin_user']        = '".$val["admin_user"]."'; //default: admin\n";
	$conf_file .= "\$phpwcms['admin_pass']        = '".$val["admin_pass"]."'; //MD5(phpwcms)\n";
	$conf_file .= "\$phpwcms['admin_email']       = '".$val["admin_email"]."'; //default: noreplay@host\n";

	$conf_file .= "\n// paths\n";
	if(!$val['DOC_ROOT'] || $val['DOC_ROOT'] == $_SERVER['DOCUMENT_ROOT']) {
		$conf_file .= "\$phpwcms['DOC_ROOT']          = \$_SERVER"."['DOCUMENT_ROOT'];";
	} else {
		$conf_file .= "\$phpwcms['DOC_ROOT']          = '".$val["DOC_ROOT"]."';         //default: \$_SERVER['DOCUMENT_ROOT']";
	}
	$real_doc_root = dirname(__FILE__);
	$real_doc_root = str_replace("\\", '/', $real_doc_root);
	$real_doc_root = str_replace('/setup', '', $real_doc_root);
	$real_doc_root = str_replace($val["root"], '', $real_doc_root);
	$real_doc_root = preg_replace('/\/$/i', '', $real_doc_root);
	$conf_file .= "// real DOC_ROOT seems to be: '".$real_doc_root."' \n";
	$conf_file .= "\$phpwcms['root']         		= '".$val["root"]."';         //default: ''\n";
	$conf_file .= "\$phpwcms['file_path']         = '".$val["file_path"]."';    //default: 'filearchive'\n";
	$conf_file .= "\$phpwcms['templates']         = '".$val["templates"]."';    //default: 'template'\n";
	$conf_file .= "\$phpwcms['content_path']      = '".$val["content_path"]."'; //default: 'content'\n";
	$conf_file .= "\$phpwcms['cimage_path']       = 'images';  //default: 'images'\n";
	$conf_file .= "\$phpwcms['ftp_path']          = '".$val["ftp_path"]."';     //default: 'upload'\n";

	$conf_file .= "\n// content values\n";
	$conf_file .= "\$phpwcms['file_maxsize']      = ".intval($val["file_maxsize"])."; //Bytes (50 x 1024 x 1024)\n";
	$conf_file .= "\$phpwcms['content_width']     = ".intval($val["content_width"])."; //max width of the article content column - important for rendering multi column images\n";
	$conf_file .= "\$phpwcms['img_list_width']    = ".intval($val["img_list_width"])."; //max with of the list thumbnail image\n";
	$conf_file .= "\$phpwcms['img_list_height']   = ".intval($val["img_list_height"])."; //max height of the list thumbnail image\n";
	$conf_file .= "\$phpwcms['img_prev_width']    = ".intval($val["img_prev_width"])."; //max width of the large preview image\n";
	$conf_file .= "\$phpwcms['img_prev_height']   = ".intval($val["img_prev_height"])."; //max height of the large preview image\n";
	$conf_file .= "\$phpwcms['max_time']          = ".intval($val["max_time"])."; //logout after max_time/60 seconds\n";

	$conf_file .= "\n// other stuff\n";
	$conf_file .= "\$phpwcms['image_library']     = 'GD2';    //GD, GD2, ImageMagick, NetPBM\n";
	$conf_file .= "\$phpwcms['library_path']      = '';       //Path to ImageMagick or NetPBM\n";
	$conf_file .= "\$phpwcms['rewrite_url']       = 0; // whether URL should be rewritable\n";
	$conf_file .= "\$phpwcms['rewrite_ext']	  	  = '.html'; // The file extension used while URL is rewritten\n";
	$conf_file .= "\$phpwcms['alias_allow_slash'] = 0; // Allow slashes / in ALIAS\n";
	$conf_file .= "\$phpwcms['wysiwyg_editor']    = 1;  //0 = no wysiwyg editor, 1 = CKEditor, 2 = FCKeditor\n";
	$conf_file .= "\$phpwcms['phpmyadmin']        = 0;  //enable/disable phpMyAdmin in Backend\n";
	$conf_file .= "\$phpwcms['allowed_lang']      = array('en','de','fr','es');     //array of allowed languages: array('en', 'de', 'fr', 'es')\n";
	$conf_file .= "\$phpwcms['be_lang_parse']     = false; // to disable backend language parsing use false, otherwise 'BBCode' or 'BraceCode'\n";
	$conf_file .= "\$phpwcms['DOCTYPE_LANG']      = '';		  //by default same as \$phpwcms['default_lang'], but can be injected by whatever you like\n";
	$conf_file .= "\$phpwcms['default_lang']      = '".$val["default_lang"]."';  //default language\n";
	$conf_file .= "\$phpwcms['charset']           = '".$val["charset"]."';  //default charset 'utf-8'\n";
	$conf_file .= "\$phpwcms['allow_remote_URL']  = 1;  //0 = no remote URL in {PHP:...} replacement tag allowed, 1 = allowed\n";
	$conf_file .= "\$phpwcms['jpg_quality']       = 85; //JPG Quality Range 25-100\n";
	$conf_file .= "\$phpwcms['sharpen_level']     = 1;  //Sharpen Level - only ImageMagick: 0, 1, 2, 3, 4, 5 -- 0 = no, 5 = extra sharp\n";
	$conf_file .= "\$phpwcms['allow_ext_init']    = 1;  //allow including of custom external scripts at frontend initialization\n";
	$conf_file .= "\$phpwcms['allow_ext_render']  = 1;  //allow including of custom external scripts at frontend rendering\n";
	$conf_file .= "\$phpwcms['cache_enabled']     = 0;        //cache On/Off - 1 = caching On / 0 = caching Off (default)\n";
	$conf_file .= "\$phpwcms['cache_timeout']     = 0;  //default cache timeout setting in seconds - 0 = caching Off\n";
	$conf_file .= "\$phpwcms['imgext_disabled']   = '';  //comma seperated list of imagetypes which should not be handled 'pdf,ps'\n";
	$conf_file .= "\$phpwcms['multimedia_ext']    = 'aif,aiff,mov,movie,mp3,mpeg,mpeg4,mpeg2,wav,swf,swc,ram,ra,wma,wmv,avi,au,midi,moov,rm,rpm,mid,midi'; //comma seperated list of file extensiosn allowed for multimedia\n";
	$conf_file .= "\$phpwcms['inline_download']   = 1;  //1 = try to display download documents in new window; 0 = show safe under dialog\n";
	$conf_file .= "\$phpwcms['form_tracking']     = 1; //make a db entry for each form\n";
	$conf_file .= "\$phpwcms['formmailer_set']    = array('allow_send_copy' => 0, 'global_recipient_email' => 'mail@example.com'); //for better security handling\n";
	$conf_file .= "\$phpwcms['allow_cntPHP_rt']   = 0; //allow PHP replacement tags and includes in content parts\n";
	$conf_file .= "\$phpwcms['GETparameterName']  = 'id'; //must have a minimum of 2 chars \n";
	$conf_file .= "\$phpwcms['BOTS']              = array('googlebot', 'msnbot', 'bingbot', 'ia_archiver', 'altavista', 'slurp', 'yahoo', 'jeeves', 'teoma', 'lycos', 'crawler'); //don't start session \n";
	$conf_file .= "\$phpwcms['mode_XHTML']        = 1; // Doctype: 1 = XHTML 1.0 Transitional, 0 = HTML 4.01 Transitional, 2 = XHTML 1.0 Strict, 3 = HTML5 \n";
	$conf_file .= "\$phpwcms['header_XML']        = 0; // Content Type: 1 = application/xhtml+xml, 0 = text/html \n";
	$conf_file .= "\$phpwcms['IE7-js']        	  = 0; // load IE7-js - fix for HTML/CSS/PNG bugs in IE\n";
	$conf_file .= "\$phpwcms['php_timezone']  	  = ''; // overwrite PHP default time zone http://php.net/manual/en/timezones.php\n";
	$conf_file .= "\$phpwcms['wysiwyg_template']  = array( 'FCKeditor' => 'phpwcms_basic,phpwcms_default,Default,Basic', 'CKEditor' => 'phpwcms_basic,phpwcms_default,Default,Basic' );\n";
	$conf_file .= "\$phpwcms['GET_pageinfo']      = 0; // will add \"&pageinfo=/cat1/cat2/page-title.htm\" based on the breadcrumb information for each site link \n";
	$conf_file .= "\$phpwcms['version_check']     = 1; // checks for current release of phpwcms online \n";
	$conf_file .= "\$phpwcms['SESSION_FEinit']    = 0; // set 1 to enable sessions in frontend, 0 to disable sessions in frontend \n";
	$conf_file .= "\$phpwcms['Login_IPcheck']     = 0; \n";
	$conf_file .= "\$phpwcms['frontend_edit']	  = 0; // enable content specific direct links - linking direct into the backend \n";
	$conf_file .= "\$phpwcms['gd_memcheck_off']   = 0; // disable GD php memory check before resize an image \n";
	$conf_file .= "\$phpwcms['enable_chat']		  = 0; // enable or disable chat function, by default it is disabled - not recommend anymore to use it \n";
	$conf_file .= "\$phpwcms['enable_messages']	  = 0; // enable or disable internal messags, by default it is disabled - not recommend anymore to use it \n";
	$conf_file .= "\$phpwcms['enable_seolog']	  = 1; // enable or disable logging of search engine referrer data \n";
	$conf_file .= "\$phpwcms['i18n_parse']	  	  = 1; // enable|disable browser based language parser - all @@Text@@ will be parsed and checked for translation/var based replacement\n";
	$conf_file .= "\$phpwcms['i18n_complex']	  = 0; // enable|disable the way browser language setting should be used, false = the easier way (always 2 chars 'en'), true - 'en-gb'...\n";
	$conf_file .= "\$phpwcms['FCK_FileBrowser']   = 1; // enable|disable phpwcms Filebrowser in FCKeditor instead of built-in FCK file bowser support\n";
	$conf_file .= "\$phpwcms['JW_FLV_License']    = ''; // insert your JW FLV Media Player License Code here - License warning will no longer displayed\n";
	$conf_file .= "\$phpwcms['feuser_regkey']	  = 'FEUSER';\n";
	$conf_file .= "\$phpwcms['login.php']	  	  = 'login.php';\n";
	$conf_file .= "\$phpwcms['js_lib']			  = array('mootools-1.4'=>'MooTools 1.4','mootools-1.4-compat'=>'MooTools 1.4 Compat','mootools-1.2'=>'MooTools 1.2','mootools-1.1'=>'MooTools 1.1','jquery-1.7'=>'jQuery 1.7','jquery-1.6'=>'jQuery 1.6','jquery-1.5'=>'jQuery 1.5','jquery-1.4'=>'jQuery 1.4','jquery'=>'jQuery 1.3');\n";
	$conf_file .= "\$phpwcms['video-js']          = 'http://vjs.zencdn.net/c/'; // can be stored locally too 'template/lib/video-js/ (http://videojs.com/)\n";
	$conf_file .= "\$phpwcms['render_device']     = 0; // allow user agent specific rendering templates <!--if:mobile-->DoMobile<!--/if--><!--!if:mobile-->DoNotMobile<!--/!if--><!--!if:default-->Default<!--/!if-->\n";
	$conf_file .= "\$phpwcms['detect_pixelratio'] = 0; // will inject the page with JavaScript to detect Retina devices\n";

	$conf_file .= "\n// dynamic ssl encryption engine\n";
	$conf_file .= "\$phpwcms['site_ssl_mode']     = '0'; // tuns the SSL Support of WCMS on(1) or off (0) DEFAULT '0'\n";
	$conf_file .= "\$phpwcms['site_ssl_url']      = '';  //URL assigned to the SSL Certificate. DON'T add a slash at the End! Exp. 'https://www.yourdomainhere.tld'\n";
	$conf_file .= "\$phpwcms['site_ssl_port']     = '443'; //The Port on which you SSL Service serve the secure Sites. Servers DEFAULT is '443'\n";
	
	$conf_file .= "\n// smtp values\n";
	$conf_file .= "\$phpwcms['SMTP_FROM_EMAIL']   = '".$val["SMTP_FROM_EMAIL"]."'; // reply/from email address\n";
	$conf_file .= "\$phpwcms['SMTP_FROM_NAME']    = '".$val["SMTP_FROM_NAME"]."'; // reply/from name\n";
	$conf_file .= "\$phpwcms['SMTP_HOST']         = '".$val["SMTP_HOST"]."'; // SMTP server (host/IP)\n";
	$conf_file .= "\$phpwcms['SMTP_PORT']         = ".intval($val["SMTP_PORT"])."; // SMTP-Server port (default 25)\n";
	$conf_file .= "\$phpwcms['SMTP_MAILER']       = '".$val["SMTP_MAILER"]."'; // default phpMailer: smtp, mail (default), sendmail\n";
	$conf_file .= "\$phpwcms['SMTP_AUTH']         = ".intval($val["SMTP_AUTH"])."; // sets SMTP_AUTH to ON/OFF\n";
	$conf_file .= "\$phpwcms['SMTP_USER']         = '".$val["SMTP_USER"]."'; // default SMTP login (user) name\n";
	$conf_file .= "\$phpwcms['SMTP_PASS']         = '".$val["SMTP_PASS"]."'; // default SMTP password\n";
	
	$conf_file .= "\ndefine('PHPWCMS_INCLUDE_CHECK', true);\n";
	
	$conf_file .= "\n?>";
	
	write_textfile("setup.conf.inc.php", $conf_file);	
}

function aporeplace($string_to_convert="") {
	//Ändert die einfachen Apostrophe für SQL-Funktionen in doppelte
	$string_to_convert = str_replace("\\", "\\\\", $string_to_convert);
	$string_to_convert = str_replace("'", "''", $string_to_convert );
	return $string_to_convert;
}

function html_specialchars($h="") {
	//used to replace the htmlspecialchars original php function
	//not compatible with many internation chars like turkish, polish
	$h = preg_replace("/&(?!#[0-9]+;)/s", '&amp;', $h );
	$h = str_replace( "<", "&lt;"  , $h );
	$h = str_replace( ">", "&gt;"  , $h );
	$h = str_replace( '"', "&quot;", $h );
	$h = str_replace( "'", "&#039;", $h );
	$h = str_replace( "\\", "&#92;", $h );
	return $h;
}


// taken from http://de.php.net/manual/de/function.phpinfo.php#59573
function parsePHPModules() {
 ob_start();
 phpinfo(INFO_MODULES);
 $s = ob_get_clean(); 
 $s = strip_tags($s,'<h2><th><td>');
 $s = preg_replace('/<th[^>]*>([^<]+)<\/th>/',"<info>\\1</info>",$s);
 $s = preg_replace('/<td[^>]*>([^<]+)<\/td>/',"<info>\\1</info>",$s);
 $vTmp = preg_split('/(<h2>[^<]+<\/h2>)/',$s,-1,PREG_SPLIT_DELIM_CAPTURE);
 $vModules = array();
 for ($i=1;$i<count($vTmp);$i++) {
  if (preg_match('/<h2>([^<]+)<\/h2>/',$vTmp[$i],$vMat)) {
   $vName = trim($vMat[1]);
   $vTmp2 = explode("\n",$vTmp[$i+1]);
   foreach ($vTmp2 AS $vOne) {
   $vPat = '<info>([^<]+)<\/info>';
   $vPat3 = "/$vPat\s*$vPat\s*$vPat/";
   $vPat2 = "/$vPat\s*$vPat/";
   if (preg_match($vPat3,$vOne,$vMat)) { // 3cols
     $vModules[$vName][trim($vMat[1])] = array(trim($vMat[2]),trim($vMat[3]));
   } elseif (preg_match($vPat2,$vOne,$vMat)) { // 2cols
     $vModules[$vName][trim($vMat[1])] = trim($vMat[2]);
   }
   }
  }
 }
 return $vModules;
}


function errorWarning($warning='') {
	$t  = '<p class="error"><img src="../img/famfamfam/icon_alert.gif" alt="Alert" border="0" class="icon1" /><b>';
	$t .= $warning;
	$t .= '</b></p>';
	return $t;
}

// based on definitions of phpMyAdmin

$mysql_charset_map = array(
    'big5'         => 'big5',
    'cp-866'       => 'cp866',
    'euc-jp'       => 'ujis',
    'euc-kr'       => 'euckr',
    'gb2312'       => 'gb2312',
    'gbk'          => 'gbk',
    'iso-8859-1'   => 'latin1',
    'iso-8859-2'   => 'latin2',
    'iso-8859-7'   => 'greek',
    'iso-8859-8'   => 'hebrew',
    'iso-8859-8-i' => 'hebrew',
    'iso-8859-9'   => 'latin5',
    'iso-8859-13'  => 'latin7',
    'iso-8859-15'  => 'latin1',
    'koi8-r'       => 'koi8r',
    'shift_jis'    => 'sjis',
    'tis-620'      => 'tis620',
    'utf-8'        => 'utf8',
    'windows-1250' => 'cp1250',
    'windows-1251' => 'cp1251',
    'windows-1252' => 'latin1',
    'windows-1256' => 'cp1256',
    'windows-1257' => 'cp1257',
);

$available_languages = array(
    'af-iso-8859-1'     => array('af|afrikaans', 'afrikaans-iso-8859-1', 'af', ''),
    'af-utf-8'          => array('af|afrikaans', 'afrikaans-utf-8', 'af', ''),
    'ar-win1256'        => array('ar|arabic', 'arabic-windows-1256', 'ar', '&#1575;&#1604;&#1593;&#1585;&#1576;&#1610;&#1577;'),
    'ar-utf-8'          => array('ar|arabic', 'arabic-utf-8', 'ar', '&#1575;&#1604;&#1593;&#1585;&#1576;&#1610;&#1577;'),
    'az-iso-8859-9'     => array('az|azerbaijani', 'azerbaijani-iso-8859-9', 'az', 'Az&#601;rbaycanca'),
    'az-utf-8'          => array('az|azerbaijani', 'azerbaijani-utf-8', 'az', 'Az&#601;rbaycanca'),

    'becyr-win1251'     => array('be|belarusian', 'belarusian_cyrillic-windows-1251', 'be', '&#1041;&#1077;&#1083;&#1072;&#1088;&#1091;&#1089;&#1082;&#1072;&#1103;'),
    'becyr-utf-8'       => array('be|belarusian', 'belarusian_cyrillic-utf-8', 'be', '&#1041;&#1077;&#1083;&#1072;&#1088;&#1091;&#1089;&#1082;&#1072;&#1103;'),
    'belat-utf-8'       => array('be[-_]lat|belarusian latin', 'belarusian_latin-utf-8', 'be-lat', 'Byelorussian'),
    'bg-win1251'        => array('bg|bulgarian', 'bulgarian-windows-1251', 'bg', '&#1041;&#1098;&#1083;&#1075;&#1072;&#1088;&#1089;&#1082;&#1080;'),
    'bg-koi8-r'         => array('bg|bulgarian', 'bulgarian-koi8-r', 'bg', '&#1041;&#1098;&#1083;&#1075;&#1072;&#1088;&#1089;&#1082;&#1080;'),
    'bg-utf-8'          => array('bg|bulgarian', 'bulgarian-utf-8', 'bg', '&#1041;&#1098;&#1083;&#1075;&#1072;&#1088;&#1089;&#1082;&#1080;'),
    'bs-win1250'        => array('bs|bosnian', 'bosnian-windows-1250', 'bs', 'Bosanski'),
    'bs-utf-8'          => array('bs|bosnian', 'bosnian-utf-8', 'bs', 'Bosanski'),
    'ca-iso-8859-1'     => array('ca|catalan', 'catalan-iso-8859-1', 'ca', 'Catal&agrave;'),
    'ca-utf-8'          => array('ca|catalan', 'catalan-utf-8', 'ca', 'Catal&agrave;'),
    'cs-iso-8859-2'     => array('cs|czech', 'czech-iso-8859-2', 'cs', '&#268;esky'),
    'cs-win1250'        => array('cs|czech', 'czech-windows-1250', 'cs', '&#268;esky'),
    'cs-utf-8'          => array('cs|czech', 'czech-utf-8', 'cs', '&#268;esky'),
    'da-iso-8859-1'     => array('da|danish', 'danish-iso-8859-1', 'da', 'Dansk'),
    'da-utf-8'          => array('da|danish', 'danish-utf-8', 'da', 'Dansk'),
    'de-iso-8859-1'     => array('de|german', 'german-iso-8859-1', 'de', 'Deutsch'),
    'de-iso-8859-15'    => array('de|german', 'german-iso-8859-15', 'de', 'Deutsch'),
    'de-utf-8'          => array('de|german', 'german-utf-8', 'de', 'Deutsch'),
    'el-iso-8859-7'     => array('el|greek',  'greek-iso-8859-7', 'el', '&Epsilon;&lambda;&lambda;&eta;&nu;&iota;&kappa;&#940;'),
    'el-utf-8'          => array('el|greek',  'greek-utf-8', 'el', '&Epsilon;&lambda;&lambda;&eta;&nu;&iota;&kappa;&#940;'),
    'en-iso-8859-1'     => array('en|english',  'english-iso-8859-1', 'en', ''),
    'en-iso-8859-15'    => array('en|english',  'english-iso-8859-15', 'en', ''),
    'en-utf-8'          => array('en|english',  'english-utf-8', 'en', ''),
    'es-iso-8859-1'     => array('es|spanish', 'spanish-iso-8859-1', 'es', 'Espa&ntilde;ol'),
    'es-iso-8859-15'    => array('es|spanish', 'spanish-iso-8859-15', 'es', 'Espa&ntilde;ol'),
    'es-utf-8'          => array('es|spanish', 'spanish-utf-8', 'es', 'Espa&ntilde;ol'),
    'et-iso-8859-1'     => array('et|estonian', 'estonian-iso-8859-1', 'et', 'Eesti'),
    'et-utf-8'          => array('et|estonian', 'estonian-utf-8', 'et', 'Eesti'),
    'eu-iso-8859-1'     => array('eu|basque', 'basque-iso-8859-1', 'eu', 'Euskara'),
    'eu-utf-8'          => array('eu|basque', 'basque-utf-8', 'eu', 'Euskara'),
    'fa-win1256'        => array('fa|persian', 'persian-windows-1256', 'fa', '&#1601;&#1575;&#1585;&#1587;&#1740;'),
    'fa-utf-8'          => array('fa|persian', 'persian-utf-8', 'fa', '&#1601;&#1575;&#1585;&#1587;&#1740;'),
    'fi-iso-8859-1'     => array('fi|finnish', 'finnish-iso-8859-1', 'fi', 'Suomi'),
    'fi-iso-8859-15'    => array('fi|finnish', 'finnish-iso-8859-15', 'fi', 'Suomi'),
    'fi-utf-8'          => array('fi|finnish', 'finnish-utf-8', 'fi', 'Suomi'),
    'fr-iso-8859-1'     => array('fr|french', 'french-iso-8859-1', 'fr', 'Fran&ccedil;ais'),
    'fr-iso-8859-15'    => array('fr|french', 'french-iso-8859-15', 'fr', 'Fran&ccedil;ais'),
    'fr-utf-8'          => array('fr|french', 'french-utf-8', 'fr', 'Fran&ccedil;ais'),
    'gl-iso-8859-1'     => array('gl|galician', 'galician-iso-8859-1', 'gl', 'Galego'),
    'gl-utf-8'          => array('gl|galician', 'galician-utf-8', 'gl', 'Galego'),
    'he-iso-8859-8-i'   => array('he|hebrew', 'hebrew-iso-8859-8-i', 'he', '&#1506;&#1489;&#1512;&#1497;&#1514;'),
    'he-utf-8'          => array('he|hebrew', 'hebrew-utf-8', 'he', '&#1506;&#1489;&#1512;&#1497;&#1514;'),
    'hi-utf-8'          => array('hi|hindi', 'hindi-utf-8', 'hi', '&#2361;&#2367;&#2344;&#2381;&#2342;&#2368;'),
    'hr-win1250'        => array('hr|croatian', 'croatian-windows-1250', 'hr', 'Hrvatski'),
    'hr-iso-8859-2'     => array('hr|croatian', 'croatian-iso-8859-2', 'hr', 'Hrvatski'),
    'hr-utf-8'          => array('hr|croatian', 'croatian-utf-8', 'hr', 'Hrvatski'),
    'hu-iso-8859-2'     => array('hu|hungarian', 'hungarian-iso-8859-2', 'hu', 'Magyar'),
    'hu-utf-8'          => array('hu|hungarian', 'hungarian-utf-8', 'hu', 'Magyar'),
    'id-iso-8859-1'     => array('id|indonesian', 'indonesian-iso-8859-1', 'id', 'Bahasa Indonesia'),
    'id-utf-8'          => array('id|indonesian', 'indonesian-utf-8', 'id', 'Bahasa Indonesia'),
    'it-iso-8859-1'     => array('it|italian', 'italian-iso-8859-1', 'it', 'Italiano'),
    'it-iso-8859-15'    => array('it|italian', 'italian-iso-8859-15', 'it', 'Italiano'),
    'it-utf-8'          => array('it|italian', 'italian-utf-8', 'it', 'Italiano'),
    'ja-euc'            => array('ja|japanese', 'japanese-euc', 'ja', '&#26085;&#26412;&#35486;'),
    'ja-sjis'           => array('ja|japanese', 'japanese-sjis', 'ja', '&#26085;&#26412;&#35486;'),
    'ja-utf-8'          => array('ja|japanese', 'japanese-utf-8', 'ja', '&#26085;&#26412;&#35486;'),
    'ko-euc-kr'         => array('ko|korean', 'korean-euc-kr', 'ko', '&#54620;&#44397;&#50612;'),
    'ko-utf-8'          => array('ko|korean', 'korean-utf-8', 'ko', '&#54620;&#44397;&#50612;'),
    'ka-utf-8'          => array('ka|georgian', 'georgian-utf-8', 'ka', '&#4325;&#4304;&#4320;&#4311;&#4323;&#4314;&#4312;'),
    'lt-win1257'        => array('lt|lithuanian', 'lithuanian-windows-1257', 'lt', 'Lietuvi&#371;'),
    'lt-utf-8'          => array('lt|lithuanian', 'lithuanian-utf-8', 'lt', 'Lietuvi&#371;'),
    'lv-win1257'        => array('lv|latvian', 'latvian-windows-1257', 'lv', 'Latvie&scaron;u'),
    'lv-utf-8'          => array('lv|latvian', 'latvian-utf-8', 'lv', 'Latvie&scaron;u'),
    'mn-utf-8'          => array('mn|mongolian', 'mongolian-utf-8', 'mn', '&#1052;&#1086;&#1085;&#1075;&#1086;&#1083;'),
    'ms-iso-8859-1'     => array('ms|malay', 'malay-iso-8859-1', 'ms', 'Bahasa Melayu'),
    'ms-utf-8'          => array('ms|malay', 'malay-utf-8', 'ms', 'Bahasa Melayu'),
    'nl-iso-8859-1'     => array('nl|dutch', 'dutch-iso-8859-1', 'nl', 'Nederlands'),
    'nl-iso-8859-15'    => array('nl|dutch', 'dutch-iso-8859-15', 'nl', 'Nederlands'),
    'nl-utf-8'          => array('nl|dutch', 'dutch-utf-8', 'nl', 'Nederlands'),
    'no-iso-8859-1'     => array('no|norwegian', 'norwegian-iso-8859-1', 'no', 'Norsk'),
    'no-utf-8'          => array('no|norwegian', 'norwegian-utf-8', 'no', 'Norsk'),
    'pl-iso-8859-2'     => array('pl|polish', 'polish-iso-8859-2', 'pl', 'Polski'),
    'pl-win1250'        => array('pl|polish', 'polish-windows-1250', 'pl', 'Polski'),
    'pl-utf-8'          => array('pl|polish', 'polish-utf-8', 'pl', 'Polski'),
    'ptbr-iso-8859-1'   => array('pt[-_]br|brazilian portuguese', 'brazilian_portuguese-iso-8859-1', 'pt-BR', 'Portugu&ecirc;s'),
    'ptbr-utf-8'        => array('pt[-_]br|brazilian portuguese', 'brazilian_portuguese-utf-8', 'pt-BR', 'Portugu&ecirc;s'),
    'pt-iso-8859-1'     => array('pt|portuguese', 'portuguese-iso-8859-1', 'pt', 'Portugu&ecirc;s'),
    'pt-iso-8859-15'    => array('pt|portuguese', 'portuguese-iso-8859-15', 'pt', 'Portugu&ecirc;s'),
    'pt-utf-8'          => array('pt|portuguese', 'portuguese-utf-8', 'pt', 'Portugu&ecirc;s'),
    'ro-iso-8859-1'     => array('ro|romanian', 'romanian-iso-8859-1', 'ro', 'Rom&acirc;n&#259;'),
    'ro-utf-8'          => array('ro|romanian', 'romanian-utf-8', 'ro', 'Rom&acirc;n&#259;'),
    'ru-win1251'        => array('ru|russian', 'russian-windows-1251', 'ru', '&#1056;&#1091;&#1089;&#1089;&#1082;&#1080;&#1081;'),
    'ru-cp-866'         => array('ru|russian', 'russian-cp-866', 'ru', '&#1056;&#1091;&#1089;&#1089;&#1082;&#1080;&#1081;'),
    'ru-koi8-r'         => array('ru|russian', 'russian-koi8-r', 'ru', '&#1056;&#1091;&#1089;&#1089;&#1082;&#1080;&#1081;'),
    'ru-utf-8'          => array('ru|russian', 'russian-utf-8', 'ru', '&#1056;&#1091;&#1089;&#1089;&#1082;&#1080;&#1081;'),
    'sk-iso-8859-2'     => array('sk|slovak', 'slovak-iso-8859-2', 'sk', 'Sloven&#269;ina'),
    'sk-win1250'        => array('sk|slovak', 'slovak-windows-1250', 'sk', 'Sloven&#269;ina'),
    'sk-utf-8'          => array('sk|slovak', 'slovak-utf-8', 'sk', 'Sloven&#269;ina'),
    'sl-iso-8859-2'     => array('sl|slovenian', 'slovenian-iso-8859-2', 'sl', 'Sloven&scaron;&#269;ina'),
    'sl-win1250'        => array('sl|slovenian', 'slovenian-windows-1250', 'sl', 'Sloven&scaron;&#269;ina'),
    'sl-utf-8'          => array('sl|slovenian', 'slovenian-utf-8', 'sl', 'Sloven&scaron;&#269;ina'),
    'sq-iso-8859-1'     => array('sq|albanian', 'albanian-iso-8859-1', 'sq', 'Shqip'),
    'sq-utf-8'          => array('sq|albanian', 'albanian-utf-8', 'sq', 'Shqip'),
    'srlat-win1250'     => array('sr[-_]lat|serbian latin', 'serbian_latin-windows-1250', 'sr-lat', 'Srpski'),
    'srlat-utf-8'       => array('sr[-_]lat|serbian latin', 'serbian_latin-utf-8', 'sr-lat', 'Srpski'),
    'srcyr-win1251'     => array('sr|serbian', 'serbian_cyrillic-windows-1251', 'sr', '&#1057;&#1088;&#1087;&#1089;&#1082;&#1080;'),
    'srcyr-utf-8'       => array('sr|serbian', 'serbian_cyrillic-utf-8', 'sr', '&#1057;&#1088;&#1087;&#1089;&#1082;&#1080;'),
    'sv-iso-8859-1'     => array('sv|swedish', 'swedish-iso-8859-1', 'sv', 'Svenska'),
    'sv-utf-8'          => array('sv|swedish', 'swedish-utf-8', 'sv', 'Svenska'),
    'th-tis-620'        => array('th|thai', 'thai-tis-620', 'th', '&#3616;&#3634;&#3625;&#3634;&#3652;&#3607;&#3618;'),
    'th-utf-8'          => array('th|thai', 'thai-utf-8', 'th', '&#3616;&#3634;&#3625;&#3634;&#3652;&#3607;&#3618;'),
    'tr-iso-8859-9'     => array('tr|turkish', 'turkish-iso-8859-9', 'tr', 'T&uuml;rk&ccedil;e'),
    'tr-utf-8'          => array('tr|turkish', 'turkish-utf-8', 'tr', 'T&uuml;rk&ccedil;e'),
    'tt-iso-8859-9'     => array('tt|tatarish', 'tatarish-iso-8859-9', 'tt', 'Tatar&ccedil;a'),
    'tt-utf-8'          => array('tt|tatarish', 'tatarish-utf-8', 'tt', 'Tatar&ccedil;a'),
    'uk-win1251'        => array('uk|ukrainian', 'ukrainian-windows-1251', 'uk', '&#1059;&#1082;&#1088;&#1072;&#1111;&#1085;&#1089;&#1100;&#1082;&#1072;'),
    'uk-utf-8'          => array('uk|ukrainian', 'ukrainian-utf-8', 'uk', '&#1059;&#1082;&#1088;&#1072;&#1111;&#1085;&#1089;&#1100;&#1082;&#1072;'),
    'zhtw-big5'         => array('zhtw|chinese traditional', 'chinese_traditional-big5', 'zh-TW', '&#20013;&#25991;'),
    'zhtw-utf-8'        => array('zhtw|chinese traditional', 'chinese_traditional-utf-8', 'zh-TW', '&#20013;&#25991;'),
    'zh-gb2312'         => array('zh|chinese simplified', 'chinese_simplified-gb2312', 'zh', '&#20013;&#25991;'),
    'zh-utf-8'          => array('zh|chinese simplified', 'chinese_simplified-utf-8', 'zh', '&#20013;&#25991;'),
);

function _dbQuery($query='', $_queryMode='ASSOC') {

	if(empty($query)) return false;
	
	global $db;
	$queryResult	= array();
	$queryCount		= 0;
	
	if($result = @mysql_query($query, $db)) {
	
		switch($_queryMode) {

			// INSERT, UPDATE, DELETE
			case 'INSERT':	$queryResult['INSERT_ID']		= mysql_insert_id($db);
			case 'DELETE':	
			case 'UPDATE':	
							$queryResult['AFFECTED_ROWS']	= mysql_affected_rows($db);
							return $queryResult;
							break;

			// SELECT Queries	
			case 'ROW':		$_queryMode = 'mysql_fetch_row';	break;
			case 'ARRAY':	$_queryMode = 'mysql_fetch_array';	break;
			default: 		$_queryMode = 'mysql_fetch_assoc';
	
		}
	
		while($row = $_queryMode($result)) {
			
			$queryResult[$queryCount] = $row;
			$queryCount++;

		}
		mysql_free_result($result);
	
		return $queryResult;
	
	} else {
		return false;
	}
}

if(!function_exists('decode_entities')) {
	function decode_entities($string) {
		// replace numeric entities
		$string = preg_replace('~&#x([0-9a-f]+);~ei', 'chr(hexdec("\\1"))', $string);
		$string = preg_replace('~&#([0-9]+);~e', 'chr(\\1)', $string);
		// replace literal entities
		$trans_tbl = get_html_translation_table(HTML_ENTITIES);
		$trans_tbl = array_flip($trans_tbl);
		return strtr($string, $trans_tbl);
	}
}

?>