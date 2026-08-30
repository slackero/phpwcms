<?php
/**
 * phpwcms
 *
 * @author Oliver Georgi <og@phpwcms.org>
 * @copyright Copyright (c) 2002-2026, Oliver Georgi
 * @license http://opensource.org/licenses/GPL-2.0 GNU GPL-2
 *
 **/


/**
 * Initialize Slimbox CSS and JavaScript for jQuery
 */
function initGlightbox() {
    initJSLib();
    $GLOBALS['block']['custom_htmlhead']['glightbox.css'] = '  <link href="'.TEMPLATE_PATH.'lib/glightbox/glightbox.min.css" rel="stylesheet" type="text/css" media="screen" />';
    $GLOBALS['block']['custom_htmlhead']['glightbox.js']  = getJavaScriptSourceLink(TEMPLATE_PATH.'lib/glightbox/glightbox.min.js');
    if (empty($GLOBALS['block']['custom_htmlhead']['glightbox.init'])) {
        $options = array('selector' => 'a[rel^="lightbox"]');
        if (!empty($GLOBALS['phpwcms']['glightbox_options']) && is_array($GLOBALS['phpwcms']['glightbox_options'])) {
            $options = array_merge($options, $GLOBALS['phpwcms']['glightbox_options']);
        }
        $overlayColor   = isset($options['overlayColor']) ? trim($options['overlayColor']) : null;
        $overlayOpacity = isset($options['overlayOpacity']) ? (float)$options['overlayOpacity'] : null;
        unset($options['overlayColor'], $options['overlayOpacity']);

        if ($overlayColor !== null || $overlayOpacity !== null) {
            $overlayColor   = $overlayColor ?: '#000000';
            $overlayOpacity = $overlayOpacity ?? 0.65;
            $cssColor = $overlayColor;
            if (str_starts_with($overlayColor, '#')) {
                $hex = ltrim($overlayColor, '#');
                if (strlen($hex) === 3) {
                    $r = hexdec(str_repeat(substr($hex, 0, 1), 2));
                    $g = hexdec(str_repeat(substr($hex, 1, 1), 2));
                    $b = hexdec(str_repeat(substr($hex, 2, 1), 2));
                } else {
                    $r = hexdec(substr($hex, 0, 2));
                    $g = hexdec(substr($hex, 2, 2));
                    $b = hexdec(substr($hex, 4, 2));
                }
                $cssColor = 'rgba(' . $r . ', ' . $g . ', ' . $b . ', ' . $overlayOpacity . ')';
            }
            $GLOBALS['block']['custom_htmlhead']['glightbox.overlay.css'] = '  <style>.goverlay{background: ' . $cssColor . ' !important;}</style>';
        }
        $jsonOptions = json_encode($options, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
        $GLOBALS['block']['custom_htmlhead']['glightbox.init'] = '  <script'.SCRIPT_ATTRIBUTE_TYPE.'>' . LF . SCRIPT_CDATA_START . LF . '  document.addEventListener("DOMContentLoaded", function() { if (typeof GLightbox === "function") { GLightbox(' . $jsonOptions . '); } });' . LF . SCRIPT_CDATA_END . LF . '  </script>';
    }
}

/**
 * Backward compatibility alias for initGlightbox
 */
function initSlimbox() {
    initGlightbox();
}

/**
 * Initialize Frontend Edit DomReady JavaScript
 */
function init_frontend_edit_js() {

    $GLOBALS['block']['custom_htmlhead']['frontend_edit.js'] = '  <script' . SCRIPT_ATTRIBUTE_TYPE . '>' . LF .
        '  document.addEventListener("DOMContentLoaded", function() {' . LF .
        '    var feToggle = document.getElementById("fe-link");' . LF .
        '    var feLinks = document.querySelectorAll("a.fe-link");' . LF .
        '    feLinks.forEach(function(el) { el.style.display = "none"; });' . LF .
        '    if (feToggle) {' . LF .
        '      feToggle.addEventListener("click", function() {' . LF .
        '        var isEnabled = feToggle.classList.toggle("enabled");' . LF .
        '        feToggle.classList.toggle("disabled", !isEnabled);' . LF .
        '        feLinks.forEach(function(el) { el.style.display = isEnabled ? "inline-flex" : "none"; });' . LF .
        '      });' . LF .
        '    }' . LF .
        '  });' . LF .
        '  </script>';

}

/**
 * Create JavaScript Domready Section
 */
function jsOnDomReady($js='', $return=false, $prefix='  ') {

    if($js) {

        initJSLib();

        $_js  = $prefix . '<script'.SCRIPT_ATTRIBUTE_TYPE.'>'.LF.SCRIPT_CDATA_START.LF;
        $_js .= '    jQuery(function() {' . LF . $js . LF . '    });';
        $_js .= LF.SCRIPT_CDATA_END.LF.$prefix.'</script>';

        if($return) {
            return $_js;
        }

        $GLOBALS['block']['custom_htmlhead'][] = $_js;
    }

    return true;
}

/**
 * Create JavaScript UnLoad Section
 */
function jsOnUnLoad($js='', $return=false, $prefix='  ') {

    if($js) {

        initJSLib();

        $_js  = $prefix . '<script'.SCRIPT_ATTRIBUTE_TYPE.'>'.LF.SCRIPT_CDATA_START.LF;
        $_js .= '    jQuery(window).on(\'unload\', function() {' . LF . $js . LF . '    });';
        $_js .= LF.SCRIPT_CDATA_END.LF.$prefix.'</script>';

        if($return) {
            return $_js;
        }

        $GLOBALS['block']['custom_htmlhead'][] = $_js;
    }

    return true;
}

/**
 * Simple jQuery Plugin Loader
 */
function initJSPlugin($plugin='') {
    $plugin = 'jquery.'.$plugin.'.js';
    if(empty($GLOBALS['block']['custom_htmlhead'][$plugin])) {
        initJSLib();
        $GLOBALS['block']['custom_htmlhead'][$plugin] = getJavaScriptSourceLink(TEMPLATE_PATH.'lib/jquery/plugin/'.$plugin);
    }
    return true;
}
