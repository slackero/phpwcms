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


//enhance you custom frontend funtions here 

// -------------------------------------------------------------


// added by jens for content type 89: poll
function showPollImage($image, $zoom = 0) {
	
	$image_border		= ' border="'.intval($GLOBALS["template_default"]["article"]["imagelist_border"]).'"';
	if(empty($GLOBALS["template_default"]["article"]["imagelist_imgclass"])) {
		$image_imgclass	= '';
	} else {
		$image_imgclass	= ' class="'.$GLOBALS["template_default"]["article"]["imagelist_imgclass"].'"';
	}
	
	$thumb_image = get_cached_image(
		array(	
			"target_ext"	=>	$image[3],
			"image_name"	=>	$image[2].'.'.$image[3],
			"max_width"		=>	$image[4],
			"max_height"	=>	$image[5],
			"thumb_name"	=>	md5($image[2].$image[4].$image[5].$GLOBALS['phpwcms']["sharpen_level"])
		)
	);
	
	if($zoom) {
		$zoominfo = get_cached_image(
				array(	
							"target_ext"	=>	$image[3],
							"image_name"	=>	$image[2] . '.' . $image[3],
							"max_width"		=>	$GLOBALS['phpwcms']["img_prev_width"],
							"max_height"	=>	$GLOBALS['phpwcms']["img_prev_height"],
							"thumb_name"	=>	md5($image[2].$GLOBALS['phpwcms']["img_prev_width"].$GLOBALS['phpwcms']["img_prev_height"].$GLOBALS['phpwcms']["sharpen_level"])
				)
		);
	}

	$list_img_temp  = '<img src="'.PHPWCMS_IMAGES.$thumb_image[0].'" '.$thumb_image[3].$image_border.$image_imgclass.' />';
	
	if($zoom && !empty($zoominfo)) {
		// if click enlarge the image
		$open_popup_link = 'image_zoom.php?'.getClickZoomImageParameter($zoominfo[0].'?'.$zoominfo[3]);
		$open_link = $open_popup_link;
		$return_false = 'return false;';
			
		$html .= "<a href=\"".$open_link."\" onclick=\"checkClickZoom();clickZoom('".$open_popup_link."','previewpic','width=";
		$html .= $zoominfo[1].",height=".$zoominfo[2]."');".$return_false.'">';
		$html .= $list_img_temp."</a>";
	} else {
		// if not click enlarge
		$html .= $list_img_temp;
	}
	return $html;
}

// taken from http://www.php.net/manual/en/function.utf8-decode.php
// vpribish at shopping dot com, 10-Sep-2004 08:55
function utf2html($str) {
	$ret = '';
	$max = strlen($str);
	$last = 0;  // keeps the index of the last regular character
	for ($i=0; $i < $max; $i++) {
		$c = $str{$i};
		$c1 = ord($c);
		if ($c1>>5 == 6) {  // 110x xxxx, 110 prefix for 2 bytes unicode
			$ret .= substr($str, $last, $i-$last); // append all the regular characters we've passed
			$c1 &= 31; // remove the 3 bit two bytes prefix
			$c2 = ord($str{++$i}); // the next byte
			$c2 &= 63;  // remove the 2 bit trailing byte prefix
			$c2 |= (($c1 & 3) << 6); // last 2 bits of c1 become first 2 of c2
			$c1 >>= 2; // c1 shifts 2 to the right
			$ret .= "&#" . ($c1 * 100 + $c2) . ";"; // this is the fastest string concatenation
			$last = $i+1;
		} 
	}
	return $ret . substr($str, $last, $i); // append the last batch of regular characters
}

