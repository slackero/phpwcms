<?php
/**
 * phpwcms content management system
 *
 * @author Oliver Georgi <og@phpwcms.org>
 * @copyright Copyright (c) 2002-2022, Oliver Georgi
 * @license http://opensource.org/licenses/GPL-2.0 GNU GPL-2
 * @link http://www.phpwcms.org
 *
 **/

//setup functions

$DOCROOT = rtrim(str_replace('\\', '/', dirname(dirname(dirname(__FILE__)))), '/');
include $DOCROOT . '/include/inc_lib/revision/revision.php';

if (empty($_SERVER['DOCUMENT_ROOT'])) {
    $_SERVER['DOCUMENT_ROOT'] = $DOCROOT;
}

$phpwcms_version = PHPWCMS_VERSION;
$phpwcms_release_date = PHPWCMS_RELEASE_DATE;
$phpwcms_revision = PHPWCMS_REVISION;
define('PHP7', defined('PHP_MAJOR_VERSION') && PHP_MAJOR_VERSION >= 7);
define('PHP8', defined('PHP_MAJOR_VERSION') && PHP_MAJOR_VERSION >= 8);

function read_textfile($filename) {
    if (is_file($filename)) {
        $fd = @fopen($filename, "rb");
        $text = fread($fd, filesize($filename));
        fclose($fd);
        return $text;
    }
    return false;
}

function write_textfile($filename, $text) {
    if ($fp = @fopen($filename, "w+b")) {
        fwrite($fp, $text);
        fclose($fp);
        return true;
    }
    return false;
}

function set_chmod($path, $rights, $status, $file_folder = 0) {
    $Cpath = $_SERVER['DOCUMENT_ROOT'] . $path;
    if (@file_exists($Cpath) && @chmod($Cpath, $rights)) {
        $status = $file_folder ? check_path_status($path) : check_file_status($path);
    }
    return $status;
}

function check_path_status($path) {
    $path = $_SERVER['DOCUMENT_ROOT'] . $path;
    if (is_dir($path) && is_writable($path)) {
        return 1;
    }
    return 0;
}

function check_file_status($path) {
    $path = $_SERVER['DOCUMENT_ROOT'] . $path;
    if (is_file($path) || is_dir($path)) {
        return is_writable($path) ? 2 : 1;
    }
    return 0;
}

function gib_bg_color($status) {
    if ($status === 1 || $status === 2) {
        return ' bgcolor="#99CC00"';
    }
    return ' bgcolor="#FF3300"';
}

function gib_status_text($status) {
    switch ($status) {
        case 2:
            $msg = "&nbsp;<b>OK</b> (exists + writable)";
            break;
        case 1:
            $msg = "&nbsp;<b>FALSE</b> (exists + not writable)";
            break;
        case 3:
            $msg = "&nbsp;<b>OK</b> (exists + not writable)";
            break;
        default:
            $msg = "&nbsp;<b>FALSE</b> (not existing)";
    }
    return $msg;
}

function slweg($string_wo_slashes_weg, $string_laenge = 0) {
    // Falls die Serverfunktion magic_quotes_gpc aktiviert ist, so
    // sollen die Slashes herausgenommen werden, anderenfalls nicht
    $string_wo_slashes_weg = trim($string_wo_slashes_weg);
    if (!PHP7 && get_magic_quotes_gpc()) {
        $string_wo_slashes_weg = stripslashes($string_wo_slashes_weg);
    }
    if ($string_laenge) {
        $string_wo_slashes_weg = substr($string_wo_slashes_weg, 0, $string_laenge);
    }
    return $string_wo_slashes_weg;
}

function clean_slweg($string_wo_slashes_weg, $string_laenge = 0) {
    // Falls die Serverfunktion magic_quotes_gpc aktiviert ist, so
    // sollen die Slashes herausgenommen werden, anderenfalls nicht
    $string_wo_slashes_weg = trim($string_wo_slashes_weg);
    if (!PHP7 && get_magic_quotes_gpc()) {
        $string_wo_slashes_weg = stripslashes($string_wo_slashes_weg);
    }
    $string_wo_slashes_weg = strip_tags($string_wo_slashes_weg);
    if ($string_laenge) {
        $string_wo_slashes_weg = substr($string_wo_slashes_weg, 0, $string_laenge);
    }
    return $string_wo_slashes_weg;
}

function escape_quote($text='') {
    return str_replace(array('\\', "'"), array('\\\\', "\'"), $text);
}

