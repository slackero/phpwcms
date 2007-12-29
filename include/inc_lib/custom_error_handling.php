<?php

error_reporting(0); 
$old_error_handler = set_error_handler('customErrorHandler');

function customErrorHandler($errno, $errmsg, $filename, $linenum, $vars) { 

	$time		= date("d M Y H:i:s"); 
	// Get the error type from the error number 
	$errortype	= array (	1    => "Error",
							2    => "Warning",
							4    => "Parsing Error",
							8    => "Notice",
							16   => "Core Error",
							32   => "Core Warning",
							64   => "Compile Error",
							128  => "Compile Warning",
							256  => "User Error",
							512  => "User Warning",
							1024 => "User Notice"); 
	
	$errlevel	= $errortype[$errno]; 
	//Write error to log file (CSV format) 
	$errfile	= fopen('errors.csv', 'a'); 
	fputs($errfile, '"'.$time.'","'.$filename.': '.$linenum.'","('.$errlevel.') '.$errmsg.'"'."\n"); 
	fclose($errfile);
	
	if($errno != 2 && $errno != 8) {
		//Terminate script if fatal errror
		die('A fatal error has occured. Script execution has been aborted'); 
	} 
} 

?>