<?php
/**
 * phpwcms
 *
 * @author Oliver Georgi <og@phpwcms.org>
 * @copyright Copyright (c) 2002-2026, Oliver Georgi
 * @license http://opensource.org/licenses/GPL-2.0 GNU GPL-2
 *
 **/

// ----------------------------------------------------------------
// obligate check for phpwcms constants
if (!defined('PHPWCMS_ROOT')) {
    die("You Cannot Access This Script Directly, Have a Nice Day.");
}
// ----------------------------------------------------------------

// Overwrite some defaults
if(!isset($phpwcms['set_article_active'])) {
    $phpwcms['set_article_active'] = 1;
}
if(!isset($phpwcms['set_category_active'])) {
    $phpwcms['set_category_active'] = 1;
}
if(!isset($phpwcms['set_file_active'])) {
    $phpwcms['set_file_active'] = 1;
}
if(!isset($phpwcms['set_news_active'])) {
    $phpwcms['set_news_active'] = 1;
}
if(isset($phpwcms['set_sociallink']) && is_array($phpwcms['set_sociallink'])) {
    $phpwcms['set_sociallink'] = array_merge(array('article' => false, 'articlecat' => false, 'news' => false, 'shop' => false, 'render' => true), $phpwcms['set_sociallink']);
} else {
    $phpwcms['set_sociallink'] = array('article' => false, 'articlecat' => false, 'news' => false, 'shop' => false, 'render' => true);
}

//new function to build file icons
function ext_icon($ext) {
  $image = array("jpg","jpeg","png","gif","tif","tiff","bmp","pic","psd","eps","ai","svg","ps");
  $code = array("html","xml","ini","sql","db");
  $pdf = array("pdf");
  $archive = array("zip","rar","7z","s7z","dmg","bz2","gz","tar","tgz");
  $video = array("mkv","webm","vob","ogg","ogv","mov","qt","wmv","mpg","mpeg","mp3","mp4","m4p","flv","f4v","f4p","f4a","f4b");
  $powerpoint = array("ppt","pptx");
  $word = array("doc","docx");
  $excel = array("xls","xlsx");
  $text = array("odt","odm","odg","ods","odp","odf","odc","odb","sxw","sxc","sxi","csv","txt","rtf");

  if (in_array($ext, $image)) {
    $faicon = 'file-image';
  } elseif (in_array($ext, $code)) {
    $faicon = 'file-code';
  } elseif (in_array($ext, $pdf)) {
    $faicon = 'file-pdf';
  } elseif (in_array($ext, $archive)) {
    $faicon = 'file-archive';
  } elseif (in_array($ext, $video)) {
    $faicon = 'file-video';
  } elseif (in_array($ext, $powerpoint)) {
    $faicon = 'file-powerpoint';
  } elseif (in_array($ext, $word)) {
    $faicon = 'file-word';
  } elseif (in_array($ext, $excel)) {
    $faicon = 'file-excel';
  } elseif (in_array($ext, $text)) {
    $faicon = 'file-text';
  } else {
    $faicon = 'file';
  }
  return $faicon;
}

/**
 * Invalidate cached frontend structure
 */
function update_cache() {
    if (function_exists('_setConfig')) {
        _setConfig('structure_array_vmode_all', '', 'frontend_render', 1);
        _setConfig('structure_array_vmode_editor', '', 'frontend_render', 1);
        _setConfig('structure_array_vmode_admin', '', 'frontend_render', 1);
    }
}

/**
 * Javascript forwarding
 *
 * @throws JsonException
 */
function forward_to($to, $link, $time=2500) {
    if($to) {
        echo '<script>';
        echo 'setTimeout(function(){document.location.href=' . json_encode((string)$link, JSON_THROW_ON_ERROR) . ';}, ' . (int)$time . ');';
        echo '</script>';
    }
}

function subnavtext($text, $link, $is, $should, $getback=1, $js='') {
    //generate ul based subnavigation based on text
    $sn = '';
    if ($is == $should) {
        $sn .= '<li class="subnavactive"><a href="' . $link . '">' . $text . '</a></li>';
    } else {
        $sn .= '<li class="subnavinactive"><a href="' . $link . '" ' . $js . '>' . $text . '</a></li>';
    }
    if (!$getback) {
        return $sn;
    }

    echo $sn;

    return null;
}

function subnavtextext($text, $link, $target='_blank', $getback=1) {
    //generate ul based subnavigation based on text and links to new page
    $sn = '<li class="subnavinactive"><a href="' . $link . '" target="' . $target . '" >' . $text . '</a></li>';

    if (!$getback) {
        return $sn;
    }
    echo $sn;
}

/**
 * check_image_extension function.
 *
 * @access public
 * @param mixed $file
 * @param string $filename (default: '')
 * @param mixed &$file_image_size
 * @return string
 */
function check_image_extension($file, $filename, &$file_image_size) {

    $result = false;
    if(empty($file_image_size[2])) {
        $file_image_size = getimagesize($file);
    }

    if(!empty($file_image_size[2])) {

        if($filename === '') {
            $filename = basename($file);
        }

        switch($file_image_size[2]) {
            case  1: $result = 'gif'; break;
            case  2: $result = 'jpg'; break;
            case  3: $result = 'png'; break;
            case  4: $result = 'swf'; break;
            case  5: $result = 'psd'; break;
            case  6: $result = 'bmp'; break;
            case  7:                         //(intel byte order),
            case  8: $result = 'tif'; break; //(motorola byte order),
            case  9: $result = 'jpc'; break;
            case 10: $result = 'jp2'; break;
            case 11: $result = 'jpx'; break;
            case 12: $result = 'jb2'; break;

            case 13: // there is a problem in some cases swf -> swc ? why ever!
                     // do an additional extension check and compare against swf
                     $result = strtolower(which_ext($filename)) === 'swf' ? 'swf' : 'swc';
                     break;

            case 14: $result = 'iff'; break;

            case 15: // there seems to be a problem with getimagesize and Quicktime VR
                     // mov -> wmbf ? why ever!
                     // do an additional extension check and compare against mov
                     $result = strtolower(which_ext($filename)) === 'mov' ? 'mov' : 'wbmp';
                     break;

            case 16: $result = 'xbm'; break;
            case 18:
            case 32: $result = 'webp'; break;
        }
    }

    return $result;
}

function getParentStructArray($structID) {

    $result = _dbQuery("SELECT * FROM ".DB_PREPEND."phpwcms_articlecat WHERE acat_id=".intval($structID)." LIMIT 1");

    if(isset($result[0]['acat_id'])) {
        return $result[0];
    }

    return $GLOBALS['indexpage'];
}

/*
 * Get sort value for an article based on category
 */
function getArticleSortValue($cat_id=0) {

    $cat_id = (int) $cat_id;

    $sql = 'SELECT COUNT(article_id) AS cnt, MAX(article_sort) AS max_sort FROM ' . DB_PREPEND . 'phpwcms_article ' .
           'WHERE article_cid = ' . $cat_id . ' AND article_deleted = 0';
    $result = _dbQuery($sql);

    $count    = isset($result[0]['cnt']) ? (int) $result[0]['cnt'] : 0;
    $max_sort = isset($result[0]['max_sort']) ? (int) $result[0]['max_sort'] : 0;

    $count = ($count + 1) * 10;

    return max($count, $max_sort + 10);
}

/*
 * Make a re-sort for given structure ID and
 * return new sorted articles as array
 */
