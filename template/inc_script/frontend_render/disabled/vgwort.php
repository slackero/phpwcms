<?php
/**
 * phpwcms
 *
 * VG Wort Pixel Tracking frontend render script
 *
 * Handles tracking pixel of VG Wort (Verwertungsgesellschaft WORT)
 * 1) Define tracking code: Fill in unique text ID in article keyword like: VGW:e2531725f43b35656065
 * 2) Put replacement tag {VGWort} inside template source code
 *
 * @author Oliver Georgi <og@phpwcms.org>
 * @copyright Copyright (c) 2002-2026, Oliver Georgi
 * @license http://opensource.org/licenses/GPL-2.0 GNU GPL-2
 *
 **/

// ----------------------------------------------------------------
// Obligate check for phpwcms constants
if (!defined('PHPWCMS_ROOT')) {
    die('You Cannot Access This Script Directly, Have a Nice Day.');
}
// ----------------------------------------------------------------

$vgwort_pixel = '';

if (strpos($content['all'], '{VGWort}') !== false && !empty($content['all_keywords']) && is_array($content['all_keywords'])) {
    foreach ($content['all_keywords'] as $vgwort) {
        if (strpos($vgwort, 'VGW:') === 0) {
            $vg_id = trim(substr($vgwort, 4));
            if ($vg_id !== '') {
                $vgwort_pixel = '<img src="https://ssl-vg00.met.vgwort.de/na/' . html_specialchars($vg_id) . '?timestamp=' . time() . '" width="1" height="1" alt="" class="vgwort-pixel" />';
                break;
            }
        }
    }
}

$content['all'] = str_replace('{VGWort}', $vgwort_pixel, $content['all']);

