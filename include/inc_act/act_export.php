<?php
/**
 * phpwcms content management system
 *
 * @author Oliver Georgi <og@phpwcms.org>
 * @copyright Copyright (c) 2002-2025, Oliver Georgi
 * @license http://opensource.org/licenses/GPL-2.0 GNU GPL-2
 * @link http://www.phpwcms.org
 **/

$phpwcms = array('SESSION_START' => true);
require_once '../../include/config/conf.inc.php';
require_once '../inc_lib/default.inc.php';
require_once PHPWCMS_ROOT . '/include/inc_lib/helper.session.php';
require_once PHPWCMS_ROOT . '/include/inc_lib/dbcon.inc.php';
require_once PHPWCMS_ROOT . '/include/inc_lib/general.inc.php';

$action = isset($_GET['action']) ? $_GET['action'] : '';
$apikey = '';
$fid = isset($_GET['fid']) ? intval($_GET['fid']) : 0;

if (empty($_GET['apikey']) || $action !== 'exportformresult') {
    checkLogin();
    validate_csrf_tokens();
} else {
    $apikey = clean_slweg($_GET['apikey']);
    if (strlen($apikey) !== 16) {
        $apikey = '';
    } else {
        $where_apikey = _dbEscape('direct_download_apikey";s:16:"' . $apikey . '"', true, '%', '%');
        $where_allow = _dbEscape('direct_download";i:1', true, '%', '%');
        $form = _dbGet(
            'phpwcms_articlecontent',
            'acontent_id, acontent_form',
            'acontent_id=' . $fid . ' AND acontent_type=23 AND acontent_trash=0 AND acontent_form LIKE ' . $where_allow . ' AND acontent_form LIKE ' . $where_apikey
        );
        if (empty($form[0]['acontent_id']) || intval($form[0]['acontent_id']) !== $fid) {
            $apikey = '';
        }
    }
    if (!$apikey) {
        echo '<html><body><h1>403 Forbidden</h1></body></html>';
        headerRedirect('', 403, false);
        die();
    }
}
require_once PHPWCMS_ROOT . '/include/inc_lib/backend.functions.inc.php';

use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;