function getArticleReSorted($cat_id, $ordered_by) {

    // get all articles including deleted and update sorting
    // in correct sort order by adding sort + 10
    $cat_id             = (int) $cat_id;
    $sort               = 10;
    $sort_multiply_by   = 1;
    $count_article      = 0;
    $article            = array();
    $ao                 = get_order_sort($ordered_by);

    $sql  = 'SELECT article_id, article_cid, article_title, article_aktiv, article_uid, ' .
            "date_format(article_tstamp, '%Y-%m-%d %H:%i:%s') AS article_date, article_sort, article_deleted, article_tstamp " .
            'FROM ' . DB_PREPEND . 'phpwcms_article ' .
            'WHERE article_cid = ' . $cat_id . ' ORDER BY ' . $ao[2];

    $result = _dbQuery($sql);

    if(!empty($result) && is_array($result)) {

        // now check if it's sorted manually and DESC
        // then sort has to be lowered by -10
        if($ao[0] === 0 && $ao[1] === 1) {
            $sort = (count($result) + 1) * 10;
            $sort_multiply_by = -1;
        }

        $ids   = array();
        $cases = array();

        // take all entries and build new array with it
        foreach($result as $row) {

            $row_id  = (int) $row['article_id'];
            $ids[]   = $row_id;
            $cases[] = 'WHEN ' . $row_id . ' THEN ' . $sort;

            // add entry to the returning array only for article_deleted=0
            // drops all deleted articles or articles having another status
            if(empty($row['article_deleted'])) {
                $row['article_sort']     = $sort;
                $article[$count_article] = $row;
                $count_article++;
            }

            // count sort up by 10
            $sort += (10 * $sort_multiply_by);
        }

        if(!empty($ids)) {
            $update_sql = 'UPDATE ' . DB_PREPEND . 'phpwcms_article SET ' .
                          'article_sort = CASE article_id ' . implode(' ', $cases) . ' END, ' .
                          'article_tstamp = article_tstamp ' .
                          'WHERE article_id IN (' . implode(',', $ids) . ')';
            _dbQuery($update_sql, 'UPDATE');
        }
    }

    return $article;
}

function phpwcmsversionCheck() {
    global $phpwcms;
    global $BL;

    if(empty($phpwcms['version_check'])) {
        $version = '<div class="versioncheck">';
        $version .= '<h1>' . $BL['Version_information'] . '</h1>';
        $version .= '<p class="alert alert-info">';
        $version .= sprintf($BL['Current_version_info'], PHPWCMS_VERSION . ' ('. PHPWCMS_RELEASE_DATE. ', r' . PHPWCMS_REVISION . ')');
        $version .= '</p>';
        $version .= '</div>';

        return $version;
    }

    if(!empty($_SESSION['phpwcms_version_check'])) {
        return $_SESSION['phpwcms_version_check'];
    }

    // Check for new version
    $errno          = 0;
    $errstr         = '';
    $version_info   = '';
    $get_info       = false;
    $has_sockets    = false;

    $identify  = '?version='.rawurlencode(PHPWCMS_VERSION.' '.str_replace('/', '', PHPWCMS_RELEASE_DATE));
    $identify .= '&hash='.md5($_SERVER['REQUEST_URI']);
    $identify .= '&url='.rawurlencode(PHPWCMS_URL);
    $identify .= '&revision='.rawurlencode(PHPWCMS_REVISION);

    if(function_exists('fsockopen')) {
        $has_sockets = true;
        if($fsock = @fsockopen('ssl://www.phpwcms.org', 443, $errno, $errstr, 10)) {
            @fputs($fsock, "GET /versioncheck/".$identify." HTTP/1.1\r\n");
            @fputs($fsock, "Host: www.phpwcms.org\r\n");
            @fputs($fsock, "Connection: close\r\n\r\n");

            $response_header = '';
            while (!@feof($fsock)) {
                if($get_info) {
                    $version_info .= @fread($fsock, 1024);
                } else {
                    $line = @fgets($fsock, 1024);
                    $response_header .= $line;
                    if ($line === "\r\n") {
                        $get_info = true;
                    }
                }
            }
            @fclose($fsock);

            if (preg_match('/^HTTP\/1\.[01]\s+(\d+)/i', $response_header, $status_match)) {
                $status_code = intval($status_match[1]);
                if ($status_code !== 200) {
                    $errstr = 'HTTP Error ' . $status_code;
                    $get_info = false;
                }
            }
        }
    }

    if(!$get_info && function_exists('file_get_contents') && ini_get('allow_url_fopen')) {
        $has_sockets = true;
        $context = stream_context_create([
            'http' => [
                'ignore_errors' => true,
                'timeout' => 10,
                'header' => 'User-Agent: phpwcms/' . PHPWCMS_VERSION . "\r\n"
            ]
        ]);
        $version_info = @file_get_contents('https://www.phpwcms.org/versioncheck/'.$identify, false, $context);
        if ($version_info !== false) {
            $get_info = true;
            if (
                isset($http_response_header[0])
                && preg_match('/^HTTP\/1\.[01]\s+(\d+)/i', $http_response_header[0], $status_match)
            ) {
                $status_code = (int)$status_match[1];
                if ($status_code !== 200) {
                    $errstr = 'HTTP Error ' . $status_code;
                    $get_info = false;
                }
            }
        } else {
            $errstr = 'file_get_contents failed';
        }
    }

    if($get_info && preg_match('/.*BEGIN -->(.+)<!-- END.*/s', $version_info, $match)) {
        $version_info       = explode(LF, $match[1]);
        if(count($version_info) < 3) {
            return '<p class="error">' . sprintf($BL['Connect_socket_error'], 'invalid version check response') . '</p>';
        }
        $latest_version     = trim($version_info[0]);
        $latest_revdate     = trim($version_info[1]);
        $latest_revision    = (int)trim($version_info[2]);
        $latest_time        = strtotime($latest_revdate.' 00:00:00');
        $version_time       = strtotime(PHPWCMS_RELEASE_DATE.' 00:00:00');

        if($latest_revision <= $phpwcms['revision'] || $latest_time <= $version_time)   {
            $version_info  = '<p class="valid">' . $BL['Version_up_to_date'] . '</p>';
        } else {
            $version_info  = '<p class="error">' . $BL['Version_not_up_to_date'] . '</p>';
        }

        if($latest_time > $version_time) {
            $mark_date_prefix = '<span class="error">';
            $mark_date_suffix = '</span>';
        } elseif($latest_time < $version_time) {
            $mark_date_prefix = '<span class="valid">';
            $mark_date_suffix = '</span>';
        } else {
            $mark_date_prefix = '';
            $mark_date_suffix = '';
        }

        if($latest_revision > $phpwcms['revision']) {
            $mark_rev_prefix = '<span class="error">';
            $mark_rev_suffix = '</span>';
        } elseif($latest_revision < $phpwcms['revision']) {
            $mark_rev_prefix = '<span class="valid">';
            $mark_rev_suffix = '</span>';
        } else {
            $mark_rev_prefix = '';
            $mark_rev_suffix = '';
        }

        $version_info .= '<p>' . sprintf($BL['Latest_version_info'], $latest_version.' ('.$latest_revdate.', r'.$latest_revision.')'). '<br />';
        $version_info .= sprintf($BL['Current_version_info'], $mark_rev_prefix.PHPWCMS_VERSION.$mark_rev_suffix.' ('.$mark_date_prefix.PHPWCMS_RELEASE_DATE.$mark_date_suffix.', r'.PHPWCMS_REVISION.')') . '</p>';

    } elseif ($errstr) {
        $version_info = '<p class="error">' . sprintf($BL['Connect_socket_error'], $errstr) . '</p>';
    } elseif (!$has_sockets) {
        $version_info = '<p>' . $BL['Socket_functions_disabled'] . '</p>';
    } else {
        $version_info = '<p class="error">' . sprintf($BL['Connect_socket_error'], 'Invalid response format') . '</p>';
    }

    $_SESSION['phpwcms_version_check'] = '<div class="versioncheck"><h1>'.$BL['Version_information'].'</h1> '.$version_info.'</div>';

    return $_SESSION['phpwcms_version_check'];
}