// http://www.evilwalrus.com/viewcode.php?codeEx=627
function is_date($PASSED,$TXT_DATE_FORMAT='m/d/Y') { 
    $lib_import_datearr=array(); 
    $lib_import_datearr['h'] = 2; // 01-12 - time - hours 12 
    $lib_import_datearr['H'] = 2; // 00-23 - time - hours 24 
    $lib_import_datearr['g'] = 0; // 1-12  - time - hours 12 
    $lib_import_datearr['G'] = 0; // 0-23  - time - hours 24 
    $lib_import_datearr['i'] = 2; // 00-59 - time - minutes 
    $lib_import_datearr['k'] = 0; // 0-59  - time - minutes ** k - non standard code. 
    $lib_import_datearr['s'] = 2; // 00-59 - time - seconds 
    $lib_import_datearr['x'] = 0; // 0-59  - time - seconds ** x - non standard code. 
    $lib_import_datearr['a'] = 2; // am/pm - time 
    $lib_import_datearr['A'] = 2; // AM/PM - time 
    $lib_import_datearr['j'] = 0; // 1-31  - date - day 
    $lib_import_datearr['d'] = 2; // 01-31 - date - day 
    $lib_import_datearr['n'] = 0; // 1-12  - date - month 
    $lib_import_datearr['m'] = 2; // 01-12 - date - month 
    $lib_import_datearr['y'] = 2; // 04    - date - year 
    $lib_import_datearr['Y'] = 4; // 2004  - date - year 
    $PASSED = trim($PASSED); // No spaces at beginning or end of date value. 
    $TXT_DATE_FORMAT = trim($TXT_DATE_FORMAT); // No spaces at beginning or end of formatter string 
    $store_arr = array(); // Storage array 
    $lastchar = ""; // Badly named. This really stores the data chunk we are working with 
    $dte_frmt_lstchr = ""; // Current date formatting character 
    $dte_frmt_idx = 0; // Index of where we are in date formatting rule string 
    $bln_formatter = FALSE; // Boolean. Is the formatting character a value or a place holder (ie 'm' vs '/' or ':') 
    $bln_twelve_hour_cycle = FALSE; // Boolean. Whether or not stored hours are 1-12 or 0-23. TRUE = 1-12 
    for ($i=0;$i < strlen($PASSED); $i++) { 
        $dte_frmt_lstchr=substr($TXT_DATE_FORMAT, $dte_frmt_idx,1); // Get first date formatting character 
        $dte_frmt_idx ++; // Move index for format string ahead one. 
        if ((is_int($dte_frmt_lstchr) || is_string($dte_frmt_lstchr)) && array_key_exists($dte_frmt_lstchr, $lib_import_datearr)) { // See if this formatting character is a date value or a spacer value of some sort 
            $bln_formatter = FALSE; // This value needs to be parsed for the date value 
        } 
        else { 
            $bln_formatter = TRUE; // This is a placeholder character 
        } 
        // *** Get the value 
        if ($bln_formatter) { // Just get the character and test for equivalence 
            $lastchar = substr($PASSED,$i,1); 
            if ($lastchar!=$dte_frmt_lstchr) { // The current character does not match the expected formatting character. Crash and Burn! 
                $store_arr = FALSE; // Set the return value to false 
                $i = strlen($PASSED)+1; // Break the loop 
            } 
        } // END get character value 
        else { // Get the date value 
            switch ($lib_import_datearr[$dte_frmt_lstchr]) { // How many characters to get? Remember, type 0 means you must find the end. (As in month, 1 or 2 characters?). 
                case 0: // Zero is for those with either 1 or 2 places. Rule: if 2nd character is also a number, it belongs to the item. 
                    $lastchar = substr($PASSED,$i,1); 
                    if ($i+1 < strlen($PASSED)) { // are there more characters? 
                        if (is_numeric(substr($PASSED,$i+1,1))) { $lastchar=$lastchar.substr($PASSED,$i+1,1); $i++; } // tack on next character. Move in string pointer forward 1 
                    } 
                    switch ($dte_frmt_lstchr) { 
                        case "j": 
                            if (!is_numeric($lastchar)) { $store_arr = FALSE; $i = strlen($PASSED)+1; } // The the value. Must be a number. Break out 
                            else { $store_arr['mday']=$lastchar; } // assign the value to the array 
                            break; 
                        case "n": 
                            if (!is_numeric($lastchar)) { $store_arr = FALSE; $i = strlen($PASSED)+1; } // The the value. Must be a number. Break out 
                            else { $store_arr['mon']=$lastchar; } // assign the value to the array 
                            break; 
                        case "k": 
                            if (!is_numeric($lastchar)) { $store_arr = FALSE; $i = strlen($PASSED)+1; } // The the value. Must be a number. Break out 
                            else { $store_arr['minutes']=$lastchar; } // assign the value to the array 
                            break; 
                        case "x": 
                            if (!is_numeric($lastchar)) { $store_arr = FALSE; $i = strlen($PASSED)+1; } // The the value. Must be a number. Break out 
                            else { $store_arr['seconds']=$lastchar; } // assign the value to the array 
                            break; 
                        case "g": 
                            if (!is_numeric($lastchar)) { $store_arr = FALSE; $i = strlen($PASSED)+1; } // The the value. Must be a number. Break out 
                            else { $store_arr['hours']=$lastchar; $bln_twelve_hour_cycle= TRUE; } // assign the value to the array 
                            break; 
                        case "G": 
                            if (!is_numeric($lastchar)) { $store_arr = FALSE; $i = strlen($PASSED)+1; } // The the value. Must be a number. Break out 
                            else { $store_arr['hours']=$lastchar; $bln_twelve_hour_cycle= FALSE; } // assign the value to the array 
                            break; 
                    } 
                    break; 
                case 2: 
                    $lastchar = substr($PASSED,$i,2); 
                    if (strlen($lastchar)!=2) { // Crap. We ran off the end of the string. Error out 
                        $store_arr = FALSE; // Set the return value to false 
                        $i = strlen($PASSED)+1; // Break the loop 
                    } 
                    else { // Right length. Test Type 
                        $i++; // Move in string pointer forward 1 
                        switch ($dte_frmt_lstchr) { 
                            case "A": 
                                if (strtoupper($lastchar)!="AM" && strtoupper($lastchar)!="PM") { $store_arr = FALSE; $i = strlen($PASSED)+1; } // Invalid AM/PM. Crash and burn 
                                else { $store_arr['ampm']=strtoupper($lastchar); } // assign the value to the array 
                                break;   
                            case "a": 
                                if (strtoupper($lastchar)!="AM" && strtoupper($lastchar)!="PM") { $store_arr = FALSE; $i = strlen($PASSED)+1; } // Invalid AM/PM. Crash and burn 
                                else { $store_arr['ampm']=strtoupper($lastchar); } // assign the value to the array 
                                break; 
                            case "H": 
                                if (!is_numeric($lastchar)) { $store_arr = FALSE; $i = strlen($PASSED)+1; } // The the value. Must be a number. Break out 
                                else { $store_arr['hours']=$lastchar; $bln_twelve_hour_cycle= FALSE; } // assign the value to the array 
                                break;   
                            case "h": 
                                if (!is_numeric($lastchar)) { $store_arr = FALSE; $i = strlen($PASSED)+1; } // The the value. Must be a number. Break out 
                                else { $store_arr['hours']=$lastchar; $bln_twelve_hour_cycle= TRUE; } // assign the value to the array 
                                break;   
                            case "i": 
                                if (!is_numeric($lastchar)) { $store_arr = FALSE; $i = strlen($PASSED)+1; } // The the value. Must be a number. Break out 
                                else { $store_arr['minutes']=$lastchar; } // assign the value to the array 
                                break;   
                            case "s": 
                                if (!is_numeric($lastchar)) { $store_arr = FALSE; $i = strlen($PASSED)+1; } // The the value. Must be a number. Break out 
                                else { $store_arr['seconds']=$lastchar; } // assign the value to the array 
                                break;   
                            case "d": 
                                if (!is_numeric($lastchar)) { $store_arr = FALSE; $i = strlen($PASSED)+1; } // The the value. Must be a number. Break out 
                                else { $store_arr['mday']=$lastchar; } // assign the value to the array 
                                break;                          
                            case "m": 
                                if (!is_numeric($lastchar)) { $store_arr = FALSE; $i = strlen($PASSED)+1; } // The the value. Must be a number. Break out 
                                else { $store_arr['mon']=$lastchar; } // assign the value to the array 
                                break; 
                            case "y": 
                                if (!is_numeric($lastchar)) { $store_arr = FALSE; $i = strlen($PASSED)+1; } // The the value. Must be a number. Break out 
                                else { 
                                    if ($lastchar<70) { $lastchar="20".$lastchar; } 
                                    else { $lastchar="19".$lastchar; } 
                                    $store_arr['year']=$lastchar; 
                                } // assign the value to the array 
                                break; 
                        } 
                    } 
                    break; 
                case 4: 
                    $lastchar = substr($PASSED,$i,4); 
                    if (strlen($lastchar)!=4) { // Crap. We ran off the end of the string. Error out 
                        $store_arr = FALSE; // Set the return value to false 
                        $i = strlen($PASSED)+1; // Break the loop 
                    } 
                    else { // Right length. Test Type 
                        $i=$i+3; // Move in string pointer forward 3 
                        switch ($dte_frmt_lstchr) { 
                            case "Y": 
                                if (!is_numeric($lastchar)) { $store_arr = FALSE; $i = strlen($PASSED)+1; } // The the value. Must be a number. Break out 
                                else { $store_arr['year']=$lastchar; } // assign the value to the array 
                                break; 
                        } 
                    } 
                    break; 
            } // END switch 
        } // END else get the date value 
    } 
    if (isset($store_arr['hours'])) { // are hours are set? 
        if ($bln_twelve_hour_cycle) { // If the recieved data was 12-hour cycle, we may need to test for PM and do some math! 
            if (isset($store_arr['ampm'])) { 
                if ($store_arr['ampm']=="PM") { // Is it PM? If so test to see if hour is set 
                    $store_arr['hours']=$store_arr['hours']+12; // The 12 hour date was in PM. Example 11 pm really is 11+12 or 23! 
                } 
                else { // This is AM. Only 1 test needs to be done: 12 am! 
                    if ($store_arr['hours']==12) { $store_arr['hours']=0; } // 12am in 24 cycle is really 0 (0-23!) 
                } 
            } 
        } 
    } 
    if (isset($store_arr['ampm'])) { 
        unset($store_arr['ampm']); 
    } 
    return $store_arr; 
}

