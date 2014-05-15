<?php
/**
 * phpwcms content management system
 *
 * @author Oliver Georgi <oliver@phpwcms.de>
 * @copyright Copyright (c) 2002-2014, Oliver Georgi
 * @license http://opensource.org/licenses/GPL-2.0 GNU GPL-2
 * @link http://www.phpwcms.de
 *
 **/


session_start();
$phpwcms = array();
require_once ('../../config/phpwcms/conf.inc.php');
require_once ('../inc_lib/default.inc.php');
require_once (PHPWCMS_ROOT.'/include/inc_lib/dbcon.inc.php');

require_once (PHPWCMS_ROOT.'/include/inc_lib/general.inc.php');
checkLogin();
require_once (PHPWCMS_ROOT.'/include/inc_lib/backend.functions.inc.php');


$action = isset($_GET['action']) ? $_GET['action'] : '';

// export form results
if($action == 'exportformresult' && isset($_GET['fid']) && ($fid = intval($_GET['fid']))) {

	$data		= _dbQuery("SELECT *, DATE_FORMAT(formresult_createdate, '%Y-%m-%d %H:%i:%s') AS formresult_date  FROM ".DB_PREPEND.'phpwcms_formresult WHERE formresult_pid='.$fid);

	if(!$data) die('Just a problem!');

	$export		= array();
	$row		= 1;
	$export[0]	= array('#'=>'','#ID'=>'','#Date'=>'','#IP'=>'');

	// run all data first and combine array elements
	foreach($data as $key => $value) {

		// numbering starting at 1
		$export[$row]['#']		= $row;
		$export[$row]['#ID']	= $value['formresult_id'];
		$export[$row]['#Date']	= $value['formresult_createdate'];
		$export[$row]['#IP']	= $value['formresult_ip'];

		$val_array				= @unserialize($value['formresult_content']);
		if(is_array($val_array) && count($val_array)) {
			foreach($val_array as $a_key => $a_value) {
				$export[$row][$a_key]	= $a_value;
				$export[0][$a_key]		= '';
			}
		}

		$row++;
	}

	$elements = array();

	$elements[0]  = '	<tr>'.LF;
	foreach($export[0] as $key => $value) {
		$elements[0] .= '		<th>';
		$elements[0] .= $key;
		$elements[0] .= '</th>'.LF;
	}
	$elements[0] .= '	</tr>';


	for($x = 1; $x < $row; $x++) {

		$elements[$x]  = '	<tr>'.LF;
		foreach($export[0] as $key => $value) {

			$elements[$x] .= '		<td>';
			$elements[$x] .= isset($export[$x][$key]) ? html($export[$x][$key]) : '';
			$elements[$x] .= '</td>'.LF;

		}
		$elements[$x] .= '	</tr>';

		unset($export[$x]); // free memory
	}

	unset($export); // free memory

	$filename = date('Y-m-d_H-i-s').'_formresultID-'.$fid.'.xls';

	if (isset($_SERVER['HTTP_USER_AGENT']) && strstr($_SERVER['HTTP_USER_AGENT'], 'MSIE')) {
		// workaround for IE filename bug with multiple periods / multiple dots in filename
		// that adds square brackets to filename - eg. setup.abc.exe becomes setup[1].abc.exe
		$filename = preg_replace('/\./', '%2e', $filename, substr_count($filename, '.') - 1);
	}

	//header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
	//header('Last-Modified: '.gmdate('D, d M Y H:i:s GMT', time()) );
	//header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0, post-check=0, pre-check=0');


	//header('Content-type: application/force-download');
 	//header('Content-Disposition: attachment; filename="'.$filename.'"');
	//header('Content-Transfer-Encoding: binary'.LF);

	echo '<table border="1" cellspacing="1" cellpadding="2">'.LF;
	echo implode(LF, $elements);
	echo LF.'</table>';
	flush();
	exit();

} elseif($action == 'exportformresultdetail' && isset($_GET['fid']) && ($fid = intval($_GET['fid']))) {

	$data		= _getDatabaseQueryResult("SELECT *, DATE_FORMAT(formresult_createdate, '%Y-%m-%d %H:%i:%S') AS formresult_date FROM ".DB_PREPEND.'phpwcms_formresult WHERE formresult_pid='.$fid);

	if(!$data) die('Just a problem!');

	$export		= array();
	$row		= 1;
	$export[0]	= array('#ID'=>'','#Date'=>'','#IP'=>'');

	// run all data first and combine array elements
	foreach($data as $key => $value) {

		// numbering starting at 1
		$export[$row]['#ID']	= $value['formresult_id'];
		$export[$row]['#Date']	= $value['formresult_createdate'];
		$export[$row]['#IP']	= $value['formresult_ip'];

		$val_array				= @unserialize($value['formresult_content']);
		if(is_array($val_array) && count($val_array)) {
			foreach($val_array as $a_key => $a_value) {
				$export[$row][$a_key]	= $a_value;
				$export[0][$a_key]		= '';
			}
		}

		$row++;
	}


	header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
	header('Last-Modified: '.gmdate('D, d M Y H:i:s GMT', time()) );
	header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0, post-check=0, pre-check=0');

	$filename = date('Y-m-d_H-i-s').'_formresultdetailID-'.$fid.'.html';
	header('Content-type: text/html');
 	header('Content-Disposition: attachment; filename="'.$filename.'"');

	echo '<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">'.LF;
	echo '<html>'.LF;
	echo '<head>'.LF;
	echo '	<title>Formresult Detail Export ID'.$fid.'</title>'.LF;
	echo '	<style type="text/css">
	<!--
		body {font-family:Arial,Helvetica,sans-serif;font-size:10pt;}
		hr {margin:0;padding:0;height:1px;border:0;border-bottom:1px solid #666666;page-break-after:always;}
		td {font-size: 10pt;}
	// -->
	</style>'.LF;
	echo '</head>'.LF;
	echo '<body>'.LF;

	$elements = array();


	for($x = 1; $x < $row; $x++) {

		echo '<p style="font-weight:bold">page '.$x.' of '.($row-1).'</p>'.LF;
		echo '<table border="0" cellspacing="0" cellpadding="0" summary="ID:'.$fid.'">'.LF;

		foreach($export[0] as $key => $value) {

			echo '<tr>'.LF;
			echo '	<td valign="top" style="padding:0 5px 0 0;"><strong>'.ucfirst($key).'</strong></td>'.LF;
			echo '	<td valign="top" style="padding:0 0 3px 0;">';
			if(isset($export[$x][$key])) {

				if(strpos($export[$x][$key], '/'.$phpwcms["content_path"].'form/')) {

					$ext = which_ext($export[$x][$key]);
					$export[$x][$key] = html($export[$x][$key]);
					if($ext == 'jpg' || $ext == 'gif' || $ext == 'png') {
						echo '<img src="'.$export[$x][$key].'" border="0" alt="" />';
					} else {
						echo '<a href="'.$export[$x][$key].'">'.$export[$x][$key].'</a>';
					}

				} else {
					echo html($export[$x][$key]);
				}

			} else {

				echo '&nbsp;';

			}

			echo '</td>'.LF.'</tr>'.LF;

		}
		echo '</table>'.LF.'<hr />'.LF;

	}

	echo '</body>'.LF.'</html>';
	exit();


} elseif($action == 'exportsubscriber') {

	// export list of newsletter subscribers
	$_userInfo = array();

	// default settings for listing selected users
	$_userInfo['list_active']		= isset($_SESSION['list_active']) ? $_SESSION['list_active'] : 1;
	$_userInfo['list_inactive']		= isset($_SESSION['list_inactive']) ? $_SESSION['list_inactive'] : 1;

	$_userInfo['where_query']	= '';

	if($_userInfo['list_active'] != $_userInfo['list_inactive'] && $_userInfo['list_active']) {
		$_userInfo['where_query']	= ' WHERE address_verified=1';
	} elseif($_userInfo['list_active'] != $_userInfo['list_inactive'] && $_userInfo['list_inactive']) {
		$_userInfo['where_query']	= ' WHERE address_verified=0';
	}

	if(isset($_SESSION['filter_subscriber']) && count($_SESSION['filter_subscriber'])) {

		$_userInfo['filter_array'] = array();

		foreach($_SESSION['filter_subscriber'] as $_userInfo['filter']) {
			//usr_name, usr_login, usr_email
			$_userInfo['filter_array'][] = "CONCAT(address_email, address_name) LIKE '%".aporeplace($_userInfo['filter'])."%'";
		}
		if(count($_userInfo['filter_array'])) {

			$_userInfo['where_query'] .= $_userInfo['where_query'] ? ' AND ' : ' WHERE ';
			$_userInfo['where_query'] .= '('.implode('OR', $_userInfo['filter_array']).')';

		}

	}

	// get all subscribers from db
	$data = _dbQuery("SELECT *, DATE_FORMAT(address_tstamp, '%Y-%m-%d %H:%i:%s') AS addate FROM ".DB_PREPEND."phpwcms_address".$_userInfo['where_query'].' ORDER BY address_tstamp');
	if($data) {

		// send header data
		$filename = date('Y-m-d_H-i-s').'_newsletterRecipients.xls';

		header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
		header('Last-Modified: '.gmdate('D, d M Y H:i:s GMT', time()) );
		header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0, post-check=0, pre-check=0');

		header('Content-type: application/vnd-ms-excel');
 		header('Content-Disposition: attachment; filename="'.$filename.'"');

		echo '<table border="1" cellspacing="1" cellpadding="2">'.LF;

		// 1st row - column names
		echo '<tr>'.LF;

		echo '<th>verified</th>'.LF;
		echo '<th>email</th>'.LF;
		echo '<th>name</th>'.LF;
		echo '<th>last change</th>'.LF;
		echo '<th>all</th>'.LF;

		// now check subscriptions
		$_userInfo['subscriptions'] = _dbQuery("SELECT * FROM ".DB_PREPEND."phpwcms_subscription ORDER BY subscription_name");

		$_userInfo['channel'] = array();

		if($_userInfo['subscriptions']) {

			$x = 0;
			foreach($_userInfo['subscriptions'] as $value) {

				// echo channel column name
				echo '<th>'.html($value['subscription_name']).'</th>'.LF;
				$_userInfo['channel'][$x] = $value['subscription_id'];
				$x++;

			}

		}

		echo '</tr>'.LF;

		$_userInfo['count'] = count($_userInfo['channel']);

		foreach($data as $value) {

			// make check if all szubscriptions or special
			if($value['address_subscription']) {

				$value['all'] = '';

				$value['address_subscription']	= unserialize($value['address_subscription']);
				if(in_array(0, $value['address_subscription'])) $value['all'] = 'X';

			} else {

				$value['all'] = 'X';

			}


			echo '<tr>'.LF;
			echo '<td align="center">'.($value['address_verified'] ? 'X' : '').'</td>'.LF;
			echo '<td>'.html($value['address_email']).'</td>'.LF;
			echo '<td>'.html($value['address_name']).'</td>'.LF;
			echo '<td>'.html($value['addate']).'</td>'.LF;
			echo '<td align="center">'.$value['all'].'</td>'.LF;

			// custom subscriptions
			if($_userInfo['count']) {

				if($value['all'] === '') {

					for($x=0; $x < $_userInfo['count']; $x++) {

						echo '<td align="center">';
						echo in_array($_userInfo['channel'][$x], $value['address_subscription']) ? 'X' : '';
						echo '</td>'.LF;

					}


				} else {

					echo str_repeat('<td></td>'.LF, $_userInfo['count']);

				}

			}

			echo '</tr>'.LF;

		}

		echo '</table>';

	}
	exit();


} else {

	die('Just a problem!');

}


?>