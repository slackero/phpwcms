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

$phpwcms = array();
$root = rtrim(str_replace('\\', '/', realpath(dirname(__FILE__).'/../') ), '/').'/';
require_once $root.'/include/config/conf.inc.php';
require_once $root.'/include/inc_lib/default.inc.php';
require_once PHPWCMS_ROOT.'/include/inc_lib/general.inc.php';
require_once PHPWCMS_ROOT.'/include/inc_lib/imagick.convert.inc.php';

// get segments: cmsimage.php/WIDTH[[[[xHEIGHT]xCROP]xQUALITY]xGS]/[[HASH|ID].EXT]
// ...xGS will convert image to GrayScale
$request_uri = isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : $_SERVER['PHP_SELF'];

// strip out PHPSESSNAME=...
if(session_id() && session_name()) {
    // session expected at the end of REQUEST URI when added by PHP
    $session_name_pos = strpos($request_uri, session_name().'=');
    if($session_name_pos !== FALSE) {
        $request_uri = trim(trim(mb_substr($request_uri, 0, $session_name_pos), '&'), '?');
    }
}

if(strpos($request_uri, '/im/') !== false) {
    $data = explode('/im/', $request_uri, 2);
} elseif(strpos($request_uri, 'cmsimage.php?') === false) {
    $data = explode('cmsimage.php/', $request_uri, 2);
} else {
    $data = explode('cmsimage.php?', $request_uri, 2);
}