// http://de3.php.net/manual/en/function.is-numeric.php
// kiss dot pal at expert-net dot hu
// 05-Jan-2005 09:13 
function is_float_ex($pNum) {
	$num_chars = "0123456789.,+-";
	if(strlen(trim($pNum)) == 0) { // empty $pNum -> null
		return false;
	} else {
		$i = 0;
		$f = 1;  // modify 
		$v = strlen($num_chars) - $f;
		while(($i < strlen($pNum)) && ($v >= 0)) {
			$v=strlen($num_chars)-$f;
			while(($v >= 0) && ($num_chars[$v] <> $pNum[$i])) {
				$v--;
			}
			if($f==1) { // Only first item + vagy -
				$f=3;
		 	}
			if(($pNum[$i] == '.') || ($pNum[$i] == ',')) {
				$f=5;
			}
			$i++;
		}
		return ($v < 0) ? false : true;
	}
}


/*
 * {SHOW_CONTENT} 
 * thanks to Jens Zetterström who has initiated this in 2005
 * Shows the content of the article content part with the specified id.
 * use it {SHOW_CONTENT:MODE,id[,id[,...]]}
 * where MODE is what should returned
 * and id is the corresponding ID
 * MODE options:
 *   CP   - list of Content Parts | id = id of the content part, one or more possible, comma seperated.
 *   CPA  - ascending list of Content Parts but based on selected article  | id = id of article, comma seperated
 *   CPAD - same as CPA, but descending
 *   AS   - list of Article Summaries | id = id of articles, comma separated
 *   CAS  - list of Article Summaries | id = id of structure level, comma separated
 */
