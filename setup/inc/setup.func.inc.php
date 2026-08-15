<?php
/**
 * phpwcms
 *
 * @author Oliver Georgi <og@phpwcms.org>
 * @copyright Copyright (c) 2002-2026, Oliver Georgi
 * @license http://opensource.org/licenses/GPL-2.0 GNU GPL-2
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
define('PHPWCMS_SETUP', true);

function read_textfile($filename) {
    if (is_file($filename)) {
        $fd = @fopen($filename, 'rb');
        $text = fread($fd, filesize($filename));
        fclose($fd);
        return $text;
    }
    return false;
}

function write_textfile($filename, $text) {
    if ($fp = @fopen($filename, 'w+b')) {
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
            $msg = '&nbsp;<b>OK</b> (exists + writable)';
            break;
        case 1:
            $msg = '&nbsp;<b>FALSE</b> (exists + not writable)';
            break;
        case 3:
            $msg = '&nbsp;<b>OK</b> (exists + not writable)';
            break;
        default:
            $msg = '&nbsp;<b>FALSE</b> (not existing)';
    }
    return $msg;
}

function slweg($string_wo_slashes_weg, $string_laenge = 0) {
    $string_wo_slashes_weg = trim($string_wo_slashes_weg);
    if ($string_laenge) {
        $string_wo_slashes_weg = substr($string_wo_slashes_weg, 0, $string_laenge);
    }
    return $string_wo_slashes_weg;
}

function clean_slweg($string_wo_slashes_weg, $string_laenge = 0) {
    $string_wo_slashes_weg = trim($string_wo_slashes_weg);
    $string_wo_slashes_weg = strip_tags($string_wo_slashes_weg);
    if ($string_laenge) {
        $string_wo_slashes_weg = substr($string_wo_slashes_weg, 0, $string_laenge);
    }
    return $string_wo_slashes_weg;
}

function escape_quote($text = '') {
    if ($text === null) {
        return '';
    }
    return str_replace(array('\\', "'"), array('\\\\', "\\'"), (string)$text);
}

function write_conf_file($val) {
    $conf_file = '<?' . "php\n\n";
    $conf_file .= "// database values\n";
    $conf_file .= "\$phpwcms['db_host'] = '" . escape_quote($val['db_host'] ?? 'localhost') . "';\n";
    $conf_file .= "\$phpwcms['db_port'] = " . (empty($val['db_port']) || !intval($val['db_port']) ? 3306 : (int)$val['db_port']) . ";\n";
    $conf_file .= "\$phpwcms['db_user'] = '" . escape_quote($val['db_user'] ?? '') . "';\n";
    $conf_file .= "\$phpwcms['db_pass'] = '" . escape_quote($val['db_pass'] ?? '') . "';\n";
    $conf_file .= "\$phpwcms['db_table'] = '" . escape_quote($val['db_table'] ?? '') . "';\n";
    $conf_file .= "\$phpwcms['db_prepend'] = '" . escape_quote($val['db_prepend'] ?? '') . "';\n";
    $conf_file .= "\$phpwcms['db_pers'] = " . intval($val['db_pers'] ?? 0) . ";\n";
    $conf_file .= "\$phpwcms['db_charset'] = '" . escape_quote($val['db_charset'] ?? 'utf8mb4') . "';\n";
    $conf_file .= "\$phpwcms['db_collation'] = '" . escape_quote($val['db_collation'] ?? 'utf8mb4_unicode_ci') . "';\n";
    $conf_file .= "\$phpwcms['db_version'] = '" . escape_quote($val['db_version'] ?? '') . "';\n";
    $conf_file .= "\$phpwcms['db_timezone'] = '" . escape_quote(trim($val['db_timezone'] ?? '')) . "'; // SET MySQL session time zone https://dev.mysql.com/doc/refman/5.7/en/time-zone-support.html\n";
    $conf_file .= "\$phpwcms['db_sql_mode'] = 'NO_ENGINE_SUBSTITUTION'; // SET MySQL session time zone https://dev.mysql.com/doc/refman/5.7/en/sql-mode.html#sql-mode-setting\n";
    $conf_file .= "\$phpwcms['db_errorlog'] = false; // Log DB queries - false|true\n";

    $conf_file .= "\n// site values\n";
    $site_url = $val['site'] ?? '';
    $check_url = rtrim($site_url, '/');
    if ($check_url === 'http://' . ($_SERVER['SERVER_NAME'] ?? '') || $check_url === 'https://' . ($_SERVER['SERVER_NAME'] ?? '')) {
        $conf_file .= "\$phpwcms['site'] = '';";
    } else {
        $conf_file .= "\$phpwcms['site'] = '" . escape_quote($site_url) . "';";
    }

    $conf_file .= " // leave empty to auto configure or try 'http://'.\$_SERVER['SERVER_NAME'].'/'\n";
    $conf_file .= "\$phpwcms['site_ssl_mode'] = 0; // turns the SSL Support of WCMS on (1) or off (0), default value 0\n";
    $conf_file .= "\$phpwcms['site_ssl_url'] = ''; // URL assigned to the SSL Certificate. Recommend 'https://'.\$_SERVER['SERVER_NAME'].'/'\n";
    $conf_file .= "\$phpwcms['site_ssl_port'] = 443; // The Port on which you SSL Service serve the secure Sites, default SSL port is 443\n\n";

    $conf_file .= "\$phpwcms['admin_name'] = '" . escape_quote($val['admin_name'] ?? 'Webmaster') . "'; //default: Webmaster\n";
    $conf_file .= "\$phpwcms['admin_user'] = '" . escape_quote($val['admin_user'] ?? 'admin') . "'; //default: admin\n";
    $conf_file .= "\$phpwcms['admin_pass'] = '" . escape_quote($val['admin_pass'] ?? '') . "'; //password_hash\n";
    $conf_file .= "\$phpwcms['admin_email'] = '" . escape_quote($val['admin_email'] ?? '') . "'; //default: noreplay@host\n";

    $conf_file .= "\n// paths\n";
    $doc_root = $val['DOC_ROOT'] ?? '';
    if (!$doc_root || $doc_root == ($_SERVER['DOCUMENT_ROOT'] ?? '')) {
        $conf_file .= "\$phpwcms['DOC_ROOT'] = \$_SERVER['DOCUMENT_ROOT'];";
    } else {
        $conf_file .= "\$phpwcms['DOC_ROOT'] = '" . escape_quote($doc_root) . "'; //default: \$_SERVER['DOCUMENT_ROOT']";
    }

    $real_doc = str_replace('\\', '/', dirname(dirname(dirname(__FILE__))));
    $root_val = $val['root'] ?? '';
    if ($root_val !== '') {
        $real_doc_parts = explode($root_val, $real_doc);
        $real_doc = rtrim($real_doc_parts[0], '/');
    }
    $conf_file .= "// current DOC_ROOT seems to be: '" . escape_quote($real_doc) . "' \n";
    $conf_file .= "\$phpwcms['root'] = '" . escape_quote($root_val) . "'; //default: ''\n";
    $conf_file .= "\$phpwcms['file_path'] = '" . escape_quote($val['file_path'] ?? 'filearchive') . "'; //default: 'filearchive'\n";
    $conf_file .= "\$phpwcms['templates'] = '" . escape_quote($val['templates'] ?? 'template') . "'; //default: 'template'\n";
    $conf_file .= "\$phpwcms['content_path'] = '" . escape_quote($val['content_path'] ?? 'content') . "'; //default: 'content'\n";
    $conf_file .= "\$phpwcms['cimage_path'] = 'images';  //default: 'images'\n";
    $conf_file .= "\$phpwcms['ftp_path'] = '" . escape_quote($val['ftp_path'] ?? 'upload') . "'; //default: 'upload'\n";
    $conf_file .= "\$phpwcms['ads_path'] = 'marketing'; // it's the former 'ads' dir in '/content'\n";

    $conf_file .= "\n// content values\n";
    $conf_file .= "\$phpwcms['file_maxsize'] = " . intval($val['file_maxsize'] ?? 52428800) . "; //Bytes (50 x 1024 x 1024)\n";
    $conf_file .= "\$phpwcms['content_width'] = " . intval($val['content_width'] ?? 538) . "; //max width of the article content column - important for rendering multi column images\n";
    $conf_file .= "\$phpwcms['img_list_width'] = " . intval($val['img_list_width'] ?? 100) . "; //max with of the list thumbnail image\n";
    $conf_file .= "\$phpwcms['img_list_height'] = " . intval($val['img_list_height'] ?? 75) . "; //max height of the list thumbnail image\n";
    $conf_file .= "\$phpwcms['img_prev_width'] = " . intval($val['img_prev_width'] ?? 538) . "; //max width of the large preview image\n";
    $conf_file .= "\$phpwcms['img_prev_height'] = " . intval($val['img_prev_height'] ?? 400) . "; //max height of the large preview image\n";
    $conf_file .= "\$phpwcms['max_time'] = " . intval($val['max_time'] ?? 1800) . "; //logout after max_time/60 seconds\n";
    $conf_file .= "\$phpwcms['responsive'] = 1; // 0 max. image width = \$phpwcms['content_width'], 1 = as given\n";
    $conf_file .= "\$phpwcms['preserve_image_name'] = 0; // keep file name for resized versions of the image\n";

    $val['rewrite_url'] = check_htaccess($val);

    $conf_file .= "\n// other stuff\n";
    $conf_file .= "\$phpwcms['image_library'] = '" . escape_quote($val['image_library'] ?? 'GD2') . "'; //GD, GD2, Imagick, ImageMagick, GraphicsMagick or GM, NetPBM\n";
    $conf_file .= "\$phpwcms['library_path'] = '" . escape_quote($val['library_path'] ?? '') . "'; //Path to ImageMagick or NetPBM\n";
    $conf_file .= "\$phpwcms['rewrite_url'] = " . ($val['rewrite_url'] ? 1 : 0) . "; // whether URL should be rewritable\n";
    $conf_file .= "\$phpwcms['rewrite_ext'] = '.html'; // The extension for URL ReWrite, '.html' -> /alias.html, '/' -> /alias/\n";
    $conf_file .= "\$phpwcms['alias_allow_slash'] = 1; // Allow slashes / in ALIAS\n";
    $conf_file .= "\$phpwcms['alias_allow_utf8'] = 1; // If charset is utf-8 special chars will survive alias checking\n";
    $conf_file .= "\$phpwcms['wysiwyg_editor'] = 2; //0 = no wysiwyg editor, 1 = CKEditor (legacy), 2 = TinyMCE 8\n";
    $conf_file .= "\$phpwcms['allowed_lang'] = array('en','de','fr','es'); //array of allowed languages: array('en', 'de', 'fr', 'es')\n";
    $conf_file .= "\$phpwcms['frontend_lang_key'] = 'phpwcms_frontend_lang'; // session and cookie key for frontend language selection\n";
    $conf_file .= "\$phpwcms['lang_parse'] = true; // enable|disable global frontend language block tag parsing [LANG:xx]...[/LANG] / [xx]...[/xx]\n";
    $conf_file .= "\$phpwcms['use_content_lang'] = false; // if true use content language based on article and/or structure level\n";
    $conf_file .= "\$phpwcms['be_lang_parse'] = false; // to disable backend language parsing use false, otherwise 'BBCode' or 'BraceCode'\n";
    $conf_file .= "\$phpwcms['DOCTYPE_LANG'] = ''; //by default same as \$phpwcms['default_lang'], but can be injected by whatever you like\n";
    $conf_file .= "\$phpwcms['default_lang'] = '" . escape_quote($val['default_lang'] ?? 'en') . "';  //default language\n";
    $conf_file .= "\$phpwcms['charset'] = '" . escape_quote($val['charset'] ?? 'utf-8') . "';  //default charset 'utf-8'\n";
    $conf_file .= "\$phpwcms['php_charset'] = false; // set PHP default charset to \$phpwcms['charset']\n";
    $conf_file .= "\$phpwcms['allow_remote_URL'] = 1;  //0 = no remote URL in {PHP:...} replacement tag allowed, 1 = allowed\n";
    $conf_file .= "\$phpwcms['jpg_quality'] = " . (isset($val['jpg_quality']) ? intval($val['jpg_quality']) : 85) . "; //JPG Quality Range 25-100\n";
    $conf_file .= "\$phpwcms['webp_enable'] = 1; // Render all images as WebP if the client browser supports it\n";
    $conf_file .= "\$phpwcms['webp_quality'] = " . (isset($val['webp_quality']) ? intval($val['webp_quality']) : 85) . "; // Set the WebP quality\n";
    $conf_file .= "\$phpwcms['resize_animated_gif']  = true; // Try to resize animated GIF, this can lead to bigger file sizes\n";
    $conf_file .= "\$phpwcms['sharpen_level'] = " . (isset($val['sharpen_level']) ? intval($val['sharpen_level']) : 1) . "; //Sharpen Level - only ImageMagick: 0, 1, 2, 3, 4, 5 -- 0 = no, 5 = extra sharp\n";
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
    $conf_file .= "\$phpwcms['enable_messages'] = 0; // enable or disable internal messags, by default it is disabled - not recommend anymore to use it \n";
    $conf_file .= "\$phpwcms['enable_seolog'] = 1; // enable or disable logging of search engine referrer data \n";
    $conf_file .= "\$phpwcms['i18n_parse'] = 1; // enable|disable browser based language parser - all @@Text@@ will be parsed and checked for translation/var based replacement\n";
    $conf_file .= "\$phpwcms['i18n_complex'] = 0; // enable|disable the way browser language setting should be used, false = the easier way (always 2 chars 'en'), true - 'en-gb'...\n";
    $conf_file .= "\$phpwcms['FCK_FileBrowser'] = 1; // enable|disable phpwcms Filebrowser in FCKeditor instead of built-in FCK file bowser support\n";
    $conf_file .= "\$phpwcms['feuser_regkey'] = 'FEUSER';\n";
    $conf_file .= "\$phpwcms['login.php'] = 'login.php';\n";
    $conf_file .= "\$phpwcms['js_lib'] = array(); // extends default lib settings array('jquery'=>'jQuery 1.3','mootools-1.4'=>'MooTools 1.4','mootools-1.1'=>'MooTools 1.1);\n";
    $conf_file .= "\$phpwcms['glightbox_options'] = array(); // GLightbox options, e.g. array('selector' => 'a[rel^=\"lightbox\"]', 'loop' => true);\n";
    $conf_file .= "\$phpwcms['video-js'] = ''; // can be stored locally too 'template/lib/video-js/ (https://vjs.zencdn.net/8.23.4/)\n";
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
    $conf_file .= "\$phpwcms['set_file_active'] = 1; // activate (1) or disable (0) files and folders by default on create\n";
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
    $conf_file .= "\$phpwcms['preserve_getVar'] = array(); // phpwcms removes some internal GET vars by default, add the ones that should be preserved\n";
    $conf_file .= "\$phpwcms['enable_GDPR'] = true; // Try to handle GDPR inside of phpwcms by default (anonymize IP...)\n";
    $conf_file .= "\$phpwcms['login_autocomplete'] = true; // If true the browser/user can decide to store login/password and/or autofill in credentials\n";
    $conf_file .= "\$phpwcms['lazy_loading'] = 'lazy'; // Set how images or iframes should be loaded: lazy (recommend), eager (right away) or auto (let browser decide).\n";
    $conf_file .= "\$phpwcms['disable_generator'] = false; // Disable <meta name=\"generator\"> and header `X-phpwcms-Release`\n";
    $conf_file .= "\$phpwcms['disable_processed_in'] = false; // Hide header `X-phpwcms-Page-Processed-In`\n";
    $conf_file .= "\$phpwcms['session.cookie_httponly.off'] = false; // Set this to `true` if the session Cookie should also be accessible by JavaScript\n";
    $conf_file .= "\$phpwcms['session.cookie_samesite'] = 'Lax'; // Define the Cookie sameSite setting None (deprecated), Lax, Strict, use PHP 7.3+ otherwise it's not or not well supported\n";
    $conf_file .= "\$phpwcms['enable_backend_newsletter'] = false; // Enable newsletter menu item in the backend, disabled by default\n";
    $conf_file .= "\$phpwcms['enable_backend_module'] = false; // Enable module menu item in the backend, disabled by default\n";
    $conf_file .= "\$phpwcms['remove_empty_get_vars'] = true; // If true all GET parameters without a value except the alias will be deleted\n";

    $conf_file .= "\n// Email specific settings (based on phpMailer)\n";
    $conf_file .= "\$phpwcms['SMTP_FROM_EMAIL'] = '" . escape_quote($val['SMTP_FROM_EMAIL'] ?? '') . "'; // reply/from email address\n";
    $conf_file .= "\$phpwcms['SMTP_FROM_NAME'] = '" . escape_quote($val['SMTP_FROM_NAME'] ?? '') . "'; // reply/from name\n";
    $conf_file .= "\$phpwcms['SMTP_HOST'] = '" . escape_quote($val['SMTP_HOST'] ?? 'localhost') . "'; // SMTP server (host/IP)\n";
    $conf_file .= "\$phpwcms['SMTP_PORT'] = " . intval($val['SMTP_PORT'] ?? 25) . "; // SMTP server port (default 25)\n";
    $conf_file .= "\$phpwcms['SMTP_MAILER'] = '" . escape_quote($val['SMTP_MAILER'] ?? 'mail') . "'; // mail method: mail (default), smtp, sendmail\n";
    $conf_file .= "\$phpwcms['SMTP_USER'] = '" . escape_quote($val['SMTP_USER'] ?? '') . "'; // default SMTP login (user) name\n";
    $conf_file .= "\$phpwcms['SMTP_PASS'] = '" . escape_quote($val['SMTP_PASS'] ?? '') . "'; // default SMTP password\n";
    $conf_file .= "\$phpwcms['SMTP_SECURE'] = '" . escape_quote($val['SMTP_SECURE'] ?? '') . "'; // secure connection, phpMailer options: '', 'ssl' or 'tls'\n";
    $conf_file .= "\$phpwcms['SMTP_AUTH'] = " . intval($val['SMTP_AUTH'] ?? 0) . "; // SMTP authentication, ON=1/OFF=0\n";
    $conf_file .= "\$phpwcms['SMTP_AUTH_TYPE'] = '" . escape_quote($val['SMTP_AUTH_TYPE'] ?? '') . "'; // sets SMTP auth type: CRAM-MD5, LOGIN, PLAIN, XOAUTH2\n";
    $conf_file .= "\$phpwcms['SMTP_XOAUTH_PROVIDER'] = '" . escape_quote($val['SMTP_XOAUTH_PROVIDER'] ?? '') . "'; // XOAUTH2 authentication provider: 'Google', 'Microsoft' or 'Azure'\n";
    $conf_file .= "\$phpwcms['SMTP_CLIENT_ID'] = '" . escape_quote($val['SMTP_CLIENT_ID'] ?? '') . "'; // The client ID for OAuth2 authentication\n";
    $conf_file .= "\$phpwcms['SMTP_CLIENT_SECRET'] = '" . escape_quote($val['SMTP_CLIENT_SECRET'] ?? '') . "'; // The client secret for OAuth2 authentication\n";
    $conf_file .= "\$phpwcms['SMTP_TENANT_ID'] = '" . escape_quote($val['SMTP_TENANT_ID'] ?? '') . "'; // The tenant ID for Microsoft OAuth2 authentication\n";
    $conf_file .= "\$phpwcms['SMTP_REFRESH_TOKEN'] = '" . escape_quote($val['SMTP_REFRESH_TOKEN'] ?? '') . "'; // The OAuth2 refresh token\n";
    $conf_file .= "\$phpwcms['SMTP_DEBUG'] = " . intval($val['SMTP_DEBUG'] ?? 0) . "; // SMTP debug level, 0 = off, 1 = client messages, 2 = client and server messages\n";

    $conf_file .= "\n// Backend Dashboard Support/Contact settings\n";
    $conf_file .= "\$phpwcms['support'] = array(\n";
    $conf_file .= "    'name'    => '', // Custom company/support name\n";
    $conf_file .= "    'address' => '', // Custom support address\n";
    $conf_file .= "    'phone'   => '', // Custom support phone number\n";
    $conf_file .= "    'email'   => ''  // Custom support email address (falls back to GitHub support page if empty)\n";
    $conf_file .= ");\n";

    $conf_file .= "\ndefine('PHPWCMS_INCLUDE_CHECK', true);\n";

    write_textfile(__DIR__ . '/../setup.conf.inc.php', $conf_file);
}

function html_specialchars($h = '') {
    //used to replace the htmlspecialchars original php function
    //not compatible with many internation chars like turkish, polish
    $h = preg_replace('/&(?!#[0-9]+;)/s', '&amp;', $h);
    $h = str_replace('<', '&lt;', $h);
    $h = str_replace('>', '&gt;', $h);
    $h = str_replace('"', '&quot;', $h);
    $h = str_replace("'", '&#039;', $h);
    $h = str_replace("\\", '&#92;', $h);
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
    $vModules = [];
    for ($i = 1, $count = count($vTmp); $i < $count; $i++) {
        if (preg_match('/<h2>([^<]+)<\/h2>/', $vTmp[$i], $vMat)) {
            $vName = trim($vMat[1]);
            $vTmp2 = explode("\n", $vTmp[$i + 1]);
            foreach ($vTmp2 AS $vOne) {
                $vPat = '<info>([^<]+)<\/info>';
                $vPat3 = "/$vPat\s*$vPat\s*$vPat/";
                $vPat2 = "/$vPat\s*$vPat/";
                if (preg_match($vPat3, $vOne, $vMat)) { // 3cols
                    $vModules[$vName][trim($vMat[1])] = [trim($vMat[2]), trim($vMat[3])];
                } elseif (preg_match($vPat2, $vOne, $vMat)) { // 2cols
                    $vModules[$vName][trim($vMat[1])] = trim($vMat[2]);
                }
            }
        }
    }
    return $vModules;
}

function errorWarning($warning = '') {
    $t = '<p class="error"><i class="fas fa-exclamation-triangle text-danger mr-2"></i><b>';
    $t .= $warning;
    $t .= '</b></p>';
    return $t;
}

// based on definitions of phpMyAdmin
$mysql_charset_map = [
    'utf-8' => 'utf8mb4'
];

$available_languages = [
    'af-utf-8' => ['af|afrikaans', 'afrikaans-utf-8', 'af', ''],
    'ar-utf-8' => ['ar|arabic', 'arabic-utf-8', 'ar', '&#1575;&#1604;&#1593;&#1585;&#1576;&#1610;&#1577;'],
    'az-utf-8' => ['az|azerbaijani', 'azerbaijani-utf-8', 'az', 'Az&#601;rbaycanca'],
    'becyr-utf-8' => ['be|belarusian', 'belarusian_cyrillic-utf-8', 'be', '&#1041;&#1077;&#1083;&#1072;&#1088;&#1091;&#1089;&#1082;&#1072;&#1103;'],
    'belat-utf-8' => ['be[-_]lat|belarusian latin', 'belarusian_latin-utf-8', 'be-lat', 'Byelorussian'],
    'bg-utf-8' => ['bg|bulgarian', 'bulgarian-utf-8', 'bg', '&#1041;&#1098;&#1083;&#1075;&#1072;&#1088;&#1089;&#1082;&#1080;'],
    'bs-utf-8' => ['bs|bosnian', 'bosnian-utf-8', 'bs', 'Bosanski'],
    'ca-utf-8' => ['ca|catalan', 'catalan-utf-8', 'ca', 'Catal&agrave;'],
    'cs-utf-8' => ['cs|czech', 'czech-utf-8', 'cs', '&#268;esky'],
    'da-utf-8' => ['da|danish', 'danish-utf-8', 'da', 'Dansk'],
    'de-utf-8' => ['de|german', 'german-utf-8', 'de', 'Deutsch'],
    'el-utf-8' => ['el|greek', 'greek-utf-8', 'el', '&Epsilon;&lambda;&lambda;&eta;&nu;&iota;&kappa;&#940;'],
    'en-utf-8' => ['en|english', 'english-utf-8', 'en', ''],
    'es-utf-8' => ['es|spanish', 'spanish-utf-8', 'es', 'Espa&ntilde;ol'],
    'et-utf-8' => ['et|estonian', 'estonian-utf-8', 'et', 'Eesti'],
    'eu-utf-8' => ['eu|basque', 'basque-utf-8', 'eu', 'Euskara'],
    'fa-utf-8' => ['fa|persian', 'persian-utf-8', 'fa', '&#1601;&#1575;&#1585;&#1587;&#1740;'],
    'fi-utf-8' => ['fi|finnish', 'finnish-utf-8', 'fi', 'Suomi'],
    'fr-utf-8' => ['fr|french', 'french-utf-8', 'fr', 'Fran&ccedil;ais'],
    'gl-utf-8' => ['gl|galician', 'galician-utf-8', 'gl', 'Galego'],
    'he-utf-8' => ['he|hebrew', 'hebrew-utf-8', 'he', '&#1506;&#1489;&#1512;&#1497;&#1514;'],
    'hi-utf-8' => ['hi|hindi', 'hindi-utf-8', 'hi', '&#2361;&#2367;&#2344;&#2381;&#2342;&#2368;'],
    'hr-utf-8' => ['hr|croatian', 'croatian-utf-8', 'hr', 'Hrvatski'],
    'hu-utf-8' => ['hu|hungarian', 'hungarian-utf-8', 'hu', 'Magyar'],
    'id-utf-8' => ['id|indonesian', 'indonesian-utf-8', 'id', 'Bahasa Indonesia'],
    'it-utf-8' => ['it|italian', 'italian-utf-8', 'it', 'Italiano'],
    'ja-utf-8' => ['ja|japanese', 'japanese-utf-8', 'ja', '&#26085;&#26412;&#35486;'],
    'ko-utf-8' => ['ko|korean', 'korean-utf-8', 'ko', '&#54620;&#44397;&#50612;'],
    'ka-utf-8' => ['ka|georgian', 'georgian-utf-8', 'ka', '&#4325;&#4304;&#4320;&#4311;&#4323;&#4314;&#4312;'],
    'lt-utf-8' => ['lt|lithuanian', 'lithuanian-utf-8', 'lt', 'Lietuvi&#371;'],
    'lv-utf-8' => ['lv|latvian', 'latvian-utf-8', 'lv', 'Latvie&scaron;u'],
    'mn-utf-8' => ['mn|mongolian', 'mongolian-utf-8', 'mn', '&#1052;&#1086;&#1085;&#1075;&#1086;&#1083;'],
    'ms-utf-8' => ['ms|malay', 'malay-utf-8', 'ms', 'Bahasa Melayu'],
    'nl-utf-8' => ['nl|dutch', 'dutch-utf-8', 'nl', 'Nederlands'],
    'no-utf-8' => ['no|norwegian', 'norwegian-utf-8', 'no', 'Norsk'],
    'pl-utf-8' => ['pl|polish', 'polish-utf-8', 'pl', 'Polski'],
    'ptbr-utf-8' => ['pt[-_]br|brazilian portuguese', 'brazilian_portuguese-utf-8', 'pt-BR', 'Portugu&ecirc;s'],
    'pt-utf-8' => ['pt|portuguese', 'portuguese-utf-8', 'pt', 'Portugu&ecirc;s'],
    'ro-utf-8' => ['ro|romanian', 'romanian-utf-8', 'ro', 'Rom&acirc;n&#259;'],
    'ru-utf-8' => ['ru|russian', 'russian-utf-8', 'ru', '&#1056;&#1091;&#1089;&#1089;&#1082;&#1080;&#1081;'],
    'sk-utf-8' => ['sk|slovak', 'slovak-utf-8', 'sk', 'Sloven&#269;ina'],
    'sl-utf-8' => ['sl|slovenian', 'slovenian-utf-8', 'sl', 'Sloven&scaron;&#269;ina'],
    'sq-utf-8' => ['sq|albanian', 'albanian-utf-8', 'sq', 'Shqip'],
    'srlat-utf-8' => ['sr[-_]lat|serbian latin', 'serbian_latin-utf-8', 'sr-lat', 'Srpski'],
    'srcyr-utf-8' => ['sr|serbian', 'serbian_cyrillic-utf-8', 'sr', '&#1057;&#1088;&#1087;&#1089;&#1082;&#1080;'],
    'sv-utf-8' => ['sv|swedish', 'swedish-utf-8', 'sv', 'Svenska'],
    'th-utf-8' => ['th|thai', 'thai-utf-8', 'th', '&#3616;&#3634;&#3625;&#3634;&#3652;&#3607;&#3618;'],
    'tr-utf-8' => ['tr|turkish', 'turkish-utf-8', 'tr', 'T&uuml;rk&ccedil;e'],
    'tt-utf-8' => ['tt|tatarish', 'tatarish-utf-8', 'tt', 'Tatar&ccedil;a'],
    'uk-utf-8' => ['uk|ukrainian', 'ukrainian-utf-8', 'uk', '&#1059;&#1082;&#1088;&#1072;&#1111;&#1085;&#1089;&#1100;&#1082;&#1072;'],
    'zhtw-utf-8' => ['zhtw|chinese traditional', 'chinese_traditional-utf-8', 'zh-TW', '&#20013;&#25991;'],
    'zh-utf-8' => ['zh|chinese simplified', 'chinese_simplified-utf-8', 'zh', '&#20013;&#25991;']
];

function _dbQuery($query = '', $_queryMode = 'ASSOC') {

    if (empty($query)) {
        return false;
    }

    global $db;
    $queryResult = [];
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

if (!function_exists('convertDecChar')) {
    function convertDecChar($decChar) {
        if ($decChar < 128) {
            return chr($decChar);
        } elseif ($decChar < 2048) {
            return chr(($decChar >> 6) + 192) . chr(($decChar & 63) + 128);
        } elseif ($decChar < 65536) {
            return chr(($decChar >> 12) + 224) . chr((($decChar >> 6) & 63) + 128) . chr(($decChar & 63) + 128);
        } elseif ($decChar < 2097152) {
            return chr($decChar >> 18 + 240) . chr((($decChar >> 12) & 63) + 128) . chr(($decChar >> 6) & 63 + 128) . chr($decChar & 63 + 128);
        }

        return $decChar;
    }

    function convertHexNumericToChar($matches) {
        return convertDecChar(hexdec($matches[1]));
    }

    function convertNumericToChar($matches) {
        return convertDecChar($matches[1]);
    }
}

if (!function_exists('decode_entities')) {
    function decode_entities($text) {
        $text = @html_entity_decode($text, ENT_QUOTES, PHPWCMS_CHARSET);
        if (!str_contains($text, '&')) {
            return $text;
        }
        $text = preg_replace_callback('/&#x([0-9a-f]+);/i', 'convertHexNumericToChar', $text);
        $text = preg_replace_callback('/&#([0-9]+);/', 'convertNumericToChar', $text);

        return $text;
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
    $host = $use_forwarded_host && isset($_SERVER['HTTP_X_FORWARDED_HOST']) ? $_SERVER['HTTP_X_FORWARDED_HOST'] : ($_SERVER['HTTP_HOST'] ?? null);
    $host = empty($host) ? $_SERVER['SERVER_NAME'] . $port : $host;

    return $protocol . $host;
}

function check_htaccess($val) {

    $val['rewrite_url'] = empty($val['rewrite_url']) ? 0 : 1;

    if ($val['rewrite_url']) {

        $root = dirname(__FILE__, 3);
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

        if ($val['root']) {
            $htaccess_new_content = str_replace('#RewriteBase /subfolder/', '#RewriteBase#/' . $val['root'] . '/', $htaccess_new_content);
            $htaccess_new_content = str_replace('RewriteBase /', '#RewriteBase /', $htaccess_new_content);
            $htaccess_new_content = str_replace('#RewriteBase#/' . $val['root'] . '/', 'RewriteBase /' . $val['root'] . '/', $htaccess_new_content);
        }

        $val['rewrite_url'] = @write_textfile($root . '/.htaccess', $htaccess_new_content) ? 1 : 0;
    }

    return $val['rewrite_url'];
}

function get_setup_steps() {
    return array(
        'license' => array('title' => '1. License', 'url' => 'index.php'),
        0         => array('title' => '2. System Check', 'url' => 'setup.php?step=0'),
        1         => array('title' => '3. Database', 'url' => 'setup.php?step=1'),
        2         => array('title' => '4. Site & Email', 'url' => 'setup.php?step=2'),
        3         => array('title' => '5. Paths', 'url' => 'setup.php?step=3'),
        4         => array('title' => '6. Media', 'url' => 'setup.php?step=4'),
        5         => array('title' => '7. Finalize', 'url' => 'setup.php?step=5'),
    );
}

function render_setup_steps($current_step = 'license') {
    $steps = get_setup_steps();
    $keys = array_keys($steps);
    $current_index = array_search($current_step, $keys, true);
    if ($current_index === false) {
        $current_index = 0;
    }

    $html = '<div class="setup-steps-wrapper mb-4">' . "\n";
    $html .= '    <nav class="setup-steps" aria-label="Setup steps">' . "\n";

    $i = 0;
    foreach ($steps as $key => $step_info) {
        $title = html_specialchars($step_info['title']);
        $url = html_specialchars($step_info['url']);

        if ($i < $current_index) {
            $class = 'setup-step completed';
            $href = ' href="' . $url . '"';
            $aria = '';
        } elseif ($i === $current_index) {
            $class = 'setup-step active';
            $href = ' href="' . $url . '"';
            $aria = ' aria-current="step"';
        } else {
            $class = 'setup-step disabled';
            $href = ' href="#" tabindex="-1" aria-disabled="true"';
            $aria = '';
        }

        $html .= '        <a class="' . $class . '"' . $href . $aria . '>' . $title . '</a>' . "\n";
        $i++;
    }

    $html .= '    </nav>' . "\n";
    $html .= '</div>';

    return $html;
}

function detect_system_image_tools() {
    $tools = array(
        'gd' => array(
            'name'      => 'GD Graphics Library',
            'type'      => 'PHP Extension',
            'installed' => false,
            'version'   => '',
            'path'      => '',
            'details'   => array(),
        ),
        'imagick' => array(
            'name'      => 'Imagick Extension',
            'type'      => 'PHP Extension (PECL)',
            'installed' => false,
            'version'   => '',
            'path'      => '',
            'details'   => array(),
        ),
        'imagemagick' => array(
            'name'      => 'ImageMagick CLI',
            'type'      => 'System Binary (convert / magick)',
            'installed' => false,
            'version'   => '',
            'path'      => '',
            'details'   => array(),
        ),
        'graphicsmagick' => array(
            'name'      => 'GraphicsMagick CLI',
            'type'      => 'System Binary (gm)',
            'installed' => false,
            'version'   => '',
            'path'      => '',
            'details'   => array(),
        ),
        'netpbm' => array(
            'name'      => 'NetPBM Tools',
            'type'      => 'System Binary (pnmscale)',
            'installed' => false,
            'version'   => '',
            'path'      => '',
            'details'   => array(),
        ),
        'ghostscript' => array(
            'name'      => 'Ghostscript',
            'type'      => 'System Binary (gs) &ndash; PDF/EPS Engine',
            'installed' => false,
            'version'   => '',
            'path'      => '',
            'details'   => array(),
        ),
    );

    // 1. GD
    if (extension_loaded('gd') && function_exists('gd_info')) {
        $gd_info = gd_info();
        $tools['gd']['installed'] = true;
        $tools['gd']['version'] = $gd_info['GD Version'] ?? 'Installed';
        $formats = array();
        if (!empty($gd_info['GIF Read Support']) || !empty($gd_info['GIF Create Support'])) {
            $formats[] = 'GIF';
        }
        if (!empty($gd_info['JPEG Support'])) {
            $formats[] = 'JPEG';
        }
        if (!empty($gd_info['PNG Support'])) {
            $formats[] = 'PNG';
        }
        if (!empty($gd_info['WebP Support'])) {
            $formats[] = 'WebP';
        }
        if (!empty($gd_info['AVIF Support'])) {
            $formats[] = 'AVIF';
        }
        if (!empty($gd_info['BMP Support'])) {
            $formats[] = 'BMP';
        }
        if (!empty($gd_info['FreeType Support'])) {
            $formats[] = 'FreeType (TTF)';
        }
        $tools['gd']['details'] = $formats;
    }

    // 2. Imagick PHP extension
    if (extension_loaded('imagick') && class_exists('Imagick')) {
        $tools['imagick']['installed'] = true;
        $tools['imagick']['version'] = phpversion('imagick') ?: 'Installed';
        try {
            $im_ver = Imagick::getVersion();
            if (!empty($im_ver['versionString'])) {
                $tools['imagick']['path'] = $im_ver['versionString'];
            }
            $sample_formats = array('JPEG', 'PNG', 'GIF', 'WEBP', 'AVIF', 'PDF', 'EPS', 'PS', 'AI', 'SVG', 'TIFF');
            $supported = array();
            foreach ($sample_formats as $fmt) {
                if (count(Imagick::queryFormats($fmt)) > 0) {
                    $supported[] = $fmt;
                }
            }
            $tools['imagick']['details'] = $supported;
        } catch (Exception $e) {
            // Ignore exception during version query
        }
    }

    // Standard CLI binary paths
    $env_path = 'PATH="/usr/local/bin:/opt/local/bin:/opt/local/lib/ImageMagick7/bin:/opt/homebrew/bin:/usr/bin:/bin:/opt/bin"';
    $search_paths = array(
        '/opt/local/lib/ImageMagick7/bin',
        '/opt/homebrew/bin',
        '/usr/local/bin',
        '/usr/bin',
        '/opt/local/bin',
        '/opt/bin',
    );

    // 3. ImageMagick CLI (convert / magick)
    if (function_exists('shell_exec') && !in_array('shell_exec', explode(',', (string)ini_get('disable_functions')), true)) {
        $im_path = @shell_exec($env_path . ' which convert 2>/dev/null || ' . $env_path . ' which magick 2>/dev/null');
        if ($im_path && ($im_path = trim(explode("\n", $im_path)[0])) && @is_executable($im_path)) {
            $tools['imagemagick']['installed'] = true;
            $tools['imagemagick']['path'] = $im_path;
            $ver_out = @shell_exec(escapeshellarg($im_path) . ' -version 2>/dev/null');
            if ($ver_out && preg_match('/Version:\s*([^\n\r]+)/i', $ver_out, $m)) {
                $tools['imagemagick']['version'] = trim($m[1]);
            }
        }
    }
    if (!$tools['imagemagick']['installed']) {
        foreach ($search_paths as $p) {
            foreach (array('convert', 'magick') as $bin) {
                $candidate = $p . '/' . $bin;
                if (@is_executable($candidate)) {
                    $tools['imagemagick']['installed'] = true;
                    $tools['imagemagick']['path'] = $candidate;
                    if (function_exists('shell_exec')) {
                        $ver_out = @shell_exec(escapeshellarg($candidate) . ' -version 2>/dev/null');
                        if ($ver_out && preg_match('/Version:\s*([^\n\r]+)/i', $ver_out, $m)) {
                            $tools['imagemagick']['version'] = trim($m[1]);
                        }
                    }
                    break 2;
                }
            }
        }
    }
    if ($tools['imagemagick']['installed'] && function_exists('shell_exec')) {
        $im_fmts = @shell_exec($env_path . ' ' . escapeshellarg($tools['imagemagick']['path']) . ' -list format 2>/dev/null');
        $cap = array();
        if ($im_fmts) {
            if (preg_match('/^\s*(JPEG|JPG)\*?\s+/mi', $im_fmts)) $cap[] = 'JPEG';
            if (preg_match('/^\s*PNG\*?\s+/mi', $im_fmts)) $cap[] = 'PNG';
            if (preg_match('/^\s*GIF\*?\s+/mi', $im_fmts)) $cap[] = 'GIF';
            if (preg_match('/^\s*WEBP\*?\s+/mi', $im_fmts)) $cap[] = 'WebP';
            if (preg_match('/^\s*AVIF\*?\s+/mi', $im_fmts)) $cap[] = 'AVIF';
            if (preg_match('/^\s*PDF\*?\s+/mi', $im_fmts)) $cap[] = 'PDF';
            if (preg_match('/^\s*EPS\*?\s+/mi', $im_fmts)) $cap[] = 'EPS';
            if (preg_match('/^\s*PS\*?\s+/mi', $im_fmts)) $cap[] = 'PS';
            if (preg_match('/^\s*SVG\*?\s+/mi', $im_fmts)) $cap[] = 'SVG';
            if (preg_match('/^\s*AI\*?\s+/mi', $im_fmts)) $cap[] = 'AI';
            if (preg_match('/^\s*TIFF\*?\s+/mi', $im_fmts)) $cap[] = 'TIFF';
        }
        $tools['imagemagick']['details'] = $cap;
    }

    // 4. GraphicsMagick CLI (gm)
    if (function_exists('shell_exec') && !in_array('shell_exec', explode(',', (string)ini_get('disable_functions')), true)) {
        $gm_path = @shell_exec($env_path . ' which gm 2>/dev/null');
        if ($gm_path && ($gm_path = trim(explode("\n", $gm_path)[0])) && @is_executable($gm_path)) {
            $tools['graphicsmagick']['installed'] = true;
            $tools['graphicsmagick']['path'] = $gm_path;
            $ver_out = @shell_exec($env_path . ' ' . escapeshellarg($gm_path) . ' -version 2>/dev/null');
            if ($ver_out && preg_match('/GraphicsMagick\s+([0-9\.]+[^\n\r]*)/i', $ver_out, $m)) {
                $tools['graphicsmagick']['version'] = 'GraphicsMagick ' . trim($m[1]);
            }
        }
    }
    if (!$tools['graphicsmagick']['installed']) {
        foreach ($search_paths as $p) {
            $candidate = $p . '/gm';
            if (@is_executable($candidate)) {
                $tools['graphicsmagick']['installed'] = true;
                $tools['graphicsmagick']['path'] = $candidate;
                if (function_exists('shell_exec')) {
                    $ver_out = @shell_exec($env_path . ' ' . escapeshellarg($candidate) . ' -version 2>/dev/null');
                    if ($ver_out && preg_match('/GraphicsMagick\s+([0-9\.]+[^\n\r]*)/i', $ver_out, $m)) {
                        $tools['graphicsmagick']['version'] = 'GraphicsMagick ' . trim($m[1]);
                    }
                }
                break;
            }
        }
    }
    if ($tools['graphicsmagick']['installed'] && function_exists('shell_exec')) {
        $gm_fmts = @shell_exec($env_path . ' ' . escapeshellarg($tools['graphicsmagick']['path']) . ' convert -list format 2>/dev/null');
        $cap = array();
        if ($gm_fmts) {
            if (preg_match('/^\s*(JPEG|JPG)\*?\s+/mi', $gm_fmts)) $cap[] = 'JPEG';
            if (preg_match('/^\s*PNG\*?\s+/mi', $gm_fmts)) $cap[] = 'PNG';
            if (preg_match('/^\s*GIF\*?\s+/mi', $gm_fmts)) $cap[] = 'GIF';
            if (preg_match('/^\s*WEBP\*?\s+/mi', $gm_fmts)) $cap[] = 'WebP';
            if (preg_match('/^\s*PDF\*?\s+/mi', $gm_fmts)) $cap[] = 'PDF';
            if (preg_match('/^\s*EPS\*?\s+/mi', $gm_fmts)) $cap[] = 'EPS';
            if (preg_match('/^\s*PS\*?\s+/mi', $gm_fmts)) $cap[] = 'PS';
            if (preg_match('/^\s*SVG\*?\s+/mi', $gm_fmts)) $cap[] = 'SVG';
            if (preg_match('/^\s*TIFF\*?\s+/mi', $gm_fmts)) $cap[] = 'TIFF';
        }
        $tools['graphicsmagick']['details'] = $cap;
    }

    // 5. NetPBM CLI (pnmscale)
    if (function_exists('shell_exec') && !in_array('shell_exec', explode(',', (string)ini_get('disable_functions')), true)) {
        $netpbm_path = @shell_exec($env_path . ' which pnmscale 2>/dev/null');
        if ($netpbm_path && ($netpbm_path = trim(explode("\n", $netpbm_path)[0])) && @is_executable($netpbm_path)) {
            $tools['netpbm']['installed'] = true;
            $tools['netpbm']['path'] = dirname($netpbm_path);
            $ver_out = @shell_exec(escapeshellarg($netpbm_path) . ' -version 2>/dev/null');
            if ($ver_out && preg_match('/Netpbm\s+([0-9\.]+[^\n\r]*)/i', $ver_out, $m)) {
                $tools['netpbm']['version'] = 'NetPBM ' . trim($m[1]);
            }
        }
    }
    if (!$tools['netpbm']['installed']) {
        foreach ($search_paths as $p) {
            $candidate = $p . '/pnmscale';
            if (@is_executable($candidate)) {
                $tools['netpbm']['installed'] = true;
                $tools['netpbm']['path'] = $p;
                break;
            }
        }
    }

    // 6. Ghostscript CLI (gs) - Used for PDF/EPS/PS conversion
    if (function_exists('shell_exec') && !in_array('shell_exec', explode(',', (string)ini_get('disable_functions')), true)) {
        $gs_path = @shell_exec($env_path . ' which gs 2>/dev/null');
        if ($gs_path && ($gs_path = trim(explode("\n", $gs_path)[0])) && @is_executable($gs_path)) {
            $tools['ghostscript']['installed'] = true;
            $tools['ghostscript']['path'] = $gs_path;
            $ver_out = @shell_exec(escapeshellarg($gs_path) . ' --version 2>/dev/null');
            if ($ver_out) {
                $tools['ghostscript']['version'] = 'Ghostscript ' . trim($ver_out);
            }
        }
    }
    if (!$tools['ghostscript']['installed']) {
        foreach ($search_paths as $p) {
            $candidate = $p . '/gs';
            if (@is_executable($candidate)) {
                $tools['ghostscript']['installed'] = true;
                $tools['ghostscript']['path'] = $candidate;
                if (function_exists('shell_exec')) {
                    $ver_out = @shell_exec(escapeshellarg($candidate) . ' --version 2>/dev/null');
                    if ($ver_out) {
                        $tools['ghostscript']['version'] = 'Ghostscript ' . trim($ver_out);
                    }
                }
                break;
            }
        }
    }

    return $tools;
}

function render_format_badges($supported_formats, $standard_formats = array()) {
    if (empty($standard_formats)) {
        $standard_formats = $supported_formats;
    }
    $html = '';
    foreach ($standard_formats as $fmt) {
        $is_supported = in_array($fmt, $supported_formats, true);
        if ($is_supported) {
            $is_doc = in_array($fmt, array('PDF', 'EPS', 'PS', 'AI', 'SVG'), true);
            $badge_class = $is_doc ? 'badge badge-info text-white' : 'badge badge-secondary text-white';
            $html .= '<span class="' . $badge_class . ' mr-1 mb-1 font-weight-normal">' . html_specialchars($fmt) . '</span> ';
        } else {
            $html .= '<span class="badge badge-light text-muted border mr-1 mb-1 font-weight-normal" style="opacity: 0.45; text-decoration: line-through;" title="' . html_specialchars($fmt) . ' is not supported">' . html_specialchars($fmt) . '</span> ';
        }
    }
    return trim($html);
}