function createOptionTransferSelectList($id, $leftData, $rightData, $option = array()) {
    // used to create
    global $BL;

    $id_left                = $id.'_left';
    $id_right               = $id.'_right';
    $id_left_box            = $id_left.'list';
    $id_right_box           = $id_right.'list';
    $option_object          = generic_string(4, 4);

    $table                  = '';

    $option['rows']         = empty($option['rows']) || !intval($option['rows']) ? 5 : $option['rows'];
    $option['delimeter']    = empty($option['delimeter']) ? ',' : $option['delimeter'];
    $option['encode']       = !(isset($option['encode']) && $option['encode'] === false);
    $option['style']        = empty($option['style']) ? '' : ' style="'.$option['style'].'"';
    $option['class']        = empty($option['class']) ? ' class="#SIDE#"' : ' class="#SIDE# '.$option['class'].' form-control form-control-sm mb-1"';
    $option['formname']     = empty($option['formname']) ? 'document.forms[0]' : 'document.getElementById(\''.$option['formname'].'\')';
    $table .= '<div class="row">';
    // left select list
    $table .= '<div class="col"><select class="form-select" name="'.$id_left_box.'" id="'.$id_left_box.'" size="'.$option['rows'].'" multiple="multiple"';
    $table .= $option['style'].str_replace('#SIDE#', 'leftSide', $option['class']).' ondblclick="'.$option_object.'.transferRight()">';
    if(!empty($leftData) && is_array($leftData)) {
        foreach($leftData as $key => $value) {
            $table .= '<option value="'.html($key).'">'.html($value).'</option>';
        }
    }
    $table .= '</select>';
    $table .= '<div class="btn btn-sm btn-secondary me-1" onclick="moveOptionUp('.$option['formname'].'.'.$id_left_box.');'.$option_object.'.update();"><i class="fa fa-angle-up fa-fw" aria-hidden="true"></i></div>';
    $table .= '<div class="btn btn-sm btn-secondary me-1" onclick="moveOptionDown('.$option['formname'].'.'.$id_left_box.');'.$option_object.'.update();"><i class="fa fa-angle-down fa-fw" aria-hidden="true"></i></div>';
    $table .= '<div class="btn btn-sm btn-secondary me-1" data-bs-toggle="tooltip" title="'.html($BL['be_admin_struct_remove_this']).'" onclick="'.$option_object.'.transferRight();"><i class="fa fa-angle-right fa-fw" aria-hidden="true"></i></div>';
    $table .= '<div class="btn btn-sm btn-secondary" data-bs-toggle="tooltip" title="'.html($BL['be_admin_struct_remove_all']).'" onclick="'.$option_object.'.transferAllRight();"><i class="fa fa-angle-double-right fa-fw" aria-hidden="true"></i></div>';
    $table .= '</div>';

    // right select list
    $table .= '<div class="col"><select class="form-select" name="'.$id_right_box.'" id="'.$id_right_box.'" size="'.$option['rows'].'" multiple="multiple"';
    $table .= $option['style'].str_replace('#SIDE#', 'rightSide', $option['class']).' ondblclick="'.$option_object.'.transferLeft()">';
    if(!empty($rightData) && is_array($rightData)) {
        foreach($rightData as $key => $value) {
            $table .= '<option value="'.html($key).'">'.html($value).'</option>';
        }
    }
    $table .= '</select>';
    $table .= '<div class="btn btn-sm btn-secondary me-1" data-bs-toggle="tooltip" title="'.html($BL['be_admin_struct_adduser_all']).'" onclick="'.$option_object.'.transferAllLeft();"><i class="fa fa-angle-double-left fa-fw" aria-hidden="true"></i></div>';
    $table .= '<div class="btn btn-sm btn-secondary" data-bs-toggle="tooltip" title="'.html($BL['be_admin_struct_adduser_this']).'" onclick="'.$option_object.'.transferLeft();"><i class="fa fa-angle-left fa-fw" aria-hidden="true"></i></div>';
    $table .= '</div></div>';

    $table .= '<input type="hidden" name="'.$id_left.'" id="'.$id_left.'" value="" />';
    $table .= '<input type="hidden" name="'.$id_right.'" id="'.$id_right.'" value="" />';

    $table .= '<script type="text/javascript">'.LF;
    $table .= ' var '.$option_object.' = new OptionTransfer("'.$id_left_box.'","'.$id_right_box.'");'.LF;
    $table .= ' '.$option_object.'.setAutoSort(false);'.LF;
    $table .= ' '.$option_object.'.setDelimiter('.json_encode((string)$option['delimeter']).');'.LF;
    $table .= ' '.$option_object.'.saveNewLeftOptions("'.$id_left.'");'.LF;
    $table .= ' '.$option_object.'.saveNewRightOptions("'.$id_right.'");'.LF;
    $table .= ' '.$option_object.'.init('.$option['formname'].');'.LF;
    $table .= '</script>'.LF;

    return $table;
}

function countNewsletterRecipients($target) {
    // try to count all recipients for special newsletter
    $recipients = _dbQuery('SELECT address_id, address_subscription FROM '.DB_PREPEND.'phpwcms_address WHERE address_verified=1');
    $counter    = 0;
    $check      = !(empty($target) || !is_array($target) || !count($target));
    foreach($recipients as $value) {
        if (empty($value['address_subscription'])) {
            $counter++;
            continue;
        }

        if($check) {
            $value['address_subscription'] = @unserialize($value['address_subscription'], ['allowed_classes' => false]);
            if(is_array($value['address_subscription']) && count($value['address_subscription'])) {
                foreach($value['address_subscription'] as $subscr) {
                    if(in_array(intval($subscr), $target)) {
                        $counter++;
                        break;
                    }
                }
            } else {
                $counter++;
            }
        }
    }
    return $counter;
}

/**
 * Render content part select option tags
 *
 * @param mixed $value Content part ID, empty string, or null
 * @param string $text Option label
 * @param string|int $selected Currently selected content part ID
 * @param string $module Custom module / content part key
 * @param bool|string $action Control action: true/'reset' to reset, 'selected' to get selected index
 * @return string|int
 */
