<?php
/**
 * phpwcms
 *
 * @author Oliver Georgi <og@phpwcms.org>
 * @copyright Copyright (c) 2002-2026, Oliver Georgi
 * @license http://opensource.org/licenses/GPL-2.0 GNU GPL-2
 *
 **/

$phpwcms = array();
require_once __DIR__ . '/include/config/conf.inc.php';
require_once __DIR__ . '/include/inc_lib/default.inc.php';

$src = "img/leer.gif";
$name = '';
$attr = '';

if(!empty($_GET["show"]) && ($data = json_decode(base64_decode($_GET["show"]), true))) {
    $src = strip_tags($data['src']);
    $is_valid_cdn = false;
    if (PHPWCMS_CDN_IMAGE_URL && str_starts_with($src, PHPWCMS_CDN_IMAGE_URL)) {
        $cdn_rel = substr($src, strlen(PHPWCMS_CDN_IMAGE_URL));
        if (strspn($cdn_rel, '/\\.') === 0 && strpos($cdn_rel, '..') === false) {
            $is_valid_cdn = true;
        }
    }
    if (!$is_valid_cdn) {
        $src_schema = parse_url($src);
        if (!empty($src_schema['scheme'])) {
            $src = "img/leer.gif";
        }
        // keep the image source a relative path contained under the install dir
        if (strspn($src, '/\\.') !== 0 || strpos($src, '..') !== false) {
            $src = "img/leer.gif";
        }
    }
    $name = is_scalar($data['name']) ? strip_tags((string)$data['name']) : '';
}

?><!DOCTYPE html>
<html <?php echo get_backend_html_tag_attributes($phpwcms['DOCTYPE_LANG'], false); ?>>
<head>
    <title><?php echo html($name); ?></title>
    <meta charset="<?php echo PHPWCMS_CHARSET ?>">
    <script src="<?php echo TEMPLATE_PATH; ?>inc_js/imagezoom.js"></script>
    <link href="<?php echo TEMPLATE_PATH; ?>inc_css/dialog/popup.image.css" rel="stylesheet">
</head>
<body><a href="#" title="Close PopUp" onclick="window.close();return false;"><img src="<?php echo html($src); ?>" alt="<?php echo html($name); ?>" /></a></body>
</html>
