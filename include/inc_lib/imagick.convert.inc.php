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

/**
 * Load external GD image handling class
 */
require_once PHPWCMS_ROOT . '/include/inc_lib/helper.image.php';

/**
 * Deprecated function, used for 3rd party fallback
 *
 * @param array $config
 * @return array
 */
function imagick_converting($config=array()) {
    return image_manipulate($config);
}

/**
 * Resize, crop and other image manipulation
 *
 * @param array $config
 * @return array
 */
function image_manipulate($config=array()) {

    global $phpwcms;

    // Merge config values with default
    $config = array_merge(
        array(
            'max_width' => $phpwcms['img_list_width'],
            'max_height' => $phpwcms['img_list_height'],
            'error' => '',
            'image_name' => '',
            'thumb_name' => '',
            'target_ext' => PHPWCMS_WEBP ? 'webp' : 'jpg',
            'image_dir' => PHPWCMS_ROOT . '/' . PHPWCMS_FILES,
            'thumb_dir' => PHPWCMS_THUMB,
            'quality' => PHPWCMS_QUALITY,
            'sharpen_level' => 0,
            'density' => 72,
            'add_command' => '',
            'crop_image' => false,
            'crop_pos' => '',
            'master_dim' => 'auto'
        ),
        $config
    );

    // Test width and height and set correct dimensions
    if(!intval($config["max_width"]) && !intval($config["max_height"])) {
        // Should not happen, but better have a fallback
        $config["max_width"] = $phpwcms["img_list_width"];
        $config["max_height"] = $phpwcms["img_list_height"];
        $config['master_dim'] = 'auto';
    } elseif(!intval($config["max_width"])) {
        // No width given, recalculate final image size based on height
        $config["max_width"] = $phpwcms["img_prev_width"];
        $config['crop_image'] = false;
        $config['master_dim'] = 'height';
    } elseif(!intval($config["max_height"])) {
        // No height given, recalculate final image size based on width
        $config["max_height"] = $phpwcms["img_prev_height"];
        $config['crop_image'] = false;
        $config['master_dim'] = 'width';
    }

    // Check if source image is accessible
    // otherwise use placeholder image "filestorage/image_placeholder.png"
    if(!is_file($config["image_dir"] . $config["image_name"])) {
        $config["image_name"] = 'image_placeholder.png';
        $config["thumb_name"] = 'temp_' .$config["thumb_name"];
    }

    // Doubled config setting but especially for Image manipulation class
    $image_config = array(
        'image_library' => $phpwcms['image_library'],
        'library_path' => $phpwcms['library_path'],
        'source_image' => $config["image_dir"] . $config["image_name"],
        'new_image' => $config["thumb_dir"] . $config["thumb_name"] . '.' . $config["target_ext"],
        'maintain_ratio' => true,
        'width' => $config['max_width'],
        'height' => $config['max_height'],
        'master_dim' => $config['master_dim'],
        'sharpen' => $config['sharpen_level'],
        'quality' => $config['quality'],
        'create_thumb' => false,
        'target_ext' => $config["target_ext"],
        'colorspace' => $phpwcms['colorspace'],
        'animated_gif' => $config["animated_gif"]
    );

    $IMG = new Phpwcms_Image_lib($image_config);

    // try to handle limited PHP memory
    if(empty($GLOBALS['phpwcms']['gd_memcheck_off']) && ($phpwcms['image_library'] === 'gd2' || $phpwcms['image_library'] === 'gd')) {

        $php_memory = getBytes(@ini_get('memory_limit'));
        $img_memory = getRealImageSize($IMG->image_current_vals);

        // do memory checks only when PHP's memory limit
        // and "real" image size is known
        if($php_memory && $img_memory) {

            // test if we have enough PHP memory for this image and test to set it up
            if($php_memory / 3 < $img_memory) {
                @ini_set('memory_limit', $img_memory * 3);
            }

            $php_memory = getBytes(@ini_get('memory_limit'));

            // still not enough, use fallback memory warning image
            if($php_memory / 3 < $img_memory) {
                $config["image_name"] = 'image_memoryinfo.png';
                $config["thumb_name"] = 'mem_' . $config["thumb_name"];

                $image_config['source_image'] = $config["image_dir"] . $config["image_name"];
                $image_config['new_image'] = $config["thumb_dir"] . $config["thumb_name"] . '.' . $config["target_ext"];

                $IMG->initialize($image_config);
            }
        }
    }

    // do not resize if image is smaller than target sizes
    if(!$config['crop_image'] && substr($phpwcms['image_library'], 0, 2) === 'gd' && !empty($IMG->orig_width) && !empty($IMG->orig_height) && $image_config['width'] > $IMG->orig_width && $image_config['height'] > $IMG->orig_height) {
        $config['max_width'] = $IMG->orig_width;
        $config['max_height'] = $IMG->orig_height;
        $image_config['width'] = $IMG->orig_width;
        $image_config['height'] = $IMG->orig_height;
        $IMG->width = $IMG->orig_width;
        $IMG->height = $IMG->orig_height;
    }

    if($config['crop_image']) {

        $image_config = set_cropped_imagesize($image_config, $IMG->orig_width, $IMG->orig_height, $config['crop_pos']);

        if( $image_config['do_cropping'] ) {

            // first resize width recalculated height/width
            $IMG->width = $image_config['resize_width'];
            $IMG->height = $image_config['resize_height'];
            $IMG->quality = 100;
            $IMG->resize();

            $image_config['sharpen'] = 0;
            $image_config['maintain_ratio'] = false;
            $image_config['create_thumb'] = false;
            $image_config['source_image'] = $image_config['new_image'];

            $IMG->initialize( $image_config );
            $IMG->crop();

        } else {

            $IMG->resize();

        }

    } else {

        $IMG->resize();

    }

    $config["thumb_name"] = $IMG->dest_image;
    $config['error'] = $IMG->display_errors('<li>', '</li>', '<ul class="error">', '</ul>');

    return $config;
}