// export form results
if ($action === 'exportformresult' && $fid) {

    $data = _dbQuery("SELECT *, DATE_FORMAT(formresult_createdate, '%Y-%m-%d %H:%i:%s') AS formresult_date FROM " . DB_PREPEND . 'phpwcms_formresult WHERE formresult_pid=' . $fid);

    if (!$data) {
        die('No data returned or another error processing the export.');
    }

    $export = array();
    $row = 1;
    $export[0] = array('#' => 1, '#ID' => 2, '#Date' => 3, '#IP' => 4);
    $col = 5;

    // run all data first and combine array elements
    foreach ($data as $key => $value) {

        // numbering starting at 1
        $export[$row]['#'] = $row;
        $export[$row]['#ID'] = $value['formresult_id'];
        $export[$row]['#Date'] = $value['formresult_createdate'];
        $export[$row]['#IP'] = $value['formresult_ip'];

        $val_array = @unserialize($value['formresult_content'], ['allowed_classes' => false]);
        if (is_array($val_array) && count($val_array)) {
            foreach ($val_array as $a_key => $a_value) {
                $export[$row][$a_key] = $a_value;
                if (!isset($export[0][$a_key])) {
                    $export[0][$a_key] = $col;
                    $col++;
                }
            }
        }

        $row++;
    }

    $filename = date('Y-m-d_H-i-s') . '_formresultID-' . $fid;

    if (IS_PHP8 || version_compare(PHP_VERSION, '7.3.0', '>=')) {

        $spreadsheet = new Spreadsheet();
        $spreadsheet->getProperties()
                    ->setCreator('phpwcms')
                    ->setLastModifiedBy('phpwcms')
                    ->setTitle('phpwcms Form Result Export ID ' . $fid)
                    ->setSubject('phpwcms Form Result Export ID ' . $fid);

        $sheet = $spreadsheet->setActiveSheetIndex(0);

        // First row contains column names
        foreach($export[0] as $column_title => $column) {
            $sheet->setCellValueByColumnAndRow($column, 1, $column_title);
        }

        for ($x = 1; $x < $row; $x++) {
            $current = $export[$x];
            foreach($export[0] as $column_title => $column) {
                $column_value = isset($current[$column_title]) ? $current[$column_title] : '';
                $sheet->setCellValueByColumnAndRow($column, $x+1, $column_value);
            }
        }

        // Redirect output to a client’s web browser (Xlsx)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '.xlsx"');
        header('Cache-Control: max-age=1');

        header('Expires: Mon, 21 Jun 2022 00:00:00 GMT');
        header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');
        header('Cache-Control: cache, must-revalidate');
        header('Pragma: public');

        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
        $writer->save('php://output');

    } else {

        $elements = array();

        $elements[0] = '<tr>';
        foreach ($export[0] as $key => $value) {
            $elements[0] .= '<th>';
            $elements[0] .= $key;
            $elements[0] .= '</th>';
        }
        $elements[0] .= '</tr>';

        for ($x = 1; $x < $row; $x++) {

            $elements[$x] = '<tr>';
            foreach ($export[0] as $key => $value) {

                $elements[$x] .= '<td>';
                $elements[$x] .= isset($export[$x][$key]) ? html($export[$x][$key]) : '';
                $elements[$x] .= '</td>';
            }
            $elements[$x] .= '</tr>';
        }

        if (isset($_SERVER['HTTP_USER_AGENT']) && strstr($_SERVER['HTTP_USER_AGENT'], 'MSIE')) {
            // workaround for IE filename bug with multiple periods / multiple dots in filename
            // that adds square brackets to filename - eg. setup.abc.exe becomes setup[1].abc.exe
            $filename = preg_replace('/\./', '%2e', $filename, substr_count($filename, '.') - 1);
        }

        header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
        header('Last-Modified: ' . gmdate('D, d M Y H:i:s GMT', time()));
        header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0, post-check=0, pre-check=0');

        header('Content-type: text/html; charset=' . PHPWCMS_CHARSET);
        header('Content-Disposition: attachment;filename="' . $filename . '.html"');

        echo '<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">';
        echo '<html><head>';
        echo '<meta http-equiv="Content-Type" content="text/html; charset=' . PHPWCMS_CHARSET . '"/>';
        echo '<style type="text/css">body {font-family:sans-serif;font-size:10pt;} td {mso-number-format:\@;}</style>';
        echo '</head><body>';
        echo '<table border="1" cellspacing="1" cellpadding="2">';
        echo implode(LF, $elements);
        echo '</table></body></html>';
        flush();
    }

    exit;

} elseif ($action == 'exportformresultdetail' && $fid) {

    $data = _dbQuery("SELECT *, DATE_FORMAT(formresult_createdate, '%Y-%m-%d %H:%i:%S') AS formresult_date FROM " . DB_PREPEND . 'phpwcms_formresult WHERE formresult_pid=' . $fid);

    if (!$data) {
        die('No data returned or another error processing the export.');
    }

    $export = array();
    $row = 1;
    $export[0] = array('#ID' => '', '#Date' => '', '#IP' => '');

    // run all data first and combine array elements
    foreach ($data as $key => $value) {

        // numbering starting at 1
        $export[$row]['#ID'] = $value['formresult_id'];
        $export[$row]['#Date'] = $value['formresult_createdate'];
        $export[$row]['#IP'] = $value['formresult_ip'];

        $val_array = @unserialize($value['formresult_content'], ['allowed_classes' => false]);
        if (is_array($val_array) && count($val_array)) {
            foreach ($val_array as $a_key => $a_value) {
                $export[$row][$a_key] = $a_value;
                $export[0][$a_key] = '';
            }
        }

        $row++;
    }

    header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
    header('Last-Modified: ' . gmdate('D, d M Y H:i:s GMT', time()));
    header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0, post-check=0, pre-check=0');

    $filename = date('Y-m-d_H-i-s') . '_formresultdetailID-' . $fid . '.html';
    header('Content-type: text/html; charset=' . PHPWCMS_CHARSET);
    header('Content-Disposition: attachment; filename="' . $filename . '"');

    echo '<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">';
    echo '<html>';
    echo '<head>';
    echo '<meta http-equiv="Content-Type" content="text/html; charset=' . PHPWCMS_CHARSET . '"/>';
    echo '<title>Formresult Detail Export ID' . $fid . '</title>';
    echo '<style type="text/css">
        body {font-family:Arial,Helvetica,sans-serif;font-size:10pt;}
        hr {margin:0;padding:0;height:1px;border:0;border-bottom:1px solid #666666;page-break-after:always;}
        td {mso-number-format:\@;font-size:10pt;}
    </style>';
    echo '</head>';
    echo '<body>';

    for ($x = 1; $x < $row; $x++) {

        echo '<p style="font-weight:bold">page ' . $x . ' of ' . ($row - 1) . '</p>';
        echo '<table border="0" cellspacing="0" cellpadding="0" summary="ID:' . $fid . '">';

        foreach ($export[0] as $key => $value) {

            echo '<tr>';
            echo '<td valign="top" style="padding:0 5px 0 0;"><strong>' . ucfirst($key) . '</strong></td>';
            echo '<td valign="top" style="padding:0 0 3px 0;">';
            if (isset($export[$x][$key])) {

                if (strpos($export[$x][$key], '/' . $phpwcms["content_path"] . 'form/')) {

                    $ext = which_ext($export[$x][$key]);
                    $export[$x][$key] = html($export[$x][$key]);
                    if ($ext == 'jpg' || $ext == 'gif' || $ext == 'png' || (PHPWCMS_WEBP && $ext == 'webp')) {
                        echo '<img src="' . $export[$x][$key] . '" border="0" alt="" />';
                    } else {
                        echo '<a href="' . $export[$x][$key] . '">' . $export[$x][$key] . '</a>';
                    }
                } else {
                    echo html($export[$x][$key]);
                }
            }

            echo '</td></tr>';
        }
        echo '</table><hr />';
    }

    echo '</body></html>';
    exit;

} elseif ($action == 'exportsubscriber' && !empty($_SESSION["wcs_user_admin"])) {

    // export list of newsletter subscribers
    $_userInfo = array();

    // default settings for listing selected users
    $_userInfo['list_active'] = isset($_SESSION['list_active']) ? $_SESSION['list_active'] : 1;
    $_userInfo['list_inactive'] = isset($_SESSION['list_inactive']) ? $_SESSION['list_inactive'] : 1;

    $_userInfo['where_query'] = '';

    if ($_userInfo['list_active'] != $_userInfo['list_inactive'] && $_userInfo['list_active']) {
        $_userInfo['where_query'] = ' WHERE address_verified=1';
    } elseif ($_userInfo['list_active'] != $_userInfo['list_inactive'] && $_userInfo['list_inactive']) {
        $_userInfo['where_query'] = ' WHERE address_verified=0';
    }

    if (isset($_SESSION['filter_subscriber']) && count($_SESSION['filter_subscriber'])) {

        $_userInfo['filter_array'] = array();

        foreach ($_SESSION['filter_subscriber'] as $_userInfo['filter']) {
            //usr_name, usr_login, usr_email
            $_userInfo['filter_array'][] = "CONCAT(address_email, address_name) LIKE " . _dbEscape($_userInfo['filter'], true, '%', '%');
        }
        if (count($_userInfo['filter_array'])) {
            $_userInfo['where_query'] .= $_userInfo['where_query'] ? ' AND ' : ' WHERE ';
            $_userInfo['where_query'] .= '(' . implode('OR', $_userInfo['filter_array']) . ')';
        }
    }

    // get all subscribers from db
    $data = _dbQuery("SELECT *, DATE_FORMAT(address_tstamp, '%Y-%m-%d %H:%i:%s') AS addate FROM " . DB_PREPEND . "phpwcms_address" . $_userInfo['where_query'] . ' ORDER BY address_tstamp');
    if ($data) {

        // send header data
        $filename = date('Y-m-d_H-i-s') . '_newsletterRecipients.html';

        header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
        header('Last-Modified: ' . gmdate('D, d M Y H:i:s GMT', time()));
        header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0, post-check=0, pre-check=0');

        header('Content-type: text/html; charset=' . PHPWCMS_CHARSET);
        header('Content-Disposition: attachment; filename="' . $filename . '"');

        echo '<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">';
        echo '<html><head>';
        echo '<meta http-equiv="Content-Type" content="text/html; charset=' . PHPWCMS_CHARSET . '"/>';
        echo '<style type="text/css">body {font-family:sans-serif;font-size:10pt;} td {mso-number-format:\@;}</style>';
        echo '</head><body>';
        echo '<table border="1" cellspacing="1" cellpadding="2">';

        // 1st row - column names
        echo '<tr>';
        echo '<th>verified</th>';
        echo '<th>email</th>';
        echo '<th>name</th>';
        echo '<th>last change</th>';
        echo '<th>all</th>';

        // now check subscriptions
        $_userInfo['subscriptions'] = _dbQuery("SELECT * FROM " . DB_PREPEND . "phpwcms_subscription ORDER BY subscription_name");

        $_userInfo['channel'] = array();

        if ($_userInfo['subscriptions']) {

            $x = 0;
            foreach ($_userInfo['subscriptions'] as $value) {

                // echo channel column name
                echo '<th>' . html($value['subscription_name']) . '</th>';
                $_userInfo['channel'][$x] = $value['subscription_id'];
                $x++;
            }
        }

        echo '</tr>';

        $_userInfo['count'] = count($_userInfo['channel']);

        foreach ($data as $value) {

            // make check if all szubscriptions or special
            if ($value['address_subscription']) {

                $value['all'] = '';

                $value['address_subscription'] = unserialize($value['address_subscription'], ['allowed_classes' => false]);
                if (in_array(0, $value['address_subscription'])) {
                    $value['all'] = 'X';
                }
            } else {

                $value['all'] = 'X';
            }

            echo '<tr>';
            echo '<td align="center">' . ($value['address_verified'] ? 'X' : '') . '</td>';
            echo '<td>' . html($value['address_email']) . '</td>';
            echo '<td>' . html($value['address_name']) . '</td>';
            echo '<td>' . html($value['addate']) . '</td>';
            echo '<td align="center">' . $value['all'] . '</td>';

            // custom subscriptions
            if ($_userInfo['count']) {

                if ($value['all'] === '') {

                    for ($x = 0; $x < $_userInfo['count']; $x++) {
                        echo '<td align="center">';
                        echo in_array($_userInfo['channel'][$x], $value['address_subscription']) ? 'X' : '';
                        echo '</td>';
                    }
                } else {

                    echo str_repeat('<td></td>', $_userInfo['count']);
                }
            }

            echo '</tr>';
        }

        echo '</table></body></html>';
    }

    exit;

} else {

    die('No data returned or another error processing the export.');

}
