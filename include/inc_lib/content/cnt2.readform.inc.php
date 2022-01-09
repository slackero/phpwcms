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

// Content Type Images

$content["image_list"]      = isset($_POST["cimage_list"]) ? $_POST["cimage_list"] : array();
$content["image_pos"]       = intval($_POST["cimage_pos"]);

$content["image_width"]     = (intval($_POST["cimage_width"])) ? intval($_POST["cimage_width"]) : "";
$temp_width                 = $content["image_width"];

$content["image_height"]    = (intval($_POST["cimage_height"])) ? intval($_POST["cimage_height"]) : "";
$temp_height                = $content["image_height"];

$content["image_space"]     = intval($_POST["cimage_space"]);
$content["image_col"]       = intval($_POST["cimage_col"]);
$content["image_caption"]   = clean_slweg($_POST["cimage_caption"]);
$content["image_zoom"]      = empty($_POST["cimage_zoom"]) ? 0 : 1;
$content["image_cctext"]    = explode("\n", $content["image_caption"]);

$content["template"]        = clean_slweg($_POST['template']);

$content['tmp_images']      = array();
$imgx = 0;

if(is_array($content["image_list"]) && sizeof($content["image_list"])) {

    // zuweisen der passenden ImageID und Neuvergabe des Arrays
    $img_sql = "SELECT * FROM " . DB_PREPEND . "phpwcms_file WHERE (";
    $img_sort = array();

    foreach($content["image_list"] as $key => $value) {
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
        $content["image_col"] = ($content["image_col"] > $temp_count_img) ? $temp_count_img : $content["image_col"];

        if(RESPONSIVE_MODE) {
            $temp_width = $content["image_width"];
        } else {
            $temp_img_maxwidth = $phpwcms["content_width"] - (($content["image_col"] - 1) * $content["image_space"]);
            $temp_img_maxwidth = ($content["image_pos"] == 6 || $content["image_pos"] == 7) ? intval($temp_img_maxwidth / 1.75) : $temp_img_maxwidth;
            $temp_img_maxwidth = intval($temp_img_maxwidth / $content["image_col"]);

            if($content["image_width"] > $temp_img_maxwidth || $content["image_width"] == "") {
                $content["image_width"] = $temp_img_maxwidth;
                $temp_width = $content["image_width"];
            }
        }

        $imgx = 0;

        // try to handle multiple same image IDs

        $temp_img_row = array();
        foreach($img_result as $img_row ) {
            $temp_img_row[$img_row['f_id']] = $img_row;
        }

        foreach($content["image_list"] as $key => $value) {
            if(isset($temp_img_row[$value])) {

                $content['tmp_images'][$key][0] = $temp_img_row[$value]['f_id'];
                $content['tmp_images'][$key][1] = $temp_img_row[$value]['f_name'];
                $content['tmp_images'][$key][2] = $temp_img_row[$value]['f_hash'];
                $content['tmp_images'][$key][3] = $temp_img_row[$value]['f_ext'];
                $content['tmp_images'][$key][4] = $temp_width;
                $content['tmp_images'][$key][5] = $temp_height;
                $content['tmp_images'][$key][6] = isset($content["image_cctext"][$key]) ? trim($content["image_cctext"][$key]) : '';

            }
        }
    }
}

// take values
$content['image_list']              = array();
$content['image_list']['images']    = $content['tmp_images'];
$content['image_list']['width']     = $temp_width;
$content['image_list']['height']    = $temp_height;
$content['image_list']['pos']       = $content["image_pos"];
$content['image_list']['col']       = $content["image_col"];
$content['image_list']['zoom']      = $content["image_zoom"];
$content['image_list']['space']     = $content["image_space"];
$content['image_list']['lightbox']  = empty($_POST["cimage_lightbox"]) ? 0 : 1;
$content['image_list']['nocaption'] = empty($_POST["cimage_nocaption"]) ? 0 : 1;
$content['image_list']['crop']      = empty($_POST["cimage_crop"]) ? 0 : 1;
$content["image_list"]['random']    = empty($_POST["cimage_random"]) ? 0 : 1;
$content["image_list"]['limit']     = intval($_POST["cimage_limit"]);
$content["image_list"]['usetable']  = empty($_POST["cimage_usetable"]) ? 0 : 1;
