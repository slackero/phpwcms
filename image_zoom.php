<?php
/**
 * phpwcms
 *
 * @author Oliver Georgi <og@phpwcms.org>
 * @copyright Copyright (c) 2002-2026, Oliver Georgi
 * @license http://opensource.org/licenses/GPL-2.0 GNU GPL-2
 *
 **/

$phpwcms = [];
require_once __DIR__ . '/include/config/conf.inc.php';
require_once __DIR__ . '/include/inc_lib/default.inc.php';
require_once PHPWCMS_ROOT . '/include/inc_lib/general.inc.php';

$src = 'img/leer.gif';
$name = '';
$lang = '';

if (!empty($_GET['lang']) && is_string($_GET['lang'])) {
    $lang = clean_slweg($_GET['lang']);
}

if (!empty($_GET['show']) && is_string($_GET['show'])) {
    $raw_data = base64_decode($_GET['show'], true);
    $data = $raw_data ? json_decode($raw_data, true) : null;

    if (is_array($data)) {
        $src = clean_slweg($data['src'] ?? '');
        $is_valid_cdn = false;
        if (PHPWCMS_CDN_IMAGE_URL && str_starts_with($src, PHPWCMS_CDN_IMAGE_URL)) {
            $cdn_rel = substr($src, strlen(PHPWCMS_CDN_IMAGE_URL));
            if (strspn($cdn_rel, '/\\.') === 0 && !str_contains($cdn_rel, '..')) {
                $is_valid_cdn = true;
            }
        }
        if (!$is_valid_cdn) {
            $src_schema = parse_url($src);
            if (!empty($src_schema['scheme'])) {
                $src = 'img/leer.gif';
            }
            // keep the image source a relative path contained under the install dir
            if (strspn($src, '/\\.') !== 0 || str_contains($src, '..')) {
                $src = 'img/leer.gif';
            }
        }
        $name = is_scalar($data['name'] ?? null) ? clean_plain_utf8((string)$data['name'], 'utf-8') : '';
        if (PHPWCMS_CHARSET !== 'utf-8') {
            $name = makeCharsetConversion($name, 'utf-8', PHPWCMS_CHARSET);
        }
        if ($lang === '' && !empty($data['lang']) && is_string($data['lang'])) {
            $lang = clean_slweg($data['lang']);
        }
    }
}

$lang = strtolower(preg_replace('/[^a-z0-9_-]/i', '', $lang));
if ($lang === '') {
    $lang = $phpwcms['DOCTYPE_LANG'];
}

?><!DOCTYPE html>
<html lang="<?php echo html($lang); ?>"<?php echo get_html_dir_attribute($lang); ?>>
<head>
    <title><?php echo html($name); ?></title>
    <meta charset="<?php echo PHPWCMS_CHARSET ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>
        *,
        *::before,
        *::after {
            box-sizing: border-box;
        }

        html,
        body {
            margin: 0;
            padding: 0;
            width: 100%;
            height: 100%;
            min-height: 100vh;
            background-color: #1a1a1a;
            color: #ffffff;
            color-scheme: dark;
        }

        body {
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
        }

        a {
            display: flex;
            align-items: center;
            justify-content: center;
            text-decoration: none;
            cursor: pointer;
            max-width: 100%;
            max-height: 100%;
        }

        img {
            display: block;
            max-width: 100vw;
            max-height: 100vh;
            width: auto;
            height: auto;
            border: 0;
            object-fit: contain;
            user-select: none;
            -webkit-user-drag: none;
        }
    </style>
    <script src="<?php echo TEMPLATE_PATH; ?>inc_js/imagezoom.js"></script>
</head>
<body><a href="#" title="Close PopUp" onclick="window.close();return false;"><img src="<?php echo html($src); ?>" alt="<?php echo html($name); ?>" /></a></body>
</html>