if(isset($data[1])) {

    $data = explode('/', $data[1]);

    // first check hashed data
    if(isset($data[1])) {

        $data[1]    = preg_replace('/[^a-fgijpn0-9\.]/i', '', $data[1]);
        $hash       = cut_ext($data[1]);
        $ext        = which_ext($data[1]);
        $value      = array();
        $svg        = 0;

        if($ext === '' && isset($data[2])) {
            $ext = which_ext($data[2]);
        }

        if(substr($data[0], 0, 7) === 'convert') {
            // get image convert function but limit to max of 5 chars
            $convert_function = substr(substr($data[0], 8), 0, 5);

            if(!empty($convert_function) && $hash && $ext && function_exists('phpwcms_convertimage_'.$convert_function)) {

                $source_image       = $hash.'.'.$ext;
                $target_image       = $hash.'-'.$convert_function.'.'.$ext;
                $convert_function   = 'phpwcms_convertimage_'.$convert_function;

                // deliver cached image first
                if(!is_file(PHPWCMS_THUMB.$target_image)) {

                    $result = $convert_function(PHPWCMS_THUMB.$source_image, PHPWCMS_THUMB.$target_image);

                    if(empty($result['error']) && !empty($result['image'])) {

                        $target_image = $result['image'];

                    } elseif(is_file(PHPWCMS_THUMB.$source_image)) {

                        $target_image = $source_image;

                    } else {

                        $target_image = '';
                    }

                }

                if($target_image) {

                    if(!empty($phpwcms['cmsimage_redirect'])) {
                        headerRedirect(PHPWCMS_URL.PHPWCMS_IMAGES.$target_image, 301);
                    }

                    $filename = empty($data[2]) ? '' : '; filename="'.rawurlencode($data[2]).'"';

                    header('Content-Type: ' . get_mimetype_by_extension($ext));
                    header('Content-Disposition: inline' . $filename);
                    @readfile(PHPWCMS_THUMB.$target_image);
                    exit;

                }

            }

            // uncached transparent GIF
            phpwcms_empty_gif();

        } else {
            $data[0] = preg_replace('/[^0-9xgsXGSctrlb\-]/', '', $data[0]);
        }

        // Check allowed cmsimage settings and use fallback/default if not matching
        if($ext !== 'svg' && !empty($phpwcms['cmsimage_settings']) && $data[0] && !in_array($data[0], $phpwcms['cmsimage_settings'])) {
            if(isset($phpwcms['cmsimage_settings']['default'])) {
                $data[0] = $phpwcms['cmsimage_settings']['default'];
            } else {
                reset($phpwcms['cmsimage_settings']);
                $data[0] = current($phpwcms['cmsimage_settings']);
            }
            // check if script should stop here with an empty GIF
            if(empty($data[0]) || $data[0] === 'empty') {
                phpwcms_empty_gif();
            }
        }

        if(is_intval($hash)) {

            $phpwcms['SESSION_START'] = true;
            require_once PHPWCMS_ROOT.'/include/inc_lib/helper.session.php';
            $file_public = empty($_SESSION["wcs_user_id"]) ? 'f_public=1' : '(f_public=1 OR f_uid='.intval($_SESSION["wcs_user_id"]).')';

            require_once(PHPWCMS_ROOT.'/include/inc_lib/dbcon.inc.php');

            $sql   = 'SELECT f_hash, f_ext, f_svg, f_image_width, f_image_height, f_name FROM '.DB_PREPEND.'phpwcms_file WHERE ';
            $sql  .= 'f_id='.intval($hash)." AND ";
            if(substr($phpwcms['image_library'], 0, 2) === 'gd') {
                $sql .= "f_ext IN ('jpg','jpeg','png','gif','bmp', 'svg', 'webp') AND ";
            }
            $sql  .= 'f_trash=0 AND f_aktiv=1 AND '.$file_public;
            $hash  = _dbQuery($sql);
            if(isset($hash[0]['f_hash'])) {
                $ext  = $hash[0]['f_ext'];
                $svg  = intval($hash[0]['f_svg']);
                $_w   = $hash[0]['f_image_width'];
                $_h   = $hash[0]['f_image_height'];
                $name = $hash[0]['f_name'];
                $hash = $hash[0]['f_hash']; // this overwrites $hash!!!
            } else {
                $hash = '';
                $ext  = '';
                $svg  = 0;
                $_w   = '';
                $_h   = '';
                $name = '';
            }

        } elseif(strlen($hash) === 32 && (!$ext || !is_file(PHPWCMS_ROOT.'/'.PHPWCMS_FILES.$hash.'.'.$ext))) {

            $phpwcms['SESSION_START'] = true;
            require_once PHPWCMS_ROOT.'/include/inc_lib/helper.session.php';
            $file_public = empty($_SESSION["wcs_user_id"]) ? 'f_public=1' : '(f_public=1 OR f_uid='.intval($_SESSION["wcs_user_id"]).')';

            require_once PHPWCMS_ROOT.'/include/inc_lib/dbcon.inc.php';

            $sql   = 'SELECT f_hash, f_ext, f_svg, f_image_width, f_image_height, f_name FROM '.DB_PREPEND.'phpwcms_file WHERE ';
            $sql  .= 'f_hash='._dbEscape($hash)." AND ";
            if(substr($phpwcms['image_library'], 0, 2) === 'gd') {
                $sql .= "f_ext IN ('jpg', 'jpeg', 'png', 'gif', 'bmp', 'svg', 'webp') AND ";
            }
            $sql  .= 'f_trash=0 AND f_aktiv=1 AND '.$file_public;
            $hash  = _dbQuery($sql);
            if(isset($hash[0]['f_hash'])) {
                $ext  = $hash[0]['f_ext'];
                $svg  = intval($hash[0]['f_svg']);
                $_w   = $hash[0]['f_image_width'];
                $_h   = $hash[0]['f_image_height'];
                $name = $hash[0]['f_name'];
                $hash = $hash[0]['f_hash']; // this overwrites $hash!!!
            } else {
                $hash = '';
                $ext  = '';
                $svg  = 0;
                $_w   = '';
                $_h   = '';
                $name = '';
            }
        }

        if(strlen($hash) === 32 && $ext) {

            $attribute  = explode('x', $data[0]);
            $width      = intval($attribute[0]);
            $height     = isset($attribute[1]) ? intval($attribute[1]) : 0;
            $crop       = isset($attribute[2]) ? $attribute[2] : 0;
            $crop_pos   = ''; // the old behavior center,center | cc
            $grid       = 0;
            if($crop) {
                $crop   = explode('-', $crop, 2);

                if(isset($crop[1]) && in_array($crop[1], array('tl', 'tc', 'tr', 'cl', 'cr', 'bl', 'bc', 'br'))) {
                    $crop_pos = $crop[1];
                }
                $crop   = intval($crop[0]);
                if($crop) {
                    $grid       = $crop > 1 ? $crop : 0;
                    $crop       = 1;
                } else {
                    $crop_pos   = '';
                    $crop       = 0;
                }
            }

            // quality
            if(isset($attribute[3]) && ($quality = intval($attribute[3])) ) {
                if($quality < 10 || $quality > 100) {
                    $quality = '';
                } else {
                    $value['quality'] = $quality;
                }
            } else {
                $quality = '';
            }

            if(isset($attribute[4]) && strtolower($attribute[4]) == 'gs') {
                $phpwcms['colorspace'] = 'GRAY';
            }

            $value["max_width"]     = $width ? $width : '';
            $value["max_height"]    = $height ? $height : '';
            $value['target_ext']    = $ext;
            $value['image_name']    = $hash . '.' . $ext;
            $value['thumb_name']    = md5($hash.$value["max_width"].$value["max_height"].$phpwcms['sharpen_level'].$crop.$crop_pos.$quality.$phpwcms['colorspace']);
            $value['crop_image']    = $crop;
            $value['crop_pos']      = $crop_pos;
            $value['is_file']       = is_file(PHPWCMS_ROOT.'/'.PHPWCMS_FILES.$value['image_name']);

            if($svg && $value['is_file']) {

                // calculate target dimensions
                $resize_factor = $_w / $_h;
                $svg_edit = true;
                $svg_preserveAspectRatio = '';

                if($value["max_height"] && $value["max_width"]) {

                    if($value['crop_image']) {

                        $svg_preserveAspectRatio = 'xMidYMid slice';

                    } else {

                        $resize_factor_x = $value["max_width"] / $_w;
                        $resize_factor_y = $value["max_height"] / $_h;

                        if ($resize_factor_x * $_h < $value["max_height"]) { // Resize the image based on width
                        	$value["max_height"] = round($resize_factor_x * $_h);
                        } else {
                        	$value["max_width"] = round($resize_factor_y * $_w);
                        }

                    }

                } elseif($value["max_height"] && !$value["max_width"]) {
                    $value["max_width"] = floor($value["max_height"] * $_w / $_h);
                    $value['crop_image'] = 0;
                } elseif($value["max_width"] && !$value["max_height"]) {
                    $value["max_height"] = floor($value["max_width"] * $_h / $_w);
                    $value['crop_image'] = 0;
                } elseif(!$value["max_width"] && !$value["max_height"]) {
                    $value["max_width"]  = $_w;
                    $value["max_height"] = $_h;
                    $value['crop_image'] = 0;
                    $svg_edit = false;
                }

                if($svg_edit) {

                    $doc = new DOMDocument();
                    $doc->load(PHPWCMS_ROOT.'/'.PHPWCMS_FILES.$value['image_name']);
                    $svg_tag = $doc->getElementsByTagName('svg')->item(0);
                    $svg_tag->setAttribute('width', round($value['max_width']) . 'px');
                    $svg_tag->setAttribute('height', round($value['max_height']) . 'px');
                    // Fix Affinity related SVG attribute error
                    $svg_viewBox = $svg_tag->getAttribute('viewBox');
                    if($svg_viewBox === '' && ($svg_viewbox = $svg_tag->getAttribute('viewbox')) !== '') {
                        $svg_tag->setAttribute('viewBox', $svg_viewbox);
                    }
                    if($svg_preserveAspectRatio) {
                        $svg_tag->setAttribute('preserveAspectRatio', $svg_preserveAspectRatio);
                    }
                    $svg = $doc->saveXML();
                    $svg_length = mb_strlen($svg, PHPWCMS_CHARSET);

                } else {

                    $svg = file_get_contents(PHPWCMS_ROOT.'/'.PHPWCMS_FILES.$value['image_name']);
                    $svg_length = filesize(PHPWCMS_ROOT.'/'.PHPWCMS_FILES.$value['image_name']);

                }

                if(empty($name)) {
                    $name = empty($data[2]) ? $value['image_name'] : $data[2];
                }

                header('Content-Type: image/svg+xml');
                header('Content-length: '.$svg_length);
                header('Content-Disposition: inline; filename="'.rawurlencode($name).'"');

                echo $svg;
                exit();

            }

            // Set width/height based on grid
            if($grid) {
                if(!$value["max_width"] || !$value["max_height"]) {
                    if($value['is_file'] && ($imgdata = @getimagesize(PHPWCMS_ROOT.'/'.PHPWCMS_FILES.$value['image_name']))) {

                        if($value["max_height"] && !$value["max_width"]) {
                            $resize_factor = $imgdata[1] / $value["max_height"];
                            $value["max_width"] = floor($imgdata[0] / $resize_factor);
                        }
                        if($value["max_width"] && !$value["max_height"]) {
                            $resize_factor = $imgdata[0] / $value["max_width"];
                            $value["max_height"] = floor($imgdata[1] / $resize_factor);
                        }
                        if(!$value["max_width"] && !$value["max_height"]) {
                            $value["max_width"]  = $imgdata[0];
                            $value["max_height"] = $imgdata[1];
                        }

                    } elseif(!$value["max_width"]) {
                        $value["max_width"] = $value["max_height"];
                    } elseif(!$value["max_height"]) {
                        $value["max_height"] = $value["max_width"];
                    }
                }
                $basis = floor($value["max_width"] / $grid);
                if(!$basis) {
                    $basis = 1;
                }
                $value["max_width"] = $basis * $grid;

                $basis = floor($value["max_height"] / $grid);
                if(!$basis) {
                    $basis = 1;
                }
                $value["max_height"] = $basis * $grid;
            }

            if (PHPWCMS_WEBP && !$svg) {
                $value['target_ext'] = 'webp';
            }

            $image = get_cached_image($value, false, false);

            if(!empty($image[0])) {

                // Redirect, the "old" way
                if(!empty($phpwcms['cmsimage_redirect'])) {
                    headerRedirect(PHPWCMS_URL.PHPWCMS_IMAGES.$image[0], 301);
                }

                if(empty($image['type'])) {
                    $image['type'] = get_mimetype_by_extension(which_ext($image[0]));
                }

                header('Content-Type: ' . $image['type']);
                header('Content-length: '.filesize(PHPWCMS_THUMB.$image[0]));
                header('Content-Disposition: inline; filename="'.rawurlencode($image[0]).'"');
                @readfile(PHPWCMS_THUMB.$image[0]);
                exit;
            }

        }

    }

}

// uncached transparent GIF
phpwcms_empty_gif();
