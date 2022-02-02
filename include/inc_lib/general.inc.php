<?php
/**
 * phpwcms content management system
 *
 * @author Oliver Georgi <og@phpwcms.org>
 * @copyright Copyright (c) 2002-2022, Oliver Georgi
 * @license http://opensource.org/licenses/GPL-2.0 GNU GPL-2
 * @link http://www.phpwcms.org
 **/

// ----------------------------------------------------------------
// obligate check for phpwcms constants
if (!defined('PHPWCMS_INCLUDE_CHECK')) {
    die("You Cannot Access This Script Directly, Have a Nice Day.");
}
// ----------------------------------------------------------------
if (PHPWCMS_CHARSET === 'utf-8') {
    require_once PHPWCMS_ROOT . '/include/inc_lib/lib.php_special_entities.utf-8.php';
} else {
    require_once PHPWCMS_ROOT . '/include/inc_lib/lib.php_special_entities.php';
}
require_once PHPWCMS_ROOT . '/include/inc_lib/charset_helper.inc.php';
require_once PHPWCMS_ROOT . '/include/inc_ext/htmlfilter.php';
require_once PHPWCMS_ROOT . '/include/inc_lib/helper.inc.php';
require_once PHPWCMS_ROOT . '/include/inc_ext/rfc822.php';
function str_empty($string) {
    return $string === null || $string === '';
}

function slweg($text = '', $maxlen = 0, $trim = true) {
    if (!IS_PHP7 && get_magic_quotes_gpc()) {
        $text = stripslashes($text);
    }
    if ($text && substr($text, -1) === '>') {
        $text = preg_replace(array('/<br>$/i', '/<br \/>$/i', '/<p><\/p>$/i', '/<p>&nbsp;<\/p>$/i'), '', rtrim($text));
    }
    if ($trim) {
        $text = trim($text);
    }
    if ($maxlen && strlen($text) > $maxlen) {
        $text = mb_substr($text, 0, $maxlen);
    }

    return $text;
}

function clean_slweg($text, $maxlen = 0, $trim = true) {
    if (!IS_PHP7 && get_magic_quotes_gpc()) {
        $text = stripslashes($text);
    }
    $text = strip_tags($text);
    if ($trim) {
        $text = trim($text);
    }
    if ($maxlen && strlen($text) > $maxlen) {
        $text = mb_substr($text, 0, $maxlen);
    }

    return $text;
}

function getpostvar($formvar, $string_laenge = 0) {
    //combines trim, stripslashes und apostrophe replace
    return _dbEscape(slweg($formvar, $string_laenge), false);
}

function html_despecialchars($h = '') {
    //call off html_specialchars
    $h = str_replace('&amp;', '&', $h);
    $h = str_replace('&lt;', '<', $h);
    $h = str_replace('&gt;', '>', $h);
    $h = str_replace('&quot;', '"', $h);
    $h = str_replace('&#039;', "'", $h);
    $h = str_replace('&#92;', "\\", $h);

    return $h;
}

function list_country($c, $lang = '') {
    //Create the country list menu for forms with the given value selected
    //$c = selected value
    if (empty($c)) {
        $c = strtoupper($GLOBALS['phpwcms']['default_lang']);
    }
    $country_list = '';
    $country = getCountry($lang);
    foreach ($country as $key => $value) {
        $country_list .= '  <option value="' . html($key) . '"';
        if ($key == $c) {
            $country_list .= ' selected="selected"';
        }
        $country_list .= '>' . html($value) . '</option>' . LF;
    }

    return $country_list;
}

function getCountry($lang = '', $get = 'COUNTRY_ARRAY') {
    global $phpwcms;
    if (empty($lang)) {
        $lang = isset($_SESSION["wcs_user_lang"]) ? strtolower($_SESSION["wcs_user_lang"]) : $GLOBALS['phpwcms']['default_lang'];
    }
    $lang = strtolower(substr($lang, 0, 2));
    $country_lang_var = $get . '_' . $lang;
    if (!empty($phpwcms['country'][$country_lang_var])) {
        return $phpwcms['country'][$country_lang_var];
    }
    $country_name = 'country_name_' . _dbEscape($lang, false);
    $sql = 'SHOW COLUMNS FROM ' . DB_PREPEND . "phpwcms_country WHERE Field='" . $country_name . "'";
    $result = _dbQuery($sql);
    if (!isset($result[0])) {
        $country_name = 'country_name';
    }
    if ($get == 'COUNTRY_NAME') {
        $phpwcms['country'][$country_lang_var] = strtoupper($lang);
        $sql = 'SELECT ' . $country_name . ' AS country FROM ' . DB_PREPEND . "phpwcms_country WHERE ";
        $sql .= "country_iso=" . _dbEscape($phpwcms['country'][$country_lang_var]) . " LIMIT 1";
        $result = _dbQuery($sql);
        if (isset($result[0]['country'])) {
            $phpwcms['country'][$country_lang_var] = $result[0]['country'];
        }
    } else {
        $country_lang_var = 'COUNTRY_ARRAY_' . $lang;
        $phpwcms['country'][$country_lang_var] = array();
        $sql = 'SELECT country_iso, ' . $country_name . ' AS country FROM ' . DB_PREPEND . 'phpwcms_country ORDER BY ' . $country_name;
        $result = _dbQuery($sql);
        if (isset($result[0])) {
            foreach ($result as $row) {
                $phpwcms['country'][$country_lang_var][$row['country_iso']] = $row['country'];
            }
        }
    }

    return $phpwcms['country'][$country_lang_var];
}

function list_profession($c) {
    //Create the profession list menu for forms with the given value selected
    if (empty($c)) {
        $c = $GLOBALS['BL']['be_n/a'];
    }
    $sql = "SELECT prof_name FROM " . DB_PREPEND . "phpwcms_profession ORDER BY prof_name";
    if ($result = _dbQuery($sql)) {
        foreach ($result as $a) {
            echo '<option value="' . html($a["prof_name"]) . '"';
            if ($a["prof_name"] == $c) {
                echo ' selected="selected"';
            }
            echo '>' . html($a["prof_name"]) . "</option>";
        }
    }
}

function is_selected($c, $chkvalue, $xhtml = 1, $echoit = 1) {
    $e = strval($c) == strval($chkvalue) ? ' selected="selected"' : '';
    if ($echoit) {
        echo $e;
    }

    return $e;
}

function is_checked($c, $chkvalue, $xhtml = 1, $echoit = 1) {
    $e = strval($c) == strval($chkvalue) ? ' checked="checked"' : '';
    if ($echoit) {
        echo $e;
    }

    return $e;
}

function check_checkbox($c) {
    //Prüft, ob korrekte Werte via Checkbox übergeben wurden
    $c = intval($c);
    if ($c !== 0 && $c !== 1) {
        $c = 0;
    }

    return $c;
}

function which_ext($filename) {
    // return file extension
    return strtolower(str_replace('.', '', strrchr(trim($filename), '.')));
}

function cut_ext($dateiname) {
    //cuts extension of file
    $cutoff = strrpos($dateiname, '.');

    return ($cutoff !== false) ? mb_substr($dateiname, 0, $cutoff) : $dateiname;
}

function fsize($zahl, $spacer = '&nbsp;', $short = 1) {
    //Creates Filesize-Info
    //number_format($_FILES["wcsfile"]["size"] / 1024, 0, ',', '.')." kB)
    //$short 0 = ultrashort = B, K, M, G, T
    //$short 1 = short = B, KB, MB, GB, TB
    //$short 2 = long = Byte, KiloByte, MegaByte, GigaByte, TeraByte
    $_unit = array(
        0 => array(
            "B" => "B",
            "K" => "K",
            "M" => "M",
            "G" => "G",
            "T" => "T",
        ),
        1 => array(
            "B" => "Byte",
            "K" => "KB",
            "M" => "MB",
            "G" => "GB",
            "T" => "TB",
        ),
        2 => array(
            "B" => "Byte",
            "K" => "KiloByte",
            "M" => "MegaByte",
            "G" => "GigaByte",
            "T" => "TeraByte",
        ),
    );
    $zahl = intval($zahl);
    if ($zahl < 1024) {
        $zahl = number_format($zahl, 0, '.', '.');
        $unit = "B";
    } elseif ($zahl < 1048576) {
        $zahl = number_format($zahl / 1024, 2, '.', '.');
        $unit = "K";
    } elseif ($zahl < 1073741824) {
        $zahl = number_format($zahl / 1048576, 2, '.', '.');
        $unit = "M";
    } elseif ($zahl < 1099511627776) {
        $zahl = number_format($zahl / 1073741824, 2, '.', '.');
        $unit = "G";
    } else {
        $zahl = number_format($zahl / 1125899906842624, 2, ' ', '.');
        $unit = "T";
    }

    return $zahl . $spacer . $_unit[$short][$unit];
}

function fsizelong($zahl, $spacer = '&nbsp;') {
    return fsize($zahl, $spacer, 1);
}

function extimg($ext) {
    //get extension image
    $img = array(
        "exe" => "icon_exe.gif",
        "com" => "icon_exe.gif",
        "bat" => "icon_exe.gif",
        "pdf" => "icon_pdf.gif",
        "txt" => "icon_txt.gif",
        "xls" => "icon_xls.gif",
        "cvs" => "icon_xls.gif",
        "rtf" => "icon_txt.gif",
        "htm" => "icon_htm.gif",
        "html" => "icon_htm.gif",
        "pix" => "icon_pix.gif",
        "tif" => "icon_pix.gif",
        "jpg" => "icon_pix.gif",
        "jpeg" => "icon_pix.gif",
        "gif" => "icon_pix.gif",
        "png" => "icon_pix.gif",
        "psd" => "icon_pix.gif",
        "rar" => "icon_rar.gif",
        "zip" => "icon_zip.gif",
        "tar" => "icon_zip.gif",
        "gzip" => "icon_zip.gif",
        "sit" => "icon_sit.gif",
        "sea" => "icon_sit.gif",
        "doc" => "icon_doc.gif",
        "dot" => "icon_doc.gif",
        "ai" => "icon_ai.gif",
        "ps" => "icon_ps.gif",
        "eps" => "icon_eps.gif",
        "gz" => "icon_gz.gif",
        "tgz" => "icon_gz.gif",
        "aif" => "icon_snd.gif",
        "aiff" => "icon_snd.gif",
        "mp3" => "icon_snd.gif",
        "snd" => "icon_snd.gif",
        "wav" => "icon_snd.gif",
        "mid" => "icon_snd.gif",
        "mov" => "icon_vid.gif",
        "avi" => "icon_vid.gif",
        "qt" => "icon_vid.gif",
        "mpeg" => "icon_vid.gif",
    );

    return (isset($img[$ext])) ? $img[$ext] : "icon_generic.gif";
}

