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

// ----------------------------------------------------------------
// obligate check for phpwcms constants
if (!defined('PHPWCMS_ROOT')) {
    die("You Cannot Access This Script Directly, Have a Nice Day.");
}
// ----------------------------------------------------------------



// Content Type List
// initiated by Jéróme

$crow['attr_class_id'] = array();
if($crow['acontent_attr_class']) {
    $crow['attr_class_id'][] = 'class="'.html($crow['acontent_attr_class']).'"';
}
if($crow['acontent_attr_id']) {
    $crow['attr_class_id'][] = 'id="'.html($crow['acontent_attr_id']).'"';
}

if(($crow['attr_class_id'] = implode(' ', $crow['attr_class_id']))) {
    $CNT_TMP .= '<div '.$crow['attr_class_id'].'>';
    $crow['attr_class_id_close'] = '</div>';
} else {
    $crow['attr_class_id_close'] = '';
}

$CNT_TMP .= headline($crow["acontent_title"], $crow["acontent_subtitle"], $template_default["article"]);

if(substr_count ($crow["acontent_text"], '~')) {

    //please proof that section again
    //the first line will always start with an delimeter
    //and every linebreak \n will be converted to <br />

    // split into all parent <li>
    $crow["acontent_text"] = substr($crow["acontent_text"], 1);
    $crow["acontent_text"] = str_replace("\r\n~", '###', $crow["acontent_text"]);
    $crow["acontent_text"] = str_replace("\n~", '###', $crow["acontent_text"]);

    $clist_listtype = @unserialize($crow["acontent_form"]);

    switch($clist_listtype['list_type']) {
        case 1:     $clist_listmain  = 'ol';
                    $clist_listentry = 'li';
                    break;

        case 2:     $clist_listmain  = 'dl';
                    $clist_listentry = 'dt';
                    break;

        default:    $clist_listmain  = 'ul';
                    $clist_listentry = 'li';
    }


    $clist_list = explode('###', $crow["acontent_text"]);
    $clist_line = count($clist_list);

    if($clist_line) {

        // start list
        $crow["acontent_text"]  = '<'.$clist_listmain.'>' . LF;

        // now check level depth
        $clist_level = array();
        foreach($clist_list as $key => $value) {

            $clist_diff = 0;

            $clist_level[$key] = 0;

            while(substr($value,0,1) == '~') {

                $value = substr($value, 1);
                $clist_level[$key]++;

            }
            $clist_list[$key] = $value;

        }

        //--------------------------------------------------------

        foreach($clist_list as $key => $value) {


            //check previous difference
            if(isset($clist_level[$key-1])) {
                $clist_diff = $clist_level[$key] - $clist_level[$key-1];
            } else {
                $clist_diff = $clist_level[$key];
            }

            //now create list stuff before value

            if($clist_diff > 0) {
                for($i=0; $i < $clist_diff; $i++) {
                    $crow["acontent_text"] .= '<'.$clist_listmain.'>' . LF;
                }
            }

            //proof if it is a <dl> and split into definition and description
            if($clist_listtype['list_type'] == 2) {
                $value = explode('|', $value);
                $value[1] = empty($value[1]) ? '' : trim($value[1]);
            } else {
                $value = array(0 => $value, 1 => '');
            }

            $value[0] = trim($value[0]);

            //insert value
            $crow["acontent_text"] .= '<'.$clist_listentry.'>'.plaintext_htmlencode($value[0]);
            if($clist_listtype['list_type'] == 2 && $value[1]) {
                $crow["acontent_text"] .= LF . '<dd>'.plaintext_htmlencode($value[1]).'</dd>' . LF;
            }

            //--------------------------------------------------------

            //check next difference

            if(isset($clist_level[$key+1])) {
                $clist_diff_next = $clist_level[$key] - $clist_level[$key+1];
            } else {
                $clist_diff_next = $clist_level[$key];
            }

            if($clist_diff_next == 0) {
                    //entry close tag
                    $crow["acontent_text"] .= '</'.$clist_listentry.'>' . LF;

            } elseif($clist_diff_next > 0) {
                //entry close tag and list close tag
                $crow["acontent_text"] .= '</'.$clist_listentry.'>' . LF . '</'.$clist_listmain.'>' . LF;
                if($clist_diff_next >= 1) {
                    for($i=0; $i < (abs($clist_diff_next)-1); $i++) {
                        //entry close tag
                        if(!$i) $crow["acontent_text"] .= '</'.$clist_listentry.'>' . LF;
                        //list close tag
                        $crow["acontent_text"] .= '</'.$clist_listmain.'>' . LF;
                    }
                    //entry close tag
                    $crow["acontent_text"] .= '</'.$clist_listentry.'>' . LF;
                }
            }
        }
        //list close tag
        $crow["acontent_text"] .= '</'.$clist_listmain.'>' . LF;
    }

    $CNT_TMP .= $crow["acontent_text"];

} else {

    // show text only and do nothing else
    $CNT_TMP .= div_class(plaintext_htmlencode($crow["acontent_text"]), $template_default["article"]["text_class"]);

}

$CNT_TMP .= $crow['attr_class_id_close'];