function showSelectedContent($param='') {

	global $template_default;
	global $db;
	global $content;
	global $block;
	global $phpwcms;
	global $aktion;
	
	$topcount = 999999;
	$template = '';
	
	if($cp = explode(',', $param)) {
		$mode	= strtoupper(trim($cp[0]));
		if(substr($mode, 0, 2) == 'AS') {
			$mode = explode('|', $cp[0]);
			if(isset($mode[1])) {
				$mode[1] = trim($mode[1]);
				if(is_numeric($mode[1])) {
					$topcount = intval($mode[1]);
				} elseif(empty($mode[2]) && strlen($mode[1]) > 4 && ($mode[1] == 'default' || is_file(PHPWCMS_TEMPLATE.'inc_cntpart/articlesummary/list/'.$mode[1]))) {
					$template = $mode[1];
				}
			}
			if(isset($mode[2])) {
				$mode[2] = trim($mode[2]);
				if(is_numeric($mode[2])) {
					$topcount = intval($mode[2]);
				} elseif(strlen($mode[2]) > 4 && ($mode[2] == 'default' || is_file(PHPWCMS_TEMPLATE.'inc_cntpart/articlesummary/list/'.$mode[2]))) {
					$template = $mode[2];
				}
			}
			$mode = strtoupper(trim($mode[0]));
			if(isset($cp[1])) { // now check if 
				$cp[1] = trim($cp[1]);
				if(!is_numeric($cp[1])) {
					switch($cp[1]) {
						case 'new':		$cp = array('new'		=> 1);	break;
						case 'random':	$cp = array('random'	=> 1);	break;
						case 'related':	if(isset($cp[2])) {
											unset($cp[0], $cp[1]);
											$related = array();
											foreach($cp as $value) {
												$related[] = "article_keyword LIKE '%".aporeplace(strtoupper(trim($value)))."%'";
											}
											$cp = array('related' => 1); break;
										}
					
						default:		$cp = array('new'		=> 1);
					}
				}
			}
		}
		unset($cp[0]);
		foreach($cp as $key => $value) {
			$value	= intval($value);
			if(!$value) {
				unset($cp[$key]);
			} else {
				$cp[$key] = $value;
			}
		}
		if(!is_array($cp) || !count($cp)) {
			return '';
		}
	} else {
		// oh no ID given, end function
		return '';
	}
	
	$CNT_TMP = '';
	
	if(substr($mode, 0, 2) == 'AS') {
	
		if(substr($mode, -1) == 'P') {
			$mode = substr($mode, 0, -1);
			$priorize = 'article_priorize DESC, ';
		} else {
			$priorize = '';
		}
		
		switch($mode) {
							
			case 'ASL':		$sort = $priorize.'article_begin ASC';		break; // sorted by livedate ascending
			case 'ASLD':	$sort = $priorize.'article_begin DESC';		break; // sorted by livedate descending
			case 'ASK':		$sort = $priorize.'article_end ASC';		break; // sorted by killdate ascending
			case 'ASKD':	$sort = $priorize.'article_end DESC';		break; // sorted by killdate descending
			case 'ASC':		$sort = $priorize.'article_tstamp ASC';		break; // sorted by change date ascending
			case 'ASCD':	$sort = $priorize.'article_tspamp DESC';	break; // sorted by change date descending
			case 'ASR':		$sort = 'RAND()';							break; // random sort
			default:		$sort = '';

		}

		$CNT_TMP = list_articles_summary( get_article_data( $cp, $topcount, $sort ) , $topcount, $template);


	} elseif($mode == 'CP' || $mode == 'CPA' || $mode == 'CPAD') {
	
		$sort = ($mode=='CPAD') ? ' DESC' : ''; //means ASCENDING
	
		foreach($cp as $value) {
		
			if($mode == 'CP') { 
				// content part listing
				$sql  = "SELECT * FROM " . DB_PREPEND . "phpwcms_articlecontent ";
				$sql .= "INNER JOIN " . DB_PREPEND . "phpwcms_article ON ";
				$sql .= DB_PREPEND . "phpwcms_article.article_id = " . DB_PREPEND . "phpwcms_articlecontent.acontent_aid ";
				$sql .= "WHERE acontent_id = " . $value . " AND acontent_visible = 1 ";
				
				if( !FEUSER_LOGIN_STATUS ) {
					$sql .= 'AND acontent_granted=0 ';
				}
				
				$sql .= "AND acontent_trash = 0 AND " . DB_PREPEND . "phpwcms_article.article_deleted=0 AND ";
				$sql .= DB_PREPEND."phpwcms_article.article_begin < NOW() AND " . DB_PREPEND . "phpwcms_article.article_end > NOW() ";
				$sql .= "LIMIT 1";
				
			} else {
				// content parts based on article ID				
				$sql  = "SELECT * FROM ".DB_PREPEND."phpwcms_articlecontent ";
				$sql .= "WHERE acontent_aid=". $value." AND acontent_visible=1 AND acontent_trash=0 ";
				
				if( !FEUSER_LOGIN_STATUS ) {
					$sql .= 'AND acontent_granted=0 ';
				}
				
				$sql .= "ORDER BY acontent_sorting".$sort.", acontent_id";
				
			}
		
			if($cresult = mysql_query($sql, $db)) {
				while($crow = mysql_fetch_assoc($cresult))	{
				
					if($crow["acontent_type"] == 30 && !isset($phpwcms['modules'][$crow["acontent_module"]])) {
						continue;
					}
				
					if($crow["acontent_type"] == 24) {
						// first retrieve alias ID information and settings
						$crow = getContentPartAlias($crow);
					}
				
					$space = getContentPartSpacer($crow["acontent_before"], $crow["acontent_after"]);
					
					// Space before
					$CNT_TMP .= $space['before'];
					
					// set frontend edit link
					$CNT_TMP .= getFrontendEditLink('CP', $crow['acontent_aid'], $crow['acontent_id']);
											
					// include content part code section
					if($crow["acontent_type"] != 30) {
					
						include(PHPWCMS_ROOT.'/include/inc_front/content/cnt' . $crow["acontent_type"] . '.article.inc.php');
					
					} elseif($crow["acontent_type"] == 30 && file_exists($phpwcms['modules'][$crow["acontent_module"]]['path'].'inc/cnt.article.php')) {
				
						$CNT_TMP .= getFrontendEditLink('module', $phpwcms['modules'][$crow["acontent_module"]]['name']);
				
						// now try to include module content part code
						include($phpwcms['modules'][$crow["acontent_module"]]['path'].'inc/cnt.article.php');
				
					}
			
					//check if top link should be shown
					$CNT_TMP .= getContentPartTopLink($crow["acontent_top"]);
					
					//Maybe content part ID should b used inside templates or for something different
					$CNT_TMP  = str_replace( array('[%CPID%]', '{CPID}'), $crow["acontent_id"], $CNT_TMP );
					
					// trigger content part functions
					$CNT_TMP = trigger_cp($CNT_TMP, $crow);
			
					// Space after
					$CNT_TMP .= $space['after'];
					
				}
				mysql_free_result($cresult);
			}
		}
	}
	
	if(empty($phpwcms["allow_cntPHP_rt"])) {
		$CNT_TMP = remove_unsecure_rptags($CNT_TMP);
	}
	return $CNT_TMP;
}