function getContentPartOptionTag($value = '', $text = '', $selected = '', $module = '', $action = false) {
    static $counter = 0;
    static $selected_index = 0;

    // Reset state before building a new select list
    if ($action === true || $action === 'reset') {
        $counter = 0;
        $selected_index = 0;
        return '';
    }

    // Retrieve the matching selected index
    if ($action === 'selected' || $action === 'get_selected') {
        return $selected_index;
    }

    $result = '';

    // necessary plugin check
    if ($value == 30) {
        foreach ($GLOBALS['phpwcms']['modules'] as $module_value) {
            if ($module_value['cntp'] && file_exists($module_value['path'] . 'inc/cnt.list.php')) {
                $result .= '<option value="' . $value . ':' . $module_value['name'] . '"';
                if ($value == $selected && $module_value['name'] == $module) {
                    $result .= ' selected="selected"';
                    $selected_index = $counter;
                }
                $result .= '>' . $text;
                $result .= ': ' . $GLOBALS['BL']['modules'][$module_value['name']]['listing_title'];
                $result .= '</option>' . LF;
                $counter++;
            }
        }
    } elseif ($value == 60 && function_exists('get_custom_contentparts')) {
        $custom_cpts = get_custom_contentparts(true);
        if (is_array($custom_cpts) && count($custom_cpts)) {
            foreach ($custom_cpts as $cpt_key => $cpt_data) {
                $result .= '<option value="' . $value . ':' . $cpt_key . '"';
                if ($value == $selected && $cpt_key == $module) {
                    $result .= ' selected="selected"';
                    $selected_index = $counter;
                }
                $cpt_title = function_exists('i18n_substitute_text') ? i18n_substitute_text($cpt_data['cpt_title']) : $cpt_data['cpt_title'];
                $result .= '>' . $text . ': ' . html($cpt_title);
                $result .= '</option>' . LF;
                $counter++;
            }
        } else {
            $result .= '<option value="' . $value . '"';
            if ($value == $selected) {
                $result .= ' selected="selected"';
                $selected_index = $counter;
            }
            $result .= '>' . $text . '</option>' . LF;
            $counter++;
        }
    } else {
        $result .= '<option value="' . $value . '"';
        if ($value == $selected) {
            $result .= ' selected="selected"';
            $selected_index = $counter;
        }
        $result .= '>' . $text . '</option>' . LF;
        $counter++;
    }

    return $result;
}

function isContentPartSet($value='') {

    $value = explode(':', $value);
    $value[0] = intval($value[0]);

    // check for set module
    if(empty($value[1])) {

        $value[1] = 1;

    } elseif(       isset($GLOBALS['phpwcms']['modules'][$value[1]])
                &&  $GLOBALS['phpwcms']['modules'][$value[1]]['cntp']
                &&  file_exists($GLOBALS['phpwcms']['modules'][$value[1]]['path'].'inc/cnt.list.php')) {

        $value[1] = 1;

    } else {

        $value[1] = 0;

    }

    // check if content part ID exists
    if(isset($GLOBALS['wcs_content_type'][ $value[0] ]) && $value[1]) {

        return true;

    } else {

        return false;

    }

}

/*
 *  Show System Status Message
 */
function show_status_message($return_status=false) {
    if(empty($_SESSION['system_status']['msg'])) {
        $status = '';
    } else {
        $status  = '<div  class="alert alert-' . $_SESSION['system_status']['type'] .'">';
        $status .= nl2br( trim( html($_SESSION['system_status']['msg']) ) ) . '</div>';
        $_SESSION['system_status']['msg'] = '';
    }
    if($return_status) {
        return $status;
    } else {
        echo $status;
        return NULL;
    }
}
/*
 *  Set System Status Message
 */
function set_status_message($msg='', $type='info', $replace=array()) {
    if(is_array($replace) && count($replace)) {
        foreach($replace as $key => $item) {
            $msg = str_replace('{'.strtoupper($key).'}', $item, $msg);
        }
    }
    $_SESSION['system_status']['msg']  = $msg;
    switch($type) {
        case 'success':
        case 'info':
        case 'danger':
        case 'error':
        case 'warning': break;
        default: $type = 'info';
    }
    $_SESSION['system_status']['type'] = $type;
    return NULL;
}

function set_language_cookie($lang='en') {
    setcookie('phpwcmsBELang', $lang, time()+(3600*24*365), '/', getCookieDomain(), PHPWCMS_SSL, true);
}

function set_theme_cookie($theme = 'auto') {
    $theme = in_array($theme, array('light', 'dark', 'auto'), true) ? $theme : 'auto';
    setcookie('phpwcmsBETheme', $theme, time()+(3600*24*365), '/', getCookieDomain(), PHPWCMS_SSL, false);
}

function get_backend_theme() {
    if (!empty($_SESSION['wcs_user_theme']) && in_array($_SESSION['wcs_user_theme'], array('light', 'dark', 'auto'), true)) {
        return $_SESSION['wcs_user_theme'];
    }
    if (!empty($_COOKIE['phpwcmsBETheme']) && in_array($_COOKIE['phpwcmsBETheme'], array('light', 'dark', 'auto'), true)) {
        return $_COOKIE['phpwcmsBETheme'];
    }
    return 'auto';
}

/**
 * Helper to compute next available alias from a set of existing alias names
 */
function _get_unique_alias_candidate($alias, $existing_aliases = [], $pad_length = 0, $check_file = false) {
    if (preg_match('/^(.*?)-(\d+)$/', $alias, $match) && $match[1] !== '') {
        $base_alias = $match[1];
        $pad = $pad_length ? max($pad_length, strlen($match[2])) : 0;
        $counter = (int) $match[2] + 1;
    } else {
        $base_alias = $alias;
        $pad = $pad_length;
        $counter = 1;
    }

    do {
        $suffix = $pad ? sprintf('%0' . $pad . 'd', $counter) : (string)$counter;
        $candidate = $base_alias . '-' . $suffix;
        $counter++;
    } while (
        isset($existing_aliases[$candidate]) ||
        ($check_file && defined('PHPWCMS_REWRITE_EXT') && PHPWCMS_REWRITE_EXT !== '' && is_file(PHPWCMS_ROOT . '/' . $candidate . PHPWCMS_REWRITE_EXT))
    );

    return $candidate;
}

