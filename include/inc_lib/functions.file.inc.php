<?php
/*************************************************************************************
   Copyright notice
   
   (c) 2002-2010 Oliver Georgi (oliver@phpwcms.de) // All rights reserved.
 
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


function _getFileInfo($value, $limit='1', $mode='hash') {

	$sql = '';

	switch($mode) {

		case 'hash':	$sql  = "SELECT * FROM ".DB_PREPEND."phpwcms_file WHERE f_aktiv=1 AND ";
						$sql .= "f_trash=0 AND f_public=1 AND ";
						$sql .= "f_hash='".aporeplace($value)."'";
						if( !FEUSER_LOGIN_STATUS ) {
							$sql .= ' AND f_granted=0';
						}
						if($limit) {
							$sql .= " LIMIT ".$limit;
						}
						break;
	}

	return _dbQuery($sql);
	
}

function dl_file_resume($file='', $fileinfo=array(), $onsuccess = false) {

	// function based on this one here:
	// http://www.php.net/manual/de/function.fread.php#63893

	//First, see if the file exists
	if (!is_file($file) && connection_status()) return false;
	
	//Gather relevent info about file
	$filename 		= empty($fileinfo['realfname'])	? basename($file) : $fileinfo['realfname'];
	$disposition	= empty($fileinfo['method'])	? 'attachment' : $fileinfo['method'];
	
	if($disposition == 'attachment') {
	
		$fileinfo['mimetype'] = "application/force-download";
	
	}
	
	if(empty($fileinfo['mimetype']) && empty($fileinfo['extension'])) {
	
		$file_extension	=  strtolower(substr(strrchr($filename,"."),1));
	
		//This will set the Content-Type to the appropriate setting for the file
		switch( $file_extension ) {
			case "exe": 	$ctype="application/octet-stream"; 	break;
			case "zip": 	$ctype="application/zip"; 			break;
			case "mp3": 	$ctype="audio/mpeg"; 				break;
			case "mpg":		$ctype="video/mpeg"; 				break;
			case "avi": 	$ctype="video/x-msvideo"; 			break;
			default: 		$ctype="application/force-download";
		}
		
	} else {
	
		$ctype 			= $fileinfo['mimetype'];
		$file_extension	= $fileinfo['extension'];
	
	}

	//Begin writing headers
	header('Cache-Control: ');
	header('Cache-Control: public');
	header('Pragma: ');
	
	//Use the switch-generated Content-Type
	header('Content-Type: '.$ctype);
	
	
	if (isset($_SERVER['HTTP_USER_AGENT']) && strstr($_SERVER['HTTP_USER_AGENT'], 'MSIE')) {
		// workaround for IE filename bug with multiple periods / multiple dots in filename
		// that adds square brackets to filename - eg. setup.abc.exe becomes setup[1].abc.exe
		$filename = preg_replace('/\./', '%2e', $filename, substr_count($filename, '.') - 1);
	}
	
	
	//header('Accept-Ranges: bytes');
	
	$size = filesize($file);

	
	header('Content-Length: '.$size);
	header('Content-Transfer-Encoding: binary'.LF);
	
	
	//check if http_range is sent by browser (or download manager)
	/*
	if(isset($_SERVER['HTTP_RANGE'])) {

		//if yes, download missing part
		list($a, $range)	= explode('=', $_SERVER['HTTP_RANGE']);
		$range				= explode('-', $range);
		$seek_start			= empty($range[0]) ? 0 : intval($range[0]);
		$seek_end			= empty($range[1]) ? 0 : intval($range[1]);

		header('HTTP/1.1 206 Partial Content');
		header('Content-Length: '.($seek_end - $seek_start + 1));
		header('Content-Range: bytes '.$seek_start.'-'.$seek_end.'/'.$size);

	} else {

		$rage = 0;
		header('Content-Range: bytes 0-'. ($size - 1) .'/'.$size);
		header('Content-Length: '.$size);

	}
	*/
	
	header('Content-Disposition: '.$disposition.'; filename="'.$filename.'"');
	

	//reset time limit for big files
	@set_time_limit(0);

	//open the file
	$fp = fopen($file, 'rb');

	//seek to start of missing part
	//fseek($fp, $range);

	//start buffered download
	while(!feof($fp) && !connection_status()){
	
		print(fread($fp, 1024*8));
		flush();
		//ob_flush();
	}
	fclose($fp);

	return ($onsuccess && !connection_status() && !connection_aborted()) ? true : false;
}


?>