function generic_string($length, $i = 0) {
    $gen_string = '';
    $p[0] = "abcdefghijklmnopqrstuvwxyz";
    $p[1] = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
    $p[2] = "1234567890";
    switch ($i) {
        case 1:
            $chars = $p[0] . $p[2];
            break;
        case 2:
            $chars = $p[1] . $p[2];
            break;
        case 3:
            $chars = $p[0] . $p[1];
            break;
        case 4:
            $chars = $p[0];
            break;
        case 5:
            $chars = $p[1];
            break;
        case 6:
            $chars = $p[2];
            break;
        default:
            $chars = $p[0] . $p[2] . $p[1];
    }
    mt_srand((double)microtime() * 1000000);
    $count = strlen($chars) - 1;
    for ($i = 0; $i < $length; $i++) {
        $gen_string .= substr($chars, mt_rand(0, $count), 1);
    }

    return $gen_string;
}

function genlogname() {
    $usercount = _dbQuery('SELECT COUNT(*) FROM ' . DB_PREPEND . "phpwcms_user WHERE usr_login LIKE 'user%'", 'COUNT');
    $usercount = $usercount ? $usercount + 1 : 1;

    return 'user' . $usercount;
}

function gib_part($value, $part, $separator) {
    //Gibt den Wert an Stelle $part von $value zurück
    $value_array = explode($separator, $value);

    return $value_array[$part];
}

function cut_string($string, $endchar = '&#8230;', $length = 20, $trim = 1) {
    // alias function for older function
    return getCleanSubString($string, $length, $endchar);
}

function which_folder_active($ist, $soll, $ac = "#9BBECA", $nc = "#363E57", $nclass = "msgreiter") {
    if ($ist == $soll) {
        echo "bgcolor='" . $ac . "' class='" . $nclass . "'";
    } else {
        echo "bgcolor='" . $nc . "' class='" . $nclass . "' ";
        echo "onMouseOver=\"bgColor='#FF6600'\" onMouseOut=\"bgColor='" . $nc . "'\"";
    }
}

function FileExtension($filename) {
    return mb_substr(strrchr($filename, "."), 1);
}

function convert_into($extension) {
    if (PHPWCMS_WEBP) {
        return 'webp';
    }
    //check which extension to give back
    $extension = strtolower($extension);
    $ext = 'jpg';
    if (IMAGICK_ON) {
        switch ($extension) {
            case 'gif':
                $ext = 'gif';
                break;
            case 'png':
                $ext = 'png';
                break;
        }
    } else {
        switch ($extension) {
            case 'gif':
                $ext = (imagetypes() & IMG_GIF) ? 'gif' : 'png';
                break;
            case 'png':
                $ext = 'png';
                break;
        }
    }

    return $ext;
}

function is_ext_true($extension) {
    global $phpwcms;
    $ext = false;
    if ($phpwcms['image_library'] === 'gd2' || $phpwcms['image_library'] === 'gd') {
        // if GD is used
        switch ($extension) {
            case 'jpg':
            case 'jpeg':
                $ext = 'jpg';
                break;
            case 'gif':
                $ext = (imagetypes() && IMG_GIF) ? 'gif' : 'png';
                break;
            case 'png':
                $ext = 'png';
                break;
            case 'webp':
                $ext = PHPWCMS_WEBP ? 'webp' : 'jpg';
                break;
        }
    } else {
        // if ImageMagick for thumbnail creation
        switch ($extension) {
            case "jpg":
            case "jpeg":
            case "tif":
            case "tiff":
            case "psd":
            case "bmp":
            case "pic":
            case "ps2":
            case "ps3":
            case "pn":
            case "wmf":
            case "tga":
            case "pict":
            case "jp2":
            case "jpc":
            case "ico":
            case "fax":
                $ext = "jpg";
                break;
            case "eps":
            case "ps":
            case "ai":
            case "png":
            case "pdf":
                $ext = "png";
                break;
            case "gif":
                $ext = "gif";
                break;
            case 'webp':
                $ext = PHPWCMS_WEBP ? 'webp' : 'jpg';
                break;
        }
    }
    if ($ext && !empty($GLOBALS['phpwcms']["imgext_disabled"])) {
        if (is_string($GLOBALS['phpwcms']["imgext_disabled"])) {
            $GLOBALS['phpwcms']["imgext_disabled"] = convertStringToArray(strtolower($GLOBALS['phpwcms']["imgext_disabled"]));
        }
        if (in_array($ext, $GLOBALS['phpwcms']["imgext_disabled"])) {
            $ext = false;
        }
    }

    return $ext;
}

function make_date($datestring, $dateformat = "d.m.y") {
    return phpwcms_strtotime($datestring, $dateformat, '');
}

function switch_on_off($wert) {
    //switches the value off->on and on->off
    return intval($wert) ? 0 : 1;
}

function online_users($spacer = '<br />', $wrap = '<span class="useronline">|<span>') {
    $wrap = explode('|', $wrap);
    $users = array();
    if ($result = _dbQuery("SELECT logged_user FROM " . DB_PREPEND . "phpwcms_userlog WHERE logged_in=1")) {
        foreach ($result as $user) {
            $users[] = html($user['logged_user']);
        }
    }
    if ($users) {
        return $wrap[0] . implode($spacer, $users) . (isset($wrap[1]) ? $wrap[1] : '');
    }

    return '';
}

function get_filecat_childcount($fcatid = 0) {
    return _dbQuery('SELECT COUNT(fkey_id) FROM ' . DB_PREPEND . 'phpwcms_filekey WHERE fkey_deleted=0 AND fkey_cid=' . intval($fcatid), 'COUNT');
}

/**
 * Test email based on RFC 822/2822/5322 Email Parser
 *
 * @param string email address
 *
 * @return bool
 * @copyright Cal Henderson <cal@iamcal.com>
 */
function is_valid_email($email, $options = array()) {
    // IDN conversion
    $email = idn_encode($email);

    // wrapped by default function as used since long time in phpwcms
    return is_valid_email_address($email, $options);
}

/**
 * Convert internationalized domain names
 *
 * @param string
 *
 * @return string
 */
function idn_encode($string = '') {
    require_once PHPWCMS_ROOT . '/include/inc_ext/idna_convert/idna_convert.class.php';
    // convert to utf-8 first
    $string = makeCharsetConversion($string, PHPWCMS_CHARSET, 'utf-8');
    // include punicode conversion if >= PHP5
    if (empty($string) || !class_exists('idna_convert')) {
        return $string;
    }
    $IDN = new idna_convert();

    return $IDN->encode($string);
}

function read_textfile($filename, $mode = 'rb') {
    if (is_file($filename)) {
        $fd = @fopen($filename, $mode);
        $text = fread($fd, filesize($filename));
        fclose($fd);

        return $text;
    }

    return false;
}

function write_textfile($filename, $text, $mode = 'w+b') {
    if ($fp = @fopen($filename, $mode)) {
        if (empty($text)) {
            $text = "\n";
        }
        fwrite($fp, $text);
        fclose($fp);

        return true;
    }

    return false;
}

function check_cache($file, $cache_timeout = 0) {
    if (is_file($file)) {    // file exists
        $filetime = filemtime($file);
        $fileage = time() - $filetime;
        if ($cache_timeout > $fileage) {
            return 'VALID';     // file is up-to-date
        } else {
            return 'EXPIRED';   // file is too old and expired
        }
    } else {
        return 'MISSING';       // file not present
    }
}

//added: 09-20-2003
function add_keywords_to_search($list_of_keywords, $keywords, $spacer = " ", $start_spacer = 1) {
    //adds available keywords to the values used by search engine in file section
    //returns a string
    $kw_string = '';
    if (is_array($list_of_keywords) && count($list_of_keywords) && $keywords) {
        $kw = explode(":", $keywords);
        if (count($kw)) {
            foreach ($kw as $value) {
                list($kw_cat, $kw_id) = explode("_", $value);
                $kw_id = intval($kw_id);
                if ($kw_string) {
                    $kw_string .= $spacer;
                }
                if (isset($list_of_keywords[$kw_id])) {
                    $kw_string .= $list_of_keywords[$kw_id];
                }
            }
        }
    }

    return (($start_spacer) ? $spacer : '') . $kw_string;
}

function get_list_of_file_keywords() {
    //reads possible keywords defined by admin and returns
    //array with values if exists else it returns false
    $file_key = array();
    if ($result = _dbQuery("SELECT fkey_id, fkey_name FROM " . DB_PREPEND . "phpwcms_filekey")) {
        foreach ($result as $row) {
            $file_key[intval($row["fkey_id"])] = html($row["fkey_name"]);
        }
    }

    return count($file_key) ? $file_key : false;
}

function js_singlequote($t = '') {
    // make singe quotes js compatible
    return str_replace(array("\\", "&#92;", "'", '"'), array("\\\\", "\\\\", '&#39;', '&quot;'), $t);
}

function get_tmpl_files($dir = '', $ext = '', $sort = true) {
    //browse a dir and return all template files
    $c = '\.html|\.htm|\.php|\.inc|\.tmpl';
    if ($ext) {
        $ext = explode(',', $ext);
        if (count($ext)) {
            $c = array();
            foreach ($ext as $value) {
                $c[] = '\.' . $value;
            }
            $c = implode('|', $c);
        }
    }
    $regexp = '/(' . $c . ')$/i';
    $fa = array(); //file array
    if (is_dir($dir)) {
        $ph = opendir($dir);
        while ($pf = readdir($ph)) {
            if (substr($pf, 0, 1) !== '.' && is_file($dir . '/' . $pf) && preg_match($regexp, $pf)) {
                $fa[] = $pf; //add $pf to file array for current dir
            }
        }
        closedir($ph);
        if (count($fa) && $sort === true) {
            sort($fa);
        }
    }

    return $fa;
}

function get_tmpl_section($s = '', $t = '') {
    // try to return the matching section of template
    // within HTML comments like <!--SECTION_START//-->...<!--SECTION_END//-->
    return preg_match("/<!--" . $s . "_START\/\/-->(.*?)<!--" . $s . "_END\/\/-->/si", $t, $g) ? $g[1] : '';
}

function replace_tmpl_section($s = '', $t = '', $r = '') {
    // try to delete the matching section of template
    // within HTML comments like <!--SECTION_START//-->...<!--SECTION_END//-->
    return preg_replace("/<!--" . $s . "_START\/\/-->(.*?)<!--" . $s . "_END\/\/-->/si", $r, $t);
}