// checks for alias and sets unique value
function proof_alias($current_id, $alias='', $mode='CATEGORY', $fallback_name='') {
    global $phpwcms;

    // normalize the id so strict 'index' comparisons behave the same
    // regardless of the caller passing int, numeric string or 'index'
    $current_id = (string)$current_id;

    $allow_slash = !($mode === 'FILE') && PHPWCMS_ALIAS_WSLASH;
    $alias       = uri_sanitize(clean_slweg($alias), $allow_slash);
    if (function_exists('mb_strtolower')) {
        $alias = mb_strtolower($alias, 'UTF-8');
    } else {
        $alias = strtolower($alias);
    }

    $reserved = [
        'print',
        'newsdetail',
        'newspage',
        'id',
        'aid',
        'subgallery',
        'listpage',
        'page',
        'subscribe',
        'unsubscribe',
        'email',
        'u',
        's',
        'q',
        'feedimport',
        'r404',
        'phpwcms-preview',
        'dl',
        'fmp',
        'index',
        'robots',
        'sitemap',
        'favicon'
    ];

    if($alias === '') {
        if ($mode !== 'FILE' && !empty($GLOBALS['phpwcms']['allow_empty_alias'])) {
            return '';
        }

        if($mode === 'CATEGORY' && isset($_POST['acat_name'])) {
            $alias = $_POST['acat_name'];
        } elseif($mode === 'ARTICLE' && isset($_POST['article_title'])) {
            $alias = $_POST['article_title'];
        } elseif($mode === 'CONTENT' && ( isset($_POST['cnt_title']) || isset($_POST['cnt_name']) )) {
            $alias = trim($_POST['cnt_title'] ?? '') !== '' ? $_POST['cnt_title'] : ($_POST['cnt_name'] ?? '');
        } elseif($mode === 'FILE') {
            $alias = $fallback_name !== '' ? cut_ext($fallback_name) : cut_ext($_POST['file_name'] ?? '');
        }

        $alias = uri_sanitize(clean_slweg($alias), $allow_slash);
        if (function_exists('mb_strtolower')) {
            $alias = mb_strtolower($alias, 'UTF-8');
        } else {
            $alias = strtolower($alias);
        }

        if($alias === '') {
            if ($mode === 'FILE') {
                $alias = 'file' . ($current_id ? '-' . (int)$current_id : '');
            } else {
                return '';
            }
        }
    }

    // Test against existing folders to avoid problems with rewrite
    if($allow_slash && str_contains($alias, '/')) {
        $root_folders = returnSubdirListAsArray(PHPWCMS_ROOT);

        if($root_folders !== false) {

            // Only check first "path" section
            $alias_sections = explode('/', $alias);
            $alias_proof = function_exists('mb_strtolower') ? mb_strtolower($alias_sections[0], 'UTF-8') : strtolower($alias_sections[0]);
            $alias_proof_suffix = '';
            $alias_proof_suffix_count = 0;

            foreach($root_folders as $key => $folder) {
                $root_folders[$key] = function_exists('mb_strtolower') ? mb_strtolower($folder, 'UTF-8') : strtolower($folder);
            }

            while(in_array($alias_proof.$alias_proof_suffix, $root_folders)) {
                $alias_proof_suffix_count++;
                $alias_proof_suffix = '-'.$alias_proof_suffix_count;
            }

            if($alias_proof_suffix_count) {
                $alias_sections[0] .= $alias_proof_suffix;
                $alias = implode('/', $alias_sections);
            }
        }
    }

    if (function_exists('mb_substr')) {
        $alias = mb_substr($alias, 0, 230, 'UTF-8');
    } else {
        $alias = substr($alias, 0, 230);
    }

    if($allow_slash) {
        $alias = trim($alias, '/');
    }

    $alias_proof_suffix = '';
    $alias_proof_suffix_count = 0;

    // Now test against existing files to avoid problems with rewrite
    if ($mode !== 'FILE') {
        while(is_file(PHPWCMS_ROOT.'/'.$alias.$alias_proof_suffix.PHPWCMS_REWRITE_EXT)) {
            $alias_proof_suffix_count++;
            $alias_proof_suffix = '-'.$alias_proof_suffix_count;
        }

        if($alias_proof_suffix_count) {
            $alias .= $alias_proof_suffix;
        }
    }

    // new reserved alias can be defined in $phpwcms['reserved_alias']
    if(isset($phpwcms['reserved_alias']) && is_array($phpwcms['reserved_alias']) && count($phpwcms['reserved_alias'])) {
        $reserved = array_merge($reserved, $phpwcms['reserved_alias']);
    }

    if($alias === '' || in_array($alias, $reserved) || ($alias === 'index' && $current_id !== 'index') ) {
        if ($mode === 'FILE') {
            $alias .= '-' . ($current_id ? (int)$current_id : 'file');
        } elseif ($mode === 'CONTENT') {
            $alias .= date('_Ymd');
        } else {
            $alias .= '-view';
        }
    }

    $alias = trim($alias, '-');

    $where_acat     = '';
    $where_article  = '';
    $where_content  = '';
    $where_file     = '';
    $current_sql_id = $current_id === 'index' ? 0 : (int) $current_id;

    switch($mode) {
        case 'CATEGORY':    $where_acat     = 'acat_id != '.$current_sql_id.' AND ';    break;
        case 'ARTICLE':     $where_article  = 'article_id != '.$current_sql_id.' AND '; break;
        case 'CONTENT':     $where_content  = 'cnt_id != '.$current_sql_id.' AND ';     break;
        case 'FILE':        $where_file     = 'f_id != '.$current_sql_id.' AND ';        break;
    }

    if ($mode === 'FILE') {
        $sql = 'SELECT COUNT(f_id) FROM ' . DB_PREPEND . 'phpwcms_file WHERE ' . $where_file . 'f_alias = ' . _dbEscape($alias);
        $file_count = _dbQuery($sql, 'COUNT');

        if ($file_count > 0) {
            $base_alias = preg_match('/^(.*?)-(\d+)$/', $alias, $match) && $match[1] !== '' ? $match[1] : $alias;
            $sql  = 'SELECT f_alias FROM ' . DB_PREPEND . 'phpwcms_file WHERE ';
            $sql .= $where_file;
            $sql .= '(f_alias = ' . _dbEscape($base_alias) . ' OR f_alias LIKE ' . _dbEscape($base_alias, true, '', '-%') . ')';
            $all_file_alias = _dbQuery($sql);

            $all_alias = [];
            if (is_array($all_file_alias)) {
                foreach ($all_file_alias as $item) {
                    $all_alias[$item['f_alias']] = true;
                }
            }

            $alias = _get_unique_alias_candidate($alias, $all_alias, 0, false);
        }

        return $alias;
    }

    // check alias against all structure alias
    $sql  = 'SELECT COUNT(acat_id) FROM ' . DB_PREPEND . 'phpwcms_articlecat WHERE ' . $where_acat . 'acat_alias = ' . _dbEscape($alias);
    $acat_count = _dbQuery($sql, 'COUNT');

    // check alias against all articles
    $sql  = 'SELECT COUNT(article_id) FROM ' . DB_PREPEND . 'phpwcms_article WHERE ' . $where_article . 'article_alias = ' . _dbEscape($alias);
    $article_count = _dbQuery($sql, 'COUNT');

    // check alias against all "sub" contents like news
    $sql  = 'SELECT COUNT(cnt_id) FROM ' . DB_PREPEND . 'phpwcms_content WHERE ' . $where_content . 'cnt_alias = ' . _dbEscape($alias);
    $content_count = _dbQuery($sql, 'COUNT');

    if ($acat_count > 0 || $article_count > 0 || $content_count > 0) {
        $base_alias = preg_match('/^(.*?)-(\d+)$/', $alias, $match) && $match[1] !== '' ? $match[1] : $alias;

        $sql  = 'SELECT acat_alias AS a FROM ' . DB_PREPEND . 'phpwcms_articlecat WHERE ' . $where_acat . '(acat_alias = ' . _dbEscape($base_alias) . ' OR acat_alias LIKE ' . _dbEscape($base_alias, true, '', '-%') . ') ';
        $sql .= 'UNION ALL ';
        $sql .= 'SELECT article_alias AS a FROM ' . DB_PREPEND . 'phpwcms_article WHERE ' . $where_article . '(article_alias = ' . _dbEscape($base_alias) . ' OR article_alias LIKE ' . _dbEscape($base_alias, true, '', '-%') . ') ';
        $sql .= 'UNION ALL ';
        $sql .= 'SELECT cnt_alias AS a FROM ' . DB_PREPEND . 'phpwcms_content WHERE ' . $where_content . '(cnt_alias = ' . _dbEscape($base_alias) . ' OR cnt_alias LIKE ' . _dbEscape($base_alias, true, '', '-%') . ')';

        $all_existing = _dbQuery($sql);
        $all_alias = [];
        if (is_array($all_existing)) {
            foreach ($all_existing as $item) {
                $all_alias[$item['a']] = true;
            }
        }

        $alias = _get_unique_alias_candidate($alias, $all_alias, 2, true);
    }

    return $alias;
}

function proof_file_alias($file_id, $alias = '', $fallback_name = '') {
    return proof_alias($file_id, $alias, 'FILE', $fallback_name);
}

