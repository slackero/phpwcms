<?php
/**
 * phpwcms
 *
 * @author Oliver Georgi <og@phpwcms.org>
 * @copyright Copyright (c) 2002-2026, Oliver Georgi
 * @license http://opensource.org/licenses/GPL-2.0 GNU GPL-2
 **/

// ----------------------------------------------------------------
// obligate check for phpwcms constants
if (!defined('PHPWCMS_INCLUDE_CHECK')) {
    die('You Cannot Access This Script Directly, Have a Nice Day.');
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
require_once PHPWCMS_ROOT . '/include/inc_lib/classes/PhpwcmsMailer.php';
require_once PHPWCMS_ROOT . '/include/inc_lib/classes/PhpwcmsTwoFactor.php';
require_once PHPWCMS_ROOT . '/include/inc_lib/custom_fields.inc.php';
require_once PHPWCMS_ROOT . '/include/inc_lib/helper.mail.php';

function str_empty($string) {
    return $string === null || $string === '';
}

function slweg($text = '', $maxlen = 0, $trim = true) {
    $text = (string) $text;
    if ($text && str_ends_with($text, '>')) {
        $text = \voku\helper\UTF8::html_stripe_empty_tags($text);
        $text = \voku\helper\UTF8::remove_html_breaks($text);
        $text = preg_replace('/<p>&nbsp;<\/p>$/i', '', rtrim($text));
    }
    $text = \voku\helper\UTF8::cleanup($text);
    if ($trim) {
        $text = trim($text);
    }
    if ($maxlen && mb_strlen($text) > $maxlen) {
        $text = mb_substr($text, 0, $maxlen);
    }

    return $text;
}

/**
 * Sanitize a redirect target coming from a POSTed frontend form value:
 * strip CR/LF/NUL (header injection) and allow absolute or scheme-relative
 * URLs only when they point to the same origin (open redirect).
 */
function sanitize_form_redirect($target = '') {
    $target = trim((string)$target);
    $target = str_replace(["\r", "\n", "\0"], '', $target);
    if ($target !== '' && stripos($target, PHPWCMS_URL) !== 0 && (str_contains($target, '://') || str_starts_with($target, '//'))) {
        $target = '';
    }
    return $target;
}

function clean_slweg($text = '', $maxlen = 0, $trim = true) {
    $text = (string) $text;
    $text = phpwcms_strip_tags($text);
    $text = \voku\helper\UTF8::cleanup($text);
    if ($trim) {
        $text = trim($text);
    }
    if ($maxlen && mb_strlen($text) > $maxlen) {
        $text = mb_substr($text, 0, $maxlen);
    }

    return $text;
}

/**
 * UTF-8 and multibyte safe strip_tags() replacement using voku/portable-utf8
 *
 * @param string $string
 * @param string|array<string>|null $allowable_tags
 * @return string
 */
function phpwcms_strip_tags($string = '', $allowable_tags = null) {
    if (!is_string($string) || $string === '') {
        return (string)$string;
    }

    if (is_array($allowable_tags)) {
        $allowable_tags = empty($allowable_tags) ? null : '<' . implode('><', array_map(static function($tag) {
            return trim($tag, '<> ');
        }, $allowable_tags)) . '>';
    }

    return \voku\helper\UTF8::strip_tags($string, $allowable_tags, true);
}

function getpostvar($formvar, $string_laenge = 0) {
    //combines trim, stripslashes und apostrophe replace
    return _dbEscape(slweg($formvar, $string_laenge), false);
}

function html_despecialchars($h = '') {
    //call off html_specialchars
    return str_replace(
        ['&amp;', '&lt;', '&gt;', '&quot;', '&#039;', '&#92;'],
        ['&', '<', '>', '"', "'", "\\"],
        $h
    );
}

/**
 * Returns country as <option> list
 *
 * @param string $default
 * @param string $lang
 * @param array $prepend
 *
 * @return string
 */
function list_country($default = '', $lang = '', $prepend = []) {
    //Create the country list menu for forms with the given value selected
    if (empty($default)) {
        $default = strtoupper($GLOBALS['phpwcms']['default_lang']);
    }
    $country_list = '';
    $country = getCountry($lang);
    $prepend_sorted = [];
    if ($prepend) {
        foreach ($prepend as $value) {
            $prepend_sorted[$value] = '';
        }
    }
    foreach ($country as $key => $value) {
        $country_option = '<option value="'.html($key).'"';
        if ($key === $default) {
            $country_option .= ' selected="selected"';
        }
        $country_option .= '>'.html($value).'</option>';
        if (isset($prepend_sorted[$key])) {
            $prepend_sorted[$key] = $country_option;
        } else {
            $country_list .= $country_option;
        }
    }
    $prepend_sorted = implode('', $prepend_sorted);
    if ($prepend_sorted) {
        $prepend_sorted .= '<option disabled="disabled">──────────</option>';
    }
    return $prepend_sorted . $country_list;
}

function getCountry($lang = '', $get = 'COUNTRY_ARRAY') {
    global $phpwcms;
    if (empty($lang)) {
        $lang = isset($_SESSION['wcs_user_lang']) ? strtolower($_SESSION['wcs_user_lang']) : $GLOBALS['phpwcms']['default_lang'];
    }
    if (strtolower($lang) === 'native') {
        $lang = 'native';
    } else {
        $lang = strtolower(substr($lang, 0, 2));
    }
    $country_lang_var = $get . '_' . $lang;
    if (!empty($phpwcms['country'][$country_lang_var])) {
        return $phpwcms['country'][$country_lang_var];
    }
    $country_name = 'country_name_' . _dbEscape($lang, false);
    $sql = 'SHOW COLUMNS FROM ' . DB_PREPEND . "country WHERE Field='" . $country_name . "'";
    $result = _dbQuery($sql);
    if (!isset($result[0])) {
        $country_name = 'country_name';
    }
    if ($get === 'COUNTRY_NAME') {
        $phpwcms['country'][$country_lang_var] = strtoupper($lang);
        $sql = 'SELECT ' . $country_name . ' AS country FROM ' . DB_PREPEND . 'country WHERE ';
        $sql .= 'country_iso=' . _dbEscape($phpwcms['country'][$country_lang_var]) . ' LIMIT 1';
        $result = _dbQuery($sql);
        if (isset($result[0]['country'])) {
            $phpwcms['country'][$country_lang_var] = $result[0]['country'];
        }
    } else {
        $country_lang_var = 'COUNTRY_ARRAY_' . $lang;
        $phpwcms['country'][$country_lang_var] = [];
        $sql = 'SELECT country_iso, ' . $country_name . ' AS country FROM ' . DB_PREPEND . 'country ORDER BY ' . $country_name;
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
    $sql = 'SELECT prof_name FROM ' . DB_PREPEND . 'profession ORDER BY prof_name';
    if ($result = _dbQuery($sql)) {
        foreach ($result as $a) {
            echo '<option value="' . html($a['prof_name']) . '"';
            is_selected($c, $a['prof_name']);
            echo '>' . html($a['prof_name']) . '</option>';
        }
    }
}

function is_selected($c, $chkvalue, $xhtml = 1, $echoit = 1) {
    $e = (string)$c == (string)$chkvalue ? ' selected="selected"' : '';
    if ($echoit) {
        echo $e;
    }

    return $e;
}

function is_checked($c, $chkvalue, $xhtml = 1, $echoit = 1) {
    $e = (string)$c == (string)$chkvalue ? ' checked="checked"' : '';
    if ($echoit) {
        echo $e;
    }

    return $e;
}

function check_checkbox($c) {
    // Check if valid boolean value (0 or 1) was passed via checkbox
    $c = (int)$c;
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
    $_unit = [
        0 => [
            'B' => 'B',
            'K' => 'K',
            'M' => 'M',
            'G' => 'G',
            'T' => 'T',
        ],
        1 => [
            'B' => 'Byte',
            'K' => 'KB',
            'M' => 'MB',
            'G' => 'GB',
            'T' => 'TB',
        ],
        2 => [
            'B' => 'Byte',
            'K' => 'KiloByte',
            'M' => 'MegaByte',
            'G' => 'GigaByte',
            'T' => 'TeraByte',
        ],
    ];
    $zahl = (int)$zahl;
    if ($zahl < 1024) {
        $zahl = number_format($zahl, 0, '.', '.');
        $unit = 'B';
    } elseif ($zahl < 1048576) {
        $zahl = number_format($zahl / 1024, 2, '.', '.');
        $unit = 'K';
    } elseif ($zahl < 1073741824) {
        $zahl = number_format($zahl / 1048576, 2, '.', '.');
        $unit = 'M';
    } elseif ($zahl < 1099511627776) {
        $zahl = number_format($zahl / 1073741824, 2, '.', '.');
        $unit = 'G';
    } else {
        $zahl = number_format($zahl / 1125899906842624, 2, ' ', '.');
        $unit = 'T';
    }

    return $zahl . $spacer . $_unit[$short][$unit];
}

function fsizelong($zahl, $spacer = '&nbsp;') {
    return fsize($zahl, $spacer, 1);
}

function extimg($ext) {
    //get extension icon - delegate to the single extension icon map
    $map = ext_icon_map();
    return $map[$ext] ?? 'file';
}

function generic_string($length, $i = 0) {
    $gen_string = '';
    $p[0] = 'abcdefghijklmnopqrstuvwxyz';
    $p[1] = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $p[2] = '1234567890';
    $chars = match ($i) {
        1 => $p[0] . $p[2],
        2 => $p[1] . $p[2],
        3 => $p[0] . $p[1],
        4 => $p[0],
        5 => $p[1],
        6 => $p[2],
        default => $p[0] . $p[2] . $p[1],
    };
    mt_srand((int)(microtime(true) * 1000000));
    $count = strlen($chars) - 1;
    for ($x = 0; $x < $length; $x++) {
        $gen_string .= $chars[random_int(0, $count)];
    }

    return $gen_string;
}

function genlogname() {
    $usercount = _dbQuery('SELECT COUNT(*) FROM ' . DB_PREPEND . "user WHERE usr_login LIKE 'user%'", 'COUNT');
    $usercount = $usercount ? $usercount + 1 : 1;

    return 'user' . $usercount;
}

function gib_part($value, $part, $separator) {
    // Return the part at index $part from $value
    $value_array = explode($separator, $value);

    return $value_array[$part];
}

function cut_string($string, $endchar = '&#8230;', $length = 20, $trim = 1) {
    // alias function for older function
    return getCleanSubString($string, $length, $endchar);
}


function FileExtension($filename) {
    $filename = strrchr(trim($filename), '.');
    return $filename === false ? '' : mb_substr($filename, 1);
}

function convert_into($extension) {
    $extension = strtolower($extension);
    if ($extension === 'avif') {
        return 'avif';
    }
    if (PHPWCMS_WEBP) {
        return 'webp';
    }
    //check which extension to give back
    $ext = 'jpg';
    if (function_exists('imagegif')) {
        switch ($extension) {
            case 'gif':
                $ext = 'gif';
                break;
            case 'png':
                $ext = 'png';
                break;
            case 'webp':
                $ext = 'webp';
                break;
            case 'avif':
                $ext = 'avif';
                break;
        }
    } else {
        switch ($extension) {
            case 'gif':
                $ext = imagetypes() & IMG_GIF ? 'gif' : 'png';
                break;
            case 'png':
                $ext = 'png';
                break;
            case 'webp':
                $ext = 'webp';
                break;
            case 'avif':
                $ext = 'avif';
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
                $ext = (imagetypes() & IMG_GIF) ? 'gif' : 'png';
                break;
            case 'png':
                $ext = 'png';
                break;
            case 'webp':
                static $gd_has_webp = null;
                if ($gd_has_webp === null) {
                    $gd_has_webp = function_exists('imagewebp') && function_exists('imagecreatefromwebp');
                }
                $ext = $gd_has_webp ? 'webp' : 'jpg';
                break;
            case 'avif':
                static $gd_has_avif = null;
                if ($gd_has_avif === null) {
                    $gd_has_avif = function_exists('imageavif') && function_exists('imagecreatefromavif');
                }
                $ext = $gd_has_avif ? 'avif' : 'jpg';
                break;
        }
    } else {
        // if ImageMagick for thumbnail creation
        switch ($extension) {
            case 'jpg':
            case 'jpeg':
            case 'tif':
            case 'tiff':
            case 'psd':
            case 'bmp':
            case 'pic':
            case 'ps2':
            case 'ps3':
            case 'pn':
            case 'wmf':
            case 'tga':
            case 'pict':
            case 'jp2':
            case 'jpc':
            case 'ico':
            case 'fax':
                $ext = 'jpg';
                break;
            case 'eps':
            case 'ps':
            case 'ai':
            case 'png':
            case 'pdf':
                $ext = 'png';
                break;
            case 'gif':
                $ext = 'gif';
                break;
            case 'webp':
                $ext = 'webp';
                break;
            case 'avif':
                $ext = 'avif';
                break;
        }
    }
    if ($ext && !empty($GLOBALS['phpwcms']['imgext_disabled'])) {
        if (is_string($GLOBALS['phpwcms']['imgext_disabled'])) {
            $GLOBALS['phpwcms']['imgext_disabled'] = convertStringToArray(strtolower($GLOBALS['phpwcms']['imgext_disabled']));
        }
        if (in_array($ext, $GLOBALS['phpwcms']['imgext_disabled'], true)) {
            $ext = false;
        }
    }

    return $ext;
}

function make_date($datestring, $dateformat = 'd.m.y') {
    return phpwcms_strtotime($datestring, $dateformat, '');
}

function switch_on_off($wert) {
    //switches the value off->on and on->off
    return (int)$wert ? 0 : 1;
}

function online_users($spacer = '<br />', $wrap = '<span class="useronline">|<span>') {
    $wrap = explode('|', $wrap);
    $users = [];
    if ($result = _dbQuery('SELECT logged_user FROM ' . DB_PREPEND . 'userlog WHERE logged_in=1')) {
        foreach ($result as $user) {
            $users[] = html($user['logged_user']);
        }
    }
    if ($users) {
        return $wrap[0] . implode($spacer, $users) . ($wrap[1] ?? '');
    }

    return '';
}

function get_filecat_childcount($fcatid = 0) {
    return _dbQuery('SELECT COUNT(fkey_id) FROM ' . DB_PREPEND . 'filekey WHERE fkey_deleted=0 AND fkey_cid=' . (int)$fcatid, 'COUNT');
}

/**
 * Test email based on RFC 822
 *
 * @param string $email email address
 *
 * @return bool
 */
function is_valid_email($email) {
    $email = makeCharsetConversion($email, PHPWCMS_CHARSET, 'utf-8');
    $explode = explode('@', $email);
    if (empty($explode[1])) {
        return false;
    }
    $email = $explode[0] . '@' . idn_to_ascii($explode[1]);
    return filter_var($email, FILTER_VALIDATE_EMAIL);
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
        }

        return 'EXPIRED';   // file is too old and expired
    }

    return 'MISSING';       // file not present
}