function importedFile_toString($filename = '') {
    $file = array();
    if (isset($_FILES[$filename]) && !$_FILES[$filename]['error']) {
        $file['name'] = $_FILES[$filename]['name'];
        $file['data'] = file_get_contents($_FILES[$filename]['tmp_name']);
    } else {
        $file = false;
    }

    return $file;
}

function get_order_sort($order = 0, $resort = 0) {
    // for getting right article structure sorting INT
    // $o[0] = $acat_order; $o[1] = $acat_ordersort;
    $o = array(3);
    $order = intval($order);
    switch ($order) {
        case  0:
            $o[0] = 0;
            $o[1] = 0;
            $o[2] = ' article_sort ASC';
            break;
        case  1:
            $o[0] = 0;
            $o[1] = 1;
            $o[2] = ' article_sort DESC';
            break;
        case  2:
            $o[0] = 2;
            $o[1] = 0;
            $o[2] = ' article_created ASC';
            break;
        case  3:
            $o[0] = 2;
            $o[1] = 1;
            $o[2] = ' article_created DESC';
            break;
        case  4:
            $o[0] = 4;
            $o[1] = 0;
            $o[2] = ' article_tstamp ASC';
            break;
        case  5:
            $o[0] = 4;
            $o[1] = 1;
            $o[2] = ' article_tstamp DESC';
            break;
        case  6:
            $o[0] = 6;
            $o[1] = 0;
            $o[2] = ' article_begin ASC';
            break;
        case  7:
            $o[0] = 6;
            $o[1] = 1;
            $o[2] = ' article_begin DESC';
            break;
        case  8:
            $o[0] = 8;
            $o[1] = 0;
            $o[2] = ' article_title ASC';
            break;
        case  9:
            $o[0] = 8;
            $o[1] = 1;
            $o[2] = ' article_title DESC';
            break;
        case 10:
            $o[0] = 10;
            $o[1] = 0;
            $o[2] = ' article_end ASC';
            break;
        case 11:
            $o[0] = 10;
            $o[1] = 1;
            $o[2] = ' article_end DESC';
            break;
    }
    $o[2] = ' article_priorize DESC,' . $o[2];

    return $o;
}

function getRefererURL() {
    $url = strtolower(substr($GLOBALS['phpwcms']['site'], 0, 5)) !== 'https' ? 'http://' : 'https://';

    return $url . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
}

function build_QueryString() {
    // used to build a query string based on given parameters
    // there is no limitation in length
    // first Parameter is the delimtere char
    // build_QueryString('&amp;', 'k=1', 'b=5')
    $numargs = func_num_args();
    $query = array();
    $delimeter = '';
    if ($numargs) {
        $delimeter = func_get_arg(0);
        for ($i = 1; $i < $numargs; $i++) {
            $query[] = func_get_arg($i);
        }
    }

    return implode($delimeter, $query);
}

function getAltTitle($string = '', $altAndTitle = 0, $echo = 0) {
    $attribute = trim($string);
    $altAndTitle = intval($altAndTitle);
    if ($altAndTitle === 0) {
        $attribute = 'alt="' . $attribute . '" title="' . $attribute . '"';
    } elseif ($altAndTitle === 1) {
        $attribute = 'alt="' . $attribute . '"';
    } elseif ($altAndTitle === 2) {
        $attribute = 'title="' . $attribute . '"';
    }
    if ($echo) {
        echo $attribute;
    }

    return $attribute;
}

function sendEmail($data = array(
    'recipient' => '',
    'toName' => '',
    'subject' => '',
    'isHTML' => 0,
    'html' => '',
    'text' => '',
    'attach' => array(),
    'from' => '',
    'fromName' => '',
    'sender' => '',
    'senderName' => '',
    'stringAttach' => array(),
)) {
    // used to send a standardized email message
    global $phpwcms;
    $mailInfo = array(0 => false, 1 => '');
    $sendTo = array();
    $from = empty($data['from']) || !is_valid_email($data['from']) ? $phpwcms['SMTP_FROM_EMAIL'] : $data['from'];
    $sender = empty($data['sender']) || !is_valid_email($data['sender']) ? $from : $data['sender'];
    $fromName = empty($data['fromName']) ? '' : cleanUpForEmailHeader($data['fromName']);
    $senderName = empty($data['senderName']) ? $fromName : cleanUpForEmailHeader($data['senderName']);
    $toName = empty($data['toName']) ? '' : cleanUpForEmailHeader($data['toName']);
    $subject = empty($data['subject']) ? 'Email sent by phpwcms' : cleanUpForEmailHeader($data['subject']);
    if (empty($data['html'])) {
        $data['html'] = '';
        $data['isHTML'] = 0;
    } elseif (empty($data['isHTML'])) {
        $data['isHTML'] = 0;
    } else {
        $data['isHTML'] = 1;
    }
    if (empty($data['text'])) {
        $data['text'] = '';
    }
    if (!is_array($data['recipient'])) {
        $recipient = str_replace(' ', '', trim($data['recipient']));
        $recipient = str_replace(',', ';', $recipient);
        $recipient = str_replace(' ', '', $recipient);
        $recipient = explode(';', $recipient);
    } else {
        $recipient = $data['recipient'];
    }
    if (is_array($recipient) && count($recipient)) {
        foreach ($recipient as $value) {
            if (is_valid_email($value)) {
                $sendTo[] = $value;
            }
        }
    }
    if (count($sendTo)) {
        require_once PHPWCMS_ROOT . '/include/inc_ext/phpmailer/PHPMailerAutoload.php';
        $mail = new PHPMailer();
        $mail->Mailer = $phpwcms['SMTP_MAILER'];
        $mail->Host = $phpwcms['SMTP_HOST'];
        $mail->Port = $phpwcms['SMTP_PORT'];
        if ($phpwcms['SMTP_AUTH']) {
            $mail->SMTPAuth = 1;
            $mail->Username = $phpwcms['SMTP_USER'];
            $mail->Password = $phpwcms['SMTP_PASS'];
        }
        if (!empty($phpwcms['SMTP_SECURE'])) {
            $mail->SMTPSecure = $phpwcms['SMTP_SECURE'];
        }
        if (!empty($phpwcms['SMTP_AUTH_TYPE'])) {
            $mail->AuthType = $phpwcms['SMTP_AUTH_TYPE'];
            if ($phpwcms['SMTP_AUTH_TYPE'] === 'NTLM') {
                if (!empty($phpwcms['SMTP_REALM'])) {
                    $mail->Realm = $phpwcms['SMTP_REALM'];
                }
                if (!empty($phpwcms['SMTP_WORKSTATION'])) {
                    $mail->Workstation = $phpwcms['SMTP_WORKSTATION'];
                }
            }
        }
        $mail->CharSet = $phpwcms["charset"];
        $mail->isHTML($data['isHTML']);
        $mail->Subject = $data['subject'];
        if ($data['isHTML']) {
            if ($data['text'] != '') {
                $mail->AltBody = $data['text'];
            }
            $mail->Body = $data['html'];
        } else {
            $mail->Body = $data['text'];
        }
        if (!$mail->setLanguage($phpwcms['default_lang'], PHPWCMS_ROOT . '/include/inc_ext/phpmailer/language/')) {
            $mail->setLanguage('en', PHPWCMS_ROOT . '/include/inc_ext/phpmailer/language/');
        }
        $mail->setFrom($from, $fromName);
        $mail->addReplyTo($sender, $senderName);
        $mail->addAddress($sendTo[0], $toName);
        unset($sendTo[0]);
        if (is_array($sendTo) && count($sendTo)) {
            foreach ($sendTo as $value) {
                $mail->addBCC($value);
            }
        }
        if (isset($data['attach']) && is_array($data['attach']) && count($data['attach'])) {
            foreach ($data['attach'] as $attach_file) {
                $mail->addAttachment($attach_file);
            }
        }
        if (isset($data['stringAttach']) && is_array($data['stringAttach']) && count($data['stringAttach'])) {
            $attach_counter = 1;
            foreach ($data['stringAttach'] as $attach_string) {
                if (is_array($attach_string) && !empty($attach_string['data'])) {
                    $attach_string['filename'] = empty($attach_string['filename']) ? 'attachment_' . $attach_counter : $attach_string['filename'];
                    $attach_string['mime'] = empty($attach_string['mime']) ? 'application/octet-stream' : $attach_string['mime'];
                    $attach_string['encoding'] = empty($attach_string['encoding']) ? 'base64' : $attach_string['encoding'];
                    $mail->addStringAttachment($attach_string['data'], $attach_string['filename'], $attach_string['encoding'], $attach_string['mime']);
                    $attach_counter++;
                }
            }
        }
        if (!$mail->send()) {
            $mailInfo[0] = false;
            $mailInfo[1] = $mail->ErrorInfo;
        } else {
            $mailInfo[0] = true;
        }
        unset($mail);
    } else {
        $mailInfo[0] = false;
        $mailInfo[1] = 0; //means no recipient
    }

    return $mailInfo;
}

function getFormTrackingValue() {
    //creates a new form tracking entry in database
    //returns a <input type="hidden">
    $ip = getRemoteIP();
    $hash = md5($ip . $GLOBALS['phpwcms']["db_pass"] . date('G'));
    $entry_id = time();
    if (!empty($GLOBALS['phpwcms']["form_tracking"]) && !PHPWCMS_GDPR_MODE) {
        $sql = "INSERT INTO " . DB_PREPEND . "phpwcms_formtracking SET formtracking_hash=" . _dbEscape($hash) . ", formtracking_ip=" . _dbEscape($ip);
        $result = _dbQuery($sql, 'INSERT');
        if (isset($result['INSERT_ID'])) {
            $entry_id = $result['INSERT_ID'];
        }
    }

    return '<input type="hidden" name="' . $hash . '" value="' . $entry_id . '" />';
}

function checkFormTrackingValue() {
    //compare given tracking value against db tracking entry
    $ip = getRemoteIP();
    $hash1 = md5($ip . $GLOBALS['phpwcms']["db_pass"] . date('G'));
    $hash2 = md5($ip . $GLOBALS['phpwcms']["db_pass"] . date('G', time() - 3600)); //max form delay of 1 hour
    $valid = false;
    if (isset($_POST[$hash1])) {
        // form method POST
        $entry_id = intval($_POST[$hash1]);
        $valid = true;
        unset($_POST[$hash1]);
    } elseif (isset($_POST[$hash2])) {
        // form method POST 1 hour ago
        $entry_id = intval($_POST[$hash2]);
        $valid = true;
        unset($_POST[$hash2]);
    } else {
        // hm, no hash means - ERROR
        $valid = false;
    }

    return $valid;
}