function _getTime($time='', $delimeter=':', $default_time='H:i:s') {

    $timeformat     = explode($delimeter, trim($default_time));
    $time           = explode($delimeter, trim($time));

    $hour           = 0;
    $minute         = 0;
    $second         = 0;

    for($x=0; $x<=2; $x++) {
        if(isset($timeformat[$x])) {
            switch(substr(trim($timeformat[$x]), 0, 1)) {
                case 'H':
                    if(isset($time[$x])) {
                        $hour = intval($time[$x]);
                        if($hour < 0 || $hour > 23) {
                            $hour = 0;
                        }
                    }
                    break;

                case 'i':
                    if(isset($time[$x])) {
                        $minute = intval($time[$x]);
                        if($minute < 0 || $minute > 59) {
                            $minute = 0;
                        }
                    }
                    break;

                case 's':
                    if(isset($time[$x])) {
                        $second = intval($time[$x]);
                        if($second < 0 || $second > 59) {
                            $second = 0;
                        }
                    }
                    break;
            }
        }
    }

    return str_replace(
        [
            $delimeter,
            'H',
            'i',
            's'
        ],
        [
            ':',
            substr('0' . $hour, -2),
            substr('0' . $minute, -2),
            substr('0' . $second, -2)
        ],
        $default_time
    );
}

function _getDate($date='', $delimeter='', $default_date='') {
    global $BL;

    $delimeter      = $delimeter == '' ? $BL['default_date_delimiter'] : $delimeter;
    $default_date   = $default_date == '' ? $BL['default_date'] : $default_date;

    $dateformat     = explode($delimeter, trim($default_date));
    $date           = explode($delimeter, trim($date));

    $day            = '';
    $month          = '';
    $year           = '';

    for($x=0; $x<=2; $x++) {
        if(isset($dateformat[$x])) {
            switch(strtolower(substr(trim($dateformat[$x]), 0, 1))) {
                case 'y':
                    if(isset($date[$x])) {
                        $year = intval($date[$x]);
                        if($year < 0) {
                            $year = '';
                        }
                    }
                    break;
                case 'd':
                    if(isset($date[$x])) {
                        $day = intval($date[$x]);
                        if($day < 1 || $day > 31) {
                            $day = '';
                        }
                    }
                    break;
                case 'm':
                    if(isset($date[$x])) {
                        $month = intval($date[$x]);
                        if($month < 1 || $month > 12) {
                            $month = '';
                        }
                    }
                    break;
            }
        }
    }

    if($year && $month && $day) {
        return substr('000' . $year, -4) . '-' . substr('0' . $month, -2) . '-' . substr('0' . $day, -2);
    }

    return '0000-00-00';
}

/**
 * @param string|array $categories
 * @param string|int $type
 * @param int $pid
 * @param string $seperator
 * @return void
 */
function _dbSaveCategories($categories = [], $type = '', $pid = 0, $seperator = ',') {
    $pid = (int)$pid;
    $type = trim($type);

    if (is_string($categories)) {
        $categories = convertStringToArray($categories, $seperator);
    }

    // delete all related categories first
    if ($type && $pid) {

        $sql = 'DELETE FROM ' . DB_PREPEND . 'phpwcms_categories WHERE cat_pid=' . $pid . " AND cat_type=" . _dbEscape($type);
        _dbQuery($sql, 'DELETE');

    }

    if (is_array($categories) && count($categories) && $type && $pid) {
        $data = [
            'cat_type' => $type,
            'cat_pid' => $pid,
            'cat_status' => 1,
            'cat_createdate' => date('Y-m-d H:i:s'),
            'cat_changedate' => date('Y-m-d H:i:s'),
            'cat_name' => '',
            'cat_info' => ''
        ];

        foreach ($categories as $value) {
            $value = trim($value);
            if ($value != '') {
                $data['cat_name'] = $value;
                _dbInsert('phpwcms_categories', $data);
            }
        }
    }
}

function setItemsPerPage($default=25) {
    if( isset($_GET['showipp']) ) {
        $ipp = (int)(is_numeric($_GET['showipp']) ? $_GET['showipp'] : $default);
        setcookie('phpwcmsBEItemsPerPage', (string) $ipp, time()+157680000, '/', getCookieDomain(), PHPWCMS_SSL, true);
    } elseif(isset($_SESSION['PAGE_FILTER']['IPP'])) {
        $ipp = $_SESSION['PAGE_FILTER']['IPP'];
    } elseif( isset($_COOKIE['phpwcmsBEItemsPerPage']) ) {
        $ipp = (int)$_COOKIE['phpwcmsBEItemsPerPage'];
    } else {
        $ipp = $default;
    }

    if(!isset($_SESSION['PAGE_FILTER'])) {
        $_SESSION['PAGE_FILTER'] = array();
    }

    $_SESSION['PAGE_FILTER']['IPP'] = $ipp;

    return $ipp;
}

function getItemsPerPageMenu($steps=array(5, 10, 25, 50, 100, 250, 0), $separator='', $id='news-paginate', $aria_label='') {

    $ipp = $_SESSION['PAGE_FILTER']['IPP'] ?? setItemsPerPage();

    if(!in_array($ipp, $steps)) {
        array_unshift($steps, $ipp);
    }

    $aria = $aria_label ? ' aria-label="' . html($aria_label) . '"' : ' aria-label="' . html($GLOBALS['BL']['be_ftptakeover_all'] ?? 'Items per page') . '"';

    $menu = [];
    foreach($steps as $x => $item) {
        $menu[$x]  = '<option value="'.$item.'"';
        if($ipp == $item) {
            $menu[$x] .= ' selected="selected"';
        }
        $menu[$x] .= '>';
        $menu[$x] .= $item == 0 ? $GLOBALS['BL']['be_ftptakeover_all'] : $item;
        $menu[$x] .= '</option>';
    }

    return '<select class="form-select form-select-sm" id="' . html($id) . '"' . $aria . '>' . implode($separator, $menu) . '</select>';
}

function initJsCalendar() {
    $GLOBALS['BE']['HEADER']['flatpickr-material.min.css'] = '<link href="include/inc_css/flatpickr-material.min.css" rel="stylesheet">';
    $GLOBALS['BE']['HEADER']['dayjs.js']                   = getJavaScriptSourceLink('include/inc_js/dayjs.min.js');
    $GLOBALS['BE']['HEADER']['flatpickr.js']               = getJavaScriptSourceLink('include/inc_js/flatpickr.min.js');
}

function initJsAutocompleter() {
    initJQuery();
    $GLOBALS['BE']['HEADER']['tom-select.js'] = getJavaScriptSourceLink('include/inc_js/jquery/tom-select.complete.min.js');
    $GLOBALS['BE']['HEADER']['tom-select.css'] = ' <link href="include/inc_css/tom-select.bootstrap5.css" rel="stylesheet" type="text/css" />';
}

function initAceEditor() {
    $GLOBALS['BE']['HEADER']['ace.js'] = getJavaScriptSourceLink('include/inc_js/ace/ace.js');
    if (isset($GLOBALS['BE']['CSP'])) {
        $GLOBALS['BE']['CSP']['worker-src'] = array_unique(array_merge($GLOBALS['BE']['CSP']['worker-src'] ?? ["'self'"], ['blob:']));
        $GLOBALS['BE']['CSP']['child-src'] = array_unique(array_merge($GLOBALS['BE']['CSP']['child-src'] ?? ["'self'"], ['blob:']));
    }
}

function initJQuery() {

    // add jQuery at first position and keep the key
    $GLOBALS['BE']['HEADER'] = array('jquery.js' => getJavaScriptSourceLink('include/inc_js/jquery/jquery-3.7.1.min.js')) + $GLOBALS['BE']['HEADER'];
}

// make phpwcms compatibility and upgrade check (auto-discovers and executes pending revisions)
function phpwcms_get_available_revisions() {
    $files = glob(PHPWCMS_ROOT . '/include/inc_lib/revision/r*.php');
    $revisions = array();
    if (is_array($files)) {
        foreach ($files as $file) {
            if (preg_match('/r(\d+)\.php$/i', $file, $matches)) {
                $revisions[] = (int)$matches[1];
            }
        }
        sort($revisions, SORT_NUMERIC);
    }
    return $revisions;
}

