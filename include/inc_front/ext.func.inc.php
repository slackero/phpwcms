<?php
/**
 * phpwcms content management system
 *
 * @author Oliver Georgi <og@phpwcms.org>
 * @copyright Copyright (c) 2002-2022, Oliver Georgi
 * @license http://opensource.org/licenses/GPL-2.0 GNU GPL-2
 * @link http://www.phpwcms.org
 *
 **/

// added by jens for content type 89: poll
function showPollImage($image, $zoom = 0) {

    $image_border       = ' border="'.intval($GLOBALS["template_default"]["article"]["imagelist_border"]).'"';
    if(empty($GLOBALS["template_default"]["article"]["imagelist_imgclass"])) {
        $image_imgclass = '';
    } else {
        $image_imgclass = ' class="'.$GLOBALS["template_default"]["article"]["imagelist_imgclass"].'"';
    }

    $thumb_image = get_cached_image(array(
            "target_ext"    =>  $image[3],
            "image_name"    =>  $image[2].'.'.$image[3],
            "max_width"     =>  $image[4],
            "max_height"    =>  $image[5],
            "thumb_name"    =>  md5($image[2].$image[4].$image[5].$GLOBALS['phpwcms']["sharpen_level"].$GLOBALS['phpwcms']['colorspace'])
    ));

    if($zoom) {
        $zoominfo = get_cached_image(array(
            "target_ext"    =>  $image[3],
            "image_name"    =>  $image[2] . '.' . $image[3],
            "max_width"     =>  $GLOBALS['phpwcms']["img_prev_width"],
            "max_height"    =>  $GLOBALS['phpwcms']["img_prev_height"],
            "thumb_name"    =>  md5($image[2].$GLOBALS['phpwcms']["img_prev_width"].$GLOBALS['phpwcms']["img_prev_height"].$GLOBALS['phpwcms']["sharpen_level"].$GLOBALS['phpwcms']['colorspace'])
        ));
    }

    $list_img_temp  = '<img src="'.$thumb_image['src'].'" '.$thumb_image[3].$image_border.$image_imgclass.PHPWCMS_LAZY_LOADING.HTML_TAG_CLOSE;

    if($zoom && !empty($zoominfo)) {
        // if click enlarge the image
        $open_popup_link = 'image_zoom.php?'.getClickZoomImageParameter($zoominfo['src'], $zoominfo[3], $image[1]);
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
        $c = substr($str, $i, 1);
        $c1 = ord($c);
        if ($c1>>5 == 6) {  // 110x xxxx, 110 prefix for 2 bytes unicode
            $ret .= substr($str, $last, $i-$last); // append all the regular characters we've passed
            $c1 &= 31; // remove the 3 bit two bytes prefix
            $c2 = ord(substr($str, ++$i, 1)); // the next byte
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
function is_date($PASSED, $TXT_DATE_FORMAT='m/d/Y') {
    $lib_import_datearr = array();
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
                } elseif ($store_arr['hours']==12) { // This is AM. Only 1 test needs to be done: 12 am!
                    $store_arr['hours']=0; // 12am in 24 cycle is really 0 (0-23!)
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
 * Shows the content of the article content parts with the specified id.
 * use it {SHOW_CONTENT:MODE,id[,id[,...]]}
 * There are now even more possible options:
 * CP, CPA, CPAD, CPS, CPAS, CPASD,
 * AS, ASP, ASL, ASLD, ASK, ASKD, ASC, ASCD, ASR, AST, ASTD
 * - L = live, K = kill, C = change date, R = random, T = keyword
 * All AS* work as AS*P = Priorize mode
 * All AS* can have additional options:
 * - AS*|topcount; AS*|topcount|template; AS*|template
 * - AS*,new; AS*,random, AS*,related,keyword1,kewyword2,…
 * - AS*,related|AND,keyword1,kewyword2,…
 */
function showSelectedContent($param='', $cpsql=null, $listmode=false) {

    global $template_default;
    global $content;
    global $block;
    global $phpwcms;
    global $aktion;

    $topcount       = 999999;
    $template       = '';
    $param          = is_array($param) && isset($param[1]) ? $param[1] : $param;
    $type           = null;
    $mode           = null;
    $related_type   = 'OR';
    $where          = '';
    $not            = array();

    if($cpsql === null) {
        if($cp = explode(',', $param)) {
            $mode = strtoupper(trim($cp[0]));
            $type = substr($mode, 0, 2);
            if($type === 'AS') {
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
                        $cp[1] = explode('|', $cp[1], 2);
                        // Check for OR or AND
                        if(isset($cp[1][1])) {
                            $related_type = strtoupper(trim($cp[1][1]));
                            if($related_type !== 'AND' && $related_type !== 'OR') {
                                $related_type = 'OR';
                            }
                        }
                        $cp[1] = trim($cp[1][0]);
                        switch($cp[1]) {
                            case 'random':
                                $where = 'RANDOM';
                                break;
                            case 'related':
                                if(isset($cp[2])) {
                                    unset($cp[0], $cp[1]);
                                    $related = array();
                                    foreach($cp as $value) {
                                        $related[] = "article_keyword LIKE "._dbEscape(strtoupper(trim($value)), true, '%', '%');
                                    }
                                    if(count($related)) {
                                        $where = '('.implode(' '.$related_type.' ', $related).')';
                                    }
                                }
                                break;
                            case 'new':
                            default:
                                $where = 'NEW';
                                break;
                        }
                        $not[] = $aktion[1];
                        $cp = array();
                    }
                }
            }
            if(count($cp)) {
                unset($cp[0]);
                foreach($cp as $key => $value) {
                    $value = intval($value);
                    if(!$value) {
                        unset($cp[$key]);
                    } else {
                        $cp[$key] = $value;
                    }
                }
                if(!count($cp)) {
                    return '';
                }
            }
        } else {
            // oh no ID given, end function
            return '';
        }

    } elseif(is_string($cpsql)) {

        // Otherwise custom SQL
        // and fallback to CPC mode
        $type   = 'CP';
        $mode   = 'CPC';
        $cp     = array(0);

    }

    $CNT_TMP = '';

    // Article Mode
    if($type === 'AS') {

        if(substr($mode, -1) == 'P') {
            $mode = substr($mode, 0, -1);
            $priorize = 'article_priorize DESC, ';
        } else {
            $priorize = '';
        }

        switch($mode) {

            case 'ASL':     $sort = $priorize.'article_begin ASC';      break; // sorted by livedate ascending
            case 'ASLD':    $sort = $priorize.'article_begin DESC';     break; // sorted by livedate descending
            case 'ASK':     $sort = $priorize.'article_end ASC';        break; // sorted by killdate ascending
            case 'ASKD':    $sort = $priorize.'article_end DESC';       break; // sorted by killdate descending
            case 'ASC':     $sort = $priorize.'article_tstamp ASC';     break; // sorted by change date ascending
            case 'ASCD':    $sort = $priorize.'article_tspamp DESC';    break; // sorted by change date descending
            case 'AST':     $sort = $priorize.'article_keyword ASC';    break; // sorted by keyword ascending
            case 'ASTD':    $sort = $priorize.'article_keyword DESC';   break; // sorted by keyword descending
            case 'ASR':     $sort = 'RAND()';                           break; // random sort
            default:        $sort = '';

        }

        $CNT_TMP = list_articles_summary( get_article_data( $cp, $topcount, $sort, $where, $not ) , $topcount, $template);

    // Content Part mode CP, CPA, CPAD, CPS, CPAS, CPASD
    } elseif($type === 'CP') {

        $sort = ($mode == 'CPAD' || $mode == 'CPASD') ? ' DESC' : ''; //means ASCENDING

        foreach($cp as $value) {

            if($mode == 'CP') {
                // content part listing
                $sql  = "SELECT * FROM " . DB_PREPEND . "phpwcms_articlecontent ac ";
                $sql .= "INNER JOIN " . DB_PREPEND . "phpwcms_article ar ON ";
                $sql .= "ar.article_id=ac.acontent_aid ";
                $sql .= "WHERE ac.acontent_id=" . $value . " AND ac.acontent_visible=1 ";
                $sql .= "AND ac.acontent_block NOT IN ('CPSET', 'SYSTEM') ";
                $sql .= 'AND ac.acontent_granted' . (FEUSER_LOGIN_STATUS ? '!=2' : '=0') . ' ';
                $sql .= "AND ac.acontent_trash=0 AND ar.article_deleted=0 AND ";
                $sql .= "ac.acontent_livedate < NOW() AND (ac.acontent_killdate='0000-00-00 00:00:00' OR ac.acontent_killdate > NOW()) ";

                if(!PREVIEW_MODE) {
                    $sql .= " AND ar.article_begin < NOW() AND (ar.article_end='0000-00-00 00:00:00' OR ar.article_end > NOW()) ";
                }
                $sql .= "LIMIT 1";

            } elseif($mode == 'CPS') {

                $sql  = "SELECT * FROM " . DB_PREPEND . "phpwcms_articlecontent ac ";
                $sql .= "INNER JOIN " . DB_PREPEND . "phpwcms_article ar ON ";
                $sql .= "ar.article_id=ac.acontent_aid ";
                $sql .= "WHERE ac.acontent_id=" . $value . " AND ac.acontent_visible=1 AND ";
                $sql .= "ac.acontent_livedate < NOW() AND (ac.acontent_killdate='0000-00-00 00:00:00' OR ac.acontent_killdate > NOW()) ";
                $sql .= "AND ac.acontent_block='SYSTEM' ";
                $sql .= 'AND ac.acontent_granted' . (FEUSER_LOGIN_STATUS ? '!=2' : '=0') . ' ';
                $sql .= "AND ac.acontent_trash=0 AND ar.article_deleted=0 ";
                if(!PREVIEW_MODE) {
                    $sql .= " AND ar.article_begin < NOW() AND (ar.article_end='0000-00-00 00:00:00' OR ar.article_end > NOW()) ";
                }
                $sql .= "LIMIT 1";

            } elseif($mode == 'CPC') {

                $sql = $cpsql;

            } else {

                // content parts based on article ID
                $sql  = "SELECT * FROM " . DB_PREPEND . "phpwcms_articlecontent ";
                $sql .= "WHERE acontent_aid=". $value." AND acontent_visible=1 AND acontent_trash=0 AND ";
                $sql .= "acontent_livedate < NOW() AND (acontent_killdate='0000-00-00 00:00:00' OR acontent_killdate > NOW()) ";

                if($mode == 'CPAS' || $mode == 'CPASD') {
                    $sql .= "AND acontent_block='SYSTEM' ";
                } else {
                    $sql .= "AND acontent_block NOT IN ('CPSET', 'SYSTEM') ";
                }

                $sql .= 'AND acontent_granted' . (FEUSER_LOGIN_STATUS ? '!=2' : '=0') . ' ';
                $sql .= "ORDER BY acontent_sorting".$sort.", acontent_id";

            }

            if(!empty($sql)) {

                $cresult = _dbQuery($sql);

                if(isset($cresult[0]['acontent_type'])) {
                    foreach($cresult as $crow)  {

                        if($crow["acontent_type"] == 30 && !isset($phpwcms['modules'][$crow["acontent_module"]])) {
                            continue;
                        }

                        if($crow["acontent_type"] == 24) {
                            // first retrieve alias ID information and settings
                            $crow = getContentPartAlias($crow);
                            if($crow === false) {
                                continue;
                            }
                        }

                        // Set listmode setting, allows fallback listmode content part template
                        // for content parts which supports it (ToDo extend it)
                        $crow['acontent_template_listmode'] = $listmode;

                        $space = getContentPartSpacer($crow["acontent_before"], $crow["acontent_after"]);

                        // Space before
                        $CNT_TMP .= $space['before'];

                        // set frontend edit link
                        $CNT_TMP .= getFrontendEditLink('CP', $crow['acontent_aid'], $crow['acontent_id']);

                        // include content part code section
                        if($crow["acontent_type"] != 30) {

                            include PHPWCMS_ROOT.'/include/inc_front/content/cnt' . $crow["acontent_type"] . '.article.inc.php';

                        } elseif($crow["acontent_type"] == 30 && file_exists($phpwcms['modules'][$crow["acontent_module"]]['path'].'inc/cnt.article.php')) {

                            $CNT_TMP .= getFrontendEditLink('module', $phpwcms['modules'][$crow["acontent_module"]]['name'], $crow['acontent_aid']);

                            // now try to include module content part code
                            include $phpwcms['modules'][$crow["acontent_module"]]['path'].'inc/cnt.article.php';

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
                }
            }
        }
    }

    if(empty($phpwcms["allow_cntPHP_rt"]) || empty($phpwcms['enable_inline_php'])) {
        $CNT_TMP = remove_unsecure_rptags($CNT_TMP);
    }
    return trim($CNT_TMP);
}

function getContentPartSpacer($space_before=0, $space_after=0) {

    $spacers = array(
        'before' => '',
        'after'  => ''
    );

    if($space_before === '' && $space_after === '') {
        return $spacers;
    }

    global $template_default;

    if(!empty($template_default["article"]["div_spacer"])) {

        if(empty($template_default['article']['div_spacer_tag'])) {
            $template_default['article']['div_spacer_tag'] = 'div';
        }

        if(empty($template_default['article']['div_spacer_style'])) {
            $template_default['article']['div_spacer_style'] = 'margin';
        }

        if(empty($template_default['article']['div_spacer_unit'])) {
            $template_default['article']['div_spacer_unit'] = 'px';
        }

    }

    if($space_before !== '' && $space_after !== '') {

        if(empty($template_default["article"]["div_spacer"])) {
            $spacers['before'] = '<br class="'.$template_default['classes']['spaceholder-cp-before'].'" />'.spacer(1, $space_before);
            $spacers['after'] = '<br class="'.$template_default['classes']['spaceholder-cp-after'].'" />'.spacer(1, $space_after);
    } else {
            $spacers['before'] .= '<'.$template_default['article']['div_spacer_tag'].' style="';
            if($template_default['article']['div_spacer_style'] === 'padding' || $template_default['article']['div_spacer_style'] === 'height') {
                $spacers['before'] .= 'padding-top:'.$space_before.$template_default['article']['div_spacer_unit'].';';
                $spacers['before'] .= 'padding-bottom:'.$space_after.$template_default['article']['div_spacer_unit'].';';
            } else {
                $spacers['before'] .= 'margin-top:'.$space_before.$template_default['article']['div_spacer_unit'].';';
                $spacers['before'] .= 'margin-bottom:'.$space_after.$template_default['article']['div_spacer_unit'].';';
            }
            $spacers['before'] .= '" class="'.trim($template_default['classes']['spaceholder-cp-before'].' '.$template_default['classes']['spaceholder-cp-after']).'">';
            $spacers['after'] .= '</'.$template_default['article']['div_spacer_tag'].'>';
        }

    } elseif($space_before !== '') {

        if(empty($template_default["article"]["div_spacer"])) {
            $spacers['before'] = '<br class="'.$template_default['classes']['spaceholder-cp-before'].'" />'.spacer(1, $space_before);
        } else {
            $spacers['before'] .= '<'.$template_default['article']['div_spacer_tag'].' style="';
            if($template_default['article']['div_spacer_style'] === 'padding') {
                $spacers['before'] .= 'padding-top';
            } elseif($template_default['article']['div_spacer_style'] === 'height') {
                $spacers['before'] .= 'height';
            } else {
                $spacers['before'] .= 'margin-top';
            }
            $spacers['before'] .= ':'.$space_before.$template_default['article']['div_spacer_unit'].';" ';
            $spacers['before'] .= 'class="'.$template_default['classes']['spaceholder-cp-before'].'">';
            $spacers['before'] .= '</'.$template_default['article']['div_spacer_tag'].'>';
        }

    } elseif(empty($template_default["article"]["div_spacer"])) {
        $spacers['after'] = '<br class="'.$template_default['classes']['spaceholder-cp-after'].'" />'.spacer(1, $space_after);
    } else {
         $spacers['after'] .= '<'.$template_default['article']['div_spacer_tag'].' style="';
        if($template_default['article']['div_spacer_style'] === 'padding') {
            $spacers['after'] .= 'padding-bottom';
        } elseif($template_default['article']['div_spacer_style'] === 'height') {
            $spacers['after'] .= 'height';
        } else {
            $spacers['after'] .= 'margin-bottom';
        }
        $spacers['after'] .= ':'.$space_after.$template_default['article']['div_spacer_unit'].';" ';
        $spacers['after'] .= 'class="'.$template_default['classes']['spaceholder-cp-after'].'">';
        $spacers['after'] .= '</'.$template_default['article']['div_spacer_tag'].'>';
    }

    return $spacers;
}

function getContentPartTopLink($param=0) {
    global $template_default;
    $toplink = '';
    if($param) {
        if($template_default["article"]["top_sign_before"].$template_default["article"]["top_sign_after"]) {
            $toplink .= $template_default["article"]["top_sign_before"];
            $toplink .= '<a href="'.rel_url().'#top">'.$template_default["article"]["top_sign"].'</a>';
            $toplink .= $template_default["article"]["top_sign_after"];
        } else{
            $toplink .= '<br /><a href="'.rel_url().'#top">' . $template_default["article"]["top_sign"] . '</a>';
        }
    }
    return $toplink;
}

function getContentPartAlias($crow) {

    $alias = @unserialize($crow["acontent_form"]);
    $alias_visible = false;
    if(!empty($alias['alias_ID'])) {
        $alias['alias_ID'] = intval($alias['alias_ID']);
        $sql_alias  = "SELECT * FROM ".DB_PREPEND."phpwcms_articlecontent WHERE acontent_id=";
        $sql_alias .= $alias['alias_ID'] . " AND acontent_trash=0 AND ";
        $sql_alias .= "acontent_livedate < NOW() AND (acontent_killdate='0000-00-00 00:00:00' OR acontent_killdate > NOW()) ";
        if(!empty($alias['alias_status'])) {
            $sql_alias .= 'AND acontent_visible=1 ';
        }
        $sql_alias .= 'AND acontent_granted' . (FEUSER_LOGIN_STATUS ? '!=2' : '=0') . ' ';
        $sql_alias .= "LIMIT 1";

        $result = _dbQuery($sql_alias);
        if(isset($result[0]['acontent_id'])) {
            if(empty($alias['alias_block'])) {
                $result[0]['acontent_block'] = $crow['acontent_block'];
            }
            if(empty($alias['alias_spaces'])) {
                $result[0]['acontent_before'] = $crow['acontent_before'];
                $result[0]['acontent_after']  = $crow['acontent_after'];
            }
            if(empty($alias['alias_title'])) {
                $result[0]['acontent_title']     = $crow['acontent_title'];
                $result[0]['acontent_subtitle']  = $crow['acontent_subtitle'];
            }
            if(empty($alias['alias_toplink'])) {
                $result[0]['acontent_top'] = $crow['acontent_top'];
            }
            $crow = $result[0];
            $alias_visible = true;
        }
    }

    if(!$alias_visible) {
        $crow = false;
    }

    return $crow;
}


function get_article_data($article_id, $limit=0, $sort='', $where='', $not=array()) {

    if(is_string($article_id)) {
        $article_id = explode(',', $article_id);
    }
    if(is_array($article_id) && count($article_id)) {
        foreach($article_id as $key => $value) {
            $value = intval($value);
            if(!$value) {
                unset($article_id[$key]);
            }
            $article_id[$key] = $value;
        }
        if(count($article_id)) {
            $article_id = array_unique($article_id);
        }
    }
    if(!is_array($article_id) || !count($article_id)) {
        if($where === '') {
            return array();
        }
        $article_id = array();
    }

    $sql  = 'SELECT *, UNIX_TIMESTAMP(article_tstamp) AS article_date, ';
    $sql .= "UNIX_TIMESTAMP(article_begin) AS article_livedate, ";
    $sql .= "UNIX_TIMESTAMP(article_end) AS article_killdate ";
    $sql .= 'FROM '.DB_PREPEND.'phpwcms_article ';

    $sql_where = array('article_deleted=0');

    // VISIBLE_MODE: 0 = frontend (all) mode, 1 = article user mode, 2 = admin user mode
    switch(VISIBLE_MODE) {
        case 0: $sql_where[] = 'article_aktiv=1';
                break;
        case 1: $sql_where[] = '(article_aktiv=1 OR article_uid='.$_SESSION["wcs_user_id"].')';
                break;
    }
    if(!PREVIEW_MODE) {
        $sql_where[] = "article_begin < NOW() AND (article_end='0000-00-00 00:00:00' OR article_end > NOW())";
    }

    if(count($not)) {
        $sql_where[] = 'article_id NOT IN (' . implode( ',', $not ) . ')';
        $article_id = array_diff($article_id, $not);
    }

    if($where === '') {
        $sql_where[] = 'article_id IN (' . implode( ',', $article_id ) . ')';
    } elseif($where === 'RANDOM') {

        $sort = 'RAND()';

        if(count($article_id)) {
            $sql_where[] = 'article_id IN (' . implode( ',', $article_id ) . ')';
        }

    } elseif($where === 'NEW') {

        if($sort) {
            $sort = ','.$sort;
        }
        $sort = 'article_created DESC'.$sort;

        if(count($article_id)) {
            $sql_where[] = 'article_id IN (' . implode( ',', $article_id ) . ')';
        }

    } else {
        $sql_where[] = $where;
    }
    if(count($sql_where)) {
        $sql .= 'WHERE '.implode(' AND ', $sql_where).' ';
        $sql .= 'GROUP BY article_id ';
    }
    if($sort) {
        $sql .= 'ORDER BY '.$sort;
    }
    if($limit) {
        $sql .= ' LIMIT '.$limit;
    }

    $data   = array();
    $result = _dbQuery($sql);

    if(!is_array($result)) {
        return array();
    }

    if($sort === '') {
        foreach($article_id as $row) {
            $data[$row] = '';
        }
    }

    foreach($result as $row) {

        $row["article_id"] = intval($row["article_id"]);

        $data[$row["article_id"]] = array(
            "article_id"        => $row["article_id"],
            "article_cid"       => $row["article_cid"],
            "article_title"     => $row["article_title"],
            "article_subtitle"  => $row["article_subtitle"],
            "article_keyword"   => $row["article_keyword"],
            "article_summary"   => $row["article_summary"],
            "article_redirect"  => $row["article_redirect"],
            "article_date"      => $row["article_date"],
            "article_username"  => $row["article_username"],
            "article_sort"      => $row["article_sort"],
            "article_notitle"   => $row["article_notitle"],
            "article_created"   => $row["article_created"],
            "article_image"     => @unserialize($row["article_image"]),
            "article_timeout"   => $row["article_cache"],
            "article_nosearch"  => $row["article_nosearch"],
            "article_nositemap" => $row["article_nositemap"],
            "article_aliasid"   => $row["article_aliasid"],
            "article_headerdata"=> $row["article_headerdata"],
            "article_morelink"  => $row["article_morelink"],
            "article_begin"     => $row["article_begin"],
            "article_end"       => $row["article_end"],
            "article_alias"     => $row["article_alias"],
            'article_livedate'  => $row["article_livedate"],
            'article_killdate'  => $row["article_killdate"]
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
                    case 0: $alias_sql .= " AND article_aktiv=1";
                            break;
                    case 1: $alias_sql .= " AND (article_aktiv=1 OR article_uid=".$_SESSION["wcs_user_id"].')';
                            break;
                }
                if(!PREVIEW_MODE) {
                    $alias_sql .= " AND article_begin < NOW() AND (article_end='0000-00-00 00:00:00' OR article_end > NOW())";
                }
            }
            $alias_sql .= " AND article_deleted=0 LIMIT 1";
            $alias_result = _dbQuery($alias_sql);
            foreach($alias_result as $alias_row) {
                $data[$aid]["article_id"] = $alias_row["article_id"];
                // use alias article header data
                if(!$row["article_headerdata"]) {
                    $data[$aid]["article_title"]    = $alias_row["article_title"];
                    $data[$aid]["article_subtitle"] = $alias_row["article_subtitle"];
                    $data[$aid]["article_keyword"]  = $alias_row["article_keyword"];
                    $data[$aid]["article_summary"]  = $alias_row["article_summary"];
                    $data[$aid]["article_redirect"] = $alias_row["article_redirect"];
                    $data[$aid]["article_date"]     = $alias_row["article_date"];
                    $data[$aid]["article_image"]    = @unserialize($alias_row["article_image"]);
                    $data[$aid]["article_begin"]    = $alias_row["article_begin"];
                    $data[$aid]["article_end"]      = $alias_row["article_end"];
                    $data[$aid]['article_livedate'] = $alias_row["article_livedate"];
                    $data[$aid]['article_killdate'] = $alias_row["article_killdate"];
                }
            }
        }
    }

    if($sort === '' && count($data)) {
        foreach($data as $key => $value) {
            if($value === '') {
                unset($data[$key]);
            }
        }
    }

    return $data;
}

function convert2html($matches) {

    if(isset($matches[1])) {
        return html_entities($matches[1]);
    }

    return '';
}

function convert2htmlspecialchars($matches) {

    if(isset($matches[1])) {
        return html($matches[1]);
    }

    return '';
}

/**
 * Parse BBCode style [img] tags
 * [img=123.pngx200x100x1x85 alt Text]Title[/img]
 *
 * @param $matches
 * @return string
 */
function parse_images($matches) {

    if(isset($matches[1])) {

        // Image file ID
        $img_id     = intval($matches[1]);

        // check for Alt-Text
        $alt        = explode(' ', trim($matches[2]), 2);
        $value      = explode('x', trim(strtolower($alt[0])));

        $alt        = isset($alt[1]) ? html_specialchars(trim($alt[1])) : '';

        if(substr($value[0], 0, 1) == '.') {
            $ext    = trim($value[0]);
        } else {
            $ext    = '.jpg';
        }

        $width      = isset($value[ 1 ]) ? intval($value[ 1 ]) : 0;
        $height     = isset($value[ 2 ]) ? intval($value[ 2 ]) : 0;
        $crop       = isset($value[ 3 ]) ? intval($value[ 3 ]) : 0;
        $quality    = isset($value[ 4 ]) ? intval($value[ 4 ]) : 0;

        $image      = '<img src="'.PHPWCMS_URL.PHPWCMS_RESIZE_IMAGE.'/'.$width.'x'.$height.'x'.$crop;
        if($quality <= 100 && $quality >= 10) {
            $image .= 'x'.$quality;
        }
        $image     .= '/'.$img_id.$ext.'" alt="'.$alt.'"';
        if($width) {
            $image .= ' width="' . $width . '"';
        }
        if($height) {
            $image .= ' height="' . $height . '"';
        }
        if(isset($matches[3])) {

            $title = html( preg_replace('/\s+/', ' ', clean_slweg( xss_clean( $matches[3] ) ) ) );
            if($title) {
                $image .= ' title="'.$title.'"';
            }
        }

        $class = empty($GLOBALS['template_default']['classes']['image-parse-inline'])  ? 'img-bbcode' : $GLOBALS['template_default']['classes']['image-parse-inline'];
        $image     .= ' class="' . $class . ' ' . $class . '-' .$img_id . '"' . PHPWCMS_LAZY_LOADING . HTML_TAG_CLOSE;

        return $image;

    }

    return '';

}

function parse_downloads($match) {

    if(isset($match[1])) {

        $value = array();
        $value['cnt_object']['cnt_files']['id'] = convertStringToArray($match[1]);

        if(isset($value['cnt_object']['cnt_files']['id']) && is_array($value['cnt_object']['cnt_files']['id']) && count($value['cnt_object']['cnt_files']['id'])) {

            global $phpwcms;

            $value['cnt_object']['cnt_files']['caption'] = isset($match[3]) ? @html_entity_decode(trim($match[3]), ENT_QUOTES, PHPWCMS_CHARSET) : '';
            $value['files_direct_download'] = 0;
            $value['files_template'] = 'download-inline';
            if(!empty($match[2])) {
                $match[2] = explode('=', trim($match[2]));
                if(!empty($match[2][1])) {
                    $value['files_template'] = trim($match[2][1]);
                    if(which_ext($value['files_template']) == '') {
                        $value['files_template'] .= '.tmpl';
                    }
                }
            }

            $IS_NEWS_CP = true;
            $crow       = array();
            $news       = array('files_result' => '');

            // include content part files renderer
            include PHPWCMS_ROOT.'/include/inc_front/content/cnt7.article.inc.php';

            if($news['files_result']) {
                return $news['files_result'];
            }

        }

    }

    return isset($match[3]) ? $match[3] : '';

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
 * @param   string  $function   name of the trigger function
 * @param   string  $method     method how trigger function should be registered
 *                              LAST    - register as last, multiple possible
 *                              FIRST   - register as first, multiple possible
 *                              RFIRST  - if not registered as first
 *                              RLAST   - if not registered as last
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


/**
 * Check referrer string for search engine related information
 * and log those fetched data in database
 * Basic idea: http://www.tellinya.com/read/2007/07/11/34.html
 *
 * @return  array
 * @param   string  referrer string
 *
 **/
function seReferrer($ref = false) {

    if(!empty($ref) && is_string($ref)) {
        $SeReferer = trim($ref);
    } elseif(isset($_SERVER['HTTP_REFERER'])) {
        $SeReferer = trim($_SERVER['HTTP_REFERER']);
    } else {
        return false;
    }
    $SePos      = 0;
    $SeDomain   = '';

    //Check against Google, Yahoo, MSN, Ask and others
    if( $SeReferer && preg_match('/[&\?](q|p|w|s|qry|searchfor|as_q|as_epq|query|qt|keyword|keywords|encquery)=([^&]+)/i', $SeReferer, $pcs) ){
        if( preg_match("/https?:\/\/([^\/]+)\//i", $SeReferer, $SeDomain) ) {
            $SeDomain   = trim(strtolower($SeDomain[1]));
            $SeQuery    = $pcs[2];
            if(preg_match("/[&\?](start|b|first|stq)=([0-9]*)/i",$SeReferer,$pcs)) {
                $SePos  = (int)trim($pcs[2]);
            }
        }
    }
    if(!isset($SeQuery)){
        //Check against DogPile
        if( preg_match('/\/search\/web\/([^\/]+)\//i', $SeReferer, $pcs) ) {
            if( preg_match("/https?:\/\/([^\/]+)\//i", $SeReferer, $SeDomain) ){
            $SeDomain   = trim(strtolower($SeDomain[1]));
            $SeQuery    = $pcs[1];
            }
        }

        // We Do Not have a query
        if(!isset($SeQuery)){
            return false;
        }
    }

    $OldQ       = $SeQuery;
    $SeQuery    = urldecode($SeQuery);

    // The Multiple URLDecode Trick to fix DogPile %XXXX Encodes
    while($SeQuery != $OldQ){
        $OldQ       = $SeQuery;
        $SeQuery    = urldecode($SeQuery);
    }

    // check given query and decode utf-8
    if(PHPWCMS_CHARSET != 'utf-8' && phpwcms_seems_utf8($SeQuery)) {
        $SeQuery = makeCharsetConversion($SeQuery, 'utf-8', PHPWCMS_CHARSET, false);
    }

    return array(   "domain"    => $SeDomain,
                    "query"     => $SeQuery,
                    "pos"       => $SePos,
                    "referrer"  => $SeReferer   );
}