/**
 * Build thumbnail image name
 *
 * @param array $val
 * @param bool $db_track
 * @param bool $return_all_imageinfo
 * @return array|bool
 */
function get_cached_image($val=array(), $db_track=true, $return_all_imageinfo=true) {

    $val = array_merge(
        array(
            'max_width' => $GLOBALS['phpwcms']['img_list_width'],
            'max_height' => $GLOBALS['phpwcms']['img_list_height'],
            'image_dir' => PHPWCMS_ROOT . '/' . PHPWCMS_FILES,
            'thumb_dir' => PHPWCMS_ROOT . '/' . PHPWCMS_IMAGES,
            'quality' => PHPWCMS_QUALITY,
            'sharpen_level' => $GLOBALS['phpwcms']['sharpen_level'],
            'crop_image'  => false,
            'crop_pos' => '',
            'img_filename' => '',
            'animated_gif' => false
        ),
        $val
    );

    $imgCache = false; //do not insert file information in db image cache
    $thumb_image_info = array(
        0 => false,
        'svg' => false
    );

    if($val['target_ext'] === 'svg' && is_file($val['image_dir'].$val['image_name'])) {

        $thumb_image_info['svg'] = true;
        $thumb_image_info[0] = $val['image_name'];
        $thumb_image_info[1] = $val['max_width'];
        $thumb_image_info[2] = $val['max_height'];
        if ($val['max_width']) {
            $thumb_image_info[3] = 'width="' . $val['max_width'] . '"';
        } elseif ($val['max_height']) {
            $thumb_image_info[3] = 'height="' . $val['max_height'] . '"';
        } else {
            $thumb_image_info[3] = '';
        }
        $thumb_image_info['type'] = 'image/svg+xml';
        $thumb_image_info['src'] = PHPWCMS_RESIZE_IMAGE . '/' . $val['max_width'] . 'x' . $val['max_height'];

        $thumb_spec_info = '';
        if($val['crop_image']) {
            $thumb_image_info['src'] .= 'x1';
            $thumb_spec_info .= 'c' . $val['crop_image'];
            if ($val['crop_pos']) {
                $thumb_spec_info .= $val['crop_pos'];
            }
        }
        $thumb_image_info['src'] .= '/' . $val['image_name'];

        if(!empty($val['img_filename'])) {
            $thumb_image_info['src'] .= '/' . rawurlencode($val['img_filename']);
            $thumb_filename_basis = cut_ext($val['img_filename']);

            $thumb_image_info[0] = substr($thumb_filename_basis, 0, 230) . '_' . $val['max_width'] . 'x' . $val['max_height'];
            if ($thumb_spec_info) {
                $thumb_image_info[0] .= '-' . $thumb_spec_info;
            }
            $thumb_image_info[0] .= '.svg';
        }

        if (!is_file($val['thumb_dir'].$thumb_image_info[0])) {
            copy ($val['image_dir'].$val['image_name'], $val['thumb_dir'].$thumb_image_info[0]);
        }

        return $thumb_image_info;

    }

    // Check if animated GIF
    if ($val['target_ext'] === 'gif' && in_array($GLOBALS['phpwcms']['image_library'], array('imagemagick', 'gm', 'graphicsmagick')) && is_animated_gif($val['image_dir'].$val['image_name'])) {
        $val['animated_gif'] = true; // Try to preserve animated GIF
    } elseif (PHPWCMS_WEBP) { // Test against WebP support
        $val['target_ext'] = 'webp';
    } elseif ($val['target_ext'] === 'webp') {
        $val['target_ext'] = 'jpg';
    }

    // Try to catch file name from database
    if(empty($val['img_filename']) && PHPWCMS_PRESERVE_IMAGENAME) {

        if(!defined('PHPWCMS_DB_VERSION')) {
            require_once PHPWCMS_ROOT.'/include/inc_lib/dbcon.inc.php';
        }

        $hash = cut_ext($val['image_name']);

        $file_public = empty($_SESSION["wcs_user_id"]) ? 'f_public=1' : '(f_public=1 OR f_uid='.intval($_SESSION["wcs_user_id"]).')';

        $sql  = 'SELECT f_hash, f_ext, f_image_width, f_image_height, f_name FROM ' . DB_PREPEND . 'phpwcms_file WHERE ';
        $sql .= 'f_kid=1 AND f_hash=' . _dbEscape($hash)." AND ";
        $sql .= 'f_trash=0 AND f_aktiv=1 AND '.$file_public;
        if(substr($GLOBALS['phpwcms']['image_library'], 0, 2) === 'gd') {
            $sql .= " AND f_ext IN ('jpg', 'jpeg', 'png', 'gif', 'bmp', 'webp')";
        }
        $imagedetail = _dbQuery($sql);

        if(isset($imagedetail[0]['f_hash'])) {

            $val['img_filename'] = $imagedetail[0]['f_name'];
            $thumb_filename_basis = cut_ext($imagedetail[0]['f_name']);

        }

    }

    if(!empty($val['img_filename'])) {

        $thumb_spec_info = '';
        if ($val['crop_image']) {
            $thumb_spec_info .= 'c' . $val['crop_image'];
            if ($val['crop_pos']) {
                $thumb_spec_info .= $val['crop_pos'];
            }
        }
        if ($val['sharpen_level']) {
            $thumb_spec_info .= 's' . $val['sharpen_level'];
        }
        if ($val['target_ext'] === 'jpg' || $val['target_ext'] === 'webp') {
            $thumb_spec_info .= 'q' . $val['quality'];
        }
        if (!isset($thumb_filename_basis)) {
            $thumb_filename_basis = cut_ext($val['img_filename']);
        }

        $val['thumb_name'] = substr($thumb_filename_basis, 0, 230) . '_' . $val['max_width'] . 'x' . $val['max_height'];
        if ($thumb_spec_info) {
            $val['thumb_name'] .= '-' . $thumb_spec_info;
        }
    }
    $thumb_check = $val['thumb_dir'] . $val['thumb_name'];

    if (PHPWCMS_WEBP) {
        if (is_file($thumb_check . '.webp')) {
            $thumb_image_info[0] = $val['thumb_name'].'.webp';
            $thumb_image_info['type'] = 'image/webp';
        } elseif ($val["target_ext"] = is_ext_true($val["target_ext"])) {
            $create_preview = image_manipulate($val);
            if (is_file($val['thumb_dir'].$create_preview["thumb_name"])) {
                $thumb_image_info[0] = $create_preview["thumb_name"];
                $imgCache = true; // insert/update information in db image cache
            }
        }
    } elseif (is_file($thumb_check.'.jpg')) {
        $thumb_image_info[0] = $val['thumb_name'].'.jpg';
        $thumb_image_info['type'] = 'image/jpeg';
    } elseif (is_file($thumb_check.'.png')) {
        $thumb_image_info[0] = $val['thumb_name'].'.png';
        $thumb_image_info['type'] = 'image/png';
    } elseif (is_file($thumb_check.'.gif')) {
        $thumb_image_info[0] = $val['thumb_name'].'.gif';
        $thumb_image_info['type'] = 'image/gif';
        // check if current file's extension is handable by ImageMagick or GD
    } elseif ($val["target_ext"] = is_ext_true($val["target_ext"])) {
        $create_preview = image_manipulate($val);
        if (is_file($val['thumb_dir'].$create_preview["thumb_name"])) {
            $thumb_image_info[0] = $create_preview["thumb_name"];
            $imgCache = true; // insert/update information in db image cache
        }
    }

    if($thumb_image_info[0] !== false) {
        if($return_all_imageinfo === false) {
            return $thumb_image_info;
        }
        $thumb_info = @getimagesize($val['thumb_dir'] . $thumb_image_info[0]);
        if(is_array($thumb_info)) {
            $thumb_image_info[1] = $thumb_info[0]; // width
            $thumb_image_info[2] = $thumb_info[1]; // height
            $thumb_image_info[3] = $thumb_info[3]; // HTML width & height attribute
            $thumb_image_info['src'] = PHPWCMS_IMAGES . $thumb_image_info[0];
            $thumb_image_info['type'] = $thumb_info['mime'];
        } else {
            // if wrong - no result, return false
            return false;
        }
    } else {
        // if wrong - no result, return false
        return false;
    }

    // Return cached thumbnail image info
    // $thumb_image_info[0] = Name,
    // $thumb_image_info[1] = width,
    // $thumb_image_info[2] = height,
    // $thumb_image_info[3] = HTML width & height attribute
    return $thumb_image_info;
}

