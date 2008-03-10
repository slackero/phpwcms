<?php
/*************************************************************************************
   Copyright notice
   
   (c) 2002-2008 Oliver Georgi (oliver@phpwcms.de) // All rights reserved.
 
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


require_once (PHPWCMS_ROOT.'/include/inc_lib/lib.php_special_entities.php');
require_once (PHPWCMS_ROOT.'/include/inc_lib/charset_helper.inc.php');
require_once (PHPWCMS_ROOT.'/include/inc_ext/ConvertCharset/ConvertCharset.class.php');
require_once (PHPWCMS_ROOT.'/include/inc_ext/htmlfilter/htmlfilter.php');


function isEmpty($string) {
	return ($string == NULL || $string == '') ? 1 : 0;
}

function aporeplace($string_to_convert='') {
	//Ändert die einfachen Apostrophe für SQL-Funktionen in doppelte
	$string_to_convert = str_replace("\\", "\\\\", $string_to_convert);
	$string_to_convert = str_replace("'", "''", $string_to_convert );
	return $string_to_convert;
}

function slweg($string_wo_slashes_weg, $string_laenge=0, $trim=true) {
	// Falls die Serverfunktion magic_quotes_gpc aktiviert ist, so
	// sollen die Slashes herausgenommen werden, anderenfalls nicht
	if($trim) $string_wo_slashes_weg = trim($string_wo_slashes_weg);
	if( get_magic_quotes_gpc() ) $string_wo_slashes_weg = stripslashes ($string_wo_slashes_weg);
	if($string_laenge) $string_wo_slashes_weg = substr($string_wo_slashes_weg, 0, $string_laenge);
	$string_wo_slashes_weg = preg_replace( array('/<br>$/i','/<br \/>$/i','/<p><\/p>$/i','/<p>&nbsp;<\/p>$/i') , '', $string_wo_slashes_weg);
	return $string_wo_slashes_weg;
}

function clean_slweg($string_wo_slashes_weg, $string_laenge=0, $trim=true) {
	// Falls die Serverfunktion magic_quotes_gpc aktiviert ist, so
	// sollen die Slashes herausgenommen werden, anderenfalls nicht
	if($trim) $string_wo_slashes_weg = trim($string_wo_slashes_weg);
	if( get_magic_quotes_gpc() ) $string_wo_slashes_weg = stripslashes ($string_wo_slashes_weg);
	$string_wo_slashes_weg = strip_tags($string_wo_slashes_weg);
	if($string_laenge) $string_wo_slashes_weg = substr($string_wo_slashes_weg, 0, $string_laenge);
	return $string_wo_slashes_weg;
}

function getpostvar($formvar, $string_laenge=0) {
	//combines trim, stripslashes und apostrophe replace
	$formvar = slweg($formvar);
	return aporeplace($formvar);
}

function html_specialchars($h='') {
	//used to replace the htmlspecialchars original php function
	//not compatible with many internation chars like turkish, polish
	$h = preg_replace('/&(?!((#[0-9]+)|[a-z]+);)/s', '&amp;', $h ); //works correct for "&#8230;" and/or "&ndash;"
	//$h = preg_replace('/&(?!#[0-9]+;)/s', '&amp;', $h );
	$h = str_replace( '<', '&lt;'  , $h );
	$h = str_replace( '>', '&gt;'  , $h );
	$h = str_replace( '"', '&quot;', $h );
	$h = str_replace( "'", '&#039;', $h );
	$h = str_replace( "\\", '&#92;', $h );
	return $h;
}
	
function html_despecialchars($h='') {
	//call off html_specialchars
	$h = str_replace( '&amp;' , '&', $h );
	$h = str_replace( '&lt;'  , '<', $h );
	$h = str_replace( '&gt;'  , '>', $h );
	$h = str_replace( '&quot;', '"', $h );
	$h = str_replace( '&#039;', "'", $h );
	$h = str_replace( '&#92;' , "\\", $h );	
	return $h;
}

function trimhtml($h='') {
	//return html_specialchars(trim($name));
	$h = html_specialchars(trim($h));
	return $h;
}

function list_country($c, $dbcon){
	//Create the country list menu for forms 
	//with the given value selected
	//$c = selected value
	if(isEmpty($c)) $c = "DE";
	$sql = mysql_query("SELECT country_iso, country_name FROM ".DB_PREPEND."phpwcms_country ORDER BY country_name", $dbcon);
	$country_list = "";
	while($a = mysql_fetch_assoc($sql)) {
		$country_list .= "<option value=\"".$a["country_iso"]."\"";
		$country_list .= ($a["country_iso"] != $c) ? "" : " selected";
		$country_list .= ">".html_specialchars($a["country_name"])."</option>\n";
	}
	mysql_free_result($sql);
	return $country_list;
}

function list_profession($c){
	//Create the profession list menu for forms 
	//with the given value selected
	//$c = selected value
	if(isEmpty($c)) $c = " n/a";
	$sql = mysql_query("SELECT prof_name FROM ".DB_PREPEND."phpwcms_profession ORDER BY prof_name");
	while($a = mysql_fetch_assoc($sql)) {
		if($a["prof_name"] != $c) {
			echo "\t\t\t<option value=\"".$a["prof_name"]."\">".trim($a["prof_name"])."</option>\n";
		} else {
			echo "\t\t\t<option value=\"".$a["prof_name"]."\" selected>".trim($a["prof_name"])."</option>\n";
		}		
	}
	mysql_free_result($sql);
}

function is_selected($c, $chkvalue, $xhtml=0, $echoit=1) {
	$e = '';
	if(strval($c) == strval($chkvalue)) {
		$e = (!$xhtml) ? ' selected' : ' selected="selected"' ;
	}
	if($echoit) {
		echo $e;
	} else {
		return $e;
	}
}

function is_checked($c, $chkvalue, $xhtml=0, $echoit=1) {
	$e = '';
	if(strval($c) == strval($chkvalue)) {
		$e = (!$xhtml) ? ' checked' : ' checked="checked"' ;
	}
	if($echoit) {
		echo $e;
	} else {
		return $e;
	}
}

function check_checkbox($c) {
	//Prüft, ob korrekte Werte via Checkbox übergeben wurden
	$c = intval($c);
	if($c != 0 AND $c != 1) $c = 0;
	return $c;
}

function which_ext($filename) {
	// return file extension
	return strtolower(str_replace('.', '', strrchr(trim($filename), '.')));
}

function cut_ext($dateiname) {
	//cuts extension of file
	$cutoff = strrpos($dateiname, '.');
	return ($cutoff !== false) ? substr($dateiname, 0, $cutoff) : $dateiname;
}

function fsize($zahl,$spacer='&nbsp;',$short=1) {
	//Creates Filesize-Info
	//number_format($_FILES["wcsfile"]["size"] / 1024, 0, ',', '.')." kB)
	//$short 0 = ultrashort = B, K, M, G, T
	//$short 1 = short = B, KB, MB, GB, TB
	//$short 2 = long = Byte, KiloByte, MegaByte, GigaByte, TeraByte
	$_unit = array(
		0 => array(	"B"	=>	"B",		"K"	=>	"K",		"M"	=>	"M",
					"G"	=>	"G",		"T"	=>	"T"
				   ),
		1 => array(	"B"	=>	"Byte",		"K"	=>	"KB",		"M"	=>	"MB",
					"G"	=>	"GB",		"T"	=>	"TB"
				   ),
		2 => array(	"B"	=>	"Byte",		"K"	=>	"KiloByte",	"M"	=>	"MegaByte",
					"G"	=>	"GigaByte",	"T"	=>	"TeraByte"
				   ) );
	$zahl = intval($zahl);
	if($zahl < 1024) {
		$zahl = number_format($zahl, 0, '.', '.');
		$unit = "B";
	} elseif($zahl < 1048576) {
		$zahl = number_format($zahl/1024, 2, '.', '.');
		$unit = "K";
	} elseif ($zahl < 1073741824) {
		$zahl = number_format($zahl/1048576, 2, '.', '.');
		$unit = "M";
	} elseif ($zahl < 1099511627776) {
		$zahl = number_format($zahl/1073741824, 2, '.', '.');
		$unit = "G";
	} else {
		$zahl = number_format($zahl/1125899906842624, 2, ' ', '.');
		$unit = "T";
	}

	return $zahl.$spacer.$_unit[$short][$unit];
}

function fsizelong($zahl,$spacer='&nbsp;') {
	return fsize($zahl,$spacer,1);
}

function extimg($ext) {
	//get extension image
	$img =	array	(
		"exe" =>	"icon_exe.gif",		"com" =>	"icon_exe.gif",
		"bat" =>	"icon_exe.gif",		"pdf" =>	"icon_pdf.gif",
		"txt" =>	"icon_txt.gif",		"xls" =>	"icon_xls.gif",
		"cvs" =>	"icon_xls.gif",		"rtf" =>	"icon_txt.gif",
		"htm" =>	"icon_htm.gif",		"html" =>	"icon_htm.gif",
		"pix" =>	"icon_pix.gif",		"tif" =>	"icon_pix.gif",
		"jpg" =>	"icon_pix.gif",		"jpeg" =>	"icon_pix.gif",
		"gif" =>	"icon_pix.gif",		"png" =>	"icon_pix.gif",
		"psd" =>	"icon_pix.gif",		"rar" =>	"icon_rar.gif",
		"zip" =>	"icon_zip.gif",		"tar" =>	"icon_zip.gif",
		"gzip" =>	"icon_zip.gif",		"sit" =>	"icon_sit.gif",
		"sea" =>	"icon_sit.gif",		"doc" =>	"icon_doc.gif",
		"dot" =>	"icon_doc.gif",		"ai"  =>	"icon_ai.gif",
		"ps"  =>	"icon_ps.gif",		"eps" =>	"icon_eps.gif",
		"tar" =>	"icon_tar.gif",		"gz"  =>	"icon_gz.gif",
		"tgz" =>	"icon_gz.gif",		"aif" =>	"icon_snd.gif",
		"aiff" =>	"icon_snd.gif",		"mp3" =>	"icon_snd.gif",
		"snd" =>	"icon_snd.gif",		"wav" =>	"icon_snd.gif",
		"mid" =>	"icon_snd.gif",		"mov" =>	"icon_vid.gif",
		"avi" =>	"icon_vid.gif",		"qt"  =>	"icon_vid.gif",
		"mpeg" =>	"icon_vid.gif"					
				);
	return (isset($img[$ext])) ? $img[$ext] : "icon_generic.gif";
}

function randpassword($length=6) {
	//totally random password creation
	return generic_string($length);
}

function generic_string($length, $i=0) {
	$gen_string = '';
	$p[0] = "abcdefghijklmnopqrstuvwxyz";
	$p[1] = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
	$p[2] = "1234567890";
	switch($i) {
		case 1:		$chars = $p[0].$p[2];	break;
		case 2: 	$chars = $p[1].$p[2];	break;
		case 3: 	$chars = $p[0].$p[1];	break;
		case 4: 	$chars = $p[0];			break;
		case 5: 	$chars = $p[1];			break;
		case 6: 	$chars = $p[2];			break;
		default:	$chars = $p[0].$p[2].$p[1];
	}
	mt_srand((double)microtime()*1000000);
	$count = strlen($chars)-1;
    for($i = 0; $i < $length; $i++){
        $gen_string .= substr($chars, mt_rand(0,$count),1);
    }
    return $gen_string;
}

function genlogname() {
	$usercount = _dbQuery('SELECT COUNT(*) FROM '.DB_PREPEND."phpwcms_user WHERE usr_login LIKE 'user%'", 'COUNT');
	$usercount = $usercount ? $usercount+1 : 1;
	return 'user'.$usercount; 
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

function which_folder_active($ist, $soll, $ac="#9BBECA", $nc="#363E57", $nclass="msgreiter") {
	if($ist == $soll) {
		echo "bgcolor='".$ac."' class='".$nclass."'";
	} else {
		echo "bgcolor='".$nc."' class='".$nclass."' ";
		echo "onMouseOver=\"bgColor='#FF6600'\" onMouseOut=\"bgColor='".$nc."'\"";
	}
}

function FileExtension($filename) {
	return substr(strrchr($filename, "."), 1, strlen(strrchr($filename, ".")));
}

function convert_into($extension) {
	//check which extension to give back
	$extension = strtolower($extension);
	$ext = 'jpg';
	if(IMAGICK_ON) {
		switch($extension) {
			case 'gif':	$ext = 'gif'; break;
			case 'png':	$ext = 'png'; break;
		}
	} else {
		switch($extension) {
			case 'gif':	$ext = (imagetypes() & IMG_GIF) ? "gif" : "png";
						break;
			case 'png':	$ext = 'png'; break;
		}
	}
	return $ext;
}

function is_ext_true($extension) {
	$ext = false;
	if(IMAGICK_ON) {
		// if ImageMagick for thumbnail creation
		switch($extension) {
			case "jpg":		$ext="jpg"; break;
			case "jpeg":	$ext="jpg"; break;
			case "tif":		$ext="jpg"; break;
			case "tiff":	$ext="jpg"; break;
			case "psd":		$ext="jpg"; break;
			case "bmp":		$ext="jpg"; break;
			case "pic":		$ext="jpg"; break;
			case "eps":		$ext="jpg"; break;
			case "ps":		$ext="jpg"; break;
			case "ai":		$ext="jpg"; break;
			case "ps2":		$ext="jpg"; break;
			case "ps3":		$ext="jpg"; break;
			case "pn":		$ext="jpg"; break;
			case "wmf":		$ext="jpg"; break;
			case "gif":		$ext="gif"; break;
			case "png":		$ext="png"; break;
			case "tga":		$ext="jpg"; break;
			case "pdf":		$ext="jpg"; break;
			case "pict":	$ext="jpg"; break;
			case "jp2":		$ext="jpg"; break;
			case "jpc":		$ext="jpg"; break;
			case "ico":		$ext="jpg"; break;
			case "fax":		$ext="jpg"; break;
		}
	} else {
		// if GD is used
		switch($extension) {
			case "jpg":		$ext="jpg"; break;
			case "jpeg":	$ext="jpg"; break;
			case "gif":		$ext=(imagetypes() & IMG_GIF) ? "gif" : "png";
							break;
			case "png":		$ext="png"; break;
		}
	}
	if($ext && !empty($GLOBALS['phpwcms']["imgext_disabled"])) {
		$GLOBALS['phpwcms']["imgext_disabled"] = str_replace(' ', '', $GLOBALS['phpwcms']["imgext_disabled"]);
		$GLOBALS['phpwcms']["imgext_disabled"] = strtolower($GLOBALS['phpwcms']["imgext_disabled"]);
		$disabled_ext = explode(',', $GLOBALS['phpwcms']["imgext_disabled"]);
		if(in_array($ext, $disabled_ext)) {
			$ext = false;
		}
	}
	return $ext;
}

function make_date($datestring, $dateformat = "d.m.y") {
	$unixtime=strtotime($datestring);
	return ($unixtime) ? date($dateformat, $unixtime) : $datestring;
}

function switch_on_off($wert) {
	//switches the value off->on and on->off
	return intval($wert) ? 0 : 1;
}

function online_users($dbcon, $spacer="<br />", $wrap="<span class=\"useronline\">|<span>") {
	$wrap = explode("|", $wrap);
	$x=0; $xo="";
	if($o = mysql_query("SELECT logged_user FROM ".DB_PREPEND."phpwcms_userlog WHERE logged_in=1", $dbcon)) {
		while($uo = mysql_fetch_row($o)) {
			$xo .= ($x) ? $spacer : "";
			$xo .= html_specialchars($uo[0]);
			$x++;
		}
		mysql_free_result($o);
	}
	return ($x) ? $wrap[0].$xo.$wrap[1] : "";
}

function get_filecat_childcount ($fcatid, $dbcon) {
	$sql = "SELECT COUNT(fkey_id) FROM ".DB_PREPEND."phpwcms_filekey WHERE fkey_deleted=0 AND fkey_cid=".intval($fcatid);
	if($result = mysql_query($sql, $dbcon)) {
		if($row = mysql_fetch_row($result)) $count = $row[0];
		mysql_free_result($result);
	}
	return intval($count);
}


function is_valid_email($email) {

	// Split it into sections to make life easier
	$email_array = explode('@', $email);
	$count = count($email_array);
	
	// First, we check that there's one @ symbol, and that the lengths are right
	if($count != 2) {
		return false;
	}
	if(empty($email_array[0]) || strlen($email_array[0]) > 64) {
		return false;
	}
	if(empty($email_array[1]) || strlen($email_array[1]) > 255) {
		return false;
	}	
	$local_array = explode('.', $email_array[0]);
	for ($i = 0; $i < count($local_array); $i++) {
		if (!ereg("^(([A-Za-z0-9!#$%&'*+/=?^_`{|}~-][A-Za-z0-9!#$%&'*+/=?^_`{|}~\.-]{0,63})|(\"[^(\\|\")]{0,62}\"))$", $local_array[$i])) {
			return false;
		}
	}  
	if (!ereg("^\[?[0-9\.]+\]?$", $email_array[1])) { // Check if domain is IP. If not, it should be valid domain name
		$domain_array = explode('.', $email_array[1]);
		$count = count($domain_array);
		if ($count < 2) {
			return false; // Not enough parts to domain
		}
		for ($i = 0; $i < $count; $i++) {
			if (!ereg("^(([A-Za-z0-9][A-Za-z0-9-]{0,61}[A-Za-z0-9])|([A-Za-z0-9]+))$", $domain_array[$i])) {
				return false;
			}
		}
		
		// check if it is valid TLD
		$tld = strtolower($domain_array[ $count-1 ]);
		// TLD based on http://data.iana.org/TLD/tlds-alpha-by-domain.txt
		// Version 2006120301, Last Updated Mon Dec 4 09:07:02 2006 UTC
		$all_td = array('ac'=>'','ad'=>'','ae'=>'','aero'=>'','af'=>'','ag'=>'','ai'=>'','al'=>'','am'=>'','an'=>'','ao'=>'','aq'=>'','ar'=>'','arpa'=>'',
				'as'=>'','at'=>'','au'=>'','aw'=>'','ax'=>'','az'=>'','ba'=>'','bb'=>'','bd'=>'','be'=>'','bf'=>'','bg'=>'','bh'=>'','bi'=>'',
				'biz'=>'','bj'=>'','bm'=>'','bn'=>'','bo'=>'','br'=>'','bs'=>'','bt'=>'','bv'=>'','bw'=>'','by'=>'','bz'=>'','ca'=>'','cat'=>'',
				'cc'=>'','cd'=>'','cf'=>'','cg'=>'','ch'=>'','ci'=>'','ck'=>'','cl'=>'','cm'=>'','cn'=>'','co'=>'','com'=>'','coop'=>'','cr'=>'',
				'cu'=>'','cv'=>'','cx'=>'','cy'=>'','cz'=>'','de'=>'','dj'=>'','dk'=>'','dm'=>'','do'=>'','dz'=>'','ec'=>'','edu'=>'','ee'=>'',
				'eg'=>'','er'=>'','es'=>'','et'=>'','eu'=>'','fi'=>'','fj'=>'','fk'=>'','fm'=>'','fo'=>'','fr'=>'','ga'=>'','gb'=>'','gd'=>'',
				'ge'=>'','gf'=>'','gg'=>'','gh'=>'','gi'=>'','gl'=>'','gm'=>'','gn'=>'','gov'=>'','gp'=>'','gq'=>'','gr'=>'','gs'=>'','gt'=>'',
				'gu'=>'','gw'=>'','gy'=>'','hk'=>'','hm'=>'','hn'=>'','hr'=>'','ht'=>'','hu'=>'','id'=>'','ie'=>'','il'=>'','im'=>'','in'=>'',
				'info'=>'','int'=>'','io'=>'','iq'=>'','ir'=>'','is'=>'','it'=>'','je'=>'','jm'=>'','jo'=>'','jobs'=>'','jp'=>'','ke'=>'','kg'=>'',
				'kh'=>'','ki'=>'','km'=>'','kn'=>'','kr'=>'','kw'=>'','ky'=>'','kz'=>'','la'=>'','lb'=>'','lc'=>'','li'=>'','lk'=>'','lr'=>'',
				'ls'=>'','lt'=>'','lu'=>'','lv'=>'','ly'=>'','ma'=>'','mc'=>'','md'=>'','mg'=>'','mh'=>'','mil'=>'','mk'=>'','ml'=>'','mm'=>'',
				'mn'=>'','mo'=>'','mobi'=>'','mp'=>'','mq'=>'','mr'=>'','ms'=>'','mt'=>'','mu'=>'','museum'=>'','mv'=>'','mw'=>'','mx'=>'','my'=>'',
				'mz'=>'','na'=>'','name'=>'','nc'=>'','ne'=>'','net'=>'','nf'=>'','ng'=>'','ni'=>'','nl'=>'','no'=>'','np'=>'','nr'=>'','nu'=>'',
				'nz'=>'','om'=>'','org'=>'','pa'=>'','pe'=>'','pf'=>'','pg'=>'','ph'=>'','pk'=>'','pl'=>'','pm'=>'','pn'=>'','pr'=>'','pro'=>'',
				'ps'=>'','pt'=>'','pw'=>'','py'=>'','qa'=>'','re'=>'','ro'=>'','ru'=>'','rw'=>'','sa'=>'','sb'=>'','sc'=>'','sd'=>'','se'=>'',
				'sg'=>'','sh'=>'','si'=>'','sj'=>'','sk'=>'','sl'=>'','sm'=>'','sn'=>'','so'=>'','sr'=>'','st'=>'','su'=>'','sv'=>'','sy'=>'',
				'sz'=>'','tc'=>'','td'=>'','tf'=>'','tg'=>'','th'=>'','tj'=>'','tk'=>'','tl'=>'','tm'=>'','tn'=>'','to'=>'','tp'=>'','tr'=>'',
				'travel'=>'','tt'=>'','tv'=>'','tw'=>'','tz'=>'','ua'=>'','ug'=>'','uk'=>'','um'=>'','us'=>'','uy'=>'','uz'=>'','va'=>'','vc'=>'',
				've'=>'','vg'=>'','vi'=>'','vn'=>'','vu'=>'','wf'=>'','ws'=>'','ye'=>'','yt'=>'','yu'=>'','za'=>'','zm'=>'','zw'=>'');

		if(!isset($all_td[$tld])) {
			return false;
		}
	}
	
	return true;
}

function MailVal($Addr, $Level, $Timeout = 15000) {
	// just simple alias function
    return is_valid_email($Addr) ? 0 : 1;
}

function read_textfile($filename) {
	if(is_file($filename)) {
		$fd = @fopen($filename, "rb");
		$text = fread($fd, filesize($filename));
		fclose($fd);
		return $text;				
	} else {
		return false;
	}
}

function write_textfile($filename, $text) {
	if($fp = @fopen($filename, "w+b")) {
		if(empty($text)) $text = "\n";
		fwrite($fp, $text);
		fclose($fp);
		return true;	
	} else {
		return false;
	}
}

function check_cache($file, $cache_timeout=0) {

	if(is_file($file)) {	// file exists

		$filetime	= filemtime($file);
		$fileage	= time() - $filetime;
		
        if($cache_timeout > $fileage) {
			return 'VALID';		// file is up-to-date
		} else {
			return 'EXPIRED';	// file is too old and expired
		}
	
	} else {

		return 'MISSING';		// file not present

	}
}

//added: 09-20-2003
function add_keywords_to_search ($list_of_keywords, $keywords, $spacer=" ", $start_spacer=1) {
	//adds available keywords to the values used by search engine in file section
	//returns a string
	$kw_string = "";
	if(sizeof($list_of_keywords) && $keywords) {
		$kw = explode(":", $keywords);
		if(sizeof($kw)) {
			foreach($kw as $value) {
				list($kw_cat, $kw_id) = explode("_", $value);
				$kw_id = intval($kw_id);
				if($kw_string) {
					$kw_string .= $spacer;
				}
				if(isset($list_of_keywords[$kw_id])) {
					$kw_string .= $list_of_keywords[$kw_id];
				}
				
			}
		}
	}
	return (($start_spacer) ? $spacer : "") . $kw_string;
}

function get_list_of_file_keywords() {
	//reads possible keywords defined by admin and returns
	//array with values if exists
	//else it returns false
	if($result = mysql_query("SELECT * FROM ".DB_PREPEND."phpwcms_filekey")) {
		while($row = mysql_fetch_assoc($result)) {
			$file_key[intval($row["fkey_id"])] = html_specialchars($row["fkey_name"]);
		}
		mysql_free_result($result);
	}
	return (!empty($file_key) && count($file_key)) ? $file_key : false;
}

function get_int_or_empty($value, $emptyreturn='""') {
	//is used to return configuration values
	//that's why the default empty return value is ""
	$value = intval($value);
	return ($value) ? $value : $emptyreturn;
}

function get_pix_or_percent($val) {
	//is used to return configuration width/height values
	//whether based on pixel or percent
	//that's why the default empty return value is ""
	//returns a string
	$val = trim($val);
	$intval = intval($val);
	if(strlen($val) > 1 && strlen($val)-1 == strrpos($val, "%") && $intval) {
		$val = (($intval > 100) ? "100" : $intval)."%";
	} else {
		$val = ($intval) ? $intval : "";
	}
	return $val;
}

function check_URL($url) {
	//checks if URL is valid
	$fp = @fopen($url, "r");
	if(!$fp) {
		$url_status = 0;
	} else {
		$url_status = 1;
		fclose($fp);
	}
	return $url_status;
}

function validate_email($email) {
	// checks if the Email is well formatted
	return preg_match("/[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$/", $email); 
}

function validate_url($url) {
	// checks if the URL is well formatted
    return preg_match("/(((ht|f)tps*:\/\/)*)((([a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3}))|(([0-9]{1,3}\.){3}([0-9]{1,3})))((\/|\?)[a-z0-9~#%&'_\+=:\?\.-]*)*)$/", $url); 
}

function convert_url($text) {
	// converts URLs in Texts to link
    $text = eregi_replace("((ht|f)tp(s*)://www\.|www\.)([a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})((/|\?)[a-z0-9~#%&\\/'_\+=:\?\.-]*)*)", "http\\3://www.\\4", $text); 
    $text = eregi_replace("((ht|f)tp(s*)://)((([a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3}))|(([0-9]{1,3}\.){3}([0-9]{1,3})))((/|\?)[a-z0-9~#%&'_\+=:\?\.-]*)*)", "\\0", $text); 
    return $text; 
}

function link_url($text) {
	// converts URLs in Texts to link
    $text = eregi_replace("((ht|f)tp(s*)://www\.|www\.)([a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})((/|\?)[a-z0-9~#%&\\/'_\+=:\?\.-]*)*)", "http\\3://www.\\4", $text); 
    $text = eregi_replace("((ht|f)tp(s*)://)((([a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3}))|(([0-9]{1,3}\.){3}([0-9]{1,3})))((/|\?)[a-z0-9~#%&'_\+=:\?\.-]*)*)", "<a href=\"\\0\">\\0</a>", $text); 
    return $text; 
}

function convert_email($text) {
	// converts Email addresses in Texts to mailto link
	return eregi_replace("([_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3}))", "mailto:\\0", $text); 
}

function link_email($text) {
	// converts Email addresses in Texts to mailto link
	return eregi_replace("([_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3}))", "<a href='mailto:\\0'>\\0</a>", $text); 
}

function convert_all_links($text) {
	// combines convertMail and convertURL
	$text = link_url($text);
    $text = link_email($text);
    return $text;
}

function convert_url_email($text) {
	// combines convertMail and convertURL
	$text = convert_email($text);
    $text = convert_url($text);
    return $text;
}

function validate_url_email($text) {
	// combined url email validation
	if(validate_email($text) || validate_url($text)) {
		return 1;
	} else {
		return 0;
	}
}

function remove_multiple_whitespaces($text) {
	// removes all multiple whitespaces from string
	return preg_replace("/(\s)+/"," ",$text);
}

function cut_redirect($text) {
	// formats the redirect string
	// returns only the first 2 parts if availabe like
	// "part1 part2 part3" -> "part1 part2" 
	// if only 1 part is returned trim the string
	return trim(preg_replace("/((.*?)\s(.*?))\s(.*)/","$1",$text));
}

function format_redirect($text) {
	// combines remove_multiple_whitespaces and cut_redirect
	return cut_redirect(remove_multiple_whitespaces($text));
}

function gd_image_check($file) {
	// when GD thumbnail creation is enabled
	// then check if image can be used by GD image function
	// GIF, JPG, PNG
	$status = 1;
	if(!IMAGICK_ON) {
		$image_check = getimagesize($file);
		$status = (!$image_check) ? 0 : 1;
		if($status && $image_check["channels"] < 4 && ($image_check[2] == 1 || $image_check[2] == 2 || $image_check[2] == 3)) {
			$status = 1;
		} else { 
			$status = 0;
		}
	}	
	return $status;
}

function encode($in_str, $charset) {
   $out_str = $in_str;
   if ($out_str && $charset) {

       // define start delimimter, end delimiter and spacer
       $end = "?=";
       $start = "=?" . $charset . "?B?";
       $spacer = $end . "\r\n " . $start;

       // determine length of encoded text within chunks
       // and ensure length is even
       $length = 75 - strlen($start) - strlen($end);
       $length = floor($length/2) * 2;

       // encode the string and split it into chunks 
       // with spacers after each chunk
       $out_str = base64_encode($out_str);
       $out_str = chunk_split($out_str, $length, $spacer);

       // remove trailing spacer and 
       // add start and end delimiters
       $spacer = preg_quote($spacer);
       $out_str = preg_replace("/" . $spacer . "$/", "", $out_str);
       $out_str = $start . $out_str . $end;
   }
   return $out_str;
}

function js_singlequote($t='') {
	// make singe quotes js compatible
	$t = str_replace("\\", "\\\\", $t );
	$t = str_replace("&#92;", "\\\\", $t );
	$t = str_replace("'", '&#39;', $t);
	//$t = str_replace("&#039;", "\\'", $t);
	$t = str_replace('"', '&quot;', $t );
	//$t = str_replace('&quot;', '\"', $t );
	//$t = str_replace('&#58;', ':', $t ); //send by pappnase
	return $t;
}

function get_tmpl_files($dir='', $ext='') {
	//browse a dir and return all template files
	$c = '\.html|\.htm|\.php|\.inc|\.tmpl'; //$c = '\.html|\.htm|\.txt|\.php|\.inc|\.tmpl';
	if($ext) {
		$ext = explode(',', $ext);
		if(count($ext)) {
			$c = '';
			foreach($ext as $value) {
				if($c) $c .= '|';
				$c .= '\.'.$value;
			}
		}
	}
	$regexp = '/('.$c.')$/';
	$fa = array(); //file array
	if(is_dir($dir)) {
		$ph = opendir($dir);
		while($pf = readdir($ph)) {
   			if( $pf != '.' && $pf != '..' && !is_dir($dir.'/'.$pf) && preg_match($regexp, strtolower($pf)) ) {
				$fa[] = $pf; //add $pf to file array for current dir
			}
		}
		closedir($ph);
	}
	return $fa;
}

function get_tmpl_section($s='',$t='') {
	// try to return the matching section of template
	// within HTML comments like <!--SECTION_START//-->...<!--SECTION_END//-->
	return (preg_match("/<!--".$s."_START\/\/-->(.*?)<!--".$s."_END\/\/-->/si", $t, $g)) ? $g[1] : '';
}

function replace_tmpl_section($s='',$t='',$r='') {
	// try to delete the matching section of template
	// within HTML comments like <!--SECTION_START//-->...<!--SECTION_END//-->
	return preg_replace("/<!--".$s."_START\/\/-->(.*?)<!--".$s."_END\/\/-->/si", $r, $t);
}

// -------------------------------------------------------------

function importedFile_toString($filename='') {

	$file = array();
	
	if(isset($_FILES[$filename]) && !$_FILES[$filename]['error']) {
		
		$file['name'] = $_FILES[$filename]['name'];
		$file['data'] = file_get_contents($_FILES[$filename]['tmp_name']);

	} else {
		
		$file = false;
	
	}
	
	return $file;
}

// -------------------------------------------------------------

function get_order_sort($order=0, $resort=0) {
	// for getting right article structure sorting INT
	// $o[0] = $acat_order; $o[1] = $acat_ordersort;
	$o		= array(3);
	$order	= intval($order);
	switch($order) {
		case  0: $o[0] =  0; $o[1] = 0; $o[2] = ' article_sort ASC';		break;
		case  1: $o[0] =  0; $o[1] = 1; $o[2] = ' article_sort DESC';		break;
		case  2: $o[0] =  2; $o[1] = 0; $o[2] = ' article_created ASC';		break;
		case  3: $o[0] =  2; $o[1] = 1; $o[2] = ' article_created DESC';	break;
		case  4: $o[0] =  4; $o[1] = 0; $o[2] = ' article_tstamp ASC';		break;
		case  5: $o[0] =  4; $o[1] = 1; $o[2] = ' article_tstamp DESC';		break;
		case  6: $o[0] =  6; $o[1] = 0; $o[2] = ' article_begin ASC';		break;
		case  7: $o[0] =  6; $o[1] = 1; $o[2] = ' article_begin DESC';		break;
		case  8: $o[0] =  8; $o[1] = 0; $o[2] = ' article_title ASC';		break;
		case  9: $o[0] =  8; $o[1] = 1; $o[2] = ' article_title DESC';		break;
		case 10: $o[0] = 10; $o[1] = 0; $o[2] = ' article_end ASC';			break;
		case 11: $o[0] = 10; $o[1] = 1; $o[2] = ' article_end DESC';		break;
	}
	$o[2] = ' article_priorize DESC,'.$o[2];
	return $o;
}

// -------------------------------------------------------------

function getRefererURL() {
	if(strtolower(substr($GLOBALS['phpwcms']['site'],0,5)) != 'https') {
		$url = 'http://';
	} else {
		$url = 'https://';
	}
	$url .= $_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
	return $url;
}

// -------------------------------------------------------------

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

// -------------------------------------------------------------

function getAltTitle($string='', $altAndTitle=0, $echo=0) {
	$attribute = trim($string);
	switch(intval($altAndTitle)) {	
		case 0:	// alt and title attribute
				$attribute = 'alt="'.$attribute.'" title="'.$attribute.'"';
				break;
		case 1:	// alt only
				$attribute = 'alt="'.$attribute.'"';
				break;
		case 2:	// alt only
				$attribute = 'title="'.$attribute.'"';
				break;
	}
	if($echo != 0) {
		echo $attribute;
	} else {
		return $attribute;
	}
}

// -------------------------------------------------------------

function sendEmail($data = array(	'recipient'=>'','toName'=>'','subject'=>'','isHTML'=>0,'html'=>'','text'=>'',
									'attach'=>array(),'from'=>'','fromName'=>'','sender'=>'','stringAttach'=>array())  ) {
	// used to send a standardized email message
	
	global $phpwcms;
	
	$mailInfo		= array(0 => false, 1 => '');
	
	$sendTo			= array();
	$from			= empty($data['from']) || !is_valid_email($data['from']) 		? $phpwcms['SMTP_FROM_EMAIL'] 	: $data['from'];
	$sender			= empty($data['sender']) || !is_valid_email($data['sender']) 	? $from 						: $data['sender'];
	$fromName		= empty($data['fromName'])										? '' 							: cleanUpForEmailHeader($data['fromName']);
	$toName			= empty($data['toName'])										? ''							: cleanUpForEmailHeader($data['toName']);
	$subject		= empty($data['subject'])										? 'Email sent by phpwcms'		: cleanUpForEmailHeader($data['subject']);
	
	$data['isHTML']	= empty($data['isHTML'])										? 0								: 1;
	
	if(!is_array($data['recipient'])) {
		$recipient = str_replace(' ', '', trim($data['recipient']));
		$recipient = str_replace(',', ';', $recipient);
		$recipient = str_replace(' ', '', $recipient);
		$recipient = explode(';', $recipient);		
	} else {
		$recipient = $data['recipient'];
	}
	
	if(is_array($recipient) && count($recipient)) {
		foreach($recipient as $value) {
			if(is_valid_email($value)) {
				$sendTo[] = $value;
			}
		}
	}
	
	if(count($sendTo)) {
	
		include_once(PHPWCMS_ROOT.'/include/inc_ext/phpmailer/class.phpmailer.php');
	
		$mail = new PHPMailer();
		$mail->Mailer 			= $phpwcms['SMTP_MAILER'];
		$mail->Host 			= $phpwcms['SMTP_HOST'];
		$mail->Port 			= $phpwcms['SMTP_PORT'];
		if($phpwcms['SMTP_AUTH']) {
			$mail->SMTPAuth 	= 1;
			$mail->Username 	= $phpwcms['SMTP_USER'];
			$mail->Password 	= $phpwcms['SMTP_PASS'];
		}
		$mail->CharSet	 		= $phpwcms["charset"];
		
		$mail->IsHTML($data['isHTML']);
		$mail->Subject			= $data['subject'];
		if($data['isHTML']) {
			$mail->AltBody		= $data['text'];
			$mail->Body 		= $data['html'];
		} else {
			$mail->Body 		= $data['text'];
		}
		
		if(!$mail->SetLanguage($phpwcms['default_lang'])) {
			$mail->SetLanguage('en');
		}
		
		$mail->From 		= $from;
		$mail->FromName		= $fromName;
		$mail->Sender	 	= $sender;

		$mail->AddAddress($sendTo[0], $toName);
		unset($sendTo[0]);
		if(is_array($sendTo) && count($sendTo)) {
			foreach($sendTo as $value) {
				$mail->AddBCC($value);
			}
		}
		
		if(isset($data['attach']) && is_array($data['attach']) && count($data['attach'])) {
			foreach($data['attach'] as $attach_file) {
				$mail->AddAttachment($attach_file);
			}
		}
		
		if(isset($data['stringAttach']) && is_array($data['stringAttach']) && count($data['stringAttach'])) {
			$attach_counter = 1;
			foreach($data['stringAttach'] as $attach_string) {
				if(is_array($attach_string) && !empty($attach_string['data'])) {
					$attach_string['filename']	= empty($attach_string['filename']) ? 'attachment_'.$attach_counter : $attach_string['filename'];
					$attach_string['mime']		= empty($attach_string['mime']) ? 'application/octet-stream' : $attach_string['mime'];
					$attach_string['encoding']	= empty($attach_string['encoding']) ? 'base64' : $attach_string['encoding'];
					$mail->AddStringAttachment($attach_string['data'], $attach_string['filename'], $attach_string['encoding'], $attach_string['mime']);
					$attach_counter++;
				}
			}
		}
	
		if(!$mail->Send()) {
			$mailInfo[0]  = false;
			$mailInfo[1]  = $mail->ErrorInfo;
		} else {
			$mailInfo[0]  = true;
		}
		unset($mail);
		
	} else {
		$mailInfo[0]  = false;
		$mailInfo[1]  = 0; //means no recipient
	}

	return $mailInfo;
}

// -------------------------------------------------------------

function getFormTrackingValue() {
	//creates a new form tracking entry in database
	//returns a <input type="hidden">
	$ip   		= getRemoteIP();
	$hash 		= md5($ip.$GLOBALS['phpwcms']["db_pass"].date('G'));
	$entry_id 	= time();	
	if(!empty($GLOBALS['phpwcms']["form_tracking"])) {
		$sql  = "INSERT INTO ".DB_PREPEND."phpwcms_formtracking SET ";
		$sql .= "formtracking_hash = '".$hash."', ";
		$sql .= "formtracking_ip = '".aporeplace($ip)."'";
		if($entry_created = mysql_query($sql, $GLOBALS['db'])) {
			$entry_id = mysql_insert_id($GLOBALS['db']);
		}
	}
	return '<input type="hidden" name="'.$hash.'" value="'.$entry_id.'" />';
}

function checkFormTrackingValue() {
	//compare given tracking value against db tracking entry
	$ip    = getRemoteIP();
	$hash1  = md5($ip.$GLOBALS['phpwcms']["db_pass"].date('G'));
	$hash2  = md5($ip.$GLOBALS['phpwcms']["db_pass"].date('G', time()-3600)); //max form delay of 1 hour
	$valid = false;
	if(isset($_POST[$hash1])) {
		// form method POST
		$entry_id = intval($_POST[$hash1]);
		$valid = true;
		unset($_POST[$hash1]);
	} elseif(isset($_POST[$hash2])) {
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

// -------------------------------------------------------------

function dumpVar($var, $commented=false) {
	//just a simple funcction returning formatted print_r()
	switch($commented) {
		case 1:		echo "\n<!--\n";
					print_r($var);
					echo "\n//-->\n";
					return NULL;
					break;
		case 2:		return '<pre>'.print_r($var, true).'</pre>';
					break;
		default: 	echo '<pre>';
					print_r($var);
					echo '</pre>';
					return NULL;
	}
}


// -------------------------------------------------------------

// workaround functions for PHP < 4.3

if(!function_exists('file_get_contents')) {
	function file_get_contents($file) {
		$f = fopen($file,'r');
		if (!$f) return '';
		$t = '';
		while ($s = fread($f,100000)) $t .= $s;
		fclose($f);
		return $t;
	}
}

if(!function_exists('html_entity_decode')) {
	function html_entity_decode($string, $test='', $charset='') {
		$trans_tbl = get_html_translation_table(HTML_ENTITIES);
		$trans_tbl = array_flip($trans_tbl);
		return strtr($string, $trans_tbl);
	}
}

function cleanUpSpecialHtmlEntities($string='') {
	if(isset($GLOBALS['SPECIAL_ENTITIES_TABLES'])) {
		$string = str_replace($GLOBALS['SPECIAL_ENTITIES_TABLES']['latin1_encode'], $GLOBALS['SPECIAL_ENTITIES_TABLES']['latin1_decode'], $string);
		$string = str_replace($GLOBALS['SPECIAL_ENTITIES_TABLES']['symbol_encode'], $GLOBALS['SPECIAL_ENTITIES_TABLES']['symbol_decode'], $string);
		$string = str_replace($GLOBALS['SPECIAL_ENTITIES_TABLES']['specialchars_encode'], $GLOBALS['SPECIAL_ENTITIES_TABLES']['specialchars_decode'], $string);
	}
	return $string;
}

function encode_SpecialHtmlEntities($string='', $mode='ALL') {
	global $SPECIAL_ENTITIES_TABLES;
	switch($mode) {
	
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
	if(strpos("\n", $string) !== false) {
		return '';
	}
	$string = clean_slweg($string);
	$string = cleanUpSpecialHtmlEntities($string);
	return $string;
}

function cleanUpForEmailHeader($text='') {
	list($text) = explode("\n", $text);
	list($text) = explode("%0D", $text);
	list($text) = explode("%0d", $text);
	list($text) = explode("\r", $text);
	list($text) = explode("%0A", $text);
	list($text) = explode("%0a", $text);
	$spam		= array('/bcc:/i', '/cc:/i', '/to:/i', '/from:/i', '/mime-version:/i', '/reply-to:/i');
	$text 		= preg_replace($spam, '', $text);
	return trim($text);
}

function getCleanSubString($cutString='', $maxLength, $moreChar='', $cutMode='char') {
	// used to cut a string by words or chars
	if(empty($maxLength) || $maxLength < 0) return $cutString;
	
	if($cutMode == 'word') {
	
		$words		= preg_split("/[\s]+/", $cutString, -1, PREG_SPLIT_NO_EMPTY);
		$cutString	= '';
		for($i = 0; $i < $maxLength; $i++) {
			if(!empty($words[$i])) {
				$cutString .= $words[$i].' ';
			}
		}
		$cutString = trim($cutString);
		if(count($words) > $maxLength && $moreChar) {
			$cutString .= $moreChar;
		}

	} else {

		$curString = trim($cutString);
		if($curString == '') {
		
			return '';
		
		} elseif($maxLength >= (MB_SAFE ? mb_strlen($curString) : strlen($curString))) {
		
			return $curString;
		
		}
		
		preg_match_all('/&[^;]+;|./', $curString, $match);
		if(is_array($match[0]) && count($match[0]) > $maxLength) {

			$match[0]   = array_slice($match[0], 0, $maxLength);
			$cutString  = trim(implode('', $match[0]));
			$cutString .= $moreChar;

		}
	}
	
	return $cutString;
}

function headerAvoidPageCaching() {
	header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
	header("Last-Modified: " . gmdate("D, d M Y H:i:s") ." GMT");
	header("Cache-Control: no-cache");
	header("Pragma: no-cache");
	header("Cache-Control: post-check=0, pre-check=0", FALSE);
}

function getFileInformation($fileID) {
	
	if(empty($fileID)) return false;

	$f = '';
	if(is_array($fileID)) {
	
		if(count($fileID) == 0) return false;
		
		$x		= 0;
		foreach($fileID as $value) {
			if($x) {
				$f .= ' OR ';
			}
			$f .= 'f_id='.intval($value);
			$x++;
		}


	} elseif(intval($fileID)) {
	
		$f = 'f_id='.intval($fileID);
	
	} else {
	
		return false;
	
	}
	
	$sql = "SELECT * FROM ".DB_PREPEND."phpwcms_file WHERE f_public=1 AND f_aktiv=1 AND f_kid=1 AND f_trash=0 AND (".$f.")";
	
	return _dbQuery($sql);
	
}

function getJavaScriptSourceLink($src='', $prefix='	') {
	return ($src) ? $prefix.'<script src="'.$src.'" type="text/javascript"></script>' : '';
}

function convertStringToArray($string='', $seperator=',', $mode='UNIQUE', $rmvDblWSp=true) {
	// clean up a seperator seperated string and return as array
	if(trim($string) == '') return array();
	// replace all duplicate white chars by single space
	if($rmvDblWSp) $string = preg_replace('/\s\s+/', ' ', $string);
	$string = explode($seperator, $string);
	$string = array_map('trim', $string);
	$string = array_diff($string, array('',NULL,false));
	if($mode=='UNIQUE') {
		$string = array_unique($string);
	}
	return $string;
}

function decode_entities($text) {
	$text = @html_entity_decode($text, ENT_QUOTES, PHPWCMS_CHARSET);
	if(strpos($text, '&') === false) return $text;
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
	if($decChar < 128) {
		return chr($decChar);
	} elseif($decChar < 2048) {
		return chr(($decChar>>6)+192).chr(($decChar&63)+128);
	} elseif($decChar < 65536) {
		return chr(($decChar>>12)+224).chr((($decChar>>6)&63)+128).chr(($decChar&63)+128);
	} elseif($decChar < 2097152) {
		return chr($decChar>>18+240).chr((($decChar>>12)&63)+128).chr(($decChar>>6)&63+128).chr($decChar&63+128);
	}
	return $decChar;
}

function stripped_cache_content($page='') {
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
	$numargs	= func_num_args();
	$text		= '';
	if($numargs) {
		for ($i = 0; $i < $numargs; $i++) {
			$text .= ' ' . func_get_arg($i);
		}
		
		$text	= stripped_cache_content($text);
		$text	= cleanUpSpecialHtmlEntities($text);
		$text	= decode_entities($text);
		$text	= str_replace(array('!', '"', "'", '.', '#', ';', '~', '+', '*', '%', '&', '$', '§', ':', '@', ',', '|'), ' ', $text);
		$text	= preg_replace('/\[.*?\]/', '', $text);
		$text	= preg_replace('/\{.*?\}/', '', $text);
		$text	= strtoupper($text);
		$text	= implode(' ', convertStringToArray($text, ' ', 'UNIQUE', false) );
		
	}
	return $text;
}

function return_bytes_shorten($val, $round=2, $return_bytes=0) {
	$last	= strtolower($val{strlen(trim($val))-1});
	if(empty($return_bytes)) {
		$space	= '';
		$byte	= '';
	} else {
		$space	= $return_bytes;
		$byte	= 'B';
	}
	if($last == 'g' || $last == 'm' || $last == 'k') {
		$val = trim($val);
		if($byte) $val .= $space.'Byte';
		return $val;
	}
	$val	= ceil($val);
	if($val >= (1024 * 1024 * 1024)) {
		//G
		$val  = round($val / (1024 * 1024 * 1024), $round);
		$val .= $space.'G'.$byte;
	} elseif($val >= (1024 * 1024)) {
		//M
		$val  = round($val / (1024 * 1024), $round);
		$val .= $space.'M'.$byte;
	} elseif($val >= 1024) {
		//K
		$val  = round($val / 1024, $round);
		$val .= $space.'K'.$byte;
	}
	return $val;
}

function return_bytes($val) {
	// taken from: http://de3.php.net/manual/en/function.ini-get.php
	$val	= trim($val);
	$last	= strtolower($val{strlen($val)-1});
	$val	= floatval($val);
	switch($last) {
		case 'g':	$val *= 1024;
		case 'm':	$val *= 1024;
		case 'k':	$val *= 1024;
   }
   return ceil($val);
}

function return_upload_errormsg($value) {
	$err = '';
	switch ($value) {
		case 0:
			break;
		case 1:
			$err = "The uploaded file exceeds the upload_max_filesize directive (".@ini_get("upload_max_filesize").") in php.ini.";
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

function csvFileToArray($csvfile, $delimiter=';', $heading=false, $enclosure='"', $linelength=1000) {
	//import CSV file and convert to array
	
	if(!is_file($csvfile)) return false;
	
	$first	= 0;
	$datas	= array();
	
	$phpver	= version_compare('4.3.0', phpversion(), '<');
	if($phpver) {
		$oldini = ini_get('auto_detect_line_endings');
		@ini_set('auto_detect_line_endings', '1');
	}
	
	$handle	= fopen($csvfile, 'rb');

	while( ($data = fgetcsv($handle, $linelength, $delimiter, $enclosure)) !== false ) {

		// continue in case there is header row
		if($heading && !$first) {
			foreach($data as $key => $value) {
				$value = trim($value);
				$datas[0][$key] = $value ? $value : 'Column'.$key;
			}
			$first++;
			continue;
		}
		if(trim(implode('', $data)) == '') {
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

function shortHash($string='', $_Hash_function='md5') {

	return rtrim( base64_encode( pack('H*', $_Hash_function( $string ) ) ), '=' );

}

function replaceGlobalRT($string='') {
	$string = str_replace(array('{SITE}', '{PHPWCMS_URL}'), PHPWCMS_URL, $string);
	$string = str_replace('{PHPWCMS_TEMPLATE}', TEMPLATE_PATH, $string);
	$string = str_replace('{IP}', getRemoteIP(), $string);
	//$string = preg_replace_callback('/\{(DATE|GMDATE):(.*?)\}/', 'formatRTDate', $string);
	$string = renderRTDate($string);
	return $string;
}
function renderRTDate($string='') {
	return preg_replace_callback('/\{(DATE|GMDATE):(.*?)\}/', 'formatRTDate', $string);
}
function formatRTDate($matches) {
	// very cool function to render date or gmdate
	// - {DATE:DATE_FORMAT}, {GMDATE:DATE_FORMAT},
	// - {DATE:DATE_FORMAT SET:TIMESTAMP}, {GMDATE:DATE_FORMAT SET:TIMESTAMP}
	$type = strtolower($matches[1]);
	$matches = explode(' SET:', $matches[2]);
	if(empty($matches[1])) {
		return $type($matches[0]);
	}
	$matches[1] = trim($matches[1]);
	if(is_numeric($matches[1])) {
		$matches[1] = intval($matches[1]);
		return $type($matches[0], $matches[1]);
	}
	return $type($matches[0], strtotime($matches[1]));
}

function makeCharsetConversion($string='', $in_charset='utf-8', $out_charset='utf-8', $entityEncode=0) {

	$in_charset		= strtolower($in_charset);
	$out_charset	= strtolower($out_charset);
	if(empty($string) || $in_charset == $out_charset || empty($in_charset) || empty($out_charset)) {
		return $string;
	}
	$phpCharsetSuppport = returnCorrectCharset($in_charset);
	if($phpCharsetSuppport) {
		$string = doHtmlEntityPHPCleanUp($string, $phpCharsetSuppport);
	}
	$NewEncoding = new ConvertCharset; 
	$NewEncoding = $NewEncoding->Convert($string, $in_charset, $out_charset, $entityEncode);
	return $NewEncoding;

}

function doHtmlEntityPHPCleanUp($string, $charset) {

	$string = @htmlentities($string, ENT_QUOTES, $charset);
	return decode_entities($string);

}

function returnCorrectCharset($in_charset='') {

	$in_charset = strtolower($in_charset);
	switch($in_charset) {
	
		case 'iso-8859-1':
		case 'iso8859-1':		$in_charset = 'iso-8859-1';
								break;
								
		case 'iso-8859-15':
		case 'iso8859-15':		$in_charset = 'iso-8859-15';
								break;
								
		case 'utf-8':			$in_charset = 'utf-8';
								break;
		
		case 'cp866':
		case 'ibm866':
		case '866':				$in_charset = version_compare(phpversion(), '4.3.2', '<') ? false : 'cp866';
								break;
		
		case 'cp1251':
		case 'windows-1251':
		case 'win-1251':
		case '1251':			$in_charset = version_compare(phpversion(), '4.3.2', '<') ? false : 'windows-1251';
								break;
			
		case 'cp1252':
		case 'windows-1252':
		case 'win-1252':
		case '1252':			$in_charset = 'windows-1252';
								break;
			
		case 'koi8-r':
		case 'koi8-ru':
		case 'koi8r':			$in_charset = version_compare(phpversion(), '4.3.2', '<') ? false : 'koi8-r';
								break;
								
		case 'big5':
		case '950':				$in_charset = 'big5';
								break;
								
		case 'gb2312':
		case '936':				$in_charset = 'gb2312';
								break;
								
		case 'big5-hkscs':		$in_charset = 'big5-hkscs';
								break;
								
		case 'shift_jis':
		case 'sjis':
		case '932':				$in_charset = 'shift_jis';
								break;
								
		case 'euc-jp':
		case 'eucjp':			$in_charset = 'euc-jp';
								break;
		
		default:				$in_charset = false;
	
	}
	
	return $in_charset;

}

function returnSubdirListAsArray($dir='') {
	// browse a given path and return all sub directories
	if(empty($dir) || !is_dir($dir)) {
		return false;
	}
	$subdir = array();
	$ph = opendir($dir);
	while($pf = readdir($ph)) {
		if(is_dir($dir.'/'.$pf) && strpos($pf, '.') !== 0) { //$pf != '.' && $pf != '..' && 
			$subdir[] = $pf;
		}
	}
	closedir($ph);
	return $subdir;
}


function returnFileListAsArray($dir='', $extfilter='') {
	// browse a given path and return all contained files
	if(empty($dir) || !is_dir($dir)) {
		return false;
	}
	
	$files		= array();
	$ph			= opendir($dir);
	$extfilter	= strtolower(trim($extfilter));
	$extfilter	= $extfilter ? convertStringToArray($extfilter) : array();
	$dofilter	= count($extfilter) ? true : false;
	
	while($pf = readdir($ph)) {
		if(is_file($dir.'/'.$pf) && strpos($pf, '.') !== 0) { //$pf != '.' && $pf != '..' &&
			$ext = which_ext($pf);
			if($dofilter) {
				if(!in_array($ext, $extfilter)) {
					continue;
				}
			}
			$files[$pf] = array(	'filename'	=> $pf,
									'filesize'	=> filesize($dir.'/'.$pf), 
									'filetime'	=> filemtime($dir.'/'.$pf),
									'ext'		=> $ext
								);
		}
	}
	closedir($ph);
	return $files;
}

function parse_ini_str($Str, $ProcessSections=true, $SplitInNameValue=false) {
	/*
	for parsing a string formatted like INI file
	[Files]
	x=File1
	y=File2
	*/
	$Section = NULL;
	$Data = array();
	if ($Temp = strtok($Str,"\r\n")) {
		do {
			switch ($Temp{0}) {
				
				case ';':
				
				case '#':	break;
				
				case '[':	if (!$ProcessSections) break;
							$Pos = strpos($Temp,'[');
							$Section = substr($Temp,$Pos+1,strpos($Temp,']',$Pos)-1);
							if($Section) $Data[$Section] = array();
							break;
				
				default:	$Pos = strpos($Temp,'=');
							if ($Pos === FALSE) break;
							if(!$SplitInNameValue) {
								$key = trim(substr($Temp,0,$Pos));
								$val = trim(substr($Temp,$Pos+1),' "');
								if ($ProcessSections && $Section) {
									$Data[$Section][$key] = $val;
								} else {
									$Data[$key] = $val;
								}
							} else {
								$Value = array();
								$Value["NAME"] = trim(substr($Temp,0,$Pos));
								$Value["VALUE"] = trim(substr($Temp,$Pos+1),' "');
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
	$domain = parse_url(PHPWCMS_URL);
	$domain = strtolower($domain['host']);
	if(strpos($domain, 'www') === 0) {
		$domain = substr($domain, 3);
	}
	return $domain;
}

function _mkdir($target) {
	// taken from WordPress
	if (file_exists($target)) {	// from php.net/mkdir user contributed notes
		return (!@is_dir($target)) ? false : true;
	}
	umask(0);
	if(@mkdir($target)) {	// Attempting to create the directory may clutter up our display.
		$stat = @stat(dirname($target));
		$dir_perms = $stat['mode'] & 0007777;  // Get the permission bits.
		@chmod($target, $dir_perms);
		return true;
	} elseif(is_dir(dirname($target)))	{
		return false;
	}
	if (_mkdir(dirname($target))) {	// If the above failed, attempt to create the parent node, then try again.
		return _mkdir($target);
	}
	return false;
}

function saveUploadedFile($file, $target, $exttype='', $imgtype='', $rename=0, $maxsize=0) {
	// imgtype can be all exif_imagetype supported by your PHP install
	// see http://www.php.net/exif_imagetype
	$file_status = array(
		'status'	=> false, 	'error'		=> '',		'name'		=> '',
		'tmp_name'	=> '',		'size'		=> 0,		'path'		=> '',
		'ext'		=> '',		'rename'	=> '',		'maxsize'	=> intval($maxsize) );
		
	if(!isset($_FILES[$file]) || !is_uploaded_file($_FILES[$file]['tmp_name'])) {
		$file_status['error'] = 'Upload not defined';
		return $file_status;
	}

	$file_status['name']		= trim($_FILES[$file]['name']);
	$file_status['ext']			= which_ext($file_status['name']);
	$file_status['tmp_name']	= $_FILES[$file]['tmp_name'];
	$file_status['size']		= $_FILES[$file]['size'];
	$file_status['path']		= $target;
	$file_status['rename']		= $file_status['name'];
	$file_status['maxsize']		= empty($file_status['maxsize']) ? $GLOBALS['phpwcms']['file_maxsize'] : $file_status['maxsize'];
	
	if(intval($file_status['size']) > $file_status['maxsize']) {
		$file_status['error'] = 'File is too large';
		return $file_status;
	}

	if(empty($target)) {
		$file_status['error'] = 'Target directory not defined';
		return $file_status;
	}
	if(!@_mkdir($target)) {
		$file_status['error'] = 'The target directory "'.$target.'" can not be found or generated';
		return $file_status;
	}
	if($_FILES[$file]['error']) {
		$file_status['error'] = $_FILES[$file]['error'];
		return $file_status;
	}
	if($imgtype) {
		$imgtype	= convertStringToArray($imgtype);
		if(count($imgtype)) {
			$data	= @getimagesize($_FILES[$file]['tmp_name']);
			$exif_imagetype = array(
				1=>'gif',	2=>'jpeg',	2=>'jpg',	3=>'png',	4=>'swf',	5=>'psd',
				6=>'bmp',	7=>'tif',	8=>'tiff',	9=>'jpc',	10=>'jp2',	11=>'jpx',
				12=>'jb2',	13=>'swc',	14=>'iff',	15=>'wbmp',	16=>'xbm'  );
			if($data == false) {
				$file_status['error']  = 'Format not supported (';
				$allowed = array();
				foreach($imgtype as $value) {
					$allowed[] = '*.'.$exif_imagetype[$value];
				}
				$file_status['error'] .= implode(', ', $allowed).')';
				@unlink($_FILES[$file]['tmp_name']);
				return $file_status;
			}
			if(empty($exif_imagetype[$data[2]]) || !in_array($data[2], $imgtype)) {
				$file_status['error'] = 'File type '.$data[2].' is not supported for this upload ('.implode(', ', $imgtype).' only)';
				@unlink($_FILES[$file]['tmp_name']);
				return $file_status;
			}
		}
	}

	if($exttype) {
		$exttype	= convertStringToArray(strtolower($exttype));
		if(!in_array($file_status['ext'], $exttype)) {
			$file_status['error'] = 'File type *.'.$file_status['ext'].' is not supported for this upload (*.'.implode(', *.', $exttype).' only)';
			@unlink($_FILES[$file]['tmp_name']);
			return $file_status;
		}		
	}
	if(!is_writable($target)) {
		$file_status['error'] = 'Target directory <b>'.str_replace(PHPWCMS_ROOT, '', $target).'</b> is not writable';
		@unlink($_FILES[$file]['tmp_name']);
		return $file_status;	
	}	
	$rename	= convertStringToArray($rename);
	if(count($rename)) {
		foreach($rename as $value) {
			switch($value) {
				case 1:		$file_status['rename'] = remove_accents($file_status['name']);
							$file_status['rename'] = str_replace(array(':','/',"\\"), '-', $file_status['rename']);
							break;
				case 2:		$file_status['rename'] = time().'_'.$file_status['name'];
							break;
				case 3:		$file_status['rename'] = date('Ymd-His').'_'.$file_status['name'];
							break;
				case 4:		$file_status['rename'] = date('Ymd').'_'.$file_status['name'];
							break;
				case 5:		$file_status['rename'] = generic_string(6).'_'.$file_status['name'];
							break;
				case 6:		$file_status['rename'] = md5($file_status['name']);
							if($file_status['ext']) $file_status['rename'] .= '.';
							$file_status['rename'] .= $file_status['ext'];
							break;
				case 7:		$file_status['rename'] = shortHash($file_status['name']);
							if($file_status['ext']) $file_status['rename'] .= '.';
							$file_status['rename'] .= $file_status['ext'];
							break;	
			}
		}
	}	
	if(!@move_uploaded_file($_FILES[$file]['tmp_name'], $target.$file_status['rename'])) {
		$file_status['error'] = 'Uploaded file <b>'.$file_status['name'].'</b> can not be moved to <b>'.str_replace(PHPWCMS_ROOT, '', $target).'</b>';
		@unlink($_FILES[$file]['tmp_name']);
		return $file_status;
	}
	@chmod($target.$file_status['rename'], 0644);
	
	$file_status['status']	= true;
	return $file_status;
	
}

function get_alnum_dashes($string, $remove_accents = false, $replace_space='-') {
	if($remove_accents) {
		$string = remove_accents($string);
	}
	$string = str_replace(' ', $replace_space, $string);
	return preg_replace('/[^a-z0-9\-_]/i', '', $string);
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
		$val = preg_replace('/(&#[x|X]0{0,8}'.dechex(ord($search[$i])).';?)/i', $search[$i], $val); // with a ;
		// &#00064 @ 0{0,7} matches '0' zero to seven times
		$val = preg_replace('/(&#0{0,8}'.ord($search[$i]).';?)/', $search[$i], $val); // with a ;
	}
	
	// now the only remaining whitespace attacks are \t, \n, and \r
	$ra1 = array(	'javascript', 'vbscript', 'expression', 'applet', 'meta', 'xml', 'blink', 'link', 'style', 
					'script', 'embed', 'object', 'iframe', 'frame', 'frameset', 'ilayer', 'layer', 'bgsound', 'title', 'base'
				);
	$ra2 = array(	'onabort', 'onactivate', 'onafterprint', 'onafterupdate', 'onbeforeactivate', 'onbeforecopy', 'onbeforecut',
					'onbeforedeactivate', 'onbeforeeditfocus', 'onbeforepaste', 'onbeforeprint', 'onbeforeunload', 'onbeforeupdate', 
					'onblur', 'onbounce', 'oncellchange', 'onchange', 'onclick', 'oncontextmenu', 'oncontrolselect', 'oncopy', 'oncut', 
					'ondataavailable', 'ondatasetchanged', 'ondatasetcomplete', 'ondblclick', 'ondeactivate', 'ondrag', 'ondragend', 
					'ondragenter', 'ondragleave', 'ondragover', 'ondragstart', 'ondrop', 'onerror', 'onerrorupdate', 'onfilterchange', 
					'onfinish', 'onfocus', 'onfocusin', 'onfocusout', 'onhelp', 'onkeydown', 'onkeypress', 'onkeyup', 'onlayoutcomplete', 
					'onload', 'onlosecapture', 'onmousedown', 'onmouseenter', 'onmouseleave', 'onmousemove', 'onmouseout', 'onmouseover', 
					'onmouseup', 'onmousewheel', 'onmove', 'onmoveend', 'onmovestart', 'onpaste', 'onpropertychange', 'onreadystatechange', 
					'onreset', 'onresize', 'onresizeend', 'onresizestart', 'onrowenter', 'onrowexit', 'onrowsdelete', 'onrowsinserted', 
					'onscroll', 'onselect', 'onselectionchange', 'onselectstart', 'onstart', 'onstop', 'onsubmit', 'onunload'
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
			$replacement = substr($ra[$i], 0, 2).'<x>'.substr($ra[$i], 2); // add in <> to nerf the tag
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

function checkLogin($mode='REDIRECT') {

	$sql  = "UPDATE ".DB_PREPEND."phpwcms_userlog SET ";
	$sql .= "logged_in = 0, logged_change = '".time()."' ";
	$sql .= "WHERE logged_in = 1 AND ( ".time()." - logged_change ) > ".intval($GLOBALS['phpwcms']["max_time"]);
	_dbQuery($sql, 'UPDATE');
	
	if(!empty($_SESSION["wcs_user"])) {
		$sql  = "SELECT COUNT(*) FROM ".DB_PREPEND."phpwcms_userlog ";
		$sql .= "WHERE logged_user='".aporeplace($_SESSION["wcs_user"])."' AND ";
		$sql .= "logged_in=1";
		if(!empty($phpwcms['Login_IPcheck'])) {
			$sql .= " AND logged_ip='".aporeplace(getRemoteIP())."'";
		}
		
		$check = _dbCount($sql);
		
		if($check == 0) {
			unset($_SESSION["wcs_user"]);
		} else {
			$sql  = "UPDATE ".DB_PREPEND."phpwcms_userlog SET ";
			$sql .= "logged_change=".time()." WHERE ";
			$sql .= "logged_user='".aporeplace($_SESSION["wcs_user"])."' AND logged_in=1";
			_dbQuery($sql, 'UPDATE');
		}
	}
	if(empty($_SESSION["wcs_user"])) {
		@session_destroy();
		$ref_url = '';
		if(!empty($_SERVER['QUERY_STRING'])) {
			$ref_url = '?ref='.rawurlencode(PHPWCMS_URL.'phpwcms.php?'.xss_clean($_SERVER['QUERY_STRING']));
		}
		if($mode == 'REDIRECT') {
			headerRedirect(PHPWCMS_URL.'login.php'.$ref_url);
		} else {
			return false;
		}
	}

	return true;
}

?>