function getContentPartSpacer($space_before=0, $space_after=0) {

	if(!$space_before && !$space_after) {
		return array('before' => '', 'after'  => '');

	} elseif($space_before && $space_after) {
		return array('before' => '<div style="margin:' .$space_before. 'px 0 ' .$space_after. 'px 0; padding:0;">',	'after'  => '</div>');

	} elseif($space_before && !$space_after) {
		return array('before' => '<div style="margin:' .$space_before. 'px 0 0 0;padding:0;clear:both;"></div>', 'after'  => '');

	} else {
		return array('before' => '', 'after'  => '<div style="margin:0 0' .$space_after. 'px 0;padding:0;clear:both;"></div>');

	}
}

function getContentPartTopLink($param=0) {
	global $template_default;
	$toplink = '';
	if($param) {
		if($template_default["article"]["top_sign_before"].$template_default["article"]["top_sign_after"]) {
			$toplink .= $template_default["article"]["top_sign_before"];
			$toplink .= '<a href="#top">'.$template_default["article"]["top_sign"].'</a>';
			$toplink .= $template_default["article"]["top_sign_after"];
		} else{
			$toplink .= '<br /><a href="#top">' . $template_default["article"]["top_sign"] . '</a>';
		}
	}
	return $toplink;
}

function getContentPartAlias($crow) {
	global $db;
	$alias = unserialize($crow["acontent_form"]);
	if(!empty($alias['alias_ID'])) {
		$alias['alias_ID'] = intval($alias['alias_ID']);
		$sql_alias  = "SELECT * FROM ".DB_PREPEND."phpwcms_articlecontent WHERE acontent_id=";
		$sql_alias .= $alias['alias_ID'] . " AND acontent_trash=0 ";
		if( !FEUSER_LOGIN_STATUS ) {
			$sql_alias .= 'AND acontent_granted=0 ';
		}
		$sql_alias .= "LIMIT 1"; 
		if($alias_result = mysql_query($sql_alias, $db)) {
			if($alias_row = mysql_fetch_assoc($alias_result)) {
				if(empty($alias['alias_block'])) {
					$alias_row['acontent_block'] = $crow['acontent_block'];
				}
				if(empty($alias['alias_spaces'])) {
					$alias_row['acontent_before'] = $crow['acontent_before'];
					$alias_row['acontent_after']  = $crow['acontent_after'];
				}
				if(empty($alias['alias_title'])) {
					$alias_row['acontent_title']     = $crow['acontent_title'];
					$alias_row['acontent_subtitle']  = $crow['acontent_subtitle'];
				}
				if(empty($alias['alias_toplink'])) {
					$alias_row['acontent_top'] = $crow['acontent_top'];
				}
				$crow = $alias_row;
			}
			mysql_free_result($alias_result);
		}
	}
	return $crow;
}