/**
 * Set cropped image size
 *
 * @param $config
 * @param int $orig_width
 * @param int $orig_height
 * @param string $crop_pos
 * @return mixed
 */
function set_cropped_imagesize($config, $orig_width=0, $orig_height=0, $crop_pos='') {

    $config['resize_width'] = $config['width'];
    $config['resize_height'] = $config['height'];
    $config['x_axis'] = 0;
    $config['y_axis'] = 0;
    $config['do_cropping'] = false;

    if($orig_width && $orig_height) {

        // fallback if $orig_width < $config['width'] or $orig_height < $config['height']
        $resize_factor = 1;
        if($orig_width < $config['width']) {
            $resize_factor = $config['width'] / $orig_width;
        }
        if($orig_height < $config['height']) {
            $resize_factor = max($resize_factor, ($config['height'] / $orig_height));
        }
        if($resize_factor > 1) {
            $orig_width = ceil($orig_width * $resize_factor);
            $orig_height = ceil($orig_height * $resize_factor);
        }

        // compare original image sizes against cropped image size
        $ratio_width = $orig_width / $config['width'];
        $ratio_height = $orig_height / $config['height'];

        // check if cropping is necessary
        if($ratio_width !== $ratio_height) {

            $config['do_cropping'] = true;

            // source image dimensions are both larger than target
            if($ratio_width >= 1 && $ratio_height >= 1) {

                if($ratio_width <= $ratio_height) {
                    $config['resize_height'] = ceil($orig_height / $ratio_width);
                    $config['y_axis'] = get_cropped_pos('y', $crop_pos, $config['resize_height'], $config['height']);
                } else {
                    $config['resize_width'] = ceil($orig_width / $ratio_height);
                    $config['x_axis'] = get_cropped_pos('x', $crop_pos, $config['resize_width'], $config['width']);
                }

                // source image dimensions width and/or height is smaller than target
            } elseif($ratio_width <= $ratio_height) {
                $config['resize_width'] = ceil($orig_width + ($orig_width * (1 - $ratio_height)));
                $config['x_axis'] = get_cropped_pos('x', $crop_pos, $config['resize_width'], $config['width']);
            } else {
                $config['resize_height'] = ceil($orig_height + ($orig_height * (1 - $ratio_width)));
                $config['y_axis'] = get_cropped_pos('y', $crop_pos, $config['resize_height'], $config['height']);
            }
        }
    }

    return $config;
}

