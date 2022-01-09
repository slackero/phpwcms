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
if (!defined('PHPWCMS_ROOT')) {
    die("You Cannot Access This Script Directly, Have a Nice Day.");
}
// ----------------------------------------------------------------

// try

if (isset($_GET['edit'])) {
    $plugin['id'] = intval($_GET['edit']);
} else {
    $plugin['id'] = 0;
}

// process post form
if (isset($_POST['adcampaign_title'])) {
    $plugin['data'] = array(
        'adcampaign_id' => intval($_POST['adcampaign_id']),
        'adcampaign_title' => clean_slweg($_POST['adcampaign_title']),
        'adcampaign_created' => date('Y-m-d H:i:s'),
        'adcampaign_changed' => date('Y-m-d H:i:s'),
        'adcampaign_comment' => clean_slweg($_POST['adcampaign_comment']),
        'adcampaign_data' => array(
            'width' => intval($_POST['adcampaign_width']),
            'height' => intval($_POST['adcampaign_height']),
            'unique' => empty($_POST['adcampaign_unique']) ? 0 : 1,
            'url' => clean_slweg($_POST['adcampaign_url']),
            'target' => clean_slweg($_POST['adcampaign_target']),
            'image' => isset($_POST['adcampaign_image']) ? clean_slweg($_POST['adcampaign_image']) : '',
            'flash' => isset($_POST['adcampaign_flash']) ? clean_slweg($_POST['adcampaign_flash']) : '',
            'html' => slweg($_POST['adcampaign_html']),
            'alt_text' => clean_slweg($_POST['adcampaign_alt_text']),
            'title_text' => clean_slweg($_POST['adcampaign_title_text']),
            'css' => isset($_POST['adcampaign_css']) ? clean_slweg($_POST['adcampaign_css']) : '',
            'bgcolor' => clean_slweg($_POST['adcampaign_bgcolor']),
            'bordercolor' => clean_slweg($_POST['adcampaign_bordercolor']),
            'flashversion' => clean_slweg($_POST['adcampaign_flashversion']),
        ),
        'adcampaign_status' => empty($_POST['adcampaign_status']) ? 0 : 1,
        'adcampaign_date_start' => clean_slweg($_POST['adcampaign_date_start']),
        'adcampaign_date_end' => clean_slweg($_POST['adcampaign_date_end']),
        'adcampaign_time_start' => clean_slweg($_POST['adcampaign_time_start']),
        'adcampaign_time_end' => clean_slweg($_POST['adcampaign_time_end']),
        'adcampaign_type' => empty($_POST['adcampaign_type']) ? 0 : intval($_POST['adcampaign_type']),
        'adcampaign_place' => intval($_POST['adcampaign_place']),
        'adcampaign_maxview' => intval($_POST['adcampaign_max_views']),
        'adcampaign_maxclick' => intval($_POST['adcampaign_max_click']),
        'adcampaign_maxviewuser' => intval($_POST['adcampaign_max_viewuser']),
        'adcampaign_duplicate' => empty($_POST['adcampaign_duplicate']) ? 0 : 1,
    );

    if (empty($plugin['data']['adcampaign_data']['flashversion'])) {
        $plugin['data']['adcampaign_data']['flashversion'] = '7';
    }

    if ($plugin['data']['adcampaign_type'] > 4) {
        $plugin['data']['adcampaign_type'] = 0;
    }

    // clean up date/time
    include_once $phpwcms['modules'][$module]['path'] . 'inc/processing.datetime.inc.php';

    if (empty($plugin['data']['adcampaign_title'])) {

        $plugin['error']['adcampaign_title'] = 1;
    }

    // handle media upload
    if ($plugin['data']['adcampaign_id'] && !empty($_FILES['adcampaign_upload_image']['name'])) {
        // image upload
        $plugin['data']['upload'] = saveUploadedFile('adcampaign_upload_image', PHPWCMS_CONTENT . PHPWCMS_ADS_DIR . '/' . $plugin['data']['adcampaign_id'] . '/', '', '1,2,3,18', '1,4');
        if ($plugin['data']['upload']['status']) {
            $plugin['data']['adcampaign_data']['image'] = $plugin['data']['upload']['rename'];
            if (!is_file(PHPWCMS_CONTENT . PHPWCMS_ADS_DIR . '/' . $plugin['data']['adcampaign_id'] . '/.htaccess')) {
                @file_put_contents(PHPWCMS_CONTENT . PHPWCMS_ADS_DIR . '/' . $plugin['data']['adcampaign_id'] . '/.htaccess', "<Files *.php>\nOrder allow,deny\nDeny from all\n</Files>");
            }
            unset($_POST['save']);
        } else {
            $plugin['error']['image'] = $plugin['data']['upload']['error'];
        }
    }
    if ($plugin['data']['adcampaign_id'] && !empty($_FILES['adcampaign_upload_flash']['name'])) {
        // flash upload
        $plugin['data']['upload'] = saveUploadedFile('adcampaign_upload_flash', PHPWCMS_CONTENT . PHPWCMS_ADS_DIR . '/' . $plugin['data']['adcampaign_id'] . '/', 'swf', '', '1,4');
        if ($plugin['data']['upload']['status']) {
            $plugin['data']['adcampaign_data']['flash'] = $plugin['data']['upload']['rename'];
            if (!is_file(PHPWCMS_CONTENT . PHPWCMS_ADS_DIR . '/' . $plugin['data']['adcampaign_id'] . '/.htaccess')) {
                @file_put_contents(PHPWCMS_CONTENT . PHPWCMS_ADS_DIR . '/' . $plugin['data']['adcampaign_id'] . '/.htaccess', "<Files *.php>\nOrder allow,deny\nDeny from all\n</Files>");
            }
            unset($_POST['save']);
        } else {
            $plugin['error']['flash'] = $plugin['data']['upload']['error'];
        }
    }
    if ($plugin['data']['adcampaign_id'] && !empty($_FILES['adcampaign_upload_css']['name'])) {
        // css upload
        $plugin['data']['upload'] = saveUploadedFile('adcampaign_upload_css', PHPWCMS_CONTENT . PHPWCMS_ADS_DIR . '/' . $plugin['data']['adcampaign_id'] . '/', 'css', '', '1,4');
        if ($plugin['data']['upload']['status']) {
            $plugin['data']['adcampaign_data']['css'] = $plugin['data']['upload']['rename'];
            if (!is_file(PHPWCMS_CONTENT . PHPWCMS_ADS_DIR . '/' . $plugin['data']['adcampaign_id'] . '/.htaccess')) {
                @file_put_contents(PHPWCMS_CONTENT . PHPWCMS_ADS_DIR . '/' . $plugin['data']['adcampaign_id'] . '/.htaccess', "<Files *.php>\nOrder allow,deny\nDeny from all\n</Files>");
            }
            unset($_POST['save']);
        } else {
            $plugin['error']['css'] = $plugin['data']['upload']['error'];
        }
    }

    if (!isset($plugin['error'])) {
        if ($plugin['data']['adcampaign_duplicate']) {
            $plugin['data']['adcampaign_id'] = 0;
        }

        if ($plugin['data']['adcampaign_id']) {
            // UPDATE
            $sql = 'UPDATE ' . DB_PREPEND . 'phpwcms_ads_campaign SET ';
            $sql .= "adcampaign_changed='" . aporeplace($plugin['data']['adcampaign_changed']) . "', ";
            $sql .= "adcampaign_status=" . $plugin['data']['adcampaign_status'] . ", ";
            $sql .= "adcampaign_title='" . aporeplace($plugin['data']['adcampaign_title']) . "', ";
            $sql .= "adcampaign_comment='" . aporeplace($plugin['data']['adcampaign_comment']) . "', ";
            $sql .= "adcampaign_datestart='" . aporeplace($plugin['data']['adcampaign_datestart']) . "', ";
            $sql .= "adcampaign_dateend='" . aporeplace($plugin['data']['adcampaign_dateend']) . "', ";
            $sql .= "adcampaign_maxview=" . $plugin['data']['adcampaign_maxview'] . ", ";
            $sql .= "adcampaign_maxclick=" . $plugin['data']['adcampaign_maxclick'] . ", ";
            $sql .= "adcampaign_maxviewuser=" . $plugin['data']['adcampaign_maxviewuser'] . ", ";
            $sql .= "adcampaign_type=" . $plugin['data']['adcampaign_type'] . ", ";
            $sql .= "adcampaign_place=" . $plugin['data']['adcampaign_place'] . ", ";
            $sql .= "adcampaign_data='" . aporeplace(serialize($plugin['data']['adcampaign_data'])) . "' ";
            $sql .= "WHERE adcampaign_id=" . $plugin['data']['adcampaign_id'];

            if (@_dbQuery($sql, 'UPDATE')) {
                if (isset($_POST['save'])) {
                    headerRedirect(decode_entities(MODULE_HREF) . '&listcampaign=1');
                }
            } else {
                $plugin['error']['update'] = _dbError();
            }
        } else {
            // INSERT
            $sql = 'INSERT INTO ' . DB_PREPEND . 'phpwcms_ads_campaign (';
            $sql .= "   adcampaign_created, adcampaign_changed, adcampaign_status, adcampaign_title, ";
            $sql .= "   adcampaign_comment, adcampaign_datestart, adcampaign_dateend, ";
            $sql .= "   adcampaign_maxview, adcampaign_maxclick, adcampaign_maxviewuser, ";
            $sql .= "   adcampaign_type, adcampaign_place, adcampaign_data";
            $sql .= ') VALUES (';
            $sql .= "   '" . aporeplace($plugin['data']['adcampaign_created']) . "', ";
            $sql .= "   '" . aporeplace($plugin['data']['adcampaign_changed']) . "', ";
            $sql .= $plugin['data']['adcampaign_status'] . ", ";
            $sql .= "   '" . aporeplace($plugin['data']['adcampaign_title']) . "', ";
            $sql .= "   '" . aporeplace($plugin['data']['adcampaign_comment']) . "', ";
            $sql .= "   '" . aporeplace($plugin['data']['adcampaign_datestart']) . "', ";
            $sql .= "   '" . aporeplace($plugin['data']['adcampaign_dateend']) . "', ";
            $sql .= $plugin['data']['adcampaign_maxview'] . ", ";
            $sql .= $plugin['data']['adcampaign_maxclick'] . ", ";
            $sql .= $plugin['data']['adcampaign_maxviewuser'] . ", ";
            $sql .= $plugin['data']['adcampaign_type'] . ", ";
            $sql .= $plugin['data']['adcampaign_place'] . ", ";
            $sql .= "   '" . aporeplace(serialize($plugin['data']['adcampaign_data'])) . "'";
            $sql .= ')';

            if ($plugin_new_id = @_dbQuery($sql, 'INSERT')) {
                if (isset($_POST['save'])) {
                    headerRedirect(decode_entities(MODULE_HREF) . '&listcampaign=1');
                } elseif (!empty($plugin_new_id['INSERT_ID'])) {
                    headerRedirect(decode_entities(MODULE_HREF) . '&campaign=1&edit=' . $plugin_new_id['INSERT_ID']);
                }
            } else {
                $plugin['error']['update'] = _dbError();
            }
        }
    }
}