function cleanUpSpecialHtmlEntities($string = '') {
    if (isset($GLOBALS['SPECIAL_ENTITIES_TABLES'])) {
        $string = str_replace($GLOBALS['SPECIAL_ENTITIES_TABLES']['latin1_encode'], $GLOBALS['SPECIAL_ENTITIES_TABLES']['latin1_decode'], $string);
        $string = str_replace($GLOBALS['SPECIAL_ENTITIES_TABLES']['symbol_encode'], $GLOBALS['SPECIAL_ENTITIES_TABLES']['symbol_decode'], $string);
        $string = str_replace($GLOBALS['SPECIAL_ENTITIES_TABLES']['specialchars_encode'], $GLOBALS['SPECIAL_ENTITIES_TABLES']['specialchars_decode'], $string);
    }

    return $string;
}

function encode_SpecialHtmlEntities($string = '', $mode = 'ALL') {
    global $SPECIAL_ENTITIES_TABLES;
    switch ($mode) {
        case 'LATIN':
            $string = str_replace($SPECIAL_ENTITIES_TABLES['latin1_decode'], $SPECIAL_ENTITIES_TABLES['latin1_encode'], $string);
            break;
        case 'SYMBOL':
            $string = str_replace($SPECIAL_ENTITIES_TABLES['symbol_decode'], $SPECIAL_ENTITIES_TABLES['symbol_encode'], $string);
            break;
        case 'LATIN SYMBOL':
        case 'SYMBOL LATIN':
            $string = str_replace($SPECIAL_ENTITIES_TABLES['latin1_decode'], $SPECIAL_ENTITIES_TABLES['latin1_encode'], $string);
            $string = str_replace($SPECIAL_ENTITIES_TABLES['symbol_decode'], $SPECIAL_ENTITIES_TABLES['symbol_encode'], $string);
            break;
        case 'SPECIALCHARS':
            $string = str_replace($SPECIAL_ENTITIES_TABLES['specialchars_decode'], $SPECIAL_ENTITIES_TABLES['specialchars_encode'], $string);
            break;
        case 'LATIN SPECIALCHARS':
        case 'SPECIALCHARS LATIN':
            $string = str_replace($SPECIAL_ENTITIES_TABLES['latin1_decode'], $SPECIAL_ENTITIES_TABLES['latin1_encode'], $string);
            $string = str_replace($SPECIAL_ENTITIES_TABLES['specialchars_decode'], $SPECIAL_ENTITIES_TABLES['specialchars_encode'], $string);
            break;
        case 'SYMBOL SPECIALCHARS':
        case 'SPECIALCHARS SYMBOL':
            $string = str_replace($SPECIAL_ENTITIES_TABLES['symbol_decode'], $SPECIAL_ENTITIES_TABLES['symbol_encode'], $string);
            $string = str_replace($SPECIAL_ENTITIES_TABLES['specialchars_decode'], $SPECIAL_ENTITIES_TABLES['specialchars_encode'], $string);
            break;
        default:
            $string = str_replace($SPECIAL_ENTITIES_TABLES['latin1_decode'], $SPECIAL_ENTITIES_TABLES['latin1_encode'], $string);
            $string = str_replace($SPECIAL_ENTITIES_TABLES['symbol_decode'], $SPECIAL_ENTITIES_TABLES['symbol_encode'], $string);
            $string = str_replace($SPECIAL_ENTITIES_TABLES['specialchars_decode'], $SPECIAL_ENTITIES_TABLES['specialchars_encode'], $string);
    }

    return $string;
}

function cleanUpFormMailerPostValue($string = '') {
    if (strpos("\n", $string) !== false) {
        return '';
    }
    $string = clean_slweg($string);
    $string = cleanUpSpecialHtmlEntities($string);

    return $string;
}

function cleanUpForEmailHeader($text = '') {
    list($text) = explode("\n", $text);
    list($text) = explode("%0D", $text);
    list($text) = explode("%0d", $text);
    list($text) = explode("\r", $text);
    list($text) = explode("%0A", $text);
    list($text) = explode("%0a", $text);
    $spam = array('/bcc:/i', '/cc:/i', '/to:/i', '/from:/i', '/mime-version:/i', '/reply-to:/i');
    $text = preg_replace($spam, '', $text);

    return trim($text);
}

function getCleanSubString($cutString = '', $maxLength = 0, $moreChar = '', $cutMode = 'char', $sanitize = null) {
    // used to cut a string by words or chars
    if (empty($maxLength) || $maxLength < 0) {
        return $cutString;
    }
    if ($cutMode == 'word') {
        $words = preg_split("/[\s]+/", $cutString, -1, PREG_SPLIT_NO_EMPTY);
        $cutString = '';
        for ($i = 0; $i < $maxLength; $i++) {
            if (!empty($words[$i])) {
                $cutString .= $words[$i] . ' ';
            }
        }
        $cutString = trim($cutString);
        if (count($words) > $maxLength && $moreChar) {
            $cutString .= $moreChar;
        }
    } else {
        $curString = trim($cutString);
        if ($curString == '') {
            return '';
        } elseif ($sanitize === null && $maxLength >= mb_strlen($curString)) {
            return $curString;
        }
        preg_match_all('/&[^;]+;|./', $curString, $match);
        if (is_array($match[0]) && count($match[0]) > $maxLength) {
            $match[0] = array_slice($match[0], 0, $maxLength);
            $cutString = trim(implode('', $match[0]));
            $cutString .= $moreChar;
        }
    }
    if ($sanitize !== null && $cutString) {
        $cutString = htmlfilter_sanitize($cutString, array(), array(), array('img', 'br', 'hr', 'input'), true);
    }

    return $cutString;
}

function headerAvoidPageCaching() {
    header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
    header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
    header("Cache-Control: no-cache");
    header("Pragma: no-cache");
    header("Cache-Control: post-check=0, pre-check=0", false);
}

function getFileInformation($fileID) {
    if (empty($fileID)) {
        return false;
    }
    $f = '';
    if (is_array($fileID)) {
        if (!count($fileID)) {
            return false;
        }
        $f = array();
        foreach ($fileID as $value) {
            $f[] = 'f_id=' . intval($value);
        }
        $f = implode(' OR ', $f);
    } elseif (intval($fileID)) {
        $f = 'f_id=' . intval($fileID);
    } else {
        return false;
    }
    $sql = "SELECT * FROM " . DB_PREPEND . "phpwcms_file WHERE f_public=1 AND f_aktiv=1 AND f_kid=1 AND f_trash=0 AND (" . $f . ")";

    return _dbQuery($sql);
}

function getJavaScriptSourceLink($src, $prefix = '  ') {
    return ($src) ? $prefix . '<script' . SCRIPT_ATTRIBUTE_TYPE . ' src="' . $src . '"></script>' : '';
}

function convertStringToArray($string = '', $seperator = ',', $mode = 'UNIQUE', $rmvDblWSp = true) {
    // clean up a seperator seperated string and return as array
    if (trim($string) === '') {
        return array();
    }
    // replace all duplicate white chars by single space
    if ($rmvDblWSp) {
        $string = preg_replace('/\s\s+/', ' ', $string);
    }
    $string = explode($seperator, $string);
    $string = array_map('trim', $string);
    $string = array_diff($string, array('', null, false));
    if ($mode == 'UNIQUE') {
        $string = array_unique($string);
    }

    return $string;
}

function get_unique_array($array, $diff=array('', null, false)) {
    if ($diff) {
        $array = array_diff($array, $diff);
    }
    return array_unique($array);
}

function decode_entities($text) {
    $text = @html_entity_decode($text, ENT_QUOTES, PHPWCMS_CHARSET);
    if (strpos($text, '&') === false) {
        return $text;
    }
    $text = preg_replace_callback('/&#x([0-9a-f]+);/i', 'convertHexNumericToChar', $text);
    $text = preg_replace_callback('/&#([0-9]+);/', 'convertNumericToChar', $text);

    return $text;
}

function convertHexNumericToChar($matches) {
    return convertDecChar(hexdec($matches[1]));
}

function convertNumericToChar($matches) {
    return convertDecChar($matches[1]);
}

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

function is_html($string = '') {
    $length_1 = strlen($string);
    $length_2 = strlen(strip_tags($string));
    if ($length_1 != $length_2) {
        return true;
    }
    $length_2 = strlen(decode_entities($string));
    if ($length_1 != $length_2) {
        return true;
    }

    return false;
}

function stripped_cache_content($page = '') {
    // clean up html page
    $page = preg_replace('@<script[^>]*?>.*?</script>@si', '', $page);
    $page = str_replace('><', '> <', $page);
    $page = strip_tags($page);
    $page = decode_entities($page);
    $page = preg_replace('/\s+/s', ' ', $page);

    return $page;
}

function optimizeForSearch() {
    // used to build a string optimized for search
    $numargs = func_num_args();
    $text = '';
    if ($numargs) {
        for ($i = 0; $i < $numargs; $i++) {
            $text .= ' ' . func_get_arg($i);
        }
        $text = stripped_cache_content($text);
        $text = cleanUpSpecialHtmlEntities($text);
        $text = decode_entities($text);
        $text = str_replace(array(
                                '!',
                                '"',
                                "'",
                                '.',
                                '#',
                                ';',
                                '~',
                                '+',
                                '*',
                                '%',
                                '&',
                                '$',
                                '§',
                                ':',
                                '@',
                                ',',
                                '|',
                            ), ' ', $text);
        $text = preg_replace('/\[.*?\]/', '', $text);
        $text = preg_replace('/\{.*?\}/', '', $text);
        $text = strtoupper($text);
        $text = implode(' ', convertStringToArray($text, ' ', 'UNIQUE', false));
    }

    return $text;
}

function return_bytes_shorten($val, $round = 2, $return_bytes = 0) {
    $val = strtolower(trim($val));
    $last = substr($val, -1);
    if (empty($return_bytes)) {
        $space = '';
        $byte = '';
    } else {
        $space = $return_bytes === '1' ? ' ' : $return_bytes;
        $byte = 'B';
    }
    if ($last === 'k' || $last === 'm' || $last === 'g' || $last === 't') {
        if ($byte) {
            $val .= $space . 'Byte';
        }

        return $val;
    }
    $val = ceil($val);
    if ($val >= (1024 * 1024 * 1024 * 1024)) {
        //T
        $val = round($val / (1024 * 1024 * 1024 * 1024), $round);
        $val .= $space . 'T' . $byte;
    } elseif ($val >= (1024 * 1024 * 1024)) {
        //G
        $val = round($val / (1024 * 1024 * 1024), $round);
        $val .= $space . 'G' . $byte;
    } elseif ($val >= (1024 * 1024)) {
        //M
        $val = round($val / (1024 * 1024), $round);
        $val .= $space . 'M' . $byte;
    } elseif ($val >= 1024) {
        //K
        $val = round($val / 1024, $round);
        $val .= $space . 'K' . $byte;
    } elseif ($val < 1024) {
        //Byte but as 0.xxx KB
        $val = round($val / 1024, $round + 1);
        $val .= $space . 'K' . $byte;
    }

    return $val;
}

