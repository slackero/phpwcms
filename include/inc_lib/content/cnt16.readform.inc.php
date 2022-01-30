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

// Content Type E-Card
$content['ecard']['images']     = array();
$content["ecard"]["list"]       = isset($_POST["cimage_list"]) ? $_POST["cimage_list"] : array();

$content["ecard"]["width"]      = (intval($_POST["cecard_width"]))  ? intval($_POST["cecard_width"])  : '';
$content["ecard"]["height"]     = (intval($_POST["cecard_height"])) ? intval($_POST["cecard_height"]) : '';
$temp_width                     = $content["ecard"]["width"];
$temp_height                    = $content["ecard"]["height"];

$content["ecard"]["space"]      = intval($_POST["cecard_space"]);
$content["ecard"]["col"]        = intval($_POST["cecard_col"]);
$content["ecard"]["pos"]        = intval($_POST["cecard_pos"]);
$content["ecard"]["caption"]    = clean_slweg($_POST["cecard_caption"]);

$content["ecard"]["subject"]    = clean_slweg($_POST["cecard_subject"]);
$content["ecard"]["form"]       = slweg($_POST["cecard_form"]);
$content["ecard"]["mail"]       = slweg($_POST["cecard_mail"]);
$content["ecard"]["send"]       = slweg($_POST["cecard_send"]);
$content["ecard"]["zoom"]       = isset($_POST["cecard_zoom"]) ? 1 : 0;
$content["ecard"]["selector"]   = isset($_POST["cecard_selector"]) ? intval($_POST["cecard_selector"]) : 0;
$content["ecard"]["onover"]     = slweg($_POST["cecard_onover"]);
$content["ecard"]["onclick"]    = slweg($_POST["cecard_onclick"]);
$content["ecard"]["onout"]      = slweg($_POST["cecard_onout"]);

$imgx = 0;

$content["ecard"]["image_cctext"] = explode("\n", $content["ecard"]["caption"]);

// remove form tag from form template
$content["ecard"]["form"] = preg_replace("'<form[^>]*?>(.*?)</form>'si", '$1', $content["ecard"]["form"]);

if(is_array($content["ecard"]["list"]) && count($content["ecard"]["list"])) {

    $img_sql    = "SELECT * FROM ".DB_PREPEND."phpwcms_file WHERE (";
    $img_sort   = array();

    foreach($content["ecard"]["list"] as $key => $value) {
        if ($imgx) {
            $img_sql .= " OR ";
        }
        $img_sql .= "f_id=" . intval($value);
        $imgx++;
    }
    if(!$imgx) {
        $img_sql .= "0";
    }
    $img_sql .= ")";

    $img_result = _dbQuery($img_sql);

    // check for image information and get alle infos from file
    if(isset($img_result[0]['f_id'])) {

        // Gegenrechnen von Breite zu Anzahl Spalten und Bildabstand
        $temp_count_img = count($img_result);
        if($content["ecard"]["col"] > $temp_count_img) {
            $content["ecard"]["col"] = $temp_count_img;
        }
        $temp_img_maxwidth = $phpwcms["content_width"] - (($content["ecard"]["col"] - 1) * $content["ecard"]["space"]);
        $temp_img_maxwidth = intval($temp_img_maxwidth / $content["ecard"]["col"]);

        if((!RESPONSIVE_MODE && $content["ecard"]["width"] > $temp_img_maxwidth) || ($content["ecard"]["width"] == "")) {
            $content["ecard"]["width"] = $temp_img_maxwidth;
            $temp_width = $content["ecard"]["width"];
        }

        $imgx = 0;
        $current_img_key = 0;

        foreach($img_result as $img_row) {

            // set correct sorting
            foreach($content["ecard"]["list"] as $key => $value) {
                if($value == $img_row['f_id']) {
                    $current_img_key = $key;
                    unset($content["ecard"]["list"][$key]);
                    break;
                }
            }
            $content['ecard']['images'][$current_img_key][0] = $img_row['f_id'];
            $content['ecard']['images'][$current_img_key][1] = $img_row['f_name'];
            $content['ecard']['images'][$current_img_key][2] = $img_row['f_hash'];
            $content['ecard']['images'][$current_img_key][3] = $img_row['f_ext'];
            $content['ecard']['images'][$current_img_key][4] = $temp_width;
            $content['ecard']['images'][$current_img_key][5] = $temp_height;
            $content['ecard']['images'][$current_img_key][6] = isset($content["ecard"]["image_cctext"][$current_img_key]) ? trim($content["ecard"]["image_cctext"][$current_img_key]) : '';

            $imgx++;

        }

        ksort($content['ecard']['images']);
    }
}

unset($content["ecard"]["list"]);