function write_conf_file($val) {
    $conf_file = '<?' . "php\n\n";
    $conf_file .= "// database values\n";
    $conf_file .= "\$phpwcms['db_host'] = '" . escape_quote($val["db_host"]) . "';\n";
    $conf_file .= "\$phpwcms['db_user'] = '" . escape_quote($val["db_user"]) . "';\n";
    $conf_file .= "\$phpwcms['db_pass'] = '" . escape_quote($val["db_pass"]) . "';\n";
    $conf_file .= "\$phpwcms['db_table'] = '" . escape_quote($val["db_table"]) . "';\n";
    $conf_file .= "\$phpwcms['db_prepend'] = '" . escape_quote($val["db_prepend"]) . "';\n";
    $conf_file .= "\$phpwcms['db_pers'] = " . intval($val["db_pers"]) . ";\n";
    $conf_file .= "\$phpwcms['db_charset'] = '" . escape_quote($val["db_charset"]) . "';\n";
    $conf_file .= "\$phpwcms['db_collation'] = '" . escape_quote($val["db_collation"]) . "';\n";
    $conf_file .= "\$phpwcms['db_version'] = '" . escape_quote($val["db_version"]) . "';\n";
    $conf_file .= "\$phpwcms['db_timezone'] = '" . escape_quote(trim($val["db_timezone"])) . "'; // SET MySQL session time zone https://dev.mysql.com/doc/refman/5.5/en/time-zone-support.html\n";
    $conf_file .= "\$phpwcms['db_sql_mode'] = 'NO_ENGINE_SUBSTITUTION'; // SET MySQL session time zone https://dev.mysql.com/doc/refman/5.5/en/sql-mode.html#sql-mode-setting\n";
    $conf_file .= "\$phpwcms['db_errorlog'] = false; // Log DB queries - false|true\n";

    $conf_file .= "\n// site values\n";
    $check_url = rtrim($val["site"], '/');
    if ($check_url === 'http://' . $_SERVER['SERVER_NAME'] || $check_url === 'https://' . $_SERVER['SERVER_NAME']) {
        $conf_file .= "\$phpwcms['site'] = '';";
    } else {
        $conf_file .= "\$phpwcms['site'] = '" . escape_quote($val["site"]) . "';";
    }

    $conf_file .= " // leave empty to auto configure or try 'http://'.\$_SERVER['SERVER_NAME'].'/'\n";
    $conf_file .= "\$phpwcms['site_ssl_mode'] = 0; // turns the SSL Support of WCMS on (1) or off (0), default value 0\n";
    $conf_file .= "\$phpwcms['site_ssl_url'] = ''; // URL assigned to the SSL Certificate. Recommend 'https://'.\$_SERVER['SERVER_NAME'].'/'\n";
    $conf_file .= "\$phpwcms['site_ssl_port'] = 443; // The Port on which you SSL Service serve the secure Sites, default SSL port is 443\n\n";

    $conf_file .= "\$phpwcms['admin_name'] = '" . escape_quote($val["admin_name"]) . "'; //default: Webmaster\n";
    $conf_file .= "\$phpwcms['admin_user'] = '" . escape_quote($val["admin_user"]) . "'; //default: admin\n";
    $conf_file .= "\$phpwcms['admin_pass'] = '" . escape_quote($val["admin_pass"]) . "'; //MD5(phpwcms)\n";
    $conf_file .= "\$phpwcms['admin_email'] = '" . escape_quote($val["admin_email"]) . "'; //default: noreplay@host\n";

    $conf_file .= "\n// paths\n";
    if (!$val['DOC_ROOT'] || $val['DOC_ROOT'] == $_SERVER['DOCUMENT_ROOT']) {
        $conf_file .= "\$phpwcms['DOC_ROOT'] = \$_SERVER['DOCUMENT_ROOT'];";
    } else {
        $conf_file .= "\$phpwcms['DOC_ROOT'] = '" . escape_quote($val["DOC_ROOT"]) . "'; //default: \$_SERVER['DOCUMENT_ROOT']";
    }

    $real_doc = str_replace('\\', '/', dirname(dirname(dirname(__FILE__))));
    if (isset($val["root"]) && $val["root"] !== '') {
        $real_doc = explode($val["root"], $real_doc);
        $real_doc = rtrim($real_doc[0], '/');
    }
    $conf_file .= "// current DOC_ROOT seems to be: '" . escape_quote($real_doc) . "' \n";
    $conf_file .= "\$phpwcms['root'] = '" . escape_quote($val["root"]) . "'; //default: ''\n";
    $conf_file .= "\$phpwcms['file_path'] = '" . escape_quote($val["file_path"]) . "'; //default: 'filearchive'\n";
    $conf_file .= "\$phpwcms['templates'] = '" . escape_quote($val["templates"]) . "'; //default: 'template'\n";
    $conf_file .= "\$phpwcms['content_path'] = '" . escape_quote($val["content_path"]) . "'; //default: 'content'\n";
    $conf_file .= "\$phpwcms['cimage_path'] = 'images';  //default: 'images'\n";
    $conf_file .= "\$phpwcms['ftp_path'] = '" . escape_quote($val["ftp_path"]) . "'; //default: 'upload'\n";
    $conf_file .= "\$phpwcms['ads_path'] = 'marketing'; // it's the former 'ads' dir in '/content'\n";

    $conf_file .= "\n// content values\n";
    $conf_file .= "\$phpwcms['file_maxsize'] = " . intval($val["file_maxsize"]) . "; //Bytes (50 x 1024 x 1024)\n";
    $conf_file .= "\$phpwcms['content_width'] = " . intval($val["content_width"]) . "; //max width of the article content column - important for rendering multi column images\n";
    $conf_file .= "\$phpwcms['img_list_width'] = " . intval($val["img_list_width"]) . "; //max with of the list thumbnail image\n";
    $conf_file .= "\$phpwcms['img_list_height'] = " . intval($val["img_list_height"]) . "; //max height of the list thumbnail image\n";
    $conf_file .= "\$phpwcms['img_prev_width'] = " . intval($val["img_prev_width"]) . "; //max width of the large preview image\n";
    $conf_file .= "\$phpwcms['img_prev_height'] = " . intval($val["img_prev_height"]) . "; //max height of the large preview image\n";
    $conf_file .= "\$phpwcms['max_time'] = " . intval($val["max_time"]) . "; //logout after max_time/60 seconds\n";
    $conf_file .= "\$phpwcms['responsive'] = 1; // 0 max. image width = \$phpwcms['content_width'], 1 = as given\n";
    $conf_file .= "\$phpwcms['preserve_image_name'] = 0; // keep file name for resized versions of the image\n";

    $val["rewrite_url"] = check_htaccess($val);

    $conf_file .= "\n// other stuff\n";
    $conf_file .= "\$phpwcms['image_library'] = 'GD2'; //GD, GD2, ImageMagick, GraphicsMagick or GM, NetPBM\n";
    $conf_file .= "\$phpwcms['library_path'] = ''; //Path to ImageMagick or NetPBM\n";
    $conf_file .= "\$phpwcms['rewrite_url'] = " . $val["rewrite_url"] . "; // whether URL should be rewritable\n";
    $conf_file .= "\$phpwcms['rewrite_ext'] = '.html'; // The extension for URL ReWrite, '.html' -> /alias.html, '/' -> /alias/\n";
    $conf_file .= "\$phpwcms['alias_allow_slash'] = 1; // Allow slashes / in ALIAS\n";
    $conf_file .= "\$phpwcms['alias_allow_utf8'] = 1; // If charset is utf-8 special chars will survive alias checking\n";
    $conf_file .= "\$phpwcms['wysiwyg_editor'] = 1; //0 = no wysiwyg editor, 1 = CKEditor 4\n";
    $conf_file .= "\$phpwcms['allowed_lang'] = array('en','de','fr','es'); //array of allowed languages: array('en', 'de', 'fr', 'es')\n";
    $conf_file .= "\$phpwcms['use_content_lang'] = false; // if true use content language based on article and/or structure level\n";
    $conf_file .= "\$phpwcms['be_lang_parse'] = false; // to disable backend language parsing use false, otherwise 'BBCode' or 'BraceCode'\n";
    $conf_file .= "\$phpwcms['DOCTYPE_LANG'] = ''; //by default same as \$phpwcms['default_lang'], but can be injected by whatever you like\n";
    $conf_file .= "\$phpwcms['default_lang'] = '" . escape_quote($val["default_lang"]) . "';  //default language\n";
    $conf_file .= "\$phpwcms['charset'] = '" . escape_quote($val["charset"]) . "';  //default charset 'utf-8'\n";
    $conf_file .= "\$phpwcms['php_charset'] = false; // set PHP default charset to \$phpwcms['charset']\n";
    $conf_file .= "\$phpwcms['allow_remote_URL'] = 1;  //0 = no remote URL in {PHP:...} replacement tag allowed, 1 = allowed\n";
    $conf_file .= "\$phpwcms['jpg_quality'] = 85; //JPG Quality Range 25-100\n";
    $conf_file .= "\$phpwcms['webp_enable'] = 1; // Render all images as WebP if the client browser supports it\n";
    $conf_file .= "\$phpwcms['webp_quality'] = 85; // Set the WebP quality\n";
    $conf_file .= "\$phpwcms['resize_animated_gif'] = true; // Try to resize animated GIF, this can lead to bigger file sizes\n";
    $conf_file .= "\$phpwcms['sharpen_level'] = 1; //Sharpen Level - only ImageMagick: 0, 1, 2, 3, 4, 5 -- 0 = no, 5 = extra sharp\n";
    $conf_file .= "\$phpwcms['allow_ext_init'] = 1; //allow including of custom external scripts at frontend initialization\n";
    $conf_file .= "\$phpwcms['allow_ext_render'] = 1; //allow including of custom external scripts at frontend rendering\n";
    $conf_file .= "\$phpwcms['cache_enabled'] = 0; //cache On/Off - 1 = caching On / 0 = caching Off (default)\n";
    $conf_file .= "\$phpwcms['cache_timeout'] = 0; //default cache timeout setting in seconds - 0 = caching Off\n";
    $conf_file .= "\$phpwcms['imgext_disabled'] = ''; //comma seperated list of imagetypes which should not be handled 'pdf,ps'\n";
    $conf_file .= "\$phpwcms['multimedia_ext'] = 'aif,aiff,mov,movie,mp3,mpeg,mpeg4,mpeg2,wav,swf,swc,ram,ra,wma,wmv,avi,au,midi,moov,rm,rpm,mid,midi'; //comma seperated list of file extensiosn allowed for multimedia\n";
    $conf_file .= "\$phpwcms['inline_download'] = 1; //1 = try to display download documents in new window; 0 = show safe under dialog\n";
    $conf_file .= "\$phpwcms['sanitize_dlname'] = 0; // if there are problems downloading files with special chars in name try to enable this setting\n";
    $conf_file .= "\$phpwcms['form_tracking'] = 1; //make a db entry for each form\n";
    $conf_file .= "\$phpwcms['formmailer_set'] = array('allow_send_copy' => 0, 'global_recipient_email' => 'mail@example.com'); //for better security handling\n";
    $conf_file .= "\$phpwcms['allow_cntPHP_rt'] = 0; //allow PHP replacement tags and includes in content parts\n";
    $conf_file .= "\$phpwcms['GETparameterName'] = 'id'; //must have a minimum of 2 chars \n";
    $conf_file .= "\$phpwcms['BOTS'] = array('googlebot', 'msnbot', 'bingbot', 'baiduspider', 'yandex', 'sosospider', 'ia_archiver', 'altavista', 'slurp', 'yahoo', 'jeeves', 'teoma', 'lycos', 'crawler'); //don't start session \n";
    $conf_file .= "\$phpwcms['mode_XHTML'] = 3; // Doctype: 1 = XHTML 1.0 Transitional, 0 = HTML 4.01 Transitional, 2 = XHTML 1.0 Strict, 3 = HTML5 \n";
    $conf_file .= "\$phpwcms['header_XML'] = 0; // Content Type: 1 = application/xhtml+xml, 0 = text/html \n";
    $conf_file .= "\$phpwcms['IE7-js'] = 0; // load IE7-js - fix for HTML/CSS/PNG bugs in IE\n";
    $conf_file .= "\$phpwcms['php_timezone'] = ''; // overwrite PHP default time zone http://php.net/manual/en/timezones.php\n";
    $conf_file .= "\$phpwcms['wysiwyg_template'] = array(); // deprecated\n";
    $conf_file .= "\$phpwcms['GET_pageinfo'] = 0; // will add \"&pageinfo=/cat1/cat2/page-title.htm\" based on the breadcrumb information for each site link \n";
    $conf_file .= "\$phpwcms['version_check'] = 1; // checks for current release of phpwcms online \n";
    $conf_file .= "\$phpwcms['SESSION_FEinit'] = 0; // set 1 to enable sessions in frontend, 0 to disable sessions in frontend \n";
    $conf_file .= "\$phpwcms['Login_IPcheck'] = 0; \n";
    $conf_file .= "\$phpwcms['frontend_edit'] = 0; // enable content specific direct links - linking direct into the backend \n";
    $conf_file .= "\$phpwcms['gd_memcheck_off'] = 0; // disable GD php memory check before resize an image \n";
    $conf_file .= "\$phpwcms['enable_chat'] = 0; // enable or disable chat function, by default it is disabled - not recommend anymore to use it \n";
    $conf_file .= "\$phpwcms['enable_messages'] = 0; // enable or disable internal messags, by default it is disabled - not recommend anymore to use it \n";
    $conf_file .= "\$phpwcms['enable_seolog'] = 1; // enable or disable logging of search engine referrer data \n";
    $conf_file .= "\$phpwcms['i18n_parse'] = 1; // enable|disable browser based language parser - all @@Text@@ will be parsed and checked for translation/var based replacement\n";
    $conf_file .= "\$phpwcms['i18n_complex'] = 0; // enable|disable the way browser language setting should be used, false = the easier way (always 2 chars 'en'), true - 'en-gb'...\n";
    $conf_file .= "\$phpwcms['FCK_FileBrowser'] = 1; // enable|disable phpwcms Filebrowser in FCKeditor instead of built-in FCK file bowser support\n";
    $conf_file .= "\$phpwcms['feuser_regkey'] = 'FEUSER';\n";
    $conf_file .= "\$phpwcms['login.php'] = 'login.php';\n";
    $conf_file .= "\$phpwcms['js_lib'] = array(); // extends default lib settings array('jquery'=>'jQuery 1.3','mootools-1.4'=>'MooTools 1.4','mootools-1.1'=>'MooTools 1.1);\n";
    $conf_file .= "\$phpwcms['video-js'] = ''; // can be stored locally too 'template/lib/video-js/ (https://vjs.zencdn.net/7.19.0/)\n";
    $conf_file .= "\$phpwcms['render_device'] = 0; // allow user agent specific rendering templates <!--if:mobile-->DoMobile<!--/if--><!--!if:mobile-->DoNotMobile<!--/!if--><!--!if:default-->Default<!--/!if-->\n";
    $conf_file .= "\$phpwcms['detect_pixelratio'] = 0; // will inject the page with JavaScript to detect Retina devices\n";
    $conf_file .= "\$phpwcms['im_fix_colorspace'] = 'RGB'; // newer ImageMagick installs tend to have problems with colorspace setting, if colors are look bad try SRGB\n";
    $conf_file .= "\$phpwcms['wkhtmltopdf_path'] = ''; // used for generating PDF, use full path including application name '/usr/bin/wkhtmltopdf'\n";
    $conf_file .= "\$phpwcms['render_clean_html'] = 0; // clean up HTML source a bit, experimental can have unexpected side effects\n";
    $conf_file .= "\$phpwcms['browser_check'] = array('fe' => false, 'be' => false, 'vs' => '', 'insecure' => true, 'required' => ''); // enable Browser Update check in frontend and/or backend, use 'vs' to which browser version, see http://www.browser-update.org/index.html#install\n";
    $conf_file .= "\$phpwcms['usergroup_support'] = false; // set true or false to support/disable this feature, is experimental\n";
    $conf_file .= "\$phpwcms['force301_id2alias'] = false; // send 301 HTTP Redirect when article/structure has alias but ID is given\n";
    $conf_file .= "\$phpwcms['force301_2struct'] = false; // send 301 HTTP Redirect to structure level when only 1 article is inside\n";
    $conf_file .= "\$phpwcms['allow_empty_alias'] = false; // do not auto-create (default) alias when alias field is empty\n";
    $conf_file .= "\$phpwcms['enable_deprecated'] = false; // enable/disable deprecated functionality, enable if you miss things\n";
    $conf_file .= "\$phpwcms['reserved_alias'] = array(); // use this to block custom alias\n";
    $conf_file .= "\$phpwcms['canonical_off'] = false; // disable canonical link tag\n";
    $conf_file .= "\$phpwcms['viewport'] = 'width=device-width, initial-scale=1'; // set viewport https://developer.mozilla.org/en-US/docs/Web/HTML/Viewport_meta_tag\n";
    $conf_file .= "\$phpwcms['X-UA-Compatible'] = ''; // what version of Internet Explorer the page should be rendered as, IE=edge, IE=10...\n";
    $conf_file .= "\$phpwcms['base_href'] = true; // set the <base href=\"\"> tag, use string (URL) or bool TRUE/FALSE\n";
    $conf_file .= "\$phpwcms['cp_default'] = 0; // set the default CP ID here as used in structure level editor, see http://goo.gl/BVODr\n";
    $conf_file .= "\$phpwcms['js_in_body'] = 0; // add <script /> direct before </body> instead inside of <head>\n";
    $conf_file .= "\$phpwcms['set_article_active'] = 1; // activate (1) or disable (0) article by default on create\n";
    $conf_file .= "\$phpwcms['set_category_active'] = 1; // activate (1) or disable (0) category/structure level by default on create\n";
    $conf_file .= "\$phpwcms['set_file_active'] = 1; // activate (1) sor disable (0) files and folders by default on create\n";
    $conf_file .= "\$phpwcms['set_news_active'] = 1; // activate (1) or disable (0) news by default on create\n";
    $conf_file .= "\$phpwcms['log_404error'] = false; // log each 404 for redirect edit\n";
    $conf_file .= "\$phpwcms['set_sociallink'] = array('article' => false, 'articlecat' => false, 'news' => false, 'shop' => false, 'render' => true); // TRUE/FALSE to enable status for article/articlecat/news/shop by default, render TRUE/FALSE to enable/disable in frontend\n";
    $conf_file .= "\$phpwcms['header_comment'] = '';\n";
    $conf_file .= "\$phpwcms['cnt_sort'] = 'a-z'; // not set or empty or false like before; 'a-z' or reverse 'z-a'\n";
    $conf_file .= "\$phpwcms['cmsimage_redirect'] = false; // redirect to the resized/cropped image if true\n";
    $conf_file .= "\$phpwcms['disable_next_prev'] = false; // https://support.google.com/webmasters/answer/1663744\n";
    $conf_file .= "\$phpwcms['allowed_upload_ext'] = 'jpg,jpeg,png,gif,tif,tiff,bmp,pic,psd,eps,ai,svg,pdf,ps,doc,docx,xls,xlsx,ppt,pptx,odt,odm,odg,ods,odp,odf,odc,odb,sxw,sxc,sxi,csv,txt,rtf,html,xml,ini,sql,db,zip,rar,7z,s7z,dmg,bz2,gz,tar,tgz,mkv,webm,vob,ogg,ogv,mov,qt,wmv,mpg,mpeg,mp3,mp4,m4p,flv,f4v,f4p,f4a,f4b';\n";
    $conf_file .= "\$phpwcms['enable_inline_php'] = false; // disable [PHP] {PHP…} … by default\n";
    $conf_file .= "\$phpwcms['parse_html_mode'] = 'before'; // when to parse html: [null|before, after, before+after] frontend render\n";
    $conf_file .= "\$phpwcms['trash_delete_files'] = false; // set to true if files should be deleted if trash is emptied\n";
    $conf_file .= "\$phpwcms['cmsimage_settings'] = array(); // to prevent flooding dynamic image resizing set which sizes are allowed only array('500x500x0', '1280x800x1'[, …]), first is used as fallback or 'default' or use 'default'=>'empty' to return empty gif\n";
    $conf_file .= "\$phpwcms['opengraph_imagesize'] = '1200x630x0'; // customize the open graph image size (Width x Height [x 1 = Crop], use 500x500 as minimum\n";
    $conf_file .= "\$phpwcms['unregister_getVar']   = array(); // array('myvar1', 'myvar2', …) - if there are custom GET vars that should not be registered for global use in rel_url(), abs_url()\n";
    $conf_file .= "\$phpwcms['preserve_getVar'] = array(); // phpwcms removes some internal GET vars by default, add the ones that should be preserved https://github.com/slackero/phpwcms/blob/master/include/inc_lib/default.inc.php#L520\n";
    $conf_file .= "\$phpwcms['enable_GDPR'] = true; // Try to handle GDPR inside of phpwcms by default (anonymize IP...)\n";
    $conf_file .= "\$phpwcms['login_autocomplete'] = true; // If true the browser/user can decide to store login/password and/or autofill in credentials\n";
    $conf_file .= "\$phpwcms['lazy_loading'] = 'lazy'; // Set how images or iframes should be loaded: lazy (recommend), eager (right away) or auto (let browser decide).\n";
    $conf_file .= "\$phpwcms['markdown_extra'] = false; // Enable/disable Markdown Extra https://michelf.ca/projects/php-markdown/extra/.\n";
    $conf_file .= "\$phpwcms['disable_generator'] = false; // Disable <meta name=\"generator\"> and header `X-phpwcms-Release`\n";
    $conf_file .= "\$phpwcms['disable_processed_in'] = false; // Hide header `X-phpwcms-Page-Processed-In`\n";
    $conf_file .= "\$phpwcms['session.cookie_httponly.off'] = false; // Set this to `true` if the session Cookie should also be accessible by JavaScript\n";
    $conf_file .= "\$phpwcms['session.cookie_samesite'] = 'Lax'; // Define the Cookie sameSite setting None (deprecated), Lax, Strict, use PHP 7.3+ otherwise it's not or not well supported\n";

    $conf_file .= "\n// Email specific settings (based on phpMailer)\n";
    $conf_file .= "\$phpwcms['SMTP_FROM_EMAIL'] = '" . escape_quote($val["SMTP_FROM_EMAIL"]) . "'; // reply/from email address\n";
    $conf_file .= "\$phpwcms['SMTP_FROM_NAME'] = '" . escape_quote($val["SMTP_FROM_NAME"]) . "'; // reply/from name\n";
    $conf_file .= "\$phpwcms['SMTP_HOST'] = '" . escape_quote($val["SMTP_HOST"]) . "'; // SMTP server (host/IP)\n";
    $conf_file .= "\$phpwcms['SMTP_PORT'] = " . intval($val["SMTP_PORT"]) . "; // SMTP server port (default 25)\n";
    $conf_file .= "\$phpwcms['SMTP_MAILER'] = '" . escape_quote($val["SMTP_MAILER"]) . "'; // mail method: mail (default), smtp, sendmail\n";
    $conf_file .= "\$phpwcms['SMTP_USER'] = '" . escape_quote($val["SMTP_USER"]) . "'; // default SMTP login (user) name\n";
    $conf_file .= "\$phpwcms['SMTP_PASS'] = '" . escape_quote($val["SMTP_PASS"]) . "'; // default SMTP password\n";
    $conf_file .= "\$phpwcms['SMTP_SECURE'] = '" . escape_quote($val["SMTP_SECURE"]) . "'; // secure connection, phpMailer options: '', 'ssl' or 'tls'\n";
    $conf_file .= "\$phpwcms['SMTP_AUTH'] = " . intval($val["SMTP_AUTH"]) . "; // SMTP authentication, ON=1/OFF=0\n";
    $conf_file .= "\$phpwcms['SMTP_AUTH_TYPE'] = '" . escape_quote($val["SMTP_AUTH_TYPE"]) . "'; // sets SMTP auth type: LOGIN (default), PLAIN, NTLM, CRAM-MD5\n";
    $conf_file .= "\$phpwcms['SMTP_REALM'] = '" . escape_quote($val["SMTP_REALM"]) . "'; // SMTP realm, used for NTLM auth type\n";
    $conf_file .= "\$phpwcms['SMTP_WORKSTATION'] = '" . escape_quote($val["SMTP_WORKSTATION"]) . "'; // SMTP workstation, used for NTLM auth type\n";

    $conf_file .= "\ndefine('PHPWCMS_INCLUDE_CHECK', true);\n";

    write_textfile("setup.conf.inc.php", $conf_file);
}