function return_bytes($val) {
    // taken from: http://de3.php.net/manual/en/function.ini-get.php
    $val = strtolower(trim($val));
    $last = substr($val, -1);
    $val = floatval($val);
    switch ($last) {
        case 't':
            $val *= 1024;
        case 'g':
            $val *= 1024;
        case 'm':
            $val *= 1024;
        case 'k':
            $val *= 1024;
    }

    return ceil($val);
}

function return_upload_errormsg($value) {
    $err = '';
    switch ($value) {
        case 0:
            break;
        case 1:
            $err = "The uploaded file exceeds the upload_max_filesize directive (" . @ini_get("upload_max_filesize") . ") in php.ini.";
            break;
        case 2:
            $err = "The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form.";
            break;
        case 3:
            $err = "The uploaded file was only partially uploaded.";
            break;
        case 4:
            $err = "No file was uploaded.";
            break;
        case 6:
            $err = "Missing a temporary folder.";
            break;
        case 7:
            $err = "Failed to write file to disk";
            break;
        default:
            $err = "Unknown file upload error";
    }

    return $err;
}

function csvFileToArray($csvfile, $delimiter = ';', $heading = false, $enclosure = '"', $linelength = 1000) {
    //import CSV file and convert to array
    if (!is_file($csvfile)) {
        return false;
    }
    $first = 0;
    $datas = array();
    $phpver = version_compare('4.3.0', phpversion(), '<');
    if ($phpver) {
        $oldini = ini_get('auto_detect_line_endings');
        @ini_set('auto_detect_line_endings', '1');
    }
    $handle = fopen($csvfile, 'rb');
    while (($data = fgetcsv($handle, $linelength, $delimiter, $enclosure)) !== false) {
        // continue in case there is header row
        if ($heading && !$first) {
            foreach ($data as $key => $value) {
                $value = trim($value);
                $datas[0][$key] = $value ? $value : 'Column' . $key;
            }
            $first++;
            continue;
        }
        if (trim(implode('', $data)) == '') {
            continue;
        }
        $datas[$first] = $data;
        $first++;
    }
    fclose($handle);
    if ($phpver) {
        @ini_set('auto_detect_line_endings', $oldini);
    }

    return $datas;
}

function shortHash($string = '', $_Hash_function = 'md5') {
    return rtrim(base64_encode(pack('H*', $_Hash_function($string))), '=');
}

function replaceGlobalRT($string = '') {
    $string = str_replace(array('{SITE}', '{PHPWCMS_URL}'), PHPWCMS_URL, $string);
    $string = str_replace('{PHPWCMS_TEMPLATE}', TEMPLATE_PATH, $string);
    $string = str_replace('{IP}', PHPWCMS_GDPR_MODE ? getAnonymizedIp() : getRemoteIP(), $string);
    $string = renderRTDate($string);

    return $string;
}

function renderRTDate($string = '') {
    return preg_replace_callback('/\{(DATE|GMDATE):(.*?)\}/', 'formatRTDate', $string);
}

function formatRTDate($matches) {
    // very cool function to render date or gmdate
    // - {DATE:DATE_FORMAT}, {GMDATE:DATE_FORMAT},
    // - {DATE:DATE_FORMAT SET:TIMESTAMP}, {GMDATE:DATE_FORMAT SET:TIMESTAMP}
    $type = strtolower($matches[1]);
    $matches = explode(' SET:', $matches[2]);
    if (empty($matches[1])) {
        return $type($matches[0]);
    }
    $matches[1] = trim($matches[1]);
    if (is_numeric($matches[1])) {
        $matches[1] = intval($matches[1]);

        return $type($matches[0], $matches[1]);
    }

    return $type($matches[0], phpwcms_strtotime($matches[1], null, now()));
}

function makeCharsetConversion($string = '', $in_charset = 'utf-8', $out_charset = 'utf-8', $entityEncode = false) {
    global $phpwcms;
    $in_charset = strtolower($in_charset);
    $out_charset = strtolower($out_charset);
    if (empty($string) || $in_charset == $out_charset || empty($in_charset) || empty($out_charset)) {
        return $string;
    }
    $phpCharsetSuppport = returnCorrectCharset($in_charset);
    if ($phpCharsetSuppport) {
        $string = doHtmlEntityPHPCleanUp($string, $phpCharsetSuppport);
    }
    if ($entityEncode) {
        $convertInOut = $in_charset . $out_charset . 'EntitiesOn';
        $entityEncode = true;
    } else {
        $convertInOut = $in_charset . $out_charset . 'EntitiesOff';
        $entityEncode = false;
    }
    if (!isset($phpwcms['convert_charsets'])) {
        $phpwcms['convert_charsets'] = array();
    }
    if (!isset($phpwcms['convert_charsets'][$convertInOut])) {
        require_once PHPWCMS_ROOT . '/include/inc_ext/ConvertCharset/ConvertCharset.class.php';
        $phpwcms['convert_charsets'][$convertInOut] = new ConvertCharset($in_charset, $out_charset, $entityEncode);
    }
    $NewEncoding =& $phpwcms['convert_charsets'][$convertInOut];

    return $NewEncoding->Convert($string);
}

function doHtmlEntityPHPCleanUp($string, $charset) {
    $string = html_entities($string);

    return decode_entities($string);
}

function returnCorrectCharset($in_charset = '') {
    $in_charset = strtolower($in_charset);
    switch ($in_charset) {
        case 'iso-8859-1':
        case 'iso8859-1':
            $in_charset = 'iso-8859-1';
            break;
        case 'iso-8859-15':
        case 'iso8859-15':
            $in_charset = 'iso-8859-15';
            break;
        case 'utf-8':
            $in_charset = 'utf-8';
            break;
        case 'cp866':
        case 'ibm866':
        case '866':
            $in_charset = version_compare(phpversion(), '4.3.2', '<') ? false : 'cp866';
            break;
        case 'cp1251':
        case 'windows-1251':
        case 'win-1251':
        case '1251':
            $in_charset = version_compare(phpversion(), '4.3.2', '<') ? false : 'windows-1251';
            break;
        case 'cp1252':
        case 'windows-1252':
        case 'win-1252':
        case '1252':
            $in_charset = 'windows-1252';
            break;
        case 'koi8-r':
        case 'koi8-ru':
        case 'koi8r':
            $in_charset = version_compare(phpversion(), '4.3.2', '<') ? false : 'koi8-r';
            break;
        case 'big5':
        case '950':
            $in_charset = 'big5';
            break;
        case 'gb2312':
        case '936':
            $in_charset = 'gb2312';
            break;
        case 'big5-hkscs':
            $in_charset = 'big5-hkscs';
            break;
        case 'shift_jis':
        case 'sjis':
        case '932':
            $in_charset = 'shift_jis';
            break;
        case 'euc-jp':
        case 'eucjp':
            $in_charset = 'euc-jp';
            break;
        default:
            $in_charset = false;
    }

    return $in_charset;
}

function returnSubdirListAsArray($dir = '') {
    // browse a given path and return all sub directories
    if (empty($dir) || !is_dir($dir)) {
        return false;
    }
    $subdir = array();
    $ph = opendir($dir);
    while ($pf = readdir($ph)) {
        if (substr($pf, 0, 1) !== '.' && is_dir($dir . '/' . $pf)) {
            $subdir[] = $pf;
        }
    }
    closedir($ph);

    return $subdir;
}

function returnFileListAsArray($dir = '', $extfilter = '') {
    // browse a given path and return all contained files
    if (empty($dir) || !is_dir($dir)) {
        return false;
    }
    $files = array();
    $ph = opendir($dir);
    if (empty($extfilter)) {
        $extfilter = array();
    } elseif (is_string($extfilter)) {
        $extfilter = convertStringToArray(strtolower(trim($extfilter)));
    } elseif (!is_array($extfilter)) {
        $extfilter = array();
    }
    $dofilter = count($extfilter) ? true : false;
    while ($pf = readdir($ph)) {
        if (substr($pf, 0, 1) !== '.' && is_file($dir . '/' . $pf)) {
            $ext = which_ext($pf);
            if ($dofilter && !in_array($ext, $extfilter)) {
                continue;
            }
            $files[$pf] = array(
                'filename' => $pf,
                'filesize' => filesize($dir . '/' . $pf),
                'filetime' => filemtime($dir . '/' . $pf),
                'ext' => $ext,
                'path' => $dir . '/' . $pf
            );
        }
    }
    closedir($ph);

    return $files;
}

function parse_ini_str($Str, $ProcessSections = true, $SplitInNameValue = false) {
    /*
    for parsing a string formatted like INI file
    [Files]
    x=File1
    y=File2
    */
    $Section = null;
    $Data = array();
    $Escape = array(
        'search' => array('\t', '\r', '\n', '\;', '\#', '\=', '\:', "\\\\"),
        'replace' => array("\t", "\r", "\n", ';', '#', '=', ':', "\\"),
    );
    if ($Temp = strtok($Str, "\r\n")) {
        do {
            switch (substr($Temp, 0, 1)) {
                case ';':
                case '#':
                    break;
                case '[':
                    if (!$ProcessSections) {
                        break;
                    }
                    $Pos = strpos($Temp, '[');
                    $Section = mb_substr($Temp, $Pos + 1, strpos($Temp, ']', $Pos) - 1);
                    if ($Section) {
                        $Data[$Section] = array();
                    }
                    break;
                default:
                    $Pos = strpos($Temp, '=');
                    if ($Pos === false) {
                        break;
                    }
                    if (!$SplitInNameValue) {
                        $key = trim(mb_substr($Temp, 0, $Pos));
                        $val = str_replace($Escape['search'], $Escape['replace'], trim(mb_substr($Temp, $Pos + 1), ' "'));
                        if ($ProcessSections && $Section) {
                            $Data[$Section][$key] = $val;
                        } else {
                            $Data[$key] = $val;
                        }
                    } else {
                        $Value = array();
                        $Value["NAME"] = trim(mb_substr($Temp, 0, $Pos));
                        $Value["VALUE"] = str_replace($Escape['search'], $Escape['replace'], trim(mb_substr($Temp, $Pos + 1), ' "'));
                        if ($ProcessSections && $Section) {
                            $Data[$Section][] = $Value;
                        } else {
                            $Data[] = $Value;
                        }
                    }
                    break;
            }
        } while ($Temp = strtok("\r\n"));
    }

    return $Data;
}