function get_article_data($article_id, $limit=0, $sort='', $where='') {

	if(is_string($article_id)) {
		$article_id = explode(',', $article_id);
	}
	if(is_array($article_id) && count($article_id)) {
		foreach($article_id as $value) {
			$value = intval($value);
			if(!$value) {
				unset($article_id);
			}
		}
	}
	if(!is_array($article_id) || !count($article_id)) {
		return array();
	}
	$article_id	= array_unique($article_id);

	$sql  = 'SELECT *, UNIX_TIMESTAMP(article_tstamp) AS article_date, ';
	$sql .= "UNIX_TIMESTAMP(article_begin) AS article_livedate, ";
	$sql .= "UNIX_TIMESTAMP(article_end) AS article_killdate ";
	$sql .= 'FROM '.DB_PREPEND.'phpwcms_article ';
	$sql .= 'WHERE ';
	
	// VISIBLE_MODE: 0 = frontend (all) mode, 1 = article user mode, 2 = admin user mode
	switch(VISIBLE_MODE) {
		case 0: $sql .= 'article_public=1 AND article_aktiv=1 AND ';
				break;
		case 1: $sql .= 'article_uid='.$_SESSION["wcs_user_id"].' AND ';
				break;
		//case 2: admin mode no additional neccessary
	}
	$sql .= 'article_deleted=0 AND article_begin < NOW() AND article_end > NOW() AND ';
	
	if($where === '') {
		$sql .= 'article_id IN (' . implode( ',', $article_id ) . ') ';
	} else {
		$sql .= ' ' . $where . ' ';
	}
	$sql .= 'GROUP BY article_id ';
	if($sort) {
		$sql .= 'ORDER BY '.$sort;
	}
	if($limit) {
		$sql .= ' LIMIT '.$limit;
	}
	
	$data	= array();
	$result	= _dbQuery($sql);
	
	if(!is_array($result)) {
		return array();
	}
	
	if($sort == '') {
		foreach($article_id as $row) {
			$data[$row] = '';
		}
	}
	
	foreach($result as $row) {

		$data[$row["article_id"]] = array(
								"article_id"		=> $row["article_id"],
								"article_cid"		=> $row["article_cid"],
								"article_title"		=> $row["article_title"],
								"article_subtitle"	=> $row["article_subtitle"],
								"article_keyword"	=> $row["article_keyword"],
								"article_summary"	=> $row["article_summary"],
								"article_redirect"	=> $row["article_redirect"],
								"article_date"		=> $row["article_date"],
								"article_username"	=> $row["article_username"],
								"article_sort"		=> $row["article_sort"],
								"article_notitle"	=> $row["article_notitle"],
								"article_created"	=> $row["article_created"],
								"article_image"		=> @unserialize($row["article_image"]),
								"article_timeout"	=> $row["article_cache"],
								"article_nosearch"	=> $row["article_nosearch"],
								"article_nositemap"	=> $row["article_nositemap"],
								"article_aliasid"	=> $row["article_aliasid"],
								"article_headerdata"=> $row["article_headerdata"],
								"article_morelink"	=> $row["article_morelink"],
								"article_begin"		=> $row["article_begin"],
								"article_end"		=> $row["article_end"],
								"article_alias"		=> $row["article_alias"],
								'article_livedate'	=> $row["article_livedate"],
								'article_killdate'	=> $row["article_killdate"]
										);
		// now check for article alias ID
		if($row["article_aliasid"]) {
			$aid = $row["article_id"];
			$alias_sql  = "SELECT *, UNIX_TIMESTAMP(article_tstamp) AS article_date, ";
			$alias_sql .= "UNIX_TIMESTAMP(article_begin) AS article_livedate, ";
			$alias_sql .= "UNIX_TIMESTAMP(article_end) AS article_killdate "; 
			$alias_sql .= "FROM ".DB_PREPEND."phpwcms_article ";
			$alias_sql .= "WHERE article_deleted=0 AND article_id=".intval($row["article_aliasid"]);
			if(!$row["article_headerdata"]) {
				switch(VISIBLE_MODE) {
					case 0: $alias_sql .= " AND article_public=1 AND article_aktiv=1";
							break;
					case 1: $alias_sql .= " AND article_uid=".$_SESSION["wcs_user_id"];
							break;
				}
				$alias_sql .= " AND article_begin < NOW() AND article_end > NOW()";
			}
			$alias_sql .= " AND article_deleted=0 LIMIT 1";
			$alias_result = _dbQuery($alias_sql);
			foreach($alias_result as $alias_row) {
				$data[$aid]["article_id"] = $alias_row["article_id"];
				// use alias article header data
				if(!$row["article_headerdata"]) {
					$data[$aid]["article_title"]	= $alias_row["article_title"];
					$data[$aid]["article_subtitle"]	= $alias_row["article_subtitle"];
					$data[$aid]["article_keyword"]	= $alias_row["article_keyword"];
					$data[$aid]["article_summary"]	= $alias_row["article_summary"];
					$data[$aid]["article_redirect"]	= $alias_row["article_redirect"];
					$data[$aid]["article_date"]		= $alias_row["article_date"];
					$data[$aid]["article_image"]	= @unserialize($alias_row["article_image"]);
					$data[$aid]["article_begin"]	= $alias_row["article_begin"];
					$data[$aid]["article_end"]		= $alias_row["article_end"];
					$data[$aid]['article_livedate']	= $alias_row["article_livedate"];
					$data[$aid]['article_killdate']	= $alias_row["article_killdate"];
				}
			}
		}
	}
	
	if($sort == '') {
		return array_diff($data, array(''));
	}
	
	return $data;
}

