<?php
/**
 * phpwcms content management system
 *
 * @author Oliver Georgi <og@phpwcms.org>
 * @copyright Copyright (c) 2002-2025, Oliver Georgi
 * @license http://opensource.org/licenses/GPL-2.0 GNU GPL-2
 * @link http://www.phpwcms.org
 *
 **/

// <img src="image_resized.php?format=jpg&w=100&h=200&q=85&imgfile=test.jpg" alt="" border="0">

$img_target = isset($_GET['format']) ? strtolower(trim($_GET['format'])) : 'jpg';
$img_file = isset($_GET['imgfile']) ? trim(urldecode($_GET['imgfile'])) : 'img/leer.gif';
$img_width = isset($_GET['w']) ? (int)$_GET['w'] : 0;
$img_height = isset($_GET['h']) ? (int)$_GET['h'] : 0;
$img_quality = isset($_GET['q']) && (int)$_GET['q'] <= 100 && (int)$_GET['q'] ? (int)$_GET['q'] : 75;
$result = false;

// Ensure no protocol handlers (http://…) or something like C:\ or C:/ or ./ or ../ is part of the file name
if (
    $img_file
    &&
    (
        $img_file[0] === '.'
        ||
        $img_file[0] === '/'
        ||
        $img_file[0] === '\\'
        ||
        strpos($img_file, ':/') !== false
        ||
        strpos($img_file, './') !== false
        ||
        strpos($img_file, ':\\') !== false
        ||
        strpos($img_file, '.\\') !== false
    )
) {
    $img_file = '';
} else {
    // Absolute path only related to the current directory
    $img_file = __DIR__ . '/' . $img_file;
}

switch ($img_target) {
    case 'png':
        $img_mimetype = 'image/png';
        $img_target = 'jpg';
        break;

    case 'gif':
        if (function_exists('imagegif')) {
            $img_mimetype = 'image/gif';
            $img_target = 'gif';
        } else {
            $img_target = 'png';
            $img_mimetype = 'image/png';
        }
        break;

    case 'webp':
        $img_mimetype = 'image/webp';
        $img_target = 'webp';
        break;

    case 'jpeg':
    case 'jpg':
    default:
        $img_mimetype = 'image/jpeg';
        $img_target = 'jpg';
}

if ($img_file !== '' && is_readable($img_file) && $img_info = getimagesize($img_file)) {

    if (!$img_width || $img_width >= $img_info[0]) {
        $percent_width = 1;
    } else {
        $percent_width = $img_width / $img_info[0];
    }

    if (!$img_height || $img_height >= $img_info[1]) {
        $percent_height = 1;
    } else {
        $percent_height = $img_height / $img_info[1];
    }

    if ($percent_height < $percent_width) {
        $percent = $percent_height;
    } elseif ($percent_height > $percent_width) {
        $percent = $percent_width;
    } else {
        $percent = $percent_width;
    }

    $img_width = $img_info[0] * $percent;
    $img_height = $img_info[1] * $percent;

    switch ($img_target) {
        case 'jpg':
        case 'png':
        case 'webp':
            $new_img = imagecreatetruecolor($img_width, $img_height);
            break;

        case 'gif':
            $new_img = imagecreate($img_width, $img_height);
            break;
    }

    switch ($img_info[2]) {
        case IMAGETYPE_GIF: // GIF
            $img_source = imagecreatefromgif($img_file);
            break;

        case IMAGETYPE_JPEG: // JPG
            $img_source = imagecreatefromjpeg($img_file);
            break;

        case IMAGETYPE_PNG: // PNG
            $img_source = imagecreatefrompng($img_file);
            break;

        case IMAGETYPE_WEBP: // WEBP
            $img_source = imagecreatefromwebp($img_file);
            break;
    }

    $result = imagecopyresized($new_img, $img_source, 0, 0, 0, 0, $img_width, $img_height, $img_info[0], $img_info[1]);

    if ($result) {
        header('Content-type: ' . $img_mimetype);

        switch ($img_target) {
            case 'jpg':
                $result = imagejpeg($new_img, NULL, $img_quality);
                break;
            case 'webp':
                $result = imagewebp($new_img, NULL, $img_quality);
                break;
            case 'png':
                $result = imagepng($new_img, NULL, 9);
                break;
            case 'gif':
                $result = imagegif($new_img);
                break;
        }
    }

    imagedestroy($new_img);
    imagedestroy($img_source);
}

// Error / no image
if (!$result) {
    header('Content-type: image/png');
    $new_img = imagecreatetruecolor(75, 20);
    $text_color = imagecolorallocate($new_img, 255, 255, 255);
    imagestring($new_img, 1, 5, 5, 'Image Error', $text_color);
    imagepng($new_img, NULL, 9);
    imagedestroy($new_img);
}