function getCookieDomain() {
    if (empty($GLOBALS['phpwcms']['parse_url']['host'])) {
        $domain = parse_url(PHPWCMS_URL);
        $domain = strtolower($domain['host']);
        if (strpos($domain, 'www') === 0) {
            $domain = substr($domain, 3);
        }
        $GLOBALS['phpwcms']['parse_url']['host'] = $domain;
    }
    return $GLOBALS['phpwcms']['parse_url']['host'];
}

function _mkdir($target) {
    // taken from WordPress
    if (file_exists($target)) { // from php.net/mkdir user contributed notes
        return (!@is_dir($target)) ? false : true;
    }
    umask(0);
    if (@mkdir($target)) {   // Attempting to create the directory may clutter up our display.
        $stat = @stat(dirname($target));
        $dir_perms = $stat['mode'] & 0007777;  // Get the permission bits.
        @chmod($target, $dir_perms);

        return true;
    } elseif (is_dir(dirname($target))) {
        return false;
    }
    if (_mkdir(dirname($target))) { // If the above failed, attempt to create the parent node, then try again.
        return _mkdir($target);
    }

    return false;
}

function sanitize_filename($filename) {
    //Filename anpassen und säubern
    if (!IS_PHP7 && get_magic_quotes_gpc()) {
        $filename = stripslashes($filename);
    }
    $remove = array(
        "?",
        "[",
        "]",
        "/",
        "\\",
        "=",
        "<",
        ">",
        ":",
        ";",
        ",",
        "'",
        '"',
        "&",
        "$",
        "#",
        "*",
        "(",
        ")",
        "|",
        "~",
        "`",
        "!",
        "{",
        "}",
        chr(0),
    );
    $filename = str_replace($remove, '', $filename);
    $filename = preg_replace('/[\s-]+/', '-', $filename);
    $filename = trim($filename, ' .-_');

    return $filename;
}

function saveUploadedFile($file, $target, $exttype = '', $imgtype = '', $rename = 0, $maxsize = 0) {
    // imgtype can be all exif_imagetype supported by your PHP install
    // see http://www.php.net/exif_imagetype
    $file_status = array(
        'status' => false,
        'error' => '',
        'name' => '',
        'tmp_name' => '',
        'size' => 0,
        'path' => '',
        'ext' => '',
        'rename' => '',
        'maxsize' => intval($maxsize),
        'error_num' => 0,
        'type' => '',
    );
    if (!isset($_FILES[$file]) || !is_uploaded_file($_FILES[$file]['tmp_name'])) {
        $file_status['error'] = 'Upload not defined';

        return $file_status;
    }
    $file_status['name'] = sanitize_filename($_FILES[$file]['name']);
    $file_status['ext'] = which_ext($file_status['name']);
    $file_status['tmp_name'] = $_FILES[$file]['tmp_name'];
    $file_status['size'] = $_FILES[$file]['size'];
    $file_status['type'] = empty($_FILES[$file]['type']) || !is_mimetype_format($_FILES[$file]['type']) ? get_mimetype_by_extension($file_status['ext']) : $_FILES[$file]['type'];
    $file_status['path'] = $target;
    $file_status['rename'] = $file_status['name'];
    $file_status['maxsize'] = empty($file_status['maxsize']) ? $GLOBALS['phpwcms']['file_maxsize'] : $file_status['maxsize'];
    if (intval($file_status['size']) > $file_status['maxsize']) {
        $file_status['error'] = 'File is too large';
        $file_status['error_num'] = 400;

        return $file_status;
    }
    if (empty($target)) {
        $file_status['error'] = 'Target directory not defined';
        $file_status['error_num'] = 412;

        return $file_status;
    }
    if (!@_mkdir($target)) {
        $file_status['error'] = 'The target directory "' . $target . '" can not be found or generated';
        $file_status['error_num'] = 412;

        return $file_status;
    }
    if ($_FILES[$file]['error']) {
        $file_status['error'] = $_FILES[$file]['error'];
        $file_status['error_num'] = 409;

        return $file_status;
    }
    if(is_string($GLOBALS['phpwcms']['allowed_upload_ext'])) {
        $GLOBALS['phpwcms']['allowed_upload_ext'] = convertStringToArray(strtolower($GLOBALS['phpwcms']['allowed_upload_ext']));
    }
    if (empty($file_status['ext']) || !in_array($file_status['ext'], $GLOBALS['phpwcms']['allowed_upload_ext'])) {
        $file_status['error'] = 'The file with extension *.' . $file_status['ext'] . ' is not allowed or invalid for uploading';
        $file_status['error_num'] = 415;
        @unlink($_FILES[$file]['tmp_name']);

        return $file_status;
    }
    if ($imgtype) {
        $imgtype = convertStringToArray(strtolower($imgtype));
        if (count($imgtype)) {
            $data = @getimagesize($_FILES[$file]['tmp_name']);
            $exif_imagetype = array(
                1 => 'gif',
                2 => 'jpg',
                3 => 'png',
                4 => 'swf',
                5 => 'psd',
                6 => 'bmp',
                7 => 'tif',
                8 => 'tiff',
                9 => 'jpc',
                10 => 'jp2',
                11 => 'jpx',
                12 => 'jb2',
                13 => 'swc',
                14 => 'iff',
                15 => 'wbmp',
                16 => 'xbm',
                18 => 'webp'
            );
            if (!$data && !$exttype) {
                $file_status['error'] = 'Format' . ($file_status['ext'] ? ' *.' . $file_status['ext'] : '') . ' not supported (';
                $allowed = array();
                foreach ($imgtype as $value) {
                    if (($value = intval($value)) && isset($exif_imagetype[$value])) {
                        $allowed[] = '*.' . $exif_imagetype[$value];
                    }
                }
                $file_status['error'] .= implode(', ', $allowed) . ')';
                $file_status['error_num'] = 415;
                @unlink($_FILES[$file]['tmp_name']);

                return $file_status;
            } elseif ($data) {
                if (empty($exif_imagetype[$data[2]]) || !in_array($data[2], $imgtype)) {
                    $file_status['error'] = 'File type ';
                    $file_status['error'] .= empty($exif_imagetype[$data[2]]) ? $data[2] : $exif_imagetype[$data[2]];
                    $file_status['error'] .= ' is not supported for this upload (';
                    foreach ($imgtype as $imgt) {
                        $file_status['error'] .= empty($exif_imagetype[$imgt]) ? $imgt : $exif_imagetype[$imgt];
                        $file_status['error'] .= ', ';
                    }
                    $file_status['error'] = trim(trim($file_status['error']), ',');
                    $file_status['error'] .= ' only)';
                    $file_status['error_num'] = 415;
                    @unlink($_FILES[$file]['tmp_name']);

                    return $file_status;
                }
                $file_status['image'] = $data;
                $exttype = '';
            }
        }
    }
    if ($exttype) {
        $exttype = convertStringToArray(strtolower($exttype));
        if (!in_array($file_status['ext'], $exttype)) {
            $file_status['error'] = 'File type *.' . $file_status['ext'] . ' is not supported for this upload (*.' . implode(', *.', $exttype) . ' only)';
            $file_status['error_num'] = 415;
            @unlink($_FILES[$file]['tmp_name']);

            return $file_status;
        }
    }
    if (!is_writable($target)) {
        $file_status['error'] = 'Target directory <b>' . str_replace(PHPWCMS_ROOT, '', $target) . '</b> is not writable';
        $file_status['error_num'] = 412;
        @unlink($_FILES[$file]['tmp_name']);

        return $file_status;
    }
    $rename = convertStringToArray($rename);
    if (count($rename)) {
        $_temp_name = cut_ext($file_status['rename']);
        foreach ($rename as $value) {
            switch ($value) {
                case 1:
                    $_temp_name = str_replace(array(':', '/', "\\", ' '), array('-', '-', '-', '_'), phpwcms_remove_accents($_temp_name));
                    $_temp_name = preg_replace('/[^0-9a-z_\-\.]/i', '', $_temp_name);
                    break;
                case 2:
                    $_temp_name = time() . '_' . $_temp_name;
                    break;
                case 3:
                    $_temp_name = date('Ymd-His') . '_' . $_temp_name;
                    break;
                case 4:
                    $_temp_name = date('Ymd') . '_' . $_temp_name;
                    break;
                case 5:
                    $_temp_name = generic_string(6) . '_' . $_temp_name;
                    break;
                case 6:
                    $_temp_name = md5($_temp_name . ($file_status['ext'] ? '.' . $file_status['ext'] : ''));
                    break;
                case 7:
                    $_temp_name = shortHash($_temp_name . ($file_status['ext'] ? '.' . $file_status['ext'] : ''));
                    break;
            }
        }
        $file_status['rename'] = $_temp_name . ($file_status['ext'] ? '.' . $file_status['ext'] : '');
    }
    @umask(0);
    if (!@move_uploaded_file($_FILES[$file]['tmp_name'], $target . $file_status['rename'])) {
        if (!copy($_FILES[$file]['tmp_name'], $target . $file_status['rename'])) {
            $file_status['error'] = 'Saving uploaded file <b>' . html($file_status['name']) . '</b> to <b>' . html(str_replace(PHPWCMS_ROOT, '', $target . $file_status['rename'])) . '</b> failed';
            $file_status['error_num'] = 412;
            @unlink($_FILES[$file]['tmp_name']);

            return $file_status;
        }
    }
    @chmod($target . $file_status['rename'], 0644);
    $file_status['status'] = true;

    return $file_status;
}

function get_alnum_dashes($string, $remove_accents = false, $replace_space = '-', $allow_slashes = false) {
    $string = str_replace(array(' ', '?', ':', '&', '\\', ';', '#', '=', '+'), $replace_space, $string);
    if ($remove_accents) {
        $string = phpwcms_remove_accents($string);
        $string = preg_replace('/[^a-z0-9\-_\.' . ($allow_slashes ? '\/' : '') . ']/i', '', $string);
    } else {
        $string = preg_replace('/[^a-z0-9\x{00A0}-\x{D7FF}\x{F900}-\x{FDCF}\x{FDF0}-\x{FFEF}\-_\.' . ($allow_slashes ? '\/' : '') . ']/iu', '', $string);
    }

    return trim(preg_replace('/\-+/', $replace_space, $string), '._/' . $replace_space);
}