/**
 * Set cropped X/Y axis values
 *
 * @param $axis
 * @param $pos
 * @param $default_size
 * @param $crop_value
 * @return float|int
 */
function get_cropped_pos($axis, $pos, $default_size, $crop_value) {

    // tl, tc, tr, cl, cc, cr , bl, bc, br
    $distance = $default_size - $crop_value;
    $pos = $axis === 'y' ? substr($pos, 0, 1) : substr($pos, -1);

    if($pos === 't' || $pos === 'l') {
        return 0;
    } elseif($pos === 'b' || $pos === 'r') {
        return $distance;
    } else {
        return round($distance / 2);
    }
}

/**
 * Return transparent 1x1px GIF and stop rendering
 *
 * @param bool $cache
 */
function phpwcms_empty_gif($cache=false) {

    if(!$cache) {
        headerAvoidPageCaching();
    }

    header('Content-Type: image/gif');
    echo base64_decode('R0lGODlhAQABAIAAAP///wAAACH5BAEAAAAALAAAAAABAAEAAAICRAEAOw==');
    exit();
}

/**
 * Convert existing temporary image to grayscale
 *
 * @param string $source_img
 * @param string $target_img
 * @param array $config
 * @return array
 */
function phpwcms_convertimage_gs($source_img='', $target_img='', $config=array()) {

    global $phpwcms;

    $config = array_merge(
        array(
            'image_library' => $phpwcms['image_library'],
            'library_path' => $phpwcms['library_path'],
            'source_image' => $source_img,
            'new_image' => $target_img,
            'sharpen' => 0,
            'quality' => PHPWCMS_QUALITY,
            'create_thumb' => false,
            'colorspace' => 'GRAY',
            'density' => 72
        ),
        $config
    );

    $IMG = new Phpwcms_Image_lib($config);
    $IMG->resize();

    return array(
        'image' => $IMG->dest_image,
        'error' => $IMG->display_errors('<li>', '</li>', '<ul class="error">', '</ul>')
    );
}

