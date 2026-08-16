<?php
/**
 * phpwcms
 *
 * @author Oliver Georgi <og@phpwcms.org>
 * @copyright Copyright (c) 2002-2026, Oliver Georgi
 * @license http://opensource.org/licenses/GPL-2.0 GNU GPL-2
 *
 **/

// ----------------------------------------------------------------
// obligate check for phpwcms constants
if (!defined('PHPWCMS_ROOT')) {
    die("You Cannot Access This Script Directly, Have a Nice Day.");
}
// ----------------------------------------------------------------

// Content Type Custom Content Part
$content['custom_template'] = $row['acontent_template'] ?? '';
$content['custom_html']     = $row['acontent_html'] ?? '';

$decoded_form = json_decode($row['acontent_form'] ?? '', true);
$content['custom_form'] = is_array($decoded_form) ? $decoded_form : @unserialize($row['acontent_form'] ?? '', ['allowed_classes' => false]);
if (!is_array($content['custom_form'])) {
    $content['custom_form'] = array();
}
if (!isset($content['custom_form']['custom_elements']) || !is_array($content['custom_form']['custom_elements'])) {
    $content['custom_form']['custom_elements'] = array();
}