// Thanks to: http://quickwired.com/smallprojects/php_xss_filter_function.php
function xss_clean($val) {
    // remove all non-printable characters. CR(0a) and LF(0b) and TAB(9) are allowed
    // this prevents some character re-spacing such as <java\0script>
    // note that you have to handle splits with \n, \r, and \t later since they *are* allowed in some inputs
    $val = preg_replace('/([\x00-\x08][\x0b-\x0c][\x0e-\x20])/', '', $val);
    // straight replacements, the user should never need these since they're normal characters
    // this prevents like <IMG SRC=&#X40&#X61&#X76&#X61&#X73&#X63&#X72&#X69&#X70&#X74&#X3A&#X61&#X6C&#X65&#X72&#X74&#X28&#X27&#X58&#X53&#X53&#X27&#X29>
    $search = 'abcdefghijklmnopqrstuvwxyz';
    $search .= 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $search .= '1234567890!@#$%^&*()';
    $search .= '~`";:?+/={}[]-_|\'\\';
    for ($i = 0; $i < strlen($search); $i++) {
        // ;? matches the ;, which is optional
        // 0{0,7} matches any padded zeros, which are optional and go up to 8 chars
        // &#x0040 @ search for the hex values
        $val = preg_replace('/(&#[x|X]0{0,8}' . dechex(ord($search[$i])) . ';?)/i', $search[$i], $val); // with a ;
        // &#00064 @ 0{0,7} matches '0' zero to seven times
        $val = preg_replace('/(&#0{0,8}' . ord($search[$i]) . ';?)/', $search[$i], $val); // with a ;
    }
    // now the only remaining whitespace attacks are \t, \n, and \r
    $ra1 = array(
        'javascript',
        'vbscript',
        'expression',
        'applet',
        'meta',
        'xml',
        'blink',
        'link',
        'style',
        'script',
        'embed',
        'object',
        'iframe',
        'frame',
        'frameset',
        'ilayer',
        'layer',
        'bgsound',
        'title',
        'base',
    );
    $ra2 = array(
        'onabort',
        'onactivate',
        'onafterprint',
        'onafterupdate',
        'onbeforeactivate',
        'onbeforecopy',
        'onbeforecut',
        'onbeforedeactivate',
        'onbeforeeditfocus',
        'onbeforepaste',
        'onbeforeprint',
        'onbeforeunload',
        'onbeforeupdate',
        'onblur',
        'onbounce',
        'oncellchange',
        'onchange',
        'onclick',
        'oncontextmenu',
        'oncontrolselect',
        'oncopy',
        'oncut',
        'ondataavailable',
        'ondatasetchanged',
        'ondatasetcomplete',
        'ondblclick',
        'ondeactivate',
        'ondrag',
        'ondragend',
        'ondragenter',
        'ondragleave',
        'ondragover',
        'ondragstart',
        'ondrop',
        'onerror',
        'onerrorupdate',
        'onfilterchange',
        'onfinish',
        'onfocus',
        'onfocusin',
        'onfocusout',
        'onhelp',
        'onkeydown',
        'onkeypress',
        'onkeyup',
        'onlayoutcomplete',
        'onload',
        'onlosecapture',
        'onmousedown',
        'onmouseenter',
        'onmouseleave',
        'onmousemove',
        'onmouseout',
        'onmouseover',
        'onmouseup',
        'onmousewheel',
        'onmove',
        'onmoveend',
        'onmovestart',
        'onpaste',
        'onpropertychange',
        'onreadystatechange',
        'onreset',
        'onresize',
        'onresizeend',
        'onresizestart',
        'onrowenter',
        'onrowexit',
        'onrowsdelete',
        'onrowsinserted',
        'onscroll',
        'onselect',
        'onselectionchange',
        'onselectstart',
        'onstart',
        'onstop',
        'onsubmit',
        'onunload',
    );
    $ra = array_merge($ra1, $ra2);
    $found = true; // keep replacing as long as the previous round replaced something
    while ($found == true) {
        $val_before = $val;
        for ($i = 0; $i < count($ra); $i++) {
            $pattern = '/';
            for ($j = 0; $j < strlen($ra[$i]); $j++) {
                if ($j > 0) {
                    $pattern .= '(';
                    $pattern .= '(&#[x|X]0{0,8}([9][a][b]);?)?';
                    $pattern .= '|(&#0{0,8}([9][10][13]);?)?';
                    $pattern .= ')?';
                }
                $pattern .= $ra[$i][$j];
            }
            $pattern .= '/i';
            $replacement = substr($ra[$i], 0, 2) . '<x>' . substr($ra[$i], 2); // add in <> to nerf the tag
            $val = preg_replace($pattern, $replacement, $val); // filter out the hex tags
            if ($val_before == $val) {
                // no replacements were made, so exit the loop
                $found = false;
            }
        }
    }

    return $val;
}

function sanitize_multiple_emails($string) {
    $string = preg_replace('/\s|\,]/', ';', $string);
    $string = convertStringToArray($string, ';');
    $string = implode(';', $string);

    return $string;
}

function checkLogin($mode = 'REDIRECT') {
    $sql = "UPDATE " . DB_PREPEND . "phpwcms_userlog SET logged_in=0, logged_change=" . _dbEscape(time()) . " ";
    $sql .= "WHERE logged_in=1 AND (" . time() . "-logged_change) > " . intval($GLOBALS['phpwcms']["max_time"]);
    _dbQuery($sql, 'UPDATE');
    checkLoginCount();
    if (empty($_SESSION['wcs_user']) || empty($_SESSION['PHPWCMS_BROWSER_HASH']) || $_SESSION['PHPWCMS_BROWSER_HASH'] !== $GLOBALS['phpwcms']['USER_AGENT']['hash']) {
        $_SESSION = array();
        @session_destroy();
        if (!empty($_SERVER['QUERY_STRING'])) {
            $ref_url = '?ref=' . rawurlencode(PHPWCMS_URL . 'phpwcms.php?' . xss_clean($_SERVER['QUERY_STRING']));
        } else {
            $ref_url = '';
        }
        if ($mode === 'REDIRECT') {
            // check again if user was logged in and this is a valid redirect request
            $sql = 'SELECT COUNT(*)  FROM ' . DB_PREPEND . 'phpwcms_userlog WHERE ';
            $sql .= "logged_ip=" . _dbEscape(PHPWCMS_GDPR_MODE ? getAnonymizedIp() : getRemoteIP()) . " AND ";
            $sql .= '( ' . time() . ' - logged_change ) < 3600';
            $ref_url = _dbCount($sql) > 0 ? get_login_file() . $ref_url : '';
            headerRedirect(PHPWCMS_URL . $ref_url, 401);
        } else {
            return false;
        }
    }

    return true;
}

function logout_user($reason = '', $type = '') {
    $sql = "UPDATE " . DB_PREPEND . "phpwcms_userlog SET logged_change=" . _dbEscape(time()) . ", logged_in=0 ";
    $sql .= "WHERE logged_user=" . _dbEscape($_SESSION["wcs_user"]) . " AND logged_in=1";
    _dbQuery($sql, 'UPDATE');
    $_SESSION = array();
    @session_destroy();
    $login_url = PHPWCMS_URL . get_login_file();
    $get_parameter = array();
    if ($reason) {
        $get_parameter[] = 'reason=' . rawurlencode($reason);
    }
    if ($type) {
        $get_parameter[] = 'type=' . rawurlencode($type);
    }
    if (count($get_parameter)) {
        $login_url .= '?' . implode('&', $get_parameter);
    }
    headerRedirect($login_url, 401);
}

/**
 * Convert 2 to x line breaks of plain text into correct <p> and <br>
 */
function plaintext_htmlencode($text = '', $encode_function = 'html_specialchars', $render_bbcode = true) {
    $text = trim($text);
    if ($text) {
        $text = '[p]' . preg_replace('/\s{0,}\n\s{0,}\n\s{0,}/s', '[/p][p]', $text) . '[/p]';
        $text = preg_replace('/\s{0,}\n\s{0,}/s', '[br]', $text);
        $text = $encode_function($text);
        $text = str_replace(array('[/p][p]', '[p]', '[/p]', '[br]'), array("</p>\n<p>", '<p>', '</p>', "<br />\n"), $text);
        if ($render_bbcode) {
            return render_bbcode_basics($text);
        }
    }

    return $text;
}

/**
 * Convert line break to <br>
 */
function br_htmlencode($text = '', $encode_function = 'html_specialchars') {
    if ($text) {
        $text = $encode_function($text);
        $text = nl2br($text);
    }

    return $text;
}

/**
 * Render simple BBCode
 **/