function convert2html($matches) {
	if(isset($matches[1])) {
		return htmlentities($matches[1]);
	}
}

function convert2htmlspecialchars($matches) {
	if(isset($matches[1])) {
		return html_specialchars($matches[1]);
	}
}

function parse_images($matches) {

	if(isset($matches[1])) {
		
		// Image file ID
		$img_id 	= intval($matches[1]);
		
		// check for Alt-Text
		$alt		= explode(' ', $matches[2], 2);
		$value		= explode('x', trim(strtolower($alt[0])));

		$alt		= isset($alt[1]) ? html_specialchars(trim($alt[1])) : '';
		
		if(substr($value[0], 0, 1) == '.') {
			$ext	= trim($value[0]);
		} else {
			$ext	= '.jpg';
		}
		
		$width		= isset($value[ 1 ]) ? intval($value[ 1 ]) : 0;
		$height		= isset($value[ 2 ]) ? intval($value[ 2 ]) : 0;
		$crop		= isset($value[ 3 ]) && intval($value[ 3 ]) === 1 ? 1 : 0;
		$quality	= isset($value[ 4 ]) ? intval($value[ 4 ]) : 0;
		
		$image		= '<img src="'.PHPWCMS_URL.'img/cmsimage.php/'.$width.'x'.$height.'x'.$crop;
		if($quality <= 100 && $quality >= 10) {
			$image .= 'x'.$quality;
		}
		$image	   .= '/'.$img_id.$ext.'" alt="'.$alt.'" border="0"';
		if(isset($matches[3])) {
		
			$title = html_specialchars( preg_replace('/\s+/', ' ', clean_slweg( xss_clean( $matches[3] ) ) ) );
			if($title !== '') {
				$image .= ' title="'.$title.'"';
			}
		}
		$image	   .= ' />';
		
		return $image;
		
	}

	return '<img src="'.PHPWCMS_URL.'img/leer.gif" alt="" border="0" />';

}