function phpwcms_mark_revision_checked($revision, $return_message = '') {
    $revision_str = (string)$revision;
    $GLOBALS['phpwcms']['check_r' . $revision_str] = true;
    $extra = empty($return_message) ? '' : "\n\nReturn:\n-------\n" . $return_message;
    $tmp_file = PHPWCMS_TEMP . 'r' . $revision_str . '.checked.tmp';
    $result = @write_textfile($tmp_file, date('Y-m-d H:i:s') . $extra);

    if (!empty($GLOBALS['db']) && function_exists('_setConfig')) {
        @_setConfig('revision_r' . $revision_str, 1, 'sys_revision');
    }

    return $result;
}

function phpwcms_revision_check($revision) {
    if (empty($revision)) {
        return false;
    }

    $target_revision = (int)$revision;
    $available_revisions = phpwcms_get_available_revisions();
    $GLOBALS['phpwcms']['revision_error'] = '';

    foreach ($available_revisions as $rev) {
        if ($rev > $target_revision) {
            break;
        }

        if (phpwcms_revision_check_temp($rev) === true) {
            continue;
        }

        $revision_file = PHPWCMS_ROOT . '/include/inc_lib/revision/r' . $rev . '.php';
        if (is_file($revision_file)) {
            include_once $revision_file;

            $revision_function = 'phpwcms_revision_r' . $rev;
            if (function_exists($revision_function) && empty($GLOBALS['phpwcms']['check_r' . $rev])) {
                $GLOBALS['phpwcms']['revision_return'] = '';
                $exec_result = false;
                try {
                    $exec_result = call_user_func($revision_function);
                } catch (Throwable $e) {
                    $exec_result = false;
                    $GLOBALS['phpwcms']['revision_return'] = $e->getMessage();
                }

                if ($exec_result !== false) {
                    $return_msg = $GLOBALS['phpwcms']['revision_return'];
                    phpwcms_mark_revision_checked($rev, $return_msg);
                } else {
                    $db_err = function_exists('_dbError') ? _dbError() : '';
                    $ret_msg = $GLOBALS['phpwcms']['revision_return'];
                    $err_msg = 'Database revision update r' . $rev . ' failed!';
                    if (!empty($ret_msg)) {
                        $err_msg .= ' Error: ' . $ret_msg;
                    }
                    if (!empty($db_err)) {
                        $err_msg .= ' MySQL Error: ' . $db_err;
                    }

                    $GLOBALS['phpwcms']['revision_error'] = $err_msg;
                    $log_msg = date('Y-m-d H:i:s') . ' [REVISION ERROR] ' . $err_msg . "\n";
                    if (defined('PHPWCMS_LOGDIR')) {
                        @file_put_contents(PHPWCMS_LOGDIR . '/phpwcms_revision_error.log', $log_msg, FILE_APPEND);
                    }
                    if (defined('PHPWCMS_TEMP')) {
                        @file_put_contents(PHPWCMS_TEMP . 'revision_error.log', $log_msg, FILE_APPEND);
                    }

                    trigger_error($err_msg, E_USER_WARNING);
                    return false;
                }
            }
        }
    }

    return true;
}

function phpwcms_run_pending_migrations($target_revision = null) {
    if ($target_revision === null && defined('PHPWCMS_REVISION')) {
        $target_revision = PHPWCMS_REVISION;
    }
    return phpwcms_revision_check($target_revision);
}

// check upgrade temp file or DB sysvalue for current revision
function phpwcms_revision_check_temp($revision) {
    if (empty($revision) || !preg_match('/^\d+$/', strval($revision))) {
        return null;
    }
    $revision_str = strval($revision);
    $tmp_file = PHPWCMS_TEMP . 'r' . $revision_str . '.checked.tmp';
    if (is_file($tmp_file)) {
        return true;
    }

    if (!empty($GLOBALS['db']) && function_exists('_getConfig')) {
        $db_checked = _getConfig('revision_r' . $revision_str, false);
        if (!empty($db_checked)) {
            $GLOBALS['phpwcms']['check_r' . $revision_str] = true;
            @write_textfile($tmp_file, date('Y-m-d H:i:s') . "\n\nSynced from DB sys_revision");
            return true;
        }
    }

    return false;
}

function get_language_name($lang='', $default=true) {

    if(empty($lang)) {
        if($default == false) {
            return '';
        }
        $lang = $GLOBALS['phpwcms']['default_lang'];
    }

    $lang = strtoupper($lang);

    return $GLOBALS['BL'][$lang] ?? $lang;

}

function get_pix_or_percent($val) {
    //is used to return configuration width/height values
    //whether based on pixel or percent
    //that's why the default empty return value is ""
    //returns a string
    $val = trim($val);
    $intval = (int)$val;
    if(strlen($val) > 1 && strlen($val)-1 == strrpos($val, '%') && $intval) {
        $val = ($intval > 100 ? '100' : $intval). '%';
    } else {
        $val = ($intval) ?: '';
    }
    return $val;
}

/**
 * Render select options for directory tree
 *
 * @param int $pid Parent directory ID
 * @param int $zid Selected directory ID
 * @param string $vor Prefix indentation string
 * @param int $userID User ID
 * @param string $vorzeichen Indentation increment character
 * @param array|null $tree In-memory directory tree (grouped by f_pid)
 * @return string
 */
function dir_menu($pid, $zid, $vor, $userID, $vorzeichen = ':', &$tree = null) {
    if ($tree === null) {
        $tree = [];
        $where_uid = empty($_SESSION['wcs_user_admin']) ? 'f.f_uid = ' . (int)$userID . ' AND ' : '';
        $sql  = 'SELECT f.f_id, f.f_pid, f.f_name, f.f_uid, u.usr_login FROM ' . DB_PREPEND . 'phpwcms_file f ';
        $sql .= 'LEFT JOIN ' . DB_PREPEND . 'phpwcms_user u ON u.usr_id = f.f_uid ';
        $sql .= 'WHERE ' . $where_uid . 'f.f_kid = 0 AND f.f_trash = 0 ORDER BY f.f_name';
        $rows = _dbQuery($sql);
        if (is_array($rows)) {
            foreach ($rows as $row) {
                $tree[(int)$row['f_pid']][] = $row;
            }
        }
    }

    $pid = (int)$pid;
    $current_uid = $_SESSION['wcs_user_id'] ?? 0;

    if (!empty($tree[$pid])) {
        foreach ($tree[$pid] as $row) {
            $dirname = html($row['f_name']);
            if ($current_uid != $row['f_uid']) {
                $dirname .= ' (' . html($row['usr_login'] ?? '') . ')';
            }
            $selected = ((int)$zid === (int)$row['f_id']) ? ' selected="selected"' : '';
            echo '<option value="' . (int)$row['f_id'] . '"' . $selected . '>' . $vor . ' ' . $dirname . "</option>\n";
            dir_menu($row['f_id'], $zid, $vor . $vorzeichen, $userID, $vorzeichen, $tree);
        }
    }

    return $vor;
}

