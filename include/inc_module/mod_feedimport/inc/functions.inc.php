<?php

function decformat($num=0) {

	return number_format(floatval($num), $GLOBALS['BLM']['decimals'], $GLOBALS['BLM']['dec_point'], $GLOBALS['BLM']['thousands_sep']);

}

function clean_preformatted_number($number=0, $auto=true) {

	if(is_string($number) && strlen($number) > 3) {

		if($auto === true) {
			$test_decimal	= substr($number, -3, 1);
			$test_thousands	= substr($number, -4, 1);
		} elseif($auto == 'DEC2MET') {
			$test_decimal	= ',';
			$test_thousands	= '.';
		} else {
			$test_decimal	= '.';
			$test_thousands	= ',';
		}

		if($test_decimal == ',' || $test_thousands == '.') {

			$number = str_replace('.', '', $number);
			$number = str_replace(',', '.', $number);

		} elseif($test_decimal == '.' || $test_thousands == ',') {

			$number = str_replace(',', '', $number);

		}

	}

	return $number;

}

function feedimport_article_templates($tmpl='') {
	$templates	= array('default' => $GLOBALS['BL']['be_cnt_default']);
	if(!empty($tmpl)) {
		$tmplfiles	= get_tmpl_files($tmpl);
		if(count($tmplfiles)) {
			foreach($tmplfiles as $val) {
				$templates[$val] = $val;
			}
		}
	}
	return $templates;
}

function is_feed_available($url, $timeout = 30) {
	$ch = curl_init(); // get cURL handle

	// set cURL options
	$opts = array(
		CURLOPT_RETURNTRANSFER	=> true,	// do not output to browser
		CURLOPT_URL				=> $url,	// set URL
		CURLOPT_NOBODY			=> true,	// do a HEAD request only
		CURLOPT_TIMEOUT			=> $timeout	// set timeout
	);
	curl_setopt_array($ch, $opts);

	curl_exec($ch); // do it!

	$retval = curl_getinfo($ch, CURLINFO_HTTP_CODE); // check if HTTP OK

	curl_close($ch); // close handle

	return $retval == 200; // true | false
}

function feedimport_article_authors() {

	$result = _dbGet('phpwcms_user', 'usr_id, usr_name, usr_login, usr_admin', 'usr_aktiv=1', '', 'usr_admin DESC, usr_name');

	if(!isset($result[0]['usr_id'])) {
		return array(0 => $GLOBALS['BL']['be_cnt_default']);
	}

	$users = array();

	foreach($result as $user) {

		$users[ $user['usr_id'] ] = $user['usr_name'] ? $user['usr_name'] : $user['usr_login'];
		if($user['usr_admin']) {
			$users[ $user['usr_id'] ] .= ' ('.$GLOBALS['BL']['be_article_adminuser'].')';
		}
	}

	return $users;
}

function feedimport_filestorage_dirlist($pid=0, $prefix='+', $userID=null, $counter=0) {
	if(!$counter) {
		$GLOBALS['filestorage_dirlist'] = array(
			0 => $GLOBALS['BL']['be_ftptakeover_rootdir'] // Root
		);
	}
	$pid  = intval($pid);
	$sql  = "SELECT f_id, f_name, f_uid, usr_login FROM ".DB_PREPEND."phpwcms_file f ";
	$sql .= "LEFT JOIN ".DB_PREPEND."phpwcms_user u ON u.usr_id=f.f_uid ";
	$sql .= "WHERE f.f_pid=".$pid." AND ";
	if(empty($_SESSION["wcs_user_admin"]) && $userID) {
		$sql .= "f.f_uid=".intval($userID)." AND ";
	}
	$sql .= "f.f_kid=0 AND f.f_trash=0 ORDER BY f_name";
	$result = _dbQuery($sql);
	if(isset($result[0]['f_id'])) {
		foreach($result as $row) {
			if($_SESSION["wcs_user_id"] != $row['f_uid']) {
				$row["f_name"] .= ' (' . $row["usr_login"] . ')';
			}
			$GLOBALS['filestorage_dirlist'][ (int) $row['f_id'] ] = str_repeat($prefix, $counter+1).' '.$row["f_name"];
			feedimport_filestorage_dirlist($row['f_id'], $prefix, $userID, $counter+1);
		}
	}
	if(!$counter) {
		$result = $GLOBALS['filestorage_dirlist'];
		unset($GLOBALS['filestorage_dirlist']);
		return $result;
	}
}
