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

// ----------------------------------------------------------------
// obligate check for phpwcms constants
if (!defined('PHPWCMS_ROOT')) {
   die("You Cannot Access This Script Directly, Have a Nice Day.");
}
// ----------------------------------------------------------------


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
	
		$file_extension	= strtolower(substr(strrchr($filename,"."),1));
		$ctype			= isset($GLOBALS['phpwcms']['mime_types'][$file_extension]) ? $GLOBALS['phpwcms']['mime_types'][$file_extension] : 'application/force-download';
	
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

// http://www.thomthom.net/blog/2007/09/php-resumable-download-server/
function rangeDownload($file) {
 
	$fp = @fopen($file, 'rb');
 
	$size   = filesize($file); // File size
	$length = $size;           // Content length
	$start  = 0;               // Start byte
	$end    = $size - 1;       // End byte
	// Now that we've gotten so far without errors we send the accept range header
	/* At the moment we only support single ranges.
	 * Multiple ranges requires some more work to ensure it works correctly
	 * and comply with the spesifications: http://www.w3.org/Protocols/rfc2616/rfc2616-sec19.html#sec19.2
	 *
	 * Multirange support annouces itself with:
	 * header('Accept-Ranges: bytes');
	 *
	 * Multirange content must be sent with multipart/byteranges mediatype,
	 * (mediatype = mimetype)
	 * as well as a boundry header to indicate the various chunks of data.
	 */
	header("Accept-Ranges: 0-$length");
	// header('Accept-Ranges: bytes');
	// multipart/byteranges
	// http://www.w3.org/Protocols/rfc2616/rfc2616-sec19.html#sec19.2
	if (isset($_SERVER['HTTP_RANGE'])) {
 
		$c_start = $start;
		$c_end   = $end;
		// Extract the range string
		list(, $range) = explode('=', $_SERVER['HTTP_RANGE'], 2);
		// Make sure the client hasn't sent us a multibyte range
		if (strpos($range, ',') !== false) {
 
			// (?) Shoud this be issued here, or should the first
			// range be used? Or should the header be ignored and
			// we output the whole content?
			header('HTTP/1.1 416 Requested Range Not Satisfiable');
			header("Content-Range: bytes $start-$end/$size");
			// (?) Echo some info to the client?
			exit;
		}
		// If the range starts with an '-' we start from the beginning
		// If not, we forward the file pointer
		// And make sure to get the end byte if spesified
		if ($range0 == '-') {
 
			// The n-number of the last bytes is requested
			$c_start = $size - substr($range, 1);
		}
		else {
 
			$range  = explode('-', $range);
			$c_start = $range[0];
			$c_end   = (isset($range[1]) && is_numeric($range[1])) ? $range[1] : $size;
		}
		/* Check the range and make sure it's treated according to the specs.
		 * http://www.w3.org/Protocols/rfc2616/rfc2616-sec14.html
		 */
		// End bytes can not be larger than $end.
		$c_end = ($c_end > $end) ? $end : $c_end;
		// Validate the requested range and return an error if it's not correct.
		if ($c_start > $c_end || $c_start > $size - 1 || $c_end >= $size) {
 
			header('HTTP/1.1 416 Requested Range Not Satisfiable');
			header("Content-Range: bytes $start-$end/$size");
			// (?) Echo some info to the client?
			exit;
		}
		$start  = $c_start;
		$end    = $c_end;
		$length = $end - $start + 1; // Calculate new content length
		fseek($fp, $start);
		header('HTTP/1.1 206 Partial Content');
	}
	// Notify the client the byte range we'll be outputting
	header("Content-Range: bytes $start-$end/$size");
	header("Content-Length: $length");
 
	// Start buffered download
	$buffer = 1024 * 8;
	while(!feof($fp) && ($p = ftell($fp)) <= $end) {
 
		if ($p + $buffer > $end) {
 
			// In case we're only outputtin a chunk, make sure we don't
			// read past the length
			$buffer = $end - $p + 1;
		}
		set_time_limit(0); // Reset time limit for big files
		echo fread($fp, $buffer);
		flush(); // Free up memory. Otherwise large files will trigger PHP's memory limit.
	}
 
	fclose($fp);
 
}



?>