function parse_downloads($match) {

	if(isset($match[1])) {

		$value											= array();

		$value['cnt_object']['cnt_files']['id']			= convertStringToArray($match[1]);

		if(isset($value['cnt_object']['cnt_files']['id']) && is_array($value['cnt_object']['cnt_files']['id']) && count($value['cnt_object']['cnt_files']['id'])) {
		
			global $phpwcms;
		
			$IS_NEWS_CP										= true;
			
			$news											= array();
			$news['files_result']							= '';
			
			$crow											= array();
			
			$value['cnt_object']['cnt_files']['caption']	= isset($match[2]) ? @html_entity_decode(trim($match[2]), ENT_QUOTES, PHPWCMS_CHARSET) : '';		
			$value['files_direct_download']					= 0;
			$value['files_template']						= '';
			
			// include content part files renderer
			include(PHPWCMS_ROOT.'/include/inc_front/content/cnt7.article.inc.php');

			return $news['files_result'];
		
		}

	}

	return '';

}

/**
 * process content part trigger functions
 **/
function trigger_cp($CP, & $CPDATA) {
	foreach($GLOBALS['content']['CpTrigger'] as $trigger_function) {
		if(function_exists($trigger_function)) {
			$CP = $trigger_function($CP, $CPDATA);
		}
	}
	return $CP;
}

/**
 * register content part trigger function
 * @param	string	$function	name of the trigger function
 * @param	string	$method		method how trigger function should be registered
 *								LAST	- register as last, multiple possible
 *								FIRST	- register as first, multiple possible
 *								RFIRST	- if not registered as first
 *								RLAST	- if not registered as last
 *
 * Good place to place custom trigger function is
 * /template/inc_script/frontend_init
 *
 *   function replace_cp_word($text, & $data) {
 *       return str_replace('12345', '*12345 replaced by CPID:'.$data['acontent_id'].'*', $text);
 *   }
 *   register_cp_trigger('replace_cp_word');
 *
 **/
function register_cp_trigger($function='', $method='LAST') {
	if(is_string($function)) {
		switch($method) {
			case 'FIRST': 	
				array_unshift($GLOBALS['content']['CpTrigger'], $function);
				break;
		
			case 'RFIRST':
				if(!in_array($function, $GLOBALS['content']['CpTrigger'])) {
					array_unshift($GLOBALS['content']['CpTrigger'], $function);
				}
				break;
				
			case 'RLAST':
				if(!in_array($function, $GLOBALS['content']['CpTrigger'])) {
					array_push($GLOBALS['content']['CpTrigger'], $function);
				}
				break;

			case 'LAST':
			default:
				array_push($GLOBALS['content']['CpTrigger'], $function);
		}
	}
}

?>