/**
 * Get SVG image size
 *
 * @param $svg_file
 * @return array|null
 */
function phpwcms_svg_getimagesize($svg_file) {

    if(empty($svg_file) || !is_file($svg_file) || !($svg = @simplexml_load_file($svg_file))) {
        return null;
    }

    $svg_attributes = $svg->attributes();

    return array(
        'width' => empty($svg_attributes->width) ? 0 : (string) $svg_attributes->width,
        'height' => empty($svg_attributes->height) ? 0 : (string) $svg_attributes->height
    );
}

function is_animated_gif($file) {

    if (is_string($file) && is_file($file) && $fp = fopen($file, 'rb')) {

        if (fread($fp, 3) !== 'GIF') {
            fclose($fp);

            return false;
        }

        $frames = 0;

        while (!feof($fp) && $frames < 2) {
            if (fread($fp, 1) === "\x00") {
                /* Some of the animated GIFs do not contain graphic control extension (starts with 21 f9) */
                $x21x2C = fread($fp, 1);
                if ($x21x2C === "\x21" || $x21x2C === "\x2C" || fread($fp, 2) === "\x21\xf9") {
                    $frames++;
                }
            }
        }

        //an animated gif contains multiple "frames", with each frame having a
        //header made up of:
        // * a static 4-byte sequence (\x00\x21\xF9\x04)
        // * 4 variable bytes
        // * a static 2-byte sequence (\x00\x2C) (some variants may use \x00\x21 ?)

        // We read through the file til we reach the end of the file, or we've found
        // at least 2 frame headers
        /*
        $chunk = false;
        while(!feof($fp) && $frames < 2) {
            //add the last 20 characters from the previous string, to make sure the searched pattern is not split.
            $chunk = ($chunk ? substr($chunk, -20) : '') . fread($fp, 1024 * 100); //read 100kb at a time
            if (preg_match_all('#\x00\x21\xF9\x04.{4}\x00(\x2C|\x21)#s', $chunk, $matches)) {
                $frames++;
            }
        }
        */

        fclose($fp);

        return $frames > 1;
    }

    return false;
}