function render_bbcode_basics($text = '', $mode = 'basic') {
    if ($text === '') {
        return $text;
    }
    $text = render_bbcode_url($text);
    if ($mode == 'basic') {
        $search = array(
            '[i]',
            '[/i]',
            '[u]',
            '[/u]',
            '[s]',
            '[/s]',
            '[b]',
            '[/b]',
            '[em]',
            '[/em]',
            '[br]',
            '[p]',
            '[/p]',
            '[strong]',
            '[/strong]',
            '[nowrap]',
            '[/nowrap]',
        );
        $replace = array(
            '<i>',
            '</i>',
            '<u>',
            '</u>',
            '<s>',
            '</s>',
            '<b>',
            '</b>',
            '<em>',
            '</em>',
            '<br />',
            '<p>',
            '</p>',
            '<strong>',
            '</strong>',
            '<span class="nowrap">',
            '</span>',
        );

        return str_replace($search, $replace, $text);
    }
    $search = array();
    $replace = array();
    $search[0] = '/\[i\](.*?)\[\/i\]/is';
    $replace[0] = '<i>$1</i>';
    $search[1] = '/\[u\](.*?)\[\/u\]/is';
    $replace[1] = '<u>$1</u>';
    $search[2] = '/\[s\](.*?)\[\/s\]/is';
    $replace[2] = '<del>$1</del>';
    $search[3] = '/\[b\](.*?)\[\/b\]/is';
    $replace[3] = '<b>$1</b>';
    $search[4] = '/\[br\]/i';
    $replace[4] = '<br />';
    $search[5] = '/\[em\](.*?)\[\/em\]/is';
    $replace[5] = '<em>$1</em>';
    $search[6] = '/\[code\](.*?)\[\/code\]/is';
    $replace[6] = '<code>$1</code>';
    $search[7] = '/\[cite\](.*?)\[\/cite\]/is';
    $replace[7] = '<cite>$1</cite>';
    $search[8] = '/\[li\](.*?)\[\/li\]/is';
    $replace[8] = '<li>$1</li>';
    $search[9] = '/\[dt\](.*?)\[\/dt\]/is';
    $replace[9] = '<dt>$1</dt>';
    $search[10] = '/\[dd\](.*?)\[\/dd\]/is';
    $replace[10] = '<dd>$1</dd>';
    $search[11] = '/\[ul\](.*?)\[\/ul\]/is';
    $replace[11] = '<ul>$1</ul>';
    $search[12] = '/\[ol\](.*?)\[\/ol\]/is';
    $replace[12] = '<ol>$1</ol>';
    $search[13] = '/\[dl\](.*?)\[\/dl\]/is';
    $replace[13] = '<dl>$1</dl>';
    $search[14] = '/\[h1\](.*?)\[\/h1\]/is';
    $replace[14] = '<h1>$1</h1>';
    $search[15] = '/\[h2\](.*?)\[\/h2\]/is';
    $replace[15] = '<h2>$1</h2>';
    $search[16] = '/\[h3\](.*?)\[\/h3\]/is';
    $replace[16] = '<h3>$1</h3>';
    $search[17] = '/\[h4\](.*?)\[\/h4\]/is';
    $replace[17] = '<h4>$1</h4>';
    $search[18] = '/\[h5\](.*?)\[\/h5\]/is';
    $replace[18] = '<h5>$1</h5>';
    $search[19] = '/\[h6\](.*?)\[\/h6\]/is';
    $replace[19] = '<h6>$1</h6>';
    $search[20] = '/\[p\](.*?)\[\/p\]/is';
    $replace[20] = '<p>$1</p>';
    $search[21] = '/\[strong\](.*?)\[\/strong\]/is';
    $replace[21] = '<strong>$1</strong>';
    $search[22] = '/\[nowrap\](.*?)\[\/nowrap\]/is';
    $replace[22] = '<span class="nowrap">$1</span>';
    $search[23] = '/\[blockquote\](.*?)\[\/blockquote\]/is';
    $replace[23] = '<blockquote>$1</blockquote>';

    return preg_replace($search, $replace, $text);
}

function render_bbcode_url($text) {
    if ($text === '') {
        return $text;
    }
    $text = preg_replace_callback(array('/\[url=([^ ]+)(.*?)\](.*?)\[\/url\]/', '/\[a=([^ ]+)(.*?)\](.*?)\[\/a\]/'), 'get_bbcode_ahref', $text);
    // Fallback for URL parameter having =[http://
    if (strpos($text, '=[') !== false) {
        $text = str_replace('=[[', '=####[#[####', $text);
        $text = str_replace('=[', '=####[####', $text);
        $text = preg_replace_callback('/\[(http|https|ftp):\/\/([^ ]+)(.*?)\]/', 'get_link_ahref', $text);
        $text = str_replace('=####[#[####', '=[[', $text);
        $text = str_replace('=####[####', '=[', $text);
    } else {
        $text = preg_replace_callback('/\[(http|https|ftp):\/\/([^ ]+)(.*?)\]/', 'get_link_ahref', $text);
    }

    return $text;
}

function get_bbcode_ahref($match) {
    $href = empty($match[1]) ? '#' : xss_clean($match[1]);
    $target = trim($match[2]) == '' ? '' : ' target="' . trim($match[2]) . '"';
    $text = empty($match[3]) ? $href : $match[3];

    return '<a href="' . $href . '"' . $target . '>' . $text . '</a>';
}

function get_link_ahref($match) {
    $href = empty($match[2]) ? '#' : xss_clean($match[2]);
    $text = empty($match[3]) ? $href : trim($match[3]);

    return '<a href="' . $match[1] . '://' . $href . '" target="_blank">' . $text . '</a>';
}

function strip_bbcode($text) {
    $text = str_replace(array('[br]', '[BR]'), ' ', $text);
    $text = preg_replace('|[[\/\!]*?[^\[\]]*?]|si', '', $text);
    $text = preg_replace('/\s\s+/', ' ', $text);

    return $text;
}

/**
 * Convert short file size (100M) to bytes
 */
function getBytes($size) {
    if (is_numeric($size)) {
        return $size;
    } elseif ($size) {
        $_unit = array(
            'B' => 1,
            'K' => 1024,
            'M' => 1048576,
            'G' => 1073741824,
            'T' => 1099511627776,
            'KB' => 1024,
            'MB' => 1048576,
            'GB' => 1073741824,
            'TB' => 1099511627776,
            'BYTE' => 1,
            'KILOBYTE' => 1024,
            'MEGABYTE' => 1048576,
            'GIGABYTE' => 1073741824,
            'TERABYTE' => 1099511627776,
        );
        $size = trim($size);
        foreach ($_unit as $key => $value) {
            if (preg_match('/.*?' . $key . '$/i', $size)) {
                $num = trim(preg_replace('/(.*?)' . $key . '$/i', '$1', $size));

                return ceil($num * $value);
            }
        }
    }

    return $size == false ? 0 : floatval($size);
}

/**
 * Try to calculate the memory necessary to
 * handle the image in RAM to avoid
 * errors based on memory limit.
 */
function getRealImageSize($imginfo) {
    $size = 0;
    // check image width and height
    if (!empty($imginfo[0]) && !empty($imginfo[1])) {
        $size = $imginfo[0] * $imginfo[1];
    }
    // handle possible alpha channel for PNG and TIF
    $alpha = isset($imginfo[2]) && ($imginfo[2] == 3 || $imginfo[2] == 7 || $imginfo[2] == 6) ? 1 : 0;
    if ($size && !empty($imginfo['channels'])) {
        // channel - in general this is 3 (RGB) or 4 (CMYK)
        $size = $size * ($imginfo['channels'] + $alpha);
    } elseif ($size && !empty($imginfo['bits'])) {
        // bits - general value is 8Bit, but can be higher too
        $size = $size * (log($imginfo['bits'], 2) + $alpha);
    } elseif ($size) {
        // use a default of 4 like for CMYK
        // should meet general usage
        $size = $size * (4 + $alpha);
    }

    return $size;
}

function is_intval($str, $signed = true) {
    $reg_expr = $signed ? '/^[\-+]?[0-9]+$/' : '/^[0-9]+$/';

    return (bool)preg_match($reg_expr, $str);
}

function attribute_name_clean($name = '') {
    $name = trim(phpwcms_remove_accents($name));
    $name = str_replace(array(' ', '/', '\\', '#', '+', ':', '.'), array('_', '-', '-', '_', '-', '-', '-'), $name);
    $name = preg_replace('/[^a-zA-Z0-9\-_]/', '', $name);
    $name = preg_replace('/^\d+/', '', $name);

    return $name;
}

/**
 * Try alternative way to test for bool value
 *
 * @param mixed
 * @param bool
 */
function phpwcms_boolval($BOOL, $STRICT = false) {
    if (function_exists('boolval')) {
        return boolval($BOOL);
    }
    if (is_string($BOOL)) {
        $BOOL = strtoupper($BOOL);
    }
    // no strict test, check only against false bool
    if (!$STRICT && in_array($BOOL, array(false, 0, null, 'FALSE', 'NO', 'N', 'OFF', '0'), true)) {
        return false;
        // strict, check against true bool
    } elseif ($STRICT && in_array($BOOL, array(true, 1, 'TRUE', 'YES', 'Y', 'ON', '1'), true)) {
        return true;
    }

    // let PHP decide
    return $BOOL ? true : false;
}

// sanitize a text for nice URL/alias or whatever
function uri_sanitize($text) {
    $text = str_replace(array('[br]', '__', '[b]', '[/b]', '[i]', '[/i]'), ' ', $text); // cleanup special bbcode and __
    $text = get_alnum_dashes($text, !PHPWCMS_ALIAS_UTF8, '-', PHPWCMS_ALIAS_WSLASH);
    $text = trim($text);
    if ($text !== '') {
        $text = trim(preg_replace('/\-\-+/', '-', $text), '-');
        $text = trim(preg_replace('/__+/', '_', $text), '_');
        if (PHPWCMS_ALIAS_WSLASH) {
            $text = trim(preg_replace('/\/+/', '/', $text), '/');
            $text = preg_replace('/\-\/\-/', '/', $text);
        }
    }

    return $text;
}

function phpwcms_strtotime($date, $date_format = null, $empty_return = false) {
    $strtotime = strtotime($date);
    if ($strtotime < 0 || $strtotime === false) {
        return $empty_return;
    }

    return is_string($date_format) ? date($date_format, $strtotime) : $strtotime;
}

function dec_num_count($value) {
    if ((int)$value == $value) {
        return 0;
    } elseif (!is_numeric($value)) {
        return false;
    }

    return strlen($value) - strrpos($value, '.') - 1;
}

function convert_rel2abs($text, $base) {
    if (empty($base)) {
        return $text;
    }
    // Base URL needs trailing /
    if (substr($base, -1, 1) !== '/') {
        $base .= '/';
    }
    // Fix a href
    $pattern = "/<a([^>]*) href=\"([^http|ftp|https|mailto|tel|fax|###DELETE_LINK###|###SITE_URL###][^\"]*)\"/";
    $replace = "<a\${1} href=\"" . $base . "\${2}\"";
    $text = preg_replace($pattern, $replace, $text);
    // Fix img src
    $pattern = "/<img([^>]*) src=\"([^http|ftp|https][^\"]*)\"/";
    $replace = "<img\${1} src=\"" . $base . "\${2}\"";
    $text = preg_replace($pattern, $replace, $text);

    return $text;
}

/**
 * Render the relative download URL
 *
 * @param string $hash
 * @param string $filename
 * @param bool $countonly
 * @param bool $htmlencode
 * @param null $inline
 *
 * @return string
 */
function rel_download($hash = '', $filename = '', $countonly = false, $htmlencode = true, $inline = null) {
    $href = '';
    $get = array();
    if (PHPWCMS_REWRITE) {
        $href .= 'dl/' . $hash . '/' . rawurlencode($filename);
    } else {
        $href .= 'download.php';
        $get[] = 'f=' . $hash;
    }
    if ($countonly) {
        $get[] = 'countonly=1';
    }
    if ($inline || ($inline === null && !empty($GLOBALS['phpwcms']['inline_download']))) {
        $get[] = 'target=1';
    }
    if (count($get)) {
        $href .= '?' . implode($htmlencode ? '&amp;' : '&', $get);
    }

    return $href;
}