//added: 09-20-2003
function add_keywords_to_search($list_of_keywords, $keywords, $spacer = ' ', $start_spacer = 1) {
    //adds available keywords to the values used by search engine in file section
    //returns a string
    $kw_string = '';
    if ($keywords && is_array($list_of_keywords) && count($list_of_keywords)) {
        $kw = explode(':', $keywords);
        if (count($kw)) {
            foreach ($kw as $value) {
                list($kw_cat, $kw_id) = explode('_', $value);
                $kw_id = (int)$kw_id;
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
    $file_key = [];
    if ($result = _dbQuery('SELECT fkey_id, fkey_name FROM ' . DB_PREPEND . 'filekey')) {
        foreach ($result as $row) {
            $file_key[(int)$row['fkey_id']] = html($row['fkey_name']);
        }
    }

    return count($file_key) ? $file_key : false;
}

function js_singlequote($t = '') {
    // make singe quotes js compatible
    return str_replace(["\\", '&#92;', "'", '"'], ["\\\\", "\\\\", '&#39;', '&quot;'], (string)$t);
}

function get_tmpl_files($dir = '', $ext = '', $sort = true) {
    //browse a dir and return all template files
    $c = '\.html|\.htm|\.php|\.inc|\.tmpl';
    if ($ext) {
        $ext = explode(',', $ext);
        if (count($ext)) {
            $c = [];
            foreach ($ext as $value) {
                $c[] = '\.' . $value;
            }
            $c = implode('|', $c);
        }
    }
    $regexp = '/(' . $c . ')$/i';
    $fa = []; //file array
    if (is_dir($dir)) {
        $ph = opendir($dir);
        while ($pf = readdir($ph)) {
            if (!str_starts_with($pf, '.') && is_file($dir . '/' . $pf) && preg_match($regexp, $pf)) {
                $fa[] = $pf; //add $pf to file array for current dir
            }
        }
        closedir($ph);
        if ($sort === true && count($fa)) {
            sort($fa);
        }
    }

    return $fa;
}

function get_tmpl_section($s = '', $t = '') {
    // try to return the matching section of template
    // within HTML comments like <!--SECTION_START//-->...<!--SECTION_END//-->
    return preg_match('/<!--' . $s . '_START\/\/-->(.*?)<!--' . $s . '_END\/\/-->/si', $t, $g) ? $g[1] : '';
}

function replace_tmpl_section($s = '', $t = '', $r = '') {
    // try to delete the matching section of template
    // within HTML comments like <!--SECTION_START//-->...<!--SECTION_END//-->
    return preg_replace('/<!--' . $s . '_START\/\/-->(.*?)<!--' . $s . '_END\/\/-->/si', $r, $t);
}

function importedFile_toString($filename = '') {
    $file = [];
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
    $o = [3];
    $order = (int)$order;
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
    $url = stripos($GLOBALS['phpwcms']['site'], 'https') !== 0 ? 'http://' : 'https://';

    // do not trust the client-controlled Host header: when it does not
    // match the configured site host, fall back to the configured site URL
    $siteHost    = parse_url($GLOBALS['phpwcms']['site'], PHP_URL_HOST);
    $requestHost = isset($_SERVER['HTTP_HOST']) ? strtolower($_SERVER['HTTP_HOST']) : '';
    if(empty($requestHost) || ( !empty($siteHost) && strtolower($siteHost) !== $requestHost )) {
        return $GLOBALS['phpwcms']['site'];
    }

    return $url . $_SERVER['HTTP_HOST'] . ( $_SERVER['REQUEST_URI'] ?? '/' );
}

function build_QueryString() {
    // used to build a query string based on given parameters
    // there is no limitation in length
    // first Parameter is the delimtere char
    // build_QueryString('&amp;', 'k=1', 'b=5')
    $numargs = func_num_args();
    $query = [];
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
    $altAndTitle = (int)$altAndTitle;
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

/**
 * Render the standardized system email HTML layout.
 * Table-based, inline CSS only (Gmail/Outlook strip <style> blocks),
 * 600px centered card, auto-branded with site name and URL.
 * Optional branding config keys in conf.inc.php:
 *   $phpwcms['mail_logo_url']   - header logo, https?:// URL or data:image/*;base64 URI
 *                                 (raster only; default: bundled phpwcms PNG as data URI)
 *   $phpwcms['mail_footer']     - custom footer line
 *   $phpwcms['mail_signature']   - closing signature name (fallback: site host)
 *
 * @param string $title Header line shown above the content
 * @param string $contentHtml Inner content markup (paragraphs, buttons, tables)
 * @param string $preheader Optional preview text (hidden summary line)
 * @return string Complete HTML document for sendEmail('html' => ...)
 */
function renderSystemEmailHTML($title, $contentHtml, $preheader = '', ?string $lang = null) {
    global $phpwcms;

    $siteName = empty($phpwcms['site']) ? get_brand_name() : $phpwcms['site'];
    $siteUrl = PHPWCMS_URL;
    $siteLink = '<a href="' . html($siteUrl) . '" style="color:#6c757d;text-decoration:none;">' . html(PHPWCMS_HOST) . '</a>';

    // header: site name, optionally preceded by a logo image
    // mail_logo_url accepts https?:// URLs and data:image/*;base64, URIs
    // (raster formats only — SVG does not render in Outlook desktop);
    $logoUrl = empty($phpwcms['mail_logo_url']) ? '' : $phpwcms['mail_logo_url'];
    if ($logoUrl === '' && is_whitelabel()) {
        $logoUrl = get_brand_email_logo();
    } elseif ($logoUrl === '' && is_file(PHPWCMS_ROOT . '/img/phpwcms-logo.png')) {
        $logoUrl = 'data:image/png;base64,' . base64_encode((string)file_get_contents(PHPWCMS_ROOT . '/img/phpwcms-logo.png'));
    }
    // logo element
    if ($logoUrl !== '' && (preg_match('#^https?://#i', $logoUrl) || preg_match('#^data:image/(png|jpe?g|gif|webp);base64,#i', $logoUrl))) {
        $logoHtml = '<img src="' . html($logoUrl) . '" alt="' . html($siteName) . '" height="50" style="display:block;height:50px;max-width:240px;border:0;" />';
        $headerHtml = '<a href="' . html($siteUrl) . '">' . $logoHtml . '</a>';
    } else {
        $logoHtml = '<span style="font-size:18px;font-weight:bold;color:#212529;">' . html(PHPWCMS_HOST) . '</span>';
        $headerHtml = '<a href="' . html($siteUrl) . '" style="font-size:18px;font-weight:bold;color:#212529;text-decoration:none;">' . html(PHPWCMS_HOST) . '</a>';
    }

    // Check if custom or default mail_header sub-template exists
    if (function_exists('get_system_email_template')) {
        $header_def = get_system_email_template('mail_header', $lang);
        if (!empty($header_def['active']) && !empty($header_def['content_html'])) {
            $h_replacements = [
                '{LOGO}'        => $logoHtml,
                '{SITE}'        => PHPWCMS_HOST,
                '{SITE_URL}'    => $siteUrl,
                '{ADMIN_EMAIL}' => $phpwcms['admin_email'] ?? $phpwcms['SMTP_FROM_EMAIL'] ?? ''
            ];
            $headerHtml = str_replace(array_keys($h_replacements), array_values($h_replacements), $header_def['content_html']);
        }
    }

    // footer: custom line or site link
    $footerHtml = empty($phpwcms['mail_footer']) ? $siteLink : html($phpwcms['mail_footer']);

    // Check if custom or default mail_footer sub-template exists
    if (function_exists('get_system_email_template')) {
        $footer_def = get_system_email_template('mail_footer', $lang);
        if (!empty($footer_def['active']) && !empty($footer_def['content_html'])) {
            $f_replacements = [
                '{SITE}'        => PHPWCMS_HOST,
                '{SITE_URL}'    => $siteUrl,
                '{ADMIN_EMAIL}' => $phpwcms['admin_email'] ?? $phpwcms['SMTP_FROM_EMAIL'] ?? ''
            ];
            $footerHtml = str_replace(array_keys($f_replacements), array_values($f_replacements), $footer_def['content_html']);
        }
    }

    // Check if custom or default mail_layout template exists
    $layout_tpl = '';
    if (function_exists('get_system_email_template')) {
        $layout_def = get_system_email_template('mail_layout', $lang);
        if (!empty($layout_def['active']) && !empty($layout_def['content_html'])) {
            $layout_tpl = $layout_def['content_html'];
        }
    }

    if ($layout_tpl !== '') {
        $replacements = [
            '{PREHEADER}' => html($preheader),
            '{HEADER}'    => $headerHtml,
            '{TITLE}'     => html($title),
            '{CONTENT}'   => $contentHtml,
            '{FOOTER}'    => $footerHtml,
            '{SITE}'      => PHPWCMS_HOST,
            '{SITE_URL}'  => $siteUrl,
            '{ADMIN_EMAIL}' => $phpwcms['admin_email'] ?? $phpwcms['SMTP_FROM_EMAIL'] ?? ''
        ];
        return str_replace(array_keys($replacements), array_values($replacements), $layout_tpl);
    }

    return '<!DOCTYPE html>
<html>
<body style="margin:0;padding:0;background-color:#f4f5f7;">
<div style="display:none;font-size:1px;color:#f4f5f7;max-height:0;overflow:hidden;">' . html($preheader) . '</div>
<table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background-color:#f4f5f7;padding:24px 12px;">
<tr><td align="center">
<table role="presentation" width="600" cellpadding="0" cellspacing="0" style="width:600px;max-width:600px;background-color:#ffffff;border-radius:8px;">
<tr><td style="padding:24px 32px 12px;font-family:system-ui,-apple-system,BlinkMacSystemFont,\'Segoe UI\',Roboto,\'Helvetica Neue\',Arial,sans-serif;">
    ' . $headerHtml . '
</td></tr>
<tr><td style="padding:0 32px;border-bottom:1px solid #e9ecef;"><div style="height:1px;line-height:1px;font-size:0;">&nbsp;</div></td></tr>
<tr><td style="padding:24px 32px;font-family:system-ui,-apple-system,BlinkMacSystemFont,\'Segoe UI\',Roboto,\'Helvetica Neue\',Arial,sans-serif;font-size:15px;line-height:1.6;color:#212529;">
    <div style="font-size:20px;font-weight:bold;color:#212529;margin-bottom:16px;">' . html($title) . '</div>
    ' . $contentHtml . '
</td></tr>
<tr><td style="padding:16px 32px 24px;font-family:system-ui,-apple-system,BlinkMacSystemFont,\'Segoe UI\',Roboto,\'Helvetica Neue\',Arial,sans-serif;font-size:12px;line-height:1.5;color:#6c757d;border-top:1px solid #e9ecef;">
    ' . $footerHtml . '
</td></tr>
</table>
</td></tr>
</table>
</body>
</html>';
}

/**
 * Render the localized closing signature for system email HTML bodies.
 * Uses $BL['email_regards'] when available, site host as the signer name.
 *
 * @return string
 */
function renderEmailSignatureHTML() {
    global $phpwcms, $BL;
    $regards = $BL['email_regards'] ?? 'Regards,';
    $signer = empty($phpwcms['mail_signature']) ? PHPWCMS_HOST : $phpwcms['mail_signature'];
    return '<p>' . html($regards) . '<br />' . html($signer) . '</p>';
}

/**
 * Render a CTA button for system email HTML bodies.
 * Bulletproof padding-based layout so it renders in Outlook too.
 *
 * @param string $url Absolute link target
 * @param string $label Button caption
 * @return string
 */
function renderEmailButtonHTML($url, $label) {
    return '<div style="margin:24px 0;"><a href="' . html($url) . '" style="display:inline-block;background-color:#0d6efd;color:#ffffff;font-family:system-ui,-apple-system,BlinkMacSystemFont,\'Segoe UI\',Roboto,\'Helvetica Neue\',Arial,sans-serif;font-size:15px;font-weight:bold;text-decoration:none;padding:12px 28px;border-radius:6px;">' . html($label) . '</a></div>
<div style="font-family:system-ui,-apple-system,BlinkMacSystemFont,\'Segoe UI\',Roboto,\'Helvetica Neue\',Arial,sans-serif;font-size:12px;line-height:1.5;color:#6c757d;word-break:break-all;">' . html($url) . '</div>';
}

/**
 * Render a key/value field table for system email HTML bodies.
 *
 * @param array $rows Associative array label => value
 * @return string
 */
function renderEmailFieldTableHTML($rows) {
    $html = '<table role="presentation" cellpadding="0" cellspacing="0" style="margin:16px 0;width:100%;border-collapse:collapse;font-family:system-ui,-apple-system,BlinkMacSystemFont,\'Segoe UI\',Roboto,\'Helvetica Neue\',Arial,sans-serif;font-size:14px;">';
    foreach ($rows as $label => $value) {
        $html .= '<tr>'
            . '<td style="padding:8px 12px;border:1px solid #dee2e6;color:#6c757d;width:40%;">' . html($label) . '</td>'
            . '<td style="padding:8px 12px;border:1px solid #dee2e6;color:#212529;font-weight:bold;word-break:break-all;">' . html($value) . '</td>'
            . '</tr>';
    }
    $html .= '</table>';
    return $html;
}

function sendEmail($data = [
    'recipient' => '',
    'toName' => '',
    'subject' => '',
    'isHTML' => false,
    'html' => '',
    'text' => '',
    'attach' => [],
    'from' => '',
    'fromName' => '',
    'sender' => '',
    'senderName' => '',
    'stringAttach' => [],
]) {
    // used to send a standardized email message
    global $phpwcms;
    $mailInfo = [0 => false, 1 => ''];
    $sendTo = [];
    $from = empty($data['from']) || !is_valid_email($data['from']) ? $phpwcms['SMTP_FROM_EMAIL'] : $data['from'];
    $sender = empty($data['sender']) || !is_valid_email($data['sender']) ? $from : $data['sender'];
    $fromName = empty($data['fromName']) ? '' : cleanUpForEmailHeader(i18n_substitute_text($data['fromName']));
    $senderName = empty($data['senderName']) ? $fromName : cleanUpForEmailHeader(i18n_substitute_text($data['senderName']));
    $toName = empty($data['toName']) ? '' : cleanUpForEmailHeader(i18n_substitute_text($data['toName']));
    $subject = empty($data['subject']) ? 'Email sent by ' . get_brand_name() : cleanUpForEmailHeader(i18n_substitute_text($data['subject']));
    if (empty($data['html'])) {
        $data['html'] = '';
        $data['isHTML'] = false;
    } elseif (empty($data['isHTML'])) {
        $data['isHTML'] = false;
    } else {
        $data['isHTML'] = true;
    }
    if (empty($data['text'])) {
        $data['text'] = '';
    }
    if ($data['html'] !== '') {
        $data['html'] = i18n_substitute_text($data['html']);
    }
    if ($data['text'] !== '') {
        $data['text'] = i18n_substitute_text($data['text']);
    }
    if (!is_array($data['recipient'])) {
        $recipient = str_replace([' ', ',', ' '], ['', ';', ''], trim($data['recipient']));
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
        $mail = new PhpwcmsMailer($phpwcms);
        // always send as UTF-8, regardless of a legacy install charset —
        // convert body and subject so mail clients never see mixed encodings
        if (PHPWCMS_CHARSET !== 'utf-8') {
            $mail->CharSet = 'UTF-8';
            if (!phpwcms_seems_utf8($data['html'])) {
                $data['html'] = makeCharsetConversion($data['html'], PHPWCMS_CHARSET, 'UTF-8');
            }
            if (!phpwcms_seems_utf8($data['text'])) {
                $data['text'] = makeCharsetConversion($data['text'], PHPWCMS_CHARSET, 'UTF-8');
            }
            if (!phpwcms_seems_utf8($subject)) {
                $subject = makeCharsetConversion($subject, PHPWCMS_CHARSET, 'UTF-8');
            }
        }
        $mail->isHTML($data['isHTML']);
        $mail->Subject = $subject;
        if ($data['isHTML']) {
            if ($data['text'] !== '') {
                $mail->AltBody = $data['text'];
            } else {
                $altBody = new \Html2Text\Html2Text($data['html']);
                $mail->AltBody = $altBody->getText();
            }
            $mail->Body = $data['html'];
        } else {
            $mail->Body = $data['text'];
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
                    $mail->addStringAttachment(
                        $attach_string['data'],
                        $attach_string['filename'],
                        $attach_string['encoding'],
                        $attach_string['mime']
                    );
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

function getFormTrackingSalt() {
    if (defined('PHPWCMS_FORM_SALT') && PHPWCMS_FORM_SALT !== '') {
        return (string)PHPWCMS_FORM_SALT;
    }
    if (!empty($GLOBALS['phpwcms']['form_salt'])) {
        return (string)$GLOBALS['phpwcms']['form_salt'];
    }
    return (string)($GLOBALS['phpwcms']['db_pass'] ?? 'phpwcms_form_token');
}

function getFormTrackingValue() {
    // creates a new form tracking entry in database
    // returns a <input type="hidden">
    $ip = getRemoteIP();
    $salt = getFormTrackingSalt();
    $hash = md5($ip . $salt . date('G'));
    $entry_id = time();
    if (!empty($GLOBALS['phpwcms']['form_tracking']) && !PHPWCMS_GDPR_MODE) {
        $sql = 'INSERT INTO ' . DB_PREPEND . 'formtracking SET formtracking_hash=' . _dbEscape($hash) . ', formtracking_ip=' . _dbEscape($ip);
        $result = _dbQuery($sql, 'INSERT');
        if (isset($result['INSERT_ID'])) {
            $entry_id = $result['INSERT_ID'];
        }
    }

    return '<input type="hidden" name="' . $hash . '" value="' . $entry_id . '">';
}

/**
 * Check that the form was submitted through a valid form tracking
 * field rendered by renderFormTrackingHiddenField() — the field name
 * is a hash of IP, salt and hour, valid for the current and previous hour.
 *
 * @return bool
 */
function checkFormTrackingValue() {
    $ip = getRemoteIP();
    $salt = getFormTrackingSalt();
    $hash1 = md5($ip . $salt . date('G'));
    $hash2 = md5($ip . $salt . date('G', time() - 3600)); // max form delay of 1 hour
    if (isset($_POST[$hash1])) {
        unset($_POST[$hash1]);
        return true;
    }
    if (isset($_POST[$hash2])) {
        unset($_POST[$hash2]);
        return true;
    }

    return false;
}

function cleanUpSpecialHtmlEntities($string = '') {
    return decode_entities($string);
}

function cleanUpFormMailerPostValue($string = '') {
    if (str_contains("\n", $string)) {
        return '';
    }
    $string = clean_slweg($string);

    return decode_entities($string);
}

function cleanUpForEmailHeader($text = '') {
    list($text) = explode("\n", $text);
    list($text) = explode('%0D', $text);
    list($text) = explode('%0d', $text);
    list($text) = explode("\r", $text);
    list($text) = explode('%0A', $text);
    list($text) = explode('%0a', $text);
    $spam = ['/bcc:/i', '/cc:/i', '/to:/i', '/from:/i', '/mime-version:/i', '/reply-to:/i'];
    $text = preg_replace($spam, '', $text);

    return trim($text);
}

function getCleanSubString($cutString = '', $maxLength = 0, $moreChar = '', $cutMode = 'char', $sanitize = null) {
    // used to cut a string by words or chars
    if (empty($maxLength) || $maxLength < 0) {
        return $cutString;
    }
    if ($cutMode === 'word') {
        $words = preg_split('/\s+/', $cutString, -1, PREG_SPLIT_NO_EMPTY);
        $cutString = '';
        for ($i = 0; $i < $maxLength; $i++) {
            if (!empty($words[$i])) {
                $cutString .= $words[$i] . ' ';
            }
        }
        $cutString = trim($cutString);
        if ($moreChar && count($words) > $maxLength) {
            $cutString .= $moreChar;
        }
    } else {
        $curString = trim($cutString);
        if ($curString == '') {
            return '';
        }

        if ($sanitize === null && $maxLength >= mb_strlen($curString)) {
            return $curString;
        }
        $match_res = @preg_match_all('/&[^;]+;|./u', $curString, $match);
        if ($match_res === false || empty($match[0])) {
            preg_match_all('/&[^;]+;|./', $curString, $match);
        }
        if (is_array($match[0]) && count($match[0]) > $maxLength) {
            $match[0] = array_slice($match[0], 0, $maxLength);
            $cutString = trim(implode('', $match[0]));
            $cutString .= $moreChar;
        }
    }
    if ($sanitize !== null && $cutString) {
        $cutString = htmlfilter_sanitize($cutString, [], [], ['img', 'br', 'hr', 'input'], true);
    }

    return $cutString;
}

function headerAvoidPageCaching() {
    header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
    header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');
    header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
    header('Cache-Control: post-check=0, pre-check=0', false);
    header('Pragma: no-cache');
}

function getFileInformation($fileID) {
    if (empty($fileID)) {
        return false;
    }
    if (is_array($fileID)) {
        if (!count($fileID)) {
            return false;
        }
        $f = [];
        foreach ($fileID as $value) {
            $f[] = 'f_id=' . (int)$value;
        }
        $f = implode(' OR ', $f);
    } elseif ((int)$fileID) {
        $f = 'f_id=' . (int)$fileID;
    } else {
        return false;
    }
    $sql = 'SELECT * FROM ' . DB_PREPEND . 'file WHERE f_public=1 AND f_aktiv=1 AND f_kid=1 AND f_trash=0 AND (' . $f . ')';

    return _dbQuery($sql);
}

function getJavaScriptSourceLink($src, $prefix = '  ') {
    return ($src) ? $prefix . '<script' . SCRIPT_ATTRIBUTE_TYPE . ' src="' . $src . '"></script>' : '';
}

function getJavaScriptTranslations() {
    global $BL;
    $translations = [
        'cancel' => $BL['modal_cancel'] ?? 'Cancel',
        'ok' => $BL['modal_ok'] ?? 'OK',
        'confirm' => $BL['modal_confirm'] ?? 'Confirm',
        'titleConfirm' => $BL['modal_title_confirm'] ?? 'Confirmation',
        'titleAlert' => $BL['modal_title_alert'] ?? 'Information',
        'delete' => $BL['modal_delete'] ?? 'Delete',
        'move' => $BL['modal_move'] ?? 'Move',
        'copy' => $BL['modal_copy'] ?? 'Copy',
        'flush' => $BL['modal_flush'] ?? 'Flush',
        'editorFullscreen' => $BL['be_editor_fullscreen'] ?? 'Fullscreen',
        'editorWordWrap' => $BL['be_editor_wordwrap'] ?? 'Word wrap'
    ];

    return '<script' . SCRIPT_ATTRIBUTE_TYPE . '>window.PHPWCMS_LANG = ' . json_encode($translations, JSON_UNESCAPED_UNICODE) . ';</script>';
}

function convertStringToArray($string = '', $seperator = ',', $mode = 'UNIQUE', $rmvDblWSp = true) {
    // clean up a separator-separated string and return as array
    if (trim($string) === '') {
        return [];
    }
    // replace all duplicate white chars by single space
    if ($rmvDblWSp) {
        $string = preg_replace('/\s\s+/', ' ', $string);
    }
    $string = explode($seperator, $string);
    $string = array_map('trim', $string);
    $string = array_diff($string, ['', null, false]);
    if ($mode === 'UNIQUE') {
        $string = array_unique($string);
    }

    return $string;
}

function get_unique_array($array, $diff= ['', null, false]) {
    if ($diff) {
        $array = array_diff($array, $diff);
    }
    return array_unique($array);
}

function decode_entities($text, $flags = null, $encoding = 'UTF-8') {
    if (!is_string($text) || $text === '' || !str_contains($text, '&')) {
        return (string)$text;
    }

    return \voku\helper\UTF8::html_entity_decode($text, $flags, $encoding);
}

/**
 * Clean HTML tags, convert to UTF-8 from source charset, and decode entities
 *
 * @param mixed $string
 * @param string|null $in_charset Source charset, defaults to PHPWCMS_CHARSET or 'utf-8'
 * @param string|array<string>|null $allowable_tags Optional tags to preserve during strip_tags
 * @return string
 */
function clean_plain_utf8($string = '', $in_charset = null, $allowable_tags = null) {
    if (!is_string($string) || $string === '') {
        return is_scalar($string) ? (string)$string : '';
    }

    $string = phpwcms_strip_tags($string, $allowable_tags);

    $in_charset = $in_charset !== null && $in_charset !== ''
        ? strtolower($in_charset)
        : (defined('PHPWCMS_CHARSET') ? PHPWCMS_CHARSET : 'utf-8');

    if ($in_charset !== 'utf-8') {
        if (function_exists('makeCharsetConversion')) {
            $string = makeCharsetConversion($string, $in_charset, 'utf-8');
        } elseif (class_exists(\voku\helper\UTF8::class)) {
            $string = \voku\helper\UTF8::encode('utf-8', $string, false, $in_charset);
        }
    }

    return decode_entities($string);
}

function convertHexNumericToChar($matches) {
    return convertDecChar(hexdec($matches[1]));
}

function convertNumericToChar($matches) {
    return convertDecChar($matches[1]);
}

function convertDecChar($decChar) {
    return \voku\helper\UTF8::chr((int)$decChar);
}

function is_html($string = '') {
    if (!is_string($string) || $string === '') {
        return false;
    }

    return \voku\helper\UTF8::is_html($string) || (str_contains($string, '&') && \voku\helper\UTF8::html_entity_decode($string) !== $string);
}

function stripped_cache_content($page = '') {
    // clean up html page
    $page = preg_replace('@<script[^>]*?>.*?</script>@si', '', (string)$page);
    $page = str_replace('><', '> <', $page);
    $page = phpwcms_strip_tags($page);

    return preg_replace('/\s+/s', ' ', decode_entities($page));
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
        $text = decode_entities($text);
        $text = str_replace(
            [
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
            ],
            ' ',
            $text
        );
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
    $val = ceil((float) $val);
    if ($val >= (1024 * 1024 * 1024 * 1024)) {
        // T
        $val = round($val / (1024 * 1024 * 1024 * 1024), $round);
        $val .= $space . 'T' . $byte;
    } elseif ($val >= (1024 * 1024 * 1024)) {
        // G
        $val = round($val / (1024 * 1024 * 1024), $round);
        $val .= $space . 'G' . $byte;
    } elseif ($val >= (1024 * 1024)) {
        // M
        $val = round($val / (1024 * 1024), $round);
        $val .= $space . 'M' . $byte;
    } elseif ($val >= 1024) {
        // K
        $val = round($val / 1024, $round);
        $val .= $space . 'K' . $byte;
    } else {
        // $val < 1024
        // Byte but as 0.xxx KB
        $val = round($val / 1024, $round + 1);
        $val .= $space . 'K' . $byte;
    }

    return $val;
}

/**
 * Convert PHP ini shorthand size values (128M, 2G, 512K)
 * to bytes. Thin wrapper around getBytes(), kept for the
 * ini-get use case it was originally written for.
 *
 * @param mixed $val Size shorthand or numeric value
 * @return float|int|string
 */
function return_bytes($val) {
    return getBytes($val);
}

function return_upload_errormsg($value) {
    $err = '';
    switch ($value) {
        case 0:
            break;
        case 1:
            $err = 'The uploaded file exceeds the upload_max_filesize directive (' . @ini_get('upload_max_filesize') . ') in php.ini.';
            break;
        case 2:
            $err = 'The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form.';
            break;
        case 3:
            $err = 'The uploaded file was only partially uploaded.';
            break;
        case 4:
            $err = 'No file was uploaded.';
            break;
        case 6:
            $err = 'Missing a temporary folder.';
            break;
        case 7:
            $err = 'Failed to write file to disk';
            break;
        default:
            $err = 'Unknown file upload error';
    }

    return $err;
}

function csvFileToArray($csvfile, $delimiter = ';', $heading = false, $enclosure = '"', $linelength = 1000) {
    //import CSV file and convert to array
    if (!is_file($csvfile)) {
        return false;
    }
    $first = 0;
    $datas = [];
    $phpver = version_compare('4.3.0', PHP_VERSION, '<');
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
                $datas[0][$key] = $value ?: 'Column' . $key;
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
    $string = str_replace(
        ['{SITE}', '{PHPWCMS_URL}', '{PHPWCMS_TEMPLATE}', '{IP}'],
        [PHPWCMS_URL, PHPWCMS_URL, TEMPLATE_PATH, PHPWCMS_GDPR_MODE ? getAnonymizedIp() : getRemoteIP()],
        $string
    );

    return renderRTDate($string);
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
        $matches[1] = (int)$matches[1];

        return $type($matches[0], $matches[1]);
    }

    return $type($matches[0], phpwcms_strtotime($matches[1], null, now()));
}

function makeCharsetConversion($string = '', $in_charset = 'utf-8', $out_charset = 'utf-8', $entityEncode = false) {
    if (empty($string)) {
        return $string;
    }
    $in_charset = strtolower($in_charset);
    $out_charset = strtolower($out_charset);
    if (($in_charset === $out_charset && !$entityEncode) || empty($in_charset) || empty($out_charset)) {
        return $string;
    }
    if ($in_charset !== $out_charset) {
        $string = \voku\helper\UTF8::encode($out_charset, $string, false, $in_charset);
    }
    if ($entityEncode) {
        $utf8_str = ($out_charset === 'utf-8') ? $string : \voku\helper\UTF8::to_utf8($string);
        $string = \voku\helper\UTF8::encode('HTML', $utf8_str);
    }

    return $string;
}

function returnSubdirListAsArray($dir = '') {
    // browse a given path and return all sub directories
    if (empty($dir) || !is_dir($dir)) {
        return false;
    }
    $subdir = [];
    $ph = opendir($dir);
    while ($pf = readdir($ph)) {
        if (!str_starts_with($pf, '.') && is_dir($dir . '/' . $pf)) {
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
    $files = [];
    $ph = opendir($dir);
    if (empty($extfilter)) {
        $extfilter = [];
    } elseif (is_string($extfilter)) {
        $extfilter = convertStringToArray(strtolower(trim($extfilter)));
    } elseif (!is_array($extfilter)) {
        $extfilter = [];
    }
    $dofilter = count($extfilter);
    while ($pf = readdir($ph)) {
        if (!str_starts_with($pf, '.') && is_file($dir . '/' . $pf)) {
            $ext = which_ext($pf);
            if ($dofilter && !in_array($ext, $extfilter, true)) {
                continue;
            }
            $files[$pf] = [
                'filename' => $pf,
                'filesize' => filesize($dir . '/' . $pf),
                'filetime' => filemtime($dir . '/' . $pf),
                'ext' => $ext,
                'path' => $dir . '/' . $pf
            ];
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
    $Data = [];
    $Escape = [
        'search' => ['\t', '\r', '\n', '\;', '\#', '\=', '\:', "\\\\"],
        'replace' => ["\t", "\r", "\n", ';', '#', '=', ':', "\\"],
    ];
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
                        $Data[$Section] = [];
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
                        $Value = [];
                        $Value['NAME'] = trim(mb_substr($Temp, 0, $Pos));
                        $Value['VALUE'] = str_replace($Escape['search'], $Escape['replace'], trim(mb_substr($Temp, $Pos + 1), ' "'));
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
    if (str_starts_with($domain, 'www')) {
        $domain = substr($domain, 3);
    }
        $GLOBALS['phpwcms']['parse_url']['host'] = $domain;
    }
    return $GLOBALS['phpwcms']['parse_url']['host'];
}

function _mkdir($target) {
    // taken from WordPress
    if (file_exists($target)) { // from php.net/mkdir user contributed notes
        return @is_dir($target);
    }
    umask(0);
    if (mkdir($target) || is_dir($target)) {   // Attempting to create the directory may clutter up our display.
        $stat = @stat(dirname($target));
        $dir_perms = $stat['mode'] & 0007777;  // Get the permission bits.
        @chmod($target, $dir_perms);

        return true;
    }

    if (is_dir(dirname($target))) {
        return false;
    }

    if (_mkdir(dirname($target))) { // If the above failed, attempt to create the parent node, then try again.
        return _mkdir($target);
    }

    return false;
}

/**
 * Normalize user/group member ids to a unique array of integers.
 * Accepts an array (e.g. $_POST values) or a comma-separated string
 * (e.g. a group_member column value). Non-numeric values are dropped.
 *
 * @access public
 * @param array|string $ids Raw id values or CSV string.
 * @return array Unique integer ids.
 */
function sanitize_int_array($ids)
{
    if (!is_array($ids)) {
        $ids = convertStringToArray($ids);
    }
    $ids = array_map('intval', array_filter($ids, 'is_numeric'));
    return array_values(array_unique($ids));
}

function sanitize_filename($filename) {
    if (class_exists('Normalizer')) {
        $filename = Normalizer::normalize($filename, Normalizer::FORM_C);
    } else {
        // Comprehensive fallback for NFD decomposed Latin characters if ext-intl is missing
        $nfd_map = [
            // Umlauts / Diaeresis (\xCC\x88)
            "a\xCC\x88" => 'ä', "o\xCC\x88" => 'ö', "u\xCC\x88" => 'ü',
            "A\xCC\x88" => 'Ä', "O\xCC\x88" => 'Ö', "U\xCC\x88" => 'Ü',
            "e\xCC\x88" => 'ë', "E\xCC\x88" => 'Ë', "i\xCC\x88" => 'ï', "I\xCC\x88" => 'Ï', "y\xCC\x88" => 'ÿ', "Y\xCC\x88" => 'Ÿ',
            // Acute accents (\xCC\x81)
            "a\xCC\x81" => 'á', "e\xCC\x81" => 'é', "i\xCC\x81" => 'í', "o\xCC\x81" => 'ó', "u\xCC\x81" => 'ú', "y\xCC\x81" => 'ý', "c\xCC\x81" => 'ć', "n\xCC\x81" => 'ń', "s\xCC\x81" => 'ś', "z\xCC\x81" => 'ź',
            "A\xCC\x81" => 'Á', "E\xCC\x81" => 'É', "I\xCC\x81" => 'Í', "O\xCC\x81" => 'Ó', "U\xCC\x81" => 'Ú', "Y\xCC\x81" => 'Ý', "C\xCC\x81" => 'Ć', "N\xCC\x81" => 'Ń', "S\xCC\x81" => 'Ś', "Z\xCC\x81" => 'Ź',
            // Grave accents (\xCC\x80)
            "a\xCC\x80" => 'à', "e\xCC\x80" => 'è', "i\xCC\x80" => 'ì', "o\xCC\x80" => 'ò', "u\xCC\x80" => 'ù',
            "A\xCC\x80" => 'À', "E\xCC\x80" => 'È', "I\xCC\x80" => 'Ì', "O\xCC\x80" => 'Ò', "U\xCC\x80" => 'Ù',
            // Circumflex (\xCC\x82)
            "a\xCC\x82" => 'â', "e\xCC\x82" => 'ê', "i\xCC\x82" => 'î', "o\xCC\x82" => 'ô', "u\xCC\x82" => 'û',
            "A\xCC\x82" => 'Â', "E\xCC\x82" => 'Ê', "I\xCC\x82" => 'Î', "O\xCC\x82" => 'Ô', "U\xCC\x82" => 'Û',
            // Tilde (\xCC\x83)
            "n\xCC\x83" => 'ñ', "a\xCC\x83" => 'ã', "o\xCC\x83" => 'õ',
            "N\xCC\x83" => 'Ñ', "A\xCC\x83" => 'Ã', "O\xCC\x83" => 'Õ',
            // Cedilla (\xCC\xA7)
            "c\xCC\xA7" => 'ç', "C\xCC\xA7" => 'Ç', "s\xCC\xA7" => 'ş', "S\xCC\xA7" => 'Ş',
            // Ring (\xCC\x8A)
            "a\xCC\x8A" => 'å', "A\xCC\x8A" => 'Å'
        ];
        $filename = strtr($filename, $nfd_map);
    }
    $remove = [
        '?',
        '[',
        ']',
        '/',
        "\\",
        '=',
        '<',
        '>',
        ':',
        ';',
        ',',
        "'",
        '"',
        '&',
        '$',
        '#',
        '*',
        '(',
        ')',
        '|',
        '~',
        '`',
        '!',
        '{',
        '}',
    ];
    // Strip control characters (0x00-0x1F, 0x7F)
    for ($i = 0; $i < 32; $i++) {
        $remove[] = chr($i);
    }
    $remove[] = chr(127);

    $filename = str_replace($remove, '', $filename);
    $filename = preg_replace('/[ \t\n\r\f\v]+/', ' ', $filename);

    return trim($filename, " \t\n\r\0\x0B.");
}

/**
 * Sanitize string which will be written to set the value of a PHP variable.
 * Should avoid users from bypassing it and execute PHP code.
 *
 * @param string $text
 *
 * @return string
 */
function sanitize_quote_backslash($text) {
    return str_replace(['\\', "'"], ["\\\\", "\\'"], $text);
}

/**
 * Sanitize a file name used as path component inside a fixed directory.
 * Prevents path traversal (separators, absolute paths, null bytes) in
 * template- and file name fields stored by content part forms.
 * Unicode letters and numbers are kept, so existing installations
 * using e.g. umlauts in template file names keep working.
 *
 * @param string $name
 *
 * @return string
 */
function sanitize_template_name($name) {
    $name = (string) $name;
    if ($name === '' || $name === '.' || $name === '..' || str_contains($name, "\0")) {
        return '';
    }
    if (basename($name) !== $name) {
        return '';
    }
    return preg_match('/^[\p{L}\p{N}._ -]+$/u', $name) ? $name : '';
}

/**
 * Check that a resolved path is the given root directory or inside it.
 * Compares on path separator boundaries, so a sibling directory sharing
 * the same prefix (e.g. /var/www/site-evil for root /var/www/site) is
 * not treated as contained.
 *
 * @param string $path
 * @param string $root
 *
 * @return bool
 */
function path_is_within($path, $root) {
    $path = rtrim(str_replace('\\', '/', (string) $path), '/');
    $root = rtrim(str_replace('\\', '/', (string) $root), '/');
    if ($path === '' || $root === '') {
        return false;
    }
    return $path === $root || str_starts_with($path, $root . '/');
}

function saveUploadedFile($file, $target, $exttype = '', $imgtype = '', $rename = 0, $maxsize = 0) {
    // imgtype can be all exif_imagetype supported by your PHP install
    // see http://www.php.net/exif_imagetype
    $file_status = [
        'status' => false,
        'error' => '',
        'name' => '',
        'tmp_name' => '',
        'size' => 0,
        'path' => '',
        'ext' => '',
        'rename' => '',
        'maxsize' => (int)$maxsize,
        'error_num' => 0,
        'type' => '',
    ];
    if (!isset($_FILES[$file]) || !is_uploaded_file($_FILES[$file]['tmp_name'])) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && empty($_POST) && empty($_FILES) && isset($_SERVER['CONTENT_LENGTH']) && (int)$_SERVER['CONTENT_LENGTH'] > 0) {
            $max_post = ini_get('post_max_size');
            $file_status['error'] = !empty($GLOBALS['BL']['be_fprivup_err10']) ? sprintf($GLOBALS['BL']['be_fprivup_err10'], $max_post) : "The uploaded file or request exceeds the server limit (post_max_size: $max_post). Please upload a smaller file.";
            $file_status['error_num'] = 413;
        } else {
            $file_status['error'] = !empty($GLOBALS['BL']['be_fprivup_err1']) ? $GLOBALS['BL']['be_fprivup_err1'] : 'Upload not defined';
        }

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
    if ((int)$file_status['size'] > $file_status['maxsize']) {
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
    if (empty($file_status['ext']) || !in_array($file_status['ext'], $GLOBALS['phpwcms']['allowed_upload_ext'], true)) {
        $file_status['error'] = 'The file with extension *.' . $file_status['ext'] . ' is not allowed or invalid for uploading';
        $file_status['error_num'] = 415;
        @unlink($_FILES[$file]['tmp_name']);

        return $file_status;
    }
    if ($imgtype) {
        $imgtype = convertStringToArray(strtolower($imgtype));
        if (count($imgtype)) {
            $data = @getimagesize($_FILES[$file]['tmp_name']);
            $exif_imagetype = [
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
                18 => 'webp',
                19 => 'avif'
            ];
            if (!$data && !$exttype) {
                $file_status['error'] = 'Format *.' . $file_status['ext'] . ' not supported (';
                $allowed = [];
                foreach ($imgtype as $value) {
                    if (($value = (int)$value) && isset($exif_imagetype[$value])) {
                        $allowed[] = '*.' . $exif_imagetype[$value];
                    }
                }
                $file_status['error'] .= implode(', ', $allowed) . ')';
                $file_status['error_num'] = 415;
                @unlink($_FILES[$file]['tmp_name']);

                return $file_status;
            }

            if ($data) {
                if (empty($exif_imagetype[$data[2]]) || !in_array($data[2], $imgtype, true)) {
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
        if (!in_array($file_status['ext'], $exttype, true)) {
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
                    $_temp_name = str_replace([':', '/', "\\", ' '], ['-', '-', '-', '_'], phpwcms_remove_accents($_temp_name));
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
                    $_temp_name = md5($_temp_name . '.' . $file_status['ext']);
                    break;
                case 7:
                    $_temp_name = shortHash($_temp_name . '.' . $file_status['ext']);
                    break;
            }
        }
        $file_status['rename'] = $_temp_name . '.' . $file_status['ext'];
    }
    @umask(0);
    if (!@move_uploaded_file($_FILES[$file]['tmp_name'], $target . $file_status['rename']) && !copy($_FILES[$file]['tmp_name'], $target . $file_status['rename'])) {
        $file_status['error'] = 'Saving uploaded file <b>' . html($file_status['name']) . '</b> to <b>' . html(str_replace(PHPWCMS_ROOT, '', $target . $file_status['rename'])) . '</b> failed';
        $file_status['error_num'] = 412;
        @unlink($_FILES[$file]['tmp_name']);

        return $file_status;
    }
    @chmod($target . $file_status['rename'], 0644);
    $file_status['status'] = true;

    return $file_status;
}

/**
 * Convert string to alphanumeric characters and dashes
 *
 * @param string $string
 * @param bool $remove_accents
 * @param string $replace_space
 * @param bool $allow_slashes
 * @return string
 */
function get_alnum_dashes($string, $remove_accents = false, $replace_space = '-', $allow_slashes = false) {
    if (!is_string($string) || $string === '') {
        return '';
    }

    $string = \voku\helper\UTF8::cleanup($string);
    $replace_chars = [' ', '?', ':', '&', '\\', ';', '#', '=', '+'];
    if (!$allow_slashes) {
        $replace_chars[] = '/';
    }
    $string = str_replace($replace_chars, $replace_space, $string);
    if ($remove_accents) {
        $string = phpwcms_remove_accents($string);
        $string = preg_replace('/[^a-z0-9\-_\.' . ($allow_slashes ? '\/' : '') . ']/i', '', (string)$string);
    } else {
        $string = preg_replace('/[^\p{L}\p{N}\-_\.' . ($allow_slashes ? '\/' : '') . ']/iu', '', (string)$string);
    }

    if ($allow_slashes) {
        $string = preg_replace('/\/+/', '/', (string)$string);
        $string = preg_replace('/[\-_]*\/+[\-_]*/', '/', (string)$string);
    }
    $string = preg_replace('/\-\-+/', $replace_space, (string)$string);
    $string = preg_replace('/__+/', '_', (string)$string);

    return trim((string)$string, '._/-' . $replace_space);
}

/**
 * Sanitize input against XSS attacks using voku/anti-xss
 *
 * @param string|array<mixed> $val
 * @return string|array<mixed>
 */
function xss_clean($val) {
    if (empty($val)) {
        return $val;
    }

    static $antiXss = null;
    if ($antiXss === null) {
        $antiXss = new \voku\helper\AntiXSS();
    }

    return $antiXss->xss_clean($val);
}

function sanitize_multiple_emails($string) {
    $string = preg_replace('/[\s,]+/', ';', $string);
    $string = convertStringToArray($string, ';');

    return implode(';', $string);
}

function checkLogin($mode = 'REDIRECT') {
    $sql = 'UPDATE ' . DB_PREPEND . 'userlog SET logged_in=0, logged_change=' . _dbEscape(time()) . ' ';
    $sql .= 'WHERE logged_in=1 AND (' . time() . '-logged_change) > ' . (int)$GLOBALS['phpwcms']['max_time'];
    _dbQuery($sql, 'UPDATE');
    checkLoginCount();
    if (empty($_SESSION['wcs_user']) || empty($_SESSION['PHPWCMS_BROWSER_HASH']) || $_SESSION['PHPWCMS_BROWSER_HASH'] !== $GLOBALS['phpwcms']['USER_AGENT']['hash']) {
        $_SESSION = [];
        @session_destroy();
        if (!empty($_SERVER['QUERY_STRING'])) {
            $ref_url = '?ref=' . rawurlencode(PHPWCMS_URL . 'phpwcms.php?' . xss_clean($_SERVER['QUERY_STRING']));
        } else {
            $ref_url = '';
        }
        if ($mode === 'REDIRECT') {
            // check again if user was logged in and this is a valid redirect request
            $sql = 'SELECT COUNT(*)  FROM ' . DB_PREPEND . 'userlog WHERE ';
            $sql .= 'logged_ip=' . _dbEscape(PHPWCMS_GDPR_MODE ? getAnonymizedIp() : getRemoteIP()) . ' AND ';
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
    $sql = 'UPDATE ' . DB_PREPEND . 'userlog SET logged_change=' . _dbEscape(time()) . ', logged_in=0 ';
    $sql .= 'WHERE logged_user=' . _dbEscape($_SESSION['wcs_user']) . ' AND logged_in=1';
    _dbQuery($sql, 'UPDATE');
    $_SESSION = [];
    @session_destroy();
    $login_url = PHPWCMS_URL . get_login_file();
    $get_parameter = [];
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
        $text = str_replace(['[/p][p]', '[p]', '[/p]', '[br]'], ["</p>\n<p>", '<p>', '</p>', "<br />\n"], $text);
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
    if ($mode === 'basic') {
        $search = [
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
        ];
        $replace = [
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
            '<span class="text-nowrap">',
            '</span>',
        ];

        return str_replace($search, $replace, $text);
    }
    $search = [];
    $replace = [];
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
    $replace[22] = '<span class="text-nowrap">$1</span>';
    $search[23] = '/\[blockquote\](.*?)\[\/blockquote\]/is';
    $replace[23] = '<blockquote>$1</blockquote>';

    return preg_replace($search, $replace, $text);
}

function render_bbcode_url($text) {
    if ($text === '') {
        return $text;
    }
    $text = preg_replace_callback(['/\[url=([^ ]+)(.*?)\](.*?)\[\/url\]/', '/\[a=([^ ]+)(.*?)\](.*?)\[\/a\]/'], 'get_bbcode_ahref', $text);
    // Fallback for URL parameter having =[http://
    if (str_contains($text, '=[')) {
        $text = str_replace(['=[[', '=['], ['=####[#[####', '=####[####'], $text);
        $text = preg_replace_callback('/\[(http|https|ftp):\/\/([^ ]+)(.*?)\]/', 'get_link_ahref', $text);
        $text = str_replace(['=####[#[####', '=####[####'], ['=[[', '=['], $text);
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
    $text = str_replace(['[br]', '[BR]'], ' ', $text);
    $text = preg_replace('|[[\/\!]*?[^\[\]]*?]|si', '', $text);

    return preg_replace('/\s\s+/', ' ', $text);
}

/**
 * Convert short file size (100M) to bytes
 */
function getBytes($size) {
    if (is_numeric($size)) {
        return $size;
    }

    if ($size && preg_match('/^([\d.,]+)\s*([A-Za-z]*)$/', trim($size), $match)) {
        $_unit = [
            '' => 1,
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
        ];
        $unit = strtoupper($match[2]);
        if (isset($_unit[$unit])) {
            return ceil((float) $match[1] * $_unit[$unit]);
        }
    }

    return $size == false ? 0 : (float)$size;
}

/**
 * Try to calculate the memory necessary to
 * handle the image in RAM to avoid
 * errors based on memory limit.
 */
function getRealImageSize($imginfo) {
    if (empty($imginfo) || !is_array($imginfo)) {
        return 0;
    }
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
    $name = str_replace([' ', '/', '\\', '#', '+', ':', '.'], ['_', '-', '-', '_', '-', '-', '-'], $name);
    $name = preg_replace('/[^a-zA-Z0-9\-_]/', '', $name);

    return preg_replace('/^\d+/', '', $name);
}

/**
 * Boolean conversion with handling of common boolean word values
 * (FALSE/NO/N/OFF, TRUE/YES/Y/ON) for string input.
 *
 * Bool, null and 0 are returned directly. Strict mode only accepts
 * the known true word list for strings; everything else falls back
 * to PHP truthiness.
 *
 * @param mixed $BOOL Value to convert to bool
 * @param bool $STRICT Restrict string true detection to the known true word list
 * @return bool
 */
function phpwcms_boolval($BOOL, $STRICT = false) {
    if (is_bool($BOOL)) {
        return $BOOL;
    }
    if ($BOOL === null) {
        return false;
    }
    if ($BOOL === 0) {
        return false;
    }
    if (is_string($BOOL)) {
        $BOOL = strtoupper($BOOL);
        if (in_array($BOOL, ['FALSE', 'NO', 'N', 'OFF', '0'], true)) {
            return false;
        }
        if ($STRICT && in_array($BOOL, ['TRUE', 'YES', 'Y', 'ON', '1'], true)) {
            return true;
        }
    }

    // let PHP decide
    return (bool)$BOOL;
}

/**
 * Sanitize a text for nice URL/alias or slug
 *
 * @param string $text
 * @param bool|int|null $allow_slash
 * @return string
 */
function uri_sanitize($text, $allow_slash = PHPWCMS_ALIAS_WSLASH) {
    if (!is_string($text) || $text === '') {
        return '';
    }

    $allow_slash = (bool)$allow_slash;
    if (str_contains($text, '%')) {
        $text = \voku\helper\UTF8::rawurldecode($text);
    }
    if (str_contains($text, '&')) {
        $text = \voku\helper\UTF8::html_entity_decode($text);
    }
    $text = phpwcms_strip_tags($text);
    $text = \voku\helper\UTF8::cleanup($text);
    $text = str_replace(['[br]', '__', '[b]', '[/b]', '[i]', '[/i]'], ' ', $text); // cleanup special bbcode and __
    if (!$allow_slash) {
        $text = str_replace('/', '-', $text);
    }
    $text = get_alnum_dashes($text, !PHPWCMS_ALIAS_UTF8, '-', $allow_slash);
    $text = trim($text);
    if ($text !== '') {
        if ($allow_slash) {
            $text = preg_replace('/\/+/', '/', $text);
            $text = preg_replace('/[\-_]*\/+[\-_]*/', '/', $text);
        }
        $text = preg_replace('/\-\-+/', '-', (string)$text);
        $text = preg_replace('/__+/', '_', (string)$text);
        $text = trim((string)$text, $allow_slash ? '-_./' : '-_.');
    }

    return $text;
}

function phpwcms_strtotime($date, $date_format = null, $empty_return = false) {
    $strtotime = is_null($date) ? false : strtotime($date);
    if ($strtotime < 0 || $strtotime === false) {
        return $empty_return;
    }

    return is_string($date_format) ? date($date_format, $strtotime) : $strtotime;
}

function dec_num_count($value) {
    if ((int)$value == $value) {
        return 0;
    }

    if (!is_numeric($value)) {
        return false;
    }

    return strlen($value) - strrpos($value, '.') - 1;
}

function convert_rel2abs($text, $base) {
    if (empty($base)) {
        return $text;
    }
    // Base URL needs trailing /
    if (!str_ends_with($base, '/')) {
        $base .= '/';
    }
    // Fix a href - skip absolute URLs, other protocols, placeholders, anchors and empty hrefs
    $pattern = "/<a([^>]*) href=\"(?!(?:https?|ftp|mailto|tel|fax):|###DELETE_LINK###|###SITE_URL###|\"|#)([^\"]*)\"/";
    $replace = "<a\${1} href=\"" . $base . "\${2}\"";
    $text = preg_replace($pattern, $replace, $text);
    // Fix img src
    $pattern = "/<img([^>]*) src=\"(?!(?:https?|ftp):|\")([^\"]*)\"/";
    $replace = "<img\${1} src=\"" . $base . "\${2}\"";

    return preg_replace($pattern, $replace, $text);
}

/**
 * Render the relative download URL
 *
 * @param string $hash
 * @param string $filename
 * @param bool $countonly
 * @param bool $htmlencode
 * @param bool|null $inline
 *
 * @return string
 */
function rel_download($hash = '', $filename = '', $countonly = false, $htmlencode = true, $inline = null) {
    $href = '';
    $get = [];
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

/**
 * Resolve ISO language code to standardized OpenGraph / BCP47 locale
 *
 * @param string $lang_iso ISO language tag (e.g. 'en', 'de', 'de-AT', 'zh-cn')
 * @return string Standardized locale (e.g. 'en_US', 'de_AT', 'zh_CN')
 */
function get_locale_canonical($lang_iso = '') {
    $lang_iso = strtolower(trim((string)$lang_iso));

    if($lang_iso === '') {
        $lang_iso = !empty($GLOBALS['phpwcms']['DOCTYPE_LANG'])
            ? strtolower($GLOBALS['phpwcms']['DOCTYPE_LANG'])
            : (!empty($GLOBALS['phpwcms']['default_lang']) ? strtolower($GLOBALS['phpwcms']['default_lang']) : 'en');
    }

    $locale_map = [
        'ar'    => 'ar_AR',
        'bg'    => 'bg_BG',
        'bn'    => 'bn_IN',
        'bs'    => 'bs_BA',
        'ca'    => 'ca_ES',
        'cs'    => 'cs_CZ',
        'da'    => 'da_DK',
        'de'    => 'de_DE',
        'de-de' => 'de_DE',
        'de-at' => 'de_AT',
        'de-ch' => 'de_CH',
        'el'    => 'el_GR',
        'en'    => 'en_US',
        'en-gb' => 'en_GB',
        'en-us' => 'en_US',
        'es'    => 'es_ES',
        'et'    => 'et_EE',
        'eu'    => 'eu_ES',
        'fi'    => 'fi_FI',
        'fr'    => 'fr_FR',
        'fr-fr' => 'fr_FR',
        'gl'    => 'gl_ES',
        'gr'    => 'el_GR',
        'he'    => 'he_IL',
        'hi'    => 'hi_IN',
        'hr'    => 'hr_HR',
        'hu'    => 'hu_HU',
        'id'    => 'id_ID',
        'is'    => 'is_IS',
        'it'    => 'it_IT',
        'ja'    => 'ja_JP',
        'ko'    => 'ko_KR',
        'lt'    => 'lt_LT',
        'lv'    => 'lv_LV',
        'mk'    => 'mk_MK',
        'nb'    => 'nb_NO',
        'nl'    => 'nl_NL',
        'nn'    => 'nn_NO',
        'no'    => 'nb_NO',
        'pa'    => 'pa_IN',
        'pl'    => 'pl_PL',
        'pt'    => 'pt_PT',
        'pt-br' => 'pt_BR',
        'ro'    => 'ro_RO',
        'ru'    => 'ru_RU',
        'sk'    => 'sk_SK',
        'sl'    => 'sl_SI',
        'sq'    => 'sq_AL',
        'sr'    => 'sr_RS',
        'sv'    => 'sv_SE',
        'ta'    => 'ta_IN',
        'th'    => 'th_TH',
        'tr'    => 'tr_TR',
        'ua'    => 'uk_UA',
        'uk'    => 'uk_UA',
        'ur'    => 'ur_PK',
        'vi'    => 'vi_VN',
        'zh'    => 'zh_CN',
        'zh-cn' => 'zh_CN',
        'zh-tw' => 'zh_TW',
        'zh-hk' => 'zh_HK',
    ];

    if(isset($locale_map[$lang_iso])) {
        return $locale_map[$lang_iso];
    }

    if(class_exists(\Locale::class, false) || class_exists('Locale')) {
        $canonical = \Locale::canonicalize(str_replace('_', '-', $lang_iso));
        if(!empty($canonical)) {
            return $canonical;
        }
    }

    return (strlen($lang_iso) === 2) ? $lang_iso . '_' . strtoupper($lang_iso) : str_replace('-', '_', $lang_iso);
}