function html_specialchars($h = "") {
    //used to replace the htmlspecialchars original php function
    //not compatible with many internation chars like turkish, polish
    $h = preg_replace("/&(?!#[0-9]+;)/s", '&amp;', $h);
    $h = str_replace("<", "&lt;", $h);
    $h = str_replace(">", "&gt;", $h);
    $h = str_replace('"', "&quot;", $h);
    $h = str_replace("'", "&#039;", $h);
    $h = str_replace("\\", "&#92;", $h);
    return $h;
}

// taken from http://de.php.net/manual/de/function.phpinfo.php#59573
function parsePHPModules() {
    ob_start();
    phpinfo(INFO_MODULES);
    $s = strip_tags(ob_get_clean(), '<h2><th><td>');
    $s = preg_replace('/<th[^>]*>([^<]+)<\/th>/', "<info>\\1</info>", $s);
    $s = preg_replace('/<td[^>]*>([^<]+)<\/td>/', "<info>\\1</info>", $s);
    $vTmp = preg_split('/(<h2>[^<]+<\/h2>)/', $s, -1, PREG_SPLIT_DELIM_CAPTURE);
    $vModules = array();
    for ($i = 1; $i < count($vTmp); $i++) {
        if (preg_match('/<h2>([^<]+)<\/h2>/', $vTmp[$i], $vMat)) {
            $vName = trim($vMat[1]);
            $vTmp2 = explode("\n", $vTmp[$i + 1]);
            foreach ($vTmp2 AS $vOne) {
                $vPat = '<info>([^<]+)<\/info>';
                $vPat3 = "/$vPat\s*$vPat\s*$vPat/";
                $vPat2 = "/$vPat\s*$vPat/";
                if (preg_match($vPat3, $vOne, $vMat)) { // 3cols
                    $vModules[$vName][trim($vMat[1])] = array(trim($vMat[2]), trim($vMat[3]));
                } elseif (preg_match($vPat2, $vOne, $vMat)) { // 2cols
                    $vModules[$vName][trim($vMat[1])] = trim($vMat[2]);
                }
            }
        }
    }
    return $vModules;
}