// try to read entry from database
if ($plugin['id'] && !isset($plugin['error'])) {
    $sql = 'SELECT *,';
    $sql .= "DATE_FORMAT(adcampaign_datestart, '%d" . $BLM['date_delimiter'] . "%m" . $BLM['date_delimiter'] . "%Y') AS adcampaign_date_start, ";
    $sql .= "DATE_FORMAT(adcampaign_dateend,   '%d" . $BLM['date_delimiter'] . "%m" . $BLM['date_delimiter'] . "%Y') AS adcampaign_date_end, ";
    $sql .= "DATE_FORMAT(adcampaign_datestart, '%H:%i') AS adcampaign_time_start, ";
    $sql .= "DATE_FORMAT(adcampaign_dateend,   '%H:%i') AS adcampaign_time_end ";
    $sql .= 'FROM ' . DB_PREPEND . 'phpwcms_ads_campaign WHERE adcampaign_id=' . $plugin['id'];
    $plugin['data'] = _dbQuery($sql);
    $plugin['data'] = $plugin['data'][0];
    $plugin['data']['adcampaign_data'] = @unserialize($plugin['data']['adcampaign_data']);
    if (!is_array($plugin['data']['adcampaign_data'])) {
        $plugin['data']['adcampaign_data'] = array(
            'unique' => 0,
            'width' => '',
            'height' => '',
            'url' => '',
            'target' => '_blank',
            'image' => '',
            'flash' => '',
            'html' => '',
            'alt_text' => '',
            'title_text' => '',
            'css' => '',
            'bgcolor' => '',
            'bordercolor' => '',
            'flashversion' => '7',
        );
    }
}

// default values
if (empty($plugin['data'])) {
    $plugin['data'] = array(
        'adcampaign_id' => 0,
        'adcampaign_title' => '',
        'adcampaign_created' => '',
        'adcampaign_changed' => date('Y-m-d H:i:s'),
        'adcampaign_comment' => '',
        'adcampaign_data' => array(
            'unique' => 0,
            'width' => '',
            'height' => '',
            'url' => '',
            'target' => '_blank',
            'image' => '',
            'flash' => '',
            'html' => '',
            'alt_text' => '',
            'title_text' => '',
            'css' => '',
            'bgcolor' => '',
            'bordercolor' => '',
            'flashversion' => '7',
        ),
        'adcampaign_status' => 0,
        'adcampaign_date_start' => '',
        'adcampaign_date_end' => '',
        'adcampaign_time_start' => '00:00',
        'adcampaign_time_end' => '23:59',
        'adcampaign_place' => 0,
        'adcampaign_type' => 0,
        'adcampaign_maxview' => 0,
        'adcampaign_maxclick' => 0,
        'adcampaign_maxviewuser' => 0,
    );
}