function get_struct_alias($start_id=0, $parent_alias=false) {
    if($start_id == 0) {
        global $indexpage;

        if ($parent_alias && !empty($indexpage['acat_alias'])) {
            return $indexpage['acat_alias'];
        }

        if (!empty($indexpage['acat_pagetitle']) && strlen($indexpage['acat_name']) > strlen($indexpage['acat_pagetitle'])) {
            return strtolower(uri_sanitize($indexpage['acat_pagetitle']));
        }

        if(!empty($indexpage['acat_name'])) {
            return strtolower(uri_sanitize($indexpage['acat_name']));
        }

        return '';
    }
    $start_id = intval($start_id);

    $sql  = 'SELECT acat_id, acat_struct, acat_name, acat_pagetitle, acat_alias ';
    $sql .= 'FROM '.DB_PREPEND.'phpwcms_articlecat WHERE acat_trash=0 ';
    if($parent_alias) {
        $sql .= 'AND acat_id='.$start_id;
    } else {
        $sql .= 'ORDER BY acat_struct, acat_sort';
    }

    $result = _dbQuery($sql);
    $struct_array = array();

    if(isset($result[0]['acat_id'])) {
        if($parent_alias && !empty($result[0]['acat_alias'])) {
            return $result[0]['acat_alias'];
        }

        foreach($result as $value) {
            $value['acat_id'] = intval($value['acat_id']);
            $value['acat_struct'] = intval($value['acat_struct']);
            $struct_array[$value['acat_id']] = $value;
        }
    } else {
        return '';
    }

    $data = array();
    $visited = array();
    while($start_id && isset($struct_array[$start_id]) && !isset($visited[$start_id])) {
        $visited[$start_id] = true;
        if(!empty($struct_array[$start_id]['acat_pagetitle']) && strlen($struct_array[$start_id]['acat_name']) > strlen($struct_array[$start_id]['acat_pagetitle'])) {
            $data[$start_id] = strtolower(uri_sanitize($struct_array[$start_id]['acat_pagetitle']));
        } else {
            $data[$start_id] = strtolower(uri_sanitize($struct_array[$start_id]['acat_name']));
        }
        $start_id = $struct_array[$start_id]["acat_struct"];
    }
    if(!empty($struct_array[$start_id]['acat_pagetitle']) && strlen($struct_array[$start_id]['acat_name']) > strlen($struct_array[$start_id]['acat_pagetitle'])) {
        $data[$start_id] = strtolower(uri_sanitize($struct_array[$start_id]['acat_pagetitle']));
    } elseif(!empty($struct_array[$start_id]['acat_name'])) {
        $data[$start_id] = strtolower(uri_sanitize($struct_array[$start_id]['acat_name']));
    }

    return implode($GLOBALS['phpwcms']['alias_allow_slash'] ? '/' : '-', array_reverse($data));
}


/**
 * Correct the text in case phpwcms charset is different from UTF-8
 *
 * @access public
 * @param string $text
 * @param bool $js (default: false)
 * @return string
 */
function correct_charset($text='', $js=false) {

    if (strpos($text, '&') !== false) {
        $text = html_entity_decode($text, ENT_QUOTES | ENT_HTML5, 'UTF-8');
    }
    if(PHPWCMS_CHARSET !== 'utf-8' && phpwcms_seems_utf8($text)) {
        $text = mb_convert_encoding($text, PHPWCMS_CHARSET);
    }
    if($js) {
        $text = js_singlequote($text);
    }
    return $text;
}

/**
 * Render IPTC fields (for use in image info fields)
 *
 * @access public
 * @param mixed $iptc_data
 * @return array
 */
function render_iptc_fileinfo($iptc_data) {
    $iptc_rules = $GLOBALS['phpwcms']['iptc_rules'];
    $iptc_keys = $GLOBALS['phpwcms']['iptc_keys'];
    $fileinfo = array(
        'title' => '',
        'longinfo' => '',
        'copyright' => '',
        'alt' => ''
    );

    if(is_array($iptc_data) && count($iptc_data)) {
        $fileinfo['title'] = $iptc_rules['title'];
        $fileinfo['longinfo'] = $iptc_rules['longinfo'];
        $fileinfo['copyright'] = $iptc_rules['copyright'];
        $fileinfo['alt'] = $iptc_rules['alt'];

        foreach($iptc_data as $iptc_key => $iptc_value) {
            if(empty($iptc_value)) {
                $iptc_value = '';
            } elseif(is_array($iptc_value)) {
                if(empty($GLOBALS['phpwcms']['iptc_rules_multiple'])) {
                    $iptc_value = $iptc_value[0];
                } else {
                    $iptc_value = implode($GLOBALS['phpwcms']['iptc_separator'], $iptc_value);
                }
            }

            $fileinfo['title'] = render_custom_tag($fileinfo['title'], $iptc_key, $iptc_value);
            $fileinfo['longinfo'] = render_custom_tag($fileinfo['longinfo'], $iptc_key, $iptc_value);
            $fileinfo['copyright'] = render_custom_tag($fileinfo['copyright'], $iptc_key, $iptc_value);
            $fileinfo['alt'] = render_custom_tag($fileinfo['alt'], $iptc_key, $iptc_value);

            unset($iptc_keys[$iptc_key]);
        }
    }

    if(count($iptc_keys)) {
        foreach($fileinfo as $field => $value) {
            if((strpos($value, '{') !== false && strpos($value, '}') !== false) || strpos($value, '[/') !== false) {
                foreach($iptc_keys as $iptc_key => $iptc_value) {
                    if($fileinfo[$field] === '') {
                        break;
                    }
                    $fileinfo[$field] = trim( render_custom_tag($fileinfo[$field], $iptc_key, $iptc_value) );
                }
            }
        }
    }

    return $fileinfo;
}

function render_custom_tag($text='', $tag='', $value='', $value_else='', $case_sensitive=true) {
    $value = strval($value);
    $case_sensitive = $case_sensitive ? '' : 'i';
    if($value !== '') {
        $text = preg_replace('/\['.$tag.'\](.*?)\[\/'.$tag.'\]/'.$case_sensitive.'s', '$1', $text);
        $text = preg_replace('/\['.$tag.'_ELSE\].*?\[\/'.$tag.'_ELSE\]/'.$case_sensitive.'s', '', $text);
    } else {
        $text = preg_replace('/\['.$tag.'_ELSE\](.*?)\[\/'.$tag.'_ELSE\]/'.$case_sensitive.'s', '$1', $text);
        $text = preg_replace('/\['.$tag.'\].*?\[\/'.$tag.'\]/'.$case_sensitive.'s', '', $text);
        $text = str_replace('{'.$tag.'_ELSE}', $value_else, $text);
    }
    $text = str_replace('{'.$tag.'}', $value, $text);
    return $text;
}

function get_template_file_select($block='', $name='', $selected='', $path='') {
    if($block) {
        if($name === '') {
            $name = 'template_' . $block . '_file';
        }
        if($path === '') {
            $path = PHPWCMS_TEMPLATE . 'inc_cntpart/template-sections/' . $block;
        }
        if(is_dir($path)) {
            $files = get_tmpl_files($path, 'tmpl,html,tpl');
            if(count($files)) {
                $select = '<select name="' . $name .'" class="form-select form-select-sm mb-1">';
                $select .= '<option value=""';
                if($selected === '') {
                    $select .= ' selected="selected"';
                }
                $select .= '>' . $GLOBALS['BL']['be_admin_template_choose_file'] . '</option>';
                foreach($files as $file) {
                    $select .= '<option value="' . html($file) . '"';
                    if($selected === $file) {
                        $select .= ' selected="selected"';
                    }
                    $select .= '>inc_cntpart/template-sections/' . html($block.'/'.$file) . '</option>';
                }
                $select .= '</select><br />';
                return $select;
            }
        }
        return '<input type="hidden" name="' . $name . '" value="" />';
    }
    return '';
}