function errorWarning($warning = '') {
    $t = '<p class="error"><img src="../img/famfamfam/icon_alert.gif" alt="Alert" border="0" class="icon1" /><b>';
    $t .= $warning;
    $t .= '</b></p>';
    return $t;
}

// based on definitions of phpMyAdmin
$mysql_charset_map = array(
    'utf-8' => 'utf8'
);

$available_languages = array(
    'af-utf-8' => array('af|afrikaans', 'afrikaans-utf-8', 'af', ''),
    'ar-utf-8' => array('ar|arabic', 'arabic-utf-8', 'ar', '&#1575;&#1604;&#1593;&#1585;&#1576;&#1610;&#1577;'),
    'az-utf-8' => array('az|azerbaijani', 'azerbaijani-utf-8', 'az', 'Az&#601;rbaycanca'),
    'becyr-utf-8' => array('be|belarusian', 'belarusian_cyrillic-utf-8', 'be', '&#1041;&#1077;&#1083;&#1072;&#1088;&#1091;&#1089;&#1082;&#1072;&#1103;'),
    'belat-utf-8' => array('be[-_]lat|belarusian latin', 'belarusian_latin-utf-8', 'be-lat', 'Byelorussian'),
    'bg-utf-8' => array('bg|bulgarian', 'bulgarian-utf-8', 'bg', '&#1041;&#1098;&#1083;&#1075;&#1072;&#1088;&#1089;&#1082;&#1080;'),
    'bs-utf-8' => array('bs|bosnian', 'bosnian-utf-8', 'bs', 'Bosanski'),
    'ca-utf-8' => array('ca|catalan', 'catalan-utf-8', 'ca', 'Catal&agrave;'),
    'cs-utf-8' => array('cs|czech', 'czech-utf-8', 'cs', '&#268;esky'),
    'da-utf-8' => array('da|danish', 'danish-utf-8', 'da', 'Dansk'),
    'de-utf-8' => array('de|german', 'german-utf-8', 'de', 'Deutsch'),
    'el-utf-8' => array('el|greek', 'greek-utf-8', 'el', '&Epsilon;&lambda;&lambda;&eta;&nu;&iota;&kappa;&#940;'),
    'en-utf-8' => array('en|english', 'english-utf-8', 'en', ''),
    'es-utf-8' => array('es|spanish', 'spanish-utf-8', 'es', 'Espa&ntilde;ol'),
    'et-utf-8' => array('et|estonian', 'estonian-utf-8', 'et', 'Eesti'),
    'eu-utf-8' => array('eu|basque', 'basque-utf-8', 'eu', 'Euskara'),
    'fa-utf-8' => array('fa|persian', 'persian-utf-8', 'fa', '&#1601;&#1575;&#1585;&#1587;&#1740;'),
    'fi-utf-8' => array('fi|finnish', 'finnish-utf-8', 'fi', 'Suomi'),
    'fr-utf-8' => array('fr|french', 'french-utf-8', 'fr', 'Fran&ccedil;ais'),
    'gl-utf-8' => array('gl|galician', 'galician-utf-8', 'gl', 'Galego'),
    'he-utf-8' => array('he|hebrew', 'hebrew-utf-8', 'he', '&#1506;&#1489;&#1512;&#1497;&#1514;'),
    'hi-utf-8' => array('hi|hindi', 'hindi-utf-8', 'hi', '&#2361;&#2367;&#2344;&#2381;&#2342;&#2368;'),
    'hr-utf-8' => array('hr|croatian', 'croatian-utf-8', 'hr', 'Hrvatski'),
    'hu-utf-8' => array('hu|hungarian', 'hungarian-utf-8', 'hu', 'Magyar'),
    'id-utf-8' => array('id|indonesian', 'indonesian-utf-8', 'id', 'Bahasa Indonesia'),
    'it-utf-8' => array('it|italian', 'italian-utf-8', 'it', 'Italiano'),
    'ja-utf-8' => array('ja|japanese', 'japanese-utf-8', 'ja', '&#26085;&#26412;&#35486;'),
    'ko-utf-8' => array('ko|korean', 'korean-utf-8', 'ko', '&#54620;&#44397;&#50612;'),
    'ka-utf-8' => array('ka|georgian', 'georgian-utf-8', 'ka', '&#4325;&#4304;&#4320;&#4311;&#4323;&#4314;&#4312;'),
    'lt-utf-8' => array('lt|lithuanian', 'lithuanian-utf-8', 'lt', 'Lietuvi&#371;'),
    'lv-utf-8' => array('lv|latvian', 'latvian-utf-8', 'lv', 'Latvie&scaron;u'),
    'mn-utf-8' => array('mn|mongolian', 'mongolian-utf-8', 'mn', '&#1052;&#1086;&#1085;&#1075;&#1086;&#1083;'),
    'ms-utf-8' => array('ms|malay', 'malay-utf-8', 'ms', 'Bahasa Melayu'),
    'nl-utf-8' => array('nl|dutch', 'dutch-utf-8', 'nl', 'Nederlands'),
    'no-utf-8' => array('no|norwegian', 'norwegian-utf-8', 'no', 'Norsk'),
    'pl-utf-8' => array('pl|polish', 'polish-utf-8', 'pl', 'Polski'),
    'ptbr-utf-8' => array('pt[-_]br|brazilian portuguese', 'brazilian_portuguese-utf-8', 'pt-BR', 'Portugu&ecirc;s'),
    'pt-utf-8' => array('pt|portuguese', 'portuguese-utf-8', 'pt', 'Portugu&ecirc;s'),
    'ro-utf-8' => array('ro|romanian', 'romanian-utf-8', 'ro', 'Rom&acirc;n&#259;'),
    'ru-utf-8' => array('ru|russian', 'russian-utf-8', 'ru', '&#1056;&#1091;&#1089;&#1089;&#1082;&#1080;&#1081;'),
    'sk-utf-8' => array('sk|slovak', 'slovak-utf-8', 'sk', 'Sloven&#269;ina'),
    'sl-utf-8' => array('sl|slovenian', 'slovenian-utf-8', 'sl', 'Sloven&scaron;&#269;ina'),
    'sq-utf-8' => array('sq|albanian', 'albanian-utf-8', 'sq', 'Shqip'),
    'srlat-utf-8' => array('sr[-_]lat|serbian latin', 'serbian_latin-utf-8', 'sr-lat', 'Srpski'),
    'srcyr-utf-8' => array('sr|serbian', 'serbian_cyrillic-utf-8', 'sr', '&#1057;&#1088;&#1087;&#1089;&#1082;&#1080;'),
    'sv-utf-8' => array('sv|swedish', 'swedish-utf-8', 'sv', 'Svenska'),
    'th-utf-8' => array('th|thai', 'thai-utf-8', 'th', '&#3616;&#3634;&#3625;&#3634;&#3652;&#3607;&#3618;'),
    'tr-utf-8' => array('tr|turkish', 'turkish-utf-8', 'tr', 'T&uuml;rk&ccedil;e'),
    'tt-utf-8' => array('tt|tatarish', 'tatarish-utf-8', 'tt', 'Tatar&ccedil;a'),
    'uk-utf-8' => array('uk|ukrainian', 'ukrainian-utf-8', 'uk', '&#1059;&#1082;&#1088;&#1072;&#1111;&#1085;&#1089;&#1100;&#1082;&#1072;'),
    'zhtw-utf-8' => array('zhtw|chinese traditional', 'chinese_traditional-utf-8', 'zh-TW', '&#20013;&#25991;'),
    'zh-utf-8' => array('zh|chinese simplified', 'chinese_simplified-utf-8', 'zh', '&#20013;&#25991;')
);

function _dbQuery($query = '', $_queryMode = 'ASSOC') {

    if (empty($query)) {
        return false;
    }

    global $db;
    $queryResult = array();
    $queryCount = 0;

    if ($result = mysqli_query($db, $query)) {

        switch ($_queryMode) {

            // INSERT, UPDATE, DELETE
            case 'INSERT':
                $queryResult['INSERT_ID'] = mysqli_insert_id($db);

            case 'DELETE':
            case 'UPDATE':
                $queryResult['AFFECTED_ROWS'] = mysqli_affected_rows($db);
                return $queryResult;

            // SELECT Queries
            case 'ROW':
                $_queryMode = 'mysqli_fetch_row';
                break;

            case 'ARRAY':
                $_queryMode = 'mysqli_fetch_array';
                break;

            default:
                $_queryMode = 'mysqli_fetch_assoc';
        }

        while ($row = $_queryMode($result)) {

            $queryResult[$queryCount] = $row;
            $queryCount++;
        }

        return $queryResult;
    } else {

        return false;
    }
}

if (!function_exists('decode_entities')) {
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

function get_url_origin($use_forwarded_host = false, $set_protocol = true, $enable_port = true) {
    $ssl = (!empty($_SERVER['HTTPS']) && strtolower($_SERVER['HTTPS']) !== 'off');
    $sp = strtolower($_SERVER['SERVER_PROTOCOL']);
    if ($set_protocol) {
        $protocol = substr($sp, 0, strpos($sp, '/')) . ($ssl ? 's' : '') . '://';
    } else {
        $protocol = '';
    }
    if ($enable_port) {
        $port = intval($_SERVER['SERVER_PORT']);
        $port = (!$ssl && $port === 80) || ($ssl && $port === 443) ? '' : ':' . $port;
    } else {
        $port = '';
    }
    $host = $use_forwarded_host && isset($_SERVER['HTTP_X_FORWARDED_HOST']) ? $_SERVER['HTTP_X_FORWARDED_HOST'] : (isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : null);
    $host = empty($host) ? $_SERVER['SERVER_NAME'] . $port : $host;

    return $protocol . $host;
}

function check_htaccess($val) {

    $val["rewrite_url"] = empty($val["rewrite_url"]) ? 0 : 1;

    if ($val["rewrite_url"]) {

        $root = dirname(dirname(dirname(__FILE__)));
        $htaccess_content = '';
        $htaccess_new_content = '';

        if (is_file($root . '/.htaccess')) {
            $htaccess_content = read_textfile($root . '/.htaccess');
        }

        // Test if RewriteEngine is On or disable rewrite of phpwcms
        if ($htaccess_content) {
            return (strpos(strtolower($htaccess_content), 'rewriteengine on')) !== false ? 1 : 0;
        }

        if (is_file($root . '/_.htaccess')) {
            $htaccess_new_content = read_textfile($root . '/_.htaccess');
        }

        // Disable rewrite during setup if the _.htaccess is empty
        if (!$htaccess_new_content) {
            return 0;
        }

        if ($val["root"]) {
            $htaccess_new_content = str_replace('#RewriteBase /subfolder/', '#RewriteBase#/' . $val["root"] . '/', $htaccess_new_content);
            $htaccess_new_content = str_replace('RewriteBase /', '#RewriteBase /', $htaccess_new_content);
            $htaccess_new_content = str_replace('#RewriteBase#/' . $val["root"] . '/', 'RewriteBase /' . $val["root"] . '/', $htaccess_new_content);
        }

        $val["rewrite_url"] = @write_textfile($root . '/.htaccess', $htaccess_new_content) ? 1 : 0;
    }

    return $val["rewrite_url"];
}
